<?php
// silence is gold
require_once dirname(__FILE__) . '/settings.php';
if(ae_get_option('use_escrow')) {
	require_once dirname(__FILE__) . '/ppadaptive.php';
	require_once dirname(__FILE__) . '/paypal.php';
}

function fre_process_escrow($payment_type, $data) {
    $payment_return = array(
        'ACK' => false
    );

    if ($payment_type == 'paypaladaptive') {
        $ppadaptive = AE_PPAdaptive::get_instance();

        $response = $ppadaptive->PaymentDetails($data['payKey']);
        $payment_return['payment_status'] = $response->responseEnvelope->ack;

        // email confirm
        if (strtoupper($response->responseEnvelope->ack) == 'SUCCESS') {
            $payment_return['ACK'] = true;
            // UPDATE order
            $paymentInfo = $response->paymentInfoList->paymentInfo;
            if ($paymentInfo[0]->transactionStatus == 'COMPLETED') {
                wp_update_post(array(
                    'ID' => $data['order_id'],
                    'post_status' => 'publish'
                ));
                // assign project
                $bid_action = Fre_BidAction::get_instance();
                $bid_action->assign_project($data['bid_id']);
            }

            if ($paymentInfo[0]->transactionStatus == 'PENDING') {
                //pendingReason
                $payment_return['pending_msg'] = $ppadaptive->get_pending_message($paymentInfo[0]->pendingReason);
                $payment_return['msg'] = $ppadaptive->get_pending_message($paymentInfo[0]->pendingReason);
            }
        }

        if (strtoupper($response->responseEnvelope->ack) == 'FAILURE') {
            $payment_return['msg'] = $response->error[0]->message;
        }
    }
    return apply_filters( 'fre_process_escrow', $payment_return, $payment_type, $data);
}