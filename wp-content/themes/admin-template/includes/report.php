<?php

/**
 * this file contain all function about report project, profile
 * dispute report, close project, quit project report.
 * @author Dakachi
 * @since 1.3
 */
class Fre_Report extends AE_Comments {
	public function __construct( $comment_type, $meta_key = array(), $post_type = '' ) {
		$this->comment_type = 'fre_report';
		$this->meta         = array();

		$this->post_arr   = array();
		$this->author_arr = array();

		$this->duplicate  = true;
		$this->limit_time = false;
	}

	function convert( $comment, $thumb = 'thumbnail', $merge_post = false, $merge_author = false ) {
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		/**
		 * add comment meta
		 */
		if ( ! empty( $this->meta ) ) {
			foreach ( $this->meta as $key => $value ) {
				$comment->$value = get_comment_meta( $comment->comment_ID, $value, true );
			}
		}

		$comment->comment_content = wpautop( esc_attr( $comment->comment_content ) );

		// comment link
		$comment->comment_link = get_comment_link( $comment );
		$comment->ID           = $comment->comment_ID;
		$comment->id           = $comment->comment_ID;
		$comment->avatar       = get_avatar( $comment->user_id, '33' );

		$file_arr           = get_comment_meta( $comment->comment_ID, 'fre_comment_file', true );
		$comment->file_list = '';
		if ( ! empty( $file_arr ) ) {
			$attachment = get_posts( array( 'post_type' => 'attachment', 'post__in' => $file_arr ,'posts_per_page' => -1) );
			ob_start();
			foreach ( $attachment as $key => $file ) {
				echo '<p class="message-file"><a target="_blank" rel="noopener noreferrer" href="' . $file->guid . '" ><i class="fa fa-paperclip" aria-hidden="true"></i> ' . $file->post_title . '</a></p>';
			}
			$message_file       = ob_get_clean();
			$comment->file_list = $message_file;
		}

		$display_name          = get_the_author_meta( 'display_name', $comment->user_id );
		$comment->display_name = $display_name;

		unset( $comment->comment_author_email );

		$comment->message_time = sprintf( __( 'on %s', ET_DOMAIN ), get_comment_date( $date_format, $comment ) ) . '&nbsp;' . sprintf( __( 'at %s', ET_DOMAIN ), get_comment_date( $time_format, $comment ) );

		if ( user_can($comment->user_id,'manage_options')) {
			$class = 'admin-message';
		} else {
			$class = '';
			if ( $comment->user_id == get_current_user_id() ) {
				$class = 'partner-message';
			}
		}
		$comment->class = $class;

		return $comment;
	}
}

/**
 * class init all action releated to report
 * @since 1.3
 * @author Dakachi
 */
class Fre_ReportForm extends AE_Base {
	function __construct() {
		$this->report = new Fre_Report( 'fre_report' );

		// employer close project and send report to admin
		$this->add_ajax( 'fre-close-project', 'close_project' );

		// freelancer quit project and send report to admin
		$this->add_ajax( 'fre-quit-project', 'quit_project' );

		// when a project  is disputing, freelancer and employer can send report
		$this->add_ajax( 'fre_report_dispute_project', 'report_dispute' );

		// use add report to project
		$this->add_ajax( 'ae-sync-report', 'report' );

		$this->add_ajax( 'ae-fetch-reports', 'fetchReport' );

		// prevent normal user access workspace
		// $this->add_action('template_redirect', 'preventAccessReport');

		$this->add_ajax( 'report-remove-file', 'removeFile' );
	}

	/**
	 * ajax callback get report collection
	 * @author Dakachi
	 */
	function fetchReport() {
		global $user_ID;
		$review_object = $this->report;

		// get review object

		$page  = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 2;
		$query = $_REQUEST['query'];

		// If milestone was deactived, will get all comment are not changelog
		if ( ! defined( 'MILESTONE_DIR_URL' ) ) {
			$query['meta_query'] = array(
				array(
					'key'     => 'changelog',
					'value'   => '',
					'compare' => 'NOT EXISTS'
				)
			);
		}

		$map = array(
			'status'  => 'approve',
			'type'    => 'message',
			'number'  => '4',
			'total'   => '10',
			'order'   => 'DESC',
			'orderby' => 'date'
		);

		$query['page'] = $page;
		$all_cmts      = get_comments( $query );

		/**
		 * get page 1 reviews
		 */
		if ( ! isset( $query['total'] ) ) {
			$query['number'] = get_option( 'posts_per_page' );

			$total_messages = count( $all_cmts );
			$comment_pages  = ceil( $total_messages / $query['number'] );
			$query['total'] = $comment_pages;
		}
		//add_filter( 'comments_clauses' , array($this, 'groupby') );
		$data = $review_object->fetch( $query );
		if ( ! empty( $data ) ) {
			// $data['success'] = true;s
			if ( isset( $query['use_heartbeat'] ) && $query['use_heartbeat'] == 1 ) {
				$project = fre_get_project_infor( $data['data']['0']->comment_post_ID );
				if ( $project ) {
					$this->fre_reset_project_meta( $project );
				}
				wp_send_json( array(
					'success' => true,
					'data'    => $data['data']['0']
				) );
			}
			$data['query']['page'] ++;
			$data_result = $data['data'];
			wp_send_json( array(
				'success'  => true,
				'data'     => $data_result,
				'paginate' => $data['paginate'],
				'query'    => $data['query']
			) );
			// wp_send_json($data);
		} else {
			wp_send_json( array(
				'success' => false,
				'data'    => $data
			) );
		}
	}

