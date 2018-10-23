<?php wp_reset_query();
global $user_ID, $post;
$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );
?>
<!-- MODAL BIG -->
<div class="modal fade" id="modal_not_bid">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
				<?php if ( ! ( ae_get_option( 'invited_to_bid' ) && ! fre_check_invited( $user_ID, $post->ID ) ) ) { ?>
                    <h4 class="modal-title"><?php _e( 'Bid project', ET_DOMAIN ); ?></h4>
				<?php } else { ?>
                    <h4 class="modal-title"><?php _e( 'Project Bidding', ET_DOMAIN ); ?></h4>
				<?php } ?>
            </div>
            <div class="modal-body">
				<?php
				if ( empty( $profile_id ) or ! is_numeric( $profile_id ) ) { ?>
                    <div class="not-bid-form fre-modal-form">
                        <h2><?php _e( 'You have to update your profile before bidding on this project!', ET_DOMAIN ); ?></h2>
                        <p><?php _e( 'You cannot bid on any project without updating your profile. Please click the Update button below to update the profile.', ET_DOMAIN ); ?></p>
                        <div class="fre-form-btn">
                            <input type="hidden" id="project-id" value="">
                            <a href="<?php echo et_get_page_link( "profile" ) ?>" class="fre-normal-btn btn-submit">
								<?php _e( 'Update', ET_DOMAIN ) ?>
                            </a>
                            <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ); ?></span>
                        </div>
                    </div>
				<?php } else {
					if ( ae_get_option( 'invited_to_bid' ) && ! fre_check_invited( $user_ID, $post->ID ) ) { ?>
                        <div class="not-bid-form fre-modal-form">
                            <h2><?php _e( 'You cannot bid on this project!', ET_DOMAIN ); ?></h2>
                            <p><?php _e( "You cannot bid on this project without being invited by the project's owner.", ET_DOMAIN ); ?></p>
                            <div class="fre-form-btn">
                                <input type="hidden" id="project-id" value="">
                                <a href="javascript:void(0)" data-dismiss="modal" class="fre-normal-btn btn-submit">
									<?php _e( 'OK', ET_DOMAIN ) ?>
                                </a>
                            </div>
                        </div>
					<?php } else { ?>
                        <form role="form" id="not_bid_form" class="not-bid-form fre-modal-form">
                            <h2><?php _e( 'You need available bid to bid this project!', ET_DOMAIN ); ?></h2>
                            <p><?php _e( 'This project requires at least one avaialble bid to take bid action. You can get available bids
                        by purchasing bids.', ET_DOMAIN ); ?></p>
                            <div class="fre-form-btn">
                                <input type="hidden" id="project-id" value="">
                                <button type="submit" class="fre-normal-btn btn-submit">
									<?php _e( 'Purchase Bid', ET_DOMAIN ) ?>
                                </button>
                                <span class="fre-form-close"
                                      data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ); ?></span>
                            </div>
                        </form>
					<?php } ?>
				<?php } ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->