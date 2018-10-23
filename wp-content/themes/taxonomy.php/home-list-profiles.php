<?php
/**
 * List profiles
 */
$query_args = array(
	'post_type' => PROFILE ,
	'post_status' => 'publish' ,
	'posts_per_page' => 4,
	'meta_key' => 'rating_score',
	'meta_query' =>  array(
    	array(
 			'key'   => 'user_available',
    		'value'   => 'on',
    		'compare' => '='
       )
   ),
	'orderby'  => array(
		'meta_value_num' => 'DESC',
		'post_date'      => 'DESC',
	),
) ;
$loop = new WP_Query( $query_args);
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( PROFILE );
?>
<div class="row">
	<?php
		if($loop->have_posts()) {
			$postdata = array();
			foreach ($loop->posts as $key => $value) {
				$post = $value;
			    $convert = $post_object->convert($post);
			    $postdata[] = $convert; 
	?>
				<div class="col-lg-6 col-md-12">
					<div class="fre-freelancer-wrap">
						<a class="free-avatar" href="<?php echo get_author_posts_url( $convert->post_author ); ?>">
							<?php echo $convert->et_avatar;?>
						</a>
						<h2><a href="<?php echo get_author_posts_url( $convert->post_author ); ?>"><?php the_author_meta( 'display_name', $convert->post_author ); ?></a></h2>
						<p><?php echo $convert->et_professional_title;?></p>
						<div class="free-rating rate-it" data-score="<?php echo $convert->rating_score ; ?>"></div>
						<div class="free-hourly-rate">
							<?php printf(__('%s/hr', ET_DOMAIN), "<span>".fre_price_format($convert->hour_rate)."</span>");?>
						</div>
						<div class="free-experience">
							<span><?php echo $convert->experience; ?></span>
							<span><?php echo $convert->project_worked; ?></span>
						</div>
						<div class="free-skill">
						<?php
							if(isset($convert->tax_input['skill']) && $convert->tax_input['skill']){
								$skills = $convert->tax_input['skill'];
                                for ($i = 0; $i <= 2; $i++){
                                	if(isset($skills[$i])){
                                    	echo '<span class="fre-label"><a href="'.get_post_type_archive_link( PROFILE ).'?skill_profile='.$skills[$i]->slug.'">'.$skills[$i]->name.'</a></span>';
                                	}
                             	}
                            }
						?>
						</div>
					</div>
				</div>
	<?php
			}
		}
	?>
</div>