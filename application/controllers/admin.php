<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function index()
	{
		$this->load->model('user');
		
		$user_id = $this->input->post('user_id', true);
		$user_pass = $this->input->post('user_pass', true);
		
		if (!empty($user_id) && !empty($user_pass))
		{
			$data = $this->user->confirm($user_id, $user_pass);
			if ($data->num_rows() > 0)
			{
				$row = $data->result();
				
				if ($user_id == 'admin')
					$this->session->set_userdata('SESS_PRIVILEGE', 'admin');
				else
					$this->session->set_userdata('SESS_PRIVILEGE', 'user');
				$this->session->set_userdata('SESS_USER_ID', $user_id);
				$this->session->set_userdata('SESS_STORE_ID', $row[0]->id);
			}
			else
			{
				$this->session->set_flashdata('LOGIN_STATUS', 'failed');
				redirect('admin');
			}
		}
		
		if ($this->session->userdata('SESS_USER_ID') && $this->session->userdata('SESS_STORE_ID'))
		{
			redirect('admin/main');
		}
		$this->load->view('admin/index');
	}
	
	public function logout()
	{
		$this->session->unset_userdata('SESS_USER_ID');
		$this->session->unset_userdata('SESS_STORE_ID');
		$this->session->unset_userdata('SESS_CATEGORY_ID');
		$this->session->sess_destroy();
		
		redirect('admin');
	}
	
	public function main()
	{
		$this->load->helper('admin');
		
		$data = array();
		signin_check();
		
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/welcome', $data);
		$this->load->view('admin/footer');
	}
	
	public function user()
	{
		$this->load->model('user');
		$this->load->helper('admin');
		
		admin_signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'delete')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->user->delete($id);
			$data['process_flag'] = 1;
		}
		
		// seraching
		$keys = array();
		$keys['key_name'] = $this->input->post('key_name', true);
		$keys['key_email'] = $this->input->post('key_email', true);
		$keys['key_telephone'] = $this->input->post('key_telephone', true);
		$keys['key_status'] = $this->input->post('key_status', true);
		$keys['key_date'] = $this->input->post('key_date', true);
		
		$data['keys'] = $keys;
		
		// Paging
		$page = $this->input->post('page', true);
		if (!is_numeric($page) || $page < 1)
			$page = 1;
		$data['page'] = $page;
		
		$line_num = $this->config->item('line_num');
		$data['line_num'] = $line_num;
		
		$result = $this->user->get($keys);
		
		$total_record = $result->num_rows();
		$data['total_record'] = $total_record;
		
		$total_pages = ceil($total_record / $line_num);
		if ($total_pages <= 0)
			$total_pages = 1;
		$data['total_pages'] = $total_pages;
		
		//Get user data.
		$offset = ($page - 1) * $line_num;
		$result = $this->user->get($keys, $line_num, $offset);
		$data['result'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/user', $data);
		$this->load->view('admin/footer');
	}
	
	public function user_new()
	{
		$this->load->model('user');
		$this->load->helper('admin');
		$this->load->helper('date');
		
		admin_signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'new')
		{
			$user_info = array();
			$user_info['user_id'] = $this->input->post('user_id', true);
			$user_info['user_passwd'] = md5($this->input->post('pwd', true));
			$user_info['user_email'] = $this->input->post('email', true);
			$user_info['telephone'] = $this->input->post('telephone', true);
			
			$result = $this->user->add($user_info);
			if ($result)
				$data['process_flag'] = 1;
			else
				$data['process_flag'] = 2;
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/user_add', $data);
		$this->load->view('admin/footer');
	}
	
	public function user_edit($id = 0)
	{
		$this->load->model('user');
		$this->load->helper('admin');
		
		admin_signin_check();
		
		if (!is_numeric($id) || $id == 0)
			redirect('admin/user');
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'save')
		{
			$update_data = array();
			//$update_data['user_email'] = $this->input->post('email', true);
			//$update_data['telephone'] = $this->input->post('telephone', true);
			$update_data['is_approve'] = $this->input->post('approved', true);
			
			$result = $this->user->update($update_data, $id);
			if ($result)
				$data['process_flag'] = 1;
			else
				$data['process_flag'] = 2;
		}
		$contents = $this->user->get_by_id($id);
		$data['contents'] = $contents;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/user_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function customer()
	{
		$this->load->model('customer');
		$this->load->helper('admin');
		
		admin_signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'delete')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->customer->delete($id);
			$data['process_flag'] = 1;
		}
		
		// seraching
		$keys = array();
		$keys['key_name'] = $this->input->post('key_name', true);
		$keys['key_address'] = $this->input->post('key_address', true);
		$keys['key_email'] = $this->input->post('key_email', true);
		$keys['key_telephone'] = $this->input->post('key_telephone', true);
		$keys['key_status'] = $this->input->post('key_status', true);
		$keys['key_date'] = $this->input->post('key_date', true);
		
		$data['keys'] = $keys;
		
		// Paging
		$page = $this->input->post('page', true);
		if (!is_numeric($page) || $page < 1)
			$page = 1;
		$data['page'] = $page;
		
		$line_num = $this->config->item('line_num');
		$data['line_num'] = $line_num;
		
		$result = $this->customer->get($keys);
		
		$total_record = $result->num_rows();
		$data['total_record'] = $total_record;
		
		$total_pages = ceil($total_record / $line_num);
		if ($total_pages <= 0)
			$total_pages = 1;
		$data['total_pages'] = $total_pages;
		
		//Get user data.
		$offset = ($page - 1) * $line_num;
		$result = $this->customer->get($keys, $line_num, $offset);
		$data['result'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/customer', $data);
		$this->load->view('admin/footer');
	}
	
	public function customer_new()
	{
		$this->load->model('customer');
		$this->load->helper('admin');
		$this->load->helper('date');
		
		admin_signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/customer_add', $data);
		$this->load->view('admin/footer');
	}
	
	public function customer_edit($id = 0)
	{
		$this->load->model('customer');
		$this->load->helper('admin');
		
		admin_signin_check();
		
		if (!is_numeric($id) || $id == 0)
			redirect('admin/customer');
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'save')
		{
			$update_data = array();
			$update_data['approved'] = $this->input->post('approved', true);
			
			$result = $this->customer->update($update_data, $id);
			if ($result)
				$data['process_flag'] = 1;
			else
				$data['process_flag'] = 2;
		}
		$contents = $this->customer->get_by_id($id);
		$data['contents'] = $contents;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/customer_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function admin_order()
	{
		$this->load->model('order');
		$this->load->helper('admin');
		
		admin_signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$filters = array();
		$filters['key_date'] = ($this->input->post('key_date', true) != false) ? $this->input->post('key_date', true) : 'DESC';
		$filters['s_date'] = $this->input->post('s_date', true);
		$filters['e_date'] = $this->input->post('e_date', true);
		$filters['shop'] = $this->input->post('shop', true);
		$filters['post_code'] = $this->input->post('post_code', true);
		$filters['key_orderid'] = $this->input->post('key_orderid', true);
		$filters['key_customername'] = $this->input->post('key_customername', true);
		$filters['key_telephone'] = $this->input->post('key_telephone', true);
		$filters['key_status'] = $this->input->post('key_status', true);
		
		$data['keys'] = $filters;
		
		$page = $this->input->post('page', true);
		if (!is_numeric($page) || $page < 1)
			$page = 1;
		$data['page'] = $page;
		
		$line_num = $this->config->item('line_num');
		$data['line_num'] = $line_num;
		
		$result = $this->order->get($filters);
		
		$total_record = $result->num_rows();
		$data['total_record'] = $total_record;
		
		$total_pages = ceil($total_record / $line_num);
		if ($total_pages <= 0)
			$total_pages = 1;
		$data['total_pages'] = $total_pages;
		
		//Get user data.
		$offset = ($page - 1) * $line_num;
		$result = $this->order->get($filters, $line_num, $offset);
		$data['result'] = $result;
		
		$total_amount = $this->order->total_amount($filters);
		$data['total_amount'] = $total_amount;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/admin_order', $data);
		$this->load->view('admin/footer');
	}
	
	public function admin_faxdata()
	{
		$this->load->model('faxdata');
		$this->load->helper('admin');
		
		admin_signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$keys['shop'] = $this->input->post('shop', true);
		$data['keys'] = $keys;
		
		$dir_path = FAXDATA_DIR;
		$files = array();
		if (!$keys['shop'])
			$dir_path .= $keys['shop'] . '/';
		$data['dir_path'] = $dir_path;
		
		$this->faxdata->get($dir_path, $files);
		$data['files'] = $files;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/admin_faxdata', $data);
		$this->load->view('admin/footer');
	}
	
	public function admin_setting()
	{
		$this->load->model('setting');
		$this->load->helper('admin');
		$this->load->library('upload');
		
		admin_signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$default_css = DEFAULT_CSS_PATH;
		$dir_path = CSS_PATH;
		
		$mode = $this->input->post('mode', true);
		if ($mode == "add")
		{
			$this->upload->set_upload_path($dir_path);
			$this->upload->set_allowed_types('css');
			if ($this->upload->do_upload('css_uploaded'))
				$data['process_flag'] = 1;
			else
				$data['process_flag'] = 2;
		}
		
		$files = array();
		$files[0]['name'] = 'default.css';
		$files[0]['path'] = $default_css;
		$files[0]['time'] = (file_exists($default_css)) ? filemtime($default_css) : 0;
		
		if (is_dir($dir_path) && $dh = opendir($dir_path))
		{
			while (($file = readdir($dh)) !== false)
			{
				$file_type = filetype($dir_path . $file);
				if ($file_type == 'file')
				{
					if (strtolower(substr($file, -3)) != "css")
						continue;
					$temp['name'] = $file;
					$temp['path'] = $dir_path . $file;
					$temp['time'] = filemtime($dir_path . $file);
					array_push($files, $temp);
				}
			}
			closedir($dh);
		}
		$data['files'] = $files;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/admin_setting', $data);
		$this->load->view('admin/footer');
	}
	
	public function account()
	{
		$this->load->model('user');
		$this->load->helper('admin');
		
		admin_signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'edit')
		{
			$update_data = array();
			$user_pid = $this->input->post('upid', true);
			$update_data['user_email'] = $this->input->post('email', true);
			$new_pwd = $this->input->post('newpwd', true);
			
			if ($new_pwd)
				$update_data['user_passwd'] = md5($new_pwd);
			
			$result = $this->user->update($update_data, $user_pid);
			if ($result)
				$data['process_flag'] = 1;
			else
				$data['process_flag'] = 2;
		}
		
		$result = $this->user->get_by_userid($this->session->userdata('SESS_USER_ID'));
		$data['email'] = $result->user_email;
		$data['user_id'] = $result->user_id;
		$data['upid'] = $result->id;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/account', $data);
		$this->load->view('admin/footer');
	}
	
	public function account_edit()
	{
		$this->load->model('user');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$user_id = $this->session->userdata('SESS_USER_ID');
		$mode = $this->input->post('mode', true);
		if ($mode == 'update')
		{
			$update_data = array();
			$user_pid = $this->input->post('upid', true);
			$update_data['first_name'] = $this->input->post('first_name', true);
			$update_data['last_name'] = $this->input->post('last_name', true);
			$update_data['user_email'] = $this->input->post('email', true);
			$update_data['telephone'] = $this->input->post('telephone', true);
			$update_data['city'] = $this->input->post('city', true);
			$update_data['street'] = $this->input->post('street', true);
			$update_data['house_no'] = $this->input->post('house_no', true);
			$update_data['postcode'] = $this->input->post('postcode', true);
			$update_data['company'] = $this->input->post('company', true);
			
			$result = $this->user->update($update_data, $user_pid);
			if ($result)
				$data['process_flag'] = 1;
			else
				$data['process_flag'] = 2;
		}
		
		$result = $this->user->get_by_userid($user_id);
		$data['contents'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/account_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function category()
	{
		$this->load->model('category');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'delete')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->category->delete($id);
			$data['process_flag'] = 1;
		}
		if ($mode == 'copy')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->category->copy($id);
			$data['process_flag'] = 1;
		}
		
		$result = $this->category->get($this->session->userdata('SESS_STORE_ID'));
		$data['contents'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/category', $data);
		$this->load->view('admin/footer');
	}
	
	public function category_new()
	{
		$this->load->model('category');
		$this->load->helper('admin');
		$this->load->helper('date');
		$this->load->library('upload');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'add')
		{
			$update_data = array();
			$update_data['name'] = $this->input->post('name', true);
			$update_data['description'] = $this->input->post('description', true);
			$update_data['is_active'] = $this->input->post('is_active', true);
			$update_data['created_on_date'] = mdate('Y-m-d');
			$update_data['seo_name'] = $this->input->post('seo_name', true);
			$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
			
			$this->upload->set_upload_path(ADMIN_UPDATE_PATH);
			$this->upload->set_allowed_types('jpg|png|bmp|gif');
			if ($this->upload->do_upload('uploaded'))
			{
				$update_data['image_url'] = $this->upload->file_name;
			}
			$result = $this->category->add($update_data);
			$curr_category_id = get_category_last_id();
			
			if ($result)
			{
				$sizenames = $this->input->post('size_name', true);
				$size_desc = $this->input->post('size_desc', true);
				$is_offer = $this->input->post('is_offer', true);
				
				if ($sizenames)
				{
					for ($i = 0; $i < count($sizenames); $i++)
					{
						if (trim($sizenames[$i]) != "")
						{
							$size_data['id_category'] = $curr_category_id;
							$size_data['name'] = $sizenames[$i];
							$size_data['desc'] = $size_desc[$i];
							$size_data['is_offer'] = $is_offer[$i];
							$result = $this->category->add_size($size_data);
						
		//$query = "INSERT INTO `oos_size` (`id`, `id_category`, `name`, `desc`,`is_offer`) VALUES (NULL, '" . get_category_last_id() . "', '" . $_REQUEST[size_name][$i] . "', '" . $_REQUEST[size_desc][$i] . "', '" . $_REQUEST[is_offer][$i] . "');";
						//$result = mysql_query($query) or die(mysql_error());
						}
					}
				}
				else
				{
					$size_data['id_category'] = $curr_category_id;
					$size_data['name'] = 'Default size';
					$size_data['desc'] = '';
					$result = $this->category->add_size($size_data);
				
		//$query = "INSERT INTO `oos_size` (`id`, `id_category`, `name`, `desc`) VALUES (NULL, '" . get_category_last_id() . "', 'Default size', '" . $_REQUEST[size_desc][$i] . "');";
				//$result = mysql_query($query) or die(mysql_error());
				}
			}
			$data['process_flag'] = 1;
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/category_add', $data);
		$this->load->view('admin/footer');
	}
	
	public function category_edit($id)
	{
		$this->load->model('category');
		$this->load->helper('admin');
		$this->load->library('upload');
		
		signin_check();
		
		if (!is_numeric($id) || $id == 0)
			redirect('admin/category');
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'update')
		{
			$update_data = array();
			$update_data['name'] = $this->input->post('name', true);
			$update_data['description'] = $this->input->post('description', true);
			$update_data['is_active'] = $this->input->post('is_active', true);
			$update_data['seo_name'] = $this->input->post('seo_name', true);
			
			$this->upload->set_upload_path(ADMIN_UPDATE_PATH);
			$this->upload->set_allowed_types('jpg|png|bmp|gif');
			if ($this->upload->do_upload('uploaded'))
			{
				$update_data['image_url'] = $this->upload->file_name;
			}
			$result = $this->category->update($update_data, $id);
			
			$ids = $this->input->post('ids');
			if ($ids)
				$ids_arr = explode(",", $ids);
			
			$sizenames = $this->input->post('size_name', true);
			$size_id = $this->input->post('size_id', true);
			$size_desc = $this->input->post('size_desc', true);
			$is_offer = $this->input->post('is_offer', true);
			if ($sizenames)
			{
				for ($i = 0; $i < count($sizenames); $i++)
				{
					$update_size['name'] = $sizenames[$i];
					$update_size['desc'] = $size_desc[$i];
					$update_size['is_offer'] = $is_offer[$i];
					
					if (isset($size_id[$i]) && isset($ids_arr) && in_array($size_id[$i], $ids_arr, true))
					{
						if (trim($sizenames[$i]) != "")
						{
							$result = $this->category->update_size($size_id[$i], $update_size);
						
		//$query = "update oos_size set `name`='" . $_REQUEST[size_name][$i] . "', `desc`='" . $_REQUEST[size_desc][$i] . "', `is_offer`='" . $_REQUEST[is_offer][$i] . "' where id=" . $_REQUEST[size_id][$i];
						//$result = mysql_query($query);
						}
					}
					else
					{
						$update_size['id_category'] = $id;
						$result = $this->category->add_size($update_size);
					
		//$query = "INSERT INTO `oos_size` (`id`, `id_category`, `name`, `desc`,`is_offer`) VALUES (NULL, '" . $_REQUEST[category_id] . "', '" . $_REQUEST[size_name][$i] . "', '" . $_REQUEST[size_desc][$i] . "', '" . $_REQUEST[is_offer][$i] . "');";
					//$result = mysql_query($query);
					}
				}
				if ($size_id)
				{
					$diff = array_diff($ids_arr, $size_id);
					foreach ($diff as $key => $val)
					{
						$result = $this->category->delete_size($val);
					}
				
		//$query = "delete from oos_size where id=" . $diff[$i];
				//$result = mysql_query($query);
				}
			}
			else
			{
				//$update_size['id_category'] = $id;
				//$update_size['name'] = 'Default size';
				//$update_size['desc'] = '';
				//$result = $this->category->add_size($update_size);
				//$query = "INSERT INTO `oos_size` (`id`, `id_category`, `name`, `desc`) VALUES (NULL, '" . $_REQUEST[category_id] . "', 'Default size', '" . $_REQUEST[size_desc][$i] . "');";
				//$result = mysql_query($query) or die(mysql_error());
				

				for ($i = 0; $i < count($ids_arr) - 1; $i++)
				{
					$result = $this->category->delete_size($ids_arr[$i]);
				
		//$query = "delete from oos_size where id=" . $ids_arr[$i];
				//$result = mysql_query($query);
				}
			}
			$data['process_flag'] = 1;
		}
		$contents = $this->category->get_by_id($id);
		if (!$contents)
			redirect('admin/category');
		$data['contents'] = $contents;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/category_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function topping()
	{
		$this->load->model('topping');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'delete')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->topping->delete($id);
			$data['process_flag'] = 1;
		}
		
		if ($mode == 'copy')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->topping->copy($id);
			$data['process_flag'] = 1;
		}
		
		$result = $this->topping->get($this->session->userdata('SESS_STORE_ID'));
		$data['contents'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/topping', $data);
		$this->load->view('admin/footer');
	}
	
	public function topping_new()
	{
		$this->load->model('topping');
		$this->load->helper('admin');
		$this->load->helper('date');
		$this->load->library('upload');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'add')
		{
			$topping_name = $this->input->post('topping_name');
			$topping_price = $this->input->post('topping_price');
			$size_name = $this->input->post('size_name');
			$category = $this->input->post('category');
			foreach ($topping_name as $key => $top_name)
			{
				$update_data['price_add'] = $topping_price[$key];
				$update_data['id_category'] = $category;
				$update_data['id_size'] = $size_name[$key];
				$update_data['topping_name'] = $top_name;
				$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
				
				$result = $this->topping->add($update_data);
			}
			
			$data['process_flag'] = 1;
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/topping_add', $data);
		$this->load->view('admin/footer');
	}
	
	public function topping_edit($id)
	{
		$this->load->model('topping');
		$this->load->helper('admin');
		$this->load->library('upload');
		
		signin_check();
		
		if (!is_numeric($id) || $id == 0)
			redirect('admin/topping');
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'update')
		{
			$update_data['price_add'] = $this->input->post('price');
			$update_data['topping_name'] = $this->input->post('topping_name');
			
			$result = $this->topping->update($update_data, $id);
			
			$data['process_flag'] = 1;
		}
		$contents = $this->topping->get_by_id($id);
		if (!$contents)
			redirect('admin/topping');
		$data['contents'] = $contents;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/topping_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function product()
	{
		$this->load->model('product');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'delete')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->product->delete($id);
			$data['process_flag'] = 1;
		}
		
		if ($mode == 'copy')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->category->copy($id);
			$data['process_flag'] = 1;
		}
		
		// seraching
		$keys = array();
		$keys['key_product_code'] = $this->input->post('key_product_code', true);
		$keys['key_product_name'] = $this->input->post('key_product_name', true);
		$keys['key_price'] = $this->input->post('key_price', true);
		$keys['key_offered'] = $this->input->post('key_offered', true);
		$keys['key_status'] = $this->input->post('key_status', true);
		$keys['key_category'] = $this->input->post('key_category', true);
		
		$data['keys'] = $keys;
		
		// Paging
		$page = $this->input->post('page', true);
		if (!is_numeric($page) || $page < 1)
			$page = 1;
		$data['page'] = $page;
		
		$line_num = $this->config->item('line_num');
		$data['line_num'] = $line_num;
		
		$result = $this->product->get($keys);
		
		$total_record = $result->num_rows();
		$data['total_record'] = $total_record;
		
		$total_pages = ceil($total_record / $line_num);
		if ($total_pages <= 0)
			$total_pages = 1;
		$data['total_pages'] = $total_pages;
		
		//Get user data.
		$offset = ($page - 1) * $line_num;
		$result = $this->product->get($keys, $line_num, $offset);
		$data['result'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/product', $data);
		$this->load->view('admin/footer');
	}
	
	public function product_new()
	{
		$this->load->model('product');
		$this->load->model('price');
		$this->load->helper('admin');
		$this->load->helper('date');
		$this->load->library('upload');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'add')
		{
			$update_data = array();
			$update_data['id_category'] = $this->input->post('id_category');
			$update_data['name'] = $this->input->post('name');
			$update_data['description'] = $this->input->post('description');
			$update_data['is_active'] = $this->input->post('is_active');
			$update_data['is_offer'] = $this->input->post('is_offer');
			$update_data['created_on_date'] = mdate('Y-m-d');
			$update_data['seo_name'] = $this->input->post('seo_name');
			$update_data['code'] = $this->input->post('code');
			$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
			
			$result = $this->product->add($update_data);
			unset($update_data);
			$update_data = array();
			
			if ($result)
			{
				$size_id = $this->input->post('size_id');
				$price = $this->input->post('price');
				if ($size_id != false)
				{
					for ($i = 0; $i < count($size_id); $i++)
					{
						$update_data['price'] = $price[$i];
						$update_data['id_product'] = get_product_last_id();
						$update_data['id_size'] = $size_id[$i];
						$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
						
						$result = $this->price->add($update_data);
					}
				}
			}
			$data['process_flag'] = 1;
			$data['category'] = $this->input->post('id_category');
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/product_add', $data);
		$this->load->view('admin/footer');
	}
	
	public function product_edit($id)
	{
		$this->load->model('product');
		$this->load->model('price');
		$this->load->helper('admin');
		$this->load->library('upload');
		
		signin_check();
		
		if (!is_numeric($id) || $id == 0)
			redirect('admin/product');
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'update')
		{
			$update_data = array();
			$update_data['id_category'] = $this->input->post('id_category');
			$update_data['name'] = $this->input->post('name');
			$update_data['description'] = $this->input->post('description');
			$update_data['is_active'] = $this->input->post('is_active');
			$update_data['is_offer'] = $this->input->post('is_offer');
			$update_data['seo_name'] = $this->input->post('seo_name');
			$update_data['code'] = $this->input->post('code');
			
			$result = $this->product->update($update_data, $id);
			unset($update_data);
			$update_data = array();
			
			if ($result)
			{
				$result = $this->price->delete($id);
				
				$size_id = $this->input->post('size_id');
				$price = $this->input->post('price');
				if ($size_id != false)
				{
					for ($i = 0; $i < count($size_id); $i++)
					{
						$update_data['price'] = $price[$i];
						$update_data['id_product'] = $id;
						$update_data['id_size'] = $size_id[$i];
						$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
						
						$result = $this->price->add($update_data);
					}
				}
			}
			$data['process_flag'] = 1;
		}
		$contents = $this->product->get_by_id($id);
		if (!$contents)
			redirect('admin/product');
		$data['contents'] = $contents;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/product_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function special_offer()
	{
		$this->load->model('special_offer');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'delete')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->special_offer->delete($id);
			$data['process_flag'] = 1;
		}
		if ($mode == 'copy')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->special_offer->copy($id);
			$data['process_flag'] = 1;
		}
		
		$result = $this->special_offer->get($this->session->userdata('SESS_STORE_ID'));
		$data['contents'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/special_offer', $data);
		$this->load->view('admin/footer');
	}
	
	public function special_offer_new()
	{
		$this->load->model('special_offer');
		$this->load->helper('admin');
		$this->load->helper('date');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'add')
		{
			$update_data = array();
			$update_data['offer_code'] = $this->input->post('offer_code', true);
			$update_data['offer_name'] = $this->input->post('offer_name', true);
			$update_data['offer_price'] = $this->input->post('offer_price', true);
			$update_data['extra_product'] = $this->input->post('extra_product', true);
			$update_data['add_date'] = mdate('Y-m-d');
			$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
			$result = $this->special_offer->add($update_data);
			
			if ($result)
			{
				$categorys = $this->input->post('category_id', true);
				if ($categorys && is_array($categorys))
				{
					for ($i = 0; $i < count($categorys); $i++)
					{
						$vals = array();
						$vals['id_offer'] = get_offer_last_id();
						$vals['id_category'] = $categorys[$i];
						$result = $this->special_offer->add_contents($vals);
					}
				}
			}
			$data['process_flag'] = 1;
		}
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/special_offer_add', $data);
		$this->load->view('admin/footer');
	}
	
	public function special_offer_edit($offer_id = '')
	{
		$this->load->model('special_offer');
		$this->load->helper('admin');
		$this->load->helper('date');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'update')
		{
			$categorys = $this->input->post('category_id', true);
			if ($categorys && is_array($categorys))
			{
				$result = $this->special_offer->delete_contents($offer_id);
				for ($i = 0; $i < count($categorys); $i++)
				{
					$vals = array();
					$vals['id_offer'] = $offer_id;
					$vals['id_category'] = $categorys[$i];
					$result = $this->special_offer->add_contents($vals);
				}
			}
			$update_data = array();
			$update_data['offer_code'] = $this->input->post('offer_code', true);
			$update_data['offer_name'] = $this->input->post('offer_name', true);
			$update_data['offer_price'] = $this->input->post('offer_price', true);
			$update_data['extra_product'] = $this->input->post('extra_product', true);
			$result = $this->special_offer->update($update_data, $offer_id);
			
			$process_flag = 1;
		}
		$result = $this->special_offer->get_by_id($offer_id);
		$data['contents'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/special_offer_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function order()
	{
		$this->load->model('order');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'delete')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->order->delete($id);
			$data['process_flag'] = 1;
		}
		
		// seraching
		$keys = array();
		$keys['key_date'] = $this->input->post('key_date', true);
		if (!$keys['key_date'])
			$keys['key_date'] = 'DESC';
		$keys['post_code'] = $this->input->post('post_code', true);
		$keys['key_orderid'] = $this->input->post('key_orderid', true);
		$keys['key_customername'] = $this->input->post('key_customername', true);
		$keys['key_telephone'] = $this->input->post('key_telephone', true);
		$keys['key_status'] = $this->input->post('key_status', true);
		
		$data['keys'] = $keys;
		
		// Paging
		$page = $this->input->post('page', true);
		if (!is_numeric($page) || $page < 1)
			$page = 1;
		$data['page'] = $page;
		
		$line_num = $this->config->item('line_num');
		$data['line_num'] = $line_num;
		
		$result = $this->order->get($keys);
		
		$total_record = $result->num_rows();
		$data['total_record'] = $total_record;
		
		$total_pages = ceil($total_record / $line_num);
		if ($total_pages <= 0)
			$total_pages = 1;
		$data['total_pages'] = $total_pages;
		
		//Get user data.
		$offset = ($page - 1) * $line_num;
		$result = $this->order->get($keys, $line_num, $offset);
		$data['result'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/order', $data);
		$this->load->view('admin/footer');
	}
	
	public function order_edit($id = 0)
	{
		$this->load->model('order');
		$this->load->helper('admin');
		
		admin_signin_check();
		
		if (!is_numeric($id) || $id == 0)
			redirect('admin/order');
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'save')
		{
			$update_data = array();
			$update_data['order_status_id'] = $this->input->post('order_status_id', true);
			
			$result = $this->order->update($update_data, $id);
			if ($result)
				$data['process_flag'] = 1;
			else
				$data['process_flag'] = 2;
		}
		$contents = $this->order->get_by_id($id);
		$data['contents'] = $contents;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/order_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function faxdata()
	{
		$this->load->model('faxdata');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$dir_path = FAXDATA_DIR . $this->session->userdata('SESS_STORE_ID') . "/";
		$files = array();
		if (is_dir($dir_path) && $dh = opendir($dir_path))
		{
			while (($file = readdir($dh)) !== false)
			{
				$file_type = filetype($dir_path . $file);
				$file_date = filemtime($dir_path . $file);
				$cur_date = mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"));
				
				if ($file_type == "file")
				{
					if ($file_date < $cur_date)
					{
						unlink($dir_path . $file);
						continue;
					}
					$temp['name'] = $file;
					$temp['path'] = $dir_path . $file;
					$temp['time'] = filemtime($dir_path . $file);
					array_push($files, $temp);
				}
			}
			closedir($dh);
		}
		rsort($files);
		
		$data['files'] = $files;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/faxdata', $data);
		$this->load->view('admin/footer');
	}
	
	public function delivery_area()
	{
		$this->load->model('delivery_area');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'delete')
		{
			$ids = $this->input->post('chks', true);
			if ($ids)
				foreach ($ids as $id)
					$this->delivery_area->delete($id);
			$data['process_flag'] = 1;
		}
		$result = $this->delivery_area->get($this->session->userdata('SESS_STORE_ID'));
		$data['result'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/delivery_area', $data);
		$this->load->view('admin/footer');
	}
	
	public function delivery_area_new()
	{
		$this->load->model('delivery_area');
		$this->load->helper('admin');
		$this->load->helper('date');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'add')
		{
			$update_data = array();
			$update_data['postcode'] = $this->input->post('postcode', true);
			$update_data['price'] = $this->input->post('price', true);
			$update_data['delivery_charge'] = $this->input->post('delivery_charge', true);
			$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
			
			$result = $this->delivery_area->add($update_data);
			$data['process_flag'] = 1;
		}
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/delivery_area_add', $data);
		$this->load->view('admin/footer');
	}
	
	public function delivery_area_edit($delivery_area_id = '')
	{
		$this->load->model('delivery_area');
		$this->load->helper('admin');
		$this->load->helper('date');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'update')
		{
			$update_data = array();
			$update_data['postcode'] = $this->input->post('postcode', true);
			$update_data['price'] = $this->input->post('price', true);
			$update_data['delivery_charge'] = $this->input->post('delivery_charge', true);
			
			$result = $this->delivery_area->update($update_data, $delivery_area_id);
			$data['process_flag'] = 1;
			;
		}
		$result = $this->delivery_area->get_by_id($delivery_area_id);
		$data['contents'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/delivery_area_edit', $data);
		$this->load->view('admin/footer');
	}
	
	public function calendar()
	{
		$this->load->model('calendar');
		$this->load->helper('admin');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$mode = $this->input->post('mode', true);
		switch ($mode)
		{
			case 'add':
				$update_data = array();
				$added_date = $this->input->post('add_date', true);
				if ($added_date)
				{
					$update_data['month'] = date('m', strtotime($added_date));
					$update_data['day'] = date('d', strtotime($added_date));
				}
				$update_data['type'] = $this->input->post('sel_type', true);
				$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
				
				$result = $this->calendar->get($update_data['store_id'], $update_data['month'], $update_data['day']);
				if ($result->num_rows() > 0)
				{
					$row = $result->result();
					$this->calendar->update($row[0]->id, $update_data);
					$data['process_flag'] = 1;
				}
				else
				{
					$this->calendar->add($update_data);
					$data['process_flag'] = 1;
				}
				unset($result);
				break;
			case 'delete':
				$ids = $this->input->post('chks', true);
				if ($ids)
				{
					foreach ($ids as $id)
					{
						if ($this->calendar->delete($id))
							$data['process_flag'] = 1;
					}
				}
				
				break;
		}
		$month = $this->input->post('selyear', true);
		if ($month < 1 && $month > 12)
			$month = 0;
		$result = $this->calendar->get($this->session->userdata('SESS_STORE_ID'));
		$data['result'] = $result;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/calendar', $data);
		$this->load->view('admin/footer');
	}
	
	public function calendar_edit()
	{
		$this->load->model('calendar');
		$this->load->helper('admin');
		$this->load->helper('date');
		
		$update_data = array();
		$pid = $this->input->post('pid', true);
		$update_date = $this->input->post('date', true);
		if ($update_date)
		{
			$update_data['month'] = date('m', strtotime($update_date));
			$update_data['day'] = date('d', strtotime($update_date));
		}
		$update_data['type'] = $this->input->post('type', true);
		$this->calendar->update($pid, $update_data);
	}
	
	public function setting()
	{
		$this->load->model('setting');
		$this->load->helper('admin');
		$this->load->library('upload');
		
		signin_check();
		
		$data = array();
		$process_flag = get_process_flag($this->session->userdata('SESS_STORE_ID'));
		$data['process_flag'] = $process_flag;
		
		$logo_target = ADMIN_UPDATE_PATH . 'shop_logo/';
		$css_target = CSS_PATH;
		$default_css = DEFAULT_CSS_PATH;
		
		$mode = $this->input->post('mode', true);
		if ($mode == 'save')
		{
			$update_data = array();
			$this->upload->set_upload_path($logo_target);
			$this->upload->set_allowed_types('jpg|png|bmp|gif');
			if ($this->upload->do_upload('logo_uploaded'))
			{
				$update_data['image_url'] = $this->upload->file_name;
			}
			
			if ($this->setting->get_count($this->session->userdata('SESS_STORE_ID')) == 0)
			{
				$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
				$update_data['shop_name'] = $this->input->post('shop_name', true);
				$update_data['imprint'] = $this->input->post('imprint', true);
				$update_data['region_name'] = $this->input->post('region_name', true);
				$update_data['address'] = $this->input->post('address', true);
				$update_data['city'] = $this->input->post('city', true);
				$update_data['postcode'] = $this->input->post('postcode', true);
				$update_data['email'] = $this->input->post('email', true);
				$update_data['telephone'] = $this->input->post('telephone', true);
				$update_data['fax'] = $this->input->post('fax', true);
				$update_data['main_key'] = $this->input->post('main_key', true);
				$update_data['min_price'] = $this->input->post('min_price', true);
				$update_data['style_url'] = $this->input->post('css_file', true);
				
				$result = $this->setting->add($update_data);
				
				if ($result)
				{
					$start1 = $this->input->post('start1', true);
					$to1 = $this->input->post('to1', true);
					$start2 = $this->input->post('start2', true);
					$to2 = $this->input->post('to2', true);
					for ($i = 0; $i < count($start1); $i++)
					{
						$vals = array();
						$vals['day_number'] = $i;
						$vals['workinghour_from'] = $start1[$i];
						$vals['workinghour_to'] = $to1[$i];
						$vals['workinghour_from2'] = $start2[$i];
						$vals['workinghour_to2'] = $to2[$i];
						$vals['store_id'] = $this->session->userdata('SESS_STORE_ID');
						
						$result = $this->setting->add_workinghours($vals);
					}
				}
			}
			else
			{
				//$update_data['store_id'] = $this->session->userdata('SESS_STORE_ID');
				$update_data['shop_name'] = $this->input->post('shop_name', true);
				$update_data['imprint'] = $this->input->post('imprint', true);
				$update_data['region_name'] = $this->input->post('region_name', true);
				$update_data['address'] = $this->input->post('address', true);
				$update_data['city'] = $this->input->post('city', true);
				$update_data['postcode'] = $this->input->post('postcode', true);
				$update_data['email'] = $this->input->post('email', true);
				$update_data['telephone'] = $this->input->post('telephone', true);
				$update_data['fax'] = $this->input->post('fax', true);
				$update_data['main_key'] = $this->input->post('main_key', true);
				$update_data['min_price'] = $this->input->post('min_price', true);
				$update_data['style_url'] = $this->input->post('css_file', true);
				
				$result = $this->setting->update($update_data, $this->session->userdata('SESS_STORE_ID'));
				if ($result)
				{
					$start1 = $this->input->post('start1', true);
					$to1 = $this->input->post('to1', true);
					$start2 = $this->input->post('start2', true);
					$to2 = $this->input->post('to2', true);
					for ($i = 0; $i < count($start1); $i++)
					{
						$vals = array();
						$vals['day_number'] = $i;
						$vals['workinghour_from'] = $start1[$i];
						$vals['workinghour_to'] = $to1[$i];
						$vals['workinghour_from2'] = $start2[$i];
						$vals['workinghour_to2'] = $to2[$i];
						$vals['store_id'] = $this->session->userdata('SESS_STORE_ID');
						
						$result = $this->setting->update_workinghours($vals, $this->session->userdata('SESS_STORE_ID'), $i);
					}
				}
			}
			$data['process_flag'] = 1;
		}
		
		//	$query = "select * from oos_setting where store_id=$_SESSION[SESS_STORE_ID]";
		$result = $this->setting->get_by_store_id($this->session->userdata('SESS_STORE_ID'));
		//$result = mysql_query($query);
		//if($data = mysql_fetch_object($result)){
		if ($result)
		{
			$data['shop_name'] = $result->shop_name;
			$data['address'] = $result->address;
			$data['city'] = $result->city;
			$data['postcode'] = $result->postcode;
			$data['email'] = $result->email;
			$data['telephone'] = $result->telephone;
			$data['fax'] = $result->fax;
			$data['image_url'] = $result->image_url;
			$data['main_key'] = $result->main_key;
			$data['min_price'] = $result->min_price;
			$data['region_name'] = $result->region_name;
			$data['imprint'] = $result->imprint;
			$data['style_url'] = $result->style_url;
		}
		else
		{
			$data['shop_name'] = '';
			$data['address'] = '';
			$data['city'] = '';
			$data['postcode'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['fax'] = '';
			$data['image_url'] = '';
			$data['main_key'] = '';
			$data['min_price'] = '';
			$data['region_name'] = '';
			$data['imprint'] = '';
			$data['style_url'] = '';
		}
		
		$css_files = array();
		$css_files[0]['name'] = "default.css";
		$css_files[0]['path'] = $default_css;
		$css_files[0]['time'] = filemtime($default_css);
		if (is_dir($css_target) && $dh = opendir($css_target))
		{
			while (($file = readdir($dh)) !== false)
			{
				$file_type = filetype($css_target . $file);
				if ($file_type == "file")
				{
					if (strtolower(substr($file, -3)) != "css")
						continue;
					$temp['name'] = $file;
					$temp['path'] = $css_target . $file;
					$temp['time'] = filemtime($css_target . $file);
					array_push($css_files, $temp);
				}
			}
			closedir($dh);
		}
		$data['css_files'] = $css_files;
		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar');
		$this->load->view('admin/setting', $data);
		$this->load->view('admin/footer');
	}

}

?>