<?php
$today = date("Y-m-d");
$tomorrow = date("Y-m-d",mktime(0, 0, 0, date("n"),date("j")+1, date("Y") ));

?>
<form id="order_form">
<table class="order_contact_data w98" align="center">
	<tr>
		<td><div class="odr_data w50">
			<label for="firstname">First name<span class="required">*</span></label>
			<input id="firstname" class="contact" type="text" maxlength="64" name="firstname"></div>
			<div class="odr_data w50">
			<label for="lastname">Last Name<span class="required">*</span></label>
			<input id="lastname" class="contact" type="text" maxlength="64" name="lastname"></div>
		</td>
	</tr>
	<tr>
		<td><div class="odr_data wide">
			<label for="sex">Gender</label>&emsp;
			<label><input type="radio" value="1" name="sex" checked>male</label>
			<label><input type="radio" value="2" name="sex">female</label>
			</div>
		</td>
	</tr>
	<tr>
		<td><div class="odr_data w50">
			<label for="company">Company</label>
			<input id="company" class="contact" type="text" maxlength="64" name="company"></div>
			<div class="odr_data w50">
			<label for="department">Department</label>
			<input id="department" class="contact" type="text" maxlength="64" name="department"></div>
		</td>
	</tr>
	<tr>
		<td><div class="odr_data w66">
			<label for="street">Street<span class="required">*</span></label><br>
			<input id="street" class="contact" type="text" maxlength="64" name="street"></div>
			<div class="odr_data w33">
			<label for="house_no">House No<span class="required">*</span></label><br>
			<input id="house_no" class="contact" type="text" maxlength="64" name="house_no"></div>
		</td>
	</tr>
	<tr>
		<td><div class="odr_data w33">
			<label for="postcode">Postcode<span class="required">*</span></label><br>
			<input id="postcode" class="contact" type="text" maxlength="12" name="postcode"></div>
			<div class="odr_data w66">
			<label for="city">City<span class="required">*</span></label><br>
			<input id="city" class="contact" type="text" maxlength="54" name="city"></div>
		</td>
	</tr>
	<tr>
		<td><div class="odr_data wide">
			<label for="add_details">Additional address</label>
			<textarea id="add_details" class="contact" wrap="PHYSICAL" rows="2" cols="33" name="add_details"></textarea>
		</td>
	</tr>
	<tr>
		<td><div class="odr_data w50">
			<label for="telephone">Telephone number<span class="required">*</span></label><br>
			<input id="telephone" class="contact" type="text" maxlength="64" name="telephone"></div>
			<div class="odr_data w50">
			<label for="email">Email Address<span class="required">*</span></label><br>
			<input id="email" class="contact" type="text" maxlength="64" name="email"></div>
		</td>
	</tr>
	<tr>
		<td><div class="odr_data wide">
			<label for="details">Special note / Delivery time</label>
			<textarea id="details" class="contact" wrap="PHYSICAL" rows="2" cols="33" name="details"></textarea>
		</td>
	</tr>
	<!-- <tr>
		<td><div class="odr_data wide">
			<label for="delivery_time">Delivery time</label>
			<label><input type="radio" value="1" name="delivery_time">Immediately</label>
			<label><input type="radio" value="2" name="delivery_time">Pre order</label>
			</div>
		</td>
	</tr>
	<tr>
		<td><div class="odr_data w50">
			<label for="delivery_time">Day</label>
			<select>
				<option value="<?php echo $today?>"><?php echo $today?></option>
				<option value="<?php echo $tomorrow?>"><?php echo $tomorrow?></option>
			</select>
			<label><input type="radio" value="2" name="delivery_time">Pre order</label>
			</div>
		</td>
	</tr> -->
	<tr>
		<td align="center"><a href="javascript:void(0)" class="order_now" onclick="order_product()">Order Now</a></td>
	</tr>
</table>
</form>
