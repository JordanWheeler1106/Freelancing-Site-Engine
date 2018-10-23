<?php
$author_id = get_query_var('author');
?>
<script type="text/template" id="ae-bid-history-loop">
    <div class="name-history">
        <a href="{{=author_url}}"><span class="avatar-bid-item">{{= project_author_avatar }} </span>  </a>
        <div class="content-bid-item-history">
            <h5><a href = "{{= project_link }}">{{= project_title }}</a>
            <# if(project_status == 'complete') { #>
                <div class="rate-it" data-score="{{= rating_score }}"></div>
            </h5>
            <span class="comment-author-history">{{= project_comment }}</span>
            <# } else if(project_status == 'publish'){ #>
            </h5>
            <span class="stt-in-process"><?php _e('Job is open', ET_DOMAIN);?></span>
            <# }else if(post_status == 'close') { #>
            </h5>
            <span class="stt-in-process"><?php _e('Job is closed', ET_DOMAIN);?></span>
            <# } #>
        </div>
    </div>
    <ul class="info-history">
        <li>{{= project_post_date }}</li>
        <# if(bid_budget) { #>
            <li><?php _e('Bid Budget :', ET_DOMAIN) ; ?> <span class="number-price-project-info"> {{= bid_budget_text }} </span></li>
        <# } #>
    </ul>
    <div class="clearfix"></div>
</script>
<!-- Template work history loop of Freelancer-->
<script type="text/template" id="ae-work-history-loop-freelancer">
    <# if(project_status == 'complete') { #>
        <div class="name-history">
            <a href="{{= author_url}}">
                <span class="avatar-bid-item">{{= project_author_avatar}}</span>
            </a>
            <div class="content-bid-item-history">
                <h5><a href="{{= project_link}}">{{= project_title}}</a></h5>
            </div>
            <div class="content-complete">
                <div class="rate-it" data-score="{{= rating_score}}"></div>
                <# if(project_comment) {#>
                    <div class="comment-author-history full-text">
                        <p>{{= project_comment}}</p>
                    </div>
                <# }else{ #>
                <span class="stt-in-process"><?php _e('Job is closed', ET_DOMAIN);?></span>
                <# } #>
            </div>
        </div>
        <ul class="info-history action-project">
            <li><p class="number-blue"><?php _e('Completed', ET_DOMAIN);?></p></li>
            <li class="date"><?php _e('Start date:', ET_DOMAIN);?> {{= project_post_date}}</li>
        </ul>
    <# }else if(project_status == 'disputing') { #>
        <div class="name-history">
            <a href="{{= author_url}}">
                <span class="avatar-bid-item">{{= project_author_avatar}}</span>
            </a>
            <div class="content-bid-item-history">
                <h5><a href="{{= project_link}}">{{= project_title}}</a></h5>
            </div>
            <div class="content-complete">
                <span class="stt-in-process"><?php _e('In disputing process', ET_DOMAIN);?></span>
            </div>
        </div>
        <ul class="info-history action-project">
            <li><p class="number-blue"><?php _e('Disputed', ET_DOMAIN);?></p></li>
            <li class="date"><?php _e('Start date:', ET_DOMAIN);?> {{= project_post_date}}</li>
            <?php if(is_page_template('page-profile.php')) { ?>
            <li><a href="{{= project_link}}" class="btn-apply-project-item"><?php _e('Dispute Page', ET_DOMAIN);?></a></li>
            <?php } ?>
        </ul>
    <# }else if(project_status == 'disputed') { #>
        <div class="name-history">
            <a href="{{= author_url}}">
                <span class="avatar-bid-item">{{= project_author_avatar}}</span>
            </a>
            <div class="content-bid-item-history">
                <h5><a href="{{= project_link}}">{{= project_title}}</a></h5>
            </div>
            <div class="content-complete">
                <span class="stt-in-process"><?php _e('Resolved by Admin', ET_DOMAIN);?></span>
            </div>
        </div>
        <ul class="info-history action-project">
            <li><p class="number-blue"><?php _e('Resolved', ET_DOMAIN);?></p></li>
            <li class="date"><?php _e('Start date:', ET_DOMAIN);?> {{= project_post_date}}</li>
        </ul>
    <# } #>
    <div class="clearfix"></div>
</script>



