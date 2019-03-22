<script type="text/javascript">
	var checkNumFlag = 1;
	$(document).ready(function(e) {
		$('#form1').validate({
			rules: {
				topping_name: {
					required: true
				},
				price:{
					required: true,
					number: true
				}
			}
		});
        $('#btn_save').bind("click", null, function(){

			$('#form1').submit();

		});
		
		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/topping'?>";
		});
	});
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open_multipart('admin/topping-edit/'.$contents->id, array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'update');
	?>
    	<div>
	    	<h1>Edit Topping Price</h1>
		</div>
		<div style="margin-left:15px;">
            <div style="margin-right:50px; float:right;">
            	<input type="button" value="Save" id="btn_save" />
            	<input type="button" value="Back" id="btn_cancel" class="btn" />
            </div>
            <div>
                <table width="76%" class="list">
                    <tr>
                        <td width="26%" class="td_header">Topping Name : </td>
                        <td width="74%" class="td_value"><input type="text" name="topping_name" value="<?php echo $contents->topping_name; ?>" /></td>
                    </tr>
                    <tr>
                        <td class="td_header">price : </td>
                        <td class="td_value"><input type="text" name="price" value="<?php echo $contents->price_add; ?>" /></td>
                    </tr>
               </table>
			</div>
        </div>
    <!-- end .content -->
    <?php echo form_close();?>
	</div>
