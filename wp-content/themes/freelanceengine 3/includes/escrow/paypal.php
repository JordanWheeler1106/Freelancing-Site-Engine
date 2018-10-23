<?php
/**
 * register post type fre_order to handle escrow order
 * @author Dakachi
 */
function fre_register_order() {
    register_post_type('fre_order', $args = array(
        'labels' => array(
            'name' => __('Fre Order', ET_DOMAIN) ,
            'singular_name' => __('Fre Order', ET_DOMAIN)
        ) ,
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
    ));
}
add_action('init', 'fre_register_order');

/**
 * enqueue script to open modal accept bid
 * @author Dakachi
 */
function fre_enqueue_escrow() {
    if (is_singular(PROJECT)) {
        wp_enqueue_script('escrow-accept', TEMPLATEURL . '/assets/js/accept-bid.js', array(
            'jquery',
            'underscore',
            'backbone',
            'appengine'
        ) , ET_VERSION, true);
    }
}
add_action('wp_print_scripts', 'fre_enqueue_escrow');

/**
 * ajax callback to setup bid info and send to client
 * @author Dakachi
 */
function fre_get_accept_bid_info() {
    $bid_id = $_GET['bid_id'];
    global $user_ID;
    $error = array(
        'success' => false,
        'msg' => __('Invalid bid', ET_DOMAIN)
    );
    if (!isset($_REQUEST['bid_id'])) {
        wp_send_json($error);
    }
    $bid_id = $_REQUEST['bid_id'];
    $bid = get_post($bid_id);

    // check bid is valid
    if (!$bid || is_wp_error($bid) || $bid->post_type != BID) {
        wp_send_json($error);
    }

    $bid_budget = get_post_meta($bid_id, 'bid_budget', true);

    // get commission settings
    $commission = ae_get_option('commission', 0);
    $commission_fee = $commission;

    // caculate commission fee by percent
    $commission_type = ae_get_option('commission_type');
    if ($commission_type != 'currency') {
        $commission_fee = ((float)($bid_budget * (float)$commission)) / 100;
    }

    $commission = fre_price_format($commission_fee);
    $payer_of_commission = ae_get_option('payer_of_commission', 'project_owner');
    if ($payer_of_commission == 'project_owner') {
        $total = (float)$bid_budget + (float)$commission_fee;
    }
    else {
        $commission = 0;
        $total = $bid_budget;
    }
    $number_format = ae_get_option('number_format');
    $decimal = (isset($number_format['et_decimal'])) ? $number_format['et_decimal'] : get_theme_mod('et_decimal', 2);
    $data = array(
        'budget'=>$bid_budget,
        'commission'=>$commission,
        'total'=>round((double)$total, $decimal)
        );
    $data = apply_filters( 'ae_accept_bid_infor', $data);
    wp_send_json(array(
        'success' => true,
        'data' => array(
            'budget' => fre_price_format($data['budget']) ,
            'commission' => $data['commission'],
            'total' => fre_price_format($data['total']),
            'data_not_format' => $data
        )
    ));
}
add_action('wp_ajax_ae-accept-bid-info', 'fre_get_accept_bid_info');

/**
 * ajax callback process bid escrow and send redirect url to client
 * This method run after employer accept project for a freelancer.
 * @author Dakachi
 */
