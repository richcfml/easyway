<?php 
class clscountry{ 


//________________________________________________________________________________

 function get_country($where,$orderby){ 
 	 return mysql_query("SELECT * FROM country $where $orderby");
}

//________________________________________________________________________________

 function get_name($iso){ 
 	 $name_qry_exec = mysql_query("SELECT name FROM country WHERE iso = '$iso'");
	 $name_rs       = mysql_fetch_object($name_qry_exec);
	 return $name_rs->name;
}
//________________________________________________________________________________

 function get_printable_name($iso){ 
 	 $printable_name_qry_exec = mysql_query("SELECT printable_name FROM country WHERE iso = '$iso'");
	 $printable_name_rs       = mysql_fetch_object($printable_name_qry_exec);
	 return $printable_name_rs->printable_name;
}
//________________________________________________________________________________

 function get_iso3($iso){ 
 	 $iso3_qry_exec = mysql_query("SELECT iso3 FROM country WHERE iso = '$iso'");
	 $iso3_rs       = mysql_fetch_object($iso3_qry_exec);
	 return $iso3_rs->iso3;
}
//________________________________________________________________________________

 function get_numcode($iso){ 
 	 $numcode_qry_exec = mysql_query("SELECT numcode FROM country WHERE iso = '$iso'");
	 $numcode_rs       = mysql_fetch_object($numcode_qry_exec);
	 return $numcode_rs->numcode;
}
//________________________________________________________________________________

 function get_country_drop_down($value){ 
 	$drop_qry_exec = mysql_query("SELECT * FROM country");
//	$drop_qry_rs   = mysql_fetch_object($drop_qry_exec);
	while($drop_qry_rs = mysql_fetch_object($drop_qry_exec)){
		 if($drop_qry_rs->iso==$value){ 
			 echo "<option value=".$drop_qry_rs->iso." selected>".$drop_qry_rs->printable_name."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->iso.">".$drop_qry_rs->printable_name."</option>";
		}
	 } 
}
//________________________________________________________________________________

 function get_users_drop_down($value){ 
 	$drop_qry_exec = mysql_query("SELECT * FROM users");
	while($drop_qry_rs = mysql_fetch_object($drop_qry_exec)){
		 if($drop_qry_rs->id==$value){ 
			 echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		} else { 
			 echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
		}
	 } 
	}
//________________________________________________________________________________


}
 ?>