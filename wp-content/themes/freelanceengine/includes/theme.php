<?php
if (!function_exists('fre_project_demonstration')) {

    /**
     * render project desmonstration settings in hompage
     * @param bool $home if true render home page desmonstration/ false render list project demonstration
     * @since v1.0
     * @author Dakachi
     */
    function fre_project_demonstration($home = false) {
        $project_demonstration = ae_get_option('project_demonstration');
        if ($home) {
            echo $project_demonstration['home_page'];
            return;
        }
        echo $project_demonstration['list_project'];
    }
}

if (!function_exists('fre_profile_demonstration')) {

    /**
     * render profile desmonstration settings in header
     * @param bool $home if true render home page desmonstration/ false render list project demonstration
     * @since v1.0
     * @author Dakachi
     */
    function fre_profile_demonstration($home = false) {
        $project_demonstration = ae_get_option('profile_demonstration');
        if ($home) {
            echo $project_demonstration['home_page'];
            return;
        }
        echo $project_demonstration['list_profile'];
    }
}

if (!function_exists('fre_logo')) {

    /**
     * render site logo image get from option
     * @author tam
     * @return void
     */
    function fre_logo($option_name = '') {
        if ($option_name == '') {
            if (is_front_page()) {
                $option_name = 'site_logo_white';
            } else {
                $option_name = 'site_logo_black';
            }
        }
        switch ($option_name) {
            case 'site_logo_black':
                $img = get_template_directory_uri() . "/img/logo-fre-black.png";
                break;

            case 'site_logo_white':
                $img = get_template_directory_uri() . "/img/logo-fre-white.png";
                break;

            default:
                $img = get_template_directory_uri() . "/img/logo-fre-black.png";
                break;
        }
        $options = AE_Options::get_instance();

        // save this setting to theme options
        $site_logo = $options->$option_name;
        if (!empty($site_logo)) {
            $img = $site_logo['large'][0];
        }
        echo '<img alt="' . $options->blogname . '" src="' . $img . '" />';
    }
}

if(!function_exists('fre_logo_mobile')){
    /**
     * render site mobile logo image get from option
     * @author Tuandq
     * @return void
     */
    function fre_logo_mobile() {
        $img = get_template_directory_uri() . "/img/logo-fre-white.png";
        $options = AE_Options::get_instance();
        // save this setting to theme options
        $mobile_site_logo = $options->mobile_logo;
        if (!empty($mobile_site_logo))
            $img = $mobile_site_logo['large'][0];
        else
            $img = get_template_directory_uri() . "/img/logo-fre-white.png";
        echo '<img alt="' . $options->blogname . '" src="' . $img . '" />';
    }
}

/**
 * check site option shared role or not
 * @since 1.2
 * @author Dakachi
 */
function fre_share_role() {
    $options = AE_Options::get_instance();

    // save this setting to theme options
    return $options->fre_share_role;
}

/**
 * allow user to upload a video file
 * @author tam
 *
 */
add_filter('upload_mimes', 'fre_add_mime_types');
add_filter('et_upload_file_upload_mimes', 'fre_add_mime_types');
function fre_add_mime_types($mimes) {
    /**
     * admin can add more file extension
     */
    if (current_user_can('manage_options')) {
        return array_merge($mimes, array(
            'ac3' => 'audio/ac3',
            'mpa' => 'audio/MPA',
            'flv' => 'video/x-flv',
            'svg' => 'image/svg+xml',
            'mp4' => 'video/MP4',
            'doc|docx' => 'application/msword',
            'pdf' => 'application/pdf',
            'zip' => 'multipart/x-zip',
            'xla|xls|xlt|xlw|xlsx'  => 'application/vnd.ms-excel',
        ));
    }
    // if user is normal user
    $mimes = array_merge($mimes, array(
        'xla|xls|xlt|xlw|xlsx'  => 'application/vnd.ms-excel',
        'doc|docx' => 'application/msword',
        'pdf' => 'application/pdf',
        'zip' => 'multipart/x-zip'
    ));
    return $mimes;
}

/**
 * get content current currency sign (icon)
 * @param $echo bool
 * @author Dakachi
 */
function fre_currency_sign($echo = true) {

    // get option instance to retrieve option value
    $options = AE_Options::get_instance();
    if ($echo)

    // echo option
    echo $options->currency['icon'];
    else

    // return an option value
    return $options->currency['icon'];
}

