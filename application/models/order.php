<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Order extends CI_Model
{
	private function filter_ware($fields = array())
	{
		$where = array();
		$where_str = '';
		
		if (isset($fields['s_date']))
			$where[] = 'DATE_FORMAT(oos_order.date,\'%Y-%m-%d\') >= \'' . $fields['s_date'] . '\'';
		if (isset($fields['e_date']))
			$where[] = 'DATE_FORMAT(oos_order.date,\'%Y-%m-%d\') <= \'' . $fields['e_date'] . '\'';
		if (isset($fields['e_date']))
			$where[] = 'oos_order.store_id = \'' . $fields['e_date'] . '\'';
		if (isset($fields['post_code']))
			$where[] = 'oos_order.postcode = \'' . $fields['post_code'] . '\'';
		if (isset($fields['key_orderid']))
			$where[] = 'oos_order.id = \'' . $fields['key_orderid'] . '\'';
		if (isset($fields['key_customername']))
			$where[] = '(oos_order.firstname LIKE \'%' . $fields['key_customername'] . '%\' OR oos_order.lastname LIKE \'%' . $fields['key_customername'] . '%\')';
		if (isset($fields['key_telephone']))
			$where[] = 'oos_order.telephone LIKE \'' . $fields['key_telephone'] . '\'';
		if (isset($fields['key_status']))
			$where[] = 'oos_order.order_status_id = \'' . $fields['key_status'] . '\'';
		if (count($where) > 0)
			$where_str = implode(" AND ", $where);
		
		return $where_str;
	}
	
	public function get($fields = array(), $limit = '', $offset = 0)
	{
		$sql = 'SELECT oos_order.*, status.name status, setting.shop_name FROM oos_order 
			LEFT JOIN oos_order_status status ON oos_order.order_status_id = status.id
			LEFT JOIN oos_setting setting ON setting.store_id = oos_order.store_id';
		
		$where = $this->filter_ware($fields);
		if (!empty($where))
			$sql .= ' WHERE ' . $where;
		if ($fields['key_date'])
			$sql .= ' ORDER BY oos_order.date ' . $fields['key_date'];
		if ($limit !== '')
			$sql .= ' LIMIT ' . $offset . ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	public function total_amount($filter = array())
	{
		$sql = 'SELECT SUM(total) AS amount FROM `oos_order`';
		$where = $this->filter_ware($filter);
		if (!empty($where))
			$sql .= ' WHERE ' . $where;
		
		$query = $this->db->query($sql);
		if ($query->num_rows() == 0)
			return 0;
		$row = $query->result();
		return $row[0]->amount;
	}
	
	public function update($data = array(), $id)
	{
		$result = $this->db->update('oos_order', $data, array('id' => $id));
		return $result;
	}
	
	public function delete($id)
	{
		$result = $this->db->delete('oos_order', array('id' => $id));
		return $result;
	}

	public function get_by_id($id)
	{
		$sql = 'SELECT oos_order.*, name FROM oos_order, oos_order_status WHERE order_status_id=oos_order_status.id AND oos_order.id=' . $id;
		$query = $this->db->query($sql);
		if ($query->num_rows() == 0)
			return null;
		
		$row = $query->result();
		return $row[0];
	}
}
?>