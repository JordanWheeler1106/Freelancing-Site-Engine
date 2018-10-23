<?php
global $wp_query, $ae_post_factory, $post;
$post_object = $ae_post_factory->get( 'testimonial' );
?>
<div class="list-testimonial testimonial-list-container grid">
     <!-- block control  -->
    <?php
        if(have_posts()) {
            $postdata = array();
            while(have_posts()) { the_post();
                $convert    = $post_object->convert($post);
                $postdata[] = $convert;
                get_template_part('template/testimonial', 'item' );
            }
            /**
            * render post data for js
            */
            echo '<script type="data/json" class="testimonial_data" >'.json_encode($postdata).'</script>';
        }
    ?>
</div>
<!--// blog list  -->
<!-- pagination -->

<?php
    echo '<div class="paginations-wrapper col-md-12 mg-t-20">';
    ae_pagination($wp_query, get_query_var('paged'), 'load_more');
    echo '</div>';
?>
