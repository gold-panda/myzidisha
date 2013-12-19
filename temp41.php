<?php
	error_reporting(E_ALL);
	include("library/session.php");
	global $database,$session;
	$q="ALTER TABLE `borrowers_extn`  ADD `late_repayment_reminder` INT NULL DEFAULT NULL,  ADD `late_repayment_reminders` INT NULL DEFAULT NULL";
	$result=$db->query($q);
	echo $result;
?>