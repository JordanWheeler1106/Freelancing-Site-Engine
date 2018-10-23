<?php
/**
 * The template for displaying a bid info item,
 * this template is used to display bid info in a project details,
 * and called at template/list-bids.php
 * @since 1.0
 * @author Dakachi
 */
    global $wp_query, $ae_post_factory, $post, $user_ID;

    $project_object = $ae_post_factory->get(PROJECT);;
    $project = $project_object->current_post;

    $post_object    = $ae_post_factory->get( BID );
    $convert        = $post_object->convert($post);

    $bid_accept     = get_post_meta($project->ID, 'accepted', true);
    $project_status = $project->post_status;

?>

<div class="row list-bidding">
    <div class="info-bidding fade-out fade-in bid-item <?php echo $convert->add_class_bid;?> bid-<?php the_ID();?> bid-item-<?php echo $project_status;?> "/> 
        <div class="col-md-7 col-sm-7 col-xs-12">
        	<div class="avatar-freelancer-bidding"><a href="<?php echo get_author_posts_url( $convert->post_author ); ?>"><span class="avatar-profile"> <?php echo $convert->et_avatar; ?></span></a></div>
            <div class="info-profile-freelancer-bidding">
                <span class="name-profile"><?php echo $convert->profile_display ;?></span><br />
                <span class="position-profile"><?php echo $convert->et_professional_title ?></span>
                <div class="rate-exp-wrapper">
                    <div class="rate-it" data-score="<?php echo $convert->rating_score ; ?>"></div>
                    <div class="experience">
                        <?php
                            if(!empty($convert->experience)) {
                                if((float)$convert->experience > 1){
                                    printf(__('%s Years', ET_DOMAIN), $convert->experience);
                                }else{
                                    printf(__('%s Year', ET_DOMAIN), $convert->experience);
                                }
                            }else{
                                printf(__('0 Years', ET_DOMAIN));
                            }
                        ?>
                    </div>
                </div>
                <?php if($convert->post_content){ ?>
                    <div class="comment-author-history full-text">
                        <p><?php echo $convert->post_content;?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-5 col-sm-5 col-xs-12">
        <?php
            $time = $convert->bid_time;
            $type = $convert->type_time;
        ?>

    	<div class="number-price-project">
            <?php
            /**
             * user can view bid details
             # when a project is complete
             # when current user is project owner
             # when current user is bid owner
             */
           // if( in_array($project_status, array('complete','close', 'disputing') )
            if( ( $user_ID && $user_ID == $project->post_author ) || ( $user_ID && $user_ID == $convert->post_author ) ) { ?>
                <span class="number-price"><?php echo $convert->bid_budget_text; ?></span>
                <span class="number-day"><?php echo $convert->bid_time_text; ?></span>
            <?php } else { ?>
                <span class="number-price"><?php _e("In Process", ET_DOMAIN); ?></span>
            <?php } ?>

        </div>
        <div class="action-employer-bidden">
            <?php if( $convert->flag == 1 ){ ?>
                <?php if(ae_get_option('use_escrow')) { ?>
                    <button href="#" id="<?php the_ID();?>" rel="<?php echo $project->ID;?>" class="fre-small-btn btn-accept-bid"
                            title="">
                        <?php _e('Accept',ET_DOMAIN) ; ?>
                    </button>
                <?php }else{ ?>
                    <button class="fre-small-btn btn-accept-bid btn-accept-bid-no-escrow"  id="<?php the_ID();?>">
                        <?php _e('Accept',ET_DOMAIN) ;?>
                    </button>
                <?php } ?>
                <!--<span class="confirm"></span>-->
            <?php } else if( $convert->flag == 2) { ?>
                <span class="ribbon"><i class="fa fa-trophy"></i></span>
            <?php } ?>
            <?php 
                if(in_array($project_status, array('publish') )){
                    do_action('ae_bid_item_template', $convert, $project ); 
                }
            ?>
        </div>
        <?php if($convert->project_status != 'publish' && $convert->project_author == $user_ID ){ ?>
        <div class="show-info">
            <i class="fa fa-circle" aria-hidden="true"></i>
            <i class="fa fa-circle" aria-hidden="true"></i>
            <i class="fa fa-circle" aria-hidden="true"></i>
        </div>
        <?php } ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>