<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object = $ae_post_factory->get( PROJECT );
$project     = $post_object->current_post;

$number_bids = (int) get_number_bids( get_the_ID() );
add_filter( 'posts_orderby', 'fre_order_by_bid_status' );
$bid_query = new WP_Query( array(
		'post_type'      => BID,
		'post_parent'    => get_the_ID(),
		'post_status'    => array(
			'publish',
			'complete',
			'accept',
			'unaccept',
			'disputing',
			'disputed',
			'archive',
			'hide'
		),
		'posts_per_page' => - 1
	)
);
remove_filter( 'posts_orderby', 'fre_order_by_bid_status' );
$bid_data = array();

?>

<div id="project-detail-bidding" class="project-detail-box no-padding">
    <div class="freelancer-bidding-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <div class="row">
                    <div class="col-md-8 col-sm-12">
                        <div class="col-free-bidding"><?php printf( __( 'FREELANCER BIDDING (%s)', ET_DOMAIN ), $number_bids ); ?></div>
                    </div>
                    <div class="col-md-4 hidden-sm hidden-xs">
                        <div class="col-free-reputation"><?php _e( 'REPUTATION', ET_DOMAIN ); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-3 hidden-xs">
                <div class="col-free-bid"><?php _e( 'BID', ET_DOMAIN ); ?></div>
            </div>
        </div>
    </div>

    <div class="freelancer-bidding">
		<?php
		if ( $bid_query->have_posts() ) {
			global $wp_query, $ae_post_factory, $post;
			$post_object = $ae_post_factory->get( BID );
			while ( $bid_query->have_posts() ) {
				$bid_query->the_post();
				$convert = $post_object->convert( $post );
				get_template_part( 'template/bidding', 'item' );
			}
		} else {
			get_template_part( 'template/bid', 'not-item' );
		}
		?>
    </div>
	<?php
	wp_reset_postdata();
	wp_reset_query();
	?>
</div>