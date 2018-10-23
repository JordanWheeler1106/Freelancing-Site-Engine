<script type="text/template" id="ae-project-loop">
    <div class="row">
        <div class="col-md-5 col-sm-5 col-xs-7 text-ellipsis pd-r-30">
            <p style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <a href="{{= author_url }}" class="title-project">
                    {{= et_avatar }}
                </a>
                <a title="{{= post_title }}" href="{{= permalink }}" class="project-item-title">
                    {{= post_title }}
                </a>
            </p>
        </div>
        <div class="col-md-2 col-sm-3 hidden-xs">
            <?php /*
            <# if(parseInt(et_featured)) { #>
                <span class="ribbon"><i class="fa fa-star"></i></span>
            <# } #>
            */?>
            <span><a href="{{= author_url }}"> {{=author_name}} </a></span>
        </div>
        <div class="col-md-2 col-sm-2 hidden-sm hidden-xs">
            <span>{{= post_date }}</span>
        </div>
        <div class="col-md-1 col-sm-2 col-xs-4 hidden-xs">
             <span class="budget-project">{{=budget}}</span>
        </div>
        <# if(post_status == 'pending'){ #>
            <div class="col-md-2 col-sm-2 col-xs-5 text-right">
                <a href="#" class="action approve" data-action="approve">
                    <i class="fa fa-check"></i>
                </a> &nbsp;
                <a href="#" class="action reject" data-action="reject">
                    <i class="fa fa-times"></i>
                </a> &nbsp;
                <a href="#edit_place" class="action edit" data-target="#" data-action="edit">
                    <i class="fa fa-pencil"></i>
                </a> &nbsp;
            </div>

        <# } else { #>

            <div class="col-md-2 col-sm-2 col-xs-5">
            <# if( current_user_bid ){  #>
                 <span class="wrapper-btn">
                    <a href="{{=permalink}}" class="bid-label" >
                        <i class="fa fa-check"></i><?php _e(' Bid',ET_DOMAIN);?>
                    </a>
                 </span>
            <# }else{ #>
                <p class="wrapper-btn">
                    <a href="{{=permalink}}" class="btn-sumary btn-apply-project">
                        <?php _e('Apply',ET_DOMAIN);?>
                    </a>
                </p>
            <# } #>
            </div>
        <# } #>
    </div>
</script>