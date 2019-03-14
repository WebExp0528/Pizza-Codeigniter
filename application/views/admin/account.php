<script type="text/javascript">
	$(document).ready(function(e) {
		$('#form1').validate({
			rules: {
				oldpwd: {
					required: true
				},
				newpwd:{
					required: true,
					minlength: 5
				},
				rpwd:{
					required: true,
					equalTo:'#newpwd',
					minlength: 5
				},
				email:{
					required: true,
					email:true
				}
			}
		});

        $('#btn_save').bind("click", null, function(){

	        var user_pwd = $('#oldpwd').val();
	        var user_id = $('#user_id').val();
			
			$.post("<?php echo base_url('ajax/check_password');?>", { user_pwd: user_pwd, user_id:user_id }, function(result){  
					//if the result is 1  
					if(result == 1){  
						//show that the username is available  
						$('#form1').submit();
					}else{  
						//show that the username is NOT available  
						alert("Old Password is incorrect!");
					}  
			});  
		});
	});
</script>
	<div class="content">
	<?php 
	echo put_alert($process_flag);
	echo form_open_multipart('admin/account', array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'edit');
	echo form_hidden('upid', $upid);
	?>
    	<div>
	    	<h1>Account</h1>
		</div>
        <div style="margin-left:10px;">
        	<div style="float:right; margin:0 50px 10px 0;">
            	<input type="button" value="Save" id="btn_save" />
            </div>
        	<div>
                <table class="list">
                    <tr>
                        <td width="31%" class="td_header">User ID</td>
                        <td width="69%" class="td_value"><?php echo form_input('user_id', $user_id, 'id="user_id",  disabled="disabled"');?></td>
                    </tr>
                    <tr>
                        <td class="td_header">Email Address</td>
                        <td class="td_value"><?php echo form_input('email', $email, 'id="email"');?></td>
                    </tr>
                    <tr>
                        <td width="31%" class="td_header">Old Password</td>
                        <td width="69%" class="td_value"><?php echo form_password('oldpwd', '', 'id="oldpwd"');?></td>
                    </tr>
                    <tr>
                        <td class="td_header">New Password</td>
                        <td class="td_value"><?php echo form_password('newpwd', '', 'id="newpwd"');?></td>
                    </tr>
                    <tr>
                        <td class="td_header">Confirm Password</td>
                        <td class="td_value"><?php echo form_password('rpwd', '', 'id="rpwd"');?></td>
                    </tr>
                </table>
			</div>
        </div>
    <!-- end .content -->
    <?php echo form_close();?>
    </div>
