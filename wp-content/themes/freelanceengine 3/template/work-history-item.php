<?php
/**
 * Template part for employer project details
 # this template is loaded in template/list-work-history.php
 * @since 1.0
 * @package FreelanceEngine
 */
global $user_ID;
$author_id = get_query_var('author');
if(!$author_id) {
    $author_id = $user_ID;
}


global $wp_query, $ae_post_factory, $post;

$post_object = $ae_post_factory->get( PROJECT );
$current     = $post_object->current_post;

if(!$current){
    return;
}

$status = array(
    'reject'    => __("REJECTED", ET_DOMAIN) ,
    'pending'   => __("PENDING", ET_DOMAIN) ,
    'publish'   => __("ACTIVE", ET_DOMAIN),
    'close'     => __("PROCESSING", ET_DOMAIN),
    'complete'  => __("COMPLETED", ET_DOMAIN),
    'draft'     => __("DRAFT", ET_DOMAIN),
    'archive'   => __("ARCHIVED", ET_DOMAIN),
    'disputing' => __("DISPUTED" , ET_DOMAIN ),
    'disputed'  => __("RESOLVED" , ET_DOMAIN )
);

?>
<li class="bid-item">
    <div class="name-history">
        <a href="<?php echo $current->author_url;?>">
            <span class="avatar-bid-item">
                <?php echo $current->et_avatar; ?>
            </span>
        </a>
        <div class="content-bid-item-history">
            <h5>
                <a href ="<?php echo $current->permalink; ?>"><?php echo $current->post_title; ?></a>
            </h5>
        </div>
        <div class="content-complete content-complete-employer">
            <p><?php _e("Budget", ET_DOMAIN); ?> :
            <span class="number-price-project-info"><?php echo $current->budget; ?></span></p>
            <p class="date"><?php echo $current->post_date; ?></p>
            <?php if($current->post_status == 'complete' && !empty($current->project_comment)){ ?>
                <div class="review-rate" style="display:none;">
                    <div class="rate-it" data-score="<?php echo $current->rating_score ; ?>"></div>
                    <span class="comment-author-history "><?php echo $current->project_comment; ?></span>
                    <div class="review-link">
                        <a title="<?php _e('Rating & Review', ET_DOMAIN);?>" class="review" data-target="#" href="#">
                            <?php _e('Hide', ET_DOMAIN);?><i class="fa fa-sort-asc" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
    <ul class="info-history">
        <li class="post-control">
            <div class="inner">
            <?php if($author_id && $author_id == $user_ID) {?>
                    <div class="action-project">
                        <p class="number-blue"><?php echo $status[$current->post_status];?></p>
                    </div>
                    <?php if( !in_array($current->post_status, array('pending','draft', 'reject')) ){?>
                    <div class="show-action-bids">
                        <p class="number-static-bid text-blue-light text-bid-view">
                            <?php
                                if($current->total_bids > 1){
                                    printf(__('%s <span class="normal-text">Bids</span>', ET_DOMAIN), $current->total_bids);
                                }else{
                                    if($current->total_bids == 0 ){
                                        printf(__('%s <span class="normal-text">Bids</span>', ET_DOMAIN), $current->total_bids);    
                                    }else{
                                        printf(__('%s <span class="normal-text">Bid</span>', ET_DOMAIN), $current->total_bids);
                                    }
                                    
                                }
                            ?>
                        </p>
                        <p class="number-static-bid">
                            <?php
                                if($post->post_views > 1 ){
                                    printf(__("%d <span class='normal-text'>Views</span>", ET_DOMAIN), $post->post_views);
                                }else{
                                    if($post->post_views == 0 ){
                                        printf(__("%d <span class='normal-text'>Views</span>", ET_DOMAIN), $post->post_views);
                                    }else{
                                        printf(__("%d <span class='normal-text'>View</span>", ET_DOMAIN), $post->post_views);
                                    }
                                    
                                }
                            ?>
                        </p>
                    </div>

                <?php } ?></div>
                <div class="post-button-control">
                    <?php ae_edit_post_button($current);?>
                </div>
            <?php } ?>
        </li>
    </ul>
    <div class="clearfix"></div>
</li>