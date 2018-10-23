<?php

/**
 * This file contain functions related to user notification system
 * @since 1.1.1
 * @author Dakachi
 */

/**
 * register post notification to store user notification
 * @since 1.2
 * @author Dakachi
 */
function fre_register_notification() {
	$labels = array(
		'name'               => __( 'Notifications', ET_DOMAIN ),
		'singular_name'      => __( 'Notification', ET_DOMAIN ),
		'add_new'            => _x( 'Add New notification', ET_DOMAIN, ET_DOMAIN ),
		'add_new_item'       => __( 'Add New notification', ET_DOMAIN ),
		'edit_item'          => __( 'Edit notification', ET_DOMAIN ),
		'new_item'           => __( 'New notification', ET_DOMAIN ),
		'view_item'          => __( 'View notification', ET_DOMAIN ),
		'search_items'       => __( 'Search notifications', ET_DOMAIN ),
		'not_found'          => __( 'No notifications found', ET_DOMAIN ),
		'not_found_in_trash' => __( 'No notifications found in Trash', ET_DOMAIN ),
		'parent_item_colon'  => __( 'Parent notification:', ET_DOMAIN ),
		'menu_name'          => __( 'Notifications', ET_DOMAIN ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => true,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => 'notifications',
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => array(
			'slug' => 'notification'
		),
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			'editor',
			'author',
			'excerpt'
		)
	);
	register_post_type( 'notify', $args );

	// register notify object
	global $ae_post_factory;
	$ae_post_factory->set( 'notify', new AE_Posts( 'notify', array(), array(
		'seen'
	) ) );
}

add_action( 'init', 'fre_register_notification' );

/**
 * class Fre_Notification
 * notify employer and freelancer when have any change on bid and project
 * @since 1.2
 * @author Dakachi
 */
class Fre_Notification extends AE_PostAction {
	public static $instance;

	/**
	 * getInstance method
	 *
	 */
	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	function __construct() {
		$this->post_type = 'notify';

		// init notify object
		$this->notify = new AE_Posts( 'notify' );

		// catch action insert new bid to notify employer
		$this->add_action( 'ae_insert_bid', 'newBid', 10, 2 );
		// catch action insert new bid to notify employer
		//$this->add_action('ae_insert_project', 'newProject', 10, 2);
		// catch action insert new bid to notify employer
		//$this->add_action('ae_update_project', 'updateProject', 10, 2);

		// catch action a bid accepted and notify freelancer
		$this->add_action( 'fre_accept_bid', 'bidAccepted' );

		// add action when employer complete project
		$this->add_action( 'fre_complete_project', 'completeProject', 10, 2 );

		// add action review project owner
		$this->add_action( 'fre_freelancer_review_employer', 'reviewProjectOwner', 10, 2 );
		// add a notification when have new message
		$this->add_action( 'fre_send_message', 'newMessage', 10, 3 );

		$this->add_action( 'fre_new_invite', 'newInvite', 10, 3 );

		$this->add_action( 'ae_update_user', 'clearNotify', 10, 2 );

		$this->add_ajax( 'ae-fetch-notify', 'fetch_post' );
		$this->add_action( 'ae_convert_notify', 'convert_notify' );

		$this->add_action( 'wp_footer', 'render_template_js' );

		$this->add_action( 'template_redirect', 'mark_user_read_message' );

		$this->add_ajax( 'ae-notify-sync', 'notify_sync' );

		$this->add_action( 'transition_comment_status', 'fre_approve_comment_callback', 10, 3 );

		$this->add_action( 'wp_insert_comment', 'fre_auto_approve_comment_callback', 11, 2 );

		$this->add_action( 'fre_report_close_project', 'close_project', 10, 3 );

		$this->add_action( 'fre_report_quit_project', 'quit_project', 10, 3 );

		$this->add_action( 'ae_reject_post', 'reject_project', 10, 3 );
		$this->add_action( 'ae_lock_upload_file', 'act_lock_upload_file', 10, 3 );
		$this->add_action( 'ae_unlock_upload_file', 'act_unlock_upload_file', 10, 3 );

		$this->add_action( 'fre_publish_post', 'publish_project', 10, 3 );
		$this->add_action( 'fre_archive_post', 'archive_project', 10, 3 );
		$this->add_action( 'fre_delete_post', 'delete_project', 10, 3 );
		$this->add_action( 'ae_after_update_order', 'update_order', 10, 3 );

		$this->add_action( 'fre_report_dispute_project', 'admin_report_dispute_project_freelancer', 10, 3 );
		$this->add_action( 'fre_report_dispute_project', 'admin_report_dispute_project_employer', 10, 3 );

		$this->add_action( 'fre_resolve_project_notification', 'resolve_project_employer', 10, 3 );
		$this->add_action( 'fre_resolve_project_notification', 'resolve_project_freelancer', 10, 3 );
	}

