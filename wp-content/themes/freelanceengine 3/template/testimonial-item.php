<?php
/**
 * The template for displaying profile in a loop
 * @since  1.0
 * @package FreelanceEngine
 * @category Template
 */
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( 'testimonial' );
$current = $post_object->current_post;
if(!$current){
    return;
}
?>
<div class="col-md-4 grid-item">
    <div class="testimonial">
        <div class="test-content">
            <?php the_content( ); ?>
        </div>
        <div class="test-info">
            <span class="test-avatar">
                <?php the_post_thumbnail( 'thumbnail' ); ?>
            </span>
            <span class="test-name">
                <?php the_title() ?><span class="test-position"><?php echo get_post_meta( $post->ID, '_test_category', true ); ?></span>
            </span>
        </div>
    </div>
</div>