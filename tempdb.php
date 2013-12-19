<?php
	include("library/session.php");
	global $database,$session;
	$q = "UPDATE `borrowers` SET  `Assigned_to` =  '0',`Assigned_date` = NULL  WHERE  `borrowers`.`userid` =5085";
	$res = $db->query($q);
	$q = "UPDATE  `borrowers` SET  `Assigned_to` =  '0',`Assigned_date` = NULL WHERE  `borrowers`.`userid` =6784";
	$db->query($q);
?>