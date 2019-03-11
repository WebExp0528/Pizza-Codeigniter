//var shopid = $("input[name=shop_id]").val();
var orderUrl = commonUrl + "?task=order&request=order&special=1";
var checkInputObj = "firstname,lastname,street,house_no,postcode,city,telephone,email";
var checkInputLabel = "First name,Last Name,Street,House No,Postcode,City,Phone number,Email Address";
function checkInputValue(){
	var invalid = "";
	var objArr = checkInputObj.split(",");
	var labelArr = checkInputLabel.split(",");
	for(var i=0;i<objArr.length;i++){
		var inputValue = $("#"+objArr[i]).val();
		$("#"+objArr[i]).removeClass("invalidInput");
		if(inputValue=="") {
			$("#"+objArr[i]).focus();
			$("#"+objArr[i]).addClass("invalidInput");
			return labelArr[i];
		}
	}
	return 0;
}
function order_cancel(){
	$.modal.close();
}
interfaceObject.prototype.Init = function () {
	for (i=0;i<this.elements.length;i++)
	{
			if (this.elements[i].type  == "hidden" || this.elements[i].type  == "text" || this.elements[i].type  == "password")
			{
				this.elements[i].value = "";
			}
	}
}

function orderClass() {

	this.OrderForm		= new interfaceObject("order_form");
	this.ajax	= new ajaxObject(this.callAfter);
};
orderClass.prototype = {
	init : function () {
	},
	sendRequest : function (url) {
		order.ajax.loadXML(url);
	},
	order_data_store : function () {
		var msg = "";
		var chkResult = checkInputValue();
		var userChk = $("#user_accept").attr("checked");
		if(chkResult != 0) {
			msg = "* Please enter "+chkResult;
			$("#messageDiv").html(msg);
			return false;
		}
		if(!userChk) {
			msg = "You must agree to the terms and conditions.";
			$("#messageDiv").html(msg);
			return false;
		}
		insertExtraUrl = order.OrderForm.getExtraUrl()+"&option=orderDataStore";
		insertExtraFaxUrl = order.OrderForm.getExtraUrl()+"&option=sendOrderFax";
		progressControl(true);
		order.sendRequest(orderUrl+insertExtraUrl);
	},
	send_fax : function () {
		insertExtraUrl = order.OrderForm.getExtraUrl()+"&option=sendOrderFax";
		//order.sendRequest(orderUrl+insertExtraUrl);
	},
	callAfter : function (p) {
		if (p.sub_exists("order_store")){
			var result = p.sub("order_store").content();
			progressControl(false);
			if(result==1) {
				alert("success store!");
				$("#send_fax").attr("src", orderUrl+insertExtraFaxUrl);
				$(".selected_product").fadeOut("flow");
				$("#total_selected_sum").text("0");
				window.setTimeout(function(){$(".selected_product").remove();},800);
			}
			else alert("Error store!");
			$.modal.close();
			
		}
		return;
	}
}
var order = new orderClass();
order.init();
$(document).ready(function(e) {
	$("input[name=postcode]").autocomplete(orderUrl+"&option=postcode_complete", {
		width: 100,
		matchContains: true,
		selectFirst: false
	});
	$("input[name=postcode]").keypress(function(){
		$.post(orderUrl,
			{option:"deliveryCharge",postcode:$("input[name=postcode]").val()},
			function(result){
				$("input[name=delivery_charge]").val(result);
				var charge = 0;
				if(!isNaN(parseFloat(result))) charge = parseFloat(result);
				var total_val = charge + parseFloat($("input[name=total]").val());
				$("#total_price").html(total_val);
		});
	});
});