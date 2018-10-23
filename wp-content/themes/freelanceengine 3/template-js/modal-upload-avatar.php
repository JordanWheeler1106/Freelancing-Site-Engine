<?php
global $user_ID;

$avatar_id = get_user_meta($user_ID,'et_avatar',true);
$avatar_url = get_user_meta($user_ID,'et_avatar_url',true);

$avatar_data = wp_get_attachment_image_url($avatar_id,'full');
?>
<style>
	#md_user_avatar_thumbnail img{
		max-width: 100%;
		margin: 0 auto;
	}
    #container_crop_avatar > img {
        max-width: 100%;
    }
</style>
<div class="modal fade" id="uploadAvatar">
	<div class="modal-dialog">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <i class="fa fa-times"></i>
            </button>
            <h4 class="modal-title"><?php _e("Upload profile picture", ET_DOMAIN) ?></h4>
        </div>
		<div class="modal-content">
			<div class="modal-body">
				<form class="form-save-avatar" data-processing = 'no'>
					<div class="fre-input-field text-center">

                        <div class="preview-image" id="container_crop_avatar" data-is_crop="false">
							<?php if(!empty($avatar_data)){ ?>
                                <img src="<?php echo $avatar_data ?>" class="avatar photo avatar-default">
							<?php }else{ ?>
								<?php echo get_avatar( $user_ID, 150 ) ?>
							<?php } ?>
                        </div>

						<div class="" style="margin-top: 50px">
							<button type="submit" class="fre-normal-btn fre-submit-portfolio">
								<?php _e('Save profile picture', ET_DOMAIN) ?>
							</button><br><br>

                            <div  id="md_user_avatar_container" data-avatar_id="<?php echo $avatar_id ?>">
                                <a class="fre-form-close" href="#" id="md_user_avatar_browse_button" style="margin-left: 0">
		                            <?php _e( 'Change picture', ET_DOMAIN ) ?>
                                </a>
                                <span class="et_ajaxnonce hidden" id="<?php echo de_create_nonce( 'md_user_avatar_et_uploader' ); ?>"></span>
                            </div>
						</div>

					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->