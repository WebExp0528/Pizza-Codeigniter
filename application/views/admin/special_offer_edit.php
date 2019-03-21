<script type="text/javascript">
	var checkFlag = 1;
	var category_count = 0;
	$(document).ready(function(e) {
		
		$('#btn_save').bind("click", null, function(){
			$('.cls_category_id').each(function(index, element) {
				category_count++;
			});

			if(category_count > 0){
				var mode_val = $("input[name=mode]").val();
				if(mode_val == "Add") $("input[name=action]").val("insert");
				else $("input[name=action]").val("update");
				$('#form1').submit();
			}
			else
				alert("You must select at least one category.");
		});

		$('#btn_add').click(function(){
			var category_name = $('#category_name :selected').text();
			var category_id = $('#category_name').val();
			
//			$('#category_container .category_left').each(function(index, element) {
//				if($(element).html() == category_name){
//					checkFlag = 0;
//					return false;
//				} else {
//					checkFlag = 1;
//				}
//			});
//			if(checkFlag == 1){
				$('#category_container').append("<div class=\"category_subcontainer\"><div class=\"category_obj\"><div class=\"category_left\">" + category_name + "</div><input type=\"hidden\" value=\"" + category_id + "\" name=\"category_id[]\" class=\"cls_category_id\" /><div class=\"category_right\"><a href=\"javascript:void(0);\" class=\"size_close\" onclick='remove(this);'>delete</a></div><div style=\"clear:both;\"></div></div></div>");
//			} else {
//				alert("You already have selected");
//			}
		});
		
		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/special_offer'?>";
		});

		$('#form1').validate({
			rules: {
				offer_name: {
					required: true
				},
				offer_code: {
					required: true
				}
			}
		});
		
	});
	
	function remove(obj)
	{
		$(obj).parent().parent().parent().remove();
		checkFlag = 1;
	}
	
</script>
</head>
	<div class="content">
<?php
	echo put_alert($process_flag);
	echo form_open('admin/specialoffer-edit/'.$contents->id, array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'update');
?>
		<div>
			<h1>Add Special Offer</h1>
		</div>
		<div style="margin-left:10px;">
			<div style="float:right; margin-right:50px;">
				<input type="button" value="Save" id="btn_save" />
				<input type="button" value="Back" id="btn_cancel" class="btn" />
			</div>
			<div>
				<table class="list">
					<tr>
						<td width="30%" class="td_header">Special Offer Name 
						</td>
						<td width="70%" class="td_value">
							<input name="offer_name" type="text" value="<?php echo $contents->offer_name;?>" />
						</td>
					</tr>
					<tr>
						<td width="30%" class="td_header">Code
						</td>
						<td width="70%" class="td_value">
							<input name="offer_code" type="text" value="<?php echo $contents->offer_code;?>" />
						</td>
					</tr>
					<tr>
						<td width="30%" class="td_header">Price
						</td>
						<td width="70%" class="td_value">
							<input name="offer_price" type="text" value="<?php echo $contents->offer_price;?>" />
						</td>
					</tr>
					<tr>
						<td width="30%" class="td_header">Category Name 
						</td>
						<td width="70%" class="td_value">
							<?php 
							$status = get_category($this->session->userdata('SESS_STORE_ID'));
							$arr_options = array(0 => 'Select');
							foreach ($status->result() as $row) {
								$arr_options[$row->id] = $row->name;
							}
							echo form_dropdown('category_name', $arr_options, '', 'id="category_name"');
							?>
							<input type="button" value="Add" name="btn_add" id="btn_add" />
						</td>
					</tr>
					<tr>
						<td class="td_header">&nbsp;</td>
						<td valign="top" class="td_value">
							<div id="category_container">
							
							
							<?php
							$query = "SELECT oos_specialoffer_contents.*, oos_category.name as cate_name";
							$query .= " FROM oos_specialoffer_contents LEFT JOIN oos_category ON oos_category.id=id_category";
							$query .= " WHERE id_offer=" . $contents->id;
							$query .= " ORDER By id";
							$result = $this->db->query($query);
							if ($result->num_rows() > 0)
							{
								//$result = mysql_query($query);
								foreach ($result->result() as $data)
								{
								//while($result && ($data = mysql_fetch_object($result))){
							?>
								<div class="category_subcontainer">
									<div class="category_obj">
										<div class="category_left"><?php echo $data->cate_name; ?></div>
										<input type="hidden" value="<?php echo $data->id_category?>" name="category_id[]" class="cls_category_id" />
										<div class="category_right">
											<a href="javascript:void(0);" class="size_close" onclick="remove(this);">delete</a>
										</div>
										<div style="clear:both;"></div>
									</div>
								</div>
							<?php
								}
								}
							?>

							</div>
						</td>
					</tr>
					<tr>
						<td width="30%" class="td_header">Extra product</td>
						<td width="70%" class="td_value">
							<textarea name="extra_product" rows="5" cols="50" style="overflow:auto;"><?php echo $contents->extra_product;?></textarea>
						</td>
					</tr>
				</table>
			</div>
		</div>
	<?php echo form_close();?>
	</div>
  <!-- end .container -->
