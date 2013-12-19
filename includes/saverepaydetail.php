<?php
	include_once("../library/session.php");
	global $db;	
	$res=0;
	$id = $_POST["q"];
	$name = $_POST["name"];
	$number = $_POST["number"];
	$date = strtotime($_POST["date"]);
	$currentdate = time();
	$note = $_POST["note"];
	$borrowerid = $_POST["borrowerid"];
	$loanid = $_POST["loanid"];
	$isedit = $_POST["isedit"];
	$mentor = $_POST["mentor"];
	$res1 = 0;
	if((!empty($id))) {
		$q = "UPDATE ! set rec_form_offcr_name = ?, rec_form_offcr_num = ?, mentor_id=? where userid = ?";
		$res = $db->query($q, array('borrowers_extn', $name, $number, $mentor, $borrowerid ));
		Logger("REPAYRPT: update tbl: comments refOfficial_name=". $name ."refOfficial_number". $number . "id = ".$borrowerid. "logged in user: ". $session->userid ."\n");
	}
	if(!empty($borrowerid)) {
		$p = "SELECT id, note from ! where borrowerid = ?";
		$repaydet = $db->getRow($p,array('repay_report_detail', $borrowerid));
		if(!empty($repaydet['id'])) {
			$new_note = $repaydet['note']." ".$note;
			if($isedit==1) {
				$q1 = "UPDATE ! set expected_repaydate = ?, note = ?, modified = $currentdate where id = ?";
				$res1 = $db->query($q1, array('repay_report_detail', $date, $note, $repaydet['id'] ));
				Logger("REPAYRPT: update tbl: repay_report_detail expected_repaydate=". $date ."note". $note . "id = ".$repaydet['id']. "logged in user: ". $session->userid ."\n");
			} else {
				$q1 = "UPDATE ! set expected_repaydate = ?, note = ?, modified = $currentdate where id = ?";
				$res1 = $db->query($q1, array('repay_report_detail', $date, $new_note, $repaydet['id'] ));
				Logger("REPAYRPT: update tbl: repay_report_detail expected_repaydate=". $date ."note". $new_note . "id = ".$repaydet['id']. "logged in user: ". $session->userid ."\n");
			}
		}else {
			$q1 = "INSERT INTO ! (borrowerid, expected_repaydate, note, created) VALUES  (?,?,?,?)";
			$res1=$db->query($q1, array('repay_report_detail', $borrowerid, $date, $note, $currentdate));
			Logger("REPAYRPT: insert tbl: repay_report_detail expected_repaydate=". $date ."note". $note . "bid = ".$borrowerid. "loanid =".$loanid ." logged in user: ". $session->userid ."\n");			
		}
	}
	else{
				Logger("REPAYRPT: blank data bid = ".$borrowerid. "loanid =".$loanid ."\n");			
	}
	if($res1 === 1) {
		if($isedit ==1) {
			echo 2;
		} else {
			echo 1;
		}
	}
	else
		echo 0;
?>