<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User extends CI_Model
{
	public function confirm($user_id, $user_pass)
	{
		//$query = "select id from oos_user where user_id='" . $_REQUEST[log] . "' and user_passwd=md5('" . $_REQUEST[pwd] . "')";
		$qyert = $this->db->select('id')->get_where('oos_user', array('user_id' => $user_id, 'user_passwd' => md5($user_pass)));
		return $qyert;
	}
	
	public function get($fields = array(), $limit = '', $offset = 0)
	{
		$orderby = '';
		$where = '';
		$sql = 'SELECT * FROM oos_user';
		
		if (is_array($fields) && count($fields) > 0)
		{
			if ($fields['key_email'])
			{
				$where .= ' AND user_email LIKE \'%' . $fields['key_email'] . '%\'';
			}
			if ($fields['key_name'])
			{
				$where .= ' AND user_id LIKE \'%' . $fields['key_name'] . '%\'';
			}
			if ($fields['key_telephone'])
			{
				$where .= ' AND telephone LIKE \'%' . $fields['key_telephone'] . '%\'';
			}
			if ($fields['key_status'])
			{
				$where .= ' AND is_approve = \'' . $fields['key_status'] . '\'';
			}
			if ($fields['key_date'])
			{
				$orderby = ' ORDER BY added_date ' . $fields['key_date'];
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
		$query = $this->db->select('*')->get_where('oos_user', array('id' => $id));
		if ($query->num_rows() == 0)
			return null;			
		
		$row = $query->result();
		return $row[0];
	}
	
	public function get_by_userid($user_id)
	{
		$query = $this->db->select('*')->get_where('oos_user', array('user_id' => $user_id));
		if ($query->num_rows() == 0)
			return null;			
		
		$row = $query->result();
		return $row[0];
	}
	
	public function delete($id)
	{
		$this->db->delete('oos_user', array('id' => $id));
		$this->db->delete('oos_category', array('store_id' => $id));
		$this->db->delete('oos_order', array('store_id' => $id));
		$this->db->delete('oos_deliver_area', array('store_id' => $id));
		$this->db->delete('oos_price', array('store_id' => $id));
		$this->db->delete('oos_product', array('store_id' => $id));
		$this->db->delete('oos_setting', array('store_id' => $id));
		$this->db->delete('oos_specialoffer', array('store_id' => $id));
		$this->db->delete('oos_topping', array('store_id' => $id));
		$this->db->delete('oos_workinghours', array('store_id' => $id));
	}
	
	public function add($data = array())
	{
		$query = $this->db->select('id')->get_where('oos_user', array('user_id' => $data['user_id']));
		if ($query->num_rows() > 0)
		return false;
		
		$data['added_date'] = mdate('Y-m-d');
		$result = $this->db->insert('oos_user', $data);
		return $result;
	}
	
	public function update($data = array(), $id)
	{
		$result = $this->db->update('oos_user', $data, array('id' => $id));
		return $result;
	}
	
	function userRegister()
	{
		$email_chk = $this->userEmailDuplicateChk();
		if (!$email_chk)
		{
			echo "dbl-email";
			exit();
		}
		$sql = "insert into oos_customer(user_id, passwd, firstname, lastname, gender, email, telephone, region, city, street, house_no, postcode,address_detail, date_added) values('$_REQUEST[user_id]', md5('$_REQUEST[passwd]'), '$_REQUEST[firstname]', '$_REQUEST[lastname]', '$_REQUEST[gender]', '$_REQUEST[email]', '$_REQUEST[telephone]', '$_REQUEST[region]', '$_REQUEST[city]','$_REQUEST[street]', '$_REQUEST[house_no]', '$_REQUEST[postcode]', '$_REQUEST[details]', now())";
		$query = $this->db->query($sql);
		//$result = $query->result();
		if ($query)
			echo "ok";
		else
			echo "false";
		exit();
	}
	
	function userLogin()
	{
		$email = $this->input->post('email', true);
		$passwd = $this->input->post('passwd', true);
		$sql = "SELECT * FROM oos_customer WHERE email='$email' and passwd= md5('$passwd')";
		
		$query = $this->db->query($sql);
		//echo $query->num_rows();
		if ($query->num_rows() > 0)
		{
			$row = $query->result();
			$user = $row[0];
			
			if ($user->approved == "Y")
			{
				//$this->session->set_userdata('SESS_CUSTOMER_ID', $user->id);
				//$this->session->set_userdata('SESS_CUSTOMER_USERID', $user->user_id);
				//$this->session->set_userdata('SESS_CUSTOMER_EMAIL', $user->email);
				//$this->session->set_userdata('SESS_CUSTOMER_NAME', $user->user_id);
				//$this->session->set_userdata('SESS_CUSTOMER_POSTCODE', $user->postcode);
				$_SESSION['SESS_CUSTOMER_ID'] = $user->id;
				$_SESSION['SESS_CUSTOMER_EMAIL'] = $user->email;
				
				echo "ok";
			}
			else
			{
				echo "non-approve";
			}
			exit();
		}
		else
		{
			echo "false";
			exit();
		}
	}
	
	function userLogout()
	{
		//session_unset();
		//$this->session->unset_userdata('SESS_CUSTOMER_ID');
		//$this->session->unset_userdata('SESS_CUSTOMER_USERID');
		//$this->session->unset_userdata('SESS_CUSTOMER_EMAIL');
		//$this->session->unset_userdata('SESS_CUSTOMER_NAME');
		//$this->session->unset_userdata('SESS_CUSTOMER_POSTCODE');
		$_SESSION['SESS_CUSTOMER_ID'] = '';
		$_SESSION['SESS_CUSTOMER_EMAIL'] = '';
		unset($_SESSION['SESS_CUSTOMER_ID']);
		unset($_SESSION['SESS_CUSTOMER_EMAIL']);
		
		echo "logout";
		exit();
	}
	
	function userEmailDuplicateChk()
	{
		$email = $this->input->post('email', true);
		$sql = "select * from oos_customer where email='$email'";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return false;
		}
		return true;
	}
}

?>
