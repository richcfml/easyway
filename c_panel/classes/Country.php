<?php 
class Country{
	
	/*
	*	Get Countries
	*/
	function getCountry($field='*', $where='',$orderby=''){
		$where = ($where!='')? 'WHERE '.$where:'';
		$orderby = ($orderby!='')? 'orderby '.$orderby:'';
 		return dbAbstract::Execute("SELECT $field FROM country $where $orderby",1);
	}
	
	/*
	*	Get Country Name By Iso
	*/
	function getCountryNameByIso($iso){
		$name_qry_exec = $this->getCountry('name', "iso = '$iso'");
		$name_rs       = dbAbstract::returnObject($name_qry_exec,1);
		return $name_rs->name;
	}
	
	/*
	*	Get Printable Country Name By Iso
	*/
	function getPrintableNameByIso($iso){ 
		$printable_name_qry_exec = $this->getCountry('printable_name', "iso = '$iso'");
		$printable_name_rs       = dbAbstract::returnObject($printable_name_qry_exec,1);
		return $printable_name_rs->printable_name;
	}
	
	/*
	*	Get Country Short Code Consist On 3 Characters by Iso
	*/
	function getISO3ByIso($iso){
		$iso3_qry_exec = $this->getCountry('iso3', "iso ='$iso'");
		$iso3_rs       = dbAbstract::returnObject($iso3_qry_exec,1);
		return $iso3_rs->iso3;
	}
	
	/*
	*	Get Country Number code by Iso
	*/
	function getNumberCodeByIso($iso){ 
		$numcode_qry_exec = $this->getCountry('numcode', "iso ='$iso'");
		$numcode_rs       = dbAbstract::returnObject($numcode_qry_exec,1);
		return $numcode_rs->numcode;
	}
	
	/*
	*	Get Country Dropdown List
	*/
	function getCountyDropDownList($value){
		$drop_qry_exec = $this->getCountry('*');
		while($drop_qry_rs = dbAbstract::returnObject($drop_qry_exec,1)){
			if($drop_qry_rs->iso==$value){ 
				echo "<option value=".$drop_qry_rs->iso." selected>".$drop_qry_rs->printable_name."</option>";
			}
			else{ 
				echo "<option value=".$drop_qry_rs->iso.">".$drop_qry_rs->printable_name."</option>";
			}
		} 
	}
	
	/*	End of class country	*/
}
 ?>