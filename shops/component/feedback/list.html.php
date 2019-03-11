<?php
//$shop_id = mosGetParam($_REQUEST,"shop_id","");
global $shopid;
$shop_id = $shopid;
function getTotalRating($shop_id){
	global $database;
	$sql = "SELECT SUM( rate_star ) sum, COUNT( id ) count FROM oos_rate_data";
	$sql .= " WHERE store_id = {$shop_id}";
	$database->setQuery($sql);
	$database->loadObject($result);
	return $result;
}
function getRatingList($shop_id){
	global $database;
	$sql = "SELECT * FROM oos_rate_data";
	$sql .= " WHERE store_id = {$shop_id}";
	$sql .= " ORDER By rate_date DESC";
	$database->setQuery($sql);
	$result = $database->loadObjectList();
	return $result;
}
$rateMax = 5;
$total = getTotalRating($shop_id);
$total_rate_sum = ($total) ? $total->sum: 0;
$total_count = ($total) ? $total->count : 1;
$overage_rating = round($total_rate_sum/$total_count,1);
$ratingList = getRatingList($shop_id);
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
				<td class='header category_name'>FeedBack</td>
				<td class='header_right'></td>
			</tr>
			<tr class='list_body'>
				<td colspan="2"></td>
				<td>
					<div class="rating_bg l_float">
						<div class="rating l_float" data="<?php echo $overage_rating;?>"></div>
						<div class="rating_value l_float bold"><?php echo $overage_rating."/5";?></div>
					</div>
					<div class="l_float w10">&nbsp;</div>
					<div class="rating_text l_float w50 bold">
						TOTAL OVERAGE RATING <?php echo $overage_rating;?> OF <?php echo $total_count;?> REVIEWS
					</div>
				</td>
				<td></td>
			</tr>
			<?php
				for($i=0;$i<count($ratingList);$i++){
					$row = $ratingList[$i];
					$date = date("j F Y",strtotime($row->rate_date));

			?>
			<tr class='list_body'>
				<td colspan="2"></td>
				<td>
					<div class="l_float w20">
						<div class="rating_bg">
							<div class="rating l_float" data="<?php echo $row->rate_star;?>"></div>
							<div class="rating_value l_float bold"><?php echo $row->rate_star."/5";?></div>
						</div>
					</div>
					<div class="rating_text l_float w15 bold">
						<?php echo $row->customer_name;?> 
					</div>
					<div class="rating_text l_float w20 bold">
						<?php echo $date;?>
					</div>
					<div class="l_float w33">
						<div class="l_float thin">&nbsp;</div>
						<div class="l_float w90 wrap"><?php echo str_replace("\n","<br>",$row->comment);?></div>
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
<br>
