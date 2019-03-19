<?php
//	if(isset($_REQUEST[mode]) && ($_REQUEST[mode] == "Add")){
//		if(isset($_REQUEST[id_category]))
//			$_SESSION[SESS_CATEGORY_ID] = $_REQUEST[id_category];
//		else
//			$_SESSION[SESS_CATEGORY_ID] = -1;
//
//	}
//
//	$name = "";
//	$description = "";
//	$code = "";
//	$is_active = "Y";
//	$seo_name = "";
//	$is_offer = "N";
//	$category_id = -1;
//
//
//	if($_REQUEST[mode] == "Add"){
//		$temp_product_id = -1;
//		
//		if($_REQUEST[submode] == "Insert"){
//			$query = "insert into oos_product(id_category, name, description, is_active, is_offer, created_on_date, seo_name, code, store_id) values(" . $_REQUEST[id_category] . ", '" . $_REQUEST[name] . "', '" . $_REQUEST[description] . "', '" . $_REQUEST[is_active] . "', '" . $_REQUEST[is_offer] . "', now(), '" . $_REQUEST[seo_name] . "', '" . $_REQUEST[code] . "', $_SESSION[SESS_STORE_ID])";
////echo $query;
//			$result = mysql_query($query);
//			
//			if($result){
//				if(isset($_REQUEST[size_id])){
//					for($i=0;$i<count($_REQUEST[size_id]);$i++){
//						$query = "insert into oos_price(price, id_product, id_size, store_id) values(" . $_REQUEST[price][$i] . ", " . get_product_last_id() .", " . $_REQUEST[size_id][$i] . ", $_SESSION[SESS_STORE_ID])";
//						$result = mysql_query($query);
//					}
//				}
//			}
//			$process_flag = 1;
//		}
//		if($_SESSION[SESS_CATEGORY_ID] == -1){
//			$query = "select id from oos_category where store_id=$_SESSION[SESS_STORE_ID] order by id";
//			$result = mysql_query($query);
//			if(mysql_num_rows($result)){
//				$category_id = mysql_result($result, 0, 0);
//			}
//		} else {
//			$category_id = $_SESSION[SESS_CATEGORY_ID];
//		}
//	} else if($_REQUEST[mode] == "Edit"){
//		
//		$temp_product_id = $_REQUEST[product_id];
//		
//		
//		if(isset($_REQUEST[submode]) && $_REQUEST[submode] == "Update"){
//		// in case update
//			$category_id = $_REQUEST[id_category];
//			$query = "update oos_product set id_category=$_REQUEST[id_category],
//											name='$_REQUEST[name]',
//											description='$_REQUEST[description]',
//											is_active='$_REQUEST[is_active]',
//											is_offer='$_REQUEST[is_offer]',
//											code='$_REQUEST[code]',
//											seo_name='$_REQUEST[seo_name]' where id=$_REQUEST[product_id]";
////echo $query;
//			$result = mysql_query($query);
//			if($result){
//				$query = "delete from oos_price where id_product=$_REQUEST[product_id]";
//				$result = mysql_query($query);
//				if(isset($_REQUEST[size_id])){
//					for($i=0;$i<count($_REQUEST[size_id]);$i++){
//						$query = "insert into oos_price(price, id_product, id_size, store_id) values(" . $_REQUEST[price][$i] . ", " . $_REQUEST[product_id] .", " . $_REQUEST[size_id][$i] . ", $_SESSION[SESS_STORE_ID])";
//						$result = mysql_query($query);
//					}
//				}
//			}
//			$process_flag = 1;
//		}
//		$query = "select * from oos_product where id=$_REQUEST[product_id]";
//		$result = mysql_query($query);
//		if(mysql_num_rows($result))
//		{
//			$data = mysql_fetch_object($result);
//			$name = $data->name;
//			$description = $data->description;
//			$code = $data->code;
//			$is_active = $data->is_active;
//			$seo_name = $data->seo_name;
//			$is_offer = $data->is_offer;
//			$category_id = $data->id_category;
//		} else {
//			header('Location: ' . $_SERVER[HTTP_REFERER]);
//		}
//	}
?>

