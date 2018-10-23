<?php
/**
 * The template for displaying user porfolio in profile details, edit profiles
 * @since 1.0
 * @package FreelanceEngine
 * @category Template
 */
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( PORTFOLIO );
$current     = $post_object->current_post;
$author_id   = get_query_var( 'author' );
if ( ! $current ) {
	return;
}
$profile_id = get_user_meta( $author_id, 'user_profile_id', true );

$is_edit = get_query_var('is_edit');
?>

<li class="col-md-4 col-sm-6 col-sx-12">
    <div class="freelance-portfolio-wrap" id="portfolio_item_<?php echo $current->ID ?>">
        <div class="freelance-portfolio">
            <a href="javascript:void(0)" class="fre-view-portfolio-new"
               data-id="<?php echo $current->ID ?>">
                <img src="<?php echo $current->the_post_thumbnail_full; ?>"
                     alt="<?php echo $current->post_title ?>">
            </a>
        </div>
        <div class="portfolio-title">
            <a class="fre-view-portfolio-new" href="javascript:void(0)"
               data-id="<?php echo $current->ID ?>"> <?php echo $current->post_title ?> </a>
        </div>
		<?php if ( $is_edit ) { ?>
            <div class="portfolio-action">
                <a href="javascript:void(0)" class="edit-portfolio" data-id="<?php echo $current->ID ?>"><i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i><?php _e( 'Edit', ET_DOMAIN ) ?></a>

                <a href="javascript:void(0)" class="remove_portfolio" data-portfolio_id="<?php echo $current->ID ?>"><i
                            class="fa fa-trash-o" aria-hidden="true"></i><?php _e( 'Remove', ET_DOMAIN ) ?></a>
            </div>
		<?php } ?>
    </div>
</li>

