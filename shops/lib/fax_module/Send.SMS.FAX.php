<?php 

require_once ('superbilk-sipgateapi/xmlrpc-3.0.0.beta/lib/xmlrpc.inc');
require_once ('superbilk-sipgateapi/sipgateAPI.php');

//$number : phone number  with national prefix
// $file: path  path of a pdf file
function sendFAX($username,$password,$number, $file){
	$myAPI = new sipgateAPI($username, $password);
	$r = $myAPI->samurai_ClientIdentify();
	$r = $myAPI->sendFax($number, $file);	
	}

function sendSMS($username,$password,$mnumber, $message){
	$myAPI = new sipgateAPI($username, $password);
	$r = $myAPI->samurai_ClientIdentify();
	$r = $myAPI->sendSMS($number, $message);	
	}


?>