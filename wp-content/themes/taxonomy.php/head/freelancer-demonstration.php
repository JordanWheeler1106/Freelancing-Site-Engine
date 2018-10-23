<div class="color-left">
    <div class="content-sub">
            <h1><?php fre_project_demonstration( false );?></h1>
    		  <!-- <h1>The best way to<br>find a professional</h1> -->
            <p>
                <a href="<?php echo get_post_type_archive_link(PROJECT) ?>" class="btn-sumary btn-sub-post box-shadow-button-dark-blue">
                    <?php _e("Find a Project", ET_DOMAIN); ?>
                </a>
            </p>
    </div>
</div>
<div class="color-right">
	<div class="content-sub">
        <h1><?php fre_profile_demonstration( true ); ?></h1>
    	<!-- <h1>Need a job?<br>Tell us your story</h1> -->
        <p>
            <a href="<?php echo et_get_page_link( array('page_type' => 'profile', 'post_title' => __("Create a Profile", ET_DOMAIN )) ); ?>" class="btn-sumary btn-sub-create box-shadow-button-orange">
                <?php _e("Review your Profile", ET_DOMAIN); ?>
            </a>
        </p>
    </div>
</div>