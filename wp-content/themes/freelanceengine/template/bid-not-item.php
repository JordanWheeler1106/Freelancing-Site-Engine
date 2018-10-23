<?php
/**
 * The template for displaying no bidding info in a project details page
 * @since 1.0
 * @author Dakachi
 */
?>
<div class="no-bid-found">
	<div class="row">
	<?php
		global $wp_query, $ae_post_factory, $post,$user_ID;
		// get current project post data
	    $project_object = $ae_post_factory->get(PROJECT);;
	    $project = $project_object->current_post;

		$role = ae_user_role();
		if($project->post_status == 'publish' ){
		 	if( (int) $project->post_author == $user_ID || $role != FREELANCER ){?>
		 		<div class="col-md-8 col-sm-8" style="line-height:26px;">
				<?php _e('There are no bids yet.',ET_DOMAIN);?>
				</div>
				<?php if($role == FREELANCER || !is_user_logged_in()) { ?>
				<div class="col-md-4 col-sm-4 text-right">
					<a href="#"  class="btn btn-apply-project-item btn-login-trigger" ><?php  _e('Bid',ET_DOMAIN);?></a>
				</div>
				<?php } ?>
				<div class="clearfix"></div>
			<?php } else if( $role == 'freelancer' || !$user_ID ) { ?>
				<div class="col-md-8 col-sm-8" style="line-height:26px;">
				   <?php _e('There are no bids yet. Be the first one now!',ET_DOMAIN);?>

				</div>
				<div class="col-md-4 col-sm-4 text-right">
					<?php fre_button_bid($project->ID);?>					
				</div>
				<div class="clearfix"></div>
			<?php }
		}  else {
			echo '<div class="col-md-12" >';
			$status = 	array(	'pending' => __('This project is pending', ET_DOMAIN),
								'archive' => __('This project has been archived',ET_DOMAIN) ,
								'reject'  => __('This project has been rejected',ET_DOMAIN) );
			if(isset($status[$project->post_status]))
				printf($status[$project->post_status]);

			echo '</div>';
		}
	?>
	</div>
</div>