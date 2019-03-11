<?php
	$link = mysql_connect("localhost", "root", "apmsetup");
	mysql_select_db("membership");
	$option		= $_REQUEST['option'];
	$country	= $_POST['country'];
	$payeename	= $_POST['payeename'];
	$street	= $_POST['street'];
	$suite	= $_POST['suite'];
	$city	= $_POST['city'];
	$province	= $_POST['province'];
	$postcode	= $_POST['postcode'];
	$firstname	= $_POST['firstname'];
	$lastname	= $_POST['lastname'];
	$email	= $_POST['email'];
	$phone	= $_POST['phone'];
	$website	= $_POST['website'];
	$nickname	= $_POST['nickname'];
	if ( $option == "insert" ){
		$query = "INSERT INTO `tbl_content` (`id` ,`userid` ,`country` ,`payeename` ,`street` ,`suite` ,`city` ,`province` ,`postcode` ,`firstname` ,`lastname` ,`email` ,`phone` ,`website` ,`nickname` ,`check`) VALUES (NULL,'','$country','$payeename','$street','$suite', '$city', '$province', '$postcode','$firstname','$lastname', '$email', '$phone', '$website', '$nickname', '$check')";
		$result = mysql_query($query);
	}
?> 

<style>
input { width:196px; }         
select { width:200px; }
#helpBar {
	text-align: right;
	font-size: 8pt;
	height: 0px;
	top: -10px;
	position: relative;
}

html, body {margin: 0; 
	padding: 0;
	height: 100%;
}


#container {
	min-height: 100%;
	position: relative;
}


#header {
	padding: 0;
}


#body {padding-bottom: 125px;width: 800px;
	margin: 0 auto;text-align: left;
	padding-left:20px; padding-right:20px;
}

#footer {
	position: absolute;
	bottom:0;
	width:100%;
	background: #f4eeeb; height:125px;
}