<script type="text/javascript">
	var product_id = <?php echo $contents->id?>;
	var checkNumFlag = 1;
	$(document).ready(function(e) {
		
		$('#form1').validate({
			rules: {
				name: {
					required: true
				},
				code:{
					required: true
				}
			}
		});

        $('#btn_save').bind("click", null, function(){

			$('#size_container').find('.cls_price').each(function(index, element) {
				if(isNaN($(element).val()) || ($(element).val().length <= 0) || ($(element).val() < 0)){

					checkNumFlag = 0;
					$(element).focus();
					alert("Please enter a valid number.");					
					return false;
				} else {
					checkNumFlag = 1;
				}
			});
			
			if(checkNumFlag == 1)
				$('#form1').submit();

		});
		
		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo base_url('admin/product');?>";
		});
		
		$('#id_category').change(function(e) {
			$('#size_container').empty();
//alert($(this).val());
/*
		$.getJSON("get_size.php", {category_id:$(this).val(), product_id:product_id}, function(data){
			$.each(data, function(i, tweet){
				alert(tweet.name);
			});
		});
*/
			$.post("<?php echo site_url().'ajax/get_size_for_category'?>", {category_id:$(this).val(), product_id:product_id}, function(result){
//alert(result);
				if(result == "]"){
					// in case no size
					$('#size_container').append("<div class=\"size_subblock\"><div>");
					$('#size_container').append("<div class=\"size_value_column\">");
					$('#size_container').append("<input type=\"input\" id=\"price_id_0\" class=\"cls_price\" value=\"0\" name=\"price[]\" />");
					$('#size_container').append("<input type=\"hidden\" value=\"-1\" name=\"size_id[]\" />");
					$('#size_container').append("</div>");
					$('#size_container').append("<div class=\"clearboth\"></div>");
				}
				
				var obj = $.parseJSON(result);

				if(obj.length > 1){
					$.each(obj, function(i, tweet){
						$('#size_container').append("<div class=\"size_subblock\"><div>");
						$('#size_container').append("<div class=\"size_name_column\">" + tweet.name + "</div>");
						$('#size_container').append("<div class=\"size_value_column\">");
						$('#size_container').append("<input type=\"input\" id=\"price_id_" + i + "\" class=\"cls_price\" value=\"" + tweet.price + "\" name=\"price[]\" />");
						$('#size_container').append("<input type=\"hidden\" value=\"" + tweet.id + "\" name=\"size_id[]\" />");
						$('#size_container').append("</div>");
						$('#size_container').append("<div class=\"clearboth\"></div>");
						$('#size_container').append("</div><div class=\"clearboth\"></div></div>");
					});
					
				} else {
					$.each(obj, function(i, tweet){
						$('#size_container').append("<div class=\"size_subblock\"><div>");
						$('#size_container').append("<div class=\"size_value_column\">");
						$('#size_container').append("<input type=\"input\" id=\"price_id_" + i + "\" class=\"cls_price\" value=\"" + tweet.price + "\" name=\"price[]\" />");
						$('#size_container').append("<input type=\"hidden\" value=\"" + tweet.id + "\" name=\"size_id[]\" />");
						$('#size_container').append("</div>");
						$('#size_container').append("<div class=\"clearboth\"></div>");
					});
				}
			});
        });
		
	})
	
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open_multipart('admin/product-edit/'.$contents->id, array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'update');
	?>
    	<div>
	    	<h1>Edit Product</h1>
		</div>
		<div style="margin-left:15px;">
            <div style="margin-right:50px; float:right;">
            	<input type="button" value="Save" id="btn_save" />
            	<input type="button" value="Back" id="btn_cancel" class="btn" />
            </div>
        	<table width="100%" class="list">
                <tr>
                    <td valign="top" class="td_header"> Category   </td>
                    <td class="td_value">
                        <?php
						$category_query = get_category($this->session->userdata('SESS_STORE_ID'));
						$arr_options = array(0 => 'Select');
						foreach ($category_query->result() as $row) {
							$arr_options[$row->id] = $row->name;
						}
						echo form_dropdown('id_category', $arr_options, $contents->id_category, 'id="id_category"');
						?>
                  </td>
			  </tr>
                <tr>
                  	<td valign="top" class="td_header"> Price  </td>
                  	<td class="td_value">
                        <div id="size_container">
