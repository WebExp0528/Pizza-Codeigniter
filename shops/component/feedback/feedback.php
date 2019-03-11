<?

$database = new database($database_host,$database_user,$database_password,$database_name);
class categoryControl {

	function categoryControl() {
		global $shop;
		$this->catid = mosGetParam($_REQUEST,"catid","");
		$this->shop_id = mosGetParam($_REQUEST,"shop_id",$shop->id);
		$this->product_id = mosGetParam($_REQUEST,"product_id","");
		$this->size_id = mosGetParam($_REQUEST,"size_id");
		$this->topping_ids = mosGetParam($_REQUEST,"topping_ids","");
		$this->offer_id = mosGetParam($_REQUEST,"offer_id","");
		$this->table_id = mosGetParam($_REQUEST,"table_id","");
		$this->product_ids = mosGetParam($_REQUEST,"product_ids","");
		$this->offers_str = mosGetParam($_REQUEST,"offers_str","");
		$this->prefix = "ajax";
	}
	function response() {

		$xml_result = "<?xml version='1.0' encoding=\"utf-8\"?>";
		$response = "";
		$option = mosGetParam( $_REQUEST, 'option', '' );

		switch ($option) {
			default:
			case "selcetCategory":
				if($this->catid > 0 )
					$response = $this->responseSelectCategory();
				else $response = $this->responseImpress();
				break; 
			case "selectOffer":
				$response = $this->responseSelectOffer();
				break; 
			case "sessionOrderData":
				$response = $this->responseSessionOrderdata();
				break; 
			case "selectProduct":
				$response = $this->responseSelectProduct();
				break; 
			case "addTopping":
				$response = $this->responseAddTopping();
				break; 
			case "addSpecial_offer":
				$response = $this->responseAddSpecialOffer();
				break; 
			case "getProductDataApp":
				$response = $this->responseGetProductDataApp();
				return $response;
				break; 
			case "removeProduct":
				$response = $this->responseRemoveProduct();
				break; 
			case "removeOffer":
				$response = $this->responseRemoveOffer();
				break; 

		}
		header('Content-Type: text/xml');
		return $xml_result.$response;
	}
	function responseSelectCategory()
	{
		global $database,$site_root,$base_url;
		$category = $this->getCategory($this->catid);
		$categoryName = $category[0]->name;
		$image_url = ($category[0]->image_url)?$base_url."/admin/upload/".$category[0]->image_url:"";
		$xml_result = $this->make_Prefix();
		$xml_result .= "<category>1</category>";
		$xml_result .= "<id>".$this->catid."</id>";
		$xml_result .= "<name>".$categoryName."</name>";
		$xml_result .= "<image_url>".$image_url."</image_url>";
		$xml_result .= "<productlist>";
		$xml_result .= "<![CDATA[<table cellpadding='0' cellspacing='0' border='0' class='simpleList wide'>";
		$xml_result .= "<tr><td class='header_left'></td>";
		$xml_result .= "<td class='header thin'></td>";
		$xml_result .= "<td class='header category_name'>{$categoryName}</td>";
		for($i=0;$i<count($category);$i++){
			if(!$category[$i]->id_size) $category[$i]->id_size = -1;
			if(count($category)==1){
				$xml_result .= "<td class='header w10'>Price</td>";
			} else {
				$xml_result .= "<td class='header w10'>{$category[$i]->size}";
				$xml_result .= "<span class='description'>{$category[$i]->size_detail}</span></td>";
			}
		}
		$xml_result .= "<td class='header_right'></td></tr>";
		$productList = $this->getProductList($this->catid);
		for($i=0;$i<count($productList);$i++){
			$no = $i+1;
			$product = $productList[$i];
			//if($i%2) $row_class = "even"; else $row_class = "uneven";
			$sql = "SELECT p.price,p.id_size FROM oos_price p LEFT JOIN oos_size s ON s.id=p.id_size WHERE p.id_product=".$product->id;
			$database->setQuery($sql);
			$result = $database->loadObjectList();
			$priceList = array();
			foreach($result as $price){
				$priceList[$price->id_size] = $price->price;
			}
			$tr_result = "<tr class='list_body' onmouseover='this.className=\"even_over\"' onmouseout='this.className=\"list_body\"'>";
			$tr_result .= "<td></td><td></td>";
			//$xml_result .= "<td class='w5' align='center'>{$no}</td>";
			if(count($category)==1){
				$tr_result .= "<td><span class='product_title'>";
				$tr_result .= "<a href='javascript:void(0)' class='select_product' onclick='that.clickProduct({$product->id},{$category[0]->id_size})'>{$product->name}</a></span>";
				$tr_result .= "<br><span class='description'>{$product->description}</span></td>";
			} else {
				$tr_result .= "<td><span class='product_title'>{$product->name}</a></span><br>";
				$tr_result .= "<span class='description'>{$product->description}</span></td>";
			}
			$price = 0;
			for($j=0;$j<count($category);$j++){
				$price_str = (intval($priceList[$category[$j]->id_size])>0)?"€&nbsp;".$priceList[$category[$j]->id_size]:"";
				if(count($category)==1){
					$tr_result .= "<td><span class='product_prce'>{$price_str}</span></td>";
				} else {
					$tr_result .= "<td><a href='javascript:void(0)' class='select_product' onclick='that.clickProduct({$product->id},{$category[$j]->id_size})'><span class='product_prce'>{$price_str}</span></a></td>";
				}
				$price = ($priceList[$category[$j]->id_size]>0)?$priceList[$category[$j]->id_size]:$price;
			}
			$tr_result .= "<td></td>";
			$tr_result .= "</tr>";
			if($price>0) $xml_result .= $tr_result;
		}
		$xml_result .= "";
		$xml_result .= "</table>]]>";
		$xml_result .= "</productlist>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseSelectOffer()
	{
		global $database,$base_url;
		$offers = $this->getSpecialOffer($this->catid);
		$offerName = $offers[0]->offer_name;
		$image_url = ($offers[0]->image_url)?$base_url."/admin/upload/".$offers[0]->image_url:"";
		$xml_result = $this->make_Prefix();
		$xml_result .= "<category>1</category>";
		$xml_result .= "<id>".$this->offer_id."</id>";
		$xml_result .= "<name>".$offerName."</name>";
		$xml_result .= "<image_url>".$image_url."</image_url>";
		$xml_result .= "<productlist>";
		$xml_result .= "<![CDATA[<table cellpadding='0' cellspacing='0' border='0' class='simpleList wide'>";
		$xml_result .= "<tr><td class='header_left'></td>";
		$xml_result .= "<td class='header thin'></td>";
		$xml_result .= "<td class='header category_name'>{$offerName}</td>";
		$xml_result .= "<td class='header w20'>Price</td>";
		$xml_result .= "<td class='header_right'></td></tr>";
		for($index=0;$index<count($offers);$index++){
			$productList = $this->getProductList($offers[$index]->id);
			$sizeList = $this->getSizeList($offers[$index]->id);
			for($i=0;$i<count($productList);$i++){
				$no = $i+1;
				$product = $productList[$i];
				$sql = "SELECT p.price,p.id_size FROM oos_price p LEFT JOIN oos_size s ON s.id=p.id_size WHERE p.id_product=".$product->id;
				$database->setQuery($sql);
				$result = $database->loadObjectList();
				$priceList = array();
				foreach($result as $price){
					$priceList[$price->id_size] = $price->price;
				}
				$tr_result = "<tr class='list_body' onmouseover='this.className=\"even_over\"' onmouseout='this.className=\"list_body\"'>";
				$tr_result .= "<td></td><td></td>";
				$tr_result .= "<td><span class='product_title'>{$product->name}</a></span><br>";
				$tr_result .= "<span class='description'>{$product->description}</span></td>";
				$price = 0;
				$tr_result .= "<td>";
				for($j=0;$j<count($sizeList);$j++){
					$price_str = (intval($priceList[$sizeList[$j]->id])>0)?"€&nbsp;".$priceList[$sizeList[$j]->id]:"";
					$tr_result .= "<a href='javascript:void(0)' class='select_product' onclick='that.clickProduct({$product->id},{$sizeList[$j]->id})' style='margin-right:10px;'><span class='product_prce' title='{$sizeList[$j]->name}'>{$price_str}</span></a>";
					$price = ($priceList[$sizeList[$j]->id]>0)?$priceList[$sizeList[$j]->id]:$price;
				}
				$tr_result .= "<td><td></td>";
				$tr_result .= "</tr>";
				if($price>0) $xml_result .= $tr_result;
			}
		}
		$xml_result .= "";
		$xml_result .= "</table>]]>";
		$xml_result .= "</productlist>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseImpress()
	{
		global $database,$shop;
		$sql = "SELECT imprint FROM oos_setting WHERE store_id = ".$shop->id;
		$database->setQuery($sql);
		$impress = $database->loadResult();
		$xml_result = $this->make_Prefix();
		$xml_result .= "<impress>".$impress."</impress>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseSessionOrderdata()
	{
		$session_id = session_id();
		$result = getSessionData($session_id,$this->shop_id);
		$xml_result = $this->make_Prefix();
		
		$xml_result .= "<sessionOrderData>1</sessionOrderData>";
		$xml_result .= "<orderlist><![CDATA[";
		$data = array();
		$data = array();
		if($result->order_data) {$data = explode(";",$result->order_data);}
		for($i=count($data)-1;$i>=0;$i--){
			$row = explode(",",$data[$i]);
			if($row[0]==1){
				$offer = $this->getSpecialOffer($row[1]);
				$price = $offer[0]->offer_price;
				$product_names = array();
				$product_ids = explode("|",$row[2]);
				$productlist = '<tr><td colspan="2"></td><td colspan=5>';
				$productlist .= '<table cellpadding="0" cellspacing="0" border=0 class="wide">';
				for($j=0;$j<count($product_ids);$j++){
					$product_id = explode(":",$product_ids[$j]);
					$product = $this->getProduct($product_id[0]);
					$productlist .= '<tr class="product_data"><td align="center">+</td>';
					$productlist .= '<td colspan="2" class="w75">'.$product->name.'</td>';
					$productlist .= '<td class="w20 over_line">'.$product->price.'</td>';
					$productlist .= '<td>€</td></tr>';
					$topping_ids = explode(" ",$product_id[1]);
					for($k=0;$k<count($topping_ids);$k++){
						if(!$topping_ids[$k]) continue;
						$_t = $this->getToppingById($topping_ids[$k]);
						$productlist .= '<tr class="topping_data"><td align="center">+</td>';
						$productlist .= '<td colspan="2" class="w75">'.$_t->topping_name.'</td>';
						$productlist .= '<td class="w20">'.$_t->price_add.'</td>';
						$productlist .= '<td>€</td></tr>';
						$price += $_t->price_add;
					}
				}
				$extra = explode("\n",$offer[0]->extra_product);
				for($j=0;$j<count($extra);$j++){
					if(!$extra[$j]) continue;
					$productlist .= '<tr class="product_data"><td align="center">+</td>';
					$productlist .= '<td colspan="2" class="w75">'.$extra[$j].'</td>';
					$productlist .= '<td colspan="2" class="w20">addition</td>';
					$productlist .= '</tr>';
				}
				$productlist .= "</table>";
				$productlist .= "</td></tr>";
				$table_id = 'offer_'.$row[1].'_'.rand();
				$xml_result .= '<table class="selected_product wide" id="'.$table_id.'">';
				$xml_result .= '<tr><td class="thin"></td>';
				$xml_result .= '<td class="select_arrow "></td>';
				$xml_result .= '<td class="w66 wrap" colspan="3"><span class="product_title">'.$offer[0]->offer_name.'</span></td>';
				$xml_result .= '<td class="product_prce">'.$price.'</td>';
				$xml_result .= '<td class="thin">€</td>';
				$xml_result .= '</tr>';
				$xml_result .= '<tr><td></td>';
				$xml_result .= '<td><a href="javascript:void(0)" class="add" onclick="that.addSpecial_offer('.$row[1].',\''.$row[2].'\',\''.$table_id .'\')"></a>';
				$xml_result .= '<a href="javascript:void(0)" class="remove" onclick="that.removeOffer('.$row[1].',\''.$row[2].'\',\''.$table_id .'\')"></a></td>';
				$xml_result .= '<td class="count">'.$row[3].'</td>';
				$xml_result .= '<td colspan="2">';
				$xml_result .= '<span class="description">Special Offer</span>';
				$xml_result .= '<input type="hidden" value="'.$row[2].'" name="'.$table_id.'">';
				$xml_result .= '</td>';
				$xml_result .= '<td colspan="2"></td>';
				$xml_result .= '</tr>';
				$xml_result .= $productlist;
				$xml_result .= '</table>';
			} else if($row[0]==2){
				$product = $this->getProduct($row[1],$row[2]);
				$topping = $this->getTopping($product->id_category,$row[2]);
				$price = $product->price;
				$toppinglist = '<tr><td colspan="2"></td><td colspan=5 class="topping_data">';
				if(!empty($row[4])) {
					$toppinglist .= '<table cellpadding="0" cellspacing="0" border=0 class="wide">';
					$topping_ids = explode(":",$row[4]);
					for($j=0;$j<count($topping_ids);$j++){
						$_t = $this->getToppingById($topping_ids[$j]);
						$toppinglist .= '<tr><td align="center">+</td>';
						$toppinglist .= '<td colspan="2" class="w75">'.$_t->topping_name.'</td>';
						$toppinglist .= '<td class="w20">'.$_t->price_add.'</td>';
						$toppinglist .= '<td>€</td></tr>';
						$price += $_t->price_add;
					}
					$toppinglist .= "</table>";
				}
				$toppinglist .= "</td></tr>";
				$xml_result .= '<table class="selected_product wide" id="product_'.$row[1].'_'.$row[2].'">';
				$xml_result .= '<tr><td class="thin"></td>';
				$xml_result .= '<td class="select_arrow "></td>';
				$xml_result .= '<td class="w66" colspan="3"><span class="product_title">'.$product->name.'</span></td>';
				$xml_result .= '<td class="product_prce">'.$price.'</td>';
				$xml_result .= '<td class="thin">€</td>';
				$xml_result .= '</tr>';
				$xml_result .= '<tr><td></td>';
				$xml_result .= '<td style="float:center;"><a href="javascript:void(0)" class="add" onclick="that.clickProduct('.$row[1].','.$row[2].')"></a>';
				$xml_result .= '<a href="javascript:void(0)" class="remove" onclick="that.remove_product(this,'.$row[1].','.$row[2].')"></a></td>';
				$xml_result .= '<td class="count">'.$row[3].'</td>';
				$xml_result .= '<td>';
				if($product->size) $xml_result .= '<span class="description">'.$product->size.'&nbsp;'.$product->size_detail.'</span>';
				$xml_result .= '</td>';
				$xml_result .= '<td>';
				if($topping) $xml_result .= '<a href="javascript:void(0)" class="add_topping" onclick="add_topping_modal(this,'.$row[1].','.$row[2].',\''.$product->name.'\')" style="width:10px;">Add Topping</a>';
				$xml_result .= '</td>';
				$xml_result .= '<td colspan="2"></td>';
				$xml_result .= '</tr>';
				$xml_result .= $toppinglist;
				$xml_result .= '</table>';
			}
		}
		$xml_result .= "]]></orderlist>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseSelectProduct()
	{
		$product = $this->getProduct($this->product_id,$this->size_id);
		$count = $this->sessionDataStore($this->product_id,$this->size_id);
		$topping = $this->getTopping($product->id_category,$this->size_id);
		$topping = ($topping)?1:0;

		$price = $product->price;

		$xml_result = $this->make_Prefix();
		$xml_result .= "<product>".$product->id."</product>";
		$xml_result .= "<id>".$product->id."</id>";
		$xml_result .= "<name><![CDATA[".$product->name."]]></name>";
		$xml_result .= "<price><![CDATA[".$price."]]></price>";
		$xml_result .= "<size><![CDATA[".$product->size."]]></size>";
		$xml_result .= "<size_id>".$this->size_id."</size_id>";
		$xml_result .= "<size_detail><![CDATA[".$product->size_detail."]]></size_detail>";
		$xml_result .= "<count>".$count."</count>";
		$xml_result .= "<topping>".$topping."</topping>";
		

		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseAddTopping()
	{
		$product = $this->getProduct($this->product_id,$this->size_id);
		$count = $this->sessionToppingDataStore($this->product_id,$this->size_id,$this->topping_ids);
		$topping_ids = explode(":",$this->topping_ids);
		$toppinglist = "";
		$price = $product->price;
		if($this->topping_ids){
			$toppinglist .= '<table cellpadding="0" cellspacing="0" border=0 class="wide">';
			for($i=0;$i<count($topping_ids);$i++){
				$_t = $this->getToppingById($topping_ids[$i]);
				$toppinglist .= '<tr class="topping_data"><td colspan="2"></td><td align="center">+</td>';
				$toppinglist .= '<td colspan="2" class="w75">'.$_t->topping_name .'</td>';
				$toppinglist .= '<td>'.$_t->price_add.'</td>';
				$toppinglist .= '<td>€</td></tr>';
				$price += $_t->price_add;
			}
			$toppinglist .= "</table>";
		}

		$xml_result = $this->make_Prefix();
		$xml_result .= "<id>".$product->id."</id>";
		$xml_result .= "<size_id>".$this->size_id."</size_id>";
		$xml_result .= "<price><![CDATA[".$price."]]></price>";
		$xml_result .= "<toppinglist><![CDATA[".$toppinglist."]]></toppinglist>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseAddSpecialOffer()
	{
		$count = $this->sessionOfferDataStore($this->offer_id,$this->offers_str);
		$offer = $this->getSpecialOffer($this->offer_id);
		$price = $offer[0]->offer_price;
		$product_ids = explode("|",$this->offers_str);
		$productlist = '<tr><td colspan="2"></td><td colspan=5>';
		$productlist .= '<table cellpadding="0" cellspacing="0" border=0 class="wide">';
		for($i=0;$i<count($product_ids);$i++){
			$product_id = explode(":",$product_ids[$i]);
			$product = $this->getProduct($product_id[0]);
			$productlist .= '<tr class="product_data"><td align="center">+</td>';
			$productlist .= '<td colspan="2" class="w75">'.$product->name.'</td>';
			$productlist .= '<td class="w20 over_line">'.$product->price.'</td>';
			$productlist .= '<td>€</td></tr>';
			$topping_ids = explode(" ",$product_id[1]);
			for($j=0;$j<count($topping_ids);$j++){
				if(!$topping_ids[$j]) continue;
				$_t = $this->getToppingById($topping_ids[$j]);
				$productlist .= '<tr class="topping_data"><td align="center">+</td>';
				$productlist .= '<td colspan="2" class="w75">'.$_t->topping_name.'</td>';
				$productlist .= '<td class="w20">'.$_t->price_add.'</td>';
				$productlist .= '<td>€</td></tr>';
				$price += $_t->price_add;
			}
		}
		$extra = explode("\n",$offer[0]->extra_product);
		for($i=0;$i<count($extra);$i++){
			if(!$extra[$i]) continue;
			$productlist .= '<tr class="product_data"><td align="center">+</td>';
			$productlist .= '<td colspan="2" class="w75">'.$extra[$i].'</td>';
			$productlist .= '<td colspan="2" class="w20">addition</td>';
			$productlist .= '</tr>';
		}
		$productlist .= "</table>";
		$productlist .= "</td></tr>";
		$table_id = $this->table_id?$this->table_id:'offer_'.$this->offer_id.'_'.rand();
		$table_str = '<table class="selected_product wide" id="'.$table_id.'">';
		$table_str .= '<tr><td class="thin"></td>';
		$table_str .= '<td class="select_arrow "></td>';
		$table_str .= '<td class="w66 wrap" colspan="3"><span class="product_title">'.$offer[0]->offer_name.'</span></td>';
		$table_str .= '<td class="product_prce">'.$price.'</td>';
		$table_str .= '<td class="thin">€</td>';
		$table_str .= '</tr>';
		$table_str .= '<tr><td></td>';
		$table_str .= '<td><a href="javascript:void(0)" class="add" onclick="that.addSpecial_offer('.$this->offer_id.',\''.$this->offers_str.'\',\''.$table_id .'\')"></a>';
		$table_str .= '<a href="javascript:void(0)" class="remove" onclick="that.removeOffer('.$this->offer_id.',\''.$this->offers_str.'\',\''.$table_id .'\')"></a></td>';
		$table_str .= '<td class="count">'.$count.'</td>';
		$table_str .= '<td colspan="2">';
		$table_str .= '<span class="description">Special Offer</span>';
		$table_str .= '<input type="hidden" value="'.$this->offers_str.'" name="'.$table_id.'">';
		$table_str .= '</td>';
		$table_str .= '<td colspan="2"></td>';
		$table_str .= '</tr>';
		$table_str .= $productlist;
		$table_str .= '</table>';

		$xml_result = $this->make_Prefix();
		$xml_result .= "<special_offer>".$this->offer_id."</special_offer>";
		$xml_result .= "<id>".$this->offer_id."</id>";
		$xml_result .= "<name><![CDATA[".$offer[0]->offer_name."]]></name>";
		$xml_result .= "<offers_str><![CDATA[".$this->offers_str."]]></offers_str>";
		$xml_result .= "<price><![CDATA[".$price."]]></price>";
		$xml_result .= "<count><![CDATA[".$count."]]></count>";
		$xml_result .= "<table_str><![CDATA[".$table_str."]]></table_str>";
		$xml_result .= "<table_id><![CDATA[".$table_id."]]></table_id>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseGetProductDataApp()
	{
		$code = trim(mosGetParam($_REQUEST,"code",""));
		$product = $this->getProductByCode($code);
		$detail = $this->getProductDetail($product->id);
		$id_price = $price = $id_size = $size = "";
		for($i=0;$i<count($detail);$i++){
			if($i==0){
				$id_price = $detail[$i]->id_price;
				$price = $detail[$i]->price;
				$id_size = $detail[$i]->id_size;
				$size = $detail[$i]->name;
			} else {
				$id_price .= ",".$detail[$i]->id_price;
				$price .= ",".$detail[$i]->price;
				$id_size .= ",".$detail[$i]->id_size;
				$size .= ",".$detail[$i]->name;
			}
			
		}
		$xml_result = "<data>";
		$xml_result .= "<id>".$product->id."</id>";
		$xml_result .= "<code>".$product->code."</code>";
		$xml_result .= "<name><![CDATA[".$product->name."]]></name>";
		$xml_result .= "<description><![CDATA[".$product->description."]]></description>";
		$xml_result .= "<id_price>".$id_price."</id_price>";
		$xml_result .= "<price><![CDATA[".$price."]]></price>";
		$xml_result .= "<id_size>".$id_size."</id_size>";
		$xml_result .= "<size><![CDATA[".$size."]]></size>";
		$xml_result .= "</data>";
		return $xml_result;
	}
	function getCategory($id){
		global $database;
		$sql = "SELECT c.*,s.name AS size,s.desc AS size_detail,s.id AS id_size";
		$sql .= " FROM oos_category c LEFT JOIN oos_size s ON s.id_category=c.id";
		$sql .= " WHERE c.id=".$id;
		$sql .= " AND c.store_id=".$this->shop_id;
		$sql .= " AND c.is_active = 'Y'";
		$database->setQuery($sql);
		$category = $database->loadObjectList();
		return $category;
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
	function getProductList($cid){
		global $database;
		$sql = "SELECT * FROM oos_product WHERE id_category=".$cid;
		$sql .= " AND is_active = 'Y'";
		$sql .= " ORDER By code";
		$database->setQuery($sql);
		$productList = $database->loadObjectList();
		return $productList;
	}
	function getSizeList($cid){
		global $database;
		$sql = "SELECT * FROM oos_size WHERE id_category=".$cid;
		$database->setQuery($sql);
		$productList = $database->loadObjectList();
		return $productList;
	}
	function getProduct($pid,$sid=""){
		global $database;
		$sql = "SELECT p.*,c.price,s.name size,s.desc size_detail FROM oos_product p";
		$sql .= " LEFT JOIN oos_price c ON c.id_product=p.id LEFT JOIN oos_size s ON s.id=c.id_size";
		$sql .= " WHERE p.id={$pid}";
		if($sid) $sql .= " AND c.id_size={$sid}";
		$sql .= " AND p.is_active = 'Y'";
		$database->setQuery($sql);
		$database->loadObject($result);
		return $result;
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
	function getProductByCode($code){
		global $database;
		$sql = "SELECT * FROM oos_product WHERE code='{$code}' AND store_id=".$this->shop_id;
		$sql .= " AND is_active = 'Y'";
		$database->setQuery($sql);
		$database->loadObject($result);
		return $result;
	}
	function getProductDetail($id_product){
		global $database;
		$sql = "SELECT p.id id_price,price,id_size,s.* FROM oos_price p Left JOIN oos_size s ON s.id=p.id_size";
		$sql .= " WHERE p.id_product={$id_product} ORDER By s.id";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		return $result;
	}

	function sessionDataStore($pid,$sid,$topping_ids=""){
		global $database;
		$session_id = session_id();
		$result = getSessionData($session_id,$this->shop_id);
		$data = $_data = array();
		$flag = 0;
		if(count($result->order_data)) $data = explode(";",$result->order_data);
		for($i=0;$i<count($data);$i++){
			$row = explode(",",$data[$i]);
			//if($row[0]!=2) continue;
			if($row[0]==2&&$pid==$row[1]&&$sid==$row[2]) {
				$row[3]++;$count = $row[3];$flag = 1;
			}
			array_push($_data,implode(",",$row));
		}
		if($flag == 1) $order_data = implode (";",$_data);
		elseif($result->order_data) {$order_data = ($result->order_data).";2,".$pid.",".$sid.",1,";$count=1;}
		else {$order_data = "2,".$pid.",".$sid.",1,";$count=1;}
		$row = new session_table($database);
		$row->id = $result->id;
		$row->session_id = $session_id;
		$row->store_id = $this->shop_id;
		$row->date = date("Y-m-d");
		$row->time = time();
		$row->order_data = $order_data;
		$row->store();

		return $count;
	}
	function sessionToppingDataStore($pid,$sid,$topping_ids=""){
		global $database;
		$session_id = session_id();
		$result = getSessionData($session_id,$this->shop_id);
		$data = $_data = array();
		if(count($result->order_data)) $data = explode(";",$result->order_data);
		for($i=0;$i<count($data);$i++){
			$row = explode(",",$data[$i]);
			//if($row[0]!=2) continue;
			if($row[0]==2&&$pid==$row[1]&&$sid==$row[2]) {
				$row[4] = $topping_ids;
			}
			array_push($_data,implode(",",$row));
		}
		$order_data = implode (";",$_data);
		$row = new session_table($database);
		$row->id = $result->id;
		$row->session_id = $session_id;
		$row->store_id = $this->shop_id;
		$row->date = date("Y-m-d");
		$row->time = time();
		$row->order_data = $order_data;
		$row->store();

		return $count;
	}
	function sessionOfferDataStore($offer_id,$offers_str=""){
		global $database;
		$session_id = session_id();
		$result = getSessionData($session_id,$this->shop_id);
		$data = $_data = array();
		$flag = 0;
		if($result->order_data) $data = explode(";",$result->order_data);
		for($i=0;$i<count($data);$i++){
			$row = explode(",",$data[$i]);
			//if($row[0]!=1) continue;
			if($row[0]==1&&$offer_id==$row[1]&&$offers_str==$row[2]) {
				$row[3]++;$count = $row[3];$flag = 1;
			}
			array_push($_data,implode(",",$row));
		}
		if($flag == 1) $order_data = implode (";",$_data);
		elseif($result) {$order_data = ($result->order_data).";1,".$offer_id.",".$offers_str.",1";$count=1;}
		else {$order_data = "1,".$offer_id.",".$offers_str.",1";$count=1;}
		$row = new session_table($database);
		$row->id = $result->id;
		$row->session_id = $session_id;
		$row->store_id = $this->shop_id;
		$row->date = date("Y-m-d");
		$row->time = time();
		$row->order_data = $order_data;
		$row->store();
		return $count;
	}
	function responseRemoveProduct(){
		global $database;
		$xml_result = $this->make_Prefix();
		$session_id = session_id();
		$result = getSessionData($session_id,$this->shop_id);
		$data = $_data = array();
		if(count($result->order_data)) $data = explode(";",$result->order_data);
		$flag = 0;
		for($i=0;$i<count($data);$i++){
			$row = explode(",",$data[$i]);
			//if($row[0]!=2) continue;
			if($row[0]==2&&$this->product_id==$row[1]&&$this->size_id==$row[2]) $row[3]--;
			$count = $row[3];
			if($count) array_push($_data,implode(",",$row));
		}
		if(count($_data)>1) $order_data = implode (";",$_data);
		else $order_data = implode ("",$_data);
		$row = new session_table($database);
		$row->id = $result->id;
		$row->session_id = $session_id;
		$row->store_id = $this->shop_id;
		$row->date = date("Y-m-d");
		$row->time = time();
		$row->order_data = $order_data;
		if(count($_data)) $row->store();
		else $row->delete();
		$xml_result .= "<product_remove>1</product_remove>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function responseRemoveOffer(){
		global $database;
		$xml_result = $this->make_Prefix();
		$session_id = session_id();
		$result = getSessionData($session_id,$this->shop_id);
		$data = $_data = array();
		if($result->order_data) $data = explode(";",$result->order_data);
		$flag = 0;
		for($i=0;$i<count($data);$i++){
			$row = explode(",",$data[$i]);
			//if($row[0]!=1) continue;
			if($row[0]==2&&$row[1]==$this->offer_id&&$row[2]==$this->offers_str) {
				$row[3]--;
			}
			$count = $row[3];
			if($count) array_push($_data,implode(",",$row));
		}
		if(count($_data)>1) $order_data = implode (";",$_data);
		else $order_data = implode ("",$_data);
		$row = new session_table($database);
		$row->id = $result->id;
		$row->session_id = $session_id;
		$row->store_id = $this->shop_id;
		$row->date = date("Y-m-d");
		$row->time = time();
		$row->order_data = $order_data;
		if(count($_data)) $row->store();
		else $row->delete();
		$xml_result .= "<product_remove>1</product_remove>";
		$xml_result .= $this->make_Prefix(0);
		return $xml_result;
	}
	function removeOldSessionData(){
		global $database;
		$date = date("Y-m-d",mktime(0, 0, 0, date("n"),date("j")-2, date("Y") ));
		$sql = "SELECT * FROM oos_session WHERE date <'{$date}'";
		$database->setQuery($sql);
		$result = $database->loadObjectList();
		for($i=0;$i<count($result);$i++){
			$row = new session_table( $database );
			$row->id = $result[$i]->id;
			$row->delete();
		}
		return;
	}
	function make_Prefix ($f=1) {
		return ($f)?"<".$this->prefix.">":"</".$this->prefix.">";
	}

}

$viewCtrl = new categoryControl();
$result = $viewCtrl->response();

//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
//header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT'); 
//header('Cache-Control: no-cache, must-revalidate'); 
//header('Pragma: no-cache');
//header('Content-Type: text/xml');
// retrieve new messages from the server

echo $result;
?>
