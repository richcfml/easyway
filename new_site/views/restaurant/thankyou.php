<script language="javascript" type="text/javascript">
	$(document).ready(function()	
	{
		jQuery.fn.center = function () 
		{
			this.css("position","absolute");
			this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
														$(window).scrollTop()) - 200 + "px");
			this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
														$(window).scrollLeft()) + "px");
			return this;
		}
	});
</script>
<?php 
if (is_numeric($loggedinuser->id))
{
	$clone=$cart->myclone();
}

$favItems = array();
foreach ($loggedinuser->arrFavorites as $favItem)
{
	array_push($favItems, trim(strtolower($favItem->title)));
}
	 
if( isset($clone))
{
	if($clone->isempty()|| $clone->order_created===0) 
  	{
		//@mysql_close($mysql_conn);
		redirect($SiteUrl .$objRestaurant->url ."/?item=menu" );
		exit;
	}
			 
	if(isset($_POST['addfavourites']))
	{
		$repidreodering=1;
		$loggedinuser->addMenuToCustomerFavorites($_POST['foodtitle'],serialize($clone->products),$repidreodering,$clone->delivery_type, $clone->driver_tip);
			
		if($repidreodering==1 &&  $objRestaurant->did_number!='0' && trim($objRestaurant->did_number)!='') 
		{
			$objcdyne->sendSMS($loggedinuser->cust_phone1,"You now have ". $objRestaurant->name ."'s Rapid Re-Order!  Text one of your favorites to this number to Re-Order it","New Registeration",'',$objcdyne->SYSTEM_NEW_CUSTOMER);				
		}
			 
		//@mysql_close($mysql_conn);
		$cart->destroyclone();
		$cart->destroysession();
		redirect($SiteUrl .$objRestaurant->url ."/?item=menu" );exit;
		// header("location: ?item=menu" );exit;
	}
}
else 
{
	if($cart->isempty()|| $cart->order_created===0) 
	{
		//@mysql_close($mysql_conn);
		redirect($SiteUrl .$objRestaurant->url ."/?item=menu" );exit;
	}
 	
	if(isset($_POST['x_order_id'])) 
	{
		extract($_POST);
		if($_POST['x_response_reason_code']==1)
		{
			$file_name = "pdffiles/pdf".$cart->order_id.".pdf";
			include $site_root_path. "views/cart/views/notify_customers.php";
			dbAbstract::Update("UPDATE ordertbl SET payment_approv=1 WHERE OrderID=".$cart->order_id); 	
			//@mysql_close($mysql_conn);	
			echo "<script type=\"text/javascript\">window.location='".$SiteUrl.$objRestaurant->url."/?item=thankyou';</script>";
			exit;
		}
		else 
		{
			//@mysql_close($mysql_conn);
			echo "<script type=\"text/javascript\">window.location='".$SiteUrl.$objRestaurant->url."/?item=failed&response_code=". $_POST['x_response_reason_code'] ."';</script>";
			
		}
	}
?>
	<style type="text/css">
		#ContentImages{ width:975px; /*border:#999 solid 1px;*/ /*padding:50px*/;}
		#ImageOrderItsWay{ float:left; padding:30px 70px 30px 30px; border:#999 solid 1px; /*width:449px;*/}
		#ImageAddThis{ float:left; border:#999 solid 1px; /*width:455px;*/ margin-top:-19px; padding-top: 26px; padding-left: 80px; padding-right:75px; }
		#OrderInformationContent{ padding:20px; border:#999 solid 1px; width: 940px;}
		#TableInfo1{ padding-left:20px; padding-top:30px;}
		#Total{ font-size:13px; font-weight:bold; }
	</style>
	<div id="thank_you_area" style="padding:5px; float:none;">
		<div id="ContentImages">
			<table cellspacing="0" bordercolor="#999999" style="width: 100%;" border="1">
				<tr>
					<td style="padding:30px 70px 30px 30px;">
						<img src="../images/YourOrderNowItsWay.png">
					</td>
					<td style="text-align: center; vertical-align: middle; width: 50%;">
						<table style="width: 100%;">
							<tr>
								<td style="padding-top: 26px; padding-left: 70px;">
									<?php 
									if ($objRestaurant->did_number!='0' && trim($objRestaurant->did_number)!='') 
									{
									?>
										<img src="../images/AddThisOrder.png">
									<?php
									}
                        			else
									{
									?>
										<img src="../images/favourite_order.png">
									<?php
									}
									?>								
								</td>
							</tr>        
							<tr>
								<td style="padding-top: 10px; padding-bottom: 20px; padding-left: 70px;">




								
								
								
								<?php 
								if(is_numeric($loggedinuser->id))
								{ 
								?>
									<div style="width: 100%; text-align: left;">
										<div style="font-size:12px; font-weight:bold; text-align: left;">
											<form method="post" id="favoritesform"  action="">
												<div style="margin: 0; padding: 0;">
													<script type="text/javascript" language="javascript">
														$("#addfavourites").live('click',function(e) 
														{
															$('#spnError').hide();
															$('#spnReq').hide();
															
															if ($('#iAgree').length>0) //SMS Order Enabled
															{
																if (!($('#iAgree').is(':checked')))
																{
																	e.preventDefault();
																	alert("please agree to terms and conditions");
																}
																else
																{
																	if ($('#foodtitle').val()=='') 
																	{ 
																		$('#spnReq').show();
																		e.preventDefault();
																	} 
																	else 
																	{
																		var jsFav = <?php echo json_encode($favItems); ?>;
																		if (jsFav.indexOf($.trim($('#foodtitle').val().toLowerCase())) >= 0)
																		{
																			$('#spnError').show();
																			e.preventDefault();
																		}
																	}
																}
															}
															else //SMS Order Disabled
															{
																if ($('#foodtitle').val()=='') 
																{ 
																	$('#spnReq').show();
																	e.preventDefault();
																} 
																else 
																{
																	var jsFav = <?php echo json_encode($favItems); ?>;
																	if (jsFav.indexOf($.trim($('#foodtitle').val().toLowerCase())) >= 0)
																	{
																		$('#spnError').show();
																		e.preventDefault();
																	}
																}
															}
														});
													</script>
													<input type="text" name="foodtitle" id="foodtitle" maxlength="12" size="25"  />&nbsp;&nbsp;<span id="spnReq" name="spnReq" style="display: none; color: red; font-weigth: bold;">*</span>&nbsp;&nbsp;
													<input  type="submit" id="addfavourites" name="addfavourites" value="Save" /><br />
													<span id="spnError" style="display: none; color: red; font-size: 12px;">A favorite order with same name already exists.</span>
													<?php 
													if ($objRestaurant->did_number!='' )
													{ 
													?>
														<br /><input type="checkbox" name="iAgree" id="iAgree" value="1" checked="checked" /><span style="font-weight: normal !important;">I agree to the <a onclick="jQuery.facebox({div: '#repidreoderingterms'});" class="hrefTerms1" id="hrefTerms1" name="hrefTerms1" style="cursor: hand; cursor: pointer;">Quick Favorite terms </a></span><br />
													<?php
													}
													?>
												</div>
												<?php 
												if ($objRestaurant->did_number!='' )
												{ 
												?>
												<script language="javascript" type="text/javascript">
													$(document).ready(function()	
													{
														$("#hrefLM").click(function() 
														{
															jQuery.facebox({ div: '#dvLM' });
															$('#facebox').center();
															$('.lnkCM').click(function()
															{ 
																jQuery.facebox({div: '?mod=resturants&item=reordercm&ajax=1'});  
																$('#facebox').center();
																return false;  
															});
															
															$('.lnkCC1').click(function()
															{ 
																jQuery.facebox({div: '?mod=resturants&item=reordermobile&ajax=1'});  
																$('#facebox').center();
																return false;  
															});
															
															$('.hrefTerms').click(function()
															{  	
																jQuery.facebox({div: '#repidreoderingterms'});  
																$('#facebox').center();
															});  
														});
														
														$(".hrefEdit").die('click').live("click", function()
														{ 
															jQuery.facebox({div: '?mod=resturants&item=reordercm&ajax=1'});  
															$('#facebox').center();
															return false; 
														});
													});
												</script>
													<br />&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="hrefLM" name="hrefLM">LEARN MORE</a>&nbsp;&nbsp;&nbsp;<a id="hrefEdit" class="hrefEdit" name="hrefEdit" style="color:#36C; font-size: 12px; text-decoration: none; cursor: pointer; cursor: hand;">EDIT QUICK FAVORITE PREFERENCES</a>
													<div style="width: 800px; padding:10px;font-weight:normal;font-size:11px; display:none;"  class="repidreoderingterms" id="repidreoderingterms" name="repidreoderingterms">
														<div style="width:98%;height:250px;overflow-y:scroll; border:1px solid #EFEFEF;" >
															<p><b>Terms:</b></p>
															<p>There is no charge for this service; however,&nbsp;<strong>message  and data rates may apply</strong>&nbsp;from your mobile carrier. Subject to the  terms and conditions of your mobile carrier, you may receive text messages sent  to your mobile phone. By providing your consent to participate in this program,  you approve any such charges from your mobile carrier. Charges for text  messages may appear on your mobile phone bill or be deducted from your prepaid  balance. EasyWay INC reserves the right to terminate this SMS service, in whole  or in part, at any time without notice.&nbsp; The information in any message  may be subject to certain time lags and/or delays.&nbsp; EasyWay INC nor our  restaurant clients are responsible for delayed or un-received orders.&nbsp; </p>
															<p>Submission of a food order using this service will result in a  charge from the restaurant. These charges can only be reversed at the  restaurant&rsquo;s discretion which may or may not be granted.  It is your responsibility to ensure that only  you or authorized users have access to your mobile phone and your secret  favorite nicknames.   EasyWay INC nor our  restaurant clients are responsible for un-intentionally placed or unauthorized  orders.</p>
															<p>Use of this service constitutes your continued agreement to  these terms which may be altered at any time.</p>
															<p><strong>United States participating carriers include:</strong> <br />
															ACS/Alaska, Alltel, AT&amp;T, Bluegrass Cellular, Boost,  Cellcom, Cellone Nation, Cellular One of East Central Illinois, Cellular South,  Centennial, Cincinnati Bell, Cox Communications, Cricket, EKN/Appalachian  Wireless, Element Mobile, GCI, Golden State Cellular, Illinois Valley Cellular,  Immix/Keystone Wireless, Inland Cellular, iWireless, Nex-Tech Wireless, Nextel,  nTelos, Plateau Wireless, South Canaan, Sprint, T-Mobile, Thumb Cellular,  United Wireless, US Cellular, Verizon Wireless, Viaero Wireless, Virgin,  WCC&nbsp;<br />
															Additional carriers may be added.</p>
															<p><strong>Canada participating carriers include:</strong> <br />
															Aliant Mobility, Bell Mobility, Fido, MTS, NorthernTel Mobility,  Rogers Wireless, SaskTel Mobility, Télébec Mobilité, TELUS Mobility, Vidéotron,  Virgin Mobile Canada, WIND Mobile.&nbsp; Additional carriers may be added.</p>
														</div>
													</div>
													<div style="width: 430px; padding:10px;font-weight:normal;font-size:12px; display:none;"  class="dvLM" id="dvLM" name="dvLM">
														Quick Favortie lets you order your favorite	meal simply by sending a text message!<br /><br />
														+ Add our number <span style="color: red; font-weight: bold;"><?= $objRestaurant->did_number ?></span> to your	contact list<br />
														+ Add at least one meal to your profile<br />
														+ Register phone and payment method <span style="color: red; font-weight: bold;"><a href="#" class="lnkCM" style="color:red" >here</a></span><br /><br />
														Next time your hungry, just text the name of your favorite meal code to our Quick Favorite number from your mobile phone and your order will be placed. It’s that easy!
														you can	update your	mobile number <span style="color: red; font-weight: bold;"><a href="#" class="lnkCC1" style="color:red" >here</a></span><br /><br />
														<a href="#" id="hrefTerms" name="hrefTerms" class="hrefTerms">Terms & Conditions</a>
													</div>
												<?php 
												} 
												?>
											</form>
										</div>
									</div>
								<?php 
								} 
								?>


									



















								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	<?php 
	if($cart->payment_menthod==cart::CASH)
	{	
	?>
		<span style="margin-top: 15px;">
			<br />
			Your order has been received. A copy of your order has been emailed to you. Should you have any questions, please do not hesitate to contact us. <br />
			<a href="<?=$SiteUrl. $objRestaurant->url ?>/"><strong>Go to Home Page</strong></a> <br />
			<br />
			<br />
		</span>	
	<?php
	} 
	else 
	{
	?>
		<span style="margin-top: 15px;">
			<br />
			Your order has been received. A copy of your order has been emailed to you. Should you have any questions, please do not hesitate to contact us.
			<a href="<?=$SiteUrl. $objRestaurant->url ?>/"><strong>Go to Home Page</strong></a> 
			<br />
			<br />
		</span>
		<div id="order_information_div" style="float: left; margin-left: 20px;">
			<p style="color:#999; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold;">ORDER INFORMATION</p>
			<div id="order_information">
				<ul style="width:120px;">
					<li>Merchant:</li>
					<li>Description:</li>
					<li>Invoice Number:</li>
            <?php 
            if ($objRestaurant->payment_gateway=="suregate")
            {
            ?>
                <li>PNRef:</li>
            <?php
            }
            ?>
				</ul>
				<ul>
					<li>
						<?=$objRestaurant->name; ?>
					</li>
					<li>
					  Customer Order
					</li>
					<li>
					  <?=  $cart->invoice_number; ?>
					</li>
                <?php 
                if ($objRestaurant->payment_gateway=="suregate")
                {
                    $mSQL = "UPDATE ordertbl SET PNRef='".$cart->PNRef."' WHERE OrderID=".$cart->order_id;
                    dbAbstract::Insert($mSQL);
                ?>
                <li>
                    <?=$cart->PNRef?>
                </li>
                <?php
                }
                ?>
				</ul>
				<div style="clear:left"></div>
				<div class="vertical_line" style="margin:0px 10px 10px 0px;">&nbsp;</div>
				<ul style="width:200px; margin-top: 20px;">
					<li>
						<strong>Billing Information</strong>
					</li>
					<li>
						<?= $loggedinuser->billing_fname.' '.$loggedinuser->billing_lname ?>
					</li>
					<li>
						<?= $loggedinuser->billing_address ?>
					</li>
					<li>
						<?php echo $loggedinuser->billing_city ." ". $loggedinuser->billing_state ." ". $loggedinuser->billing_zip; ?>
					</li>
					<li> 
						USA 
					</li>
					<li>
						<a href="#">
							<?=$loggedinuser->cust_email ?>
						</a>
					</li>
					<li>
						<?=$loggedinuser->cust_phone1 ?>
					</li>
				</ul>
				<ul style="margin-top: 20px;">
					<li>
						<strong>Shipping Information</strong>
					</li>
					<li>
						<?= $loggedinuser->cust_your_name .' '. $loggedinuser->LastName; ?>
					</li>
			                <li>
						<?= ($loggedinuser->delivery_address_choice==1 ? $loggedinuser->street1 ." ". $loggedinuser->street2 : $loggedinuser->delivery_street1 ." ". $loggedinuser->delivery_street2 ) ?>
					</li>
					<li>
						<?php  
						if($loggedinuser->delivery_address_choice==1) 
						{ 
							echo $loggedinuser->cust_ord_city ." ". $loggedinuser->cust_ord_state ." ". $loggedinuser->cust_ord_zip;
						}
						else
						{
							echo $loggedinuser->delivery_city1." " .$loggedinuser->delivery_state1 ." ". $loggedinuser->deivery1_zip;
						}
						?>
					<li> 
						USA 
					</li>
				</ul>
				<div style="clear:left"></div>
				<div class="vertical_line" style="margin:0px 10px 10px 0px;">&nbsp;</div>
				<div id="Total">Total:&nbsp;US $
					<?=$cart->grand_total() ?>
				</div>
				<div style="clear:left"></div>
			</div>
		</div>
	<?php
	}
	?>
	<div class="clear"></div>
	<br/>
	</div> 
	<?php
	$cart->createclone();
 	$cart->destroysession();
}

if($objRestaurant->yelp_review_request == 1 && !empty($objRestaurant->yelp_restaurant_url))
{
?>
	<div id="yelp_review">
    	<a href="<?=$objRestaurant->yelp_restaurant_url?>" target="_blank"><img src="<?=$SiteUrl?>images/Yelp-Review-Button.jpg" width="236px" style="float:left;"></a>
		<div style="font-size:19px;padding-top: 83px;">We want you to love your meal.
        	If you do, please leave us a <a href="<?=$objRestaurant->yelp_restaurant_url?>" target="_blank">Yelp review</a>.
        	If you are unsatisfied for any reason please <a href="mailto:<?=$objRestaurant->email?>">let us know</a> so we can make it right
		</div>
	</div>
<?php 
} 
?>
<div class="clear"></div>
