<?php
/**
 * The template for displaying project report list, form in single project
 */
global $post, $user_ID, $ae_post_factory;
$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );

$role  = ae_user_role();
$paged = ! empty( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$post_object         = $ae_post_factory->get( PROJECT );
$convert             = $project = $post_object->convert( $post );
$bid_accepted_author = get_post_field( 'post_author', $project->accepted );
$profile_id          = $post->post_author;
if ( ( fre_share_role() || $role != FREELANCER ) ) {
	$profile_id = $bid_accepted_author;
}

$query_args = array(
	'type'     => 'fre_report',
	'post_id'  => $post->ID,
	'paginate' => 'load',
	'order'    => 'DESC',
	'orderby'  => 'date'
);
/**
 * count all reivews
 */
$total_args = $query_args;
$all_cmts   = get_comments( $total_args );

/**
 * get page 1 reviews
 */
$query_args['number'] = 10;//get_option('posts_per_page');
$comments             = get_comments( $query_args );

$total_messages      = count( $all_cmts );
$comment_pages       = ceil( $total_messages / $query_args['number'] );
$query_args['total'] = $comment_pages;
$query_args['text']  = __( "......", ET_DOMAIN );

$messagedata    = array();
$message_object = new Fre_Report( 'fre_report' );

$freelancer_info = get_userdata( $bid_accepted_author );
$ae_users        = AE_Users::get_instance();
$freelancer_data = $ae_users->convert( $freelancer_info->data );

if ( $project->post_status == "disputing" or $post->post_status == 'disputed' ) {
	?>

    <div class="project-dispute-box">
        <div class="project-detail-info">
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <h1 class="project-detail-title"><a
                                href="<?php echo $project->permalink ?>"><?php the_title(); ?></a></h1>
                    <ul class="project-disputed-info-list">
						<?php if ( current_user_can( 'manage_options' ) ) { ?>
                            <li>
                                <span><?php _e( 'Employer', ET_DOMAIN ); ?></span>
                                <a href="<?php echo $convert->author_url; ?>" target="_blank"><span><?php echo $convert->author_name; ?></span></a>
                            </li>
						<?php } ?>
                        <li>
							<?php
							if ( ( fre_share_role() || $role == FREELANCER ) ) { ?>
                                <span><?php _e( 'Employer', ET_DOMAIN ); ?></span>
                                <a href="<?php echo $convert->author_url; ?>" target="_blank"><span><?php echo $convert->author_name; ?></span></a>
							<?php } else { ?>
                                <span><?php _e( 'Freelancer', ET_DOMAIN ); ?></span>
                                <a href="<?php echo $freelancer_data->author_url; ?>" target="_blank"><span><?php echo the_author_meta( 'display_name', $profile_id ); ?></span></a>
							<?php } ?>
                        </li>
                        <li>
                            <span><?php _e( 'Winning Bid', ET_DOMAIN ); ?></span>
                            <span><?php echo $project->bid_budget_text; ?></span>
                        </li>
                        <li>
                            <span><?php _e( 'Deadline', ET_DOMAIN ); ?></span>
                            <span><?php echo date( 'F j, Y', strtotime( $project->project_deadline ) ); ?></span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-5">
                    <p class="project-detail-posted"><?php printf( __( 'Posted on %s', ET_DOMAIN ), $project->post_date ); ?></p>
                    <span class="project-detail-status">
                         <?php
                         $status_arr = array(
	                         'close'     => __( "Processing", ET_DOMAIN ),
	                         'complete'  => __( "Completed", ET_DOMAIN ),
	                         'disputing' => __( "Disputed", ET_DOMAIN ),
	                         'disputed'  => __( "Resolved", ET_DOMAIN ),
	                         'publish'   => __( "Active", ET_DOMAIN ),
	                         'pending'   => __( "Pending", ET_DOMAIN ),
	                         'draft'     => __( "Draft", ET_DOMAIN ),
	                         'reject'    => __( "Rejected", ET_DOMAIN ),
	                         'archive'   => __( "Archived", ET_DOMAIN ),
                         );
                         echo $status_arr[ $post->post_status ];
                         ?>
                    </span>
					<?php if ( current_user_can( 'manage_options' ) && $post->post_status != 'disputed' ) { ?>
                        <div class="project-detail-action">
                            <a class="fre-normal-btn btn-arbitrate-project" id="<?php echo $project->ID ?>"
                               href="javascript:void(0)"><?php _e( 'Resolve Dispute', ET_DOMAIN ) ?></a>
                        </div>
					<?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="project-dispute-wrap report-details">
        <ul class="nav nav-tabs nav-tabs-disputed hidden-lg hidden-md" role="tablist">
            <li class="active"><a href="#disputed-conversation" data-group="conversation" data-toggle="tab"
                                  role="tab"><span><?php _e( 'Negotiation', ET_DOMAIN ) ?></span></a></li>
            <li class="next"><a href="#disputed-information" data-group="informantion" data-toggle="tab"
                                role="tab"><span><?php _e( 'Dispute Information', ET_DOMAIN ) ?></span></a></li>
        </ul>
        <div class="row report-container">
            <div class="col-md-8">
                <div id="disputed-conversation" class="disputed-conversation tab-pane fade in active">
                    <h2 class="disputed-title"><?php _e( 'Negotiation', ET_DOMAIN ) ?></h2>
                    <div class="fre-conversation-disputed-wrap">
						<?php if ( $comment_pages > 1 ) { ?>
                            <div class="paginations-wrapper" style="text-align: center">
								<?php ae_comments_pagination( $comment_pages, $paged, $query_args ); ?>
                            </div>
						<?php } ?>
                        <ul class="fre-conversation-disputed-list">
							<?php
							if ( ! empty( $comments ) ) {
								array_multisort( $comments, SORT_ASC );
								foreach ( $comments as $key => $message ) {
									$convert       = $message_object->convert( $message );
									$messagedata[] = $convert;
									?>
                                    <li class="message-item <?php echo $message->class ?>">
                                        <span class="message-avatar">
                                            <?php echo $message->avatar; ?>
                                        </span>
                                        <div class="message-item">
                                            <h2 class="author-message"><?php echo $convert->display_name ?></h2>
											<?php echo $convert->comment_content; ?>
											<?php
											if ( $convert->file_list ) {
												echo $convert->file_list;
											}
											?>
                                            <span class="message-time">  <?php echo $message->message_time; ?></span>
                                        </div>
                                    </li>
								<?php }
							} ?>
                        </ul>
						<?php echo '<script type="json/data" class="postdata" > ' . json_encode( $messagedata ) . '</script>'; ?>
                    </div>

					<?php if ( $post->post_status == 'disputing' ) { ?>
                        <div class="conversation-disputed-typing">
                            <form class="form-report">
                                <div id="report_docs_container">
                                    <div class="fre-input-field" style="margin-bottom: 0">
                                <textarea name="comment_content"
                                          placeholder="<?php _e( 'Your message here...', ET_DOMAIN ) ?>"></textarea>
                                    </div>

                                    <div class="disputed-attached-typing file-attachment-wrapper">
                                        <ul class="fre-attached-list apply_docs_file_list">
                                            <!-- report file list -->
                                        </ul>
                                    </div>

                                    <input class="fre-normal-btn submit-chat-content" type="submit"
                                           value="<?php _e( "Send", ET_DOMAIN ); ?>">

                                    <label class="form-group form-submit-notify">
                                        <a href="javascript:void(0)" class="disputed-attach-file"
                                           id="report_docs_browse_button">
                                            <i class="fa fa-paperclip"
                                               aria-hidden="true"></i><?php _e( "Attach files", ET_DOMAIN ); ?>
                                        </a>
                                    </label>
                                    <span class="et_ajaxnonce"
                                          id="<?php echo wp_create_nonce( 'file_et_uploader' ) ?>"></span>
                                    <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>"/>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
					<?php } ?>
                </div>
            </div>
            <div class="col-md-4">
                <div id="disputed-information" class="disputed-information tab-pane fade in">
                    <div class="disputed-info-wrap">

						<?php if ( $post->post_status == 'disputing' ) { ?>
                            <p>
                                <b>
									<?php
									$project_report_by = get_post_meta( $post->ID, 'dispute_by', true );
									if ( ae_user_role( $project_report_by ) == FREELANCER ) {
										$reporter      = $project_report_by;
										$reporter_name = "<strong>" . sprintf( __( 'Freelancer %s', ET_DOMAIN ), get_the_author_meta( 'display_name', $reporter ) ) . "</strong>";
									} else {
										$reporter      = $post->post_author;
										$reporter_name = "<strong>" . sprintf( __( 'Employer %s', ET_DOMAIN ), get_the_author_meta( 'display_name', $reporter ) ) . "</strong>";
									}

									echo $reporter_name;
									?>
                                </b>
								<?php _e( 'has quit this project. The final result of the dispute will be decided based on reports and proofs from both sides. Email, image, contracts, files, etc. are accepted.', ET_DOMAIN ); ?>
                            </p>
							<?php
						} else {
							$comment_of_admin    = get_post_meta( $post->ID, 'comment_of_admin', true );
							$winner_of_arbitrate = get_post_meta( $post->ID, 'winner_of_arbitrate', true );
							if ( $winner_of_arbitrate == 'freelancer' ) {
								$text_win = get_the_author_meta( 'display_name', $bid_accepted_author );
							} else {
								$text_win = get_the_author_meta( 'display_name', $project->post_author );
							}
							?>
                            <p>
                                <b>
									<?php _e( 'Admin comment:', ET_DOMAIN ) ?>
                                </b>
								<?php echo $comment_of_admin ?>
                            </p>
                            <br>
                            <p><?php _e( 'Money has been tranfered to', ET_DOMAIN ) ?> <b><?php echo $text_win ?></b>
                            </p>
						<?php } ?>


                        <div class="disputed-info-link">
							<?php if ( $user_ID == $project->post_author or $user_ID == $bid_accepted_author ) { ?>
                                <p><a href="<?php echo $project->permalink . '?workspace=1' ?>" target="_blank"><i
                                                class="fa fa-external-link"
                                                aria-hidden="true"></i><?php _e( 'Project workspace', ET_DOMAIN ) ?>
                                    </a></p>
							<?php } ?>
                            <p><a href="<?php echo $project->permalink ?>"
                                  target="_blank"><i class="fa fa-external-link"
                                                     aria-hidden="true"></i><?php _e( 'Project description', ET_DOMAIN ) ?>
                                </a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>