	/**
	 * Notify employer when admin resolve project
	 *
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function resolve_project_employer( $project_id ) {
		global $user_ID;
		$project      = get_post( $project_id );
		$content      = 'type=resolve_project&project=' . $project_id . '&admin=' . $user_ID;
		$title        = __( 'Resolve the disputed project' );
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $project->post_author,
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify employer when admin resolve project
	 *
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function resolve_project_freelancer( $project_id ) {
		global $user_ID;
		$project      = get_post( $project_id );
		$bid_id       = get_post_meta( $project_id, 'accepted', true );
		$bid_author   = get_post_field( 'post_author', $bid_id );
		$content      = 'type=resolve_project&project=' . $project_id . '&admin=' . $user_ID;
		$title        = __( 'Resolve the disputed project' );
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $bid_author,
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify employer, freelance when admin report in dispute project
	 *
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function admin_report_dispute_project_freelancer( $project_id, $report ) {
		global $user_ID;
		$project = get_post( $project_id );
		// Freelancer
		$bid_id     = get_post_meta( $project_id, 'accepted', true );
		$bid_author = get_post_field( 'post_author', $bid_id );
		if ( $bid_author == $user_ID ) {
			return;
		}
		$content      = 'type=report_dispute_project&project=' . $project_id . '&admin=' . $user_ID;
		$title        = __( 'Admin send Freelancer a report' );
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $bid_author,
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	function admin_report_dispute_project_employer( $project_id, $report ) {
		global $user_ID;
		$project = get_post( $project_id );
		if ( $project->post_author == $user_ID ) {
			return;
		}
		// Employer
		$content      = 'type=report_dispute_project&project=' . $project_id . '&admin=' . $user_ID;
		$title        = __( 'Admin send Employer a report' );
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $project->post_author,
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify employer when admin approve/reject package
	 *
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function update_order( $order_id ) {
		global $user_ID;
		$order = get_post( $order_id );
		// check status
		if ( ! in_array( $order->post_status, array( 'publish', 'draft' ) ) ) {
			return;
		}

		switch ( $order->post_status ) {
			case 'publish':
				$content = 'type=approve_order&order=' . $order_id . '&admin=' . $user_ID;
				$title   = sprintf( __( 'Approve payment', ET_DOMAIN ), $order->post_title );
				break;
			case 'draft':
				$content = 'type=cancel_order&order=' . $order_id . '&admin=' . $user_ID;
				$title   = sprintf( __( 'Cancel payment', ET_DOMAIN ), $order->post_title );
				break;
			default:
				$content = '';
				$title   = '';
				break;
		}
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $order->post_author, // notify to employer
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_parent'  => $order_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify Employer when admin archive your project
	 *
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function archive_project( $args ) {
		global $user_ID;
		$project_id = $args['ID'];
		$post       = get_post( $project_id );
		if ( $user_ID == $post->post_author ) {
			return;
		}
		$content      = 'type=archive_project&project=' . $project_id . '&admin=' . $user_ID;
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $args['post_author'], // notify to Freelance
			'post_title'   => __( "Archive the draft project", ET_DOMAIN ),
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify Employer when admin delete your project
	 *
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function delete_project( $args ) {
		global $user_ID;
		$project_id = $args['ID'];
		$post       = get_post( $project_id );
		if ( $user_ID == $post->post_author ) {
			return;
		}
		$content      = 'type=delete_project&project=' . $project_id . '&admin=' . $user_ID;
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $args['post_author'], // notify to Freelance
			'post_title'   => __( "Archive the draft project", ET_DOMAIN ),
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify Employer when admin reject your project
	 *
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function reject_project( $args ) {
		global $user_ID;
		$project_id = $args['ID'];
		$post       = get_post( $project_id );
		if ( $user_ID == $post->post_author ) {
			return;
		}
		$content      = 'type=reject_project&project=' . $project_id . '&admin=' . $user_ID;
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $args['post_author'], // notify to Freelance
			'post_title'   => sprintf( __( "Reject the pending project %s", ET_DOMAIN ), get_the_title( $project_id ) ),
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}


	function act_lock_upload_file( $project_id ) {
		global $user_ID, $ae_post_factory;
		$post          = get_post( $project_id );
		$projects_data = $ae_post_factory->get( PROJECT );
		$projects      = $projects_data->convert( $post );
		$bids          = get_post( $projects->accepted );
		if ( $user_ID != $post->post_author ) {
			return;
		}
		$content      = 'type=lock_file_project&project=' . $project_id . '&sender=' . $user_ID;
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $bids->post_author, // notify to Freelance
			'post_title'   => sprintf( __( "%s locked the file section in the project %s. Click here for details.", ET_DOMAIN ), get_the_author_meta( 'display_name', $post->post_author ), get_the_title( $project_id ) ),
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}


	function act_unlock_upload_file( $project_id ) {
		global $user_ID, $ae_post_factory;
		$post          = get_post( $project_id );
		$projects_data = $ae_post_factory->get( PROJECT );
		$projects      = $projects_data->convert( $post );
		$bids          = get_post( $projects->accepted );
		if ( $user_ID != $post->post_author ) {
			return;
		}
		$content      = 'type=unlock_file_project&project=' . $project_id . '&sender=' . $user_ID;
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $bids->post_author, // notify to Freelance
			'post_title'   => sprintf( __( "%s unlocked the file section in the project %s. Click here for details.", ET_DOMAIN ), get_the_author_meta( 'display_name', $post->post_author ), get_the_title( $project_id ) ),
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify Employer when admin approve your project
	 *
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function publish_project( $args ) {
		global $user_ID;
		$project_id = $args['ID'];
		$post       = get_post( $project_id );
		if ( $user_ID == $post->post_author ) {
			return;
		}
		$content      = 'type=publish_project&project=' . $project_id . '&admin=' . $user_ID;
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $args['post_author'], // notify to employer
			'post_title'   => sprintf( __( "Approve the pending project %s", ET_DOMAIN ), get_the_title( $project_id ) ),
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify freelance when employer close project
	 *
	 * @param Object $bid object
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function close_project( $project_id ) {
		$bid_id       = get_post_meta( $project_id, 'accepted', true );
		$bid_author   = get_post_field( 'post_author', $bid_id );
		$content      = 'type=close_project&project=' . $project_id;
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $bid_author, // notify to Freelance
			'post_title'   => sprintf( __( "Close project %s", ET_DOMAIN ), get_the_title( $project_id ) ),
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify employer when freelancer quit project
	 *
	 * @param Object $bid object
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author ThanhTu
	 */
	function quit_project( $project_id ) {
		$bid_id        = get_post_meta( $project_id, 'accepted', true );
		$bid_author    = get_post_field( 'post_author', $bid_id );
		$project_owner = get_post_field( 'post_author', $project_id );
		$content       = 'type=quit_project&project=' . $project_id;
		$notification  = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $project_owner, // notify to Freelance
			'post_title'   => sprintf( __( "Quit project %s", ET_DOMAIN ), get_the_title( $project_id ) ),
			'post_status'  => 'publish',
			'post_parent'  => $project_id
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify employer when have new freelancer bid on his project
	 *
	 * @param Object $bid object
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function newBid( $bid, $args ) {
		$project = get_post( $args['post_parent'] );

		$content = 'type=new_bid&project=' . $args['post_parent'] . '&bid=' . $bid;

		// insert notification
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $project->post_author,
			'post_title'   => sprintf( __( "New bid on %s", ET_DOMAIN ), get_the_title( $project->ID ) ),
			'post_status'  => 'publish',
			'post_parent'  => $project->ID
		);
		$notify_id    = $this->insert( $notification );
		update_post_meta( $bid, 'notify_id', $notify_id );

		return;
	}

