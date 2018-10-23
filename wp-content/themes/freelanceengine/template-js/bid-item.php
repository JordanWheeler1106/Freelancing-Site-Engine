
<script type="text/template" id="ae-bid-loop">
    <div class="bid-item-{{=post_status}}">
        <div class="col-md-4 col-xs-4">
            <div class="avatar-freelancer-bidding"><span class="avatar-profile"> {{= et_avatar }}</span></div>
            <div class="info-profile-freelancer-bidding">
                <span class="name-profile">{{=profile_display }}</span><br>
                <span class="position-profile"> {{=et_professional_title }}</span>
            </div>

        </div>
        <div class="col-md-4 col-xs-4">
			<div class="rate-exp-wrapper">
				<div class="rate-it" data-score= "{{=rating_score }}" ></div>

				<div class="experience"><# if(experience){ #> {{=experience}} <# } #> </div>
			</div>
        </div>
        <div class="col-md-4 col-xs-4 block-bid">
            <span class="number-price-project">
            <# if( ae_globals.user_ID == project_author || ae_globals.user_ID == post_author ) { #>
                <span class="number-price">{{= bid_budget_text }}</span>
                <span class="number-day">{{= bid_time_text }}</span>
            <# } #>

            <# if( post_status =='publish' && current_user == project_author ) { #>
                <button class="btn-sumary btn-accept-bid btn-bid-status" rel="{{= post_parent }}" href="#"> <?php _e('Accept', ET_DOMAIN); ?> </button>
                <span class="confirm"></span>

            <# } else if( post_status == 'close' && accepted == ID){ #>
                <div class="ribbon"><i class="fa fa-trophy"></i></div>
            <# } #>
            </span>
            {{= button_message}}
            <?php //do_action('ae_bid_item_template_js', $convert, $project ); ?>
        </div>
		<div class="clearfix"></div>

        <#  if( post_content ){ #>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <blockquote class="comment-author-history">
                    {{= post_content }}
                </blockquote>
            </div>
        <# } #>
    </div>
</script>