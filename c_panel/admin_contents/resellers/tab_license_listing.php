<?

session_start();
?>
<link href="../../css/adminMain.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.js"></script>
<script language="javascript" type="text/javascript">
// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{
			try{
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}

		return xmlhttp;
    }

        function getCardInfo( ownerId  ) {
            
		var strURL="../resturants/tab_find_cardInfo.php?ownerId="+ownerId;
		var req = getXMLHTTP();

		if (req) {

			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
                                                console.log(document.getElementById('card_div'))
						$('.card_div').html(req.responseText);
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}
			}
			req.open("GET", strURL, true);
			req.send(null);
		}
		//get_licenses_of_reseller( resellerId );
	}


</script>
<?
	if($_SESSION['admin_type'] != 'admin'  && $_SESSION['admin_type'] != 'reseller') {die("Invalid access");}
	
		
include("../../../includes/config.php");
require("../../classes/chargifyApi.php");
require("../../../includes/class.phpmailer.php");
include_once("../../../includes/function.php");
$chargify = new chargifyApi();
$reseller_id = (isset($_REQUEST['reseller_id']) ?$_REQUEST['reseller_id'] :$_SESSION['owner_id']);
	 
  

	if( $_POST['submit'] ) {
		extract($_POST);
		for( $i= 1; $i<= $number_of_licences ; $i++ ) {
		$license_key = rand(0,99999999);
		$secure_key=md5($license_key * rand(0,45));
		$license_qry = "INSERT INTO licenses ( license_key, reseller_id, status, dated,secure_key ) VALUES ( '$license_key', '$reseller_id', 'unused',".time().",'$secure_key' )";
		mysql_query( $license_qry );
		$insert_id = mysql_insert_id();				
		$license_key = $insert_id.$license_key;
		$license_update_qry_srt = "UPDATE licenses SET license_key ='".$license_key."' WHERE id = '".$insert_id."'";
		mysql_query( $license_update_qry_srt );
 
		}
	}
	
	if( isset($_POST['activate'] ))
        {
            
            extract($_POST);
            
            
            $resData = mysql_fetch_object(mysql_query("Select id,name,region,rest_address,rest_city,rest_state,phone,rest_zip,premium_account from resturants where id = (select  resturant_id from licenses where license_key ='$license_id' and reseller_id=$reseller_id limit 0,1) and premium_account = 1"));
            
            $subcription_method = 'automatic';
            if($card_no=='' && $credit_card_number =="-1")
            {
                $subcription_method = 'invoice';
            }
            $chargify_subscription_id = mysql_fetch_object(mysql_query("Select chargify_subscription_id,premium_account,name from resturants where id = (select  resturant_id from licenses where license_key ='$license_id' and reseller_id=$reseller_id limit 0,1) "));
            $license_quantity = mysql_fetch_object(mysql_query("SELECT count(*) as total_license FROM `licenses` WHERE reseller_id = $reseller_id and status != 'suspended'"));
            $reseller_chargify_id = mysql_fetch_object(mysql_query("Select chargify_subcription_id from users where id =$reseller_id"));
            if(!empty($chargify_subscription_id->chargify_subscription_id))
            {
                $chargify_subscription = $chargify->reactivateSubcription($chargify_subscription_id->chargify_subscription_id,$subcription_method,$credit_card_number,$card_no,$exp_month,$exp_year);
                if(!empty($chargify_subscription->subscription))
                {
			mysql_query("UPDATE analytics SET  status='1' WHERE resturant_id = (select  resturant_id from licenses where license_key ='$license_id' and reseller_id=$reseller_id limit 0,1)");
                        mysql_query("UPDATE licenses SET  status='activated' WHERE license_key ='$license_id' and reseller_id=$reseller_id");
                        mysql_query("UPDATE resturants SET  status='1' WHERE id = (select  resturant_id from licenses where license_key ='$license_id' and reseller_id=$reseller_id limit 0,1)");
                        if($subcription_method =='invoice')
                        {
                        $objMail = new testmail();
                                        $mMessage = '<table style="font:Arial; font-family: Arial; font-size: 14px; width: 80%; border: 0" border="0" cellpadding="0" cellspacing="0">
                                                                        <tr>
                                                                                <td valign="top" style="width: 3%;">
                                                                                </td>
                                                                                <td valign="top" style="width: 94%;">
                                                                                        <table style="font:Arial; font-family: Arial; font-size: 14px; width: 100%; border: 0" border="0" cellpadding="0" cellspacing="0">
                                                                                                <tr style="height: 15px;">
                                                                                                        <td valign="top">
                                                                                                        </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                        <td valign="top">
                                                                                                                <span style="font-size: 14px;">Restaurant subscripton has been activated.</span>
                                                                                                        </td>


                                                                                                </tr>
                                                                                                <tr style="height: 10px;">
                                                                                                        <td valign="top">
                                                                                                                <span style="font-size: 14px;">Subscription ID: '.$chargify_subscription_id->chargify_subscription_id.'</span>
                                                                                                        </td>
                                                                                                </tr>
                                                                                                <tr style="height: 15px;">
                                                                                                        <td valign="top">
                                                                                                                <span style="font-size: 14px;">Restaurant Name: '.$chargify_subscription_id->name.'</span>
                                                                                                        </td>
                                                                                                </tr>
                                                                                                <tr style="height: 30px;">
                                                                                                        <td valign="top">
                                                                                                        </td>
                                                                                                </tr>
                                                                                                <tr style="height: 15px;">
                                                                                                        <td valign="top">
                                                                                                                Thank You,
                                                                                                        </td>
                                                                                                </tr>
                                                                                        </table>
                                                                                </td>
                                                                        </tr>
                                                                </table>';


					//$objMail->sendTo($mMessage, "Signup wizard Started", "menus@easywayordering.com", true);
					//$objMail->sendTo($mMessage, "Subscription is activated", "ashernedian02@gmail.com", true);
                                        $objMail->sendTo($mMessage, "Subscription has been activated", "ops@easywayordering.com", true);
                }
                $quantity = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$chargify_subscription_id->premium_account);
                $quantity = $quantity+1;
                $chargify->allocationQuantity($reseller_chargify_id->chargify_subcription_id,$quantity,$chargify_subscription_id->premium_account,'activate');
                $chargify_customer = $chargify_subscription->subscription->customer;
         
                $credit_card_info = $chargify_subscription->subscription;
                $check_card_data_Qry = mysql_fetch_object(mysql_query("Select * from chargify_payment_method where chargify_customer_id = '".$chargify_customer->id."' and card_number='".$credit_card_info->credit_card->masked_card_number."'"));
                
                if(empty($check_card_data_Qry))
                {   
                    mysql_query(
                            "INSERT INTO chargify_payment_method
                            SET user_id= '".addslashes($reseller_id)."'
                                    ,chargify_customer_id= '".addslashes($chargify_customer->id)."'
                                    ,Payment_profile_id='".addslashes($credit_card_info->credit_card->id)."'
                                    ,card_number='".$credit_card_info->credit_card->masked_card_number."'"
                    );
                }

            
            if(!empty($resData))
            {
                $getOwnerEmail = mysql_fetch_object(mysql_query("Select email from users where id = '".$owner_name."'"));
                $cntry = $resData->region;
                if($cntry=='0'){$cntry="GB";}else if($cntry=='1'){$cntry="US";} else if($cntry=='2'){$cntry="CA";}else{$cntry="US";}
                $getSrid= $chargify->createVendestaPremium($resData->name,$cntry,$resData->rest_address,$resData->rest_city,$resData->rest_state,$resData->rest_zip,"false",$resData->phone,$getOwnerEmail->email);
                mysql_query("UPDATE resturants SET srid='".$getSrid."' where id = $resData->id");

                    }
                }
                else
                {   
                    $errorMsg = $chargify_subscription->errors;
                    
                    $errMessage =  $errorMsg[0];
                }


            }
            
            
        }
	if($_REQUEST){
			$license_id = $_REQUEST['license_id'];
		if ($_REQUEST['action'] == 'del') {
			mysql_query("DELETE FROM licenses WHERE license_key ='$license_id' and reseller_id=$reseller_id");
		
		} else if ($_REQUEST['action'] == 'suspend'){
		 
			mysql_query("UPDATE licenses SET  status='suspended' WHERE license_key ='$license_id' and reseller_id=$reseller_id");
			mysql_query("UPDATE resturants SET  status='2' WHERE id = (select  resturant_id from licenses where license_key ='$license_id' and reseller_id=$reseller_id limit 0,1)");
			mysql_query("UPDATE analytics SET  status='2' WHERE resturant_id = (select resturant_id from licenses where license_key ='$license_id' and reseller_id=$reseller_id limit 0,1)");
			$sridSql = mysql_fetch_object(mysql_query("Select id,srid from resturants where id = (select  resturant_id from licenses where license_key ='$license_id' limit 0,1) and premium_account = 1 "));
                        
                        $chargify_subscription_id = mysql_fetch_object(mysql_query("Select chargify_subscription_id,premium_account from resturants where id = (select  resturant_id from licenses where license_key ='$license_id' and reseller_id=$reseller_id limit 0,1)"));
			$license_quantity = mysql_fetch_object(mysql_query("SELECT count(*) as total_license FROM `licenses` WHERE reseller_id = $reseller_id and status != 'suspended'"));
                        
                        $reseller_chargify_id = mysql_fetch_object(mysql_query("Select chargify_subcription_id from users where id =$reseller_id"));
                        $quantity = $chargify->getallocationQuantity($reseller_chargify_id->chargify_subcription_id,$chargify_subscription_id->premium_account);
                       
			if(!empty($quantity))
                        {	
                            $quantity = $quantity-1;
                            $chargify->allocationQuantity($reseller_chargify_id->chargify_subcription_id,$quantity,$chargify_subscription_id->premium_account,'suspend');
                        }
			
                       // if($chargify_subscription_id->premium_account == 1)
                        //{
                            $chargify->cancelSubcriptionByAdmin($chargify_subscription_id->chargify_subscription_id);
                            
                            
                        //}print_r($sridSql);
                        if(!empty($sridSql->srid))
                        {   
                            $chargify->cancelVendesta($sridSql->srid);
                            mysql_query("update resturants set srid = '' where id = ".$sridSql->id."");
                        }
                        
                        ?>
                          <script type='text/javascript'>window.location='?mod=resellers&item=edit&reseller_id=<?=$reseller_id?>'</script>
                        <?
		} else if ($_REQUEST['action'] == 'activate'){
			
			mysql_query("UPDATE licenses SET  status='activated' WHERE secure_key ='$license_id' and reseller_id=$reseller_id");	
			mysql_query("UPDATE resturants SET  status='1' WHERE id = (select  resturant_id from licenses where secure_key ='$license_id' and reseller_id=$reseller_id limit 0,1)");
			mysql_query("UPDATE analytics SET  status='1' WHERE resturant_id = (select  resturant_id from licenses where secure_key ='$license_id' and reseller_id=$reseller_id limit 0,1)");
			
                        $subcription_method = 'automatic';
                        if($card_no=='' && $credit_card_number =="-1")
                        {
                            $subcription_method = 'invoice';
                        }
                        
                        $chargify_subscription_id = mysql_fetch_object(mysql_query("Select chargify_subscription_id from resturants where id = (select  resturant_id from licenses where secure_key ='$license_id' and reseller_id=$reseller_id limit 0,1)"));
                        if(!empty($chargify_subscription_id->chargify_subscription_id))
                        {
                            $chargify->reactivateSubcription($chargify_subscription_id->chargify_subscription_id,$subcription_method,$credit_card_number,$card_no,$exp_month,$exp_year);
                        }
		
		} else if ($_REQUEST['action'] == 'deactivate'){
	
			mysql_query("UPDATE licenses SET  status='0' WHERE license_key ='$license_id' and reseller_id=$reseller_id");
			
		}
	
	}
 
 
	$licenseQry		=	mysql_query("select count(*) total from licenses WHERE reseller_id = $reseller_id");
	$rstotal  = mysql_fetch_object( $licenseQry ) ;
	$total_licenses =$rstotal->total;
	
	$active_licenses_Qry		=	mysql_query("select count(*) total from licenses WHERE status = 'activated' AND reseller_id = $reseller_id");
	$rstotal  = mysql_fetch_object( $active_licenses_Qry ) ;
	$total_active_licenses =$rstotal->total;
	
 
	$suspended_licenses_Qry		=	mysql_query("select count(*) total from licenses WHERE status = 'suspended' AND reseller_id = $reseller_id");
	$rstotal  = mysql_fetch_object( $suspended_licenses_Qry ) ;
	$total_suspended_licenses =$rstotal->total;
	 
	$unused_licenses_Qry		=	mysql_query("select count(*) total from licenses WHERE status = 'unused' AND reseller_id = $reseller_id");
	
	$rstotal  = mysql_fetch_object( $unused_licenses_Qry ) ;
	$total_unused_licenses =$rstotal->total;
	
 

 
 
