<?php 
echo doctype('xhtml1-trans');
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo meta('Content-Type', 'text/html; charset='.$this->config->item('charset'), 'http-equiv');
//echo meta('refresh', '3', 'http-equiv');
echo '<title>Administrator</title>';

echo link_tag('css/jquery.ui-1.8.14.css');
echo link_tag('css/jquery.ui.datepicker.css');
echo link_tag('css/jquery.ui.dialog.css');
echo link_tag('css/jquery.ui.autocomplete.css');
echo link_tag('css/backend.css');

echo '<script language="javascript">var base_url = "'.base_url().'";</script>';
echo '<script type="text/javascript" src="'.base_url('js/jquery-1.8.3.js').'"></script>';
echo '<script type="text/javascript" src="'.base_url('js/jquery.validate.js').'"></script>';
echo '<script type="text/javascript" src="'.base_url('js/jquery.simplemodal.js').'"></script>';
echo '<script type="text/javascript" src="'.base_url('js/jquery.placeholder.js').'"></script>';
echo '<script type="text/javascript" src="'.base_url('js/jquery-ui.custom.js').'"></script>';
echo '<script type="text/javascript" src="'.base_url('js/common_admin.js').'"></script>';
echo '</head>';
?>
<body>

<div class="container">
	<div class="header">
		<div>
			<div class="div1" style="border:2px; float:left;">Shop Administration</div>
			<div style="float:right"><?php echo anchor('admin/logout', 'logout');?></div>
			<div style="clear:both;"></div>
		</div>
	</div>
