<?php 
echo doctype('xhtml1-trans');
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo meta('Content-Type', 'text/html; charset='.$this->config->item('charset'), 'http-equiv');
echo '<title>Administrator</title>';
echo link_tag('css/backend.css');

echo '<script type="text/javascript" src="'.base_url().'/js/jquery-1.8.3.js"></script>';
echo '<script type="text/javascript" src="'.base_url().'/js/jquery.validate.js"></script>';
?>
<script language="javascript" type="text/javascript">
	$(document).ready(function(e) {
		$('#loginform').validate({
			rules: {
				log: {
					required: true,
					number: false
				},
				pwd: {
					required: true,
					number: false
				}
			}
		});
    });
</script>
</head>

<body style="background:#4E5869;">
	<div style="margin:7em auto;">
    <?php
    	if($this->session->flashdata('LOGIN_STATUS') == 'failed')
    	{
    		echo '<div style="height:40px; color:#FFFF99; text-align:center; width:300px; margin:0 auto;">Login Process is failed. Try agin.</div>';
		}
	?>
        <div style="height:230px; width:300px; margin:0 auto; background:#8090AB; -moz-border-radius:10px; -webkit-border-radius:10px; border-right:10px;">
        <?php
        echo form_open('admin', array('id' => 'loginform', 'name' => 'loginform', 'method' => 'post'));
        
        echo '<p>';
        echo form_label('User ID', '', 'class="login_label"');
        echo br();
        echo form_input('user_id', '', 'id="user_id" class="input" size="20"');
        echo '</p>';
        
        echo '<p>';
        echo form_label('Password', '', 'class="login_label"');
        echo br();
        echo form_password('user_pass', '', 'id="user_pass" class="input" size="20"');
        echo '</p>';
        
        echo '<p class="submit">';
        echo form_submit('Sign In', 'Sign In', 'id="btn_signin"');
        echo '</p>';
		
        echo form_close();
        ?>
        </div>
        <script type="text/javascript">document.loginform.user_id.focus();</script>
	</div>
</body>
</html>
