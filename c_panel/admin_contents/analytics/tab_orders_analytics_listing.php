<?php
	$analytics = dbAbstract::Execute("SELECT * FROM analytics WHERE resturant_id=$resturant_id", 1);
	if(!empty($analytics) && dbAbstract::returnRowsCount($analytics, 1) > 0) {
		$analytics = dbAbstract::returnObject($analytics, 1);
?>
		<style type="text/css">
		
			table#analytics {
				border: 1px solid #d0d0d0;
				border-radius: 5px;
				margin-right: 15px;
			}
			table#analytics th {
				background-color: #e0e0e0;
				text-align: left;
				font-size: 14px;
			}
			table#analytics tr {
				background-color: #ffffff !important;
			}
			table#analytics .title {
				font-weight: bold;
			}
			
			table tr:nth-child(2n+1) {
				background-color: #ffffff;
			}
			table td {
				vertical-align: top;
			}
		</style>
		<table>
			<tr>
				<td>
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="3">Order's source statistics</th>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td class="title">Orders</td>
								<td class="title">Total Amount</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="title">Website orders</td>
								<td><? echo $analytics->website_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->website_total_amount; ?></td>
							</tr>
							<tr>
								<td class="title">Mobile Website Orders</td>
								<td><? echo $analytics->mobile_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->mobile_total_amount; ?></td>
							</tr>
							<tr>
								<td class="title">Rapid re-orders</td>
								<td><? echo $analytics->rapid_reorders_count; ?></td>
								<td><?=$currency?><? echo $analytics->rapid_reorders_total_amount; ?></td>
							</tr>
							<tr>
								<td class="title">Facebook Orders</td>
								<td><? echo $analytics->fb_orders; ?></td>
								<td><?=$currency?><? echo $analytics->fb_orders_amount; ?></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="3">Guests, New and Repeat Customers</th>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td class="title">Orders</td>
								<td class="title">Total Amount</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="title">Guests</td>
								<td><? echo $analytics->guests_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->guests_orders_total_value; ?></td>
							</tr>
							<tr>
								<td class="title">New Customers</td>
								<td><? echo $analytics->new_customers_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->new_customers_total_value; ?></td>
							</tr>
							<tr>
								<td class="title">Repeat Customer</td>
								<td><? echo $analytics->repeat_customers_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->repeat_customers_total_values; ?></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="3">Pickup vs Delivery</th>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td class="title">Orders</td>
								<td class="title">Total Amount</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="title">Pickup</td>
								<td><? echo $analytics->pickup_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->pickup_orders_total_value; ?></td>
							</tr>
							<tr>
								<td class="title">Delivery</td>
								<td><? echo $analytics->delivery_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->delivery_orders_total_value; ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3">
					<table cellpadding="10px" id="analytics" cellspacing="0">
						<thead>
							<tr>
								<th colspan="3">Cash vs Credit card</th>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td class="title">Orders</td>
								<td class="title">Total Amount</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="title">Cash</td>
								<td><? echo $analytics->cash_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->cash_orders_total_value; ?></td>
							</tr>
							<tr>
								<td class="title">Credit card</td>
								<td><? echo $analytics->credit_card_orders_count; ?></td>
								<td><?=$currency?><? echo $analytics->credit_card_orders_total_value; ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<div class="clearfix" style="margin-bottom: 15px;"></div>
<?
	}
?>