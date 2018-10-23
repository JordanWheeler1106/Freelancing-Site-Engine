<?php
/**
 * Template part for employer posted project block
 # this template is loaded in page-profile.php , author.php
 * @since 1.0
 * @package FreelanceEngine
 */
if(is_page_template('page-profile.php')) {
    $status = array(
        'reject'  => __("REJECTED", ET_DOMAIN) ,
        'pending' => __("PENDING", ET_DOMAIN) ,
        'publish' => __("ACTIVE", ET_DOMAIN),
        'close' => __("HIRED", ET_DOMAIN),
        'complete' => __("COMPLETED", ET_DOMAIN),
        'draft'   => __("DRAFT", ET_DOMAIN),
        'archive' => __("ARCHIVED", ET_DOMAIN),
        'disputing' => __( "DISPUTING" , ET_DOMAIN ),
        'disputed' => __( "DISPUTED" , ET_DOMAIN )
    );
}else {
    $status = array(
        'publish' => __("ACTIVE", ET_DOMAIN),
        'complete' => __("COMPLETED", ET_DOMAIN)
    );
}


?>
<div class="profile-history project-history">
<?php
$author_id = get_query_var('author');
$stat = array('publish','close', 'complete');
if(is_page_template('page-profile.php')) {
    global $user_ID;
    $author_id = $user_ID;
    $stat = array('pending','publish','close', 'complete', 'disputing', 'disputed');
}
$is_author = is_author();
$is_page_profile = is_page_template('page-profile.php');

// filter order post by status
add_filter('posts_orderby', 'fre_order_by_project_status');
query_posts( array( 'is_profile' => $is_page_profile,
                    'post_status' => $stat,
                    'post_type' => PROJECT,
                    'author' => $author_id )
                );
// remove filter order post by status
$bid_posts   = $wp_query->found_posts;
$display_name = get_the_author_meta( 'display_name', $author_id );

?>
    <div class="work-history-heading">
        <h4 class="title-big-info-work-history-items">
            <?php
            if(fre_share_role() ) {
                printf(__("Posted projects (%d)", ET_DOMAIN), $wp_query->found_posts);
            }else{
                printf(__("Work history and Reviews (%d)", ET_DOMAIN), $wp_query->found_posts);
            }
            ?>
        </h4>
        <div class="project-status-filter" >
            <select class="status-filter chosen-select" name="post_status" data-chosen-width="100%" data-chosen-disable-search="1"
                data-placeholder="<?php _e("Select a status", ET_DOMAIN); ?>">
                <option value=""><?php _e("Select a status", ET_DOMAIN); ?></option>
                <?php foreach ($status as $key => $stat) {
                    echo '<option value="'.$key.'">'.$stat.'</option>' ;
                }  ?>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php
        // list portfolio
        if(have_posts()):
            get_template_part( 'template/work', 'history-list' );
            global $wp_query;
            $wp_query->query = array_merge(  $wp_query->query ,array('is_author' => $is_author)) ;
            echo '<div class="paginations-wrapper">';
            ae_pagination($wp_query, get_query_var('paged'), 'page');
            echo '</div>';
        else :
            echo '<ul style="list-style:none;padding:0;"><li><span class="no-results">'.__('You have not posted any projects yet.', ET_DOMAIN).'</span></li></ul>';
        endif;
        //wp_reset_postdata();
     ?>

</div>
<?php
wp_reset_query();
remove_filter('posts_orderby', 'fre_order_by_project_status');