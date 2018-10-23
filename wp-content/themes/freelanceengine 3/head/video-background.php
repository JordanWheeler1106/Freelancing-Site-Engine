
<?php 
    $video = ae_get_option('header_video');
    $fall_back = ae_get_option('header_video_fallback');
?>
<!-- SLIDER -->


<div id="video-background-wrapper" class="covervid-wrapper" style="width: 100%; height:800px;position: relative;
min-width: 500px; background: url(<?php echo $fall_back; ?>) no-repeat center center; background-size :cover;" >
<?php if(!et_load_tablet()) { ?> 
    <video id="covervid-video" style="height:100%;" class="covervid-video" autoplay 
        <?php if(ae_get_option('header_video_loop', true)) { echo 'loop'; } ?> 
        poster="<?php echo $fall_back; ?>"
    >
        <?php if( $video ) { ?>
        <source src="<?php echo $video; ?>" type="video/mp4">
        <?php } ?>
    </video>
<?php } ?>
    <div class="bg-sub-wrapper">
        <div class="bg-color-wrapper">
            <?php 
                if(!is_user_logged_in()){
                    get_template_part('head/nologin', 'demonstration');
                }else{
                    if( ae_user_role() == FREELANCER ) {
                        get_template_part('head/freelancer', 'demonstration');
                    }else {
                        get_template_part('head/employer', 'demonstration');
                    }
                }
            ?>
        </div>
    </div>
</div>
<!-- SLIDER / END -->

<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            var h = $(window).height(),
                w = $(window).width();
            $('.covervid-wrapper, .bg-sub-wrapper').css({'height':h+'px'});
            <?php if(!et_load_tablet()) { ?>
            var coverVid=function(a,b,c){function d(a,b){var c=null;return function(){var d=this,e=arguments;window.clearTimeout(c),c=window.setTimeout(function(){a.apply(d,e)},b)}}function e(){var d=a.parentNode.offsetHeight,e=a.parentNode.offsetWidth,f=b,g=c,h=d/g,i=e/f;i>h?(a.style.height="auto",a.style.width=e+"px"):(a.style.height=d+"px",a.style.width="auto")}document.addEventListener("DOMContentLoaded",e),window.onresize=function(){d(e(),50)},a.style.position="absolute",a.style.top="50%",a.style.left="50%",a.style["-webkit-transform"]="translate(-50%, -50%)",a.style["-ms-transform"]="translate(-50%, -50%)",a.style.transform="translate(-50%, -50%)",a.parentNode.style.overflow="hidden"};window.jQuery&&jQuery.fn.extend({coverVid:function(){return coverVid(this[0],arguments[0],arguments[1]),this}}); 
            $('.covervid-video').coverVid(w, h);
            <?php } ?>
        });
    })(jQuery);
</script>
<?php 