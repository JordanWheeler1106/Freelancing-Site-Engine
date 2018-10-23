<?php

/**
 * Registers a new post type Project
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param string  Post type key, must not exceed 20 characters
 * @param array|string See optional args description above.
 *
 * @return object|WP_Error the registered post type object, or an error object
 */
function fre_register_project() {

	$labels = array(
		'name'               => __( 'Projects', ET_DOMAIN ),
		'singular_name'      => __( 'Project', ET_DOMAIN ),
		'add_new'            => _x( 'Add New project', ET_DOMAIN, ET_DOMAIN ),
		'add_new_item'       => __( 'Add New project', ET_DOMAIN ),
		'edit_item'          => __( 'Edit project', ET_DOMAIN ),
		'new_item'           => __( 'New project', ET_DOMAIN ),
		'view_item'          => __( 'View project', ET_DOMAIN ),
		'search_items'       => __( 'Search Projects', ET_DOMAIN ),
		'not_found'          => __( 'No Projects found', ET_DOMAIN ),
		'not_found_in_trash' => __( 'No Projects found in Trash', ET_DOMAIN ),
		'parent_item_colon'  => __( 'Parent project:', ET_DOMAIN ),
		'menu_name'          => __( 'Projects', ET_DOMAIN ),
	);

	$args = array(
		'labels'       => $labels,
		'hierarchical' => true,

		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => ae_get_option( 'fre_project_archive', 'projects' ),
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => array(
			'slug' => ae_get_option( 'fre_project_slug', 'project' )
		),
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'custom-fields',
			'trackbacks',
			'comments',
			//'revisions',
			'page-attributes',
			'post-formats'
		)
	);
	register_post_type( PROJECT, $args );

	/**
	 * Create a taxonomy project category
	 *
	 * @uses  Inserts new taxonomy project category  object into the list
	 */
	$labels = array(
		'name'                  => _x( 'Project Categories', 'Taxonomy plural name', ET_DOMAIN ),
		'singular_name'         => _x( 'Project Category', 'Taxonomy singular name', ET_DOMAIN ),
		'search_items'          => __( 'Search Project Categories', ET_DOMAIN ),
		'popular_items'         => __( 'Popular Project Categories', ET_DOMAIN ),
		'all_items'             => __( 'All Project Categories', ET_DOMAIN ),
		'parent_item'           => __( 'Parent Project Category', ET_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Project Category', ET_DOMAIN ),
		'edit_item'             => __( 'Edit Project Category', ET_DOMAIN ),
		'update_item'           => __( 'Update Project Category', ET_DOMAIN ),
		'add_new_item'          => __( 'Add New Project Category', ET_DOMAIN ),
		'new_item_name'         => __( 'New Project Category Name', ET_DOMAIN ),
		'add_or_remove_items'   => __( 'Add or remove Project Categories', ET_DOMAIN ),
		'choose_from_most_used' => __( 'Choose from most used enginetheme', ET_DOMAIN ),
		'menu_name'             => __( 'Project Category', ET_DOMAIN ),
	);

	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'show_tagcloud'     => true,
		'show_ui'           => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'         => ae_get_option( 'project_category_slug', 'project_category' ),
			'hierarchical' => ae_get_option( 'project_category_hierarchical', false )
		),
		'capabilities'      => array(
			'manage_terms',
			'edit_terms',
			'delete_terms',
			'assign_terms'
		)
	);

	register_taxonomy( 'project_category', array(
		PROJECT,
		PROFILE
	), $args );

	/**
	 * Create a taxonomy project category
	 *
	 * @uses  Inserts new taxonomy project category  object into the list
	 */

	$labels = array(
		'name'                  => _x( 'Project Types', 'Taxonomy plural name', ET_DOMAIN ),
		'singular_name'         => _x( 'Project Type', 'Taxonomy singular name', ET_DOMAIN ),
		'search_items'          => __( 'Search Project Types', ET_DOMAIN ),
		'popular_items'         => __( 'Popular Project Types', ET_DOMAIN ),
		'all_items'             => __( 'All Project Types', ET_DOMAIN ),
		'parent_item'           => __( 'Parent Project Type', ET_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Project Type', ET_DOMAIN ),
		'edit_item'             => __( 'Edit Project Type', ET_DOMAIN ),
		'update_item'           => __( 'Update Project Type', ET_DOMAIN ),
		'add_new_item'          => __( 'Add New Project Type', ET_DOMAIN ),
		'new_item_name'         => __( 'New Project Type Name', ET_DOMAIN ),
		'add_or_remove_items'   => __( 'Add or remove Project Types', ET_DOMAIN ),
		'choose_from_most_used' => __( 'Choose from most used enginetheme', ET_DOMAIN ),
		'menu_name'             => __( 'Project Type', ET_DOMAIN ),
	);

	$args = array(
		'labels'            => $labels,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'show_tagcloud'     => true,
		'show_ui'           => true,
		'query_var'         => true,
		'rewrite'           => array(
			'slug'         => ae_get_option( 'project_type_slug', 'project_type' ),
			'hierarchical' => false
		),
		'query_var'         => true,
		'capabilities'      => array(
			'manage_terms',
			'edit_terms',
			'delete_terms',
			'assign_terms'
		)
	);

	register_taxonomy( 'project_type', array(
		PROJECT
	), $args );

	global $ae_post_factory;
	$project_tax = array(
		'skill',
		'project_category',
		'project_type',
		'country'
	);

	$project_meta = array(
		'et_budget',
		'et_expired_date',

		// 'bid_type',
		// 'hour_rate',
		// 'hours_works',
		'deadline',
		'total_bids',

		// tb cong tong so bid
		'bid_average',

		// accepted bid id
		'accepted',

		// project_deadline
		'project_deadline',

		// payment data
		'et_payment_package',

		// count post view, this field should not be updated by author
		'post_views'
	);

	$ae_post_factory->set( PROJECT, new AE_Posts( PROJECT, $project_tax, $project_meta ) );
}

add_action( 'init', 'fre_register_project', 1 );

/**
 * register post type bid.
 */
class Fre_ProjectAction extends AE_PostAction {
	function __construct( $post_type = 'project' ) {
		$this->mail         = new Fre_Mailing();
		$this->post_type    = PROJECT;
		$this->disable_plan = ae_get_option( 'disable_plan', false );
		$this->add_ajax( 'ae-fetch-projects', 'fetch_post' );
		$this->add_ajax( 'ae-project-sync', 'post_sync' );
		$this->add_ajax( 'ae-project-action', 'project_action' );
		$this->add_ajax( 'lock_upload_file', 'fre_lock_upload_file' );
		$this->add_ajax( 'get_lock_file_status', 'fre_get_lock_file_status' );
		$this->add_ajax( 'fre_get_skills', 'fre_get_skills' );
		$this->add_filter( 'ae_convert_project', 'ae_convert_project' );

		/**
		 * catch wp head check cookie and set post views
		 * # update post views
		 */
		$this->add_action( 'template_redirect', 'update_post_views' );

		$this->add_filter( 'ae_pre_update_project', 'add_project_type' );
		$this->add_filter( 'ae_pre_insert_project', 'add_project_type' );

		$this->add_action( 'delete_post', 'fre_after_delete_project', 12, 1 );

		/**
		 * catch ad change status event, update expired date
		 */
		$this->add_action( 'transition_post_status', 'change_post_status', 10, 3 );

		/**
		 * add action publish ad, update ad order and related ad in a package
		 */
		$this->add_action( 'ae_publish_post', 'publish_post_action' );

		$this->add_action( 'ae_after_update_order', 'mail_after_update_order', 10, 3 );
		$this->add_action( 'ae_after_change_status_publish', 'mail_after_change_status' );
		if ( is_super_admin() || ! ae_get_option( 'use_pending', false ) ) {
			$this->add_action( 'ae_after_process_payment_by_admin', 'mail_after_process_payment_by_admin' );
		}
		$this->add_action( 'ae_process_payment_action', 'mail_after_payment_free', 10, 3 );
		$this->add_filter( 'ae_reject_post_message', 'replace_mail_reject_post', 10, 2 );
	}

