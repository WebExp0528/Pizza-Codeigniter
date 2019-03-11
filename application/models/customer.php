<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Customer extends CI_Model
{
	public function get($fields = array(), $limit = '', $offset = 0)
	{
		$orderby = '';
		$where = '';
		$sql = 'SELECT * FROM oos_customer';
		
		if (is_array($fields) && count($fields) > 0)
		{
			if ($fields['key_email'])
			{
				$where .= ' AND email LIKE \'%' . $fields['key_email'] . '%\'';
			}
			if ($fields['key_name'])
			{
				$where .= ' AND (firstname LIKE \'%'.$fields['key_name'].'%\' or lastname LIKE \'%'.$fields['key_name'].'%\')';
			}
			if ($fields['key_address'])
			{
				$where .= ' AND (city LIKE \'%'.$fields['key_address'].'%\' or street LIKE \'%'.$fields['key_address'].'%\' or postcode LIKE \'%'.$fields['key_address'].'%\')';
			}
			if ($fields['key_telephone'])
			{
				$where .= ' AND telephone LIKE \'%' . $fields['key_telephone'] . '%\'';
			}
			if ($fields['key_status'])
			{
				$where .= ' AND approved = \'' . $fields['key_status'] . '\'';
			}
			if ($fields['key_date'])
			{
				$orderby = ' ORDER BY date_added ' . $fields['key_date'];
			}
			
			if ($where != '')
			{
				$where = substr($where, 4, strlen($where));
				$sql .= ' WHERE ' . $where;
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
		$query = $this->db->select('*')->get_where('oos_customer', array('id' => $id));
		if ($query->num_rows() == 0)
			return null;			
		
		$row = $query->result();
		return $row[0];
	}
	
	public function delete($id)
	{
		$result = $this->db->delete('oos_customer', array('id' => $id));
		return $result;
	}
	
	public function update($data = array(), $id)
	{
		$result = $this->db->update('oos_customer', $data, array('id' => $id));
		return $result;
	}
}

?>