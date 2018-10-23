<script type="text/template" id="freelancer_current_project_template_js">
    <div class="fre-table-col project-title-col">
        <# if( post_status === 'archive' ) { #>
            {{= project_title }}
        <# } else { #>
            <a href="{{= project_link }}">{{= project_title }}</a>
        <# } #>
    </div>
    <div class="fre-table-col project-bids-col">{{= total_bids }}<span><?php _e( 'Bids', ET_DOMAIN ); ?></span></div>
    <div class="fre-table-col project-bid-col">
        <span><?php _e( 'Bid', ET_DOMAIN ); ?></span><b>{{= bid_budget}}</b><span>{{= bid_time_text}}</span>
    </div>
    <div class="fre-table-col project-average-col"><span><?php _e( 'Average Bid', ET_DOMAIN ); ?></span>{{= bid_average}}</div>
    <div class="fre-table-col project-status-col <# if ( post_status === 'archive' ) { #>project-status-archive<# } #>">{{= project_status_view }}</div>
    <div class="fre-table-col project-action-col">
        <# if ( post_status === 'accept' ) { #>
            <a href="{{= project_link }}?workspace=1" target="_blank"><?php _e( 'Workspace', ET_DOMAIN ); ?></a>
        <# } else if ( post_status === 'unaccept' ) { #>
            <p><i>
	           <?php _e( 'Your bid is not accepted', ET_DOMAIN ); ?>
            </i></p>
        <# } else if ( post_status === 'publish' ) { #>
            <a class="bid-action" data-action="cancel" data-bid-id="{{= ID }}"><?php _e( 'Cancel Bid', ET_DOMAIN ); ?></a>
        <# } else if ( post_status === 'disputing' ) { #>
            <a href="{{= project_link }}?dispute=1" target="_blank"><?php _e( 'Dispute Page', ET_DOMAIN ); ?></a>
        <# } else if ( post_status === 'archive' ) { #>
            <a class="bid-action" data-action="remove" data-bid-id="{{= ID }}"><?php _e( 'Remove', ET_DOMAIN ); ?></a>
        <# } #>
    </div>
</script>