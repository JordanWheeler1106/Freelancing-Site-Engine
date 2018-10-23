<?php
/**
 * Template part for freelancer bid history
 # this template is loaded in template/bid-history.php
 * @since 1.0
 * @package FreelanceEngine
 */
global $wp_query, $ae_post_factory;
$author_id = get_query_var('author');

$post_object = $ae_post_factory->get(BID);

?>
<div class="inner">
    <h4 class="title-big-info-project-items"><?php printf(__("Your Work History and Reviews (%d)", ET_DOMAIN), $wp_query->found_posts) ?></h4>
    <?php if(have_posts()):?>
    <div class="filter-project" >
        <select class="status-filter chosen-select" name="filter_work_history" data-chosen-width="100%" data-chosen-disable-search="1"
            data-placeholder="<?php _e("View all", ET_DOMAIN); ?>">
            <option value=""><?php _e("View all", ET_DOMAIN); ?></option>
            <option value="complete"><?php _e("Completed", ET_DOMAIN); ?></option>
            <option value="disputing"><?php _e("Disputed", ET_DOMAIN); ?></option>
            <option value="disputed"><?php _e("Resolved", ET_DOMAIN); ?></option>
        </select>
    </div>
    <?php endif;?>
</div>
<ul class="list-history-profile">
	<?php
	$postdata = array();
    if(have_posts()):
    	while (have_posts()) { the_post();
    	    $convert = $post_object->convert($post,'thumbnail');
    	    $postdata[] = $convert;
    	    get_template_part( 'template/bid-history', 'item' );
        }
    else:
        echo '<span class="profile-no-results">';
        _e('There are no activities yet.',ET_DOMAIN);
        echo '</span>';
    endif;
    ?>
</ul>
<?php
	/* render post data for js */
	echo '<script type="data/json" class="postdata" >'.json_encode($postdata).'</script>';
?>
<!-- pagination -->
<?php
	$wp_query->query = array_merge(  $wp_query->query  ) ;   
    echo '<div class="paginations-wrapper">';
    ae_pagination($wp_query, get_query_var('paged'), 'page');
    echo '</div>';
?>
