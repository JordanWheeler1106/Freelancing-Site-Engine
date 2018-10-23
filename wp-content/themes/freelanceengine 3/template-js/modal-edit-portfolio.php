<?php
	global $user_ID;
	$profile_id = get_user_meta($user_ID, 'user_profile_id', true);
?>
<div class="modal fade" id="modal_edit_portfolio">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title"><?php _e("Edit portfolio", ET_DOMAIN) ?></h4>
			</div>
			<div class="modal-body">
				<form role="form" class="fre-modal-form auth-form update_portfolio">
					<div class="fre-input-field">
                		<label class="fre-field-title"><?php _e('Portfolio Title', ET_DOMAIN) ?></label>
                		<input type="text" name="post_title"  />
                	</div>
					<div class="fre-input-field">
						<label class="fre-field-title"><?php _e('Portfolio Description', ET_DOMAIN) ?></label>
						<textarea name="post_content" cols="30" rows="10"></textarea>
					</div>

					<div class="fre-input-field box_upload_img">
                        <div id="edit_portfolio_img_thumbnail" style="display: none"></div>
                        <ul class="portfolio-thumbs-list row image ctn_portfolio_img">

						</ul>

                        <div id="edit_portfolio_img_container">
                            <span class="et_ajaxnonce hidden" data-id="<?php echo de_create_nonce( 'edit_portfolio_img_et_uploader' ); ?>"></span>
                            <!--<label class="fre-upload-file" for="edit_portfolio_img_browse_button">
                			<input type="file" name="post_thumbnail" id="edit_portfolio_img_browse_button" value="<?php /*_e('Browse', ET_DOMAIN); */?>" />
                			<?php /*_e('Upload Files', ET_DOMAIN) */?>
                		</label>-->
                            <a class="fre-upload-file" href="#" id="edit_portfolio_img_browse_button" style="display: block;">
		                        <?php _e( 'Upload Files', ET_DOMAIN ) ?>
                            </a>
                            <div class="list_id_image"></div>
                        </div>
                		<p class="fre-allow-upload"><?php _e('(Maximum upload file size is limited to 10MB, allowed file types in the png, jpg, and gif.)', ET_DOMAIN) ?></p>
					</div>

                	<div class="fre-input-field no-margin-bottom">
                		<label class="fre-field-title"><?php _e('Skills (optional)', ET_DOMAIN); ?></label>
                        <div class="list_skill"></div>
                	</div>
                	<div class="fre-form-btn">
                		<button type="submit" class="fre-normal-btn fre-submit-portfolio">
							<?php _e('Save', ET_DOMAIN) ?>
						</button>
						<span class="fre-form-close" data-dismiss="modal"><?php _e('Cancel', ET_DOMAIN) ?></span>
                	</div>

                    <input type="hidden" name="ID" value="">
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->