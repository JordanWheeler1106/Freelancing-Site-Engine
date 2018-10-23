<?php
/**
 * count user posts
 * @since 1.1
 * @author Thái NT
 */
function fre_count_user_posts( $userid, $post_type = 'post' ) {
	global $wpdb;

	$where = get_posts_by_author_sql( $post_type, true, $userid );

	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

  	return apply_filters( 'get_usernumposts', $count, $userid );
}
/**
 * render latest page in single page
 * @since 1.0
 * @author Thái NT
 */
function fre_latest_pages($id){
	$query = new WP_Query(array(
		'post_type'    => 'page',
		'showposts'    => 3,
		'post__not_in' => array($id)
		));
	if($query->have_posts()){
		echo '<ul>';
		while ($query->have_posts()) {
			$query->the_post();
			?>
			<li>
				<a href="<?php the_permalink() ?>">
					<?php the_title();?>
				</a>
			</li>
			<?php
		}
		echo '</ul>';
	}
	wp_reset_query();
}
if(!function_exists('fre_profile_button')) {
	/**
	 * render profile button associate with user login
	 * @since 1.0
	 * @author Dakachi
	 */
	function fre_profile_button(){
		global $user_ID;
		if(!fre_check_register()) return;
		/* user have not logged in */
		if(!$user_ID) { ?>
			<a href="<?php echo et_get_page_link('auth'); ?>" class="btn btn-sumary btn-post-profile">
		        <i class="fa fa-plus-circle"></i><?php _e("Create a Profile", ET_DOMAIN); ?>
		    </a>
		<?php
			return ;
		}
		// current user is a freelancer
		if( fre_share_role() || ae_user_role() == FREELANCER ) {
		?>
			<a href="<?php echo et_get_page_link( array('page_type' => 'profile', 'post_title' => __("Profile", ET_DOMAIN )) ); ?>" class="btn btn-sumary btn-post-profile">
	            <i class="fa fa-plus-circle"></i><?php _e("Review your Profile", ET_DOMAIN); ?>
	        </a>
		<?php
			return;
		}

		// current user is an employer
		?>
			<a href="<?php echo get_post_type_archive_link(PROFILE) ?>" class="btn btn-sumary btn-post-profile">
	            <i class="fa fa-plus-circle"></i><?php _e("Find a Freelancer", ET_DOMAIN); ?>
	        </a>
		<?php

	}
}


if(!function_exists('fre_project_button')) {
	/**
	 * render project button associate with user login
	 * @since 1.0
	 * @author Dakachi
	 */
	function fre_project_button($echo = false){
		global $user_ID;
		// if(!fre_check_register()) return;
		/* user have not logged in */
		if(!$user_ID) { ?>
			<a href="<?php echo et_get_page_link('submit-project'); ?>" class="btn btn-sumary btn-post-project">
		        <i class="fa fa-plus-circle"></i><?php _e("Post a Project", ET_DOMAIN); ?>
		    </a>
		<?php
			return ;
		}
		// current user is a freelancer
		if( ae_user_role() != FREELANCER ) {
		?>
			<a href="<?php echo et_get_page_link( array('page_type' => 'submit-project', 'post_title' => __("Post a Project", ET_DOMAIN )) ); ?>" class="btn btn-sumary btn-post-project">
				<i class="fa fa-plus-circle"></i><?php _e("Post a Project", ET_DOMAIN); ?>
			</a>
		<?php
			return '';
		}

		// current user is an employer
		?>
			<a href="<?php echo get_post_type_archive_link(PROJECT) ?>" class="btn btn-sumary btn-post-project"><i class="fa fa-plus-circle"></i>
	            <?php _e("Find a Project", ET_DOMAIN); ?>
	        </a>
		<?php
	}

}

