<div class="modal fade" id="modal_unlock_file">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title"><?php _e( "Unlock Files", ET_DOMAIN ) ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="form-unlock-file" class="form-unlock-file fre-modal-form">
                    <div class="fre-content-confirm">
                        <h2><?php _e( 'Are you sure you want to unlock this section?', ET_DOMAIN ) ?></h2>
                        <p><?php _e( "Once you unlock this section, freelancer can add a new file or delete the upload files. You can also lock these files whenever you want.", ET_DOMAIN ) ?></p>
                    </div>
                    <input type="hidden" id="project-id" value="">
                    <div class="fre-form-btn">
                        <input class="fre-normal-btn btn-submit" type="submit"
                               value="<?php _e( 'Unlock', ET_DOMAIN ); ?>">
                        <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->