<div class="sidebar1">
	<ul class="nav">
	<?php
	if ($this->session->userdata('SESS_PRIVILEGE') != "admin")
	{
		echo '<li>'.anchor('admin/category', 'Category').'</li>';
		echo '<li>'.anchor('admin/topping', 'Topping Price').'</li>';
		echo '<li>'.anchor('admin/product', 'Product').'</li>';
		echo '<li>'.anchor('admin/special_offer', 'Special Offer').'</li>';
		echo '<li>'.anchor('admin/order', 'Order').'</li>';
		echo '<li>'.anchor('admin/faxdata', 'FaxData').'</li>';
		echo '<li>'.anchor('admin/delivery_area', 'Delivery Area').'</li>';
		echo '<li>'.anchor('admin/calendar', 'Calendar').'</li>';
		echo '<li>'.anchor('admin/setting', 'Setting').'</li>';
		echo '<li>'.anchor('admin/account_edit', 'Account').'</li>';
	}
	if ($this->session->userdata('SESS_PRIVILEGE') == "admin")
	{
		echo '<li>'.anchor('admin/user', 'Shop Manager').'</li>';
		echo '<li>'.anchor('admin/customer', 'Buyer Manager').'</li>';
		echo '<li>'.anchor('admin/admin_order', 'Order Info').'</li>';
		echo '<li>'.anchor('admin/admin_faxdata', 'Fax Datas').'</li>';
		echo '<li>'.anchor('admin/admin_setting', 'Setting').'</li>';
		echo '<li>'.anchor('admin/account', 'Account').'</li>';
	}
	?>
	</ul>
</div>