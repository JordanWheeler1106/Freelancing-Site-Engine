<?php
/**
 * Template list all freelancer current bid
 * # This template is load page-profile.php
 * @since 1.0
 */
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( BID );
?>
    <ul class="bid-list-container">
		<?php
		$postdata = array();
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				global $post;
				$convert    = $post_object->convert( $post );
				$postdata[] = $convert;
				get_template_part( 'template/user', 'bid-item' );
			}
		} else {
			?>
            <li>
                <div class="no-results">
					<?php printf( __( "<p>Oops! You haven't had any project bids yet. Let's find the appropriate project and bid on them right now.</p>", ET_DOMAIN ) ); ?>
                    <div class="add-project"><a class="fre-normal-btn"
                                                href="<?php echo get_post_type_archive_link( PROJECT ); ?>"><?php _e( 'Find a project', ET_DOMAIN ) ?></a>
                    </div>
                </div>
            </li>
			<?php

		}
		?>

    </ul>
<?php
echo '<div class="paginations-wrapper">';
ae_pagination( $wp_query, get_query_var( 'paged' ) );
echo '</div>';
/**
 * render post data for js
 */
echo '<script type="data/json" class="postdata" >' . json_encode( $postdata ) . '</script>';
?>