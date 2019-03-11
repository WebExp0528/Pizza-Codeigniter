//var shopid = $("input[name=shop_id]").val();
var feedbackUrl = commonUrl + "index.php?task=feedback&request=feedback&special=1";
function order_checkout(){
	var total = $("#total_selected_sum").html();
	var min_price = parseFloat($("#min_price").html());
	if(total<min_price) {alert("The minimum order value is "+min_price+" €");return false;}
	var checkout_url = commonUrl + "index.php?task=order&request=order&special=3";
	$('#osx-modal-data').load(checkout_url);
	$("#osx-modal-title").html("Review the order and submit");
	$('#osx-modal-content').modal({
		overlayId: 'osx-overlay',
		containerId: 'osx-container',
		closeHTML: null,
		minHeight: 80,
		opacity: 65, 
		containerCss: {width:800,height: 600,overflow:'auto'}, 
		escClose: false,
		onOpen: OSX.open,
		onClose: OSX.close
	});
}
function add_topping_modal(obj,pid,sid,name){
	var checkout_url = commonUrl + "index.php?task=category&request=add_topping&special=3&pid="+pid+"&sid="+sid;
	$('#osx-modal-data').load(checkout_url);
	$("#osx-modal-title").html(name+" - Add Topping");
	$('#osx-modal-content').modal({
		overlayId: 'osx-overlay',
		containerId: 'osx-container',
		closeHTML: null,
		minHeight: 80,
		opacity: 65, 
		containerCss: {width:650,height: 500,overflow:'auto'}, 
		escClose: false,
		onOpen: OSX.open,
		onClose: OSX.close
	});
}

