<?php 
/**
 * Template Name: Page List Notification 
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.0
 */
global $user_ID;
get_header();
?>
	<div class="fre-page-wrapper">
		<div class="fre-page-title">
			<div class="container">
				<h2><?php _e('Your Notifications', ET_DOMAIN); ?></h2>
			</div>
		</div>

		<div class="fre-page-section">
			<div class="container">
				<div class="page-notification-wrap" id="fre_notification_container">
					<?php fre_user_notification( $user_ID, 1,'', 'fre-notification-list'); ?>
				</div>
			</div>
		</div>
	</div>

 <?php get_footer(); ?>
