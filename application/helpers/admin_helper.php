<?php
if (!function_exists('signin_check'))
{
	function signin_check()
	{
		$CI = & get_instance();
		if (!$CI->session->userdata('SESS_USER_ID') || !$CI->session->userdata('SESS_STORE_ID'))
		{
			redirect('admin');
		}
		return true;
	}
}

if (!function_exists('admin_signin_check'))
{
	function admin_signin_check()
	{
		$CI = & get_instance();
		if (!$CI->session->userdata('SESS_USER_ID') || !$CI->session->userdata('SESS_STORE_ID') || $CI->session->userdata('SESS_PRIVILEGE') != 'admin')
		{
			redirect('admin');
		}
		return true;
	}
}

if (!function_exists('get_process_flag'))
{
	function get_process_flag($stord_id)
	{
		$CI = & get_instance();
		
		$query = $CI->db->select('*')->get_where('oos_setting', array('store_id' => $stord_id));
		if ($query->num_rows() == 0)
			return 3;
		
		$row = $query->result();
		$setting_postcode = $row[0]->postcode;
		$query = $CI->db->select('count(*) AS cnt')->get_where('oos_deliver_area', array('postcode' => $setting_postcode));
		if ($query->num_rows() >= 0)
		{
			$row = $query->result();
			if ($row[0]->cnt == 0)
				return 4;
		}
		return 0;
	}
}

if (!function_exists('put_alert'))
{
	function put_alert($process_flag = 0)
	{
		$CI = & get_instance();
		
		$html = '';
		if ($process_flag == 1)
		{
			$html .= '<div class="alert_visible">The proccess is execute successfully.</div>';
			$html .= '<script>$(".alert_visible").delay(2000).fadeOut(400);</script>';
		}
		else if ($process_flag == 2)
		{
			$html .= '<div class="alert_visible">User ID already is existed.</div>';
			$html .= '<script>$(".alert_visible").delay(2000).fadeOut(400);</script>';
		}
		else if ($process_flag == 3 && $CI->session->userdata('SESS_USER_ID') != "admin")
		{
			$html .= '<div class="alert_visible">Please set your shop.</div>';
			$html .= '<script>$(".alert_visible").delay(2000).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400);</script>';
		}
		else if ($process_flag == 4 && $CI->session->userdata('SESS_USER_ID') != "admin")
		{
			$html .= '<div class="alert_visible">You must insert your shop\'s postcode in Delivery Area.</div>';
			$html .= '<script>$(".alert_visible").delay(2000).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400).fadeOut(1400).fadeIn(400);</script>';
		}
		return $html;
	}
}

if (!function_exists('pagination'))
{
	function pagination($page = 1, $total_page = 1, $record_num = 5, $total_record = 0)
	{
		$html = '';
		$html .= '<div style="float:left; width:70%;">';
		$html .= '<input type="hidden" name="page" id="id_page" value="' . $page . '" />';
		$html .= '<input type="hidden" name="totalpages" id="id_totalpages" value="' . $total_page . '" />';
		$html .= '<div style="float:left; padding-right:10px;">';
		$html .= img(array('src' => 'images/admin/pagination.gif', 'border' => 0, 'usemap' => '#Map'));
		$html .= '</div>';
		$html .= '<div style="float:left; width:2%; color:#999;">|</div>';
		$html .= '<div style="float:left; width:45%; text-align:center;">';
		
		for ($i = $page - 3; $i <= $page + 3; $i++)
		{
			if ($i > 0 && $i <= $total_page)
			{
				if ($i == $page)
				{
					$html .= '<span class="pagination" style="color:#900;">' . $i . '</span>';
				}
				else
				{
					$html .= '<a class="pagination link" href="#" alt="' . $i . '">' . $i . '</a>';
				}
			}
		}
		
		$html .= '</div>';
		$html .= '<div style="float: left; width: 5%; color: #999;">|</div>';
		$html .= '<div style="float: left; width: 30%; font-size: 12px; padding-top: 2px;">';
		
		$end_record = $page * $record_num;
		if ($end_record > $total_record)
			$end_record = $total_record;
		
		$start = ($total_record == 0) ? 0 : ($page - 1) * $record_num + 1;
		$html .= $start . ' - ' . $end_record . ' of ' . $total_record . ' object';
		
		$html .= '</div>';
		$html .= '<map name="Map" id="Map">';
		$html .= '<area shape="rect" id="id_first" coords="2,3,19,19" href="#" />';
		$html .= '<area shape="rect" id="id_prev" coords="22,3,39,19" href="#" />';
		$html .= '<area shape="rect" id="id_next" coords="40,3,59,19" href="#" />';
		$html .= '<area shape="rect" id="id_last" coords="61,3,79,18" href="#" />';
		$html .= '</map>';
		$html .= '</div>';
		return $html;
	}
}

