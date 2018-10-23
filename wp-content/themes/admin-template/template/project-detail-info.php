<?php
/**
 * The template for displaying project heading info in single project detail
 * @since 1.0
 * @package FreelanceEngine
 * @category Template
 */
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object    = $ae_post_factory->get(PROJECT);

$convert            = $post_object->current_post;
$currency           = ae_get_option('currency',array('align' => 'left', 'code' => 'USD', 'icon' => '$'));
?>
<div class="info-project-item info-project-item-tan">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-8">
        <?php

            if( empty($convert->total_bids ) ) {
                $convert->total_bids = 0;
            }

            $et_expired_date = $convert->et_expired_date;
            ?>
            <ul class="info-item">
                <li><span class="number-blue">
                <?php
                    $total_count = get_comments(array( 'post_id' => $post->ID, 'type' => 'comment', 'count' => true, 'status' => 'approve' ));
                    if ( $total_count > 0){
                        if ( $total_count == 1){
                            printf(__('%d <span class="text-normal">Comment</span>', ET_DOMAIN), $total_count);
                        }else{
                            printf(__('%d <span class="text-normal">Comments</span>', ET_DOMAIN), $total_count);
                        }
                    } else {
                        printf(__('%d <span class="text-normal">Comments</span>', ET_DOMAIN), $total_count);
                    }
                ?>
                </li>
                <li>
                    <span class="number-blue">
                        <?php
                            if ( $convert->total_bids > 0 ) {
                                if ( $convert->total_bids == 1 ) {
                                    printf(__('%s <span class="text-normal">Bid</span>', ET_DOMAIN),$convert->total_bids );
                                }else{
                                    printf(__('%s <span class="text-normal">Bids</span>', ET_DOMAIN),$convert->total_bids );   
                                }
                            } else {
                                printf(__('%s <span class="text-normal">Bids</span>', ET_DOMAIN),$convert->total_bids );
                            }
                        ?>
                    </span>
                </li>
                <li>
                    <span class="number-blue">
                    <?php
                        $avg = 0;
                        if ($convert->total_bids > 0) $avg = get_total_cost_bids($convert->ID) / $convert->total_bids;
                        echo fre_price_format($avg);
                    ?>
                    </span>
                    <span class="text-normal">
                        <?php printf(__("Avg Bid (%s)",ET_DOMAIN), $currency['code']);?>
                    </span>
                </li>
                <li>
                    <?php if($post->post_status == 'publish') { ?>
                        <span class="number-blue">
                            <?php _e("Active", ET_DOMAIN); ?>
                        </span>
                        <span class="text-normal">
                        <?php
                            if( empty($et_expired_date) ) {
                                echo ae_the_time(get_the_time('U'));
                                // printf(__('%s ago',ET_DOMAIN), human_time_diff( get_the_time('U'), time() ) );
                            }else{
                                printf(__('%s left',ET_DOMAIN), human_time_diff( time(), strtotime($et_expired_date)) );
                            }
                        ?>
                        </span>
                    <?php }else {
                        echo '<span class="number-blue">';
                        $status_arr = array(
                                    'close'     => __("Processing", ET_DOMAIN),
                                    'complete'  => __("Completed", ET_DOMAIN),
                                    'disputing' => __("Disputed" , ET_DOMAIN ),
                                    'disputed'  => __("Resolved" , ET_DOMAIN ),
                                    'publish'   => __("Active", ET_DOMAIN),
                                    'pending'   => __("Pending", ET_DOMAIN),
                                    'draft'     => __("Draft", ET_DOMAIN),
                                    'reject'    => __("Rejected", ET_DOMAIN),
                                    'archive'   => __("Archived", ET_DOMAIN),
                                    );
                        echo $status_arr[$post->post_status];
                        echo '</span>';
                    } ?>

                </li>
            </ul>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="info-project-item-right">
                <?php  if(( fre_share_role() || ae_user_role() == 'employer' || ae_user_role() == 'administrator' ) && 
                        !in_array($post->post_status, array('disputing','disputed'))){ ?>
                <span class="link-clone">
                    <a href="<?php echo et_get_page_link( array('page_type' => 'submit-project', 'post_title' => __("Submit project", ET_DOMAIN )) ); ?>" title="">
                        <i class="fa fa-plus-circle"></i><?php _e('Create a Project like this',ET_DOMAIN);?>
                    </a>
                </span>
                <?php }  ?>
                <ul class="list-share-social addthis_toolbox addthis_default_style" >
                    <li>
                        <a href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=<?php echo $convert->permalink; ?>&title=<?php echo $convert->post_title; ?>" rel="nofollow" class=""
                        onclick="window.open(this.href, '', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;" >
                            <i class="fa fa-facebook "></i>
                        </a>
                    </li>
                    <li>
                        <a href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?url=<?php echo $convert->permalink; ?>&title=<?php echo $convert->post_title; ?>" rel="nofollow" class=""
                        onclick="window.open(this.href, '', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;" >
                            <i class="fa fa-twitter "></i>
                        </a>
                    </li>
                    <li><a href="http://api.addthis.com/oexchange/0.8/forward/googleplus/offer?url=<?php echo $convert->permalink; ?>&title=<?php echo $convert->post_title; ?>" rel="nofollow" class=""
                        onclick="window.open(this.href, '', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;" >
                        <i class="fa fa-google-plus"></i>
                    </a>
                    </li> <!--addthis_button_google_plusone_share !-->
                </ul>
            </div>
        </div>
    </div>
</div> <!-- end .info-project-item !! -->
