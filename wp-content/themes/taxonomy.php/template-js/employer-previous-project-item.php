<script type="text/template" id="employer_previous_project_template_js">
    <div class="fre-table-col project-title-col">
        <a href="{{= permalink }}">{{= post_title }}</a>
    </div>
    <div class="fre-table-col project-start-col">{{= post_date }}</div>
    <div class="fre-table-col project-bid-col"><span><?php _e( 'Bid won:', ET_DOMAIN ); ?></span><b>{{= bid_budget_text }}</b><span>{{= bid_won_date }}</span></div>
    <div class="fre-table-col project-status-col">{{= project_status_view }}</div>
    <div class="fre-table-col project-review-col">
        <# if( typeof win_disputed !== 'undefined' && win_disputed !== '' ) { #>
            <# if ( win_disputed === 'employer' ) { #>
                <i>
			       <?php _e( 'Won dispute', ET_DOMAIN ); ?>
                </i>
            <# } else { #>
                <i>
                    <?php _e( 'Lost dispute', ET_DOMAIN ); ?>
                </i>
            <# } #>
        <# } else { #>
            <# if ( typeof rating_score > 0 ) { #>
                <span class="rate-it" data-score="{{= rating_score}}"></span>
            <# } else { #>
                <?php _e( '<i>No rating & review yet.</i>', ET_DOMAIN ); ?>
            <# } #>
            <# if( typeof project_comment !== 'undefined' && project_comment !== '' ) { #>
                <p>{{= project_comment }}</p>
            <# } #>
        <# } #>
    </div>
</script>