	/**
	 * replace [Dashboard] of template mail Reject post
	 * @author ThanhTu
	 *
	 * @param $message
	 * @param $data
	 *
	 * @return String
	 */
	public function replace_mail_reject_post( $message, $data ) {
		$linkProfile = et_get_page_link( "profile" ) . '#tab_project_details';
		$string      = "<a href='" . $linkProfile . "' target='_Blank' rel='noopener noreferrer'>" . __( 'Dashboard', ET_DOMAIN ) . "</a>";
		$message     = str_replace( '[dashboard]', $string, $message );

		return $message;
	}

	// Payment Free
	public function mail_after_payment_free( $payment_return, $data ) {
		if ( $payment_return['ACK'] == true && isset( $payment_return['payment_type'] ) && $payment_return['payment_type'] == 'free' ) {
			$ID = $data['ad_id'];
			global $ae_post_factory;
			$project_obj = $ae_post_factory->get( 'project' );
			$post        = get_post( $ID );
			$convert     = $project_obj->convert( $post );
			if ( $convert->post_status == 'publish' ) {
				$this->mail->new_project_of_category( $convert );
			}
		}
	}

	public function mail_after_update_order( $order_id ) {
		global $ae_post_factory;
		$request      = $_REQUEST;
		$order_status = $request['status'];
		$order        = get_post( $order_id );
		if ( $order_status == 'publish' ) {
			if ( $order->post_parent ) {
				$post = get_post( $order->post_parent );
				if ( ! empty( $post ) ) {
					$project_obj = $ae_post_factory->get( 'project' );
					$convert     = $project_obj->convert( $post );
					$this->mail->new_project_of_category( $convert );
				}
			} else {
				// This function will auto update user's credits after admin approved cash payment
				$meta  = get_post_meta( $order_id );
				$packs = AE_Package::get_instance();
				$sku   = $meta['et_order_plan_id'][0];
				$pack  = $packs->get_pack( $sku, 'bid_plan' );

				if ( isset( $pack->et_number_posts ) && (int) $pack->et_number_posts > 0 ) {
					update_credit_number( $order->post_author, (int) $pack->et_number_posts );
					update_credit_number_pending( $order->post_author, - (int) $pack->et_number_posts );
					// send mail admin approved payment
					$this->mail->approved_payment_notification( $order_id, $pack );
				}
			}
		} elseif ( $order_status == 'draft' ) {
			if ( ! $order->post_parent ) {
				$meta  = get_post_meta( $order_id );
				$packs = AE_Package::get_instance();
				$sku   = $meta['et_order_plan_id'][0];
				$pack  = $packs->get_pack( $sku, 'bid_plan' );
				if ( isset( $pack->et_number_posts ) && (int) $pack->et_number_posts > 0 ) {
					update_credit_number_pending( $order->post_author, - (int) $pack->et_number_posts );
				}
			}
		}
	}

	public function mail_after_process_payment_by_admin( $id ) {
		global $ae_post_factory;
		$request = $_REQUEST;
		if ( empty( $id ) ) {
			return;
		}

		$post        = get_post( $id );
		$project_obj = $ae_post_factory->get( 'project' );
		$convert     = $project_obj->convert( $post );
		if ( $convert->post_status == 'publish' && ( is_super_admin() || ! ae_get_option( 'use_pending', false ) ) ) {
			$this->mail->new_project_of_category( $convert );
		}
	}

	public function mail_after_change_status( $post ) {
		global $ae_post_factory;
		$request     = $_REQUEST;
		$project_obj = $ae_post_factory->get( 'project' );
		$convert     = $project_obj->convert( $post );
		if ( isset( $request['method'] ) && $request['method'] == 'update' && $post->post_status == 'publish' ) {
			$this->mail->new_project_of_category( $convert );
		}
	}

	/**
	 * update post views
	 */
	public function update_post_views() {
		if ( is_singular( $this->post_type ) ) {
			global $post;
			$views = get_post_meta( $post->ID, 'post_views', true );
			if ( $post->post_status == 'publish' ) {
				$cookie = 'cookie_' . $post->ID . '_visited';
				if ( ! isset( $_COOKIE[ $cookie ] ) ) {
					update_post_meta( $post->ID, 'post_views', (int) $views + 1 );
					setcookie( $cookie, 'is_visited', time() + 3 * 3600 );
				}
			}
		}
	}

