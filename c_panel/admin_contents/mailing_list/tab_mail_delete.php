<? $mailid = $_REQUEST['mailid'];

mysql_query("DELETE from mailing_list where id=".$mailid);



?>
<script language="javascript">
window.location="?mod=mailing_list&cid=<?=$mRestaurantIDCP?>";
</script>