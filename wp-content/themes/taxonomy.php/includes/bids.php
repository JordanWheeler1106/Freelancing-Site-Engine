<?php
function fre_register_bid() {

	// list args for bi post_type
	$bid_labels = array(
		'name'               => __( 'Bids', ET_DOMAIN ),
		'singular_name'      => __( 'Bids', ET_DOMAIN ),
		'add_new'            => _x( 'Add New', ET_DOMAIN, ET_DOMAIN ),
		'add_new_item'       => __( 'Add New', ET_DOMAIN ),
		'edit_item'          => __( 'Edit Bid', ET_DOMAIN ),
		'new_item'           => __( 'New bid', ET_DOMAIN ),
		'view_item'          => __( 'View bid', ET_DOMAIN ),
		'search_items'       => __( 'Search Bids', ET_DOMAIN ),
		'not_found'          => __( 'No Bids found', ET_DOMAIN ),
		'not_found_in_trash' => __( 'No Bids found in Trash', ET_DOMAIN ),
		'parent_item_colon'  => __( 'Parent bid:', ET_DOMAIN ),
		'menu_name'          => __( 'Bids', ET_DOMAIN ),
	);

	$bid_args = array(
		'labels'              => $bid_labels,
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 10,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => false,
		'exclude_from_search' => false,
		'has_archive'         => false,
		'query_var'           => false,
		'can_export'          => true,
		'rewrite'             => array(
			'slug' => 'bid'
		),

		// need fix to global
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			'editor',
			'author',
			'excerpt',
			'custom-fields',
			'page-attributes',
			'comments',
		)
	);
	register_post_type( BID, $bid_args );

	// need fix to global.
	global $ae_post_factory, $post, $wpdb;
	$ae_post_factory->set( BID, new AE_Posts( BID, array(), array(

		// price enter when bid a project
		'bid_budget',
		'dealine',

		// bid status set to 1 if project owner accept bid
		'accepted',

		// time dealine
		'bid_time',

		// time type (day,week,hour)
		'type_time'
	) ) );
}

add_action( 'init', 'fre_register_bid', 11 );

/**
 * class control all action related to a bid object
 * @author Dan
 */
