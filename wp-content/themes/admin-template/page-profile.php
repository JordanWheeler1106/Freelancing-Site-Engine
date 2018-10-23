<?php
/**
 * Template Name: Member Profile Page
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.0
 */
global $wp_query, $ae_post_factory, $post, $current_user, $user_ID;
//convert current user
$ae_users  = AE_Users::get_instance();
$user_data = $ae_users->convert( $current_user->data );
$user_role = ae_user_role( $current_user->ID );
//convert current profile
$post_object = $ae_post_factory->get( PROFILE );

$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );

$profile = array();
if ( $profile_id ) {
	$profile_post = get_post( $profile_id );
	if ( $profile_post && ! is_wp_error( $profile_post ) ) {
		$profile = $post_object->convert( $profile_post );
	}
}

//get profile skills
$current_skills = get_the_terms( $profile, 'skill' );
//define variables:
$skills         = isset( $profile->tax_input['skill'] ) ? $profile->tax_input['skill'] : array();
$job_title      = isset( $profile->et_professional_title ) ? $profile->et_professional_title : '';
$hour_rate      = isset( $profile->hour_rate ) ? $profile->hour_rate : '';
$currency       = isset( $profile->currency ) ? $profile->currency : '';
$experience     = isset( $profile->et_experience ) ? $profile->et_experience : '';
$hour_rate      = isset( $profile->hour_rate ) ? $profile->hour_rate : '';
$about          = isset( $profile->post_content ) ? $profile->post_content : '';
$display_name   = $user_data->display_name;
$user_available = isset( $user_data->user_available ) && $user_data->user_available == "on" ? 'checked' : '';
$country        = isset( $profile->tax_input['country'][0] ) ? $profile->tax_input['country'][0]->name : '';
$category       = isset( $profile->tax_input['project_category'][0] ) ? $profile->tax_input['project_category'][0]->slug : '';

get_header();
// Handle email change requests
$user_meta = get_user_meta( $user_ID, 'adminhash', true );

if ( ! empty( $_GET['adminhash'] ) ) {
	if ( is_array( $user_meta ) && $user_meta['hash'] == $_GET['adminhash'] && ! empty( $user_meta['newemail'] ) ) {
		wp_update_user( array(
			'ID'         => $user_ID,
			'user_email' => $user_meta['newemail']
		) );
		delete_user_meta( $user_ID, 'adminhash' );
	}
	echo "<script> window.location.href = '" . et_get_page_link( "profile" ) . "'</script>";
} elseif ( ! empty( $_GET['dismiss'] ) && 'new_email' == $_GET['dismiss'] ) {
	delete_user_meta( $user_ID, 'adminhash' );
	echo "<script> window.location.href = '" . et_get_page_link( "profile" ) . "'</script>";
}

$rating        = Fre_Review::employer_rating_score( $user_ID );
$role_template = 'employer';
if ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) {
	$rating        = Fre_Review::freelancer_rating_score( $user_ID );
	$role_template = 'freelance';
}

$projects_worked = get_post_meta( $profile_id, 'total_projects_worked', true );
$project_posted  = fre_count_user_posts_by_type( $user_ID, 'project', '"publish","complete","close","disputing","disputed", "archive" ', true );
$hire_freelancer = fre_count_hire_freelancer( $user_ID );

$currency = ae_get_option( 'currency', array(
	'align' => 'left',
	'code'  => 'USD',
	'icon'  => '$'
) );
?>


