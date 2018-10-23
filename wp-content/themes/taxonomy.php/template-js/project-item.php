<script type="text/template" id="ae-project-loop">

    <div class="project-list-wrap">
        <h2 class="project-list-title">
            <a href="{{= permalink }}" title="{{= post_title }}">{{= post_title }}</a>
        </h2>
        <div class="project-list-info">
            <span><?php _e('Posted', ET_DOMAIN); ?> {{= post_date }}</span>
            <span>{{= text_total_bid}}</span>
            <# if(text_country != '') {#>
                <span>{{= text_country}}</span>
            <# } #>
            <span>{{= budget}}</span>
        </div>
        <div class="project-list-desc">
            <p>{{= post_content_trim}}</p>
        </div>
        {{= list_skills}}
    </div>

</script>