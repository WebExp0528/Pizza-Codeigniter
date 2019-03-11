//var shopid = $("input[name=shop_id]").val();
var orderUrl = commonUrl + "?task=order&request=order&special=1";
$(document).ready(function(e) {
	$('.rating').each(function(){
		var isDisabled = $(this).attr('isDisabled');
		isDisabled = (isDisabled=="on")?true:false;
		$(this).jRating({
			isDisabled : isDisabled
		});
	});
	$(".feedback_set").click(function(){
		var orderid = $(this).attr("orderid");
		var rate_obj = $("#feedback_"+orderid);
		var rate_star = $(rate_obj).find("input[type=radio]:checked").val();
		var rate_comment = $(rate_obj).find("textarea").val();
		if(!rate_star){
			$(rate_obj).find(".messageDiv").text("Please select rating star.");
			return;
		}
		if(!rate_comment){
			$(rate_obj).find(".messageDiv").text("Please enter your comment.");
			return;
		}
		$.post(orderUrl, 
			{
				option:"setFeedback",
				orderid:orderid, 
				rate_star:rate_star,
				rate_comment:rate_comment
			}, 
			function(result){
				if(result == "faild"){
					$(rate_obj).find(".messageDiv").text("Register faild.");
				} else {
					$(rate_obj).children().fadeOut("fast");
					window.setTimeout(function(){
						$(rate_obj).children().remove();
						$(rate_obj).append(result);
						$(rate_obj).children().hide();
						$(rate_obj).children().fadeIn("fast");
						$(rate_obj).find('.rating').each(function(){
							$(this).jRating({
								isDisabled : true
							});
						});
					},300);
				}
		});
	});
});