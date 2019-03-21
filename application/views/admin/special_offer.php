<script type="text/javascript">
	$(document).ready(function(e) {
		$('#btn_insert').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/specialoffer-new'?> ";
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
		$('#btn_copy').bind("click", null, function(){
			$('#id_mode').val('copy');
			$('#form1').submit();
		});

	});
</script>
	<div class="content">
		<?php	echo put_alert($process_flag);?>
		<div>
			<h1>Special Offer</h1>
		</div>
	  	<div style="margin-left:15px;">
			<!--input type="button" id="btn_insert" value="Insert" />
			<input type="button" id="btn_delete" value="Delete" /-->
			<a href="#" class="action" id="btn_insert"><?php echo img(array('src' => 'images/admin/new.gif', 'border' => 0, 'title' => 'New Offer'));?>Insert</a>
			<a href="#" class="action" id="btn_delete"><?php echo img(array('src' => 'images/admin/delete.gif', 'border' => 0, 'title' => 'Delete Offer'));?>Delete</a>
			<a href="#" class="action" id="btn_copy"><?php echo img(array('src' => 'images/admin/copy.gif', 'border' => 0, 'title' => 'Copy Offer'));?>Copy</a>
	  	</div>
		<div style="margin-left:15px;">
		<?php 	echo form_open('admin/special_offer', array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));?>
			<table class="list" id="tbl1">
				<tr style="background-color:#E7EFEF;">
					<td width="5%" align="center">
						<input type="checkbox" id="chk" />
					</td>
					<td width="10%">Code</td>
					<td width="40%">Name</td>
					<td width="20%">Price</td>
					<td width="20%">Date Added</td>
					<td width="10%">Action</td>
				</tr>
				<?php
					if($contents->num_rows() > 0)
					{
						foreach ($contents->result() as $data)
						{
				?>
				<tr class="td_value">
					<td align="center"><?php echo form_checkbox('chks[]', $data->id, false, 'class="cls_chk"');?></td>
					<td>
						<?php echo $data->offer_code ?>
					</td>
					<td>
						<?php echo $data->offer_name ?>
					</td>
					<td>
						€ <?php echo $data->offer_price; ?>
					</td>
					<td>
						<?php echo $data->add_date ?>
					</td>
					<td>[<?php echo anchor('admin/specialoffer-edit/'.$data->id, 'Edit'); ?>]</td>
				</tr>
				<?php
						}
					} else {
				?>
				<tr class="td_value">
                	<td colspan="5" style="text-align:center;">No data</td>
				</tr>
			<?php
				}
			?>
		   </table>
		   <input type="hidden" id="id_mode" value="delete" name="mode" />
		   <?php echo form_close();?>
		</div>
	<!-- end .content -->
	</div>
