<script type="text/javascript">
	$(document).ready(function(e) {
		$('.sendFax').click(function(){
			var filename = $(this).attr("id");
			if(!confirm("Are you sure resend fax?")) return false;
			$.post("<?php echo site_url().'ajax/fax-data-send';?>",
				{filename:filename},
				function(result){
					if(result == "1") alert("success send")
					else alert("error");
			});
		});
		$('.deleteFaxData').click(function(){
			var filename = $(this).attr("id");
			if(!confirm("Are you sure delete?")) return false;
			$.post("<?php echo site_url().'ajax/fax-data-delete';?>",
				{filename:filename},
				function(result){
					if(result == "1") 
						$("#form1").submit();
			});
		});
		$('select[name=shop]').change(function(){
			$("#form1").submit();
		});
	});
</script>
	<div class="content">
    	<?php echo form_open('admin/admin_faxdata', array('method' => 'post', 'id' => 'form1'));?>
    	<div>
	        <h1>Fax Datas</h1>
		</div>
		<div style="margin:15px">
			Select the Shop : 
			<?php 
			$shops = get_shops();
			$shop_names = array(0 => 'All shops');
			foreach ($shops->result() as $shop)
			{
				$shop_names[$shop->store_id] = $shop->shop_name;
			}
			echo form_dropdown('shop', $shop_names, array($keys['shop']));
			?>
		</div>
		<div style="clear:both"></div>
		<div style="margin-left:15px;">
		<table class="list">
			<tr style="background-color:#E7EFEF; height:30px;">
				<td width="5%">No</td>
				<td width="10%">Shop</td>
				<td width="40%">File Name</td>
				<td width="20%">Created Date</td>
				<td width="18%">Send Fax</td>
				<td width="12%">Action</td>
			</tr>
			<?php 
			for ($i = 0; $i < count($files); $i++)
			{
				$file = $files[$i];
				?>
			<tr>
				<td align="center"><?php echo $i+1;?></td>
				<td>
					<?php echo $shop_names[$file['shop_code']];?>
				</td>
				<td>
					<?php anchor('fax-data-open/'.$file['path'], $file['name']);?>
				</td>
				<td><?php echo date("Y-m-d H:i:s",$file["time"]);?></td>
				<td>
					<a href="javascript:void(0)" class="sendFax" id="<?php echo $file['name'];?>">Send Fax this file</a>
				</td>
				<td>
					<a href="javascript:void(0)" class="deleteFaxData" id="<?php echo $file['path'];?>">Delete</a>
				</td>
			</tr>
			<?php }?>
	   </table>
        </div>
        <?php echo form_close();?>
    <!-- end .content -->
    </div>
