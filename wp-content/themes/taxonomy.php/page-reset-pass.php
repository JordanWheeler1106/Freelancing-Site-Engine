<?php
/**
 * Template Name: Reset Password Page Template
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.0
 */

	global $post;
	get_header();
?>
<div class="fre-page-wrapper">
	<div class="fre-page-section">
		<div class="container">
			<div class="fre-authen-wrapper">
				<div class="fre-authen-reset-pass">
					<h2><?php _e('Reset Your Password', ET_DOMAIN);?></h2>
					<form role="form" id="resetpass_form" class="signin_form">
						<input type="hidden" id="user_login" name="user_login" value="<?php if(isset($_GET['user_login'])) echo $_GET['user_login'] ?>" />
						<input type="hidden" id="user_key" name="user_key" value="<?php if(isset($_GET['key'])) echo $_GET['key'] ?>">
						<div class="fre-input-field">
							<input type="password" name="new_password" id="new_password" placeholder="<?php _e('New Password', ET_DOMAIN);?>">
						</div>
						<div class="fre-input-field">
							<input type="password" id="re_new_password" name="re_new_password" placeholder="<?php _e('New Password Confirm', ET_DOMAIN);?>">
						</div>
						<div class="fre-input-field">
							<button class="fre-submit-btn btn-submit"><?php _e('Change Password', ET_DOMAIN);?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	get_footer();
?>