<?php 

/**
 * Freelanceengine Customizer functionality
 * @author ThanhTu
 */
class Fre_Customizer extends AE_Base {
	function __construct(){
		$this->add_action('customize_register', 'fre_customize_register', 11);
		$this->add_action('customize_preview_init', 'fre_customize_js');
		$this->add_action('customize_save_after', 'ae_update_option', 10, 3);
	}
	/**
	 * register javascript 
	 * @author ThanhTu
	 */
	public function fre_customize_js() {
        wp_enqueue_script('fre-customizer',
            get_template_directory_uri() . '/includes/Fre_Customize/assets/fre_customizer.js', array('jquery', 'appengine', 'customize-preview'),
            ET_VERSION,
            true
        );
    }
    /**
	 * Update option ET 
	 * @author ThanhTu
	 */
    public function ae_update_option($wp_customize_manager){
    	// Sync site_logo
        $customize_site_logo = get_theme_mod('site_logo');
        $site_logo_id = attachment_url_to_postid($customize_site_logo);
        $attach_data = et_get_attachment_data($site_logo_id); 
        ae_update_option('site_logo', $attach_data);
    }
    /**
	 * Customize Register
	 * @author ThanhTu
	 */
	public function fre_customize_register($wp_customize){
		// Block Banner
		$wp_customize->add_panel("fre_panel", array(
			"title" => __("Title & Background", ET_DOMAIN),
			"priority" => 30,
			'capability'     => 'edit_theme_options',
		));
		$this->fre_customize_banner($wp_customize);
		$this->fre_customize_work($wp_customize);
		$this->fre_customize_freelance($wp_customize);
		$this->fre_customize_project($wp_customize);
		$this->fre_customize_story($wp_customize);
		$this->fre_customize_service($wp_customize);
		$this->fre_customize_start($wp_customize);

		$wp_customize->add_setting('site_logo', array(
			"transport" => "postMessage",
			'default'	=>  get_template_directory_uri()."/img/logo-fre.png"
		));
		$wp_customize->add_control(new WP_Customize_Upload_Control(
	           $wp_customize,
	           'site_logo',
	           array(
	           		'control_id' => 'site_logo',
	               	'label'      => __('Site Logo', ET_DOMAIN ),
	               	'description' => __( 'Your logo should be in PNG, GIF or JPG format, within 150x50px and less than 1500Kb.', ET_DOMAIN ),
	               	'section'    => 'title_tagline',
	               	'settings'   => 'site_logo',
                	'option_type' => 'theme_mod',
                	'field_type' => 'cropped_image',
	               	'width' => 150,
                	'height' => 50
	            )
	        )
		);
	}

