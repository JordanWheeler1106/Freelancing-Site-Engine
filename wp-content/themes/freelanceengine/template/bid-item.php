<?php
/**
 * The template for displaying a bid info item,
 * this template is used to display bid info in a project details,
 * and called at template/list-bids.php
 * @since 1.0
 * @author Dakachi
 */
    global $wp_query, $ae_post_factory, $post,$user_ID;

    $project_object = $ae_post_factory->get(PROJECT);;
    $project = $project_object->current_post;

    $post_object    = $ae_post_factory->get( BID );
    $convert        = $post_object->convert($post);

    $bid_accept     = get_post_meta($project->ID, 'accepted', true);
    $project_status = $project->post_status;

    $role           = ae_user_role();

?>

<div class="row list-bidding">
    <div class="info-bidding fade-out fade-in bid-item bid-<?php the_ID();?> bid-item-<?php echo $project_status;?>">
        <div class="col-md-4 col-xs-4">

        	<div class="avatar-freelancer-bidding"><a href="<?php echo get_author_posts_url( $convert->post_author ); ?>"><span class="avatar-profile"> <?php echo $convert->et_avatar; ?></span></a></div>
            <div class="info-profile-freelancer-bidding">
                <span class="name-profile"><?php echo $convert->profile_display ;?></span><br />
                <span class="position-profile"><?php echo $convert->et_professional_title ?></span>
            </div>
        </div>
        <div class="col-md-4 col-xs-4">
        	<div class="rate-exp-wrapper">
                <div class="rate-it" data-score="<?php echo $convert->rating_score ; ?>"></div>
                <div class="experience"><?php if(!empty($convert->experience)) echo '+ '.$convert->experience;  ?></div>
            </div>
        </div>
        <div class="col-md-4 col-xs-4 block-bid">
        <?php
            $time = $convert->bid_time;
            $type = $convert->type_time;
        ?>

    	<span class="number-price-project">
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
                <span class="number-day">
                    <?php echo $convert->bid_time_text; ?>
                </span>
            <?php } else { ?>
                <span class="number-price"><?php _e("In Process", ET_DOMAIN); ?></span>
            <?php }
            // end biding budget details

            /**
             * project accept button
             # only project owner can see & use this button
             */
            if( $user_ID == (int) $project->post_author && $project_status == 'publish' ){ ?>
                <button href="#" id="<?php the_ID();?>" rel="<?php echo $project->ID;?>" class="btn-sumary btn-accept-bid btn-bid-status"
                        title="" data-original-title="<?php _e('Accept Bid', ET_DOMAIN); ?>">
                    <?php _e('Accept',ET_DOMAIN) ; ?>
                </button>
                <span class="confirm"></span>
                <?php

            } else if( $bid_accept && $project->accepted == $convert->ID && in_array($project_status, array('complete','close', 'disputing') ) ) { ?>
                <span class="ribbon"><i class="fa fa-trophy"></i></span>
                <?php
            }
            ?>

        </span>
            <?php
            do_action('ae_bid_item_template', $convert, $project );
            ?>
        </div>

        <?php if($convert->post_content){ ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <blockquote class="comment-author-history">
                    <?php echo $convert->post_content; ?>
                </blockquote>
            </div>
        <?php } ?>

        <div class="clearfix"></div>
    </div>

</div>