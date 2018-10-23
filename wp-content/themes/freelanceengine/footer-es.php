<?php
wp_reset_query();
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.0
 */
?>
<?php
if( is_active_sidebar( 'fre-footer-1' )    || is_active_sidebar( 'fre-footer-2' ) 
    || is_active_sidebar( 'fre-footer-3' ) || is_active_sidebar( 'fre-footer-4' )
    )
{$flag=true; ?>
<!-- FOOTER -->
<footer>
	<div class="container">
    	<div class="row">
            <div class="col-md-3 col-sm-6">
                <?php if( is_active_sidebar( 'fre-footer-1' ) ) dynamic_sidebar( 'fre-footer-1' );?>
            </div>
            <div class="col-md-3 col-sm-6">
                <?php if( is_active_sidebar( 'fre-footer-2' ) ) dynamic_sidebar( 'fre-footer-2' );?>
            </div>
            <div class="col-md-3 col-sm-6">
                <?php if( is_active_sidebar( 'fre-footer-2' ) ) dynamic_sidebar( 'fre-footer-3' );?>
            </div>
            <div class="col-md-3 col-sm-6">
                <?php if( is_active_sidebar( 'fre-footer-2' ) ) dynamic_sidebar( 'fre-footer-4' );?>
            </div>
        </div>
    </div>
</footer>
<?php }else{ $flag = false;} ?>
<div class="copyright-wrapper <?php if(!$flag){ echo 'copyright-wrapper-margin-top'; } ?> ">
<?php
    $copyright = ae_get_option('copyright');
    $has_nav_menu = has_nav_menu( 'et_footer' );
    $col = 'col-md-6';
    if($has_nav_menu) {
        $col = 'col-md-4';
    }
?>
	<div class="container">
        <div class="row">
            <div class="<?php echo $col ?> col-sm-4">
            	<a href="<?php echo home_url(); ?>" class="logo-footer"><?php fre_logo('site_logo_white') ?></a>
            </div>
            <?php if($has_nav_menu){ ?>
            <div class="col-md-4 col-sm-4">
                <?php
                    wp_nav_menu( array('theme_location' =>'et_footer') );
                ?>
            </div>
            <?php }?>
            <div class="<?php echo $col;?> col-sm-4">
            	<p class="text-copyright">
                    <?php
                        if($copyright){ echo $copyright; }
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER / END -->

<!-- MODAL QUIT PROJECT-->
<a href="#" data-toggle="modal" data-target="#quit_project">
  QUIT PROJECT
</a>

<div class="modal fade" id="quit_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title alert-color"><?php _e("Awww! Why quit?", ET_DOMAIN) ?></h4>
                <p class="alert-color"><?php _e("You're going to quit this project. However, you are still available to access the workspace.", ET_DOMAIN) ?></p>
			</div>
			<div class="modal-body">
				<form role="form" id="signin_form" class="auth-form signin_form">
					<div class="form-group">
						<label for="user_login"><?php _e('Please give us a clear report', ET_DOMAIN) ?></label>
						<textarea></textarea>
					</div>
                    <div class="clearfix"></div>
                    <div class="form-group">
						<label for="user_login"><?php _e('Percent finished', ET_DOMAIN) ?></label>
					</div>
                    <div class="clearfix"></div>
                    <div class="form-group">
						<input type="text" class="form-control" id="pass_close" name="pass_close" placeholder="<?php _e('Enter your password to close', ET_DOMAIN) ?>">
                        <button type="submit" class="btn-submit btn-sumary btn-sub-create">
							<?php _e('Quit', ET_DOMAIN) ?>
                        </button>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
<!-- MODAL QUIT PROJECT-->

<!-- MODAL CLOSE PROJECT-->
<a href="#" data-toggle="modal" data-target="#close_project">
 CLOSE PROJECT
</a>

