<?php
/**
 * the template for displaying the freelancer work (bid success a project)
 # this template is loaded in template/bid-history-list.php
 * @since 1.0
 * @package FreelanceEngine
 */
$author_id = get_query_var('author');
global $wp_query, $ae_post_factory, $post;

$post_object = $ae_post_factory->get(BID);

$current     = $post_object->current_post;

if(!$current || !isset( $current->project_title )){
    return;
}
?>
<li>
    <div class="fre-author-project">
        <h2 class="author-project-title"><a href="<?php echo $current->project_link; ?>" title="<?php echo esc_attr($current->project_title) ?>"><?php echo $current->project_title ?></a></h2>
        <div class="author-project-info">
            <span class="rate-it" data-score="<?php echo $current->rating_score; ?>"></span>
            <span class="budget"><?php echo $current->bid_budget_text  ?></span>
            <span class="posted"><?php  _e($current->project_post_date,ET_DOMAIN)?></span>
        </div>
        <div class="author-project-comment">
	        <?php if(isset($current->project_comment) && !empty($current->project_comment)){ ?>
                <?php echo $current->project_comment;?>
	        <?php } ?>
        </div>
    </div>
</li>
