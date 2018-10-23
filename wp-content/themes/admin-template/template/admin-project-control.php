<?php
/**
 * The template for displaying admin control button to edit project
 * @since 1.0
 * @author Dakachi
 */
global $post;
// $featured = get_post_meta( $post->ID, 'et_featured', true);
if( current_user_can( 'manage_options' )) { 
    if( $post->post_status == 'complete' && ae_get_option('use_escrow') ) {
            // success update order data
            $bid_id_accepted = get_post_meta( $post->ID, 'accepted', true );
            $order = get_post_meta($bid_id_accepted, 'fre_bid_order', true);
            $order_status = get_post_field( 'post_status', $order );
            if(ae_get_option('manual_transfer', false)){
                if($order_status != 'finish') :?>
                    <span class="btn-complete-project manual-transfer"  title="<?php _e("Transfer Money To Freelancer", ET_DOMAIN); ?>">
                        <?php _e("Transfer Money", ET_DOMAIN); ?>
                    </span>
        <?php   else : ?>
                    <span class="money-transfered-btn">
                        <?php _e("Money Transfered", ET_DOMAIN); ?>
                    </span>
        <?php   endif;
            }
    }else { ?>
        <?php if($post->post_status == 'disputing'){ ?>
            <a class="btn-excecute-project btn-arbitrate-project" href="#"><?php _e("Arbitrate", ET_DOMAIN); ?></a>
        <?php }else{ ?>
            <ul class="button-event event-listing">
                <!-- button edit -->
                <?php if(in_array($post->post_status, array('pending', 'publish', 'reject', 'draft'))){ ?>
                    <li class="tooltips update edit">
                        <a data-toggle="tooltip" title="<?php _e("Edit", ET_DOMAIN); ?>" data-original-title="<?php _e("Edit", ET_DOMAIN); ?>" href="<?php echo et_get_page_link('edit-project', array('id' => $post->ID)) ?>"><i class="fa fa-pencil" data-icon="p"></i></a>
                    </li>
                <?php } ?>
                <!-- button edit -->
                <!-- button archive -->
                <?php if(in_array($post->post_status, array('publish', 'draft'))){ ?>
                    <li class="tooltips remove archive">
                        <a class="action" data-action="archive" data-toggle="tooltip" title="<?php _e("Archive", ET_DOMAIN); ?>" data-original-title="<?php _e("Archive", ET_DOMAIN); ?>" href="#"><span class="icon fa fa-trash-o" data-icon="#"></span></a>
                    </li>
                <?php } ?>
                <!-- button archive -->
                <!-- button active & reject -->
                <?php if( $post->post_status == 'pending') { ?>
                    <li class="tooltips remove approve">
                        <a class="action" data-action="approve" data-toggle="tooltip" title="<?php _e("Approve", ET_DOMAIN); ?>" data-original-title="<?php _e("Approve", ET_DOMAIN); ?>" href="#"><span class="fa fa-check" data-icon="3"></span></a>
                    </li>
                    <li class="tooltips remove reject">
                        <a class="action" data-action="reject" data-toggle="tooltip" title="<?php _e("Reject", ET_DOMAIN); ?>" data-original-title="<?php _e("Reject", ET_DOMAIN); ?>" href="#"><span class="icon color-purple fa fa-times" data-icon="*"></span></a>
                    </li>
                <?php } ?>
                <!-- button active & reject -->
                <!-- button delete -->
                <?php if( $post->post_status == 'archive') { ?>
                    <li class="tooltips remove reject">
                        <a class="action" data-action="delete" data-toggle="tooltip" title="<?php _e("Delete", ET_DOMAIN); ?>" data-original-title="<?php _e("Delete", ET_DOMAIN); ?>" href="#"><span class="icon color-purple fa fa-times" data-icon="*"></span></a>
                    </li>
                <?php } ?>
                <!-- button delete -->
            </ul>
        <?php } ?>
<?php }
}
?>