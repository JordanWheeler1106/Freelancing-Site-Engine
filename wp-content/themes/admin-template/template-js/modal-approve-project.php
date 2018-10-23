<div class="modal fade" id="modal_approve_project">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title"><?php _e( "Project Approval", ET_DOMAIN ) ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="form-approve-project" class="form-approve-project fre-modal-form">
                    <div class="fre-content-confirm">
                        <h2><?php _e( 'Are you sure you want to approve this project?', ET_DOMAIN ); ?></h2>
                        <p><?php _e( 'Once you approve this project, the project will be published on your site and available for freelancers to bid.', ET_DOMAIN ); ?></p>
                    </div>
                    <input type="hidden" id="project-id" value="">
                    <div class="fre-form-btn">
                        <button type="submit"
                                class="fre-normal-btn btn-submit btn-approve-project"><?php _e( 'Confirm', ET_DOMAIN ) ?></button>
                        <span class="fre-form-close" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ); ?></span>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->