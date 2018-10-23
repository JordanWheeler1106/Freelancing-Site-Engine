<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Freelance Engine
 * @since Freelance Engine 1.0
 */

get_header(); ?>

<section class="blog-header-container">
	<div class="container">
		<!-- blog header -->
		<div class="row">
		    <div class="col-md-12 blog-classic-top">
		        <h2><?php _e("Not Found", ET_DOMAIN); ?></h2>
		    </div>
		</div>
		<!--// blog header  -->
	</div>
</section>

<div class="container">
	<div class="page-notfound-content">
		<h2><?php _e("404 Error", ET_DOMAIN); ?></h2>
        <h4><?php _e("Sorry, the page you were looking for doesn't exist!", ET_DOMAIN ); ?></h4>
        <p>
        	<?php printf(__('Go back to <a href="%s">home</a> page or return to <a href="#" onclick="window.history.back()">previous</a> page.', ET_DOMAIN),
        			home_url()); ?></p>
    </div>
</div>
<?php
get_footer();
