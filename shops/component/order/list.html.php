<?php
$customer_id = $_SESSION['SESS_CUSTOMER_ID'];
if(!$customer_id) getModule('error');
//$shop_id = $shopid;//mosGetParam($_REQUEST,"shop_id","");
global $shopid;
$shop_id = $shopid;
function getOrderList($customer_id,$shop_id){
	global $database;
	$sql = "SELECT o.*,s.name status,r.customer_name,r.rate_star,r.comment,r.rate_date FROM oos_order o";
	$sql .= " LEFT JOIN oos_order_status s ON o.order_status_id = s.id";
	$sql .= " LEFT JOIN oos_rate_data r ON o.id = r.order_id";
	$sql .= " WHERE o.store_id=".$shop_id;
	$sql .= " AND o.customer_id=".$customer_id;
	$sql .= " ORDER By o.id";
	$database->setQuery($sql);
	$result = $database->loadObjectList();
	return $result;
}
$orderList = getOrderList($customer_id,$shop_id);
$rate_str = array(1=>"Very bad",2=>"Bad",3=>"Ok",4=>"Good",5=>"Very good");
?>
<div id="banner_bg">
	<div id="banner">
		<div id="banner_content">
			<div id="categoris" class="l_float"><?php getModule('categorylist');?></div>
			<div id="company_logo" class="r_float"><a href="<?php echo $base_url;?>"><img src="<?php echo $base_url;?>images/default_logo.png" width="94" height="82" /></a></div>
			<!-- <div id="company_title" class="r_float bold">Company Name</div> -->
			<div class="clear"></div>
		</div>
	</div>
</div>
<div id="inner_content">
	<br>
	<form id="contentForm" method="post">
		<table cellpadding="0" cellspacing="0" border="0" class="simpleList wide">
			<tr>
				<td class='header_left'></td>
				<td class='header thin'></td>
				<td class='header category_name'>Order History</td>
				<td class='header_right'></td>
			</tr>
			<tr class='list_body'>
				<td colspan="2"></td>
				<td>
					<div class="l_float w20 bold">
						Order Date
					</div>
					<div class="l_float w30 bold">
						Article
					</div>
					<div class="l_float w30 bold">
						Your Feedback
					</div>
					<div class="clear"></div>
				</td>
				<td></td>
			</tr>
			<?php
				for($i=0;$i<count($orderList);$i++){
					$row = $orderList[$i];
					$date = date("j F Y",strtotime($row->date));
					$star = ($row->rate_star)?$row->rate_star:0;

			?>
			<tr class='list_body'>
				<td colspan="2"></td>
				<td>
					<div class="l_float w20">
						<?php echo $date;?>
					</div>
					<div class="l_float w30 bold">
						Article
					</div>
					<div class="l_float w50" id="feedback_<?php echo $row->id;?>">
					<?php if($star) {?>
						<div class="l_float w50">
							<div class="rating_bg l_float">
								<div class="rating l_float" data="<?php echo $star;?>" isDisabled="on"></div>
								<div class="rating_value l_float bold"><?php echo $star."/5";?></div>
							</div>
							<div class="rating_text l_float bold w30">
								<?php echo $rate_str[$star];?>
							</div>
						</div>
						<div class="l_float wrap" style="width:200px;">
							<?php echo str_replace("\n","<br>",$row->comment);?>
						</div>
					<?php } else { ?>
						<div class="l_float w50">
						<?php for($j=5;$j>0;$j--){?>
							<div class="rating_bg l_float">
								<div class="rating l_float" data="<?php echo $j;?>" isDisabled="on"></div>
								<div class="rating_value l_float bold"><input type="radio" name="rating_sel<?php echo $row->id;?>" value="<?php echo $j;?>"></div>
							</div>
							<div class="rating_text l_float bold w30">
								<?php echo $rate_str[$j];?>
							</div>
							<div class="clear"></div>
						<?php }?>
						</div>
						<div class="l_float">
							<textarea rows="5" cols="20" placeholder="Please enter your comment" class="rate_comment" id="rate_comment<?php echo $row->id;?>"></textarea>
							<div class="text_center"><input type="button" class="feedback_set" value="Post your feedback" orderid="<?php echo $row->id;?>"></div>
							<div class="messageDiv text_center"></div>
						</div>
					<?php }?>
					</div>
					<div class="clear"></div>
				</td>
				<td></td>
			</tr>
			<?php 
				}
			?>

		</table>
		<div id="product_list"></div>
	</form>
	<div class="clear"></div>
</div>
