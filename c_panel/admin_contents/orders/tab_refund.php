<?php

$result=-1;
$key = 'EASYWAY-REFUND-SECURE-KEY';
//$plaintext = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $ciphertext, MCRYPT_MODE_CFB);
if(isset($_POST['btnsubmit']))
{
	$restaurant_refundpassword=trim($Objrestaurant->refund_password);
	extract($_POST);
	//Modified01082013
	if($restaurant_refundpassword==$refundpassword)
	{
		$qry=dbAbstract::Execute("SELECT c.cust_your_name,c.LastName,c.cust_email,o.OrderID,o.Totel,o.OrderDate,o.transaction_id,o.cdata,o.Totel
						FROM `ordertbl` o inner join `customer_registration` c on o. UserID=c.id where OrderID='". $refundid ."' and cat_id=". $Objrestaurant->id  ." and transaction_id >0 and payment_approv=1",1);
		$details=dbAbstract::returnObject($qry,1);
		if (!isset($details->transaction_id)) 
		{
			$result=2;
		}
		else 
		{
			$result=3; 
			$details->transaction_key=$function_obj->encrypt($details->transaction_id,$key);
            //Log::write('refund page', 'Refund page for Order Id#'.$refundid);
		}
	 }///Query 
	else 
	{
		 $result=0;
	}//PASSWORD mismatch
}//POSET
else if(isset($_POST['btnrefund']))
{
	$transactionid=$function_obj->decrypt($_POST['trasaction_key'],$key);
    $qry=dbAbstract::Execute("SELECT c.id AS ID, c.cust_your_name,c.LastName,c.cust_email,o.OrderID,o.Totel,o.OrderDate,o.transaction_id,IFNULL(o.CardToken, '') AS CardToken,o.cdata,o.Totel
					FROM `ordertbl` o inner join `customer_registration` c on o. UserID=c.id where transaction_id='". $transactionid ."' and cat_id=". $Objrestaurant->id  ." and transaction_id >0 and payment_approv=1",1);
	$details=dbAbstract::returnObject($qry,1);
	$gUID = $details->ID; //UserID 
	$amount = $details->Totel;
	$cc = $details->cdata;
	$gCardToken = $details->CardToken;
    
 	if( $Objrestaurant->payment_gateway=="authoriseDotNet")  
	{
		$Objrestaurant->payment_gateway="AuthorizeNet";
	}
	require_once 'admin_contents/gateways/'. $Objrestaurant->payment_gateway .'.php';
    
	if($success==1)
	{       
                Log::write("Update Order detail in ordertbl - tab_order_approve.php", "QUERY -- update ordertbl set payment_approv=0 where OrderID=". $_POST['order_id']  ."", 'order', 1 , 'cpanel');
		dbAbstract::Update("update ordertbl set payment_approv=0 where OrderID=". $_POST['order_id']  ."",1);
		$user_email;
		$testmail=new testmail();
		$message="<br/> Dear Customer <br/><br/> Your Order Payment is refunded: <br/><br/> Order ID: ". $_POST['order_id'] ."<br/><br/> Order Date: ". $_POST['order_date'] ."<br/><br/> Order Total: ". $_POST['Totel'] ." <br/> ";
		$message .=" <a href=".$SiteUrl.$Objrestaurant->url  ."/'>".$SiteUrl.$Objrestaurant->url  ."/</a>";
		$message .="<br/><br/>Phone: ". $Objrestaurant->phone  ."";
		$message .="<br/><br/>Fax: ". $Objrestaurant->fax  ."";
		$subject="Easyway Ordering Refund";
		$testmail->sendTo($message,$subject,$_POST['user_email']);

		$message="<br/> Dear Restaurant Owner <br/><br/> Order Payment is refunded: <br/><br/> Order ID: ". $_POST['order_id'] ."<br/><br/> Order Date: ". $_POST['order_date'] ."<br/><br/> Order Total: ". $_POST['Totel'] ." <br/> ";
		$message .=" <a href='".$SiteUrl.$Objrestaurant->url  ."/'>".$SiteUrl.$Objrestaurant->url  ."/</a>";
		$message .="<br/><br/>Phone: ". $Objrestaurant->phone  ."";
		$message .="<br/><br/>Fax: ". $Objrestaurant->fax  ."";
		$testmail->sendTo($message,$subject,$Objrestaurant->email);
		
		$message="Transaction Refunded successfully <br /><a href=''>Click here to do another refund</a>";
	}
	else 
	{
		$message="Transaction Refund failed <br/><br/> Gateway Message= ".$message ."<br/><br/><a href=''>Click here to do try again</a>";
	}
$result=4;
}
?>

<div id="main_heading">Refund Order</div>
<form id="frmrefund_pass" name="frmrefund_pass" method="post" action="">
	<div id="refund_pass" style="margin-left:10%;" >
		<div style="border:1px #e8e8e8 solid;width:60%;margin-bottom:5px;">
			<?php 
			if($result==3)  
			{
			?>
				<div style="background-color:#eed000;padding:10px;"><strong>Refund Order Details</strong></div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:15px;font-weight:bold;width:160px;">OrderID:</div>
					<div style="float:left;margin-right:40px;"><?=$details->OrderID?></div>
					<div style="clear:both"></div>
				</div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:15px;font-weight:bold;width:160px;">Customer Name:</div>
					<div style="float:left;margin-right:40px;"><?=$details->cust_your_name .' '. $details->LastName  ?></div>
					<div style="clear:both"></div>
				</div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:15px;font-weight:bold;width:160px;">Customer Email:</div>
					<div style="float:left;margin-right:40px;"><?=$details->cust_email?></div>
					<div style="clear:both"></div>
				</div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:15px;font-weight:bold;width:160px;">Order Total:</div>
					<div style="float:left;margin-right:40px;"><?=$details->Totel?></div>
					<div style="clear:both"></div>
				</div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:15px;font-weight:bold;width:160px;">Order Date:</div>
					<div style="float:left;margin-right:40px;"><?=$details->OrderDate?></div>
					<div style="clear:both"></div>
				</div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:15px;font-weight:bold;width:160px;">Gateway Transaction Id:</div>
					<div style="float:left;margin-right:40px;"><?=$details->transaction_id?></div>
					<div style="clear:both"></div>
				</div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:40px;">
						<input type="hidden" name="trasaction_key" value="<?= $details->transaction_key ?>"> 
						<input type="hidden" name="user_email" value="<?= $details->cust_email ?>"> 
						<input type="hidden" name="order_id" value="<?= $details->OrderID ?>"> 
						<input type="hidden" name="order_date" value="<?= $details->OrderDate ?>"> 
						<input type="hidden" name="Totel" value="<?= $details->Totel ?>"> 
						<input type="submit"  name="btnrefund" value="Confirm and refund" onclick="return confirm('Are you sure you want to refund this order ?')" />
						<input type="button"  name="btncance" value="Cancel" onClick="window.location=window.location;"  />
					</div>
					<div style="clear:both"></div>
				</div>
			<?php 
			} 
			else if($result==4)  
			{
			?> 
				<div style="background-color:#eed000;padding:10px;"><strong>Refund Order Details</strong></div>
				<div class="alert-error"><?= $message ?></div>
			<?php 
			}
			else 
			{ 
			?>
				<div style="background-color:#eed000;padding:10px;"><strong>Order Refund</strong></div>
				<?php 
				if($result==0) 
				{ 
				?>
					<div class="alert-error">Incorrect password, try again or contact your reseller to reset the refund password</div>
				<?php 
				}
				else if($result==2) 
				{ 
				?>
					<div class="alert-error">Incorrect orderid, try again and enter correct order id</div>
				<?php 
				} 
				?>  
				<div style="padding:10px;">
					<div style="float:left;margin-right:15px;font-weight:bold; width:160px;">Enter refund password: </div>
					<div style="float:left;margin-right:40px;">
						<input type="password" name="refundpassword" id="refundpassword"  />
					</div>
					<div style="clear:both"></div>
				</div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:15px;font-weight:bold;width:160px;">Enter refund order id:</div>
					<div style="float:left;margin-right:40px;">
						<input type="text" name="refundid"  id="refundid" />
					</div>
					<div style="clear:both"></div>
				</div>
				<div style="padding:10px;">
					<div style="float:left;margin-right:40px;">
						<input type="submit"  name="btnsubmit" value="Refund order" onclick="return confirm('Are you sure you want to refund this order ?')" />
					</div>
					<div style="clear:both"></div>
				</div>
				<script type="text/javascript">
				$(function()
				{
					$("#frmrefund_pass").validate
					({
						rules: 
						{
							refundpassword: {required: true},
							refundid: {required: true },
						},
						messages: 
						{
							refundpassword: 
							{
								required: "please enter your refund password",
							},
							refundid: 
							{
								required: "please enter order id to refund",								  
							},
						},
						errorElement: "span",
						errorClass: "alert-error",
					});
				});
			</script>
		<?php  
		} 
		?>
	</div>
</div>
</form>
 