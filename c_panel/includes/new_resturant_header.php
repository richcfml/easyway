<? 
	 
	require_once("../classes/restaurant.php");
	$Objrestaurant_data=new restaurant();
	$Objrestaurant=new restaurant();
        
	if(isset($_SESSION['restaurant_detail'])){
	 		$Objrestaurant=$Objrestaurant_data->getSession();
		
		if(isset($_GET['cid'])){
			if($Objrestaurant->id!=$_GET['cid']){
 			   $Objrestaurant = $Objrestaurant_data->getDetail($_GET['cid']);			 
			}
		}		
	}
	else {
		  $Objrestaurant= $Objrestaurant_data->getDetail($_GET['cid']);
	}
 
	if($Objrestaurant->region == 1  || $Objrestaurant->region == 2) {$currency = "$";$java_currency = "$";}else{$currency = "&#163;"; $java_currency = "\u00A3";}
	
		$restId=$Objrestaurant->id;
	 if ( $_SESSION['admin_type'] == 'store owner' && $Objrestaurant->owner_id !=$_SESSION['owner_id'] ){
	  	   echo "<script>window.location='./?mod=resturant'</script>";
	 }else if( $_SESSION['admin_type'] == 'reseller')  {
					$client_ids= $_SESSION['RESSELER_CLIENTS'] ;
					$qry = "SELECT count(*) as total FROM resturants WHERE owner_id IN ( $client_ids ) and id=$restId";
					$result=dbAbstract::ExecuteObject($qry,1);
					if($result->total==0)
						 echo "<script>window.location='./?mod=resturant'</script>";
			 }
		 
//		"http://easywayordering.com/c_panel/?mod=resturant"
		
	
	?>
  
    
 
    
  <? if($Objrestaurant) { ?>
<!--  <div style="padding-bottom:10px;text-align:center">
      <img style="width:1085px; height:90px;" src="../images/resturant_headers/<?=$Objrestaurant->header_image?>" border="0" />
  </div>-->
  <? } ?>
 	
  <? 
	@$customerQry		=	dbAbstract::Execute("select count(*) as total from customer_registration where password != '' AND resturant_id= " .$Objrestaurant->id,1);
	@$totalCustomers_rs	=	dbAbstract::returnObject($customerQry,1);
	$totalCustomers=$totalCustomers_rs->total;
	
	@$orderQry			=	dbAbstract::Execute("select count(*) as total from ordertbl where cat_id= ". $Objrestaurant->id ." AND Approve = 0",1);
	@$totalOrders_rs 	=  dbAbstract::returnObject($orderQry,1);
	$totalOrders=$totalOrders_rs->total;	
	if(!isset($item))$item='';
	
  ?>


