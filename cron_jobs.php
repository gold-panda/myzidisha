<?php
	include("library/session.php");
	global $database,$session;
	/*
		Function reInvite_frnds()
		For re inviting friends It is just a reminder for thise people who were invited by lender.
	*/
//	$session->reInvite_frnds();
	/*
		Function feedbackReminder()
		It will send reminder email for those lenders who have not submitted their feedback yet.
		This reminder will go max 5 times and once in a week.
	*/
	//$session->feedbackReminder();

	/*
		Function newLoanApplication()
		It will send email for those lenders who have set preference yes for getting notification if new loan is posted by any borrower.
		This loan application must be 2nd or onwards on zidisha site.

	*/
	$session->newLoanApplication();
	/*
		Function sendAccountExpiredMail()
		It will send email for those users who have not logged in over the duration set in constant eg. 12 months (ACCOUNT_EXPIRE_MAIL_DURATION) and send them email notification before the duration set in constant eg. 1 month (ACCOUNT_EXPIRE_DURATION).
	*/
//	$session->sendAccountExpiredMail();
	/*
		Function ProcessAutoBidding()
		it will process all bids by lenders preferences set in auto_lending option.

	*/
//	$session->ProcessAutoBidding();
	/*
		Function SendExpiringLoanMailToBorrower()
		it will send mail to  all borrowers which loans is going to expire.

	*/
//	$session->SendExpiringLoanMailToBorrower();

	//$session->checkDeactivatedAndDonate();
	//1-Jan-2013 Anupam, sends reminder email to lenders who have not yet forgive the loan nor denied.
//	$session->forgiveReminder();
	// deactivate lender account
//	$session->DeactivateAndDonate();
	// deactivate expire gift card
//	$session->DeactivateExpiredGiftCard();

	exit;
?>