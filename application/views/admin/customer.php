<script type="text/javascript">
	$(document).ready(function(e) {
        $('#btn_insert').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/customer-new';?>";
		});
        $('#btn_delete').bind("click", null, function(){
			if(confirm("Do you want to delete the record(s)?")){
				$('#form1').submit();
			}
		});
		$('#chk').bind("change", null, function(){
			if($('#chk').attr('checked'))
			{
				$('.cls_chk').attr('checked','checked');
			} else {
				$('.cls_chk').removeAttr('checked');
			}
		});
		
		$('#id_key_status').bind("change", null, function(){
			$('#id_mode').val('');
			$('#form1').submit();
		});

		$('#id_key_date').bind("change", null, function(){
			$('#id_mode').val('');
			$('#form1').submit();
		});
		
		$('.key_input').keydown(function(event) {
  			if(event.keyCode == 13){
				$('#id_mode').val('');
				$('#form1').submit();
			}
		});
    });
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open('admin/customer', array('method' => 'post', 'id' => 'form1'));
	?>
    	<div>
	    	<h1>Customer Management</h1>
        </div>
        <div style="margin-left:15px;">
			<div style="float:left; width:20%;">
	            <!-- <a href="#"><?php echo img(array('src' => 'images/admin/new.gif', 'id' => 'btn_insert', 'border' => 0, 'title' => 'New Product'));?></a> -->
	            <a href="#"><?php echo img(array('src' => 'images/admin/delete.gif', 'id' => 'btn_delete', 'border' => 0, 'title' => 'Delete Product'));?></a>
            </div>
			<?php echo pagination($page, $total_pages, $line_num, $total_record);?>
        </div>
		<div style="clear:both"></div>
        <div style="margin-left:15px;">
        	<table class="list">
            	<tr style="background-color:#E7EFEF; height:30px;">
                	<td width="4%">&nbsp;</td>
                	<td width="4%">No</td>
                	<td width="9%">Name</td>
                	<td width="28%">Address</td>
                	<td width="12%">Email Address</td>
                	<td width="13%">Phone Number</td>
                	<td width="11%">Date Added</td>
                	<td width="11%">Approved</td>
                	<td width="8%">Action</td>
                </tr>
            	<tr style="background-color:#E7EFEF; height:30px;">
                	<td width="4%"><?php echo form_checkbox('chk', '', false, 'id="chk"');?></td>
                	<td width="4%">&nbsp;</td>
                	<td width="9%"><?php echo form_input('key_name', $keys['key_name'], 'class="key_input" id="id_key_name" size="6"');?></td>
                	<td width="28%"><?php echo form_input('key_address', $keys['key_address'], 'class="key_input" id="id_key_address" size="10"');?></td>
                	<td width="12%"><?php echo form_input('key_email', $keys['key_email'], 'class="key_input" id="id_key_email" size="10"');?></td>
                	<td width="13%"><?php echo form_input('key_telephone', $keys['key_telephone'], 'class="key_input" id="id_key_telephone" size="8"');?></td>
                	<td width="11%"><?php echo form_dropdown('key_date', array('Select', 'desc' => 'desc', 'asc' => 'asc'), array($keys['key_date']), 'id="id_key_date"');?></td>
                	<td width="11%"><?php echo form_dropdown('key_status', array('Select', 'Y' => 'Enalbled', 'N' => 'Disabled'), array($keys['key_status']), 'id="id_key_status"');?></td>
                	<td width="8%">&nbsp;</td>
                </tr>
                <?php
				if ($result->num_rows() > 0)
				{
					$no = ($page - 1) * $line_num;
					foreach ($result->result() as $data)
					{
						$no++;
				?>
            	<tr class="td_value">
                	<td><?php echo form_checkbox('chks[]', $data->id, false, 'class="cls_chk"');?></td>
                	<td><?php echo $no; ?></td>
                	<td><?php echo $data->firstname.' '.$data->lastname; ?></td>
                	<td><?php echo $data->city.' '.$data->street.' '.$data->house_no.' ('.$data->postcode.')'; ?></td>
                	<td><?php echo $data->email; ?></td>
                	<td><?php echo $data->telephone; ?></td>
                	<td><?php echo $data->date_added; ?></td>
                	<td><?php echo $data->approved; ?></td>
                	<td><?php echo '['.anchor('admin/customer-edit/'.$data->id, 'Edit').']'; ?></td>
                </tr>
                <?php
					}
				} else {
				?>
            	<tr class="td_value">
                	<td colspan="9" style="text-align:center;">No data</td>
				</tr>
                <?php
				}
				?>
           </table>
        </div>
    <!-- end .content -->
        <input type="hidden" id="id_mode" value="delete" name="mode" />
    <?php echo form_close();?>
    </div>
