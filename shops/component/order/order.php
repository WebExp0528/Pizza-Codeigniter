<?

$database = new database($database_host,$database_user,$database_password,$database_name);
include("lib/mpdf51/mpdf.php");
include("lib/fax_module/Send.SMS.FAX.php");
class categoryControl {

	function categoryControl() {
		global $shop;
		$this->shop_id = $shop->shop_id;//mosGetParam($_REQUEST,"shop_id",$shop->id);
		$this->id = mosGetParam($_REQUEST,"id","1");
		$this->product_id = mosGetParam($_REQUEST,"product_id","1");
		$this->size_id = mosGetParam($_REQUEST,"size_id");
		$this->postcode = mosGetParam($_REQUEST,"postcode");
		$this->prefix = "ajax";
	}
	function response() {

		$xml_result = "<?xml version='1.0' encoding=\"utf-8\"?>";
		$response = "";
		$option = mosGetParam( $_REQUEST, 'option', '' );

		switch ($option) {
			//default:
			case "orderDataStore":
				$response = $this->responseOrderDataStore();
				break; 
			case "sendOrderFax":
				$response = $this->responseSendOrderFax();
				break; 
			case "setOrderDataApp":
				$response = $this->responseSetOrderDataApp();
				return $response;
				break; 
			case "orderListApp":
				$response = $this->responseOrderListApp();
				return $response;
				break; 
			case "orderDetailApp":
				$response = $this->responseOrderDetailApp();
				return $response;
				break; 
			case "orderDeleteApp":
				$response = $this->responseOrderDeleteApp();
				return $response;
				break; 
			case "postcode_complete":
				$response = $this->postcode_complete();
				break; 
			case "deliveryCharge":
				$response = $this->responseDeliveryCharge();
				break; 
			case "setFeedback":
				$response = $this->responseSetFeedback();
				break; 
		}
		header('Content-Type: text/xml');
		return $xml_result.$response;
	}
	function removeSessionData($session_id){
		global $database;
		$sql = "SELECT * FROM oos_session WHERE session_id ='{$session_id}'";
		$database->setQuery($sql);
		$database->loadObject($result);
		$row = new session_table( $database );
		$row->id = $result->id;
		$row->delete();
		return;
	}
	function responseOrderDataStore(){
		global $database,$shop;
		$session_id = session_id();
		$data = getSessionData($session_id,$this->shop_id);
		$xml_result = $this->make_Prefix();
		$row = new order( $database );
		$row->bind($_REQUEST);
		$row->customer_id = $_SESSION['SESS_CUSTOMER_ID'];
		$row->store_id = $this->shop_id;
		$row->date = date("Y-m-d H:i:s");
		$row->order_data = $data->order_data;
		$row->delivery_charge = $_REQUEST["delivery_charge"];
		$row->total = $row->total + $_REQUEST["delivery_charge"];
		if($data->order_data) {
			$row->store();
			$xml_result .= "<order_store>1</order_store>";
		} else $xml_result .= "<order_store>0</order_store>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseSendOrderFax(){
		global $shop;
		$dir_path = "temp/faxData/".$this->shop_id;
		if(!is_dir($dir_path)) mkdir($dir_path,0,true);
		$session_id = session_id();
		$faxHTML = $this->createFaxString();
		$this->removeSessionData($session_id);
		$temp = $dir_path."/faxData".date("Y-m-d")."_".time().".pdf";
		$mpdf=new mPDF('','A4','','',13,13,30,15,14,10);
		$mpdf->setAutoFont();
		$mpdf->WriteHTML($faxHTML);
		$mpdf->Output($temp,'F');
		$username = "b.qadeer";
		$password = "Bhakkar$74";
		$number = $shop->fax;
		sendFAX($username,$password,$number, $temp);
//		unlink($temp);
		exit;
	}
	function createFaxString(){
		global $shop;
		$session_id = session_id();
		$data = getSessionData($session_id,$shop->id);
		$str = '<div style="width:210mm"><div style="margin:auto;width:95%;text-align:left"><h1>Order Information</h1>';
		$str .= '</div>';
		$str .= '<div style="margin:auto;width:95%;text-align:left"><h5>Order Time : '.date("Y-m-d H:i:s").'</h5>';
		$str .= '<div style="margin:auto;width:95%;text-align:left"><h3>Customer Information</h3>';
		$str .= '</div>';
		$str .= '<table width="95%" align="center">';
		$str .= '<tr><td width="20%">Name</td>';
		$str .= '<td width="30%">'.$_REQUEST['firstname'].'&nbsp;'.$_REQUEST['lastname'].'</td>';
		$str .= '<td width="20%">Phone Number</td>';
		$str .= '<td width="30%">'.$_REQUEST['telephone'].'</td>';
		$str .= '</tr>';
		$str .= '<tr><td width="10%">Street</td>';
		$str .= '<td width="30%">'.$_REQUEST['street'].'</td>';
		$str .= '<td width="10%">House No</td>';
		$str .= '<td width="30%">'.$_REQUEST['house_no'].'</td>';
		$str .= '</tr>';
		$str .= '<tr><td width="10%">Postcode</td>';
		$str .= '<td width="30%">'.$_REQUEST['postcode'].'</td>';
		$str .= '<td width="10%">City</td>';
		$str .= '<td width="30%">'.$_REQUEST['city'].'</td>';
		$str .= '</tr>';
		$str .= '<tr><td width="10%">Addtional Address</td>';
		$str .= '<td width="30%" colspan="3">'.$_REQUEST['add_details'].'</td>';
		$str .= '</tr>';
		$str .= '<tr><td width="10%">Description</td>';
		$str .= '<td width="30%" colspan="3">'.$_REQUEST['order_notice'].'</td>';
		$str .= '</tr>';
		$str .= '</table><br>';
		$str .= '<div style="margin:auto;width:95%;text-align:left"><h3>Order Summary</h3>';
		$str .= '</div>';
		$str .= '<table border="1" width="95%" align="center" cellpadding="0" cellspacing="0">';
		$str .= '<tr><td width="5%">no</td>';
		$str .= '<td width="50%">Product</td>';
		$str .= '<td width="15%">Price</td></tr>';
		$no = 0;
		$sum_price = 0;
		if(count($data)) {$data = explode(";",$data->order_data);}
		for($i=count($data)-1;$i>=0;$i--){
			$row = explode(",",$data[$i]);
			$no++;
			if($row[0]==1){
				$offer = $this->getSpecialOffer($row[1],$shop->id);
				$price = $offer[0]->offer_price;
				$productlist .= '';
				$product_ids = explode("|",$row[2]);
				for($j=0;$j<count($product_ids);$j++){
					$product_id = explode(":",$product_ids[$j]);
					$product = $this->getProduct($product_id[0]);
					$productlist .= '<tr><td>&nbsp;</td>';
					$productlist .= '<td>+'.$product->name.'</td>';
					$productlist .= '<td style="text-decoration:line-through;">€ '.$product->price.'</td>';
					$productlist .= '</tr>';
					$topping_ids = explode(" ",$product_id[1]);
					for($k=0;$k<count($topping_ids);$k++){
						if(!$topping_ids[$k]) continue;
						$_t = $this->getToppingById($topping_ids[$k]);
						$productlist .= '<tr><td>&nbsp;</td>';
						$productlist .= '<td>+'.$_t->topping_name.'</td>';
						$productlist .= '<td>€ '.$_t->price_add.'</td>';
						$productlist .= '</tr>';
						$price += $_t->price_add;
					}
				}
				$extra = explode("\n",$offer[0]->extra_product);
				for($j=0;$j<count($extra);$j++){
					if(!$extra[$j]) continue;
					$productlist .= '<tr><td>&nbsp;</td>';
					$productlist .= '<td>+'.$extra[$j].' &emsp; (addition)</td>';
					$productlist .= '<td>&nbsp;</td>';
					$productlist .= '</tr>';
				}


				$str .= '<tr style="font-weight:bold;"><td align="center">'.$no.'</td>';
				$str .= '<td>'.$row[3].' x '.$offer[0]->offer_name.'';
				$str .= '&emsp;(Special offer)</td>';
				$str .= '<td>€&nbsp;'.$price.'</td>';
				$str .= '</tr>';
				$str .= $productlist;
				$sum_price += $price*$row[3];
			} else if($row[0]==2){
				$product = $this->getProduct($row[1],$row[2]);
				$topping = $this->getTopping($product->id_category,$row[2]);
				$price = $product->price;
				$toppinglist = '<tr><td></td><td colspan="3" class="topping_data">';
				$toppinglist = '';
				if(!empty($row[4])) {
					$topping_ids = explode(":",$row[4]);
					for($j=0;$j<count($topping_ids);$j++){
						$_t = $this->getToppingById($topping_ids[$j]);
						$toppinglist .= '<tr><td>&nbsp;</td>';
						$toppinglist .= '<td>+'.$_t->topping_name.'</td>';
						$toppinglist .= '<td>€&nbsp;'.$_t->price_add.'</td>';
						$toppinglist .= '</tr>';
						$price += $_t->price_add;
					}
				}
				$str .= '<tr style="font-weight:bold;"><td align="center">'.$no.'</td>';
				$str .= '<td>'.$row[3].' x '.$product->name.'';
				$str .= '&emsp;('.$product->size.')</td>';
				$str .= '<td>€&nbsp;'.$price.'</td>';
				$str .= '</tr>';
				$str .= $toppinglist;
				$sum_price += $price*$row[3];
			}
		}
		$str .= '<tr><td colspan="2" align="right">Deliver Charge </td>';
		$str .= '<td width="15%">€&nbsp;'.$_REQUEST['delivery_charge'].'</td></tr>';
		$str .= '<tr><td colspan="2" align="right">Summe </td>';
		$str .= '<td width="15%">€&nbsp;'.($sum_price+$_REQUEST['delivery_charge']).'</td></tr>';
		$str .= '</table></div>';
		return $str;
	}
	function getTopping($cid,$sid=0){
		global $database;
		$sql = "SELECT * FROM oos_topping";
		$sql .= " WHERE id_category = {$cid}";
		if($sid) $sql .= " AND id_size={$sid}";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		return $result;
	}
	function getToppingById($id){
		global $database;
		$sql = "SELECT * FROM oos_topping WHERE id = {$id}";
		$database->setQuery($sql);
		$database->loadObject($result);
		return $result;
	}
	function responseSetFeedback() {
		global $database;
		$sql = "SELECT * FROM oos_rate_data";
		$sql .= " WHERE store_id = {$this->shop_id}";
		$sql .= " AND order_id = {$_REQUEST['orderid']}";
		$sql .= " AND customer_id = {$_SESSION['SESS_CUSTOMER_ID']}";
		$database->setQuery($sql);
		$database->loadObject($result);

		$row = new rate_data( $database );
		$row->id = $result->id;
		$row->store_id = $this->shop_id;
		$row->customer_id = $_SESSION['SESS_CUSTOMER_ID'];
		$row->customer_name = $_SESSION['SESS_CUSTOMER_NAME'];
		$row->rate_star = $_REQUEST["rate_star"];
		$row->comment = $_REQUEST["rate_comment"];
		$row->rate_date = date("Y-m-d H:i:s");
		$row->order_id = $_REQUEST["orderid"];
		$rate_str = array(1=>"Very bad",2=>"Bad",3=>"Ok",4=>"Good",5=>"Very good");
		if($row->store()) {
			$str = '
					<div class="l_float w50">
						<div class="rating_bg l_float">
							<div class="rating l_float" data="'.$_REQUEST["rate_star"].'" isDisabled="on"></div>
							<div class="rating_value l_float bold">'.$_REQUEST["rate_star"].'/5</div>
						</div>
						<div class="rating_text l_float bold w30">
							'.$rate_str[$_REQUEST["rate_star"]].'
						</div>
					</div>
					<div class="l_float wrap" style="width:200px;">
						'.str_replace("\n","<br>",$_REQUEST["rate_comment"]).'
					</div>';
			echo $str;
		}
		else echo "faild";
		exit;
	}
	function responseSetOrderDataApp()
	{
		global $database;
		$userid = trim(mosGetParam($_REQUEST,"customer_id",""));
		$userName = explode(" ",trim(mosGetParam($_REQUEST,"customer_name","")));
		//if(!$userid||$userid="null"){
			$user = new customer_table( $database );
			$user->load($userid);
			$user->bind($_REQUEST);
			$user->firstname = $userName[0];
			if($userName[1]) $user->lastname = $userName[1];
			$user->date_added = date("Y-m-d");
			$user->last_order_date = date("Y-m-d");
			$user->store();
//		} else {
//			$user = new customer_table( $database );
//			$user->load($userid);
//			$user->last_order_date = date("Y-m-d");
//			$user->store();
//		}

		$row = new order( $database );
		$row->bind($_REQUEST);
		$row->store_id = $this->shop_id;
		$row->customer_id = $userid?$userid:$user->id;
		$row->date = date("Y-m-d H:i:s");
		$row->firstname = $userName[0];
		if($userName[1]) $row->lastname = $userName[1];
		$row->store();
		$xml_result = "<data>";
		$xml_result .= "<id>1</id>";
		$xml_result .= "<code>1</code>";

		$xml_result .= "</data>";
		return $xml_result;
	}
	function responseOrderListApp () {
		$list = $this->getOrderList();
		$xml_result = "<data>";
		for($i=0;$i<count($list);$i++){
			$user = $list[$i];
			$xml_result .= "<userInfo>";
			$xml_result .= "<id>".$user->customer_id."</id>";
			$xml_result .= "<firstname><![CDATA[".$user->firstname."]]></firstname>";
			$xml_result .= "<lastname><![CDATA[".$user->lastname."]]></lastname>";
			$xml_result .= "<phone><![CDATA[".$user->telephone."]]></phone>";
			$xml_result .= "<street><![CDATA[".$user->street."]]></street>";
			$xml_result .= "<house_no><![CDATA[".$user->house_no."]]></house_no>";
			$xml_result .= "<postcode><![CDATA[".$user->postcode."]]></postcode>";
			$xml_result .= "<city><![CDATA[".$user->city."]]></city>";
			$xml_result .= "<orderdate><![CDATA[".substr($user->date,0,10)."]]></orderdate>";
			$xml_result .= "<price><![CDATA[".$user->total."]]></price>";
			$xml_result .= "<orderid><![CDATA[".$user->id."]]></orderid>";
			$xml_result .= "</userInfo>";
		}
		$xml_result .= "</data>";
		return $xml_result;
	}
	function responseOrderDetailApp () {
		$orderid = trim(mosGetParam($_REQUEST,"orderid",""));
		$list = $this->getOrderDetail($orderid);
		$xml_result = "<data>";
		if(count($list)) {$data = explode(";",$list->order_data);}
		for($i=0;$i<count($data);$i++){
			$row = explode(",",$data[$i]);
			$product = $this->getProduct($row[0],$row[1]);
			$xml_result .= "<orderInfo>";
			$xml_result .= "<id>".$product->id."</id>";
			$xml_result .= "<code>".$product->code."</code>";
			$xml_result .= "<name><![CDATA[".$product->name."]]></name>";
			$xml_result .= "<description><![CDATA[".$product->description."]]></description>";
			$xml_result .= "<price><![CDATA[".$product->price."]]></price>";
			$xml_result .= "<size><![CDATA[".$product->size."]]></size>";
			$xml_result .= "<count><![CDATA[".$row[2]."]]></count>";
			$xml_result .= "</orderInfo>";
		}
		$xml_result .= "</data>";
		return $xml_result;
	}
	function responseOrderDeleteApp () {
		$orderid = trim(mosGetParam($_REQUEST,"orderid",""));
		global $database;
		$row = new order( $database );
		$row->load($orderid);
		$row->delete();
		$xml_result = "<data>";
		$xml_result = "<orderDelete>1</orderDelete>";
		
		$xml_result .= "</data>";
		return $xml_result;
	}
	function postcode_complete(){
		global $database;
		$query = "SELECT * FROM oos_cityname WHERE postcode LIKE '$_REQUEST[q]%' GROUP By postcode ORDER By postcode";
		$database->setQuery($query);
		$list = $database->loadObjectList();
		for($i=0;$i<count($list);$i++){
			echo $list[$i]->postcode."\n";
		}
		exit;
	}
	function responseDeliveryCharge(){
		global $database;
		$query = "SELECT delivery_charge FROM oos_deliver_area";
		$query .= " WHERE postcode = '$this->postcode' AND store_id=".$this->shop_id;
		$database->setQuery($query);
		$result = $database->loadResult();
		echo $result;
		exit;
	}
	function getOrderList(){
		global $database;
		$sql = "SELECT o.*,s.name status FROM oos_order o LEFT JOIN oos_order_status s ON o.order_status_id = s.id";
		$sql .= " WHERE o.store_id=".$this->shop_id;
		$sql .= " ORDER By o.id";
		$database->setQuery($sql);
		$productList = $database->loadObjectList();
		return $productList;
	}
	function getOrderDetail($orderid){
		global $database;
		$sql = "SELECT * FROM oos_order WHERE id={$orderid}";
		$database->setQuery($sql);
		$database->loadObject($result);
		return $result;
	}
	function getSpecialOffer($id){
		global $database;
		$sql = "SELECT off.id offer_id,off.offer_name,off.offer_price,off.extra_product,cat.*";
		$sql .= "FROM oos_specialoffer off LEFT JOIN oos_specialoffer_contents con ON off.id = con.id_offer JOIN oos_category cat ON cat.id = con.id_category";
		$sql .= " WHERE off.store_id = ".$this->shop_id;
		$sql .= " AND off.id=".$id;
		$database->setQuery($sql);
		$offers = $database->loadObjectList();
		return $offers;
	}
	function getProduct($pid,$sid=""){
		global $database;
		$sql = "SELECT p.*,c.price,s.name size,s.desc size_detail FROM oos_product p";
		$sql .= " LEFT JOIN oos_price c ON c.id_product=p.id LEFT JOIN oos_size s ON s.id=c.id_size";
		$sql .= " WHERE p.id={$pid}";
		if($sid) $sql .= " AND c.id_size={$sid}";
		$database->setQuery($sql);
		$database->loadObject($result);
		return $result;
	}
	function make_Prefix ($f=1) {
		return ($f)?"<".$this->prefix.">":"</".$this->prefix.">";
	}

}

$viewCtrl = new categoryControl();
$result = $viewCtrl->response();
// retrieve new messages from the server

echo $result;
?>
