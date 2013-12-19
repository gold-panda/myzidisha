<?php
	include("library/session.php");
	global $database,$session;
	$tablename = 'labels';
	$clone_tablename = 'labels1';
	$copytable = "CREATE TABLE $clone_tablename LIKE $tablename"; 
	$res = $db->query($copytable);
	$copydata = "INSERT $clone_tablename SELECT * FROM $tablename";
	$res = $db->query($copydata);
	$truncate = "Truncate table $tablename";
	$res = $db->query($truncate);
	$sql = "ALTER TABLE  $tablename DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
	$res = $db->query($sql);
	$restore = "INSERT $tablename SELECT * FROM $clone_tablename";
	$res = $db->query($restore);
	echo"complete";
	exit;
?>