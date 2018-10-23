<?php
/**
 * The template for displaying a bid info item,
 * this template is used to display bid info in a project details,
 * and called at template/list-bids.php
 * @since 1.0
 * @author Dakachi
 */
global $wp_query, $ae_post_factory, $post, $user_ID;
$project_object = $ae_post_factory->get( PROJECT );
$project = $project_object->current_post;
$post_object    = $ae_post_factory->get( BID );
$convert        = $post_object->convert( $post );
$project_status = $project->post_status;
$user_role      = ae_user_role( $user_ID );

?>
<div class="row freelancer-bidding-item">
    <div class="col-md-10 col-sm-9">
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="col-free-bidding">
                    <a class="free-bidding-avatar" href="<?php echo get_author_posts_url( $convert->post_author ); ?>">
						<?php echo $convert->et_avatar; ?>
                    </a>
                    <h2>
                        <a href="<?php echo get_author_posts_url( $convert->post_author ); ?>"><?php echo $convert->profile_display; ?></a>
                    </h2>
                    <p><?php echo $convert->et_professional_title ?></p>
                    <p><?php echo $convert->author_country; ?></p>
                </div>
				<?php
				if ( $convert->flag == 2 ) {
					echo '<div class="free-ribbon hidden-xs"><span class="ribbon"><i class="fa fa-trophy"></i></span></div>';
				}
				?>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="col-free-reputation">
                    <div class="rate-it" data-score="<?php echo $convert->rating_score; ?>"></div>
					<?php
					printf( __( '<p>%s year(s) experience</p>', ET_DOMAIN ), $convert->experience );
					printf( __( '<p>%s project(s) worked</p>', ET_DOMAIN ), $convert->total_projects_worked );
					?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-3">
        <div class="col-free-bid">
            <p class="hidden-lg hidden-md hidden-sm"><?php _e( 'Bid', ET_DOMAIN ); ?></p>
            <p><b><?php echo $convert->bid_budget_text; ?></b></p>
            <p><?php echo $convert->bid_time_text; ?></p>
        </div>
        <?php
        if ( $convert->flag == 2 ) {
            echo '<div class="free-ribbon visible-xs"><span class="ribbon"><i class="fa fa-trophy"></i></span></div>';
        }
        ?>
    </div>
    <div class="col-md-7 col-sm-12">
        <div class="col-content-bid col-content-bid-<?php echo $convert->ID ?>">
			<?php
			if ( $convert->post_content ) {
				echo $convert->post_content;
			}
			if ( $user_ID == $project->post_author ) {
				if ( $convert->flag == 1 ) {
					if ( ae_get_option( 'use_escrow' ) ) {
						echo '<a id="' . get_the_ID() . '" rel="' . $project->ID . '" class="fre-normal-btn btn-accept-bid">' . __( 'Accept Bid', ET_DOMAIN ) . '</a>';
					} else {
						echo '<a class="fre-normal-btn btn-accept-bid btn-accept-bid-no-escrow" id="' . get_the_ID() . '">' . __( 'Accept Bid', ET_DOMAIN ) . '</a>';
					}
				}
				if(in_array($project_status, array('publish') )){
					do_action('ae_bid_item_template', $convert, $project );
				}
			}
			?>
        </div>
    </div>
</div>