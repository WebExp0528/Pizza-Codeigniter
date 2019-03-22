<script type="text/javascript">
	$(document).ready(function(e) {

		$('#form1').validate({
			rules: {
				user_id: {
					required: true
				},
				pwd:{
					required: true
				},
				email:{
					required: true,
					email:true
				},
				telephone:{
					required: true
				}
			}
		});

        $('#btn_save').bind("click", null, function(){
			$('#form1').submit();
		});
        
		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/user';?>";
		});
		
		$('#btn_password').bind("click", null, function(){
			$.post("<?php echo site_url().'ajax/create_password';?>", null, function(result){
				$('#pwd').val(result);
			});
		});
		
	});
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open_multipart('admin/user-new', array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'new');
	?>
    	<div>
	    	<h1>Add User</h1>
		</div>
		<div style="margin-left:15px;">
            <div style="margin-right:50px; float:right;">
            	<input type="button" value="Save" id="btn_save" />
            	<input type="button" value="Back" id="btn_cancel" class="btn" />
            </div>
            <div>
                <table width="76%" class="list">
                    <tr>
                        <td width="26%" class="td_header">Login User ID</td>
                        <td width="74%" class="td_value"><?php echo form_input('user_id', '', 'id="user_id"');?></td>
                    </tr>
                    <tr>
                        <td class="td_header">Password</td>
                        <td class="td_value">
                        	<?php echo form_input('pwd', '', 'id="pwd"');?>&nbsp;&nbsp;&nbsp;
                            <input type="button" value="Create Password" id="btn_password" />
                        </td>
                    </tr>
                    <tr>
                        <td class="td_header">Email Address</td>
                        <td class="td_value"><?php echo form_input('email', '', 'id="email"');?></td>
                    </tr>
                    <tr>
                        <td class="td_header">Telephone Number</td>
                        <td class="td_value"><?php echo form_input('telephone', '', 'id="telephone"');?></td>
                    </tr>
               </table>
			</div>
        </div>
    <!-- end .content -->
    <?php echo form_close();?>
	</div>
  <!-- end .container -->
