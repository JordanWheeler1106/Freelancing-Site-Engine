<?php 
get_header();
?>
<div class="fre-page-wrapper">
	<div class="fre-page-section">
		<div class="container">
			<div class="fre-authen-wrapper">
				<div class="fre-authen-lost-pass">
					<h2><?php _e('Reset Your Password', ET_DOMAIN);?></h2>
					<p><?php _e("Enter your email address below. We'll look for your account and send you a password reset email.", ET_DOMAIN);?></p>
					<form role="form" id="forgot_form" class="auth-form forgot_form">
						<!-- <ul class="fre-validate-error">
							<li>Email exists</li>
						</ul> -->
						<div class="fre-input-field">
							<input type="text" id="user_email" name="user_email" placeholder="<?php _e('Your email address', ET_DOMAIN);?>">
							<!-- <div class="message">This field is required.</div> -->
						</div>
						<div class="fre-input-field">
							<button class="fre-submit-btn btn-submit"><?php _e('Send Password Reset', ET_DOMAIN);?></button>
						</div>
					</form>
					<div class="fre-authen-footer">
						<p><?php _e('Already have an account?', ET_DOMAIN);?> <a href="<?php echo et_get_page_link("login") ?>"><?php _e('Log In', ET_DOMAIN);?></a></p>		
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>