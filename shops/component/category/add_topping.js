function chk_topping(id,name,price){
	var elms = document.getElementById("modal_add_topping").getElementsByTagName("a");
	var length = elms.length;
	var str = "<span>";
	str += '<a href="javascript:void(0)" class="added_topping" onclick="remove_topping(this)" id="'+id+'" alt="'+id+'" price="'+price+'">';
//	if(length>0) str +=",";
	str += name+',</a></span>';
	$("#modal_add_topping").append(str);
	calc_topping_price();
}
function remove_topping(obj){
	$(obj).fadeOut("slow");
	window.setTimeout(function(){$(obj).parent().remove();calc_topping_price();},500);
	
}
function add_cart_topping(pid,sid){
	var elms = document.getElementById("modal_add_topping").getElementsByTagName("a");
	var ids = "";
	var length = elms.length;
	for(var i=0; i<length; i++) {
		var topping_id = price = $(elms[i]).attr("alt");
		if(ids=="") ids = topping_id;
		else ids += ":"+ topping_id;
	}
	window.parent.that.addTopping(pid,sid,ids);
	//if(ids != "") window.parent.that.clickProduct(pid,sid,ids);
	$.modal.close();
}
function calc_topping_price(){
	var elms = document.getElementById("modal_add_topping").getElementsByTagName("a");
	var product_price = total_price = parseFloat($("#product_price").text());
	var topping_price = 0;
//	var total_price = parseFloat($("#added_topping_price").text());
	var length = elms.length;
	for(var i=0; i<length; i++) {
		price = parseFloat($(elms[i]).attr("price"));
		topping_price += price;
	}
	if(!isNaN(topping_price)) {
		total_price += topping_price;
		topping_price = "€ "+Math.round(topping_price*100)/100;
	} else topping_price = "";
	$("#added_topping_price").html(topping_price);
	$("#added_topping_sum_price").html(Math.round(total_price*100)/100);
}
calc_topping_price();
