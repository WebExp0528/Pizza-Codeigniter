
<script type="text/javascript">
	$(document).ready(function(e) {
        $('#btn_delete').bind("click", null, function(){
			if(confirm("Do you want to delete the record(s)?")){
				$('#form1').submit();
			}
		});
		$('#chk').bind("change", null, function(){
			if($('#chk').attr('checked'))
			{
				$('.cls_chk').attr('checked','checked');
			} else {
				$('.cls_chk').removeAttr('checked');
			}
		})
		$('#btn_add').click(function(){
			if ($("#datepicker").val() == '') {
				$("#datepicker").focus();
				return;
			}
			$('#mode').val('add');
			$('#form1').submit();
		});
		$("#datepicker").datepicker({
			dateFormat:'yy/mm/dd'
		});
		$('#icon_calendar').click(function () {
			$( "#datepicker" ).trigger('focus');
		});
		$("#datepicker_edit").datepicker({
			dateFormat:'yy/mm/dd'
		});
		$('#icon_calendar_1').click(function () {
			$( "#datepicker_edit" ).trigger('focus');
		});
		$("#dialog-form").dialog({
			autoOpen: false,
			resizable: false,
			height:180,
			modal: true,
			buttons: {
				"Save": function() {
					$('#mode').val('edit');
					$.post("<?php echo base_url('admin/calendar-edit');?>", {
						pid:$('#pid').val(), 
						date:$('#datepicker_edit').val(), 
						type:$('#update_type').val()
						}, function(result){
						location.href="<?php echo base_url('admin/calendar');?>";
					});
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
			}
		});
    });
    
    function modify(id, date, type)
    {
    	$( "#datepicker_edit" ).datepicker( "setDate", date );
    	$( "#update_type" ).val(type);
    	$( "#pid" ).val(id);
    	$( "#dialog-form" ).dialog('open');
    }
</script>
	<div class="content">
	<?php	
	echo put_alert($process_flag);
	echo form_open('admin/calendar', array('method' => 'post', 'id' => 'form1'));
	?>
    	<div>
	    	<h1>Calendar</h1>
		</div>
      	<div style="margin-left:40px; margin-bottom: 5px;">
            <a href="#"><?php echo img(array('src' => 'images/admin/delete.gif', 'id' => 'btn_delete', 'border' => 0, 'title' => 'Delete'));?></a>
        </div>
		<div style="margin-left:15px; padding-left:15px;">
        	<table width="60%" class="small_list">
            	<tr class="tr_header">
                	<td width="5%"><?php echo form_checkbox('chk', '', false, 'id="chk"');?></td>
                	<td width="10%">No</td>
                	<td width="40%">Date</td>
                	<td width="15%">Type</td>
                	<td width="25%"></td>
                </tr>
            	<tr bgcolor="#FCC">
                	<td colspan="2"></td>
                	<td><?php echo form_input('add_date', '', 'id="datepicker" placeholder="Select date!"'); echo img(array('src' => base_url('images/calendar.gif'), 'id' => 'icon_calendar'));?></td>
                	<td><?php echo form_dropdown('sel_type', array('holiday' => 'holiday', 'closed' => 'closed'));?></td>
                	<td><?php echo form_button('btn_add', 'Add', 'id="btn_add"');?></td>
                </tr>
                <?php
				if ($result->num_rows() > 0)
				{
					$no = 0;
					//$style = 'odd';
					//$cur_mon = '';
					foreach ($result->result() as $data)
					{
						$no++;
						if ($data->type == 'holiday') {
							$style = 'style="color:#F00;"';
						} else {
							$style = 'style="color:#333;"';
						}
							?>
            	<tr class="td_value">
                	<td><?php echo form_checkbox('chks[]', $data->id, false, 'class="cls_chk"');?></td>
                	<td><?php echo $no; ?></td>
                	<td class="td_value"><?php echo $data->day; ?> / <?php echo $data->month; ?></td>
                	<td align="center" <?php echo $style;?>><?php echo $data->type;?></td>
                	<td><?php echo form_button('btn_edit', 'Edit', 'id="btn_edit" onclick="modify(\''.$data->id.'\', \''.(date('Y').'/'.$data->month.'/'.$data->day).'\', \''.$data->type.'\')"');?></td>
                </tr>
                <?php
						}
					} else {
				?>
            	<tr class="td_value">
                	<td colspan="6" style="text-align:center;">
                    	no data
                    </td>
				</tr>
                <?php
					}
				?>
           </table>
        </div>
		<div id="dialog-form" title="Modify data">
			<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
			<fieldset>
				<label for="update_date">Date: </label>
				<?php echo form_input('update_date', '', 'id="datepicker_edit" placeholder="Select date!"'); 
				echo img(array('src' => base_url('images/calendar.gif'), 'id' => 'icon_calendar_1'));?><br/><br/>
				<label for="update_type">Type: </label>
				<?php echo form_dropdown('update_type', array('holiday' => 'holiday', 'closed' => 'closed'), 'holiday', 'id="update_type"');?>
			</fieldset>
		</div>
	    <!-- end .content -->
       	<input type="hidden" value="" name="pid" id="pid" />
        <input type="hidden" value="delete" name="mode" id="mode" />
     <?php echo form_close();?>
	</div>
	