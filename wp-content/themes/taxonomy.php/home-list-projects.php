<?php
	$query_args = array(
		'post_type' => PROJECT ,
        'post_status' => 'publish' ,
        'posts_per_page' => 6,
        'orderby'   => 'date',
        'order'     => 'DESC',
        'is_block'  => 'projects'
    ) ;
    query_posts( $query_args);
?>
<ul class="fre-jobs-list">
	<?php 
		global $wp_query, $ae_post_factory, $post;
		$post_object = $ae_post_factory->get('project');
		while (have_posts()) { the_post();
	        $convert = $post_object->convert($post);
	        $postdata[] = $convert;
	?>
			<li>
				<div class="jobs-title">
					<p><?php echo $convert->post_title;?></p>
				</div>
				<div class="jobs-date">
					<p><?php echo $convert->post_date;?></p>
				</div>
				<div class="jobs-price">
					<p><?php echo fre_price_format($convert->et_budget);?></p>
				</div>
				<div class="jobs-view">
					<a href="<?php the_permalink();?>"><?php _e('View details', ET_DOMAIN)?></a>
				</div>
			</li>
	<?php } ?>
</ul>
<div class="fre-jobs-online-more">
	<a class="fre-btn-o" href="<?php echo get_post_type_archive_link( PROJECT ); ?>"><?php _e('See all jobs', ET_DOMAIN)?></a>
</div>