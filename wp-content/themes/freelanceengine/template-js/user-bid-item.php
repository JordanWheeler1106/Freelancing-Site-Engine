<?php $currency =  ae_get_option('currency',array('align' => 'left', 'code' => 'USD', 'icon' => '$')); ?>
<script type="text/template" id="ae-user-bid-loop">

    <li class="bid type-bid status-publish hentry user-bid-item">
        <div class="row user-bid-item-list">
            <div class="col-md-9">
                <a href="{{= author_url}}" class="avatar-author-project-item">
                {{= project_author_avatar }}
                </a>
                <a href= " {{=project_link}}"<span class="content-title-project-item">{{=project_title}}</span> </a>
            </div>
            <div class="col-md-3">
            <# if(project_status == 'publish') {#>
            <a class="btn btn-apply-project-item" href="{{= project_link }}">
               <?php _e('Cancel',ET_DOMAIN);?>
            </a>
            <# }else if(project_status == 'complete' && ID == project_accepted ){  #>
                <a href="{{= review_link }}" class="btn btn-apply-project-item">
                    <?php _e("Review job", ET_DOMAIN) ?>
                </a>
            <# } else if(project_status == 'close' && ID == project_accepted  ){ #>
               <a href="{{= project_workspace_link }}" class="btn btn-apply-project-item">
                    <?php _e("Open Workspace", ET_DOMAIN) ?>
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
                        {{=status_text}}
                    </span>
                </li>
                <li>
                    <span>
                        <?php _e('Bidding',ET_DOMAIN);?>: {{=bid_budget_text}}
                    </span>
                    <span class="number-blue">

                    </span> {{= bid_time_text }}
                </li>
            </ul>
        </div>
    </li>

</script>