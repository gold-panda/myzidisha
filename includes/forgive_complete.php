<?php
include_once("library/session.php");
include_once("./editables/loanstatn.php");
$path=	getEditablePath('loanstatn.php');
include_once("editables/".$path);
?>
<div class='span12'>
<?php
if($session->userlevel==LENDER_LEVEL)
{
	$res=$database->getLastForgiveDetailOfLender($session->userid);
	if(!empty($res))
	{
		$bname=$database->getNameById($res['borrower_id']);
		$loanprurl = getLoanprofileUrl($bid,$loanid);
		$damount=number_format($res['damount'], 2, ".", ",");
		$loanid=$res['loan_id'];
		$bid=$res['borrower_id'];
		echo "<br/>";
		echo "<p>Thank you!  ".$bname."'s remaining repayments have been reduced by USD $damount.</p>";
		echo "<br/>";
		echo "<p><a href='$loanprurl'>Back to loan profile page</a></p>";
	}
}
?>
</div>