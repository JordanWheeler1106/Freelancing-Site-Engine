<div class="modal fade" id="modal_transfer_money">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title"><?php _e( "Transfer Money", ET_DOMAIN ) ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="transfer-money-form" class="fre-modal-form">
                    <div class="fre-transfer-money-info">
                        
                    </div>
                    <div class="fre-form-btn">
                        <button type="submit" class="fre-normal-btn btn-submit"><?php _e( 'Transfer', ET_DOMAIN ) ?></button>
                        <span class="fre-form-close" data-dismiss="modal">Cancel</span>
                    </div>
                    
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog login -->
</div><!-- /.modal -->
<script type="text/template" id="transfer_money_info_template">
    <p>{{= message}}</p>
    <div class="transfer-budget">
        <span><?php _e( 'Bid budget', ET_DOMAIN ); ?></span>
        <span>{{= bid_budget}}</span>
    </div>
    <div class="transfer-commision">
        <span><?php _e( 'Commision fee', ET_DOMAIN ); ?></span>
        <span>{{= commission_fee}}</span>
    </div>
    <div class="transfer-amount">
        <span><?php _e( 'Transfered amount', ET_DOMAIN ); ?></span>
        <span class="text-green-dark">{{= amount}}</span>
    </div>
</script>