<script type="text/javascript">
	$(document).ready(function(e) {
		$('#form1').validate({
			rules: {
				name: {
					required: true
				}
			}
		});
        $('#btn_save').bind("click", null, function(){
			var checkNumFlag = 1;

			$('.size_container').find('.cls_size_name').each(function(index, element) {
				if($.trim($(element).val()).length <= 0){
					checkNumFlag = 0;
					$(element).focus();
					alert("Please enter a product size name.");
					return false;
				} else {
					checkNumFlag = 1;
				}
			});
			if(checkNumFlag == 1)
				$('#form1').submit();
		});
		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo site_url('admin/category');?>";
		});
        $('#btn_add').click(function()
        		{
        			var newly_element = $('.size_container').append(
        					'<div class="size_block"><div class="size_subblock">' + 
        					'<div class="size_left"><div><div class="size_name_column">name : </div>' + 
        					'<div class="size_value_column">' + 
        					'<input type="text" name="size_name[]" class="cls_size_name" /></div>' + 
        					'<div class="clearboth"></div></div>' + 
        					'<div><div class="size_name_column">description : </div>' + 
        					'<div class="size_value_column"><input type="text" name="size_desc[]" /></div>' + 
        					'<div class="clearboth"></div></div></div>	' + 
        					'<div class="size_left"><div><div class="size_value_column">is offer : </div>' + 
        					'<div class="size_value_column"><select name="is_offer[]">' + 
        					'<option value="1">Yes</option>	' + 
        					'<option value="0">No</option></select></div>' + 
        					'<div class="clearboth"></div></div></div>' + 
        					'<div class="size_right"><a href="javascript:void(0);" class="size_close" onclick="test(this);">close</a></div>' + 
        					'<div class="clearboth"></div></div></div>');
        		});
    });
	
	function test(obj)
	{
		$(obj).parent().parent().parent().remove();
	}
</script>
	<div class="content">
		<?php	
		echo put_alert($process_flag);
		echo form_open_multipart('admin/category-edit/'.$contents->id, array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
		echo form_hidden('mode', 'update');
		?>
    	<div>
		    <h1>Edit Category</h1>
        </div>
    	<div style="margin-left:15px;">
        	<div style="float:right; margin-right:50px;">
            	<input type="button" value="Save" id="btn_save" />
            	<input type="button" value="Back" id="btn_cancel" class="btn" />
            </div>
            <div class="clearboth">
            </div>
        	<div>
	        	<table class="list">
                	<tr>
                   	  <td width="29%" class="td_header">Category Name</td>
                		<td width="71%" class="td_value"><?php echo form_input('name', $contents->name);?></td>
                	<tr>
                   	  <td width="29%" class="td_header">Seo Key</td>
                		<td width="71%" class="td_value"><?php echo form_input('seo_name', $contents->seo_name);?></td>
					</tr>
               	  	<tr>
                   	  <td align="right" valign="top" class="td_header">Category Description</td>
		                <td class="td_value"><?php echo form_textarea('description', $contents->description, ' cols="25" rows="5" id="description"');?></td>
					</tr>
                	<tr>
                   	  <td align="right" valign="top" class="td_header">Is Active</td>
		                <td class="td_value">
                        <?php
                        	echo form_radio('is_active', 'Y', ($contents->is_active == 'Y')).' Yes ';
                        	echo form_radio('is_active', 'N', ($contents->is_active == 'N')).' No';
						?>
       				  </td>
					</tr>
                	<tr>
                   	  <td class="td_header">Category Image</td>
                        <td class="td_value"><?php echo form_upload('uploaded');?></td>
	           		</tr>
                	<tr>
                   	  	<td colspan="2"><?php echo img(array('src' => 'upload/'.$contents->image_url, 'border' => 0));?></td>
	           		</tr>
				</table>
			</div>
        </div>

		<div style="height:10px;">
        </div>
<!------ size scope------->
		<div class="size_scope" style="width:93%;">
            <div class="header_3">Edit Size</div>
            <div style="float:right; margin-right:0px;">
            	<?php echo form_button('btn_add', 'Add', 'id="btn_add"');?>
            </div>
            <div style="clear:both;"></div>
<!---------from here size container-------->
            <div class="size_container">
		<?php
		$ids = "";
		$size_data = get_size($contents->id);
		if ($size_data->num_rows() > 0)
		{
			foreach ($size_data->result() as $data)
			{
				$ids .= $data->id . ",";
		?>
				<div class="size_block">
                	<div class="size_subblock">
                    	<div class="size_left">
                        	<div>
                            	<div class="size_name_column">name : </div>
                                <div class="size_value_column">
                                	<input type="text" value="<?php echo $data->name; ?>" class="cls_size_name" name="size_name[]" />
                                    <input type="hidden" value="<?php echo $data->id; ?>" name="size_id[]" />
                                </div>
                            	<div class="clearboth"></div>
                    		</div>
                    		<div>
                    			<div class="size_name_column">description : </div>
                        		<div class="size_value_column">
                        			<input type="text" name="size_desc[]" value="<?php echo $data->desc; ?>" />
                        		</div>
                    			<div class="clearboth"></div>
                			</div>
            			</div>
                    	<div class="size_left">
                        	<div>
                            	<div class="size_value_column">is offer : </div>
                                <div class="size_value_column">
                                	<select name="is_offer[]">
										<option value="1" <?php if($data->is_offer==1) echo "selected"; ?>>Yes</option>
										<option value="0" <?php if($data->is_offer==0) echo "selected"; ?>>No</option>
									</select>
                                </div>
                            	<div class="clearboth"></div>
                    		</div>
            			</div>
            			<div class="size_right">
            				<a href="javascript:void(0);" class="size_close" onclick='test(this);'>
                				close
                			</a>
	            		</div>
    	    			<div class="clearboth"></div>
    				</div>
				</div>
		<?php
			}
			echo form_hidden('ids', $ids);
		}
		?>
            </div>
<!----------to above size container end--->
		</div>
    	<?php echo form_close();?>
    </div>
