<script type="text/javascript">
	var checkNumFlag = 1;
	$(document).ready(function(e) {
		$('#form1').validate({
			rules: {
				category: {
					required: true
				}
			}
		});
        $('#btn_save').bind("click", null, function(){
			
			$('.size_container').find('.cls_size_name').each(function(index, element) {
				if($(element).val().length <= 0){

					checkNumFlag = 0;
					$(element).focus();
					alert("Please enter a Topping name.");					
					return false;
				} else {
					checkNumFlag = 1;
				}
			});
			if(checkNumFlag == 1){
				$('.size_container').find('.cls_price').each(function(index, element) {
					if(isNaN($(element).val()) || ($(element).val().length <= 0) || ($(element).val() < 0)){
						checkNumFlag = 0;
						$(element).focus();
						alert("Please enter a valid number.");					
						return false;
					} else {
						checkNumFlag = 1;
					}
				});
			}
			if(checkNumFlag == 1)
				$('#form1').submit();
			});
		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/topping'?>";
		});
		
        $('#btn_add').click(function()
		{
			var newly_element = $('.size_container').append("<div class=\"size_block\"><div class=\"size_subblock\"><div class=\"size_left\"><div><div class=\"size_name_column\">name : </div><div class=\"size_value_column\"><input type=\"text\" name=\"topping_name[]\" class=\"cls_size_name\" /></div><div class=\"clearboth\"></div></div><div><div class=\"size_name_column\">Price : </div><div class=\"size_value_column\"><input type=\"text\" class=\"cls_price\" name=\"topping_price[]\" /></div><div class=\"clearboth\"></div></div></div><div class=\"size_right\"><a href=\"javascript:void(0);\" class=\"size_close\" onclick='test(this);'>close</a></div><div class=\"clearboth\"></div></div></div>");

		});
		$('#id_category').change(function(e) {

			$('.size_container').empty();

			$.post("<?php echo site_url('ajax/get_size_for_topping');?>", {category_id:$(this).val()}, function(result){
				if (result != "") {
				var obj = $.parseJSON(result);

				$.each(obj, function(i, tweet){
					var newly_element = $('.size_container').append("<div class=\"size_block\"><div class=\"size_subblock\"><div class=\"size_left\"><div><div class=\"size_name_column\">Size name : </div><div class=\"size_value_column\">" + tweet.name + "</div><input type=\"hidden\" name=\"size_name[]\" value=\"" + tweet.id + "\" /><div class=\"clearboth\"></div></div><div><div class=\"size_name_column\">Topping name : </div><div class=\"size_value_column\"><input type=\"text\" name=\"topping_name[]\" class=\"cls_size_name\" /></div><div class=\"clearboth\"></div></div><div><div class=\"size_name_column\">Price : </div><div class=\"size_value_column\"><input type=\"text\" class=\"cls_price\" name=\"topping_price[]\" /></div><div class=\"clearboth\"></div></div></div><div class=\"size_right\"><a href=\"javascript:void(0);\" class=\"size_close\" onclick='test(this);'><!--close--></a></div><div class=\"clearboth\"></div></div></div>");

				});
				}
			});
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
	echo form_open_multipart('admin/topping-new/', array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'add');
	?>
    	<div>
	    	<h1>Add Topping Price</h1>
		</div>
		<div style="margin-left:15px;">
            <div style="margin-right:50px; float:right;">
            	<input type="button" value="Save" id="btn_save" />
            	<input type="button" value="Back" id="btn_cancel" class="btn" />
            </div>
            <div>
                <table width="76%" class="list">
                    <tr>
                        <td width="26%" class="td_header">Category</td>
                        <td width="74%" class="td_value">
                            <?php
							$category_query = get_category($this->session->userdata('SESS_STORE_ID'));
							$arr_options = array(0 => '(Select Category)');
							foreach ($category_query->result() as $row) {
								$arr_options[$row->id] = $row->name;
							}
							echo form_dropdown('category', $arr_options, '', 'id="id_category"');
							?>
                        </td>
                    </tr>
<!--
                    <tr>
                        <td class="td_header">
                            Size
                        </td>
                        <td class="td_value">
                        	<select id="id_size" name="size">
                        <?php
//							if($size != ""){
//								$size_query = "select * from oos_size where id_category=" . $category . " order by id";
//								$size_result = mysql_query($size_query);
//								while($size_data = mysql_fetch_object($size_result)){
//									if($size_data->id == $size){
						?>
                            <option value="<?php //echo $size_data->id; ?>" selected="selected"><?php //echo $size_data->name ?></option>
                        <?php
									//} else {
						?>
							<option value="<?php// echo $size_data->id; ?>"><?php //echo $size_data->name ?></option>
						<?php
									//}
								//}
							//}
						?>
							</select>
                        </td>
                    </tr>
-->
               </table>
			</div>
		<div class="size_scope" style="width:93%;">
            <div class="header_3">
                Topping
            </div>
            <div style="float:right; margin-right:0px;">
                <!--<input type="button" value="Add" id="btn_add" /> -->
            </div>
            <div style="clear:both;"></div>
<!---------from here size container-------->
            <div class="size_container">
           
            
            </div>
<!----------to above size container end--->
		</div>
        </div>
    <!-- end .content -->
    <?php echo form_close();?>
	</div>
