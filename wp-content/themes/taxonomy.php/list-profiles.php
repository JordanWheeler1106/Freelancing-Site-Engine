<?php
/**
 * Template list profiles
 */
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( PROFILE );
?>
<ul class="fre-profile-list profile-list-container">
	<?php
	$postdata = array();
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			$convert    = $post_object->convert( $post );
			$postdata[] = $convert;
			get_template_part( 'template/profile', 'item' );
		}
	}
	?>
</ul>
<div class="profile-no-result" style="display: none;">
    <div class="profile-content-none">
        <p><?php _e( 'There are no results that match your search!', ET_DOMAIN ); ?></p>
        <ul>
            <li><?php _e( 'Try more general terms', ET_DOMAIN ) ?></li>
            <li><?php _e( 'Try another search method', ET_DOMAIN ) ?></li>
            <li><?php _e( 'Try to search by keyword', ET_DOMAIN ) ?></li>
        </ul>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php
/**
 * render post data for js
 */
echo '<script type="data/json" class="postdata" >' . json_encode( $postdata ) . '</script>';
?>