	/**
	 * Override filter_query_args for action fetch_post.
	 */
	public function filter_query_args( $query_args ) {
		global $user_ID;
		$query = $_REQUEST['query'];

		if ( isset( $_REQUEST['query']['skill'] ) ) {
			if ( isset( $query['skill'] ) && $query['skill'] != '' ) {

				//$query_args['skill_slug__and'] = $query['skill'];
				$query_args['tax_query'] = array(
					'skill' => array(
						'taxonomy' => 'skill',
						'terms'    => $query['skill'],
						'field'    => 'slug'
					)
				);
			}
		}
		if ( isset( $query['country'] ) && $query['country'] != '' ) {
			$query_args['tax_query'] = array(
				'country' => array(
					'taxonomy' => 'country',
					'terms'    => $query['country'],
					'field'    => 'slug'
				)
			);
		}
		if ( isset( $query['number_bids'] ) && $query['number_bids'] ) {
			$number_bids = $query['number_bids'];
			$number_bids = explode( ",", $number_bids );
			if ( $number_bids[0] > 30 ) {
				$query_args['meta_query'] = array(
					array(
						'key'     => 'total_bids',
						'value'   => $number_bids[0],
						'type'    => 'numeric',
						'compare' => '>='
					)
				);
			} else {
				$query_args['meta_query'] = array(
					array(
						'key'     => 'total_bids',
						'value'   => array(
							(int) $number_bids[0],
							(int) $number_bids[1]
						),
						'type'    => 'numeric',
						'compare' => 'BETWEEN'
					)
				);
				if ( $number_bids[0] == 0 ) {
					$query_args['meta_query'] = array(
						'relation' => 'OR',
						array(
							'key'     => 'total_bids',
							'compare' => 'NOT EXISTS'
						),
						array(
							'key'     => 'total_bids',
							'value'   => array(
								(int) $number_bids[0],
								(int) $number_bids[1]
							),
							'type'    => 'numeric',
							'compare' => 'BETWEEN'
						)
					);
				}
			}
		}
		// list featured profile
		if ( isset( $query['meta_key'] ) ) {
			$query_args['meta_key'] = $query['meta_key'];
			if ( isset( $query['meta_value'] ) ) {
				$query_args['meta_value'] = $query['meta_value'];
			}
		}

		// // filter project by project category
		if ( isset( $query['project_category'] ) && $query['project_category'] != '' ) {
			$query_args['project_category'] = $query['project_category'];
		}

		// query project by project type
		if ( isset( $query['project_type'] ) && $query['project_type'] != '' ) {
			$query_args['project_type'] = $query['project_type'];
		}

		// filter project by budget
		if ( isset( $query['et_budget'] ) && ! empty( $query['et_budget'] ) ) {
			$budget = $query['et_budget'];
			$budget = explode( ",", $budget );
			if ( (int) $budget[0] == (int) $budget[1] ) {
				$query_args['meta_query'] = array(
					array(
						'key'   => 'et_budget',
						'value' => (int) $budget[0],
						'type'  => 'numeric'
					)
				);
			} else {
				$query_args['meta_query'] = array(
					array(
						'key'     => 'et_budget',
						'value'   => array(
							(int) $budget[0],
							(int) $budget[1]
						),
						'type'    => 'numeric',
						'compare' => 'BETWEEN'
					)
				);
			}
		}

		// project posted from query date
		if ( isset( $query['date'] ) ) {
			$date                     = $query['date'];
			$day                      = date( 'd', strtotime( $date ) );
			$mon                      = date( 'm', strtotime( $date ) );
			$year                     = date( 'Y', strtotime( $date ) );
			$query_args['date_query'] = array(
				array(
					'year'      => $year,
					'month'     => $mon,
					'day'       => $day,
					'inclusive' => true
				)
			);
		}

		/**
		 * add query when archive project type
		 */
		if ( current_user_can( 'manage_options' ) && isset( $query['is_archive_project'] ) && $query['is_archive_project'] == true ) {
			$query_args['post_status'] = array(
				// 'pending',
				'publish'
			);
		}

		// query arg for filter page default
		if ( isset( $query['orderby'] ) ) {
			$orderby = $query['orderby'];
			switch ( $orderby ) {
				case 'et_featured':
					$query_args['meta_key']   = $orderby;
					$query_args['orderby']    = 'meta_value_num date';
					$query_args['meta_query'] = array(
						'relation' => 'OR',
						array(
							//check to see if et_featured has been filled out
							'key'     => $orderby,
							'compare' => 'IN',
							'value'   => array(
								0,
								1
							)
						),
						array(
							//if no et_featured has been added show these posts too
							'key'     => $orderby,
							'value'   => 0,
							'compare' => 'NOT EXISTS'
						)
					);
					break;

				case 'et_budget':
					$query_args['meta_key'] = 'et_budget';
					$query_args['orderby']  = 'meta_value_num date';
					break;
				case 'date':
					$query_args['orderby'] = 'date';
					$query_args['order']   = 'DESC';
					break;
				default:
					// add_filter('posts_orderby', array(
					//     'ET_FreelanceEngine',
					//     'order_by_post_pending'
					// ) , 2, 12);
					break;
			}
		}

		if ( isset( $query['is_profile'] ) && $query['is_profile'] == 'true' ) {
			// Args post_status in page profile
			if ( ! isset( $query['post_status'] ) || $query['post_status'] == '' ) {
				$query_args['post_status'] = array(
					'pending',
					'publish',
					'close',
					'complete',
					'disputing',
					'disputed',
					'archive',
					'reject',
					'draft'
				);
			} else {
				$query_args['post_status'] = $query['post_status'];
			}
			$query_args['author'] = $query['author'];
			//add_filter( 'posts_orderby', 'fre_order_by_project_status' );
		}

		if ( isset( $query['post_status'] ) && isset( $query['author'] ) && $query['post_status'] && $user_ID == $query['author'] ) {
			$query_args['post_status'] = $query['post_status'];
		}

		/*
		 * set post status when query in page author
		*/

		if ( isset( $query['is_author'] ) && $query['is_author'] == 'true' ) {
			if ( ! isset( $query['post_status'] ) || $query['post_status'] == '' ) {
				$query_args['post_status'] = array(
					// 'close',
					'complete',
					'publish'
				);
			} else {
				$query_args['post_status'] = $query['post_status'];
			}
			$query_args['author'] = $query['author'];
		}

		/**
		 * Query order of block projects
		 */
		if ( isset( $query['is_block'] ) && $query['is_block'] == 'projects' ) {
			if ( $query['orderby'] == 'order_date' ) {
				$query_args['order']   = isset( $query['order'] ) ? $query['order'] : 'DESC';
				$query_args['orderby'] = 'date';
				unset( $query_args['meta_key'] );
			}
			if ( $query['orderby'] == 'order_budget' ) {
				$query_args['order']    = isset( $query['order'] ) ? $query['order'] : 'DESC';
				$query_args['orderby']  = 'meta_value_num date';
				$query_args['meta_key'] = 'et_budget';
			}
			$query_args['is_block'] = 'projects';
		}

		return apply_filters( 'fre_project_query_args', $query_args, $query );
	}


