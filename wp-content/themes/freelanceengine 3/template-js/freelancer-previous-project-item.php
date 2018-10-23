<script type="text/template" id="freelancer_previous_project_template_js">
    <div class="fre-table-col project-title-col">
        <a href="{{= project_link }}">{{= project_title }}</a>
    </div>
    <div class="fre-table-col project-start-col">{{= project_post_date }}</div>
    <div class="fre-table-col project-status-col">{{= project_status_view }}</div>
    <div class="fre-table-col project-review-col">
        <# if( typeof win_disputed !== 'undefined' && win_disputed !== '' ) { #>
            <# if ( win_disputed === 'freelancer' ) { #>
                <i>
			        <?php _e( 'Won dispute', ET_DOMAIN ); ?>
                </i>
            <# } else { #>
                <i>
                    <?php _e( 'Lost dispute', ET_DOMAIN ); ?>
                </i>
            <# } #>
        <# } else { #>
            <span class="rate-it" data-score="{{= rating_score }}"></span>
            <# if(typeof project_comment !== 'undefined' && project_comment !== '' ) { #>
                <p>{{= project_comment }}</p>
            <# } #>
        <# } #>
    </div>
</script>

