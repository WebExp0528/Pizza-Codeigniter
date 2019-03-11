<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Delivery_area extends CI_Model
{
	public function get($store_id = 0)
	{
		$query = $this->db->get_where('oos_deliver_area', array('store_id' => $store_id));
		return $query;
	}
	
	public function get_by_id($id)
	{
		$query = $this->db->get_where('oos_deliver_area', array('id' => $id));
		if ($query->num_rows() == 0)
			return null;
		
		$row = $query->result();
		return $row[0];
	}
	
	public function add($data = array())
	{
		$result = $this->db->insert('oos_deliver_area', $data);
		return $result;
	}
	
	public function delete($id)
	{
		$result = $this->db->delete('oos_deliver_area', array('id' => $id));
		return $result;
	}
	
	public function update($data = array(), $id)
	{
		$result = $this->db->update('oos_deliver_area', $data, array('id' => $id));
		return $result;
	}
}

?>