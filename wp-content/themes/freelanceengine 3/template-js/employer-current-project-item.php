<script type="text/template" id="employer_current_project_template_js">
    <div class="fre-table-col project-title-col">
        <a href="{{= permalink }}">{{= post_title }}</a>
    </div>
    <div class="fre-table-col project-bids-col">{{= total_bids }}<span><?php _e( 'Bids', ET_DOMAIN ); ?></span></div>
    <div class="fre-table-col project-budget-col"><span><?php _e( 'Budget', ET_DOMAIN ); ?></span>{{= budget }}</div>
    <div class="fre-table-col project-open-col"><span><?php _e( 'Open on', ET_DOMAIN ); ?></span>{{= post_date }}</div>
    <div class="fre-table-col project-status-col">{{= project_status_view }}</div>
    <# if ( post_status === 'close' ) { #>
        <div class="fre-table-col project-action-col">
            <a href="{{= permalink }}?workspace=1" target="_blank"><?php _e( 'Workspace', ET_DOMAIN ); ?></a>
        </div>
    <# } else if ( post_status === 'disputing' ) { #>
        <div class="fre-table-col project-action-col">
            <a href="{{= permalink }}?dispute=1" target="_blank"><?php _e( 'Dispute Page', ET_DOMAIN ); ?></a>
        </div>
    <# } else if ( post_status === 'publish' ) { #>
        <div class="fre-table-col project-action-col">
            <a class="project-action" data-action="archive" data-project-id="{{= ID }}"><?php _e( 'Archive', ET_DOMAIN ); ?></a>
        </div>
    <# } else if ( post_status === 'pending' ) { #>
        <div class="fre-table-col project-action-col">
            <a href="<?php echo et_get_page_link('edit-project') ?>?id={{= ID }}" target="_blank"><?php _e( 'Edit', ET_DOMAIN ); ?></a>
        </div>
    <# } else if ( post_status === 'draft' ) { #>
        <div class="fre-table-col project-action-col project-action-two">
            <a href="<?php echo et_get_page_link('submit-project') ?>?id={{= ID }}" target="_blank"><?php _e( 'Edit', ET_DOMAIN ); ?></a>
            <a class="project-action" data-action="delete" data-project-id="{{= ID }}"><?php _e( 'Delete', ET_DOMAIN ); ?></a>
        </div>
    <# } else if ( post_status === 'reject' ) { #>
        <div class="fre-table-col project-action-col">
            <a href="<?php echo et_get_page_link('edit-project') ?>?id={{= ID }}" target="_blank"><?php _e( 'Edit', ET_DOMAIN ); ?></a>
        </div>
    <# } else if ( post_status === 'archive' ) { #>
        <div class="fre-table-col project-action-col project-action-two">
            <a href="<?php echo et_get_page_link('submit-project') ?>?id={{= ID }}" target="_blank"><?php _e( 'Renew', ET_DOMAIN ); ?></a>
            <a class="project-action" data-action="delete" data-project-id="{{= ID }}"><?php _e( 'Delete', ET_DOMAIN ); ?></a>
        </div>
    <# } #>
</script>