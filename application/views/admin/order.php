<script type="text/javascript">
	var totalpages = <?php echo $totalpages;?>;
	$(document).ready(function(e) {
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
		})

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

</head>

	<div class="content">
		<?php	
		echo put_alert($process_flag);
		echo form_open('admin/order', array('method' => 'post', 'id' => 'form1'));
		?>
		<div>
			<h1>Orders</h1>
		</div>
		<div style="margin-left:15px;">
			<div style="float:left; width:20%;">		
			<!--input type="button" value="Delete" id="btn_delete" /-->
	            <a href="#" class="action" id="btn_delete"><?php echo img(array('src' => 'images/admin/delete.gif', 'border' => 0, 'title' => 'Delete Product'));?>Delete</a>
			</div>
		<?php echo pagination($page, $total_pages, $line_num, $total_record);?>
		</div>
		<div style="clear:both"></div>
		<div style="margin-left:15px;">
			<table class="list">
				<tr style="background-color:#E7EFEF; height:30px;">
					<td width="5%" rowspan="2" align="center"><input type="checkbox" name="chk" id="chk" /></td>
					<td width="12%">Order ID</td>
					<td width="27%">Customer Name</td>
					<td width="12%">Telephone</td>
					<td width="11%">Status</td>
					<td width="18%">Date Added</td>
					<td width="7%" rowspan="2" align="center">Price</td>
					<td width="8%" rowspan="2" align="center">Action</td>
				</tr>
				<tr style="background-color:#E7EFEF; height:30px;">
					<td><?php echo form_input('key_orderid', $keys['key_orderid'], 'class="key_input" id="id_key_orderid" style="width:60px;"');?></td>
					<td><?php echo form_input('key_customername', $keys['key_customername'], 'class="key_input" id="id_key_customername" style="width:150px;"');?></td>
					<td><?php echo form_input('key_telephone', $keys['key_telephone'], 'class="key_input" id="id_key_telephone" style="width:100px;"');?></td>
					<td>
						<?php 
						$status_result = get_order_status();
						$arr_options = array(0 => 'Select');
						foreach ($status_result->result() as $row) {
							$arr_options[$row->id] = $row->name;
						}
						echo form_dropdown('key_status', $arr_options, $keys['key_status'], 'id="id_key_status"');
						?>
					</td>
					<td><?php echo form_dropdown('key_date', array('Select', 'desc' => 'desc', 'asc' => 'asc'), array($keys['key_date']), 'id="id_key_date"');?></td>
				</tr>
				<?php
					if ($result->num_rows() > 0)
					{
						foreach ($result->result() as $data) {
				?>
				<tr class="td_value">
					<td align="center"><?php echo form_checkbox('chks[]', $data->id, false, 'class="cls_chk"');?></td>
					<td>
						<?php echo $data->id; ?>
					</td>
					<td>
						<?php echo $data->firstname; ?>&nbsp;
						<?php echo $data->lastname; ?>
					</td>
					<td>
						<?php echo $data->telephone; ?>
					</td>
					<td>
						<?php echo $data->status; ?>
					</td>
					<td>
						<?php echo $data->date; ?>
					</td>
					<td>
						<?php echo $data->total;//echo getTotalPrice($data->order_data); ?>
					</td>
					<td>[<?php echo anchor('admin/oerder-edit/'.$data->id, 'Edit'); ?>]</td>
				</tr>
				<?php
						}
					}
				else {
				?>
				<tr class="td_value">
					<td colspan="8" style="text-align:center;">
						no data
					</td>
				</tr>
				<?php
					}
				?>
		   </table>
		</div>
		
		<input type="hidden" id="id_mode" value="delete" name="mode" />
		<?php echo form_close();?>
	<!-- end .content -->
	</div>
