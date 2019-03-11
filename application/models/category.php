<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Category extends CI_Model
{
	public function get($store_id = 0)
	{
		$query = $this->db->select('*')->order_by('id', '')->get_where('oos_category', array('store_id' => $store_id));
		return $query;
	}
	
	public function get_by_id($id)
	{
		$query = $this->db->select('*')->get_where('oos_category', array('id' => $id));
		if ($query->num_rows() == 0)
			return null;			
		
		$row = $query->result();
		return $row[0];
	}
	
	public function copy($id)
	{
		$sql = 'INSERT INTO oos_category(name, description, is_active, image_url, created_on_date, last_modified_on_date, last_modified_by_user, seo_name, store_id) 
				SELECT name, description, is_active, image_url, now(), last_modified_on_date, last_modified_by_user, seo_name, store_id 
				FROM oos_category WHERE id=' . $id;
		$result = $this->db->query($sql);
		
		if ($result)
		{
			$sql = 'INSERT INTO oos_size(`name`, `id_category`, `desc`) 
					SELECT `name`, ' . get_category_last_id() . ', `DESC` 
					FROM oos_size WHERE id_category=' . $id;
			$this->db->query($sql);
			
			$sql = 'INSERT INTO oos_topping(`price_add`, `id_category`, `topping_name`, store_id) 
					SELECT `price_add`, ' . get_category_last_id() . ', `topping_name`, store_id 
					FROM oos_topping WHERE id_category=' . $id;
			$this->db->query($sql);
		}
		return $result;
	}
	
	public function add($data = array())
	{
		$result = $this->db->insert('oos_category', $data);
		return $result;
	}
	
	function get_category_last_id()
	{
		$query = $this->db->query('SELECT LAST_INSERT_ID() AS lid FROM oos_category');
		if ($query->num_rows() == 0) {
			return 0;
		}
		$row = $query->result();
		$last_id = $row[0]->lid;
	}
	
	public function delete($id)
	{
		$result = $this->db->delete('oos_category', array('id' => $id));
		
		if ($result)
		{
			$this->db->delete('oos_size', array('id_category' => $id));
			$this->db->delete('oos_topping', array('id_category' => $id));
		}
		return $result;
	}

	public function update($data = array(), $id)
	{
		$result = $this->db->update('oos_category', $data, array('id' => $id));
		return $result;
	}
	
	function add_size($data = array())
	{
		$result = $this->db->insert('oos_size', $data);
		return $result;
	}
	
	function update_size($size_id, $data = array())
	{
		$result = $this->db->update('oos_size', $data, array('id' => $size_id));
		return $result;
	}
	
	function delete_size($size_id)
	{
		$result = $this->db->delete('oos_size', array('id' => $size_id));
		return $result;
	}
}

?>