$licenseQry		=	mysql_query("select * from licenses WHERE reseller_id = $reseller_id");
$licenseRows	=	mysql_num_rows($licenseQry);
$counter = 0;

?>
<? if ($errMessage != "" ) { ?><div class="msg_done"><?=$errMessage?></div>
<? }?>
<body style="background-color:#FFFFFF">
<div id="main_heading">
 Licenses List 
</div>
<? 	if($_SESSION['admin_type'] == 'admin') { ?>
   <form action="" method="post" name="addlicense" enctype="multipart/form-data">
    <table  width="100%" border="0"  cellpadding="4" cellspacing="0">
      <tr align="left" valign="top">
        <td class="Width"><strong>Grant more Licenses:</strong></td>
      </tr>
      <tr align="left" valign="top">
        <td>
            <select name="number_of_licences" id="number_of_licences" style="width:365px;" >
              <option value="-1">Select Licenses</option>
				<?
                for($i= 1; $i<= 20 ; $i++) {
                ?>
                 <option value="<?=$i?>"> <?=$i?> </option>
                <?
                }
                ?>
            </select>
        </td>
        <td>
            <input type="submit" name="submit" value="Submit" />
        </td>
      </tr>
      
     </table>
    </form>
    <? } ?>
  <table class="listig_table" width="100%" border="0" align="center" cellspacing="1">
    <tr >
      <td colspan="5">Total Number of Licenses: &nbsp;&nbsp;&nbsp;<?=$total_licenses?><br/>
      	   Active Licenses: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$total_active_licenses?><br/>
           Suspended Licenses:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$total_suspended_licenses?><br/>
           Unused Licenses: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$total_unused_licenses?><br/>
      </td>
    </tr>
    <tr>
    <tr bgcolor="#729338">
      <th width="113"><strong>License Key</strong></th>
      <th width="113"><strong>License status</strong></th>
      <th width="113"><strong>Resturant</strong></th>
      <th width="113"><strong>Activation Date</strong></th>
      <? if($_SESSION['admin_type'] == 'admin') { ?>
      <th width="250"><strong>Action</strong></th>
      <? } ?>
    </tr>
    <? while($licenseRs	=	mysql_fetch_object($licenseQry)){ ?>
  	
   <?  if( $counter++ % 2 == 0)  
   			$bgcolor = '#F8F8F8';
	   else $bgcolor = '';
   ?>
    <tr bgcolor="<?=$bgcolor ?>" >
     
      <td><?=$licenseRs->license_key?></td>
      <td><?=$licenseRs->status?> </td>
      <td>
	  <?
      	if( $licenseRs->resturant_id ) {
			$resurant_sql_str = "SELECT name FROM resturants WHERE id=".$licenseRs->resturant_id;
			$resurant_qry = mysql_query( $resurant_sql_str );
			$resurant_rs  = mysql_fetch_object( $resurant_qry ) ;
			$resturant_name = $resurant_rs->name;
		} else {
			$resturant_name = "";
		}
		
		echo $resturant_name;
	  ?> 
      </td>
      <td>
	  	<? 
		if ($licenseRs->activation_date == 0) 
			$activationDate = ""; 
		else  $activationDate = strftime("%m-%d-%Y", $licenseRs->activation_date); 
		echo $activationDate;
		?>
      </td>
      <td>
      
     
     <? if( $_SESSION['admin_type'] == 'admin' ) { ?>
		  <?php if($licenseRs->status == 'activated') { ?>
              <a href="?action=suspend<?= $_SESSION['admin_type'] == 'admin' ? "&reseller_id=".$reseller_id:""?>&license_id=<?=$licenseRs->license_key ?>" onClick="return confirm('Are you sure you want to change the status of this License')" style="text-decoration:underline;">Suspend</a>
          <?php } else if( $licenseRs->status == 'suspended' ) { ?>
           	<span class="openFancyDiv" style="text-decoration:underline;color:#0000ee;cursor:pointer" license_id="<?=$licenseRs->license_key ?>">Activate</span>
		  <? }else if( $licenseRs->status == 'unused' ) { ?>
          &nbsp;&nbsp;<a href="?action=del<?= $_SESSION['admin_type'] == 'admin' ? "&reseller_id=".$reseller_id:""?>&license_id=<?=$licenseRs->license_key  ?>"  onclick="return confirm('Are you sure you want to Delete this License')" style="text-decoration:underline;">Delete</a> 
		  <? } ?>
      </td>
<? }?>
    </tr>
    <? }?>
	
    <tr>
        <td colspan="5">
            
        </td>
    </tr>

    <tr>
        <td colspan="5">
        <form id="form2" name ="form2" method="post" action="">
        <input type="hidden" name="license_id" class="license_id" value="<?=$licenseRs->secure_key ?>"/>
       <strong>Billing Information</strong>
            <table class="PaymentMethod1">
                <tr align="left" valign="top">
            <td>Subcription type</td>
            <td><input name="subcription_method" type="radio" value="automatic"  id="payment_collection_method" checked> automatic           &nbsp;&nbsp;
            <input name="subcription_method" type="radio" value="invoice"  id="payment_collection_method" >Invoice</td>
          </tr>

                <tbody class="card_new_existing">
          <tr align="left" valign="top">
               <td></td>
            <td><input name="credit_card" type="radio" value="0"  id="credit_card" checked>New Credit Card            &nbsp;&nbsp;
            <input name="credit_card" type="radio" value="1"  id="credit_card" >Choose Existing Card</td>
          </tr>

                <tbody class="payment_Method">
                <tr align="left" valign="top">
            <td><strong>Card no:</strong></td>
            <td>
		<input name="card_no" type="text" size="40"  id="card_no" /> </td>
           </tr>

                <tr align="left" valign="top">
            <td><strong>Exp Date:</strong></td>
            <td>
                 <select name="exp_month" id="exp_month" style="width:70px;" >
                    <option value="1">1 -Jan</option>
                    <option value="2">2 -Feb</option>
                    <option value="3">3 -Mar</option>
                    <option value="4">4 -Apr</option>
                    <option value="5">5 -May</option>
                    <option value="6">6 -Jun</option>
                    <option value="7">7 -Jul</option>
                    <option value="8">8 -Aug</option>
                    <option value="9">9 -Sep</option>
                    <option value="10">10 -Oct</option>
                    <option value="11">11 -Nov</option>
                    <option value="12">12 -Dec</option>
                 </select>

                <select name="exp_year" id="exp_year" style="width:70px;" >
                    <option value="2014">2014</option>
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                </select>
            </td>
          </tr>
                </tbody>

                 <tbody class="choose_existing">
            <tr align="left" valign="top">
                <td><strong>Credit card no:</strong></td>
                <td  class="card_div">
                    <select name="credit_card_number" id="credit_card_number" style="width:270px;" >
                    <option value="-1">Select Card</option>
                    </select>
                </td>
            </tr>

           </tbody>
                </tbody>
               <tr>
                    <td>
                         <input type="submit" name="activate" value="Activate" id="activate"/>
                    </td>
                </tr>
            </table>
         </form>
        </td>
    </tr>
  </table>
