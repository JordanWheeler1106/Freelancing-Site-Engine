<?php
/**
 * The template for displaying profile in a loop
 * @since  1.0
 * @package FreelanceEngine
 * @category Template
 */
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( PROFILE );
$current = $post_object->current_post;
if(!$current){
    return;
}
?>
<li class="profile-item">
    <div class="profile-list-wrap">
        <a class="profile-list-avatar" href="<?php echo $current->permalink; ?>">
            <?php echo get_avatar($post->post_author); ?>
        </a>
        <h2 class="profile-list-title">
            <a href="<?php echo $current->permalink; ?>"><?php the_author_meta( 'display_name', $post->post_author ); ?></a>
        </h2>
        <p class="profile-list-subtitle"><?php echo $current->et_professional_title;?></p>
        <div class="profile-list-info">
            <div class="profile-list-detail">
                <span class="rate-it" data-score="<?php echo $current->rating_score ; ?>"></span>
                <span><?php echo $current->experience ?></span>
                <span><?php echo $current->project_worked; ?></span>
                <span><b><?php echo $current->hourly_rate_price; ?></b>/hr</span>
                <span style="font-weight: normal"><?php echo ($current->earned); ?></span>
            </div>
            <div class="profile-list-desc">
	            <?php echo $current->post_content;?>
            </div>
        </div>
    </div>
</li>
