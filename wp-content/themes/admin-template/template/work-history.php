<?php
/**
 * Template part for employer posted project block
 # this template is loaded in page-profile.php , author.php
 * @since 1.0
 * @package FreelanceEngine
 */
if(is_page_template('page-profile.php')) {
    $status = array(
        'close'     => __("PROCESSING", ET_DOMAIN),
        'complete'  => __("COMPLETED", ET_DOMAIN),
        'disputing' => __("DISPUTED" , ET_DOMAIN ),
        'disputed'  => __("RESOLVED" , ET_DOMAIN ),
        'publish'   => __("ACTIVE", ET_DOMAIN),
        'pending'   => __("PENDING", ET_DOMAIN),
        'draft'     => __("DRAFT", ET_DOMAIN),
        'reject'    => __("REJECTED", ET_DOMAIN),
        'archive'   => __("ARCHIVED", ET_DOMAIN),
    );
}else {
    $status = array(
        'publish'   => __("ACTIVE", ET_DOMAIN),
        'complete'  => __("COMPLETED", ET_DOMAIN)
    );
}


?>
<div class="profile-history project-history no-margin-top">
<?php
$is_author = is_author();
$is_page_profile = is_page_template('page-profile.php');
$author_id = get_query_var('author');
$stat = array('publish','complete');
$query_args = array('is_author' => true,
                    'post_status' => $stat,
                    'post_type' => PROJECT,
                    'author' => $author_id,
                    'order' => 'DESC',
                    'orderby' => 'date');

if(is_page_template('page-profile.php')) {
    global $user_ID;
    $author_id = $user_ID;
    $stat = array('pending','publish','close', 'complete', 'disputing', 'disputed', 'reject', 'archive', 'draft');
    $query_args = array('is_profile' => $is_page_profile,
                    'post_status' => $stat,
                    'post_type' => PROJECT,
                    'author' => $author_id,
                    'order' => 'DESC',
                    'orderby' => 'date');
}
// filter order post by status
add_filter('posts_orderby', 'fre_order_by_project_status');
query_posts( $query_args);
// remove filter order post by status
$bid_posts   = $wp_query->found_posts;
$display_name = get_the_author_meta( 'display_name', $author_id );
?>
<div class="info-project-items-employer">
    <div class="inner">
        <h4 class="title-big-info-project-items">
            <?php
            if(fre_share_role() ) {
                printf(__("Posted projects (%d)", ET_DOMAIN), $wp_query->found_posts);
            }else{
                printf(__("Your Projects (%d)", ET_DOMAIN), $wp_query->found_posts);
            }
            ?>
        </h4>
        <?php if(have_posts()):?>
        <div class="filter-project filter-project-employer">
            <div class="filter-area">
                <select class="status-filter chosen-select" name="post_status" data-chosen-width="100%" data-chosen-disable-search="1"
                        data-placeholder="<?php _e("Filter by project's status", ET_DOMAIN); ?>">
                    <option value=""><?php _e("Filter by project's status", ET_DOMAIN); ?></option>
                    <?php foreach ($status as $key => $stat) {
                        echo '<option value="'.$key.'">'.$stat.'</option>' ;
                    }  ?>
                </select>
            </div>
        </div>
        <?php endif; ?>
        <!--
        <div class="project-status-filter">
            <a class="show-bidden click-type bid-show" data-name='bidden' data-type='show'><?php _e('View projects being bidden', ET_DOMAIN)?></a>
            <a class="show-bidden click-type bid-hide" data-name='bidden' data-type='hide' style="display:none;"><?php _e('View all projects', ET_DOMAIN)?></a>
        </div>-->
        <div class="clearfix"></div>
    </div>
    <?php
        // list portfolio
        if(have_posts()):
            get_template_part( 'template/work', 'history-list' );
            global $wp_query;
            // $wp_query->query = array_merge(  $wp_query->query ,array('is_author' => $is_author)) ;
            echo '<div class="paginations-wrapper">';
            ae_pagination($wp_query, get_query_var('paged'), 'page');
            echo '</div>';
        else :
            $link_submitProject = et_get_page_link('submit-project');
            echo '<ul style="list-style:none;padding:0;"><li><div class="no-results">'.__('<p>You have not created any projects yet.</p><p>It is time to start creating ones.</p>', ET_DOMAIN).'<div class="add-project"><a href="'.$link_submitProject.'" class="fre-normal-btn">'.__('Post a project', ET_DOMAIN).'</a></div></div></li></ul>';
        endif;
        //wp_reset_postdata();
     ?>

    </div>
</div>
<?php
wp_reset_query();
remove_filter('posts_orderby', 'fre_order_by_project_status');