<?php

class Fre_Message extends AE_Comments {
	public static $instance;

	/**
	 * return class $instance
	 */
	public static function get_instance() {
		if ( self::$instance == null ) {

			self::$instance = new Fre_Message();
		}

		return self::$instance;
	}

	public function __construct() {
		$this->comment_type = 'message';
		$this->meta         = array();

		$this->post_arr   = array();
		$this->author_arr = array();

		$this->duplicate  = true;
		$this->limit_time = '';
	}

	function convert( $comment, $thumb = 'thumbnail', $merge_post = false, $merge_author = false ) {
		global $user_ID;
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

		$comment->comment_content = esc_attr( $comment->comment_content );

		// comment link
		$comment->comment_link = get_permalink( $comment->comment_post_ID );

		$comment->ID     = $comment->comment_ID;
		$comment->id     = $comment->comment_ID;
		$comment->avatar = get_avatar( $comment->user_id, '33' );

		unset( $comment->comment_author_email );

//        $comment->message_time = sprintf(__('on %s', ET_DOMAIN) , get_comment_date($date_format, $comment)) . '&nbsp;' . sprintf(__('at %s', ET_DOMAIN) , get_comment_date($time_format, $comment));
		$comment->message_time  = sprintf( _x( '%s ago', '%s = human-readable time difference', ET_DOMAIN ), human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) ) );
		$file_arr               = get_comment_meta( $comment->comment_ID, 'fre_comment_file', true );
		$file_attach_arr        = get_comment_meta( $comment->comment_ID, 'fre_comment_file_attach', true );
		$comment->file_list     = '';
		$comment->template_file = '';
		$comment->remove_file   = get_comment_meta( $comment->comment_ID, 'remove_file', true );
		$comment->isFile        = ( ! is_array( $file_arr ) && $file_arr === '0' ) ? 'isFile' : '';
		$comment->isAttach      = ( ! is_array( $file_attach_arr ) && $file_attach_arr === '0' ) ? 'isAttach' : '';
		if ( ! empty( $file_arr ) && is_array( $file_arr ) ) {
			$attachment      = get_posts( array( 'post_type' => 'attachment', 'post__in' => $file_arr ) );
			$current_user_id = get_current_user_id();

			// show file trong report
			ob_start();
			echo '<ul class="list-file-attack">';
			foreach ( $attachment as $key => $file ) {
				if ( $file->post_author == $current_user_id ) {
					$html_removeAtt = '<a href="#" data-id="' . $file->ID . '"><i class="fa fa-times removeAtt" data-id="' . $file->ID . '"></i></a>';
				} else {
					$html_removeAtt = '';
				}
				echo '<li><a target="_blank" rel="noopener noreferrer" href="' . $file->guid . '" class="attack-file"><i class="fa fa-paperclip"></i> ' . $file->post_title . '</a> ' . $html_removeAtt . '</li>';
			}
			echo '</ul>';
			$message_file       = ob_get_clean();
			$comment->file_list = $message_file;

			// Show file in workspace
			ob_start();
			foreach ( $attachment as $key => $value ) {
				$post = get_post( $comment->comment_post_ID );
				if ( $post->post_status == 'close' && $value->post_author == $user_ID && ! $value->post_parent ) {
					$html_removeAtt = '<a href="#" data-post-id="' . $value->ID . '" data-project-id="' . $post->ID . '" data-file-name="' . $value->post_title . '" class="removeAtt"><i class="fa fa-times" aria-hidden="true" data-post-id="' . $value->ID . '" data-project-id="' . $post->ID . '" data-file-name="' . $value->post_title . '"></i></a>';
				} else {
					$html_removeAtt = '';
				}
				echo '<li class="attachment-' . $value->ID . '">
                        <span class="file-attack-name">
                            <a href="' . $value->guid . '" target="_Blank">' . $value->post_title . '</a>
                        </span>
                        <span class="file-attack-time">' . get_the_date( '', $value->ID ) . '</span>
                        ' . $html_removeAtt . '
                    </li>';
			}
			$comment->file_name     = get_the_title( $value->ID );
			$comment->file_size     = size_format( filesize( get_attached_file( $value->ID ) ) );
			$file_type              = wp_check_filetype( get_attached_file( $value->ID ) );
			$comment->file_type     = $file_type['ext'];
			$template_file          = ob_get_clean();
			$comment->template_file = $template_file;
			$comment->isAttach      = 'isAttach';
			$comment->attachId      = $value->ID;
		}

		if ( ! empty( $file_attach_arr ) && is_array( $file_attach_arr ) ) {
			$attachment      = get_posts( array( 'post_type' => 'attachment', 'post__in' => $file_attach_arr ) );
			$current_user_id = get_current_user_id();

			// show file trong report
			ob_start();
			echo '<ul class="list-file-attack">';
			foreach ( $attachment as $key => $file ) {
				if ( $file->post_author == $current_user_id ) {
					$html_removeAtt = '<a href="#" data-id="' . $file->ID . '"><i class="fa fa-times removeAtt" data-id="' . $file->ID . '"></i></a>';
				} else {
					$html_removeAtt = '';
				}
				echo '<li><a target="_blank" rel="noopener noreferrer" href="' . $file->guid . '" class="attack-file"><i class="fa fa-paperclip"></i> ' . $file->post_title . '</a> ' . $html_removeAtt . '</li>';
			}
			echo '</ul>';
			$message_file       = ob_get_clean();
			$comment->file_list = $message_file;

			// Show file in workspace
			ob_start();
			foreach ( $attachment as $key => $value ) {
				$icon  = '<i class="fa fa-file-text-o"></i>';
				$types = wp_check_filetype( get_attached_file( $value->ID ) );
				if ( $types['ext'] == 'png' || $types['ext'] == 'jpg' || $types['ext'] == 'jpeg' || $types['ext'] == 'gif' ) {
					$icon = '<i class="fa fa-file-image-o"></i>';
				}
				echo '<li class="partner-message" id="comment-' . $comment->comment_ID . '">
						<span class="message-avatar">' . get_avatar( $comment->user_id, '33' ) . '</span>
						<div class="message-item message-item-file">
                        	<p><a href="' . $value->guid . '" download="">' . $icon . '<span>' . $value->post_title . '</span><span>' . size_format( filesize( get_attached_file( $value->ID ) ) ) . '</span></a></p>
                    	</div>
                    </li>';
			}
			$comment->file_name     = get_the_title( $value->ID );
			$comment->file_size     = size_format( filesize( get_attached_file( $value->ID ) ) );
			$file_type              = wp_check_filetype( get_attached_file( $value->ID ) );
			$comment->file_type     = $file_type['ext'];
			$template_file          = ob_get_clean();
			$comment->template_file = $template_file;
			$comment->isAttach      = 'isAttach';
			$comment->attachId      = $value->ID;
		}
		$comment              = apply_filters( 'ae_convert_message', $comment );
		$comment->str_message = sprintf( __( '<span class="author-name">%s</span> marked <span class="">"%s"</span> as <span class="status">%s</span><span class="changelog-time" >%s</span>', ET_DOMAIN ), $comment->author_name, $comment->milestone_title, $comment->action, $comment->message_time );

		return $comment;
	}
}

