<?php
/* 22 April 2015
 * Developer: Gulfam
 * Its the database abstract/access class which will be used to handle all sort of SQL Queries.
 * Previously all of the queries were written in PHP files (UI)or separate class files for 
 * each DB table but from now all of the queries will start using this class. 
 */

class dbAbstract
{
    public static function Execute($pSQL, $pC_Panel = 0, $pLogResults = false)
    {
        global $dbh;
        if ($pC_Panel==0)
        {
            Log::write("Execute - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract');
        }
        else
        {
            Log::write("Execute - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel');
        }
        
		$mResult = $dbh->prepare($pSQL);
		$mResult->execute();

		//$mResult =  mysqli_query($mysqli, $pSQL);

        if ($pLogResults == true)
        {
            if ($pC_Panel==0)
            {
                Log::write("Execute - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract');
            }
            else
            {
                Log::write("Execute - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract', 1, 'cpanel');
            }
        }
        
        return $mResult;   
    }
    
    public static function ExecuteArray($pSQL, $pC_Panel = 0, $pLogResults = false)
    {
        global $dbh;
        if ($pC_Panel==0)
        {
            Log::write("ExecuteArray - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract');
        }
        else
        {
            Log::write("ExecuteArray - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel');
        }
        
		//$mResult =  $dbh->query($mysqli, $pSQL);
		$mResult = $dbh->prepare($pSQL);
		$mResult->execute();
        
        if ($pLogResults == true)
        {
            if ($pC_Panel==0)
            {
                Log::write("ExecuteArray - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract');
            }
            else
            {
                Log::write("ExecuteArray - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract', 1, 'cpanel');
            }
        }
        
        if ($mResult->rowCount() > 0)
        {
			$mResult = $mResult->fetchAll(PDO::FETCH_ASSOC);
			if(count($mResult)==1){
				return $mResult[0];
			}
            return $mResult->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return null;
        }
    }
    
    public static function ExecuteObject($pSQL, $pC_Panel = 0, $pClassName = "", $pLogResults = false)
    {
        global $dbh;
        if ($pC_Panel==0)
        {
            Log::write("ExecuteObject - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract');
        }
        else
        {
            Log::write("ExecuteObject - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel');
        }
        
        //$mResult =  $dbh->query($mysqli, $pSQL);
		$mResult = $dbh->prepare($pSQL);
		$mResult->execute();
        
        if ($pLogResults == true)
        {
            if ($pC_Panel==0)
            {
                Log::write("ExecuteObject - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract');
            }
            else
            {
                Log::write("ExecuteObject - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract', 1, 'cpanel');
            }
        }
        
        if ($mResult->rowCount() > 0)
        {
            if ($pClassName=="")
            {
                return $mResult->fetchObject();
            }
            else
            {
                return $mResult->fetchObject($pClassName);
            }
        }
        else
        {
            return null;
        }
    }
    
    public static function ExecuteAssoc($pSQL, $pC_Panel = 0, $pLogResults = false)
    {
        global $dbh;
        if ($pC_Panel==0)
        {
            Log::write("ExecuteAssoc - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract');
        }
        else
        {
            Log::write("ExecuteAssoc - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel');
        }
        
        $mResult = $dbh->prepare($pSQL);
		$mResult->execute();
        
        if ($pLogResults == true)
        {
            if ($pC_Panel==0)
            {
                Log::write("ExecuteAssoc - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract');
            }
            else
            {
                Log::write("ExecuteAssoc - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract', 1, 'cpanel');
            }
        }
        
        if ($mResult->rowCount() > 0)
        {
            return $mResult->fetch(PDO::FETCH_ASSOC);
        }
        else
        {
            return null;
        }
    }
    
    public static function Update($pSQL, $pC_Panel = 0, $pReturnType = 0, $pLogResults = false)
    {
        global $dbh;
		$mResult = $dbh->prepare($pSQL);
		
		if($mResult->execute()){
			$qResult = true;
		}else $qResult = false;
		
        $mAffectedRows = $mResult->rowCount();

        if ($pC_Panel==0)
        {
            Log::write("DB Abstract Update Function - dbAbstract.php", "QUERY -- ".$pSQL."\nRows Affected: ".$mAffectedRows, 'dbAbstract');   
        }
        else
        {
            Log::write("DB Abstract Update Function - dbAbstract.php", "QUERY -- ".$pSQL."\nRows Affected: ".$mAffectedRows, 'dbAbstract', 1, 'cpanel');   
        }
        
        if ($pLogResults == true)
        {
            if ($pC_Panel==0)
            {
                Log::write("Update - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract');
            }
            else
            {
                Log::write("Update - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract', 1, 'cpanel');
            }
        }
        
        if ($qResult==false) //LOG Failed queries
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract Update Function - Query Failed - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 0, 'failed');       
            }
            else
            {
                Log::write("DB Abstract Update Function - Query Failed - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel/failed');       
            }
        }
        
        if ($pReturnType == 0) //Return true or false
        {
            return $qResult;
        }
        else if ($pReturnType == 1) //Return number of rows affected
        {
            return $mAffectedRows;
        }
    }
    
    public static function Insert($pSQL, $pC_Panel = 0, $pReturnType = 0, $pLogResults = false)
    {
        global $dbh;
		$mResult = $dbh->prepare($pSQL);
		
		if($mResult->execute()){
			$qResult = true;
		}else $qResult = false;
		
        $mAffectedRows = $mResult->rowCount();
        
        if ($pC_Panel==0)
        {
            Log::write("DB Abstract Insert Function - dbAbstract.php", "QUERY -- ".$pSQL."\nRows Affected: ".$mAffectedRows, 'dbAbstract');   
        }
        else
        {
            Log::write("DB Abstract Insert Function - dbAbstract.php", "QUERY -- ".$pSQL."\nRows Affected: ".$mAffectedRows, 'dbAbstract', 1, 'cpanel');   
        }
     
        if ($pLogResults == true)
        {
            if ($pC_Panel==0)
            {
                Log::write("Insert - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract');
            }
            else
            {
                Log::write("Insert - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract', 1, 'cpanel');
            }
        }
        
        if ($qResult==false) //LOG Failed queries
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract Insert Function - Query Failed - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 0, 'failed');       
            }
            else
            {
                Log::write("DB Abstract Insert Function - Query Failed - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel/failed');       
            }
        }
        
        if ($pReturnType == 0) //Return true or false
        {
            return $qResult;
        }
        else if ($pReturnType == 1) //Return number of rows affected
        {
            return $mAffectedRows;
        }
        else if ($pReturnType == 2) //Return last inserted id
        {
            return $dbh->lastInsertId();
        }
    }
    
    public static function Delete($pSQL, $pC_Panel = 0, $pReturnType = 0, $pLogResults = false)
    {
         global $dbh;
		$mResult = $dbh->prepare($pSQL);
		$mResult->execute();
		$mAffectedRows = $mResult->rowCount();
        
        if ($pC_Panel==0)
        {
            Log::write("DB Abstract Delete Function - dbAbstract.php", "QUERY -- ".$pSQL."\nRows Affected: ".$mAffectedRows, 'dbAbstract');
        }
        else
        {
            Log::write("DB Abstract Delete Function - dbAbstract.php", "QUERY -- ".$pSQL."\nRows Affected: ".$mAffectedRows, 'dbAbstract', 1, 'cpanel');
        }
        
        if ($pLogResults == true)
        {
            if ($pC_Panel==0)
            {
                Log::write("Delete - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract');
            }
            else
            {
                Log::write("Delete - Query Result - dbAbstract.php", print_r($mResult), 'dbAbstract', 1, 'cpanel');
            }
        }
        
        if ($mResult==false) //LOG Failed queries
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract Delete Function - Query Failed - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 0, 'failed');
            }
            else
            {
                Log::write("DB Abstract Delete Function - Query Failed - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel/failed');
            }
        }
        
        if ($pReturnType == 0) //Return true or false
        {
            return $mResult;
        }
        else if ($pReturnType == 1) //Return number of rows affected
        {
            return $mAffectedRows;
        }
    }
    
    public static function returnObject($pResult, $pC_Panel = 0, $pClassName = "", $pLogResults = false) //$pResult is result(object) of mysqli_query
    {
        if ($pClassName=="")
        {
            $mObject =  $pResult->fetchObject();
        }
        else
        {
            $mObject =  $pResult->fetchObject($pClassName);
        }
     
        if ($pLogResults)
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract returnObject Function - dbAbstract.php", "QUERY -- ".print_r($mObject), 'dbAbstract', 0);
            }
            else
            {
                Log::write("DB Abstract returnObject Function - dbAbstract.php", "QUERY -- ".print_r($mObject), 'dbAbstract', 1, 'cpanel');
            }
        }
        return $mObject;
    }
    
    public static function returnRow($pResult, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysqli_query
    {
        $mRow = $pResult->fetch();
        if ($pLogResults)
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract returnRow Function - dbAbstract.php", "QUERY -- ".print_r($mRow), 'dbAbstract', 0);
            }
            else
            {
                Log::write("DB Abstract returnRow Function - dbAbstract.php", "QUERY -- ".print_r($mRow), 'dbAbstract', 1, 'cpanel');
            }
        }
        return $mRow;
    }
    
    public static function returnArray($pResult, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysqli_query
    {
        $mObject =  $pResult->fetchAll();
        if ($pLogResults)
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract returnArary Function - dbAbstract.php", "QUERY -- ".print_r($mObject), 'dbAbstract', 0);
            }
            else
            {
                Log::write("DB Abstract returnArray Function - dbAbstract.php", "QUERY -- ".print_r($mObject), 'dbAbstract', 1, 'cpanel');
            }
        }
        return $mObject;
    }
    
    public static function returnAssoc($pResult, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysqli_query
    {
        $mObject = $pResult->fetch(PDO::FETCH_ASSOC);
        if ($pLogResults)
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract returnAssoc Function - dbAbstract.php", "QUERY -- ".print_r($mObject), 'dbAbstract', 0);
            }
            else
            {
                Log::write("DB Abstract returnAssoc Function - dbAbstract.php", "QUERY -- ".print_r($mObject), 'dbAbstract', 1, 'cpanel');
            }
        }
        return $mObject;
    }
    
    public static function returnRowsCount($pResult, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysqli_query
    {
        if ($pC_Panel==0)
        {
            Log::write("DB Abstract returnRowsCount Function - dbAbstract.php", "QUERY -- ".print_r($pResult), 'dbAbstract', 0);    
        }
        else
        {
            Log::write("DB Abstract returnRowsCount Function - dbAbstract.php", "QUERY -- ".print_r($pResult), 'dbAbstract', 1, 'cpanel');
        }
        $mRowsCount = $pResult->rowCount();
        if ($pLogResults)
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract returnRowsCount Function - dbAbstract.php", "QUERY -- ".$mRowsCount, 'dbAbstract', 0);
            }
            else
            {
                Log::write("DB Abstract returnRowsCount Function - dbAbstract.php", "QUERY -- ".$mRowsCount, 'dbAbstract', 1, 'cpanel');
            }
        }
        return $mRowsCount;
    }
    
    public static function returnResult($pResult, $pRowNum, $pField, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysqli_query
    {
        //$mReturn = mysqli_result($pResult, $pRowNum, $pField);
		$mReturn = $pResult[$pRowNum][$pField];
        if ($pLogResults)
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract returnResult Function - dbAbstract.php", "QUERY -- ".print_r($mReturn), 'dbAbstract', 0);
            }
            else
            {
                Log::write("DB Abstract returnResult Function - dbAbstract.php", "QUERY -- ".print_r($mReturn), 'dbAbstract', 1, 'cpanel');
            }
        }
        return $mReturn;
    }
    
    public static function returnRealEscapedString($pString, $pC_Panel = 0, $pLogResults = false)
    {
		/*
		* We do not need this function any more.
		* Which is why, instead we use what is called "a prepared statement", 
		* as it eliminates the  need to sanitize the inputs in the first place.
		*/
		return $pString;
        global $mysqli;
        $mReturn =  mysqli_real_escape_string($mysqli, $pString);
        if ($pLogResults)
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract returnRealEscapedString Function - dbAbstract.php", "Return String -- ".$mReturn, 'dbAbstract', 0);
            }
            else
            {
                Log::write("DB Abstract returnRealEscapedString Function - dbAbstract.php", "Return String -- ".$mReturn, 'dbAbstract', 1, 'cpanel');
            }
        }
        return $mReturn;
    }
}
?>