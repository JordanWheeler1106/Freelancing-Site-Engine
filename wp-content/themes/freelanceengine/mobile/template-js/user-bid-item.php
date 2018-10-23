<?php $currency =  ae_get_option('currency',array('align' => 'left', 'code' => 'USD', 'icon' => '$')); ?>
<script type="text/template" id="ae-user-bid-loop">

    <div class="info-single-project-wrapper">
        <div class="container">
            <div class="info-project-top">
                <div class="avatar-author-project">
                    <a href="{{=author_url }}">
                        {{= project_author_avatar }}
                    </a>
                </div>
                <h1 class="title-project">
                    <a href="{{=project_link }}">
                        {{=project_title }}
                    </a>
                </h1>
                <div class="clearfix"></div>
            </div>
            <div class="info-bottom">
                <span class="name-author"> 
                    <?php _e('Posted by ', ET_DOMAIN); ?>{{=profile_display }}
                </span>
                <span class="price-project">{{=bid_budget_text }}</span>
            </div>
        </div>
    </div>

    <div class="info-bid-wrapper">
        <ul class="bid-top">
            <li>
            {{=total_bids}}</span> <# if(total_bids >1) { #> <?php _e('Bids',ET_DOMAIN);?> <# } else { #> <?php _e('Bid',ET_DOMAIN) ?> <# } #>
            </li>

            <li>
                <span class="number">
                    {{=bid_average}}
                </span>
                 <?php printf( __('Avg Bid (%s)', ET_DOMAIN), $currency['code'] ) ?>
            </li>

            <li class="clearfix"></li>

            <li class="stt-bid">
                <div class="time">
                    <span class="number">
                        {{=status_text }}
                    </span><!-- 1 day, 12 hours left -->
                </div>
                <p class="btn-warpper-bid">
                    <# if(project_status == 'publish') {#>
                    <a href="{{=project_link }}" class="btn-sumary btn-bid">
                        <?php _e("CANCEL", ET_DOMAIN) ?>
                    </a>
                    <# }else if(project_status == 'complete' && ID == project_accepted ){  #>
                        <a href="{{= review_link }}" class="btn-sumary btn-bid">
                            <?php _e("Review job", ET_DOMAIN) ?>
                        </a>
                    <# } else if(project_status == 'close' && ID == project_accepted  ){ #>
                        <a href="{{= project_workspace_link }}" class="btn-sumary btn-bid">
                            <?php _e("Open Workspace", ET_DOMAIN) ?>
                        </a>
                    <# }#>
                </p>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <?php /*
    <li class="post-259 bid type-bid status-publish hentry user-bid-item">
        <div class="row user-bid-item-list">
            <div class="col-md-6">
               {{= project_author_avatar }}
               <a href= " {{=project_link}}"<span class="content-title-project-item">{{=project_title}}</span> </a>
            </div>
                <div class="col-md-6">
                <# if(post_status == 'publish') {#>
                <a class="btn btn-apply-project-item" href="{{=project_link}}">
                   <?php _e('Cancel',ET_DOMAIN);?>
                </a>
                <# } #>
                </div>
        </div>

        <div class="user-bid-item-info">
            <ul class="info-item">
                <li>
                    <span class="number-blue"> {{=total_bids}}</span> <# if(total_bids >1) { #> <?php _e('Bids',ET_DOMAIN);?> <# } else { #> <?php _e('Bid',ET_DOMAIN) ?> <# } #>  </li>
                <li>
                    <span class="number-blue">
                       {{=bid_average}}     </span> <?php printf(__('Avg Bid (%s)',ET_DOMAIN), $currency['code']);?>            </li>
                <li>
                    <span class="number-blue">

                    </span>
                </li>
                <li>
                    <span>
                        <?php _e('Bidding',ET_DOMAIN);?>: {{=bid_budget_text}}
                    </span>
                    <span class="number-blue">

                    </span> in {{=bid_time}}  {{=type_time}}            </li>
            </ul>
        </div>
    </li>
    */?>
</script>