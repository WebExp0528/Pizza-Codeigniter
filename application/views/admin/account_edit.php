<script type="text/javascript">
	$(document).ready(function(e) {
		$('#form1').validate({
			rules: {
				first_name:{
					required: true
				},
				last_name:{
					required: true
				},
				email:{
					required: true,
					email:true
				},
				telephone:{
					required: true
				},
				postcode:{
					required: true,
					number: true
				}
			}
		});
        $('#btn_save').bind("click", null, function(){
			$('#form1').submit();
		});
	});
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open('admin/account_edit', array('method' => 'post', 'id' => 'form1'));
	echo form_hidden('upid', $contents->id);
	?>
    	<div>
	    	<h1>My Account Info</h1>
        </div>
        <div style="margin-left:15px;">
            <div style="float:right; margin-right:50px;">
                <input type="button" value="Save" id="btn_save" />
            </div>
        	<table class="list">
            	<tr>
                	<td width="31%" class="td_header">User ID</td>
                	<td width="69%" class="td_value"><?php echo form_input('user_name', $contents->user_id, 'readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">First Name</td>
                	<td class="td_value"><?php echo form_input('first_name', $contents->first_name);?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Last Name</td>
                	<td class="td_value"><?php echo form_input('last_name', $contents->last_name);?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Email</td>
                	<td class="td_value"><?php echo form_input('email', $contents->user_email);?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Phone Number</td>
                	<td class="td_value"><?php echo form_input('telephone', $contents->telephone);?></td>
               	</tr>
            	<tr>
                	<td class="td_header">City</td>
                	<td class="td_value"><?php echo form_input('city', $contents->city);?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Street</td>
                	<td class="td_value"><?php echo form_input('street', $contents->street);?></td>
               	</tr>
            	<tr>
                	<td class="td_header">House No</td>
                	<td class="td_value"><?php echo form_input('house_no', $contents->house_no);?></td>
               	</tr>
            	<tr>
                	<td class="td_header">post code</td>
                	<td class="td_value"><?php echo form_input('postcode', $contents->postcode);?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Company</td>
                	<td class="td_value"><?php echo form_input('company', $contents->company);?></td>
               	</tr>
           </table>
        </div>
        <input type="hidden" value="update" name="mode" />
        </form>
    <!-- end .content -->
    </div>
