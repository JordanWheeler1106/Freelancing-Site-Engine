<?php
/**
 * Template part for user bid history block
 * # This template is loaded in page-profile.php , author.php
 * @since v1.0
 * @package EngineTheme
 */

?>

<div class="profile-history bid-history">
	<?php
	global $user_bids, $wp_query;
	$author_id = get_query_var( 'author' );
	$is_author = is_author();
	$isProfile = is_page_template( 'page-profile.php' );
	if ( is_page_template( 'page-profile.php' ) ) {
		global $user_ID;
		$author_id = $user_ID;
	}
	add_filter( 'posts_orderby', 'fre_reset_order_by_project_status' );
	add_filter( 'posts_where', 'fre_filter_where_bid' );
	query_posts( array( 'post_status'         => array( 'complete', 'accept' ),
	                    'post_type'           => BID,
	                    'author'              => $author_id,
	                    'accepted'            => 1,
	                    'filter_work_history' => '',
	                    'is_author'           => $is_author
	) );
	$bid_posts = $wp_query->found_posts;
	?>

	<?php
	// list Work history and review
	if ( $isProfile ) {
		if ( ! empty( $bid_posts ) ) {
			get_template_part( 'template/bid', 'history-list' );
		}
	} else {
		get_template_part( 'template/bid', 'history-list' );
	}
	remove_filter( 'posts_where', 'fre_filter_where_bid' );
	remove_filter( 'posts_orderby', 'fre_reset_order_by_project_status' );
	?>
</div>