</style>
</head>
<body>
<form id="contentForm" name="contentForm" action="index.php?task=user&request=signup&special=2&option=insert" method="post">
<table border=0 align="center"><tr><td>
		<table border=0 cellspacing="3" cellpadding="0" width="500px" align="center" style="font-size:13px;font-weight:bold;">
		<tr>
			<td align="left">
				Country:
			</td>
			<td>
				<select tabindex="1" name="country" id="country"
					 onchange="javascript: loadProvinceList(this.value, false);" >
					<option value="UNITED STATES" >UNITED STATES</option><option value="CANADA" >CANADA</option><option value="BRITAIN" >BRITAIN</option><option value="AUSTRALIA" >AUSTRALIA</option><option value="FRANCE" >FRANCE</option><option value="GERMANY" >GERMANY</option><option value="SPAIN" >SPAIN</option><option value="MEXICO" >MEXICO</option><option value="APO/FPO" >APO/FPO</option><option value="Aland Is" >Aland Is</option><option value="Albania" >Albania</option><option value="Andorra" >Andorra</option><option value="Anguilla" >Anguilla</option><option value="ANTARCTICA" >Antarctica</option><option value="Antigua &amp; Barbuda" >Antigua &amp; Barbuda</option><option value="Argentina" >Argentina</option><option value="Armenia" >Armenia</option><option value="Aruba" >Aruba</option><option value="Australia" >Australia</option><option value="Austria" >Austria</option><option value="Bahamas" >Bahamas</option><option value="Bahrain" >Bahrain</option><option value="Barbados" >Barbados</option><option value="Belgium" >Belgium</option><option value="Belize" >Belize</option><option value="Benin" >Benin</option><option value="Bermuda" >Bermuda</option><option value="Bhutan" >Bhutan</option><option value="Bolivia" >Bolivia</option><option value="Botswana" >Botswana</option><option value="Bouvet Is" >Bouvet Is</option><option value="Brazil" >Brazil</option><option value="Brunei" >Brunei</option><option value="Bulgaria" >Bulgaria</option><option value="Cambodia" >Cambodia</option><option value="Cameroon" >Cameroon</option><option value="Canada" >Canada</option><option value="Cape Verde" >Cape Verde</option><option value="Cayman Is" >Cayman Is</option><option value="Chile" >Chile</option><option value="China" >China</option><option value="Christmas Is" >Christmas Is</option><option value="Cocos Is" >Cocos Is</option><option value="Colombia" >Colombia</option><option value="Comoros" >Comoros</option><option value="Cook Is" >Cook Is</option><option value="Costa Rica" >Costa Rica</option><option value="Cote d Ivoire" >Cote d Ivoire</option><option value="Croatia" >Croatia</option><option value="Cyprus" >Cyprus</option><option value="Czech Republic" >Czech Republic</option><option value="Denmark" >Denmark</option><option value="Diego Garcia" >Diego Garcia</option><option value="Dominica" >Dominica</option><option value="Egypt" >Egypt</option><option value="El Salvador" >El Salvador</option><option value="Estonia" >Estonia</option><option value="Falkland Is" >Falkland Is</option><option value="Faroe Is" >Faroe Is</option><option value="Fiji" >Fiji</option><option value="Finland" >Finland</option><option value="France" >France</option><option value="French Guiana" >French Guiana</option><option value="French Polynesia" >French Polynesia</option><option value="French Southern Terr" >French Southern Terr</option><option value="Gambia" >Gambia</option><option value="Georgian Republic" >Georgian Republic</option><option value="Germany" >Germany</option><option value="Ghana" >Ghana</option><option value="Gibraltar" >Gibraltar</option><option value="Greece" >Greece</option><option value="Greenland" >Greenland</option><option value="Grenada" >Grenada</option><option value="Guadeloupe" >Guadeloupe</option><option value="Guam" >Guam</option><option value="Guatemala" >Guatemala</option><option value="Guernsey Is" >Guernsey Is</option><option value="Haiti" >Haiti</option><option value="Heard &amp; McDonald Is" >Heard &amp; McDonald Is</option><option value="Honduras" >Honduras</option><option value="Hong Kong" >Hong Kong</option><option value="Hungary" >Hungary</option><option value="Iceland" >Iceland</option><option value="India" >India</option><option value="Indonesia" >Indonesia</option><option value="Ireland" >Ireland</option><option value="Isle Of Man" >Isle Of Man</option><option value="Israel" >Israel</option><option value="Italy" >Italy</option><option value="Jamaica" >Jamaica</option><option value="Japan" >Japan</option><option value="Jersey Is" >Jersey Is</option><option value="Jordan" >Jordan</option><option value="Kiribati" >Kiribati</option><option value="Korea, South" >Korea, South</option><option value="Kuwait" >Kuwait</option><option value="Kyrgyzstan" >Kyrgyzstan</option><option value="Latvia" >Latvia</option><option value="Lebanon" >Lebanon</option><option value="Lesotho" >Lesotho</option><option value="Liechtenstein" >Liechtenstein</option><option value="Lithuania" >Lithuania</option><option value="Luxembourg" >Luxembourg</option><option value="Macao" >Macao</option><option value="Macedonia" >Macedonia</option><option value="Malaysia" >Malaysia</option><option value="Maldives" >Maldives</option><option value="Mali" >Mali</option><option value="Malta" >Malta</option><option value="Marshall Is" >Marshall Is</option><option value="Martinique" >Martinique</option><option value="Mauritius" >Mauritius</option><option value="Mayotte" >Mayotte</option><option value="Mexico" >Mexico</option><option value="Micronesia" >Micronesia</option><option value="Monaco" >Monaco</option><option value="Montenegro" >Montenegro</option><option value="Montserrat" >Montserrat</option><option value="Morocco" >Morocco</option><option value="Namibia" >Namibia</option><option value="Nauru" >Nauru</option><option value="Netherlands" >Netherlands</option><option value="Netherlands Antilles" >Netherlands Antilles</option><option value="New Caledonia" >New Caledonia</option><option value="New Zealand" >New Zealand</option><option value="Nicaragua" >Nicaragua</option><option value="Niue" >Niue</option><option value="Norfolk Is" >Norfolk Is</option><option value="Northern Mariana Is" >Northern Mariana Is</option><option value="Norway" >Norway</option><option value="Oman" >Oman</option><option value="Pakistan" >Pakistan</option><option value="Palau" >Palau</option><option value="Panama" >Panama</option><option value="Paraguay" >Paraguay</option><option value="Peru" >Peru</option><option value="Philippines" >Philippines</option><option value="Pitcairn Is" >Pitcairn Is</option><option value="Poland" >Poland</option><option value="Portugal" >Portugal</option><option value="Puerto Rico" >Puerto Rico</option><option value="Qatar" >Qatar</option><option value="Reunion Is" >Reunion Is</option><option value="Romania" >Romania</option><option value="Russian Federation" >Russian Federation</option><option value="Samoa, East" >Samoa, East</option><option value="Samoa, West" >Samoa, West</option><option value="San Marino" >San Marino</option><option value="Sandwich Is" >Sandwich Is</option><option value="Sao Tome &amp; Principe" >Sao Tome &amp; Principe</option><option value="Saudi Arabia" >Saudi Arabia</option><option value="Seychelles" >Seychelles</option><option value="Singapore" >Singapore</option><option value="Slovak Republic" >Slovak Republic</option><option value="Slovenia" >Slovenia</option><option value="Solomon Is" >Solomon Is</option><option value="South Africa" >South Africa</option><option value="Spain" >Spain</option><option value="Sri Lanka" >Sri Lanka</option><option value="St Helena" >St Helena</option><option value="St Kitts &amp; Nevis" >St Kitts &amp; Nevis</option><option value="St Lucia" >St Lucia</option><option value="St Pierre &amp; Miquelon" >St Pierre &amp; Miquelon</option><option value="St Vincent &amp; Grenadines" >St Vincent &amp; Grenadines</option><option value="Suriname" >Suriname</option><option value="Svalbard &amp; Jan Mayen Is" >Svalbard &amp; Jan Mayen Is</option><option value="Swaziland" >Swaziland</option><option value="Sweden" >Sweden</option><option value="Switzerland" >Switzerland</option><option value="Taiwan" >Taiwan</option><option value="Thailand" >Thailand</option><option value="Togo" >Togo</option><option value="Tokelau" >Tokelau</option><option value="Tonga" >Tonga</option><option value="Trinidad &amp; Tobago" >Trinidad &amp; Tobago</option><option value="Tunisia" >Tunisia</option><option value="Turkey" >Turkey</option><option value="Turks &amp; Caicos Is" >Turks &amp; Caicos Is</option><option value="Tuvalu" >Tuvalu</option><option value="Ukraine" >Ukraine</option><option value="United Arab Emirates" >United Arab Emirates</option><option value="United Kingdom" >United Kingdom</option><option value="United States" >United States</option><option value="Uruguay" >Uruguay</option><option value="USA Minor Outlying IS" >USA Minor Outlying IS</option><option value="Uzbekistan" >Uzbekistan</option><option value="Vanuatu" >Vanuatu</option><option value="Vatican" >Vatican</option><option value="Venezuela" >Venezuela</option><option value="Vietnam" >Vietnam</option><option value="Virgin Is, UK" >Virgin Is, UK</option><option value="Virgin Is, US" >Virgin Is, US</option><option value="Wallis &amp; Futuna Is" >Wallis &amp; Futuna Is</option><option value="Western Sahara" >Western Sahara</option><option value="Yemen" >Yemen</option><option value="UNITED STATES" >UNITED STATES</option><option value="CANADA" >CANADA</option><option value="BRITAIN" >BRITAIN</option><option value="AUSTRALIA" >AUSTRALIA</option><option value="FRANCE" >FRANCE</option><option value="GERMANY" >GERMANY</option><option value="SPAIN" >SPAIN</option><option value="MEXICO" >MEXICO</option><option value="APO/FPO" >APO/FPO</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="left">Payee Name:</td><td>
			<input class="class_01" name="payeename" size="25" id="payeename" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">Street / PO Box:</td>
			<td><input class="class_01" name="street" size="25" id="street" maxlength="50"></td>
		</tr>
		<tr>
			<td  align="left">Suite or Apt #:</td>
			<td><input class="class_01" name="suite" size="25" id="suite" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">City:</td>
			<td><input class="class_01" name="city" size="25" id="city" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">State / Province:</td>
			<td id="provinceCell"></td>
			<!--td><input class="class_01" name="province" size="25" id="province" maxlength="50">
			</td-->
		</tr>
		<tr>
			<td  align="left">Zip / Post Code:</td>
			<td><input class="class_01" name="postcode" size="25" id="postcode" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">First name:</td>
			<td><input class="class_01" name="firstname" size="25" id="firstname" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">Last name:</td>
			<td><input class="class_01" name="lastname" size="25" id="lastname" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">Email address:</td>
			<td><input class="class_01" name="email" size="25" id="email" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">Phone number:</td>
			<td><input class="class_01" name="phone" size="25" id="phone" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">Address of Web site:</td>
			<td><input class="class_01" name="website" size="25" id="website" maxlength="50">
			</td>
		</tr>
		<tr>
			<td  align="left">Account Nickname:</td>
			<td><input class="class_01" name="nickname" size="25" id="nickname" maxlength="50">
			</td>
		</tr>
		</table>
	</td></tr>
	<tr><td align="center">
	<br>
	<input type = "button" value = "submit" onclick = "submit();"/>
	</td>
