<?php
function generateToken($str=null)
{
	$capcha='';
	if(!empty($str))
	{
		if(isset($_SESSION['CSRF'][$str])) {
			$capcha=$_SESSION['CSRF'][$str];
		}
		else {
			$capcha=md5(microtime());
			$_SESSION['CSRF'][$str]=$capcha;
		}
	}
	return $capcha;
}
function checkToken()
{
	
	if(isset($_POST["reg-borrower"])){
		return true;  // capcha support already there
		//return validateToken('reg-borrower');
	}
	else if(isset($_POST["reg-lender"])){
		return true;  // capcha support already there
		//return validateToken('reg-lender');
	}
	else if(isset($_POST['reg-partner'])){
		return true;  // capcha support already there
		//return validateToken('reg-partner');
	}
	else if(isset($_POST["userlogin"])){
		return true;
		//return validateToken('userlogin');
	}
	else if(isset($_POST['loanapplication'])){
		return validateToken('loanapplication');
	}
	else if(isset($_POST['editloanapplication'])){
		return validateToken('editloanapplication');
	}
	else if(isset($_POST['exrate'])){
		return validateToken('exrate');
	}
	else if(isset($_POST['amt_entered'])){
		return validateToken('amt_entered');
	}
	else if(isset($_POST['confirmApplication'])){
		return validateToken('confirmApplication');
	}
	else if(isset($_POST['lenderbid'])){
		return validateToken('lenderbid');
	}
	else if(isset($_POST['lenderbidUp'])){
		return validateToken('lenderbidUp');
	}
	else if(isset($_POST['minfundamount'])){
		return validateToken('minfundamount');
	}
	else if(isset($_POST['activatePartner'])){
		return validateToken('activatePartner');
	}
	else if(isset($_POST['deactivatePartner'])){
		return validateToken('deactivatePartner');
	}
	else if(isset($_POST['activateLender'])){
		return validateToken('activateLender');
	}
	else if(isset($_POST['deactivateLender'])){
		return validateToken('deactivateLender');
	}
	else if(isset($_POST['deactivateBorrower'])){
		return validateToken('deactivateBorrower');
	}
	else if(isset($_POST['deleteBorrower'])){
		return validateToken('deleteBorrower');
	}
	else if(isset($_POST['deletePartner'])){
		return validateToken('deletePartner');
	}
	else if(isset($_POST['deleteLender'])){
		return validateToken('deleteLender');
	}
	else if(isset($_POST['makeLoanExpire'])){
		return validateToken('makeLoanExpire');
	}
	else if(isset($_POST['makeLoanActive'])){
		return validateToken('makeLoanActive');
	}
	else if(isset($_POST['sendbulkmails'])){
		return validateToken('sendbulkmails');
	}
	else if(isset($_POST['addpaymenttolender'])){
		return validateToken('addpaymenttolender');
	}
	else if(isset($_POST['adddonationtolender'])){
		return validateToken('adddonationtolender');
	}
	else if(isset($_POST['changePassword'])){
		return validateToken('changePassword');
	}
	else if(isset($_POST['forgiveShare'])){
		return validateToken('forgiveShare');
	}
	else if(isset($_POST['assignedPartner'])){
		return true;
	}
	else if(isset($_POST['referral'])){
		return validateToken('referral');
	}
	else if(isset($_POST['add-repayment_instruction'])){
		return validateToken('add-repayment_instruction');
	}
	if(isset($_POST["editborrower"])){
		return validateToken('editborrower');
	}
	else if(isset($_POST["editlender"])){
		return validateToken('editlender');
	}
	else if(isset($_POST['editpartner'])){
		return validateToken('editpartner');
	}
	else if(isset($_POST['activateBorrower'])){
		return validateToken('activateBorrower');
	}
	else if(isset($_POST['acceptbids'])){
		return validateToken('acceptbids');
	}
	else if(isset($_POST['Payment'])){
		return validateToken('Payment');
	}
	else if(isset($_POST['repaymentfeedback'])){
		return validateToken('repaymentfeedback');
	}
	else if(isset($_POST['makeLoanDefault'])){
		return validateToken('makeLoanDefault');
	}
	else if(isset($_POST['makeLoanUndoDefault'])){
		return validateToken('makeLoanUndoDefault');
	}
	else if(isset($_POST['cancelloan'])){
		return validateToken('cancelloan');
	}
	else if(isset($_POST['forgetpassword'])){
		return validateToken('forgetpassword');
	}
	else if(isset($_POST['withdraw'])){
		return validateToken('withdraw');
	}
	else if(isset($_POST['paywithdraw'])){
		return validateToken('paywithdraw');
	}
	else if(isset($_POST['PaySimplewithdraw'])){
		return validateToken('PaySimplewithdraw');
	}
	else if(isset($_POST['paysimplewithdrawadmin'])){
		return validateToken('paysimplewithdrawadmin');
	}
	else if(isset($_POST['Otherwithdraw'])){
		return validateToken('Otherwithdraw');
	}
	else if(isset($_POST['payotherwithdrawadmin'])){
		return validateToken('payotherwithdrawadmin');
	}
	else if(isset($_POST['emailregister'])){
		return validateToken('emailregister');
	}
	else if(isset($_POST['emailsent'])){
		return validateToken('emailsent');
	}
	else if(isset($_POST['portfolioreport'])){
		return validateToken('portfolioreport');
	}
	else if(isset($_POST['portfolioreportnew'])){
		return validateToken('portfolioreportnew');
	}
	else if(isset($_POST['transactionhistory'])){
		return validateToken('transactionhistory');
	}
	else if(isset($_POST['tr_hidden'])){
		return validateToken('tr_hidden');
	}
	else if(isset($_POST['translatorhidden'])){
		return validateToken('translatorhidden');
	}
	else if(isset($_POST['translatorlang'])){
		return validateToken('translatorlang');
	}
	else if(isset($_POST['giftcardorder'])){
		return validateToken('giftcardorder');
	}
	else if(isset($_POST['redeemCard'])){
		return validateToken('redeemCard');
	}
	else if(isset($_POST['donate_card'])){
		return validateToken('donate_card');
	}
	else if(isset($_POST['promotLoan'])){
		return validateToken('promotLoan');
	}
	else if(isset($_POST['invite_frnds'])){
		return validateToken('invite_frnds');
	}
	else if(isset($_POST['get_contacts'])){
		return validateToken('get_contacts');
	}
	else if(isset($_POST['get_loans'])){
		return validateToken('get_loans');
	}
	else if(isset($_POST['repay_report'])){
		return validateToken('repay_report');
	}
	else if(isset($_POST['declinedBorrower'])){
		return validateToken('declinedBorrower');
	}
	else if(isset($_POST['reScheduleLoan'])){
		return validateToken('reScheduleLoan');
	}
	else if(isset($_POST['update-repayment_instruction'])){
		return validateToken('update-repayment_instruction');
	}
	else if(isset($_POST['del-repayment_instruction'])){
		return validateToken('del-repayment_instruction');
	}
	else if(isset($_POST['del-repayment_instruction'])){
		return validateToken('del-repayment_instruction');
	}
	else if(isset($_POST['sendShareEmail'])){
		return validateToken('sendShareEmail');
	}
	else if(isset($_POST['campaign'])){
		return validateToken('campaign');
	}
	else if(isset($_POST['deactivateAccount'])){
		return validateToken('deactivateAccount');
	}
	else if(isset($_POST['emailedTo'])){
		return true;
	}
	else if(isset($_POST['automaticLending'])){
		return validateToken('automaticLending');
	}

	else {
		return true;
	}
}
function validateToken($str=null)
{
	if(isset($_SESSION['CSRF'][$str]) && $_POST['user_guess']==$_SESSION['CSRF'][$str]) {
		//Anupam 13-12-2012  changes to retain csrf token per user per session
		//unset($_SESSION['CSRF'][$str]); 
		return true;
	}
	return false;
}