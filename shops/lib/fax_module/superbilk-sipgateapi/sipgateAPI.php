<?php
require_once 'sipgateAPI_Exception.php';

class sipgateAPI{
    const ClientVersion	= 1.1;
    const ClientName	= "sipgateAPI-sms.pl";
    const ClientVendor	= "sipgate GmbH";

    const SIP_URI_prefix = 'sip:';
    const SIP_URI_host = '@sipgate.de';

    private $client = null;
    private $url;
    private $debug;

    public function __construct($username, $password, $debug = FALSE){
        if (!class_exists("xmlrpc_client")) {
			echo 'You need "xmlrpc for PHP" - Please download at http://phpxmlrpc.sourceforge.net';
            throw new sipgateAPI_Exception ('You need "xmlrpc for PHP" - Please download at http://phpxmlrpc.sourceforge.net');
			};
        $this->debug = $debug;
        if ( !empty($username) AND !empty($password) ) {
            $this->getClient($username, $password);
			}
        else {
			echo 'Provide valid credentials';
            throw new sipgateAPI_Exception('Provide valid credentials');
			};
        return $this->client;
		}
    private function getClient($username, $password){
        if (null === $this->client) {
            $this->url = "https://" . urlencode($username) . ":" . urlencode($password);
            if (self::isTeam($username)) {
               $this->url .= "@api.sipgate.net:443/RPC2";
				} 
			else {
               $this->url .= "@samurai.sipgate.net:443/RPC2";
				}
            $this->client = new xmlrpc_client($this->url);
            if ($this->debug) {
                $this->client->setDebug(2);
				}
            $this->client->setSSLVerifyPeer(FALSE);
			}
        return $this->client;
		}
    private function isTeam($username){
        return !FALSE == strpos($username, '@');
		}

