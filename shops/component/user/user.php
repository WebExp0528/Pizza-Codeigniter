<?
$database = new database($database_host,$database_user,$database_password,$database_name);


class userControl {

	function userControl() {
		global $shop;
		$this->id = mosGetParam($_REQUEST,"id");
		$this->shop_id = mosGetParam($_REQUEST,"shop_id",$shop->id);
		$this->username = mosGetParam($_REQUEST,"loginId","");
		$this->userpasswd = mosGetParam($_REQUEST,"loginPasswd","");
		$this->prefix = "ajax";
	}
	function response() {

		$xml_result = "<?xml version='1.0' encoding=\"utf-8\"?>";
		$response = "";
		$option = mosGetParam( $_REQUEST, 'option', 'list' );

		switch ($option) {
			case "userLogin":
				$response = $this->responseUserLogin();
				break;
			case "userLogout":
				$response = $this->responseUserLogout();
				break;
			case "getUserDataApp":
				$response = $this->responseGetUserDataApp();
				return $response;
				break;
			case "userUpdateApp":
				$response = $this->responseUserUpdateApp();
				return $response;
				break;
			case "customerListApp":
				$response = $this->responseCustomerListApp();
				return $response;
				break; 
			case "reportListApp":
				$response = $this->responseReportListApp();
				return $response;
				break; 
			case "setLetterApp":
				$response = $this->responseSetLetterApp();
				return $response;
				break; 
			case "getLetterApp":
				$response = $this->responseGetLetterApp();
				return $response;
				break; 


		}
		header('Content-Type: text/xml');
		return $xml_result.$response;
	}
	function responseUserLogin () {
		$user = new loginUser();
		$loginresult = $user->userLoginCheck($this->username,$this->userpasswd);
		$xml_result = $this->make_Prefix();
		$xml_result .= "<login>".$loginresult."</login>";
		$xml_result .= $this->make_Prefix(0);
		
		return $xml_result;
	}
	function responseUserLogout () {
		$user = new loginUser();
		$loginresult = $user->userLogout();
		$xml_result = $this->make_Prefix();
		$xml_result .= "<logout>".$loginresult."</logout>";
		$xml_result .= $this->make_Prefix(0);
		
		return $xml_result;
	}
	function responseUserUpdateApp () {
		global $database;
		$row = new customer_table( $database );
		$row->bind($_REQUEST);
		if($row->store()) $result = 1;
		else $result = 0;
		$xml_result = "<result>".$result;
		$xml_result .= "</result>";
		return $xml_result;
	}
	function responseGetUserDataApp () {
		$id = trim(mosGetParam($_REQUEST,"customer_id",""));
		$phone = trim(mosGetParam($_REQUEST,"telephone",""));
		$name = trim(mosGetParam($_REQUEST,"customer_name",""));
		$user = $this->getUser($id,$phone,$name);
		$xml_result = "<data>";
		$xml_result .= "<id>".$user->id."</id>";
		$xml_result .= "<name><![CDATA[".$user->firstname." ".$user->lastname."]]></name>";
		$xml_result .= "<phone><![CDATA[".$user->telephone."]]></phone>";
		$xml_result .= "<street><![CDATA[".$user->street."]]></street>";
		$xml_result .= "<house><![CDATA[".$user->house_no."]]></house>";
		$xml_result .= "<postcode><![CDATA[".$user->postcode."]]></postcode>";
		$xml_result .= "<city><![CDATA[".$user->city."]]></city>";
		$xml_result .= "</data>";
		return $xml_result;
	}
	function getUser($id="",$phone="",$name=""){
		global $database;
		if($id==""&&$phone==""&&$name=="") return;
		$sql = "SELECT * FROM oos_customer WHERE 1";
		if($id) $sql .= " AND id=".$id;
		if($phone) $sql .= " AND `telephone` LIKE '%{$phone}%'";
		if($name) $sql .= " AND (`firstname` LIKE '%{$name}%' OR `lastname` LIKE '%{$name}%')";
		$database->setQuery($sql);
		$database->loadObject($user);
		return $user;
	}
	function responseCustomerListApp () {
		$list = $this->getCustomerList();
		$xml_result = "<data>";
		//for($j=0;$j<10;$j++){
		for($i=0;$i<count($list);$i++){
			$user = $list[$i];
			$xml_result .= "<userInfo>";
			$xml_result .= "<id>".$user->id."</id>";
			$xml_result .= "<firstname><![CDATA[".$user->firstname."]]></firstname>";
			$xml_result .= "<lastname><![CDATA[".$user->lastname."]]></lastname>";
			$xml_result .= "<phone><![CDATA[".$user->telephone."]]></phone>";
			$xml_result .= "<street><![CDATA[".$user->street."]]></street>";
			$xml_result .= "<house_no><![CDATA[".$user->house_no."]]></house_no>";
			$xml_result .= "<postcode><![CDATA[".$user->postcode."]]></postcode>";
			$xml_result .= "<city><![CDATA[".$user->city."]]></city>";
			$xml_result .= "<lastdate><![CDATA[".$user->last_order_date."]]></lastdate>";
			$xml_result .= "</userInfo>";
		}
		//}
		$xml_result .= "</data>";
		return $xml_result;
	}
	function responseReportListApp () {
		$list = $this->getReportList();
		$xml_result = "<data>";
		for($i=0;$i<count($list);$i++){
			$user = $list[$i];
			$xml_result .= "<userInfo>";
			$xml_result .= "<id>".$user->id."</id>";
			$xml_result .= "<firstname><![CDATA[".$user->firstname."]]></firstname>";
			$xml_result .= "<lastname><![CDATA[".$user->lastname."]]></lastname>";
			$xml_result .= "<phone><![CDATA[".$user->telephone."]]></phone>";
			$xml_result .= "<street><![CDATA[".$user->street."]]></street>";
			$xml_result .= "<house_no><![CDATA[".$user->house_no."]]></house_no>";
			$xml_result .= "<postcode><![CDATA[".$user->postcode."]]></postcode>";
			$xml_result .= "<city><![CDATA[".$user->city."]]></city>";
			$xml_result .= "<lastdate><![CDATA[".$user->last_order_date."]]></lastdate>";
			$xml_result .= "</userInfo>";
		}
		$xml_result .= "</data>";
		return $xml_result;
	}
	function responseGetLetterApp () {
		global $database;
		$sql = " SELECT content FROM oos_letter WHERE store_id =".$this->shop_id;
		$sql .= " ORDER By add_date DESC,id DESC LIMIT 0,1";
		$database->setQuery($sql);
		$result = $database->loadResult();
		$xml_result = "<letter><![CDATA[".$result."]]></letter>";
		return $xml_result;
	}
	function responseSetLetterApp () {
		$content = mosGetParam($_REQUEST,"content","");
		global $database;
		$row = new oos_letter( $database );
		$row->store_id = $this->shop_id;
		$row->content = $content;
		$row->add_date = date("Y-m-d");
		if($row->store()) $result = 1;
		else $result = 0;
		$xml_result = "<result>".$result;
		$xml_result .= "</result>";
		return $xml_result;
	}
	function getCustomerList(){
		global $database;
		$sql = "SELECT * FROM oos_customer";
		$sql .= " ORDER By id";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		return $result;
	}
	function getReportList(){
		global $database;
		$lastdate = date("Y-m-d",mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
		$sql = "SELECT * FROM oos_customer";
		$sql .= " WHERE last_order_date <= '{$lastdate}'";
		$sql .= " ORDER By id";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		return $result;
	}
	function make_Prefix ($f=1) {
		return ($f)?"<".$this->prefix.">":"</".$this->prefix.">";
	}

}

$userCtrl = new userControl();
$result = $userCtrl->response();

//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
//header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT'); 
//header('Cache-Control: no-cache, must-revalidate'); 
//header('Pragma: no-cache');
// retrieve new messages from the server

echo $result;
?>
