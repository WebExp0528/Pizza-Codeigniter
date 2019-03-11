$(document).ready(function() {
	$("#selected_offer0").removeClass("hide");
	$("#offer_category0").removeClass("hide");
	$("#current_step").val("0");
	//$("#offer_topping0").removeClass("none");
	$(".offer_select_radio").click(function(){
		var offer_id = $(this).attr("name");
		$("#offer_select_val"+offer_id).val($(this).val());
	});
	$("#next_step").click(function(){
		var cur_step = $("#current_step").val();
		gotoNext(cur_step);
	});
	$("#add_special_offer").click(function(){
		product_ids = new Array();
		topping_ids = new Array();
		$(".added_product").each(function(){
			var index = $(this).attr("index");
			var product_id = $(this).attr("alt");
			product_ids[index] = product_id;
		});
		$(".offer_topping_list").each(function(){
			topping_id = new Array();
			$(this).find(".added_topping").each(function(){
				topping_id.push($(this).attr("alt"));
			});
			var index = $(this).attr("index");
			topping_ids[index] = topping_id.join(" ");
		});
		var total_cat_num = $("#total_cat_num").val();
		offers = new Array();
		for(i=0;i<total_cat_num;i++){
			if(product_ids[i] == undefined) continue;
			offers[i] = product_ids[i] + ":" + topping_ids[i];
		}
		var offer_id = $("#offer_id").val();
		var offers_str = offers.join("|");
		if(product_ids.length != total_cat_num) return;
		window.parent.that.addSpecial_offer(offer_id,offers_str);
		$.modal.close();
	});
});
function select_offer_product(index,product_id,product_name,topping_count){
	var total_cat_num = $("#total_cat_num").val();
	if((index+1) == total_cat_num){
		$("#add_special_offer").addClass("active");
	}
	var str = "<span>";
	str += '<a href="javascript:void(0)" class="added_product" alt="'+product_id+'" index="'+index+'">';
	str += product_name+',</a></span>';
	$("#selected_offer_product"+index).html(str);
	$("#selected_offer_product_value_"+index).val(product_id);
	if(topping_count>0){
		$("#selected_topping"+index).fadeIn();
		$("#offer_category"+index).hide();
		$("#offer_topping"+index).fadeIn();
		$("#next_step").addClass("active");
	} else {
		gotoNext(index);
	}
}
function select_topping(index,topping_id,name,price){
	var str = "<span>";
	str += '<a href="javascript:void(0)" class="added_topping" onclick="remove_topping(this)" alt="'+topping_id+'" price="'+price+'">';
	str += name+',</a></span>';
	$("#selected_offer_topping"+index).append(str);
	calc_topping_price();
}
function gotoNext(index){
	$("#next_step").removeClass("active");
	var cur_val = $("#selected_offer_product_value_"+index).val();
	if(!cur_val) return;
	var total_cat_num = $("#total_cat_num").val();
	var next_index = parseInt(index) + 1;
	$("#current_step").val(next_index);
	if(total_cat_num != next_index){
		$("#selected_offer"+next_index).fadeIn();
		$("#offer_category"+index).hide();
		$("#offer_topping"+index).hide();
		$("#offer_category"+next_index).fadeIn();
	}
}
function remove_topping(obj){
	$(obj).fadeOut();
	window.setTimeout(function(){$(obj).parent().remove();calc_topping_price();},500);
}
function calc_topping_price(){
	var topping_price = 0;
	var offer_price = parseFloat($("#offer_price").text());
	$(".offer_topping_list").each(function(){
		var sub_topping_price = 0;
		$(this).find(".added_topping").each(function(){
			sub_topping_price += parseFloat($(this).attr("price"));
		});
		sub_topping_price = Math.round(sub_topping_price*100)/100;
		$(this).find(".sub_topping_price").html(sub_topping_price);
	});
	$(".added_topping").each(function(){
		var price = parseFloat($(this).attr("price"));
		if(isNaN(price)) price = 0;
		topping_price += price;
	});
	if(!isNaN(offer_price)) {
		offer_price += topping_price;
		offer_price = Math.round(offer_price*100)/100;
	}
	$("#total_offer_price").text(offer_price);
}
