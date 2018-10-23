<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object = $ae_post_factory->get( PROJECT );
$convert     = $project = $post_object->current_post;

$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );

// Load milestone change log if ae-milestone plugin is active
if ( defined( 'MILESTONE_DIR_URL' ) ) {
	$query_args = array(
		'type'       => 'message',
		'post_id'    => $post->ID,
		'paginate'   => 'load',
		'order'      => 'DESC',
		'orderby'    => 'date',
		'meta_query' => array(
			array(
				'key'     => 'fre_comment_file',
				'compare' => 'NOT EXISTS'
			)
		)
	);
} else {
	$query_args = array(
		'type'       => 'message',
		'post_id'    => $post->ID,
		'paginate'   => 'load',
		'order'      => 'DESC',
		'orderby'    => 'date',
		'meta_query' => array(
			array(
				'key'     => 'changelog',
				'value'   => '',
				'compare' => 'NOT EXISTS'
			),
			array(
				'key'     => 'fre_comment_file',
				'compare' => 'NOT EXISTS'
			)
		)
	);
}
$query_args['text'] = __( "Load older message", ET_DOMAIN );
echo '<script type="data/json"  id="workspace_query_args">' . json_encode( $query_args ) . '</script>';
/**
 * count all reivews
 */
$total_args = $query_args;
$all_cmts   = get_comments( $total_args );

/**
 * get page 1 reviews
 */
$query_args['number'] = 10000;//get_option('posts_per_page');
$comments             = get_comments( $query_args );

$total_messages      = count( $all_cmts );
$comment_pages       = ceil( $total_messages / $query_args['number'] );
$query_args['total'] = $comment_pages;

$messagedata    = array();
$message_object = Fre_Message::get_instance();
$bid_id         = get_post_meta( $post->ID, "accepted", true );
$lock_file      = get_post_meta( $post->ID, "lock_file", true );
$bid            = get_post( $bid_id );

foreach ( $comments as $key => $message ) {
	$convert       = $message_object->convert( $message );
	$messagedata[] = $convert;
	$author_name   = get_the_author_meta( 'display_name', $message->user_id );
	$isAttach      = $message->isAttach;

}

$args = array(
	'post_type'      => 'ae_milestone',
	'posts_per_page' => - 1,
	'post_status'    => 'any',
	'post_parent'    => $project->ID,
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_key'       => 'position_order'
);

$query = new WP_Query( $args );

?>

<style>
    .conversation-send-file-btn input {
        display: block;
    }
</style>

