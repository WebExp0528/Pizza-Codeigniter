<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Setting extends CI_Model
{
	public function get()
	{
		$query = $this->db->get('oos_setting');
		return $query;
	}
	
	public function get_by_store_id($store_id = '')
	{
		$query = $this->db->get_where('oos_setting', array('store_id' => $store_id));
		if ($query->num_rows() == 0)
			return 0;
		
		$row = $query->result();
		return $row[0];
	}
	
	function get_by_option($option = 'region', $value = '')
	{
		if ($option == 'region')
		{
			$sql = 'SELECT s.* FROM oos_setting s 
					LEFT JOIN oos_user u ON u.id=s.store_id 
					WHERE s.region_name=\'' . $value . '\' AND u.is_approve = \'Y\'';
		}
		else if ($option == 'postcode')
		{
			//$sql = 'SELECT s.* FROM oos_setting s 
			//		LEFT JOIN oos_user u ON u.id=s.store_id 
			//		WHERE s.postcode=\'' . $value . '\' AND u.is_approve = \'Y\'';
			$sql = 'SELECT s.* FROM oos_setting s 
					INNER JOIN oos_user u ON u.id=s.store_id  AND u.is_approve = \'Y\' 
					INNER JOIN oos_deliver_area d ON d.store_id=s.store_id 
					WHERE s.postcode=\''.$value.'\' OR d.postcode=\''.$value.'\' 
					GROUP BY s.id';
		}
		else
		{
			return null;
		}
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function get_count($store_id)
	{
		$query = $this->db->select('COUNT(id) AS cnt')->get_where('oos_setting', array('store_id' => $store_id));
		if ($query->num_rows() == 0)
			return 0;
		
		$row = $query->result();
		return $row[0]->cnt;
	}
	
	public function get_closed_shops($month = '', $day = '')
	{
		if (empty($month))
			$month = date('m');
		if (empty($day))
			$day = date('d');
		$sql = 'SELECT s.* FROM oos_setting s INNER JOIN oos_calendar c ON c.store_id=s.store_id AND c.month=\''.$month.'\' AND c.day=\''.$day.'\' AND c.type=\'closed\'';
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function add($data = array())
	{
		$result = $this->db->insert('oos_setting', $data);
		return $result;
	}
	
	public function add_workinghours($data = array())
	{
		$result = $this->db->insert('oos_workinghours', $data);
		return $result;
	}
	
	public function update($data = array(), $store_id)
	{
		$result = $this->db->update('oos_setting', $data, array('store_id' => $store_id));
		return $result;
	}
	
	public function update_workinghours($data = array(), $store_id, $day_number)
	{
		$result = $this->db->update('oos_workinghours', $data, array('store_id' => $store_id, 'day_number' => $day_number));
		return $result;
	}
}
?>