<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 
$cat_id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
$category = getCategory($shop->id, $cat_id);
$keywords = array();
$keywords[] = $site_name;
$keywords[] = $shop->shop_name;
if ($category) {
	$keywords[] = $category->seo_name;
	$keywords[] = $category->name;
}
if(!$shop->style_url || $shop->style_url == "default.css")
	$css_file = $site_root."template/default.css";
else 
	$css_file = $site_root."template/style/".$shop->style_url;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
		<meta http-equiv="keywords" content="<?php echo implode(",",$keywords);?>"/>
		<title><?php echo $shop->shop_name." - ".$site_name;?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo $site_root;?>template/osx_modal.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $site_root;?>template/jquery.autocomplete.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $site_root;?>template/jquery.jRating.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css_file;?>">
		<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/jquery-1.6.2.min.js"></script>
		<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/jquery.simplemodal.js"></script>
		<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/dhtmlxcommon.js"></script>
		<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/jquery.autocomplete.js"></script>
		<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/jquery.jRating.js"></script>
		<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/jquery.placeholder.js"></script>
		<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/ajax.js"></script>
		<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/common.js"></script>
		<script>
			commonUrl = "<?php echo $shop_url;?>";
		</script>
	</head>
<body>
<input type="hidden" id="shop_id" name="shop_id"  value="<?php echo $shopid;?>">
<div id="progressbar"></div>
