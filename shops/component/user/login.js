var userUrl = commonUrl + "index.php?task=user&request=user&special=1";
function check_userid(e){
	if(e.keyCode == 13){
		var err_msg = "";
		var userName = $("input[name=loginId]").val();
		if(userName=="") err_msg = "Login failed. Please try insert Username!";
		if(err_msg!="") {$("#error_notice").text(err_msg);return false;}
		$("input[name=loginPasswd]").focus();
	}
}
function check_password(e){
	if(e.keyCode == 13){
		var err_msg = "";
		var userPasswd = $("input[name=loginPasswd]").val();
		if(userPasswd=="") err_msg = "Login failed. Please try insert Password!";
		if(err_msg!="") {$("#error_notice").text(err_msg);return false;}
		userLogin.loginConfirm();
	}
}
function user_login(){
	var err_msg = "";
	var userName = $("input[name=loginId]").val();
	var userPasswd = $("input[name=loginPasswd]").val();
	if(userName==""||userName=="Email Address") err_msg = "Login failed. Please try insert Username!";
	if(userPasswd=="") err_msg = "Login failed. Please try insert Password!";
	if(err_msg!="") {$("#error_notice").text(err_msg);return false;}
	userLogin.loginConfirm();
	//$("#login_form").submit();
}
function userLoginClass() {

	this.loginForm		= new interfaceObject("login_form");
	this.ajax	= new ajaxObject(this.callAfter);
};
userLoginClass.prototype = {
	init : function () {
	},
	sendRequest : function (url) {
		this.ajax.loadXML(url);
	},
	loginConfirm : function () {
		insertExtraUrl = userLogin.loginForm.getExtraUrl()+"&option=userLogin";
		userLogin.sendRequest(userUrl+insertExtraUrl);
	},
	callAfter : function (p) {
		if (p.sub_exists("login")){
			var err_msg = "";
			var result = p.sub("login").content();
			switch(result){
				case "0":
					err_msg = "Password Incorrect !";
					$("input[name=loginPasswd]").val("");
					$("input[name=loginPasswd]").focus();
					break;
				case "-1":
					err_msg = "Username Incorrect !";
					$("input[name=loginId]").focus();
					break;
				case "1":
					err_msg = "";
					break;

			}
			if(err_msg!="") {$("#error_notice").text(err_msg);return false;}
			else {
				$.modal.close();
				window.location = commonUrl + "index.php";
			}
		}

		return;
	}
}
var userLogin = new userLoginClass();
userLogin.init();
