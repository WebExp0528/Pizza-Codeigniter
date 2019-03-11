$(document).ready(function() {
	$('.datepicker')
	.datepicker({
		showAnim:'fadeIn', // show|slideDown|fadeIn
		changeYear:true,
		showOtherMonths:true,
		changeMonth:true,
		selectOtherMonths: true,
		showButtonPanel:true,
		gotoCurrent: true ,
		dateFormat: 'yy-mm-dd', firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true
	});
	$(".side_menu").click(function(){
		$(this).addClass("active");
	});
	// pagination 
	$('#id_first').click(function(){
		$('#id_page').val(1);
		$('#id_mode').val('');
		$('form').submit();
	});
	$('#id_prev').click(function(){
		var curpage = $('#id_page').val();
		if(curpage > 1){
			curpage--;
			$('#id_page').val(curpage);
			$('#id_mode').val('');
			$('form').submit();
		}
	});
	$('#id_next').click(function(){
		var curpage = $('#id_page').val();
		var totalpages = $('#id_totalpages').val();
		if(curpage < totalpages){
			curpage++;
			$('#id_page').val(curpage);
			$('#id_mode').val('');
			$('form').submit();
		}
	});
	$('#id_last').click(function(){
		var totalpages = $('#id_totalpages').val();
		$('#id_page').val(totalpages);
		$('#id_mode').val('');
		$('form').submit();
	});
	$('a.pagination').click(function(){
		var page = $(this).attr("alt");
		$('#id_page').val(page);
		$('#id_mode').val('');
		$('form').submit();
	});

});
