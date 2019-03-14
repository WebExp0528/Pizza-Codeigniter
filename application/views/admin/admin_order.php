<script type="text/javascript">
	$(document).ready(function(e) {
		$('#id_key_status').bind("change", null, function(){
			$('#id_mode').val('');
			$('#form1').submit();
		});
		$('#id_key_date').bind("change", null, function(){
			$('#id_mode').val('');
			$('#form1').submit();
		});

		$('.key_input').keypress(function(event) {
  			if(event.keyCode == 13){
				$('#id_mode').val('');
				$('#form1').submit();
			}
		});
	});
</script>
	<div class="content">
		<?php echo form_open('admin/admin_order', array('method' => 'post', 'id' => 'form1'));?>
		<div>
			<h1>Order Info</h1>
		</div>
		<div style="margin:15px;">
			From : <?php echo form_input('s_date', $keys['s_date'], 'class="datepicker key_input" style="width:80px"');?>
			To : <?php echo form_input('e_date', $keys['e_date'], 'class="datepicker key_input" style="width:80px"');?>
			&emsp;
			Select the Shop : 
			<?php
			$shops = get_shops();
			$arr_options = array(0 => 'All shops');
			foreach ($shops->result() as $shop) {
				$arr_options[$shop->store_id] = $shop->shop_name;
			}
			echo form_dropdown('shop', $arr_options, array($keys['shop']));
			?>
			&emsp;Post Code : <?php echo form_input('post_code', $keys['post_code'], 'class="key_input" style="width:80px"');?>
			&emsp;<?php echo form_submit('search', 'Search');?>
		</div>
		<div style="margin-left:15px;">
		<?php echo pagination($page, $total_pages, $line_num, $total_record);?>
			<div style="float:left; width:30%;">
				Total Amount : <span id="total_amount">€ <?php echo number_format($total_amount, 2);?></span>
			</div>
		</div>
		<div style="clear:both"></div>
		<div style="margin-left:15px;">
			<table class="list">
				<tr style="background-color:#E7EFEF; height:30px;">
					<td width="5%" rowspan="2" align="center">No</td>
					<td width="12%" rowspan="2" align="center">Shop Name</td>
					<td width="8%">Order ID</td>
					<td width="20%">Customer Name</td>
					<td width="12%">Telephone</td>
					<td width="11%">Status</td>
					<td width="15%">Date Added</td>
					<td width="7%" rowspan="2" align="center">Price</td>
					<td width="7%" rowspan="2" align="center">Action</td>
				</tr>
				<tr style="background-color:#E7EFEF; height:30px;">
					<td><?php form_input('key_orderid', $keys['key_orderid'], 'class="key_input" style="width:60px;"');?></td>
					<td><?php form_input('key_customername', $keys['key_customername'], 'class="key_input" style="width:150px;"');?></td>
					<td><?php form_input('key_telephone', $keys['key_telephone'], 'class="key_input" style="width:100px;"');?></td>
					<td>
						<?php 
						$status = get_order_status();
						$arr_options = array(0 => 'Select');
						foreach ($status->result() as $row) {
							$arr_options[$row->id] = $row->name;
						}
						echo form_dropdown('key_status', $arr_options, array($keys['key_status']), 'id="key_status" style="width:80px;"');
						?>
					</td>
					<td><?php echo form_dropdown('key_date', array('desc' => 'desc', 'asc' => 'asc'), array($keys['key_date']), 'id="id_key_date" style="width:120px;"');?></td>
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
					<td align="center"><?php echo $no;?></td>
					<td><?php echo $data->shop_name; ?></td>
					<td><?php echo $data->id; ?></td>
					<td><?php echo $data->firstname; ?>&nbsp;<?php echo $data->lastname; ?></td>
					<td><?php echo $data->telephone; ?></td>
					<td><?php echo $data->status; ?></td>
					<td><?php echo $data->date; ?></td>
					<td><?php echo $data->total;//echo getTotalPrice($data->order_data); ?></td>
					<td>[<?php echo anchor('admin/order-edit/'.$data->id, 'Edit'); ?>]</td>
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
		<input type="hidden" id="id_mode" value="delete" name="mode" />
 		<?php echo form_close();?>
	<!-- end .content -->
	</div>