	function project_action() {
		global $post, $user_ID;
		if ( ! AE_Users::is_activate( $user_ID ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "Your account is pending. You have to activate your account to continue this step.", ET_DOMAIN )
			) );
		};
		$request    = $_REQUEST;
		$project_id = $request['ID'];
		$method     = isset( $request['method'] ) ? $request['method'] : '';
		if ( $method == 'archive' ) {
			if ( isset( $project_id ) && $project_id != '' ) {
				$result  = wp_update_post( array(
					'ID'          => $project_id,
					'post_status' => 'archive'
				) );
				$project = get_post( $result, ARRAY_A );
				if ( is_wp_error( $result ) ) {
					wp_send_json( array(
						'success' => false,
						'msg'     => $result->get_error_message()
					) );
				} else {
					//update bid status
					$bids_post = get_children(
						array(
							'post_parent' => $request['ID'],
							'post_type'   => BID,
							'numberposts' => - 1,
							'post_status' => 'any'
						)
					);

					if ( ! empty( $bids_post ) ) {
						foreach ( $bids_post as $bid ) {
							wp_update_post(
								array(
									'ID'          => $bid->ID,
									'post_status' => 'archive'
								)
							);
						}
					}
					$message = __( "Update project successful!", ET_DOMAIN );
					do_action( 'fre_archive_post', $project );
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
		} else if ( $method == 'delete' ) {
			if ( isset( $project_id ) && $project_id != '' ) {
				$project = get_post( $project_id, ARRAY_A );
				$result  = wp_delete_post( $project_id );
				if ( is_wp_error( $result ) ) {
					wp_send_json( array(
						'success' => false,
						'msg'     => $result->get_error_message()
					) );
				} else {
					//update bid status
					$bids_post = get_children(
						array(
							'post_parent' => $request['ID'],
							'post_type'   => BID,
							'numberposts' => - 1,
							'post_status' => 'any'
						)
					);

					if ( ! empty( $bids_post ) ) {
						foreach ( $bids_post as $bid ) {
							wp_delete_post( $bid->ID );
						}
					}

					$message = __( "Delete project successful!", ET_DOMAIN );
					do_action( 'fre_delete_post', $project );
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
		} else if ( $method == 'approve' ) {
			if ( isset( $project_id ) && $project_id != '' ) {
				$result  = wp_update_post( array(
					'ID'          => $project_id,
					'post_status' => 'publish'
				) );
				$project = get_post( $result, ARRAY_A );
				if ( is_wp_error( $result ) ) {
					wp_send_json( array(
						'success' => false,
						'msg'     => $result->get_error_message()
					) );
				} else {
					$permalink = get_permalink( $result );
					$message   = __( "Update project successful!", ET_DOMAIN );
					do_action( 'fre_publish_post', $project );
					wp_send_json( array(
						'success'   => true,
						'msg'       => $message,
						'permalink' => $permalink
					) );
				}
			} else {
				$message = __( "Request failed. Please refresh the page and try again", ET_DOMAIN );
				wp_send_json( array(
					'success' => false,
					'msg'     => $message,
				) );
			}
		} else if ( $method == 'reject' ) {
			if ( ( isset( $project_id ) && $project_id != '' ) && ( isset( $request['reject_message'] ) && $request['reject_message'] != '' ) ) {
				$result                    = wp_update_post( array(
					'ID'          => $project_id,
					'post_status' => 'reject'
				) );
				$project                   = get_post( $result, ARRAY_A );
				$project['reject_message'] = $request['reject_message'];
				if ( is_wp_error( $result ) ) {
					wp_send_json( array(
						'success' => false,
						'msg'     => $result->get_error_message()
					) );
				} else {
					$permalink = get_permalink( $result );
					$message   = __( "Update project successful!", ET_DOMAIN );
					do_action( 'ae_reject_post', $project );
					wp_send_json( array(
						'success'   => true,
						'msg'       => $message,
						'permalink' => $permalink
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
	}

	function fre_lock_upload_file() {
		global $post, $user_ID;
		if ( ! AE_Users::is_activate( $user_ID ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "Your account is pending. You have to activate your account to continue this step.", ET_DOMAIN )
			) );
		};
		$request    = $_REQUEST;
		$project_id = $request['project_id'];
		$type       = $request['type'];

		if ( $project_id ) {
			if ( $type == 'lock' ) {
				$result = update_post_meta( $project_id, 'lock_file', 'lock' );
				if ( is_wp_error( $result ) ) {
					wp_send_json( array(
						'success' => false,
						'msg'     => $result->get_error_message()
					) );
				} else {
					$message = __( "Project files locked", ET_DOMAIN );
					do_action( 'ae_lock_upload_file', $project_id );
					wp_send_json( array(
						'success' => true,
						'msg'     => $message
					) );
				}
			} else if ( $type == 'unlock' ) {
				$result = update_post_meta( $project_id, 'lock_file', 'unlock' );
				if ( is_wp_error( $result ) ) {
					wp_send_json( array(
						'success' => false,
						'msg'     => $result->get_error_message()
					) );
				} else {
					$message = __( "Project files unlocked", ET_DOMAIN );
					do_action( 'ae_unlock_upload_file', $project_id );
					wp_send_json( array(
						'success' => true,
						'msg'     => $message
					) );
				}
			}
		} else {
			$message = __( "Request failed. Please refresh the page and try again", ET_DOMAIN );
			wp_send_json( array(
				'success' => false,
				'msg'     => $message,
			) );
		}
	}

	function fre_get_lock_file_status() {
		global $post, $user_ID;
		if ( ! AE_Users::is_activate( $user_ID ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "Your account is pending. You have to activate your account to continue this step.", ET_DOMAIN )
			) );
		};
		$request    = $_REQUEST;
		$project_id = $request['project_id'];
		if ( $project_id ) {
			$result = get_post_meta( $project_id, 'lock_file', true );
			wp_send_json( array(
				'status' => $result,
			) );
		}
	}

	/**
	 * ajax callback sync post details
	 * - update
	 * - insert
	 * - delete
	 */
	function post_sync() {
		$request = $_REQUEST;
		global $ae_post_factory, $user_ID;

		if ( ! AE_Users::is_activate( $user_ID ) ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "Your account is pending. You have to activate your account to continue this step.", ET_DOMAIN )
			) );
		};

		// prevent freelancer submit project
		if ( ! fre_share_role() && ae_user_role() == FREELANCER ) {
			wp_send_json( array(
				'success' => false,
				'msg'     => __( "You need an employer account to post a project.", ET_DOMAIN )
			) );
		}
		// unset package data when edit place if user can edit others post
		if ( ( ! isset( $request['is_submit_project'] ) || $request['is_submit_project'] != 1 ) && isset( $request['ID'] ) && ! isset( $request['renew'] ) ) {
			unset( $request['et_payment_package'] );
		}

		if ( isset( $request['archive'] ) ) {
			$request['post_status'] = 'archive';
		}
		if ( isset( $request['publish'] ) ) {
			$request['post_status'] = 'publish';
		}
		if ( isset( $request['delete'] ) ) {
			$request['post_status'] = 'trash';
		}
		if ( isset( $request['disputed'] ) ) {
			$request['post_status'] = 'disputed';
		}

		if ( isset( $request['project_type'] ) ) {
			unset( $request['project_type'] );
		}

		$place = $ae_post_factory->get( $this->post_type );

		// sync place
		$result = $place->sync( $request );

		if ( ! is_wp_error( $result ) ) {
			$post_status = isset( $request['post_status'] ) ? $request['post_status'] : '';
			//update bid status
			if ( $post_status == 'archive' ) {
				$bids_post = get_children(
					array(
						'post_parent' => $request['ID'],
						'post_type'   => BID,
						'numberposts' => - 1,
						'post_status' => 'any'
					)
				);

				if ( ! empty( $bids_post ) ) {
					foreach ( $bids_post as $bid ) {
						wp_update_post(
							array(
								'ID'          => $bid->ID,
								'post_status' => $post_status
							)
						);
					}
				}
			}

			// update place carousels
			if ( isset( $request['et_carousels'] ) ) {

				// loop request carousel id
				foreach ( $request['et_carousels'] as $key => $value ) {
					$att = get_post( $value );

					// just admin and the owner can add carousel
					if ( current_user_can( 'manage_options' ) || $att->post_author == $user_ID ) {
						wp_update_post( array(
							'ID'          => $value,
							'post_parent' => $result->ID
						) );
					}
				}
			}
			/**
			 * check payment package and check free or use package to send redirect link
			 */
			if ( isset( $request['et_payment_package'] ) ) {

				// check seller use package or not
				$check = AE_Package::package_or_free( $request['et_payment_package'], $result );

				// check use package or free to return url
				if ( $check['success'] ) {
					$result->redirect_url = $check['url'];
				}

				$result->response = $check;

				// check seller have reached limit free plan
				$check = AE_Package::limit_free_plan( $request['et_payment_package'] );
				if ( $check['success'] ) {

					// false user have reached maximum free plan
					$response['success'] = false;
					$response['msg']     = $check['msg'];

					// send response to client
					wp_send_json( $response );
				}
			}
			if ( $this->disable_plan && $request['method'] == 'update' && isset( $request['renew'] ) ) {
				// disable plan, free to post place
				$response = array(
					'success' => true,
					'data'    => array(
						// set redirect url
						'redirect_url' => $result->permalink
					),
					'msg'     => __( "Submit project successfull.", ET_DOMAIN )
				);
				wp_send_json( $response );
			}
			if ( $request['method'] == 'update' && isset( $request['renew'] ) ) {
				$bids_post = get_children(
					array(
						'post_parent' => $request['ID'],
						'post_type'   => BID,
						'numberposts' => - 1,
						'post_status' => 'any'
					)
				);

				if ( ! empty( $bids_post ) ) {
					foreach ( $bids_post as $bid ) {
						wp_delete_post( $bid->ID );
					}
				}
			}
			if ( $request['method'] == 'create' ) {
				update_post_meta( $result->ID, 'total_bids', 0 );
			}
			/*
			 * check disable plan and submit place to view details
			 */
			if ( $this->disable_plan && $request['method'] == 'create' ) {

				// disable plan, free to post place
				$response = array(
					'success' => true,
					'data'    => array(
						// set redirect url
						'redirect_url' => $result->permalink
					),
					'msg'     => __( "Submit project successfull.", ET_DOMAIN )
				);

				// Send to freelancers when a new project which related to his profile category is posted.
				if ( $result->post_status == 'publish' ) {
					global $ae_post_factory;
					$project_obj = $ae_post_factory->get( 'project' );
					$post        = get_post( $result->ID );
					$convert     = $project_obj->convert( $post );
					$this->mail->new_project_of_category( $convert );
				}
				// send mail have a new post on site when enable option "Free a submit listing"
				if ( $result->post_status == 'pending' ) {
					$ae_mailing = AE_Mailing::get_instance();
					$ae_mailing->new_post_alert( $result->ID );
				}
				// send response
				wp_send_json( $response );
			}
			// send json data to client
			wp_send_json( array(
				'success' => true,
				'data'    => $result,
				'msg'     => __( "Update project successful!", ET_DOMAIN )
			) );
		} else {

			// update false
			wp_send_json( array(
				'success' => false,
				'data'    => $result,
				'msg'     => $result->get_error_message()
			) );
		}
	}

	/**
	 * Get skill
	 */
	public function fre_get_skills() {
		$terms = get_terms( 'skill', array(
			'hide_empty' => 0,
			'fields'     => 'names'
		) );
		wp_send_json( $terms );
	}

	/**
	 *Convert project
	 *
	 *
	 */
	function ae_convert_project( $result ) {
		global $user_ID;
		if ( $result->accepted != '' ) {
			$bid_post = get_post( $result->accepted );
			if ( $bid_post->type_time == 'day' ) {
				if ( $bid_post->bid_time > 1 ) {
					$result->bid_won_date = sprintf( __( "in %d days", ET_DOMAIN ), $bid_post->bid_time );
				} else {
					$result->bid_won_date = sprintf( __( "in %d day", ET_DOMAIN ), $bid_post->bid_time );
				}
			} else {
				if ( $bid_post->bid_time > 1 ) {
					$result->bid_won_date = sprintf( __( "in %d weeks", ET_DOMAIN ), $bid_post->bid_time );
				} else {
					$result->bid_won_date = sprintf( __( "in %d week", ET_DOMAIN ), $bid_post->bid_time );
				}
			}
		}

		$winner_of_arbitrate = get_post_meta( $result->ID, 'winner_of_arbitrate', true );
		if ( $winner_of_arbitrate ) {
			$result->win_disputed = $winner_of_arbitrate;
		}

		$result->et_avatar       = get_avatar( $result->post_author, 35 );
		$result->author_url      = get_author_posts_url( $result->post_author );
		$result->author_name     = get_the_author_meta( 'display_name', $result->post_author );
		$result->budget          = fre_price_format( $result->et_budget );
		$result->bid_budget_text = fre_price_format( get_post_meta( $result->accepted, 'bid_budget', true ) );

		$result->rating_score = (float) get_post_meta( $result->ID, 'rating_score', true );

		$comment = get_comments( array(
			'post_id' => $result->ID,
			'type'    => 'fre_review'
		) );
		if ( $comment ) {
			$result->project_comment = wpautop( $comment['0']->comment_content );
		} else {
			$result->project_comment = '';
		}
		$result->project_comment_trim = wp_trim_words( $result->project_comment, $num_words = 18 );
		$result->post_content_trim    = wp_trim_words( $result->post_content, $num_words = 30 );
		$result->count_word           = str_word_count( strip_tags( $result->project_comment ) );
		$status_arr                   = array(
			'close'     => __( "Processing", ET_DOMAIN ),
			'publish'   => __( "Active", ET_DOMAIN ),
			'archive'   => __( "Archived", ET_DOMAIN ),
			'disputing' => __( "Disputed", ET_DOMAIN ),
			'pending'   => __( "Pending", ET_DOMAIN ),
			'draft'     => __( "Draft", ET_DOMAIN ),
			'reject'    => __( "Rejected", ET_DOMAIN ),
			'complete'  => __( "Completed", ET_DOMAIN ),
			'disputed'  => __( "Resolved", ET_DOMAIN )
		);
		$result->project_status_view  = $status_arr[ $result->post_status ];

		// project is disputing
		if ( $result->post_status == 'disputing' ) {
			$result->status_text = __( "DISPUTED", ET_DOMAIN );
		}

		//project is diputed
		if ( $result->post_status == 'disputed' ) {
			$result->status_text = __( "RESOLVED", ET_DOMAIN );
		}

		// project completed text status
		if ( $result->post_status == 'complete' ) {
			$result->status_text = __( "COMPLETED", ET_DOMAIN );
		}

		$result->text_status_js = sprintf( __( 'Job is %s', ET_DOMAIN ), strtolower( $result->status_text ) );
		// project close for working when accepted a bids
		if ( $result->post_status == 'close' ) {
			$result->status_text = __( "PROCESSING", ET_DOMAIN );
			if ( $user_ID == $result->post_author ) {
				$result->workspace_link = add_query_arg( array(
					'workspace' => 1
				), $result->permalink );
			}
		}
		$avg = 0;
		if ( $result->total_bids == '' ) {
			$result->total_bids = 0;
		}
		if ( $result->total_bids > 0 ) {
			$avg = get_total_cost_bids( $result->ID ) / $result->total_bids;
		}
		$result->bid_average = $avg;

		if ( $result->post_views == '' ) {
			$result->post_views = 0;
		}
		/**
		 * return carousels
		 */
		if ( current_user_can( 'manage_options' ) || $result->post_author == $user_ID ) {
			$children = get_children( array(
				'numberposts' => 15,
				'order'       => 'ASC',
				'post_parent' => $result->ID,
				'post_type'   => 'attachment'
			) );

			$result->et_carousels = array();

			foreach ( $children as $key => $value ) {
				$result->et_carousels[] = $key;
			}

			/**
			 * set post thumbnail in one of carousel if the post thumbnail doesnot exists
			 */
			if ( has_post_thumbnail( $result->ID ) ) {
				$thumbnail_id = get_post_thumbnail_id( $result->ID );
				if ( ! in_array( $thumbnail_id, $result->et_carousels ) ) {
					$result->et_carousels[] = $thumbnail_id;
				}
			}
		}
		$result->current_user_bid = current_user_bid( $result->ID );
		$result->posted_by        = sprintf( __( "Posted by %s", ET_DOMAIN ), $result->author_name );

		if ( isset( $result->tax_input['skill'] ) ) {
			unset( $result->skill );
			foreach ( $result->tax_input['skill'] as $key => $value ) {
				$result->skill[] = array( 'name' => $value->name );
			}
		}
		// render HTMl
		ob_start();
		if ( count( $result->tax_input['skill'] ) > 0 ) {
			echo '<div class="project-list-skill">';
			foreach ( $result->tax_input['skill'] as $key => $value ) {
				echo '<span class="fre-label"><a href="' . get_post_type_archive_link( PROJECT ) . '?skill_project=' . $value->slug . '">' . $value->name . '</a></span>';
			}
			echo '</div>';
		}
		$result->list_skills = ob_get_clean();

		if ( $result->total_bids > 0 ) {
			if ( $result->total_bids == 1 ) {
				$result->text_total_bid = sprintf( __( '%s Bid', ET_DOMAIN ), $result->total_bids );
			} else {
				$result->text_total_bid = sprintf( __( '%s Bids', ET_DOMAIN ), $result->total_bids );
			}
		} else {
			$result->text_total_bid = sprintf( __( '%s Bids', ET_DOMAIN ), $result->total_bids );
		}
		$result->text_country = '';
		if ( count( $result->tax_input['country'] ) > 0 ) {
			$country              = $result->tax_input['country'][0];
			$result->text_country = $country->name;
		}

		return $result;
	}

	/**
	 * Run sql to delete all bids on this project.
	 * int $project_id
	 */
	function fre_after_delete_project( $project_id ) {
		global $wpdb;
		$post_type = get_post_field( 'post_type', $project_id );
		if ( $post_type == PROJECT && current_user_can( 'delete_posts' ) ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->posts WHERE post_parent = %d AND post_type = %s ", $project_id, BID ) );
		}
	}

	/**
	 * hook to ae_update_project/ae_insert_project to add project type
	 *
	 * @param object $result Project object
	 *
	 * @since 1.0
	 * @author Dakachi
	 */
	function add_project_type( $args ) {

		/**
		 * checking old data
		 */
		if ( $args['method'] == 'update' ) {
			$prev_post = get_post( $args['ID'] );

			// get current status and compare to display msg.

			if ( $prev_post->post_status == 'reject' ) {

				// change post status to pending when edit rejected ad
				$args['post_status'] = 'pending';
			}

			global $ae_post_factory;
			$pack       = $ae_post_factory->get( 'pack' );
			$package_id = get_post_meta( $args['ID'], 'et_payment_package', true );
			$package    = $pack->get( $package_id );

			if ( isset( $package->project_type ) && $package->project_type ) {
				$project_type = get_term_by( 'id', $package->project_type, 'project_type' );
				if ( $project_type && ! is_wp_error( $project_type ) ) {
					$args['project_type'] = $project_type->term_id;
				}
			}
		}

		// add project type to param
		if ( isset( $args['et_payment_package'] ) ) {
			global $ae_post_factory;
			$pack    = $ae_post_factory->get( 'pack' );
			$package = $pack->get( $args['et_payment_package'] );

			if ( isset( $package->project_type ) && $package->project_type ) {
				$project_type = get_term_by( 'id', $package->project_type, 'project_type' );
				if ( $project_type && ! is_wp_error( $project_type ) ) {
					$args['project_type'] = $project_type->term_id;
				}
			}
		}

		return $this->validate_data( $args );
	}

	/**
	 * validate data
	 */
	public function validate_data( $data ) {
		global $user_ID;

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$require_fields = apply_filters( 'fre_project_required_fields', array(
			'et_budget',
			'project_category'
		) );

		if ( ! current_user_can( 'manage_options' ) ) {
			if ( isset( $data['renew'] ) && ! isset( $data['et_payment_package'] ) && $this->disable_plan ) {
				// if not disable package plan
				return new WP_Error( 'empty_package', __( "Cannot create a place with an empty package.", ET_DOMAIN ) );
			}

			if ( ! isset( $data['post_content'] ) || $data['post_content'] == '' ) {
				return new WP_Error( 'ad_empty_content', __( "You should enter short description for your place.", ET_DOMAIN ) );
			}

			if ( ! isset( $data['post_title'] ) || $data['post_title'] == '' ) {
				return new WP_Error( 'ad_empty_content', __( "Your place should have a title.", ET_DOMAIN ) );
			}

			if ( ! isset( $data['project_category'] ) && in_array( 'project_category', $require_fields ) && ! is_admin() ) {
				return new WP_Error( 'invalid_category', __( "Your project should has a category!", ET_DOMAIN ) );
			}

			if ( ! isset( $data['et_budget'] ) && in_array( 'et_budget', $require_fields ) ) {
				return new WP_Error( 'invalid_budget', __( "Your have to enter a budget for your requirement!", ET_DOMAIN ) );
			}
		}

		if ( in_array( 'et_budget', $require_fields ) && $data['et_budget'] <= 0 ) {
			return new WP_Error( 'budget_less_than_zero', __( "Your budget have to greater than zero!", ET_DOMAIN ) );
		}

		/**
		 * unsert featured et_featured param if user cannot  edit others posts
		 */
		if ( ! ae_user_can( 'edit_others_posts' ) ) {
			unset( $data['et_featured'] );

			// unset($data['post_status']);
			unset( $data['et_expired_date'] );
			unset( $data['post_views'] );
		}

		/**
		 * check payment package is valid or not
		 * set up featured if this package is featured
		 */
		if ( isset( $data['et_payment_package'] ) ) {

			/**
			 * check package plan exist or not
			 */
			global $ae_post_factory;
			$package = $ae_post_factory->get( 'pack' );
			$disable_plan = ae_get_option('disable_plan', false);
			$plan = $package->get( $data['et_payment_package'] );
			if ( !$disable_plan &&  ! $plan ) {
				return new WP_Error( 'invalid_plan', __( "You have selected an invalid plan.", ET_DOMAIN ) );
			}

			/**
			 * if user can not edit others posts the et_featured will no be unset and check,
			 * this situation should happen when user edit/add post in backend.
			 * Force to set featured post
			 */
			if ( ! isset( $data['et_featured'] ) || ! $data['et_featured'] ) {
				$data['et_featured'] = 0;
				if ( isset( $plan->et_featured ) && $plan->et_featured ) {
					$data['et_featured'] = 1;
				}
			}
		}

		/**
		 * check max category options, filter ad category
		 */
		$max_cat = ae_get_option( 'max_cat', 3 );
		if ( $max_cat && ! current_user_can( 'edit_others_posts' ) ) {

			/**
			 * check max category user can set for a place
			 */
			$num_of_cat = count( $data['project_category'] );
			if ( $max_cat < $num_of_cat ) {
				for ( $i = $max_cat; $i < $num_of_cat; $i ++ ) {
					unset( $data['place_category'][ $i ] );
				}
			}
		}

		return apply_filters( 'fre_project_validate_data', $data );
	}

	/**
	 * catch event change ad status, update expired date
	 */
	public function change_post_status( $new_status, $old_status, $post ) {

		// not is post type controled
		if ( $post->post_type != $this->post_type ) {
			return;
		}

		/**
		 * check post package data
		 */
		global $ae_post_factory;
		$pack = $ae_post_factory->get( 'pack' );

		$sku     = get_post_meta( $post->ID, 'et_payment_package', true );
		$package = $pack->get( $sku );

		$old_expiration = get_post_meta( $post->ID, 'et_expired_date', true );

		/**
		 * if an ad didnt have a package, force publish
		 */
		if ( ! $package || is_wp_error( $package ) ) {
			if ( $new_status == 'publish' ) {
				do_action( 'ae_publish_post', $post->ID );
			}
			$this->mail->change_status( $new_status, $old_status, $post );

			return false;
		};

		// if isset duration
		if ( isset( $package->et_duration ) && ! empty( $package->et_duration ) ) {
			$duration = (int) $package->et_duration;
			if ( $new_status == 'pending' ) {

				// clear ad expired date and post view when change from archive to pending
				if ( $old_status == "archive" || $old_status == "draft" ) {

					// force update expired date if job is change from draft or archive to publish
					$expired_date = date( 'Y-m-d H:i:s', strtotime( "+{$duration} days" ) );

					/**
					 * reset post expired date
					 */
					update_post_meta( $post->ID, 'et_expired_date', '' );

					/**
					 * reset post view
					 */
					update_post_meta( $post->ID, 'post_view', 0 );

					/**
					 * change post date
					 */
					wp_update_post( array(
						'ID'        => $post->ID,
						'post_date' => ''
					) );
				}
			} elseif ( $new_status == 'publish' ) {

				// update post expired date when publish
				if ( $old_status == "archive" || $old_status == "draft" ) {

					// force update expired date if job is change from draft or archive to publish

					$expired_date = date( 'Y-m-d H:i:s', strtotime( "+{$duration} days" ) );
					update_post_meta( $post->ID, 'et_expired_date', $expired_date );
				} else {

					// update expired date when the expired date less then current time
					if ( empty( $old_expiration ) || current_time( 'timestamp' ) > strtotime( $old_expiration ) ) {
						$expired_date = date( 'Y-m-d H:i:s', strtotime( "+{$duration} days" ) );
						update_post_meta( $post->ID, 'et_expired_date', $expired_date );
						// echo get_post_meta( $post->ID, 'et_expired_date' , true );
					}
				}
			}
		}
		// Send mail New Post when resubmit a project have post status is Reject
		if ( $new_status == 'pending' && $old_status == 'reject' ) {
			$this->mail->review_resubmmit_mail( $post->ID );
		}
		// Send to freelancers when a new project which related to his profile category is posted.
		if ( $new_status == 'publish' && $old_status == 'pending' ) {
			global $ae_post_factory;
			$project_obj = $ae_post_factory->get( 'project' );
			$convert     = $project_obj->convert( $post );

			if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'editpost' && $convert->post_status == 'publish' ) {
				$this->mail->new_project_of_category( $convert );
			}
		}

		if ( $new_status == 'publish' && ( $old_status == 'pending' || $old_status == "archive" || $old_status == "draft" || $old_status == "reject" ) ) {
			$time       = current_time( 'mysql' );
			$post_query = array(
				'ID'            => $post->ID,
				'post_date'     => $time,
				'post_date_gmt' => get_gmt_from_date( $time )
			);
			// Update the post_date into the database
			wp_update_post( $post_query );
		}

		/*if ( $new_status == 'publish' ) {
			do_action( 'ae_publish_post', $post->ID );
		}*/

		/**
		 * send mail when change ad status
		 */
		$this->mail->change_status( $new_status, $old_status, $post );
	}
}