</tr>
</table>
</form>
<script>
    var ctry = new Array('APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','APO/FPO','AUSTRALIA','AUSTRALIA','AUSTRALIA','AUSTRALIA','AUSTRALIA','AUSTRALIA','AUSTRALIA','AUSTRALIA','Australia','Australia','Australia','Australia','Australia','Australia','Australia','Australia','CANADA','CANADA','CANADA','CANADA','CANADA','CANADA','CANADA','CANADA','CANADA','CANADA','CANADA','CANADA','CANADA','Canada','Canada','Canada','Canada','Canada','Canada','Canada','Canada','Canada','Canada','Canada','Canada','Canada','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','UNITED STATES','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States','United States');
    var prov = new Array('ALABAMA','ALASKA','ARIZONA','ARKANSAS','CALIFORNIA','COLORADO','CONNECTICUT','DISTRICT OF COLUMBIA','DELAWARE','FLORIDA','GEORGIA','GUAM','HAWAII','IDAHO','ILLINOIS','INDIANA','IOWA','KANSAS','KENTUCKY','LOUISIANA','MAINE','MARYLAND','MASSACHUSETTS','MICHIGAN','MINNESOTA','MISSISSIPPI','MISSOURI','MONTANA','NEBRASKA','NEVADA','NEW HAMPSHIRE','NEW JERSEY','NEW MEXICO','NEW YORK','NORTH CAROLINA','NORTH DAKOTA','OHIO','OKLAHOMA','OREGON','PENNSYLVANIA','RHODE ISLAND','SOUTH CAROLINA','SOUTH DAKOTA','TENNESSEE','TEXAS','UTAH','VERMONT','VIRGINIA','WASHINGTON','WEST VIRGINIA','WISCONSIN','WYOMING','APO/FPO','AUSTRALIAN CAPITAL TERRITORY','NEW SOUTH WALES','NORTHERN TERRITORY','QUEENSLAND','SOUTH AUSTRALIA','TASMANIA','VICTORIA','WESTERN AUSTRALIA','AUSTRALIAN CAPITAL TERRITORY','NEW SOUTH WALES','NORTHERN TERRITORY','QUEENSLAND','SOUTH AUSTRALIA','TASMANIA','VICTORIA','WESTERN AUSTRALIA','ALBERTA','BRITISH COLUMBIA','MANITOBA','NEW BRUNSWICK','NEWFOUNDLAND & LABRADOR','NORTHWEST TERRITORIES','NOVA SCOTIA','NUNAVUT','ONTARIO','PRINCE EDWARD IS','QUEBEC','SASKATCHEWAN','YUKON','ALBERTA','BRITISH COLUMBIA','MANITOBA','NEW BRUNSWICK','NEWFOUNDLAND & LABRADOR','NORTHWEST TERRITORIES','NOVA SCOTIA','NUNAVUT','ONTARIO','PRINCE EDWARD IS','QUEBEC','SASKATCHEWAN','YUKON','ALABAMA','ALASKA','ARIZONA','ARKANSAS','CALIFORNIA','COLORADO','CONNECTICUT','DISTRICT OF COLUMBIA','DELAWARE','FLORIDA','GEORGIA','GUAM','HAWAII','IDAHO','ILLINOIS','INDIANA','IOWA','KANSAS','KENTUCKY','LOUISIANA','MAINE','MARYLAND','MASSACHUSETTS','MICHIGAN','MINNESOTA','MISSISSIPPI','MISSOURI','MONTANA','NEBRASKA','NEVADA','NEW HAMPSHIRE','NEW JERSEY','NEW MEXICO','NEW YORK','NORTH CAROLINA','NORTH DAKOTA','OHIO','OKLAHOMA','OREGON','PENNSYLVANIA','RHODE ISLAND','SOUTH CAROLINA','SOUTH DAKOTA','TENNESSEE','TEXAS','UTAH','VERMONT','VIRGINIA','WASHINGTON','WEST VIRGINIA','WISCONSIN','WYOMING','APO/FPO','ALABAMA','ALASKA','ARIZONA','ARKANSAS','CALIFORNIA','COLORADO','CONNECTICUT','DISTRICT OF COLUMBIA','DELAWARE','FLORIDA','GEORGIA','GUAM','HAWAII','IDAHO','ILLINOIS','INDIANA','IOWA','KANSAS','KENTUCKY','LOUISIANA','MAINE','MARYLAND','MASSACHUSETTS','MICHIGAN','MINNESOTA','MISSISSIPPI','MISSOURI','MONTANA','NEBRASKA','NEVADA','NEW HAMPSHIRE','NEW JERSEY','NEW MEXICO','NEW YORK','NORTH CAROLINA','NORTH DAKOTA','OHIO','OKLAHOMA','OREGON','PENNSYLVANIA','RHODE ISLAND','SOUTH CAROLINA','SOUTH DAKOTA','TENNESSEE','TEXAS','UTAH','VERMONT','VIRGINIA','WASHINGTON','WEST VIRGINIA','WISCONSIN','WYOMING','APO/FPO');
    var provCode = new Array('AL','AK','AZ','AR','CA','CO','CT','DC','DE','FL','GA','GU','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','APO/FPO','ACT','NSW','NT','QLD','SA','TAS','VIC','WA','ACT','NSW','NT','QLD','SA','TAS','VIC','WA','AB','BC','MB','NB','NL','NT','NS','NU','ON','PE','QC','SK','YT','AB','BC','MB','NB','NL','NT','NS','NU','ON','PE','QC','SK','YT','AL','AK','AZ','AR','CA','CO','CT','DC','DE','FL','GA','GU','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','APO/FPO','AL','AK','AZ','AR','CA','CO','CT','DC','DE','FL','GA','GU','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','APO/FPO');
    function loadProvinceList(country, isReload) {
        var selectedProvince = (isReload ? "" : null);
        var body = "";
        for(var i = 0; i < ctry.length; i++){
            if(country == ctry[i]){
                body += "<option value='" + provCode[i] + "'";
                if(selectedProvince == provCode[i]){
                    body += " selected='selected'";
                }
                body += ">" + prov[i] + "</option>";
            }
        }
        var entry = "";
        
        if(body.length > 0){
            entry = "<select tabindex=\"6\" name=\"province\" id=\"province\">";
            entry += body;
            entry += "</select>";
        } else {
            entry = "<input  class=\"class_01\" tabindex=\"6\" type=\"text\" name=\"province\" maxlength=\"100\" id=\"province\" size=\"25\" ";
            
            if(isReload){
                entry += "value=\"\" />";
            } else {
                entry += "value=\"\" />";
            }
        }
        
        document.getElementById('provinceCell').innerHTML = entry;
    }
loadProvinceList("UNITED STATES", false);
</script>