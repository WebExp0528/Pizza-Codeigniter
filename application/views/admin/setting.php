<script type="text/javascript">
	$(document).ready(function(e) {
		$('#btn_save').bind("click", null, function(){
			$('#form1').submit();
		});
		$('#region_name').change(function(e) {
			$('#id_city').empty();
			$('#id_postcode').empty();			
			$('#id_city').append('<option value="">(Select City)</option>');
			$('#id_postcode').append('<option value="">(Select Postcode)</option>');

			$.post("<?php echo site_url().'ajax/get_city_name'?>", {region_name:$(this).val()}, function(result){

				var obj = $.parseJSON(result);

				$.each(obj, function(i, tweet){
					$('#id_city').append('<option value="' + tweet.name + '">' + tweet.name + '</option>');
				});
				
			});
		});

		$('#id_city').change(function(e) {
			$('#id_postcode').empty();
			$('#id_postcode').append('<option value="">(Select Postcode)</option>');
//alert($(this).val());
			$.post("<?php echo site_url().'ajax/get_postcode'?>", {city_name:$(this).val()}, function(result){
//alert(result);
				var obj = $.parseJSON(result);

				$.each(obj, function(i, tweet){
					$('#id_postcode').append('<option value="' + tweet.name + '">' + tweet.name + '</option>');
				});
				
			});
		});
		$('#form1').validate({
			rules: {
				email: {
					required: true,
					email:true
				},
				address: {
					required: true
				},
				shop_name: {
					required: true
				},
				region_name: {
					required: true,
				},
				city: {
					required: true,
				},
				postcode: {
					required: true,
					number:true
				},
				telephone: {
					required: true
				},
				main_key: {
					required: true
				},
				min_price: {
					required: true,
					number:true
				},
				imprint: {
					required: true
				},
				fax: {
					required: true
				}
			}
		});

	});
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open_multipart('admin/setting', array('method' => 'post', 'id' => 'form1'));
	?>
		<div>
			<h1>Setting</h1>
		</div>
		<div style="margin-left:10px;">
			<div style="float:right; margin-right:50px;">
				<input type="button" value="Save" id="btn_save" />
			</div>
			<div class="clearboth">
			</div>
			<div>
				<table class="list">
					<tr>
						<td width="24%" class="td_header">Shop Name </td>
						<td width="76%" class="td_value"><?php echo form_input('shop_name', $shop_name);?></td>
					</tr>
					<tr>
						<td width="24%" class="td_header">Address </td>
						<td width="76%" class="td_value"><?php echo form_input('address', $address);?></td>
					</tr>
					<tr>
						<td width="24%" class="td_header">Imprint </td>
						<td width="76%" class="td_value"><?php echo form_textarea('imprint', $imprint, 'cols="70" rows="10"');?></td>
					</tr>
					<tr>
						<td class="td_header">
							Region Name
						</td>
						<td class="td_value">
							<!--input name="city" type="text" value="<?php echo $city; ?>" /-->
							<?php
								$result = get_region();
								$arr_options = array(0 => 'Select');
								foreach ($result->result() as $row) {
									$arr_options[$row->region_name] = $row->region_name;
								}
								echo form_dropdown('region_name', $arr_options, $region_name, 'id="region_name"');
							?>
						</td>
					</tr>

					<tr>
						<td class="td_header">
							City 
						</td>
						<td class="td_value">
							<!--input name="city" type="text" value="<?php echo $city; ?>" /-->
							<?php
								$result = get_cityname_by_region($region_name);
								$arr_options = array(0 => '(Select City)');
								foreach ($result->result() as $row) {
									$arr_options[$row->city_name] = $row->city_name;
								}
								echo form_dropdown('city', $arr_options, $city, 'id="id_city"');
							?>
						</td>
					</tr>
					<tr>
						<td class="td_header">
							Post Code 
						</td>
						<td class="td_value">
