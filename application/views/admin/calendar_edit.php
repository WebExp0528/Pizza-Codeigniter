<script type="text/javascript">
	$(document).ready(function(e) {
        $('#btn_save').bind("click", null, function(){
        	$('#id_mode').val('save');
        	$('#form1').submit();
		});
        
		$('#btn_cancel').bind("click", null, function(){
			location.href="<?php echo site_url().'admin/calendar'?>";
		});
		
		$('#id-month').bind("change", null, function(){
			$('#id_mode').val('');
			$('#form1').submit();
			//location.href="<?php echo site_url().'admin/calendar-edit/';?>" + $('#id-month').val();
		});
		$('#ho_chk').bind("change", null, function(){
			if($('#ho_chk').attr('checked'))
			{
				$('.cls_ho_chk').attr('checked','checked');
			} else {
				$('.cls_ho_chk').removeAttr('checked');
			}
		})
		$('#op_chk').bind("change", null, function(){
			if($('#op_chk').attr('checked'))
			{
				$('.cls_op_chk').attr('checked','checked');
			} else {
				$('.cls_op_chk').removeAttr('checked');
			}
		})
	});
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open('admin/calendar-edit', array('method' => 'post', 'id' => 'form1'));
	?>
		<input type="hidden" name="mode" id="id_mode" value="" />
    	<div>
	    	<h1>Add Calendar</h1>
		</div>
		<div style="margin-left:15px;">
            <div style="margin-right:50px; float:right;">
            	<input type="button" value="Save" id="btn_save" />
            	<input type="button" value="Back" id="btn_cancel" class="btn" />
            </div>
            <div class="clearboth">
            </div>
            <div>
            	Month
            	<?php 
            	$arr_month = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            	echo form_dropdown('month', $arr_month, $month, 'id="id-month"');
            	?>
            </div>
            <div style="padding-left:15px;">
                <table width="420" class="small_list">
                	<tr class="tr_header">
                    	<td width="100">Day of Month</td>
                        <td width="155"><?php echo form_checkbox('ho_chk', '', false, 'id="ho_chk"');?> Is holiday</td>
                        <td width="155"><?php echo form_checkbox('op_chk', '', false, 'id="op_chk"');?> Is Open</td>
                    </tr>
                    <?php  
						for ($i = 1;$i <= date('t', mktime(0, 0, 0, $month, 1, date('Y')));$i++)
						{
							//$result = get_calendar_by_mon_day($month, $i);
							$h_check = isset($result[$i])? $result[$i]['hod']: 'N';
							$o_check = isset($result[$i])? $result[$i]['opd']: 'N';
							//if($result){
							//	$h_check = $result->is_holiday;
							//	$o_check = $result->is_open;
							//} else {
							//	$h_check = "";
							//	$o_check = "Y";
							//}
					?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo form_checkbox('is_holiday_'.$i, 'Y', ($h_check == 'Y'), 'class="cls_ho_chk"');?></td>
                        <td><?php echo form_checkbox('is_opened_'.$i, 'Y', ($o_check == 'Y'), 'class="cls_op_chk"');?></td>
                    </tr>
                    <?php
						}
					?>
               </table>
			</div>
        </div>
    <!-- end .content -->
    </form>
	</div>
