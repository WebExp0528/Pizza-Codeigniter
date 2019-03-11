<?php
$session_id = session_id();
$customer_id = isset($_SESSION['SESS_CUSTOMER_ID']) ? $_SESSION['SESS_CUSTOMER_ID'] : '';
//$shop_id = $shopid;//mosGetParam($_REQUEST,"shop_id","");
global $shopid;
$shop_id = $shopid;
function getProduct($pid,$sid=""){
	global $database;
	$sql = "SELECT p.*,c.price,s.name size,s.desc size_detail FROM oos_product p";
	$sql .= " LEFT JOIN oos_price c ON c.id_product=p.id LEFT JOIN oos_size s ON s.id=c.id_size";
	$sql .= " WHERE p.id={$pid}";
	if($sid) $sql .= " AND c.id_size={$sid}";
	$database->setQuery($sql);
	$database->loadObject($result);
	return $result;
}
function getToppingById($id){
	global $database;
	$sql = "SELECT * FROM oos_topping WHERE id = {$id}";
	$database->setQuery($sql);
	$database->loadObject($result);
	return $result;
}
function getSpecialOffer($id,$shop_id){
	global $database;
	$sql = "SELECT off.id offer_id,off.offer_name,off.offer_price,off.extra_product,cat.*";
	$sql .= "FROM oos_specialoffer off LEFT JOIN oos_specialoffer_contents con ON off.id = con.id_offer JOIN oos_category cat ON cat.id = con.id_category";
	$sql .= " WHERE off.store_id = ".$shop_id;
	$sql .= " AND off.id=".$id;
	$sql .= " AND cat.is_active = 'Y'";
	$database->setQuery($sql);
	$offers = $database->loadObjectList();
	return $offers;
}
$customer = getCustomerData($customer_id);
if(!$customer){
	$gender = "male";
} else {
	$gender = $customer->gender;
}
?>
<div class="">Please check again your order and enter your delivery data.</div>
<form id="order_form">
<div class="odr_data w60">
	<h3>Your Order Details</h3>
	<table class="w98" align="center" cellpadding=1 cellspacing=0 border=0>
		<tr>
			<td><label for="gender">Gender</label></td>
			<td colspan=3><label><input type="radio" value="male" name="gender" <?php if($gender=="male") echo "checked";?>>male</label>
				<label><input type="radio" value="female" name="gender" <?php if($gender=="female") echo "checked";?>>female</label></td>
		</tr>
		<tr>
			<td class="w20"><label for="firstname">First name<span class="required">*</span></label></td>
			<td class="w30"><input id="firstname" class="contact w80" type="text" maxlength="64" name="firstname" value="<?php echo isset($customer->firstname) ? $customer->firstname : '';?>"></td>
			<td class="w20"><label for="lastname">Last Name<span class="required">*</span></label></td>
			<td class="w30"><input id="lastname" class="contact w80" type="text" maxlength="64" name="lastname" value="<?php echo isset($customer->lastname) ? $customer->lastname : '';?>"></td>
		</tr>
		<tr><td><label for="company">Company</label></td>
			<td><input id="company" class="contact w80" type="text" maxlength="64" name="company" value="<?php echo isset($customer->company) ? $customer->company : '';?>"></td>
			<td colspan=2></td>
		</tr>
		<tr>
			<td><label for="street">Street<span class="required">*</span></label></td>
			<td><input id="street" class="contact w80" type="text" maxlength="64" name="street" value="<?php echo isset($customer->street) ? $customer->street: '';?>"></td>
			<td><label for="house_no">House No<span class="required">*</span></label></td>
			<td><input id="house_no" class="contact w80" type="text" maxlength="64" name="house_no" value="<?php echo isset($customer->house_no) ? $customer->house_no: '';?>"></td>
		</tr>
		<tr>
			<td><label for="postcode">Postcode<span class="required">*</span></label></td>
			<td><input id="postcode" class="contact w80" type="text" maxlength="12" name="postcode" value="<?php echo isset($customer->postcode) ? $customer->postcode: '';?>"></td>
			<td><label for="city">City<span class="required">*</span></label></td>
			<td><input id="city" class="contact w80" type="text" maxlength="12" name="city" value="<?php echo isset($customer->city) ? $customer->city: '';?>"></td>
		</tr>
		 <tr><td><label for="add_details">Additional address</label></td>
			<td colspan=3><input id="add_details" class="contact w66" wrap="PHYSICAL" rows="2" cols="33" name="add_details" value="<?php echo isset($customer->add_details) ? $customer->add_details: '';?>"></td>
		</tr> 
		<tr><td><label for="telephone">Phone number<span class="required">*</span></label></td>
			<td><input id="telephone" class="contact w80" type="text" maxlength="64" name="telephone" value="<?php echo isset($customer->telephone) ? $customer->telephone: '';?>"></td>
			<td><label for="email">Email Address<span class="required">*</span></label></td>
			<td><input id="email" class="contact w80" type="text" maxlength="64" name="email" value="<?php echo isset($customer->email) ? $customer->email: '';?>"></td>
		</tr>
		<tr><td colspan=4>
			<div class="order_notice wide">Please enter a valid phone number!
			Orders without a valid phone number can not be processed by us!</div>
		</td></tr>
		<tr><td><label for="details">Special note / Delivery time</label></td>
			<td colspan=3><textarea id="details" class="contact w66" wrap="PHYSICAL" rows="2" cols="33" name="details"></textarea></td>
		</tr>
		<tr><td colspan=4>
			<div class="order_notice wide">Mandatory information marked with <span class="required">*</span> marks.</div>
		</td></tr>
		<tr><td colspan=4>
			<input type="checkbox" id="user_accept">
			<label for="user_accept">I accept the terms and conditions of delivery.</label>
		</td></tr>
		<tr><td colspan=4>
		<div id="messageDiv" class="w90"></div>
		</td></tr>
	</table>
