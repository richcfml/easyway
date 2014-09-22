<?php
ini_set( "display_errors", "1" );
error_reporting( E_ALL & ~E_NOTICE);

require_once("../includes/config.php"); 
/* Insert orders older than 60 days from ordertable into order_table_archive */
$insertQuery = "INSERT INTO order_table_archive
SELECT * FROM ordertbl
WHERE OrderDate < CURDATE( ) - INTERVAL 60 DAY";

$result = mysql_query($insertQuery);

/* Delete orders older than 60 days from ordertable*/
$deleteQuery = "DELETE FROM ordertbl
WHERE OrderDate < CURDATE( ) - INTERVAL 60 DAY";

$result = mysql_query($deleteQuery);
@mysql_close($mysql_conn);
?>