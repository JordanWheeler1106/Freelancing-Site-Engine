<?php
/**
 *
*/
global $user_ID;
$session    =   et_read_session ();
et_get_mobile_header();
if (isset($session['project_id'])) et_destroy_session('project_id');
if (isset($_REQUEST['project_id'])){
    // save Session
    et_write_session('project_id', $_REQUEST['project_id']);
}
?>

<!-- Breadcrumb Blog -->
<section class="blog-header-container">
    <div class="container">
        <!-- blog header -->
        <div class="row">
            <div class="col-md-12 blog-classic-top">
                <h2><?php the_title(); ?></h2>
            </div>
        </div>
        <!--// blog header  -->
    </div>
</section>
<!-- Breadcrumb Blog / End -->

<!-- Page Post Place -->
<section id="blog-page">
	<div class="container">
    	<div class="row">
        	<!-- Column left -->
        	<div class="col-md-9 col-sm-12 col-xs-12">
            	<div class="post-place-wrapper" id="upgrade-account">
                    <?php
                        get_template_part( 'template/upgrade-account', 'step1' );

                        if(!$user_ID) {
                            // template/post-place-step2.php
                            get_template_part( 'template/upgrade-account', 'step2' );
                        }

                        // template/post-place-step3.php
                        //get_template_part( 'template/upgrade-account', 'step3' );

                        // template/post-place-step4.php
                        get_template_part( 'template/upgrade-account', 'step4' );
                    ?>
                </div>
            <!-- Column left / End -->
            </div>
            <!-- Column right -->
        	<div class="col-md-3 col-sm-12 col-xs-12 page-sidebar" id="right_content">
                <?php get_sidebar('page'); ?>
            </div><!-- RIGHT CONTENT -->
            <!-- Column right / End -->
	   </div>
    </div>
</section>
<!-- Page Post Place / End -->

<?php
et_get_mobile_footer();