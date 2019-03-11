<?php global $site_root;?>
<link rel="stylesheet" type="text/css" href="<?php echo $site_root;?>/images/modal.css">
<?php 
date_default_timezone_set('Europe/Berlin');
$cur_time = date("H:i");
$cur_weekday = date("N");
if($cur_weekday==7) $cur_weekday = 0;
global $database;
$sql = "SELECT * FROM oos_workinghours WHERE day_number=".$cur_weekday;
$database->setQuery($sql);
$database->loadObject($workhour);
if(($cur_time<$workhour->workinghour_from||$cur_time<$workhour->workinghour_to)&&$_SESSION['shop_enter']!=1) {
	?>
	<script>
		jQuery(function ($) {
			$("#basic-modal-content").modal({
				onOpen: function (dialog) {
					dialog.overlay.fadeIn('slow', function () {
						dialog.container.slideDown('slow', function () {
							dialog.data.fadeIn('slow');
						});
					});
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
	</script>
<?php
	}
?>
<div id="basic-modal-content" style="display:none">
	<div id="notice_title"><span>Delivery Note</span></div>
	<div id="notice_content">
		<div class="w75" style="float:left">
		<h3>Our Shop is Currently Closed.</h3>
		<span class="notice">The delivery times are <b>from <?php echo $workhour->workinghour_from;?> to <?php echo $workhour->workinghour_to;?></b>.</span>
		<br>
		<br>
		<span>Do you want to place a pre-order?</span>
		</br>
		</br>
		<center><a class="enter_button" onclick="enterShop()">Enter th shop</a></center>
		</div>
		<div class="w25" style="float:left" align="center">
		<img src="<?php echo $site_root;?>/images/notice.gif" width="50" height="50" border="0" alt="">
		</div>
	</div>
</div>
<script>
	function enterShop(){
		<?php $_SESSION['shop_enter']=1;?>
		$.modal.close();
	};
</script>