<!--							<input name="postcode" type="text" id="postcode" value="<?php echo $postcode; ?>" /> -->
							<?php
								$result = get_postcode_by_cityname($city);
								$arr_options = array(0 => '(Select Postcode)');
								foreach ($result->result() as $row) {
									$arr_options[$row->postcode] = $row->postcode;
								}
								echo form_dropdown('postcode', $arr_options, $postcode, 'id="id_postcode"');
							?>

						</td>
					</tr>
					<tr>
						<td class="td_header">
							Email Address 
						</td>
						<td class="td_value">
							<input name="email" type="text" id="email" value="<?php echo $email; ?>" />
						</td>
					</tr>
					<tr>
						<td class="td_header">
							Telephone Number 
						</td>
						<td class="td_value">
							<input name="telephone" type="text" id="telephone" value="<?php echo $telephone; ?>" />
						</td>
					</tr>
					<tr>
						<td class="td_header">
							Main Key 
						</td>
						<td class="td_value">
							<input name="main_key" type="text" id="main_key" value="<?php echo $main_key; ?>" />
						</td>
					</tr>
					<tr>
						<td class="td_header">
							Minimum order value 
						</td>
						<td class="td_value">
							<input name="min_price" type="text" id="min_price" value="<?php echo $min_price; ?>" />
						</td>
					</tr>
					<tr>
						<td class="td_header">
							Fax Number 
						</td>
						<td class="td_value">
							<input name="fax" type="text" id="fax" value="<?php echo $fax; ?>" />
						</td>
					</tr>
					<tr>
						<td class="td_header">
							StyleSheet CSS 
						</td>
						<td class="td_value">
							<select name="css_file">
								<?php 
								for($i=0;$i<count($css_files);$i++){
								?>
								<option value="<?php echo $css_files[$i]['name'];?>" <?php if($style_url == $css_files[$i]['name']) echo "selected";?>><?php echo $css_files[$i]['name'];?></option>
								<?php }?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="td_header">
							Shop logo
						</td>
						<td class="td_value">
							<input type="file" name="logo_uploaded" />
						</td>
					</tr>
					<tr>
						<td class="td_header">&nbsp;
							
						</td>
						<td class="td_value">
							&nbsp;
							<?php
								if($image_url != ""){
									echo img(array('src' => '/upload/shop_logo/'.$image_url, 'border' => 0));
								}
							?>
						</td>
					</tr>
					<tr>
						<td valign="top" class="td_header">
							Opening Time 
						</td>
						<td class="td_value">
							<table style="border:1px solid; border-color:#CCC" cellpadding="0" cellspacing="0">
							<?php
								$weekday = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');

								$arr_hour = array('00:00','00:30','01:00','01:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30');
											
								$arr_default_time = array(array('12:00', '23:00', '00:00', '00:00'), array('11:00', '14:30', '17:00', '23:00'), array('11:00', '14:30', '17:00', '23:00'), array('11:00', '14:30', '17:00', '23:00'), array('11:00', '14:30', '17:00', '23:00'), array('11:00', '14:30', '17:00', '23:00'), array('12:00', '23:00', '00:00', '00:00'));

								for($i=0;$i<=6;$i++){
							?>
								<tr>
									<td class="week_header">
										<?php echo $weekday[$i]; ?>
								  	</td>
								  	<td>
										From : 
										<select name="start1[]">
										<?php
											$start_hour = $arr_default_time[$i][0] . ":00";
											$to_hour = $arr_default_time[$i][1] . ":00";

											$query = "select workinghour_from, workinghour_to from oos_workinghours where store_id=$_SESSION[SESS_STORE_ID] and  day_number=" . $i;
											$result = mysql_query($query);
											if(mysql_num_rows($result)){
												$start_hour = mysql_result($result, 0, 0);
												$to_hour = mysql_result($result, 0, 1);
											}

											for($j=0;$j<count($arr_hour);$j++){
												if($start_hour == $arr_hour[$j].":00"){
										?>
											<option value="<?php echo $arr_hour[$j]; ?>" selected="selected"><?php echo $arr_hour[$j]; ?></option>
										<?php
												} else {
										?>
											<option value="<?php echo $arr_hour[$j]; ?>"><?php echo $arr_hour[$j]; ?></option>
										<?php
												}
											}
										?>
										</select>
										To : 
										<select name="to1[]">
										<?php
											for($j=0;$j<count($arr_hour);$j++){
												if($to_hour == $arr_hour[$j].":00"){
										?>
											<option value="<?php echo $arr_hour[$j]; ?>" selected="selected"><?php echo $arr_hour[$j]; ?></option>
										<?php
												}else{
										?>
											<option value="<?php echo $arr_hour[$j]; ?>"><?php echo $arr_hour[$j]; ?></option>
										<?php
												}
											}
										?>
										</select>
										&nbsp;&nbsp;&nbsp;&nbsp;
										From : 
										<select name="start2[]">
										<?php
											$start_hour = $arr_default_time[$i][2] . ":00";
											$to_hour = $arr_default_time[$i][3] . ":00";
											
											$query = "select workinghour_from2, workinghour_to2 from oos_workinghours where store_id=$_SESSION[SESS_STORE_ID] and  day_number=" . $i;
											$result = mysql_query($query);
											if(mysql_num_rows($result)){
												$start_hour = mysql_result($result, 0, 0);
												$to_hour = mysql_result($result, 0, 1);
											}

											for($j=0;$j<count($arr_hour);$j++){
												if($start_hour == $arr_hour[$j].":00"){
										?>
											<option value="<?php echo $arr_hour[$j]; ?>" selected="selected"><?php echo $arr_hour[$j]; ?></option>
										<?php
												} else {
										?>
											<option value="<?php echo $arr_hour[$j]; ?>"><?php echo $arr_hour[$j]; ?></option>
										<?php
												}
											}
										?>
										</select>
										To : 
										<select name="to2[]">
										<?php
											for($j=0;$j<count($arr_hour);$j++){
												if($to_hour == $arr_hour[$j].":00"){
										?>
											<option value="<?php echo $arr_hour[$j]; ?>" selected="selected"><?php echo $arr_hour[$j]; ?></option>
										<?php
												}else{
										?>
											<option value="<?php echo $arr_hour[$j]; ?>"><?php echo $arr_hour[$j]; ?></option>
										<?php
												}
											}
										?>
										</select>
									</td>
								</tr>
						  	<?php
								}
						  	?>
						  </table>
						</td>
					</tr>
				</table>
			</div>
		</div>
	<!-- end .content -->
	<input type="hidden" value="save" name="mode" />
	<?php echo form_close();?>
	</div>
