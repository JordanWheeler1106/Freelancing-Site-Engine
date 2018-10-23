<!-- Step 4 -->
<?php
    global $user_ID;
    $step = 3;

    $disable_plan = ae_get_option('disable_plan', false);
    if($disable_plan) $step--;
    if($user_ID) $step--;

?>
<div class="step-wrapper step-payment" id="step-payment">
	<a href="#" class="step-heading active">
    	<span class="number-step"><?php echo $step; ?></span>
        <span class="text-heading-step"><?php _e("Select payment method", ET_DOMAIN); ?></span>
        <i class="fa fa-caret-right"></i>
    </a>
    <div class="step-content-wrapper content" style="display:none;">
        <?php do_action( 'before_payment_list_wrapper' ); ?>

        <form method="post" action="" id="checkout_form">
            <div class="payment_info"><input type="hidden" name="packageType" class="packageType" />  </div>
            <div style="position:absolute; left : -7777px; " >
                <input type="submit" id="payment_submit" />
            </div>
        </form>

    	<ul class="list-price">
        <?php
            $paypal = ae_get_option('paypal');
            if($paypal['enable']) {
        ?>
        	<li>
                <span class="title-plan select-payment" data-type="paypal">
                    <?php _e("Paypal", ET_DOMAIN); ?>
                    <span><?php _e("Send your payment via Paypal.", ET_DOMAIN); ?></span>
                </span>
                <a href="#" class="btn btn-submit-price-plan select-payment" data-type="paypal"><?php _e("Select", ET_DOMAIN); ?></a>
            </li>
        <?php }
            $co = ae_get_option('2checkout');
            if($co['enable']) {
         ?>
            <li>
                <span class="title-plan select-payment" data-type="2checkout">
					<?php _e("2Checkout", ET_DOMAIN); ?>
                    <span><?php _e("Send your payment via 2Checkout.", ET_DOMAIN); ?></span>
                </span>
                <a href="#" class="btn btn-submit-price-plan select-payment" data-type="2checkout"><?php _e("Select", ET_DOMAIN); ?></a>
            </li>
        <?php
        }
            $cash = ae_get_option('cash');
            if($cash['enable']) {
        ?>
            <li>
                <span class="title-plan select-payment" data-type="cash">
                    <?php _e("Cash", ET_DOMAIN); ?>
                    <span><?php _e("Send your cash payment to our bank account", ET_DOMAIN); ?></span>
                </span>
                <a href="#" class="btn btn-submit-price-plan select-payment" data-type="cash"><?php _e("Select", ET_DOMAIN); ?></a>
            </li>
        <?php }
             do_action( 'after_payment_list_upgrade_account' );
        ?>
        </ul>
        <?php do_action( 'after_payment_list_wrapper' ); ?>
    </div>
</div>

<!-- Step 4 / End -->