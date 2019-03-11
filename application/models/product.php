<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Product extends CI_Model
{
	public function get($fields = array(), $limit = '', $offset = 0)
	{
		$orderby = '';
		$where = '';
		$sql = 'SELECT distinct pd.*, cg.name as cate_name 
				FROM `oos_product` as pd,oos_price as pr, oos_category as cg 
				WHERE id_category=cg.id and pd.store_id=' . $this->session->userdata('SESS_STORE_ID');
		
		if (is_array($fields) && count($fields) > 0)
		{
			if ($fields['key_product_code'])
			{
				$where .= ' AND code LIKE \'%' . $fields['key_product_code'] . '%\'';
			}
			if ($fields['key_category'])
			{
				$where .= ' AND id_category LIKE \'%' . $fields['key_category'] . '%\'';
			}
			if ($fields['key_product_name'])
			{
				$where .= ' AND pd.name LIKE \'%' . $fields['key_product_name'] . '%\'';
			}
			if ($fields['key_offered'])
			{
				$where .= ' AND is_offer LIKE \'%' . $fields['key_offered'] . '%\'';
			}
			if ($fields['key_status'])
			{
				$where .= ' AND pd.is_active = \'' . $fields['key_status'] . '\'';
			}
			if ($fields['key_price'])
			{
				$where .= ' AND pr.id_product = pd.id';
				$orderby = ' ORDER BY pr.price ' . $fields['key_price'];
			}
			else
			{
				$orderby = " ORDER BY pd.code ASC";
			}
			
			if ($where != '')
			{
				//$where = substr($where, 4, strlen($where));
				$sql .= $where;
			}
			
			$sql .= $orderby;
		}
		if ($limit !== '')
		{
			$sql .= ' LIMIT ' . $offset . ', ' . $limit;
		}
		
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function get_by_id($id)
	{
		$query = $this->db->select('*')->get_where('oos_product', array('id' => $id));
		if ($query->num_rows() == 0)
			return null;			
		
		$row = $query->result();
		return $row[0];
	}
	
	public function copy($id)
	{
		$sql = 'INSERT INTO oos_product(id_category, code, name, description, image_url, is_active, is_offer, created_on_date, seo_name, store_id) 
				SELECT id_category, code, name, description, image_url, is_active, is_offer, created_on_date, seo_name, store_id 
				FROM oos_product where id=' . $id;
		$result = $this->db->query($sql);
		
		if ($result)
		{
			$sql = 'INSERT INTO oos_price(price, id_product, id_size, store_id) 
					SELECT price, ' . get_product_last_id() . ', id_size, store_id 
					FROM oos_price WHERE id_product=' . $id;
			$this->db->query($sql);
		}
		return $result;
	}
	
	public function add($data = array())
	{
		$result = $this->db->insert('oos_product', $data);
		return $result;
	}
	
	public function delete($id)
	{
		$result = $this->db->delete('oos_product', array('id' => $id));
		if ($result)
		{
			$this->db->delete('oos_price', array('id_product' => $id));
		}
		return $result;
	}

	public function update($data = array(), $id)
	{
		$result = $this->db->update('oos_product', $data, array('id' => $id));
		return $result;
	}
}

?>