<!-------below price area------>
						<?php
						//$query = "select * from oos_size where id_category=$category_id";
						//$result = mysql_query($query) or die(mysql_error());
						$result = get_size($contents->id_category);
						if ($result->num_rows() > 0)
						{
						//if(mysql_num_rows($result)){
							$no = 0;
							foreach ($result->result() as $data)
							{
							//while($data = mysql_fetch_object($result)){
								$price_data = get_price($contents->id, $data->id);
								//$sub_query = "select price from oos_price where id_product=" . $temp_product_id . " and id_size=" . $data->id;
								//$sub_result = mysql_query($sub_query);
								if ($price_data->num_rows() > 0) {
									$row = $price_data->result();
									$price = $row[0]->price;
								//}
								//if(mysql_num_rows($sub_result)){
									//$price = mysql_result($sub_result, 0, 0);
									echo "<div class=\"size_subblock\"><div><div class=\"size_name_column\">" . $data->name . "</div><div class=\"size_value_column\"><input type=\"input\" id=\"price_id_".$no."\" value=\"" . $price . "\" class=\"cls_price\" name=\"price[]\" /><input type=\"hidden\" value=\"" . $data->id . "\" name=\"size_id[]\" /></div><div class=\"clearboth\"></div></div><div class=\"clearboth\"></div></div>";
								} else {
									echo "<div class=\"size_subblock\"><div><div class=\"size_name_column\">" . $data->name . "</div><div class=\"size_value_column\"><input type=\"input\" value=\"0\" id=\"price_id_".$no."\" class=\"cls_price\" name=\"price[]\" /><input type=\"hidden\" value=\"" . $data->id . "\" name=\"size_id[]\" /></div><div class=\"clearboth\"></div></div><div class=\"clearboth\"></div></div>";
								}
								$no++;
							}
						} else {
							$price_data = get_price($contents->id, -1);
								//$sub_query = "select price from oos_price where id_product=" . $temp_product_id . " and id_size=-1";
								//$sub_result = mysql_query($sub_query);
								if ($price_data->num_rows() > 0) {
									$row = $price_data->result();
									$price = $row[0]->price;
								//if(mysql_num_rows($sub_result)){
									//$price = mysql_result($sub_result, 0, 0);
									echo "<div class=\"size_subblock\"><div class=\"size_value_column\"></div><input type=\"input\" value=\"" . $price . "\" class=\"cls_price\" id=\"price_id_0\" name=\"price[]\" /><input type=\"hidden\" value=\"-1\" name=\"size_id[]\" /></div><div class=\"clearboth\"></div>";
								} else {
									echo "<div class=\"size_subblock\"><div class=\"size_value_column\"></div><input type=\"input\" value=\"0\" name=\"price[]\" id=\"price_id_0\" class=\"cls_price\" /><input type=\"hidden\" value=\"-1\" name=\"size_id[]\" /></div><div class=\"clearboth\"></div>";
								}
						}
						?>
<!----------price area end ----------->
                      </div>
               	  </td>
                </tr>
				<tr>
               	  <td width="32%" class="td_header"> Product Name  </td>
               	  <td width="68%" class="td_value"><?php echo form_input('name', $contents->name, 'class="key_input" id="name"');?></td>
                </tr>
				<tr>
               	  <td width="32%" class="td_header"> Product Code  </td>
               	  <td width="68%" class="td_value"><?php echo form_input('code', $contents->code, 'class="key_input" id="code"');?></td>
                </tr>
                <tr>
               	  <td width="32%" class="td_header"> Product Description  </td>
               	  <td width="68%" class="td_value"><?php echo form_textarea('description', $contents->description, ' id="description" cols="30" rows="4"');?></td>
                </tr>

                <tr>
                  <td class="td_header"> Is offered  </td>
                  <td class="td_value">
                  	<?php echo form_radio('is_offer', 'Y', ($contents->is_offer == 'Y'));?>Yes
                  	<?php echo form_radio('is_offer', 'N', ($contents->is_offer == 'N'));?>No
				</td>
                </tr>
                <tr>
				  <td class="td_header"> Status  </td>
               	  <td class="td_value">
                  	<?php echo form_radio('is_active', 'Y', ($contents->is_active == 'Y'));?>Enable
                  	<?php echo form_radio('is_active', 'N', ($contents->is_active == 'N'));?>Disable
               	  </td>
				</tr>
                <tr>
				  <td class="td_header"> SEO KEY  </td>
               	  <td class="td_value"><?php echo form_input('seo_name', $contents->seo_name, 'class="key_input" id="seo_name"');?></td>
				</tr>
            </table>
        </div>
    <!-- end .content -->
    <?php echo form_close();?>
	</div>
