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

$experiences = array();
$profile_id = get_user_meta( $author_id, 'user_profile_id', true );
if($profile_id){
	// not use get_post_meta because this not get meta_id
    //$experiences = get_post_meta( $profile_id, 'work_experience' );
	$query       = 'SELECT * FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . $profile_id . ' AND meta_key = "work_experience" ORDER BY meta_id DESC';
	$experiences = $wpdb->get_results( $query );
}

if ( $is_edit or !empty( $experiences ) ) {
	?>

    <div class="fre-profile-box">
        <div class="profile-freelance-experience">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h2 class="freelance-experience-title"><?php _e( 'Work Experiences', ET_DOMAIN ) ?></h2>
                </div>

                <span id="fre-empty-experience">
				    <?php if ( $is_edit ) { ?>
                    <div class="col-sm-6 col-xs-12">
                        <div class="freelance-experience-add">
                            <a href="javascript:void(0)"
                               class="fre-normal-btn-o profile-show-edit-tab-btn"
                               data-ctn_edit="ctn-edit-experience" data-ctn_hide="fre-empty-experience">
								<?php _e( 'Add new', ET_DOMAIN ) ?>
                            </a>
                        </div>
                    </div>
				    <?php } ?>
                    <p class="col-xs-12 fre-empty-optional-profile" <?php echo (empty($experiences) and $is_edit) ? '' : 'style="display : none"' ?>>
                        <?php _e('Add work experience to your profile. (optional)',ET_DOMAIN) ?>
                    </p>
                </span>
            </div>

            <ul class="freelance-experience-list">
				<?php if ( $is_edit ) { ?>
                    <!-- Box add new experience-->
                    <li class="freelance-experience-new-wrap cnt-profile-hide"
                        id="ctn-edit-experience">
                        <div class="freelance-experience-new">
                            <form class="fre-experience-form freelance-experience-form" method="post">

                                <div class="fre-input-field">
                                    <input type="text" name="work_experience[title]"
                                           placeholder="<?php _e( 'Title', ET_DOMAIN ) ?>">
                                </div>

                                <div class="fre-input-field">
                                    <input type="text" name="work_experience[subtitle]"
                                           placeholder="<?php _e( 'Company', ET_DOMAIN ) ?>">
                                </div>

                                <div class="fre-input-field no-margin-bottom">
                                    <label class="fre-field-title"
                                           for=""><?php _e( 'From period', ET_DOMAIN ) ?></label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="fre-input-field">
                                                <select class="fre-chosen-single" name="work_experience[m_from]" id="">
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
                                                <select class="fre-chosen-single" name="work_experience[y_from]" id="">
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

                                <div class="fre-input-field no-margin-bottom work_experience_to">
                                    <label class="fre-field-title" for=""><?php _e( 'To period', ET_DOMAIN ) ?></label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="fre-input-field novalidate_if_current">
                                                <select class="fre-chosen-single" name="work_experience[m_to]">
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
                                            <div class="fre-input-field novalidate_if_current">
                                                <select class="fre-chosen-single" name="work_experience[y_to]" id="">
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

                                <div class="fre-input-field">
                                    <label class="fre-checkbox no-margin-bottom" for="currently-working">
                                        <input id="currently-working" type="checkbox"
                                               name="work_experience[currently_working]"
                                               value="1" class="currently-working">
                                        <span></span>
										<?php _e( 'Currently working here', ET_DOMAIN ) ?>
                                    </label>
                                </div>

                                <div class="fre-input-field no-margin-bottom">
                                    <textarea name="work_experience[content]" id="" cols="30" rows="10" placeholder="<?php _e('Description (optional)',ET_DOMAIN) ?>"></textarea>
                                </div>

                                <div class="fre-form-btn">
                                    <input type="submit" class="fre-normal-btn btn-submit" name=""
                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                    <span class="fre-experience-close profile-show-edit-tab-btn"
                                          data-ctn_edit="fre-empty-experience"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                </div>
                            </form>
                        </div>
                    </li>
                    <!-- End Box add new experience-->
				<?php } ?>

				<?php if ( ! empty( $experiences ) ) {
					foreach ( $experiences as $k => $experience ) {
						if ( ! empty( $experience->meta_value ) && is_serialized( $experience->meta_value ) ) {
							$e_value = unserialize( $experience->meta_value );
							if ( is_serialized( $e_value ) ) {
								$e_value = unserialize( $e_value );
							}
							if ( ! empty( $e_value ) ) {
								?>

                                <!-- Box show experience-->
                                <li class="cnt-profile-hide meta_history_item_<?php echo $experience->meta_id ?>"
                                    id="cnt-experience-default-<?php echo $experience->meta_id ?>"
                                    style="<?php echo $k + 1 == count( $experiences ) ? 'border-bottom: 0;padding-bottom: 0;' : '' ?>">
                                    <div class="freelance-experience-wrap">
                                        <h2><?php echo $e_value['title'] ?></h2>
                                        <h3><?php echo $e_value['subtitle'] ?></h3>
                                        <h4>
	                                        <?php
	                                        $string_time = '01-'.$e_value['m_from']. '-' . $e_value['y_from'];
	                                        $date_fr_option  = timeFormatRemoveDate(get_option('date_format'));
	                                        $date_fr = date_i18n(trim($date_fr_option),strtotime($string_time));
	                                        echo ($date_fr);
	                                        ?>
                                            -
                                            <?php if(!empty($e_value['currently_working'])){
                                                echo 'Now';
                                            }else{ ?>
	                                            <?php
	                                            $string_time = '01-'.$e_value['m_to']. '-' . $e_value['y_to'];
	                                            $date_fr_option  = timeFormatRemoveDate(get_option('date_format'));
	                                            $date_fr = date_i18n(trim($date_fr_option),strtotime($string_time));
	                                            echo ($date_fr);
	                                            ?>
                                            <?php } ?>
                                        </h4>
                                        <?php echo apply_filters( 'the_content', $e_value['content'] ) ?>
                                    </div>
									<?php if ( $is_edit ) { ?>
                                        <div class="freelance-experience-action">
                                            <a href="javascript:void(0)" class="profile-show-edit-tab-btn"
                                               data-ctn_edit="ctn-edit-experience-<?php echo $experience->meta_id ?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												<?php _e( 'Edit', ET_DOMAIN ) ?>
                                            </a>
                                            <a href="javascript:void(0)" class="remove_history_fre" data-id="<?php echo $experience->meta_id ?>">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i><?php _e('Remove',ET_DOMAIN) ?></a>
                                        </div>
									<?php } ?>
                                </li>
                                <!-- End Box show experience-->

								<?php if ( $is_edit ) { ?>
                                    <!-- Box edit experience-->
                                    <li class="freelance-experience-new-wrap cnt-profile-hide meta_history_item_<?php echo $experience->meta_id ?>"
                                        id="ctn-edit-experience-<?php echo $experience->meta_id ?>">
                                        <div class="freelance-experience-new">
                                            <form class="fre-experience-form freelance-experience-form "
                                                  method="post">

                                                <div class="fre-input-field">
                                                    <input type="text" name="work_experience[title]"
                                                           placeholder="<?php _e( 'Title', ET_DOMAIN ) ?>"
                                                           value="<?php echo $e_value['title'] ?>">
                                                </div>

                                                <div class="fre-input-field">
                                                    <input type="text" name="work_experience[subtitle]"
                                                           value="<?php echo $e_value['subtitle'] ?>"
                                                           placeholder="<?php _e( 'Company', ET_DOMAIN ) ?>">
                                                </div>

                                                <div class="fre-input-field no-margin-bottom">
                                                    <label class="fre-field-title"
                                                           for=""><?php _e( 'From period', ET_DOMAIN ) ?></label>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="fre-input-field">
                                                                <select class="fre-chosen-single"
                                                                        name="work_experience[m_from]"
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
                                                                        name="work_experience[y_from]"
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

                                                <div class="fre-input-field no-margin-bottom work_experience_to">
                                                    <label class="fre-field-title"
                                                           for=""><?php _e( 'To period', ET_DOMAIN ) ?></label>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="fre-input-field novalidate_if_current">
                                                                <select class="fre-chosen-single"
                                                                        name="work_experience[m_to]"
                                                                        id="">
                                                                    <option value=""><?php _e('Month',ET_DOMAIN) ?></option>
																	<?php
																	for ( $i = 1; $i <= 12; $i ++ ) {
																		if ( intval( $i ) < 10 ) {
																			$i = '0' . $i;
																		}
																		$mont   = date( 'Y', time() ) . '-' . $i . '-' . date( 'd', time() );
																		$name_m = date( 'F', strtotime( $mont ) ); ?>
                                                                        <option value="<?php echo $i ?>" <?php echo ! empty( $e_value['m_to'] ) && $e_value['m_to'] == $i ? 'selected' : '' ?>><?php _e( $name_m, ET_DOMAIN ) ?></option>;
																	<?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="fre-input-field novalidate_if_current">
                                                                <select class="fre-chosen-single"
                                                                        name="work_experience[y_to]"
                                                                        id="">
                                                                    <option value=""><?php _e('Year',ET_DOMAIN) ?></option>
																	<?php
																	for ( $i = date( 'Y', time() ); $i >= intval( date( 'Y', time() ) ) - 50; $i -- ) {
																		! empty( $e_value['y_to'] ) && $e_value['y_to'] == $i ? $selected = 'selected' : $selected = '';
																		echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
																	} ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="fre-input-field">
                                                    <label class="fre-checkbox no-margin-bottom"
                                                           for="currently-working-<?php echo $experience->meta_id ?>">
                                                        <input id="currently-working-<?php echo $experience->meta_id ?>" class="currently-working"
                                                               type="checkbox"
                                                               name="work_experience[currently_working]" <?php echo ! empty( $e_value['currently_working'] ) ? 'checked' : '' ?>
                                                               value="1">
                                                        <span></span>
														<?php _e( 'Currently working here', ET_DOMAIN ) ?>
                                                    </label>
                                                </div>

                                                <div class="fre-input-field no-margin-bottom">
                                                    <textarea name="work_experience[content]" id="" cols="30"  placeholder="<?php _e('Description (optional)',ET_DOMAIN) ?>"
                                                      rows="10"><?php echo ! empty( $e_value['content'] ) ? $e_value['content'] : '' ?></textarea>
                                                </div>

                                                <input type="hidden" value="<?php echo $experience->meta_id ?>"
                                                       name="work_experience[id]">

                                                <div class="fre-form-btn">
                                                    <input type="submit" class="fre-normal-btn btn-submit" name=""
                                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                                    <span class="fre-experience-close profile-show-edit-tab-btn"
                                                          data-ctn_edit="cnt-experience-default-<?php echo $experience->meta_id ?>"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                    <!-- Box edit experience-->
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