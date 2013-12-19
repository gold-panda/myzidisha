<?php
include_once("library/session.php");
global $database,$session;
$time = time();
	$q="SELECT loanid, borrowerid  from ! where adminDelete = ? and active in (2) order by loanid";
	$results=$db->getAll($q,array('loanapplic', 0));
	foreach ($results as $result) {
		$res = $database->isAllInstallmentOnTime($result['borrowerid'], $result['loanid']);
		if($res['missedInst'] == 0 && $res['TotalInstlment'] > 0) {
			$creditsetng = $database->getcreditLimitbyuser($result['borrowerid'],2);
			$sql = "update ! set `credit` =? WHERE credit=0 AND credit_type	=2 AND borrower_id=? AND loan_id = ?;";
			$res=$db->query($sql, array('credits_earned', $creditsetng['loanamt_limit'], $result['borrowerid'], $result['loanid']));

		}else {
			//$q="INSERT INTO ! (borrower_id, loan_id, credit_type, credit, created ) VALUES (?, ?, ?, ?, ?)";
			//$r=$db->query($q, array('credits_earned',$result['borrowerid'], $result['loanid'], 2, 0,$time));
		}
	}
echo"completed";
exit;
?>
