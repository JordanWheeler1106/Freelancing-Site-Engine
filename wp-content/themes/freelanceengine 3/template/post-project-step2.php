<?php 
    $step = 2;
    $disable_plan = ae_get_option('disable_plan', false);
    if($disable_plan) $step--;
?>
<div class="step-wrapper step-auth" id="step-auth">
    <a href="#" class="step-heading active">
    	<span class="number-step"><?php echo $step; ?></span>
        <span class="text-heading-step">
            <?php 
                if(fre_check_register()){
                    _e("Login or Register", ET_DOMAIN);
                }else{
                    _e("Login to your account", ET_DOMAIN);
                }
            ?>
        </span>
        <i class="fa fa-caret-right"></i>
    </a>
    <div class="step-content-wrapper content  " style="<?php if($step != 1) echo "display:none;" ?>"    >
        <div class="tab-content">
            <div class="tab-pane fade " id="signup">
            	<div class="text-intro-acc">
            		<?php _e('Already have an account?', ET_DOMAIN) ?>&nbsp;&nbsp;<a href="#signin" role="tab" data-toggle="tab"><?php _e('Login', ET_DOMAIN); ?></a>
                </div>
                <form role="form" id="signup_form_submit" class="signup_form_submit">
                    <input type="hidden" name="role" id="role" value="employer" />
                    <?php
                        $disable_name = apply_filters('free_register_disable_name','');
                        if(!$disable_name){
                            ?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label title-plan" for="first_name"><?php _e('First Name', ET_DOMAIN) ?><span><?php _e("Enter first name", ET_DOMAIN) ?></span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control text-field" id="first_name" name="first_name" placeholder="<?php _e("Enter first name", ET_DOMAIN) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label title-plan" for="last_name"><?php _e('Last Name', ET_DOMAIN) ?><span><?php _e("Enter last name", ET_DOMAIN) ?></span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control text-field" id="last_name" name="last_name" placeholder="<?php _e("Enter last name", ET_DOMAIN) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <?php
                        }
                    ?>
                    <div class="form-group">
                    	<div class="row">
                        	<div class="col-md-4">
                            	<label class="control-label title-plan" for="user_login"><?php _e('Username', ET_DOMAIN) ?><span><?php _e('Enter username', ET_DOMAIN) ?></span></label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control text-field" id="user_login" name="user_login" placeholder="<?php _e("Enter username", ET_DOMAIN); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                    	<div class="row">
                        	<div class="col-md-4">
                            	<label class="control-label title-plan" for="user_email"><?php _e('Email address', ET_DOMAIN) ?><span><?php _e('Enter a email', ET_DOMAIN) ?></span></label>
                            </div>
                            <div class="col-sm-8">
                                <input type="email" class="form-control text-field" id="user_email" name="user_email" placeholder="<?php _e("Your email address", ET_DOMAIN); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                    	<div class="row">
                        	<div class="col-md-4">
                            	<label class="control-label title-plan" for="user_pass"><?php _e('Password', ET_DOMAIN) ?><span><?php _e('Enter password', ET_DOMAIN) ?></span></label>
                            </div>
                            <div class="col-sm-8">
                                <input type="password" class="form-control text-field" id="user_pass" name="user_pass" placeholder="<?php _e('Password', ET_DOMAIN);?>">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                    	<div class="row">
                        	<div class="col-md-4">
                            	<label class="control-label title-plan" for="repeat_pass"><?php _e('Retype Password', ET_DOMAIN) ?><span><?php _e('Retype password', ET_DOMAIN) ?></span></label>
                            </div>
                            <div class="col-sm-8">
                                <input type="password" class="form-control text-field" id="repeat_pass" name="repeat_pass" placeholder="<?php _e('Password', ET_DOMAIN);?>">
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <?php if(get_theme_mod( 'termofuse_checkbox', false )){ ?>
                    <div class="form-group policy-agreement">
                        <div class="row">
                            <div class="col-md-offset-4 col-md-8" >
                                <input name="agreement" id="agreement" type="checkbox" />
                                <?php printf(__('I agree with the <a href="%s" target="_Blank" rel="noopener noreferrer" >Term of Use and Privacy policy</a>', ET_DOMAIN), et_get_page_link('tos') ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php } ?>
                    <div class="form-group">
                    	<div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-submit btn-submit-login-form">
                                    <?php _e('Create account', ET_DOMAIN) ?>
                                </button>
                            </div>
                        </div>
                        <?php if(!get_theme_mod( 'termofuse_checkbox', false )){ ?>
                        <div class="row">
                            <div class="col-md-offset-4 col-md-8" style="margin-top:10px;">
                            <?php
                                /**
                                 * tos agreement
                                */
                                $tos = et_get_page_link('tos', array() ,false);
                                if($tos) {
                                    $url_tos = '<a href="'.et_get_page_link('tos').'" rel="noopener noreferrer" target="_Blank">'.__('Term of Use and Privacy policy', ET_DOMAIN).'</a>';
                            ?>
                                <p class="text-policy">
                                    <?php printf(__('By creating an account, you agree to our %s', ET_DOMAIN), $url_tos );  ?>
                                </p>
                            <?php
                                }
                            ?>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row">
                            <?php
                                if( function_exists('ae_render_social_button')){
                                    $before_string = __("You can also sign in by:", ET_DOMAIN);
                                    ae_render_social_button( array(), array(), $before_string );
                                }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade in active" id="signin">
                <?php if(fre_check_register()){ ?>
                    <div class="text-intro-acc">
                		<?php _e('You do not have an account?', ET_DOMAIN) ?>&nbsp;&nbsp;<a href="#signup" role="tab" data-toggle="tab"><?php _e('Register', ET_DOMAIN) ?></a>
                    </div>
                <?php } ?>
                <form role="form" id="signin_form_submit" class="signin_form_submit">
                    <div class="form-group">
                    	<div class="row">
                        	<div class="col-md-4">
                            	<label class="control-label title-plan" for="user_login"><?php _e('Username', ET_DOMAIN) ?><span><?php _e('Enter Username', ET_DOMAIN) ?></span></label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control text-field" id="user_login" name="user_login" placeholder="<?php _e('Enter username', ET_DOMAIN);?>">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                    	<div class="row">
                        	<div class="col-md-4">
                            	<label class="control-label title-plan" for="user_pass"><?php _e('Password', ET_DOMAIN) ?><span><?php _e('Enter Password', ET_DOMAIN) ?></span></label>
                            </div>
                            <div class="col-sm-8">
                                <input type="password" class="form-control text-field" id="user_pass" name="user_pass" placeholder="<?php _e('Password', ET_DOMAIN);?>">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
                    	<div class="row">
                        	<div class="col-md-4">
                            </div>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-submit btn-submit-login-form">
                                    <?php _e('Submit', ET_DOMAIN) ?>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                                if(fre_check_register() && function_exists('ae_render_social_button')){
                                    $before_string = __("You can also sign in by:", ET_DOMAIN);
                                    ae_render_social_button( array(), array(), $before_string );
                                }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Step 2 / End -->