<div class="workspace-project-box">
    <ul class="nav nav-tabs nav-tabs-workspace hidden-lg hidden-md" role="tablist">
        <li class="active"><a href="#workspace-conversation" data-group="conversation" data-toggle="tab"
                              role="tab"><span><?php _e( 'Conversation', ET_DOMAIN ); ?></span></a></li>
		<?php if ( function_exists( 'ae_query_milestone' ) && $query->have_posts() ) { ?>
            <li class="next"><a href="#workspace-milestone" data-group="files" data-toggle="tab"
                                role="tab"><span><?php _e( 'Milestones', ET_DOMAIN ); ?></span></a></li>
            <li><a href="#workspace-files" data-group="files" data-toggle="tab"
                   role="tab"><span><?php _e( 'Project files', ET_DOMAIN ); ?></span></a>
            </li>
		<?php } else { ?>
            <li class="next"><a href="#workspace-files" data-group="files" data-toggle="tab"
                                role="tab"><span><?php _e( 'Project files', ET_DOMAIN ); ?></span></a>
            </li>
		<?php } ?>

    </ul>
    <div class="row">
        <div class="col-md-8">
            <div id="workspace-conversation"
                 class="project-workplace-details workplace-details workspace-conversation tab-pane fade in active">
                <h2 class="workspace-title"><?php _e( 'Conversation', ET_DOMAIN ); ?></h2>
                <div class="message-container">
                    <div class="list-chat-work-place-wrap fre-conversation-wrap fre-conversation">
                        <ul class="fre-conversation-list list-chat-work-place new-list-message-item upload_file_file_list">
							<?php
							$comments = array_reverse( $comments );
							if ( ! empty( $comments ) ) {
								foreach ( $comments as $key => $message ) {
									$convert       = $message_object->convert( $message );
									$messagedata[] = $convert;
									$author_name   = get_the_author_meta( 'display_name', $message->user_id );
									$isAttach      = $message->isAttach;
									$today         = date( $date_format );
									$yesterday     = date( $date_format, strtotime( "yesterday" ) );
									if ( $key == 0 ) {
										$message_date = date( $date_format, strtotime( $comments[ $key ]->comment_date ) );
										if ( $message_date === $today ) {
											echo '<li class="message-time" id="message-time-today">';
											_e( 'Today', ET_DOMAIN );
											echo '</li>';
										} else if ( $message_date === $yesterday ) {
											echo '<li class="message-time">';
											_e( 'Yesterday', ET_DOMAIN );
											echo '</li>';
										} else {
											echo '<li class="message-time">';
											echo $message_date;
											echo '</li>';
										}
									} else {
										$message_date        = date( $date_format, strtotime( $comments[ $key ]->comment_date ) );
										$message_date_before = date( $date_format, strtotime( $comments[ $key - 1 ]->comment_date ) );
										if ( $message_date != $message_date_before ) {
											if ( $message_date === $today ) {
												echo '<li class="message-time" id="message-time-today">';
												_e( 'Today', ET_DOMAIN );
												echo '</li>';
											} else if ( $message_date === $yesterday ) {
												echo '<li class="message-time">';
												_e( 'Yesterday', ET_DOMAIN );
												echo '</li>';
											} else {
												echo '<li class="message-time">';
												echo $message_date;
												echo '</li>';
											}
										}
									}
									if ( ! $message->isFile ) {
										if ( $message->changed_milestone_id != '' ) {
											echo '<li class="milestone-item-noti">' . get_the_author_meta( 'display_name', $message->user_id ) . ' ' . $convert->comment_content . '</li>';
										} else {
											?>
                                            <li class="<?php echo $message->user_id == $user_ID ? '' : 'partner-message' ?>"
                                                id="comment-<?php echo $message->comment_ID; ?>">
                                                <span class="message-avatar"><?php echo $message->avatar; ?></span>
												<?php if ( $isAttach ) {
													$file_type = wp_check_filetype( get_attached_file( $message->attachId ) );
													?>
                                                    <div class="message-item message-item-file">
                                                        <p>
                                                            <a href="<?php echo wp_get_attachment_url( $message->attachId ); ?>"
                                                               download>
																<?php
																if ( $convert->file_type == 'png' || $convert->file_type == 'jpg' || $convert->file_type == 'jpeg' || $convert->file_type == 'gif' ) {
																	echo '<i class="fa fa-file-image-o"></i>';
																} else {
																	echo '<i class="fa fa-file-text-o"></i>';
																}
																?>
                                                                <span><?php echo $convert->file_name; ?></span>
                                                                <span><?php echo $convert->file_size; ?></span>
                                                            </a>
                                                        </p>
                                                    </div>
												<?php } else { ?>
                                                    <div class="message-item">
														<?php echo $convert->comment_content; ?>
                                                    </div>
												<?php } ?>
                                            </li>
										<?php }
									}
								}
							} else {
								echo '<li class="message-none">' . __( 'No messages were received during the working process', ET_DOMAIN ) . '</li>';
							} ?>
                        </ul>
                    </div>
                    <div class="conversation-typing-wrap">
						<?php if ( $post->post_status == 'close' && ( $user_ID == $post->post_author || $user_ID == $bid->post_author ) ) { ?>
                            <form class="fre-workspace-form">
                                <div class="conversation-typing">
									<textarea name="comment_content" class="content-chat"
                                              placeholder="<?php _e( 'Your message here...', ET_DOMAIN ); ?>"></textarea>
                                    <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>"/>
                                </div>
                                <div class="conversation-submit-btn">
                                    <label class="conversation-send-file-btn" for="conversation-send-file">
                                        <div id="upload_file_container">
                                        <span class="et_ajaxnonce"
                                              id="<?php echo wp_create_nonce( 'file_et_uploader' ) ?>"></span>
                                            <span class="project_id" data-project="<?php echo $post->ID ?>"></span>
                                            <span class="author_id" data-author="<?php echo $user_ID ?>"></span>
                                            <a href="#" class="attack attach-file"
                                               id="upload_file_browse_button"><i class="fa fa-paperclip"
                                                                                 aria-hidden="true"></i></a>
                                        </div>
                                    </label>

                                    <label class="conversation-send-message-btn disabled"
                                           for="conversation-send-message">
                                        <input id="conversation-send-message" type="submit">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </form>
						<?php } ?>
                        <script type="application/json"
                                class="ae_query"><?php echo json_encode( $query_args ); ?></script>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="workspace-files-wrap">

				<?php
				if ( function_exists( 'ae_query_milestone' ) && $query->have_posts() ) { ?>
                    <div id="workspace-milestone" class="workspace-milestone tab-pane fade">
                        <h2 class="workspace-title"><?php echo __( "Project milestones", ET_DOMAIN ); ?></h2>
						<?php do_action( 'after_sidebar_single_project_workspace', $post ); ?>
                    </div>
				<?php } ?>

                <div id="workspace-files" class="workspace-files tab-pane fade workplace-project-details">
                    <div class="content-require-project content-require-project-attachment active">
                        <h2 class="workspace-title"><?php _e( 'Project Files', ET_DOMAIN ); ?></h2>
						<?php

						// Attachment file in workspace
						$attachment_comments = get_comments( array(
							'post_id'    => $post->ID,
							'meta_query' => array(
								array(
									'key'     => 'fre_comment_file',
									'value'   => '',
									'compare' => '!='
								)
							)
						) );
						$attachments         = array();
						foreach ( $attachment_comments as $key => $value ) {
							$file_arr = get_comment_meta( $value->comment_ID, 'fre_comment_file', true );
							if ( is_array( $file_arr ) ) {
								$attachment  = get_posts( array(
									'post_type' => 'attachment',
									'post__in'  => $file_arr
								) );
								$attachments = wp_parse_args( $attachments, $attachment );
							}
						}
						$attachments = array_reverse( $attachments );
						$lock_class  = '';
						if ( empty( $attachments ) ) {
							$lock_class = 'lock-btn-disabled';
						}

						if ( $post->post_status == 'close' && ( fre_share_role() || ae_user_role() == FREELANCER ) ) {
							if ( $lock_file != 'lock' ) { ?>
                                <div class="workplace-title-arrow file-container" id="file-container"
                                     style="font-size: 0;">
                                    <div id="apply_docs_container">
                                    <span class="et_ajaxnonce"
                                          id="<?php echo wp_create_nonce( 'file_et_uploader' ) ?>"></span>
                                        <span class="project_id" data-project="<?php echo $post->ID ?>"></span>
                                        <span class="author_id" data-author="<?php echo $user_ID ?>"></span>
                                        <a href="#" class="workspace-add-files attack attach-file"
                                           id="apply_docs_browse_button"><i
                                                    class="fa fa-plus"></i><span><?php _e( 'Add file', ET_DOMAIN ); ?></span></a>
                                    </div>
                                </div>
							<?php }
						} else if ( $post->post_status == 'close' && ( fre_share_role() || ae_user_role() == EMPLOYER ) ) {
							echo '<div class="lock-btn-wrapper">';
							if ( $lock_file == 'lock' ) {
								echo '<a href="#" class="lock-file-upload-btn" data-action="unlock" data-project-id="' . $post->ID . '"><i class="fa fa-unlock"></i>' . __( 'Unlock files', ET_DOMAIN ) . '</a>';
							} else {
								echo '<a href="#" class="lock-file-upload-btn ' . $lock_class . '" data-action="lock" data-project-id="' . $post->ID . '"><i class="fa fa-lock"></i>' . __( 'Lock file', ET_DOMAIN ) . '</a>';
							}
							echo '</div>';
						}
						?>
                        <ul class="workspace-files-list" id="workspace_files_list">
							<?php

							if ( ! empty( $attachments ) ) {
								foreach ( $attachments as $key => $value ) {
									$comment_file_id = get_post_meta( $value->ID, 'comment_file_id', true ); ?>
                                    <li class="attachment-<?php echo $value->ID; ?>">
                                        <p><?php echo $value->post_title ?>
                                            <span>
							            <?php
							            if ( $post->post_status == 'close' && $value->post_author == $user_ID && ! $value->post_parent && ( fre_share_role() || ae_user_role() == FREELANCER ) ) {
								            echo '<a href="' . $value->guid . '" target="_blank"><i class="fa fa-cloud-download" aria-hidden="true"></i></a>';
								            if ( $lock_file != 'lock' ) {
									            echo '<a href="#" data-post-id="' . $value->ID . '" data-project-id="' . $post->ID . '" data-file-name="' . $value->post_title . '" class="delete-attach-file"><i class="fa fa-times" aria-hidden="true" data-post-id="' . $value->ID . '" data-project-id="' . $post->ID . '" data-file-name="' . $value->post_title . '"></i></a>';
								            }
							            } else {
								            echo '<a href="' . $value->guid . '" target="_blank"><i class="fa fa-cloud-download" aria-hidden="true"></i></a>';
							            }
							            ?>
                                    </span>
                                        </p>
                                        <span><?php echo get_the_date( 'F j, Y g:i A', $value->ID ); ?></span>
                                    </li>
								<?php }
							} else {
								_e( '<li class="no_file_upload"><i>No files have been uploaded.</i></li>', ET_DOMAIN );
							} ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>