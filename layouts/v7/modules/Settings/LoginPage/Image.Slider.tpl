<script>
	jQuery(document).ready(function(){
		scrollx = jQuery(window).outerWidth();
		window.scrollTo(scrollx,0);
		slider = jQuery('.bxslider').bxSlider({
		auto: true,
		pause: 4000,
		randomStart : true,
		autoHover: true
	});
	jQuery('.bx-prev, .bx-next, .bx-pager-item').live('click',function(){ slider.startAuto(); });
	}); 
</script>
<div class="span4">
	<div class="carousal-container" style="width:380px;">
		<div><h2> Image Slider </h2></div>
		<ul class="bxslider">
			<li>
				<div id="slide01" class="slide">
					<img class="" src="layouts/images/Image_from_Skype.jpg">
					
				</div>
			</li>
		</ul>
	</div>
</div>
