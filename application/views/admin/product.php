<script type="text/javascript">
	$(document).ready(function(e) {
        $('#btn_insert').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/product-new'?>";
		});
        $('#btn_delete').bind("click", null, function(){
			if(confirm("Do you want to delete the record(s)?")){
				$('#form1').submit();
			}
		});

        $('#btn_copy').bind("click", null, function(){
			$('#id_mode').val('copy');
			$('#form1').submit();
		});

		$('#chk').bind("change", null, function(){
			if($('#chk').attr('checked'))
			{
				$('.cls_chk').attr('checked','checked');
			} else {
				$('.cls_chk').removeAttr('checked');
			}
		});
		$('#id_key_price').bind("change", null, function(){
			$('#id_mode').val('');
			$('#form1').submit();
		});
		$('#id_key_status').bind("change", null, function(){
			$('#id_mode').val('');
			$('#form1').submit();
		});
		$('#id_key_offered').bind("change", null, function(){
			$('#id_mode').val('');
			$('#form1').submit();
		});

		$('#id_key_category').bind("change", null, function(){
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
	echo form_open('admin/product', array('method' => 'post', 'id' => 'form1'));
	?>
    	<div>
	    	<h1>Product</h1>
		</div>
		<div style="margin-left:15px;">
       	  <div style="float:left; width:25%;">
	            <a href="#"><?php echo img(array('src' => 'images/admin/new.gif', 'id' => 'btn_insert', 'border' => 0, 'title' => 'New Product'));?></a>
	            <a href="#"><?php echo img(array('src' => 'images/admin/delete.gif', 'id' => 'btn_delete', 'border' => 0, 'title' => 'Delete Product'));?></a>
			<!-- <a href="#" class="action" id="btn_copy"><img src="images/copy.gif" border="0" title="Copy Product" />Copy</a> -->
            </div>
            
		<?php echo pagination($page, $total_pages, $line_num, $total_record);?>
        </div>
		<div style="clear:both"></div>
		<div style="margin-left:15px;">
        	<table class="list">
            	<tr style="background-color:#E7EFEF; height:30px;">
                	<td width="4%">&nbsp;</td>
                	<td width="7%">Category</td>
                	<td width="7%">Code</td>
                	<td width="35%">Product Name</td>
                	<td width="11%">Price</td>
                	<td width="15%">Offered</td>
                	<td width="13%">Status</td>
                	<td width="8%">Action</td>
                </tr>
            	<tr style="background-color:#E7EFEF;">
                	<td width="4%"><?php echo form_checkbox('chk', '', false, 'id="chk"');?></td>
                    <td>
                        <?php
						$category_query = get_category($this->session->userdata('SESS_STORE_ID'), 'Y');
						$arr_options = array(0 => 'Select');
						foreach ($category_query->result() as $row) {
							$arr_options[$row->id] = $row->name;
						}
						echo form_dropdown('key_category', $arr_options, $keys['key_category'], 'id="id_key_category"');
						?>
                    </td>
                	<td width="7%"><?php echo form_input('key_product_code', $keys['key_product_code'], 'class="key_input" id="id_key_product_code" size="5"');?></td>
                	<td width="35%"><?php echo form_input('key_product_name', $keys['key_product_name'], 'class="key_input" id="id_key_product_name" size="15"');?></td>
                	<td width="18%"><?php echo form_dropdown('key_price', array('Select', 'desc' => 'desc', 'asc' => 'asc'), array($keys['key_price']), 'id="id_key_price"');?></td>
                    <td width="15%"><?php echo form_dropdown('key_offered', array('Select', 'Y' => 'is offered', 'N' => 'not offered'), array($keys['key_offered']), 'id="id_key_offered"');?></td>
                	<td width="13%"><?php echo form_dropdown('key_status', array('Select', 'Y' => 'Enalbled', 'N' => 'Disabled'), array($keys['key_status']), 'id="id_key_status"');?></td>
                	<td width="8%">&nbsp; </td>
                </tr>
                <?php
				if ($result->num_rows() > 0)
				{
					$no = ($page - 1) * $line_num;
					foreach ($result->result() as $data)
					{
						$no++;
							?>
            	<tr style="background-color:rgb(244,244,248);">
	               	<td><?php echo form_checkbox('chks[]', $data->id, false, 'class="cls_chk"');?></td>
                	<td align="center"><?php echo $data->cate_name; ?></td>
                	<td align="center"><?php echo $data->code; ?></td>
                	<td><?php echo $data->name ?></td>
                	<td>
                    	<?php
							$price_str = "";
							$size_data = get_size($data->id_category);
							if ($size_data->num_rows() > 0)
								$price_data = get_price($data->id);
							else 
								$price_data = get_price($data->id, -1);
								
							if ($price_data->num_rows() > 0)
							{
								foreach ($price_data->result() as $price) {
									$price_str .= "&euro;" . $price->price . ",";
								}
							}
							echo substr($price_str, 0, -1);
						?>
                    </td>
                	<td align="center"><?php echo $data->is_offer;?></td>
                	<td align="center">
                    	<?php
                        	if($data->is_active == "Y")
								echo "Enable";
							else
								echo "Disable";
						?>
                    </td>
                    <td><?php echo anchor('admin/product-edit/'.$data->id, 'Edit'); ?></td>
                </tr>
                <?php
						}
					}else {
				?>
            	<tr class="td_value">
                	<td colspan="8" style="text-align:center;">No data</td>
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
