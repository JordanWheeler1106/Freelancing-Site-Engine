<?php
/**
 * Template Name: Upgrade Account
*/
global $user_ID;
$session    =   et_read_session ();
get_header();
if (isset($session['project_id'])) et_destroy_session('project_id');
if (isset($_REQUEST['project_id'])){
    // save Session
    et_write_session('project_id', $_REQUEST['project_id']);
}
?>

<div class="fre-page-wrapper">
    <div class="fre-page-title">
        <div class="container">
            <h2><?php the_title(); ?></h2>
        </div>
    </div>

    <div class="fre-page-section">
        <div class="container">
            <div class="post-place-warpper" id="upgrade-account">
                <?php
                    // template/post-place-step1.php
                    get_template_part( 'template/upgrade-account', 'step1' );

                    // template/post-place-step4.php
                    get_template_part( 'template/upgrade-account', 'step4' );
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();