	public function getBalance(){
        $m = new xmlrpcmsg('samurai.BalanceGet');
        $r = $this->client->send($m);
        if (!$r->faultCode()) {
            $php_r = php_xmlrpc_decode($r->value());
            unset($php_r["StatusCode"]);
            unset($php_r["StatusString"]);
            return $php_r;
			}
        else {
			echo 'Error';
            throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}
	public function samurai_ClientIdentify($ClientVersion 	= self::ClientVersion,$ClientName = self::ClientName,$ClientVendor	= self::ClientVendor){
       $v = array( new xmlrpcval( array(
                                       "ClientVersion" => new xmlrpcval($ClientVersion),
                                       "ClientName" => new xmlrpcval($ClientName),
                                       "ClientVendor" => new xmlrpcval($ClientVendor)
                                  ),"struct"));
       $m = new xmlrpcmsg('samurai.ClientIdentify', $v);
       $r = $this->client->send($m);
       if (!$r->faultCode()) {
			return true;
			}
       else {
			echo 'Error';
			throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		} 
		
    public function sendSMS($to, $message, $time = NULL){
        $number = self::SIP_URI_prefix . $to . self::SIP_URI_host;
        $message = substr($message, 0, 160);
        $this->samurai_SessionInitiate(NULL, $number, "text", $message, $time);
		}
    public function sendFAX($faxnumber, $file, $time = NULL){
        $number = self::SIP_URI_prefix . $faxnumber . self::SIP_URI_host;
        $file = realpath($file);
        if ( !file_exists($file) ) {
			echo 'PDF file does not exist';
            throw new Exception("PDF file does not exist");
			}
        elseif ( strtolower(pathinfo($file, PATHINFO_EXTENSION)) != 'pdf' ) {
			echo 'No PDF file';
            throw new Exception("No PDF file");
			};
        $pdf_base64 = base64_encode(file_get_contents($file));
        $r = $this->samurai_SessionInitiate(NULL, $number, "fax", $pdf_base64, $time);
        return $r;
		}
    
	protected function samurai_SessionInitiate($LocalUri, $RemoteUri, $TOS, $Content, $Schedule = NULL){
        if ( isset($LocalUri) ) {
            $val_a["LocalUri"] = new xmlrpcval($LocalUri);
			};
        if ( isset($RemoteUri) ) {
            $val_a["RemoteUri"] = new xmlrpcval($RemoteUri);
			}
        else {
			echo "no RemoteUri";
            throw new sipgateAPI_Exception("No RemoteUri");
			};
        if ( isset($TOS) ) {
            $val_a["TOS"] = new xmlrpcval($TOS);
			}
        else {
			echo "No valid TOS";
            throw new sipgateAPI_Exception("No valid TOS");
			};
        if ( isset($Content) ) {
            $val_a["Content"] = new xmlrpcval($Content);
			};
        if ( isset($Schedule) ) {
            $val_a["Schedule"] = new xmlrpcval(iso8601_encode($Schedule), "dateTime.iso8601");
			};
        $val_s = new xmlrpcval();
        $val_s->addStruct($val_a);
        $v = array();
        $v[] = $val_s;
        $m = new xmlrpcmsg('samurai.SessionInitiate', $v);
        $r = $this->client->send($m);
        if (!$r->faultCode()) {
            $php_r = php_xmlrpc_decode($r->value());
            return $php_r["SessionID"];
			}
        else {
			echo "$r->faultString(), $r->faultCode()";
            throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}
	
	

	/*
	public function samurai_AccountStatementGet($PeriodStart, $PeriodEnd){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			}
       $v = array( new xmlrpcval( 	array(
                                           "PeriodStart" => new xmlrpcval(iso8601_encode($PeriodStart), "dateTime.iso8601"),
                                           "PeriodEnd" => new xmlrpcval(iso8601_encode($PeriodEnd), "dateTime.iso8601")
                                      ),
           "struct"));
       $m = new xmlrpcmsg('samurai.AccountStatementGet', $v);
       $r = $this->client->send($m);
       if (!$r->faultCode()) {
			$php_r = php_xmlrpc_decode($r->value());
			unset($php_r["StatusCode"]);
			unset($php_r["StatusString"]);
			return $php_r;
			}
       else {
           throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}		
	public function samurai_HistoryGetByDate($LocalUriList = NULL, $StatusList = NULL, $PeriodStart = NULL, $PeriodEnd = NULL){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		if ( isset($LocalUriList) ) {
			if ( is_array($LocalUriList) ) {
				$val_a["LocalUriList"] = new xmlrpcval();
				$val_a["LocalUriList"]->addArray($this->xmlrpcArray($LocalUriList));
				}
           else {
				echo 'LocalUriList is not an array';
				throw new sipgateAPI_Exception("LocalUriList is not an array");
				};
			};
		if ( isset($StatusList) ) {
			if ( is_array($StatusList) ) {
				$val_a["StatusList"] = new xmlrpcval();
				$val_a["StatusList"]->addArray($this->xmlrpcArray($StatusList));
				}
			else {
				echo 'StatusList is not an array';
				throw new sipgateAPI_Exception("StatusList is not an array");
				};
			};
		if ( isset($PeriodStart) ) {
			$val_a["PeriodStart"] = new xmlrpcval();
			$val_a["PeriodStart"]->addScalar(iso8601_encode($PeriodStart), "dateTime.iso8601");
			};
		if ( isset($PeriodStart) ) {
			$val_a["PeriodEnd"] = new xmlrpcval();
			$val_a["PeriodEnd"]->addScalar(iso8601_encode($PeriodStart), "dateTime.iso8601");
			};
		if ( isset($val_a) ) {
			$val_s = new xmlrpcval();
			$val_s->addStruct($val_a);
			$v = array();
			$v[] = $val_s;
			}
		else {
			$v = array();
			};
		$m = new xmlrpcmsg('samurai.HistoryGetByDate', $v);
		$r = $this->client->send($m);
		if (!$r->faultCode()) {
			$php_r = php_xmlrpc_decode($r->value());
			unset($php_r["StatusCode"]);
			unset($php_r["StatusString"]);
			return $php_r["History"];
			}
       else {
			echo 'Error';
			throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}
	public function samurai_ItemizedEntriesGet($LocalUriList, $PeriodStart, $PeriodEnd){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		if ( isset($LocalUriList) ) {
			if ( is_array($LocalUriList) ) {
				$val_a["LocalUriList"] = new xmlrpcval();
				$val_a["LocalUriList"]->addArray($this->xmlrpcArray($LocalUriList));
				}
			else {
				echo 'LocalUriList is not an array';
				throw new sipgateAPI_Exception("LocalUriList is not an array");
				};
			};
		if ( isset($PeriodStart) ) {
			$val_a["PeriodStart"] = new xmlrpcval();
			$val_a["PeriodStart"]->addScalar(iso8601_encode($PeriodStart), "dateTime.iso8601");
			}
		else {
			echo 'PeriodStart is empty';
			throw new sipgateAPI_Exception("PeriodStart is empty");
			};
		if ( isset($PeriodStart) ) {
			$val_a["PeriodEnd"] = new xmlrpcval();
			$val_a["PeriodEnd"]->addScalar(iso8601_encode($PeriodStart), "dateTime.iso8601");
			}
		else {
			echo 'PeriodEnd is empty';
			throw new sipgateAPI_Exception("PeriodEnd is empty");
			};
		$val_s = new xmlrpcval();
		$val_s->addStruct($val_a);
		$v = array();
		$v[] = $val_s;
		$m = new xmlrpcmsg('samurai.ItemizedEntriesGet', $v);
		$r = $this->client->send($m);
		if (!$r->faultCode()) {
			$php_r = php_xmlrpc_decode($r->value());
			unset($php_r["StatusCode"]);
			unset($php_r["StatusString"]);
			return $php_r;
			}
		else {
			echo 'Eroor';
			throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
		}
	}
	public function samurai_OwnUriListGet(){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		$m = new xmlrpcmsg('samurai.OwnUriListGet');
		$r = $this->client->send($m);
		if (!$r->faultCode()) {
			$php_r = php_xmlrpc_decode($r->value());
			unset($php_r["StatusCode"]);
			unset($php_r["StatusString"]);
			return $php_r["OwnUriList"];
			}
		else {
			echo 'Error';
			throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}
	public function samurai_PhonebookEntryGet(){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		echo '_Method_ not supported';
		throw new sipgateAPI_Exception(__METHOD__ . " not supported");
		}
	public function samurai_PhonebookListGet(){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		$m = new xmlrpcmsg('samurai.PhonebookListGet');
		$r = $this->client->send($m);
		if (!$r->faultCode()) {
			$php_r = php_xmlrpc_decode($r->value());
			unset($php_r["StatusCode"]);
			unset($php_r["StatusString"]);
			return $php_r["PhonebookList"];
			}
		else {
			echo 'Error';
			throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}
	public function samurai_RecommendedIntervalGet(){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		throw new sipgateAPI_Exception(__METHOD__ . " not supported");
		}
	public function samurai_ServerdataGet(){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		$m = new xmlrpcmsg('samurai.ServerdataGet');
		$r = $this->client->send($m);
		if (!$r->faultCode()) {
			$php_r = php_xmlrpc_decode($r->value());
			unset($php_r["StatusCode"]);
			unset($php_r["StatusString"]);
			return $php_r;
			}
		else {
			throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}
   public function samurai_SessionClose(){
       if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		echo '_METHOD_ not supported';
       throw new sipgateAPI_Exception(__METHOD__ . " not supported");
   }

   public function samurai_SessionInitiateMulti(){
       if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		echo '_METHOD_ not supported';
		throw new sipgateAPI_Exception(__METHOD__ . " not supported");
		}
   public function samurai_SessionStatusGet(){
       if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		echo '_METHOD_ not supported';
		throw new sipgateAPI_Exception(__METHOD__ . " not supported");
		}
	public function samurai_TosListGet(){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		$m = new xmlrpcmsg('samurai.TosListGet');
		$r = $this->client->send($m);
		if (!$r->faultCode()) {
			$php_r = php_xmlrpc_decode($r->value());
			unset($php_r["StatusCode"]);
			unset($php_r["StatusString"]);
			return $php_r["TosList"];
			}
		else {
			echo'Error';
			throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}
	public function samurai_UmSummaryGet(){
		if ( ! $this->methodSupported(__FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		echo '__METHOD__ not supported';
		throw new sipgateAPI_Exception(__METHOD__ . " not supported");
		}

	public function UserdataGreetingGet(){
		echo "method called\n";
		echo $this->prefix . __FUNCTION__; exit;
		if ( ! $this->methodSupported($this->prefix . __FUNCTION__) ) {
			echo 'Method not supported';
			throw new sipgateAPI_Exception("Method not supported", 400);
			};
		echo '_METHOD_ not supported';
		throw new sipgateAPI_Exception(__METHOD__ . " not supported");
		}
	public function click2dial($caller, $callee){
		$caller = self::SIP_URI_prefix . $caller . self::SIP_URI_host;
		$callee = self::SIP_URI_prefix . $callee . self::SIP_URI_host;
		$this->samurai_SessionInitiate($caller, $callee, "voice", "");
		}
	public function methodSupported($MethodName){
		if ( !$this->supportedMethods) {
			$this->prefix = "system.";
			$this->supportedMethods = $this->listMethods();
			};
		if ( !(array_search($MethodName, $this->supportedMethods) === FALSE)) {
			return TRUE;
			};
		return FALSE;
		}

	public function tosSupported($TOS){
		if ( !$this->supportedTOS) {
			$MethodName = str_replace("_", ".", $MethodName);
			$this->supportedTOS = $this->samurai_TosListGet();
			};
		if ( !(array_search($TOS, $this->supportedTOS) === FALSE)) {
			return TRUE;
			};
		return FALSE;
		}
	protected function xmlrpcArray(&$array) {
		$tmp = array();
		foreach ($array as $value) {
			$tmp[] = new xmlrpcval($value);
			};
		return $tmp;
		}
	public function setPrefix($pre = self::prefix){
		$this->prefix = $pre;
		}
	public function __call($method, $arguments){
		$v = array();
		if ($this->prefix == "system." ) {
			switch ($method) {
				case "listMethods" 	: break;
				case "methodHelp"	:
					$v["MethodName"] = new xmlrpcval($arguments[0], "string");
				case "methodSignature"	:
					$v["MethodName"] = new xmlrpcval($arguments[0], "string");
				case "serverInfo"	: break;
				}
			}
		elseif ($this->prefix =="samurai.") {
			echo 'Not yet supported, use old method name with underscore';
			throw new sipgateAPI_Exception("Not yet supported, use old method name with underscore");
			switch ($method) {}	
			};
		$val = new xmlrpcval();
		$val->addStruct( $v );
		$values[] = $val;
		$m = new xmlrpcmsg($this->prefix . $method , $values);
		$r = $this->client->send($m);
		if (!$r->faultCode()) {
			$php_r = php_xmlrpc_decode($r->value());
			return $php_r;
			}
		else {
			echo 'Error';
			throw new sipgateAPI_Server_Exception($r->faultString(), $r->faultCode());
			}
		}

*/
	}
