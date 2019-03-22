<script type="text/javascript">
	$(document).ready(function(e) {
		
		$('#form1').validate({
			rules: {
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
	});
</script>

	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open_multipart('admin/user-edit/'.$contents->id, array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'save');
	?>
    	<div>
	    	<h1>Edit User</h1>
		</div>
		<div style="margin-left:15px;">
            <div style="margin-right:50px; float:right;">
            	<input type="button" value="Save" id="btn_save" />
            	<input type="button" value="Back" id="btn_cancel" class="btn" />
            </div>
            <div>
                <table class="list">
                    <tr>
                        <td width="31%" class="td_header">User ID</td>
                        <td width="69%" class="td_value"><?php echo form_input('user_id', $contents->user_id, 'id="user_id" readonly="readonly"');?></td>
                    </tr>
                   <tr>
                        <td class="td_header">Email</td>
                        <td class="td_value"><?php echo form_input('email', $contents->user_email, 'id="email" readonly="readonly"');?></td>
                    </tr>
                    <tr>
                        <td class="td_header">Telephone Number</td>
                        <td class="td_value"><?php echo form_input('telephone', $contents->telephone, 'id="telephone" readonly="readonly"');?></td>
                    </tr>
                    <tr>
                        <td class="td_header">Approved</td>
                        <td class="td_value">
                        	<?php echo form_radio('approved', 'Y', ($contents->is_approve == 'Y'), 'id="approved_0"');?> Yes
                        	<?php echo form_radio('approved', 'N', ($contents->is_approve == 'N'), 'id="approved_1"');?> No
                        </td>
                    </tr>
               </table>
			</div>
        </div>
    <!-- end .content -->
    <?php echo form_close();?>
	</div>
