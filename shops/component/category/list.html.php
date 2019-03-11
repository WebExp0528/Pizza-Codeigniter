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
	<div id="left_content">
		<form id="contentForm" method="post">
			<div id="category_img_pane">
				<img id="category_img" src="" width="700" height="168" border="0" alt="Category Image">
				<img id="category_img_frame" src="<?php echo $site_root;?>images/category_img_frame.png" width="700" height="168" border="0" alt="Category Image">
			</div>
			<div id="product_list"></div>
		</form>
	</div>
	<div id="right_content">
		<?php getModule('right_content');?>
	</div>
	<div class="clear"></div>
</div>
<br>
<?php 
$list = getCategoryList($shop->id);
$cat_id = ($list) ? $list[0]->id : '';
$cat_id = mosGetParam( $_REQUEST, 'catid', $cat_id);
$op = mosGetParam( $_REQUEST, 'op', 'category');?>
<script>
	var cat_id = <?php echo $cat_id;?>;
	var op = '<?php echo $op;?>';
</script>
<!-- <input type="hidden" id="cat_id" value="<?php echo $cat_id;?>"> -->
<iframe id="send_fax" src="" style="display:none"></iframe>
