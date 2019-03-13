<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Portal extends CI_Controller
{
	
	public function index()
	{
		$this->load->library('javascript');
		$this->load->helper('portal');
		$this->load->helper('date');
		
		$this->load->model('user');
		$this->load->model('setting');
		
		if (AUTO_JUMP_TO_SHOP)
		{
			$res = $this->setting->get();
			if ($res->num_rows() == 1)
			{
				$shop = $res->result();
				$shop_name = $shop[0]->shop_name;
				redirect($shop_name);
			}
		}
		
		/*$citys = get_citynames();
		
		if ($citys->num_rows() > 0)
		{
			$values = array();
			
			foreach ($citys->result() as $row)
			{
				$values[] = array($row->id, $row->region_name);
			}
			$data['citys'] = $values;
		}*/
		
		$closed_shops = $this->setting->get_closed_shops();
		$data['contents'] = $closed_shops;
		$this->load->view('portal/main', $data);
	}
	
	public function search($region = '')
	{
		$this->load->model('setting');
		$this->load->helper('portal');
		$this->load->helper('date');
		$this->load->library('javascript');
		
		// Get search result;
		$data = array();
		if ($region != '')
		{
			$region_name = get_regionname($region);
			//$param = $region_name;
			$setting = $this->setting->get_by_option('region', $region_name);
		}
		
		$postcode = $this->input->post('postcode', true);
		if ($postcode)
		{
			$setting = $this->setting->get_by_option('postcode', $postcode);
		}
		if (!$region && !$postcode)
		{
			redirect('/');
		}
		$data['shops'] = $setting;
		
		// Footer text
		if ($region != '' && $postcode)
		{
			$title = "LIEFERSERVICES in " . get_cityname_by_postcode($postcode) . ' ' . $postcode;
		}
		else if ($postcode)
		{
			$title = "LIEFERSERVICES in " . get_cityname_by_postcode($postcode) . ' ' . $postcode;
		}
		else if ('region' != '')
		{
			$title = 'LIEFERSERVICES in ' . $region_name;
		}
		
		$data['title'] = $title;
		$data['site_title'] = (!empty($title)) ? $title . ' | ' . $this->config->item('sitename') : $this->config->item('sitename');
		$data['shop_count'] = ($setting->num_rows() > 0) ? $setting->num_rows() : 'no';
		$data['region'] = $region;
		$data['postcode'] = $postcode;
		//dumpr($data);
		

		$this->load->view('portal/search', $data);
	}
}
?>