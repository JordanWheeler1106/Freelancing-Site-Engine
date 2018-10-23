<?php
if(!class_exists('WPBakeryShortCode')) return;
class WPBakeryShortCode_fre_status_block extends WPBakeryShortCode {

    protected function content($atts, $content = null) {

        $custom_css = $el_class = $title = $icon = $output = $s_content = $m_link = '';

        extract(shortcode_atts(array(
            'el_class'      => '',
            'title'         => __("Status", ET_DOMAIN),
            's_title'       => '',
            's_description' => '',
        ), $atts));
        /* ================  Render Shortcodes ================ */
        ob_start();
        ?>
        <!-- COUNTER -->
        <?php
            $count_project = wp_count_posts(PROJECT);
            // $count_profile = wp_count_posts(PROFILE);
            add_filter('posts_groupby', 'posts_groupby_profile', 10, 2);
            $query_args = array(
                'post_status'   => 'publish',
                'post_type'     => 'fre_profile',
                'posts_per_page' => -1
            );
            // if ( ! current_user_can( 'manage_options' ) ) {
            $query_args['meta_query'][] =  array( 'key'   => 'user_available',
                                                'value'   => 'on',
                                                'compare' => '='
                                                );
            // }
            $Profiles = query_posts($query_args);
            $count_profile = count($Profiles);
            remove_filter('posts_groupby', 'posts_groupby_profile');
            $result = count_users();
        ?>
        <section class="counter-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title-heading">
                            <h3><?php echo $s_title;?></h3>
                            <h4><?php echo $s_description;?></h4>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="counter-detail active">
                            <div class="counter-icon">
                                <div class="counter-icon-layla">
                                    <i class="fa fa-bullhorn"></i>
                                </div>
                            </div>
                            <div class="counter-content">
                                <div class="odometer" data-number="<?php echo $count_project->publish; ?>">0</div>
                                <h3 class="title"><?php _e('Projects', ET_DOMAIN);?></h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="counter-detail active">
                            <div class="counter-icon">
                                <div class="counter-icon-layla">
                                    <i class="fa fa-file-text"></i>
                                </div>
                            </div>
                            <div class="counter-content">
                                <div class="odometer" data-number="<?php echo $count_profile;//$result['avail_roles']['freelancer']; ?>">0</div>
                                <h3 class="title"><?php _e('Profiles', ET_DOMAIN);?></h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="counter-detail active">
                            <div class="counter-icon">
                                <div class="counter-icon-layla">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                            </div>
                            <div class="counter-content">
                                <div class="odometer" data-number="<?php echo isset($result['avail_roles']['employer']) ? $result['avail_roles']['employer'] : '';?>">0</div>
                                <h3 class="title"><?php _e('Employers', ET_DOMAIN);?></h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="counter-detail active">
                            <div class="counter-icon">
                                <div class="counter-icon-layla">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>
                            <div class="counter-content">
                                <div class="odometer" data-number="<?php echo $result['total_users'];?>">0</div>
                                <h3 class="title"><?php _e('Users', ET_DOMAIN);?></h3>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- COUNTER / END -->

        <?php
        $output = ob_get_clean();
        /* ================  Render Shortcodes ================ */
        return $output;
    }
}


vc_map( array(
    "base"      => "fre_status_block",
    "name"      => __("Site Status", ET_DOMAIN),
    "class"     => "",
    "icon"      => "",
    "category" => __("FreelanceEngine", ET_DOMAIN),
    "params"    => array(
        array(
            "type" => "textfield",
            "heading" => __("Title", ET_DOMAIN),
            "class" => "input-title",
            "param_name" => "s_title",
            "value"     => 'THE FREELANCE MARKETPLACE WP THEME MADE BY ENGINETHEMES'
        ),
        array(
            "type" => "textfield",
            "heading" => __("Description", ET_DOMAIN),
            "class" => "input-description",
            "param_name" => "s_description",
            "value"     => 'We love building awesome solutions for your business. <br/> With all our passion and experience in WP.'
        )
    )
));
