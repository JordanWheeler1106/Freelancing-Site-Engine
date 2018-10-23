<?php
/**
 * The template for displaying user bid item in page-profile.php
 */
$currency =  ae_get_option('currency',array('align' => 'left', 'code' => 'USD', 'icon' => '$'));
global $wp_query, $ae_post_factory, $post;
//get bid data
$bid_object     = $ae_post_factory->get( BID );
$bid            = $bid_object->current_post;
//get project data
$project        = get_post( $bid->post_parent );

if(!$project || is_wp_error($project) ) {
    return false;
}

$project_object = $ae_post_factory->get( PROJECT );
$project        = $project_object->convert($project);
//get all fields
$total_bids     = $project->total_bids ? $project->total_bids : 0;
$bid_average    = $project->bid_average ? $project->bid_average: 0;
$bid_budget     = $bid->bid_budget ? $bid->bid_budget : 0;
$bid_time       = $bid->bid_time ? $bid->bid_time : 0;
$type_time      = $bid->type_time ? $bid->type_time : 0;
$status_text    = $bid->status_text;

?>
<li <?php post_class( 'user-bid-item' ); ?>>

	<div class="row user-bid-item-list ">
        <div class="col-md-8 col-sm-8">
            <a href="<?php echo get_author_posts_url( $project->post_author ); ?>" class="avatar-author-project-item">
                <?php echo get_avatar( $project->post_author, 35,true, $bid->project_title ); ?>
            </a>
            <a class="project-title" href="<?php echo get_permalink($project->ID);?>">
                <span class="content-title-project-item">
                    <?php echo $bid->project_title;?>
                </span>
            </a>
            <div class="user-bid-item-info">
                <ul class="info-item">
                    <li>
                        <span>
                            <?php _e("Bidding:", ET_DOMAIN) ?>
                        </span>
                        <span class="number-blue">
                            <?php echo $bid->bid_budget_text; ?>
                        </span>
                        <?php echo $bid->bid_time_text; ?>
                    </li>
                    <li>
                        <?php printf(__('<span>Number Bids of Project:</span><span class="number-blue"> %d</span>', ET_DOMAIN), $total_bids);?>
                    </li>
                    <li>
                        <?php printf( __('Average Bid:', ET_DOMAIN)) ?>
                        <span class="number-blue">
                            <?php
                                $avg = 0;
                                if ($project->total_bids > 0) $avg = get_total_cost_bids($project->ID) / $project->total_bids;
                                echo fre_price_format($avg);
                            ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 action-project">
            
            <?php
            if($bid->post_status == 'unaccept' && ae_user_role() == 'freelancer' ){?>
                <p class="number-blue"><?php _e('Processing', ET_DOMAIN); ?></p>
                <p class="status-bid-project"><?php _e('Your bid is not accepted.', ET_DOMAIN); ?></p>
            <?php }elseif($bid->post_status == 'accept' && ae_user_role() == 'freelancer' ){?>
                <p class="number-blue"><?php _e('Processing', ET_DOMAIN); ?></p>
                <p class="status-bid-project"><?php _e('Your bid is accepted.', ET_DOMAIN); ?></p>
                <div class="status-project">
                    <a href="<?php echo add_query_arg(array('workspace' => 1), $project->permalink); ?>" class="btn-apply-project-item">
                        <?php _e("Workspace", ET_DOMAIN) ?>
                    </a>
                </div>
            <?php }elseif($bid->post_status == 'publish' && ae_user_role() == 'freelancer' ){?>
                <p class="number-blue"><?php _e('Active', ET_DOMAIN); ?></p>
                <p class="status-bid-project"><?php echo $bid->et_expired_date; ?></p>
                <div class="status-project">
                    <a href="<?php echo $project->permalink ?>" class="btn-apply-project-item">
                        <?php _e("Cancel", ET_DOMAIN) ?>
                    </a>
                </div>
            <?php } ?>

        </div>

    </div>
</li>