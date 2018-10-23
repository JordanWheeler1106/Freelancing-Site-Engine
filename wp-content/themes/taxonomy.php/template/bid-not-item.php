<?php
/**
 * The template for displaying no bidding info in a project details page
 * @since 1.0
 * @author Dakachi
 */
?>
<div class="freelancer-bidding-not-found">
	<div class="row">
	<?php
		global $wp_query, $ae_post_factory, $post,$user_ID;
		// get current project post data
	    $project_object = $ae_post_factory->get(PROJECT);;
	    $project = $project_object->current_post;

		$role = ae_user_role();
		if($project->post_status == 'publish' ){
		 	if( (int) $project->post_author == $user_ID || $role != FREELANCER ){?>
		 		<div class="col-md-12">
		 			<p><?php _e('There are no bids yet.',ET_DOMAIN);?></p>
				</div>
				<?php if($role == FREELANCER || !is_user_logged_in()) { 
					$href = et_get_page_link('login', array('ae_redirect_url'=> $project->permalink));
				?>
				<?php } ?>
			<?php } else if( $role == 'freelancer' || !$user_ID ) { ?>
				<div class="col-md-12">
					<p>
						<?php _e('There are no bids yet.',ET_DOMAIN);?>
					</p>
				</div>
				
			<?php }
		}  else {
			echo '<div class="col-md-12" ><p>';
			$status = 	array(	'pending' => __('This project is pending', ET_DOMAIN),
								'archive' => __('This project has been archived',ET_DOMAIN) ,
								'reject'  => __('This project has been rejected',ET_DOMAIN) );
			if(isset($status[$project->post_status]))
				printf($status[$project->post_status]);

			echo '</p></div>';
		}
	?>
	</div>
</div>