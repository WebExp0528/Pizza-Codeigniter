<script type="text/javascript">
	$(document).ready(function(e) {
		$('.sendFax').click(function(){
			var filename = $(this).attr("id");
			if(!confirm("Are you sure resend fax?")) return false;
			$.post("<?php echo site_url().'ajax/fax-data-send'?>",
				{filename:filename},
				function(result){
					if(result == "1") alert("success send")
					else alert("error");
			});
		});
	});
</script>
	<div class="content">
	<?php echo form_open('admin/faxdata', array('method' => 'post', 'id' => 'form1'));?>
    	<div>
	        <h1>Fax Datas</h1>
		</div>
		<div style="clear:both"></div>
		<div style="margin-left:15px;">
		<table class="list">
			<tr style="background-color:#E7EFEF; height:30px;">
				<td width="5%">No</td>
				<td width="40%">
					File Name
				</td>
				<td width="25%">
					Created Date
				</td>
				<td width="30%">
					Send Fax
				</td>
			</tr>
			<?php 
			for($i=0;$i<count($files);$i++){
				$file = $files[$i];
				?>
			<tr>
				<td align="center"><?php echo $i+1;?></td>
				<td>
					<a href="<?php echo site_url().'ajax/fax-data-open/'.$file["path"];?>"><?php echo $file["name"];?></a>
				</td>
				<td><?php echo date("Y-m-d H:i:s",$file["time"]);?></td>
				<td>
					<a href="javascript:void(0)" class="sendFax" id="<?php echo $file["name"];?>">Send Fax this file</a>
				</td>
			</tr>
			<?php }?>
	   </table>
        </div>
        <?php echo form_close();?>
    <!-- end .content -->
    </div>
