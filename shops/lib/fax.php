<?php
include("fax_module/Send.SMS.FAX.php");
function mosGetParam( &$arr, $name, $def=null, $mask=0 ) {
	static $noHtmlFilter 	= null;
	static $safeHtmlFilter 	= null;

	$return = null;
	if (isset( $arr[$name] )) {
		$return = $arr[$name];
		return $return;
	} else {
		return $def;
	}
}
$temp = "1.pdf";
//print_r(file($temp));
$username = mosGetParam( $_REQUEST, 'username', 'b.qadeer' );
$password = mosGetParam( $_REQUEST, 'password', 'Bhakkar$74' );
$number = mosGetParam( $_REQUEST, 'number', '06980104849' );
//$username = "b.qadeer";
//$password = "Bhakkar$74";
//$number = "06980104849";
//echo $number;
sendFAX($username,$password,$number, $temp);
//unlink($temp);
?>

