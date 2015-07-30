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
        if ($pC_Panel==0)
        {
            Log::write("Execute - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract');
        }
        else
        {
            Log::write("Execute - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel');
        }
        
        $mResult =  mysql_query($pSQL);
        
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
        if ($pC_Panel==0)
        {
            Log::write("ExecuteArray - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract');
        }
        else
        {
            Log::write("ExecuteArray - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel');
        }
        $mResult =  mysql_query($pSQL);
        
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
        
        if (mysql_num_rows($mResult) > 0)
        {
            return mysql_fetch_array($mResult);
        }
        else
        {
            return null;
        }
    }
    
    public static function ExecuteObject($pSQL, $pC_Panel = 0, $pClassName = "", $pLogResults = false)
    {
        if ($pC_Panel==0)
        {
            Log::write("ExecuteObject - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract');
        }
        else
        {
            Log::write("ExecuteObject - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel');
        }
        
        $mResult =  mysql_query($pSQL);
        
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
        
        if (mysql_num_rows($mResult) > 0)
        {
            if ($pClassName=="")
            {
                return mysql_fetch_object($mResult);
            }
            else
            {
                return mysql_fetch_object($mResult, $pClassName);
            }
        }
        else
        {
            return null;
        }
    }
    
    public static function ExecuteAssoc($pSQL, $pC_Panel = 0, $pLogResults = false)
    {
        if ($pC_Panel==0)
        {
            Log::write("ExecuteAssoc - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract');
        }
        else
        {
            Log::write("ExecuteAssoc - dbAbstract.php", "QUERY -- ".$pSQL, 'dbAbstract', 1, 'cpanel');
        }
        
        $mResult =  mysql_query($pSQL);
        
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
        
        if (mysql_num_rows($mResult) > 0)
        {
            return mysql_fetch_assoc($mResult);
        }
        else
        {
            return null;
        }
    }
    
    public static function Update($pSQL, $pC_Panel = 0, $pReturnType = 0, $pLogResults = false)
    {
        $mResult =  mysql_query($pSQL);
        $mAffectedRows = mysql_affected_rows();

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
        
        if ($mResult==false) //LOG Failed queries
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
            return $mResult;
        }
        else if ($pReturnType == 1) //Return number of rows affected
        {
            return mysql_affected_rows();
        }
    }
    
    public static function Insert($pSQL, $pC_Panel = 0, $pReturnType = 0, $pLogResults = false)
    {
        $mResult =  mysql_query($pSQL);
        $mAffectedRows = mysql_affected_rows();
        
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
        
        if ($mResult==false) //LOG Failed queries
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
            return $mResult;
        }
        else if ($pReturnType == 1) //Return number of rows affected
        {
            return $mAffectedRows;
        }
        else if ($pReturnType == 2) //Return last inserted id
        {
            return mysql_insert_id();
        }
    }
    
    public static function Delete($pSQL, $pC_Panel = 0, $pReturnType = 0, $pLogResults = false)
    {
        $mResult =  mysql_query($pSQL);
        $mAffectedRows = mysql_affected_rows();
        
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
    
    public static function returnObject($pResult, $pC_Panel = 0, $pClassName = "", $pLogResults = false) //$pResult is result(object) of mysql_query
    {
        if ($pClassName=="")
        {
            $mObject =  mysql_fetch_object($pResult);
        }
        else
        {
            $mObject =  mysql_fetch_object($pResult, $pClassName);
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
    
    public static function returnRow($pResult, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysql_query
    {
        $mRow = mysql_fetch_row($pResult);
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
    
    public static function returnArray($pResult, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysql_query
    {
        $mObject =  mysql_fetch_array($pResult);
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
    
    public static function returnAssoc($pResult, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysql_query
    {
        $mObject = mysql_fetch_assoc($pResult);
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
    
    public static function returnRowsCount($pResult, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysql_query
    {
        $mRowsCount = mysql_num_rows($pResult);
        if ($pLogResults)
        {
            if ($pC_Panel==0)
            {
                Log::write("DB Abstract returnRowsCount Function - dbAbstract.php", "QUERY -- ".print_r($pResult), 'dbAbstract', 0);
            }
            else
            {
                Log::write("DB Abstract returnRowsCount Function - dbAbstract.php", "QUERY -- ".print_r($pResult), 'dbAbstract', 1, 'cpanel');
            }
        }
        return $mRowsCount;
    }
    
    public static function returnResult($pResult, $pRowNum, $pField, $pC_Panel = 0, $pLogResults = false) //$pResult is result(object) of mysql_query
    {
        $mReturn = mysql_result($pResult, $pRowNum, $pField);
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
        $mReturn =  mysql_real_escape_string($pString);
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