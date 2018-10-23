<div class="modal fade" id="modal_lock_file">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title"><?php _e( "Lock Files", ET_DOMAIN ) ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="form-lock-file" class="form-lock-file fre-modal-form">
                    <div class="fre-content-confirm">
                        <h2><?php _e( 'Are you sure you want to lock this section?', ET_DOMAIN ) ?></h2>
                        <p><?php _e( "Once you lock the files, freelancer cannot add a new file or delete any uploaded files. However, you can unlock this section whenever you want.", ET_DOMAIN ) ?></p>
                    </div>
                    <input type="hidden" id="project-id" value="">
                    <div class="fre-form-btn">
                        <input class="fre-normal-btn btn-submit" type="submit"
                               value="<?php _e( 'Lock', ET_DOMAIN ); ?>">
                        <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->