<?php
	//mysql_query("DELETE FROM cydne_log WHERE ToPhoneNumber='123321123'");
	//mysql_query("DELETE FROM general_detail WHERE id_2=22831 AND data_1=1111");
	
	$qry=mysql_query("select * from repid_reordering_trace where phone_number='6465435694'");
	while ($row = mysql_fetch_array($qry))
	{
		echo($row['easyway_id'].'||'.$row['order_title'].'||'.$row['trace_status'].'||'.$row['ntries'].'||'.$row['step'].'<br />');
	}
	
	$qry=mysql_query("select * from cydne_log where ToPhoneNumber='123321123'");
	echo("<br /><br /><br /><br />");
	while ($row = mysql_fetch_array($qry))
	{
		echo($row['FromPhoneNumber'].'<br />');
	}
?>