function fre_price_format($amount, $style = '<sup>') {

    $currency = ae_get_option('currency', array(
        'align' => 'left',
        'code' => 'USD',
        'icon' => '$'
    ));

    $align = $currency['align'];

    // dafault = 0 == right;

    $currency = $currency['icon'];
    $price_format = get_theme_mod('decimal_point', 1);
    $format = '%1$s';

    switch ($style) {
        case 'sup':
            $format = '<sup>%s</sup>';
            break;

        case 'sub':
            $format = '<sub>%s</sub>';
            break;

        default:
            $format = '%s';
            break;
    }

    $number_format = ae_get_option('number_format');
    $decimal = (isset($number_format['et_decimal'])) ? $number_format['et_decimal'] : get_theme_mod('et_decimal', 2);
    $decimal_point = (isset($number_format['dec_point']) && $number_format['dec_point']) ? $number_format['dec_point'] : get_theme_mod('et_decimal_point', '.');
    $thousand_sep = (isset($number_format['thousand_sep']) && $number_format['thousand_sep']) ? $number_format['thousand_sep'] : get_theme_mod('et_thousand_sep', ',');

    if ($align != "0") {
        $format = $format . '%s';
        return sprintf($format, $currency, number_format((double)$amount, $decimal, $decimal_point, $thousand_sep));
    } else {
        $format = '%s' . $format;
        return sprintf($format, number_format((double)$amount, $decimal, $decimal_point, $thousand_sep) , $currency);
    }
}

function fre_number_format($amount, $echo = true){
    $number_format = ae_get_option('number_format');
    $decimal = (isset($number_format['et_decimal'])) ? $number_format['et_decimal'] : get_theme_mod('et_decimal', 2);
    $decimal_point = (isset($number_format['dec_point']) && $number_format['dec_point']) ? $number_format['dec_point'] : get_theme_mod('et_decimal_point', '.');
    $thousand_sep = (isset($number_format['thousand_sep']) && $number_format['thousand_sep']) ? $number_format['thousand_sep'] : get_theme_mod('et_thousand_sep', ',');
    if($echo) {
        echo number_format((double)$amount, $decimal, $decimal_point, $thousand_sep);
    }else{
        return number_format((double)$amount, $decimal, $decimal_point, $thousand_sep);
    }
}

/**
 *
 * Function add filter orderby post status
 *
 *
 */
function fre_order_by_bid_status($orderby) {
    global $wpdb;
    $orderby = " case {$wpdb->posts}.post_status
                         when 'complete' then 0
                         when 'accept' then 1
                         when 'publish' then 2
                         end,
            {$wpdb->posts}.post_date DESC";
    return $orderby;
}

/**
 *
 * Function add filter orderby project post status
 *
 *
 */
function fre_order_by_project_status($orderby) {
    global $wpdb;
    $orderby = " case {$wpdb->posts}.post_status
                            when 'disputing' then 0
                            when 'reject' then 1
                            when 'pending' then 2
                            when 'publish' then 3
                            when 'close' then 4
                            when 'complete' then 5
                            when 'draft' then 6
                            when 'archive' then 7
                            when 'disputed' then 8

                        end,
                        {$wpdb->posts}.post_date DESC";
    return $orderby;
}

/**
 *
 * Function add filter orderby project post status
 *
 *
 */
function fre_reset_order_by_project_status($orderby) {
    global $wpdb;
    $orderby = "{$wpdb->posts}.post_date DESC";
    return $orderby;
}

