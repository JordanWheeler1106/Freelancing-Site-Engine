<?php
/**
 *    Template Name: Process Payment
 */
$session = et_read_session();
global $ad, $payment_return, $order_id, $user_ID;

$payment_type = get_query_var( 'paymentType' );
if ( $payment_type == 'usePackage' || $payment_type == 'free' ) {
	$payment_return = ae_process_payment( $payment_type, $session );
	if ( $payment_return['ACK'] ) {
		$project_url = get_the_permalink( $session['ad_id'] );
		// Destroy session for order data
		et_destroy_session();
		// Redirect to project detail
		wp_redirect( $project_url );
		exit;
	}
}

/**
 * get order
 */
$order_id = isset( $_GET['order-id'] ) ? $_GET['order-id'] : '';
if ( empty( $order_id ) && isset( $_POST['orderid'] ) ) {
	$order_id = $_POST['orderid'];
}
// if(isset($session['']))
$order      = new AE_Order( $order_id );
$order_data = $order->get_order_data();
if ( ( $payment_type == 'paypaladaptive' || $payment_type == 'frecredit' || $payment_type == 'stripe' ) && ! $order_id ) {

	$payment_return  = fre_process_escrow( $payment_type, $session );
	$payment_return  = wp_parse_args( $payment_return, array( 'ACK' => false, 'payment_status' => '' ) );
	extract( $payment_return );
	if ( isset( $ACK ) && $ACK ):
		//change charge status transaction accept bid to pending from ver 1.8.2
		do_action( 'fre_change_status_accept_bid', $session['payKey'] );

		// Accept bid
		$ad_id = $session['ad_id'];
		$order_id    = $session['order_id'];
		$permalink   = get_permalink( $ad_id );
		$permalink   = add_query_arg( array( 'workspace' => 1 ), $permalink );
		$workspace   = '<a href="' . $permalink . '">' . get_the_title( $ad_id ) . '</a>';
		$bid_id      = get_post_field( 'post_parent', $order_id );
		$bid_budget  = get_post_meta( $bid_id, 'bid_budget', true );
		$content_arr = array(
			'paypaladaptive' => __( 'Paypal', ET_DOMAIN ),
			'frecredit'      => __( 'Credit', ET_DOMAIN ),
			'stripe'         => __( 'Stripe', ET_DOMAIN )
		);

		// get commission settings
		$commission     = ae_get_option( 'commission', 0 );
		$commission_fee = $commission;

		// caculate commission fee by percent
		$commission_type = ae_get_option( 'commission_type' );
		if ( $commission_type != 'currency' ) {
			$commission_fee = ( (float) ( $bid_budget * (float) $commission ) ) / 100;
		}

		$commission          = fre_price_format( $commission_fee );
		$payer_of_commission = ae_get_option( 'payer_of_commission', 'project_owner' );
		if ( $payer_of_commission == 'project_owner' ) {
			$total = (float) $bid_budget + (float) $commission_fee;
		} else {
			$commission = 0;
			$total      = $bid_budget;
		}

		get_header();
		?>
        <div class="fre-page-wrapper">
            <div class="fre-page-title">
                <div class="container">
                    <h2><?php the_title(); ?></h2>
                </div>
            </div>
            <div class="fre-page-section">
                <div class="container">
                    <div class="page-purchase-package-wrap">
                        <div class="fre-purchase-package-box">
                            <div class="step-payment-complete">
                                <h2><?php _e( "Payment Successfully Completed", ET_DOMAIN ); ?></h2>
                                <p><?php _e( "Thank you. Your payment has been received and the process is now being run.", ET_DOMAIN ); ?></p>
                                <div class="fre-table">
                                    <div class="fre-table-row">
                                        <div class="fre-table-col fre-payment-date"><?php _e( "Date:", ET_DOMAIN ); ?></div>
                                        <div class="fre-table-col"><?php echo get_the_date( get_option( 'date_format' ), $order_id ); ?></div>
                                    </div>
                                    <div class="fre-table-row">
                                        <div class="fre-table-col fre-payment-type"><?php _e( "Payment Type:", ET_DOMAIN ); ?></div>
                                        <div class="fre-table-col"><?php echo $content_arr[ $payment_type ]; ?></div>
                                    </div>
                                    <div class="fre-table-row">
                                        <div class="fre-table-col fre-payment-total"><?php _e( "Total:", ET_DOMAIN ); ?></div>
                                        <div class="fre-table-col"><?php echo fre_price_format( $total ); ?></div>
                                    </div>
                                </div>
                                <div class="fre-view-project-btn">
                                    <p><?php _e( "Your project detail is now available for you to view.", ET_DOMAIN ); ?></p>
                                    <a class="fre-btn"
                                       href="<?php echo $permalink; ?>"><?php _e( "Move now", ET_DOMAIN ); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
		get_footer();
	else:
		# code...
		// Redirect to 404
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit();
	endif;
} else if ( $order_id && ( $user_ID == $order_data['payer'] || is_super_admin( $user_ID ) ) ) {
	// Process submit project
	get_header();
	$ad         = get_post( $order_data['product_id'] );
	$project_id = ( isset( $session['project_id'] ) ) ? $session['project_id'] : '';
	?>
    <div class="fre-page-wrapper">
        <div class="fre-page-title">
            <div class="container">
                <h2><?php the_title(); ?></h2>
            </div>
        </div>
        <div class="fre-page-section">
            <div class="container">
                <div class="page-purchase-package-wrap">
                    <div class="fre-purchase-package-box">
                        <div class="step-payment-complete">
                            <h2><?php _e( "Payment Successfully Completed", ET_DOMAIN ); ?></h2>
                            <p><?php _e( "Thank you. Your payment has been received and the process is now being run.", ET_DOMAIN ); ?></p>
                            <div class="fre-table">
                                <div class="fre-table-row">
                                    <div class="fre-table-col fre-payment-id"><?php _e( "Invoice No:", ET_DOMAIN ); ?></div>
                                    <div class="fre-table-col"><?php echo $order_data['ID']; ?></div>
                                </div>
                                <div class="fre-table-row">
                                    <div class="fre-table-col fre-payment-date"><?php _e( "Date:", ET_DOMAIN ); ?></div>
                                    <div class="fre-table-col"><?php echo get_the_date( get_option( 'date_format' ), $order_id ); ?></div>
                                </div>
                                <div class="fre-table-row">
                                    <div class="fre-table-col fre-payment-type"><?php _e( "Payment Type:", ET_DOMAIN ); ?></div>
                                    <div class="fre-table-col"><?php echo $order_data['payment']; ?></div>
                                </div>
                                <div class="fre-table-row">
                                    <div class="fre-table-col fre-payment-total"><?php _e( "Total:", ET_DOMAIN ); ?></div>
                                    <div class="fre-table-col"><?php echo fre_price_format( $order_data['total'] ); ?></div>
                                </div>
                            </div>
                            <div class="fre-view-project-btn">
                                <!-- <p><?php _e( "Your project detail is now available for you to view.", ET_DOMAIN ); ?></p>
								<a class="fre-btn" href="<?php //echo $permalink;?>"><?php //_e("Move now", ET_DOMAIN);?></a> -->
								<?php
								if ( isset( $order_data['products'] ) ) {
									$product = current( $order_data['products'] );
									$type    = $product['TYPE'];
									switch ( $type ) {
										case 'bid_plan':
											// buy bid
											if ( $project_id ) {
												$permalink = get_the_permalink( $project_id );
											} else {
												$permalink = et_get_page_link( 'my-project' );
											}
											echo "<p>" . __( 'Now you can return to the project pages', ET_DOMAIN ) . "</p>";
											echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Return', ET_DOMAIN ) . "</a>";
											break;
										case 'fre_credit_plan':
											// deposit credit
											if ( $project_id ) {
												$permalink = get_the_permalink( $project_id );
											} else {
												$permalink = et_get_page_link( 'my-credit' );
											}
											echo "<p>" . __( 'Please click the button below to return to the previous page', ET_DOMAIN ) . "</p>";
											echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Click here', ET_DOMAIN ) . "</a>";
											break;
										default:

											if ( $order_data['status'] == 'publish' ) { //Buy package
												echo "<p>" . __( 'Click the button below to be redirected to the previous page', ET_DOMAIN ) . "</p>";
												echo "<a class='fre-btn' href='" . et_get_page_link( 'my-project' ) . "'>" . __( 'Go', ET_DOMAIN ) . "</a>";
											} else { // Submit project
												$permalink = get_the_permalink( $ad->ID );
												echo "<p>" . __( 'Your project details is now available for you to view', ET_DOMAIN ) . "</p>";
												echo "<a class='fre-btn' href='" . $permalink . "'>" . __( 'Go', ET_DOMAIN ) . "</a>";
											}
											break;
									}
								}
								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
	if ( $order_id && ! get_post_meta( $order_id, 'et_order_is_process_payment' ) ) {

		//processs payment
		if ( $payment_type == 'paypaladaptive' || $payment_type == 'frecredit' ) {
			$payment_return = fre_process_escrow( $payment_type, $session );
		} else {
			$payment_type   = $order_data['payment'];
			$payment_return = ae_process_payment( $payment_type, $session );
		}
		update_post_meta( $order_id, 'et_order_is_process_payment', true );
		et_destroy_session();
	}
	get_footer();
} else {
	// Redirect to 404
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 );
	exit();
}

