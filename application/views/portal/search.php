<?php
echo doctype('xhtml1-trans');
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo meta('Content-Type', 'text/html; charset=' . $this->config->item('charset'), 'http-equiv');
echo '<title>' . $this->config->item('sitename') . '</title>';
echo link_tag('css/portal.css');
echo link_tag('css/jquery.jRating.css');
echo $this->javascript->external('js/jquery-1.8.3.js');
echo $this->javascript->external('js/jquery.simplemodal.js');
echo $this->javascript->external('js/jquery.placeholder.js');
echo $this->javascript->external('js/jquery.tootip.min.js');
echo $this->javascript->external('js/common.js');

echo '<script language="javascript">var base_url = "'.base_url().'"; var site_url = "'.site_url().'";</script>';
echo $this->javascript->external('js/jquery.jRating.js');
echo '</head>';
?>
<body>
<div class="main_container">
	<div class="container">
	<?php
	echo portal_header();
	?>
		<div class="header_4"><!---- between header and body---></div>
			<div>
				<div class="search_result_alarm">
				<?php
				echo $shop_count . ' ' . $title;
				?>
				</div>
				<div class="search_form"><a href="javascript:make_search_form();">Lieferbereich ändern</a></div>
				<div class="clear-both"></div>
			</div>
			<div class="homepage_body"><!------body------>
				<div class="body_1">
					<div class="body_1_1">
					<?php
					$index = 0;
					if(isset($shops) && $shops->num_rows() > 0)
					{
						foreach($shops->result() as $data)
						{
							$date_data = get_workinghour($data->store_id, mdate('w'));
							
							$working_from1 = strtotime($date_data->workinghour_from);
							$working_from2 = strtotime($date_data->workinghour_from2);
							$working_to1 = strtotime($date_data->workinghour_to);
							$working_to2 = strtotime($date_data->workinghour_to2);
							if(($working_from1 <= now() && now() <= $working_to1) || ($working_from2 <= now() && now() <= $working_to2))
							{
								$is_opened = true;
								$time_color = "#91C701";
								$time_icon = "icon_time_green.png";
							}
							else
							{
								$is_opened = false;
								$time_color = "#857973";
								$time_icon = "icon_time_red.png";
							}
							$total = getTotalRating($data->store_id);
							$total_rate_sum = (!empty($total->sum)) ? $total->sum : 0;
							$total_count = ($total->count > 0) ? $total->count : 1;
							$overage_rating = round($total_rate_sum / $total_count, 1);
							$ratingList = getRatingList($data->store_id, 4);
							$bar_color = array(1 => "#330066", 2 => "#0066cc", 3 => "#006600", 4 => "#ffcc33", 5 => "#d20000");
							
							$index++;
					?>
						<div class="shopRow">
							<div class="shopDetailHeadWrapper">
								<div class="restaurantDetailNamehead">
									<div class="shopName">
										<?php echo $data->shop_name ?>
									</div>
									<div class="shopAddress">
										<?php echo $data->address ?>,&nbsp;&nbsp;<?php echo $data->postcode ?>&nbsp;<?php echo $data->city ?>
									</div>
								</div>
								<div class="restaurantDetailQualityhead">
									<!-- cccc -->
									<?php if($overage_rating > 0){ ?>
									<div class="rating_bg total_rating" title1="Total <?php echo $total_count;?> feedback" effect="slide">
										<div class="rating l_float" data="<?php echo $overage_rating;?>" isDisabled="on"></div>
										<div class="rating_value l_float bold"><?php echo $overage_rating."/5";?></div>
									</div>
									<?php } else {?>
										<div class="rating_text l_float bold">No Feedback</div>
									<?php }?>
									<div class="tooltip hide">
										<div class="c_float w90">
											<div class="l_float w70">
												<div class="label bold">RECENT FEEDBACK</div>
												<?php
													//for($j = 0; $j < count($ratingList); $j++)
													foreach ($ratingList as $row)
													{
														//$row = $ratingList[$j];
														//$date = date("j M Y", strtotime($row->rate_date));
														//$comment = mb_strcut($row->comment, 0, 30, "UTF-8");
												?>
												<div class="l_float w60">
													<div class="rating_bg">
														<div class="rating l_float" data="<?php echo $row->rate_star;?>"></div>
														<div class="rating_value l_float bold"><?php echo $row->rate_star.'/5';?></div>
													</div>
												</div>
												<div class="rating_text l_float w40 bold">
													<?php echo mdate('j M Y', strtotime($row->rate_date));?>
												</div>
												<div class="clear"></div>
												<div class="wide">
													<div class="l_float thin">&nbsp;</div>
													<div class="l_float w90 wrap">
														<?php echo mb_strcut($row->comment, 0, 30, "UTF-8");?>&emsp;
														<span><?php echo $row->customer_name;?></span>
													</div>
												</div>
												<?php }?>
											</div>
											<div class="l_float w30">
												<div class="label bold">DISTRIBUTION</div>
												<div class="bold">Total <?php echo $total_count;?> feedback</div>
												<?php 
												for($j = 5; $j > 0; $j--)
												{
													$subtotal = getTotalRating($data->store_id, $j);
													$width = round($subtotal->count / $total_count * 60);
													?>
													<div class="wide row">
														<div class="l_float w33"><?php echo $j;?>star : </div>
														<div class="l_float w60">
															<div style="width:<?php echo $width;?>px;height:12px;background:<?php echo $bar_color[$j];?>"></div>
														</div>
														<div class="l_float"><?php echo $subtotal->count;?></div>
														<div class="clear"></div>
													</div>
												<?php }?>
												<div>
													<br>
													<?php 
													echo anchor($data->shop_name.'/feedback', ' >> See all feedback', array('class' => 'homepage_link'));
													//echo '<a class="homepage_link" href="'.base_url().'shops/'.$data->store_id.'/feedback"> >> See all feedback</a>';
													?>
													
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="clear-both"></div>
							</div>
							<div class="shopDetailBodyWrapper">
								<div class="shopLogo">
								<?php 
									if($data->image_url != '')
										echo img(array('src' => 'upload/shop_logo/'.$data->image_url, 'width' => 118, 'height' => 68));
									else
										echo img(array('src' => 'images/default_logo.png', 'width' => 118, 'height' => 68));
								?>
								</div>
								<div class="shopInfo">
									<div class="row">
										<?php echo img(array('src' => 'images/icon_cuisine.png'));?>
										&emsp;<?php echo $data->main_key ?>
									</div>
									<div class="shop-open-time row">
										<div style="color:<?php echo $time_color;?>">
											<?php echo img(array('src' => 'images/'.$time_icon, 'border' => 0));?>&emsp;
											<?php
												if($date_data->workinghour_from != $date_data->workinghour_to)
												{
													echo substr($date_data->workinghour_from, 0, 5) . "-" . substr($date_data->workinghour_to, 0, 5);
												}
												if($date_data->workinghour_from2 != $date_data->workinghour_to2)
												{
													echo "&emsp;".substr($date_data->workinghour_from2, 0, 5) . "-" . substr($date_data->workinghour_to2, 0, 5);
												}
											?>
										</div>
										<div class="unshown-time-table absolute hide">
											<div class="rounded_STYLE">
												<div style="border-bottom:1px solid #000; text-align:center; color:#432A1F; font-size:16px;">
													ÖFFNUNGSZEITEN
												</div>
												
												<table width="95%">
												<?php
													$abbr = array('SU','MO','DI','MI','DO','FR','SA');
													for($j = 0; $j < 7; $j++)
													{
														//$date_sql = "select * from oos_workinghours where store_id=$data->store_id and day_number=$j";
														//$date_result = mysql_query($date_sql);
														//$date_data = mysql_fetch_object($date_result);
														$workinghours_data = get_workinghour($data->store_id, $j);
														if(date('w') == $j) $class = 'selected-day';
														else $class = '';
												?>
													<tr class="<?php echo $class;?>">
														<td width="30px"><?php echo $abbr[$j]; ?></td>
														<td>
															<?php
																if($workinghours_data->workinghour_from != $workinghours_data->workinghour_to){
																	echo substr($workinghours_data->workinghour_from, 0, 5) . " - " . substr($workinghours_data->workinghour_to, 0, 5);
																}
																if($workinghours_data->workinghour_from2 != $workinghours_data->workinghour_to2){
																	echo "&emsp;".substr($workinghours_data->workinghour_from2, 0, 5) ." - " . substr($workinghours_data->workinghour_to2, 0, 5);
																}
															?>
														</td>
													</tr>
												<?php
													}
												?>
												</table>
											</div>
										</div>
									</div>
									<div class="row">
										<div style="float:left; margin-right:15px;">
											<?php echo img(array('src' => 'images/icon_cash_payment.png', 'border' => 0, 'title' => 'Barzahlung'));?>
										</div>
										<div style="float:left;color:#857973; font-size:11px; width:180px;line-height:15px;">
											€ <?php echo $data->min_price ?> Mindestbestellmenge
										</div>
										<div class="clear-both"></div>
									</div>
								</div>
								<div>
									<div class="shop-Go">
									<?php
										if($is_opened)
										{
											echo anchor($data->shop_name, '<span class="speisekartenLink_menu">Zum Menü</span>');
											//echo '<a href="'.site_url($data->shop_name).'"><span class="speisekartenLink_menu">Zum Menü</span></a>';
										}
										else
										{
											echo '<a href="javascript:go(\''.base_url('/'.addslashes($data->shop_name).'/').'\');"><span class="speisekartenLink_menu">Vorbestellen</span></a>';
											//echo '<a href="javascript:go(\''.site_url($data->shop_name).'\');"><span class="speisekartenLink_menu">Vorbestellen</span></a>';
										}
									?>
									</div>
									<div class="clear-both"></div>
								</div>
							</div>
							<div class="clear-both"></div>
						</div>
				<?php 
						}
					}
					else 
					{
						echo '<div class="no-data">Sorry, There is no Shop.</div>';
					}
				?>

						<div id="basic-modal-content">
							<div class="alert_inner_window">
								<div class="titleContainer">
									<span class="title">LOGGE DICH EIN</span>
								</div>
								<div class="lightboxContent">
									<div class="fliesstextL">
										Dieses Restaurant ist derzeit geschlossen. Möchtest du vorbestellen?
									</div>
									<a class="buttonSecondary sliceCenter" id="currently_closed_preorder_link" href="#">Vorbestellen
										<!-- <table border="0">
											<tbody>
												<tr>
													<td class="sliceCenter">Vorbestellen</td>
												</tr>
											</tbody>
										</table> -->
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="body_1_2">
						<?php echo img(array('src' => 'images/portal-3.gif', 'border' => 0));?>
					</div>
					<div class="clear-both"></div>
				</div>

				<div class="clear-both"></div>
			</div>
			<div class="body_3">
					<!------- footer bar------>
					<div class="footer_shopname">
						Lieferservice 
						<?php
							if($region != '')
								echo $region;
							if($postcode)
								echo  get_cityname_by_postcode($postcode) . ' >  '.$postcode;
								
						?>
					</div>
			</div>
			<div class="body_4">
				© <?php echo mdate('Y'); ?> my-shopname.com / my shop name
			</div>
		</div>
	</div>
</body>
</html>