class Fre_BidAction extends AE_PostAction {
	public static $instance;

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new Fre_BidAction();
		}

		return self::$instance;
	}

	public function __construct( $post_type = BID ) {

		// init mail instance to send mail
		$this->mail = Fre_Mailing::get_instance();

		$this->post_type = $post_type;

		/* add more data when convert a bid */
		$this->add_filter( 'ae_convert_bid', 'ae_convert_bid' );

		// sync to update bid
		$this->add_ajax( 'ae-sync-bid', 'bid_sync' );
		$this->add_ajax( 'ae-bid-sync', 'bid_sync' );
		$this->add_ajax( 'ae-bid-hide', 'bid_hide' );

		/* accept a bid */
		$this->add_ajax( 'ae-accept-bid', 'bid_accept' );

		// request list bid
		$this->add_ajax( 'ae-fetch-bid', 'fetch_post' );

		/*
		 * check permission before insert bid.
		*/
		$this->add_filter( 'ae_pre_insert_bid', 'fre_check_before_insert_bid', 12, 1 );

		/*
		 * update project and bid after bid success.
		*/
		$this->add_action( 'ae_insert_bid', 'fre_update_after_bidding', 12, 1 );

		/*
		 * aftion after delete a bid
		 * use this action insteated the action trashed_post
		*/
		$this->add_action( 'et_delete_bid', 'fre_delete_bid', 12, 1 );

		//$this->add_action('trashed_post','fre_delete_bid');

		/*
		 * Filter for post title in back-end
		*/
		$this->add_filter( 'the_title', 'the_title_bid', 10, 2 );
		$this->add_filter( 'pre_get_posts', 'pre_get_posts' );
		/*
		 * Add column project tile in wrodpress
		*/
		$this->add_filter( 'manage_bid_posts_columns', 'manage_bid_column_project', 1 );
		$this->add_action( 'manage_bid_posts_custom_column', 'project_title_column_render', 2, 10 );
		$this->add_action( 'ae_notify_admin', 'fre_notify_admin_bid', 2, 10 );
		self::$instance = $this;
	}

	function pre_get_posts( $query ) {
		global $wpdb;
		$request = $_REQUEST;

		if ( isset( $request['query']['bid_current_status'] ) ) {
			if ( $request['query']['bid_current_status'] == '' ) {
				$query->set( 'post_status', array( 'publish', 'accept', 'unaccept', 'disputing', 'archive' ) );
			} else {
				$query->set( 'post_status', $request['query']['bid_current_status'] );
			}
		}

		if ( isset( $request['query']['bid_previous_status'] ) ) {
			if ( $request['query']['bid_previous_status'] == '' ) {
				$query->set( 'post_status', array( 'complete', 'disputed' ) );
			} else {
				$query->set( 'post_status', $request['query']['bid_previous_status'] );
			}
		}

		if ( isset( $request['query']['project_current_status'] ) ) {
			if ( $request['query']['project_current_status'] == '' ) {
				$query->set( 'post_status', array(
					'close',
					'disputing',
					'publish',
					'pending',
					'draft',
					'reject',
					'archive'
				) );
			} else {
				$query->set( 'post_status', $request['query']['project_current_status'] );
			}
		}

		if ( isset( $request['query']['project_previous_status'] ) ) {
			if ( $request['query']['project_previous_status'] == '' ) {
				$query->set( 'post_status', array( 'complete', 'disputed' ) );
			} else {
				$query->set( 'post_status', $request['query']['project_previous_status'] );
			}
		}

		if ( isset( $request['query']['filter_work_history'] ) ) {
			add_filter( 'posts_where', array( $this, 'fre_filter_where_bid' ) );
		}
		if ( isset( $request['query']['filter_bid_status'] ) ) {
			add_filter( 'posts_where', array( $this, 'fre_filter_where_current_bid' ) );
		}

		return $query;
	}

	function fre_filter_where_current_bid( $where ) {
		global $wpdb;
		$request = $_REQUEST['query'];
		$result  = $wpdb->get_col( "SELECT * FROM $wpdb->posts 
                WHERE 1=1 
                AND post_type = 'project' 
                AND post_status IN ('publish', 'close')" );
		if ( ! empty( $result ) ) {
			$where .= "AND {$wpdb->posts}.post_parent IN (" . implode( ',', $result ) . ")";
		}

		return $where;
	}

	function fre_filter_where_bid( $WHERE ) {
		global $wpdb;
		$request = $_REQUEST['query'];
		if ( ! empty( $request['filter_work_history'] ) ) {
			$status = $request['filter_work_history'];
			$sql    = "SELECT * FROM $wpdb->posts 
                                    WHERE 1=1 
                                    AND post_type = 'project' 
                                    AND post_status IN ('$status')";
		} else {
			$sql = "SELECT * FROM $wpdb->posts 
                                    WHERE 1=1 
                                    AND post_type = 'project' 
                                    AND post_status IN ('complete', 'disputing', 'disputed')";
		}
		$result = $wpdb->get_col( $wpdb->prepare( $sql ) );
		if ( ! empty( $result ) ) {
			$WHERE .= "AND {$wpdb->posts}.post_parent IN (" . implode( ',', $result ) . ")";
		}

		return $WHERE;
	}

	/**
	 * Send to admin when a freelancer buy a bid package.
	 */
	public function fre_notify_admin_bid( $data ) {
		if ( ! isset( $data['order_id'] ) ) {
			return false;
		}
		$this->mail->new_payment_notification( $data['order_id'] );
	}

	/**
	 * Override filter_query_args for bid
	 */
	public function filter_query_args( $query_args ) {

		if ( isset( $_REQUEST['query'] ) ) {
			$query = $_REQUEST['query'];
			if ( isset( $query['post_parent'] ) && $query['post_parent'] != '' ) {
				$query_args['post_parent'] = $query['post_parent'];
			}

			$query_args['post_status'] = $query['post_status'];
			if ( isset( $query['filter_bid_status'] ) && $query['filter_bid_status'] != '' ) {
				$query_args['post_status'] = $query['filter_bid_status'];
			}
		}

		return $query_args;
	}

	/**
	 * convert bid
	 */

	function ae_convert_bid( $result ) {
		global $user_ID;

		$result->et_avatar    = get_avatar( $result->post_author, 70 );
		$result->author_url   = get_author_posts_url( $result->post_author );
		$profile_id           = get_user_meta( $result->post_author, 'user_profile_id', true );
		$result->rating_score = (float) get_post_meta( $result->ID, 'rating_score', true );

		$winner_of_arbitrate = get_post_meta( $result->post_parent, 'winner_of_arbitrate', true );
		if ( $winner_of_arbitrate ) {
			$result->win_disputed = $winner_of_arbitrate;
		}

		$result->button_message = '';
		if ( ( isset( $_REQUEST ) && isset( $_REQUEST['query'] ) && isset( $_REQUEST['query']['is_single'] ) ) || is_singular( PROJECT ) ) {
			$result->rating_score = (float) get_post_meta( $profile_id, 'rating_score', true );
		}

		if ( $profile_id ) {
			$result->et_professional_title = get_post_meta( $profile_id, 'et_professional_title', true );
			$result->experience            = get_post_meta( $profile_id, 'et_experience', true );

			$result->profile_display = get_the_author_meta( 'display_name', $result->post_author );
		} else {
			$result->et_professional_title = '';
			$result->experience            = __( 'Unknow', ET_DOMAIN );
			$result->profile_display       = get_the_author_meta( 'display_name', $result->post_author );
		}

		if ( $result->type_time == 'day' ) {
			if ( $result->bid_time > 1 ) {
				$result->bid_time_text = sprintf( __( "in %d days", ET_DOMAIN ), $result->bid_time );
			} else {
				$result->bid_time_text = sprintf( __( "in %d day", ET_DOMAIN ), $result->bid_time );
			}
		} else {
			if ( $result->bid_time > 1 ) {
				$result->bid_time_text = sprintf( __( "in %d weeks", ET_DOMAIN ), $result->bid_time );
			} else {
				$result->bid_time_text = sprintf( __( "in %d week", ET_DOMAIN ), $result->bid_time );
			}
		}
		$result->bid_budget_text = fre_price_format( $result->bid_budget);

		//convert project infor to bid
		$project_post = get_post( $result->post_parent );

		/**
		 * add project data to bid
		 */
		if ( $project_post && ! is_wp_error( $project_post ) ) {

			global $ae_post_factory;
			$project_obj = $ae_post_factory->get( PROJECT );

			$post_object                   = $ae_post_factory->get( PROFILE );
			$profile_post                  = get_post( $profile_id );
			$profile                       = $post_object->convert( $profile_post );
			$project_post                  = $project_obj->convert( $project_post );
			$result->accepted              = get_post_status( $project_post->ID, 'accepted', true );
			$result->project_author_avatar = get_avatar( $project_post->post_author, 30 );
			$result->project_link          = get_permalink( $project_post->ID );
			$result->project_title         = $project_post->post_title;
			$result->project_status        = $project_post->post_status;
			$projects_worked               = get_post_meta( $profile_id, 'total_projects_worked', true );
			if ( ! $projects_worked ) {
				$projects_worked = 0;
			}

			$status_arr                     = array(
				'accept'    => __( "Processing", ET_DOMAIN ),
				'unaccept'  => __( "Processing", ET_DOMAIN ),
				'publish'   => __( "Active", ET_DOMAIN ),
				'archive'   => __( "Archived", ET_DOMAIN ),
				'disputing' => __( "Disputed", ET_DOMAIN ),
				'complete'  => __( "Completed", ET_DOMAIN ),
				'disputed'  => __( "Resolved", ET_DOMAIN ),
				'hide'      => __( "In Process", ET_DOMAIN )
			);
			$result->project_status_view    = $status_arr[ $result->post_status ];
			$result->total_projects_worked  = $projects_worked;
			$result->project_id             = $project_post->ID;
			$result->project_author         = (int) get_post_field( 'post_author', $project_post->ID );
			$result->author_country         = empty( $profile->tax_input['country'] ) ? '' : $profile->tax_input['country'][0]->name;
			$result->project_accepted       = $project_post->accepted;
			$result->review_link            = add_query_arg( 'review', 1, $project_post->permalink );
			$result->project_workspace_link = add_query_arg( array( 'workspace' => 1 ), $project_post->permalink );
			$result->project_status_text    = sprintf( __( 'Job is %s', ET_DOMAIN ), $project_post->post_status );

			if ( isset( $project_post->et_expired_date ) && ! empty( $project_post->et_expired_date ) ) {
				$result->et_expired_date = sprintf( __( '%s left for bidding', ET_DOMAIN ), human_time_diff( time(), strtotime( $project_post->et_expired_date ) ) );
			}

			/* get bid review  */
			$result->project_comment = '';
			if ( $result->project_status == 'complete' ) {

				// only project complete should have review
				$comment = get_comments( array(
					'post_id' => $result->ID,
					'type'    => 'em_review'
				) );
				if ( $comment ) {
					$comment_content              = wpautop( $comment['0']->comment_content );
					$result->project_comment      = $comment_content;
					$result->project_comment_trim = wp_trim_words( $comment_content, 25, '...' );
				}
			}

			$result->project_post_date = $project_post->post_date;

			//$result->et_budget = $project_post->et_budget;
			$result->et_budget = fre_price_format( get_post_meta( $project_post->ID, 'et_budget', true ) );

			// add new fields return @author ThaiNT
			$result->total_bids  = $project_post->total_bids ? $project_post->total_bids : 0;
			$result->bid_budget  = $result->bid_budget ? fre_price_format( $result->bid_budget ) : 0;
			$result->bid_average = $project_post->bid_average ? fre_price_format( $project_post->bid_average ) : 0;

			$result = apply_filters( 'ae_convert_bid_message', $result );

		} else {
			$result->et_expired_date       = sprintf( __( '%s ago for bidding', ET_DOMAIN ), human_time_diff( get_the_time( 'U' ), time() ) );
			$result->accepted              = '';
			$result->project_author_avatar = '';
			$result->project_link          = '';
			$result->project_title         = '';
			$result->project_status        = '';
			$result->project_post_date     = '';
			$result->et_budget             = '';
			$result->project_id            = '';
			$result->project_author        = '';
		}
		$result->is_admin     = current_user_can( 'manage_options' ) == true ? 1 : 0;
		$result->current_user = $user_ID;
		$result               = $this->ae_convert_check_bid_item( $result );

		return $result;
	}

	function ae_get_bid_won_date( $bid_post_id ) {
		$bid_post    = get_post( $bid_post_id );
		$bid_convert = $this->ae_convert_bid( $bid_post );

		return $bid_convert->bid_time_text;
	}

	function ae_convert_check_bid_item( $result ) {
		global $ae_post_factory, $user_ID;
		$bid_accept     = get_post_meta( $result->project_id, 'accepted', true );
		$project_status = $result->project_status;
		$role           = ae_user_role();
		$hide           = '';
		$user_bid       = '';
		$flag           = 0;
		if ( $role == FREELANCER ) {
			if ( $project_status == 'publish' && $result->post_author == $user_ID ) {
				$user_bid = 'bid-of-user';
			} else if ( in_array( $project_status, array( 'complete', 'close', 'disputing', 'disputed' ) ) ) {
				if ( $result->post_author == $user_ID ) {
					$user_bid = 'bid-of-user';
				} else if ( $bid_accept == $result->ID ) {
					$user_bid = 'bid_win';
				}
			}
			if ( $user_bid == '' ) {
				$hide = 'bid_unaccepted bid_hide';
			} else if ( $user_bid == 'bid_win' ) {
				$hide = 'bid_hide';
			}
		} else if ( $role == EMPLOYER ) {
			if ( $result->post_author == $user_ID ) {
				$hide = '';
			}
			if ( ! ( in_array( $project_status, array(
						'complete',
						'close',
						'disputing',
						'disputed'
					) ) && $bid_accept == $result->ID ) && $project_status != 'publish'
			) {
				$hide = 'bid_unaccepted bid_hide';
			}
		} else {
			// hide all
			if ( in_array( $project_status, array( 'complete', 'close', 'disputing', 'disputed' ) ) ) {
				if ( $result->post_author == $user_ID ) {
					$user_bid = 'bid-of-user';
				} else if ( $bid_accept == $result->ID ) {
					$user_bid = 'bid_win';
				}
			}
			if ( $user_bid == '' ) {
				$hide = 'bid_unaccepted bid_hide';
			} else if ( $user_bid == 'bid_win' ) {
				$hide = 'bid_hide';
			}
		}

		// Check button accept bid
		if ( $user_ID == (int) $result->project_author && $project_status == 'publish' ) {
			$flag = 1;
		} else if ( $bid_accept && $bid_accept == $result->ID && in_array( $project_status, array(
				'complete',
				'close',
				'disputing',
				'disputed'
			) )
		) {
			$flag = 2;
		}
		$class                 = $hide . ' ' . $user_bid;
		$result->add_class_bid = $class;
		$result->flag          = $flag;

		return $result;
	}

	/**
	 * bid_sync description
	 *  create, udpate , delete a bid.
	 * @return
	 */
	function bid_sync() {

		global $ae_post_factory, $user_ID;

		if ( ! AE_Users::is_activate( $user_ID ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "Your account is pending. You have to activate your account to continue this step.", ET_DOMAIN )
			) );
		};

		$method = isset( $_REQUEST['method'] ) ? $_REQUEST['method'] : '';
		if ( $method == 'remove' ) {
			$bid_id     = $_REQUEST['ID'];
			$project_id = get_post_field( 'post_parent', $bid_id );
			$accepted   = get_post_field( 'accepted', $project_id );
			if ( (int) $accepted == (int) $bid_id ) {
				wp_send_json( array(
					'success' => false,
					'msg'     => __( 'Error. You have been accepted for this project', ET_DOMAIN )
				) );
			}
		}

		$post_data               = (array) $_REQUEST;
		$post_data['post_title'] = get_the_title( $post_data['post_parent'] );

		// sync bid
		$bid    = $ae_post_factory->get( BID );
		$result = $bid->sync( $post_data );

		if ( is_wp_error( $result ) ) {

			// send error to client
			wp_send_json( array(
				'success' => false,
				'msg'     => $result->get_error_message()
			) );
		} else {
			$message = __( "Update bid successful.", ET_DOMAIN );
			if ( $method == 'create' ) {
				$message = __( "Create bid successful.", ET_DOMAIN );
			}
			wp_send_json( array(
				'success' => true,
				'msg'     => $message
			) );
		}
	}


	/**
	 * Change bid status to hide
	 * @author QuocTran
	 */
	function bid_hide() {
		global $user_ID;
		if ( ! AE_Users::is_activate( $user_ID ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "Your account is pending. You have to activate your account to continue this step.", ET_DOMAIN )
			) );
		};
		$request = $_REQUEST;
		$bid_id  = $request['ID'];
		if ( isset( $bid_id ) && $bid_id != '' ) {
			$result = wp_update_post( array(
				'ID'          => $bid_id,
				'post_status' => 'hide'
			) );
			if ( is_wp_error( $result ) ) {
				wp_send_json( array(
					'success' => false,
					'msg'     => $result->get_error_message()
				) );
			} else {
				$message = __( "The project has been removed!", ET_DOMAIN );
				wp_send_json( array(
					'success' => true,
					'msg'     => $message
				) );
			}
		} else {
			$message = __( "Request failed. Please refresh the page and try again", ET_DOMAIN );
			wp_send_json( array(
				'success' => false,
				'msg'     => $message,
			) );
		}
	}


	/**
	 * accept a bid for project
	 * @author Dan
	 */
	function bid_accept() {

		$request = $_POST;
		$bid_id  = isset( $request['bid_id'] ) ? $request['bid_id'] : '';
		$result  = $this->assign_project( $bid_id );

		if ( ! is_wp_error( $result ) ) {

			/**
			 * fire action fre_accept_bid after accept a bid
			 *
			 * @param int $bid_id the id of accepted bid
			 * @param Array $request
			 *
			 * @since 1.2
			 * @author Dakachi
			 */
			do_action( 'fre_accept_bid', $bid_id );

			// send message to client
			wp_send_json( array(
				'success' => true,
				'msg'     => __( 'Project has been assign successful.', ET_DOMAIN )
			) );
		}

		wp_send_json( array(
			'success' => false,
			'msg'     => $result->get_error_message()
		) );
	}

	/*
	 *check perminsion before a freelancer bidding on a project.
	*/
	function fre_check_before_insert_bid( $args ) {
		global $user_ID;
		/**
		 * add filter to filter bid required field
		 *
		 * @param Array
		 *
		 * @since 1.4
		 * @author Dakachi
		 */
		$bid_required_field = apply_filters( 'fre_bid_required_field', array(
			'bid_budget',
			'bid_time',
			'bid_content'
		) );

		if ( is_wp_error( $args ) ) {
			return $args;
		}

		if ( in_array( 'bid_content', $args ) && ! isset( $args['bid_content'] ) ) {
			return new WP_Error( 'empty_content', __( 'Please enter your bid message.', ET_DOMAIN ) );
		}

		$args['post_content'] = $args['bid_content'];
		$project_id           = isset( $args['post_parent'] ) ? $args['post_parent'] : '';
		$args['post_status']  = 'publish';
		// $request = $_POST;

		/*
		 * validate data
		*/
		if ( in_array( 'bid_budget', $bid_required_field ) && ( ! isset( $args['bid_budget'] ) || empty( $args['bid_budget'] ) ) ) {
			return new WP_Error( 'empty_bid', __( 'You have to set the bid budget.', ET_DOMAIN ) );
		}

		if ( in_array( 'bid_time', $bid_required_field ) && ( ! isset( $args['bid_time'] ) || empty( $args['bid_time'] ) ) ) {
			return new WP_Error( 'empty_time', __( 'You have to set the time to finish project.', ET_DOMAIN ) );
		}

		if ( in_array( 'bid_budget', $bid_required_field ) && $args['bid_budget'] <= 0 ) {
			return new WP_Error( 'budget_less_than_zero', __( "Your budget have to greater than zero!", ET_DOMAIN ) );
		}

		if ( ( in_array( 'bid_budget', $bid_required_field ) && ! is_numeric( $args['bid_budget'] ) ) || ( in_array( 'bid_time', $bid_required_field ) && ! is_numeric( $args['bid_time'] ) ) ) {
			return new WP_Error( 'invalid_input', __( 'Please enter a valid number in budget or bid time', ET_DOMAIN ) );
		}

		if ( ! $user_ID ) {
			return new WP_Error( 'no_permission', __( 'Please login to bid a project', ET_DOMAIN ) );
		}

		if ( get_post_status( $project_id ) != 'publish' ) {
			return new WP_Error( 'invalid_input', __( 'This project is not publish.', ET_DOMAIN ) );
		}

		// $accepted = get_post_meta($project_id,'accepted', true);

		// if($accepted || 'complete' ==  get_post_status($project_id) )
		//    return new  WP_Error (200 ,__('The project has been accepted', ET_DOMAIN));

		if ( fre_has_bid( $project_id ) ) {
			return new WP_Error( 200, __( 'You have bid on this project', ET_DOMAIN ) );
		}

		$post_author = (int) get_post_field( 'post_author', $project_id, 'display' );

		if ( $user_ID == $post_author ) {
			return new WP_Error( 200, __( 'You can\'t bid on your project', ET_DOMAIN ) );
		}

		// check role to bid project
		$role = ae_user_role();
		if ( ! fre_share_role() && $role != FREELANCER ) {
			return new WP_Error( 200, __( 'You have to be a freelancer to bid a project.', ET_DOMAIN ) );
		}

		/*
		 * check profile has set?
		*/
		$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
		$profile    = get_post( $profile_id );

		// user have to complete profile to bid a project
		if ( ! $profile || ! is_numeric( $profile_id ) ) {
			return new WP_Error( 200, __( 'You must complete your profile to bid on a project.', ET_DOMAIN ) );
		}

		/* when using escrow, freelancer must setup an paypal account */
		if ( use_paypal_to_escrow() ) {
			$paypal_account = get_user_meta( $user_ID, 'paypal', true );
			if ( ! $paypal_account ) {
				return new WP_Error( 'dont_have_paypal', __( 'You must setup your paypal account in profile to receive money.', ET_DOMAIN ) );
			}
		}

		return $args;
	}

	/*
	 * update project and bid after have a bid succesfull.
	*/
	function fre_update_after_bidding( $bid_id ) {
		global $user_ID;
		if ( 'publish' != get_post_status( $bid_id ) ) {
			wp_update_post( array(
				'ID'          => $bid_id,
				'post_status' => 'publish'
			) );
		}

		$project_id = get_post_field( 'post_parent', $bid_id );

		//update avg bids for project
		$total_bids = get_number_bids( $project_id );
		$avg        = get_post_meta( $bid_id, 'bid_average', true );
		if ( $total_bids > 0 ) {
			$avg = get_total_cost_bids( $project_id ) / $total_bids;
		}

		update_post_meta( $project_id, 'bid_average', number_format( $avg, 2 ) );
		update_post_meta( $project_id, 'total_bids', $total_bids );

		$this->mail->bid_mail( $bid_id );
		$pay_credit = get_credit_to_pay();
		$pay_credit = - 1;
		if ( ae_get_option( 'pay_to_bid', false ) ) {
			update_credit_number( $user_ID, $pay_credit );
		}
		wp_send_json( array(
			'success' => true,
			'msg'     => __( 'You have successfully bidden.', ET_DOMAIN )
		) );
	}

	/*
	 * current force delete = false,
	 * remove currenbid one again with force = true
	*/
	function fre_delete_bid( $bid_id ) {
		// current bid status = trash.
		$project_id = get_post_field( 'post_parent', $bid_id );
		// update notify
		$notify_id          = get_post_meta( $bid_id, 'notify_id', true );
		$notify_post        = get_post( $notify_id );
		$notify_post_author = get_post_field( 'post_author', $bid_id );
		$param_delete       = 'delete_bid&freelancer=' . $notify_post_author;
		$post_content       = str_replace( 'new_bid', $param_delete, $notify_post->post_content );
		wp_update_post( array(
			'ID'           => $notify_id,
			'post_content' => $post_content,
			'post_excerpt' => $post_content,
		) );
		$number = get_user_meta( $notify_post->post_author, 'fre_new_notify', true );
		$number = $number + 1;
		update_user_meta( $notify_post->post_author, 'fre_new_notify', $number );

		$bid_budget = (float) get_post_meta( $bid_id, 'bid_budget', true );

		$total_bids = (int) get_post_meta( $project_id, 'total_bids', true ) - 1;

		$total_bids = max( $total_bids, 0 );

		wp_delete_post( $bid_id, true );

		$new_avg = 0;
		if ( $total_bids > 0 ) {

			$total_cost_bid = (float) get_total_cost_bids( $project_id ) / $total_bids;
			$new_avg        = $total_cost_bid / $total_bids;
		}

		// update avg and total bid;
		update_post_meta( $project_id, 'total_bids', $total_bids );

		update_post_meta( $project_id, 'bid_average', number_format( $new_avg, 2 ) );

		wp_delete_post( $bid_id, true );
		$this->mail->bid_cancel_mail( $project_id );
		wp_send_json( array(
			'success' => true,
			'msg'     => __( 'Your bid has been deleted successfully.', ET_DOMAIN )
		) );
	}

	/**
	 *Assign a job for a freelancer.
	 */
	function assign_project( $bid_id ) {

		global $user_ID, $wpdb;
		$project_id = get_post_field( 'post_parent', $bid_id );
		$project    = get_post( $project_id );

		$result = new WP_Error( $code = '200', $message = __( 'You don\'t have perminsion to accept this project.', ET_DOMAIN ), array() );

		// check authenticate
		if ( ! $user_ID ) {
			return new WP_Error( $code = '200', $message == __( 'You must login to accept bid.', ET_DOMAIN ) );
		}

		if ( $project->post_status != 'publish' ) {

			// a project have to published when bidding
			return new WP_Error( $code = '200', $message = __( 'Your project was not pubished. You can not accept a bid!', ET_DOMAIN ) );
		}

		if ( (int) $project->post_author == $user_ID ) {

			//update deadline project when bid accepted
			$bid_time      = get_post_meta( $bid_id, 'bid_time', true );
			$bit_type_time = get_post_meta( $bid_id, 'type_time', true );
			$date          = new DateTime();
			if ( $bit_type_time == 'day' ) {
				$date->modify( '+' . $bid_time . ' days' );
			} else if ( $bit_type_time == 'week' ) {
				$date->modify( '+' . $bid_time . ' weeks' );
			}
			$deadline_time = $date->format( 'Y-m-d H:i:s' );
			update_post_meta( $project->ID, 'project_deadline', $deadline_time );

			// add accepted bid id to project meta
			update_post_meta( $project->ID, 'accepted', $bid_id );

			// change project status to close so mark it to on working
			wp_update_post( array(
				'ID'          => $project->ID,
				'post_status' => 'close'
			) );

			// change a bid to be accepted
			wp_update_post( array(
				'ID'          => $bid_id,
				'post_status' => 'accept'
			) );

			/**
			 * fire action fre_assign_project after accept a bid
			 *
			 * @param Object $project the project was assigned
			 * @param int $bid_id the id of accepted bid
			 *
			 * @since 1.2
			 * @author Dakachi
			 */
			do_action( 'fre_assign_project', $project, $bid_id );


			// send mail to freelancer if he won a project
			$freelancer_id = get_post_field( 'post_author', $bid_id );

			$q_bid = new WP_Query( array(
					'post_type'   => BID,
					'post_parent' => $project_id,
					'post_status' => array( 'publish', 'unaccept' )
				)
			);
			if ( $q_bid->have_posts() ) {
				foreach ( $q_bid->posts as $bid ) {
					if ( $bid->post_author != $freelancer_id ) {
						$result = $wpdb->update( $wpdb->posts, array( 'post_status' => 'unaccept' ), array( 'ID' => $bid->ID ) );
					}
				}
			}
			$this->mail->bid_accepted( $freelancer_id, $project->ID );
			$this->mail->bid_accepted_alternative( $freelancer_id, $project->ID );

			return true;
		}

		return $result;
	}

	/*
	 * filter title bid in back-end
	*/
	function the_title_bid( $title, $bid_id = 0 ) {
		if ( is_admin() && is_post_type_archive( BID ) && get_post_field( 'post_type', $bid_id ) == 'bid' ) {

			$bid     = get_post( $bid_id );
			$author  = get_the_author_meta( 'display_name', $bid->post_author );
			$project = get_post( $bid->post_parent );

			if ( ! empty( $project ) ) {
				/*
				* should not use get_the_title in this hook
				 */
				$title = sprintf( __( '%s bid for project "%s"', ET_DOMAIN ), $author, $project->post_title );
			}
		}

		return $title;
	}

	function manage_bid_column_project( $columns ) {
		$column_thumbnail = array(
			'avatar' => 'Avatar'
		);
		$columns          = array_slice( $columns, 0, 1, true ) + $column_thumbnail + array_slice( $columns, 1, null, true );

		return array_merge( $columns, array(
			'project_title' => __( 'Project', ET_DOMAIN )
		) );
	}

	function project_title_column_render( $column, $bid_id ) {

		if ( $column == 'project_title' ) {

			$project_id = get_post_field( 'post_parent', $bid_id );
			echo '<a href="' . get_permalink( $project_id ) . '"> ' . get_the_title( $project_id ) . '</a>';
		}

		if ( $column == 'avatar' ) {
			echo get_avatar( get_post_field( 'post_author', $bid_id ), '50' );
		}
	}
}

