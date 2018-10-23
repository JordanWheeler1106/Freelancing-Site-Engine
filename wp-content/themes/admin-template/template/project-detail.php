<?php
/**
 * The template for displaying project detail heading, author info and action button
 */
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object    = $ae_post_factory->get(PROJECT);
$convert = $project = $post_object->convert($post);

$et_expired_date    = $convert->et_expired_date;
$bid_accepted       = $convert->accepted;
$project_status     = $convert->post_status;
$profile_id         = get_user_meta($post->post_author,'user_profile_id', true);

$currency           = ae_get_option('currency',array('align' => 'left', 'code' => 'USD', 'icon' => '$'));
?>
<input type="hidden" id="project_id" name="<?php echo $post->ID;?>" value="<?php echo $post->ID;?>" />
<div class="col-md-12">
	<div class="tab-content-project">
    	<!-- Title -->
    	<div class="row title-tab-project">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <span><?php _e("PROJECT TITLE", ET_DOMAIN); ?></span>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-2 hidden-xs">
                <span><?php _e("BY", ET_DOMAIN); ?></span>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-3 hidden-sm hidden-xs">
                <span><?php _e("POSTED DATE", ET_DOMAIN); ?></span>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">
                <span><?php _e("BUDGET", ET_DOMAIN); ?></span>
            </div>
        </div>
        <!-- Title / End -->
        <!-- Content project -->
        <div class="single-projects">
            <div class="project type-project project-item">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <a href="<?php echo get_author_posts_url( $post->post_author ); ?>" class="avatar-author-project-item">
                            <?php echo get_avatar( $post->post_author, 35,true, get_the_title($profile_id) ); ?>
                        </a>
                        <h1 class="content-title-project-item"><?php the_title();?></h1>
                    </div>
                     <div class="col-lg-2 col-md-3 col-sm-2 hidden-xs">
                      	<span class="author-link-project-item"><?php the_author_posts_link();?></span>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidden-sm">
                         <span  class="time-post-project-item"><span class="visible-xs-inline"><?php _e("POSTED DATE:", ET_DOMAIN); ?></span><?php the_date(); ?></span>
                    </div>
                    <div class="col-lg-2 col-md-1 col-sm-2 col-xs-12">
                        <span class="budget-project-item"><span class="visible-xs-inline"><?php _e("BUDGET:", ET_DOMAIN); ?></span><?php echo $convert->budget; ?></span>
                    </div>
                    <div class="col-lg-2 col-md-2 text-right col-sm-4 col-xs-12 btn-fre-bid" style="padding:0; margin:0;">
                        
                    <?php
                    if(current_user_can( 'manage_options' ) && $project_status != 'close') {
                        get_template_part( 'template/admin', 'project-control' );
                    }elseif( !$user_ID && $project_status == 'publish'){ ?>
                        <a href="<?php echo et_get_page_link('login', array('ae_redirect_url'=> $project->permalink));?>"  class="btn-apply-project-item" ><?php  _e('Bid',ET_DOMAIN);?></a>
                    <?php } else {
                        $role = ae_user_role();
                        switch ($project_status) {
                            case 'publish':
                                if( ( fre_share_role() || $role == FREELANCER ) && $user_ID != $project->post_author ){
                                    $has_bid = fre_has_bid( get_the_ID() );
                                    if( $has_bid ) {
                                        ?>
                                        <a rel="<?php echo $project->ID;?>" href="#" id="<?php echo $has_bid;?>" title= "<?php _e('Cancel this bidding',ET_DOMAIN); ?>"  class="btn-del-project" >
                                            <?php  _e('Cancel',ET_DOMAIN);?>
                                        </a>
                                    <?php
                                    } else{
                                        // show button bid project
                                        fre_button_bid($project->ID);
                                    }
                                }
                                break;
                            case 'close':
                                if( (int)$project->post_author == $user_ID){ ?>
                                    <a title="<?php  _e('Finish',ET_DOMAIN);?>" href="#" id="<?php the_ID();?>"   class="btn-complete-project" >
                                        <?php  _e('Finish',ET_DOMAIN);?>
                                    </a>
                                <?php if(ae_get_option('use_escrow')){ ?>
                                    <a title="<?php _e('Close',ET_DOMAIN);?>" href="#" id="<?php the_ID();?>"   class="btn-close-project" >
                                        <?php _e('Close',ET_DOMAIN);?>
                                    </a>
                                    <?php
                                    }
                                }else{
                                    $bid_accepted_author = get_post_field( 'post_author', $bid_accepted);
                                    if($bid_accepted_author == $user_ID && ae_get_option('use_escrow')) {
                                ?>
                                    <a title="<?php  _e('Discontinue',ET_DOMAIN);?>" href="#" id="<?php the_ID();?>"   class="btn-quit-project" >
                                        <?php  _e('Discontinue',ET_DOMAIN);?>
                                    </a>
                                <?php }
                                }
                                break;
                            case 'complete' :
                                $freelan_id  = (int)get_post_field('post_author',$project->accepted);

                                $comment = get_comments( array('status'=> 'approve', 'type' => 'fre_review', 'post_id'=> get_the_ID() ) );

                                if( $user_ID == $freelan_id && empty( $comment ) ){ ?>
                                    <a href="#" id="<?php the_ID();?>"   class="btn-complete-project" ><?php  _e('Review & Rate',ET_DOMAIN);?></a>
                                    <?php
                                }
                                break;
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
                <?php
                if( ((Fre_ReportForm::AccessReport() && $post->post_status == 'disputing') || $post->post_status == 'disputed')
                        && !isset($_REQUEST['workspace'])
                    ) { 
                    
                        get_template_part('template/project-detail' , 'info');
                ?>
                    <div class="workplace-container">
                        <?php get_template_part('template/project', 'report') ?>
                    </div>
                <?php }else if( isset($_REQUEST['workspace']) && $_REQUEST['workspace'] ) { ?>
                    <div class="workplace-container">
                        <?php get_template_part('template/project', 'workspaces') ?>
                    </div>
                <?php }else {
                    get_template_part('template/project-detail' , 'info');
                    get_template_part('template/project-detail' , 'content');
                } ?>
            </div>
        <!-- Content project / End -->
        <div class="clearfix"></div>
    </div><!-- tab-content-project !-->
</div>  <!--col-md-12 !-->