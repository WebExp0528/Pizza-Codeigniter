<script type="text/javascript">
	$(document).ready(function(e) {
		
		$('#btn_save').bind("click", null, function(){
			$('#form1').submit();
		});
		
		$('#btn_cancel').bind("click", null, function(){
			location.href="order.php";
		});
	});
</script>
	<div class="content">
		<?php	
		echo put_alert($process_flag);
		echo form_open_multipart('admin/user-edit/'.$contents->id, array('method' => 'post', 'id' => 'form1', 'name' => 'form1'));
		echo form_hidden('mode', 'save');
		?>
		<div>
			<h1>Edit Orders</h1>
		</div>
		<div style="margin-left:15px;">
			<div style="float:right; margin-right:50px;">
				<input type="button" value="Save" id="btn_save" />
				<input type="button" value="Back" id="btn_cancel" class="btn" />
			</div>
			<div class="sub_title">
				Order details
			</div>
			<table class="list">
				<tr>
					<td width="35%" class="td_header">Order ID</td>
					<td width="65%" class="td_value"><?php echo $contents->id;?></td>
			   	</tr>
				<tr>
					<td class="td_header">Email</td>
					<td class="td_value"><?php echo $contents->email;?>
					</td>
			   	</tr>
				<tr>
					<td class="td_header">Telephone</td>
					<td class="td_value"><?php echo $contents->telephone;?></td>
				</tr>
				<tr>
					<td class="td_header">Post Code</td>
					<td class="td_value"><?php echo $contents->postcode;?></td>
				</tr>
				<tr>
					<td class="td_header">Date Added</td>
					<td class="td_value"><?php echo $contents->date;?></td>
		   	  </tr>
		   	  <tr>
					<td class="td_header">Order Total</td>
					<td class="td_value"><?php echo $contents->total;?></td>
			  </tr>
				<tr>
					<td class="td_header">Order Status</td>
					<td class="td_value">
						<?php 
						$status = get_order_status();
						$arr_options = array(0 => 'Select');
						foreach ($status->result() as $row) {
							$arr_options[$row->id] = $row->name;
						}
						echo form_dropdown('order_status_id', $arr_options, array($contents->order_status_id), 'id="order_status_id"');
						?>
					</td>
				</tr>
		   </table>
		   
			<div class="sub_title">
				Products
			</div>
			<table class="list">
		   	  <tr class="tr_header">
					<td>Product</td>
					<td>Size</td>
					<td>Quantity</td>
					<td>Unit Price</td>
					<td>Total</td>
			   	</tr>
				<?php
					$Total_Price = 0;
					$rows = explode(";", $contents->order_data);
					for ($i = count($rows) - 1; $i >= 0; $i--)
					{
						$row = explode(",", $rows[$i]);
						if ($row[0] == 1)
						{
							$offer = get_special_offer($row[1], $this->session->userdata['SESS_STORE_ID']);
							$price = $offer[0]->offer_price;
							$productlist .= '';
							$product_ids = explode("|", $row[2]);
							for ($j = 0; $j < count($product_ids); $j++)
							{
								$product_id = explode(":", $product_ids[$j]);
								$product = get_product($product_id[0]);
								$productlist .= '<tr>';
								$productlist .= '<td class="td_value">&emsp; '.$product->name.'</td>';
								$productlist .= '<td> </td>';
								$productlist .= '<td> </td>';
								$productlist .= '<td class="td_value over_line">€ '.$product->price.'</td>';
								$productlist .= '<td> </td>';
								$productlist .= '</tr>';
								$topping_ids = explode(" ", $product_id[1]);
								for ($k = 0; $k < count($topping_ids); $k++)
								{
									if (!$topping_ids[$k]) continue;
									$_t = get_topping_by_id($topping_ids[$k]);
									$productlist .= '<tr class="sub_row">';
									$productlist .= '<td class="td_value">&emsp; '.$_t->topping_name.'</td>';
									$productlist .= '<td> </td>';
									$productlist .= '<td> </td>';
									$productlist .= '<td class="td_value">€ '.$_t->price_add.'</td>';
									$productlist .= '<td> </td>';
									$productlist .= '</tr>';
									$price += $_t->price_add;
								}
							}
							$extra = explode("\n", $offer[0]->extra_product);
							for ($j = 0; $j < count($extra); $j++)
							{
								if (!$extra[$j]) continue;
								$productlist .= '<tr>';
								$productlist .= '<td class="td_value">&emsp; '.$extra[$j].'</td>';
								$productlist .= '<td class="td_value">addition</td>';
								$productlist .= '<td> </td>';
								$productlist .= '<td> </td>';
								$productlist .= '<td> </td>';
								$productlist .= '</tr>';
							}
						?>
							<tr style="font-weight:bold;;background:#f2fbff">
								<td class="td_value"><?php echo $offer[0]->offer_name;?></td>
								<td class="td_value">Special offer</td>
								<td class="td_value"><?php echo $row[3]; ?></td>
								<td class="td_value">€ <?php echo $price; ?></td>
								<td class="td_value">€ <?php echo $price*$row[3]; ?></td>
							</tr>
						<?php
							echo $productlist;
							$sum_price += $price * $row[3];
						}
						else if($row[0] == 2)
						{
							$product = get_product($row[1], $row[2]);
							$price = $product->price;
							$toppinglist = "";
							if (!empty($row[4]))
							{
								$topping_ids = explode(":", $row[4]);
								for($j=0;$j<count($topping_ids);$j++){
									$_t = get_topping_by_id($topping_ids[$j]);
									$toppinglist .= '<tr class="sub_row">';
									$toppinglist .= '<td class="td_value">&emsp; '.$_t->topping_name.'</td>';
									$toppinglist .= '<td> </td>';
									$toppinglist .= '<td> </td>';
									$toppinglist .= '<td class="td_value">€ '.$_t->price_add.'</td>';
									$toppinglist .= '<td> </td>';
									$toppinglist .= '</tr>';

									$price += $_t->price_add;
								}
							}
						?>
							<tr style="font-weight:bold;;background:#f2fbff">
								<td class="td_value"><?php echo $product->name;?></td>
								<td class="td_value"><?php echo $product->size;?></td>
								<td class="td_value"><?php echo $row[3]; ?></td>
								<td class="td_value">€ <?php echo $price; ?></td>
								<td class="td_value">€ <?php echo $price*$row[3]; ?></td>
							</tr>
							<?php
							echo $toppinglist;
							$sum_price += $price * $row[3];
						}
					}
				?>
				<tr>
					<td align="right" colspan="4">Deliver Charge</td>
					<td class="td_value">€<?php echo $contents->delivery_charge;?></td>
			   	</tr>
				<tr>
					<td align="right" colspan="4">Total</td>
					<td class="td_value">€<?php echo $contents->total;?></td>
			   	</tr>
		   </table>
	   	  <div class="sub_title">
				Shipping Address
			</div>
			<table class="list">
				<tr>
					<td width="35%" class="td_header">First Name</td>
					<td width="65%" class="td_value"><?php echo $contents->firstname; ?>/td>
			   	</tr>
				<tr>
					<td width="35%" class="td_header">Last Name</td>
					<td width="65%" class="td_value"><?php echo $contents->lastname; ?></td>
			   	</tr>
				<tr>
			   	  <td class="td_header">Address</td>
					<td class="td_value"><?php echo $contents->city; echo $contents->street; echo $contents->house_no; ?></td>
			   	</tr>
		   </table>
		</div>
		</form>
	<!-- end .content -->
	</div>
