<?php
	include("library/session.php");
	global $database,$session;
	$q="SELECT loanid, borrowerid, active, expires, applydate, AcceptDate from ! where adminDelete = ? order by loanid";
	$result=$db->getAll($q,array('loanapplic', 0));
	foreach($result as $res) {
		$status=$res['active'];
		if(!empty($res['applydate'])) {
			$database->setLoanStage($res['loanid'], $res['borrowerid'], LOAN_OPEN, $res['applydate'], null, null, $res['applydate']);
		}
		if(!empty($res['AcceptDate'])) {
			$database->setLoanStage($res['loanid'], $res['borrowerid'], LOAN_FUNDED, $res['AcceptDate'], LOAN_OPEN, null, $res['AcceptDate']);
		}
		$q="SELECT TrDate from ! where loanid = ? AND txn_type=?";
		$TrDate=$db->getOne($q,array('transactions', $res['loanid'], DISBURSEMENT));
		if(!empty($TrDate)) {
			$database->setLoanStage($res['loanid'], $res['borrowerid'], LOAN_ACTIVE, $TrDate, LOAN_FUNDED, null, $TrDate);
		}
		if($res['active']==LOAN_REPAID) {
			$q="SELECT paiddate from ! where userid = ? AND loanid=? order by paiddate desc";
			$paiddate=$db->getOne($q,array('repaymentschedule', $res['borrowerid'], $res['loanid']));
			if(!empty($paiddate)) {
				$database->setLoanStage($res['loanid'], $res['borrowerid'], LOAN_REPAID, $paiddate, LOAN_ACTIVE, null, $paiddate);
			}
		}
		if($res['active']==LOAN_DEFAULTED) {
			$database->setLoanStage($res['loanid'], $res['borrowerid'], LOAN_DEFAULTED, $res['expires'], LOAN_ACTIVE, null, $res['expires']);
		}
		if($res['active']==LOAN_EXPIRED) {
			$database->setLoanStage($res['loanid'], $res['borrowerid'], LOAN_EXPIRED, $res['expires'], LOAN_OPEN, null, $res['expires']);
		}
	}
?>