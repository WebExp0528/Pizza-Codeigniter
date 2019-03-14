<script type="text/javascript">
	$(document).ready(function(e) {
        $('#btn_insert').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/category-new'?>";
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
		    <h1>Category</h1>
        </div>
      	<div style="margin-left:15px;">
            <!--input type="button" id="btn_insert" value="Insert" />
            <input type="button" id="btn_delete" value="Delete" /-->
            <a href="#"><?php echo img(array('src' => 'images/admin/new.gif', 'id' => 'btn_insert', 'border' => 0, 'title' => 'New Product'));?></a>
            <a href="#"><?php echo img(array('src' => 'images/admin/delete.gif', 'id' => 'btn_delete', 'border' => 0, 'title' => 'Delete Product'));?></a>
            <a href="#"><?php echo img(array('src' => 'images/admin/copy.gif', 'id' => 'btn_copy', 'border' => 0, 'title' => 'Copy Product'));?></a>
      	</div>
		<div style="margin-left:15px;">
        	<?php echo form_open('admin/category', array('method' => 'post', 'id' => 'form1'));?>
        	<table class="list" id="tbl1">
            	<tr style="background-color:#E7EFEF;">
                	<td width="7%">
                    	<input type="checkbox" id="chk" />
                    </td>
                	<td width="50%">
                    	Category Name
                    </td>
                	<td width="20%">
                    	Date Added
                    </td>
                	<td width="11%">
                    	Actived
                    </td>
                	<td width="12%">
                    	Action
                    </td>
                </tr>
				<?php
					if ($contents->num_rows > 0)
					{
						foreach ($contents->result() as $data)
						{
				?>
            	<tr class="td_value">
                	<td><?php echo form_checkbox('chks[]', $data->id, false, 'class="cls_chk"');?></td>
                	<td><?php echo $data->name ?></td>
                	<td><?php echo $data->created_on_date ?></td>
                	<td><?php if($data->is_active == "Y") { echo "Active"; } else { echo "Inactive"; }?></td>
                	<td>[<?php echo anchor('admin/category-edit/'.$data->id, 'Edit'); ?>]</td>
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