	// employer force to close project and end working
	function close_project() {
		global $user_ID;
		$request = $_REQUEST;

		$report = $this->insert_report( $request );

		// update project
		if ( is_wp_error( $report ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => $report->get_error_message()
			) );
		}

		$project_id = $request['comment_post_ID'];

		// change post status to disputing
		wp_update_post( array(
			'ID'          => $request['comment_post_ID'],
			'post_status' => 'disputing'
		) );

		update_post_meta( $project_id, 'dispute_by', $user_ID );

		do_action( 'fre_report_close_project', $project_id, $request );

		// send close project report link
		$mailing = Fre_Mailing::get_instance();
		$mailing->close_project( $project_id, $request['comment_content'] );

		// update meta key
		// update_post_meta( $request['comment_post_ID'], 'dispute_by', $meta_value, $prev_value = '' )

		wp_send_json( array(
			'success' => true,
			'url' => get_permalink($project_id).'?dispute=1'
		) );
	}

	// freelancer quit a project function
	function quit_project() {
		global $user_ID;
		$request = $_REQUEST;

		$report = $this->insert_report( $request );

		// update project
		if ( is_wp_error( $report ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => $report->get_error_message()
			) );
		}
		$project_id = $request['comment_post_ID'];

		$old_status = get_post_field( 'post_status', $project_id );
		if ( $old_status == 'complete' ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( 'This project is complete.', ET_DOMAIN )
			) );
		}

		// change post status to disputing
		wp_update_post( array(
			'ID'          => $request['comment_post_ID'],
			'post_status' => 'disputing'
		) );

		update_post_meta( $project_id, 'dispute_by', $user_ID );

		do_action( 'fre_report_quit_project', $project_id, $request );

		$mailing = Fre_Mailing::get_instance();
		$mailing->quit_project( $project_id, $request['comment_content'] );
		// update meta key
		// update_post_meta( $request['comment_post_ID'], 'dispute_by', $meta_value, $prev_value = '' )

		wp_send_json( array(
			'success' => true,
			'url' => get_permalink($project_id).'?dispute=1'
		) );
	}

	// employer and freelancer report a disputing project
	function report_dispute() {
	}

	/**
	 * ajax callback user send report to a project
	 * request param contain $comment_content, $comment_post_ID
	 * @since 1.3
	 * @author Dakachi
	 */
	function report() {
		global $user_ID;
		$request = $_REQUEST;

		$report = $this->insert_report( $request );

		// update project
		if ( is_wp_error( $report ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => $report->get_error_message()
			) );
		}
		$project_id = $request['comment_post_ID'];
		$report     = get_comment( $report );

		if ( isset( $_REQUEST['fileID'] ) ) {
			$file_arr = array();
			foreach ( (array) $_REQUEST['fileID'] as $key => $file ) {
				$file_arr[] = $file['attach_id'];
			}
			update_comment_meta( $report->comment_ID, 'fre_comment_file', $file_arr );
		}

		do_action( 'fre_report_dispute_project', $project_id, $report );

		$mailing = Fre_Mailing::get_instance();
		$mailing->new_report( $project_id, $report );
		wp_send_json( array(
			'success' => true,
			'data'    => $this->report->convert( $report )
		) );
	}

	/**
	 * group all report to afunction and call insert function
	 *
	 * @param Array $args
	 *
	 * @since 1.3
	 * @author Dakachi
	 */
	function insert_report( $args ) {
		global $user_ID;
		$project = get_post( $args['comment_post_ID'] );
		if ( ! $project || is_wp_error( $project ) ) {
			wp_send_json_error( array(
				'msg' => __( "Invalid project.", ET_DOMAIN )
			) );
		}

		$bid_accepted = get_post_meta( $args['comment_post_ID'], 'accepted', true );

		// dont have accepted bid
		if ( ! $bid_accepted ) {
			return new WP_Error( 'bid_not_found', __( "This project have not accepted any bid.", ET_DOMAIN ) );
		}

		// current user dont own accepted bid
		$bid = get_post( $bid_accepted );
		if ( ! current_user_can( 'manage_options' ) && ! ( $bid->post_author == $user_ID || $project->post_author == $user_ID ) ) {
			return new WP_Error( 'permission_denied', __( "You do not have permission to report dispute this project.", ET_DOMAIN ) );
		}

		$request['comment_approved'] = 1;
		$report                      = $this->report->insert( $args );

		//update bid post
		$bids_post = get_children(
			array(
				'post_parent' => $args['comment_post_ID'],
				'post_type'   => BID,
				'numberposts' => - 1,
				'post_status' => 'any'
			)
		);

		if ( ! empty( $bids_post ) ) {
			foreach ( $bids_post as $bid ) {
				if ( $bid->ID == $bid_accepted ) {
					wp_update_post( array(
						'ID'          => $bid_accepted,
						'post_status' => 'disputing'
					) );
				} else {
					wp_update_post(
						array(
							'ID'          => $bid->ID,
							'post_status' => 'hide'
						)
					);
				}
			}
		}

		return $report;
	}

	/**
	 * prevent normal user access report content
	 * @since 1.3
	 * @author Dakachi
	 */
	public static function AccessReport() {

		global $post, $user_ID;
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		// check project owner
		$project = $post;

		// check freelancer was accepted on project
		$bid_id = get_post_meta( $project->ID, "accepted", true );
		$bid    = get_post( $bid_id );

		// current user is not project owner, or working on
		if ( ! $bid_id || $post->post_status != 'disputing' || ( $user_ID != $project->post_author && $user_ID != $bid->post_author ) ) {
			return false;
		}

		return true;
	}

	public function removeFile(){

	}
}

new Fre_ReportForm();