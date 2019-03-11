<?php
class session_table extends mosDBTable
{
	var $id				= null;
	var $session_id		= null;
	var $store_id		= null;	
	var $date			= null;	
	var $time			= null;	
	var $userid			= null;	
	var $order_data		= null;

	function session_table( &$database ) {
		$this->mosDBTable( 'oos_session', 'id', $database );
	}

}
class customer_table extends mosDBTable
{
	var $id				= null;
	var $store_id		= null;
	var $user_id		= null;	
	var $passwd			= null;	
	var $firstname		= null;	
	var	$lastname		= null;
	var $gender			= null;	
	var	$email			= null;
	var $telephone		= null;	
	var	$city			= null;
	var $street			= null;	
	var	$house_no 		= null;
	var $postcode 		= null;	
	var	$company		= null;
	var $fax			= null;	
	var	$date_added		= null;
	var $address_id		= null;	
	var	$approved		= null;
	var	$last_order_date		= null;
	
	function customer_table( &$database ) {
		$this->mosDBTable( 'oos_customer', 'id', $database );
	}

}
class oos_letter extends mosDBTable
{
	var $id				= null;
	var $store_id		= null;
	var $content		= null;	
	var	$add_date		= null;
	
	function oos_letter( &$database ) {
		$this->mosDBTable( 'oos_letter', 'id', $database );
	}

}
class order extends mosDBTable
{
	var $id				= null;  
	var $store_id		= null;
	var $customer_id	= null;	
	var $date			= null;	
	var $firstname		= null;
	var	$lastname		= null;
	var $gender			= null;
	var $company		= null;
	var $department		= null;
	var $street			= null;
	var $house_no		= null;
	var $postcode		= null;
	var $city			= null;
	var $add_details	= null;
	var $telephone		= null;
	var $email			= null;
	var $details		= null;
	var $order_data		= null;
	var $delivery_charge	= null;
	var $total			= null;

	function order( &$database ) {
		$this->mosDBTable( 'oos_order', 'id', $database );
	}

}
class rate_data extends mosDBTable
{
	var $id				= null;  
	var $store_id		= null;
	var $customer_id	= null;	
	var $customer_name	= null;	
	var $rate_star		= null;
	var	$comment		= null;
	var $rate_date		= null;
	var $order_id		= null;

	function rate_data( &$database ) {
		$this->mosDBTable( 'oos_rate_data', 'id', $database );
	}

}

?>
