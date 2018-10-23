<?php $currency =  ae_get_option('currency',array('align' => 'left', 'code' => 'USD', 'icon' => '$')); ?>
<script type="text/template" id="ae-user-bid-loop">

    <li class="bid type-bid status-publish hentry user-bid-item">
        <div class="row user-bid-item-list">
            <div class="col-md-8 col-sm-8">
                <a href="{{= author_url}}" class="avatar-author-project-item">
                    {{= project_author_avatar }}
                </a>
                <a class="project-title" href= "{{=project_link}}" >
                    <span class="content-title-project-item">{{=project_title}}</span>
                </a>
                <div class="user-bid-item-info">
                    <ul class="info-item">
                        <li>
                            <span>
                                <?php _e('Bidding',ET_DOMAIN);?>: 
                            </span>
                            <span class="number-blue">
                                {{= bid_budget_text }}
                            </span>
                            {{= bid_time_text }}
                        </li>
                        <li>
                            <span><?php _e('Number Bids of Project:',ET_DOMAIN);?></span><span class="number-blue"> {{=total_bids}}</span></li>
                        <li>
                            <?php printf( __('Average Bid:', ET_DOMAIN)) ?>
                            <span class="number-blue">{{=bid_average}}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 action-project">
                <# if(post_status == 'unaccept') {#>
                    <p class="number-blue"><?php _e('Processing', ET_DOMAIN); ?></p>
                    <p class="status-bid-project"><?php _e('Your bid is not accepted.', ET_DOMAIN); ?></p>
                <# }else if(post_status == 'accept'){ #>
                    <p class="number-blue"><?php _e('Processing', ET_DOMAIN); ?></p>
                    <p class="status-bid-project"><?php _e('Your bid is accepted.', ET_DOMAIN); ?></p>
                    <div class="status-project">
                        <a href="{{= project_workspace_link }}" class="btn-apply-project-item">
                            <?php _e("Workspace", ET_DOMAIN) ?>
                        </a>
                    </div>
                <# }else if(post_status == 'publish'){ #>
                    <p class="number-blue"><?php _e('Active', ET_DOMAIN); ?></p>
                    <p class="status-bid-project">{{= et_expired_date}}</p>
                    <div class="status-project">
                        <a class="btn-apply-project-item" href="{{= project_link }}">
                            <?php _e("Cancel", ET_DOMAIN) ?>
                        </a>
                    </div>
                <# } #>
            </div>
        </div>
    </li>

</script>