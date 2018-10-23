<?php
    global $wp_query, $ae_post_factory, $post, $current_user, $user_ID;
    //convert current user
    $ae_users  = AE_Users::get_instance();
    $user_data = $ae_users->convert($current_user->data);
    $user_role = ae_user_role($current_user->ID);
    //convert current profile
    $post_object = $ae_post_factory->get(PROFILE);
    $posts = get_posts(array(
            'post_type'   => PROFILE,
            'author'      => $current_user->ID,
            'showposts'   => 1,
            'post_status' => 'publish'
        ));
    if(!empty($posts) && isset($posts[0])){
        $profile = $post_object->convert($posts[0]);
    } else {
        $profile = array('id' => 0, 'ID' => 0);
    }
    //get profile skills
    $current_skills = get_the_terms( $profile, 'skill' );
    //define variables:
    $skills         = isset($profile->tax_input['skill']) ? $profile->tax_input['skill'] : array() ;
    $job_title      = isset($profile->et_professional_title) ? $profile->et_professional_title : '';
    $hour_rate      = isset($profile->hour_rate) ? $profile->hour_rate : '';
    $currency       = isset($profile->currency) ? $profile->currency : '';
    $experience     = isset($profile->et_experience) ? $profile->et_experience : '';
    $hour_rate      = isset($profile->hour_rate) ? $profile->hour_rate : '';
    $about          = isset($profile->post_content) ? $profile->post_content : '';
    $display_name   = $user_data->display_name;
    $user_available = isset($user_data->user_available) && $user_data->user_available == "on" ? 'checked' : '';
    $country        = isset($profile->tax_input['country'][0]) ? $profile->tax_input['country'][0]->name : '' ;
    $category       = isset($profile->tax_input['project_category'][0]) ? $profile->tax_input['project_category'][0]->slug : '' ;

    // Handle email change requests
    $user_meta = get_user_meta($user_ID, 'adminhash', true);

    if(! empty($_GET[ 'adminhash' ] )){
        if(is_array($user_meta) && $user_meta['hash'] == $_GET['adminhash'] && !empty($user_meta[ 'newemail' ])){
            wp_update_user(array('ID' => $user_ID,
                'user_email' => $user_meta['newemail']
            ));
            delete_user_meta( $user_ID, 'adminhash' );
        }
        echo "<script> window.location.href = '".et_get_page_link("profile")."'</script>";
    }elseif(! empty($_GET[ 'dismiss' ] ) && 'new_email' == $_GET['dismiss']){
        delete_user_meta( $user_ID, 'adminhash' );
        echo "<script> window.location.href = '".et_get_page_link("profile")."'</script>";
    }

	et_get_mobile_header();
