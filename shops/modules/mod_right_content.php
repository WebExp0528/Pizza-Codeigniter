<?php
global $database, $shop;
//$shop_id = mosGetParam($_REQUEST,"shop_id","");
$sql = "SELECT min_price FROM oos_setting";
$sql .= " WHERE store_id = ".$shop->id;
$database->setQuery($sql);
$min_price = $database->loadResult();
?>
<div id="order_list">
	<div id="cart_title">
		<span class="cart_title">WARENKORB</span>
	</div>
	<div id="order_title_separater"></div>
	<div class="min_price_notice wide">Zwischensumme<br>
		Mindestbetellwert €<span id="min_price"> <?php echo $min_price;?></span> , Differzbetrag
	</div>
	<div class="totalsum w90">
		<div class="totalsum_left"></div>
		<div class="totalsum_content w90"><div class="w75">&emsp;Summe</div><div class="w5">€</div><div id="total_selected_sum">0</div></div>
		<div class="totalsum_right"></div>
		<div class="clear"></div>
	</div>
	<div class="min_price_notice wide">Vestibulum ante ipsum primis in<br>
		faucibus orci luctus et ultrices posuere
	</div>
	<br>
	<center><a href="javascript:void(0)" class="order_check" onclick="order_checkout()">Vestibulum</a></center>
	<center><div class="order_button_shadow"></div></center>
	<br>
</div>
<div class="right_footer"></div>
