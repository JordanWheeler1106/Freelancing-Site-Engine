<?php
/**
 * this file contain all function related to places
 */
add_action( 'init', 'fre_init_bid_plan' );
function fre_init_bid_plan() {

	register_post_type( 'bid_plan', array(
		'labels'             => array(
			'name'               => __( 'Bid plan', ET_DOMAIN ),
			'singular_name'      => __( 'Bid plan', ET_DOMAIN ),
			'add_new'            => __( 'Add New', ET_DOMAIN ),
			'add_new_item'       => __( 'Add New Bid plan', ET_DOMAIN ),
			'edit_item'          => __( 'Edit Bid plan', ET_DOMAIN ),
			'new_item'           => __( 'New Bid plan', ET_DOMAIN ),
			'all_items'          => __( 'All Bid plans', ET_DOMAIN ),
			'view_item'          => __( 'View Bid plan', ET_DOMAIN ),
			'search_items'       => __( 'Search Bid plans', ET_DOMAIN ),
			'not_found'          => __( 'No Bid plan found', ET_DOMAIN ),
			'not_found_in_trash' => __( 'No Bid plans found in Trash', ET_DOMAIN ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Bid plans', ET_DOMAIN )
		),
		'public'             => false,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => true,

		'capability_type' => 'post',
		// 'capabilities' => array(
		//     'manage_options'
		// ) ,
		'has_archive'     => 'packs',
		'hierarchical'    => false,
		'menu_position'   => null,
		'supports'        => array(
			'title',
			'editor',
			'author',
			'custom-fields'
		)
	) );
	$package = new AE_Pack( 'bid_plan', array(
		'sku',
		'et_price',
		'et_number_posts',
		'order',
		'et_featured'
	),
		array(
			'backend_text' => array(
				'text' => __( '%s for %d bids', ET_DOMAIN ),
				'data' => array(
					'et_price',
					'et_number_posts'
				)
			)
		) );
	global $ae_post_factory;
	$ae_post_factory->set( 'bid_plan', $package );
}


if ( ! function_exists( 'fre_order_bid_plan_by_menu_order' ) ) {
	/**
	 * filter posts order by to order bid_plan post by menu order
	 *
	 * @param string $orderby The query orderby string
	 * @param object $query Current wp_query object
	 *
	 * @since 1.4
	 * @author Dakachi
	 */
	function fre_order_bid_plan_by_menu_order( $orderby, $query ) {
		global $wpdb;
		if ( $query->query_vars['post_type'] != 'bid_plan' ) {
			return $orderby;
		}
		$orderby = "{$wpdb->posts}.menu_order ASC";

		return $orderby;
	}

	add_filter( 'posts_orderby', 'fre_order_bid_plan_by_menu_order', 10, 2 );
}
/**
 * process payment for bid
 *
 * @since version 1.5.4
 * @author Tambh
 */
add_action( 'ae_select_process_payment', 'fre_setup_bid_payment', 10, 2 );
function fre_setup_bid_payment( $payment_return, $data ) {
	global $user_ID, $ae_post_factory;
	extract( $data );
	if ( ! $payment_return['ACK'] ) {
		return false;
	}
	$order_pay = $data['order']->get_order_data();
	if ( isset( $payment_return['payment_status'] ) ) {
		$packs = AE_Package::get_instance();
		$sku   = $order_pay['payment_package'];
		$pack  = $packs->get_pack( $sku, 'bid_plan' );
		if ( $payment_return['payment_status'] == 'Completed' ) {
			$products = $order_pay['products'];
			if ( isset( $pack->et_number_posts ) && (int) $pack->et_number_posts > 0 ) {
				if ( $payment_return['payment'] == 'cash' ) {
					update_credit_number_pending( $user_ID, (int) $pack->et_number_posts );
				} else {
					update_credit_number( $user_ID, (int) $pack->et_number_posts );
					$payment_return['bid_msg'] = sprintf( __( "You've successfully purchased %d credits.", ET_DOMAIN ), $pack->et_number_posts );
				}

				return $payment_return;
			}
		} else if ( $payment_return['payment_status'] == 'Pending' ) {
			if ( isset( $pack->et_number_posts ) && (int) $pack->et_number_posts > 0 ) {
				update_credit_number_pending( $user_ID, (int) $pack->et_number_posts );
			}
		}
	}

	return $payment_return;
}

/**
 * update credit number for user
 *
 * @param int $user_ID
 * @param int $credit_number credit number of package
 *
 * @return bool true if update user meta success
 * @since version 1.5.4
 * @author Tambh
 */
function update_credit_number( $user_ID, $credit_number ) {
	// if( ae_get_option( 'pay_to_bid', false ) ){
	$user_credit = get_user_credit_number( $user_ID );
	$user_credit += $credit_number;
	if ( $user_credit < 0 ) {
		$user_credit = 0;
	}
	$result = update_user_meta( $user_ID, 'credit_number', $user_credit );
	// }
}

/**
 * Get user credit number
 *
 * @param int $user_ID
 *
 * @return int user's credit number
 * @since version 1.5.4
 */
function get_user_credit_number( $user_ID ) {
	return (int) get_user_meta( $user_ID, 'credit_number', true );
}

/**
 * update credit number pending for user
 *
 * @param int $user_ID
 * @param int $credit_number credit number of package
 *
 * @return bool true if update user meta success
 * @since version 1.5.4
 * @author ThanhTu
 */
function update_credit_number_pending( $user_ID, $credit_number ) {
	$user_credit = get_user_credit_number_pending( $user_ID );
	$user_credit += $credit_number;
	if ( $user_credit < 0 ) {
		$user_credit = 0;
	}
	$result = update_user_meta( $user_ID, 'credit_number_pending', $user_credit );
}

/**
 * Get user credit number pending
 *
 * @param int $user_ID
 *
 * @return int user's credit number
 * @since version 1.5.4
 */
function get_user_credit_number_pending( $user_ID ) {
	return (int) get_user_meta( $user_ID, 'credit_number_pending', true );
}

/**
 * add package post type to list
 *
 * @since version 1.5.4
 * @author Tambh
 */
add_filter( 'ae_pack_post_types', 'fre_pack_post_type' );
function fre_pack_post_type( $pack_post_type ) {
	return wp_parse_args( array( 'pack', 'bid_plan' ), $pack_post_type );
}

/**
 * check user can or can't bid a project
 *
 * @param int $user_ID the user's ID
 *
 * @return bool true if user can bid / false if user can't bid
 * @since version 1.5.4
 * @author Tambh
 *
 */
function can_user_bid( $user_ID ) {
	global $user_ID;
	if ( ae_get_option( 'pay_to_bid', false ) ) {
		$user_credits = get_user_credit_number( $user_ID );
		if ( $user_credits > 0 ) {
			return true;
		}

		return false;
	}

	return true;
}

function get_credit_to_pay() {
	$bid_credit = (int) ae_get_option( 'ae_credit_number', 0 );
	$bid_credit = apply_filters( 'fre_credit_to_pay', $bid_credit );

	return $bid_credit;
}

/**
 * This function will auto update user's credits after admin approved cash payment
 *
 * @since 1.5.4
 *
 * @author Tambh
 */
// add_action( 'save_post', 'cash_upproved', 10, 2 );
function cash_upproved( $post_ID, $post ) {
	if ( current_user_can( 'manage_options' ) ) {
		if ( $post->post_type == 'order' && $post->post_status == 'publish' ) {
			$order     = new AE_Order( $post_ID );
			$order_pay = $order->get_order_data();
			if ( isset( $order_pay['payment'] ) && $order_pay['payment'] == 'cash' ) {
				$products = $order_pay['products'];
				$sku      = $order_pay['payment_package'];
				$packs    = AE_Package::get_instance();
				$pack     = $packs->get_pack( $sku, 'bid_plan' );
				if ( isset( $pack->et_number_posts ) && (int) $pack->et_number_posts > 0 ) {
					update_credit_number( $post->post_author, (int) $pack->et_number_posts );
				}
			}
		}
	}
}

add_filter( 'ae_admin_globals', 'fre_new_ae_global_admin' );
function fre_new_ae_global_admin( $vars ) {
	$vars['fre_updated_bids']       = ae_get_option( 'fre_updated_bids', 0 );
	$vars['fre_updated_bid_accept'] = ae_get_option( 'fre_updated_bid_accept', 0 );

	return $vars;
}

/**
 * Add enable pay to bid to ae_global
 *
 *
 * @since version 1.5.4
 * @author Tambh
 */
add_filter( 'ae_globals', 'fre_new_ae_global' );
function fre_new_ae_global( $vars ) {
	global $user_ID;
	$vars['pay_to_bid']             = ae_get_option( 'pay_to_bid', false );
	$vars['yes']                    = __( 'Yes', ET_DOMAIN );
	$vars['no']                     = __( 'No', ET_DOMAIN );
	$vars['search_result_msg']      = __( ' RESULT OF  ', ET_DOMAIN );
	$vars['search_result_msgs']     = __( ' RESULTS OF ', ET_DOMAIN );
	$vars['max_skill_text']         = sprintf( __( 'You can only choose %s!', ET_DOMAIN ), ae_get_option( 'fre_max_skill', 5 ) );
	$vars['max_cat_text']           = sprintf( __( 'You can only choose %s!', ET_DOMAIN ), ae_get_option( 'max_cat', 3 ) );
	$vars['max_skill']              = ae_get_option( 'fre_max_skill', 5 );
	$vars['user_ID']                = $user_ID;
	$vars['view_all_text']          = '<a href="' . get_post_type_archive_link( PROJECT ) . '" class="view-all" >' . __( 'View all projects ', ET_DOMAIN ) . '</a>';
	$vars['view_all_text_profile']  = '<a href="' . get_post_type_archive_link( PROFILE ) . '" class="view-all" >' . __( 'View all profiles ', ET_DOMAIN ) . '</a>';
	$vars['user_is_activate']       = ( AE_Users::is_activate( $user_ID ) ) ? 1 : 0;
	$vars['text_activate']          = __( 'Your account is pending. You have to activate your account to continue this step.', ET_DOMAIN );
	$vars['choose_validate_text']   = __( 'Please choose at least 1 project.', ET_DOMAIN );
	$vars['fre_updated_bids']       = ae_get_option( 'fre_updated_bids', 0 );
	$vars['fre_updated_bid_accept'] = ae_get_option( 'fre_updated_bid_accept', 0 );
	$vars['text_view']              = array(
		'more'   => __( 'View more', ET_DOMAIN ),
		'less'   => __( 'View less', ET_DOMAIN ),
		'lock'   => __( "Lock files", ET_DOMAIN ),
		'unlock' => __( "Unlock files", ET_DOMAIN )
	);
	$vars['text_message']           = array(
		'no_project'      => __( "<span class='project-no-results'>There are no projects found.</span>", ET_DOMAIN ),
		'no_work_history' => __( "<span class='no-results'>There are no activities yet.</span>", ET_DOMAIN ),
		'execute'         => __( 'You are going to finish disputing and send money to freelancer.', ET_DOMAIN ),
		'refund'          => __( 'You are going to send money back to employer.', ET_DOMAIN )
	);
	$vars['loginURL']               = et_get_page_link( 'login' );
	$vars['currency']               = ae_get_option( 'currency' );
	// Check Profile
	$user_profile_id      = get_user_meta( $user_ID, 'user_profile_id', true );
	$checkProfile         = get_post( $user_profile_id );
	$vars['use_escrow']   = ae_get_option( 'use_escrow', 0 );
	$vars['checkProfile'] = ( ! $checkProfile || ! is_numeric( $user_profile_id ) ) ? '0' : '1';

	return $vars;
}

/**
 * Update user's free bids number
 *
 * @param integer $plus
 *
 * @return void
 * @since void
 * @package void
 * @category void
 * @author Tambh
 */
function fre_update_user_free_bid_number( $plus = 1 ) {
	global $user_ID;
	$free_bid = (int) get_user_meta( $user_ID, 'ae_user_free_bid', true );
	$free_bid += $plus;
	update_user_meta( $user_ID, 'ae_user_free_bid', $free_bid );
}

/**
 * @since 1/0
 * @author Thanhtu
 */
function fre_update_data_credit_to_bid() {
	global $ae_post_factory, $user_ID, $wpdb;

	if ( ae_get_option( 'fre_updated_bids', false ) ) {
		return false;
	}
	$credits_bid = (int) ae_get_option( 'ae_credit_number', 1 );

	/*
	 * Update number bid of Package
	 */
	$list_packs = get_posts( array( 'post_type' => 'bid_plan' ) );
	foreach ( $list_packs as $key => $pack ) {
		$bid_old = get_post_meta( $pack->ID, 'et_number_posts', true );
		$bid_new = round( $bid_old / $credits_bid );
		update_post_meta( $pack->ID, 'et_number_posts', $bid_new, $bid_old );
	}

	/*
	 * Update number_bid of all users
	 */
	$arg_user   = array(
		'meta_key' => 'credit_number',
		'number'   => - 1
	);
	$list_users = get_users( $arg_user );
	foreach ( $list_users as $key => $user ) {
		$number_bid_old = get_user_meta( $user->ID, 'credit_number', true );
		$number_bid_new = round( $number_bid_old / $credits_bid );
		$meta_id        = $wpdb->get_var( $wpdb->prepare(
			"SELECT umeta_id FROM {$wpdb->usermeta} WHERE meta_key = 'credit_number' AND user_id = %s", $user->ID ) );

		$result = $wpdb->update( $wpdb->usermeta, array(
			'meta_value' => $number_bid_new
		), array(
			'umeta_id' => $meta_id
		) );
	}

	// After updated success
	ae_update_option( 'fre_updated_bids', 1 );
	wp_send_json( array(
		'success'  => true,
		'msg'      => __( 'Bid plans has been changed from this version. Click OK for further details.', ET_DOMAIN ),
		'redirect' => 'https://www.enginethemes.com/whats-changed-freelanceengine-1-7-9/'
	) );
}

//add_action( 'wp_ajax_update-data-credit', 'fre_update_data_credit_to_bid' );
//add_action( 'wp_ajax_nopriv_update-data-credit', 'fre_update_data_credit_to_bid' );


/**
 * @since 1/0
 * @author Thanhtu
 */
function fre_update_data_bid_unacceptable() {
	global $wpdb;

	if ( ae_get_option( 'fre_updated_bid_accept', false ) ) {
		return false;
	}
	// List projects have post_status = Close
	$query_project = "SELECT pm.post_id
                        FROM {$wpdb->posts} p LEFT JOIN {$wpdb->postmeta} pm
                        ON p.ID = pm.post_id
                        WHERE p.post_type = 'project'
                            AND p.post_status = 'close'
                            AND pm.meta_key = 'accepted'
                            AND pm.meta_value <> '' ";
	$projects      = $wpdb->get_col( $wpdb->prepare( $query_project ) );

	// List bid had Unacceptable
	$sql_bid_unaccept = "SELECT p.ID
                        FROM {$wpdb->posts} p
                        WHERE p.post_type = 'bid'
                            AND p.post_status = 'publish'
                            AND p.post_parent IN (" . implode( ',', $projects ) . ")
                         ORDER BY p.ID DESC";
	$listBids         = $wpdb->get_col( $wpdb->prepare( $sql_bid_unaccept ) );

	// update post_status 'publish' to 'unaccept'
	foreach ( $listBids as $key => $value ) {
		$result = $wpdb->update( $wpdb->posts, array( 'post_status' => 'unaccept' ), array( 'ID' => $value ) );
	}
	// After updated success
	ae_update_option( 'fre_updated_bid_accept', 1 );
	wp_send_json( array(
		'success' => true,
		'msg'     => __( 'Update successfully completed.', ET_DOMAIN )
	) );
}

//add_action( 'wp_ajax_update-bid-unacceptable', 'fre_update_data_bid_unacceptable' );
//add_action( 'wp_ajax_nopriv_update-bid-unacceptable', 'fre_update_data_bid_unacceptable' );

/**
 * Show button Bid project
 *
 * @param int $project_id
 *
 * @author ThanhTu
 */
function fre_button_bid( $project_id ) {
	global $user_ID;
	$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
	$profile    = get_post( $profile_id );

	$class   = 'btn-bid-project';
	$target  = '#modal_bid';
	$_target = '#modal_not_bid';
	if ( et_load_mobile() ) {
		$class = 'btn btn-apply-project-item btn-bid btn-agree';
	}

	// user have to complete profile to bid a project
	if ( ! $profile || ! is_numeric( $profile_id ) ) {
		//echo '<a href="#" class="fre-normal-btn ' . $class . '">' . __( 'Bid', ET_DOMAIN ) . '</a>';
		echo '<a href="#" class="fre-normal-btn purchase-bid-btn" data-project-id="' . $project_id . '">' . __( 'Bid', ET_DOMAIN ) . '</a>';
	} else {
		// Check invite to bid
		if ( ae_get_option( 'invited_to_bid' ) && ! fre_check_invited( $user_ID, $project_id ) ) {
			//echo '<a class="fre-normal-btn ' . $class . '">' . __( 'Bid', ET_DOMAIN ) . '</a>';
			echo '<a href="#" class="fre-normal-btn purchase-bid-btn" data-project-id="' . $project_id . '">' . __( 'Bid', ET_DOMAIN ) . '</a>';
		} else {
			// disable option pay to bid
			if ( ! can_user_bid( $user_ID ) ) {
				echo '<a href="#" class="fre-normal-btn purchase-bid-btn" data-project-id="' . $project_id . '">' . __( 'Bid', ET_DOMAIN ) . '</a>';
			} else {
				echo '<a href="#" class="fre-normal-btn" data-toggle="modal" data-target="' . $target . '">' . __( 'Bid', ET_DOMAIN ) . '</a>';
			}
		}
	}
}