?>
<section class="section-wrapper section-user-profile list-profile-wrapper">

	<div class="tabs-acc-details tab-profile mobile-tab-profile" id="tab_account" style="display:block">
        <div class="user-profile-avatar" id="user_avatar_container">
            <span class="image" id="user_avatar_thumbnail">
                <?php echo get_avatar( $user_data->ID, 90 ); ?>
            </span>
            <a href="#" class="icon-edit-profile-user edit-avatar-user" id="user_avatar_browse_button">
                <i class="fa fa-pencil"></i>
            </a>
            <span class="et_ajaxnonce hidden" id="<?php echo de_create_nonce( 'user_avatar_et_uploader' ); ?>"></span>
        </div>
        <form class="form-mobile-wrapper form-user-profile" id="account_form">
            <div class="form-group-mobile">
                <label><?php _e("Your Fullname", ET_DOMAIN) ?></label>
                <!-- <a href="#" class="icon-edit-profile-user edit-info-user"><i class="fa fa-pencil"></i></a> -->
                <input type="text" id="display_name" name="display_name" value="<?php echo $user_data->display_name ?>" placeholder="<?php _e("Enter Full Name", ET_DOMAIN); ?>">
            </div>
            <div class="form-group-mobile">
                <label><?php _e("Address", ET_DOMAIN) ?></label>
                <input type="text" id="location" name="location" value="<?php echo $user_data->location ?>" placeholder="<?php _e("Enter address", ET_DOMAIN); ?>">
            </div>
            <div class="form-group-mobile">
                <label><?php _e("Email Address", ET_DOMAIN) ?></label>
                <input type="text" id="user_email" value="<?php echo $user_data->user_email ?>" name="user_email" placeholder="<?php _e("Enter email", ET_DOMAIN); ?>">
                <?php
                    if(!empty($user_meta['newemail'])){
                        printf( __( '<p class="noti-pending">There is a pending change of the email to %1$s. <a href="%2$s">Cancel</a></p>', ET_DOMAIN ),
                                    '<code>' . esc_html( $user_meta['newemail'] ) . '</code>',
                                        esc_url( et_get_page_link("profile").'?dismiss=new_email' )
                                );
                    }
                ?>
            </div>
            <p class="btn-warpper-bid">
                <input type="submit" class="btn-submit btn-sumary btn-bid" value="<?php _e("Update", ET_DOMAIN) ?>" />
            </p>
            <?php if(ae_get_option('use_escrow', false)) {
                do_action( 'ae_escrow_recipient_field');
            } ?>

            <?php  
            fre_user_package_info($user_ID);
            fre_show_credit($user_role); 
            ?>
        </form>
    </div>
    <!-- Tab profile details -->
    <?php if(fre_share_role() || $user_role == FREELANCER){ ?>
    <div class="tabs-profile-details tab-profile mobile-tab-profile collapse" id="tab_profile">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button">
                            <?php _e("Update your profile", ET_DOMAIN) ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <?php
                        $user_profile_id = get_user_meta($user_ID, 'user_profile_id', true);
                        $checkProfile = get_post($user_profile_id);
                        if(!$checkProfile || !is_numeric($user_profile_id)){ ?>
                            <div class="notice-first-login">
                                <p><?php _e('<i class="fa fa-warning"></i> You must complete your profile to do any activities on site', ET_DOMAIN);?></p>
                            </div>
                        <?php } ?>
                        <form class="form-mobile-wrapper form-user-profile" id="profile_form">
                            <div class="form-group-mobile edit-profile-title">
                                <label><?php _e("Your Professional Title", ET_DOMAIN) ?></label>
                                <!-- <a href="#" class="icon-edit-profile-user edit-info-user"><i class="fa fa-pencil"></i></a> -->
                                <input type="text" id="et_professional_title" value="<?php echo $job_title; ?>" name="et_professional_title" placeholder="<?php _e("Title", ET_DOMAIN); ?>">
                            </div>
                            <div class="form-group-mobile">
                                <div class="hourly-rate-form">
                                    <label><?php _e("Your Hourly Rate", ET_DOMAIN) ?></label>
                                    <!-- <a href="#" class="icon-edit-profile-user edit-info-user"><i class="fa fa-pencil"></i></a> -->

                                    <div class="group_profile_tan">
                                        <input class="numberVal" type="text" id="hour_rate" name="hour_rate" value="<?php echo $hour_rate ?>" placeholder="<?php _e("e.g:30", ET_DOMAIN); ?>">
                                        <?php
                                        $currency = ae_get_option('currency');
                                        if($currency){
                                            ?>
                                            <span class="currency-tan"><?php echo $currency['code']; ?></span>
                                        <?php } else { ?>
                                            <span class="currency-tan"><?php _e('USD', ET_DOMAIN); ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group-mobile skill-profile-control">

                                <?php
                                $switch_skill = ae_get_option('switch_skill');
                                if(!$switch_skill){
                                    ?>
                                    <div class="wrapper-skill">
                                        <label><?php _e("Your Skills", ET_DOMAIN) ?></label>
                                        <a href="#" class="btn-sumary btn-add-skill add-skill"><?php _e("Add", ET_DOMAIN) ?></a>
                                        <input type="text" id="skill" class="skill" placeholder="<?php _e("Skills", ET_DOMAIN); ?>">
                                    </div>
                                    <div class="clearfix"></div>
                                    <ul class="list-skill skills-list" id="skills_list"></ul>
                                    <?php
                                }else{
                                    ?>
                                    <div class="wrapper-skill">
                                        <label><?php _e("Your Skills", ET_DOMAIN) ?></label>
                                    </div>
                                    <?php
                                    $c_skills = array();
                                    if(!empty($current_skills)){
                                        foreach ($current_skills as $key => $value) {
                                            $c_skills[] = $value->term_id;
                                        };
                                    }
                                    ae_tax_dropdown( 'skill' , array(  'attr' => 'data-chosen-width="95%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Skills (max is %s)", ET_DOMAIN), ae_get_option('fre_max_skill', 5)).'"',
                                            'class'             => 'experience-form chosen multi-tax-item tax-item required',
                                            'hide_empty'        => false,
                                            'hierarchical'      => false ,
                                            'id'                => 'skill' ,
                                            'show_option_all'   => false,
                                            'selected'          => $c_skills
                                        )
                                    );
                                }
                                ?>
                            </div>
                            <div class="form-group-mobile">
                                <label><?php _e("Category", ET_DOMAIN) ?></label>
                                <?php
                                $cate_arr = array();
                                if(!empty($profile->tax_input['project_category'])){
                                    foreach ($profile->tax_input['project_category'] as $key => $value) {
                                        $cate_arr[] = $value->term_id;
                                    };
                                }
                                ae_tax_dropdown( 'project_category' ,
                                    array(
                                        'attr'            => 'data-chosen-width="95%" multiple data-chosen-disable-search="" data-placeholder="'.__("Choose categories", ET_DOMAIN).'"',
                                        'class'           => 'experience-form chosen multi-tax-item tax-item required',
                                        'hide_empty'      => false,
                                        'hierarchical'    => true ,
                                        'id'              => 'project_category' ,
                                        'selected'        => $cate_arr,
                                        'show_option_all' => false
                                    )
                                );
                                ?>
                            </div>
                            <?php if(fre_share_role() || $user_role == FREELANCER){ ?>
                                <div class="form-group-mobile">
                                    <label class="et-receive-mail" for="et_receive_mail"><input type="checkbox" id="et_receive_mail" name="et_receive_mail_check" <?php echo (isset($profile->et_receive_mail) && $profile->et_receive_mail == '1') ? 'checked': '' ;?>/>
                                        <?php _e("Receive emails about projects that match your categories", ET_DOMAIN) ?>
                                    </label>
                                    <input type="hidden" value="<?php echo (isset($profile->et_receive_mail)) ? $profile->et_receive_mail : '';?>" id="et_receive_mail_value" name="et_receive_mail" />
                                </div>
                            <?php } ?>
                            <div class="form-group-mobile">
                                <label><?php _e("Country", ET_DOMAIN) ?></label>
                                <?php if(!ae_get_option('switch_country')){ ?>
                                    <input class="" type="text" id="country" placeholder="<?php _e("Country", ET_DOMAIN); ?>" name="country" value="<?php if($country){echo $country;} ?>" autocomplete="off" class="country" spellcheck="false" >
                                <?php }else{
                                    $country_arr = array();
                                    if(!empty($profile->tax_input['country'])){
                                        foreach ($profile->tax_input['country'] as $key => $value) {
                                            $country_arr[] = $value->term_id;
                                        };
                                    }
                                    ae_tax_dropdown( 'country' ,
                                        array(
                                            'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" data-placeholder="'.__("Choose country", ET_DOMAIN).'"',
                                            'class'           => 'experience-form chosen multi-tax-item tax-item required country_profile',
                                            'hide_empty'      => false,
                                            'hierarchical'    => true ,
                                            'value'           => 'slug',
                                            'id'              => 'country' ,
                                            'selected'        => $country_arr,
                                            'show_option_all' => false
                                        )
                                    );
                                }
                                ?>
                            </div>
                            <div class="form-group-mobile about-form">
                                <label><?php _e("About You", ET_DOMAIN) ?></label>
                                <!-- <a href="#" class="icon-edit-profile-user edit-info-user"><i class="fa fa-pencil"></i></a> -->
                                <textarea name="post_content" id="post_content" placeholder="<?php _e("About", ET_DOMAIN); ?>" rows="7"><?php echo trim(strip_tags($about)) ?></textarea>
                            </div>
                            <div class="form-group-mobile">
                                <label><?php _e("Your Experience", ET_DOMAIN) ?></label>
                                <!-- <a href="#" class="icon-edit-profile-user edit-info-user"><i class="fa fa-pencil"></i></a> -->
                                <div class="group_profile_tan">
                                    <input min="0" type="number" name="et_experience" value="<?php echo $experience; ?>" class="required"/>
                                    <span class="currency-tan"><?php _e('Years', ET_DOMAIN); ?></span>
                                </div>
                            </div>
                            <div class="form-group-mobile">
                                <?php do_action( 'ae_edit_post_form', PROFILE, $profile ); ?>
                            </div>
                            <p class="btn-warpper-bid btn-profile-update">
                                <input type="submit" class="btn-submit btn-sumary btn-bid" value="<?php _e("Update", ET_DOMAIN) ?>" />
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title <?php if(!$checkProfile || !is_numeric($user_profile_id)){ echo 'tab-close';}?>">
                        <a role="button">
                            <p class="text-update-profile"><?php _e("Update your portfolio", ET_DOMAIN) ?></p>
                            <p class="text-noti-profile">
                                <?php _e("Update your portfolio", ET_DOMAIN) ?>
                                <span><?php _e("Be sure your profile is updated first", ET_DOMAIN) ?></span>
                            </p>
                        </a>
                    </h4>
                </div>
                <?php if(!(!$checkProfile || !is_numeric($user_profile_id))){ ?>
                <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <div class="form-group-mobile portfolio">
                            <div class="edit-portfolio-container">
                                <?php
                                // list portfolio
                                query_posts( array(
                                    'post_status' => 'publish',
                                    'post_type'   => 'portfolio',
                                    'author'      => $current_user->ID
                                ));
                                get_template_part( 'mobile/list', 'portfolios' );
                                wp_reset_query();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="tabs-project-details tab-profile mobile-tab-profile collapse" id="tab_project">
    	<form class="form-mobile-wrapper form-user-profile">
            <div class="form-group-mobile edit-profile-title user-profile-history info-project-items">
                <?php if( $user_role == FREELANCER || fre_share_role() ){ ?>
                <!-- BIDDING -->
                <label>
                    <?php _e("Current bids", ET_DOMAIN) ?>
                </label>
                <div class="list-user-bids " id="list-user-bid-wrapper">
                    <?php
                        query_posts( array(
                            'post_status' => array('publish','accept'),
                            'post_type'   => 'bid',
                            'author'      => $current_user->ID,
                        ));
                        if(have_posts()){
                            get_template_part( 'mobile/list', 'user-bids' );
                        } else {
                            echo '<span class="no-results">';
                            _e( "No current bids.", ET_DOMAIN );
                            echo '</span>';
                        }
                        wp_reset_query();
                    ?>
                </div>
                <label>
                    <?php _e('Your Worked History and Reviews', ET_DOMAIN) ?>
                </label>
                <div class="list-bid-history" id="list-bid-history-wrapper">
                    <?php
                        query_posts( array(  'post_status' => array('accept', 'complete'),
                                    'post_type' => BID,
                                    'author' => $current_user->ID,
                                    'accepted' => 1
                                )
                            );
                        get_template_part('mobile/template/bid', 'history-list');
                        wp_reset_query();

                    } else {
                        get_template_part('mobile/template/work', 'history');
                    }
                    ?>
                </div>
                <!-- / END BIDDING -->
            </div>
        </form>
    </div>
    <!--TAB CREDITS-->
    <!-- Messages -->
        <?php do_action('fre_profile_mobile_tab_content');?>
    <!-- Messages / END -->

    <!-- Notification -->
    <section class="notification-section tab-profile mobile-tab-profile" id="tab_notification">
        <div class="container">
            <div class="back"><a href=""><i class="fa fa-angle-left" aria-hidden="true"></i>Back</a></div>
            <div class="notification-wrapper" id="notification_container">
                <?php fre_user_notification($user_ID); ?>
            </div>
        </div>
    </section>
    <!-- Notification / END -->

    <div class="tabs-acc-details tab-profile collapse" id="tab_change_pw">
        <form class="form-mobile-wrapper form-user-profile chane_pass_form" id="chane_pass_form">
            <div class="form-group-mobile edit-profile-title">
                <label><?php _e("Your Old Password", ET_DOMAIN) ?></label>
                <input type="password" id="old_password" name="old_password" placeholder="<?php _e("Old password", ET_DOMAIN); ?>">
            </div>
            <div class="form-group-mobile">
                <label><?php _e("Your New Password", ET_DOMAIN) ?></label>
                <input type="password" id="new_password" name="new_password" placeholder="<?php _e("New password", ET_DOMAIN); ?>">
            </div>
            <div class="form-group-mobile">
                <label><?php _e("Retype New Password", ET_DOMAIN) ?></label>
                <input type="password" id="renew_password" name="renew_password" placeholder="<?php _e("Retype again", ET_DOMAIN); ?>">
            </div>
            <p class="btn-warpper-bid">
                <input type="submit" class="btn-submit btn-sumary btn-bid" value="<?php _e("Change", ET_DOMAIN) ?>" />
            </p>
        </form>
    </div>
</section>

<!-- CURRENT PROFILE -->
<?php if(!empty($posts) && isset($posts[0])){ ?>
<script type="data/json" id="current_profile">
    <?php echo json_encode($profile) ?>
</script>
<?php } ?>
<!-- END / CURRENT PROFILE -->

<!-- CURRENT SKILLS -->
<?php if( !empty($current_skills) ){ ?>
<script type="data/json" id="current_skills">
    <?php echo json_encode($current_skills) ?>
</script>
<?php } ?>
<!-- END / CURRENT SKILLS -->

<?php
	et_get_mobile_footer();
?>