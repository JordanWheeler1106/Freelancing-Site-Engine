<?php

global $wp_query, $ae_post_factory, $post;
$post_object    = $ae_post_factory->get( PROJECT );
$current        = $post_object->current_post;
?>
<li <?php post_class( 'project-item hvr-shadow' ); ?>>
	<div class="row">
    	<div class="col-md-5 col-sm-5 col-xs-7 text-ellipsis pd-r-30">
            <p style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <a href="<?php echo get_author_posts_url( $current->post_author ); ?>"  class="title-project">
                    <?php echo get_avatar( $post->post_author, 35 ); ?>
                </a>
                <a href="<?php the_permalink();?>" title="<?php the_title(); ?>" class="project-item-title">
                    <?php the_title(); ?>
                </a>
            </p>
        	

        </div>
        <div class="col-md-2 col-sm-3 hidden-xs">
            <?php /*
            if($current->et_featured) { ?>
                <span class="ribbon"><i class="fa fa-star"></i></span>
            <?php } */?>
            <span>
                <?php  the_author_posts_link(); ?>
            </span>
        </div>
        <div class="col-md-2 col-sm-2 hidden-sm hidden-xs">
             <span>
                <?php echo get_the_date() ?>
            </span>
        </div>

        <div class="col-md-1 col-sm-2 col-xs-4 hidden-xs">
            <span class="budget-project">
                <?php echo fre_price_format($current->et_budget);?>
            </span>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-5">
            <?php
            if( $current->current_user_bid ){ ?>
            <span class="wrapper-btn">
                <a href="<?php the_permalink();?>" class="bid-label" >
                    <i class="fa fa-check"></i><?php _e(' Bid',ET_DOMAIN);?>
                </a>
            </span>
            <?php }
            else{
            ?>
            <p class="wrapper-btn">
                <a href="<?php the_permalink();?>" class="btn-sumary btn-apply-project">
                    <?php _e('Apply',ET_DOMAIN);?>
                </a>
            </p>
            <?php  } ?>
        </div>
    </div>
</li>