/**
 * get number bid of a project
 *
 * @param  int $project_id : project id
 * @param  string $post_type : post type of project id;
 *
 * @return int number bidding on this project.
 */
function get_number_bids( $project_id, $post_type = 'bid' ) {
	global $wpdb;
	$count_bid = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*)
                                                    FROM  $wpdb->posts
                                                    WHERE post_type =%s
                                                        and (post_status = 'publish'
                                                            or post_status = 'complete'
                                                            or post_status = 'accept'
                                                            or post_status = 'unaccept')
                                                        and post_parent = %d", $post_type, $project_id ) );

	return (int) $count_bid;
}

/**
 * return sum of bid bud get on a project.
 *
 * @param  int $project_id : project id
 * @param  string $meta_key : the metakey to sum it.
 *
 * @return sum of bid budget on this project.
 */
function get_total_cost_bids( $project_id, $meta_key = 'bid_budget' ) {
	global $wpdb;
	$sql   = "SELECT sum(meta_value)
                FROM $wpdb->postmeta pm, $wpdb->posts p
                WHERE   p.ID = pm.post_id
                        AND p.post_type ='bid'
                        AND pm.meta_key = %s
                        AND p.post_parent =%d ";
	$total = $wpdb->get_var( $wpdb->prepare( $sql, $meta_key, $project_id ) );

	return $total;
}