if (!function_exists('get_shops'))
{
	function get_shops()
	{
		$CI = & get_instance();
		$query = $CI->db->get('oos_setting');
		return $query;
	}
}

if (!function_exists('get_status'))
{
	function get_order_status()
	{
		$CI = & get_instance();
		$query = $CI->db->order_by('id', 'ASC')->get('oos_order_status');
		return $query;
	}
}

if (!function_exists('get_product'))
{
	function get_product($prd_id = '', $prd_size = '')
	{
		$CI = & get_instance();
		$sql = 'SELECT p.*, c.price, s.name size, s.desc size_detail FROM oos_product p 
				LEFT JOIN oos_price c ON c.id_product=p.id 
				LEFT JOIN oos_size s ON s.id=c.id_size 
				WHERE p.id=' . $prd_id;
		if (!empty($prd_size))
			$sql .= 'AND c.id_size=' . $prd_size;
		
		$query = $CI->db->query($sql);
		if ($query->num_rows() == 0)
			return null;
		
		$row = $query->result();
		return $row[0];
	}
}

if (!function_exists('get_topping_by_id'))
{
	function get_topping_by_id($id)
	{
		$CI = & get_instance();
		$query = $CI->db->get_where('oos_topping', array('id' => $id));
		if ($query->num_rows() == 0)
			return null;
		
		$row = $query->result();
		return $row[0];
	}
}

if (!function_exists('get_special_offer'))
{
	function get_special_offer($id, $shop_id)
	{
		$CI = & get_instance();
		$sql = 'SELECT off.id offer_id, off.offer_name, off.offer_price, off.extra_product, cat.* FROM oos_specialoffer off 
				LEFT JOIN oos_specialoffer_contents con ON off.id = con.id_offer 
				JOIN oos_category cat ON cat.id = con.id_category 
				WHERE off.store_id = ' . $shop_id . ' AND off.id=' . $id;
		$query = $CI->db->query($sql);
		return $query->result();
	}
}

if (!function_exists('get_size'))
{
	function get_size($category_id = 0)
	{
		$CI = & get_instance();
		$query = $CI->db->order_by('id')->get_where('oos_size', array('id_category' => $category_id));
		return $query;
	}
}

if (!function_exists('get_category'))
{
	function get_category($store_id = 0, $active = '')
	{
		$CI = & get_instance();
		
		$where = array();
		$where['store_id'] = $store_id;
		if (!empty($active))
			$where['is_active'] = $active;
		
		$query = $CI->db->order_by('id')->get_where('oos_category', $where);
		return $query;
	}
}

if (!function_exists('get_price'))
{
	function get_price($product_id = '', $size = '')
	{
		$CI = & get_instance();
		if (empty($size))
			$sql = 'SELECT price FROM oos_price, oos_size WHERE id_product=' . $product_id . ' AND id_size=oos_size.id';
		else
			$sql = 'SELECT price FROM oos_price WHERE id_product=' . $product_id . ' AND id_size='.$size;
			
		$query = $CI->db->query($sql);
		return $query;
	}
}

if (!function_exists('get_region'))
{
	function get_region()
	{
		$CI = & get_instance();
		$query = $CI->db->select('*')->group_by('region_name')->order_by('region_name')->get('oos_cityname');
		return $query;
	}
}

if (!function_exists('get_cityname_by_region'))
{
	function get_cityname_by_region($region_name = '')
	{
		$CI = & get_instance();
		$query = $CI->db->select('city_name')->group_by('city_name')->order_by('city_name')->get_where('oos_cityname', array('region_name' => $region_name));
		return $query;
	}
}

if (!function_exists('get_postcode_by_cityname'))
{
	function get_postcode_by_cityname($city_name = '')
	{
		$CI = & get_instance();
		$query = $CI->db->select('postcode')->order_by('postcode')->get_where('oos_cityname', array('city_name' => $city_name));
		return $query;
	}
}

?>
