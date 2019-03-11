<?php
if (!function_exists('portal_header'))
{
	function portal_header ()
	{
		$CI = &get_instance();
		
		$result = '';
		//---marked by Jon---		
		$result .= '<div class="login_div">';
		
		//if($_SESSION['SESS_CUSTOMER_EMAIL']){
		//if ($CI->session->userdata('SESS_CUSTOMER_EMAIL'))
		
		//if ($CI->session->userdata('SESS_CUSTOMER_EMAIL'))
		if (isset($_SESSION['SESS_CUSTOMER_EMAIL']) && $_SESSION['SESS_CUSTOMER_EMAIL'] != '')
		{
			$result .= '<span class="logout-span">Logout</span>';
		}
		else
		{
			$result .= '<span class="signup-span">SignUp</span>&nbsp;&nbsp; <span class="login-span">Login</span>';
		}
		$result .= '</div>';
		///////////////////////////////	
		$result .= '<div class="clear-both"></div>';
		
		$result .= '<div class="header_1">';
		$result .= '<div class="company-name">Company Name</div>';
		
		$result .= '<div class="logo">';
		$result .= anchor('', img(array('src' => 'images/default_logo.png', 'border' => 0)));
		//$result .= '<a href="./"><img src="' . $CI->config->item('base_url') . 'images/default_logo.png" border="0" /></a>';
		$result .= '</div>';
		$result .= '<div class="header_1_3">Meine Favoriten</div>';
		$result .= '<div class="header_1_2">Meine Bestellungen</div>';
		$result .= '<div class="header_1_1">Restaurants</div>';
		$result .= '<div class="clear-both"></div>';
		$result .= '</div>';
		$result .= '<div class="login_modal" id="login_modal"></div>';
		
		return $result;
	}
}

?>