function ae_edit_post_button ($post) {
	if($post->post_status == 'pending'){ ?>
        <a title="<?php _e("Edit", ET_DOMAIN); ?>" target="_Blank" href="<?php echo et_get_page_link('edit-project', array('id' => $post->ID)) ?>"><i class="fa fa-pencil"></i><?php _e("Edit", ET_DOMAIN); ?></a>
    <?php } ?>

    <?php if($post->post_status == 'publish'){ ?>
        <a title="<?php _e("Edit", ET_DOMAIN); ?>" target="_Blank" href="<?php echo et_get_page_link('edit-project', array('id' => $post->ID)) ?>"><i class="fa fa-pencil"></i><?php _e("Edit", ET_DOMAIN); ?></a>
        <a data-action="archive" class="action archive" href="#"><i class="fa fa-trash-o"></i><?php _e("Archive", ET_DOMAIN); ?></a>
    <?php } ?>
    <?php if($post->post_status == 'draft'){ ?>
        <a title="<?php _e("Edit", ET_DOMAIN); ?>" data-target="#" class="" target="_Blank" href="<?php echo et_get_page_link('submit-project', array('id' => $post->ID)) ?>">
            <i class="fa fa-pencil"></i><?php _e("Edit", ET_DOMAIN); ?>
        </a>
        <a title="<?php _e("Delete", ET_DOMAIN); ?>" data-action="delete" class="action archive" href="#">
            <i class="fa fa-times"></i><?php _e("Delete", ET_DOMAIN); ?>
        </a>
    <?php } ?>
    <?php if($post->post_status == 'archive'){ ?>
        <a title="<?php _e("Renew", ET_DOMAIN); ?>" data-target="#" class="" target="_Blank" href="<?php echo et_get_page_link('submit-project', array('id' => $post->ID)) ?>">
            <i class="fa fa-refresh"></i><?php _e("Renew", ET_DOMAIN); ?>
        </a>
        <a title="<?php _e("Delete", ET_DOMAIN); ?>" data-action="delete" class="action archive" href="#">
            <i class="fa fa-times"></i><?php _e("Delete", ET_DOMAIN); ?>
        </a>
    <?php } ?>
    <?php if($post->post_status == 'reject'){ ?>
       <a title="<?php _e('Edit', ET_DOMAIN);?>" target="_Blank" href="<?php echo et_get_page_link('edit-project', array('id' => $post->ID)) ?>">
            <i class="fa fa-pencil"></i><?php _e("Edit", ET_DOMAIN); ?>
        </a>
    <?php } ?>
    <?php if($post->post_status == 'complete' && !empty($post->project_comment) && $post->rating_score != 0){ ?>
    	<a title="<?php _e('Rating & Review', ET_DOMAIN);?>" class="review" data-target="#" href="#">
    		<i class="fa fa-eye" aria-hidden="true"></i> <?php _e('Rating & Review', ET_DOMAIN);?>
    	</a>
    <?php }
    do_action( 'ae_edit_post_button', $post );
}

function ae_js_edit_post_button () {
?>
	<# if(post_status == 'pending'){ #>
        <a href="<?php echo et_get_page_link('edit-project') ?>?id={{= ID }}" target="_Blank" title="<?php _e('Edit', ET_DOMAIN);?>"><i class="fa fa-pencil"></i><?php _e('Edit', ET_DOMAIN);?></a>
    <# } #>

    <# if(post_status == 'publish'){ #>
        <a href="<?php echo et_get_page_link('edit-project') ?>?id={{= ID }}" target="_Blank" title="<?php _e('Edit', ET_DOMAIN);?>"><i class="fa fa-pencil"></i><?php _e('Edit', ET_DOMAIN);?></a>
        <a data-action="archive" class="action archive" href="#" title="<?php _e('Archive', ET_DOMAIN);?>"><i class="fa fa-trash-o"></i><?php _e('Archive', ET_DOMAIN);?></a>
    <# } #>
    <# if(post_status == 'draft'){ #>
        <a title="<?php _e("Edit", ET_DOMAIN); ?>" data-target="#" class="" target="_Blank" href="<?php echo et_get_page_link('submit-project') ?>?id={{= ID }}">
            <i class="fa fa-pencil"></i><?php _e('Edit', ET_DOMAIN);?>
        </a>
        <a data-action="delete" class="action archive" href="#"  title="<?php _e('Delete', ET_DOMAIN);?>">
            <i class="fa fa-times"></i><?php _e('Delete', ET_DOMAIN);?>
        </a>
    <# } #>
    <# if(post_status == 'archive'){ #>
        <a title="<?php _e("Renew", ET_DOMAIN); ?>" data-target="#" class="" target="_Blank" href="<?php echo et_get_page_link('submit-project') ?>?id={{= ID }}">
            <i class="fa fa-refresh"></i><?php _e("Renew", ET_DOMAIN); ?>
        </a>
        <a data-action="delete" class="action archive" href="#"  title="<?php _e('Delete', ET_DOMAIN);?>">
            <i class="fa fa-times"></i><?php _e('Delete', ET_DOMAIN);?>
        </a>
    <# } #>
    <# if(post_status == 'reject'){ #>
    	<a title="<?php _e('Edit', ET_DOMAIN);?>" href="<?php echo et_get_page_link('edit-project') ?>?id={{= ID }}" target="_Blank"><i class="fa fa-pencil"></i><?php _e('Edit', ET_DOMAIN);?></a>
    <# } #>
    <# if(post_status == 'complete' && project_comment != '' && rating_score != 0){ #>
    	<a title="<?php _e('Rating & Review', ET_DOMAIN);?>" class="review" data-target="#" href="#">
    		<i class="fa fa-eye" aria-hidden="true"></i> <?php _e('Rating & Review', ET_DOMAIN);?>
    	</a>
    <# } #>
    <?php
}