add_action('wp_ajax_ae_upload_files', 'fre_upload_file');
function fre_upload_file() {
    $res = array(
        'success' => false,
        'msg' => __('There is an error occurred', ET_DOMAIN) ,
        'code' => 400,
    );

    // check fileID
    if (!isset($_POST['fileID']) || empty($_POST['fileID'])) {
        $res['msg'] = __('Missing image ID', ET_DOMAIN);
    } else {
        $fileID = $_POST["fileID"];
        $imgType = $_POST['imgType'];

        // check ajax nonce
        if (!de_check_ajax_referer('file_et_uploader', false, false) && !check_ajax_referer('file_et_uploader', false, false)) {
            $res['msg'] = __('Security error!', ET_DOMAIN);
        } elseif (isset($_FILES[$fileID])) {

            // handle file upload
            $attach_id = et_process_file_upload($_FILES[$fileID], 0, 0, array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'gif' => 'image/gif',
                'png' => 'image/png',
                'bmp' => 'image/bmp',
                'tif|tiff' => 'image/tiff',
                'pdf' => 'application/pdf',
                'doc|docx' => 'application/msword',
                'odt' => 'application/vnd.oasis.opendocument.text',
                'zip' => 'application/zip',
                'rar' => 'application/rar',
                'xla|xls|xlt|xlw'  => 'application/vnd.ms-excel',
            ));

            if (!is_wp_error($attach_id)) {

                try {
                    $attach_data = et_get_attachment_data($attach_id);

                    $options = AE_Options::get_instance();

                    // save this setting to theme options
                    // $options->$imgType = $attach_data;
                    // $options->save();



                    /**
                     * do action to control how to store data
                     * @param $attach_data the array of image data
                     * @param $request['data']
                     * @param $attach_id the uploaded file id
                     */

                    //do_action('ae_upload_image' , $attach_data , $_POST['data'], $attach_id );

                    $res = array(
                        'success' => true,
                        'msg' => __('File has been uploaded successfully', ET_DOMAIN) ,
                        'data' => $attach_data
                    );
                }
                catch(Exception $e) {
                    $res['msg'] = __('Error when updating settings.', ET_DOMAIN);
                }
            } else {
                $res['msg'] = $attach_id->get_error_message();
            }
        } else {
            $res['msg'] = __('Uploaded file not found', ET_DOMAIN);
        }
    }

    // send json to client
    wp_send_json($res);
}
/**
 * Check post type to use pending post
 *
 * @since 1.5.2
 *
 * @author Tambh
 */
add_filter( 'use_pending', 'filter_post_type_use_pending', 10, 2 );
function filter_post_type_use_pending( $pending, $post_type ){
    if( $post_type == PROFILE || $post_type == PORTFOLIO){
        $pending = false;
    }
    return $pending;
}

function mail_logo($logo){
    if(empty($logo)){
        $logo = get_template_directory_uri() . "/img/logo-fre-black.png";
    }
    return $logo;

}
add_filter('ae_mail_logo_url', 'mail_logo');

if( ! function_exists( 'fre_show_credit' ) ):

    /**
     * conver credit number of curent user to number can bid and display as html.
     * @since   1.7.9
     * @author danng
     * @return  void
     */
    function fre_show_credit($user_role) {
        global $user_ID, $ae_post_factory, $post;
        /*
        * only show credit number if current user is freelancer or share role and employer role
         */
        if(( $user_role == FREELANCER || ( fre_share_role() && in_array( $user_role, array(FREELANCER, EMPLOYER) ) ) ) ) {

            $credits_a_bid      = (int) ae_get_option( 'ae_credit_number', 1 );
            $credits            = get_user_credit_number( $user_ID );
            $credits_pending    = get_user_credit_number_pending( $user_ID );
            // Check user profile
            $profile_id = get_user_meta($user_ID, 'user_profile_id', true);
            $profile    = get_post($profile_id);
            ?>
            <div class="setting-profile-wrapper action-bid">
                <p class="title-name padding-title"><?php _e('You have:', ET_DOMAIN)?></p>
                <div class="inner">
                    <div class="content-bid-action">
                        <?php
                            if($credits > 0){
                                printf(__('<p><span class="number">%s</span> available bid(s) used for project bidding</p>', ET_DOMAIN), $credits);
                                if($credits_pending > 0){
                                    printf(__('<p><span class="number">%s</span> pending bid(s) are under admin review</p>', ET_DOMAIN), $credits_pending);
                                }
                            }else{
                                if($credits_pending > 0){
                                    printf(__('<p><span class="number">0</span> available bid(s) used for project bidding</p>', ET_DOMAIN));
                                    printf(__('<p><span class="number">%s</span> pending bid(s) are under admin review</p>', ET_DOMAIN), $credits_pending);
                                }else{
                                    if(!(!$profile || !is_numeric($profile_id))){
                                        printf(__('<p><span class="number">0</span> available bid(s) used for project bidding</p>', ET_DOMAIN));
                                    }else{
                                        if(ae_get_option( 'pay_to_bid', false )){
                                            printf(__('<p><span class="number">0</span> available bid(s) used for project bidding</p>', ET_DOMAIN));
                                        }
                                    }
                                }
                            }
                        ?>
                    </div>
                    <div class="buy-bid-action">
                        <a class="btn-buy-bid" href="<?php echo et_get_page_link('upgrade-account', array('project_id' => $post->ID));?>"><?php _e('Purchase Bid', ET_DOMAIN); ?></a>
                    </div>
                </div>
            </div>
<?php 
        }
    }
endif;