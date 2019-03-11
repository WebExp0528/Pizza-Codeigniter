<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "portal";
$route['404_override'] = '';

$route['admin/user-new'] 					= "admin/user_new";
$route['admin/user-edit/(:num)'] 			= "admin/user_edit/$1";
$route['admin/customer-new'] 				= "admin/customer_new";
$route['admin/customer-edit/(:num)'] 		= "admin/customer_edit/$1";
$route['admin/order-edit/(:num)']			= "admin/order_edit/$1";
$route['admin/category-new'] 				= "admin/category_new";
$route['admin/category-edit/(:num)'] 		= "admin/category_edit/$1";
$route['admin/topping-new'] 				= "admin/topping_new";
$route['admin/topping-edit/(:num)'] 		= "admin/topping_edit/$1";
$route['admin/product-new'] 				= "admin/product_new";
$route['admin/product-edit/(:num)'] 		= "admin/product_edit/$1";
$route['admin/specialoffer-new'] 			= "admin/special_offer_new";
$route['admin/specialoffer-edit/(:num)'] 	= "admin/special_offer_edit/$1";
$route['admin/delivery-area-new'] 			= "admin/delivery_area_new";
$route['admin/delivery-area-edit/(:num)'] 	= "admin/delivery_area_edit/$1";
$route['admin/calendar-edit'] 				= "admin/calendar_edit/";

$route['ajax/create_password'] 					= "ajax/create_password";
$route['ajax/check_password'] 					= "ajax/check_password";
$route['ajax/get_password'] 					= "ajax/get_password";
$route['ajax/get_city_name'] 					= "ajax/get_city_name";
$route['ajax/get_postcode'] 					= "ajax/get_postcode";
$route['ajax/get_postcode_1'] 					= "ajax/get_postcode_1";
$route['ajax/get_size_for_topping'] 			= "ajax/get_size_for_topping";
$route['ajax/get_size_for_category'] 			= "ajax/get_size_for_category";
$route['ajax/postcode_verify'] 					= "ajax/postcode_verify";
$route['ajax/fax-data-open'] 					= "ajax/fax_data_open";
$route['ajax/fax-data-send'] 					= "ajax/fax_data_send";
$route['ajax/fax-data-delete'] 					= "ajax/fax_data_delete";
$route['ajax/delete-css'] 						= "ajax/delete_css";

$route['shop/(.+)'] 							= "shops/index/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */