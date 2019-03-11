<link type="text/css" rel="stylesheet" href="images/slide.css">
<script type="text/javascript" src="common/js/silde.js"></script>

<script type="text/JavaScript">
	$(document).ready(function(){
		$("#slider").easySlider({
			firstShow: true,
			lastShow: true,
			auto: true,
			continuous: true 
		});
		$("#slider").css("visibility","visible");
	});
	
</script>

	<div id="slider" style="visibility:hidden">
		<ul>
			<li><a href="<?php $shop_url?>"><img src="<?php echo $site_root;?>images/slide_imgs/01.jpg" alt="Image1" title="1212"/></a></li>
			<li><a href="<?php $shop_url?>"><img src="<?php echo $site_root;?>images/slide_imgs/02.jpg" alt="Image2" /></a></li>
			<li><a href="<?php $shop_url?>"><img src="<?php echo $site_root;?>images/slide_imgs/03.jpg" alt="Image3" /></a></li>
			<li><a href="<?php $shop_url?>"><img src="<?php echo $site_root;?>images/slide_imgs/04.jpg" alt="Image4" /></a></li>
			<li><a href="<?php $shop_url?>"><img src="<?php echo $site_root;?>images/slide_imgs/05.jpg" alt="Image5" /></a></li>
		</ul>
	</div>
	<div class="clear"></div>