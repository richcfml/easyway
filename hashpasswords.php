<?php
    error_reporting(E_ALL);
    set_time_limit(1000);
    ini_set('max_execution_time', 1000);
    
    //require_once('includes/config.php');
    
    $mysql_conn = mysql_connect("104.239.145.91","easywayordering","3a5yw4y0rd3ring") or die( mysql_error()."  cannot connect...");
    mysql_select_db("easywayordering",$mysql_conn);
        
    $mSQL  = "SELECT id, IFNULL(password, '') AS password FROM customer_registration";
    $mRes = mysql_query($mSQL);
    
    while ($mRow=mysql_fetch_object($mRes))
    {
        if (trim($mRow->password)!="")
        {
            $mSalt = hash('sha256', mt_rand(10,1000000));    
            $ePassword= hash('sha256', $mRow->password.$mSalt);
            
            mysql_query("UPDATE customer_registration SET salt='".$mSalt."', epassword='".$ePassword."' WHERE id = ".$mRow->id);            
        }
    }
    
    $mSQL  = "SELECT id, IFNULL(password, '') AS password FROM users";
    $mRes = mysql_query($mSQL);
    
    while ($mRow=mysql_fetch_object($mRes))
    {
        if (trim($mRow->password)!="")
        {
            $mSalt = hash('sha256', mt_rand(10,1000000));    
            $ePassword= hash('sha256', $mRow->password.$mSalt);
            
            mysql_query("UPDATE users SET salt='".$mSalt."', epassword='".$ePassword."' WHERE id = ".$mRow->id);            
        }
    }
    
    echo("Process Completed.")
?>