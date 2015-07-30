<? 
	//*************************** Class utility for necessery function  *********************/
	session_start();
	 class clsFunctions  {
	 
	
	function ValidateAdmin_MySQL($user , $pass , $fun_qry_str , $pass_feild){
					if(($user) && ($user != '')) { 
                    	$quary = dbAbstract::Execute($fun_qry_str,1);
                      	$quary_rs = dbAbstract::returnArray($quary,1);
						
						if(!empty($quary_rs['id'])) {
						
                             $pass_sql = $quary_rs[$pass_feild];
                                               
									 if($pass_sql == $pass){
										// V A L I D  L O G I N
										$_SESSION['admin_type'] = $quary_rs['type'];
										$_SESSION['owner_id'] = $quary_rs['id'];
										$_SESSION['status'] = $quary_rs['status'];
										
										$query1 = dbAbstract::Execute("SELECT * FROM categories WHERE parent_id = 0 AND owner_id = $_SESSION[owner_id]",1);
										$cat_qryRs = dbAbstract::returnArray($query1,1);	
										$_SESSION['restorant_logo'] = $cat_qryRs['cat_logo'];	
										
										return 1;
																				
									 } else {
										
										return 0;
									 
									 }
                        }
					}
	
	} // End of function	
	
	function Send_Mail($email,$subject,$body,$from){
			// message
			$message = '

			<html>
			<head>
			 
			</head>
			<body>
			  '."$body".'
			</body>
			</html>
			';
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From:"."onlineorder  <qasim@qualityclix.com>". "\r\n";
			mail($email, $subject, $message, $headers);
}

	
//////////////////////////////////////////////////////////////////////////////////////
		
		function get_previous_datelist($select_date){
	
	$date	=	time();
	$today 	= date('Y-m-d',$date);
	$select_list .= "<option value=\"$date\""; 
	$select_list .= ">";
	$select_list .= "".$today."</option>";
		
	for($i=1; $i<60; $i++) 
			{
				$next			= mktime(0,0,0,date("m"),date("d")-$i,date("Y"));
				$next_date		= date('Y-m-d', $next);
				$select_list .= "<option value=\"$next\"";  
				if($select_date == $next) $select_list .= "selected=\"selected\"" ;
				$select_list .= ">";
				$select_list .= "".$next_date."</option>";
			}
	
		return $select_list;    
}
	/////////////////////////////////////////////////////////////////////////////////////////////

} //Class End 

function prepareStringForMySQL($string)
{
    $string=str_replace ( "\r" , "<br/>",$string);
    $string=str_replace ( "\n" , "<br/>",$string);
    $string=str_replace ( "\t" , " ",$string);
    $string=dbAbstract::returnRealEscapedString($string);
    return $string;
}
?>