<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Special_offer extends CI_Model
{
	public function get($store_id = 0)
	{
		$query = $this->db->order_by('id', '')->get_where('oos_specialoffer', array('store_id' => $store_id));
		return $query;
	}
	
	public function get_by_id($id)
	{
		$query = $this->db->select('*')->get_where('oos_specialoffer', array('id' => $id));
		if ($query->num_rows() == 0)
			return null;
		
		$row = $query->result();
		return $row[0];
	}
	
	public function copy($id)
	{
		$sql = 'INSERT INTO oos_specialoffer(store_id, offer_code, offer_name, add_date) 
				SELECT store_id, offer_code, offer_name, now() 
				FROM oos_specialoffer where id=' . $id;
		$result = $this->db->query($sql);
		
		if ($result)
		{
			$sql = 'INSERT INTO oos_specialoffer_contents(`id_offer`, `id_category`) 
					SELECT ' . mysql_insert_id() . ', `id_category` 
					FROM oos_specialoffer_contents WHERE id_offer=' . $id;
			$result = $this->db->query($sql);
		}
		return $result;
	}
	
	public function add($data = array())
	{
		$result = $this->db->insert('oos_specialoffer', $data);
		return $result;
	}
	
	public function add_contents($data = array())
	{
		$result = $this->db->insert('oos_specialoffer_contents', $data);
		return $result;
	}
	
	public function delete($id)
	{
		$result = $this->db->delete('oos_specialoffer', array('id' => $id));
		
		if ($result)
		{
			$this->db->delete('oos_specialoffer_contents', array('id_offer' => $id));
		}
		return $result;
	}
	
	public function delete_contents($id)
	{
		$result = $this->db->delete('oos_specialoffer_contents', array('id_offer' => $id));
		return $result;
	}
	
	public function update($data = array(), $id)
	{
		$result = $this->db->update('oos_specialoffer', $data, array('id' => $id));
		return $result;
	}
	
	public function get_by_shop_and_id($shop_id, $offer_id)
	{
		$sql = 'SELECT off.id offer_id, off.offer_name, off.offer_price, off.extra_product, cat.* FROM oos_specialoffer off 
				LEFT JOIN oos_specialoffer_contents con ON off.id = con.id_offer 
				LEFT JOIN oos_category cat ON cat.id = con.id_category 
				WHERE off.store_id = ' . $shop_id . ' AND off.id=' . $offer_id . ' AND cat.is_active = \'Y\' 
				ORDER By con.id';
		$query = $this->db->query($sql);
		
		if ($query->num_rows() == 0)
			return null;
		
		$row = $query->result();
		return $row;
	}

}

?>