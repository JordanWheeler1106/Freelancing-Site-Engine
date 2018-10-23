<?php
/**
 * Template part for employer posted project block
 * # this template is loaded in page-profile.php , author.php
 * @since 1.0
 * @package FreelanceEngine
 */
global $wp_query;

?>
    <style>
        @media (min-width: 768px){
            .fre-author-project-filter select{
                display: block !important;
                position: absolute;
                width: auto;
            }
        }

    </style>
    <div class="employer-project-history">
		<?php
		$is_author = is_author();
		$author_id = get_query_var( 'author' );
		$stat      = array( 'publish', 'complete' ,'close');

		$query_args = array(
			'is_author'   => true,
			'post_status' => $stat,
			'post_type'   => PROJECT,
			'author'      => $author_id,
			'order'       => 'DESC',
			'orderby'     => 'date'
		);

		// filter order post by status
		add_filter( 'posts_orderby', 'fre_order_by_project_status' );
		query_posts( $query_args );
		// remove filter order post by status
		$bid_posts = $wp_query->found_posts;
		?>
        <div class="fre-author-project-box">
            <div class="author-project-wrap">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h2 class="fre-author-project-title"><?php _e( 'Project Overview', ET_DOMAIN ); ?></h2>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">

						<?php if ( have_posts() ): ?>
                            <div class="fre-author-project-filter">
                                <select class="fre-chosen-single" name="post_status" data-chosen-width="100%"
                                        data-chosen-disable-search="1"
                                        data-placeholder="<?php _e( "Filter by project's status", ET_DOMAIN ); ?>">
                                    <option value=""><?php _e( "All Projects", ET_DOMAIN ); ?></option>
                                    <option value="publish"><?php _e( "Active", ET_DOMAIN ); ?></option>
                                    <option value="close"><?php _e( "Processing", ET_DOMAIN ); ?></option>
                                    <option value="complete"><?php _e( "Completed", ET_DOMAIN ); ?></option>
                                </select>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
				<?php
				if ( have_posts() ) {
					global $wp_query, $ae_post_factory;
					$author_id = get_query_var( 'author' );

					$post_object = $ae_post_factory->get( PROJECT );
					?>
                    <ul class="list-work-history-profile author-project-list">
						<?php
						$postdata = array();
						while ( have_posts() ) {
							the_post();
							$convert    = $post_object->convert( $post, 'thumbnail' );
							$postdata[] = $convert;
							get_template_part( 'template/author', 'employer-history-item' );
						}
						?>
                    </ul>

					<?php
					echo '<script type="data/json" class="postdata" >' . json_encode( $postdata ) . '</script>';

					if ( ! empty( $postdata ) && $wp_query->max_num_pages > 1 ) {
						echo '<div class="freelance-education-loadmore">';
					}
					ae_pagination( $wp_query, get_query_var( 'paged' ), 'load_more', 'View more' );
					if ( ! empty( $postdata ) && $wp_query->max_num_pages > 1 ) {
						echo '</div>';
					}
				}else{
					_e( '<span class="project-no-results">There are no activities yet.</span>', ET_DOMAIN );
				}
				wp_reset_postdata();
				?>
            </div>
        </div>
    </div>
<?php
remove_filter( 'posts_orderby', 'fre_order_by_project_status' );