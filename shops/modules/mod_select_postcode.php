<?php global $site_root;?>
<link rel="stylesheet" type="text/css" href="<?php echo $site_root;?>/images/modal.css">
<?php 
global $database,$shop;
$sql = "SELECT * FROM oos_deliver_area WHERE store_id=".$shop->id;
$database->setQuery($sql);
$list = $database->loadObjectList();
$row_num = floor(count($list)/2);
if(!$_REQUEST['task']){ 
	?>
	<script>
		jQuery(function ($) {
			$("#select_postcode").modal({
				escClose:false,
				containerCss: {width: 600,height: 400,overflow:'auto'}, 
				onOpen: function (dialog) {
					dialog.overlay.fadeIn('slow', function () {
						dialog.container.slideDown('slow', function () {
							dialog.data.fadeIn('slow');
						});
					});
					$("a.modalCloseImg").css("display","none");
				},
				onClose: function (dialog) {
					dialog.data.fadeOut('slow', function () {
						dialog.container.fadeOut('fast', function () {
							dialog.overlay.fadeOut('fast', function () {
								<?php $_SESSION['shop_enter']=1;?>
								$.modal.close(); // must call this!
							});
						});
					});
				}
			});
			return false;
		});
		function chk_topping(id,price){
			var checked = $("#"+id).attr("checked",true);
			var minPrice = parseFloat($("#min_price").text());
			minPrice += parseFloat(price);
			$("#min_price").text(Math.round(minPrice*100)/100);
			$.modal.close();
		}

	</script>
<div id="select_postcode" style="display:none">
	<div id="notice_title"><span>Select Postcode</span></div>
	<div class="w90" style="margin:0 auto">
		<br>
		<div class="w20 l_float">
			<img src="./images/site_logo.png" width="80" height="60" border="0" alt="">
		</div>
		<div class="w80 l_float"><h5>Please select your delivery area</h5></div>
		<div class="clear"></div>
		<div class="postcode_list c_float w80">
			<div class="l_float w50">
			<?php for($i=0;$i<$row_num;$i++){?>
				<li style="list-style:none">
					<label>
					<input type="radio" name="postcode" id="<?php echo $list[$i]->id;?>" onclick="chk_topping(<?php echo $list[$i]->id;?>,'<?php echo $list[$i]->price;?>')">
					<?php echo $list[$i]->postcode;?> - <?php echo $list[$i]->area_name;?></label>
				</li>
			<?php }?>
			</div>
			<div class="l_float w50">
			<?php for($i=$row_num;$i<count($list);$i++){?>
				<li style="list-style:none">
					<label>
					<input type="radio" name="postcode" id="<?php echo $list[$i]->id;?>" onclick="chk_topping(<?php echo $list[$i]->id;?>,'<?php echo $list[$i]->price;?>')">
					<?php echo $list[$i]->postcode;?> - <?php echo $list[$i]->area_name;?></label>
				</li>
			<?php }?>
			</div>
		</div>
	<div class=""></div>
	</div>
</div>
<script>
	function enterShop(){
		<?php $_SESSION['shop_enter']=1;?>
		$.modal.close();
	};
</script>
<?php
}
?>