function fre_escrow_bid() {
    global $user_ID;
    $error = array(
        'success' => false,
        'msg' => __('Invalid bid', ET_DOMAIN)
    );
    if (!isset($_REQUEST['bid_id'])) {
        wp_send_json($error);
    }
    $bid_id = $_REQUEST['bid_id'];
    $bid = get_post($bid_id);

    // check bid is valid
    if (!$bid || is_wp_error($bid) || $bid->post_type != BID) {
        wp_send_json($error);
    }

    // currency settings
    $currency = ae_get_option('currency');
    $currency = $currency['code'];

    $bid_budget = get_post_meta($bid_id, 'bid_budget', true);

    // get commission settings
    $commission = ae_get_option('commission', 0);
    $commission_fee = $commission;

    // caculate commission fee by percent
    $commission_type = ae_get_option('commission_type');
    if ($commission_type != 'currency') {
        $commission_fee = ((float)($bid_budget * (float)$commission)) / 100;
    }
    $payer_of_commission = ae_get_option('payer_of_commission', 'project_owner');

    if ($payer_of_commission == 'project_owner') {
        $total = (float)$bid_budget + (float)$commission_fee;
    }
    else {
        $total = $bid_budget;
        $bid_budget = (float)$total - (float)$commission_fee;
    }

    // get URL Project
    $post_id     = get_post_field('post_parent', $bid_id);
    $post_url  = get_permalink( $post_id );

    $escrow_data = array(
        'total'=> $total,
        'currency'=>$currency,
        'bid_budget'=> $bid_budget,
        'commission_fee'=> $commission_fee,
        'payer_of_commission'=>$payer_of_commission,
        'bid_author'=> $bid->post_author,
        'bid_id' => $bid_id
        );
    do_action('ae_escrow_payment_gateway', $escrow_data);
    //  when using escrow, employer must setup an paypal account
    $paypal_account = get_user_meta($user_ID, 'paypal', true);
    if(!$paypal_account){
        wp_send_json(array(
            'success'   => false,
            'msg'       => __('You should enter your PayPal email in the account details tab to be received money in case of dispute!', ET_DOMAIN)
            ));
    }
    $receiver = get_user_meta($bid->post_author, 'paypal', true);

    // paypal adaptive process payment and send reponse to client
    $ppadaptive = AE_PPAdaptive::get_instance();
    // get paypal adaptive settings
    $ppadaptive_settings = ae_get_option('escrow_paypal');

    // the admin's paypal business account
    $primary = $ppadaptive_settings['business_mail'];

    // get from setting
    $feesPayer = $ppadaptive_settings['paypal_fee'];

    /**
     * paypal adaptive order data
    */
    $order_data = array(
        'actionType' => 'PAY_PRIMARY',
        'returnUrl' => et_get_page_link('process-payment', array(
            'paymentType' => 'paypaladaptive'
        )) ,
        'cancelUrl' => et_get_page_link('cancel-payment', array(
            'paymentType'   => 'paypaladaptive',
            'returnUrl'     => $post_url
        )) ,

        // 'maxAmountPerPayment' => '35.00',
        'currencyCode' => $currency,
        'feesPayer' => $feesPayer,
        'receiverList.receiver(0).amount' => $total,
        'receiverList.receiver(0).email' => $primary,
        'receiverList.receiver(0).primary' => true,
        // freelancer receiver
        'receiverList.receiver(1).amount' => $bid_budget,
        'receiverList.receiver(1).email' => $receiver,
        'receiverList.receiver(1).primary' => false,
        'requestEnvelope.errorLanguage' => 'en_US'
    );

    $response = $ppadaptive->Pay($order_data);

    if (is_array($response) && isset($response['success']) && !$response['success']) {
        wp_send_json(array(
            'success' => false,
            'msg' => $response['msg']
        ));
    }

    // create order
    $order_post = array(
        'post_type' => 'fre_order',
        'post_status' => 'pending',
        'post_parent' => $bid_id,
        'post_author' => $user_ID,
        'post_title' => 'Pay for accept bid',
        'post_content' => 'Pay for accept bid ' . $bid_id
    );

    if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
        do_action('fre_accept_bid', $bid_id);
        $order_id = wp_insert_post($order_post);
        update_post_meta($order_id, 'fre_paykey', $response->payKey);
        update_post_meta($order_id, 'gateway', 'PPadaptive');

        update_post_meta($bid_id, 'fre_bid_order', $order_id);
        update_post_meta($bid_id, 'fre_paykey', $response->payKey);

        et_write_session('payKey', $response->payKey);
        et_write_session('order_id', $order_id);
        et_write_session('bid_id', $bid_id);
        et_write_session('ad_id', $bid->post_parent);

        $response->redirect_url = $ppadaptive->paypal_url . $response->payKey;
        wp_send_json($response);
    }
    else {
        wp_send_json(array(
            'success' => false,
            'msg' => $response->error[0]->message
        ));
    }
}
add_action('wp_ajax_ae-escrow-bid', 'fre_escrow_bid');

/**
 * dispute process execute payment and send money to freelancer
 * @since 1.3
 * @author Dakachi
 */
