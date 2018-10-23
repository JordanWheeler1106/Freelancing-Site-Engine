<div class="modal fade" id="modal_arbitrate">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<i class="fa fa-times"></i>
				</button>
				<h4 class="modal-title"><?php _e("Resolve Dispute", ET_DOMAIN) ?></h4>
			</div>
			<div class="modal-body">
				<form role="form" id="arbitrate_form" class="fre-modal-form fre-arbitrate-form">
					<p><?php _e('You are about to resolve this dispute. You can send your comment and transfer money to the winner.', ET_DOMAIN);?></p>
                    <div class="fre-input-field">
                        <p style="margin-bottom: 10px"><?php _e('Who would win the dispute?', ET_DOMAIN); ?></p>
                        <label class="radio-inline" for="arbitrate-freelancer">
                            <input id="arbitrate-freelancer" type="radio" name="transfer_select" value="freelancer" ><span></span><?php _e('Freelancer', ET_DOMAIN);?>
                        </label>
                        <div style="margin-top: 10px">
                        <label class="radio-inline" for="arbitrate-employer">
                            <input id="arbitrate-employer" type="radio" name="transfer_select" value="employer"><span></span><?php _e('Employer', ET_DOMAIN);?>
                        </label>
                        </div>
                    </div>
					
					<div class="fre-input-field no-margin-bottom">
						<label class="fre-field-title" for=""><?php _e('Your comment here', ET_DOMAIN); ?></label>
						<textarea name="comment_resolved" placeholder=""></textarea>
					</div>
                    <div class="fre-form-btn">
                    	<button type="submit" class="fre-normal-btn btn-submit">
							<?php _e('Arbitrate', ET_DOMAIN) ?>
						</button>
						<span class="fre-form-close" data-dismiss="modal">Cancel</span>
                    </div>

				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
