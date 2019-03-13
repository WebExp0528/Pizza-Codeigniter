<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Ajax extends CI_Controller
{
	public function index()
	{
		echo 'Access corrupted.';
	}
	
	public function login_box()
	{
		$this->load->helper('portal');
		$this->load->helper('date');
		
		$this->load->model('user');
		
		$this->load->library('javascript');
		
		$this->load->view('login_view');
	}
	
	public function signup()
	{
		$this->load->helper('portal');
		$this->load->helper('date');
		
		$this->load->model('user');
		
		$this->load->library('javascript');
		$this->load->view('signup_view');
	}
	
	function user_login()
	{
		//$this->load->library('javascript');
		//$this->load->view('login_view');
		//session_start();
		$this->load->model('user');
		$option = $this->input->post('option', true);
		
		switch ($option)
		{
			case "user_register":
				$this->user->userRegister();
				break;
			case "user_login":
				$this->user->userLogin();
				break;
			case "user_logout":
				$this->user->userLogout();
				break;
		}
	}
	
	public function create_password()
	{
		$r = '';
		for ($i = 0; $i < 8; $i++)
			$r .= chr(rand(0, 25) + ord('a'));
		echo $r;
	}
	
	public function check_password()
	{
		$this->load->model('user');
		
		$user_id = $this->input->post('user_id', true);
		$user_pass = $this->input->post('user_pwd', true);
		
		$result = $this->user->confirm($user_id, $user_pass);
		if ($result->num_rows() > 0)
			echo 1;
		else
			echo 0;
	}
	
	function get_city_name()
	{
		$ret_data = "[";
		$region_name = $this->input->post('region_name', true);
		$sql = "select * from oos_cityname where region_name='" . $region_name . "' group by city_name order by city_name";
		
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$ret_data .= "{\"name\":\"" . $row->city_name . "\"},";
			}
		}
		
		if (strlen($ret_data) > 1)
			$ret_data = substr($ret_data, 0, -1);
		$ret_data .= "]";
		echo $ret_data;
	}
	
	function get_postcode()
	{
		$ret_data = "[";
		$city_name = $this->input->post('city_name', true);
		$sql = "select postcode from oos_cityname where city_name='$city_name' order by postcode";
		//echo $sql;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$ret_data .= "{\"name\":\"" . $row->postcode . "\"},";
			}
		}
		
		$ret_data = substr($ret_data, 0, -1);
		$ret_data .= "]";
		echo $ret_data;
	}
	
	function get_postcode_1()
	{
		$ret_data = "[";
		$postcode = $this->input->post('postcode', true);
		$sql = "SELECT * FROM oos_cityname WHERE city_name LIKE '$postcode%' OR postcode LIKE '$postcode%' ORDER BY postcode LIMIT 0 , 15";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$ret_data .= "{\"city_name\":\"" . $row->city_name . "\", \"postcode\":\"" . $row->postcode . "\"},";
			}
		}
		else
		{
			$ret_data = "";
		}
		$ret_data = substr($ret_data, 0, -1);
		$ret_data .= "]";
		echo $ret_data;
	}
	
	function check_shopstate()
	{
		$post_code = $this->input->post('postcode');
		$sql = 'SELECT stt.id, stt.shop_name, cld.type FROM oos_setting stt
				LEFT JOIN oos_calendar cld ON stt.store_id = cld.store_id AND cld.month='.date('m').' AND cld.day='.date('d').'
				WHERE stt.postcode='.$post_code;
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			$row = $result->result();
			if ($row[0]->type)
			{
				echo $row[0]->type;
			}
		}
	}
	
	function get_password()
	{
		$message = "Your password is ";
		$forgot_pw_email = $this->input->post('email', true);
		$sql = "select * from oos_customer where email='$forgot_pw_email'";
		//echo $sql;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			//$values = array();				
			foreach ($query->result() as $user)
			{
				//echo $user->user_id;
			//$values[] = array($user->id, $user->user_id, $user->email, $user->postcode);
			}
		
		//$data['user'] = $values;
		}
		if ($user)
		{
			if ($user->approved == "N")
			{
				echo "non-approve";
				exit();
			}
			$message .= "$user->passwd.<br><br>Regards.";
			//echo $message;
			mb_internal_encoding("UTF-8");
			$email_html = "<html><body>";
			$email_html .= "<br /><br />";
			$email_html .= str_replace("\r\n", "<br>", $message);
			$email_html .= '</body></html>';
			
			$mail_body = $email_html;
			$email_address = $forgot_pw_email;
			$from = "nadeem@asexpress.de";
			$subject = "Password Recovery";
			$headers = "From: <" . $from . "> \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			
			mail($email_address, $subject, $mail_body, $headers);
			echo "ok";
		}
		else
		{
			echo "noexist";
		}
	}
	
	function fax_data_open($filename = '')
	{
		$shop = get_shop_data($this->session->userdata('SESS_STORE_ID'));
		//$dir_path = "../shops/temp/faxData/".$_SESSION['SESS_STORE_ID']."/";
		//$file = $this->input->$_REQUEST["filename"];
		if (file_exists($filename))
		{
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($filename));
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($filename));
			ob_clean();
			flush();
			readfile($filename);
			exit();
		}
	}
	
	function fax_data_send()
	{
		$shop = get_shop_data($this->session->userdata('SESS_STORE_ID'));
		$dir_path = FAXDATA_DIR . $this->session->userdata('SESS_STORE_ID') . "/";
		$file = $this->input->post('filename', true); //$dir_path.$_REQUEST["filename"];
		$file = $dir_path . $file;
		$username = "b.qadeer";
		$password = "Bhakkar$74";
		$number = $shop->fax;
		sendFAX($username, $password, $number, $temp);
		echo 1;
		exit();
	}
	
	function fax_data_delete()
	{
		$file = $this->input->post('filename');
		unlink($file);
		echo 1;
		exit();
	}
	
	function delete_css()
	{
		$file = $this->input->post('filename');
		$shop = get_shop_data($this->session->userdata('SESS_STORE_ID'));
		$file = CSS_PATH . $file;
		unlink($file);
		echo 1;
		exit();
	}
	function postcode_complete()
	{
		$postcode = $this->input->get('term');
		$query = "SELECT * FROM oos_cityname WHERE postcode LIKE '".$postcode."%' GROUP By postcode ORDER By postcode";
		$result = $this->db->query($query);
		$ret_data = "[";
		if ($result->num_rows() > 0)
		{
			foreach ($result->result() as $row)
			{
				//echo $row->postcode . ",\n";
				$ret_data .= '{"value":"' . $row->postcode . '"},';
			}
		}
		$ret_data = substr($ret_data, 0, -1);
		$ret_data .= "]";
		echo $ret_data;
	}
	
	function postcode_verify()
	{
		$postcode = $this->input->post('postcode', true);
		$query = "SELECT * FROM oos_cityname WHERE postcode='{$postcode}'";
		$result_post = $this->db->query($query);
		
		if ($result_post->num_rows() > 0)
		{
			$query = "SELECT count(*) AS cnt FROM oos_deliver_area WHERE postcode='{$postcode}'";
			$result = $this->db->query($query);
			if ($result->num_rows() > 0)
			{
				$row = $result->result();
				if ($row[0]->cnt > 0)
					$ret_data = "exist";
				else
					$ret_data = "ok";
			}
			else
				$ret_data = "ok";
		}
		else
		{
			$ret_data = "fail";
		}
		echo $ret_data;
	}
	
	function get_size_for_topping()
	{
		$this->load->helper('admin');
		
		$ret_data = "[";
		
		$category_id = $this->input->post('category_id', true);
		if (!$category_id)
			$category_id = 0;
		
		$result = get_size($category_id);
		if ($result->num_rows() > 0)
		{
			foreach ($result->result() as $data)
			{
				$ret_data .= "{\"name\":\"" . $data->name . "\",\"id\":\"" . $data->id . "\"},";
			}
		}
		
		$ret_data = substr($ret_data, 0, -1);
		if (!empty($ret_data))
			$ret_data .= "]";
			
		echo $ret_data;
	}
	
	function get_size_for_category()
	{
		$this->load->helper('admin');
		
		$ret_data = "[";
		
		$category_id = $this->input->post('category_id');
		$product_id = $this->input->post('product_id');
		
		if ($category_id == false)
			$category_id = 0;
		if ($product_id == false)
			$product_id = 0;
			
		$result = get_size($category_id);
		
		if ($result->num_rows() > 0)
		{
			foreach ($result->result() as $data)
			{
				$price_data = get_price($product_id, $data->id);
				if ($price_data->num_rows() > 0)
				{
					$row = $price_data->result();
					$price = $row[0]->price;
					$ret_data .= "{\"name\":\"" . $data->name . "\",\"id\":\"" . $data->id . "\",\"price\":\"" . $price . "\"},";
				}
				else
				{
					$ret_data .= "{\"name\":\"" . $data->name . "\",\"id\":\"" . $data->id . "\",\"price\":\"0\"},";
				}
			}
		}
		else
		{
			$price_data = get_price($product_id, -1);
			if ($price_data->num_rows() > 0)
			{
				$row = $price_data->result();
				$price = $row[0]->price;
				$ret_data .= "{\"name\":\"" . "" . "\",\"id\":\"" . $data->id . "\",\"price\":\"" . $price . "\"},";
			}
			else
			{
				$ret_data .= "{\"name\":\"" . "" . "\",\"id\":\"-1\",\"price\":\"0\"},";
			}
		}
		
		$ret_data = substr($ret_data, 0, -1);
		$ret_data .= "]";
		echo $ret_data;
	}
}
?>