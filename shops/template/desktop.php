<?php 
?>
<html>
	<head>
		<title><?php echo $site_name;?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>" />
		<link rel="stylesheet" type="text/css" href="template/common.css">
	</head>
<body border=0 align="center" style="margin:0;padding:0;" id="documentBody">
<center>
<table align="center" width="100%" id="desktoptable_footer" cellspacing="0" cellpadding="1">
<tr>
	<td>
		<a href="<?php $shop_url?>">Home</a> | 
		<a href="<?php $shop_url?>?task=user&request=signup&special=2" value=>Sign Up</a> | 
		<a href="<?php $shop_url?>?task=logout" value=>Log out</a>
	</td>
</tr>
</table>
<input id="userId" type="hidden" value="<?=$_SESSION["user_login_id"]; ?>">
</center>
</body>

</html>