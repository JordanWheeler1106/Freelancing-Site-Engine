<div class="modal fade" id="modal_delete_file">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title"><?php _e( "Delete the item", ET_DOMAIN ) ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="form-delete-file" class="form-delete-file fre-modal-form">
                    <div class="fre-content-confirm">
                        <h2><?php _e( 'Are your sure you want to delete this item?', ET_DOMAIN ) ?></h2>
                        <p><?php _e( "Once the item is deleted, it will be permanently removed from the site and its information won't be recovered.", ET_DOMAIN ) ?></p>
                    </div>
                    <input type="hidden" id="post-id" value="">
                    <input type="hidden" id="file-name" value="">
                    <input type="hidden" id="project-id" value="">
                    <div class="fre-form-btn">
                        <input class="fre-normal-btn btn-submit" type="submit"
                               value="<?php _e( 'Confirm', ET_DOMAIN ); ?>">
                        <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->