<?php
if (!function_exists('get_citynames'))
{
	function get_citynames()
	{
		$CI = &get_instance();
		//$sql = 'SELECT * FROM oos_cityname WHERE region_name != \'\' GROUP BY region_name ORDER BY region_name';
		$query = $CI->db->select(array('id', 'region_name'))->group_by('region_name')->order_by('region_name')->get_where('oos_cityname', 'region_name != \'\'');
		return $query;
	}
}

if (!function_exists('get_category_last_id'))
{
	function get_category_last_id()
	{
		$CI = &get_instance();
		
		$query = $CI->db->select('max(id) as val')->get_where('oos_category');
		if ($query->num_rows() == 0)
			return '';
		$row = $query->result();
		return $row[0]->val;
	}
}

if (!function_exists('get_category_list'))
{
	function get_category_list($storeid = '')
	{
		$CI = &get_instance();
		
		$query = $CI->db->get_where('oos_category', array('store_id' => $storeid, 'is_active' => 'y'));
		return $query;
	}
}

if (!function_exists('get_product_last_id'))
{
	function get_product_last_id()
	{
		$CI = &get_instance();
		
		$query = $CI->db->select('max(id) as val')->get_where('oos_product');
		if ($query->num_rows() == 0)
			return '';
		$row = $query->result();
		return $row[0]->val;
	}
}

if (!function_exists('get_regionname'))
{
	function get_regionname($id)
	{
		$CI = &get_instance();
		
		$query = $CI->db->select('region_name')->get_where('oos_cityname', 'id = \'' . $id . '\'');
		if ($query->num_rows() == 0)
			return '';
		$row = $query->result();
		return $row[0]->region_name;
	}
}

if (!function_exists('get_cityname_by_postcode'))
{
	function get_cityname_by_postcode($postcode)
	{
		$CI = &get_instance();
		
		$query = $CI->db->get_where('oos_cityname', 'postcode = \'' . $postcode . '\'');
		if ($query->num_rows() == 0)
			return '';
		$row = $query->result();
		return $row[0]->city_name;
	}
}

if (!function_exists('get_workinghour'))
{
	function get_workinghour($store_id, $day_num)
	{
		$CI = &get_instance();
		
		$query = $CI->db->select('*')->get_where('oos_workinghours', array('store_id' => $store_id, 'day_number' => $day_num));
		if ($query->num_rows() > 0)
		{
			$row = $query->result();
			return $row[0];
		}
		return null;
	}
}

if (!function_exists('getTotalRating'))
{
	function getTotalRating($shop_id, $star = '')
	{
		$CI = &get_instance();
		
		if ($star)
			$query = $CI->db->select(array('SUM( rate_star ) sum', 'COUNT( id ) count'))->get_where('oos_rate_data', array('store_id' => $shop_id, 'rate_star' => $star));
		else
			$query = $CI->db->select(array('SUM( rate_star ) sum', 'COUNT( id ) count'))->get_where('oos_rate_data', array('store_id' => $shop_id));
		
		if ($query->num_rows() > 0)
		{
			$row = $query->result();
			return $row[0];
		}
		return null;
	}
}

if (!function_exists('getRatingList'))
{
	function getRatingList($shop_id, $limit = '')
	{
		$CI = &get_instance();
		
		if ($limit > 0)
			$query = $CI->db->select('*')->order_by('rate_date', 'DESC')->get_where('oos_rate_data', array('store_id' => $shop_id), $limit, 0);
		else
			$query = $CI->db->select('*')->order_by('rate_date', 'DESC')->get_where('oos_rate_data', array('store_id' => $shop_id));
		
		if ($query->num_rows() > 0)
		{
			return $query->result();
		}
		return null;
	}
}

if (!function_exists('get_shop_data'))
{
	function get_shop_data($store_id)
	{
		$CI = &get_instance();
		$sql = "SELECT u.id,u.user_id,u.user_email,s.shop_name,s.imprint,s.address ,s.city,s.postcode,s.email,s.fax";
		$sql .= " FROM oos_user u LEFT JOIN oos_setting s ON u.id = s.store_id";
		$sql .= " WHERE u.id ='{$store_id}'";
		$result = $CI->db->query($sql);
		if ($result->num_rows() == 0)
			return null;
		
		$row = $result->result();
		return $row[0];
	}
}

if (!function_exists('get_offer_last_id'))
{
	function get_offer_last_id()
	{
		$CI = &get_instance();
		$result = $CI->db->select('MAX(id) as mid')->get('oos_specialoffer');
		if ($result->num_rows() == 0)
			return 0;
		
		$row = $result->result();
		return $row[0]->mid;
	}
}

if (!function_exists('get_shop_by_name'))
{
	function get_shop_by_name($shop_name)
	{
		$CI = &get_instance();
		$sql = "SELECT u.id, u.user_id, u.user_email, s.id AS shop_id, s.shop_name, s.imprint, s.address, s.city, s.postcode, s.email, s.fax, s.style_url FROM oos_user u 
				LEFT JOIN oos_setting s ON u.id = s.store_id 
				WHERE s.shop_name='" . $shop_name . "'";
		$result = $CI->db->query($sql);
		
		if ($result->num_rows() == 0)
			return null;
		
		$row = $result->result();
		return $row[0];
	}
}

if (!function_exists('get_topping_list'))
{
	function get_topping_list($cid, $sid = 0)
	{
		$CI = &get_instance();
		$where = array('id_category' => $cid);
		if ($sid != 0)
		{
			$where['id_size'] = $sid;
		}
		$query = $CI->db->get_where('oos_topping', $where);
		
		return $query;
	}
}

if (!function_exists('get_product_list'))
{
	function get_product_list($cid)
	{
		$CI = &get_instance();
		$result = $CI->db->get_where('oos_product', array('id_category' => $cid, 'is_active' => 'Y', 'is_offer' => 'Y'));
		return $result;
	}
}

if (!function_exists('getSessionData'))
{
	function getSessionData($session_id, $shop_id = '')
	{
		$CI = &get_instance();
		$sql = "SELECT * FROM oos_session WHERE session_id ='{$session_id}'";
		$sql .= " AND store_id=" . $shop_id;
		$result = $CI->db->query($sql);
		$row = $result->result();
		return $row[0];
	}
}

?>