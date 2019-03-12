<?php 
echo doctype('xhtml1-trans');
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo meta('Content-Type', 'text/html; charset='.$this->config->item('charset'), 'http-equiv');
echo '<title>'.$this->config->item('sitename').'</title>';
echo link_tag('css/portal.css');
echo '<script language="javascript">var base_url = "'.base_url().'";</script>';
echo $this->javascript->external('js/jquery-1.8.3.js');
echo $this->javascript->external('js/jquery.validate.js');
echo $this->javascript->external('js/jquery.simplemodal.js');
echo $this->javascript->external('js/jquery.placeholder.js');
echo $this->javascript->external('js/common.js');
echo '</head>';	
?>
<div id="signup-interface">
	<div class="form">
		<div class="signup-interface-sub1">
			<span class="title">Sign Up!</span>
		</div>
		<div class="signup-interface-sub2">
			<form id="signup-form" method="post">
			<table border="0" cellpadding="0" cellspacing="0" width="160" class="signup-table">
				<tr>
					<td colspan="2" style="height:24px;">
						Anrede*&nbsp;&nbsp;&nbsp;
						<input name="sex" type="radio" class="radiocheck" id="id-sex_man" value="male" checked="checked">
						Herr&nbsp;&nbsp;&nbsp;
						<input name="sex" type="radio" class="radiocheck" id="id-sex_woman" value="female">Frau
					</td>
				</tr>
				<tr>
					<td>
						<label for="id-firstname">Vorname*</label><br>
						<input maxlength="60" class="inputSmall" type="text" name="firstname" id="id-firstname" value="">
					</td>
					<td>
						Nachname*<br>
						<input maxlength="60" class="inputSmall" type="text" name="lastname" id="id-lastname" value="">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						Region*<br>
						<select name="region" id="id-region" style="width:280px;">
						<?php
							$sql = "select region_name from oos_cityname group by region_name order by region_name";
							$result = mysql_query($sql);
							
							while($region_data = mysql_fetch_object($result)){
								if($region_name == $region_data->region_name)
									echo "<option value=\"$region_data->region_name\" selected=\"selected\">$region_data->region_name</option>";
								else
									echo "<option value=\"$region_data->region_name\">$region_data->region_name</option>";
							}
						?>

						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						City*<br>
						<select name="city" id="id-city" style="width:280px;">
							<option value="">(Select One)</option>
							<?php
								$sql = "select city_name from oos_cityname where region_name='$region_name' group by city_name order by city_name";
								$result = mysql_query($sql);
								
								while($city_data = mysql_fetch_object($result)){
									if($city == $city_data->city_name)
										echo "<option value=\"$city_data->city_name\" selected=\"selected\">$city_data->city_name</option>";
									else
										echo "<option value=\"$city_data->city_name\">$city_data->city_name</option>";
								}
							?>

						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						PostCode*<br>
						<select name="postcode" id="id-postcode" style="width:150px;">
							<option value="0">(Select Postcode)</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Straße*<br>
						<input maxlength="60" class="inputSmall" type="text" name="street" id="id-street" value="">
					</td>
					<td>
						Nr*<br>
						<input maxlength="60" class="inputSmall" type="text" name="street_no" id="id-street_no" value="">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						Hinterhof / Etage / etc.<br>
						<textarea rows="3" style="width:280px; border:0 none;" name="details" id="id-details"></textarea>
					</td>
				</tr>
				<tr>
					<td>
						Rufnummer*<br>
						<input maxlength="60" class="inputSmall" type="text" name="phone_local_number" id="id-phone_local_number" value="">
					</td>
					<td>
						E-Mail-Adresse*<br>
						<input maxlength="100" class="inputSmall" type="text" name="email" id="id-email" value="">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						Benutzername*<br>
						<input maxlength="100" class="inputLarge" type="text" name="username" id="id-username" value="">
					</td>
				</tr>
				<tr>
					<td>
						Passwort*<br>
						<input maxlength="60" class="inputSmall" type="password" name="password" id="id-password" value="">
					</td>
					<td>
						Passwort wiederholen*<br>
						<input maxlength="60" class="inputSmall" type="password" name="passwordrep" id="id-passwordrep" value="">
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right" style="height:45px; padding-top:5px;">
						<input type="button" value="register" id="signup-btn" class="loginSubmit" />
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>
</div>