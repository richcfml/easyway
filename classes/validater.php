<?php 
 /***********************************************************************
                    validater.php

 /***********************************************************************
     *
     *   This class has different functions that are used to validate 
		 *   Form fielads that is submitted by the user 
     *   
     *
  ***********************************************************************/
	
  class validate {
	
		/*
			This function used to check if user did not left some field empty 
		*/	
     function chkEmpty($fieldVal){
		if (empty($fieldVal)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	   /*
			This Function is used to check if the text length of a field is meeting
	        the minimum range of requirements
	  */
	 function minLength($fieldVal,$Val){
	   if (strlen($fieldVal)<$Val){
			return true;	
		} else {
			return false;
		 }
	 }
	 
	 
	/* (2)
	*  This function is used to check if text is not exceeding the maximum
	*  limit
	*/
	
	function maxLength($fieldVal,$Val){
	   if (strlen($fieldVal)>$Val){
			return true;	
		} else {
			return false;
		 }
	 }
	
	
	/* (3)
		  This Function is used to check if ther is a zero charcter in the 
		  very first position of field 
	*/
	function isZeroLead($fieldVal){
	 	$first = $fieldVal{0};
	 	if($first=="0") {
	 		return true;
		} else {
			return false; 
		}	    
	}
 
 
 /* (4)
	  This Function is used to check if the given field text is a valid number 
	*/
	function isNumber($inputString) {
	 	//flag=True;
		//for $i=0;$i< strlen($inputString);$i++){
		if (is_numeric ($inputString)){
			return true;
		}else{
			return false;
		}
	}
 
 /** (10)
	*  This method is used to check if the given text in the field is only alphabetic charcters or space
	*/
	function alphabetCheck($fieldVal) { //fun
	    if (ereg("^([-a-zA-Z0-9_\.\!@#\$&\*\+\=\|])*$" , $fieldVal))
   {
   return true;
   }
   else
   {
   return false;
   }

  
  } //function end 
  
  
  /* Function Email Validation ***************  */

  function IsEMail($email)
{
   if(eregi("^[a-zA-Z0-9]+[_a-zA-Z0-9-]*(\.[_a-z0-9-]+)*@[a-z?G0-9]+(-[a-z?G0-9]+)*(\.[a-z?G0-9-]+)*(\.[a-z]{2,4})$", $email))
   {
       return false;
   } 
   else{
   return true;}
} 

  

}//class end 

?>