<script type="text/javascript">
	$(document).ready(function(e) {
        $('#btn_insert').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/topping-new'?>";
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
		    <h1>Topping Price</h1>
        </div>
      	<div style="margin-left:15px;">
            <a href="#" class="action" id="btn_insert"><?php echo img(array('src' => 'images/admin/new.gif', 'border' => 0, 'title' => 'New Topping'));?></a>
            <a href="#" class="action" id="btn_delete"><?php echo img(array('src' => 'images/admin/delete.gif', 'border' => 0, 'title' => 'Delete Topping'));?></a>
            <!--<a href="#" class="action" id="btn_copy"><?php echo img(array('src' => 'images/admin/copy.gif', 'border' => 0, 'title' => 'Copy Topping'));?>Copy</a>-->
      	</div>
		<div style="margin-left:15px;">
        	<form method="post" id="form1">
        	<table class="list" id="tbl1">
            	<tr style="background-color:#E7EFEF;">
                	<td width="5%"><input type="checkbox" id="chk" /></td>
                	<td width="27%">Category Name</td>
                	<td width="24%">Size</td>
                	<td width="18%">Topping Name</td>
                	<td width="15%">Price</td>
                	<td width="11%">Action</td>
                </tr>
			<?php
				$category_name = "";
				if ($contents->num_rows() > 0)
				{
					foreach ($contents->result() as $data)
					{
			?>
            	<tr class="td_value">
                	<td><?php echo form_checkbox('chks[]', $data->id, false, 'class="cls_chk"');?></td>
                	<td><?php
							if($category_name != $data->cate_name){
		                        echo $data->cate_name;
								$category_name = $data->cate_name;
							} else {
								echo "&nbsp;";
							}
						?>
                    </td>
                	<td><?php echo $data->size_name ?></td>
                	<td><?php echo $data->topping_name ?></td>
                	<td><?php echo $data->price_add ?></td>
                	<td>[<?php echo anchor('admin/topping-edit/'.$data->id, 'Edit'); ?>]</td>
                </tr>
			<?php
					}
				} else {
			?>
            	<tr class="td_value">
                	<td colspan="6" style="text-align:center;">No data</td>
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
