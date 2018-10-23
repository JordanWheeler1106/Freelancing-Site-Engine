<?php 
/**
 * Template Name: Homepage New
 */

get_header();
?>
<!-- Block Banner -->
<div class="fre-background" id="background_banner" style="background-image: url('<?php echo get_theme_mod("background_banner") ? get_theme_mod("background_banner") : get_template_directory_uri()."/img/fre-bg.png";?>');">
	<div class="fre-bg-content">
		<div class="container">
			<h1 id="title_banner"><?php echo get_theme_mod("title_banner") ? get_theme_mod("title_banner") : __("Find perfect freelancers for your projects or Look for freelance jobs online?", ET_DOMAIN);?></h1>
			<?php if(!is_user_logged_in()){ ?>
				<?php if(!fre_check_register()){ ?>
					<a class="fre-btn" href="<?php echo get_post_type_archive_link( PROFILE ); ?>"><?php _e('Find Freelancers', ET_DOMAIN);?></a>
					<a class="fre-btn" href="<?php echo get_post_type_archive_link( PROJECT ); ?>"><?php _e('Find Projects', ET_DOMAIN);?></a>
				<?php }else{ ?>
					<a class="fre-btn" href="<?php echo et_get_page_link('register', array("role"=>'employer')); ?>"><?php _e('Hire Freelancer', ET_DOMAIN);?></a>
					<a class="fre-btn" href="<?php echo et_get_page_link('register', array("role"=>'freelancer')); ?>"><?php _e('Apply as Freelancer', ET_DOMAIN);?></a>
				<?php } ?>
				
			<?php }else{ ?>
				<?php if(ae_user_role($user_ID) == FREELANCER){ ?>
					<a class="fre-btn" href="<?php echo get_post_type_archive_link( PROJECT ); ?>"><?php _e('Find Projects', ET_DOMAIN);?></a>
					<a class="fre-btn" href="<?php echo et_get_page_link('profile'); ?>"><?php _e('Update Profile', ET_DOMAIN);?></a>
				<?php }else{ ?>
					<a class="fre-btn" href="<?php echo et_get_page_link('submit-project'); ?>"><?php _e('Post a Project', ET_DOMAIN);?></a>
					<a class="fre-btn" href="<?php echo get_post_type_archive_link( PROFILE ); ?>"><?php _e('Find Freelancers', ET_DOMAIN);?></a>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
<!-- Block Banner -->
<!-- Block How Work -->
<div class="fre-how-work">
	<div class="container">
		<h2 id="title_work"><?php echo get_theme_mod("title_work") ? get_theme_mod("title_work") : __('How FreelanceEngine works?', ET_DOMAIN);?></h2>
		<div class="row">
			<div class="col-lg-3 col-sm-6">
				<div class="fre-work-block">
					<span>
						<img src="<?php echo get_theme_mod('img_work_1') ? get_theme_mod('img_work_1') : get_template_directory_uri().'/img/1.png';?>" id="img_work_1" alt="">
					</span>
					<p id="desc_work_1"><?php echo get_theme_mod("desc_work_1") ? get_theme_mod("desc_work_1") : __('Post projects to tell us what you need done', ET_DOMAIN);?></p>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="fre-work-block">
					<span>
						<img src="<?php echo get_theme_mod('img_work_2') ? get_theme_mod('img_work_2') : get_template_directory_uri().'/img/2.png';?>" id="img_work_2" alt="">
					</span>
					<p id="desc_work_2"><?php echo get_theme_mod("desc_work_2") ? get_theme_mod("desc_work_2") : __('Browse profiles, reviews, then hire your most favorite and start project', ET_DOMAIN);?></p>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="fre-work-block">
					<span>
						<img src="<?php echo get_theme_mod('img_work_3') ? get_theme_mod('img_work_3') : get_template_directory_uri().'/img/3.png';?>" id="img_work_3" alt="">
					</span>
					<p id="desc_work_3"><?php echo get_theme_mod("desc_work_3") ? get_theme_mod("desc_work_3") : __('Use FreelanceEngine platform to chat and share files', ET_DOMAIN);?></p>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="fre-work-block">
					<span>
						<img src="<?php echo get_theme_mod('img_work_4') ? get_theme_mod('img_work_4') : get_template_directory_uri().'/img/4.png';?>" id="img_work_4" alt="">
					</span>
					<p id="desc_work_4"><?php echo get_theme_mod("desc_work_4") ? get_theme_mod("desc_work_4") : __('With our protection, money is only paid for work you authorize', ET_DOMAIN);?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Block How Work -->
<!-- List Profiles -->
<div class="fre-perfect-freelancer">
	<div class="container">
		<h2 id="title_freelance"><?php echo get_theme_mod("title_freelance") ? get_theme_mod("title_freelance") : __('Find perfect freelancers for your projects', ET_DOMAIN);?></h2>
		<?php get_template_part( 'home-list', 'profiles' );?>
		<div class="fre-perfect-freelancer-more">
			<a class="fre-btn-o" href="<?php echo get_post_type_archive_link( PROFILE ); ?>"><?php _e('See all freelancers', ET_DOMAIN);?></a>
		</div>
	</div>
</div>
<!-- List Profiles -->
<!-- List Projects -->
<div class="fre-jobs-online">
	<div class="container">
		<h2 id="title_project"><?php echo get_theme_mod("title_project") ? get_theme_mod("title_project") : __('Browse numerous freelance jobs online', ET_DOMAIN);?></h2>
		<?php get_template_part( 'home-list', 'projects' );?>
	</div>
</div>
<!-- List Projects -->
<!-- List Testimonials -->
<div class="fre-our-stories">
	<div class="container">
		<h2 id="title_story"><?php echo get_theme_mod("title_story") ? get_theme_mod("title_story") : __('Hear what our customers have to say', ET_DOMAIN);?></h2>
		<?php get_template_part( 'home-list', 'testimonial' );?>
	</div>
</div>
<!-- List Testimonials -->
<!-- List Pricing Plan -->
<div class="fre-service">
	<div class="container">
		<h2 id="title_service">
			<?php
				if(ae_user_role($user_ID) == FREELANCER){
					echo get_theme_mod("title_service_freelancer") ? get_theme_mod("title_service_freelancer") : __('Select the level of service you need for project bidding', ET_DOMAIN);
				}else{
					echo get_theme_mod("title_service") ? get_theme_mod("title_service") : __('Select the level of service you need for project posting', ET_DOMAIN);
				}
			?>
		</h2>
		<?php get_template_part( 'home-list', 'pack' );?>
	</div>
</div>
<!-- List Pricing Plan -->
<!-- List Get Started -->
<div class="fre-get-started">
	<div class="container">
		<div class="get-started-content">
			<?php if(!is_user_logged_in()){ ?>
				<h2 id="title_start"><?php echo get_theme_mod("title_start") ? get_theme_mod("title_start") : __('Need work done? Join FreelanceEngine community!', ET_DOMAIN);?></h2>
				<?php if(fre_check_register()){ ?>
				<a class="fre-btn" href="<?php echo et_get_page_link('register');?>"><?php _e('Get Started', ET_DOMAIN)?></a>
				<?php } ?>
			<?php }else{ ?>
				<?php if(ae_user_role($user_ID) == FREELANCER){ ?>
					<h2 id="title_start"><?php echo get_theme_mod("title_start_freelancer") ? get_theme_mod("title_start_freelancer") : __("It's time to start finding freelance jobs online!" , ET_DOMAIN);?></h2>
					<a class="fre-btn" href="<?php echo get_post_type_archive_link( PROJECT ); ?>"><?php _e('Find Projects', ET_DOMAIN)?></a>
				<?php }else{ ?>
					<h2 id="title_start"><?php echo get_theme_mod("title_start_employer") ? get_theme_mod("title_start_employer") : __('The best way to find perfect freelancers!', ET_DOMAIN);?></h2>
					<a class="fre-btn" href="<?php echo et_get_page_link('submit-project'); ?>"><?php _e('Post a Project', ET_DOMAIN)?></a>
				<?php } ?>
			<?php } ?>
			
		</div>
	</div>
</div>
<!-- List Get Started -->
<?php get_footer(); ?>