<div class="fre-page-wrapper list-profile-wrapper">
    <div class="fre-page-title">
        <div class="container">
            <h2><?php _e( 'My Profile', ET_DOMAIN ) ?></h2>
        </div>
    </div>

    <div class="fre-page-section">
        <div class="container">
            <div class="profile-<?php echo $role_template; ?>-wrap">
				<?php if ( empty( $profile_id ) && ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) ) { ?>
                    <div class="notice-first-login">
                        <p>
                            <i class="fa fa-warning"></i><?php _e( 'You must complete your profile to do any activities on site', ET_DOMAIN ) ?>
                        </p>
                    </div>
				<?php } ?>
                <div class="fre-profile-box">
                    <div class="profile-<?php echo $role_template; ?>-info-wrap active">

                        <div class="profile-freelance-info cnt-profile-hide" id="cnt-profile-default"
                             style="display: block">
                            <div class="freelance-info-avatar">
                                <span class="freelance-avatar"><?php echo get_avatar( $user_data->ID, 125 ) ?> </span>
                                <span class="freelance-name">
                                    <?php echo $display_name ?>

									<?php if ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) {
										if ( $job_title ) { ?>
                                            <span><?php echo $job_title ?></span>
										<?php } else { ?>
                                            <span class="freelance-empty-info"><i><?php _e( 'No professional title', ET_DOMAIN ) ?></i></span>
										<?php }
									} ?>
                                </span>
                            </div>
                            <div class="<?php echo $role_template; ?>-info-content">
                                <div class="freelance-rating">
                                        <span class="rate-it"
                                              data-score="<?php echo $rating['rating_score']; ?>"></span>

									<?php if ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) { ?>
                                        <span class="freelance-empty-info">
                                            <?php echo ! empty( $profile->experience ) ? $profile->experience : '<i>' . __( 'No year experience information', ET_DOMAIN ) . '</i>'; ?>
                                        </span>


                                        <span><?php _e( intval( $projects_worked ) . ' projects worked', ET_DOMAIN ) ?> </span>
									<?php } else { ?>
                                        <span class=""><?php _e( $project_posted . ' projects posted', ET_DOMAIN ) ?></span>
                                        <span> <?php _e( 'hire ' . $hire_freelancer . ' freelancers', ET_DOMAIN ) ?></span>
									<?php } ?>

									<?php
									if ( ! empty( $profile->tax_input['country'] ) ) {
										echo '<span>' . $profile->tax_input['country']['0']->name . '</span>';
									} else { ?>
                                        <span class="freelance-empty-info"><?php echo '<i>' . __( 'No country information', ET_DOMAIN ) . '</i>'; ?></span>
									<?php } ?>
                                </div>

								<?php if ( ! fre_share_role() && ae_user_role( $user_ID ) != FREELANCER ) { ?>
                                    <div class="employer-mem-since">
                                            <span>
                                                <?php _e( 'Member since:', ET_DOMAIN ); ?>
                                                <?php
                                                if ( isset( $user_data->user_registered ) ) {
	                                                echo date_i18n( get_option( 'date_format' ), strtotime( $user_data->user_registered ) );
                                                }
                                                ?>
                                            </span>
                                    </div>
								<?php } ?>

								<?php if ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) { ?>
                                    <div class="freelance-hourly">
                                            <span>
                                                <b>
                                                    <?php echo ! empty( $profile->hour_rate ) ? fre_price_format( $profile->hour_rate ) : '0'; ?>
                                                </b>
                                                    /<?php _ex( 'hr', ET_DOMAIN ) ?>
                                            </span>
                                        <span>
                                            <?php echo ! empty( $profile->earned ) ? $profile->earned : price_about_format( 0 ) . ' ' . __( 'earned', ET_DOMAIN ) ?>
                                        </span>
                                    </div>

									<?php
									if ( isset( $profile->tax_input['skill'] ) && $profile->tax_input['skill'] ) {
										echo '<div class="freelance-skill">';
										foreach ( $profile->tax_input['skill'] as $tax ) {
											echo '<span class="fre-label">' . $tax->name . '</span>';
										}
										echo '</div>';
									} else { ?>
                                        <span class="freelance-empty-skill"><?php _e( 'No skill information', ET_DOMAIN ) ?></span>
									<?php } ?>
								<?php } ?>

								<?php if ( ! empty( $profile ) ) { ?>
                                    <div class="freelance-about">
										<?php echo $about; ?>
                                    </div>

									<?php if ( function_exists( 'et_the_field' ) && ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) ) {
										et_render_custom_field( $profile );
									}
									?>
								<?php } ?>

								<?php //do_action( 'fre_after_block_user_info', $user_id ); ?>
                            </div>
                            <div class="employer-info-edit">
                                <a href="javascript:void(0)"
                                   class="fre-normal-btn-o employer-info-edit-btn profile-show-edit-tab-btn"
                                   data-ctn_edit="ctn-edit-profile"><?php _e( 'Edit', ET_DOMAIN ) ?></a>
                            </div>
                            <a href="<?php echo $user_data->author_url ?>"
                               class="fre-view-as-others"><?php _e( 'View my profile as others', ET_DOMAIN ) ?></a>
                        </div>

                        <div class="profile-employer-info-edit cnt-profile-hide" id="ctn-edit-profile"
                             style="display: none">
                            <div class="employer-info-avatar avatar-profile-page">
                                <span class="employer-avatar img-avatar image">
                                    <?php echo get_avatar( $user_ID, 125 ) ?>
                                </span>
                                <a href="#" id="user_avatar_browse_button">
									<?php _e( 'Change', ET_DOMAIN ) ?>
                                </a>
                            </div>
                            <div class="fre-employer-info-form" id="accordion" role="tablist"
                                 aria-multiselectable="true">
                                <form id="profile_form" class="form-detail-profile-page" action="" method="post"
                                      novalidate>
                                    <div class="fre-input-field">
                                        <input type="text" value="<?php echo $display_name ?>"
                                               name="display_name" id="display_name"
                                               placeholder="<?php _e( 'Your name', ET_DOMAIN ) ?>">
                                    </div>

									<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
                                        <div class="fre-input-field">
                                            <input type="text" name="et_professional_title"
												<?php if ( $job_title ) {
													echo 'value= "' . esc_attr( $job_title ) . '" ';
												} ?>
                                                   placeholder="<?php _e( "Professional Title", ET_DOMAIN ) ?>">
                                        </div>
									<?php } ?>

                                    <div class="fre-input-field">
										<?php
										$country_arr = array();
										if ( ! empty( $profile->tax_input['country'] ) ) {
											foreach ( $profile->tax_input['country'] as $key => $value ) {
												$country_arr[] = $value->term_id;
											};
										}
										$validate_country = 0;
										if ( fre_share_role() || $user_role == FREELANCER ) {
											$validate_country = 1;
										}
										ae_tax_dropdown( 'country',
											array(
												'attr'            => 'data-chosen-width="100%" data-validate_filed = "' . $validate_country . '" data-chosen-disable-search="" data-placeholder="' . __( "Choose country", ET_DOMAIN ) . '"',
												'class'           => 'fre-chosen-single',
												'hide_empty'      => 0,
												'hierarchical'    => false,
												'id'              => 'country',
												'selected'        => $country_arr,
												'show_option_all' => __( "Select country", ET_DOMAIN ),
											)
										);
										?>
                                    </div>

									<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
                                        <div class="fre-input-field fre-experience-field">
                                            <input type="number" value="<?php echo $experience; ?>" name="et_experience"
                                                   id="et_experience" min="0"
                                                   placeholder="<?php _e( 'Total', ET_DOMAIN ) ?>">
                                            <span><?php _e( 'years experience', ET_DOMAIN ) ?></span>
                                        </div>
                                        <div class="fre-input-field fre-hourly-field">
                                            <input type="number" <?php if ( $hour_rate ) {
												echo "value= $hour_rate ";
											} ?> name="hour_rate" id="hour_rate" step="5" min="0"
                                                   placeholder="<?php _e( 'Hour rate', ET_DOMAIN ) ?>">
                                            <span>
                                                <?php echo $currency['icon'] ?><?php _e( '/hr', ET_DOMAIN ) ?></span>
                                        </div>

                                        <div class="fre-input-field">
											<?php
											$c_skills = array();
											if ( ! empty( $current_skills ) ) {
												foreach ( $current_skills as $key => $value ) {
													$c_skills[] = $value->term_id;
												};
											}
											ae_tax_dropdown( 'skill',
												array(
													'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="' . sprintf( __( " Skills (max is %s)", ET_DOMAIN ), ae_get_option( 'fre_max_skill', 5 ) ) . '"',
													'class'           => 'fre-chosen-multi',
													'hide_empty'      => false,
													'hierarchical'    => false,
													'id'              => 'skill',
													'show_option_all' => false,
													'selected'        => $c_skills
												)
											);
											?>
                                        </div>

                                        <div class="fre-input-field">
                                            <label class="fre-checkbox no-margin-bottom" for="email-skill">
                                                <input id="email-skill" type="checkbox" name="email_skill"
                                                       value="1" <?php echo ! empty( $profile->email_skill ) ? 'checked' : '' ?>>
                                                <span></span>
												<?php _e( 'Email me jobs that are relevant to my skills', ET_DOMAIN ) ?>
                                            </label>
                                        </div>

									<?php } ?>

                                    <div class="fre-input-field">
										<?php wp_editor( '', 'post_content', ae_editor_settings() ); ?>
                                    </div>

									<?php if ( ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) ) {
										do_action( 'ae_edit_post_form', PROFILE, $profile );
									} ?>

                                    <div class="employer-info-save btn-update-profile">
                                        <input type="submit" class="fre-normal-btn btn-submit" name=""
                                               style="width: 100%"
                                               value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                        <span class="employer-info-cancel-btn profile-show-edit-tab-btn"
                                              data-ctn_edit="cnt-profile-default"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

					<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
                        <div class="profile-freelance-available">
                            <h2><?php _e( 'Available for hire', ET_DOMAIN ) ?></h2>
                            <!--<div class="fre-input-field">
                                <input type="checkbox" <?php /*echo $user_available; */ ?> class="js-switch user-available"
                                       name="user_available"/>
                                <span class="user-status-text text <?php /*echo $user_available ? 'yes' : 'no' */ ?>"></span>
                            </div>-->

                            <div class="fre-input-field">
                                <label for="fre-switch-user-available" class="fre-switch">
                                    <input id="fre-switch-user-available"
                                           type="checkbox" <?php echo $user_available ? 'checked' : ''; ?>>
                                    <div class="fre-switch-slider">
                                    </div>
                                </label>
                            </div>
                            <p class="freelance-available-desc"><?php _e( 'Turn on to display an “Invite me”  button on your profile, allowing potential employers to suggest projects for you.', ET_DOMAIN ) ?></p>
                        </div>
					<?php } ?>
                </div>

                <div class="fre-profile-box">
                    <div class="profile-employer-secure-wrap active">

                        <h2><?php _e( 'My Account', ET_DOMAIN ); ?></h2>

                        <div class="profile-employer-secure cnt-profile-hide" id="cnt-account-default"
                             style="display: block">
                            <p>
                                <span><?php _e( 'Email address', ET_DOMAIN ) ?></span><?php echo $user_data->user_email ?>
                            </p>
							<?php
							if ( ! empty( $user_meta['newemail'] ) ) {
								printf( __( '<p class="noti-update">There is a pending change of the email to %1$s. <!--<a href="%2$s">Cancel</a>--></p>', ET_DOMAIN ),
									'<code>' . esc_html( $user_meta['newemail'] ) . '</code>',
									esc_url( et_get_page_link( "profile" ) . '?dismiss=new_email' )
								);
							}
							?>
							<?php if ( use_paypal_to_escrow() ) { ?>
                                <p>
                                    <span><?php _e( 'Paypal account', ET_DOMAIN ) ?></span>

									<?php
									$paypal = get_user_meta( $user_ID, 'paypal', true );
									if ( ! empty( $paypal ) ) {
										echo $paypal;
									} else { ?>
                                        <span class="freelance-empty-info"><?php _e( 'No yet update', ET_DOMAIN ) ?></span>
									<?php } ?>
                                </p>
							<?php } ?>

							<?php
							$escrow_stripe_api = ae_get_option( 'escrow_stripe_api', false );
							$use_escrow        = ae_get_option( 'use_escrow', false );
							if ( ! empty( $escrow_stripe_api ) && $use_escrow && function_exists( 'ae_stripe_recipient_field' ) ) {
								if ( ! empty( $escrow_stripe_api['use_stripe_escrow'] ) ) {
									?>
                                    <p>
                                        <span><?php _e( "Stripe account ", ET_DOMAIN ) ?></span>
										<?php do_action( 'ae_escrow_stripe_user_field' ); ?>
                                    </p>
								<?php }
							} ?>

                            <div class="employer-secure-edit">
                                <a href="javascript:void(0)" class="fre-normal-btn-o profile-show-edit-tab-btn"
                                   data-ctn_edit="ctn-edit-account"><?php _e( 'Edit', ET_DOMAIN ) ?></a>
                            </div>
                        </div>

                        <div class="profile-employer-secure-edit cnt-profile-hide" id="ctn-edit-account"
                             style="display: none">
                            <form id="account_form" novalidate>
                                <div class="fre-input-field">
                                    <label><?php _e( 'Email address', ET_DOMAIN ) ?></label>
                                    <input type="email" class="" id="user_email" name="user_email"
                                           value="<?php echo $user_data->user_email ?>"
                                           placeholder="<?php _e( 'Enter email', ET_DOMAIN ) ?>">
                                </div>

								<?php
								$use_escrow = ae_get_option( 'use_escrow', false );
								if ( $use_escrow ) {
									do_action( 'ae_escrow_recipient_field' );
								} ?>

                                <div class="employer-info-save btn-update-profile">
                                    <input type="submit" class="fre-normal-btn fre-btn" name="" style="width: 100%"
                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                    <span class="employer-info-cancel-btn profile-show-edit-tab-btn"
                                          data-ctn_edit="cnt-account-default"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="profile-secure-code-wrap">
                        <p>
                            <span><?php _e( "Password", ET_DOMAIN ) ?></span>
                            <a href="#" class="change-password"><?php _e( "Change Password", ET_DOMAIN ) ?></a>
                        </p>
						<?php if ( function_exists( 'fre_credit_add_request_secure_code' ) ) {
							$fre_credit_secure_code = ae_get_option( 'fre_credit_secure_code' );
							if ( ! empty( $fre_credit_secure_code ) ) {
								?>
                                <ul class="fre-secure-code">
                                    <li>
                                        <span><?php _e( "Secure code", ET_DOMAIN ) ?></span>
                                    </li>
									<?php do_action( 'fre-profile-after-list-setting' ); ?>
                                </ul>
							<?php }
						} ?>
                    </div>
                </div>


				<?php
				if ( fre_share_role() || $user_role == FREELANCER ) {
					get_template_part( 'list', 'portfolios' );
					wp_reset_query();
				} ?>

				<?php if ( fre_share_role() || $user_role == FREELANCER ) {
					get_template_part( 'list', 'experiences' );
					get_template_part( 'list', 'certifications' );
					get_template_part( 'list', 'educations' );
					wp_reset_query();
				} ?>

            </div>
        </div>
    </div>
</div>

<!-- CURRENT PROFILE -->
<?php if ( $profile_id && $profile_post && ! is_wp_error( $profile_post ) ) { ?>
    <script type="data/json" id="current_profile">
    <?php echo json_encode( $profile ) ?>

    </script>
<?php } ?>
<!-- END / CURRENT PROFILE -->

<!-- CURRENT SKILLS -->
<?php if ( ! empty( $current_skills ) ) { ?>
    <script type="data/json" id="current_skills">
    <?php echo json_encode( $current_skills ) ?>

    </script>
<?php } ?>
<!-- END / CURRENT SKILLS -->

<?php
get_footer();
?>

