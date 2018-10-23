<?php
/**
 * Template part for user bid history block
 # This template is loaded in page-profile.php , author.php
 * @since v1.0
 * @package EngineTheme
 */

?>

<div class="profile-history bid-history">
<?php
    $author_id = get_query_var('author');
    if(is_page_template('page-profile.php')) {
        global $user_ID;
        $author_id = $user_ID;
    }

    add_filter('posts_orderby', 'fre_reset_order_by_project_status');
    query_posts( array(  'post_status' => array('complete','accept'), 'post_type' => BID, 'author' => $author_id, 'accepted' => 1  ));
    $bid_posts   = $wp_query->found_posts;
?>

<?php
    // list portfolio
    if(have_posts()):
        get_template_part( 'template/bid', 'history-list' );
    else :
    endif;
    remove_filter('posts_orderby', 'fre_reset_order_by_project_status');
?>
</div>