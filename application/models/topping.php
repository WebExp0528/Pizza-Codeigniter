<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Topping extends CI_Model
{
	public function get($store_id = 0)
	{
		$sql = 'SELECT ot.*, oc.name AS cate_name, os.name AS size_name 
				FROM oos_topping ot 
				LEFT JOIN oos_category oc ON ot.id_category=oc.id 
				LEFT JOIN oos_size os ON ot.id_size=os.id 
				WHERE ot.store_id=' . $store_id . ' ORDER BY ot.id_category';
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function get_by_id($id)
	{
		$query = $this->db->select('*')->get_where('oos_topping', array('id' => $id));
		if ($query->num_rows() == 0)
			return null;
		
		$row = $query->result();
		return $row[0];
	}
	
	public function copy($id)
	{
		$sql = 'INSERT INTO oos_topping(`price_add`, `id_category`, `topping_name`, id_size, store_id) 
				SELECT `price_add`, id_category, `topping_name`, id_size, store_id 
				FROM oos_topping WHERE id=' . $id;
		$result = $this->db->query($sql);
		return $result;
	}
	
	public function add($data = array())
	{
		$result = $this->db->insert('oos_topping', $data);
		return $result;
	}
	
	public function delete($id)
	{
		$result = $this->db->delete('oos_topping', array('id' => $id));
		return $result;
	}
	
	public function update($data = array(), $id)
	{
		$result = $this->db->update('oos_topping', $data, array('id' => $id));
		return $result;
	}
	

}

?>