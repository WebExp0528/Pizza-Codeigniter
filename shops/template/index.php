<?php 
	$impressurl = $shop_url."/Impressum.html";
	$feedbackurl = $shop_url."/feedback";
	$orderurl = $shop_url."/order";
	$customer_id = isset($_SESSION['SESS_CUSTOMER_ID']) ? $_SESSION['SESS_CUSTOMER_ID'] : '';
?>
<div id="top_menu">
	<div class="topmenuPane">
		<div class="l_float w80">
			<a href="<?php echo $impressurl;?>" class="topmenu">Impressum</a><span class="menuSeparator">|</span>
			<a href="#" class="topmenu"><?php echo $shop->shop_name;?></a><span class="menuSeparator">|</span>
			<a href="#" class="topmenu"><?php echo $shop->address;?></a><span class="menuSeparator">|</span>
			<a href="#" class="topmenu"><?php echo $shop->postcode."&nbsp;".$shop->city;?></a>
		</div>
		<div class="r_float w20">
			<a href="<?php echo $feedbackurl;?>" class="topmenu">Feedback</a>
			<?php if($customer_id) {?>
			<span class="menuSeparator">|</span>
			<a href="<?php echo $orderurl;?>" class="topmenu">My Orders</a>
			<?php } ?>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div id="osx-modal-content" style="display:none">
	<div id="osx-modal-title">Notice Modal Dialog</div>
	<div class="close"><a href="#" class="simplemodal-close">x</a></div>
	<div id="osx-modal-data"></div>
</div>
