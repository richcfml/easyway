<?php $mailid = $_REQUEST['mailid'];

dbAbstract::Delete("DELETE from mailing_list where id=".$mailid, 1);



?>
<script language="javascript">
window.location="?mod=mailing_list&cid=<?=$mRestaurantIDCP?>";
</script>