function viewClass() {
	this.Interface		= new interfaceObject();
	//this.OrderForm		= new interfaceObject("order_form");
	this.ajax	= new ajaxObject(this.callAfter);
};
viewClass.prototype = {
	init : function () {
		//var cat_id = $("#cat_id").val();
//		if(op == "category") that.select_category(cat_id);
//		else if (op == "offer") that.select_offer(cat_id);
//		that.order_data_restore();
	},
	sendRequest : function (url) {
		that.ajax.loadXML(url);
	},
	//
	order_data_restore : function () {
		insertExtraUrl = "&option=sessionOrderData";
		progressControl(true);
		that.sendRequest(feedbackUrl+insertExtraUrl);
	},
	select_category : function (cid) {
		insertExtraUrl = "&catid="+cid+"&option=selcetCategory";
		progressControl(true);
		that.sendRequest(feedbackUrl+insertExtraUrl);
	},
	select_offer : function (cid) {
		insertExtraUrl = "&catid="+cid+"&option=selectOffer";
		progressControl(true);
		that.sendRequest(feedbackUrl+insertExtraUrl);
	},
	clickProduct : function (pid,sid) {
		insertExtraUrl = "&product_id="+pid+"&size_id="+sid+"&option=selectProduct";
		progressControl(true);
		that.sendRequest(feedbackUrl+insertExtraUrl);
	},
	addTopping : function (pid,sid,topping_ids) {
		if(topping_ids==undefined) topping_ids = "";
		insertExtraUrl = "&product_id="+pid+"&size_id="+sid+"&topping_ids="+topping_ids+"&option=addTopping";
		progressControl(true);
		that.sendRequest(feedbackUrl+insertExtraUrl);
	},
	addSpecial_offer : function (offer_id,offers_str,table_id) {
		if(table_id == undefined) table_id = "";
		insertExtraUrl = "&offer_id="+offer_id+"&offers_str="+offers_str+"&table_id="+table_id+"&option=addSpecial_offer";
		progressControl(true);
		that.sendRequest(feedbackUrl+insertExtraUrl);
	},
	remove_product : function (obj,pid,sid){
		var count = parseInt($('#product_'+pid+'_'+sid+' .count').html());
		count--;
		if(count==0) {
			var tableObj = $('#product_'+pid+'_'+sid);
			$(tableObj).fadeOut("fast");
			window.setTimeout(function(){$(tableObj).remove();that.calcOrderSum();},800);
		} else $('#product_'+pid+'_'+sid+' .count').text(count);
		insertExtraUrl = "&product_id="+pid+"&size_id="+sid+"&option=removeProduct";
		progressControl(true);
		that.sendRequest(feedbackUrl+insertExtraUrl);
	},
	removeOffer : function (offer_id,offers_str,table_id){
		var count = parseInt($('#'+table_id+' .count').html());
		count--;
		if(count==0) {
			var tableObj = $('#'+table_id);
			$(tableObj).fadeOut("fast");
			window.setTimeout(function(){$(tableObj).remove();that.calcOrderSum();},800);
		} else $('#'+table_id+' .count').text(count);
		insertExtraUrl = "&offer_id="+offer_id+"&offers_str="+offers_str+"&option=removeOffer";
		progressControl(true);
		that.sendRequest(feedbackUrl+insertExtraUrl);
	},
	calcOrderSum : function () {
		var elms = document.getElementById("order_list").getElementsByTagName("td");
		var sum = count = price = 0;
		var length = elms.length;
		for(var i=0; i<length; i++) {
			var classes = elms[i].className;
			if(classes == "product_prce"){
				price = parseFloat(elms[i].innerHTML);
			}
			if(classes == "count") {
				count = parseInt(elms[i].innerHTML);
				sum += price*count;
			}
		}
		
		$("#total_selected_sum").html(Math.round(sum*100)/100);
	},
	callAfter : function (p) {
		if (p.sub_exists("sessionOrderData")){
			progressControl(false);
			var html = p.sub("orderlist").content();
			$("#order_title_separater").after(html);
			that.calcOrderSum();
		}
		if (p.sub_exists("product"))
		{
			progressControl(false);
			var id = p.sub("id").content();
			var name = p.sub("name").content();
			var price = p.sub("price").content();
			var size = p.sub("size").content();
			var size_id = p.sub("size_id").content();
			var size_detail = p.sub("size_detail").content();
			var count = p.sub("count").content();
			var topping = p.sub("topping").content();
			var table_id = 'product_'+id+'_'+size_id;
			if(count==1){
				var html = '<table class="selected_product wide" style="display:none" id="'+table_id+'">';
				html += '<tr><td class="thin"></td>';
				html += '<td class="select_arrow"></td>';
				html += '<td class="w66" colspan="3"><span class="product_title">'+name+'</span></td>';
				html += '<td class="product_prce">'+price+'</td>';
				html += '<td class="thin">€</td>';
				html += '</tr>';
				html += '<tr><td></td>';
				html += '<td style="float:center;"><a href="javascript:void(0)" class="add" onclick="that.clickProduct('+id+','+size_id+')"></a>';
				html += '<a href="javascript:void(0)" class="remove" onclick="that.remove_product(this,'+id+','+size_id+')"></a></td>';
				html += '<td class="count">'+count+'</td>';
				html += '<td>';
				if(size) html += '<span class="description">'+size+'&nbsp;'+size_detail+'</span>';
				html += '</td>';
				html += '<td >';
				if(topping == 1) html += '<a href="javascript:void(0)" class="add_topping" onclick="add_topping_modal(this,'+id+','+size_id+',\''+name+'\')" style="width:10px;">Add Topping</a>';
				html += '</td>';
				html += '<td colspan="2"></td>';
				html += '</tr>';
				html += '<tr><td colspan="2"></td><td colspan=5 class="topping_data">';
				html += '</td></tr>';
				html += '</table>';
				$("#order_title_separater").after(html);
				$("#"+table_id).fadeIn("slow");
			} else {
				$('#product_'+id+'_'+size_id+' .count').fadeOut("fast");
				$('#product_'+id+'_'+size_id+' .count').fadeIn("fast");
				$('#product_'+id+'_'+size_id+' .count').text(count);
				//$('#product_'+id+'_'+size_id+' .product_prce').text(price);
			}
			that.calcOrderSum();
		}
		if (p.sub_exists("special_offer"))
		{
			progressControl(false);
			var offer_id = p.sub("id").content();
			var name = p.sub("name").content();
			var offers_str = p.sub("offers_str").content();
			var price = p.sub("price").content();
			var count = p.sub("count").content();
			var table_str = p.sub("table_str").content();
			var table_id = p.sub("table_id").content();
			if(count==1){
				$("#order_title_separater").after(table_str);
				$("#"+table_id).fadeIn("slow");
			} else {
				$('#'+table_id+' .count').fadeOut("fast");
				$('#'+table_id+' .count').fadeIn("fast");
				$('#'+table_id+' .count').text(count);
			}
			that.calcOrderSum();
		}
		if (p.sub_exists("toppinglist"))
		{
			progressControl(false);
			var id = p.sub("id").content();
			var size_id = p.sub("size_id").content();
			var price = p.sub("price").content();
			var toppinglist = p.sub("toppinglist").content();
			var table_id = 'product_'+id+'_'+size_id;
			$('#product_'+id+'_'+size_id+' .topping_data').fadeOut("fast");
			$('#product_'+id+'_'+size_id+' .topping_data').html(toppinglist);
			$('#product_'+id+'_'+size_id+' .topping_data').fadeIn("fast");
			$('#product_'+id+'_'+size_id+' .product_prce').html(price);
			that.calcOrderSum();
		}
		if (p.sub_exists("category")){
			progressControl(false);
			var id = p.sub("id").content();
			var name = p.sub("name").content();
			var image_url = p.sub("image_url").content();
			var productList = p.sub("productlist").content();
			$("#category_img").css("display","none");
			$("#category_img_frame").css("display","");
			if(image_url == ""){
				$("#category_img").attr("src","");

			} else {
				$("#category_img").attr("src",image_url);
				$("#category_img").fadeIn("slow");
			}
			$("#product_list").html(productList);
			$("#product_list").css("padding-top","180px");
			$("#product_list").css("display","none");
			$("#product_list").fadeIn("slow");
		}
		if (p.sub_exists("product_remove")){
			progressControl(false);
			that.calcOrderSum();
		}
		if (p.sub_exists("impress")){
			progressControl(false);
			var impress = p.sub("impress").content();
			$("#category_img").css("display","none");
			$("#category_img_frame").css("display","none");
			$("#product_list").css("padding-top","0px");
			$("#product_list").html(impress);
			$("#product_list").css("display","none");
			$("#product_list").fadeIn("slow");
		}

		return;
	}
}
var that = new viewClass();
that.init();
$(document).ready(function(){
	$('.rating').each(function(){
		$(this).jRating({
			isDisabled : true
		});
	});

});