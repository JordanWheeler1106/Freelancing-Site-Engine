<?php
global $post, $user_ID;
$bid                 = get_post_meta( $post->ID, 'accepted', true );
$bid_author          = get_post_field( 'post_author', $bid );
$bid_budget          = get_post_meta( $bid, 'bid_budget', true );
$bid_author_name     = get_the_author_meta( 'display_name', $bid_author );
$payer_of_commission = ae_get_option( 'payer_of_commission', 'project_owner' );
$use_escrow          = ae_get_option( 'use_escrow' );
if ( $payer_of_commission == 'project_owner' && $use_escrow && $post->post_author == $user_ID ) {
	$commission_fee = get_post_meta( $bid, 'commission_fee', true );
	if ( ! $commission_fee ) {
		// get commission settings
		$commission     = ae_get_option( 'commission', 0 );
		$commission_fee = $commission;
		// caculate commission fee by percent
		$commission_type = ae_get_option( 'commission_type' );
		if ( $commission_type != 'currency' ) {
			$commission_fee = ( (float) ( $bid_budget * (float) $commission ) ) / 100;
		}
	}
	$bid_budget = (float) $bid_budget + (float) $commission_fee;
}
?>
<!-- MODAL FINISH PROJECT-->
<div class="modal fade" id="modal_review" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title">
					<?php _e( "Project Completion", ET_DOMAIN ); ?>
                </h4>
            </div>
            <div class="modal-body">
                <form role="form" id="review_form" class="review-form fre-modal-form">
					<?php if ( $post->post_author == $user_ID ) {  // employer finish project form ?>
                        <input type="hidden" name="action" value="ae-employer-review"/>
                        <p class="notify-form">
							<?php _e( "Great! Your project is going to be finished, it's time to review and rate for your freelancer. Your review and rating will affect the freelancer's reputation.", ET_DOMAIN ); ?>
                        </p>
					<?php } else { // freelancer finish project form ?>
                        <input type="hidden" name="action" value="ae-freelancer-review"/>
                        <p class="notify-form">
							<?php _e( 'Congratulation! The employer has been marked your working project as finished. Please check your personal account to make sure money is successfully transferred.', ET_DOMAIN ); ?>
                        </p>
					<?php } ?>
                    <input type="hidden" id="project-id" value="">
                    <div class="fre-input-field">
                        <label class="fre-field-title"
                               for="comment-content"><?php _e( 'Your Rating', ET_DOMAIN ); ?></label>
                        <div class="rating-it" style="cursor: pointer;"><input type="hidden" name="score"></div>
                    </div>

                    <div class="fre-input-field">
                        <label class="fre-field-title"
                               for="comment-content"><?php _e( 'Your Review', ET_DOMAIN ); ?></label>
                        <textarea id="comment-content" name="comment_content" placeholder=""></textarea>
                    </div>

                    <div class="fre-form-btn">
                        <button type="submit" class="fre-normal-btn btn-submit">
							<?php _e( 'Finish Project', ET_DOMAIN ) ?>
                        </button>
                        <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ); ?></span>
                    </div>

                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
<!-- MODAL FINISH PROJECT-->