</div>
<div class="odr_data w40">
	<h3>Order summary</h3>
	<div style="height:400px;overflow:auto;border:1px solid #e5e5e5;">
	<table class="summaryList w98" align="center" cellpadding=1 cellspacing=0>
		<tr>
			<td class="header thin"></td>
			<td class="header w80" colspan=3>Product</td>
			<td class="header w15">Price</td>
		</tr>
		<?php 
		$sum_price = 0;
		$session_id = session_id();
		$data = getSessionData($session_id, $shop_id);
		if(count($data)) $data = explode(";",$data->order_data);
		for($i=count($data)-1;$i>=0;$i--){
			$row = explode(",",$data[$i]);
			if($row[0]==1){
				$offer = getSpecialOffer($row[1],$shop_id);
				$price = $offer[0]->offer_price;
				$productlist .= '';
				$product_ids = explode("|",$row[2]);
				for($j=0;$j<count($product_ids);$j++){
					$product_id = explode(":",$product_ids[$j]);
					$product = getProduct($product_id[0]);
					$productlist .= '<tr><td class="thin"></td>';
					$productlist .= '<td align="right">+</td>';
					$productlist .= '<td colspan="2" class="w75">'.$product->name.'</td>';
					$productlist .= '<td class="w20 over_line">€ '.$product->price.'</td>';
					$productlist .= '</tr>';
					$topping_ids = explode(" ",$product_id[1]);
					for($k=0;$k<count($topping_ids);$k++){
						if(!$topping_ids[$k]) continue;
						$_t = getToppingById($topping_ids[$k]);
						$productlist .= '<tr><td class="thin"></td>';
						$productlist .= '<td align="right">+</td>';
						$productlist .= '<td colspan="2" class="topping_data w75">'.$_t->topping_name.'</td>';
						$productlist .= '<td class="w15">€ '.$_t->price_add.'</td>';
						$productlist .= '</tr>';
						$price += $_t->price_add;
					}
				}
				$extra = explode("\n",$offer[0]->extra_product);
				for($j=0;$j<count($extra);$j++){
					if(!$extra[$j]) continue;
					$productlist .= '<tr><td class="thin"></td>';
					$productlist .= '<td align="right">+</td>';
					$productlist .= '<td colspan="2" class="w75">'.$extra[$j].'</td>';
					$productlist .= '<td class="w20">addition</td>';
					$productlist .= '</tr>';
				}
			?>
				<tr class='list_body'><td class="thin"></td>
				<td class="w5" align="center"><?php echo $row[3];?>&nbsp;x</td>
				<td class="product_title w33"><?php echo $offer[0]->offer_name;?></td>
				<td class="description w33">Special offer</td>
				<td class="product_prce w15" align="left">€&nbsp;<span class="product_prce"><?php echo $price;?></span></td>
				</tr>
			<?php echo $productlist;
				$sum_price += $price*$row[3];
			} else if($row[0]==2){
				$product = getProduct($row[1],$row[2]);
				$price = $product->price;
				$toppinglist = "";
				if(!empty($row[4])) {
					$topping_ids = explode(":",$row[4]);
					for($j=0;$j<count($topping_ids);$j++){
						$_t = getToppingById($topping_ids[$j]);
						$toppinglist .= '<tr><td class="thin"></td>';
						$toppinglist .= '<td align="right">+</td>';
						$toppinglist .= '<td colspan="2" class="topping_data w75">'.$_t->topping_name .'</td>';
						$toppinglist .= '<td class="w15">€ '.$_t->price_add.'</td></tr>';
						$price += $_t->price_add;
					}
				}
			?>
				<tr class='list_body'><td class="thin"></td>
				<td class="w5" align="center"><?php echo $row[3];?>&nbsp;x</td>
				<td class="product_title w33"><?php echo $product->name;?></td>
				<td class="description w33"><?php echo $product->size;?></td>
				<td class="product_prce w15" align="left">€&nbsp;<span class="product_prce"><?php echo $price;?></span></td>
				</tr>
				<?php echo $toppinglist;
				$sum_price += $price*$row[3];
			}
		}
		?>
		<tr>
			<td class="list_body thin"></td><td class="list_body" colspan=3>Deliver Charge</td>
			<td class="list_body">€&nbsp;<input type="text" name="delivery_charge" value="0" style="width:30px;border:0px;background:transparent;font-size:11px;" readonly></td>
		</tr>
		<tr><td class="footer thin"></td><td class="footer" colspan=3>Summe</td>
		<td class="footer">€&nbsp;<span id="total_price"><?php echo $sum_price;?></span><input type="hidden" name="total" value="<?php echo $sum_price;?>"></td></tr>
	</table>
	</div>
</div>
<div class="clear"></div>
</form>
<br>
<div align="center" class="w50" style="margin:0 auto;">
	<div class="odr_data w50"><a href="javascript:void(0)" class="order_check" onclick="order.order_data_store()">Order Now</a></div>
	<div class="odr_data w50"><a href="javascript:void(0)" class="order_check" onclick="order_cancel()">Cancel</a></div>
</div>
<br>
