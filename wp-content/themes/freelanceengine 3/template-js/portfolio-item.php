<!--<script type="text/template" id="ae-portfolio-loop">
	<a href="{{= the_post_thumbnail_full }}" title="{{= post_title }}" class="image-gallery">
		<img src="{{= the_post_thumbnail }}" >
	</a>
	<a href="#" class="delete">
		<i class="fa fa-trash-o"></i>
	</a>
</script>-->

<script type="text/template" id="ae-portfolio-loop">
    <div class="freelance-portfolio-wrap" id="portfolio_item_{{= ID }}">
        <div class="freelance-portfolio">
            <a href="javascript:void(0)" class="fre-view-portfolio-new" data-id="{{= ID }}"> <img src="{{= the_post_thumbnail_full }}"
                                               alt="{{= post_title }}"> </a>
        </div>
        <div class="portfolio-title">
            <a class="fre-view-portfolio-new" href="javascript:void(0)"
               data-id="{{= ID }}"> {{= post_title }}</a>
        </div>
	    <?php
        if( is_page_template( 'page-profile.php' )){ ?>
            <div class="portfolio-action">
                <a href="javascript:void(0)" class="edit-portfolio" data-id="{{= ID }}"><i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i><?php _e('Edit',ET_DOMAIN) ?></a>
                <a href="javascript:void(0)" class="remove_portfolio" data-portfolio_id="{{= ID }}"><i
                            class="fa fa-trash-o" aria-hidden="true"></i><?php _e('Remove',ET_DOMAIN) ?></a>
            </div>
        <?php } ?>

    </div>
</script>