
<script type="text/template" id="ae-bid-loop">
    <div class="info-bidding fade-out fade-in bid-item bid-{{=ID}} bid-item-{{=post_status}} {{= add_class_bid}}">
        <div class="col-md-7 col-xs-7">
            <div class="avatar-freelancer-bidding">
                <a href="{{= author_url}}">
                    <span class="avatar-profile">{{= et_avatar }}</span>
                </a>
            </div>
            <div class="info-profile-freelancer-bidding">
                <span class="name-profile">{{=profile_display }}</span><br />
                <span class="position-profile">{{=et_professional_title }}</span>
                <div class="rate-exp-wrapper">
                    <div class="rate-it" data-score="{{=rating_score }}"></div>
                    <div class="experience">
                        <# if(experience){ #>
                            <# if(experience > 1 ){ #>
                                {{=experience}}+ <?php printf(__('Years', ET_DOMAIN));?>
                            <# }else{ #>
                                {{=experience}}+ <?php printf(__('Years', ET_DOMAIN));?>
                            <# } #>
                        <# }else{ #>
                            <?php printf(__('+ 0 Years', ET_DOMAIN));?>
                        <# } #>
                    </div>
                </div>
                <#  if( post_content ){ #>
                    <div class="comment-author-history full-text">
                        <p>{{= post_content}}</p>
                    </div>
                <# } #>
            </div>
        </div>
        <div class="col-md-5 col-xs-5 block-bid">
            <div class="number-price-project">
                <# if( ae_globals.user_ID == project_author || ae_globals.user_ID == post_author ) { #>
                    <span class="number-price">{{= bid_budget_text }}</span>
                    <span class="number-day">{{= bid_time_text }}</span>
                <# }else{ #>
                    <span class="number-price"><?php _e("In Process", ET_DOMAIN); ?></span>
                <# } #>
            </div>
            <div class="action-employer-bidden">
                <# if( flag == 1 ) { #>
                    <# if(ae_globals.use_escrow) { #>
                        <button class="fre-small-btn btn-accept-bid" id="{{= ID}}" title="" href="#">
                            <?php _e('Accept', ET_DOMAIN); ?>
                        </button>
                    <# }else{ #>
                        <button class="fre-small-btn btn-accept-bid btn-accept-bid-no-escrow" id="{{= ID}}">
                            <?php _e('Accept',ET_DOMAIN) ;?>
                        </button>
                    <# } #>
                <# } else if( flag == 2 ){ #>
                    <div class="ribbon"><i class="fa fa-trophy"></i></div>
                <# } #>

                <# if(typeof button_message != 'undefine' && project_status =='publish' && ae_globals.user_ID == project_author){#>
                    {{= button_message}}
                <# } #>
            </div>
            <# if(project_status != 'publish' && project_author == ae_globals.user_ID){ #>
            <div class="show-info">
                <i class="fa fa-circle" aria-hidden="true"></i>
                <i class="fa fa-circle" aria-hidden="true"></i>
                <i class="fa fa-circle" aria-hidden="true"></i>
            </div>
            <# } #>
        </div>
        <div class="clearfix"></div>
    </div>
</script>