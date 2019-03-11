<?php
$shopname = (isset($_GET['shopname'])) ? $_GET['shopname'] : "";

if (empty($shopname))
{
	header('location: index.php');
}

include ("common/configration.php");
include ("common/database.php");
include ("common/dbclass.php");
include ("common/common.php");

$includeLink = "";
$contentLink = "";

$shopid = '';
$shopname = mosGetParam($_REQUEST, 'shopname', '');
$shopid = getStoreID($shopname);

if (empty($shopid))
{
	header('location: index.php');
}

$shop = getShopData($shopid);

if (!$shop)
{
	getModule('error');
	exit();
}

$TASK = mosGetParam($_REQUEST, 'task', 'category');
$REQUEST = mosGetParam($_REQUEST, 'request', 'list');
$SPECIAL = mosGetParam($_REQUEST, 'special', '2');

switch ($SPECIAL)
{
	case "1":
		$includeLink = "component/" . $TASK . "/" . $REQUEST;
		if (!file_exists($includeLink . ".php"))
		{
			getModule('error');
		}
		else
			include ($includeLink . ".php");
		break;
	case "2":
		$includeLink = "component/" . $TASK . "/" . $REQUEST;
		if (!file_exists($includeLink . ".html.php"))
		{
			getModule('error');
		}
		else
		{
			include ("template/commonheader.php");
			include ("template/index.php");
			include ($includeLink . ".html.php");
		}
		echo '<script language="JavaScript" src="' . $site_root . $includeLink . '.js"></script>';
		
		include ("template/footer.php");
		break;
	case "3":
		$includeLink = "component/" . $TASK . "/" . $REQUEST;
		if (!file_exists($includeLink . ".html.php"))
		{
			getModule('error');
		}
		else{
			//include ("template/commonheader.php");
			include ($includeLink . ".html.php");
		}
		
		echo '<script language="JavaScript" src="' . $site_root . $includeLink . '.js"></script>';
		break;
	default:
		include ("template/commonheader.php");
		include ("template/index.php");
		break;
}
