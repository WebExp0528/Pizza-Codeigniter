<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Calendar extends CI_Model
{
	public function get($store_id = 0, $month = 0, $day = 0)
	{
		$where = array('store_id' => $store_id);
		if ($month != 0){
			$where['month'] = $month;
		}
		if ($day != 0){
			$where['day'] = $day;
		}
		
		$query = $this->db->order_by('month, day', '')->get_where('oos_calendar', $where);
		return $query;
	}
	
	public function add($data = array())
	{
		$result = $this->db->insert('oos_calendar', $data);
		return $result;
	}
	
	public function update($id, $data = array())
	{
		$result = $this->db->update('oos_calendar', $data, array('id' => $id));
		return $result;
	}
	
	public function delete($id)
	{
		$result = $this->db->delete('oos_calendar', array('id' => $id));
		return $result;
	}

	public function delete_month($store_id, $month)
	{
		$result = $this->db->delete('oos_calendar', array('store_id' => $store_id, 'month' => $month));
		return $result;
	}

}

?>