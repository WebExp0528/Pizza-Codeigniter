<?php
function getSpecialOffer($shop_id,$offer_id){
	global $database;
	$sql = "SELECT off.id offer_id,off.offer_name,off.offer_price,off.extra_product ,cat.*";
	$sql .= " FROM oos_specialoffer off LEFT JOIN oos_specialoffer_contents con ON off.id = con.id_offer";
	$sql .= " LEFT JOIN oos_category cat ON cat.id = con.id_category";
	$sql .= " WHERE off.store_id = ".$shop_id;
	$sql .= " AND off.id=".$offer_id;
	$sql .= " AND cat.is_active = 'Y'";
	$sql .= " ORDER By con.id";
	$database->setQuery($sql);
	$offers = $database->loadObjectList();
	return $offers;
}
function getToppingList($cid,$sid=0){
	global $database;
	$sql = "SELECT * FROM oos_topping";
	$sql .= " WHERE id_category = {$cid}";
	if($sid) $sql .= " AND id_size={$sid}";
	$database->setQuery($sql);
	$result = $database->loadObjectList();
	return $result;
}
//$shop_id = mosGetParam($_REQUEST,"shop_id");
global $shopid;
$offer_id = mosGetParam($_REQUEST, "offer_id");
$offers = getSpecialOffer($shopid, $offer_id);
$offerName = $offers[0]->offer_name;
$offer_price = $offers[0]->offer_price;
?>
<div class="offer_title"><h1><?php echo $offerName;?></h1></div>
	<div class="w90 c_float header1 clear bold" >
		<div class="w80 l_float">
			<?php echo $offerName;?>
			<?php $extra = explode("\n",$offers[0]->extra_product);
				for($i=0;$i<count($extra);$i++){
					if(!$extra[$i]) continue;
					echo " + ".$extra[$i];
				}
			?>
		</div>
		<div class="w20 l_float">€ <span id="offer_price"><?php echo $offer_price;?></span></div>
	</div>
<?php
$index = 0;
for($i=0;$i<count($offers);$i++){
	$productList = getOfferProductList($offers[$i]->id);
	if(!count($productList)) continue;
	?>
	<div class="w90 c_float header1 hide clear" id="selected_offer<?php echo $index;?>">
		<div class="l_float w30">Please Select <?php echo $index+1;?>st Product : </div>
		<div class="l_float w50" id="selected_offer_product<?php echo $index;?>"></div>
		<input type="hidden" id="selected_offer_product_value_<?php echo $index;?>">
	</div>
	<div class="w90 c_float header1 hide clear offer_topping_list" id="selected_topping<?php echo $index;?>" index="<?php echo $index;?>">
		<div class="l_float w30">Extra topping : </div>
		<div class="l_float w50 wrap" id="selected_offer_topping<?php echo $index;?>">&nbsp;</div>
		<div class="l_float w20">€ <span class="sub_topping_price">0</span></div>
	</div>
	<div class="clear"></div>
<?php $index++;
}
?>
<div class="w90 c_float cart_step">
	<div class="w10 l_float">&nbsp;</div>
	<div class="w20 l_float">
		<a class="step_nav l_float block" id="prev_step">< Previous Step</a>
	</div>
	<div class="w20 l_float">
		<a class="step_nav l_float block" id="next_step">Next Step ></a>
	</div>
	<div class="w30 l_float">
		<div class="w10 select_arrow l_float"></div>
		<div class="l_float"><a class="step_nav" id="add_special_offer">Add to Cart</a></div>
	</div>
	<div class="w20 l_float">€ <span id="total_offer_price"><?php echo $offer_price;?></span></div>
	<div class="clear"></div>
</div>
<div class="clear"></div>

<?php 
$index = 0;
for($i=0;$i<count($offers);$i++){
	$category_name = $offers[$i]->name;
	$productList = getOfferProductList($offers[$i]->id);
	if(!count($productList)) continue;
	$toppinglist = getToppingList($offers[$i]->id);

?>
	<div class="offer_category w80 c_float hide" id="offer_category<?php echo $index;?>">
		<div class="offer_category_title" id="offer_category_title<?php echo $offers[$index]->id;?>">
			<h2>Please Select <?php echo $index+1;?>st for <?php echo $category_name;?></h2>
		</div>
	<?php 
		for($j=0;$j<count($productList);$j++){
		?>
			<div class="l_float w50">
				<a href="javascript:void(0)" class="product_name" onclick="select_offer_product(<?php echo $index.','.$productList[$j]->id.',\''.$productList[$j]->name.'\','.count($toppinglist);?>)" <?php echo count($toppinglist)?>"><?php echo $productList[$j]->name; ?></a>
			</div>

	<?php }?>
	</div>
	<div class="clear"></div>
	<div class="topping_list w70 c_float hide" id="offer_topping<?php echo $index?>">
		<h4>Please select extra topping</h4>
		<?php for($j=0;$j<count($toppinglist);$j++){
			$row = $toppinglist[$j];
		?>
			<div class="l_float w50">
				<div class="l_float w40">
					<a href="javascript:void(0)" class="topping_name" onclick="select_topping(<?php echo $index.','.$row->id.',\''.$row->topping_name.'\','.$row->price_add;?>)"><?php echo $row->topping_name; ?></a>
				</div>
				<div class="l_float w40">
					€ <?php echo $row->price_add;?>
				</div>
			</div>
		<?php }?>
	</div>
	<div class="clear"></div>
	<input type="hidden" class="offer_select_val" id="offer_select_val<?php echo $offers[$index]->id;?>" value="">

<?php $index++;
}?>
	<div class="horiz_separator wide"></div>
<input type="hidden" id="offer_id" value="<?php echo $offers[0]->offer_id;?>">
<input type="hidden" id="total_cat_num" value="<?php echo $index;?>">
<input type="hidden" id="current_step" value="">
<div class="clear"></div>


