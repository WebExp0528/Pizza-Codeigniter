<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Price extends CI_Model
{
	public function add($data = array())
	{
		$result = $this->db->insert('oos_price', $data);
		return $result;
	}
	
	public function delete($product_id)
	{
		$result = $this->db->delete('oos_price', array('id_product' => $product_id));
		return $result;
	}

//	public function update($data = array(), $id)
//	{
//		$result = $this->db->update('oos_product', $data, array('id' => $id));
//		return $result;
//	}
}

?>