/**
 * Check if user bid on this project
 *
 * @param integer $project_id ;
 *
 * @return bool true if current user bid and false if he hasn't bid yet
 * @since 1.6.5
 * @package Freelanceengine
 * @category void
 * @author Tambh
 */
function current_user_bid( $project_id ) {
	global $user_ID;
	if ( $user_ID ) {
		$args  = array(
			'post_type'   => 'bid',
			'author'      => $user_ID,
			'post_status' => 'publish',
			'post_parent' => $project_id
		);
		$posts = get_posts( $args );
		if ( ! empty( $posts ) ) {
			return true;
		}
	}

	return false;
}

/**
 * count number post of a user.
 *
 * @param  int $userid : author id of post.
 * @param  string $post_type
 *
 * @return count number project of author id.
 */
function fre_count_user_posts_by_type( $user_id, $post_type = 'project', $status = "publish", $multi = false ) {
	global $wpdb;

	//$where = get_posts_by_author_sql( $post_type, true, $userid );
	$where = '';
	if ( ! $multi ) {
		$where = "WHERE post_type = '" . $post_type . "' AND post_status = '" . $status . "'";
	} else if ( $multi ) {
		$where = "WHERE post_type = '" . $post_type . "' AND post_status IN (" . $status . ") ";
	}

	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where AND post_author = $user_id" );

	return apply_filters( 'get_usernumprojects', $count, $user_id );
}

