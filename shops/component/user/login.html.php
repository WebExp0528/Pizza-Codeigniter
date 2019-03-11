<div id="login_area">
	<div class="odr_data w50">
	<form id="login_form" action="index.php" method="post">
	<p><input name="loginId" class="user_info_text" type="text" maxlength=50 onkeypress="check_userid(event)" onfocus="this.value=''" value="Email Address" /></p>
	<p><input name="loginPasswd" class="user_info_text" type="password" maxlength=50 onkeypress="check_password(event)" onfocus="this.value=''" value="password"/></p>
	<p><center><a href="#" class="user_check" onclick="user_login()">Log in</a></center></p>
	</form>
	</div>
	<div id="error_notice" class="wide"></div>
</div>