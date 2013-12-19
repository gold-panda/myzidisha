<?php
	include("library/session.php");
	global $database,$session;
	$test = $database->getRepayment_InstructionsByCountryCode('NE');
	echo $test['description'];exit;
?>