	/**
	 * Notify admin  when Employer post a new project
	 *
	 * @param Object $project object
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function newProject( $project, $args ) {
		$project   = get_post( $project );
		$content   = 'type=new_project&project=' . $project->ID;
		$blogusers = get_users( 'role=administrator' );
		$bloguser  = $blogusers['0'];
		// insert notification
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $bloguser->ID,
			'post_title'   => sprintf( __( "New project %s is created", ET_DOMAIN ), get_the_title( $project->ID ) ),
			'post_status'  => 'publish',
			'post_parent'  => $project->ID
		);

		return $this->insert( $notification );
	}

	/**
	 * Notify admin  when Employer post a new project
	 *
	 * @param Object $project object
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function updateProject( $project, $args ) {
		if ( isset( $args['renewe'] ) ) {
			$project   = get_post( $project );
			$content   = 'type=renew_project&project=' . $project->ID;
			$blogusers = get_users( 'role=administrator' );
			$bloguser  = $blogusers['0'];
			// insert notification
			$notification = array(
				'post_type'    => $this->post_type,
				'post_content' => $content,
				'post_excerpt' => $content,
				'post_author'  => $bloguser->ID,
				'post_title'   => sprintf( __( "project %s is renewed", ET_DOMAIN ), get_the_title( $project->ID ) ),
				'post_status'  => 'publish',
				'post_parent'  => $project->ID
			);

			return $this->insert( $notification );
		}
	}

	/**
	 * notify freelancer when his bid was accepted by employer
	 *
	 * @param int $bid_id the id of bid
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function bidAccepted( $bid_id ) {
		$bid = get_post( $bid_id );
		if ( ! $bid || is_wp_error( $bid ) ) {
			return;
		}

		$project_id = $bid->post_parent;
		$project    = get_post( $project_id );
		if ( ! $project || is_wp_error( $project ) ) {
			return;
		}

		$content = 'type=bid_accept&project=' . $project_id;

		// insert notification
		$notification = array(
			'post_type'    => $this->post_type,
			'post_parent'  => $project_id,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_status'  => 'publish',
			'post_author'  => $bid->post_author,
			'post_title'   => sprintf( __( "Bid on project %s was accepted", ET_DOMAIN ), get_the_title( $project->ID ) )
		);

		return $this->insert( $notification );
	}

	/**
	 * notify freelancer after employer complete a project
	 *
	 * @param int $project_id
	 * @param Array $args
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function completeProject( $project_id, $args ) {

		$content    = 'score=' . $args['score'] . '&type=complete_project&project=' . $project_id;
		$project    = get_post( $project_id );
		$bid_id     = get_post_meta( $project_id, 'accepted', true );
		$bid_author = get_post_field( 'post_author', $bid_id );

		// insert notification
		$notification = array(
			'post_type'    => $this->post_type,
			'post_parent'  => $project_id,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $bid_author,
			'post_status'  => 'publish',
			'post_title'   => sprintf( __( "Project %s was completed", ET_DOMAIN ), get_the_title( $project->ID ) )
		);

		return $this->insert( $notification );
	}

	/**
	 * freelancer review project owner
	 *
	 * @param int $project_id
	 * @param Array $args request args
	 * #      - score
	 * #      - comment_content
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function reviewProjectOwner( $project_id, $args ) {
		global $user_ID;
		$content = 'score=' . $args['score'] . '&type=review_project&project=' . $project_id;
		$project = get_post( $project_id );

		$project_title = get_the_title( $project->ID );
		$bidder_name   = get_the_author_meta( 'display_name', $user_ID );

		// insert notification
		$notification = array(
			'post_type'    => $this->post_type,
			'post_parent'  => $project_id,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $project->post_author,
			'post_status'  => 'publish',
			'post_title'   => sprintf( __( "%s reviewed project %s", ET_DOMAIN ), $bidder_name, $project_title )
		);

		return $this->insert( $notification );
	}

	/**
	 * notify when a project working on have new message
	 *
	 * @param object $message
	 * @param object $project
	 * @param object $bid
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function newMessage( $message, $project, $bid ) {
		global $user_ID;

		$content      = 'type=new_message&project=' . $project->ID . '&sender=' . $user_ID;
		$notification = array(
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_status'  => 'publish',
			'post_type'    => $this->post_type,
			'post_title'   => sprintf( __( "New message on project %s", ET_DOMAIN ), get_the_title( $project->ID ) ),
			'post_parent'  => $project->ID
		);

		// update notify for freelancer if current user is project owner
		if ( $user_ID == $project->post_author ) {
			$notification['post_author'] = $bid->post_author;
		}

		// update notify to employer if freelancer send message
		if ( $user_ID == $bid->post_author ) {
			$notification['post_author'] = $project->post_author;
		}

		$have_new = get_post_meta( $project->ID, $user_ID . '_new_message', true );

		if ( $have_new ) {
			return;
		}
		update_post_meta( $project->ID, $user_ID . '_new_message', true );

		$mail = Fre_Mailing::get_instance();
		$mail->new_message( $notification['post_author'], $project, $message );

		return $this->insert( $notification );
	}

	/**
	 * notify user when have an invite to new project
	 *
	 * @param int $invited
	 * @param int $send_invite
	 *
	 * @since 1.3
	 * @author Dakachi
	 */
	function newInvite( $invited, $send_invite, $list_project ) {
		global $user_ID;
		$content = 'type=new_invite&send_invite=' . $send_invite;

		$author      = get_the_author_meta( 'display_name', $invited );
		$send_author = get_the_author_meta( 'display_name', $send_invite );
		// insert notification
		$notification = array(
			'post_type'    => $this->post_type,
			'post_content' => $content,
			'post_excerpt' => $content,
			'post_author'  => $invited,
			'post_status'  => 'publish',
			'post_title'   => sprintf( __( "%s have a new invite from %s", ET_DOMAIN ), $author, $send_author )
		);
		$notify       = $this->insert( $notification );
		update_post_meta( $notify, 'project_list', $list_project );

		return $notify;
	}

