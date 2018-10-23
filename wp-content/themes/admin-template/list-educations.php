<?php
/**
 * Created by PhpStorm.
 * User: Dao
 * Date: 5/25/2017
 * Time: 10:19 AM
 * Use for page author.php and page-profile.php
 */
global $wpdb;

$is_edit = false;
if(is_author()){
	$author_id        = get_query_var( 'author' );
}else{
	$author_id        = get_current_user_id();
	$is_edit = true;
}


$educations = array();
$profile_id = get_user_meta( $author_id, 'user_profile_id', true );
if($profile_id){
	// not use get_post_meta because this not get meta_id
    //$educations = get_post_meta( $profile_id, 'education' );
	$query      = 'SELECT * FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . $profile_id . ' AND meta_key = "education" ORDER BY meta_id DESC';
	$educations = $wpdb->get_results( $query );
}


if ( $is_edit or ! empty( $educations ) ) {
	?>

    <div class="fre-profile-box">
        <div class="profile-freelance-education">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h2 class="freelance-education-title"><?php _e( 'Education', ET_DOMAIN ) ?></h2>
                </div>
                <span id="fre-empty-education">
				    <?php if ( $is_edit ) { ?>
                    <div class="col-sm-6 col-xs-12">
                        <div class="freelance-education-add">
                            <a href="javascript:void(0)"
                               class="fre-normal-btn-o profile-show-edit-tab-btn" data-ctn_hide="fre-empty-education"
                               data-ctn_edit="ctn-edit-education">
								<?php _e( 'Add new', ET_DOMAIN ) ?>
                            </a>
                        </div>
                    </div>
				    <?php } ?>
                    <p class="col-xs-12 fre-empty-optional-profile" <?php echo (empty($educations) and $is_edit) ? '' : 'style="display : none"' ?>>
                        <?php _e('Add work education to your profile. (optional)',ET_DOMAIN) ?>
                    </p>
                </span>
            </div>

            <ul class="freelance-education-list">
				<?php if ( $is_edit ) { ?>
                    <!-- Box add new education-->
                    <li class="freelance-education-new-wrap cnt-profile-hide"
                        id="ctn-edit-education">
                        <div class="freelance-education-new">
                            <form class="fre-education-form freelance-education-form" action=""
                                  method="post">

                                <div class="fre-input-field">
                                    <input type="text" name="education[title]"
                                           placeholder="<?php _e( 'Degree', ET_DOMAIN ) ?>">
                                </div>

                                <div class="fre-input-field">
                                    <input type="text" name="education[subtitle]"
                                           placeholder="<?php _e( 'School', ET_DOMAIN ) ?>">
                                </div>

                                <div class="fre-input-field no-margin-bottom">
                                    <label class="fre-field-title"
                                           for=""><?php _e( 'From period', ET_DOMAIN ) ?></label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="fre-input-field">
                                                <select class="fre-chosen-single" name="education[m_from]" id="">
                                                    <option value=""><?php _e('Month',ET_DOMAIN) ?></option>
													<?php
													for ( $i = 1; $i <= 12; $i ++ ) {
														if ( intval( $i ) < 10 ) {
															$i = '0' . $i;
														}
														$mont   = date( 'Y', time() ) . '-' . $i . '-' . date( 'd', time() );
														$name_m = date( 'F', strtotime( $mont ) ); ?>
                                                        <option value="<?php echo $i ?>"><?php _e( $name_m, ET_DOMAIN ) ?></option>;
													<?php } ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="fre-input-field">
                                                <select class="fre-chosen-single" name="education[y_from]" id="">
                                                    <option value=""><?php _e('Year',ET_DOMAIN) ?></option>
													<?php
													for ( $i = date( 'Y', time() ); $i >= intval( date( 'Y', time() ) ) - 50; $i -- ) {
														echo '<option value="' . $i . '">' . $i . '</option>';
													} ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="fre-input-field no-margin-bottom">
                                    <textarea name="education[content]" cols="30" rows="10" placeholder="<?php _e('Description (optional)',ET_DOMAIN) ?>"></textarea>
                                </div>

                                <div class="fre-form-btn">
                                    <input type="submit" class="fre-normal-btn btn-submit" name=""
                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                    <span class="fre-education-close profile-show-edit-tab-btn"
                                          data-ctn_edit="fre-empty-education"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                </div>
                            </form>
                        </div>
                    </li>
                    <!-- End Box add new education-->
				<?php } ?>

				<?php if ( ! empty( $educations ) ) {
					foreach ( $educations as $k => $education ) {
						if ( ! empty( $education->meta_value ) && is_serialized( $education->meta_value ) ) {
							$e_value = unserialize( $education->meta_value );
							if ( is_serialized( $e_value ) ) {
								$e_value = unserialize( $e_value );
							}
							if ( ! empty( $e_value ) ) {
								?>

                                <!-- Box show education-->
                                <li class="cnt-profile-hide meta_history_item_<?php echo $education->meta_id ?>"
                                    id="cnt-education-default-<?php echo $education->meta_id ?>"
                                    style="<?php echo $k + 1 == count( $educations ) ? 'border-bottom: 0;padding-bottom: 0;' : '' ?>">
                                    <div class="freelance-education-wrap">
                                        <h2><?php echo $e_value['title'] ?></h2>
                                        <h3><?php echo $e_value['subtitle'] ?></h3>
                                        <h4>
	                                        <?php
	                                        $string_time = '01-'.$e_value['m_from']. '-' . $e_value['y_from'];
	                                        $date_fr_option  = timeFormatRemoveDate(get_option('date_format'));
	                                        $date_fr = date_i18n(trim($date_fr_option),strtotime($string_time));
	                                        echo ($date_fr);
	                                        ?>
                                        </h4>
                                        <?php echo apply_filters( 'the_content', $e_value['content'] ) ?>
                                    </div>
									<?php if ( $is_edit ) { ?>
                                        <div class="freelance-education-action">
                                            <a href="javascript:void(0)" class="profile-show-edit-tab-btn"
                                               data-ctn_edit="ctn-edit-education-<?php echo $education->meta_id ?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												<?php _e( 'Edit', ET_DOMAIN ) ?>
                                            </a>
                                            <a href="javascript:void(0)" class="remove_history_fre" data-id="<?php echo $education->meta_id ?>">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i><?php _e('Remove',ET_DOMAIN) ?></a>
                                        </div>
									<?php } ?>
                                </li>
                                <!-- End Box show education-->

								<?php if ( $is_edit ) { ?>
                                    <!-- Box edit education-->
                                    <li class="freelance-education-new-wrap cnt-profile-hide meta_history_item_<?php echo $education->meta_id ?>"
                                        id="ctn-edit-education-<?php echo $education->meta_id ?>">
                                        <div class="freelance-education-new">
                                            <form class="fre-education-form freelance-education-form" action=""
                                                  method="post">

                                                <div class="fre-input-field">
                                                    <input type="text" name="education[title]"
                                                           placeholder="<?php _e( 'Degree', ET_DOMAIN ) ?>"
                                                           value="<?php echo $e_value['title'] ?>">
                                                </div>

                                                <div class="fre-input-field">
                                                    <input type="text" name="education[subtitle]"
                                                           value="<?php echo $e_value['subtitle'] ?>"
                                                           placeholder="<?php _e( 'School', ET_DOMAIN ) ?>">
                                                </div>

                                                <div class="fre-input-field no-margin-bottom">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="fre-input-field">
                                                                <select class="fre-chosen-single"
                                                                        name="education[m_from]"
                                                                        id="">
                                                                    <option value=""><?php _e('Month',ET_DOMAIN) ?></option>
																	<?php
																	for ( $i = 1; $i <= 12; $i ++ ) {
																		if ( intval( $i ) < 10 ) {
																			$i = '0' . $i;
																		}
																		$mont   = date( 'Y', time() ) . '-' . $i . '-' . date( 'd', time() );
																		$name_m = date( 'F', strtotime( $mont ) ); ?>
                                                                        <option value="<?php echo $i ?>" <?php echo ! empty( $e_value['m_from'] ) && $e_value['m_from'] == $i ? 'selected' : '' ?>><?php _e( $name_m, ET_DOMAIN ) ?></option>;
																	<?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="fre-input-field">
                                                                <select class="fre-chosen-single"
                                                                        name="education[y_from]"
                                                                        id="">
                                                                    <option value=""><?php _e('Year',ET_DOMAIN) ?></option>
																	<?php
																	for ( $i = date( 'Y', time() ); $i >= intval( date( 'Y', time() ) ) - 50; $i -- ) {
																		! empty( $e_value['y_from'] ) && $e_value['y_from'] == $i ? $selected = 'selected' : $selected = '';
																		echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
																	} ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="fre-input-field no-margin-bottom">
                                            <textarea name="education[content]" id="" cols="30" placeholder="<?php _e('Description (optional)',ET_DOMAIN) ?>"
                                                      rows="10"><?php echo ! empty( $e_value['content'] ) ? $e_value['content'] : '' ?></textarea>
                                                </div>

                                                <input type="hidden" value="<?php echo $education->meta_id ?>"
                                                       name="education[id]">

                                                <div class="fre-form-btn">
                                                    <input type="submit" class="fre-normal-btn btn-submit" name=""
                                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                                    <span class="fre-education-close profile-show-edit-tab-btn"
                                                          data-ctn_edit="cnt-education-default-<?php echo $education->meta_id ?>"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                    <!-- Box edit education-->
								<?php } ?>

								<?php
							}
						}
					}
				} ?>
            </ul>
        </div>
    </div>
<?php } ?>