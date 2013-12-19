<?php
	include_once("../library/session.php");
	global $db;	
	$res=0;
	$brwrid = $_POST["id"];
	$lastvisited = strtotime($_POST["lastvisited"]);
	$admin_notes = $_POST["admin_notes"];
	$currentdate = time();
	if((!empty($brwrid))) {
		$p = "SELECT id, note from ! where borrowerid = ?";
		$repaydet = $db->getRow($p,array('repay_report_detail', $brwrid));
		if(!empty($repaydet)) {
			$q = "UPDATE ! set lastVisited = ?, note = ?, modified = ? where borrowerid = ?";
			$res = $db->query($q, array('repay_report_detail', $lastvisited, $admin_notes, $currentdate, $brwrid ));
			Logger("UPDATE activated borrower : logged in user id \n".$session->userid);
			Logger("UPDATE activated borrower : last visited , admin notes , currntdate,borrower id \n".$lastvisited."  ".$admin_notes."  ". $currentdate."  ".$brwrid);
		}else {
			
			$q1 = "INSERT INTO ! (borrowerid, lastVisited, note, created) VALUES  (?,?,?,?)";
			$res=$db->query($q1, array('repay_report_detail', $brwrid, $lastvisited,$admin_notes, $currentdate));
		}
	}
	if($res === 1) {
		echo "<font color=green>saved</font>";
	}
	else
		echo "<font color=red>failed</font>";
?>