<div class="modal fade" id="close_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
			</div>
			<div class="modal-body">
            	<div class="content-close-wrapper">
                    <p class="alert-close-text"><?php _e("We will review the reports from both freelancer and employer to give the best decision.
    It will take 3-5 business days for reviewing after receiving two reports.", ET_DOMAIN) ?> </p>
                    <button type="submit" class="btn btn-ok">
                        <?php _e('OK', ET_DOMAIN) ?>
                    </button>
                </div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
<!-- MODAL CLOSE PROJECT-->

<!-- MODAL FINISH PROJECT-->
<a href="#" data-toggle="modal" data-target="#finish_project">
  FINISH PROJECT
</a>

<div class="modal fade" id="finish_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title"><?php _e("Congratulation!", ET_DOMAIN) ?></h4>
			</div>
			<div class="modal-body">
				<form role="form" id="signin_form" class="auth-form signin_form">
                	<label style="line-height:2.5;">You are going to finish this project.<br>
					Your account will receive from employer after we have confirmation from employer.</label>
                    <p><strong class="color-green">350</strong> credits <strong class="color-green"><i class="fa fa-check"></i></strong></p>
					<div class="form-group">
						<label for="user_login"><?php _e('Your review about employer', ET_DOMAIN) ?></label>
						<textarea></textarea>
					</div>
                    <div class="clearfix"></div>
                    <div class="form-group">
						<input type="text" class="form-control" id="pass_close" name="pass_close" placeholder="<?php _e('Enter your password to close', ET_DOMAIN) ?>">
                        <button type="submit" class="btn btn-ok">
							<?php _e('Finish', ET_DOMAIN) ?>
                        </button>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
<!-- MODAL FINISH PROJECT-->

<!-- MODAL DONE PROJECT-->
<a href="#" data-toggle="modal" data-target="#done_project">
  DONE PROJECT
</a>

<div class="modal fade" id="done_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="content-close-wrapper">
                	<h4 class="alert-close-text">Done</h4>
                    <p><?php _e("Your credits has been transfered to the freelancer.", ET_DOMAIN) ?> </p>
                    <span class="check-done"><i class="fa fa-check"></i></span>
                    <button type="submit" class="btn btn-ok">
                        <?php _e('OK', ET_DOMAIN) ?>
                    </button>
                </div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
<!-- MODAL DONE PROJECT-->

<!-- MODAL BID acceptance PROJECT-->
<a href="#" data-toggle="modal" data-target="#acceptance_project">
  acceptance_project PROJECT
</a>

<div class="modal fade" id="acceptance_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title"><?php _e("Bid acceptance", ET_DOMAIN) ?></h4>
			</div>
			<div class="modal-body">
            	<label style="line-height:2.5;">You are about to accept this bid for</label>
				<p><strong class="color-green">350</strong> credits <strong class="color-green"><i class="fa fa-check"></i></strong></p>
                <br>
                <label style="line-height:2.5;">Your Current Account balance<br></label>
                <p class="text-credit-small">Credits total <strong>2,707</strong></p>
                <p class="text-credit-small">Credits available <strong style="color: #1faf67;">2,000</strong></p>
                <p class="text-credit-small">Credits frozen <strong style="color:#e74c3c;">707</strong></p>
                <br>
				<div class="form-group">
                    <button type="submit" class="btn-submit btn-sumary btn-sub-create">
                        <?php _e('Accept Bid', ET_DOMAIN) ?>
                    </button>
                </div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
<!-- MODAL BID acceptance PROJECT-->



<!-- <div class="form-finish-project">
    <h5>Congratulation!</h5>
    <p>This project has been claimed as finish.<br>
    The payment has been proceed.<br>
    View your workspace again <a href="#" >here</a>.</p>
</div> -->
<?php
    // if(!is_page_template( 'page-auth.php' )){
    	/* ======= modal register template ======= */
    	get_template_part( 'template-js/modal' , 'register' );
    	/* ======= modal register template / end  ======= */
    	/* ======= modal register template ======= */
    	get_template_part( 'template-js/modal' , 'login' );
    	/* ======= modal register template / end  ======= */
    // }

	if(is_page_template( 'page-profile.php' )){
    	/* ======= modal add portfolio template ======= */
    	get_template_part( 'template-js/modal' , 'add-portfolio' );
    	/* ======= modal add portfolio template / end  ======= */
	}
	/* ======= modal change password template ======= */
	get_template_part( 'template-js/modal' , 'change-pass' );
	/* ======= modal change password template / end  ======= */

	get_template_part( 'template-js/post' , 'item' );
	get_template_part( 'template-js/project' , 'item' );
	get_template_part( 'template-js/user' , 'bid-item' );
	get_template_part( 'template-js/profile' , 'item' );
	get_template_part( 'template-js/portfolio' , 'item' );
	get_template_part( 'template-js/work-history', 'item' );
	get_template_part( 'template-js/skill' , 'item' );

	if(is_singular('project')){

		get_template_part( 'template-js/bid' , 'item' );
        get_template_part( 'template-js/modal' , 'review');
        get_template_part( 'template-js/modal' , 'bid' );
        get_template_part( 'template-js/modal' , 'accept-bid' );
	}

    if(is_author()){
        get_template_part( 'template-js/author-project' , 'item' );
    }
	//print modal contact template
	if(is_singular( PROFILE ) || is_author() ){
		get_template_part( 'template-js/modal' , 'contact' );
	}
	/* ======= modal invite template ======= */
	get_template_part( 'template-js/modal' , 'invite' );
	/* ======= modal invite template / end  ======= */
    /* ======= modal forgot pass template ======= */
    get_template_part( 'template-js/modal' , 'forgot-pass' );
    /* ======= modal forgot pass template / end  ======= */

    // modal edit project
    if(is_post_type_archive(PROJECT) || is_page_template('page-profile.php')){
        get_template_part( 'template-js/modal' , 'edit-project' );
        get_template_part( 'template-js/modal' , 'reject' );
    }

    if(is_singular( PROJECT )) {
        get_template_part( 'template-js/message' , 'item' );
    }

	wp_footer();
?>
</body>
</html>