function fre_execute_payment() {
    // only the admin or the user have manage_options cap can execute the dispute
    if (!current_user_can('manage_options')) {
        wp_send_json(array(
            'success' => false,
            'msg' => __("You do not have permission to do this action.", ET_DOMAIN)
        ));
    }
    $project_id = $_REQUEST['project_id'];
    $credit_api = ae_get_option( 'escrow_credit_settings' );
    // cho nay co the dung action
    $bid_id_accepted = get_post_meta($project_id, 'accepted', true);
    if( !use_paypal_to_escrow() || (isset($credit_api['use_credit_escrow']) && $credit_api['use_credit_escrow'])){
        // stripe escrow process
        do_action('ae_escrow_execute', $project_id, $bid_id_accepted);
    } else {
        // execute payment and send money to freelancer
        $pay_key = get_post_meta($bid_id_accepted, 'fre_paykey', true);
        if ($pay_key) {
            $ppadaptive = AE_PPAdaptive::get_instance();
            $response = $ppadaptive->executePayment($pay_key);

            if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {

                // success update order data
                $order = get_post_meta($bid_id_accepted, 'fre_bid_order', true);
                if ($order) {
                    wp_update_post(array(
                        'ID' => $order,
                        'post_status' => 'completed'
                    ));
                }

                // success update project status
                wp_update_post(array(
                    'ID' => $project_id,
                    'post_status' => 'disputed'
                ));

	            wp_update_post(array(
		            'ID' => $bid_id_accepted,
		            'post_status' => 'disputed'
	            ));

                // update meta when admin arbitrate
                if(isset($_REQUEST['comment']) && isset($_REQUEST['winner'])){
                    $comment = $_REQUEST['comment'];
                    $winner = $_REQUEST['winner'];
                    update_post_meta($project_id, 'comment_of_admin', $comment);
                    update_post_meta($project_id, 'winner_of_arbitrate', $winner);
                }
                /**
                 * do action after admin finish dispute and execute send payment to freelancer
                 * @param int $project_id
                 * @param int $bid_id_accepted
                 * @param int $order
                 * @since 1.3
                 * @author Dakachi
                 */
                do_action('fre_dispute_execute_payment', $project_id, $bid_id_accepted, $order);
                do_action('fre_resolve_project_notification', $project_id);
                // send mail
                $mail = Fre_Mailing::get_instance();
                $mail->execute_payment($project_id, $bid_id_accepted);

                wp_send_json(array(
                    'success' => true,
                    'msg' => __("Send payment successful.", ET_DOMAIN)
                ));
            }
            else {
                wp_send_json(array(
                    'success' => false,
                    'msg' => $response->error[0]->message
                ));
            }
        }
        wp_send_json(array(
            'success' => false,
            'msg' => __("Invalid paykey.", ET_DOMAIN)
        ));
    }
}
add_action('wp_ajax_execute_payment', 'fre_execute_payment');

/**
 * dispute process refund payment to employer
 * @since 1.3
 * @author Dakachi
 */
function fre_refund_payment() {
    if (!current_user_can('manage_options')) {
        wp_send_json(array(
            'success' => false,
            'msg' => __("You do not have permission to do this action.", ET_DOMAIN)
        ));
    }
    $project_id = $_REQUEST['project_id'];
    $credit_api = ae_get_option( 'escrow_credit_settings' );
    // cho nay co the dung action
    $bid_id_accepted = get_post_meta($project_id, 'accepted', true);
    if( !use_paypal_to_escrow() || (isset($credit_api['use_credit_escrow']) && $credit_api['use_credit_escrow'])){
        // only stripe escrow process
        do_action('ae_escrow_refund', $project_id, $bid_id_accepted);
    } else {
        // only paypal escrow process
        // execute payment and send money to freelancer
        $pay_key = get_post_meta($bid_id_accepted, 'fre_paykey', true);
        $bid_budget = get_post_meta($bid_id_accepted, 'bid_budget', true);
        $currency = ae_get_option('currency');
        $currency = $currency['code'];

        if ($pay_key) {
             $ppadaptive_settings = ae_get_option('escrow_paypal');
            // the admin's paypal business account
            $primary = $ppadaptive_settings['business_mail'];
            $ppadaptive = AE_PPAdaptive::get_instance();

            $order = array(
                'payKey' => $pay_key,
                'receiverList.receiver(0).email' => $primary,
                'receiverList.receiver(0).amount' => $bid_budget,
                'requestEnvelope.errorLanguage' => 'en_US',
                'currencyCode' => $currency
            );
            $response = $ppadaptive->Refund($order);
            if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {

                // success update order data
                $order = get_post_meta($bid_id_accepted, 'fre_bid_order', true);
                if ($order) {
                    wp_update_post(array(
                        'ID' => $order,
                        'post_status' => 'refund'
                    ));
                }

                // success update project status
                wp_update_post(array(
                    'ID' => $project_id,
                    'post_status' => 'disputed'
                ));

	            // success update bid status
	            wp_update_post(array(
		            'ID' => $bid_id_accepted,
		            'post_status' => 'disputed'
	            ));

                // update meta when admin arbitrate
                if(isset($_REQUEST['comment']) && isset($_REQUEST['winner'])){
                    $comment = $_REQUEST['comment'];
                    $winner = $_REQUEST['winner'];
                    update_post_meta($project_id, 'comment_of_admin', $comment);
                    update_post_meta($project_id, 'winner_of_arbitrate', $winner);
                }
                /**
                 * do action after admin finish dispute and refund payment
                 * @param int $project_id
                 * @param int $bid_id_accepted
                 * @param int $order
                 * @since 1.3
                 * @author Dakachi
                 */
                do_action('fre_dispute_refund_payment', $project_id, $bid_id_accepted, $order);
                do_action('fre_resolve_project_notification', $project_id);
                $mail = Fre_Mailing::get_instance();
                $mail->refund($project_id, $bid_id_accepted);

                // send json back
                wp_send_json(array(
                    'success' => true,
                    'msg' => __("Send payment successful.", ET_DOMAIN) ,
                    'data' => $response
                ));
            }
            else {
                wp_send_json(array(
                    'success' => false,
                    'msg' => $response->error[0]->message
                ));
            }
        }
        wp_send_json(array(
            'success' => false,
            'msg' => __("Invalid paykey.", ET_DOMAIN)
        ));
    }
}
add_action('wp_ajax_refund_payment', 'fre_refund_payment');

