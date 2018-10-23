<?php
/**
 * Template Name: List Tesimonials
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
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get('testimonial');
get_header();
?>
<section class="section-wrapper  section-archive-testimonial">
<?php query_posts(array('post_type' => 'testimonial' , 'post_status' => 'publish')); ?>
    <div class="number-project-wrapper-archive">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-xs-6 chosen-sort">
                    <h2 class="number-project">
                    <?php
                    $found_posts = '<span class="found_post">'.$wp_query->found_posts.'</span>';
                    $plural = sprintf(__('%s Testimonials for you',ET_DOMAIN), $found_posts);
                    $singular = sprintf(__('%s testimonial for you',ET_DOMAIN),$found_posts);
                    ?>
                    <span class="plural <?php if( $wp_query->found_posts <= 1 ) { echo 'hide'; } ?>" >
                            <?php echo $plural; ?>
                        </span>
                        <span class="singular <?php if( $wp_query->found_posts > 1 ) { echo 'hide'; } ?>">
                            <?php echo $singular; ?>
                        </span>
                    </h2>
                </div>
                <div class="col-md-3 col-xs-6">

                </div>
            </div>
        </div>
    </div>
    <div class="list-project-wrapper">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-12">
                	<div class="tab-content-project">
                    	<?php
                            get_template_part( 'list', 'testimonials' );
                            wp_reset_query();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<?php
get_footer();
