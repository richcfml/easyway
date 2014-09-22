<?php
	header('Content-type: application/octet-stream');
	header('Content-Disposition: attachment; filename=ewo_order_online.png');
	readfile("../images/ewo_order_online.png");
?>