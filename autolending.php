<?php
	include("library/session.php");
	global $database,$session;
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
	$session->ProcessAutoBidding();
	exit;
?>