</body>
<script type="text/javascript">
 
  $( document ).ready(function() {
      $("#form2").hide();
      $('input:radio[name="credit_card"]').filter('[value="0"]').attr('checked', true);
      $(".openFancyDiv").click(function()
      {
          var element = $(this);
          var reseller_id = GetURLParameter('reseller_id');
          getCardInfo(reseller_id);

          $('.license_id').val(element.attr('license_id'));
          var nextrow = element.closest('tr').next();
          if($('#form2', nextrow).length == 0){
              var row = $('<tr>');
              var col = $('<td colspan="5">');
              col.append($('#form2'));
              row.append(col);
              row.insertAfter(element.closest('tr'));
              $("#form2").show();
              element.removeClass('openFancyDiv');
              element.addClass('closeFancyDiv');
              setTimeout(500);
          }else{
              nextrow.show();
          }

      });
  });

  $('.choose_existing').hide();

    $('input[type=radio][name=credit_card]').change(function()
    {
        var rdbval = $('input[name=credit_card]:checked').val();
        if(rdbval==1)
        {
            $('.payment_Method').hide();
            $('.card_new_existing').show();
            $('.choose_existing').show();
        }
        else
        {
            $('.payment_Method').show();
            $('.card_new_existing').show();
            $('.choose_existing').hide();
        }
    });

    $('input[type=radio][name=subcription_method]').change(function()
    {
        var rdbvalcard = $('input[name=credit_card]:checked').val();
        var rdbval = $('input[name=subcription_method]:checked').val();
        if(rdbval=="automatic")
        {
            if(rdbvalcard==1)
            {
                $('.payment_Method').hide();
                $('.card_new_existing').show();
                $('.choose_existing').show();
            }
            else
            {
                $('.choose_existing')
                $('.payment_Method').show();
                $('.card_new_existing').show();
            }
        }
        else
        {
            $('.payment_Method').hide();
            $('.choose_existing').hide();
            $('.card_new_existing').hide();

        }
    });

    function GetURLParameter(sParam){
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    }
</script>
<? @mysql_close($mysql_conn);?>