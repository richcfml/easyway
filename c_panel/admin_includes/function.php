<?php 
	////////////////////////////////function for restuarant name //////////////////////////////////////////////
	function cat_name($cat_id)
			{
				$cat_qry	= dbAbstract::Execute("SELECT cat_name from categories where cat_id =$cat_id",1);
				$cat_qryRs	= dbAbstract::returnObject($cat_qry,1); 
				return	$cat_qryRs->cat_name;
			}
	////////////////////////////////function for customer name //////////////////////////////////////////////
	function cust_name($cust_id)
			{
				$cust_qry	= dbAbstract::Execute("SELECT cust_your_name from customer_registration where id =$cust_id",1);
				$cust_qryRs	= dbAbstract::returnObject($cust_qry,1); 
				return	$cust_qryRs->cust_your_name; 
			}
	////////////////////////////////function for product name //////////////////////////////////////////////
	function product_name($prd_id)
			{
				$prd_qry	= dbAbstract::Execute("SELECT item_title from product where prd_id =$prd_id",1);
				$prd_qryRs	= dbAbstract::returnObject($prd_qry,1); 
				return	$prd_qryRs->item_title;
			}
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	function get_weekselectionlist($selected_week,$list_limit,$additional_limit=0){
	if($list_limit < 0){
   		$timetostart = strtotime($list_limit.' week');
		$list_limit = ($list_limit - (2*$list_limit));
	}else{
		$timetostart = time();
	}
	

	$curr_year	= date("Y",$timetostart);
	$select_list = '';
	$start_week = date("W",$timetostart);
	$k			= 0;
	$list_limit = $list_limit + $additional_limit;
	/*  Get First day of the current week*/
	for($i=$start_week; $i<=$start_week+$list_limit; $i++) {

		if($curr_year == date("Y",strtotime($curr_year.'W'.$i.'1')) ){
			$week_first_day = strtotime($curr_year.'W'.$i.'1');
		}else{
			$week_first_day = strtotime(date("Y",$week_first_day+7*24*60*60-24*60*60).'W'.$k.'1');
			$k++;
		}
		$select_list .= "<option value=\"$week_first_day\"";  
		if($selected_week == $week_first_day) $select_list .= "selected=\"selected\"" ;
		$select_list .= ">";
		$select_list .= "".strftime('%Y-%m-%d',$week_first_day) ." ~ " . strftime('%Y-%m-%d',$week_first_day+7*24*60*60-24*60*60) ."</option>";
	
		//$week_first_day = $week_first_day+7*24*60*60;
	}
	
		return $select_list;    
}

/////////////////////////////////////// get first day of weak ////////////////////////////////////////////////////////
function cat_info($cat_id)
			{
				$cat_qry	= dbAbstract::Execute("SELECT cat_id,cat_name,tax_percent,report_discount from categories where cat_id =$cat_id",1);
				$cat_qryRs	= dbAbstract::returnObject($cat_qry,1); 
				$cat_info[0]	= $cat_qryRs->cat_name;
				$cat_info[1]	=  $cat_qryRs->tax_percent;
				$cat_info[2]	=  $cat_qryRs->report_discount;
				return $cat_info;
			}

 function get_users_drop_down($value ){ 
 	if( $_SESSION['admin_type'] == 'reseller'  ) {
		$reseller_client_sqlStr =  "SELECT client_id FROM reseller_client WHERE reseller_id  = '".$_SESSION['owner_id']."' ";
		$reseller_client_qry = dbAbstract::Execute( $reseller_client_sqlStr,1 );
		$client_ids = "";
		$i = 0;
		while ( $reseller_client_rs = dbAbstract::returnArray($reseller_client_qry,1) ) {
		   
			if( $i == 0) 
				$client_ids = $reseller_client_rs['client_id'];
			else 
				$client_ids .= " ,".$reseller_client_rs['client_id'];
			$i++;
		}
		
		$drop_qry_exec = dbAbstract::Execute("SELECT * FROM users WHERE  id IN ( $client_ids )",1);
		
	} else {
 	
		$drop_qry_exec = dbAbstract::Execute("SELECT * FROM users WHERE type = 'store owner'",1);
	} 
	 
	while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
		if($drop_qry_rs->id==$value){ 
			 echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		}	
	 }
}
//________________________________________________________________________________

 function users_drop_down($value){ 
 	$drop_qry_exec = dbAbstract::Execute("SELECT * FROM users WHERE type = 'store owner'",1);
	while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
		 if($drop_qry_rs->id==$value){ 
			 echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		}	
	 } 
	}
//________________________________________________________________________________

 function client_drop_down($value, $resellerId=0){ 
 	
	if(  $_SESSION['admin_type'] == 'reseller'   ) {
		
		$resellerId = $_SESSION['owner_id'];
	} 
	
	$ids = resellers_client( $resellerId );// this function will bring the only ids whic belongs to current reseller.
  
	$clientQry	=	"select * from users WHERE type = 'store owner' AND status = 1 AND id IN ( $ids ) ";
	
	$drop_qry_exec = dbAbstract::Execute("SELECT * from users WHERE type = 'store owner' AND id IN ( $ids ) ",1);
	while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
		 if($drop_qry_rs->id==$value){ 
			 echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		}	
	 } 
	}
//________________________________________________________________________________

 function resellers_drop_down($value){ 
 	$drop_qry_exec = dbAbstract::Execute("SELECT * FROM users WHERE type = 'reseller' AND status = 1",1);
	while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
		 if($drop_qry_rs->id==$value){ 
			 echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		}	
	 } 
	}
