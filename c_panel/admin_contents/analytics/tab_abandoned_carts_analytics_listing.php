<?
	//$resturant_id = 141;
	$abandoned_carts = new abandoned_carts();
	$abandoned_cartss = $abandoned_carts->get_abandoned_carts($resturant_id);
	

	if(!empty($abandoned_cartss) && mysql_num_rows($abandoned_cartss) > 0) {
		$abandoned_carts->update_abandoned_carts_count($resturant_id, mysql_num_rows($abandoned_cartss));
?>
		<style type="text/css">
		
			table#analytics {
				border: 1px solid #d0d0d0;
				border-radius: 5px;
				float: left;
				margin-right: 15px;
			}
			table#analytics th {
				background-color: #e0e0e0;
				text-align: left;
				font-size: 14px;
			}
			table#analytics>tbody>tr>td, table#analytics>thead>tr>td {
				border-top: 1px solid #d0d0d0;
				padding: 10px;
				vertical-align: top;
			}
			table#analytics tr {
				background-color: #ffffff !important;
			}
			table#analytics .title {
				font-weight: bold;
			}
			
			table#analytics .user_column {
				width: 25%;
			}
			table#analytics .cart_column, table#analytics .reason_column {
				width: 30%;
			}
			table#analytics .device_type_column {
				width: 5%;
			}
			table#analytics .date_time_column {
				width: 10%;
			}
			table#analytics .user_details, table#analytics .cart_details, table#analytics .orders_history {
				margin-top: 5px;
				display: none;
			}
		</style>
		
		<table cellpadding="0" id="analytics" cellspacing="0">
			<thead>
				<tr>
					<td>#</td>
					<td class="title">User</td>
					<td class="title">Cart</td>
					<td class="title">Reason</td>
					<td class="title">Device Type</td>
					<td class="title">Date Time</td>
				</tr>
			</thead>
			<tbody>
				<?
					$count = 1;
					while($abandoned_cart = mysql_fetch_object($abandoned_cartss)) {
					
				?>
						<tr>							
							<td><?=$count++;?></td>
							<td class="user_column">
								<? echo $abandoned_cart->user_email; ?> <br />
								<a href="#" class="view_more" data="user_details">view details</a> - 
								<a href="#" class="view_orders_history" data="orders_history">view orders history</a>
								<? // user and abandoned order details ?>
								<table class="user_details">
									<tr>
										<td><b>Referral Source:</b> <? echo $abandoned_cart->referral_source; ?> </td>
									</tr>
									<tr>
										<td><b>Session Duration:</b> <? echo gmdate("H:i:s", $abandoned_cart->session_duration_in_seconds); ?> </td>
									</tr>
									<tr>
										<td><b>User Last Action:</b> <? echo $abandoned_cart->last_user_action; ?> </td>
									</tr>
								</table>
								
								<? // orders history ?>
								<table width="100%" border="0" cellpadding="5px" class="orders_history">
									<thead>
										<tr>
											<th>Order No</th>
											<th>Date Placed</th>
										</tr>
									</thead>
									<tbody>
										<?
											$userOrderQry = mysql_query("select OrderID, DesiredDeliveryDate from ordertbl where UserID=" . $abandoned_cart->customer_id . " LIMIT 10");

											$i=0;
											if($userOrderQry) {  
												$i=1;
												while($userOrderRs = mysql_fetch_object($userOrderQry)){
													$orderid = $userOrderRs->OrderID;
													$colour = ($i%2==0) ? "#FFF": "#E4E4E4"; 
													$i++;
										?>
													<tr style="background-color:<?=$colour?>">
														<td align="left" valign="middle">
															<a href="?mod=<?=$mod?>&item=orderhistory&Orderid=<?=$orderid?>" class="RedText_Underline">
																<?=$userOrderRs->OrderID?>
															</a>
														</td>
														<td align="left" valign="middle"><?=$userOrderRs->DesiredDeliveryDate?></td>
													</tr>
										<?
												}
										?>
												<tr><td colspan="2" style="font-size: 9px;">Listing last 10 orders</td></tr>
										<?
											} if($i==0) {
										?>
												<tr>
													<td colspan="2" align="center">
														No order is placed yet.
													</td>
												</tr>
										<?
											}
										?>
									</tbody>
								</table>
							</td>
							<td class="cart_column">							
								<? 
									$cartt = json_decode(stripslashes($abandoned_cart->cart), true);
									$productss = $cartt["products"];
									$total = 0.00;
									$html_rows = "";
									for($i = 0; $i < count($productss); $i++) {
										$total += $productss[$i]["sale_price"];
										$html_rows .= '<tr>
											<td>' . ($i+1) . '</td>
											<td>' . $productss[$i]["item_title"] . '</td>
											<td>' . $productss[$i]["sale_price"] . '</td>
										</tr>';
									}
								?>
								<?=$currency?><?=$total?><br />
								<a class="view_cart" href="#" data="cart_details">view cart</a>
								<table class="cart_details" cellpadding="5px">
									<thead>
										<tr>
											<th>#</th>
											<th>Item Name</th>
											<th>Price</th>
										</tr>
									</thead>
									<tbody>
											<?=$html_rows?>
											<tr>
												<td>&nbsp;</td>
												<td style="text-align: right;"><b>Total:</b></td>
												<td style="border-top: 1px solid #d0d0d0;"><b><?=$total?></b></td>
											</tr>
									</tbody>
								</table>
							</td>
							<td class="reason_column"><? echo $abandoned_cart->reason; ?> </td>
							<td class="device_type_column"><? echo ($abandoned_cart->platform_used == 1 ? "Desktop" : "Mobile"); ?> </td>
							<td class="date_time_column"><? echo $abandoned_cart->date_added; ?> </td>
						</tr>
				<?
					}
				?>
			</tbody>
		</table>
		<script type="text/javascript">
			(function($){
				$("table#analytics .view_more, table#analytics .view_cart, table#analytics .view_orders_history").click(function() {
					if($(this).text() == "view details") {
						$(this).text("hide details");
					} else if($(this).text() == "view cart") {
						$(this).text("hide cart");
					} else if($(this).text() == "hide details") {
						$(this).text("view details");
					} else if($(this).text() == "hide cart") {
						$(this).text("view cart");
					}else if($(this).text() == "view orders history") {
						$(this).text("hide orders history");
					}else if($(this).text() == "hide orders history") {
						$(this).text("view orders history");
					}
					$(this).siblings("." + $(this).attr("data")).toggle();
					return false;
				});
			})(jQuery);
		</script>
		
<?
	} else {
		echo "<b>Sorry! Currently no data is available to show.";
	}
?>
<br style="clear: both;" />