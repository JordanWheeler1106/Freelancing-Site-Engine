<!-- Step 4 -->
<?php 
    global $user_ID;
    $step = 4;

    $disable_plan = ae_get_option('disable_plan', false);
    if($disable_plan) $step--;
    if($user_ID) $step--;
?>
<div id="fre-post-project-3 step-payment" class="fre-post-project-step step-wrapper step-payment ">
    <div class="fre-post-project-box">
        <div class="step-edit-project">
            <p><?php _e('Your posted project has been saved and waiting for the payment to become available on site.', ET_DOMAIN);?></p>
            <a class="fre-btn-o fre-post-project-previous-btn fre-btn-previous" href=""><?php _e('Edit', ET_DOMAIN);?></a>
        </div>
    </div>
    <div class="fre-post-project-box">
        <div class="step-choose-payment">
            <?php 
                if(isset($_REQUEST['id'])) {
                    $post = get_post($_REQUEST['id']);
                    if($post) {
                        global $ae_post_factory;
                        $post_object = $ae_post_factory->get($post->post_type);
                        $post_convert = $post_object->convert($post);
                        echo '<script type="data/json"  id="edit_postdata">'. json_encode($post_convert) .'</script>';
                    }
                    //get skills
                    $current_skills = get_the_terms( $_REQUEST['id'], 'skill' );
                }
                $total_package = ae_user_get_total_package($user_ID);
            ?>
            <div class="show_select_package">
                <p class="package_title"><?php _e('Your package:', ET_DOMAIN);?> <strong></strong></p>
                <p class="package_description"></p>
            </div>
            <div class="show_had_package" style="display:none;">
                <p><?php printf(__('Your post(s) left: %s', ET_DOMAIN), $total_package);?></p>
                <p><?php _e('If you want to get more posts, you can directly move to purchase page by clicking the next button.', ET_DOMAIN);?></p>
                <?php 
                    ob_start();
                    ae_user_package_info($user_ID);
                    $package = ob_get_clean();
                    if($package != '') {
                        echo $package;
                    }
                ?>
            </div>
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
                    <li>
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
                                <p>
                                    <?php _e('Amount need to be transferred:', ET_DOMAIN);?>
                                    <br/>
                                    <span class="cash_amount">...</span>
                                </p>
                                <p>
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