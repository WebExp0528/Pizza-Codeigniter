<?php 
echo doctype('xhtml1-trans');
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo meta('Content-Type', 'text/html; charset='.$this->config->item('charset'), 'http-equiv');
echo '<title>'.$this->config->item('sitename').'</title>';
echo link_tag('css/portal.css');
echo '<script language="javascript">var base_url = "'.base_url().'"; var site_url = "'.site_url().'";</script>';
echo $this->javascript->external('js/jquery-1.8.3.js');
echo $this->javascript->external('js/jquery.validate.js');
echo $this->javascript->external('js/jquery.simplemodal.js');
echo $this->javascript->external('js/jquery.placeholder.js');
echo $this->javascript->external('js/common.js');
echo '</head>';	
?>
	<div class="basic-modal-login">
		<div class="signin">
			<div class="alert_inner_window">
				<div class="titleContainer">
					<span class="title">Derzeit geschlossen!</span>
				</div>
				<div class="lightboxContent_login">
					<div class="loginBox">
						<form id="head_loginform_form" method="post">
							<div class="loginInputfields">
								<div class="loginInputfield">
									<input class="inputSmall" type="text" name="username" id="login_username" placeholder="E-Mail"/>
								</div>
								<div class="loginInputfield">
									<input class="inputSmall" type="password" name="password" id="login_password" placeholder="Passwort"/>
								</div>
								<div class="clear-both"></div>
							</div>
							<p class="message"></p>
							<div>
							<div class="submit">
									<div class="loginLinkPwd">
										Passwort vergessen?
									</div>

								<input class="loginSubmit" id="id-login-btn" type="button" value="Login">
							</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>	
		
		<div class="forgotpassword">
			<div class="alert_inner_window">
				<div class="titleContainer">
					<span class="title">PASSWORT VERGESSEN?</span>
				</div>
				<div class="lightboxContent">
					<div class="fliesstextL">
						Tippe hier deine Email Adresse ein und wir schicken dir umgehend ein neues Passwort per Email zu.Tippe hier deine Email Adresse ein und wir schicken dir umgehend ein neues Passwort per Email zu.
					</div>
					<p class="forgot_password_message"></p>
					<form id="head_loginform_form" method="post">
						<div class="loginInputfields">
							<div class="loginInputfield_forgotpwd">
								<input class="inputSmall" type="text" name="forgot_email" id="forgot_email" placeholder="E-Mail"/>
							</div>
							<div style="float:left; padding-left:10px;">
								<input class="loginSubmit" type="button" value="Abschicken" id="id-password-recovery" >
								
							</div>
							<div class="clear-both"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
