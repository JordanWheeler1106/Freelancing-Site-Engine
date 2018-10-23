<?php
if(!class_exists('WPBakeryShortCode')) return;
class WPBakeryShortCode_fre_pricing_block extends WPBakeryShortCode {

    protected function content($atts, $content = null) {

        $custom_css = $el_class = $title = $icon = $output = $s_content = $m_link = $payment_plan_feature = $payment_plan = '';

        extract(shortcode_atts(array(
            'el_class'      => '',
            'payment_plan'     => '',
            'package_plan_block_0' => '',
            'package_plan_block_1' => '',
            'package_plan_block_2' => '',
            'package_plan_block_3' => '',
            'active_block_0' => '',
            'active_block_1' => '',
            'active_block_2' => '',
            'active_block_3' => '',

        ), $atts));
        /* ================  Render Shortcodes ================ */
        ob_start();
        ?>
        <!-- PRICING -->
        <?php
        global $ae_post_factory;
        $ae_pack = AE_Package::get_instance();
        $ae_pack = $ae_post_factory->get('pack');
        $packs = $ae_pack->fetch();
        $pack_currency = ae_get_option('currency');
        $active_block = array( $active_block_0, $active_block_1, $active_block_2, $active_block_3 );
        $arr_packages = array(
            'package_plan_block_0'=> $package_plan_block_0,
            'package_plan_block_1'=> $package_plan_block_1,
            'package_plan_block_2'=> $package_plan_block_2,
            'package_plan_block_3'=> $package_plan_block_3
            );
        $number_plan = 0;
        $i = 0;
        foreach ($arr_packages as $key => $value) {
            if( $value != '' ){
                $number_plan++;
                foreach ($packs as $key1 => $package) {
                    $pack = $ae_pack->convert($package);
                    $pack->is_block_active = $active_block[$i];
                    if( $pack->ID == $value ){
                        $arr_packages[$key] = $pack;
                        break;
                    }
                }
            }
            $i++;
        }
        $col = 'col-md-3 col-sm-6 col-xs-6';
        $class_width='';
        if($number_plan == 3) {
            $col = 'col-md-4 col-sm-4 col-xs-6';
            $class_width='width-880';
        }

        if($number_plan == 2) {
            $col = 'col-md-6 col-sm-6 col-xs-6';
            $class_width='width-580';
        }
        ?>
        <div class="pricing-container">
            <div class="container  <?php echo $class_width ?> " >
            <div class="row">
            <?php
            foreach ($arr_packages as $key => $pack) {

                if( !empty( $pack ) && is_object($pack)){
                    ?>
                    <div class="<?php echo $col ?> pricing-item">
                        <div class="pricing <?php echo ($pack->is_block_active == '1')? 'active' : ''; ?> ">
                            <div class="pricing-number pricing-wrapper">
                                <h2 class="price">
                                    <?php
                                    if($pack->et_price > 0) {
                                        ae_price($pack->et_price);
                                    }else {
                                        _e("FREE", ET_DOMAIN);
                                    }
                                    ?>
                                </h2>
                                <span>
                                    <?php echo $pack->backend_text; ?>
                                </span>
                            </div>
                            <div class="pricing-content">
                                <h3 class="pricing-title"><?php echo $pack->post_title; ?></h3>
                                <div class="pricing-detail">
                                    <?php echo $pack->post_content; ?>
                                </div>
                                <div class="submit-price">
                                    <a href="<?php echo et_get_page_link( array('page_type' => 'submit-project') ); ?>" class="btn-sumary btn-price  ">
                                        <?php
                                        if(!is_user_logged_in() ) {
                                            _e('Sign Up', ET_DOMAIN);
                                        }else{
                                            _e("Submit Project", ET_DOMAIN);
                                        }?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
             </div>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        /* ================  Render Shortcodes ================ */
        return $output;
    }
}
$packs = query_posts( 'post_type=pack' );
$packs_arr1= array('none'=> '');
$packs_arr = array();
foreach ($packs as $key => $package){
    $packs_arr[$package->post_title] = $package->ID;
    $packs_arr1[$package->post_title] = $package->ID;
}
wp_reset_query();
vc_map( array(
    "base"      => "fre_pricing_block",
    "name"      => __("FrE Pricing block", ET_DOMAIN),
    "class"     => "",
    "icon"      => "icon-wpb-de_service",
    "category" => __("FreelanceEngine", ET_DOMAIN),
    "params"    => array(
        // array (
        //     "type"       => "textfield",
        //     "class"      => "",
        //     "heading"    => __("Tagline", ET_DOMAIN),
        //     "param_name" => "tagline",
        //     "value"      => 'per month'
        // ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Select the package plan for block 1", ET_DOMAIN),
            "param_name" => "package_plan_block_0",
            "value"      => $packs_arr,
        ),
        array(
            "type"       => "checkbox",
            "class"      => "",
            "heading"    => '',
            "param_name" => "active_block_0",
            "value"      => array( __("Make this block highlight", ET_DOMAIN) => '1' )
        ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Select the package plan for block 2", ET_DOMAIN),
            "param_name" => "package_plan_block_1",
            "value"      => $packs_arr,
        ),
         array(
            "type"       => "checkbox",
            "class"      => "",
            "heading"    => '',
            "param_name" => "active_block_1",
            "value"      => array( __("Make this block highlight", ET_DOMAIN) => '1' )
        ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Select the package plan for block 3", ET_DOMAIN),
            "param_name" => "package_plan_block_2",
            "value"      => $packs_arr1,
        ),
         array(
            "type"       => "checkbox",
            "class"      => "",
            "heading"    => '',
            "param_name" => "active_block_2",
            "value"      => array( __("Make this block highlight", ET_DOMAIN) => '1' )
        ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Select the package plan for block 4", ET_DOMAIN),
            "param_name" => "package_plan_block_3",
            "value"      => $packs_arr1,
        ),
         array(
            "type"       => "checkbox",
            "class"      => "",
            "heading"    => '',
            "param_name" => "active_block_3",
            "value"      => array( __("Make this block highlight", ET_DOMAIN) => '1' )
        ),
    )
));




