<?php
    error_reporting(E_ALL);
    set_time_limit(2000);
    ini_set('max_execution_time', 2000);
    //require_once('includes/config.php');
    $mysql_conn = mysql_connect("23.253.249.73","dbUser","PR3st!2014") or die( mysql_error()."  cannot connect...");
    mysql_select_db("easywayordering",$mysql_conn);
    require_once('c_panel/includes/SimpleImage.php');
    require_once('c_panel/includes/snapshot.class.php');    
    
    $mSQL  = "SELECT * FROM resturants";
    $mRes = mysql_query($mSQL);
    
    while ($mRow=mysql_fetch_object($mRes))
    {
        if (trim($mRow->optionl_logo)!="")
        {
            if (file_exists(realpath("images/logos_thumbnail/".trim($mRow->optionl_logo)))) 
            {
                list($width, $height, $type, $attr) = getimagesize("images/logos_thumbnail/".trim($mRow->optionl_logo));
                if ($height >= $width) 
                {
                    $image = new SimpleImage();
                    $image->load("images/logos_thumbnail/".trim($mRow->optionl_logo));
                    $image->resizeToHeight(330);
                    $image->save("images/logos_thumbnail/".trim($mRow->optionl_logo));
                } 
                else 
                {
                    $image = new SimpleImage();
                    $image->load("images/logos_thumbnail/".trim($mRow->optionl_logo));
                    $image->resizeToWidth(300);
                    $image->save("images/logos_thumbnail/".trim($mRow->optionl_logo));
                }
            }
        }
    }
    
    echo("Completed.")
?>
