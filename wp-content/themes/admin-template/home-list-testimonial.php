<?php 
	$query = new WP_Query(array(
        'post_type' => 'testimonial',
        'showposts' => -1,
        'orderby'   => 'date',
        'order'     => 'DESC',
    ));
?>

<div class="owl-carousel owl-carousel-stories">
	<?php 
		global $post;
        if($query->have_posts()){
            while($query->have_posts()){
                $query->the_post();
    ?>
				<div class="item">
					<div class="fre-stories-wrap">
						<?php if(has_post_thumbnail($post)){ ?>
							<div class="stories-img">
								<?php the_post_thumbnail( 'large' );?>
							</div>
						<?php } ?>
						<div class="stories-content">
							<div class="fre-quote">
								<svg version="1.1" id="Forma_1_1_" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
									 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="27px"
									 viewBox="0 0 300 250" style="enable-background:new 0 0 300 250;" xml:space="preserve">
								<style type="text/css">
									.st0{fill-rule:evenodd;clip-rule:evenodd;}
								</style>
								<switch>
									<foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
										<i:pgfRef  xlink:href="#adobe_illustrator_pgf">
										</i:pgfRef>
									</foreignObject>
									<g i:extraneous="self">
										<g id="Forma_1">
											<g>
												<path class="st0" d="M118.7025757,15.7561646c-3.0621338-6.2647705-10.4992065-9.0736084-17.0610352-6.2647705
													C83.0498047,17.270752,67.083252,27.4274292,54.3972168,39.7435913
													C38.8676147,54.2213745,28.3690796,70.8609619,22.6818237,89.2284546
													c-5.6865234,18.5831299-8.7485962,43.8648682-8.7485962,76.060791v66.3400269
													c0,7.1276855,5.9050293,12.9633179,13.1229248,12.9633179h85.9591675c7.2178955,0,13.1236572-5.8356323,13.1236572-12.9633179
													v-84.9232788c0-7.1298828-5.9057617-12.9632568-13.1236572-12.9632568H71.8952637
													c0.437561-21.8279419,5.6864624-39.328064,15.3104248-52.5093384
													c7.8740234-10.5904541,19.6854248-19.232605,35.6520386-25.9311523
													c6.7810059-2.8110352,9.6239014-10.8061523,6.5617676-17.2888794L118.7025757,15.7561646z M277.2781372,55.3022461
													c6.7802124-2.8110352,9.6239014-10.8061523,6.5617676-17.2888794l-10.7176514-22.0369873
													c-3.0620728-6.2692871-10.4985352-9.0803223-17.0603638-6.2692871
													c-18.373291,7.7793579-34.1213379,17.9338989-47.0258179,30.036499
													c-15.5296631,14.6913452-26.2473755,31.3285522-31.9338379,49.6983032
													c-5.6872559,18.1517944-8.5308838,43.4334717-8.5308838,75.8473511v66.3400269
													c0,7.1276855,5.9057007,12.9633179,13.1236572,12.9633179h85.9591675c7.2179565,0,13.1236572-5.8356323,13.1236572-12.9633179
													v-84.9232788c0-7.1298828-5.9057007-12.9632568-13.1236572-12.9632568h-41.3392944
													c0.4376221-21.8279419,5.6871948-39.328064,15.3110962-52.5093384
													C249.5001221,70.6429443,261.3115234,62.0007935,277.2781372,55.3022461z"/>
											</g>
										</g>
									</g>
								</switch>
								</svg>
							</div>
							<p><?php echo $post->post_content;?></p>
							<br/>
							<p><?php echo $post->post_title;?></p>
							<?php 
								$position = get_post_meta( $post->ID, '_test_category', true ); ;
								if($position){
									echo '<p>'.$position.'</p>';
								}
							?>
						</div>
					</div>
				</div>
    <?php
            }
        }
        wp_reset_query();
	?>
	
</div>