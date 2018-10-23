<?php
/**
 * Use for page author.php and page-profile.php
 */
global $wp_query, $ae_post_factory, $post;
$wp_query->query = array_merge(  $wp_query->query ,array('posts_per_page' => 6)) ;

$post_object = $ae_post_factory->get( 'portfolio' );
$is_edit = false;
if(is_author()){
	$author_id        = get_query_var( 'author' );
}else{
	$author_id        = get_current_user_id();
	$is_edit = true;
}

$query_args =  array(
	// 'post_parent' => $convert->ID,
	'posts_per_page' => 6,
	'post_status' => 'publish',
	'post_type' => PORTFOLIO,
	'author' => $author_id,
	'is_edit' =>$is_edit
);

query_posts($query_args);

if(have_posts() or $is_edit) {
	?>
    <div class="fre-profile-box">
        <div class="portfolio-container">
            <div class="profile-freelance-portfolio">
                <div class="row">

                    <div class="<?php echo $is_edit ? 'col-sm-6' :'' ?> col-xs-12">
                        <h2 class="freelance-portfolio-title"><?php _e('Portfolio',ET_DOMAIN) ?></h2>
                    </div>
					<?php if($is_edit){ ?>
                        <div class="col-sm-6 col-xs-12">
                            <div class="freelance-portfolio-add">
                                <a href="#"  class="fre-normal-btn-o portfolio-add-btn add-portfolio">Add new</a>
                            </div>
                        </div>
					<?php } ?>
                </div>

				<?php if(!have_posts() and $is_edit){ ?>
                    <p class="fre-empty-optional-profile"><?php _e('Add portfolio to your profile. (optional)',ET_DOMAIN) ?></p>
				<?php }else { ?>
                    <ul class="freelance-portfolio-list row">
						<?php
						$postdata = array();
						while ( have_posts() ) {
							the_post();
							$convert    = $post_object->convert( $post, 'thumbnail' );
							$postdata[] = $convert;
							get_template_part( 'template/portfolio', 'item' );
						}
						?>
                    </ul>
				<?php } ?>

				<?php
				if ( ! empty( $postdata ) && $wp_query->max_num_pages > 1 ) {
					/**
					 * render post data for js
					 */
					echo '<script type="data/json" class="postdata portfolios-data" >' . json_encode( $postdata ) . '</script>';

					echo '<div class="freelance-portfolio-loadmore">';
					ae_pagination( $wp_query, get_query_var( 'paged' ), 'load_more','View more' );
					echo '</div>';
				}
				?>
            </div>
        </div>
    </div>
<?php }  ?>

