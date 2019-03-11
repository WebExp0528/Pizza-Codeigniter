	var initstr = "PLZ (z.B. 10179)";
	$(document).ready(function(e) {
		
        $('#id_header_3_2_4').bind('mouseover', null, function(){
			$('#id_header_3_2_4').removeClass();
			$('#id_header_3_2_4').addClass('header_3_2_4_mouseover');
		});
        $('#id_header_3_2_4').bind('mouseout', null, function(){
			$('#id_header_3_2_4').removeClass();
			$('#id_header_3_2_4').addClass('header_3_2_4');
		});
		$('#id_header_3_2_4').bind('click', null, function(){
			if($('#id_postcode').val() == initstr){
				alert("Please enter PostCode!");
			} else {
				$.post(base_url + "ajax/postcode_verify", {postcode:$('#id_postcode').val()}, function(result){
					if(result != "fail"){
						$('#id-form-postcode').val($('#id_postcode').val());
						$('#form1').submit();
					} else {
						alert("Please enter Postcode correctly!");
					}
				});
			}
		});
		$('#id_postcode').bind('focusin', null, function(){
			if($('#id_postcode').val() == initstr){
				$('#id_postcode').val('');
			}
		});

		$('#id_postcode').bind('focusout', null, function(){
			if($('#id_postcode').val() == ""){
				$('#id_postcode').val(initstr);
			}
		});
		$('#id_postcode').bind("keyup", null, function(){
			//alert($('#id_postcode').val());

            $.post(site_url + "ajax/get_postcode_1", {postcode:$('#id_postcode').val()}, function(result){
				if(result != "]"){
					$('.unshown-id-postcode-search').addClass('shown-id-postcode-search');
					var obj = $.parseJSON(result);
					$('.unshown-id-postcode-search').empty();
					$.each(obj, function(i, tweet){
						$('.unshown-id-postcode-search').append('<div><a href="javascript:set(' + tweet.postcode +')">' + tweet.postcode + " " +  tweet.city_name + '</a></div>');
					});
				} else {
					$('.unshown-id-postcode-search').removeClass('shown-id-postcode-search');
				}
			});
		});
		$('.shop-open-time').bind('mouseover', null, function(){
			$(this).find('.unshown-time-table').addClass('shown-time-table');
		});

		$('.shop-open-time').bind('mouseout', null, function(){
			$(this).find('.unshown-time-table').removeClass('shown-time-table');
		});

		$('.login-span').click(function(e) {
			$('#login_modal').load( site_url + "ajax/login_box").modal({minHeight:180, minWidth:330});
			//$('.basic-modal-login').modal({minHeight:180, minWidth:330});
        });
		
		$('.signup-span').click(function(e) {
			$('#login_modal').load( site_url + "ajax/signup").modal({minHeight:520, minWidth:330});
			//$('#signup-interface').modal({minHeight:580, minWidth:330});
        });
		
		$('.loginLinkPwd').bind('click', null, function(){
			$('.forgotpassword').removeClass();
			$('.signin').addClass('signin_unshown');
			var cheight = $('.basic-modal-login').height() + 10;
			$('#simplemodal-container').height(cheight);
			
		});
		$('.close').bind('click', null, function(){
			$.modal.close();
		});


		$('.close').click(function(e) {
			$.modal.close();
		});
		$('#signup-btn').click(function(e) {
			if($('#id-firstname').val() == ""){
				alert("Please Enter First Name!");
				$('#id-firstname').focus();
				return;
			}

			if($('#id-lastname').val() == ""){
				alert("Please Enter Last Name!");
				$('#id-lastname').focus();
				return;
			}
			
			if($('#id-region').val() == ""){
				alert("Please Select Region!");
				$('#id-region').focus();
				return;
			}

			if($('#id-city').val() == ""){
				alert("Please Select City!");
				$('#id-city').focus();
				return;
			}

			if($('#id-postcode').val() == ""){
				alert("Please Select PostCode!");
				$('#id-postcode').focus();
				return;
			}

			if($('#id-street').val() == ""){
				alert("Please Enter Straße!");
				$('#id-street').focus();
				return;
			}

			if($('#id-street_no').val() == ""){
				alert("Please Enter Straße Nr!");
				$('#id-street_no').focus();
				return;
			}

			if($('#id-phone_local_number').val() == ""){
				alert("Please Enter Rufnummer!");
				$('#id-phone_local_number').focus();
				return;
			}
			if($('#id-email').val() == ""){
				alert("Please Enter E-Mail!");
				$('#id-email').focus();
				return;
			}
			if($('#id-username').val() == ""){
				alert("Please Enter Benutzername!");
				$('#id-username').focus();
				return;
			}
			
			if(($('#id-password').val() != $('#id-passwordrep').val()) || ($('#id-password').val() == "") || ($('#id-passwordrep').val() == "")){
				alert("Password is incorrect!");
				$('#id-password').focus();
				return;
			}
			$.post( site_url + "ajax/user_login", 
				{
					option:"user_register",
					gender:$('.radiocheck:checked').val(), 
					firstname:$('#id-firstname').val(),
					lastname:$('#id-lastname').val(), 
					region:$('#id-region').val(),
					city:$('#id-city').val(),
					postcode:$('#id-postcode').val(),
					street:$('#id-street').val(),
					house_no:$('#id-street_no').val(), 
					telephone:$('#id-phone_local_number').val(), 
					details:$('#id-details').val(), 
					user_id:$('#id-username').val(), 
					email:$('#id-email').val(), 
					passwd:$('#id-password').val()
				}, 
				function(result){
		//alert (result);
					if(result == "ok"){
						alert("Register process is successful.");
						$.modal.close();
					} else if(result == "dbl-email"){
						alert("Your email address is already existed!");
					} else {
						alert("Register faild");
					}
			});
        });
		
		$('#id-region').change(function(e) {
//alert("testtest");
			$('#id-city').empty();
			$('#id-city').append('<option value="">(Select One)</option>');
			$.post(site_url + "ajax/get_city_name", {region_name:$(this).val()}, function(result){
				//alert(result);
				var obj = $.parseJSON(result);
				//alert(obj);
				$.each(obj, function(i, tweet){
					$('#id-city').append('<option value="' + tweet.name + '">' + tweet.name + '</option>');
				});
				
			});
		});
		
		$('#id-city').change(function(e) {
//alert("testtest");
			$('#id-postcode').empty();
			$('#id-postcode').append('<option value="">(Select Postcode)</option>');
			$.post(site_url + "ajax/get_postcode", {city_name:$(this).val()}, function(result){

				//alert(result);
				var obj = $.parseJSON(result);
				//alert(obj);
				$.each(obj, function(i, tweet){
					$('#id-postcode').append('<option value="' + tweet.name + '">' + tweet.name + '</option>');
				});
			});
		});

		$(".inputSmall").keypress(function(e) {
			if(e.keyCode != 13) return;
			var email = $('#login_username').val();
			var passwd = $('#login_password').val();
			if(email&&passwd){
				user_login(email,passwd);
				return;
			}
			if(email){
				$('#login_password').focus();
				$('#login_password').val("");
				return;
			}
			if(passwd){
				if(!email){
					$('.message').html('Please enter your E-mail.');
					var cheight = $('.basic-modal-login').height() + 20;
					$('#simplemodal-container').height(cheight);
					return;
				} else {
					user_login(email,passwd);
				}
			}
		});
		$('#id-login-btn').click(function(e) {
			var email = $('#login_username').val();
			var passwd = $('#login_password').val();
			
			if(!email){
				$('.message').html('Please enter your E-mail.');
				var cheight = $('.basic-modal-login').height() + 20;
				$('#simplemodal-container').height(cheight);
				return;
			}
			if(!passwd){
				$('.message').html('Please enter your password.');
				var cheight = $('.basic-modal-login').height() + 20;
				$('#simplemodal-container').height(cheight);
				return;
			}
			user_login(email,passwd);
		});
		
		$('#id-password-recovery').click(function(e) {
			$.post(site_url +"ajax/get_password", {email:$('#forgot_email').val()}, function(result){
//alert(result);
				if(result == "ok"){
					alert("Password Recovery process is successful.");
					$.modal.close();
				} else if(result == "non-approve") {
					$('.forgot_password_message').html('Your account is unapproved!');
					var cheight = $('.basic-modal-login').height() + 10;
					$('#simplemodal-container').height(cheight);
				} else {

					$('.forgot_password_message').html('Please enter your email address correctly!');
					var cheight = $('.basic-modal-login').height() + 10;
					$('#simplemodal-container').height(cheight);
				}
			});
        });
		
		$('.logout-span').click(function(e) {
			$.post(site_url + "ajax/user_login",{option:"user_logout"}, function(result){
				//alert(result);
				location.reload();
			});
        });
		
		//$('.rating').each(function(){
		//	$(this).jRating({
		//		isDisabled : true
		//	});
		//});
		/*$(".total_rating").tooltip({
			position: "bottom center",
			effect: 'slide'
		});*/
		//$("#download_now").tooltip({ effect: 'slide'});
    });	
	function set(postcode){
		//$.post(site_url + "ajax/check_shopstate", {postcode: postcode}, function (result) {
		//	if (result != '') {
		//		alert("Sorry!. (" + result + ")");
		//	} else {
				$('#id_postcode').val(postcode);
				$('.unshown-id-postcode-search').removeClass('shown-id-postcode-search');
				$('#id-form-postcode').val(postcode);
				$('#form1').submit();
		//	}
		//});
	}
	function go(url)
	{
		$('#basic-modal-content').modal();
		$('#basic-modal-content').find('#currently_closed_preorder_link').attr('href', url);
	}
	
	function make_search_form(){
		$('.search_form').html("<form method=\"post\"><input id=\"zip_code_search_only_input\" type=\"text\" name=\"postcode\" class=\"prefilled_container\" value=\"\" autocomplete=\"off\"><input id=\"zip_code_search_only_searchicon\" src=\"images/icon_lens_small.png\" type=\"image\" class=\"button_active\"></form>");
	}
	function user_login(email,passwd){
		$.post( site_url + "ajax/user_login",
			{
				option: "user_login",
				email: email,
				passwd: passwd
			}, 
			function(result){
//alert(result);
				if(result == "ok"){
					location.reload();
					$.modal.close();
				} else if(result == "non-approve") {
					$('.message').html('Your account is unapproved!');
					var cheight = $('.basic-modal-login').height() + 20;
					$('#simplemodal-container').height(cheight);
				} else {
					$('.message').html('Login fehlgeschlagen. Bitte probiere es noch einmal!');
					var cheight = $('.basic-modal-login').height() + 20;
					$('#simplemodal-container').height(cheight);
				}
		});
	}
