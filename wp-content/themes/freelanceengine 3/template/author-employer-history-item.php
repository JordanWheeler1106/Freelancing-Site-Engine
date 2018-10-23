<?php
/**
 * Template part for employer project details
 * # this template is loaded in template/list-work-history.php
 * @since 1.0
 * @package FreelanceEngine
 */
global $user_ID;
$author_id = get_query_var( 'author' );

global $wp_query, $ae_post_factory, $post;

$post_object = $ae_post_factory->get( PROJECT );
$current     = $post_object->current_post;

if ( ! $current ) {
	return;
}
?>
<li class="bid-item">
    <div class="fre-author-project">
        <h2 class="author-project-title"><a
                    href="<?php echo $current->permalink; ?>"><?php echo $current->post_title; ?></a></h2>
        <div class="author-project-info">
			<?php if( $current->post_status == 'complete'){ ?>
                <span class="rate-it" data-score="<?php echo $current->rating_score; ?>"></span>
			<?php } ?>
            <span class="budget"><?php echo $current->budget ?></span>
            <span class="posted"><?php echo $current->post_date ; ?></span>
        </div>
        <div class="author-project-comment">
			<?php
			switch ( $current->post_status ) {
				case 'publish':
					echo '<span class="stt-in-process">';
					_e( 'Project is currently available for bidding. ', ET_DOMAIN );
					echo '</span>';
					//printf( __( 'Budget: %s', ET_DOMAIN ), $current->budget );
					break;
				case 'close':
					echo '<span class="stt-in-process">';
					_e( 'Project is currently processing. ', ET_DOMAIN );
					echo '</span>';
					break;
				case 'complete':
				    if($current->rating_score){
					    echo '<span class="stt-complete">';
					    //_e( 'Project is already completed.', ET_DOMAIN );
					    echo $current->project_comment;
					    echo '</span>';
                    }else{
					    echo '<span class="stt-complete-pending"><i>';
					    _e( 'Project is complete without rating & reviewing from freelancer.', ET_DOMAIN );
					    echo '</i></span>';
                    }
                    break;
			}
			?>
        </div>
    </div>
</li>

