<?php
?> 
	<table class="user_info" border="0" align="center">
		<tr><td align="center" colspan=2><span class="user_info_title">User Login</span></td></tr>
		<tr><td align="right" class="w20">User ID</td>
		<td><input type="text" maxlength="30" id="userid" name="userid" class="text"></td>
		</tr>
		<tr><td align="right">Password</td>
		<td><input type="password" maxlength="30" id="password" name="password" class="text"></td>
		</tr>
		<tr><td align="right" height="12px"><input type="checkbox" id="remember" name="remember"></td>
		<td>Remember Me</td>
		</tr>
		<tr><td align="right"><input type="button" id="user_login" name="user_login" value="Log in"></td>
		<td align="center"><a href="user_register.htm">Create Account</a></td>
		</tr>
		
	</table>
<!-- <form action="index.php" method="post" name="loginForm" id="loginForm" >
	<table><tr><td><input name="loginIdinLoginForm" type=""  size="10" onkeypress="check_userid(event)" value="admin" style="font-size:12;font-family:Arial;" /></td>&nbsp;&nbsp;&nbsp;
	<td><input name="loginPasswdinLoginForm" type="password"  size="10" onkeypress="check_password(event)"  style="font-size:12;font-family:Arial;"/></td>
	</tr>
	</table>
	<? if ($msg != "") {?>
	<span style="background-color:#EDE823"><?=$msg?></span>
	<? } ?>
</form> -->
