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

$certifications = array();
$profile_id = get_user_meta( $author_id, 'user_profile_id', true );
if($profile_id){
// not use get_post_meta because this not get meta_id
//$certifications = get_post_meta( $profile_id, 'certification' );
	$query          = 'SELECT * FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . $profile_id . ' AND meta_key = "certification" ORDER BY meta_id DESC';
	$certifications = $wpdb->get_results( $query );
}

if ( $is_edit or ! empty( $certifications ) ) {
	?>

    <div class="fre-profile-box">
        <div class="profile-freelance-certificate">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h2 class="freelance-certificate-title"><?php _e( 'Certification', ET_DOMAIN ) ?></h2>
                </div>
                <span id="fre-empty-certification">
                    <?php if ( $is_edit ) { ?>
                        <div class="col-sm-6 col-xs-12">
                        <div class="freelance-certificate-add">
                            <a href="javascript:void(0)"
                               class="fre-normal-btn-o profile-show-edit-tab-btn"
                               data-ctn_edit="ctn-edit-certification" data-ctn_hide="fre-empty-certification">
								<?php _e( 'Add new', ET_DOMAIN ) ?>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                    <p class="col-xs-12 fre-empty-optional-profile" <?php echo (empty($certifications) and $is_edit) ? '' : 'style="display : none"' ?>>
                        <?php _e('Add work certification to your profile. (optional)',ET_DOMAIN) ?>
                    </p>
                </span>
            </div>

            <ul class="freelance-certificate-list">
				<?php if ( $is_edit ) { ?>
                    <!-- Box add new certification-->
                    <li class="freelance-certificate-new-wrap cnt-profile-hide"
                        id="ctn-edit-certification">
                        <div class="freelance-certificate-new">
                            <form class="fre-certificate-form freelance-certification-form" action=""
                                  method="post">

                                <div class="fre-input-field">
                                    <input type="text" name="certification[title]"
                                           placeholder="<?php _e( 'The course entitled', ET_DOMAIN ) ?>">
                                </div>

                                <div class="fre-input-field">
                                    <input type="text" name="certification[subtitle]"
                                           placeholder="<?php _e( 'Granted by', ET_DOMAIN ) ?>">
                                </div>

                                <div class="fre-input-field no-margin-bottom">
                                    <label class="fre-field-title"
                                           for=""><?php _e( 'From period', ET_DOMAIN ) ?></label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="fre-input-field">
                                                <select class="fre-chosen-single" name="certification[m_from]" id="">
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
                                                <select class="fre-chosen-single" name="certification[y_from]" id="">
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
                                    <textarea name="certification[content]" cols="30" rows="10"  placeholder="<?php _e('Description (optional)',ET_DOMAIN) ?>"></textarea>
                                </div>

                                <div class="fre-form-btn">
                                    <input type="submit" class="fre-normal-btn btn-submit" name=""
                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                    <span class="fre-certificate-close profile-show-edit-tab-btn"
                                          data-ctn_edit="fre-empty-certification"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                </div>
                            </form>
                        </div>
                    </li>
                    <!-- End Box add new certification-->
				<?php } ?>

				<?php if ( ! empty( $certifications ) ) {
					foreach ( $certifications as $k => $certification ) {
						if ( ! empty( $certification->meta_value ) && is_serialized( $certification->meta_value ) ) {
							$e_value = unserialize( $certification->meta_value );
							if ( is_serialized( $e_value ) ) {
								$e_value = unserialize( $e_value );
							}
							if ( ! empty( $e_value ) ) {
								?>

                                <!-- Box show certification-->
                                <li class="cnt-profile-hide meta_history_item_<?php echo $certification->meta_id ?>"
                                    id="cnt-certification-default-<?php echo $certification->meta_id ?>"
                                    style="<?php echo $k + 1 == count( $certifications ) ? 'border-bottom: 0;padding-bottom: 0;' : '' ?>">
                                    <div class="freelance-certificate-wrap">
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
                                        <div class="freelance-certificate-action">
                                            <a href="javascript:void(0)" class="profile-show-edit-tab-btn"
                                               data-ctn_edit="ctn-edit-certification-<?php echo $certification->meta_id ?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												<?php _e( 'Edit', ET_DOMAIN ) ?>
                                            </a>
                                            <a href="javascript:void(0)" class="remove_history_fre" data-id="<?php echo $certification->meta_id ?>">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i><?php _e('Remove',ET_DOMAIN) ?></a>
                                        </div>
									<?php } ?>
                                </li>
                                <!-- End Box show certification-->

								<?php if ( $is_edit ) { ?>
                                    <!-- Box edit certification-->
                                    <li class="freelance-certificate-new-wrap cnt-profile-hide meta_history_item_<?php echo $certification->meta_id ?>"
                                        id="ctn-edit-certification-<?php echo $certification->meta_id ?>">
                                        <div class="freelance-certificate-new">
                                            <form class="fre-certificate-form freelance-certification-form" action=""
                                                  method="post">

                                                <div class="fre-input-field">
                                                    <input type="text" name="certification[title]"
                                                           placeholder="<?php _e( 'The course entitled', ET_DOMAIN ) ?>"
                                                           value="<?php echo $e_value['title'] ?>">
                                                </div>

                                                <div class="fre-input-field">
                                                    <input type="text" name="certification[subtitle]"
                                                           value="<?php echo $e_value['subtitle'] ?>"
                                                           placeholder="<?php _e( 'Granted by', ET_DOMAIN ) ?>">
                                                </div>

                                                <div class="fre-input-field no-margin-bottom">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="fre-input-field">
                                                                <select class="fre-chosen-single"
                                                                        name="certification[m_from]"
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
                                                                        name="certification[y_from]"
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
                                            <textarea name="certification[content]" id="" cols="30"  placeholder="<?php _e('Description (optional)',ET_DOMAIN) ?>"
                                                      rows="10"><?php echo ! empty( $e_value['content'] ) ? $e_value['content'] : '' ?></textarea>
                                                </div>

                                                <input type="hidden" value="<?php echo $certification->meta_id ?>"
                                                       name="certification[id]">

                                                <div class="fre-form-btn">
                                                    <input type="submit" class="fre-normal-btn btn-submit" name=""
                                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                                    <span class="fre-certificate-close profile-show-edit-tab-btn"
                                                          data-ctn_edit="cnt-certification-default-<?php echo $certification->meta_id ?>"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                    <!-- Box edit certification-->
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