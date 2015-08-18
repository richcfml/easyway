<?php 
class Country{
	
	/*
	*	Get Countries
	*/
	function getCountry($where,$orderby){ 
 		return dbAbstract::Execute("SELECT * FROM country $where $orderby",1);
	}
	
	/*
	*	Get Country Name
	*/
	function getName($iso){ 
		$name_qry_exec = dbAbstract::Execute("SELECT name FROM country WHERE iso = '$iso'",1);
		$name_rs       = dbAbstract::returnObject($name_qry_exec,1);
		return $name_rs->name;
	}

	/*
	*	Get Printable Country Name
	*/
	function getPrintableName($iso){ 
		$printable_name_qry_exec = dbAbstract::Execute("SELECT printable_name FROM country WHERE iso = '$iso'",1);
		$printable_name_rs       = dbAbstract::returnObject($printable_name_qry_exec,1);
		return $printable_name_rs->printable_name;
	}
	
	/*
	*	Get Country Short Code Consist On 3 Characters
	*/
	function getISO3($iso){ 
		$iso3_qry_exec = dbAbstract::Execute("SELECT iso3 FROM country WHERE iso = '$iso'",1);
		$iso3_rs       = dbAbstract::returnObject($iso3_qry_exec,1);
		return $iso3_rs->iso3;
	}
	
	/*
	*	Get Country Number code
	*/
	function getNumberCode($iso){ 
		$numcode_qry_exec = dbAbstract::Execute("SELECT numcode FROM country WHERE iso = '$iso'",1);
		$numcode_rs       = dbAbstract::returnObject($numcode_qry_exec,1);
		return $numcode_rs->numcode;
	}
	
	/*
	*	Get Country Dropdown List
	*/
	function getDropDownList($value){
		$drop_qry_exec = dbAbstract::Execute("SELECT * FROM country",1);
		while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
			if($drop_qry_rs->iso==$value){ 
				echo "<option value=".$drop_qry_rs->iso." selected>".$drop_qry_rs->printable_name."</option>";
			}
			else{ 
				echo "<option value=".$drop_qry_rs->iso.">".$drop_qry_rs->printable_name."</option>";
			}
		} 
	}
	
	/*
	*	Get Users Dropdown List
	*/
	function getUsersDropDownList($value){ 
		$drop_qry_exec = dbAbstract::Execute("SELECT * FROM users",1);
		while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
			if($drop_qry_rs->id==$value){ 
				echo "<option value=".$drop_qry_rs->id." selected>".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
			}
			else{ 
				echo "<option value=".$drop_qry_rs->id.">".$drop_qry_rs->firstname." ".$drop_qry_rs->lastname."</option>";
			}
		} 
	}
	
	/*	End of class country	*/
}
 ?>