//________________________________________________________________________________
function licenses_drop_down( $value, $reseler_id= 0 ){ 
	if(  $_SESSION['admin_type'] == 'reseller'   ) {
		
		$resellerId = $_SESSION['owner_id'];
	} else if(  $_SESSION['admin_type'] == 'admin'  ) {
		$resellerId = $reseler_id;
	}
 	$drop_qry_exec = dbAbstract::Execute("SELECT id, license_key FROM licenses WHERE status = 'unused' AND reseller_id  = '".$resellerId."' ");
	while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec)){
		 if($drop_qry_rs->id==$value){ 
			 echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->license_key."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->license_key."</option>";
		}	
	 } 
	}
//________________________________________________________________________________

 function resellers_client( $reseller_id) {
	// get all client ids of specific reseller
	$reseller_client_sqlStr =  "SELECT client_id FROM reseller_client WHERE reseller_id  = '".$reseller_id."' ";
	$reseller_client_qry = dbAbstract::Execute( $reseller_client_sqlStr,1 );
	$client_ids = "";
	$i = 0;
	while ( $reseller_client_rs = dbAbstract::returnArray($reseller_client_qry,1)) {
			   
		if( $i == 0) 
			$client_ids = $reseller_client_rs['client_id'];
		else 
			$client_ids .= " ,".$reseller_client_rs['client_id'];
		 
		$i++;
	}
	if( empty( $client_ids ) || $client_ids == "" ) {
		
		$client_ids = "0";
	
	}
	return $client_ids;
	
}

//________________________________________________________________________________
function get_reseller_client_names( $client_id ) {
	
	$name_str = "";
	$client_reseller_sql = "select reseller_id from reseller_client WHERE client_id = '".$client_id."'";
	$client_reseller_qry = dbAbstract::Execute( $client_reseller_sql,1 );
	$client_reseller_rs	= dbAbstract::returnArray( $client_reseller_qry,1 );
	
	$reseller_name_sql 	= "select firstname,lastname from users WHERE id = '".$client_reseller_rs['reseller_id']."' AND type = 'reseller'";
	$reseller_name_qry = dbAbstract::Execute( $reseller_name_sql,1 );
	$reseller_name_rs	= dbAbstract::returnArray( $reseller_name_qry,1 );
	
	$client_name_sql	= "select firstname,lastname from users WHERE id = '".$client_id."' AND type = 'store owner'";
	$client_name_qry = dbAbstract::Execute( $client_name_sql,1 );
	$client_name_rs	= dbAbstract::returnArray( $client_name_qry,1 );
	
	$name_str = $reseller_name_rs['firstname']." ".$reseller_name_rs['lastname']." - ".$client_name_rs['firstname']." ".$client_name_rs['lastname'];
	
	return $name_str;
}
//________________________________________________________________________________
function get_number_of_clients( $reseller_id) {
	$ids = resellers_client( $reseller_id );
	
	$client_str	=	"select * from users WHERE type = 'store owner' AND status = 1 AND id IN ( $ids ) ";
	$client_qry = dbAbstract::Execute( $client_str,1 );
	$number_of_clients = dbAbstract::returnRowsCount( $client_qry,1 );
	
	return $number_of_clients;
}
//________________________________________________________________________________
function get_number_of_licenses( $reseller_id ) {
	
	
	$license_str	=	"SELECT id FROM licenses WHERE reseller_id = '$reseller_id' ";
	
	$license_qry = dbAbstract::Execute( $license_str,1 );
	$number_of_licenses = dbAbstract::returnRowsCount( $license_qry,1 );
	
	return $number_of_licenses;
}
//________________________________________________________________________________

function get_number_of_resturants( $client_id) {
	// 	GET NUMBER OF RESTURANTS FOR EACH CLIENT
	$client_resturant_sql = "SELECT id FROM resturants WHERE owner_id = '".$client_id."'";
	$client_resturant_qry = dbAbstract::Execute($client_resturant_sql,1);
	$number_rest_per_client = dbAbstract::returnRowsCount($client_resturant_qry,1); 
	
	return $number_rest_per_client;
}


//________________________________________________________________________________

 function get_menu_drop_down($values){
	  
 	$drop_qry_exec = dbAbstract::Execute("SELECT cat_id, cat_name FROM categories WHERE parent_id = $values[0]",1);
	while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
		 if($drop_qry_rs->cat_id == $values[1]){ 
			 echo "<option value=".$drop_qry_rs->cat_id." selected>".$drop_qry_rs->cat_name."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->cat_id.">".$drop_qry_rs->cat_name."</option>";
		}	
	 } 
	}
	/////////////////////////////////////// make time zones drodown ////////////////////////////////////////////////////////
 function get_timezone_drop_down($value){
 	$drop_qry_exec = dbAbstract::Execute("SELECT * FROM times_zones ",1);

        while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
		 if($drop_qry_rs->id==$value){ 
			 echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->time_zone."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->time_zone."</option>";
		}	
	 } 
	}

    function get_timezone_drop_downUK($value){
 	$drop_qry_exec = dbAbstract::Execute("SELECT * FROM times_zones where time_zone='Europe/London'",1);
        while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){

		 if($drop_qry_rs->id==$value){
			 echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->time_zone."</option>";
		} else {
			 echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->time_zone."</option>";
		}
	 }
	}

	//________________________________________________________________________________
//	function url_title($mystr){
//		$result = '';
//		$result .= preg_replace_callback('/[^a-zA-Z0-9]+/', function ($matches) { return '_'; }, $mystr);
//		return strtolower($result);
//	}
//	
//	//________________________________________________________________________________
//	function currencyToNumber($price){
//		preg_replace_callback('/[\$,]/', function ($matches) { return ''; }, $price);
//	}
			
?>