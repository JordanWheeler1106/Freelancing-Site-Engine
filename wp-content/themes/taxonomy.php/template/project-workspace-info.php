<?php

global $wp_query, $wpdb, $ae_post_factory, $post, $user_ID;
$post_object         = $ae_post_factory->get( PROJECT );
$convert             = $project = $post_object->convert( $post );
$et_expired_date     = $convert->et_expired_date;
$bid_accepted        = $convert->accepted;
$project_status      = $convert->post_status;
$project_link        = get_permalink( $post->ID );
$role                = ae_user_role();
$bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
$bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
$profile_id          = $post->post_author;
if ( ( fre_share_role() || $role != FREELANCER ) ) {
	$profile_id = $bid_accepted_author;
}
$currency               = ae_get_option( 'currency', array( 'align' => 'left', 'code' => 'USD', 'icon' => '$' ) );
$comment_for_freelancer = get_comments( array(
	'type'    => 'em_review',
	'status'  => 'approve',
	'post_id' => $bid_accepted
) );

$comment_for_employer   = get_comments( array(
	'type'    => 'fre_review',
	'status'  => 'approve',
	'post_id' => get_the_ID()
) );

$freelancer_info = get_userdata($bid_accepted_author);
$ae_users  = AE_Users::get_instance();
$freelancer_data = $ae_users->convert( $freelancer_info->data );

if ( ( fre_share_role() || $role == FREELANCER ) && $project_status == 'complete' && ! empty( $comment_for_freelancer ) ) { ?>
    <div class="project-detail-box">
        <div class="project-employer-review">
            <span class="employer-avatar-review"><?php echo $convert->et_avatar; ?></span>
            <h2><a href="<?php echo $convert->author_url; ?>" target="_blank"><?php echo $convert->author_name; ?></a></h2>
            <p><?php echo '"' . $comment_for_freelancer[0]->comment_content . '"'; ?></p>
            <div class="rate-it"
                 data-score="<?php echo get_comment_meta( $comment_for_freelancer[0]->comment_ID, 'et_rate', true ); ?>"></div>
			<?php if ( empty( $comment_for_employer ) ) { ?>
                <a href="#" id="<?php the_ID(); ?>"
                   class="fre-normal-btn btn-complete-project"> <?php _e( 'Review for Employer', ET_DOMAIN ); ?></a>
			<?php } ?>
        </div>
    </div>
<?php } else if ( ( fre_share_role() || $role == EMPLOYER ) && $project_status == 'complete' && ! empty( $comment_for_employer ) ) { ?>
    <div class="project-detail-box">
        <div class="project-employer-review">
            <span class="employer-avatar-review"><?php echo $freelancer_data->avatar; ?></span>
            <h2><a href="<?php echo $freelancer_data->author_url; ?>" target="_blank"><?php echo $freelancer_data->display_name; ?></a>
            </h2>
            <p><?php echo '"' . $comment_for_employer[0]->comment_content . '"'; ?></p>
            <div class="rate-it"
                 data-score="<?php echo get_comment_meta( $comment_for_employer[0]->comment_ID, 'et_rate', true ); ?>"></div>
        </div>
    </div>
<?php } ?>


<div class="project-detail-box">
    <div class="project-detail-info">
        <div class="row">
            <div class="col-lg-8 col-md-7">
                <h1 class="project-detail-title"><a href="<?php echo $project_link; ?>"><?php the_title(); ?></a></h1>
                <ul class="project-bid-info-list">
                    <li>
						<?php if ( ( fre_share_role() || $role == FREELANCER ) && $user_ID != $project->post_author ) { ?>
                            <span><?php _e( 'Employer', ET_DOMAIN ); ?></span>
                            <a href="<?php echo $convert->author_url; ?>" target="_blank"><span><?php echo $convert->author_name; ?></span></a>
						<?php } else if ( ( fre_share_role() || $role == EMPLOYER ) && $user_ID == $project->post_author ) { ?>
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
                <div class="project-detail-action">
					<?php
					if ( $post->post_status == 'close' ) {
						if ( (int) $project->post_author == $user_ID ) { ?>
                            <a title="<?php _e( 'Finish', ET_DOMAIN ); ?>" href="#" id="<?php the_ID(); ?>"
                               class="fre-action-btn btn-complete-project"> <?php _e( 'Finish', ET_DOMAIN ); ?></a>
							<?php if ( ae_get_option( 'use_escrow' ) ) { ?>
                                <a title="<?php _e( 'Close', ET_DOMAIN ); ?>" href="#" id="<?php the_ID(); ?>"
                                   class="fre-action-btn btn-close-project"><?php _e( 'Close', ET_DOMAIN ); ?></a>
							<?php }
						} else {
							if ( $bid_accepted_author == $user_ID && ae_get_option( 'use_escrow' ) ) { ?>
                                <a title="<?php _e( 'Discontinue', ET_DOMAIN ); ?>" href="#" id="<?php the_ID(); ?>"
                                   class="fre-action-btn btn-quit-project"><?php _e( 'Discontinue', ET_DOMAIN ); ?></a>
							<?php }
						}
					} else if ( $post->post_status == 'disputing' ) { ?>
                        <a href="<?php echo add_query_arg( array( 'dispute' => 1 ), $project_link ) ?>"
                           class="fre-normal-btn"><?php _e( 'Dispute Page', ET_DOMAIN ) ?></a>
					<?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>