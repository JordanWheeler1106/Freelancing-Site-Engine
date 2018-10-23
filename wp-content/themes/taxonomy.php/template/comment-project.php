<?php
global $user_ID, $current_user, $user_identit;
if ( ! $user_ID ) {
	$total_count = get_comments( array(
		'post_id' => $post->ID,
		'type'    => 'comment',
		'count'   => true,
		'status'  => 'approve'
	) );
	$comments    = get_comments( array( 'type' => 'comment', 'post_id' => $post->ID, 'status' => 'approve' ) );
} else {
	$total_count = get_comments( array(
		'post_id' => $post->ID,
		'type'    => 'comment',
		'count'   => true,
		'status'  => 'approve'
	) );
	$comments    = get_comments( array( 'type' => 'comment', 'post_id' => $post->ID, 'status' => 'all' ) );
}
?>
    <div id="comments" class="project-comments comments-area et-comments-area">

		<?php if ( have_comments() && $total_count > 0 ) : ?>

            <h3 class="title et-comments-title">
				<?php
				if ( $total_count == 0 ) {
					_e( "0 Comments", ET_DOMAIN );
				} else if ( $total_count == 1 ) {
					printf( __( "%d Comment", ET_DOMAIN ), intval( $total_count ) );
				} else {
					printf( __( '%d Comments', ET_DOMAIN ), $total_count );
				}
				?>
            </h3>
            <ol class="comment-list">
				<?php
				wp_list_comments( array(
					'style'      => 'ul',
					'short_ping' => true,
					'callback'   => 'fre_project_comment_callback',
				), $comments );
				?>
            </ol><!-- .comment-list -->

			<?php if ( get_comment_pages_count( $comments ) > 1 && get_option( 'page_comments' ) ) : ?>
                <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                    <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', ET_DOMAIN ) ); ?></div>
                    <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', ET_DOMAIN ) ); ?></div>
                </nav><!-- #comment-nav-below -->
			<?php endif; // Check for comment navigation. ?>

		<?php endif; ?>

		<?php if ( ! comments_open() ) : ?>
            <p class="no-comments et-comments-title">
				<?php _e( 'Comments are closed.', ET_DOMAIN ); ?>
            </p>
		<?php endif; ?>

		<?php if ( ! in_array( $post->post_status, array( 'disputed', 'close', 'complete', 'archive' ) ) ): ?>
            <div class="btm-comment-form">
				<?php
				$loginURL = et_get_page_link( 'login', array( 'ae_redirect_url' => get_permalink( $post->ID ) ) );
				comment_form( array(
					'comment_field'        => ' <div class="form-item"><label for="comment">' . __( 'Your Comment', ET_DOMAIN ) . '</label><div class="input"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" required></textarea></div></div>',
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'id_form'              => 'commentform',
					'id_submit'            => 'submit',
					'title_reply'          => __( "Comment", ET_DOMAIN ),
					'title_reply_to'       => __( 'Leave a Reply to %s', ET_DOMAIN ),
					'cancel_reply_link'    => __( 'Cancel reply', ET_DOMAIN ),
					'label_submit'         => __( 'Comment', ET_DOMAIN ),
					'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s" class="">logged in</a> to post a comment.', ET_DOMAIN ), $loginURL ) . '</p>',
					'logged_in_as'         => '<p class="logged-in-as">' .
					                          sprintf( __( 'Logged in as %1$s. <a href="%2$s" title="Log out of this account">Log out?</a>', ET_DOMAIN ),
						                          $current_user->display_name,
						                          wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) )
					                          ) .
					                          '</p>',
				) );
				?>
            </div>
		<?php endif; ?>
    </div><!-- #comments -->
<?php
function fre_project_comment_callback( $comment, $args, $depth ) {
	global $user_ID, $current_user;
	$GLOBALS['comment'] = $comment;
	$status             = wp_get_comment_status( $comment->comment_ID );
	if ( $status == 'unapproved' ) {
		if ( ! is_super_admin() && $current_user->data->user_login != $comment->comment_author ) {
			return;
		}
	}
	?>
    <li class="media et-comment" id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>">
            <a class="pull-left avatar-comment" href="#">
				<?php echo get_avatar( $comment->comment_author_email, 40 ); ?>
            </a>
            <div class="media-body">
                <h4 class="media-heading">
					<?php
					comment_author();
					if ( $status == 'unapproved' ) {
						?>
                        <span class="fre-comment-label">
		                	<?php _e( 'The comment is awaiting moderation.', ET_DOMAIN ); ?>
		                </span>
					<?php } ?>
                </h4>
                <div class="comment-text">
					<?php comment_text(); ?>
                </div>
				<?php
				comment_reply_link( array_merge( $args, array(
					'reply_text' => __( 'Reply ', ET_DOMAIN ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				) ) );
				?>
            </div>
        </div>
    </li>
	<?php
}