/**
 * ajax callback to transfer payment to freelancer
 * @since 1.3
 * @author Dakachi
 */
function fre_transfer_money() {
    if (current_user_can('manage_options')) {
        $project_id = $_REQUEST['project_id'];
        // cho nay co the dung action
        $bid_id_accepted = get_post_meta($project_id, 'accepted', true);
        $credit_api = ae_get_option( 'escrow_credit_settings' );
        if( !use_paypal_to_escrow() || (isset($credit_api['use_credit_escrow']) && $credit_api['use_credit_escrow'])){
            do_action('fre_transfer_money_ajax', $project_id, $bid_id_accepted);
        }else{
            // execute payment and send money to freelancer
            $pay_key = get_post_meta($bid_id_accepted, 'fre_paykey', true);
            if ($pay_key) {

                $ppadaptive = AE_PPAdaptive::get_instance();
                $response = $ppadaptive->executePayment($pay_key);
                if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
                    // success update order data
                    $order = get_post_meta($bid_id_accepted, 'fre_bid_order', true);
                    if ($order) {
                        wp_update_post(array(
                            'ID' => $order,
                            'post_status' => 'finish'
                        ));
                    }
                    // send mail
                    $mail = Fre_Mailing::get_instance();
                    $mail->execute($project_id, $bid_id_accepted);
                    // send json back
                    wp_send_json(array(
                        'success' => true,
                        'msg' => __("The payment has been successfully transferred.", ET_DOMAIN) ,
                        'data' => $response
                    ));
                }else {
                    wp_send_json(array(
                        'success' => false,
                        'msg' => $response->error[0]->message
                    ));
                }
            }
            else {
                wp_send_json(array(
                    'success' => false,
                    'msg' => __("Invalid paykey.", ET_DOMAIN)
                ));
            }
        }
    }
}
add_action('wp_ajax_transfer_money', 'fre_transfer_money');

/**
 * ajax callback to get information transfer payment to freelancer
 * @since 1.3
 * @author ThanhTu
 */