<!-- Template work history loop of Employer-->
<script type="text/template" id="ae-work-history-loop">
    <div class="name-history">
        <a href="{{= author_url}}">
            <span class="avatar-bid-item">
                {{= et_avatar}}
            </span>
        </a>
        <div class="content-bid-item-history">
            <h5>
                <a href = "{{= permalink}}">{{= post_title}}</a>
            </h5>
        </div>
        <div class="content-complete content-complete-employer">
            <p><?php _e("Budget", ET_DOMAIN); ?> :
            <span class="number-price-project-info">{{= budget }}</span></p>
            <p class="date">{{= post_date }}</p>
            <div class="review-rate" style="display:none;">
                <div class="rate-it" data-score="{{= rating_score}}"></div>
                <span class="comment-author-history">{{= project_comment}}</span>
                <div class="review-link"><a title="<?php _e('Rating & Review', ET_DOMAIN);?>" class="review" data-target="#" href="#">
                    <?php _e('Hide', ET_DOMAIN);?><i class="fa fa-sort-asc" aria-hidden="true"></i>
                </a></div>
            </div>
        </div>
    </div>
    <ul class="info-history">
        <li class="post-control">
            <div class="inner"><div class="action-project"><p class="number-blue">{{= status_text }}</p></div>
            <# if(post_status != 'pending' && post_status != 'draft' && post_status != 'reject'){ #>
                <div class="show-action-bids">
                    <p class="number-static-bid text-blue-light">
                        <# if(total_bids > 1){ #>
                            {{= total_bids}} <span class="normal-text"><?php _e('Bids', ET_DOMAIN);?></span>
                        <# }else if(total_bids == 0){ #>
                            {{= total_bids}} <span class="normal-text"><?php _e('Bids', ET_DOMAIN);?></span>
                        <# }else{ #>
                            {{= total_bids}} <span class="normal-text"><?php _e('Bid', ET_DOMAIN);?></span>
                        <# } #>
                    </p>
                    <p class="number-static-bid">
                        <# if(post_views > 1){ #>
                            {{= post_views}} <span class="normal-text"><?php _e( 'Views' , ET_DOMAIN ); ?></span>
                        <# }else if(post_views == 0){ #>
                            {{= post_views}} <span class="normal-text"><?php _e( 'Views' , ET_DOMAIN ); ?></span>
                        <# }else{ #>
                            {{= post_views}} <span class="normal-text"><?php _e( 'View' , ET_DOMAIN ); ?></span>
                        <# } #>
                    </p>
                </div>
            <# } #></div>
            <div class="post-button-control">
                <?php ae_js_edit_post_button(); ?>
            </div>
        </li>
    </ul>
    <div class="clearfix"></div>
</script>


<!-- Template use to page author, role is Freelancer-->
<script type="text/template" id="ae-freelancer-history-loop">

    <div class="fre-author-project">
        <h2 class="author-project-title"><a href="{{= project_link }}" title="{{= project_title }}">{{= project_title }}</a></h2>
        <div class="author-project-info">
            <span class="rate-it" data-score="{{= rating_score }}"></span>
            <span class="budget">{{= bid_budget_text }}</span>
            <span class="posted"> {{=project_post_date}}</span>
        </div>
        <div class="author-project-comment">
            {{= project_comment}}
        </div>
    </div>
    <div class="clearfix"></div>
</script>


<!-- Template use to page author, role is Employer-->
<script type="text/template" id="ae-employer-history-loop">
    <div class="fre-author-project">
        <h2 class="author-project-title"><a
                    href="{{=permalink}}">{{=post_title}}</a></h2>
        <div class="author-project-info">
            <# if(post_status == 'complete'){ #>
            <span class="rate-it" data-score="{{= rating_score}}"></span>
            <# } #>
            <span class="budget">{{= budget}}</span>
            <span class="posted">{{= post_date}}</span>
        </div>
        <div class="author-project-comment">
            <# if(post_status == 'publish'){ #>
                <span class="stt-in-process">
                    <?php _e('Project is currently available for bidding. ', ET_DOMAIN); ?>
                </span>
                <# } #>
                <# if(post_status == 'close'){ #>
	                <?php _e('Project is currently processing', ET_DOMAIN); ?>
                <# } #>
                <# if(post_status == 'complete'){ #>
                    <# if(rating_score){ #>
                        <span class="stt-complete">
                            {{= project_comment }}
                        </span>
                    <# }else{ #>
                        <span class="stt-complete-pending"><i>
                          <?php _e('Project is complete without rating & reviewing from freelancer.', ET_DOMAIN); ?>
                        </i></span>
                    <# } #>
                <# } #>
        </div>
    </div>
    <div class="clearfix"></div>
</script>
