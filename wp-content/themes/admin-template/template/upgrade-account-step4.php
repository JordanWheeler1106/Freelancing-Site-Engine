<!-- Step 4 -->
<?php 
    global $user_ID;
?>
<div id="fre-post-project-2 step-post" class="fre-post-project-step step-wrapper step-post ">
    <div class="fre-post-project-box">
        <div class="step-change-package show_select_package">
            <p class="package_title"><?php _e('Your package:', ET_DOMAIN);?> <strong></strong></p>
            <p class="package_description"></p>
            <a class="fre-btn-o fre-post-project-previous-btn fre-btn-previous" href="#"><?php _e('Change package', ET_DOMAIN);?></a>
        </div>
    </div>
    <div class="fre-post-project-box">
        <div class="step-choose-payment">
            <h2><?php _e('Payment Method', ET_DOMAIN);?><br><span><?php _e('Select your most appropriate payment method', ET_DOMAIN);?></span></h2>
            <?php do_action( 'before_payment_list_wrapper' ); ?>
            <form method="post" action="" id="checkout_form">
                <div class="payment_info"></div>
                <div style="position:absolute; left : -7777px; " >
                    <input type="submit" id="payment_submit" />
                </div>
            </form>

            <ul id="fre-payment-accordion" class="fre-payment-list panel-group">
                <?php
                    $paypal = ae_get_option('paypal');
                    if($paypal['enable']) {
                ?>
                    <li class="panel">
                        <span class="title-plan" data-type="paypal">
                            <?php _e("Paypal", ET_DOMAIN); ?>
                            <span><?php _e("Send your payment via Paypal.", ET_DOMAIN); ?></span>
                        </span>
                        <a data-toggle="collapse" data-parent="#fre-payment-accordion" href="#fre-payment-paypal" class="btn collapsed select-payment" data-type="paypal"><?php _e("Select", ET_DOMAIN); ?></a>
                    </li>
                <?php }
                    $co = ae_get_option('2checkout');
                    if($co['enable']) {
                 ?>
                    <li class="panel">
                        <span class="title-plan" data-type="2checkout">
                            <?php _e("2Checkout", ET_DOMAIN); ?>
                            <span><?php _e("Send your payment via 2Checkout.", ET_DOMAIN); ?></span>
                        </span>
                        <a href="#" class="btn collapsed btn-submit-price-plan select-payment" data-type="2checkout"><?php _e("Select", ET_DOMAIN); ?></a>
                    </li>
                <?php
                }
                    $cash = ae_get_option('cash');
                    if($cash['enable']) {
                ?>
                    <li class="panel">
                        <span class="title-plan" data-type="cash">
                            <?php _e("Cash", ET_DOMAIN); ?>
                            <span><?php _e("Transfer money directly to our bank account.", ET_DOMAIN); ?></span>
                        </span>
                        <a data-toggle="collapse" data-type="cash" data-parent="#fre-payment-accordion" href="#fre-payment-cash" class="btn collapsed other-payment"><?php _e("Select", ET_DOMAIN); ?></a>
                        <div id="fre-payment-cash" class="panel-collapse collapse fre-payment-proccess">
                            <div class="fre-payment-cash">
                                <p class="title-name">
                                    <?php _e('Amount need to be transferred:', ET_DOMAIN);?>
                                    <br/>
                                    <strong><span class="cash_amount">...</span></strong>
                                </p>
                                <p class="title-name">
                                    <?php _e('Transfer to bank account:', ET_DOMAIN);?>
                                    <br/>
                                    <span class="info_cash">
                                        <?php 
                                            $cash_options = ae_get_option('cash');
                                            
                                        ?>
                                    </span>
                                </p>
                                <strong class="cash-message"><?php echo $cash_options['cash_message']; ?></strong>
                            </div>
                            <a href="#" class="fre-btn select-payment" data-type="cash"><?php _e("Make Payment", ET_DOMAIN); ?></a>    
                        </div>
                    </li>
                <?php }
                    do_action( 'after_payment_list' );
                ?>
            </ul>
            <?php do_action( 'after_payment_list_wrapper' ); ?>
        </div>
    </div>
</div>
<!-- Step 4 / End -->