/**
 * @param $employer_id
 *
 * author : vosydao
 *
 * @return int
 */
function fre_count_hire_freelancer( $employer_id ) {
	global $wpdb;
	$total = 0;

	$query = 'SELECT * FROM ' . $wpdb->posts . ' as p
	INNER JOIN ' . $wpdb->posts . ' as p1 ON p.ID = p1.post_parent
	WHERE p.post_status IN("complete","close","disputing","disputed","archive")
	AND p.post_type = "project" AND p.post_author = ' . $employer_id . '
	AND p1.post_type = "bid" GROUP BY p1.post_author
	';

	$list_project = ( $wpdb->get_results( $query ) );
	if ( ! empty( $list_project ) ) {
		$total = count( $list_project );
	}

	return $total;
}

/** sum a meta value of user.
 * itn $user_id
 * string $post_type
 * string $meta_key,
 * get sum all value of meta_key.
 */
function fre_count_meta_value_by_user( $user_id, $post_type, $meta_key ) {
	global $wpdb;
	$sql   = "SELECT sum(pm.meta_value) from $wpdb->posts p  LEFT JOIN $wpdb->postmeta pm  ON  p.ID = pm.post_id WHERE p.post_type = '" . $post_type . "' AND p.post_status='complete' AND p.post_author = " . $user_id . "   AND pm.meta_key = '" . $meta_key . "' ";
	$count = $wpdb->get_var( $sql );

	return (float) $count;
}

function fre_count_total_user_spent( $user_id ) {
	global $wpdb;
	$sql = "SELECT SUM(pm.meta_value) from $wpdb->posts project
        JOIN  $wpdb->posts bid
            ON project.ID = bid.post_parent
        JOIN $wpdb->postmeta pm
            ON bid.id = pm.post_id
            WHERE project.post_status = 'complete'
                AND bid.post_status = 'complete'
                AND pm.meta_key ='bid_budget'
                AND project.post_author = $user_id ";

	return (float) $wpdb->get_var( $sql );
}

function fre_count_total_user_earned( $user_id ) {
	$total_amount_worked = 0;
	$list_project_worked = get_posts( array(
		'post_type'      => BID,
		'author'         => $user_id,
		'accepted'            => 1,
		'post_status'    => array( 'disputed', 'complete','completed' ),
		'posts_per_page' => - 1,
	) );
	if ( ! empty( $list_project_worked ) ) {
		foreach ( $list_project_worked as $v ) {
			$payment_amount     = 0;
			$fre_bid_order      = get_post_meta( $v->ID, 'fre_bid_order', true );
			$fre_bid_order_info = get_post( $fre_bid_order );
			if ( $fre_bid_order_info && ( $fre_bid_order_info->post_status == 'completed' or $fre_bid_order_info->post_status == 'complete' or $fre_bid_order_info->post_status == 'finish' ) ) {
			    $bid_budget = get_post_meta( $v->ID, 'bid_budget', true );
				/*$payer_of_commission = get_post_meta($v->ID,'payer_of_commission',true);
				if($payer_of_commission !='project_owner'){
					$commission_fee = get_post_meta($v->ID,'commission_fee',true);
					$payment_amount = $bid_budget - $commission_fee;
				}else{
					$payment_amount = intval($bid_budget);
				}*/
				$payment_amount = $bid_budget;

			}

			$total_amount_worked += $payment_amount;
		}
	}

	return $total_amount_worked;
}

/**
 * display html of list skill or category of project
 *
 * @param  int $id project id
 * @param  string $title - title apperance in h3
 * @param  string $slug taxonomy slug
 *
 * @return display list taxonomy of project.
 */
function list_tax_of_project( $id, $title = '', $taxonomy = 'project_category' ) {

	$terms = get_the_terms( $id, $taxonomy );
	if ( $terms && ! is_wp_error( $terms ) ): ?>
        <h4><?php printf( $title ); ?></h4>
		<?php the_taxonomy_list( $taxonomy );
	endif;
}

/**
 * display user info of a freelancer or employser in single project
 *
 * @param  int $profile_id
 *
 * @return display info in single-project.php or author.php
 */
