<script type="text/javascript">
	$(document).ready(function(e) {
        $('#btn_insert').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/delivery-area-new'?> ";
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
		})
    });
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open('admin/delivery_area', array('method' => 'post', 'id' => 'form1'));
	?>
    	<div>
	    	<h1>Delivery Area</h1>
		</div>
      <div style="margin-left:15px;">
<!--
            <input type="button" id="btn_insert" value="Insert" />
            <input type="button" id="btn_delete" value="Delete" />
-->
            <a href="#" id="btn_insert"><?php echo img(array('src' => 'images/admin/new.gif', 'border' => 0, 'title' => 'New Product'));?> New</a>
            <a href="#" id="btn_delete"><?php echo img(array('src' => 'images/admin/delete.gif', 'border' => 0, 'title' => 'Delete Product'));?> Delete</a>
            
        </div>
		<div style="margin-left:15px;">
        	<table class="list">
            	<tr class="tr_header">
                	<td width="5%"><?php echo form_checkbox('chk', '', false, 'id="chk"');?></td>
                	<td width="10%">No</td>
                	<td width="18%">Post Code</td>
                	<td width="14%">Min Price</td>
                	<td width="14%">Deliver Charges</td>
                	<td width="11%">Action</td>
                </tr>
                <?php
				if ($result->num_rows() > 0)
				{
					$no = 0;// ($page - 1) * $line_num;
					foreach ($result->result() as $data)
					{
						$no++;
                ?>
            	<tr class="td_value">
                	<td><?php echo form_checkbox('chks[]', $data->id, false, 'class="cls_chk"');?></td>
                	<td class="td_value"><?php echo $no; ?></td>
                	<td class="td_value"><?php echo $data->postcode; ?></td>
                	<td class="td_value">€<?php echo $data->price; ?></td>
                	<td class="td_value">€<?php echo $data->delivery_charge; ?></td>
                	<td class="td_value">[<?php echo anchor('admin/delivery-area-edit/'.$data->id, 'Edit'); ?>]</td>
                </tr>
                <?php
						}
					} else {
				?>
            	<tr class="td_value">
                	<td colspan="6" style="text-align:center;">
                    	no data
                    </td>
				</tr>

                <?php
					}
				?>
           </table>
        </div>
    <!-- end .content -->
        <input type="hidden" value="delete" name="mode" />
    <?php echo form_close();?>
	</div>
