<div style="float:left; width:20%;">
  <div style="width:240px;">
    
    <div id="" class="searchlist">
      <ul>
      	<?
		$reseller_id = $_SESSION['owner_id'];
		//GET ALL CLIENTS
		$reseller_client_sql1 =  "SELECT client_id FROM reseller_client WHERE reseller_id  = '".$reseller_id."' ";
		$reseller_client_qry1 = mysql_query( $reseller_client_sql1 );
		$client_ids1 = "";
		$j = 0;
		while ( $reseller_client_rs1 = mysql_fetch_array ( $reseller_client_qry1 ) ) {
			if( $j == 0) 
				$client_ids1 = $reseller_client_rs1['client_id'];
			else 
				$client_ids1 .= " ,".$reseller_client_rs1['client_id'];
			 
 			$j++;
		}
		
		$client_sql = "SELECT id, firstname, lastname FROM users where type ='store owner' AND id IN ( $client_ids1 )";
		
		$client_qry = mysql_query( $client_sql );
		 
		while ( @$client_rs = mysql_fetch_assoc ( $client_qry ) ) {
			$client_name   = $client_rs['firstname']." ".$client_rs['lastname'];
			if( $Clentid == $client_rs['id']) $class = "selected";
			else $class = "";
			
			// 	GET NUMBER OF RESTURANTS FOR EACH CLIENT
			$client_resturant_sql = "SELECT id FROM resturants WHERE owner_id = '".$client_rs[id]."'";
			$client_resturant_qry = mysql_query($client_resturant_sql);
			$number_rest_per_client = mysql_num_rows( $client_resturant_qry ); 
			 
		?>  
            <li class="<?=$class?>"> <a href="?mod=resturant&client_id=<?=$client_rs['id']?>"><?=$client_name?> <span style="color:#999"> &nbsp;(<?=$number_rest_per_client?>) </span> </a> </a></li>
      	<? } ?>
      
      </ul>
    </div>
    <div id="dotted_line"></div>
  </div>
</div>