	public function fre_customize_banner($wp_customize){
		// Block Banner
		$wp_customize->add_section("block_banner", array(
			"title" => __("Block Banner", ET_DOMAIN),
			"priority" => 35,
			"panel"	=> 'fre_panel'
		));

		$wp_customize->add_setting("desc_block_banner", array(
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_block_banner",
			array(
				"description" => __("This block will display an image that is used as a banner at the top of your homepage.", ET_DOMAIN),
				"section" => "block_banner",
				"settings" => "desc_block_banner",
				'type' => 'hidden'
			)
		));

		$wp_customize->add_setting("title_banner", array(
			"default" => __("Find perfect freelancers for your projects or Look for freelance jobs online?", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_banner",
			array(
				"label" => __("Title", ET_DOMAIN),
				"section" => "block_banner",
				"settings" => "title_banner",
				"type" => "text",
			)
		));

		$wp_customize->add_setting("background_banner", array(
			"default" => get_template_directory_uri().'/img/fre-bg.png',
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Upload_Control(
	           $wp_customize,
	           'background_banner',
	           array(
	               'label'      => __('Upload an image', ET_DOMAIN ),
	               'description' => __( 'Choose an image from your existing images in the media library or upload new ones. The min-height of the image must be greater than or equal to 623 pixel.', ET_DOMAIN ),
	               'section'    => 'block_banner',
	               'settings'   => 'background_banner',
	            )
	        )
		);
	}

	public function fre_customize_work($wp_customize){
		// Block How Work
		$wp_customize->add_section("block_work", array(
			"title" => __("Block How", ET_DOMAIN),
			"priority" => 35,
			"panel"	=> 'fre_panel'
		));
		$wp_customize->add_setting("desc_block_work", array(
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_block_work",
			array(
				"description" => __("This block will give a quick explanation about main workflow in your website.", ET_DOMAIN),
				"section" => "block_work",
				"settings" => "desc_block_work",
				'type' => 'hidden'
			)
		));

		// Title
		$wp_customize->add_setting("title_work", array(
			"default" => __("How FreelanceEngine works?", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_work",
			array(
				"label" => __("Title", ET_DOMAIN),
				"section" => "block_work",
				"settings" => "title_work",
				"type" => "text",
			)
		));
		// Item 1
		$wp_customize->add_setting("img_work_1", array(
			"default" => get_template_directory_uri().'/img/1.png',
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Upload_Control(
			$wp_customize,
			"img_work_1",
			array(
				"label" => __("Upload an image", ET_DOMAIN),
				"description" => __('Choose an image from your existing images in the media library or upload new ones. Or, leave empty if you want to use the default icon. ', ET_DOMAIN),
				"section" => "block_work",
				"settings" => "img_work_1",
			)
		));
		$wp_customize->add_setting("desc_work_1", array(
			"default" => __("Post projects to tell us what you need done", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_work_1",
			array(
				"label" => __("Description", ET_DOMAIN),
				"section" => "block_work",
				"settings" => "desc_work_1",
				"type" => "text",
			)
		));
		// Item 2
		$wp_customize->add_setting("img_work_2", array(
			"default" => get_template_directory_uri().'/img/2.png',
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Upload_Control(
			$wp_customize,
			"img_work_2",
			array(
				"label" => __("Upload an image", ET_DOMAIN),
				"description" => __('Choose an image from your existing images in the media library or upload new ones. Or, leave empty if you want to use the default icon. ', ET_DOMAIN),
				"section" => "block_work",
				"settings" => "img_work_2",
			)
		));
		$wp_customize->add_setting("desc_work_2", array(
			"default" => __("Browse profiles, reviews, then hire your most favorite and start project",ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_work_2",
			array(
				"label" => __("Description", ET_DOMAIN),
				"section" => "block_work",
				"settings" => "desc_work_2",
				"type" => "text",
			)
		));
		// Item 3
		$wp_customize->add_setting("img_work_3", array(
			"default" => get_template_directory_uri().'/img/3.png',
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Upload_Control(
			$wp_customize,
			"img_work_3",
			array(
				"label" => __("Upload an image", ET_DOMAIN),
				"description" => __('Choose an image from your existing images in the media library or upload new ones. Or, leave empty if you want to use the default icon. ', ET_DOMAIN),
				"section" => "block_work",
				"settings" => "img_work_3",
			)
		));
		$wp_customize->add_setting("desc_work_3", array(
			"default" => __("Use FreelanceEngine platform to chat and share files", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_work_3",
			array(
				"label" => __("Description", ET_DOMAIN),
				"section" => "block_work",
				"settings" => "desc_work_3",
				"type" => "text",
			)
		));
		// Item 4
		$wp_customize->add_setting("img_work_4", array(
			"default" => get_template_directory_uri().'/img/4.png',
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Upload_Control(
			$wp_customize,
			"img_work_4",
			array(
				"label" => __("Upload an image", ET_DOMAIN),
				"description" => __('Choose an image from your existing images in the media library or upload new ones. Or, leave empty if you want to use the default icon. ', ET_DOMAIN),
				"section" => "block_work",
				"settings" => "img_work_4",
			)
		));
		$wp_customize->add_setting("desc_work_4", array(
			"default" => __("With our protection, money is only paid for work you authorize", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_work_4",
			array(
				"label" => __("Description", ET_DOMAIN),
				"section" => "block_work",
				"settings" => "desc_work_4",
				"type" => "text",
			)
		));
	}

	public function fre_customize_freelance($wp_customize){
		// Block Freelance
		$wp_customize->add_section("block_freelance", array(
			"title" => __("Block Freelance", ET_DOMAIN),
			"priority" => 35,
			"panel"	=> 'fre_panel'
		));

		$wp_customize->add_setting("desc_block_freelance", array(
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_block_freelance",
			array(
				"description" => __("This block will display freelancers whose rating scores are highest.", ET_DOMAIN),
				"section" => "block_freelance",
				"settings" => "desc_block_freelance",
				'type' => 'hidden'
			)
		));

		$wp_customize->add_setting("title_freelance", array(
			"default" => __("Find perfect freelancers for your projects", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_freelance",
			array(
				"label" => __("Title", ET_DOMAIN),
				"section" => "block_freelance",
				"settings" => "title_freelance",
				"type" => "text",
			)
		));
	}

	public function fre_customize_project($wp_customize){
		// Block Freelance
		$wp_customize->add_section("block_project", array(
			"title" => __("Block Project", ET_DOMAIN),
			"priority" => 35,
			"panel"	=> 'fre_panel'
		));

		$wp_customize->add_setting("desc_block_project", array(
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_block_project",
			array(
				"description" => __("This block will display latest available projects.", ET_DOMAIN),
				"section" => "block_project",
				"settings" => "desc_block_project",
				'type' => 'hidden'
			)
		));

		$wp_customize->add_setting("title_project", array(
			"default" => __("Browse numerous freelance jobs online", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_project",
			array(
				"label" => __("Title", ET_DOMAIN),
				"section" => "block_project",
				"settings" => "title_project",
				"type" => "text",
			)
		));
	}

	public function fre_customize_story($wp_customize){
		// Block Stories
		$wp_customize->add_section("block_story", array(
			"title" => __("Block Story", ET_DOMAIN),
			"priority" => 35,
			"panel"	=> 'fre_panel'
		));

		$wp_customize->add_setting("desc_block_story", array(
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_block_story",
			array(
				"description" => __("This block will display testimonials of your users.", ET_DOMAIN),
				"section" => "block_story",
				"settings" => "desc_block_story",
				'type' => 'hidden'
			)
		));

		$wp_customize->add_setting("title_story", array(
			"default" => __("Hear what our customers have to say", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_story",
			array(
				"label" => __("Title", ET_DOMAIN),
				"section" => "block_story",
				"settings" => "title_story",
				"type" => "text",
			)
		));
	}

	public function fre_customize_service($wp_customize){
		// Block Freelance
		$wp_customize->add_section("block_service", array(
			"title" => __("Block Service", ET_DOMAIN),
			"priority" => 35,
			"panel"	=> 'fre_panel'
		));

		$wp_customize->add_setting("desc_block_service", array(
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_block_service",
			array(
				"description" => __("This block will display available package plans according to freelancer or employer role. If users are freelancers, bid packages are shown. Otherwise, packages for project posting are displayed.", ET_DOMAIN),
				"section" => "block_service",
				"settings" => "desc_block_service",
				'type' => 'hidden'
			)
		));

		$wp_customize->add_setting("title_service", array(
			"default" => __("Select the level of service you need for project posting", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_service",
			array(
				"label" => __("Title (Visitor/Employer)", ET_DOMAIN),
				"section" => "block_service",
				"settings" => "title_service",
				"type" => "text",
			)
		));

		$wp_customize->add_setting("title_service_freelancer", array(
			"default" => __("Select the level of service you need for project bidding", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_service_freelancer",
			array(
				"label" => __("Title (Freelancer)", ET_DOMAIN),
				"section" => "block_service",
				"settings" => "title_service_freelancer",
				"type" => "text",
			)
		));
	}

	public function fre_customize_start($wp_customize){
		// Block Freelance
		$wp_customize->add_section("block_start", array(
			"title" => __("Block Get Start", ET_DOMAIN),
			"priority" => 35,
			"panel"	=> 'fre_panel'
		));

		$wp_customize->add_setting("desc_block_start", array(
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"desc_block_start",
			array(
				"description" => __("This block allows you to set up a greeting sentence, displayed according to user's role.", ET_DOMAIN),
				"section" => "block_start",
				"settings" => "desc_block_start",
				'type' => 'hidden'
			)
		));

		$wp_customize->add_setting("title_start", array(
			"default" => __("Need work done? Join FreelanceEngine community!", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_start",
			array(
				"label" => __("Visitor", ET_DOMAIN),
				"section" => "block_start",
				"settings" => "title_start",
				"type" => "text",
			)
		));

		$wp_customize->add_setting("title_start_freelancer", array(
			"default" => __("It's time to start finding freelance jobs online!" , ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_start_freelancer",
			array(
				"label" => __("Freelancer", ET_DOMAIN),
				"section" => "block_start",
				"settings" => "title_start_freelancer",
				"type" => "text",
			)
		));

		$wp_customize->add_setting("title_start_employer", array(
			"default" => __("The best way to find perfect freelancers!", ET_DOMAIN),
			"transport" => "postMessage",
		));
		$wp_customize->add_control(new WP_Customize_Control(
			$wp_customize,
			"title_start_employer",
			array(
				"label" => __("Employer", ET_DOMAIN),
				"section" => "block_start",
				"settings" => "title_start_employer",
				"type" => "text",
			)
		));
	}
}
new Fre_Customizer();