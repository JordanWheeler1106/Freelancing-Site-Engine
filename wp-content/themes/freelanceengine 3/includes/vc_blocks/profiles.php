<?php
if(!class_exists('WPBakeryShortCode')) return;
class WPBakeryShortCode_fre_block_profile extends WPBakeryShortCode {

    protected function content($atts, $content = null) {

        $custom_css = $el_class = $title = $icon = $output = $s_content = $m_link = '';

        extract(shortcode_atts(array(
            'el_class'  => '',
            'showposts' => 10,
            'orderby' => 'date',
            'paginate' => '',
            // 'query' => ''
        ), $atts));
        /* ================  Render Shortcodes ================ */
        ob_start();
        $query_args = array(   'post_type' => PROFILE ,
                                'post_status' => 'publish' ,
                                'posts_per_page' => $showposts
                            ) ;
        $key = '';
        if($orderby == 'rating') {
            $key = 'rating_score';
        }

        if($orderby == 'hourly_rate') {
            $key = 'hour_rate';
            $query_args['order'] = 'ASC';
        }

        if( $orderby != '' ) {
            $query_args['meta_key'] = $key;
            $query_args['orderby'] =  !empty($key) ? 'meta_value_num date' : ' date';

            if ( ! current_user_can( 'manage_options' ) ) {
                $query_args['meta_query'][] =  array( 'key'   => 'user_available',
                                                    'value'   => 'on',
                                                    'compare' => '='
                                                    );
            }

            if( $orderby == 'rating' || $orderby == 'hourly_rate' ){
                // rating and hour
                $query_args['meta_query'] = array(
                        'relation' => 'AND',
                        array('key' => $key,'compare' => 'BETWEEN','value' => array(0,5))
                );
            }
        }
        ?>
        <!-- COUNTER -->
        <section class="section-wrapper section-project-home vs-tab-profile-home">
            <div class="list-profile-wrapper">
                <div class="tab-content-profile">
                    <!-- Tab panes -->
                    <div class="tab-content vc-block-profiles">
                        <!-- Tab panes -->
                        <?php query_posts( $query_args); ?>
                        <div class="tab-pane fade in active tab-profile-home">
                            <div class="row">
                                <?php
                                /**
                                 * Template list profiles
                                */
                                global $wp_query, $ae_post_factory, $post;
                                $post_object = $ae_post_factory->get( PROFILE );
                                ?>
                                <div class="list-profile profile-list-container">
                                     <!-- block control  -->
                                    <?php
                                    if(have_posts()) {
                                        $postdata = array();
                                        while(have_posts()) { the_post();
                                            $convert = $post_object->convert($post);
                                            $postdata[] = $convert;
                                            get_template_part('template/profile', 'item-old' );
                                        }
                                        /**
                                        * render post data for js
                                        */
                                        echo '<script type="data/json" class="postdata" >'.json_encode($postdata).'</script>';
                                    }
                                    ?>
                                </div>
                                <div class="clearfix"></div>
                                <!--// blog list  -->
                                <!-- pagination -->
                                <div class="col-md-12">
                                    <?php
                                        if($paginate == 'page' || $paginate == 'load_more') {
                                            echo '<div class="paginations-wrapper">';
                                            //ae_pagination($wp_query, get_query_var('paged'), $paginate); ?>
                                            <a href="<?php echo get_post_type_archive_link( PROFILE ); ?>" class=" view-all" ><?php _e('View all profiles ', ET_DOMAIN); ?></a>
                                        <?php echo '</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php wp_reset_query(); ?>
                    </div>
                </div>
            </div>
        </section>
        <?php
        $output = ob_get_clean();
        /* ================  Render Shortcodes ================ */
        return $output;
    }
}


vc_map( array(
    "base"      => "fre_block_profile",
    "name"      => __("List profiles", ET_DOMAIN),
    "class"     => "",
    "icon"      => "",
    "category" => __("FreelanceEngine", ET_DOMAIN),
    "params"    => array(

        array(
            "type" => "textfield",
            "heading" => __("Number of posts", ET_DOMAIN),
            "class" => "input-title",
            "param_name" => "showposts",
            "value"     => '10'
        ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Orderby", ET_DOMAIN),
            "param_name" => "orderby",
            "value"      => array('Date' => 'date', 'Rating' => 'rating' , 'Hourly' => 'hourly_rate'),
        ),
        array(
            "type"       => "dropdown",
            "class"      => "",
            "heading"    => __("Paginate", ET_DOMAIN),
            "param_name" => "paginate",
            "value"      => array('none' => '0', 'Page paginate' => 'page', 'Load More' => 'load_more'),
        )
    )
));