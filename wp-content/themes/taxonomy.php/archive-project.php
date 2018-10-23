<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.0
 */

global $wp_query, $ae_post_factory, $post, $user_ID;
$query_args = array(
	'post_type'   => PROJECT,
	'post_status' => 'publish'
);
$loop       = new WP_Query( $query_args );
get_header();
?>
    <div class="fre-page-wrapper">
        <div class="fre-page-title">
            <div class="container">
                <h2><?php _e( 'Available Projects', ET_DOMAIN ); ?></h2>
            </div>
        </div>
        <div class="fre-page-section section-archive-project">
            <div class="container">
                <div class="page-project-list-wrap">
                    <div class="fre-project-list-wrap">
						<?php get_template_part( 'template/filter', 'projects' ); ?>
                        <div class="fre-project-list-box">
                            <div class="fre-project-list-wrap">
                                <div class="fre-project-result-sort">
                                    <div class="row">
										<?php
										$query_post  = $loop->found_posts;
										$found_posts = '<span class="found_post">' . $query_post . '</span>';
										$plural      = sprintf( __( '%s projects found', ET_DOMAIN ), $found_posts );
										$singular    = sprintf( __( '%s project found', ET_DOMAIN ), $found_posts );
										$not_found   = sprintf( __( 'There are no projects posted on this site!', ET_DOMAIN ), $found_posts );
										?>
                                        <div class="col-sm-4 col-sm-push-8">
											<?php if ( $query_post >= 1 ) { ?>
                                                <div class="fre-project-sort">
                                                    <select class="fre-chosen-single sort-order" id="project_orderby"
                                                            name="orderby">
                                                        <option value="date"><?php _e( 'Latest Projects', ET_DOMAIN ); ?></option>
                                                        <option value="et_budget"><?php _e( 'Highest Budget', ET_DOMAIN ); ?></option>
                                                    </select>
                                                </div>
											<?php } ?>
                                        </div>
                                        <div class="col-sm-8 col-sm-pull-4">
                                            <div class="fre-project-result">
                                                <p>
                                                    <span class="plural <?php if ( $query_post == 1 ) {
	                                                    echo 'hide';
                                                    } ?>"><?php if ( $query_post < 1 ) {
		                                                    echo $not_found;
	                                                    } else {
		                                                    echo $plural;
	                                                    } ?></span>
                                                    <span class="singular <?php if ( $query_post > 1 || $query_post < 1 ) {
														echo 'hide';
													} ?>"><?php echo $singular; ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php get_template_part( 'list', 'projects' ); ?>
                            </div>
                        </div>
						<?php
						$loop->query = array_merge( $loop->query, array( 'is_archive_project' => is_post_type_archive( PROJECT ) ) );
						echo '<div class="fre-paginations paginations-wrapper">';
						ae_pagination( $loop, get_query_var( 'paged' ) );
						echo '</div>';
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