	/**
	 * clear notify flag, set user dont have any new notification
	 *
	 * @param int $result user id
	 * @param Array $data user submit data
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function clearNotify( $user_id, $data ) {
		global $user_ID;
		if ( $user_ID != $user_id ) {
			return $user_id = $user_ID;
		}
		if ( isset( $data['read_notify'] ) && $data['read_notify'] ) {
			delete_user_meta( $user_id, 'fre_new_notify' );
		}

		return $user_id;
	}

	/**
	 * insert user notification post
	 *
	 * @param Array $notfication Notification post data
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function insert( $notification ) {
		$notify = wp_insert_post( $notification );
		if ( $notify ) {
			$number = (int) get_user_meta( $notification['post_author'], 'fre_new_notify', true );
			$number = $number + 1;
			update_user_meta( $notification['post_author'], 'fre_new_notify', $number );
		}

		return $notify;
	}

	/**
	 * convert notification content
	 *
	 * @param object $notify The notification object
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function convert_notify( $notify ) {
		$notify->content = $this->fre_notify_item( $notify );

		return $notify;
	}

	/**
	 * build notification content
	 *
	 * @param object $notify The notification object
	 *
	 * @since 1.2
	 * @author Dakachi
	 */
	function fre_notify_item( $notify ) {
		// parse post excerpt to get data
		$post_excerpt = str_replace( '&amp;', '&', $notify->post_excerpt );
		parse_str( $post_excerpt );

		if ( ! isset( $type ) || ! $type ) {
			return;
		}

		if ( ! in_array( $type, array(
			'new_invite',
			'approve_order',
			'cancel_order',
			'approve_withdraw',
			'cancel_withdraw'
		) )
		) {
			if ( ! isset( $project ) || ! $project ) {
				return;
			}
			$project_post = get_post( $project );

			if ( ! isset( $project ) || ! $project ) {
				return;
			}
			// check project exists or deleted
			if ( ! $project_post || is_wp_error( $project_post ) ) {
				return;
			}

			$project_link = '';
			if ( isset( $project ) ) {
				$project_link = '<a class="project-link" href="' . get_permalink( $project ) . '" >' . get_the_title( $project ) . '</a>';
			}
			$postdata[] = $notify;
		}
		$content = '';
		$content = apply_filters( 'fre_notify_item', $content, $notify );
		switch ( $type ) {
			case 'resolve_project':
				// text : [Admin] Resolved the disputed project [dispute_project_name]
				$message = sprintf( __( '%s resolved the disputed project %s', ET_DOMAIN ),
					'<strong class="author-admin">' . get_the_author_meta( 'display_name', $admin ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '?dispute=1">
                                <span class="notify-avatar">' . get_avatar( $admin, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'report_dispute_project':
				// text : [Admin] Sent you a report on [dispute_detail_page]
				$classAdmin = '';
				if ( ae_user_role( $admin ) == 'administrator' ) {
					$classAdmin = 'author-admin';
				}
				$message = sprintf( __( '%s sent you a report on %s', ET_DOMAIN ),
					'<strong class="' . $classAdmin . '">' . get_the_author_meta( 'display_name', $admin ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '?dispute=1">
                                <span class="notify-avatar">' . get_avatar( $admin, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'approve_order':
				$order_type = get_post_meta( $order, 'et_order_product_type', true );
				//$href       = et_get_page_link( 'profile' );
				$href = '';
				if ( $order_type == 'fre_credit_plan' ) {
					$href = ' href="' . et_get_page_link( 'my-credit' ) . '" ';
				}
				$package = current( get_post_meta( $order, 'et_order_products', true ) );

				// text : [Admin] Approved your payment on [package_name] package
				$message = sprintf( __( '%s approved your payment on %s package', ET_DOMAIN ),
					'<strong class="author-admin">' . get_the_author_meta( 'display_name', $admin ) . '</strong>',
					'<strong>' . $package['NAME'] . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" ' . $href . '>
                                <span class="notify-avatar">' . get_avatar( $admin, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'cancel_order':
				$order_type = get_post_meta( $order, 'et_order_product_type', true );
				$href       = et_get_page_link( 'profile' );
				if ( $order_type == 'fre_credit_plan' ) {
					$href = et_get_page_link( 'my-credit' );
				}
				$package = current( get_post_meta( $order, 'et_order_products', true ) );

				// text : [Admin] Declined your payment on [package_name] package
				$message = sprintf( __( '%s declined your payment on %s package', ET_DOMAIN ),
					'<strong class="author-admin">' . get_the_author_meta( 'display_name', $admin ) . '</strong>',
					'<strong>' . $package['NAME'] . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . $href . '">
                                <span class="notify-avatar">' . get_avatar( $admin, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'reject_project':
				// text: [Admin] Rejected your project [project_name]
				$message = sprintf( __( '%s rejected your project %s', ET_DOMAIN ),
					'<strong class="author-admin">' . get_the_author_meta( 'display_name', $admin ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '">
                                <span class="notify-avatar">' . get_avatar( $admin, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'archive_project':
				// text: [Admin] Archived your draft project [project_name]
				$message = sprintf( __( '%s archived your draft project %s', ET_DOMAIN ),
					'<strong class="author-admin">' . get_the_author_meta( 'display_name', $admin ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '">
                                <span class="notify-avatar">' . get_avatar( $admin, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'delete_project':
				// text: [Admin] Deleted your archived project [project_name]
				$message = sprintf( __( '%s deleted your archived project %s', ET_DOMAIN ),
					'<strong class="author-admin">' . get_the_author_meta( 'display_name', $admin ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '">
                                <span class="notify-avatar">' . get_avatar( $admin, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'publish_project':
				// text: [Admin] Approved your project [project_name]
				$message = sprintf( __( '%s approved your project %s', ET_DOMAIN ),
					'<strong class="author-admin">' . get_the_author_meta( 'display_name', $admin ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '">
                                <span class="notify-avatar">' . get_avatar( $admin, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'close_project':
				// Text: <employer> closed your working project <project_title>
				$project_owner = get_post_field( 'post_author', $project );
				$message       = sprintf( __( '%s closed your working project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $project_owner ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content       .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '?dispute=1">
                                <span class="notify-avatar">' . get_avatar( $project_owner, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'quit_project':
				// Text: <freelancer> discontinued on your project <project_title>
				$project_owner = get_post_field( 'post_author', $project );
				$bid_id        = get_post_meta( $project, 'accepted', true );
				$bid_author    = get_post_field( 'post_author', $bid_id );
				$message       = sprintf( __( '%s discontinued on your project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $bid_author ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content       .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '?dispute=1">
                                <span class="notify-avatar">' . get_avatar( $bid_author, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'delete_bid':
				// Text: <freelancer> cancelled his bid on your project <project_title>
				$message = sprintf( __( '%s cancelled his bid on your project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $freelancer ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '">
                                <span class="notify-avatar">' . get_avatar( $freelancer, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			// notify employer when his project have new bid
			case 'new_bid':
				// Text: <freelancer> bidded on your project <project_title>
				// get bid author
				$bid_author = get_post_field( 'post_author', $bid );
				$message    = sprintf( __( '%s bidded on your project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $bid_author ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content    .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '">
                                <span class="notify-avatar">' . get_avatar( $bid_author, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;

			case 'bid_accept':
				// Text: <employer> accepted your bid on the project <project_title>
				$project_owner = get_post_field( 'post_author', $project );
				$message       = sprintf( __( '%s accepted your bid on the project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $project_owner ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content       .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '">
                                <span class="notify-avatar">' . get_avatar( $project_owner, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;

			case 'complete_project':
				// Text: <employer> finished your working project <project_title>
				$project_owner = get_post_field( 'post_author', $project );
				$message       = sprintf( __( '%s finished your working project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $project_owner ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content       .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '?workspace=1' . '">
                                <span class="notify-avatar">' . get_avatar( $project_owner, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;

			case 'review_project':
				// Text: <freelancer> reviewed on your project <project_title>
				$accepted_bid = get_post_meta( $project, 'accepted', true );
				$bid_author   = get_post_field( 'post_author', $accepted_bid );
				$message      = sprintf( __( '%s reviewed on your project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $bid_author ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content      .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '?workspace=1' . '">
                                <span class="notify-avatar">' . get_avatar( $bid_author, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;

			case 'new_message':
				// Text: <employer> sent you a message on the <project_title> workspace
				$user_profile_id = get_user_meta( $sender, 'user_profile_id', true );
				if ( ! isset( $sender ) ) {
					$sender = 1;
				}
				$workspace_link = add_query_arg( array( 'workspace' => 1 ), get_permalink( $project ) );
				$message        = sprintf( __( '%s sent you a message on %s workspace ', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $sender ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content        .= '<a class="fre-notify-wrap" href="' . $workspace_link . '">
                                <span class="notify-avatar">' . get_avatar( $sender, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'new_invite':
				$project = get_post_meta( $notify->ID, 'project_list', true );
				$message = sprintf( __( '%s invited you to join project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $send_invite ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content .= '<a class="fre-notify-wrap" href="' . get_permalink( $project ) . '">
                                <span class="notify-avatar">' . get_avatar( $send_invite, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;

			case 'new_comment':
				// Text: <user> commented on your project <project_title>
				$workspace_link = add_query_arg( array( 'workspace' => 1 ), get_permalink( $project ) );
				$message        = sprintf( __( '%s commented on your project %s', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $comment_author_id ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content        .= '<a class="fre-notify-wrap" href="' . $workspace_link . '">
                                <span class="notify-avatar">' . get_avatar( $comment_author_id, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'lock_file_project':
				// Text: <employer> sent you a message on the <project_title> workspace
				$user_profile_id = get_user_meta( $sender, 'user_profile_id', true );
				if ( ! isset( $sender ) ) {
					$sender = 1;
				}
				$workspace_link = add_query_arg( array( 'workspace' => 1 ), get_permalink( $project ) );
				$message        = sprintf( __( '%s locked the file section in the project %s. Click here for details', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $sender ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content        .= '<a class="fre-notify-wrap" href="' . $workspace_link . '">
                                <span class="notify-avatar">' . get_avatar( $sender, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			case 'unlock_file_project':
				// Text: <employer> sent you a message on the <project_title> workspace
				$user_profile_id = get_user_meta( $sender, 'user_profile_id', true );
				if ( ! isset( $sender ) ) {
					$sender = 1;
				}
				$workspace_link = add_query_arg( array( 'workspace' => 1 ), get_permalink( $project ) );
				$message        = sprintf( __( '%s unlocked the file section in the project %s. Click here for details', ET_DOMAIN ),
					'<strong>' . get_the_author_meta( 'display_name', $sender ) . '</strong>',
					'<strong>' . get_the_title( $project ) . '</strong>'
				);
				$content        .= '<a class="fre-notify-wrap" href="' . $workspace_link . '">
                                <span class="notify-avatar">' . get_avatar( $sender, 48 ) . '</span>
                                <span class="notify-info">' . $message . '</span>
                                <span class="notify-time">' . sprintf( __( "%s on %s", ET_DOMAIN ), get_the_time( '', $notify->ID ), get_the_date( '', $notify->ID ) ) . '</span>
                            </a>';
				break;
			default:
				break;
		}

		$content .= '<a class="notify-remove" data-id="' . $notify->ID . '"><span></span></a>';

		// return notification content
		return $content;
	}

	/**
	 * render js template for notification
	 * @since 1.2
	 * @author Dakachi
	 */
	function render_template_js() {
		?>
        <script type="text/template" id="ae-notify-loop">
            {{= content }}
        </script>
		<?php
	}

	/**
	 * clear a flag when user read new message
	 *
	 * @param snippet
	 *
	 * @since snippet.
	 * @author Dakachi
	 */
	function mark_user_read_message() {
		if ( isset( $_REQUEST['workspace'] ) && $_REQUEST['workspace'] ) {
			if ( is_singular( PROJECT ) ) {
				global $post, $user_ID;
				delete_post_meta( $post->ID, $user_ID . '_new_message' );
			}
		}
	}

	/**
	 * user can sync notification ( delete )
	 *
	 * @param snippet
	 *
	 * @since snippet.
	 * @author Dakachi
	 */
	function notify_sync() {
		global $ae_post_factory, $user_ID;
		$request = $_REQUEST;
		// unset($request['post_content']);
		unset( $request['post_excerpt'] );
		if ( isset( $request['delete'] ) ) {
			$request['post_status'] = 'trash';
		}

		$place = $ae_post_factory->get( $this->post_type );
		// sync notify
		$result = $place->sync( $request );

		wp_send_json( array(
			'success' => true,
			'data'    => $result,
			'msg'     => __( "Update project successful!", ET_DOMAIN )
		) );

	}

	/**
	 * Notify employer when have new comment approve
	 *
	 * @param $new_status
	 * @param $old_status
	 * @param $comment
	 *
	 * @since 1.7.5
	 * @author Tuandq
	 */
	function fre_approve_comment_callback( $new_status, $old_status, $comment ) {
		$post_status = get_post_field( 'post_status', $comment->comment_post_ID );
		if ( ! in_array( $post_status, array( 'pending', 'publish' ) ) ) {
			return;
		}
		if ( $new_status != $old_status ) {
			if ( $new_status == 'approved' ) {
				$post_excerpt = 'type=new_comment&project=' . $comment->comment_post_ID . "&comment_author=" . $comment->comment_author . "&comment_author_id=" . $comment->user_id . "&comment_author_email=" . $comment->comment_author_email;
				$post_author  = get_post_field( 'post_author', $comment->comment_post_ID );
				// insert notification
				$notification = array(
					'post_type'    => 'notify',
					'post_content' => $comment->comment_content,
					'post_excerpt' => $post_excerpt,
					'post_author'  => $post_author,
					'post_title'   => sprintf( __( "New comment on %s", ET_DOMAIN ), get_the_title( $comment->comment_post_ID ) ),
					'post_status'  => 'publish',
					'post_parent'  => $comment->comment_post_ID,
					'post_date'    => $comment->comment_date
				);
				// $noti = new Fre_Notification();
				$insert = $this->insert( $notification );
			}
		}
	}

	/**
	 * Notify employer when have new comment auto approve
	 *
	 * @param $comment_id
	 * @param $comment
	 *
	 * @since 1.7.5
	 * @author Tuandq
	 */
	function fre_auto_approve_comment_callback( $comment_id, $comment ) {
		$post_author = get_post_field( 'post_author', $comment->comment_post_ID );
		$post_status = get_post_field( 'post_status', $comment->comment_post_ID );
		if ( ! in_array( $post_status, array( 'pending', 'publish' ) ) ) {
			return;
		}
		if ( ( ! get_option( 'comment_moderation' ) && $post_author != $comment->user_id ) || ( get_option( 'comment_moderation' ) && current_user_can( 'administrator' ) ) ) {
			$post_excerpt = 'type=new_comment&project=' . $comment->comment_post_ID . "&comment_author=" . $comment->comment_author . "&comment_author_id=" . $comment->user_id . "&comment_author_email=" . $comment->comment_author_email;
			// insert notification
			$notification = array(
				'post_type'    => 'notify',
				'post_content' => $comment->comment_content,
				'post_excerpt' => $post_excerpt,
				'post_author'  => $post_author,
				'post_title'   => sprintf( __( "New comment on %s", ET_DOMAIN ), get_the_title( $comment->comment_post_ID ) ),
				'post_status'  => 'publish',
				'post_parent'  => $comment->comment_post_ID,
				'post_date'    => $comment->comment_date
			);
			// $noti = new Fre_Notification();
			$insert = $this->insert( $notification );
		}
	}

}

new Fre_Notification();

/**
 * get user notification by
 *
 * @param snippet
 *
 * @since snippet.
 * @author Dakachi
 */
function fre_user_notification( $user_id = 0, $page = 1, $showposts = 10, $class = "dropdown-menu dropdown-menu-notifi dropdown-keep-open notification-list" ) {
	if ( ! $user_id ) {
		global $user_ID;
		$user_id = $user_ID;
	}
	global $post, $wp_query, $ae_post_factory;
	$notify_object = $ae_post_factory->get( 'notify' );
	$notifications = query_posts( array(
		'post_type'   => 'notify',
		'post_status' => 'publish',
		'author'      => $user_id,
		'showposts'   => $showposts,
		'paged'       => $page
	) );
	$postdata      = array();
	echo '<ul class="list_notify ' . $class . '">';
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			$notify = $post;

			$project = get_post( $post->post_parent );
			if ( ! $project || is_wp_error( $project ) ) {
				continue;
			}
			$notify       = $notify_object->convert( $post );
			$postdata[]   = $notify;
			$type         = '';
			$post_excerpt = str_replace( '&amp;', '&', $notify->post_excerpt );
			parse_str( $post_excerpt );
			/*If ae_private_message not active is continue*/
			if ( $type == 'new_private_message' ) {
				if ( ! function_exists( 'ae_private_message_activate' ) ) {
					continue;
				}
			}
			$seenClass = '';
			if ( $notify->seen == '' ) {
				$seenClass = 'fre-notify-new';
			}
			echo '<li class="notify-item item-' . $notify->ID . ' ' . $type . ' ' . $seenClass . ' " data-id="' . $notify->ID . '">';
			echo $notify->content;
			echo '</li>';
		}
	} else {
		if ( $class == 'fre-notification-list' ) {
			echo '<li class="no-result">';
			echo '<span>' . __( 'There are no notifications found.', ET_DOMAIN ) . '</span>';
			echo '</li>';
		}
	}
	// Check is dropdown
	if ( $class != 'fre-notification-list' ) {
		echo '<li style="text-align: center;">';
		echo '<a class="view-more-notify" href="' . et_get_page_link( 'list-notification' ) . '">' . __( 'See all notifications', ET_DOMAIN ) . '</a>';
		echo '</li>';
	}
	echo '</ul>';
	echo '<script type="data/json" class="postdata" >' . json_encode( $postdata ) . '</script>';
	// Check is not dropdown
	if ( $class == 'fre-notification-list' ) {
		// pagination
		echo '<div class="fre-paginations paginations-wrapper">';
		ae_pagination( $wp_query, get_query_var( 'paged' ), 'page' );
		echo '</div>';
	}

	wp_reset_query();
}

/**
 * function check user have new notifcation or not
 * @since 1.3
 * @author Dakachi
 */
function fre_user_have_notify() {
	global $user_ID;

	return get_user_meta( $user_ID, 'fre_new_notify', true );
}

/**
 * function update seen notify
 * @author ThanhTu
 */
function fre_user_seen_notify() {
	global $user_ID;
	$request = $_REQUEST;
	if ( isset( $request['IDs'] ) ) {
		foreach ( $request['IDs'] as $key => $value ) {
			update_post_meta( $value, 'seen', 1 );
		}
	}
	$result = update_user_meta( $user_ID, 'fre_new_notify', 0 );
	$return = array( 'success' => true );
	if ( is_wp_error( $result ) ) {
		$return = array(
			'success' => false
		);
	}
	wp_send_json( $return );
}

add_action( 'wp_ajax_fre-user-seen-notify', 'fre_user_seen_notify' );
/**
 * function remove notify
 * @author ThanhTu
 */
function fre_notify_remove() {
	global $user_ID;
	$request = $_REQUEST;
	$return  = array( 'success' => false );
	if ( $request['type'] == 'delete' ) {
		// trash notify
		$post = wp_trash_post( $request['ID'] );
		if ( $post['post_status'] == 'trash' ) {
			$return = array(
				'success' => true
			);
		}
	} else if ( $request['type'] == 'undo' ) {
		// undo notify
		$post   = wp_publish_post( $request['ID'] );
		$return = array(
			'success' => true
		);
	}
	wp_send_json( $return );
}

add_action( 'wp_ajax_fre-notify-remove', 'fre_notify_remove' );