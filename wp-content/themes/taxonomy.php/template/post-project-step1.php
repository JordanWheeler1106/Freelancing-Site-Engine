<!-- Step 1 -->
<?php
    global $user_ID, $ae_post_factory;
    $ae_pack = $ae_post_factory->get('pack');
    $packs = $ae_pack->fetch('pack');

    $package_data = AE_Package::get_package_data( $user_ID );

    $orders = AE_Payment::get_current_order($user_ID);

    $number_free_plan_used = AE_Package::get_used_free_plan($user_ID);
?>
<div id="fre-post-project-1 step-plan" class="fre-post-project-step step-wrapper step-plan active">
    <div class="fre-post-project-box">
        <div class="step-post-package">
            <h2><?php _e('Choose your most appropriate package', ET_DOMAIN)?></h2>
            <ul class="fre-post-package">
                <?php foreach ($packs as $key => $package) {
                    $number_of_post =   $package->et_number_posts;
                    $sku = $package->sku;
                    $text = '';
                    $order = false;
                    if($number_of_post >= 1 ) {
                        // get package current order
                        if(isset($orders[$sku])) {
                            $order = get_post($orders[$sku]);
                        }
                        
                        if( isset($package_data[$sku] ) && isset($order->post_status) && $order->post_status != 'draft') {
                            $package_data_sku = $package_data[$sku];
                            if(isset($package_data_sku['qty']) && $package_data_sku['qty'] > 0 ){
                            /**
                             * print text when company has job left in package
                            */
                            $number_of_post =   $package_data_sku['qty'];
                            }
                        }

                        if( !$package->et_price ) {
                            $number_of_post = (int)$number_of_post - (int)$number_free_plan_used;

                            if($number_of_post < 0) $number_of_post = 0;
                        }

                        if( $package->et_price ) {
                            $price = fre_price_format($package->et_price);
                        }else {
                            $price = __("Free", ET_DOMAIN);
                        }
                        $disabled = '';

                        if($package->et_duration > 0){
                            if($package->et_duration > 1){
                                $duration_text = sprintf(__('%s days', ET_DOMAIN), $package->et_duration);
                            }else{
                                $duration_text = sprintf(__('%s day', ET_DOMAIN), $package->et_duration);
                            }
                        }else{
                            $duration_text = sprintf(__('%s days', ET_DOMAIN), $package->et_duration);
                        }
                        if($number_of_post > 0 ) {
                            if($number_of_post > 1){
                                $text = sprintf(__("%s for %s projects, displaying in %s.", ET_DOMAIN) , $price, $number_of_post, $duration_text);
                            }else{
                                $text = sprintf(__("%s for %s project, displaying in %s.", ET_DOMAIN) , $price, $number_of_post, $duration_text);
                            }
                        }else {
                            $disabled = 'disabled';
                            $text = sprintf(__("%s for %s projects, displaying in %s.", ET_DOMAIN) , $price, $number_of_post, $duration_text);
                        }
                    }
                    $class_select = '';
                    $checked = false;
                    if( isset($package_data[$sku] )){
                        $package_data_sku = $package_data[$sku];
                        if($package->et_price > 0 && isset($package_data_sku['qty']) && $package_data_sku['qty'] > 0 ) {
                            $order = get_post($orders[$sku]);
                            if( $order && !is_wp_error( $order ) && $order->post_status != 'draft'){
                                $class_select = 'auto-select '.$order->post_status ;
                                $checked = true;
                            }
                        }
                    }
                ?>
                    <li class="<?php echo $class_select;?> <?php echo $disabled;?>" data-sku="<?php echo trim($package->sku);?>" data-id="<?php echo $package->ID ?>" data-price="<?php echo $package->et_price; ?>" data-package-type="<?php echo $package->post_type; ?>" data-title="<?php echo $package->post_title ;?>" data-description="<?php echo $text;?>">
                        <label class="fre-radio" for="package-<?php echo $package->ID?>">
                            <input id="package-<?php echo $package->ID?>" name="post-package" type="radio" <?php echo $disabled; echo ($checked) ? "checked='checked'" : '' ;?>>
                            <span><?php echo $package->post_title ; ?></span>
                        </label>
                        <span class="disc"><?php echo $text;?> <?php echo wp_strip_all_tags( $package->post_content );?></span>
                    </li>
                <?php } ?>
            </ul>
            <?php echo '<script type="data/json" id="package_plans">'.json_encode($packs).'</script>'; ?>
            <div class="fre-select-package-btn">
                <!-- <a class="fre-btn" href="">Select Package</a> -->
                <input class="fre-btn fre-post-project-next-btn select-plan" type="button" value="<?php _e('Next Step', ET_DOMAIN);?>">
            </div>
        </div>
    </div>
</div>

<!-- Step 1 / End -->