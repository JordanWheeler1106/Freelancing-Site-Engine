<?php 
/**
 *	Template Name: Cancel Payment
 */
$session	=	et_read_session ();
get_header();

if(isset($session['project_id'])){
	$url = get_the_permalink($session['project_id']);
}else if(isset($_REQUEST['returnUrl'])){
	$url = $_REQUEST['returnUrl'];
}else{
	$url = home_url();
}

?>
<section class="blog-header-container">
	<div class="container">
		<!-- blog header -->
		<div class="row">
		    <div class="col-md-12 blog-classic-top">
		        <h2><?php the_title(); ?></h2>
		    </div>
		</div>
		<!--// blog header  -->
	</div>
</section>
<!-- Page Blog -->
<section id="blog-page">
	<div class="container page-container">
		<!-- block control  -->
		<div class="row block-posts block-page">
			<div class="col-md-12 col-sm-12 col-xs-12">
	            <div class="content-cancel-payment">
	            	<h2><?php _e("CANCELLING PAYMENT", ET_DOMAIN);?></h2>
	            	<p class="sub-text"><?php _e('Redirect to the homepage or project detail page within a <span class="count_down">10</span> seconds', ET_DOMAIN);?></p>
	            	<div class="content-footer">
	            		<a class="fre-btn" href="<?php echo $url;?>"><?php _e('Click here', ET_DOMAIN);?></a>
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
</section>

<script type="text/javascript">
  	jQuery(document).ready (function () {
  		var $count_down	=	jQuery('.count_down');
		setTimeout (function () {
			window.location = '<?php echo $url; ?>';
		}, 10000 );
		setInterval (function () {
			if($count_down.length > 0) {
				var i	=	 $count_down.html();
				$count_down.html(parseInt(i) -1 );
			}
		}, 1000 );
  	});
</script>
<?php 
et_destroy_session();
get_footer();
?>