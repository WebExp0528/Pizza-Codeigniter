<script type="text/javascript">
	$(document).ready(function(e) {
		$('#btn_save').bind("click", null, function(){
			var css_file = $('#css_uploaded').val();
			if(css_file != ""){
				var check = validCSSFile(css_file);
				if(!check) {$('#css_uploaded').val("");return;}
			}
			$('#form1').submit();
		});
		$('#css_uploaded').change(function() {
			var filename = $(this).val();
			var check = validCSSFile(filename);
			if(!check) $(this).val("");
		});
		$('.DeleteCSS').click(function(){
			var filename = $(this).attr("id");
			if(!confirm("Are you sure delete?")) return;
			$.post("<?php echo site_url().'ajax/delete-css'?>",
				{filename:filename},
				function(result){
					if(result == "1") {
						document.location.href = "<?php echo site_url().'admin/admin_setting';?>";
					}
			});
		});
	});
	function validCSSFile(filename){
		if(filename.toLowerCase() == "default.css") {
			alert('Select oOther css file.\nfilename is cannot "default.css"');
			return false;
		}
		var ext = filename.substring(filename.length-3,filename.length);
		ext = ext.toLowerCase();
		if(ext != 'css') {
			alert('Select correct css file.');
			return false;
		}
		return true;
	}
	
</script>
	<div class="content">
	<?php 
	echo put_alert($process_flag);
	echo form_open_multipart('admin/admin_setting', array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
	echo form_hidden('mode', 'add');
	?>
		<div>
			<h1>Setting</h1>
		</div>
		<div style="margin-left:10px;">
			<div style="float:right; margin-right:50px;">
				
			</div>
			<div class="clearboth">
			</div>
			<div>
				<table class="list">
					<tr>
						<td class="td_header">
							Select CSS File 
						</td>
						<td class="td_value">
							<?php echo form_upload('css_uploaded', '', 'id="css_uploaded"');?>
							<?php echo form_button('btn_save', 'Upload', 'style="float:right; margin-right:50px;" id="btn_save"');?>
						</td>
					</tr>
					<tr>
						<td class="td_header">CSS Files</td>
						<td class="td_value">
							<table class="list" style="clear:both">
								<tr class="td_header">
									<td width="5%" align="center">No</td>
									<td width="35%" align="center">File Name</td>
									<td width="30%" align="center">Created Date</td>
									<td width="30%" align="center">Delete</td>
								</tr>
								<?php 
								for ($i = 0; $i < count($files); $i++)
								{
									$file = $files[$i];
									?>
								<tr>
									<td align="center"><?php echo $i + 1;?></td>
									<td><?php echo $file['name'];?></td>
									<td><?php echo date("Y-m-d H:i:s", $file['time']);?></td>
									<td align="center">
										<?php 
										if($i==0) echo "Default CSS cannot delete";
										else {
										?>
										<a href="javascript:void(0)" class="DeleteCSS" id="<?php echo $file['name'];?>">Delete this file</a>
										<?php }?>
									</td>
								</tr>
								<?php }?>
							</table>

						</td>
					</tr>
				</table>
			</div>
		</div>
	<!-- end .content -->
	<?php echo form_close();?>
	</div>
