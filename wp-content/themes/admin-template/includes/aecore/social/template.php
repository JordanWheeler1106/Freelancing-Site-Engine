<?php
/**
 * Generate social connect page template;
 */
function ae_page_social_connect() {

	global $et_data;
	if ( ! isset( $_SESSION ) ) {
		ob_start();
		@session_start();
	}
	$labels = $et_data['auth_labels'];

	$et_session = et_read_session();
	if ( isset( $et_session['et_auth'] ) && $et_session['et_auth'] != '' ) {
		$auth = unserialize( $et_session['et_auth'] );
	} elseif ( isset( $_SESSION['et_auth'] ) && $_SESSION['et_auth'] != '' ) {
		$auth = unserialize( $_SESSION['et_auth'] );
	}

	?>

    <div class="fre-authen-wrapper">
        <div class="fre-authen-social">
            <h2><?php echo $labels['title']; ?></h2>
            <form id="form_username" method="post" action="">
                <div class="fre-input-field">
                    <input type="hidden" name="et_nonce" value="<?php echo wp_create_nonce( 'authentication' ) ?>">
                    <input type="hidden" name="user_email" value="<?php if ( isset( $auth['user_email'] ) ) {
						echo $auth['user_email'];
					} ?>">
                    <input type="text" name="user_login"
                           value="<?php echo isset( $auth['user_login'] ) ? $auth['user_login'] : "" ?>"
                           placeholder="<?php _e( 'Username', ET_DOMAIN ) ?>">
                </div>
				<?php
				$role_default      = ae_get_social_login_user_roles_default();
				$social_user_roles = ae_get_option( 'social_user_role', false );
				if ( ! $social_user_roles ) {
					$social_user_roles = ae_get_social_login_user_roles_default();
				}
				if ( $social_user_roles && count( $social_user_roles ) >= 1 ) { ?>
                    <div class="fre-input-field">
                        <select name="user_role" id="user_role" class="fre-chosen-single">
                            <option selected disabled value="">Choose your role</option>
							<?php foreach ( $social_user_roles as $key => $value ) { ?>
                                <option value="<?php echo $key ?>"><?php echo $role_default[ $value ]; ?></option>
							<?php } ?>
                        </select>
                    </div>
				<?php } ?>
                <div class="fre-input-field">
                    <input type="submit" class="fre-submit-btn btn-submit" value="<?php _e( 'Sign Up', ET_DOMAIN ); ?>">
                </div>
            </form>
        </div>
    </div>

	<?php
}

/**
 *Generate short code phot social connect page
 */
function ae_social_connect_page_shortcode() {
	return ae_page_social_connect();
}

add_shortcode( 'social_connect_page', 'ae_social_connect_page_shortcode' );
/**
 * init social login feature
 */
function init_social_login() {
	if ( ae_get_option( 'twitter_login', false ) ) {
		new ET_TwitterAuth();
	}
	if ( ae_get_option( 'facebook_login', false ) ) {
		new ET_FaceAuth();
	}
	if ( ae_get_option( 'gplus_login', false ) ) {
		new ET_GoogleAuth();
	}
	if ( ae_get_option( 'linkedin_login', false ) ) {
		new ET_LinkedInAuth();
	}
}

/**
 *get user for social login
 */
function ae_social_auth_support_role() {
	$default = array(
		'author'      => __( 'author', ET_DOMAIN ),
		'subscriber'  => __( 'subscriber', ET_DOMAIN ),
		'editor'      => __( 'editor', ET_DOMAIN ),
		'contributor' => __( 'contributor', ET_DOMAIN )
	);

	return apply_filters( 'ae_social_auth_support_role', $default );
}

/**
 *Render the social login button
 *
 * @param array $icon_classes are css classes for displaying social buttons
 * @param array $button_classes are css classes for displaying social buttons
 * @param string $before_text are text display before social login buttons
 * @param string $after_text are text display after social login buttons
 *
 * @since version 1.8.4 of DE
 *
 */
function ae_render_social_button( $icon_classes = array(), $button_classes = array(), $before_text = '', $after_text = '' ) {
	/* check enable option*/
	$use_facebook   = ae_get_option( 'facebook_login' );
	$use_twitter    = ae_get_option( 'twitter_login' );
	$gplus_login    = ae_get_option( 'gplus_login' );
	$linkedin_login = ae_get_option( 'linkedin_login' );
	if ( $icon_classes == '' ) {
		$icon_classes = 'fa fa-facebook-square';
	}
	$defaults_icon  = array(
		'fb'    => 'fa fa-facebook-square',
		'gplus' => 'fa fa-google-plus-square',
		'tw'    => 'fa fa-twitter-square',
		'lkin'  => 'fa fa-linkedin-square'
	);
	$icon_classes   = wp_parse_args( $icon_classes, $defaults_icon );
	$icon_classes   = apply_filters( 'ae_social_icon_classes', $icon_classes );
	$defaults_btn   = array(
		'fb'    => '',
		'gplus' => '',
		'tw'    => '',
		'lkin'  => ''
	);
	$button_classes = wp_parse_args( $button_classes, $defaults_btn );
	$button_classes = apply_filters( 'ae_social_button_classes', $button_classes );
	if ( $use_facebook || $use_twitter || $gplus_login || $linkedin_login ) {
		if ( $before_text != '' ) { ?>
            <p><?php echo $before_text ?></p>
		<?php } ?>
        <ul class="login-social-list">
			<?php if ( $use_facebook ) { ?>
                <li id="facebook">
                    <a href="#" class="fb facebook_auth_btn <?php echo $button_classes['fb']; ?>">
                        <i class="<?php echo $icon_classes['fb']; ?>"></i>
                        <span class="social-text"><?php _e( "Facebook", ET_DOMAIN ) ?></span>
                    </a>
                </li>
			<?php } ?>
			<?php if ( $gplus_login ) { ?>
                <li id="google">
                    <a href="#" class="gplus gplus_login_btn <?php echo $button_classes['gplus']; ?>">
                        <i class="<?php echo $icon_classes['gplus']; ?>"></i>
                        <span class="social-text"><?php _e( "Google", ET_DOMAIN ) ?></span>
                    </a>
                </li>
			<?php } ?>
			<?php if ( $use_twitter ) { ?>
                <li id="twitter">
                    <a href="<?php echo add_query_arg( 'action', 'twitterauth', home_url() ) ?>"
                       class="tw <?php echo $button_classes['tw']; ?>">
                        <i class="<?php echo $icon_classes['tw']; ?>"></i>
                        <span class="social-text"><?php _e( "Twitter", ET_DOMAIN ) ?></span>
                    </a>
                </li>
			<?php } ?>
			<?php if ( $linkedin_login ) { ?>
                <li id="linkedin">
                    <a href="#" class="lkin <?php echo $button_classes['tw']; ?>">
                        <i class="<?php echo $icon_classes['lkin']; ?>"></i>
                        <span class="social-text"><?php _e( "Linkedin", ET_DOMAIN ) ?></span>
                    </a>
                </li>
			<?php } ?>
        </ul>
		<?php
		if ( $after_text != '' ) { ?>
            <div class="socials-footer"><?php echo $after_text ?></div>
		<?php }
	}
}