function fre_transfer_money_info(){
    if (ae_get_option('use_escrow')) {
        $project_id = $_REQUEST['project_id'];
        $bid_id_accepted = get_post_meta($project_id, 'accepted', true);
        $credit_api = ae_get_option( 'escrow_credit_settings' );
        // get commission settings
        $commission = ae_get_option('commission', 0);
        $commission_fee = $commission;
        $payer_of_commission = ae_get_option('payer_of_commission');
        $data = array(
            'bid_budget'        => '',
            'commission_fee'    => '',
            'amount'            => '',
            'message'           => '',
            'success'           => false
        );
        if(ae_get_option('manual_transfer')){
            $bid_budget = get_post_meta($bid_id_accepted, 'bid_budget', true);
            // caculate commission fee by percent
            $commission_type = ae_get_option('commission_type');
            if ($commission_type != 'currency') {
                $commission_fee = ((float)($bid_budget * (float)$commission)) / 100;
            }
            $payer_of_commission = ae_get_option('payer_of_commission', 'project_owner');
            if ($payer_of_commission == 'project_owner') {
                $amount = (float)$bid_budget;
                $message = sprintf(__("You are about to transfer the money %s to the freelancer. Please check the info below about the money.", ET_DOMAIN), '<strong>'.fre_price_format($amount).'</strong>');
                $data = array(
                        'bid_budget'        => fre_price_format($bid_budget),
                        'commission_fee'    => '-'.fre_price_format(0),
                        'amount'            => fre_price_format($amount),
                        'message'           => $message,
                        'success' => true
                    );
            }else if($payer_of_commission == 'worker'){
                $total = $bid_budget;
                $amount = (float)$total - (float)$commission_fee;
                $message = sprintf(__("You are about to transfer the money %s to the freelancer. Please check the info below about the money.", ET_DOMAIN), '<strong>'.fre_price_format($amount).'</strong>');
                $data = array(
                        'bid_budget'        => fre_price_format($bid_budget),
                        'commission_fee'    => '-'.fre_price_format($commission_fee),
                        'amount'            => fre_price_format($amount),
                        'message'           => $message,
                        'success'           => true
                    );
            }
        }
        wp_send_json(array('data' => $data));
    }
}
add_action('wp_ajax_transfer_money_info', 'fre_transfer_money_info');

/**
 * finish project, send money when freelancer review project
 * @param int $project_id
 * @since 1.3
 * @author Dakachi
 */
function fre_finish_escrow($project_id) {
    if (ae_get_option('use_escrow')) {
        $bid_id_accepted = get_post_meta($project_id, 'accepted', true);
        $credit_api = ae_get_option( 'escrow_credit_settings' );
        if (!ae_get_option('manual_transfer')) {
            if(isset($credit_api['use_credit_escrow']) && $credit_api['use_credit_escrow']){
                do_action('fre_finish_escrow', $project_id, $bid_id_accepted);
            }else if( !use_paypal_to_escrow() ){
                // stripe escrow or third party escrow system procress.
                do_action('fre_finish_escrow', $project_id, $bid_id_accepted);
            } else{
                // paypal escrow process
                // execute payment and send money to freelancer
                $pay_key = get_post_meta($bid_id_accepted, 'fre_paykey', true);
                if ($pay_key) {
                    $ppadaptive = AE_PPAdaptive::get_instance();
                    $response = $ppadaptive->executePayment($pay_key);
                    if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
                        // success update order data
                        $order = get_post_meta($bid_id_accepted, 'fre_bid_order', true);
                        if ($order) {
                            wp_update_post(array(
                                'ID' => $order,
                                'post_status' => 'finish'
                            ));
                            $mail = Fre_Mailing::get_instance();
                            $mail->alert_transfer_money($project_id, $bid_id_accepted);
                            $mail->notify_execute($project_id, $bid_id_accepted);
                        }
                    }
                }
            }
        } else {
            $mail = Fre_Mailing::get_instance();
            $mail->alert_transfer_money($project_id, $bid_id_accepted);
        }
    }
}
add_action('fre_employer_review_freelancer', 'fre_finish_escrow');
/**
 * Add escrow account field
 * @param bool true/false
 * @return string $html
 * @since FrE-v1.7
 * @package AE_ESCROW
 * @category PPADAPTIVE
 * @author Tambh
 */
function ae_ppadaptive_recipient_field(){
    if( use_paypal_to_escrow() ) {
        global $user_ID;
        ob_start();
        ?>
        <div class="fre-input-field">
            <label><?php _e('Paypal Account', ET_DOMAIN) ?></label>
            <input type="email" id="paypal" value="<?php echo get_user_meta( $user_ID, 'paypal', true ); ?>" name="paypal" placeholder="<?php _e('Enter your paypal email', ET_DOMAIN) ?>">
        </div>
        
        <?php
        $html = ob_get_clean();
        $html = apply_filters('ae_escrow_recipient_field_html', $html);
        echo $html;
    }
}
add_action('ae_escrow_recipient_field', 'ae_ppadaptive_recipient_field');