class Fre_MessageAction extends AE_Base {
	public static $instance;

	/**
	 * return class $instance
	 */
	public static function get_instance() {
		if ( self::$instance == null ) {

			self::$instance = new Fre_MessageAction();
		}

		return self::$instance;
	}

	function __construct() {

		// send message
		$this->add_ajax( 'ae-sync-message', 'sendMessage' );

		// get older message
		$this->add_ajax( 'ae-fetch-messages', 'fetchMessage' );

		$this->add_action( 'template_redirect', 'preventAccessWorkspace' );

		$this->add_action( 'before_sidebar_single_project', 'addWorkSpaceLink' );
		$this->add_filter( 'heartbeat_settings', 'fre_change_heartbeat_rate' );
		$this->add_filter( 'heartbeat_received', 'fre_send_data_to_heartbeat', 10, 2 );

		// init message object
		$this->comment = Fre_Message::get_instance();
		//delete attack file
		$this->add_ajax( 'free_remove_attack_file', 'removeAttack' );
		$this->add_ajax( 'free_trash_comment', 'trash_comment' );
	}

	/**
	 * set comment status is trash
	 * $request ajax from client with comment_ID
	 */
	function trash_comment() {
		global $user_ID;
		if ( ! $user_ID ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "You have to login.", ET_DOMAIN )
			) );
		}
		$comment_ID = $_REQUEST['comment_ID'];
		$comment    = wp_set_comment_status( $comment_ID, 'trash' );
		if ( ! is_wp_error( $comment ) ) {
			wp_send_json( array(
				'success' => true
			) );
		} else {
			wp_send_json( array(
				'success' => false,
				'msg'     => $comment->get_error_message()
			) );
		}
	}

	/**
	 * ajax callback sync message
	 * $request ajax from client with comment_post_ID, comment_content
	 */
	function sendMessage() {
		global $user_ID;

		// check projec id is associate with current user
		$args = array();
		/**
		 * validate data
		 */
		if ( empty( $_REQUEST['comment_post_ID'] ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "Error! Can not specify which project you are working on.", ET_DOMAIN )
			) );
		}

		if ( empty( $_REQUEST['comment_content'] ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "You cannot send an empty message.", ET_DOMAIN )
			) );
		}

		if ( ! $user_ID ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "You have to login.", ET_DOMAIN )
			) );
		}

		$comment_post_ID = $_REQUEST['comment_post_ID'];

		// check project owner
		$project = get_post( $comment_post_ID );

		// check freelancer was accepted on project
		$bid_id = get_post_meta( $comment_post_ID, "accepted", true );
		$bid    = get_post( $bid_id );

		// current user is not project owner, or working on
		if ( $user_ID != $project->post_author && $user_ID != $bid->post_author ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "You are not working on this project.", ET_DOMAIN )
			) );
		}

		/**
		 * set message data
		 */
		$_REQUEST['comment_approved'] = 1;
		$_REQUEST['type']             = 'message';

		$comment = $this->comment->insert( $_REQUEST );

		if ( ! is_wp_error( $comment ) ) {

			// get comment data
			$comment = get_comment( $comment );
			if ( isset( $_REQUEST['fileID'] ) ) {
				$file_arr = array();
				foreach ( (array) $_REQUEST['fileID'] as $key => $file ) {
					$file_arr[] = $file['attach_id'];
				}
				update_comment_meta( $comment->comment_ID, 'fre_comment_file', $file_arr );
			}
			/**
			 * fire an action fre_send_message after send message
			 *
			 * @param object $comment
			 * @param object $project
			 * @param object $bid
			 *
			 * @author Dakachi
			 */
			do_action( 'fre_send_message', $comment, $project, $bid );
			$this->fre_update_project_meta( $project, 1 );
			// send json data to  client
			wp_send_json( array(
				'success' => true,
				'data'    => $this->comment->convert( $comment )
			) );
		} else {
			wp_send_json( array(
				'success' => false,
				'msg'     => $comment->get_error_message()
			) );
		}
	}

	/**
	 * ajax callback get message collection
	 * @author Dakachi
	 */
	function fetchMessage() {
		global $user_ID;
		$review_object = $this->comment;

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

	/**
	 * prevent user access workspace
	 * @since 1.2
	 * @author Dakachi
	 */
	function preventAccessWorkspace() {
		if ( isset( $_REQUEST['workspace'] ) && $_REQUEST['workspace'] ) {
			if ( is_singular( PROJECT ) ) {
				global $post, $user_ID;
				// check project owner
				$project = $post;

				// check freelancer was accepted on project
				$bid_id = get_post_meta( $project->ID, "accepted", true );
				$bid    = get_post( $bid_id );

				// current user is not project owner, or working on
				if ( ! $bid_id || ( $user_ID != $project->post_author && $user_ID != $bid->post_author ) || ( current_user_can( 'manage_options' ) && $user_ID != $project->post_author ) || ! is_user_logged_in() ) {
					wp_redirect( get_permalink( $post->ID ) );
					exit;
				}

			}
		}
		if ( is_singular( PROJECT ) ) {
			global $post, $user_ID;
			// check project owner
			$project = $post;
			if ( current_user_can( 'manage_options' ) && $user_ID != $project->post_author ) {
				return;
			}
			// check freelancer was accepted on project
			$bid_id = get_post_meta( $project->ID, "accepted", true );
			$bid    = get_post( $bid_id );

			// current user is not project owner, or working on
			if ( in_array( $project->post_status, array(
					'disputing',
					'disputed'
				) ) && ( ( $user_ID != $project->post_author && $user_ID != $bid->post_author ) || ! is_user_logged_in() ) ) {
				wp_redirect( 404 );
				exit;
			}
		}
	}

	function addWorkSpaceLink( $project ) {
		$permission   = fre_access_workspace( $project );
		$project_link = get_permalink( $project->ID );
		if ( $permission ) {
			echo '<h3 class="title-content">' . __( "Workspace:", ET_DOMAIN ) . '</h3>';
			echo '<p>' . __( 'Both employer and freelancer can open workspace to collaborate on this project.', ET_DOMAIN ) . '</p>';
			echo '<a class="fre-normal-btn"  style="font-weight:600;" href=' . add_query_arg( array( 'workspace' => 1 ), $project_link ) . '>' . __( "Open Workspace", ET_DOMAIN ) . '</a>';
		}
	}

	/**
	 * change heartbeat rate
	 *
	 * @param array $settings
	 *
	 * @return array $settings
	 * @since 1.6.5
	 * @package void
	 * @category void
	 * @author Tambh
	 */
	public function fre_change_heartbeat_rate( $settings ) {
		$settings['interval'] = 10;

		return $settings;
	}

	/**
	 * send data to heart beat
	 *
	 * @param array $response
	 * @param array $data
	 *
	 * @return void
	 * @since 1.6.5
	 * @package freelanceengine
	 * @category void
	 * @author Tambh
	 */
	public function fre_send_data_to_heartbeat( $response, $data ) {
		if ( isset( $data['new_message'] ) ) {
			global $ae_post_factory;
			$post = get_post( $data['new_message'] );
			if ( $post ) {
				$project_obj             = $ae_post_factory->get( PROJECT );
				$project                 = $project_obj->convert( $post );
				$val                     = $this->fre_get_project_meta( $project );
				$response['new_message'] = $val;
			}
		}

		return $response;
	}

	/**
	 * update project meta when there is a new message send
	 *
	 * @param integer $val
	 * @param object $project
	 *
	 * @return void
	 * @since 1.6.5
	 * @package freelanceengine
	 * @category void
	 * @author Tambh
	 */
	public function fre_update_project_meta( $project, $val = 1 ) {
		global $user_ID;
		if ( $user_ID == $project->post_author ) {
			update_post_meta( $project->ID, 'fre_freelancer_new_msg', $val );
		} else {
			update_post_meta( $project->ID, 'fre_employer_new_msg', $val );
		}
	}

	/**
	 * reset project meta when there is a new message send
	 *
	 * @param object $project
	 *
	 * @return void
	 * @since 1.6.5
	 * @package freelanceengine
	 * @category void
	 * @author Tambh
	 */
	public function fre_reset_project_meta( $project ) {
		global $user_ID;
		if ( $user_ID == $project->post_author ) {
			update_post_meta( $project->ID, 'fre_employer_new_msg', 0 );
		} else {
			update_post_meta( $project->ID, 'fre_freelancer_new_msg', 0 );
		}
	}

	/**
	 * get project meta when there is a new message send
	 *
	 * @param object $project
	 *
	 * @return interger 1 if have fetch message and 0 if don't need fetch
	 * @since 1.6.5
	 * @package freelanceengine
	 * @category void
	 * @author Tambh
	 */
	public function fre_get_project_meta( $project ) {
		global $user_ID;
		$val = 0;
		if ( $user_ID == $project->post_author ) {
			$val = get_post_meta( $project->ID, 'fre_employer_new_msg', true );
		} else {
			$val = get_post_meta( $project->ID, 'fre_freelancer_new_msg', true );
		}

		return (int) $val;
	}

	/**
	 * Remove attack file in message
	 *
	 * @param post_id
	 *
	 * @return bool
	 * @since 1.7.5
	 * @author ThanhTu
	 */
	public function removeAttack() {
		$post_id    = $_POST['post_id'];
		$file_name  = $_POST['file_name'];
		$project_id = $_POST['project_id'];

		$lock_status = get_post_meta( $project_id, 'lock_file', true );

		if ( $lock_status == 'unlock' ) {
			$current_user    = wp_get_current_user();
			$current_user_id = $current_user->id;
			$post            = get_post( $post_id );
			$post_author     = $post->post_author;
			if ( $current_user_id == $post_author ) {
				$comment_id = get_post_meta( $post_id, 'comment_file_id', true );
				update_comment_meta( $comment_id, 'fre_comment_file', '0' );
				update_comment_meta( $comment_id, 'fre_comment_file_attach', '0' );
				$result     = wp_delete_post( $post_id );
				$comment_id = wp_insert_comment( array(
					'comment_post_ID'      => $project_id,
					'comment_author'       => $current_user->data->user_login,
					'comment_author_email' => $current_user->data->user_email,
					'comment_content'      => sprintf( __( "%s removed %s file ", ET_DOMAIN ), $current_user->data->display_name, $file_name ),
					'comment_type'         => 'message',
					'user_id'              => $current_user->data->ID,
					'comment_approved'     => 1
				) );
				update_comment_meta( $comment_id, 'fre_comment_file', '0' );
				update_comment_meta( $comment_id, 'remove_file', $post_id );
			}
			if ( $result ) {
				$this->fre_update_project_meta( get_post( $project_id ) );
				echo $result->ID;
			} else {
				echo "0";
			}
		} else {
			echo "locked";
		}
		wp_die();
	}

}

new Fre_MessageAction();
/**
 * function check user can access project workspace or not
 *
 * @param object $project The project user want to access workspace
 *
 * @since 1.2
 * @author Dakachi
 */
function fre_access_workspace( $project ) {
	global $user_ID;
	// check freelancer was accepted on project
	$bid_id = get_post_meta( $project->ID, "accepted", true );
	$bid    = get_post( $bid_id );

	// current user is not project owner, or working on
	if ( in_array( $project->post_status, array( "close", "complete", "disputing", "disputed" ) ) ) {
		if ( current_user_can( 'manage_options' ) && $user_ID != $project->post_author ) {
			return false;
		} else if ( $bid_id && ( $user_ID == $project->post_author || $user_ID == $bid->post_author ) ) {
			return true;
		}
	}

	return false;
}