<div class="modal fade" id="modal_register">
	<input type="hidden" value="<?php _e("Work", ET_DOMAIN); ?>" class="work-text" name="worktext" />
	<input type="hidden" value="<?php _e("Hire", ET_DOMAIN); ?>" class="hire-text" name="hiretext" />

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title"><?php _e("Become our member!", ET_DOMAIN) ?></h4>
			</div>
			<div class="modal-body">
				<form role="form" id="signup_form" class="auth-form signup_form">
                	<p class="user-type">
                		<span class="user-text"><?php _e("What are you looking for?", ET_DOMAIN) ?></span>

                            <input type="checkbox" class="sign-up-switch" name="modal-check" value="employer"/>
                            <span class="user-role text hire">
                                <?php _e("Hire", ET_DOMAIN); ?>
                            </span>
                	</p>
                	<input type="hidden" name="role" id="role" value="employer" />
                	<?php
                		$disable_name = apply_filters('free_register_disable_name','');
                		if(!$disable_name){
	            			?>
						    <div class="form-group">
						        <label for="first_name"><?php _e('First Name', ET_DOMAIN) ?></label>
						        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php _e("Enter first name", ET_DOMAIN) ?>">
						    </div>
						    <div class="form-group">
						        <label for="last_name"><?php _e('Last Name', ET_DOMAIN) ?></label>
						        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php _e("Enter last name", ET_DOMAIN) ?>">
						    </div>
						    <?php
                		}
                	?>
					<div class="form-group">
						<label for="user_login"><?php _e('Username', ET_DOMAIN) ?></label>
						<input type="text" class="form-control" id="user_login" name="user_login" placeholder="<?php _e("Enter username", ET_DOMAIN) ?>">
					</div>
					<div class="form-group">
						<label for="register_user_email"><?php _e('Email address', ET_DOMAIN) ?></label>
						<input type="email" class="form-control" id="register_user_email" name="user_email" placeholder="<?php _e("Enter email", ET_DOMAIN) ?>">
					</div>
					<div class="form-group">
						<label for="register_user_pass"><?php _e('Password', ET_DOMAIN) ?></label>
						<input type="password" class="form-control" id="register_user_pass" name="user_pass" placeholder="<?php _e("Enter Password", ET_DOMAIN) ?>">
					</div>
					<div class="form-group">
						<label for="repeat_pass"><?php _e('Retype Password', ET_DOMAIN) ?></label>
						<input type="password" class="form-control" id="repeat_pass" name="repeat_pass" placeholder="<?php _e("Retype password", ET_DOMAIN) ?>">
					</div>
					<div class="clearfix"></div>
					<?php if(get_theme_mod( 'termofuse_checkbox', false )){ ?>
					<div class="form-group policy-agreement">
						<input name="agreement" id="agreement" type="checkbox" />
						<?php printf(__('I agree with the <a href="%s" target="_Blank" rel="noopener noreferrer" >Term of Use and Privacy policy</a>', ET_DOMAIN), et_get_page_link('tos') ); ?>
					</div>
                    <div class="clearfix"></div>
                    <?php } ?>

					<button type="submit" class="btn-submit btn-sumary btn-sub-create">
						<?php _e('Sign up', ET_DOMAIN) ?>
					</button>
					<?php if(!get_theme_mod( 'termofuse_checkbox', false )){ ?>
					<p class="text-term">
						<?php
		                /**
		                 * tos agreement
		                */
		                $tos = et_get_page_link('tos', array() ,false);
		                $url_tos = '<a href="'.et_get_page_link('tos').'" rel="noopener noreferrer" target="_Blank">'.__('Term of Use and Privacy policy', ET_DOMAIN).'</a>';
		                if($tos) {
		                	printf(__('By creating an account, you agree to our %s', ET_DOMAIN), $url_tos ); 
		                } ?>
					</p>
					<?php }
		                if( function_exists('ae_render_social_button')){
		                    $before_string = __("You can also sign in by:", ET_DOMAIN);
		                    ae_render_social_button( array(), array(), $before_string );
		                }
		            ?>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog register -->
</div><!-- /.modal -->
