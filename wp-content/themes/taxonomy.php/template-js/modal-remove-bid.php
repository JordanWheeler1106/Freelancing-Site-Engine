<div class="modal fade" id="modal_remove_bid">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title"><?php _e( "Project Removal", ET_DOMAIN ) ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="form-remove-bid" class="form-remove-bid fre-modal-form">
                    <div class="fre-content-confirm">
                        <h2><?php _e( 'Are you sure you want to remove this project?', ET_DOMAIN ); ?></h2>
                        <p><?php _e( 'Once you remove the project, it will no longer appear on your working page.', ET_DOMAIN ); ?></p>
                        <p>You can bid again after cancelling.</p>
                    </div>
                    <input type="hidden" id="bid-id" value="">
                    <div class="fre-form-btn">
                        <button type="submit"
                                class="fre-normal-btn btn-submit btn-remove-bid"><?php _e( 'Confirm', ET_DOMAIN ) ?></button>
                        <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ); ?></span>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->