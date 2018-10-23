<?php

/**
 * this file contain all function related to places
 */
add_action( 'init', 'de_init_package' );
function de_init_package() {

	register_post_type( 'pack', array(
		'labels'             => array(
			'name'               => __( 'Pack', ET_DOMAIN ),
			'singular_name'      => __( 'Pack', ET_DOMAIN ),
			'add_new'            => __( 'Add New', ET_DOMAIN ),
			'add_new_item'       => __( 'Add New Pack', ET_DOMAIN ),
			'edit_item'          => __( 'Edit Pack', ET_DOMAIN ),
			'new_item'           => __( 'New Pack', ET_DOMAIN ),
			'all_items'          => __( 'All Packs', ET_DOMAIN ),
			'view_item'          => __( 'View Pack', ET_DOMAIN ),
			'search_items'       => __( 'Search Packs', ET_DOMAIN ),
			'not_found'          => __( 'No Pack found', ET_DOMAIN ),
			'not_found_in_trash' => __( 'NoPacks found in Trash', ET_DOMAIN ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Packs', ET_DOMAIN )
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

	$package     = new AE_Package( 'pack', array( 'project_type' ) );
	$pack_action = new AE_PackAction( $package );

	global $ae_post_factory;
	$ae_post_factory->set( 'pack', $package );
}

class FRE_Payment extends AE_Payment {

	function __construct() {
		$this->no_priv_ajax = array();
		$this->priv_ajax    = array(
			'et-setup-payment'
		);
		$this->init_ajax();
	}

	public function get_plans() {
		global $ae_post_factory;
		$packageType = 'pack';
		if ( isset( $_POST['packageType'] ) && $_POST['packageType'] != '' ) {
			$packageType = $_POST['packageType'];
		}
		$pack = $ae_post_factory->get( $packageType );

		return $pack->fetch();
	}
}

new FRE_Payment();


/**
 * render user package info
 *
 * @param Integer $user_ID the user_ID want to render
 *
 * @package AE Package
 * @category payment
 *
 * @since 2.0
 * @author ThanhTu
 */
function fre_user_package_info( $user_ID ) {
	if ( ! $user_ID ) {
		return;
	}
	$user_role = ae_user_role( $user_ID );
	if ( $user_role == FREELANCER || ae_get_option( 'disable_plan', false ) ) {
		return;
	}

	global $ae_post_factory;
	$ae_pack       = $ae_post_factory->get( 'pack' );
	$packs         = $ae_pack->fetch();
	$orders        = AE_Payment::get_current_order( $user_ID );
	$package_data  = AE_Package::get_package_data( $user_ID );
	$total_package = ae_user_get_total_package( $user_ID );
	$flag          = true;
	$packages      = array();
	foreach ( $packs as $package ) {
		$sku = $package->sku;
		if ( isset( $package_data[ $sku ] ) && $package_data[ $sku ]['qty'] > 0 ) {
			$package_data_sku = $package_data[ $sku ];
			if ( $package_data_sku['qty'] > 0 ) {
				if ( $package->post_type == 'pack' ) {
					$order = get_post( $orders[ $sku ] );
					if ( ! $order || is_wp_error( $order ) || ! in_array( $order->post_status, array(
							'publish',
							'pending'
						) ) ) {
						continue;
					}
					$packages[] = $package;
					$flag       = false;
				}
			}
		}
	}
	?>

    <div class="fre-work-package-wrap">
        <div class="fre-work-package">
			<?php if ( ! $flag ) { ?>
                <p>
					<?php _e( 'Your post(s) left:', ET_DOMAIN ) ?>
                    <span class="post-number"><?php printf( __( '<b>%s</b>', ET_DOMAIN ), $total_package ); ?></span>
                </p>
			<?php } ?>
			<?php
			foreach ( $packages as $package ) {
				$sku              = $package->sku;
				$order            = get_post( $orders[ $sku ] );
				$package_data_sku = $package_data[ $sku ];
				$number_of_post   = $package_data_sku['qty'];
				echo "<p>";
				if ( $order->post_status == 'publish' ) {
					printf( __( "<b>%s</b> package and have <b>%d</b> post(s) left.", ET_DOMAIN ), $package->post_title, $number_of_post );
				}
				if ( $order->post_status == 'pending' ) {
					printf( __( "<b>%s</b> package and have <b>%d</b> post(s) left. Your package is under admin review.", ET_DOMAIN ), $package->post_title, $number_of_post );
				}
				echo "</p>";
			}
			if ( $flag ) {
				echo '<p>' . __( "There are no packages for project posting.", ET_DOMAIN ) . '</p>';
			}
			?>
            <a class="fre-normal-btn-o" href="<?php ?>"><?php _e( 'Purchase more posts', ET_DOMAIN ); ?></a>
        </div>
    </div>

<?php }

/*add_action( 'ae_buy_plan_process_payment', 'fre_setup_plan_payment', 10, 2 );
function fre_setup_plan_payment( $payment_return, $data ) {
	global $user_ID, $ae_post_factory;
	extract( $data );
	if ( ! $payment_return['ACK'] ) {
		return false;
	}
	$order_pay = $data['order']->get_order_data();

	if ( isset( $payment_return['payment_status'] ) ) {
		$packs = AE_Package::get_instance();
		$sku   = $order_pay['payment_package'];
		$pack  = $packs->get_pack( $sku, 'pack' );
		if ( $payment_return['payment_status'] == 'Completed' ) {
			if ( isset( $pack->et_number_posts ) && (int) $pack->et_number_posts > 0 ) {
				if ( $payment_return['payment'] == 'cash' ) {
					update_package_number_pending( $user_ID, (int) $pack->et_number_posts );
				} else {
					update_package_number( $user_ID, (int) $pack->et_number_posts );
					$payment_return['bid_msg'] = sprintf( __( "You've successfully purchased %d plan.", ET_DOMAIN ), $pack->et_number_posts );
				}

				return $payment_return;
			}
		} else if ( $payment_return['payment_status'] == 'Pending' ) {
			if ( isset( $pack->et_number_posts ) && (int) $pack->et_number_posts > 0 ) {
				update_package_number_pending( $user_ID, (int) $pack->et_number_posts );
			}
		}
	}

	return $payment_return;
}


function update_package_number( $user_ID, $credit_number ) {
	$user_credit = get_user_package_number( $user_ID );
	$user_credit += $credit_number;
	if ( $user_credit < 0 ) {
		$user_credit = 0;
	}
	update_user_meta( $user_ID, 'credit_number', $user_credit );
}

function get_user_package_number( $user_ID ) {
	return (int) get_user_meta( $user_ID, 'credit_number', true );
}

function update_package_number_pending( $user_ID, $credit_number ) {
	$user_credit = get_user_package_number_pending( $user_ID );
	$user_credit += $credit_number;
	if ( $user_credit < 0 ) {
		$user_credit = 0;
	}
	update_user_meta( $user_ID, 'credit_number_pending', $user_credit );
}

function get_user_package_number_pending( $user_ID ) {
	return (int) get_user_meta( $user_ID, 'credit_number_pending', true );
}*/