function fre_display_user_info_single( $user_id ) {
	global $wp_query, $user_ID;
	$user      = get_userdata( $user_id );
	$ae_users  = AE_Users::get_instance();
	$user_data = $ae_users->convert( $user );
	$role      = ae_user_role( $user_id );
	?>
    <div class="info-company-avatar">
        <a href="<?php echo get_author_posts_url( $user_id ); ?>">
            <span class="info-avatar"><?php echo get_avatar( $user_id, 35 );
	            echo get_the_title( $user_id ); ?></span>
        </a>
        <div class="info-company">
            <h3 class="info-company-name"><?php echo isset( $user_data->display_name ) ? $user_data->display_name : ''; ?></h3>
            <span class="time-since">
                <?php
                if ( isset( $user_data->user_registered ) ) {
	                printf( __( 'Member Since %s', ET_DOMAIN ), date_i18n( get_option( 'date_format' ), strtotime( $user_data->user_registered ) ) );
                }
                ?>
            </span>
        </div>
    </div>
    <ul class="list-info-company-details">
        <li>
            <div class="address">
            <span class="addr-wrap">
                <span class="title-name"><i class="fa fa-map-marker"></i><?php _e( 'Address:', ET_DOMAIN ); ?></span>
                <span class="info addr"
                      title="<?php echo isset( $user_data->location ) ? $user_data->location : ''; ?>">
                    <?php echo isset( $user_data->location ) ? $user_data->location : ''; ?>
                </span>
                </span>
            </div>
        </li>
        <li>
            <div class="spent"><i class="fa fa-money"></i>
				<?php _e( 'Total spent:', ET_DOMAIN ); ?>
                <span class="info"><?php echo fre_price_format( fre_count_total_user_spent( $user_id ) ); ?></span>
            </div>
        </li>
        <li>
            <div class="briefcase"><i class="fa fa-briefcase"></i>
				<?php _e( 'Project posted:', ET_DOMAIN ); ?>
                <span class="info"><?php echo fre_count_user_posts_by_type( $user_id, 'project', '"publish","complete","close","disputing","disputed" ', true ); ?></span>

            </div>
        </li>
        <li>
            <div class="hired"><i class="fa fa-send"></i>
				<?php _e( 'Complete project(s):', ET_DOMAIN ); ?>
                <span class="info"><?php echo fre_count_user_posts_by_type( $user_id, 'project', 'complete' ); ?></span>
            </div>
        </li>
    </ul>
	<?php
}

if ( ! function_exists( 'fre_display_user_info' ) ) {

	/**
	 * display user info of a freelancer or employser in profile author
	 *
	 * @param  int $profile_id
	 *
	 * @return display info in single-project.php or author.php
	 */
	function fre_display_user_info( $user_id ) {

		global $wp_query, $user_ID;
		$user      = get_userdata( $user_id );
		$ae_users  = AE_Users::get_instance();
		$user_data = $ae_users->convert( $user );
		$role      = ae_user_role( $user_id );
		?>
        <ul class="list-info-company-details">
			<?php if ( $role == 'freelancer' ) { ?>
                <li>
                    <div class="address">
                        <span class="addr-wrap">
                            <span class="title-name"><i
                                        class="fa fa-user"></i><?php _e( 'Address:', ET_DOMAIN ); ?></span>
                            <span class="info addr"
                                  title="<?php echo isset( $user_data->location ) ? $user_data->location : ''; ?>">
                            <?php echo isset( $user_data->location ) ? $user_data->location : ''; ?>
                            </span>
                        </span>
                    </div>
                </li>
                <li>
                    <div class="spent"><i class="fa fa-money"></i>
						<?php _e( 'Earning:', ET_DOMAIN ); ?>
                        <span class="info">
                            <?php echo fre_price_format( fre_count_meta_value_by_user( $user_id, 'bid', 'bid_budget' ) ); ?>
                        </span>
                    </div>
                </li>
                <li>
                    <div class="briefcase"><i class="fa fa-briefcase"></i>
						<?php _e( 'Complete project(s):', ET_DOMAIN ); ?>
                        <span class="info">
                            <?php echo fre_count_user_posts_by_type( $user_id, BID, 'complete' ); ?>
                        </span>
                    </div>
                </li>
			<?php } else { ?>
                <li>
                    <div class="member-since">
                    <span class="member-wrap">
                        <span class="title-name"><i
                                    class="fa fa-user"></i><?php _e( 'Member Since:', ET_DOMAIN ); ?></span>
                        <span class="info addr" title="">
                        <?php
                        if ( isset( $user_data->user_registered ) ) {
	                        echo date_i18n( get_option( 'date_format' ), strtotime( $user_data->user_registered ) );
                        }
                        ?>
                        </span>
                        </span>
                    </div>
                </li>
                <li>
                    <div class="address">
                    <span class="addr-wrap">
                        <span class="title-name"><i
                                    class="fa fa-map-marker"></i><?php _e( 'Address:', ET_DOMAIN ); ?></span>
                        <span class="info addr"
                              title="<?php echo isset( $user_data->location ) ? $user_data->location : ''; ?>">
                            <?php echo isset( $user_data->location ) ? $user_data->location : ''; ?>
                        </span>
                        </span>
                    </div>
                </li>
                <li>
                    <div class="spent"><i class="fa fa-money"></i>
						<?php _e( 'Total spent:', ET_DOMAIN ); ?>
                        <span class="info"><?php echo fre_price_format( fre_count_total_user_spent( $user_id ) ); ?></span>
                    </div>
                </li>
                <li>
                    <div class="briefcase"><i class="fa fa-briefcase"></i>
						<?php _e( 'Project posted:', ET_DOMAIN ); ?>
                        <span class="info"><?php echo fre_count_user_posts_by_type( $user_id, 'project', '"publish","complete","close","disputing","disputed" ', true ); ?></span>

                    </div>
                </li>
                <li>
                    <div class="hired"><i class="fa fa-send"></i>
						<?php _e( 'Complete project(s):', ET_DOMAIN ); ?>
                        <span class="info"><?php echo fre_count_user_posts_by_type( $user_id, 'project', 'complete' ); ?></span>
                    </div>
                </li>
			<?php } ?>
        </ul>
		<?php
		do_action( 'fre_after_block_user_info', $user_id );
	}
}

/**
 * get project infor
 *
 * @param void
 *
 * @return void
 * @since void
 * @package void
 * @category void
 * @author Tambh
 */
function fre_get_project_infor( $post_id ) {
	global $ae_post_factory;
	$project_obj = $ae_post_factory->get( PROJECT );
	$post        = get_post( $post_id );
	$project     = $post;
	if ( $post ) {
		$project = $project_obj->convert( $post );
	}

	return $project;
}

/**
 * print taxonomy dropdown with sub
 *
 * @param $taxonomy
 * @param $args
 *
 * @return void
 * @author Tuandq
 */
function fre_sub_taxonomy_dropdow( $taxonomy, $args ) {

	echo '<select ' . $args['attr'] . '  name="' . $args['name'] . '" id="' . $args['id'] . '" class="' . $args['class'] . '">';
	$args       = array( 'hide_empty' => true, 'hierarchical' => true, );
	$terms      = get_terms( 'project_category', $args );
	$value_type = 'slug';
	if ( isset( $args['show_option_all'] ) && $args['show_option_all'] ) {
		echo '<option value="" selected="selected">' . $args['show_option_all'] . '</option>';
	}
	if ( $terms ) {
		foreach ( $terms as $term ) {
			if ( $term->parent == 0 ) {
				if ( $value_type == 'slug' ) {
					echo "<option value='" . $term->slug . "' class='" . $term->slug . " cat-" . $term->term_id . "'>";
				} elseif ( $value_type == 'id' ) {
					echo "<option value='" . $term->term_id . "' class='" . $term->slug . " cat-" . $term->term_id . "'>";
				}
				echo $term->name;
				echo "</option>";
				foreach ( $terms as $value ) {
					if ( $term->term_id == $value->parent ) {
						if ( $value_type == 'slug' ) {
							echo "<option value='" . $value->slug . "' class='" . $term->slug . " cat-" . $term->term_id . "'>";
						} elseif ( $value_type == 'id' ) {
							echo "<option value='" . $value->term_id . "' class='" . $term->slug . " cat-" . $term->term_id . "'>";
						}
						echo "--" . $value->name;
						echo "</option>";
					}
				}
			}
		}
	}
	echo '</select>';
}

?>