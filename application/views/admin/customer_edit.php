<script type="text/javascript">
	$(document).ready(function(e) {
		$('#form1').validate({
			rules: {
				user_name: {
					required: true
				},
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
				city:{
					required: true
				},
				street:{
					required: true
				},
				house_no:{
					required: true
				},
				postcode:{
					required: true,
					number: true
				},
				company:{
					required: true
				}
			}
		});
        $('#btn_save').bind("click", null, function(){
			$('#form1').submit();
		});

		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/customer';?>";
		});
	});
</script>
	<div class="content">
<?php
	echo put_alert($process_flag);
	echo form_open_multipart('admin/customer-edit/'.$contents->id, array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'save');
?>
    	<div>
	    	<h1>User Management</h1>
        </div>
        <div style="margin-left:15px;">
            <div style="float:right; margin-right:50px;">
                <input type="button" value="Save" id="btn_save" />
                <input type="button" value="Cancel" id="btn_cancel" />
            </div>
        	<table class="list">
            	<tr>
                	<td width="31%" class="td_header">User ID</td>
                	<td width="69%" class="td_value"><?php echo form_input('user_name', $contents->user_id, 'id="user_name" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">First Name</td>
                	<td class="td_value"><?php echo form_input('first_name', $contents->firstname, 'id="first_name" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Last Name</td>
                	<td class="td_value"><?php echo form_input('last_name', $contents->lastname, 'id="last_name" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Gender</td>
                	<td class="td_value"><?php echo $contents->gender;?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Email</td>
                	<td class="td_value"><?php echo form_input('email', $contents->email, 'id="email" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Phone Number</td>
                	<td class="td_value"><?php echo form_input('telephone', $contents->telephone, 'id="telephone" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Region</td>
                	<td class="td_value"><?php echo form_input('region', $contents->region, 'id="region" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">City</td>
                	<td class="td_value"><?php echo form_input('city', $contents->city, 'id="city" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Street</td>
                	<td class="td_value"><?php echo form_input('street', $contents->street, 'id="street" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">House No</td>
                	<td class="td_value"><?php echo form_input('house_no', $contents->house_no, 'id="house_no" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Details</td>
                	<td class="td_value"><?php echo form_input('details', $contents->address_detail, 'id="details" readonly="readonly"');?></td>
               	</tr>
           	  <tr>
                	<td class="td_header">post code</td>
                	<td class="td_value"><?php echo form_input('postcode', $contents->postcode, 'id="postcode" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Company</td>
                	<td class="td_value"><?php echo form_input('company', $contents->company, 'id="company" readonly="readonly"');?></td>
               	</tr>
            	<tr>
                	<td class="td_header">Approved</td>
 					<td class="td_value">
						<?php echo form_radio('approved', 'Y', ($contents->approved == 'Y'), 'id="approved_0"');?> Yes
						<?php echo form_radio('approved', 'N', ($contents->approved == 'N'), 'id="approved_1"');?> No
					</td>
               	</tr>
           </table>
        </div>
        <?php echo form_close();?>
    <!-- end .content -->
    </div>
