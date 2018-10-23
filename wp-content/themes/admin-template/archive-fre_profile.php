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
$post_object = $ae_post_factory->get( PROFILE );
get_header();
$count_posts = wp_count_posts( PROFILE );
$user_role   = ae_user_role( $user_ID );
?>

    <div class="fre-page-wrapper section-archive-profile">
        <div class="fre-page-title">
            <div class="container">
                <h2><?php _e( 'Available Profiles', ET_DOMAIN ); ?></h2>
            </div>
        </div>

        <div class="fre-page-section">
            <div class="container">
                <div class="page-profile-list-wrap">
                    <div class="fre-profile-list-wrap">
						<?php get_template_part( 'template/filter', 'profiles' ); ?>
                        <div class="fre-profile-list-box">
                            <div class="fre-profile-list-wrap">
                                <div class="fre-profile-result-sort">
                                    <div class="row">
										<?php
										$query_post  = $wp_query->found_posts;
										$found_posts = '<span class="found_post">' . $query_post . '</span>';
										$plural      = sprintf( __( '%s profiles available', ET_DOMAIN ), $found_posts );
										$singular    = sprintf( __( '%s profile available', ET_DOMAIN ), $found_posts );
										$not_found   = sprintf( __( 'There are no available profiles on this site!', ET_DOMAIN ), $found_posts );
										?>
                                        <div class="col-md-4 col-md-push-8 col-sm-5 col-sm-push-7">
											<?php if ( $query_post >= 1 ) { ?>
                                                <div class="fre-profile-sort">
                                                    <select class="fre-chosen-single sort-order" name="orderby">
                                                        <option value="date"><?php _e( 'Newest Profiles', ET_DOMAIN ); ?></option>
                                                        <option value="hour_rate"><?php _e( 'Highest Hourly Rate', ET_DOMAIN ); ?></option>
                                                        <option value="rating"><?php _e( 'Highest Rating', ET_DOMAIN ); ?></option>
                                                        <option value="projects_worked"><?php _e( 'Most Projects Worked', ET_DOMAIN ); ?></option>
                                                    </select>
                                                </div>
											<?php } ?>
                                        </div>
                                        <div class="col-md-8 col-md-pull-4 col-sm-7 col-sm-pull-5">
                                            <div class="fre-profile-result">
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
								<?php get_template_part( 'list', 'profiles' ); ?>
                            </div>
                        </div>
						<?php
						echo '<div class="fre-paginations paginations-wrapper">';
						ae_pagination( $wp_query, get_query_var( 'paged' ) );
						echo '</div>';
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();


