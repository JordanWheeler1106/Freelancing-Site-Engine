<?php

/**
 * this file contain all function related to places
 */
add_action('init', 'de_init_package');
function de_init_package() {

    register_post_type('pack', array(
        'labels' => array(
            'name' => __('Pack', ET_DOMAIN) ,
            'singular_name' => __('Pack', ET_DOMAIN) ,
            'add_new' => __('Add New', ET_DOMAIN) ,
            'add_new_item' => __('Add New Pack', ET_DOMAIN) ,
            'edit_item' => __('Edit Pack', ET_DOMAIN) ,
            'new_item' => __('New Pack', ET_DOMAIN) ,
            'all_items' => __('All Packs', ET_DOMAIN) ,
            'view_item' => __('View Pack', ET_DOMAIN) ,
            'search_items' => __('Search Packs', ET_DOMAIN) ,
            'not_found' => __('No Pack found', ET_DOMAIN) ,
            'not_found_in_trash' => __('NoPacks found in Trash', ET_DOMAIN) ,
            'parent_item_colon' => '',
            'menu_name' => __('Packs', ET_DOMAIN)
        ) ,
        'public' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,

        'capability_type' => 'post',
        // 'capabilities' => array(
        //     'manage_options'
        // ) ,
        'has_archive' => 'packs',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array(
            'title',
            'editor',
            'author',
            'custom-fields'
        )
    ));

    $package = new AE_Package('pack', array('project_type'));
    $pack_action = new AE_PackAction($package);

    global $ae_post_factory;
    $ae_post_factory->set('pack', $package);
}

class FRE_Payment extends AE_Payment
{

    function __construct() {
        $this->no_priv_ajax = array();
        $this->priv_ajax = array(
            'et-setup-payment'
        );
        $this->init_ajax();
    }

    public function get_plans() {
        global $ae_post_factory;
        $packageType = 'pack';
        if( isset( $_POST['packageType'] ) && $_POST['packageType'] != '' ){
            $packageType = $_POST['packageType'];
        }
        $pack = $ae_post_factory->get( $packageType );
        return $pack->fetch();
    }
}

new FRE_Payment();


/**
 * render user package info
 * @param Integer $user_ID the user_ID want to render
 *
 * @package AE Package
 * @category payment
 *
 * @since 2.0
 * @author ThanhTu
 */
function fre_user_package_info($user_ID) {
    if (!$user_ID) return;
    $user_role = ae_user_role($user_ID);
    if($user_role == FREELANCER) return;
    global $ae_post_factory;
    $ae_pack = $ae_post_factory->get('pack');
    $packs = $ae_pack->fetch();
    $orders = AE_Payment::get_current_order($user_ID);
    $package_data = AE_Package::get_package_data($user_ID);
    $flag = true;
    $packages = array();
    foreach ($packs as $package) {
        $sku = $package->sku;
        if (isset($package_data[$sku]) && $package_data[$sku]['qty'] > 0) {
            if( $package->post_type == 'pack'){
                $order = get_post($orders[$sku]);
                if (!$order || is_wp_error($order) || !in_array($order->post_status, array('publish', 'pending'))) continue;
                $packages[] = $package;
                 $flag = false;
            }
        }
    }
    ?>
    <div class="setting-profile-wrapper  action-package action-bid">
        <?php if(!$flag){ ?>
            <p class="title-name padding-title"><?php _e('You Purchased:', ET_DOMAIN)?></p>
        <?php } ?>
        <div class="content-bid-action list-purchased">
            <div class="list-package-user">
                <?php
                foreach ($packages as $package) {
                    $sku = $package->sku;
                    $order = get_post($orders[$sku]);
                    $number_of_post = $package_data[$sku]['qty'];
                    echo "<p>";
                    if ($order->post_status == 'publish')
                        printf(__("<span class='text-bold'>%s</span> package and have <span class='text-bold'>%d</span> post(s) left.", ET_DOMAIN) , $package->post_title, $number_of_post);
                    if ($order->post_status == 'pending')
                        printf(__("<span class='text-bold'>%s</span> package and have <span class='text-bold'>%d</span> post(s) left. <br /><span class='text-italic'>Your package is under admin review.</span>", ET_DOMAIN) , $package->post_title, $number_of_post);
                    echo "</p>";
                }
                if($flag){
                    echo  '<p>'.__("There are no packages for project posting.", ET_DOMAIN).'</p>';
                }
                ?>
            </div>
        </div>
    </div>
<?php }