/*
 * check an user has bid on a project yet?
 * int $project_id,
 * $user_id default NULL
*/
if ( ! function_exists( 'fre_has_bid' ) ):

	function fre_has_bid( $project_id, $user_id = false ) {
		global $wpdb;
		if ( ! $user_id ) {
			global $user_ID;
			$user_id = $user_ID;
		}
		$bided = $wpdb->get_row( "SELECT ID FROM $wpdb->posts WHERE post_author = $user_id AND post_type = '" . BID . "'  AND post_parent  = $project_id", ARRAY_N );
		if ( $bided ) {
			return (float) $bided[0];
		}

		return false;
	}
endif;
add_filter( 'ae_ppdigital_enqueue_script', 'fre_enqueue_more_script' );
/**
 * Enqueue more script if is buy credit page
 *
 * @param bool $rt
 *
 * @return bool $rt
 * @since 1.6.5
 * @package Freelanceengine
 * @category void
 * @author Tambh
 */
function fre_enqueue_more_script( $rt ) {
	if ( is_page_template( 'page-upgrade-account.php' ) ) {
		return true;
	}

	return $rt;
}

/**
 * use paypal adaptive to escrow
 *
 * @param void
 *
 * @return bool true if we use and false if we don't use
 * @since void
 * @package void
 * @category void
 * @author Tambh
 */
function use_paypal_to_escrow() {
	$flag = false;

	if ( ae_get_option( 'use_escrow' ) ) {
		$credit_api = ae_get_option( 'escrow_credit_settings' );
		$stripe_api = ae_get_option( 'escrow_stripe_api' );
		if ( isset( $stripe_api['use_stripe_escrow'] ) && $stripe_api['use_stripe_escrow'] ) {
			$flag = false;
		} elseif ( isset( $credit_api['use_credit_escrow'] ) && $credit_api['use_credit_escrow'] ) {
			$flag = false;
		} else {
			$flag = true;
		}
	}

	// return apply_filters('use_paypal_to_escrow', $flag);
	return $flag;
}
