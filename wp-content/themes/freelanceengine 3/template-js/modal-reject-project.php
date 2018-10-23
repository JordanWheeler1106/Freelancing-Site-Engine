<div class="modal fade" id="modal_reject_project">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title"><?php _e( "Post Rejection", ET_DOMAIN ) ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="reject-project-form" class="reject-project-form fre-modal-form">
                    <p><?php echo _e('Please give the employer a message to explain why you reject his/her project.', ET_DOMAIN); ?></p>
                    <div class="fre-input-field no-margin-bottom">
                        <label class="fre-field-title"><?php _e( "Message", ET_DOMAIN ) ?></label>
                        <textarea name="reject_message" id="reject-message"></textarea>
                    </div>
                    <input type="hidden" id="project-id" value="">
                    <div class="fre-form-btn">
                        <button type="submit"
                                class="fre-normal-btn btn-submit btn-reject-project"><?php _e( 'Reject', ET_DOMAIN ) ?></button>
                        <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ); ?></span>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->