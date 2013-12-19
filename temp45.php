<?php
include("library/session.php");
global $database,$session;
$q="SELECT userid from ! where late_repayment_reminders=?";
$borrowers= $db->getAll($q, array('borrowers_extn', 2));
foreach($borrowers as $borrower){
	$q1="update ! set late_repayment_reminders=? where userid=?";
	$res= $db->query($q1, array('borrowers_extn', null, $borrower['userid']));
}
echo "complete";
?>