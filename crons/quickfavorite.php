<?php
//Gulfam - 03 October 2014 
//Following query will set those Orders to close for Rapis Re-ordering (Quick Favorite) module which are more than 30 minutes old and are still open
//Its a requirment from Client.
require_once("../includes/config.php"); 
$mUpdateQuery = "UPDATE repid_reordering_trace SET trace_status=2 WHERE trace_status=1 AND (UNIX_TIMESTAMP()-trace_date)>1800";
dbAbstract::Update($mUpdateQuery);
//@mysql_close($mysql_conn);
?>
<?php mysqli_close($mysqli);?>