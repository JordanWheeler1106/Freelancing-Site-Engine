<?php
/**
 * Template part for user bid history block
 * # This template is loaded in page-profile.php , author.php
 * @since v1.0
 * @package EngineTheme
 */
?>
<?php
global $user_bids,$wp_query;
$author_id = get_query_var( 'author' );
$is_author = is_author();
add_filter( 'posts_orderby', 'fre_reset_order_by_project_status' );
add_filter( 'posts_where', 'fre_filter_where_bid' );

$query_args = array( 'post_status'         => array('complete'),
                     'post_type'           => BID,
                     'author'              => $author_id,
                     'accepted'            => 1,
                     'filter_work_history' => '',
                     'is_author'           => $is_author
);
query_posts($query_args);
$bid_posts = $wp_query->found_posts;

global $wp_query, $ae_post_factory;
$author_id = get_query_var( 'author' );

$post_object = $ae_post_factory->get( BID );
?>
<div class="fre-profile-box">
    <div class="freelancer-project-history">
        <div class="profile-freelance-work">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h2 class="freelance-work-title"><?php printf( __( "Work History (%d)", ET_DOMAIN ), $wp_query->found_posts ) ?></h2>
                </div>
            </div>
            <ul class="list-work-history-profile author-project-list">
				<?php
				$postdata = array();
				if ( have_posts() ):
					while ( have_posts() ) {
						the_post();
						$convert    = $post_object->convert( $post, 'thumbnail' );
						$postdata[] = $convert;
						get_template_part( 'template/author-freelancer-history', 'item' );
					}
				else:
					_e( '<li class="bid-item"><span class="profile-no-results" style="padding: 0">There are no activities yet.</span></li>', ET_DOMAIN );
				endif;
				?>
            </ul>

			<?php if ( ! empty( $postdata ) && $wp_query->max_num_pages > 1 ) { ?>
				<?php
				/* render post data for js */
				echo '<script type="data/json" class="postdata" >'.json_encode($postdata).'</script>';

				echo '<div class="freelance-work-loadmore paginations">';
				ae_pagination($wp_query, get_query_var('paged'), 'load_more', 'View more');
				echo '</div>';
				?>
			<?php } ?>
        </div>
    </div>
</div>
<?php remove_filter('posts_where', 'fre_filter_where_bid');
remove_filter('posts_orderby', 'fre_reset_order_by_project_status');
?>