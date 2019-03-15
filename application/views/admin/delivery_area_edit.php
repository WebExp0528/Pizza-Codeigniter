<script type="text/javascript">
	$(document).ready(function(e) {
		$('#form1').validate({
			rules: {
				postcode: {
					required: true,
					number: true
				},
				price:{
					required: true,
					number:true
				},
				delivery_charge:{
					required: true
				}
			}
		});

		$('#region_name').change(function(e) {
			$('#id_city').empty();
			$('#id_postcode').empty();
			$('#id_city').append('<option value="">(Select City)</option>');
			$('#id_postcode').append('<option value="">(Select Postcode)</option>');
//alert($(this).val());
			$.post("<?php echo site_url().'ajax/get_city_name'?>", {region_name:$(this).val()}, function(result){

				var obj = $.parseJSON(result);

				$.each(obj, function(i, tweet){
					$('#id_city').append('<option value="' + tweet.name + '">' + tweet.name + '</option>');
				});
				
			});
		});

		$('#id_city').change(function(e) {
			$('#id_postcode').empty();
			$('#id_postcode').append('<option value="">(Select Postcode)</option>');
			$.post("<?php echo site_url().'ajax/get_postcode'?>", {city_name:$(this).val()}, function(result){
				var obj = $.parseJSON(result);

				$.each(obj, function(i, tweet){
					$('#id_postcode').append('<option value="' + tweet.name + '">' + tweet.name + '</option>');
				});
				
			});
		});
        $('#btn_save').bind("click", null, function(){
			$('#form1').submit();
		});
		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/delivery_area'?>";
		});
	});
</script>
	<div class="content">
<?php
	echo put_alert($process_flag);
	echo form_open_multipart('admin/delivery-area-edit/'.$contents->id, array('method' => 'post', 'id' => 'form1'));
	echo form_hidden('mode', 'update');
?>
    <form method="post" name="form1" enctype="multipart/form-data" id="form1">
    	<div>
	    	<h1>Edit Delivery Area</h1>
		</div>
		<div style="margin-left:15px;">
            <div style="margin:0 50px 10px 0; float:right;">
            	<input type="button" value="Save" id="btn_save" />
            	<input type="button" value="Back" id="btn_cancel" class="btn" />
            </div>
            <div style="clear:both">
                <table width="76%" class="list">
                    <tr>
                        <td width="26%" class="td_header">Post Code</td>
                        <td width="74%" class="td_value">
	                        <input type="text" name="postcode" value="<?php echo $contents->postcode;?>" id="postcode" readonly />
                        </td>
                    </tr>
                    <tr>
                        <td class="td_header">
                            Price
                        </td>
                        <td class="td_value">
                        	<input type="text" name="price" value="<?php echo $contents->price;?>" id="price" />
                        </td>
                    </tr>
                    <tr>
                        <td class="td_header">
                            Deliver Charge
                        </td>
                        <td class="td_value">
                        	<input type="text" name="delivery_charge" value="<?php echo $contents->delivery_charge;?>" id="delivery_charge" maxlength="5"/>
                        </td>
                    </tr>
               </table>
			</div>
        </div>
    </form>
	</div>
  <!-- end .container -->
