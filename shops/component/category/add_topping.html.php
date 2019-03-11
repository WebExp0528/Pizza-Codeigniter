<?php
function getProduct($pid,$sid){
	global $database;
	$sql = "SELECT p.*,c.price,s.name size,s.desc size_detail FROM oos_product p";
	$sql .= " LEFT JOIN oos_price c ON c.id_product=p.id LEFT JOIN oos_size s ON s.id=c.id_size";
	$sql .= " WHERE p.id={$pid} AND c.id_size={$sid}";
	$sql .= " AND p.is_active = 'Y'";
	$database->setQuery($sql);
	$database->loadObject($result);
	return $result;
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
function getToppingData($pid,$sid){
	global $shop;
	$session_id = session_id();
	$result = getSessionData($session_id,$shop->id);
	$data = $topping_ids = array();
	if(count($result->order_data)) $data = explode(";",$result->order_data);
	for($i=0;$i<count($data);$i++){
		$row = explode(",",$data[$i]);
		if($row[0]!=2) continue;
		if($pid==$row[1]&&$sid==$row[2]) {
			$topping_ids = explode(":",$row[4]);
		}
	}
	return $topping_ids;
}
$pid = mosGetParam($_REQUEST,"pid");
$sid = mosGetParam($_REQUEST,"sid");
$product = getProduct($pid,$sid);
$toppingData = getToppingData($pid,$sid);
$toppinglist = getToppingList($product->id_category,$sid);
$toppingNameList = array();
if(count($toppinglist)) {
	foreach($toppinglist as $topping){
		$toppingNameList[$topping->id] = $topping;
	}
}
$cnt = count($toppinglist);
$row_num = ceil($cnt/3);
$col_num = 3;
$flag = $index = 0;
?>
<table class="simpleList w90" align="center" border="0" cellpadding="3px" style="border:1px solid #d7e1e3;">
	<tr style="background:#f9feff;">
		<td class="thin" align="left"></td>
		<td class="w80" align="left" colspan="2"><?php echo $product->name;?></td>
		<td class="w10" align="center">€ <span id="product_price"><?php echo $product->price;?></span></td>
	</tr>
	<tr>
		<td align="left"></td>
		<td class="w15" align="left">Topping</td>
		<td align="left"><div id="modal_add_topping" style="width:380px;word-wrap: break-word;">
			<?php for($i=0;$i<count($toppingData);$i++){
				$id = $toppingData[$i];
				$str = "<span>";
				$str .= '<a href="javascript:void(0)" class="added_topping" onclick="remove_topping(this)" alt="'.$id.'" price="'.$toppingNameList[$id]->price_add 	.'">'.$toppingNameList[$id]->topping_name;
				$str .= ",";
				$str .= "</a></span>";
				if($id) echo $str;
			}
			?>
		</div></td>
		<td align="center"><span id="added_topping_price"><?php echo $product->price;?></span></td>
	</tr>
	<tr style="background: #166D79;color:#ffffff">
		<td align="left"></td>
		<td align="left">Summe</td>
		<td>
			<div class="w50" style="float:left;">&nbsp;</div>
			<div style="float:left;">
			<div class="w10 select_arrow" style="float:left;"></div>
			<div style="float:left"><a href="javascript:void(0)" class="add_cart" onclick="add_cart_topping(<?php echo $pid.",".$sid;?>)">Add to Cart</a></div>
			</div>
		</td>
		<td align="center">€ <span id="added_topping_sum_price"><?php echo $product->price;?></span>
		</td>
	</tr>
</table>
<br>
<div class="clear"></div>
<div class="topping_list" class="w90">
	<?php for($i=0;$i<$col_num;$i++){?>
		<ul>
			<?php for($j=0;$j<$row_num;$j++){
				$row = $toppinglist[$index];
				
				?>
				<table class="w90" cellpadding="0" cellspacing="0" border=0 style="border-bottom:1px solid #d7ffff"><tr>
					<td class="w66">
						<a href="javascript:void(0)" class="topping_name" onclick="chk_topping(<?php echo $row->id;?>,'<?php echo $row->topping_name;?>',<?php echo $row->price_add;?>)"><?php echo $row->topping_name; ?></a>
					</td>
					<td class="w33">
						€ <?php echo $row->price_add;?>
					</td>
				</tr></table>
			<?php 
			$index++;
			if($index == $cnt) {$flag= 1;break;}
			}?>
		</ul>
	<?php if($flag == 1) break;}?>
</div>
<div class="clear"></div>


