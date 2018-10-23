<?php
/**
 * the template for displaying the freelancer work (bid success a project)
 # this template is loaded in template/bid-history-list.php
 * @since 1.0
 * @package FreelanceEngine
 */
$author_id = get_query_var('author');
if(is_page_template('page-profile.php')) {
    global $user_ID;
    $author_id = $user_ID;
}

global $wp_query, $ae_post_factory, $post;

$post_object = $ae_post_factory->get(BID);

$current     = $post_object->current_post;

if(!$current || !isset( $current->project_title )){
    return;
}
?>

<li class="bid-item">
        <?php 
            switch ($current->project_status) {
                // COMPLETE
                case 'complete':
        ?>
                    <div class="name-history">
                        <a href="<?php echo get_author_posts_url( $current->post_author ); ?>">
                            <span class="avatar-bid-item"><?php echo $current->project_author_avatar;?></span>
                        </a>
                        <div class="content-bid-item-history">
                            <h5><a href = "<?php echo $current->project_link; ?>"><?php echo $current->project_title; ?></a>
                            </h5>
                        </div>
                        <div class="content-complete">
                            <div class="rate-it" data-score="<?php echo $current->rating_score; ?>"></div>
                            <?php if(isset($current->project_comment)){ ?>
                                <div class="comment-author-history full-text">
                                    <p><?php echo $current->project_comment;?></p>
                                </div>
                            <?php } else { ?>
                            <span class="stt-in-process"><?php _e('Job is closed', ET_DOMAIN);?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <ul class="info-history action-project">
                        <li><p class="number-blue"><?php _e('Completed', ET_DOMAIN);?></p></li>
                        <li class="date"><?php _e('Start date:', ET_DOMAIN); ?> <?php echo $current->project_post_date;?></li>
                    </ul>
        <?php   break;
                // DISPUTING
                case 'disputing':
        ?>
                    <div class="name-history">
                        <a href="<?php echo get_author_posts_url( $current->post_author ); ?>">
                            <span class="avatar-bid-item"><?php echo $current->project_author_avatar;?></span>
                        </a>
                        <div class="content-bid-item-history">
                            <h5>
                                <a href = "<?php echo $current->project_link; ?>"><?php echo $current->project_title; ?></a>
                            </h5>
                        </div>
                        <div class="content-complete">
                            <span class="stt-in-process"><?php _e('In disputing process', ET_DOMAIN);?></span>
                        </div>
                    </div>
                    <ul class="info-history action-project">
                        <li><p class="number-blue"><?php _e('Disputed', ET_DOMAIN);?></p></li>
                        <li class="date"><?php _e('Start date:', ET_DOMAIN);?> <?php echo $current->project_post_date;?></li>
                        <?php if(!$wp_query->query['is_author']) { ?>
                            <li><a href="<?php echo $current->project_link; ?>" class="btn-apply-project-item"><?php _e('Dispute Page', ET_DOMAIN);?></a></li>
                        <?php } ?>
                    </ul>
        <?php   break;
                // RESOLVED
                case 'disputed':
        ?>
                    <div class="name-history">
                        <a href="<?php echo get_author_posts_url( $current->post_author ); ?>">
                            <span class="avatar-bid-item"><?php echo $current->project_author_avatar;?></span>
                        </a>
                        <div class="content-bid-item-history">
                            <h5>
                                <a href = "<?php echo $current->project_link; ?>"><?php echo $current->project_title; ?></a>
                            </h5>
                        </div>
                        <div class="content-complete">
                            <span class="stt-in-process"><?php _e('Resolved by Admin', ET_DOMAIN);?></span>
                        </div>
                    </div>
                    <ul class="info-history action-project">
                        <li><p class="number-blue"><?php _e('Resolved', ET_DOMAIN);?></p></li>
                        <li class="date"><?php _e('Start date:', ET_DOMAIN);?> <?php echo $current->project_post_date;?></li>
                    </ul>
        <?php   break;
            }
        ?>
    <div class="clearfix"></div>
</li>
