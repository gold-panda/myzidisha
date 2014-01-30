<?php
include("utility.php");
include("database.php");
include("form.php");
include("validation.php");
include("csrf.php");

class Session
{
	var $username;     //Username given on sign-up
	var $userid;       //Random value generated on current login
	var $userlevel;    //The level to which the user pertains
	var $fullname;
	var $time;         //Time user was last active (page loaded)
	var $logged_in;    //True if user is logged in, false otherwise
	var $userinfo = array();  //The array holding all user info
	var $usersublevel;
	/**
	* Note: referrer should really only be considered the actual
	* page referrer in process.php, any other time it may be
	* inaccurate.
	*/
	var $errorcatch = array();

	/* Class constructor */
	function Session()
	{
		traceCalls(__METHOD__, __LINE__);
		$this->startSession();
		$this->time = time();
	}

	/**
	* startSession - Performs all the actions necessary to
	* initialize this session object. Tries to determine if the
	* the user has logged in already, and sets the variables
	* accordingly. Also takes advantage of this page load to
	* update the active visitors tables.
	*/

	function startSession()
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		if (!isset($_SESSION)) {
		  session_start();  //Tell PHP to start the session
		}
		$currentCookieParams = session_get_cookie_params();
		$sidvalue = session_id();
		setcookie(
			'PHPSESSID',//name
			$sidvalue,//value
			0,//expires at end of session
			$currentCookieParams['path'],//path
			$currentCookieParams['domain'],//domain
			COOKIE_SECURE, //secure
			true //httponly
		);
		/* Determine if user is logged in */
		$this->logged_in = $this->checkLogin();
		if(!$this->logged_in)
		{
			$this->username = $_SESSION['username'] = GUEST_NAME;
			$this->userlevel = GUEST_LEVEL;
			
		}

		//$this->sendMixpanelUser();

	}

	function redirect($url)
	{   ?>
		<script type="text/javascript">
			window.location="<?php echo $url ?>";
		</script>
<?php
	}

	/**
	* checkLogin - Checks if the user has already previously
	* logged in, and a session with the user has already been
	* established. Also checks to see if user has been remembered.
	* If so, the database is queried to make sure of the user's
	* authenticity. Returns true if the user has logged in.
	*/
	function checkLogin()
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		/* Check if user has been remembered */
		if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid']) && isset($_COOKIE['cookcsrf']))
		{
			$userinfo = $database->getUserInfo($_COOKIE['cookname']);
			if(!empty($userinfo) && $userinfo['salt']===$_COOKIE['cookcsrf'])
			{
				$this->username = $_SESSION['username'] = $_COOKIE['cookname'];
				$this->userid   = $_SESSION['userid']   = $_COOKIE['cookid'];
			}
		}
		/* Username and userid have been set and not guest */
		// var_dump($_SESSION);
		//exit();
		if(isset($_SESSION['username']) && isset($_SESSION['userid']) && $_SESSION['username'] != GUEST_NAME)
		{
			/* Confirm that username and userid are valid */
			if(!$database->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0)
			{
				/* Variables are incorrect, user not logged in */
				unset($_SESSION['username']);
				unset($_SESSION['userid']);
				return false;
			}

			/* User is logged in, set class variables */
			$this->userinfo  = $database->getUserInfo($_SESSION['username']);
			$this->username  = $this->userinfo['username'];
			$this->userid    = $this->userinfo['userid'];
			$this->fullname  = $this->userinfo['name'];
			$this->userlevel = $this->userinfo['userlevel'];
			$this->usersublevel = $_SESSION['sublevel'] = $this->userinfo['sublevel'];	
			
			return true;
		}
		/* User not logged in */
		else
		{
			return false;
		}
	}

	/**
	* login - The user has submitted his username and password
	* through the login form, this function checks the authenticity
	* of that information in the database and creates the session.
	* Effectively logging in the user if all goes well.
	*/
	function login($subuser, $subpass, $subremember,$fblogin=0)
	{
		global $database, $form, $lang;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$path=  getEditablePath('loginform.php');
		include_once("editables/".$path);
		$field="username";
		unset($_SESSION['fb_'.FB_APP_ID.'_code']);
		unset($_SESSION['fb_'.FB_APP_ID.'_access_token']);
		unset($_SESSION['fb_'.FB_APP_ID.'_user_id']);
		unset($_SESSION['FB_Detail']);
		unset($_SESSION['FB_Error']);
		unset($_SESSION['FB_Fail_Reason']);
		if(!$subuser || strlen($subuser=trim($subuser))==0 || $subuser==$lang['loginform']['username_login']){
			$form->setError($field, $lang['error']['empty_username']);
		}
		$field="password";
		if(!$subpass || strlen($subpass=trim($subpass))==0){
			$form->setError($field, $lang['error']['empty_password']);
		}
		$confirmed=$database->confirmUserPass($subuser, $subpass);
			if(!$confirmed){
				if(strstr($subuser, '@')){
					$result=$database->confirmUserEmailPass($subuser, $subpass);

					if($result){
							$subuser=$result;
							}else {
						$form->setError("username", $lang['error']['invalid_password']);
					}
				}
				else {
				$form->setError("username", $lang['error']['invalid_password']);
			}
		}

		if($form->num_errors > 0){
			/**** Integration with shift science on date 27-12-2013******/		
			$this->getLoginSiftData('invalid_login_event');	
			return 0;
		}
		$userinfo = $database->getUserInfo($subuser);
		$active = $database->confirmLenderActive($userinfo['userid']);
		if($userinfo['userlevel'] != BORROWER_LEVEL) {
			if(!$userinfo['emailVerified'])
			{
				$form->setError("username", $lang['error']['not_verified_email']);
			}
		}
		/*Dont allow inactive lenders to login*/
		if($userinfo['userlevel'] == LENDER_LEVEL && !$active)
		{
			$form->setError("username", $lang['error']['inactive_account']);
		}
		if($form->num_errors > 0)
			return 0;

		/* Username and password correct, register session variables */
		$this->userinfo  = $userinfo;
		$this->username  = $_SESSION['username'] = $this->userinfo['username'];
		$this->fullname  = $this->userinfo['name'];
		$this->userid    = $_SESSION['userid'] = $this->userinfo['userid'];
		$this->userlevel = $this->userinfo['userlevel'];
		$this->usersublevel = $_SESSION['sublevel'] = $this->userinfo['sublevel'];
		$language= $userinfo['lang'];
		if($language !='en')
			$_SESSION['language']=$language;
		/* Insert userid into database and update active users table */
		if($this->usersublevel !=READ_ONLY_LEVEL) {
			$database->setLoginTime($this->userid, $this->time);
		}
		if($subremember)
		{
			setcookie("cookname", $this->username, time()+COOKIE_EXPIRE, COOKIE_PATH, '', COOKIE_SECURE, true);
			setcookie("cookid",   $this->userid, time()+COOKIE_EXPIRE, COOKIE_PATH, '', COOKIE_SECURE, true);
			setcookie("cookcsrf",   $userinfo['salt'], time()+COOKIE_EXPIRE, COOKIE_PATH, '', COOKIE_SECURE, true);
		}
			
		/**** Integration with shift science on date 24-12-2013******/		
		$this->getLoginSiftData('login_event',$this->userid);
		
		return 1;

	}
	function loginAsUser($userId)
	{
		global $database, $form;
		$userinfo = $database->getUserById($userId);
		/* Username and password correct, register session variables */
		$this->userinfo  = $userinfo;
		$this->username  = $_SESSION['username'] = $this->userinfo['username'];
		$this->fullname  = $this->userinfo['name'];
		$this->userid    = $_SESSION['userid'] = $userId;
		$this->userlevel = $this->userinfo['userlevel'];
		$database->setLoginTime($this->userid, time());
		return 1;
	}
	/**
	* logout - Gets called when the user wants to be logged out of the
	* website. It deletes any cookies that were stored on the users
	* computer as a result of him wanting to be remembered, and also
	* unsets session variables and demotes his user level to guest.
	*/
	function logout()
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid']))
		{
			setcookie("cookname", "", time()-COOKIE_EXPIRE, COOKIE_PATH, '', COOKIE_SECURE, true);
			setcookie("cookid",   "", time()-COOKIE_EXPIRE, COOKIE_PATH, '', COOKIE_SECURE, true);
			setcookie("cookcsrf",   "", time()-COOKIE_EXPIRE, COOKIE_PATH, '', COOKIE_SECURE, true);
		}
		/* Unset PHP session variables */
		
		/**** Integration with shift science on date 24-12-2013******/		
		$this->getLoginSiftData('logout_event',$_SESSION['userid']);
		
		unset($_SESSION['username']);
		unset($_SESSION['userid']);
		unset($_SESSION['language']);
		unset($_SESSION['frnds_emails']);
		unset($_SESSION['frnds_msg']);
		unset($_SESSION['la']);
		unset($_SESSION['loanapp']);
		unset($_SESSION['sublevel']);
		unset($_SESSION['CodeByIp']);
		unset($_SESSION['Nodonationincart']);
		unset($_SESSION['pcomment']);
		unset($_SESSION['feedback']);
		/* Reflect fact that user has logged out */
		$this->logged_in = false;
		$this->username  = GUEST_NAME;
		$this->userlevel = GUEST_LEVEL;
		session_destroy();
	}

	
	function getNextLoanValue($loanValue, $loanPercent){
		$value= ($loanValue*$loanPercent)/100;
		return $value;
	}
	/**
	* register - Gets called when the user has just submitted the
	* registration form. Determines if there were any errors with
	* the entry fields, if so, it records the errors and returns
	* 1. If no errors were found, it registers the new user and
	* returns 0. Returns 2 if registration failed.
	*/


	/* -------------------Admin Section Start----------------------- */

function activateBorrower($borrowerid, $pcomment, $addmore, $cid, $ofclName = null, $OfclNumber = null)
	{
		global $database, $form, $validation;
		traceCalls(__METHOD__, __LINE__);
		$validation->validateActivateBorrower($pcomment, $ofclName, $OfclNumber);
		if($form->num_errors > 0){
			return 1;
		}
		$result=$database->activateBorrower($this->userid, $borrowerid, $pcomment, $addmore, $cid, $ofclName, $OfclNumber);
		if(!$result)
		{
			$form->setError("dberror", $lang['error']['error_website']);
			return 1;
		}
		else if($result)
		{
			$From=EMAIL_FROM_ADDR;
			$templet="editables/email/simplemail.html";
			$bdetail=$database->getEmailB($borrowerid);
			require("editables/mailtext.php");
			$language= $database->getPreferredLang($borrowerid);
			$path=  getEditablePath('mailtext.php',$language);
			require ("editables/".$path);
			$Subject=$lang['mailtext']['ActivateBorrower-subject'];
			$replyTo = SERVICE_EMAIL_ADDR;
			$To=$params['name'] = $bdetail['name'];
			$prurl = getUserProfileUrl($this->userid);
			$params['link'] = SITE_URL.$prurl ;
			$params['zidisha_link']= SITE_URL."index.php";
			$message = $this->formMessage($lang['mailtext']['ActivateBorrower-msg'], $params);
			if($addmore == 0)
				$reply=$this->mailSendingHtml($From, $To, $bdetail['email'], $Subject, '', $message, 0, $templet, 3);
			$this->sendContactConfirmation($borrowerid);
			return 0;
		}
	}
	function deactivateBorrower($bid,$set)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$return= $database->deactivateBorrower($bid, $set);
		return $return;
	}
	function activateLender($lid)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$return=$database->activateLender($lid);
		return $return;
	}
	function deactivateLender($lid)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$return=$database->deactivateLender($lid);
		return $return;
	}
	function activatePartner($pid)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$return=$database->activatePartner($pid);
		return $return;
	}
	function deactivatePartner($pid)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$return= $database->deactivatePartner($pid);
		$deat=$database->getEmailP($pid);
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$language= $database->getPreferredLang($pid);
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$Subject=$lang['mailtext']['ActivatePartner-subject'];
		$To=$params['name'] = $deat['name'];
		$params['status'] = 'Deactiveted';
		$prurl = getUserProfileUrl($pid);
		$params['link'] = SITE_URL.$prurl ;
		$message = $this->formMessage($lang['mailtext']['ActivatePartner-msg'], $params);
		$reply=$this->mailSendingHtml($From, $To, $deat['email'], $Subject, '', $message, 0, $templet, 3);
		return $return;
	}
	function setExchangeRate($amount,$currency)
	{
		global $form, $database;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$field="exrateamt";
		if(!$amount || strlen($amount)<1){
			$form->setError($field, $lang['error']['empty_rate']);
			return 0;
		}
		$result=$database->addRates($amount,$currency, time());
		if($result){
			return 1;
		}
		else
		{
			$form->setError("exrateamt", $lang['error']['error_website']);
			return 0;
		}
	}
	function saveRegistrationFee($currency,$amount)
	{
		global $database,$form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$field="currency";
		if(!$currency || $currency=='sel'){
			$form->setError($field, $lang['error']['empty_currency']);
		}
		$field= "amount";
		if(strlen($amount)<1){
			$form->setError($field, $lang['error']['empty_amount']);
		}
		else if(!eregi("^[0-9/.]", $amount)){
			$form->setError($field, $lang['error']['invalid_amount']);
		}
		if($form->num_errors >0){
			return 0;
		}
		$currency_details=explode("#",$currency);
		$currency_id=$currency_details[0];
		$currency_name=$currency_details[1];
		$currency_code=$currency_details[2];

		$saveinfo= $database->saveRegistrationFee($currency_id,$currency_name,$currency_code,$amount);
		if($saveinfo==1)
		{
			return $saveinfo;
		}
		$field="currency";
		if($saveinfo==2)
		{
			$form->setError($field, $lang['error']['already_currency']);
			return 0;
		}
	}
	function setEditAmount($amount,$currencyid)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$result=$database->setEditAmount($amount,$currencyid);
		if($result){
			return 1;
		}
		else{
			return 0;
		}
	}
	function setMinFund($amount)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("../editables/".$path);
		$field='mamount';
		if(!$amount || strlen($amount)<0){
			$form->setError($field, $lang['error']['invalid_minamt']);
		}
		else if(!is_numeric($amount)){
			$form->setError($field, $lang['error']['invalid_minamt']);
		}
		if($form->num_errors > 0){
			return 3;
		}
		$result=$database->setMinFund($amount);
		if($result){
			return 0;
		}
		else{
			return 1;
		}
	}
	function deleteBorrower($bid)
	{
		global $database, $session;
		traceCalls(__METHOD__, __LINE__);
		if($this->userlevel == ADMIN_LEVEL)
		{
			$res = $database->getBorrowerById($bid);
			$result=0;
			if(!empty($res))
			{
				$result = $database->deleteBorrower($bid);
			}
			return $result;
		}
	}
	function deletePartner($pid)
	{
		global $database, $session;
		traceCalls(__METHOD__, __LINE__);
		if($this->userlevel == ADMIN_LEVEL)
		{
			$res = $database->getPartnerDetails($pid);
			$result=0;
			if(!empty($res))
			{
				$result = $database->deletePartner($pid);
			}
			return $result;
		}
	}
	function deleteLender($lid)
	{
		global $database, $session;
		traceCalls(__METHOD__, __LINE__);
		if($this->userlevel == ADMIN_LEVEL)
		{
			$res = $database->getLenderDetails($lid);
			$result=0;
			if(!empty($res))
			{
				$result = $database->deleteLender($lid);
			}
			return $result;
		}
	}
	function makeLoanDefault($borrowerid,$loanid)
	{

		global $database;
		$result = $database->setDefultInLoan($borrowerid,$loanid);
		if($result)
		{
			$lendersArray = $database->getLendersAndAmount($loanid, true);
			$borrower_name=$database->getNameById($borrowerid);
			$loanDetail=$database->getLoanDetails($loanid);
			$percent_repaid= $this->getStatusBar($borrowerid,$loanid, 3);
			for($i =0; $i < count($lendersArray); $i++)
			{
				$this->sendDefaultedLoanMailToLender($lendersArray[$i]['lenderid'],$borrower_name,$percent_repaid,$loanDetail['reqdamt']);
			}
		}
		return $result;
	}
	function makeLoanUndoDefault($borrowerid,$loanid)
	{
		global $database;
		$result = $database->undoDefultInLoan($borrowerid,$loanid);
		return $result;
	}
	function makeLoanCancel($borrowerid,$loanid)
	{
		global $database;
		$result = $database->setCancelInLoan($borrowerid,$loanid);
		return $result;
	}
	function forgetpassword($submail, $subuser=0)
	{
		global $database, $form,$validation;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$validation->checkEmail($submail, "forgetemail");
		if($subuser ===GUEST_NAME)
		{
			$field="forgetusername";
			$form->setError($field, $lang['error']['select_username']);
			return 2;
		}
		if($form->num_errors > 0){
			return 0;
		}
		$result=$database->forgotPassword($submail, $subuser);
		if($result==0)
		{
			$field="forgeterror";
			$form->setError($field, $lang['error']['doesnot_email']);
			return 0;
		}
		else if($result==2)
		{
			return 2;
		}
		else if($result==3)
		{
			$field="forgeterror";
			$form->setError($field, $lang['error']['error_occure']);
			return 0;
		}
		else
		{   ///send new pass to user's email
			$From=EMAIL_FROM_ADDR;
			$templet="editables/email/simplemail.html";
			require("editables/mailtext.php");
			$language= $database->getPreferredLang($result['userid']);
			$path=  getEditablePath('mailtext.php',$language);
			require ("editables/".$path);
			$Subject=$lang['mailtext']['ForgotPassowrd-subject'];
			$params['password'] = $result['pass'];
			$message = $this->formMessage($lang['mailtext']['ForgotPassowrd-msg'], $params);
			$reply=$this->mailSendingHtml($From, '', $result['email'], $Subject, '', $message, 0, $templet, 3);
			return 1;
		}
	}

	function madePayment($borrowerid,$loanid,$date,$amount, $sendMail=true, $sub_type=0)
	{
		global $database, $form, $validation;
		$rtn = 0;
		$validation->checkAmount($amount, "paidamt");
		$validation->checkDated($date, "paiddate");
		$path=  getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$now = time();
		if($now < strtotime($date)) {
			$form->setError('paiddate', $lang['error']['invalid_date']);
		}

		if($form->num_errors > 0)
		{
			return 0;
		}
		if(is_numeric($date) && (int)$date == $date)
			$paidd = $date;
		else
			$paidd = strtotime($date);
		/*Divide the payment in the lenders and the web site fee
			1. Get the web site fee %
			2. Get who all lended and how much
			3. substract he website fee out of this installment
			4. remaining money should be divided in lenders according to their proportion and added
			5. If the loan gets completed with this payment set the loan status to complete
		*/
		$CurrencyRate=$database->getExRateById($paidd,$borrowerid);
		$totalOldFee = $database->getWebsiteFeeTotal($loanid);
		/*  get loanapplic data(row) of this loan id    */
		$lonedata=$database->getLoanfund($borrowerid, $loanid);
		$loanAmt=$lonedata['AmountGot']; /*The amount entered by admin on disbursement */
		$rate=$lonedata['finalrate'];   /*  Avearege interest rate of all lenders   */
		$feerate = $lonedata['WebFee'];
		$extra_period = $database->getLoanExtraPeriod($borrowerid, $loanid);
		$period=$lonedata['period'] + $extra_period;    /* Actual repayment perieds which do not includes grace periods */
		$grace=$lonedata['grace'];  /* grace periods before repayment starts */
		$weekly_inst=$lonedata['weekly_inst'];  /* intrest calculation based on if loan schedule weekly  on date 10-01-14*/
		if($weekly_inst==1){
			$feelender=((($period)*$loanAmt*($rate))/5200);
			$feeamount_org=((($period)*$loanAmt*($feerate))/5200);
		}else{
			$feelender=((($period)*$loanAmt*($rate))/1200); /* total interest amount of lenders for this loan */
			$feeamount_org=((($period)*$loanAmt*($feerate))/1200);/* zidisha fee amount for this loan */
		}
		$tamount_org=$loanAmt + $feelender + $feeamount_org; /* Total amount to be pay for by borrower */

		$totalPayment = $database->getTotalPayment($borrowerid, $loanid);
		$forgiveAmount= $database->getForgiveAmount($borrowerid,$loanid);
		$feeamount = round(((($feeamount_org - $totalOldFee) * $amount) / ($tamount_org - $totalPayment['paidtotal'] - $forgiveAmount)),4);
		$tamount=$loanAmt + $feelender;
		$pInCurrInstallment = ($loanAmt/$tamount) * ($amount-$feeamount);
		$lendersArray = $database->getLendersAndAmount($loanid, true);
		$totalDollarFee = convertToDollar($feeamount, $CurrencyRate);
		if($rtn==0)
		{	
		
			$rest1= $database->setTransactionAmount(ADMIN_ID,$totalDollarFee,'Fee',$loanid, $CurrencyRate, FEE, $paidd);
			if($rest1==0)
				$rtn=1;
		}
		$total =0;
		for($i =0; $i < count($lendersArray); $i++)
		{
			$total += $lendersArray[$i]['amount'];
		}
		for($i =0; $i < count($lendersArray); $i++)
		{
			$lenderPrincipal = ($lendersArray[$i]['amount']/$total)*$pInCurrInstallment;
			if($weekly_inst==1){	/* Added by Mohit 10-01-14 */
				$lenderInterest = ($lenderPrincipal * $lendersArray[$i]['intr'] * $period)/5200;
			}else{
				$lenderInterest = ($lenderPrincipal * $lendersArray[$i]['intr'] * $period)/1200;
			}
			$amountToLender = $lenderPrincipal +  $lenderInterest;
			$dollarAmountToLender = convertToDollar($amountToLender, $CurrencyRate);
			if($rtn==0)
			{
				$rest2= $database->setTransaction($lendersArray[$i]['lenderid'],$dollarAmountToLender,'Loan repayment received',$loanid, $CurrencyRate,LOAN_BACK_LENDER,0,$paidd, $sub_type);
				if($rest2==0)
					$rtn=1;
			}
		}
		/*amount coming into acount is +ve  going out of account is -ve*/
		$amtEntered = $amount;
		if($rtn==0)
		{	
			$rest3= $database->setTransactionAmount($borrowerid, $amtEntered,'Loan installment',$loanid, $CurrencyRate,LOAN_BACK, $paidd);
			if($rest3==0)
					$rtn=1;
		}
		if($rtn==0)
		{
			$rest4= $this->setMadePayment($borrowerid, $loanid, $amount, $paidd);
			if($rest4==0) {
				$rtn=1;
			}else {
				$database->setOntimeRepayCredit($rest4, $borrowerid, $amount);  //$rest4 contains the ids we inserted data in repayment table we will check every entry if inserted ontime.
			}

		}
		$bname=$database->getEmailB($borrowerid);
		$b_name=$bname['name'];

		$loandetail=$database->getLoanDetailsNew($borrowerid, $loanid);
		$totalAmt = $loandetail['totalAmt'];
		$totalPaidAmt = $loandetail['totalPaidAmt'];
		
		/**** Integration with shift science on date 26-12-2013******/
		$this->getBPaymentSiftData('repayment', $borrowerid, $amount);
		
		if($rtn==0)
		{
			if ( (abs($totalAmt) - abs($totalPaidAmt) <= 1))
			{
				$rest5=$database->loanpaidback($borrowerid,$loanid);
				if($rest5==0)
					$rtn=1;
				//email for these guys to come and provide review on the loan
				for($i =0; $i<count($lendersArray); $i++)
				{
					//mail for  giving feed back to each and every lender
					$r=$database->getEmail($lendersArray[$i]['lenderid']);
					$From=EMAIL_FROM_ADDR;
					$templet="editables/email/hero.html";
					require ("editables/mailtext.php");
					$loanprurl = getLoanprofileUrl($borrowerid, $loanid);
					$params['link'] = SITE_URL.$loanprurl.'#e1' ;
					$params['bname'] = $b_name;
					$params['image_src'] = $database->getProfileImage($borrowerid);
					$Subject = $this->formMessage($lang['mailtext']['RepayFeedback-subject'], $params);
					$header = $this->formMessage($lang['mailtext']['RepayFeedback-msg1'], $params);
					$message = $this->formMessage($lang['mailtext']['RepayFeedback-msg2'], $params);
					$reply=$this->mailSendingHtml($From, '', $r['email'], $Subject, $header, $message,0,$templet,3);
				
				}
			}
		}
		if($rtn==0)
		{

			$From=EMAIL_FROM_ADDR;
			$templet="editables/email/simplemail.html";
			require ("editables/mailtext.php");

//send payment receipt to borrower via email and SMS
			$Subject=$lang['mailtext']['payment_receipt_subject'];
			$currency=$database->getUserCurrency($borrowerid);
			$country= $database->getCountryCodeById($borrowerid);
			$telnumber= $database->getPrevMobile($borrowerid);
			$to_number = $this->FormatNumber($telnumber, $country);
			$bdetail=$database->getEmailB($borrowerid);
			$params['bname']=$bdetail['name'];
			$params['currency']= $currency;
			$params['bpaidamt']= $amount;
			$b_email=$bdetail['email'];
			$To=$bdetail['name'];
			$message = $this->formMessage($lang['mailtext']['payment_receipt'], $params);
			$sms_message = $this->formMessage($lang['mailtext']['payment_receipt_sms'], $params);
			$this->mailSendingHtml($From, $To, $b_email, $Subject, '', $message, 0, $templet, 3);
			$this->SendSMS($sms_message, $to_number);


//if borrower who made payment is eligible to invite, send invite eligibility notification at same time as payment receipt, via email only
			$eligible_invite = $this->isEligibleToInvite($borrowerid);

			if ($eligible_invite == 1){

				$Subject=$lang['mailtext']['eligible_invite_subject'];
				$message = $this->formMessage($lang['mailtext']['eligible_invite'], $params);
				$sms_message = $this->formMessage($lang['mailtext']['eligible_invite_sms'], $params);
				$this->mailSendingHtml($From, $To, $b_email, $Subject, '', $message, 0, $templet, 3);
				$this->SendSMS($sms_message, $to_number);
				//$this->getOnTimePaymentSiftData($borrowerid); //if borrower pays on time and has good repayment and invite record as measured by eligibility to invite, send good user label to Sift Science
		
			}

			if($sendMail) //payment receipt notification for lenders who elect to receive these emails
			{
				for($i =0; $i < count($lendersArray); $i++)
				{
					$r=$database->getEmailAndPreference($lendersArray[$i]['lenderid']);
					if($r['email_loan_repayment'])
					{
						$availableAmt = $this->amountToUseForBid($lendersArray[$i]['lenderid']);
						$availAmt = number_format(truncate_num(round($availableAmt, 4), 2) , 2, '.', ',');
						$lenderPrincipal = ($lendersArray[$i]['amount']/$total)*$pInCurrInstallment;
						if($weekly_inst==1){
							$lenderInterest = ($lenderPrincipal * $lendersArray[$i]['intr'] * $period)/5200;
						}else{
							$lenderInterest = ($lenderPrincipal * $lendersArray[$i]['intr'] * $period)/1200;
						}
						$amountToLender = $lenderPrincipal +  $lenderInterest;
						$dollarAmountToLender = convertToDollar($amountToLender, $CurrencyRate);
						$templet="editables/email/hero.html";
						$params['avail_amount'] = $availAmt;
						$params['amount'] = number_format(truncate_num(round($dollarAmountToLender, 4), 2), 2, ".", ",");
						$params['lend_link'] = WEBSITE_ADDRESS.'?p=2';
						$loanprurl = getLoanprofileUrl($borrowerid, $loanid);
						$params['link'] = SITE_URL.$loanprurl;
						$Subject = $this->formMessage($lang['mailtext']['RecivedPayment-subject'], $params);
						$message = $this->formMessage($lang['mailtext']['RecivedPayment-msg'], $params);
						$this->mailSendingHtml($From, '', $r['email'], $Subject, '', $message, 0, $templet, 3);

					}
				}
			}
		}
		if($rtn==0)
			return 1;
		else
		{
			$validation->setCustomError("failure", "error_website");
			return -1;
		}
	}
	function setMadePayment($borrowerid, $loanid, $amount, $date)
	{
		global $database, $form;
		$rtn=0;
		$res1= $database->getSchedulefromDB($borrowerid, $loanid);
		$amountbal = $amount;
		$jj=0;
		for($k=0; $k<count($res1); $k++)
		{
			if($res1[$k]['amount'] > 0)
			{
				$idIndex=$k;
				$jj=$k;
				$installment = $res1[$k]['amount'];
				break;
			}
		}
		$j=-1;
		for($i=0; $i<count($res1); $i++)
		{
			if(isset($res1[$i]['paidamt'])  && $res1[$i]['paidamt'] !=0 && $res1[$i]['paidamt'] !=NULL)
			{
				$idIndex=$i;
				$j=$i;
			}
		}
		$maxid = $res1[count($res1) - 1]['id'];
		$diff1 = 0;
		if($j > -1)
		{
			$diff1 = $res1[$j]['amount'] - $res1[$j]['paidamt'];
		}
		else
		{
			/* this case means there is no paid amount in table */
			$diff1=0;
			$idIndex--; //The id needs to be decremented as it is incremented below
			$j=$jj-1;
		}
		if($diff1 > 0)
		{
			if($diff1 >= $amountbal)
			{
				if($rtn==0)
				{
					$result=$database->madePayment($res1[$idIndex]['id'],$amountbal, $date);
					if($result==0)
						$rtn=1;
					else {
						$repayids[] = $res1[$idIndex]['id'];
					}
				}
			}
			else
			{
				for($bal=$amountbal; $bal >0;)
				{
					if($res1[$idIndex]['id']==$maxid)
					{
						if($rtn==0)
						{
							$result=$database->madePayment($res1[$idIndex]['id'],$bal, $date);
							if($result==0)
								$rtn=1;
							else {
								$repayids[] = $res1[$idIndex]['id'];
							}

							$bal=0;
						}
					}
					else
					{
						if($diff1 > 0)
						{
							if($rtn==0)
							{
								 $result=$database->madePayment($res1[$idIndex]['id'],$diff1, $date);
								 if($result==0)
									$rtn=1;
								 else {
									$repayids[] = $res1[$idIndex]['id'];
								}

								 $bal = $bal - $diff1;
								 $idIndex++;
								 $j++;
								 $diff1=0;
							}
						}
						else
						{
							$installment=$res1[$j]['amount'];
							if($bal <= $installment)
							{
								if($rtn==0)
								{
									$result=$database->madePayment($res1[$idIndex]['id'],$bal, $date);
									if($result==0)
										$rtn=1;
									else {
										$repayids[] = $res1[$idIndex]['id'];
									}

									$bal=0;
								}
							}
							else
							{
								if($rtn==0)
								{
									$result=$database->madePayment($res1[$idIndex]['id'],$installment, $date);
									if($result==0)
										$rtn=1;
									else {
										$repayids[] = $res1[$idIndex]['id'];
									}
									$bal = $bal - $installment;
									$idIndex++;
									$j++;
								}
							}
						}
					}
				}
			}
		}
		else
		{
			if($res1[$idIndex]['id'] < $maxid)
			{
				$idIndex++;
				$j++;
			}
			for($bal=$amountbal; $bal >0;)
			{
				if($res1[$idIndex]['id']==$maxid)
				{
					if($rtn==0)
					{
						$result=$database->madePayment($res1[$idIndex]['id'],$bal, $date);
						if($result==0)
							$rtn=1;
						else {
							$repayids[] = $res1[$idIndex]['id'];
						}
						
						$bal=0;
					}
				}
				else
				{
					$installment=$res1[$j]['amount'];
					if($bal <= $installment)
					{
						if($rtn==0)
						{
							$result=$database->madePayment($res1[$idIndex]['id'],$bal, $date);
							if($result==0)
								$rtn=1;
							else {
								$repayids[] = $res1[$idIndex]['id'];
							}
							$bal=0;
						}
					}
					else
					{
						if($rtn==0)
						{
							$result=$database->madePayment($res1[$idIndex]['id'],$installment, $date);
							if($result==0)
								$rtn=1;
							else {
								$repayids[] = $res1[$idIndex]['id'];
							}
							$bal = $bal - $installment;
							$idIndex++;
							$j++;
						}
					}
				}
			}
		}
		if($rtn==0)
		{
			$result=$database->madePayment_Actual($amount, $date, $loanid, $borrowerid,$res1[$idIndex]['id']);
			if($result==0)
				$rtn=1;
		}
		if($rtn==0)
			return $repayids;
		else
			return 0;
	}
	// Oct-2012 Anupam ,last argument $date_disbursed added since now admin will select disbursed date from the loan transaction page 
	function updateActiveLoan($pid, $loanid,$a_amount,$reg_fee, $date_disbursed=0)
	{
		global $database, $lang; //Anupam 25-Jan-2013 added for test cases
		traceCalls(__METHOD__, __LINE__);
		$state=LOAN_ACTIVE;
		if($loanid==0)
			return 2;//activeLoanID in borrowers is not set
		// Anupam 1-9-2013 check if already disbursed for erroneously posted form more than one time
		$disbdate = $database->getLoanDisburseDate($loanid);
		if(!empty($disbdate)) {
			return 0;
		}
		/*
			Verify if the lenders account has enough money if
			not do not go ahead. Notify admin about the Lenders who do not have
			sufficient fund. Ask lenders to come and pay. or reopen the bid.
			get the money from Lenders account ($)
			pay it to borrowers account local currency
		*/
		$lendersArray = $database->getLendersAndAmount($loanid);
		$CurrencyRate = $database->getCurrentRate($pid);
		if(empty($CurrencyRate))
			return 0;
		$proceed =1;
		/*	Now we are adding transactions for bids in transaction table so no need to check available balance and add duplication transaction
			So do not uncomment below commented code
		*/
		/*for($i =0; $i < count($lendersArray); $i++)
		{
			//Pranjal modified below since this is where we are making bid active so we need to count the total
			//available amount as some amount is stuck in this bid too
			$amt = $database->getTransaction($lendersArray[$i]['lenderid'],0); //$this->amountToUseForBid($lendersArray[$i]['lenderid']);
			$dollaramount = $lendersArray[$i]['amount'];
			if($amt < $dollaramount){
				$proceed =0; //do not check the amount for now
				break;
			}
		}*/
		if($proceed)
		{
			/*	Now we are adding transactions for bids in transaction table so no need to check available balance and add duplication transaction So do not uncomment below commented code
			*/
			/*for($i =0; $i < count($lendersArray); $i++)
			{
				$dollaramount = $lendersArray[$i]['amount'] * -1; //amount going out of account is -ve
				$res1=$database->setTransaction($lendersArray[$i]['lenderid'],$dollaramount,'Loan fund disbursement',$loanid, $CurrencyRate, LOAN_SENT_LENDER);
				if($res1==0)
					return 0; // in case if failed any lender transaction
			}*/
			//Pranjal add the amount as added by admin in native currency
			$res2= $database->updateGotAmount($loanid, $a_amount);
			if($res2==0)
				return 0;
			$loanamount = -1 *$a_amount;
			$res3= $database->setTransactionAmount($pid,$loanamount,'Got amount from loan',$loanid, $CurrencyRate, DISBURSEMENT, $date_disbursed);
			if($res3==0)
				return 0;			
			if(!empty($reg_fee))
			{
				$res7=$database->setTransaction(ADMIN_ID,$reg_fee,'Registration Fee',$loanid, $CurrencyRate, REGISTRATION_FEE);
				if($res7==0)
					return 0; // in case if failed registration fee transaction
				$reg_fee1 = -1 * $reg_fee;
				$res8=$database->setTransaction($pid,$reg_fee1,'Registration Fee',$loanid, $CurrencyRate, REGISTRATION_FEE);
				if($res8==0)
					return 0; // in case if failed registration fee transaction
			}
			$installment_day= $database->getInstallmentDate($loanid);
			$difference=0;
			if(!empty($installment_day) && $installment_day!=0){
				$difference = $this->getDateDifferecneFromToday($installment_day, $date_disbursed);
			}
			$res4= $database->updateLoanStatus($loanid,$state, $difference);
			if($res4==0)
				return 0;
			$res5= $database->updateActiveLoan($pid,$state);
			if($res5==0)
				return 0;
			$res6= $this->setSchedule($pid, $loanid, $date_disbursed);
			if($res6==0)
				return 0;
			else
			{
				require ("editables/mailtext.php");
				$From=EMAIL_FROM_ADDR;
				$templet="editables/email/hero.html";
				$params['bname'] = $database->getNameById($pid);
				$params['ddate'] = date('M d, Y',  time());
				$params['link'] = WEBSITE_ADDRESS.'?p=14&l='.$loanid ;
				$Subject= $this->formMessage($lang['mailtext']['ActiveBid-subject'], $params);
				$params['image_src'] = $database->getProfileImage($borrowerid);
				$Subject = $this->formMessage($lang['mailtext']['ActiveBid-subject'], $params);
				$header = $this->formMessage($lang['mailtext']['ActiveBid-msg1'], $params);
				$message = $this->formMessage($lang['mailtext']['ActiveBid-msg2'], $params);
					
				for($i =0; $i < count($lendersArray); $i++)
				{
					$r=$database->getEmail($lendersArray[$i]['lenderid']);
					$this->mailSendingHtml($From, '', $r['email'], $Subject, $header, $message, 0, $templet, 3);
				}
				$r = $database->getEmailB($pid);
				$Subject=$lang['mailtext']['loan_disburse_sub'];
				$To=$params['bname'] = $r['name'];
				$currency='';
				$currency_amt=$database->getReg_CurrencyAmount($pid);
				foreach($currency_amt as $row)
				{
					$currency=$row['currency'];
					if(empty($reg_fee))
						$reg_fee=$row['Amount'];
				}
				$params['reg_fee_amt'] = number_format($reg_fee, 0, ".", ",") .' ' .$currency;
				$params['disb_amt'] = number_format($a_amount, 0, ".", ",") .' ' .$currency;
				$params['net_amt'] = number_format(($a_amount - $reg_fee), 0, ".", ",") .' ' .$currency;
				$country = $database->getBorrowerCountryByLoanid($loanid);
				$repayment_instruction=$database->getRepayment_InstructionsByCountryCode($country);
				$repay_ins='';
				if(!empty($repayment_instruction))
					$repay_ins = nl2br($repayment_instruction['description']);
				$params['repay_ins'] = $repay_ins;
				$params['zidisha_link'] = SITE_URL;
				$message = $this->formMessage($lang['mailtext']['loan_disburse_body'], $params);
				$this->mailSendingHtml($From, $To, $r['email'], $Subject, '', $message, 0, $templet, 3);
				/**** Integration with shift science on date 24-12-2013 by Mohit ******/		
				$this->getBPaymentSiftData('disbursement',$pid,$a_amount);
				return 1;//success
			}
		}
		else
		{
			return 0; //insufficient funds
		}
	}
	function setSchedule($uid, $loanid, $date_disbursed)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$lonedata=$database->getLoanfund($uid, $loanid);
		$extraPeriod=$database->getLoanExtraPeriod($uid, $loanid);
		$loanid=$lonedata['loanid'];
		$amount=$lonedata['AmountGot'];
		$loneAppDate=$lonedata['applydate'];
		$rate=$lonedata['finalrate'];
		$webrate=$lonedata['WebFee'];
		$period=$lonedata['period'];
		$grace=$lonedata['grace'];
		$weekly_inst=$lonedata['weekly_inst'];

//case of loans with weekly repayment schedules, added by Julia 21-12-2013
	if ($weekly_inst==1){
			
		$inst_day = $database->getinstallmentweekday($uid, $loanid);
		if(empty($inst_day)) {
			$inst_day = date('w',time());
		}

		if(empty($date_disbursed)) {
			$date_disbursed = time();
		}
		$loneAcceptDate = $date_disbursed;
		$currday = date('d',$date_disbursed);
		//specifies day of the week loan was disbursed
		$currweekday = date('w',$date_disbursed);
		$currmonth = date('m',$date_disbursed);
		$curryear = date('Y',$date_disbursed);
		//sets the number of days we need to add to the disbursement date to make repayments fall on the borrower's desired weekday
		
		if($currweekday <= $inst_day) {
			$extradays = $inst_day - $currweekday;
		}else {
			$extradays = 7 - ($currweekday - $inst_day);
		}
		$extraseconds = $extradays * 86400;
		
		//for the purposes of generating the repayment schedule, defines the base date to which we will add the recurring periods such that repayments fall on the borrower's desired weekday
		$loneAcceptDate	 = $date_disbursed+$extraseconds;
		$period_org=$period;
		//calculates total time loan is held in weeks
		$period=$period+($extradays/7);
		$interest=(($period)/52)*(($amount*($rate/100))+($amount*($webrate/100)));
		$totalamt=$amount+$interest;
		$pinterest=$interest/($period);
		$pamount=$amount/($period-$grace);
		$pamount=round($pamount, 4);

		//correct decimal places
		$pamount1=floor($pamount);
		$dfamt=round($pamount-$pamount1, 2)*($period-$grace);


		$pinterest1=floor($pinterest);
		$dfint=round($pinterest-$pinterest1, 2)*($period);

		$count=0;
		$tint=0;
		$tprin=0;
		$ttotl=0;
		$schedule = array();
		$period_org += 1;
	
		for($i=0; $i<$period_org; $i++)
		{
			if($count < $grace)
			{
				$pint1=number_format($pinterest1, 2, ".", ",");
				$schedule[] = array('date'=>strtotime('+ '.$count.' week ' , $loneAcceptDate), 'total' => 0);
				$tint=$tint+$pinterest1;
			}
			else if($count >= $grace)
			{
				if($count==$period_org){
					$pamount1=$pamount1+$dfamt;
					$pinterest1=$pinterest1+$dfint;
				}
				$schedule[] = array('date'=> strtotime('+ '.$count.' week ' , $loneAcceptDate), 'total' => $totalamt/($period_org - $grace));
				$tint=$tint+$pinterest1;
				$tprin=$tprin+$pamount1;
				$ttotl=$ttotl+$pamount1+$pinterest1;
			}
			$count++;
		}


	}else{
	
		$inst_day = $database->getinstallmentday($uid, $loanid);
		if(empty($inst_day)) {
			$inst_day = date('d',time());
		}
		if(empty($date_disbursed)) {
			$date_disbursed = time();
		}
		$loneAcceptDate = $date_disbursed;
		$currday = date('d',$date_disbursed);
		$currmonth = date('m',$date_disbursed);
		$curryear = date('Y',$date_disbursed);
		if($currday <= $inst_day) {
			$loneAcceptDate	 = mktime(0,0,0,$currmonth,$inst_day,$curryear);
		}else {
			$currmonth = $currmonth+1;
			$loneAcceptDate	 = mktime(0,0,0,$currmonth,$inst_day,$curryear);
		}
		$period_org=$period;
		$period=$period+$extraPeriod;
		$interest=(($period)/12)*(($amount*($rate/100))+($amount*($webrate/100)));
		$totalamt=$amount+$interest;
		$pinterest=$interest/($period);
		$pamount=$amount/($period-$grace);
		$pamount=round($pamount, 4);

		//correct decimal places
		$pamount1=floor($pamount);
		$dfamt=round($pamount-$pamount1, 2)*($period-$grace);


		$pinterest1=floor($pinterest);
		$dfint=round($pinterest-$pinterest1, 2)*($period);

		$count=0;
		$tint=0;
		$tprin=0;
		$ttotl=0;
		$schedule = array();
		$period_org += 1;
		$accptday = date('d',$loneAcceptDate);
	if($accptday!=31) {
		for($i=0; $i<$period_org; $i++)
		{
			if($count < $grace)
			{
				$pint1=number_format($pinterest1, 2, ".", ",");
				$schedule[] = array('date'=>strtotime('+ '.$count.' month ' , $loneAcceptDate), 'total' => 0);
				$tint=$tint+$pinterest1;
			}
			else if($count >= $grace)
			{
				if($count==$period_org){
					$pamount1=$pamount1+$dfamt;
					$pinterest1=$pinterest1+$dfint;
				}
				$schedule[] = array('date'=> strtotime('+ '.$count.' month ' , $loneAcceptDate), 'total' => $totalamt/($period_org - $grace));
				$tint=$tint+$pinterest1;
				$tprin=$tprin+$pamount1;
				$ttotl=$ttotl+$pamount1+$pinterest1;
			}
			$count++;
		}
	}else if($accptday==31) {
		$countonce=0;
		for($i=0; $i<$period_org; $i++) {
				$month = date('m', strtotime('+ '.$count.' months ' , $loneAcceptDate));
				$year  = date('Y', strtotime('+ '.$count.' months ' , $loneAcceptDate));
				$day   = date('d', strtotime('+ '.$count.' months ' , $loneAcceptDate));
				if($day==31&& $countonce==0) {
					$fakedate = strtotime('- 30 days ' , $loneAcceptDate);
					$lastdayofmonth = date('t',$fakedate); 
					if($count < $grace)
					{
						$pint1=number_format($pinterest1, 2, ".", ",");
						$schedule[] = array('date'=>strtotime("$month/$lastdayofmonth/$year"), 'total' => 0);
						$tint=$tint+$pinterest1;
					}
					else if($count >= $grace)
					{
						if($count==$period_org){
							$pamount1=$pamount1+$dfamt;
							$pinterest1=$pinterest1+$dfint;
						}
						$schedule[] = array('date'=> strtotime("$month/$lastdayofmonth/$year"), 'total' => $totalamt/($period_org - $grace));
						$tint=$tint+$pinterest1;
						$tprin=$tprin+$pamount1;
						$ttotl=$ttotl+$pamount1+$pinterest1;
					}
					$countonce++;

				} else {
					$fakedate = strtotime('+1 months ' , $fakedate);
					$lastdayofmonth = date('t',$fakedate);
					$fakemonth = date('m',$fakedate);
					$fakeyear = date('Y',$fakedate);
					//$dated[] = strtotime("$fakemonth/$lastdayofmonth/$fakeyear");
					
					if($count < $grace)
					{
						$pint1=number_format($pinterest1, 2, ".", ",");
						$schedule[] = array('date'=>strtotime("$fakemonth/$lastdayofmonth/$fakeyear"), 'total' => 0);
						$tint=$tint+$pinterest1;
					}
					else if($count >= $grace)
					{
						if($count==$period_org){
							$pamount1=$pamount1+$dfamt;
							$pinterest1=$pinterest1+$dfint;
						}
						$schedule[] = array('date'=> strtotime("$fakemonth/$lastdayofmonth/$fakeyear"), 'total' => $totalamt/($period_org - $grace));
						$tint=$tint+$pinterest1;
						$tprin=$tprin+$pamount1;
						$ttotl=$ttotl+$pamount1+$pinterest1;
					}		

				}
			$count++;
			}
		}
	}
	if(!empty($schedule))
			return $database->setSchedule($uid, $loanid, $schedule);
		else
			return 0;
	}



	function pfreport($date3, $date4)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$date=fixdate;
		$flag=0;
		$field="fromdate";
		$flag=0;
		if(!$date3 || strlen($date3=trim($date3))==0)
		{
			$form->setError($field, $lang['error']['empty_fromdate']);
			$flag=1;
		}
		$field="todate";
		if(!$date4 || strlen($date4=trim($date4))==0)
		{
			$form->setError($field, $lang['error']['empty_todate']);
			$flag=1;
		}
		$result1=datecompare($date,$date3);
		$result2=datecompare($date3,$date4);
		if($result1==false && $flag!=1)
		{
			$field="wrongdate1";
			$form->setError($field, $lang['error']['invalid_fromdate']);
		}
		else if($result2==false && $flag!=1)
		{
			$field="wrongdate2";
			$form->setError($field, $lang['error']['greater_todate']." ".$date3);
		}
		if($form->num_errors > 0)
		{
			return 0;
		}
		else
			return 1;
	}
	function trhistory($date3, $date4)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$date=fixdate;
		if(empty($date3)){
			$form->setError("fromdate", $lang['error']['empty_fromdate']);
		}
		if(empty($date4)){
			$form->setError("todate", $lang['error']['empty_todate']);
		}
		if($form->num_errors > 0)
			return 0;
		$result1=datecompare($date,$date3);
		$result2=datecompare($date3,$date4);
		if(!$result1){
			$form->setError("fromdate", $lang['error']['invalid_fromdate']);
		}
		else if(!$result2){
			$form->setError("todate", $lang['error']['lower_fromdate']);
		}
		if($form->num_errors > 0)
			return 0;
		else
		{
			$_SESSION['date1']=$date3;
			$_SESSION['date2']=$date4;
			return 1;
		}
	}

	function getTranslate($bizdesc, $about, $summary, $loanuse, $cmnt, $id, $up_id, $loanid,$lcid=0)
	{
		global $database, $form;  //The database and form object
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if($up_id==1)
		{
			if(empty($this->userid)){
				$form->setError("loginerr", $lang['error']['loginerr']);
				return 0;
			}
			$res = $database->upadateTranslate($bizdesc, $about, $summary, $loanuse, 0, $id, $up_id, $loanid);
		}
		if($up_id==2)
		{  
		
			$res = $database->upadateTranslate(0, 0, 0, 0, $cmnt, $id, $up_id, 0);
			if(trim($cmnt) !='')
			{	
				if($reschedule_id=$database->getRescheduleIdFromComment($id))
				{
					$rescheduleResult= $database->getRescheduleData($reschedule_id);
					$lendersArray = $database->getLendersAndAmount($rescheduleResult['loan_id'], true);
					for($i =0; $i < count($lendersArray); $i++)
					{	
						$this->sendRescheduleCommentMailToLender($lendersArray[$i]['lenderid'],$rescheduleResult['borrower_id'],$rescheduleResult['period'],$cmnt, $rescheduleResult['date']);
					}
				}
				else
				{
					$this->sendTranslateCommentMails($id, $cmnt);
				}
			}
		}
		if($up_id==3)
		{
			$res = $database->upadateTranslate(0, 0, 0, 0, $cmnt, 0, $up_id, 0,$lcid);
		}
		if($res==1 || $res==3 || $res==5)
				return $res;
		else
		{
			$form->setError("updateerr", $lang['error']['failed_updation']);
			return $res;
		}
	}
	function donate_card($id,$card_code,$amt)
	{
		global $database, $session;
		traceCalls(__METHOD__, __LINE__);
		if($this->userlevel == ADMIN_LEVEL)
		{
			$res = $database->CheckGiftCardClaimed($card_code);
			if($res == 0)
			{
				$res1 = $database->CheckGiftCardExpired($card_code);
				if($res1 == 1)
				{
					$res2 = $database->donate_card($id,$card_code);
					if($res2 ==1)
					{
						$res3 = $database->setTransaction($this->userid, $amt,'Gift Card Conversion to Donation',0, 0, GIFT_DONATE);
						return 1;
					}
				}
			}
		}
		return 0;
	}
	function addpaymenttolender($userid,$amount,$donation,$autoLending=false)
	{
		global $database,$form;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($userid)){
			$form->setError("userid", $lang['error']['select_username']);
		}
		if(empty($amount) && empty($donation)){
			$form->setError("amount", "please enter any one amount");
		}
		else
		{
			if($amount <0){
				$form->setError("amount", $lang['error']['invalid_amount']);
			}
			if($donation <0){
				$form->setError("donation", $lang['error']['invalid_amount']);
			}
		}
		if($autoLending && $userid) {
			$activated=$database->IsAutoLendingActivated($userid);
			if(!$activated) {
				$form->setError("auto_lending", $lang['error']['autoLendNot_Acitve']);
			}
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			if(empty($amount))
				$amount=0;
			if(empty($donation))
				$donation=0;
			$database->startDbTxn();
			$res=$database->addpaymenttolender($userid,$amount,$donation);
			if($res==0){
				$database->rollbackTxn();
				return 0;
			}
			else
			{   
				if(!$autoLending) {
					$activated=$database->IsAutoLendingActivated($userid);
					if($activated) {
						$res=$database->UpdateLenderCreditForAutoLend($userid , $amount);
						if($res==0) {
							$database->rollbackTxn();
							return 0;
						}
					}
				}

				$database->commitTxn();
				if($amount >0){
					$this->sendFundUploadMail($userid,$amount);
				}
				if($donation >0){
					$this->sendDonationMail($userid,$donation);
				}
				return 1;
			}
		}
	}

	function adddonationtolender($name, $email, $donationamt)
	{
		global $database,$form, $validation;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($name)){
			$form->setError("name", $lang['error']['empty_name']);
		}
		$validation->checkEmail($email, "email");
		if($donationamt < 0 || empty($donationamt)){
			$form->setError("donationamt", $lang['error']['invalid_amount']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$database->startDbTxn();
			$res=$database->adddonationtolender($name, $email, $donationamt);
			if($res==0){
				$database->rollbackTxn();
				return 0;
			}
			else
			{
				$database->commitTxn();
				$this->sendDonationMail(0,$donationamt,$email, $name);
				return 1;
			}
		}
	}
	function changePassword($userid,$password,$cpassword)
	{
		global $database,$form, $validation;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($userid)){
			$form->setError("userid", $lang['error']['select_username']);
		}
		$validation->checkPassword($password, $cpassword, "password");
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$res=$database->changePassword($userid,$password);
			if($res==1){
				$_SESSION['pchange']=1;
			}
			return $res;
		}
	}
	function referral($country,$refCommission, $refPercent)
	{
		global $database,$form;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($country)){
			$form->setError("country", $lang['error']['select_country']);
		}
		if(empty($refCommission)){
			$form->setError("refCommission", $lang['error']['empty_ref_comm']);
		}
		if(empty($refPercent) && $refPercent !='0'){
			$form->setError("refPercent", $lang['error']['empty_per_repay']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$res=$database->referral($country,$refCommission, $refPercent);
			return $res;
		}
	}
	function assignedPartner($partnerid,$borrowerid)
	{
		global $database,$form;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(!$partnerid)
		{
			$field="partnerid".$borrowerid;
			$form->setError($field, $lang['error']['select_partner']);
			return false;
		}
		$res1=$database->assignedPartner($partnerid,$borrowerid);
		if($res1==1)
		{
			$_SESSION['Assigned']=1;
			return true;
		}
		else
			return false;
	}
	function declinedBorrower($borrowerid,$dreason)
	{
		global $database,$form;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		/*if(empty($dreason)){
			$form->setError("dreason", $lang['error']['empty_ineligibility']);
			return false;
		}*/
		$res1=$database->declinedBorrower($borrowerid,$dreason, $this->userid);
		if($res1==1){
			$From=EMAIL_FROM_ADDR;
			$templet="editables/email/simplemail.html";
			$bdetail=$database->getEmailB($borrowerid);
			require ("editables/mailtext.php");
			$language= $database->getPreferredLang($borrowerid);
			$path=  getEditablePath('mailtext.php',$language);
			require ("editables/".$path);
			$Subject=$lang['mailtext']['DeclineBorrower-subject'];
			$To=$params['name'] = $bdetail['name'];
			$message = $this->formMessage($lang['mailtext']['DeclineBorrower-msg'], $params);
			$reply=$this->mailSendingHtml($From, $To, $bdetail['email'], $Subject, '', $message, 0, $templet, 3);

			$_SESSION['Declined']=1;
			return true;
		}
		else
			return false;
	}

	function addRePaymentInstruction($country_code, $description)
	{
		global $database, $form,$validation;
		traceCalls(__METHOD__, __LINE__);
		$validation->validateRePaymentInstruction($country_code, $description);

		if($form->num_errors > 0){
			return 0;
		}
		$result=$database->addRePaymentInstruction($country_code, $description);
		if($result)
		{
			return true;
		}
		return false;
	}

	function updateRePaymentInstruction($country_code, $description, $id)
	{
		global $database, $form,$validation;
		traceCalls(__METHOD__, __LINE__);
		$validation->validateRePaymentInstruction($country_code, $description);

		if($form->num_errors > 0){
			return 0;
		}
		$result=$database->updateRePaymentInstruction($country_code, $description, $id);
		if($result)
		{
			return true;
		}
		return false;
	}

	function deleteRePaymentInstruction($id)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$result=$database->deleteRePaymentInstruction($id);
		if($result)
		{
			return true;
		}
		return false;
	}
	function setCampaign($code,$value,$max_use,$message,$active)
	{
		global $form, $database;
		if(!$code|| strlen($code)<1)
		$code=getCardCode16(time());
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		$field_name='name';
		$field_value='value';
		$field_max_use='max_use';
		$field_message='message';
		include_once("editables/".$path);
		if(!$value|| strlen($value)<1){
			$form->setError($field_value, $lang['error']['empty_capmaign_value']);
		}
		if(!$max_use|| strlen($max_use)<1){
			$form->setError($field_max_use, $lang['error']['empty_max_use']);
		}
		if(!$message|| strlen($message)<1){
			$form->setError($field_message, $lang['error']['empty_capmaign_msg']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		$result=$database->addCampaign($code,$value,$max_use,$message,$active,time());
		if($result){
			return 1;
		}
		else
		{
		return 0;
		}
	}
	function updateCampaign($code,$value, $max_use,$message, $active,$id)
	{
		global $database, $form,$validation;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		if(!$code|| strlen($code)<1)
		$code=getCardCode16(time());
		$field_code='code';
		$field_value='value';
		$field_max_use='max_use';
		$field_message='message';
		include_once("editables/".$path);
		if(!$code|| strlen($code)<1){
			$form->setError($field_code, $lang['error']['empty_name']);
		}
		if(!$value|| strlen($value)<1){
			$form->setError($field_value, $lang['error']['empty_capmaign_value']);
		}
		if(!$max_use|| strlen($max_use)<1){
			$form->setError($field_max_use, $lang['error']['empty_max_use']);
		}
		if(!$message|| strlen($message)<1){
			$form->setError($field_message, $lang['error']['empty_capmaign_msg']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		$result=$database->updateCampaign($code, $value,$max_use, $message, $active,$id);
		if($result)
		{
			$_SESSION['update_campaign']=1;
			return true;
		}
		return false;
	}
	function deletecampaign($id)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$result=$database->deletecampaign($id);
		if($result)
		{
			$_SESSION['del_campaign']=1;
			return true;
		}
		return false;
	}
	function ConverToDonation($lid,$amt)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$return=$database->ConverToDonation($lid,$amt,false);
		if($return){
			$loanstoforgiven = $database->getlendersLoantofogive($lid);
			foreach($loanstoforgiven as $loan) {
				$borrowerId = $database->getBorrowerId($loan['loanid']);
				$this->forgiveShare($loan['loanid'], $borrowerId, $lid);
			}
			$_SESSION['donated']=1;
			return true;
		}
		return false;
	}
	function checkDeactivatedAndDonate()
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$lenders=$database->checkDeactivatedAndDonate();
		if(!empty($lenders)){
			foreach($lenders as $lender){
				$avaiamount=$this->amountToUseForBid($lender['userid']);
				if($avaiamount>0){
				$return=$database->ConverToDonation($lender['userid'],$avaiamount,true);
				}
			}
			return true;
	}
		return false;
	}
	function emailedTo($borrowerid,$emailaddress,$ccaddress,$replyTo,$emailsubject,$emailmessage, $sendername)
	{
		global $form,$database,$validation;
		traceCalls(__METHOD__, __LINE__);
		$validation->validateEmailedTo($borrowerid,$emailaddress,$ccaddress,$replyTo,$emailsubject,$emailmessage,$sendername);
		if($form->num_errors>0)
		{
			return 0;
		}else{
			$res=$database->SetBorrowerReports($borrowerid,$emailaddress,$ccaddress,$replyTo,$emailsubject,$emailmessage, $sendername);
			if(!empty($res)){
					$From=EMAIL_FROM_ADDR;
					if(isset($replyTo) && !empty($replyTo))
						$From=$replyTo;
					$To=$emailaddress;
					$email=$emailaddress;
					$Subject=$emailsubject;
					$message=nl2br($emailmessage);
					$message=$message."<br/>".$sendername."<br/>Zidisha";
					$templet="editables/email/simplemail.html";
					if(isset($ccaddress)){
						$CCemails=explode(',',$ccaddress);
						foreach($CCemails as $CCemail)
						{
							$this->mailSendingHtml($From, $CCemail, $CCemail, $Subject, '', $message, 0, $templet, 3);
						}
					}
				$reply=$this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);
				if($reply)
					Logger_Array("Email to borrower  sent  by admin",'email, To', $email, $To);
					return true;
			}
			return false;
		}
	}
	function outstandingReport($outstanDate)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$date=fixdate;
		$flag=0;
		$field="oustandDate";
		if(!$outstanDate || strlen($outstanDate=trim($outstanDate))==0)
		{
			$form->setError($field, $lang['error']['empty_outstandate']);
			$flag=1;
		}
		if($form->num_errors > 0)
		{
			return 0;
		}
		else
			return 1;
	}
	function getDateDifferecneFromToday($installment_day, $disb_date=0) {
		$disbursed_date = date('m/d/Y');
		if(!empty($disb_date)) {
			$disbursed_date = date('m/d/Y', $disb_date);
		}
		$diff=0;
		list($month, $day, $year) = explode('/', $disbursed_date);
		if($day<=$installment_day){
			$diff=$installment_day-$day;
		}
		else{
				$next= date("Y-m-$installment_day", strtotime("+1 months"));
				$diff=date('d',strtotime($next)-strtotime($disbursed_date));
		}
		return $diff;
	}
	function StopRefferalCommision($country)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$stopped = $database->StopRefferalCommision($country);
		if($stopped){
			$_SESSION['CommisionStopped']=1;
			return 1;
		}
		return 0;
	}
	function saveCreditSetting($country, $loanamtlimit,$charlimit, $commentlimit, $type)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$res = $database->saveCreditSetting($country, $loanamtlimit, $charlimit, $commentlimit, $type);
		if($res){
			return 1;
		}
		return 0;
	}
	function isBorrowerAlreadyAccess($brwrid)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$res= $database->isBorrowerAlreadyAccess($brwrid);
		if($res){
			$_SESSION['grant_already_access']= true;
			return true;
		}
	}
	function grantAccessCo($brwrid)
	{	
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$res= $database->grantAccessCo($brwrid);
		if($res){
			$_SESSION['grant_access']= true;
			$_SESSION['granted_accessto'] = $brwrid;
			return true;
		}
	}
	function grantRemoveCo($borrowerid)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$res= $database->grantRemoveCo($borrowerid);
		if($res){
			$_SESSION['grant_remove']= true;
			$_SESSION['removed_accessfrm'] = $borrowerid;
			return true;
		}
	}
	function review_borrower($borrowerid, $is_photo_clear, $is_desc_clear, $is_addr_locatable, $is_number_provided, $is_photo_clear_other,$is_desc_clear_other,$is_addr_locatable_other,$is_number_provided_other,$is_pending_mediation,$is_pending_mediation_other) {
		global $database, $form;
		if($is_photo_clear==-1) {
			$is_photo_clear = $is_photo_clear_other;
		}
		if($is_desc_clear==-1) {
			$is_desc_clear = $is_desc_clear_other;
		}
		if($is_addr_locatable==-1) {
			$is_addr_locatable = $is_addr_locatable_other;
		}
		if( $is_number_provided==-1) {
			 $is_number_provided = $is_number_provided_other;
		}
		if($is_pending_mediation==-1) {
			$is_pending_mediation = $is_pending_mediation_other;
		}

		$res= $database->review_borrower($borrowerid, $is_photo_clear, $is_desc_clear, $is_addr_locatable, $is_number_provided, $is_pending_mediation);
		if($res){
			$_SESSION['review_complete']= true;
			return 1;
		}else {
			$_SESSION['review_not_complete']= true;
		}
		return 0;
	}
	function remove_payment($payment_id) {
		global $database;
		$database->startDbTxn();
		$res = $database->remove_payment($payment_id);
		if($res!=1) {
			$database->rollbackTxn();
			return 0;
		}else {
			$database->commitTxn();
			return 1;
		}
	}
function verify_borrower($identity_verify, $identity_verify_other, $participate_verification, $participate_verification_other, $app_know_zidisha, $app_know_zidisha_other, $how_contact, $how_contact_other, $recomnd_addr_locatable, $recomnd_addr_locatable_other, $commLead_know_applicant, $commLead_know_applicant_other , $commLead_recomnd_sign, $commLead_recomnd_sign_other, $commLead_mediate, $commLead_mediate_other, $eligible, $additional_comments, $borrowerid, $submit_type, $complete_later, $verifier_name)
	{ 
		global $database, $form, $validation;
			require("editables/brwrlist-i.php");
			$path=  getEditablePath('brwrlist-i.php');
			require ("editables/".$path);
			require("editables/error.php");
			$path=  getEditablePath('error.php');
			require ("editables/".$path);
			if($submit_type==$lang['brwrlist-i']['comlete_later']){
				$complete_later= '1';
				$res= $database->add_verify_borrower($this->userid, $complete_later, $identity_verify, $identity_verify_other, $participate_verification, $participate_verification_other, $app_know_zidisha, $app_know_zidisha_other, $how_contact, $how_contact_other, $recomnd_addr_locatable, $recomnd_addr_locatable_other, $commLead_know_applicant, $commLead_know_applicant_other , $commLead_recomnd_sign, $commLead_recomnd_sign_other, $commLead_mediate, $commLead_mediate_other, $eligible, $additional_comments, $borrowerid, $verifier_name);
				return 2;
			}else{
				$complete_later= '0';
				if(($identity_verify==0 && $identity_verify!='') || ($participate_verification==0 && $participate_verification!='') || ($app_know_zidisha==0 && $app_know_zidisha!='')|| ($how_contact ==0 && $how_contact!='')|| ($commLead_know_applicant ==0 && $commLead_know_applicant!='')|| ($eligible==0 && $eligible!='') || ($commLead_mediate ==0 && $commLead_mediate!='') ||( $recomnd_addr_locatable==0 && $recomnd_addr_locatable!='') || ($commLead_recomnd_sign==0 && $commLead_recomnd_sign!='')){
						if(empty($verifier_name)){
							$form->setError("verifier_name_intrvw", 'please enter name');
						}
						if($form->num_errors > 0){ 
							return 0;
						}else{
							$this->declinedBorrower($borrowerid, $additional_comments);
						}
						$_SESSION['Declined']=1;
						$res_decline= $database->add_verify_borrower($this->userid,$complete_later, $identity_verify, $identity_verify_other, $participate_verification, $participate_verification_other, $app_know_zidisha, $app_know_zidisha_other, $how_contact, $how_contact_other, $recomnd_addr_locatable, $recomnd_addr_locatable_other, $commLead_know_applicant, $commLead_know_applicant_other , $commLead_recomnd_sign, $commLead_recomnd_sign_other, $commLead_mediate, $commLead_mediate_other, $eligible, $additional_comments, $borrowerid, $verifier_name);
						if($res_decline)
						return 0;
				}
				$result= $validation->verify_borrower($identity_verify, $identity_verify_other, $participate_verification, $participate_verification_other, $app_know_zidisha, $app_know_zidisha_other, $how_contact, $how_contact_other, $recomnd_addr_locatable, $recomnd_addr_locatable_other, $commLead_know_applicant, $commLead_know_applicant_other , $commLead_recomnd_sign, $commLead_recomnd_sign_other, $commLead_mediate, $commLead_mediate_other, $eligible, $verifier_name);
				if($form->num_errors > 0){ 
					return 0;
				}else {
						$res= $database->add_verify_borrower($this->userid,$complete_later, $identity_verify, $identity_verify_other, $participate_verification, $participate_verification_other, $app_know_zidisha, $app_know_zidisha_other, $how_contact, $how_contact_other, $recomnd_addr_locatable, $recomnd_addr_locatable_other, $commLead_know_applicant, $commLead_know_applicant_other , $commLead_recomnd_sign, $commLead_recomnd_sign_other, $commLead_mediate, $commLead_mediate_other, $eligible, $additional_comments, $borrowerid, $verifier_name); 
						if($res){
							$From=EMAIL_FROM_ADDR;
							$templet="editables/email/simplemail.html";
							$bdetail=$database->getEmailB($borrowerid);
							require("editables/mailtext.php");
							$language= $database->getPreferredLang($borrowerid);
							$path=  getEditablePath('mailtext.php',$language);
							require ("editables/".$path);
							$Subject=$lang['mailtext']['ActivateBorrower-subject'];
							$replyTo = SERVICE_EMAIL_ADDR;
							$To=$params['name'] = $bdetail['name'];
							$prurl = getUserProfileUrl($this->userid);
							$params['link'] = SITE_URL.$prurl ;
							$message = $this->formMessage($lang['mailtext']['ActivateBorrower-msg'], $params);
							if($submit_type !=$lang['brwrlist-i']['comlete_later']){ 
								$reply=$this->mailSendingHtml($From, $To, $bdetail['email'], $Subject, '', $message, 0, $templet, 3);
								return $res;
							}
						}else{
							$path=  getEditablePath('error.php');
							include_once("editables/".$path);
							$form->setError("dberror", $lang['error']['error_website']);
							return 0;
						}
					}
				}
		}
		function co_org_note($id, $note){
			global $database;
			$res= $database->co_org_note($id, $note);
			return $res;
		}

	function verify_borrower_ByAdmin($is_eligible_ByAdmin, $eligible_no_reason, $borrowerid, $submit_bverification_ByPartner, $verifier_name){
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if($is_eligible_ByAdmin==''){
			$form->setError("is_eligible_ByAdmin", $lang['error']['verify_borrower']);
		}else if($is_eligible_ByAdmin=='0' && (empty($eligible_no_reason) || strlen(trim($eligible_no_reason))<1)){
			$form->setError("eligible_no_reason", $lang['error']['decline_reason']);
		}
		if(empty($verifier_name)){
			$form->setError("verifier_name", 'please enter name');
		}
		if($form->num_errors > 0){ 
			return 0;
		}
		if($is_eligible_ByAdmin=='0'){
			$this->declinedBorrower($borrowerid,$eligible_no_reason);
			$this->getDeclineSiftData($borrowerid);
			$_SESSION['Declined']=true;
			return 0;
		}else if($is_eligible_ByAdmin = '1'){
			$res= $database->verify_borrower_ByAdmin($this->userid, $is_eligible_ByAdmin, $borrowerid, $verifier_name);
			if($res){
				$From=EMAIL_FROM_ADDR;
				$templet="editables/email/simplemail.html";
				$bdetail=$database->getEmailB($borrowerid);
				require("editables/mailtext.php");
				$language= $database->getPreferredLang($borrowerid);
				$path=  getEditablePath('mailtext.php',$language);
				require ("editables/".$path);
				$Subject=$lang['mailtext']['ActivateBorrower-subject'];
				$replyTo = SERVICE_EMAIL_ADDR;
				$To=$params['name'] = $bdetail['name'];
				$prurl = getUserProfileUrl($this->userid);
				$params['link'] = SITE_URL.$prurl ;
				$message = $this->formMessage($lang['mailtext']['ActivateBorrower-msg'], $params);
				$reply=$this->mailSendingHtml($From, $To, $bdetail['email'], $Subject, '', $message, 0, $templet, 3);
			}
			return $res;
		}
	}

	function facebook_info($date3, $date4){		
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($date3)){
			$form->setError("fromdate", $lang['error']['empty_fromdate']);
		}
		if(empty($date4)){
			$form->setError("todate", $lang['error']['empty_todate']);
		}
		if($form->num_errors > 0)
			return 0;
		$result2=datecompare($date3,$date4);
		if(!$result2){
			$form->setError("todate", $lang['error']['lower_fromdate']);
		}
		if($form->num_errors > 0)
			return 0;
		else
		{
			$_SESSION['date1']=$date3;
			$_SESSION['date2']=$date4;
			return 1;
		}
	}

//added by Julia 22-11-2013

	function activation_rate($date3, $date4){		
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($date3)){
			$form->setError("fromdate", $lang['error']['empty_fromdate']);
		}
		if(empty($date4)){
			$form->setError("todate", $lang['error']['empty_todate']);
		}
		if($form->num_errors > 0)
			return 0;
		$result2=datecompare($date3,$date4);
		if(!$result2){
			$form->setError("todate", $lang['error']['lower_fromdate']);
		}
		if($form->num_errors > 0)
			return 0;
		else
		{
			$_SESSION['date1']=$date3;
			$_SESSION['date2']=$date4;
			return 1;
		}
	}



	function repayment_rate($date3, $date4){		
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($date3)){
			$form->setError("fromdate", $lang['error']['empty_fromdate']);
		}
		if(empty($date4)){
			$form->setError("todate", $lang['error']['empty_todate']);
		}
		if($form->num_errors > 0)
			return 0;
		$result2=datecompare($date3,$date4);
		if(!$result2){
			$form->setError("todate", $lang['error']['lower_fromdate']);
		}
		if($form->num_errors > 0)
			return 0;
		else
		{
			$_SESSION['date1']=$date3;
			$_SESSION['date2']=$date4;
			return 1;
		}
	}

	function loans_funded($date3, $date4){		
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($date3)){
			$form->setError("fromdate", $lang['error']['empty_fromdate']);
		}
		if(empty($date4)){
			$form->setError("todate", $lang['error']['empty_todate']);
		}
		if($form->num_errors > 0)
			return 0;
		$result2=datecompare($date3,$date4);
		if(!$result2){
			$form->setError("todate", $lang['error']['lower_fromdate']);
		}
		if($form->num_errors > 0)
			return 0;
		else
		{
			$_SESSION['date1']=$date3;
			$_SESSION['date2']=$date4;
			return 1;
		}
	}



/* -------------------Admin Section End----------------------- */


	/* -------------------Borrower Section Start----------------------- */

function register_b($uname, $namea, $nameb, $pass1, $pass2, $post, $city, $country, $email, $mobile,$reffered_by,$income, $about, $bizdesc, $photo, $share_update,$user_guess, &$id, $bnationid, $language, $referrer, $community_name_no, $documents, $submit_type,  $repaidPast, $debtFree, $onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1,$bneighcont2,$bneighcont3,$home_no,$rec_form_offcr_name, $rec_form_offcr_num, $refer_member, $volunteer_mentor, $cntct_type, $fb_data, $endorser_name, $endorser_email, $tnc)
	{	
		global $database, $form, $mailer, $validation;
			
		$completeLater = 0;
		traceCalls(__METHOD__, __LINE__);
		require("editables/register.php");
		$path=  getEditablePath('register.php');
		require ("editables/".$path);
		$fb_data= unserialize(stripslashes(urldecode($fb_data)));
		
		/* Comment by mohit on date 02-01-14
 		$web_acc=0;
		$fb_fail_reason=isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : ''.' : Borrower Registration Session';
		Logger_Array("FB LOG - on session 1",'fb_data', serialize($fb_data).$uname); */
		if($cntct_type!='1' || (isset($_SESSION['FB_Error']) && $_SESSION['FB_Error']!=false)){
			Logger_Array("cnt",$cntct_type, serialize($_SESSION['FB_Error']));
			$fb_data= '';
			unset($_SESSION['FB_Error']);
		}
		
		if($submit_type == trim($lang['register']['RegisterComplete'])) { 
//			Logger_Array("FB LOG - on session 3",'fb_data', serialize($fb_data).$uname);
			$validation->validateBorrowerReg($uname, $namea, $nameb, $pass1, $pass2, $post, $city, $country, $email, $mobile,$reffered_by, $income, $about, $bizdesc, $photo,  $user_guess, $bnationid, $referrer, $community_name_no, $documents,  $repaidPast, $debtFree, $share_update, $onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1,$bneighcont2,$bneighcont3,$home_no, $rec_form_offcr_name, $rec_form_offcr_num, $cntct_type, $fb_data, $endorser_name, $endorser_email, $tnc);
			}else {
			$completeLater = 1;
			$validation->checkUsername($uname,'busername');
			$validation->checkPassword($pass1,$pass2, "bpass1");
			$validation->checkEmailForBorrower($email, "bemail");
			//$validation->checkNationId($bnationid, "bnationid", $country);
			$validation->checkCountry($country, "bcountry");
		}
		if($form->num_errors>0){
			return 1;
		}
		else
		{	
			
			$retVal = $database->addBorrower($uname,$namea,$nameb, $pass1, $post, $city,$country,$email, $mobile,$reffered_by, $income, $about, $bizdesc,$bnationid, $language, $community_name_no, $documents,  $repaidPast, $debtFree,$share_update, $completeLater, $onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1,$bneighcont2,$bneighcont3,$home_no, $rec_form_offcr_name, $rec_form_offcr_num, $refer_member, $volunteer_mentor, $fb_data, $endorser_name, $endorser_email, $tnc);
			
			$id = $database->getUserId($uname);
			
			if(!empty($id)){	

				$this->getNewBAccountSiftData('create_new_account',$id,$uname,$namea,$nameb,$post,$city,$country,$bnationid,$email,$mobile,$bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1,$bneighcont2,$bneighcont3,$rec_form_offcr_name, $rec_form_offcr_num, $aboutMe,$aboutBusiness,$hearaAoutZidisha);
	
			}
			
			if(!empty($id) && $submit_type == $lang['register']['Registerlater'])
			{
					$From=EMAIL_FROM_ADDR;
					require("editables/mailtext.php");
					$templet="editables/email/simplemail.html";
					$path=  getEditablePath('mailtext.php',$language);
					require ("editables/".$path);

					$Subject=$lang['mailtext']['BorrowerReg-subject'];
					$To=$params['name'] = $namea." ".$nameb ;
					$replyTo = SERVICE_EMAIL_ADDR;
					$message = $this->formMessage($lang['mailtext']['BorrowerReg-msg'], $params);
					$this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);


					$Subject=$lang['mailtext']['email_verification_sub'];
					$activate_key = $database->getActivationKey($id);
					$link = SITE_URL."index.php?p=51&ident=$id&activate=$activate_key";
					$params['verify_link'] = $link;
					$message = $this->formMessage($lang['mailtext']['email_verification_body'], $params);
					$reply = $this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);
			}
			
			if(!empty($id) && $submit_type == $lang['register']['RegisterComplete'])
			{
				
					$From=EMAIL_FROM_ADDR;
					require("editables/mailtext.php");
					$templet="editables/email/simplemail.html";
					$path=  getEditablePath('mailtext.php',$language);
					require ("editables/".$path);

					$Subject=$lang['mailtext']['BorrowerReg-subject'];
					$To=$params['name'] = $namea." ".$nameb ;
					$replyTo = SERVICE_EMAIL_ADDR;
					$params['username'] = $uname;
					$params['password'] = $pass1;
					$message = $this->formMessage($lang['mailtext']['BorrowerReg-msg'], $params);
					$this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);

					$Subject=$lang['mailtext']['email_verification_sub'];
					$activate_key = $database->getActivationKey($id);
					$link = SITE_URL."index.php?p=51&ident=$id&activate=$activate_key";
					$params['verify_link'] = $link;
					$message = $this->formMessage($lang['mailtext']['email_verification_body'], $params);
					$reply = $this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);
					$this->sendContactConfirmation($id);	// send SMS ommunity leader, family and neighborcontacts
				if($reply)
					Logger_Array("Email Verification mail sent to borrower ",'email, To', $email, $To);
				$_SESSION['bEmailVerifiedPending']=true;
				
				if(!empty($referrer))
				{
					$userinfo = $database->getUserInfo($referrer);
					$referDetail=$database->getReferrals($userinfo['country'], false);
					if(!empty($referDetail))
					{
						$comm=$database->addCommission($id, $userinfo['userid'], $referDetail['id']);
						if($comm)
						{
							$params=array();
							$params['applicant_name'] = $namea." ".$nameb;
							$To=$params['bname'] = $userinfo['name'];
							$params['amount'] = $referDetail['ref_commission'];
							$params['referral_link'] = SITE_URL."index.php?p=50";
							$Subject = $this->formMessage($lang['mailtext']['borrower_referral_sub'], $params);
							if($referDetail['percent_repay']==0)
							{
								$message = $this->formMessage($lang['mailtext']['borrower_referral_body_1'], $params);
							}
							else
							{
								$params['repaid_percent'] = $referDetail['percent_repay'];
								$message = $this->formMessage($lang['mailtext']['borrower_referral_body_2'], $params);
							}
							$this->mailSendingHtml($From, $To, $userinfo['email'], $Subject, '', $message, 0, $templet, 3);
						}
					}
				}
				if($cntct_type=='1'){ 
					for($i=0; $i<10; $i++){ 
						if(!empty($endorser_name[$i]) && !empty($endorser_email[$i])){ 
							$e_details= $database->getEndorserForEmail($id, $endorser_name[$i], $endorser_email[$i]);
							$validation_code= $e_details['validation_code'];
							$From=$email;
							$reg_link = SITE_URL."index.php?p=93&vd=$validation_code";
							$params['reg_link']= $reg_link;
							require("editables/mailtext.php");
							$templet="editables/email/simplemail.html";
							$path=  getEditablePath('mailtext.php',$language);
							require ("editables/".$path);
							$e_email= $endorser_email[$i];
							$Subject=$namea." ".$nameb." ".$lang['mailtext']['borrowerEndorser-subject'];
							$To=$params['name'] = $endorser_name[$i] ;
							$params['bname']= $namea." ".$nameb;
							$replyTo = SERVICE_EMAIL_ADDR;
							$message = $this->formMessage($lang['mailtext']['BorrowerEndorser-msg'], $params);
							$reply= $this->mailSendingHtml($From, $To, $e_email, $Subject, '', $message, 0, $templet, 3); 
							$database->updateEndorserAfterEmail($id, $To, $e_email, $message);
						}
					}
				}
			}			
			if(!empty($fb_data['user_profile']['id'])){
				$fb_name= $fb_data['user_profile']['name'];
				if(isset($_SESSION['FB_Detail'])){
					$web_acc=1;
					$fb_fail_reason=isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : ''.' : Borrower Registration Session Ok';
				}	
				
				/*if(!empty($fb_data['user_profile']['email']) && !empty($email) && !empty($fb_name) && !empty($namea) && !empty($nameb)){
					if($fb_data['user_profile']['email']!=$email && stripos($fb_name, $namea)==false && stripos($fb_name, $nameb)==false)
						$web_acc=0;
				}*/

			//Mohit change 30 Sept 
			// Facebook and Zidisha Account email, name commented as per the julia email
			/*
			$checkName = false;
			if(!empty($fb_data['user_profile']['email']) && !empty($email)){
					if (strcasecmp($fb_data['user_profile']['email'], $email) != 0){
						$checkName = true;
					}
					else{
						$checkName = false;
					}
				}else{
					$checkName = true;
				}
				if($checkName){
					$fname=true;
					$lname=true;
					
					if(!empty($fb_name) && !empty($namea) && !empty($nameb)){
					
						$fname=stripos($fb_name, $namea);
						$lname=stripos($fb_name, $nameb);
						
					}

					if($fname===false && $lname===false){
						$web_acc=0;
					}
				   }
				*/
			      $database->saveFacebookInfo($fb_data['user_profile']['id'], serialize($fb_data), $web_acc, $id, $email, $fb_fail_reason);
			}
			$database->IsUserinvited($id, $email); 
			$this->updateBorrowerDocument($id, $documents);// check if the registered user invited by any other existing user and save it in invitees table for future tracking.
			return $retVal ;
		}
	}
	function editprofile_b($uname, $namea, $nameb, $pass1, $pass2, $post, $city, $country, $email, $mobile,$reffered_by, $income, $about, $bizdesc, $photo, $id, $bnationid, $language, $community_name_no, $documents, $ableToComp, $repaidPast, $debtFree, $share_update, $onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $borrower_behalf_id, $submit_type, $uploadedDocs, $bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3,$home_no, $rec_form_offcr_name, $rec_form_offcr_num, $refer_member, $volunteer_mentor, $cntct_type, $fb_data, $endorser_name, $endorser_email, $endorser_id)
	{
		global $database, $form, $mailer,$validation ;
		traceCalls(__METHOD__, __LINE__);
		$completeLater = 0;
		require("editables/register.php");
		$path=  getEditablePath('register.php');
		require ("editables/".$path);
		$fb_data= unserialize(stripslashes(urldecode($fb_data)));
		Logger_Array("FB LOG - on session 1",'fb_data', serialize($fb_data).$uname);
		if($cntct_type!='1' || $_SESSION['FB_Error']!=false){
//			Logger_Array("FB LOG - on session 2",'fb_data', serialize($fb_data).$uname);
			Logger_Array("cnt",$cntct_type, serialize($_SESSION['FB_Error']));
			$fb_data= '';
			unset($_SESSION['FB_Error']);
		}
		
		if($submit_type != $lang['register']['Registerlater']) {
//			Logger_Array("FB LOG - on session 3",'fb_data', serialize($fb_data).$uname);
			$validation->validateBorrowerEdit($uname, $namea, $nameb, $pass1, $pass2, $post, $city, $country, $email, $mobile,$reffered_by, $income, $about, $bizdesc, $photo, $bnationid, $community_name_no, $documents, $repaidPast, $debtFree, $share_update,$onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $submit_type, $uploadedDocs, $bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3,$home_no, $rec_form_offcr_name, $rec_form_offcr_num, $cntct_type, $fb_data, $endorser_name, $endorser_email,$id);
		} else {
			$completeLater = 1;
			if(!empty($pass1))
			$validation->checkPassword($pass1,$pass2, "bpass1");
			$validation->checkEmailForBorrower($email, "bemail", $this->userid);
//added by Julia 13-12-2013
			$validation->checkCountry($country, "bcountry");
			$iscompleteLater = $database->getiscompleteLater($this->userid);
			if($iscompleteLater) {
				//$validation->checkNationId($bnationid, "bnationid", $country, $this->userid);
			}
		}
		if($form->num_errors>0){ 
			return 1;
		}
		else
		{	
			$rtn=$database->updateBorrower($uname,$namea,$nameb, $pass1, $post, $city,$country,$email, $mobile,$reffered_by, $income, $about, $bizdesc,$id,$bnationid, $language, $community_name_no, $repaidPast, $debtFree,$share_update,$onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $borrower_behalf_id, $completeLater, $bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3, $home_no, $rec_form_offcr_name, $rec_form_offcr_num, $refer_member, $volunteer_mentor, $fb_data, $endorser_name, $endorser_email, $endorser_id);
			
			if($rtn == 0){		

				$this->getNewBAccountSiftData('edit_account',$this->userid,$uname,$namea,$nameb,$post,$city,$country,$bnationid,$email,$mobile,$bfamilycont1,$bfamilycont2,$bfamilycont3, $bneighcont1,$bneighcont2,$bneighcont3,$rec_form_offcr_name, $rec_form_offcr_num, $aboutMe,$aboutBusiness,$hearaAoutZidisha);
	
			}
			
			$isverified=$database->getVerifiedEmailBorrower($id);
			
			if($rtn == 0 && $submit_type == $lang['register']['Registerlater']) {				
					if($isverified==0){
					$From=EMAIL_FROM_ADDR;
					require("editables/mailtext.php");
					$templet="editables/email/simplemail.html";
					$path=  getEditablePath('mailtext.php',$language);
					require ("editables/".$path);
					$Subject=$lang['mailtext']['BorrowerReg-subject'];
					$replyTo = SERVICE_EMAIL_ADDR;
					$To=$params['name'] = $namea." ".$nameb ;
					$message = $this->formMessage($lang['mailtext']['BorrowerReg-msg'], $params);
					$this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);

					$Subject=$lang['mailtext']['email_verification_sub'];
					$To=$params['name'] = $namea." ".$nameb ;
					$activate_key = $database->getActivationKey($id);
					$link = SITE_URL."index.php?p=51&ident=$id&activate=$activate_key";
					$params['verify_link'] = $link;
					$message = $this->formMessage($lang['mailtext']['email_verification_body'], $params);
					$reply = $this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);
					}
			}		
			
			if($rtn == 0 && $submit_type == $lang['register']['RegisterComplete']) {			
					
					if($isverified==0){
						$From=EMAIL_FROM_ADDR;
						require("editables/mailtext.php");
						$templet="editables/email/simplemail.html";
						$path=  getEditablePath('mailtext.php',$language);
						require ("editables/".$path);
						$Subject=$lang['mailtext']['BorrowerReg-subject'];
						$replyTo = SERVICE_EMAIL_ADDR;
						$To=$params['name'] = $namea." ".$nameb ;
						$message = $this->formMessage($lang['mailtext']['BorrowerReg-msg'], $params);
						$this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3, $params);


						$Subject=$lang['mailtext']['email_verification_sub'];
						$To=$params['name'] = $namea." ".$nameb ;
						$activate_key = $database->getActivationKey($id);
						$link = SITE_URL."index.php?p=51&ident=$id&activate=$activate_key";
						$params['verify_link'] = $link;
						$message = $this->formMessage($lang['mailtext']['email_verification_body'], $params);
						$reply = $this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);
						$this->sendContactConfirmation($id);	// send SMS community leader, family and neighborcontacts
					 }
				if($reply)
					Logger_Array("Email Verification mail sent to borrower ",'email, To', $email, $To);

				if($cntct_type=='1'){ 
					for($i=0; $i<10; $i++){ 
						if(!empty($endorser_name[$i]) && !empty($endorser_email[$i])){ 
							$e_details= $database->getEndorserForEmail($id, $endorser_name[$i], $endorser_email[$i]);
							if(empty($e_details['message'])){
								$validation_code= $e_details['validation_code'];
								$From=$email;
								$reg_link = SITE_URL."index.php?p=93&vd=$validation_code";
								$params['reg_link']= $reg_link;
								require("editables/mailtext.php");
								$templet="editables/email/simplemail.html";
								$path=  getEditablePath('mailtext.php',$language);
								require ("editables/".$path);
								$e_email= $endorser_email[$i];
								$Subject=$namea." ".$nameb." ".$lang['mailtext']['borrowerEndorser-subject'];
								$To=$params['name'] = $endorser_name[$i] ;
								$params['bname']= $namea." ".$nameb;
								$replyTo = SERVICE_EMAIL_ADDR;
								$message = $this->formMessage($lang['mailtext']['BorrowerEndorser-msg'], $params);
								$reply= $this->mailSendingHtml($From, $To, $e_email, $Subject, '', $message, 0, $templet, 3); 
								$database->updateEndorserAfterEmail($id, $To, $e_email, $message);
							}
						}
					}
				}
			}
				$this->updateBorrowerDocument($id, $documents);
				return $rtn;
		}
	}

		function additional_verification($id, $language, $documents, $submitformvalue, $uploadedDocs, $fb_data, $endorser_name, $endorser_email, $endorser_id)
	{
		global $database, $form, $mailer,$validation ;
		traceCalls(__METHOD__, __LINE__);
		require("editables/register.php");
		$path=  getEditablePath('register.php');
		require ("editables/".$path);
		$fb_data= unserialize(stripslashes(urldecode($fb_data)));
		Logger_Array("FB LOG - on session 1",'fb_data', serialize($fb_data).$uname);
		if($_SESSION['FB_Error']!=false){
//			Logger_Array("FB LOG - on session 2",'fb_data', serialize($fb_data).$uname);
			Logger_Array("cnt",serialize($_SESSION['FB_Error']));
			$fb_data= '';
			unset($_SESSION['FB_Error']);
		}
		
		if($form->num_errors>0){ 
			return 1;
		}
		else
		{	
			$rtn=$database->additionalVerification($id, $fb_data, $endorser_name, $endorser_email, $endorser_id);
			
			
			if($rtn == 0) {
				
					for($i=0; $i<10; $i++){ 
						if(!empty($endorser_name[$i]) && !empty($endorser_email[$i])){ 
							$e_details= $database->getEndorserForEmail($id, $endorser_name[$i], $endorser_email[$i]);
							$bdetail=$database->getBorrowerDetails($id);
							
							if(empty($e_details['message'])){
								$validation_code= $e_details['validation_code'];
								$From=$bdetail['email'];
								$reg_link = SITE_URL."index.php?p=93&vd=$validation_code";
								$params['reg_link']= $reg_link;
								require("editables/mailtext.php");
								$templet="editables/email/simplemail.html";
								$path=  getEditablePath('mailtext.php',$language);
								require ("editables/".$path);
								$e_email= $endorser_email[$i];
								$Subject=$namea." ".$nameb." ".$lang['mailtext']['borrowerEndorser-subject'];
								$To=$params['name'] = $endorser_name[$i] ;
								$params['bname'] = $bdetail['FirstName']." ".$bdetail['LastName'];
								$replyTo = SERVICE_EMAIL_ADDR;
								$message = $this->formMessage($lang['mailtext']['BorrowerEndorser-msg'], $params);
								$reply= $this->mailSendingHtml($From, $To, $e_email, $Subject, '', $message, 0, $templet, 3); 
								$database->updateEndorserAfterEmail($id, $To, $e_email, $message);
							}
						}
					}
				
			}

				$this->updateBorrowerDocument($id, $documents);
				return $rtn;
		}
	}

	function updateBorrowerDocument($id, $documents)
	{
		global $database;
		$front_national_id='';
		$back_national_id='';
		$address_proof='';
		$legal_declaration='';
		if(is_uploaded_file($documents['front_national_id']['tmp_name']) || !empty($documents['front_national_id']['tmp_name']))
		{	
			$path_info = pathinfo($documents['front_national_id']['name']);
			$ext=$path_info['extension'];
			$front_national_id= $id."-".md5(mt_rand(0, 32).time()).".".$ext;
			$ismoved = move_uploaded_file($documents['front_national_id']['tmp_name'],DOCUMENT_DIR.$front_national_id);
			if(!$ismoved) {
				copy($documents['front_national_id']['tmp_name'],DOCUMENT_DIR.$front_national_id);
			}
			$database->updateBorrowerDocument($id, 'frontNationalId', $front_national_id);
		}
		if(is_uploaded_file($documents['back_national_id']['tmp_name']) || !empty($documents['back_national_id']['tmp_name']))
		{
			$path_info = pathinfo($documents['back_national_id']['name']);
			$ext=$path_info['extension'];
			$back_national_id= $id."-".md5(mt_rand(0, 32).time()).".".$ext;
			$ismoved1=move_uploaded_file($documents['back_national_id']['tmp_name'],DOCUMENT_DIR.$back_national_id);
			if(!$ismoved1) {
				copy($documents['back_national_id']['tmp_name'],DOCUMENT_DIR.$back_national_id);
			}
			$database->updateBorrowerDocument($id, 'backNationalId', $back_national_id);
		}
		if(is_uploaded_file($documents['address_proof']['tmp_name']) || !empty($documents['address_proof']['tmp_name']))
		{
			$path_info = pathinfo($documents['address_proof']['name']);
			$ext=$path_info['extension'];
			$address_proof= $id."-".md5(mt_rand(0, 32).time()).".".$ext;
			$ismoved2 = move_uploaded_file($documents['address_proof']['tmp_name'],DOCUMENT_DIR.$address_proof);
			if(!$ismoved2) {
				copy($documents['address_proof']['tmp_name'],DOCUMENT_DIR.$address_proof);
			}
			$database->updateBorrowerDocument($id, 'addressProof', $address_proof);
		}
		if(is_uploaded_file($documents['legal_declaration']['tmp_name']) || !empty($documents['legal_declaration']['tmp_name']))
		{
			$path_info = pathinfo($documents['legal_declaration']['name']);
			$ext=$path_info['extension'];
			$legal_declaration= $id."-".md5(mt_rand(0, 32).time()).".".$ext;
			$ismoved3 = move_uploaded_file($documents['legal_declaration']['tmp_name'],DOCUMENT_DIR.$legal_declaration);
			if(!$ismoved3) {
				copy($documents['legal_declaration']['tmp_name'],DOCUMENT_DIR.$legal_declaration);
			}
			$database->updateBorrowerDocument($id, 'legalDeclaration', $legal_declaration);
		}
		if(is_uploaded_file($documents['legal_declaration2']['tmp_name']) || !empty($documents['legal_declaration2']['tmp_name']))
		{
			$path_info = pathinfo($documents['legal_declaration2']['name']);
			$ext=$path_info['extension'];
			$legal_declaration2= $id."-".md5(mt_rand(0, 32).time()).".".$ext;
			$ismoved4 = move_uploaded_file($documents['legal_declaration2']['tmp_name'],DOCUMENT_DIR.$legal_declaration2);
			if(!$ismoved4) {
				copy($documents['legal_declaration2']['tmp_name'],DOCUMENT_DIR.$legal_declaration2);
			}
			$database->updateBorrowerDocument($id, 'legal_declaration2', $legal_declaration2);
		}
	}
	function loanApplication($amount, $interest, $installments, $gperiod, $summary, $loanuse,$tnc, $installment_day,$installment_weekday)
	{	
		global $database, $form, $lang;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$currency=$database->getUserCurrency($this->userid);
		if(empty($amount)){
			$form->setError("amount", $lang['error']['invalid_loanamt']);
		} else if(!is_numeric($amount)){
			$form->setError("amount", $lang['error']['invalid_loanamt']);
		} else {
			$rate=$database->getCurrentRate($this->userid);		
			
			/* commented by Mohit on date on date 12-12-2013 
			// AMTTHRESHOLD AMOUNT CONVERT TO LOCAL CURRENCY -06-12-13
			$amnt_ths_limit=ceil(convertToNative(AMTTHRESHOLD_LIMIT, $rate));
			$amnt_ths_below_limit=ceil(convertToNative(AMTTHRESHOLD_BELOW_LIMIT, $rate));
			$amnt_ths_medium_limit=ceil(convertToNative(AMTTHRESHOLD_MEDIUM_LIMIT, $rate));			
			if($amount<$amnt_ths_limit){
				if($amount<$amnt_ths_below_limit){
				$maxBorrowerAmt= ceil($database->getAdminSetting('maxBorrowerAmt', 0,'',0,'below_limit'));
				}else if($amount>=$amnt_ths_below_limit && $amount<=$amnt_ths_medium_limit){
				$maxBorrowerAmt= ceil($database->getAdminSetting('maxBorrowerAmt', 0,'',0,'threshold_mid_limit1'));
				}else if($amount>$amnt_ths_medium_limit && $amount<=$amnt_ths_limit){
				$maxBorrowerAmt= ceil($database->getAdminSetting('maxBorrowerAmt', 0,'',0,'threshold_mid_limit2'));
				}				
			}else{
				$maxBorrowerAmt= ceil($database->getAdminSetting('maxBorrowerAmt', 1));
			}/* It is in native currency */
			
			/*-----------------------------*/
			$maxBorrowerAmt =ceil($this->getCurrentCreditLimit($this->userid,true)); // added by Mohit on date 2-12-2013 

			$minBorrowerAmt= ceil(convertToNative($database->getAdminSetting('minBorrowerAmt'), $rate));
			if($minBorrowerAmt>$amount){
				$form->setError("amount", $lang['error']['greater_loanamt']." ".$currency." ".$minBorrowerAmt);
			}
			if($maxBorrowerAmt<$amount){
				$form->setError("amount", $lang['error']['lower_loanamt']." ".$currency." ".$maxBorrowerAmt);
			}
			if($installments >=$amount){
				$form->setError("installment_amt", $lang['error']['greater_instllAmt']);
			}
		}
		if(empty($installment_day) && empty($installment_weekday)){
			$form->setError("installment_day", $lang['error']['invalid_day']);
		}
		if(empty($interest)){
			$form->setError("interest", $lang['error']['invalid_interest']);
		} else if(!is_numeric($interest)){
			$form->setError("interest", $lang['error']['invalid_interest']);
		} else {
			$fee = $database->getAdminSetting('fee');
			$maxLoanAppInterest= $database->getAdminSetting('maxLoanAppInterest');
			if($interest < $fee) {
				$form->setError("interest", $lang['error']['greater_interest']." ". $fee."%");
			} else if($interest > ($fee+$maxLoanAppInterest)) {
				$form->setError("interest", $lang['error']['lower_interest']." ". ($fee+$maxLoanAppInterest)."%");
			}
		}
		if(empty($installments)){
			$form->setError("installment_amt", $lang['error']['empty_installment']);
		}
		else if(!is_numeric($installments)){
			$form->setError("installment_amt", $lang['error']['invalid_installment']);
		} else {

			$maxRepayPeriod_months= $database->getAdminSetting('maxPeriodValue');

			if (!empty($installment_weekday)) {
				$weekly_inst = 1;
				$maxRepayPeriod = $maxRepayPeriod_months * (52/12);
			} else {
				$weekly_inst = 0;
				$maxRepayPeriod = $maxRepayPeriod_months;
			}

			$total_months=$this->getTotalMonthByInstallments($amount, $installments, $interest, $gperiod, $weekly_inst);
			
			if($maxRepayPeriod < $total_months || $total_months <=0) {
				$minIns=$this->getMinInstallment($amount, $maxRepayPeriod, $interest, $gperiod, $weekly_inst);
				$form->setError("installment_amt", $lang['error']['min_ins_amt']." ".$currency." ".$minIns);
			}
		}
		if($gperiod<1 || !is_numeric($gperiod)){
			$maxGraceMonth=$database->getAdminSetting('maxLoanAppGracePeriod');
			$form->setError("gperiod", $lang['error']['invalid_gracetime']." ".$maxGraceMonth." ".$lang['error']['months']);
		}
		else
		{
			$maxGP=$database->getAdminSetting('maxLoanAppGracePeriod');
			if($gperiod >$maxGP){
				$form->setError("gperiod", $lang['error']['max_gracetime']." ".$maxGP." ".$lang['error']['months']);
			}
			//grace period must be lower than total period
			else if($gperiod >=$total_months && $total_months >0){
				$form->setError("gperiod", $lang['error']['lower_gracetime']." ".trim($total_months));
			}
		}
		if(empty($summary)){
			$form->setError("summary", $lang['error']['empty_summary']);
		
		}
		if(empty($loanuse)){
			$form->setError("loanuse", $lang['error']['empty_loanuse']);
		
		}
		
		else if(strlen($loanuse) <300) {
			$form->setError('loanuse', $lang['error']['min_length_comment']);
		}
		
		if(empty($tnc)){
			$form->setError("agree", $lang['error']['empty_tnc']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		return 1;
	}
	function editLoanApplication($loanid, $amount, $interest, $summary, $loanuse, $inst_amount, $inst_day, $inst_weekday, $gperiod, $validate=0, $repay_period)
	{
		global $database, $form;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$userid=$this->userid;
		$currency=$database->getUserCurrency($this->userid);
		$loandata=$database->getLastloan($userid);
		$bidsIntr=$database->getMinMaxBidIntr($loanid);
		$totBidsAmt=$database->getTotalBidAmount($loanid);
		$rate=$database->getCurrentRate($userid);
		$OldRate=$database->getExRateById($loandata['applydate'], $loandata['borrowerid']);
		$totBidsAmtNative = convertToNative($totBidsAmt, $OldRate);
		$interest = str_replace('%', '', $interest);
		if(empty($amount)){
			$form->setError("amount", $lang['error']['invalid_loanamt']);
		}
		else
		{
			$rate=$database->getCurrentRate($userid);
			
			$maxBorrowerAmt =$this->getCurrentCreditLimit($userid,true); // Added by Mohit on date 2-12-2013 
			
			$minBorrowerAmt= ceil(convertToNative($database->getAdminSetting('minBorrowerAmt'), $rate));
			if($minBorrowerAmt>$amount){
				$form->setError("amount", $lang['error']['greater_loanamt']." ".$currency." ".$minBorrowerAmt);
			}
			if($loandata['Amount'] < $amount)
			{
				if($maxBorrowerAmt < $amount)
				{
					if($loandata['Amount'] > $maxBorrowerAmt){
						$form->setError("amount", $lang['error']['lower_loanamt']." ".$currency." ".$loandata['Amount']);
					}
					else{
						$form->setError("amount", $lang['error']['lower_loanamt']." ".$currency." ".$maxBorrowerAmt);
					}
				}
			}
			elseif($loandata['Amount'] > $amount)
			{
				$AmountUsd=convertToDollar($amount, $OldRate);
				if($loandata['reqdamt'] <= $totBidsAmt){
					$form->setError("amount", $lang['error']['greater_loanamt']." ".$currency." ".$loandata['Amount']);
				}
				else if($AmountUsd < $totBidsAmt){
					$form->setError("amount", $lang['error']['greater_loanamt']." ".$currency." ".$totBidsAmtNative);
				}
			}
		}
		if(empty($interest)){
			$form->setError("interest", $lang['error']['invalid_interest']);
		}
		else
		{
			$fee = $database->getAdminSetting('fee');
			if($interest < $fee)
			{
				if(($bidsIntr['max'] + $fee) > $fee)
					$form->setError("interest", $lang['error']['greater_interest_lender']." ". ($bidsIntr['max'] + $fee));
				else
					$form->setError("interest", $lang['error']['greater_interest']." ".$fee."%");
			}
			else if($interest < $loandata['interest'])
			{
				if(!empty($bidsIntr['max']) && ($bidsIntr['max'] + $fee) >$interest)
					$form->setError("interest", $lang['error']['greater_interest_lender']." ". ($bidsIntr['max'] + $fee));
			}
		}
		if(empty($loanuse)){
			$form->setError("loanuse", $lang['error']['empty_loanuse']);
		}
		
		else if(strlen($loanuse) < 300) {
			$form->setError('loanuse', $lang['error']['min_length_comment']);
		}
		
		if((empty($inst_day) || $inst_day>31 || $inst_day < 1) && (empty($inst_weekday))){
			$form->setError("installment_day", $lang['error']['invalid_day']);
		}
		$maxGP=$database->getAdminSetting('maxLoanAppGracePeriod');
		if($gperiod > $maxGP) {
			$form->setError('gperiod', $lang['error']['max_gracetime']." ".$maxGP." ".$lang['error']['months']);
		}
		
		$maxRepayPeriod_months= $database->getAdminSetting('maxPeriodValue');

		if (!empty($inst_weekday)) {
			$weekly_inst = 1;
			$maxRepayPeriod = $maxRepayPeriod_months * (52/12);
		} else {
			$weekly_inst = 0;
			$maxRepayPeriod = $maxRepayPeriod_months;
			}

		$total_months=$this->getTotalMonthByInstallments($amount, $inst_amount, $interest, $gperiod, $weekly_inst);
			
		if($maxRepayPeriod < $total_months || $total_months <=0) {
			$minIns=$this->getMinInstallment($amount, $maxRepayPeriod, $interest, $gperiod, $weekly_inst);
			$form->setError("installment_amt", $lang['error']['min_ins_amt']." ".$currency." ".$minIns);
		}
		if($form->num_errors > 0){
			return 0;
		}
		else if($validate == 1) {
			return 1;
		}else {
			$ret=$database->updateLoanApp($userid, $loanid, $amount, $interest, $summary, $loanuse, $inst_day, $gperiod, $weekly_inst, $inst_weekday, $repay_period);
			return $ret;
		}
	}
	function confirmLoanApp($amount, $interest, $period, $gperiod, $summary, $loanuse,$tnc,$loan_installmentDate, $loan_installmentDay)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		if (!empty ($loan_installmentDay)) {
			$weekly_inst = 1;
		} else {
			$weekly_inst = 0;
		} 
		$loanid= $database->loanApplication($this->userid, $amount, $interest, $period, $gperiod, $summary, $loanuse,$tnc, $weekly_inst, $loan_installmentDate, $loan_installmentDay);
		if($loanid)
		{
			unset($_SESSION['la']);
			unset($_SESSION['loanapp']);
			$this->SendLoanConfirmMailToBorrower($this->userid, $loanid);
			$loanCount= $database->getLoanCount($this->userid);
			$userid=$this->userid;
			if($loanCount)
			{
				$oldLoanid=$database->getLastRepaidloanId($userid);
				$bname= $database->getNameById($userid);
				$lenders= $database->getLendersEmailForLoanApp($oldLoanid);
				$repay_date= $database->getRepaidDate($userid, $oldLoanid);
				foreach($lenders as $lender)
				{
					$this->sendNewLoanAppMailToLender($loanid, $lender['Email'], $lender['FirstName'].' '.$lender['LastName'], $userid, $bname, $repay_date);
				}
			}
			return 1;
		}
		else
		{
			return 0;
		}
	}
	function getStatusBar($ud,$ld, $promote=0)
	{
		global $database;
		$state=$database->getUserLoanStatus($ud,$ld); 
		if(!empty($state))
		{
			$woff=0;
			$stage=$state['active'];
			$reqAmount=$state['reqdamt'];
			$Amount=$state['Amount'];
			$text="<div class='progress'>";
			if(($stage == LOAN_OPEN) || ($stage == LOAN_FUNDED))
			{
				$p=($database->getTotalBid($ud, $ld)/($reqAmount))*100 ; // divided by reqAmount as it is in doller
				$p_org=$p;
				$p1=number_format($p, 0, ".", ",")."%";
				if($p>=100)
				{
					
					$p='100%';
					$p1='100%';
					$msg='Raised';
					$imgClass= 'fundingLoanBar';
					$bgcolor='#CCCCCC';
				}
				else
				{
					$p_rounded = number_format($p);
					if($p < 100 && $p_rounded>=100) {
						$p='99';
						$p1=number_format($p, 0, ".", ",")."%";
					}
					
					$p=number_format($p).'%';
					$msg=' Raised';
					$imgClass= 'fundingLoanBar';
					$bgcolor='#CCCCCC';
				}

			}
			else if(($stage == LOAN_ACTIVE) || ($stage == LOAN_REPAID) || ($stage == LOAN_DEFAULTED))
			{	
				$res=$database->getTotalPayment($ud, $ld);
				if($res['amttotal'] > 0) {
					$p= $res['paidtotal']/$res['amttotal']*100;
				} else {
					$p=100;
				}
				$p_org=$p;
				$p_rounded = number_format($p);
				if($p < 100 && $p_rounded>=100) {
					$p='99';
					$p1=number_format($p, 0, ".", ",")."%";
				}
				$p1=number_format($p, 0, ".", ",")."%";
				if($p>=100)
				{
					$p='100%';
					$p1='100%';
					$msg=' Repaid';
					$imgClass= 'repaidLoanBar';
					$bgcolor='#CCCCCC';
				}
				else
				{
					$p=number_format($p).'%';
					if($stage == LOAN_DEFAULTED)
						$woff=(100-number_format($p_org)).'%';
					$msg=' Repaid';
					$imgClass= 'repaidLoanBar';
					$bgcolor='#CCCCCC';
				}
			}
			else
			{
				$p_org=100;
				$p='100%';
				$p1='';
				if(($stage == LOAN_DEFAULTED))
					$msg='Written Off';
				else if($stage == LOAN_CANCELED)
					$msg='Cancelled';
				else if($stage == LOAN_EXPIRED)
					$msg='Expired';
				$imgClass= 'expiredLoanBar';
				$bgcolor='#CCCCCC';
			}
			if($promote==1)
			{
				$text="<table style='width:100%'>";
				if(($stage == LOAN_OPEN) || ($stage == LOAN_FUNDED))
					$text=$text."<tr><td align='left' height='4' bgcolor='".$bgcolor."' ><table width='".$p."'><tr><td bgcolor='#009900'></td></tr></table></td></tr>";
				else if(($stage == LOAN_ACTIVE) || ($stage == LOAN_REPAID))
					$text=$text."<tr><td align='left' height='4' bgcolor='".$bgcolor."' ><table width='".$p."'><tr><td bgcolor='#999999'></td></tr></table></td></tr>";
				else
					$text=$text."<tr><td align='left' height='4' bgcolor='".$bgcolor."' ><table width='".$p."'><tr><td bgcolor='#FE2828'></td></tr></table></td></tr>";
				if($stage == LOAN_DEFAULTED)
				{
					$text=$text."<tr><td align='center'>".$p1." ".$msg.",<br/>".$woff." Written Off</td></tr>";
				}
				else
				{
					$text=$text."<tr><td align='center'>".$p1." ".$msg." </td></tr>";
				}
				$text=$text."</table>";
				return $text;
			}
			else if($promote==2)
			{
				return $p1." ".$msg;
			}
			else if($promote==3)
			{
				return $p1;
			}
			else if($promote==4)
			{
				return $p1." ".$msg.",<br/>".$woff." Written Off";
			}
			else if($promote==5)
			{
				return round($p_org);
			}
			else
			{
				
				$text=$text."<div class='".$imgClass."' style='width:".$p."'></div></div>";
				
			}
			if($stage == LOAN_DEFAULTED)
			{

				$text=$text."<strong>".$p1." ".$msg.",<br/>".$woff." Written Off</strong>";
			}
			else
			{
				$text=$text."<strong>".$p1." ".$msg."</strong>";
			}

			return $text;
		}
		else
		{
			return '';
		}
	}
	function acceptBids($loanid, $acceptBid_note, $bid=0)
	{ 
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$userId = $this->userid;
		if($bid != 0)
			$userId = $bid;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(strlen(trim($acceptBid_note)) >300){
			$form->setError("acceptBid_note", $lang['error']['invalid_acceptBid_note']);
			return 0;
		}
		$bids=$database->getLoanBids($userId, $loanid);
		if(empty($bids))
			return 0;
		$lamount=$database->getOpenLoanAmount($userId, $loanid);
		$bidamount=0.00;
		$interest=0.00;
		$count=0;
		$temp=0.00;
		$temp1=0.00;
		$arr=array();
		$bidids=array();
		$rate = $database->getCurrentRate($userId);
		if(empty($rate))
			return 0;
		foreach($bids as $bid )
		{
			if(convertToNative($bidamount, $rate) < $lamount)
			{
				$bida=$bid['bidamount'];
				$bidint=$bid['bidint'];
				$bidid=$bid['bidid'];
				$lenderid=$bid['lenderid'];
				if(convertToNative(($bidamount+$bida) , $rate) <= $lamount)
				{
					$bidamount = $bidamount + $bida;
					$temp=$bidint*$bida;
					$interest=$interest+$temp;
					$bidids[$count]['bidid']=$bidid;
					$bidids[$count]['bidamount']=$bida;
					$bidids[$count]['bidrate']=$bidint;
					$count++;
				}
				else
				{
					$temp1=$lamount - convertToNative(($bidamount), $rate);
					$bida=convertToDollar($temp1, $rate);
					$bidamount=$bidamount+$bida;
					$temp=$bidint*$bida;
					$interest=$interest+$temp;
					$bidids[$count]['bidid']=$bidid;
					$bidids[$count]['bidamount']=$bida;
					$bidids[$count]['bidrate']=$bidint;
					break;
				}
			}
		}
		$arr['amt']=    $bidamount ;//is USD
		$arr['int']=    ($interest/$bidamount) ;
		$arr['bidids']= $bidids;
		$arr['loanid']= $loanid;
		$arr['acceptBid_note']= $acceptBid_note;
		return $arr;
	}
	function processBids($array, $bid=0)
	{
		//process a list to show which loans are approved back to proceess as a text or array
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$userId = $this->userid;
		if($bid != 0)
			$userId = $bid;
		$rr=$array['bidids'];
		$result=$database->processBids($rr);
		if($result==DB_OK)
		{	
			//all value set properly
			$state = LOAN_FUNDED;
			$borrowerid=$userId;
			$result1=$database->updateActiveLoan($borrowerid,$state);
			if($result1==0)
				return 0;
			$loanid=$array['loanid'];
			$rate=$array['int'];
			$result2=$database->updateLoanRate($loanid, $rate, $array['acceptBid_note']);
			if($result2==DB_OK)
			{
				foreach($rr as $row)
				{
					$deat=$database->getEmailBybidid($row['bidid']);
					$From=EMAIL_FROM_ADDR;
					$templet="editables/email/hero.html";
					require ("editables/mailtext.php");
					$To=$params['name'] = $deat['name'];
					$params['bname'] = $database->getNameById($borrowerid);
					$loanprurl = getLoanprofileUrl($borrowerid, $loanid);
					$params['profile_link'] = SITE_URL.$loanprurl;
					$params['image_src'] = $database->getProfileImage($borrowerid);
					$Subject = $this->formMessage($lang['mailtext']['AcceptBid-subject'], $params);
					$header = $this->formMessage($lang['mailtext']['AcceptBid-msg1'], $params);
					$message = $this->formMessage($lang['mailtext']['AcceptBid-msg2'], $params);
					$reply=$this->mailSendingHtml($From, $To, $deat['email'], $Subject, $header, $message,0,$templet,3,$params);
				}
				return 1;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
	}
	function getSchedule($amount, $rate, $period, $grace, $loneAcceptDate, $webrate, $weekly_inst)
	{
		global $database;
		require("editables/loanstatn.php");
		$path=  getEditablePath('loanstatn.php');
		require("editables/".$path);
		traceCalls(__METHOD__, __LINE__);
		//Principal + (Number of years of repayment period * ((Principal * Aggregate annual Lender interest rate) + (Principal * % annual fee charged by website)))0
		$newperiod=0;
		$installment=0;
		if(!empty($newperiod)) {
			$newperiod+=$period;
		} else {
			$newperiod=$period;
		}
		$UserCurrency = $database->getUserCurrency($this->userid);
		if ($weekly_inst ==1){
			$interest=(($newperiod)/52)*(($amount*($rate/100))); 
		} else {
			$interest=(($newperiod)/12)*(($amount*($rate/100))); 
		}
		$totalamt=$amount+$interest;
		$totalremaining = $totalamt;
		$pamount=$totalamt/($newperiod-$grace+1);
		$pamount=round($pamount, 4);
		if(!empty($installment)) {
			$pamount=$installment;
		}
		if($weekly_inst != 1) {
			$head = $lang['loanstatn']['months_after_disb_date'];
		} else {
			$head = $lang['loanstatn']['weeks_after_disb_date'];
		}
		$text="<table class='zebra-striped'>
				<thead>
					<tr>
						<th>".$head."</th>
						<th>".$lang['loanstatn']['amount']." (".$UserCurrency.")</th>
						<th>".$lang['loanstatn']['balance']."</th>
					</tr>
				</thead>
				<tbody>";
		for($i=0; $i<=$period; $i++) {
			if($i >= $grace) {
				if($i==$period) {
					if($totalremaining < $pamount) {
						$pamount = $totalremaining;
					}
				}
				$ptot=number_format(round_local($pamount), 0, ".", ",");
				$totalremaining = $totalremaining - $pamount;
				if(abs($totalremaining) < 0.5) {
					$totalremaining = 0;
				}
				$text=$text."<tr>";
				$text=$text."<td>$i</td>";
				$text=$text."<td>$ptot</td>";
				$text=$text."<td>". number_format(round_local($totalremaining), 0, '.', ',')."</td>";
				$text=$text."</tr>";
			}
		}
		$ttotl1=number_format(round_local($totalamt) , 0, ".", ",");
		$text=$text."</tbody><tfoot><tr>
						<td><strong>".$lang['loanstatn']['total_repayment']."</strong></td>
						<td><strong>$ttotl1</strong></td>
						<td></td>
					</tr></tfoot></table>";
		return $text;
	}
	function processOldLoans()
	{
		global $database;
		// please do not uncomment following line now we do not expire loan automatically
		//$database->setExpireInLoan();
	}
	function getRescheduleDates($loanid, $installment_date=0)
	{
		global $database;
		$time= time();
		$lonedata=$database->getLoanDetails($loanid); 
		$org_repay_period= ($lonedata['original_period']==0)? $lonedata['period']:$lonedata['original_period'];
		$lastDuedate=$database->getLastDueDate($loanid);
		$nextDuedate= $database->getNextDueDate($loanid);
		$nextDuedateOrg=$nextDuedate;
		$gracePeriod_limit=$database->getAdminSetting('maxGraceperiodValue');
		$weekly_inst=$lonedata['weekly_inst'];
		if(empty($nextDuedate))
		{
			$i=1;
			while(1)
			{
				$date=strtotime('+ '.$i.' month ' , $lastDuedate);
				if($date > $time ){
					$nextDuedate=$date;
					break;
				}
				$i++;
			}

		}
		if($installment_date !=0)
		{
			if(empty($nextDuedateOrg))
			{
				$remainPeriod=-$this->schInterval($lastDuedate,$installment_date,$weekly_inst);
			}
			else
			{
				if($installment_date >$lastDuedate)
					$remainPeriod=-$this->schInterval($lastDuedate,$installment_date,$weekly_inst);
				else
					//$remainPeriod=round(($lastDuedate- $installment_date)/(30*24*60*60));
					$remainPeriod=$this->schInterval($lastDuedate,$installment_date,$weekly_inst);				
			}
		}
		$max_repay_period= $database->getAdminSetting('maxRepayPeriod');
		$maxDuedate=strtotime('+ '.($max_repay_period).' month ' , $nextDuedate);
		$rescheduleDates=array();
		$nextDuedateOrg=$nextDuedate;
		for($i=1; $i<=$gracePeriod_limit; $i++)
		{
			// 6 for we will show 6 dates to borrower for rescheduling
			if($maxDuedate >=$nextDuedate)
			{
				$rescheduleDates[$i]=$nextDuedate;
				$nextDuedate=strtotime('+ '.$i.' month ' , $nextDuedateOrg);
			}
		}
		$allDates['rescheduleDates']=$rescheduleDates;
		$allDates['nextDuedate']=$nextDuedateOrg;
		$allDates['maxDuedate']=$maxDuedate;
		$allDates['max_repay_period']=$max_repay_period;
		if(isset($remainPeriod))
			$allDates['remainPeriod']=$remainPeriod;
		return $allDates;
	}
	
	
	function schInterval($lastDuedate,$installment_date,$weekly_inst){
			if($weekly_inst==1)
			   return round(($lastDuedate-$installment_date)/604800);
			 else	
			   return round(($lastDuedate-$installment_date)/2592000);	
	}
	
	function reScheduleLoan($period,$installment_amount, $installment_date,$original_period,$reschedule_reason,$confirmReScheduleLoan,$loanid,$propose_type)
	{	
		global $database,$form;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($original_period))
			$original_period=$period;
		$flag=0;
		$userid=$this->userid;
		$periodextended = 1;
		$ifreschedule= $database->canBorrowerReSchedule($this->userid,$loanid);
		if(!$ifreschedule){
			$form->setError("notreschedule", $lang['error']['notreschedule']);
			$flag=1;
		}
		if(empty($installment_date ) && !empty($installment_amount) ) {
			$allDates= $this->getRescheduleDates($loanid);
			$installment_date = $allDates['rescheduleDates'][1];
			
		}
		$allDates= $this->getRescheduleDates($loanid, $installment_date); 
		$brw2 = $database->getLoanDetails($loanid);
		$amount=$brw2['AmountGot'];
		$tmpcurr = $database->getUserCurrency($userid);
		$rate=$brw2['finalrate'];
		$period=$brw2['period'];
		$gperiod=$brw2['grace'];
		$fee=$brw2['WebFee'];
		$weekly_inst=$brw2['weekly_inst'];
		if(empty($installment_amount) && !empty($installment_date)) {
			$installment_amount=$database->getInstallmentByLoanid($loanid);
			$reschdDates = $this->getRescheduleDates($loanid);
			foreach($reschdDates['rescheduleDates'] as $key => $value) {
				{
					if($value == $installment_date) {
						$periodextended = $key;
					}
					//echo $key; // Would output "subkey" in the example array
				}
			}
		}
		$rescheduleDetail=array();
		if($confirmReScheduleLoan==0)
		{
			if($propose_type==2){

				if(empty($installment_amount)){

					$form->setError("installment_amount", $lang['error']['empty_amount']);
					$flag=1;

				}
				else if(!is_numeric($installment_amount)){
					$form->setError("installment_amount", $lang['error']['invalid_format_amount']);
					$flag=1;
				}

			}
			else if($propose_type==1){
				if(empty($installment_date)){
					$form->setError("installment_date", $lang['error']['empty_date']);
					$flag=1;
				}
					else
					{
						if(!in_array($installment_date, $allDates['rescheduleDates'])){
							$form->setError("installment_date", $lang['error']['invalid_date']);
							$flag=1;
						}
				}
		}
		else
			{
				$form->setError("propose_type", $lang['error']['empty_propose_type']);
							$flag=1;
			}
			if(strlen($reschedule_reason) <300)
				$form->setError("reschedule_reason", $lang['error']['min_length_comment']);
		}
		if(!$flag && $confirmReScheduleLoan==0)
		{
			$maxDuedate=$allDates['maxDuedate'];
			$totalrate = $rate + $fee;
			$periodFromToday= $this->getPeriodFromTodayForReschedule($userid, $loanid, $amount, $totalrate, $installment_amount); 
			if(!empty($installment_amount)) {
			
			if($weekly_inst==1){
			$maxperiodValue=$allDates['max_repay_period']*(52/12);
			}else{
			$maxperiodValue=$allDates['max_repay_period'];
			}
			
			
			if(($maxperiodValue < $periodFromToday) || $periodFromToday < 0){
					$maxRP=$period - $allDates['remainPeriod'] + $allDates['max_repay_period'];
					$possibleIns = $this->getMinInstallmentForReschedule($userid, $loanid, $amount, $totalrate);
					$form->setError("installment_amount", $lang['error']['min_ins_amt']." ".$possibleIns);
				}
			}
			$current_instlmnt = $database->getInstallmentByDate(time(), $loanid);
			if($periodFromToday <=$allDates['remainPeriod']  &&( $periodFromToday==0 || $periodFromToday >0 ) || $current_instlmnt['amount'] < $installment_amount)
			{
				$rescheduleDetail['installment_Increased'] = 1;
			}else {
				$rescheduleDetail['installment_Increased'] = 0;
			}
				$rescheduleDetail['possible_periods']=$period- $allDates['remainPeriod'] + $periodFromToday;
				$rescheduleDetail['original_period']=$original_period;
				$rescheduleDetail['installment_amount']=$installment_amount;
				$rescheduleDetail['installment_date']=$installment_date;
				
		} 
		if($form->num_errors > 0)
			return false;
		else
		{
			if($confirmReScheduleLoan==0){
				$rescheduleDetail['reschedule_reason']=$reschedule_reason;
				$rescheduleDetail['reschd_type'] = $propose_type;
				$_SESSION['rescheduleDetail']=$rescheduleDetail;
				$_SESSION['periodextended'] = $periodextended;
				return true;
			}
			else
				{ 
				$allDate = $this->getRescheduleDates($loanid);
				$acgrperiod = 0;
				foreach($allDate['rescheduleDates'] as $date) {
					$acgrperiod++;
					if($date == $_SESSION['rescheduleDetail']['installment_date'] ) {
						break;
					}
				}
				$database->startDbTxn();
				$borrower_id=$this->userid;
				$new_period=$_SESSION['rescheduleDetail']['possible_periods'];
				$propose_type = $_SESSION['rescheduleDetail']['reschd_type'];
				$isamountIncreased = $_SESSION['rescheduleDetail']['installment_Increased'];
				$period_increased = $acgrperiod;
				$NewinstallmentAmt = $_SESSION['rescheduleDetail']['installment_amount'];
				$reschedule_reason=$_SESSION['rescheduleDetail']['reschedule_reason'];
				$reschedule_id=$database->reschedule($loanid,$borrower_id,$reschedule_reason,$new_period);
				if($reschedule_id !=0)
				{
					$reSchedule=$this->generateReSchedule($borrower_id, $loanid, $new_period, $_SESSION['rescheduleDetail']['installment_amount'], $_SESSION['rescheduleDetail']['installment_date']);
					$res1=$database->setReschedule($borrower_id, $loanid,$reschedule_id,$reSchedule);
					if($res1)
					{
						$res2=$database->updateLoanPeriod($borrower_id, $loanid, $period, $new_period);
						if($res2)
						{
							
								$database->commitTxn();
								unset($_SESSION['rescheduleDetail']);
								$res3=$database->subFeedback1($borrower_id,$borrower_id,$reschedule_reason,0,0,$reschedule_id);
								return true;
						}
						else
						{
							Logger_Array("cvError",'could not updated loanperiod in loanapplic table', $loanid);
						}
					}
					else
					{
						Logger_Array("cvError",'could not updated new schedule in repaymentschedule table', $loanid);
					}
				}
				else
				{
					Logger_Array("cvError",'could not inserted data in reschedule table', $loanid);
				}
				$database->rollbackTxn();
				return false;
			}
		}
	}
	function generateReSchedule($userid, $loanid, $new_period, $installment_amount, $installment_date)
	{
		global $database,$form;
		$oldSchedule=$database->getSchedulefromDB($userid, $loanid);
		$installment_date_margin=$installment_date-36000;
		//margin of 10 hours handing time zone differnce
		$j=-1;
		$k=0;
		$n=0;
		$schedule = array();
		$index=0;
		$fullTotal=0;
		$instAmt=0;
		foreach($oldSchedule as $row)
		{
			if($row['amount'] ==0)
			{
				$schedule[$index] = $row;
				$index++;
				$j++;
				
			}
			else if($row['amount'] !=0 && $row['paidamt'] !=NULL && $row['amount']==$row['paidamt'])
			{
				$schedule[$index] = $row;
				$fullTotal +=$row['amount'];
				$index++;
				$j++;
			}
			else if($row['amount'] !=0 && $row['paidamt'] !=NULL && $row['amount'] > $row['paidamt'])
			{
				$schedule[$index] = $row;
				$schedule[$index]['amount']=$row['paidamt'];
				$schedule[$index]['update']=1;
				$instAmt +=$row['amount']-$row['paidamt'];
				$fullTotal +=$row['paidamt'];
				$index++;
				$j++;
			}
			else if($row['amount'] !=0 && $row['paidamt'] ==NULL && $row['duedate'] < $installment_date_margin)
			{
				$schedule[$index] = $row;
				$schedule[$index]['amount']=0;
				$schedule[$index]['paidamt']=NULL;
				$schedule[$index]['update']=1;
				$instAmt +=$row['amount'];
				$index++;
				$j++;
			}
			else
			{
				$k++;
				$instAmt +=$row['amount'];
			}
			$n++;
		}
		
		$lonedata=$database->getLoanfund($userid, $loanid);
		$forgiveAmount=$database->getForgiveAmount($userid,$loanid);
		$ratio=$database->getPrincipalRatio($loanid); 
		$amount=$lonedata['AmountGot'];
		$rate=$lonedata['finalrate'];
		$webrate=$lonedata['WebFee'];
		$period=$lonedata['period'];
		$original_period=$lonedata['original_period'];
		if($forgiveAmount)
			$amountAfterForgive= $amount - ($forgiveAmount * $ratio);
		else
			$amountAfterForgive= $amount;

		$weekly_inst=$lonedata['weekly_inst'];

		if ($weekly_inst==1){
			$interestNew=(($new_period - $period)/52)*(($amountAfterForgive*($rate/100))+($amountAfterForgive*($webrate/100))); 
		}else{
			$interestNew=(($new_period - $period)/12)*(($amountAfterForgive*($rate/100))+($amountAfterForgive*($webrate/100))); 
		}
		$totalAmountNew=$instAmt + $interestNew; 
		$fullTotal +=$totalAmountNew;
		$totalRemainingPeriod= $k +  $new_period - $period +0;
		$period += 1;
		$duedate=$oldSchedule[$j]['duedate'];
		$count=1;
		$flag=0;
		$totalRemainingPeriodOrg=$totalRemainingPeriod;
		
		for($i=0; $i<$totalRemainingPeriodOrg; $i++)
		{
			$j++;
			if(isset($oldSchedule[$j]))
			{
				if($totalAmountNew >$installment_amount)
				{
					$newAmount=$installment_amount;
					$totalAmountNew = $totalAmountNew-$installment_amount;
				}
				else
				{
					$newAmount=$totalAmountNew;
					$totalAmountNew = 0;
				}
				$newPaidAmount=NULL;
				$newPaidDate=NULL;
				$schedule[$index] = array('id'=>$oldSchedule[$j]['id'],'userid'=>$oldSchedule[$j]['userid'],'loanid'=>$oldSchedule[$j]['loanid'],'duedate'=>$oldSchedule[$j]['duedate'],'amount' => $newAmount,'paiddate' => $newPaidDate,'paidamt' => $newPaidAmount,'update'=>1);
				$duedate=$oldSchedule[$j]['duedate'];
				$flag=1;
				$index++;

			}
			else if($flag==0 && strtotime('+ '.$count.' month ' , $duedate) < $installment_date_margin)
			{
				if($weekly_inst==1){

					$schedule[$index] = array('duedate'=> strtotime('+ '.$count.' week ' , $duedate), 'amount' => 0,'paiddate' => NULL,'paidamt' => NULL);
		
				}else{

					$schedule[$index] = array('duedate'=> strtotime('+ '.$count.' month ' , $duedate), 'amount' => 0,'paiddate' => NULL,'paidamt' => NULL);
				
				}
				$count++;
				$index++;
				$totalRemainingPeriod--;
			}
			else
			{
				if($totalAmountNew >$installment_amount)
				{
					$newAmount=$installment_amount;
					$totalAmountNew = $totalAmountNew-$installment_amount;
				}
				else
				{
					$newAmount=$totalAmountNew;
					$totalAmountNew = 0;
				}
				$newPaidAmount=NULL;
				$newPaidDate=NULL;
				if($weekly_inst==1){

					$schedule[$index] = array('duedate'=> strtotime('+ '.$count.' week ' , $duedate), 'amount' => $newAmount,'paiddate' => $newPaidDate,'paidamt' => $newPaidAmount);
	
				}else{
					$schedule[$index] = array('duedate'=> strtotime('+ '.$count.' month ' , $duedate), 'amount' => $newAmount,'paiddate' => $newPaidDate,'paidamt' => $newPaidAmount);
				}
				$count++;
				$index++;
			}
		}
		$schedule[0]['fullTotal']=$fullTotal;
		
		return $schedule;
	}
	function checkReferralCommission($applicant_id, $percentRepay)
	{
		global $database;
		$time=time();
		$commission= $database->getPendingCommissionByApplicantId($applicant_id);
		if(!empty($commission))
		{
			if($commission['percent_repay'] <= $percentRepay)
			{
				$loanid=$database->getActiveLoanid($commission['referrer_id']);
				if($loanid)
				{
					$CurrencyRate = $database->getCurrentRate($commission['referrer_id']);
					$repayment=$database->getTotalPayment($commission['referrer_id'], $loanid);
					$balance= $repayment['amttotal'] - $repayment['paidtotal'];
					if($balance >= $commission['ref_commission'])
					{
						$database->startDbTxn();
						$comm_admin= -1 * convertToDollar($commission['ref_commission'], $CurrencyRate);
						$result2= $database->setTransaction(ADMIN_ID,$comm_admin,'Referral Program Debit',$loanid,$CurrencyRate,REFERRAL_DEBIT);
						if($result2)
						{
							$result= $this->madePayment($commission['referrer_id'], $loanid, $time,$commission['ref_commission'], false, REFERRAL_CREDIT);
							if($result==0 || $result==-1){
								$database->rollbackTxn();
							}
							else
							{
								$result1= $database->updateCommission($commission['id'], $commission['ref_commission'], $time, $loanid);
								if($result1)
									$database->commitTxn();
								else
									$database->rollbackTxn();
							}
						}
						else
							$database->rollbackTxn();
					}
					else
					{
						$database->startDbTxn();
						$comm_admin= -1 * convertToDollar($balance, $CurrencyRate);
						$result2= $database->setTransaction(ADMIN_ID,$comm_admin,'Referral Program Debit',$loanid,$CurrencyRate,REFERRAL_DEBIT);
						if($result2)
						{
							$result= $this->madePayment($commission['referrer_id'], $loanid, $time, $balance, true, REFERRAL_CREDIT);
							if($result==0 || $result==-1){
								$database->rollbackTxn();
							}
							else
							{
								$result1= $database->updateCommission($commission['id'], $balance, $time, $loanid);
								if(!$result1)
									$database->rollbackTxn();
								else
								{
									$database->commitTxn();
									$From=EMAIL_FROM_ADDR;
									$templet="editables/email/simplemail.html";
									require("editables/mailtext.php");
									$Subject=$lang['mailtext']['borrower_referral_admin_sub'];
									$params['bname'] = $database->getNameById($commission['referrer_id']);
									$params['applicant_name'] = $database->getNameById($applicant_id);
									$params['commission'] = $commission['ref_commission'];
									$params['credit_amount'] = $balance;
									$params['due_amount'] = $commission['ref_commission']- $balance;
									$message = $this->formMessage($lang['mailtext']['borrower_referral_admin_body_2'], $params);
									$reply=$this->mailSendingHtml($From, 'Admin', ADMIN_EMAIL_ADDR, $Subject, '', $message, 0, $templet, 3);
								}
							}
						}
						else
							$database->rollbackTxn();
					}
				}
				else
				{
					Logger_Array("Borrower Referral Credit Failed",'applicant id, referrer id', $applicant_id, $commission['referrer_id']);
					$reason="No active loan, Mail sent to admin.";
					$database->updateCommissionFailed($commission['id'], $reason);
					$From=EMAIL_FROM_ADDR;
					$templet="editables/email/simplemail.html";
					require("editables/mailtext.php");
					$Subject=$lang['mailtext']['borrower_referral_admin_sub'];
					$params['bname'] = $database->getNameById($commission['referrer_id']);
					$params['applicant_name'] = $database->getNameById($applicant_id);
					$params['commission'] = $commission['ref_commission'];
					$message = $this->formMessage($lang['mailtext']['borrower_referral_admin_body_1'], $params);
					$reply=$this->mailSendingHtml($From, 'Admin', ADMIN_EMAIL_ADDR, $Subject, '', $message, 0, $templet, 3);
				}
			}
		}
	}
	function AllowForgive($loanid ,$comment)
	{
		global $database, $form;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($loanid)){
			$form->setError("loan_id", $lang['error']['emptyLoan']);
		}
		if(empty($comment)){
			$form->setError("comment", $lang['error']['emptyComment']);
		}
		if($form->num_errors > 0){
			return 1;  //Errors with form
		}else {
			$commenttomail = $comment;
			$borrowerId=$database->getBorrowerIdByloanid($loanid);
			$borrowerName=$database->getNameById($borrowerId);
			
			$exists=$database->loanAlreadyInForgiveness($loanid);
			if($exists){
				$forgive_details=$database->getLoanForgiveDetails($loanid);
				// 16-Jan-2013 Anupam now we just replace new comment over older one as per mail by Julia "Your loan to John Mopel Napais" on 16-Jan-2013
				//$comment= $forgive_details['comment']." ".$comment ;
				$validation_code= $forgive_details['validation_code'];
				if(empty($validation_code)) {
					$validation_code = md5(mt_rand(0, 32).time());
					
				}
				$res = $database->updateLoanForgiveDetails($loanid,$comment, $validation_code);
			}
			else {
				$validation_code= md5(mt_rand(0, 32).time());
				$result=$database->setForgiveLoan($loanid , $borrowerId , $comment, $validation_code);
			}
			$inactivelenders = $database->getinactiveLendersbyloanid($loanid);
			foreach($inactivelenders as $lenderstoforgv ) {
				$this->forgiveShare($loanid, $borrowerId, $lenderstoforgv['userid']);
			}
			if($exists || $result ){ 
				$dateDisb=$database->getLoanDisburseDate($loanid);
				$balance=$database->getTotalPayment($borrowerId, $loanid); 
				$outstanding=$balance['amttotal']-$balance['paidtotal'];
				$lenderdenied = $database->getlenderdenied($loanid);
				$lenders=$database->getLendersForForgive($loanid, $lenderdenied); 
				$From=EMAIL_FROM_ADDR;
				$templet="editables/email/simplemail.html";
				require("editables/mailtext.php");
				$params['bname']=$borrowerName;
				$Subject = $this->formMessage($lang['mailtext']['loan_forgiveness_subj'], $params);
				$params['date']=date('F j, Y',$dateDisb);
				$params['msg']=trim($commenttomail);
				$loanprurl = getLoanprofileUrl($borrowerId, $loanid);
				$params['link'] = SITE_URL.$loanprurl.'?v='.$validation_code;
				$currencyrate=$database->getCurrentRate($borrowerId);
				$outstanding=convertToDollar($outstanding, $currencyrate); 
				foreach($lenders as $lender){  
					$params['lenderid']= $lender['userid'];
					$To=$lender['Email'];
					$email=$lender['Email'];
					$params['name'] = trim($lender['FirstName']." ".$lender['LastName']);  
					$params['out_amnt'] = number_format($outstanding,2, '.', ',');
					$params['imgyes'] = SITE_URL."images/yes.png";
					$params['imgno'] = SITE_URL."images/no.png";
					$loanprurl = getLoanprofileUrl($borrowerId, $loanid);
					$params['profile_link'] = SITE_URL.$loanprurl;
					$params['link1'] = SITE_URL.$loanprurl.'?v='.$validation_code.'&lid='.$params['lenderid']."&dntfrg=1";; 
					
					$message = $this->formMessage($lang['mailtext']['loan_forgiveness_body'], $params);
					$reply=$this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);
				}
				$_SESSION['loan_fogiveness']=true;
				$database->UpdateExpectedRepayDate($borrowerId,$loanid);
				return 0;
			} else {
			return 1;
			}
		}
	}
	function getTotalMonthByInstallments($amount ,$installment_amt, $rate, $gperiod, $weekly_inst){
		if(empty($gperiod)) {
			$gperiod=0;
		}
		if ($weekly_inst != 1) {

			$period = ceil(($amount + ($installment_amt * $gperiod) - $installment_amt)/($installment_amt - (($amount * $rate)/1200)));

		} else {

			$period = ceil(($amount + ($installment_amt * $gperiod) - $installment_amt)/($installment_amt - (($amount * $rate)/5200)));

		}
		return $period;
	}
	function getMinInstallment($amount ,$period, $rate, $gperiod, $weekly_inst){
		if(empty($gperiod)) {
			$gperiod=0;
		}
		if ($weekly_inst != 1){
			$intAmt=($amount * $rate * ($period))/1200;
		} else {
			$intAmt=($amount * $rate * ($period))/5200;
		}
		$total=$amount + $intAmt;
		$installment_amt = ceil($total / ($period-$gperiod+1));
		return $installment_amt;
	}
	function getMinInstallmentForReschedule($userid, $loanid, $amount, $totalrate){
		global $database;
		$allDates= $this->getRescheduleDates($loanid);
		$installment_date = $allDates['rescheduleDates'][1];
		$allDates= $this->getRescheduleDates($loanid, $installment_date);
		$total=$database->getTotalPayment($userid, $loanid);
		$remainAmt=$total['amttotal']-$total['paidtotal'];
		$forgiveAmount=$database->getForgiveAmount($userid,$loanid);
		$ratio=$database->getPrincipalRatio($loanid);
		$lonedata=$database->getLoanDetails($loanid);
		$weekly_inst=$lonedata['weekly_inst'];
		
		if($forgiveAmount)
			$amountAfterForgive= $amount - ($forgiveAmount * $ratio);
		else
			$amountAfterForgive= $amount;
			
		if($weekly_inst==1){
			$PF2D =  ceil(($allDates['maxDuedate'] - $allDates['nextDuedate']) / (60*60*24*7));
			$possibleIns = ceil(((5200 * $remainAmt) - ($amountAfterForgive  * ($totalrate) * $allDates['remainPeriod']) + ($amountAfterForgive  * ($totalrate) * $PF2D)) / (5200 * $PF2D));
		}else{
			$PF2D =  ceil(($allDates['maxDuedate'] - $allDates['nextDuedate']) / (60*60*24*30));
			$possibleIns = ceil(((1200 * $remainAmt) - ($amountAfterForgive  * ($totalrate) * $allDates['remainPeriod']) + ($amountAfterForgive  * ($totalrate) * $PF2D)) / (1200 * $PF2D));
		}
		return $possibleIns;
	}
	function getPeriodFromTodayForReschedule($userid, $loanid, $amount, $totalrate, $installment_amount){
		global $database;
		$allDates= $this->getRescheduleDates($loanid); 
		$installment_date = $allDates['rescheduleDates'][1];
		$allDates= $this->getRescheduleDates($loanid, $installment_date);
		$total=$database->getTotalPayment($userid, $loanid);
		$remainAmt=$total['amttotal']-$total['paidtotal'];
		$forgiveAmount=$database->getForgiveAmount($userid,$loanid);
		$ratio=$database->getPrincipalRatio($loanid);
		$lonedata=$database->getLoanDetails($loanid);
		$weekly_inst=$lonedata['weekly_inst'];
		if($forgiveAmount)
			$amountAfterForgive= $amount - ($forgiveAmount * $ratio);
		else
			$amountAfterForgive= $amount;
			
		if($weekly_inst==1){		
			$periodFromToday=floor(((5200*$remainAmt)- ($amountAfterForgive  * ($totalrate) * $allDates['remainPeriod'])) / ((5200*$installment_amount)-($amountAfterForgive  * ($totalrate))));
		}else{
			$periodFromToday=floor(((1200*$remainAmt)- ($amountAfterForgive  * ($totalrate) * $allDates['remainPeriod'])) / ((1200*$installment_amount)-($amountAfterForgive  * ($totalrate))));
		}
		return $periodFromToday;
	}

	function RepaymentRate($userid){
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$loans= $database->getLoansForRepayRate($userid);
		$missdInstll=0;
		$onTimeInstall=0;
		$totalTodayinstallment=0;
		foreach($loans as $loan){ 
			$loanDetail= $database->isAllInstallmentOnTime($userid, $loan['loanid']);
			$missdInstll= $missdInstll+$loanDetail['missedInst'];
			$totalTodayinstallment=$totalTodayinstallment+$loanDetail['totalTodayinstallment'];
			$timelyInstall= $loanDetail['totalTodayinstallment']- $loanDetail['missedInst'];
			$onTimeInstall= $onTimeInstall+$timelyInstall ;
		}	
		if($totalTodayinstallment==0){
			$repayRate=100;
		}else{
			$brwr_repayRate= ($onTimeInstall/$totalTodayinstallment)*100;
			
			if(empty($brwr_repayRate) || $brwr_repayRate < 0){
				$brwr_repayRate=0;
			}

			$repayRate= number_format($brwr_repayRate,2, '.', ',');
		}

		return $repayRate;

	}


	function register_e($uname, $namea, $nameb, $pass1, $pass2, $postadd, $city, $country, $email, $mobile, $user_guess, $id, $bnationid, $home_no, $fb_data, $validation_code, $babout, $bconfdnt, $e_candisplay)
	{ 
		global $database, $form, $mailer, $validation;
		$completeLater = 0;
		traceCalls(__METHOD__, __LINE__);
		require("editables/register.php");
		$path=  getEditablePath('register.php');
		require ("editables/".$path);
		Logger_Array("FB LOG - on session endorser",'fb_data', serialize($fb_data).$uname);
		$fb_data= unserialize(stripslashes(urldecode($fb_data)));
		$web_acc=0;
		$fb_fail_reason=isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : ''.' : Endorser Registration Session';	
		Logger_Array("FB LOG - on session endorser",'fb_data', serialize($fb_data).$uname);
		if($_SESSION['FB_Error']!=false){ 
			$fb_data= '';
			unset($_SESSION['FB_Error']);
		}
		$isEndorsedAlready= $database->IsEndorserAlreadyReg($validation_code);
		if(!empty($isEndorsedAlready)){
			$_SESSION['endored_already']=true;
			return 1;
		}
		$validation->validateEndorserReg($uname, $namea, $nameb, $pass1, $pass2, $postadd, $city, $country, $email, $mobile, $user_guess, $bnationid, $home_no, $fb_data, $babout, $bconfdnt, $e_candisplay);
		if($form->num_errors>0){
			return 1;
		}
		else
		{	
			
			$retVal = $database->addEndorser($uname, $namea, $nameb, $pass1, $postadd, $city, $country, $email, $mobile, $user_guess, $id, $bnationid, $home_no, $fb_data, $validation_code, $completeLater, $babout, $bconfdnt, $e_candisplay);
			if($retVal==0){
				$endorserid= $database->getUserId($uname);
				$borrowerid= $database->getBorrowerOfEndorser($endorserid);
				$minendorser= $database->getAdminSetting('MinEndorser');
				$endorser_cnt= $database->IsEndorsedComplete($borrowerid);
				if($endorser_cnt>=$minendorser){
					$brwrdetail= $database->getUserById($borrowerid);
					$this->sendWelcomeMailToBorrower($borrowerid, $brwrdetail['name'], $brwrdetail['email']);
				}
				if(!empty($fb_data['user_profile']['id'])){
					$fb_name= $fb_data['user_profile']['name'];
					if(isset($_SESSION['FB_Detail'])) {
						$web_acc=1;
						$fb_fail_reason=isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : ''.' : Endorser Registration Session Ok';
					}
					// Facebook and Zidisha Account email, name commented as per the julia email
					/*
					if(!empty($fb_data['user_profile']['email']) && !empty($email) && !empty($fb_name) && !empty($namea) && !empty($nameb)){
						if($fb_data['user_profile']['email']!=$email && stripos($fb_name, $namea)==false && stripos($fb_name, $nameb)==false)
							$web_acc=0;
					}
					*/
					$database->saveFacebookInfo($fb_data['user_profile']['id'], serialize($fb_data), $web_acc,$endorserid, $email, $fb_fail_reason);
				}
			}
			return $retVal ;
		}

	
	}

	function sendJoinshareEmail($to_email, $note, $email_sub, $sendme){
		global $database, $session,$form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($to_email))
		{
			$form->setError("to_email", $lang['error']['empty_emails']);
			return 0;
		}
		$email_ids =  explode(",",$to_email);
		for($i=0; $i<count($email_ids); $i++)
		{
			if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", trim($email_ids[$i])))
			{
				$form->setError("to_email", $lang['error']['invalid_emails']);
				return 0;
			}
		}
		if($form->num_errors == 0)
		{
			$this->sendJoinShareMail($email_ids, $note, $email_sub, $sendme);
			return 1;
		}
	}

	function sendJoinShareMail($email_ids, $note, $email_sub, $sendme){
		global $database,$form;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$From=EMAIL_FROM_ADDR;
		if(!empty($this->userid)) {
			$Detail=$database->getEmailB($this->userid);
			$To = "";
			$params['note'] = (empty($note)) ? "" : nl2br($note)."<br/><br/>";
			$params['zidisha_link']= SITE_URL."index.php";
			$message = $this->formMessage($lang['mailtext']['share_join_email_body'], $params);
			foreach($email_ids as $email_id) {
				$reply_to=$Detail['email'];
				$Frm='"'. $Detail['name'] .'" <'. $Detail['email'] .'>';
				$reply=$this->mailSendingHtml($Frm, $To, $email_id, $email_sub, '', $message, 0, $templet, 3);
				}
			if($sendme) {
				$reply=$this->mailSendingHtml($From, $To, $Detail['email'], $email_sub, '', $message, 0, $templet, 3);
			}
		}
	}

	function binvite_frnd($frnd_email, $user_name,$user_email,$invite_subject,$invite_message){
		global $database, $session,$form,$validation;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(!$user_name || strlen($user_name=trim($user_name))==0){ 
			$form->setError("user_name", $lang['error']['invite_user_name']);
		}
				
		if($this->userlevel != BORROWER_LEVEL) {
			$form->setError("loginError", $lang['error']['unautho_sendmail']);
		}

		if(empty($frnd_email)){
			$form->setError("emailError", $lang['error']['empty_emails']);
		}elseif(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $frnd_email))
		{
			$form->setError("emailError", $lang['error']['invalid_emails']);
		}
		if(!$user_email || strlen($user_email=trim($user_email))==0){ 
			$form->setError("user_email", $lang['error']['invite_user_email']);
		}
		else
			$validation->checkEmail($user_email, "user_email");
		if(empty($invite_message)) { 
			$form->setError("invite_message", $lang['error']['empty_invite_msg']);
		}
		if(empty($invite_subject)) {
			$form->setError("invite_subject", $lang['error']['empty_invitesub']);
		}
		if($form->num_errors >0){
			return 0;
		}
		else
		{
			$borrower_name= (!empty($user_name)) ? $user_name : $this->fullname;
			$borrower_email = (!empty($user_email)) ? $user_email : null;
			$id = $this->userid;
			$rep = $this->sentBInviteMail($id,$frnd_email,$borrower_name ,$borrower_email,$invite_subject, $invite_message);
			return $rep;
		}
	}
	/* -------------------Borrower Section End----------------------- */


	/* -------------------Lender Section Start----------------------- */

	function register_l($username, $pass1, $pass2, $email, $fname, $lname, $about, $photo, $city, $country, $hide_Amount, $loan_comment, $tnc, $user_guess, &$id, $card_code, $frnds_emails, $frnds_msg, $loan_app_notify, $loan_repayment_credited, $subscribe_newsletter, $referral_code,$lwebsite,$member_type)
	{
		global $session, $database, $form, $mailer, $validation;
		traceCalls(__METHOD__, __LINE__);
		$validation->validateLenderReg($username, $pass1, $pass2, $fname, $lname, $email, $frnds_emails, $city, $country, $tnc, $user_guess, $card_code, $referral_code,$member_type);

		if($form->num_errors > 0){
			return 1;  //Errors with form
		}
		else
		{
			$photon='exist';
			if(!$photo || strlen($photo)<1)
				$photon="none";
			if(!$lwebsite || strlen($lwebsite)<1)
				$lwebsite="";
			if(!$lname || strlen($lname)<1)
				$lname='';
			if($member_type==5)
				$sub_user_type=LENDER_GROUP_LEVEL;
			else if($member_type==2)
				$sub_user_type=LENDER_INDIVIDUAL_LEVEL;
			$retVal = $database->addLender($username, $pass1, $email, $fname, $lname, $about, $photon, $city, $country, $hide_Amount, $loan_comment, $tnc, $loan_app_notify, $loan_repayment_credited, $subscribe_newsletter,$lwebsite,$sub_user_type);
			$id = $database->getUserId($username);
			if($referral_code || strlen($referral_code)>1){
				$amount=$database->getReferralCodeamount($referral_code);
				$txn_id = $database->setTransaction($id, $amount,'Referral Code Redemption',0, 0, REFERRAL_CODE,1);
				$cookval=md5(time());
				$refretVal = $database->addReferralCode($referral_code,$id,$cookval,$txn_id);
				setcookie("xmtpysp", $cookval, time()+60*60*24*100, COOKIE_PATH, '', COOKIE_SECURE, true);
			}
			if(!empty($id))
			{
				$this->sendWelcomeMailToLender($email);

				/* these 4 lines added by chetan for redeem gift card in new lender registration */
				if(strlen($card_code=trim($card_code)) > 0)
				{
					$res = $this->redeemCard($card_code, $id);
					$_SESSION['giftRedeemResult']=$res;
					$_SESSION['giftRedeemError']=$form->error('cardRedeemError');
				}
				if(strlen($frnds_emails=trim($frnds_emails))>0)
				{
					$lender_name = $fname." ".$lname;
					$this->sentInviteMails($id,$frnds_emails,$frnds_msg,$lender_name);
				}
				if($subscribe_newsletter == 1){
					$this->subscribeLender($email, $fname, $lname);
				}
			logger('lender registerd id '.$id);
			$database->IsUserinvited($id, $email); // check if the registered user invited by any other existing user and save it in invitees table for future tracking.

			//$this->sendMixpanelEvent('lender signup');

		}
			return $retVal;
		}
	}
	function editprofile_l($username, $pass1, $pass2, $email, $fname, $lname, $about, $photo, $city, $country, $hide_Amount, $comment, $id, $loan_app_notify, $loan_repayment_credited, $subscribe_newsletter, $website)
	{
		global $session, $database, $form, $mailer, $validation;
		traceCalls(__METHOD__, __LINE__);
		$validation->validateLenderEdit($username, $pass1, $pass2, $fname, $lname, $email, $city, $country);
		if($form->num_errors > 0){
			return 1;  //Errors with form
		}
		else
		{
			$photon=$photo;
			if(!$photo || strlen($photo=trim($photo))<1){
				$photon="none";
			}
			$LenderDetails=$database->getLenderDetails($id);
			$rtn=$database->updateLender($username, $pass1, $email, $fname, $lname, $about, $photon, $city, $country, $hide_Amount, $comment, $id, $loan_app_notify, $loan_repayment_credited, $subscribe_newsletter, $website);
			if($rtn==0)
			{
				if($LenderDetails['subscribe_newsletter']==0 && $subscribe_newsletter==1)
					$this->subscribeLender($email, $fname, $lname);
				else if($LenderDetails['subscribe_newsletter']==1 && $subscribe_newsletter==0)
					$this->unSubscribeLender($email);
			}
			return $rtn;
		}
	}
	function repaymentfeedback($borrowerid,$loanid,$feedback,$pcomment,$addmore,$cid)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$authorize=false;
		if($this->userlevel == LENDER_LEVEL && $database->isMyLender($borrowerid,$this->userid)) {
			$authorize=true;
		} else if($this->userlevel == PARTNER_LEVEL && $database->isMyPartner($borrowerid,$this->userid)) {
			$authorize=true;
		}
		if(empty($this->userid)) {
			$form->setError("feedback_userid", $lang['error']['login_to_feedback']);
			$_SESSION['pcomment']= $pcomment;
			$_SESSION['feedback']= $feedback;
		} else if(!$authorize) {
			$form->setError("feedback_userid", $lang['error']['not_authorize_to_feedback']);
		} else {
			if(empty($feedback)){
				$form->setError("feedback", $lang['error']['prev_loandetail']);
			}
			if(empty($pcomment)){
				$form->setError("comment", $lang['error']['prev_loancomment']);
			}
		}
		if($form->num_errors > 0){
			return 1;
		}
		$result=$database->repaymentfeedback($this->userid,$borrowerid,$loanid,$feedback,$pcomment,$addmore,$cid);
		if(!$result){
			$form->setError("dberror", $lang['error']['error_website']);
			return 1;
		}
		else {
			unset($_SESSION['pcomment']);
			unset($_SESSION['feedback']);
			return 0;
		}
	}
	function amountToUseForBid($userid)
	{
		global $database, $form;
		$amtAvailable = $database->getTransaction($userid,0);
		return $amtAvailable;
	}
	function getTotalLenderAmount()
	{
		global $database, $form;
		$totalAmtAvailable =$database->getTotalLenderAmount();
		return $totalAmtAvailable;
	}
	function placeBid($loanid, $brwid, $amount, $interest, $up=0, $auto_lend=false, $lenderid=0,$pcart = 0) // Anupam 21-09-2012 , last argument $pcart added if the function called by ProcessCart, if we process otherwise we added into cart.
	{	
		global $database, $form;
		$loggedInid=$this->userid;
		if($auto_lend) {
			$loggedInid=$lenderid;
		}
		traceCalls(__METHOD__, __LINE__);
		$StatusBeforeBid = $this->getStatusBar($brwid,$loanid,5);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$redirectPayment=true; // Anupam 21-09-2012 from now we always redirect to lending cart , set true 
		$availableAmt=0;
		 	$pos = strpos($amount, '.');
			if($pos !== false) {
				$amount=truncate_num($amount,2);
			}
			$field = ($up) ? "pamount1" : "pamount";
			$availableAmt = $this->amountToUseForBid($loggedInid);
			$damount=$database->getOpenLoanAmount($brwid, $loanid, false);
			$CurrencyRate = $database->getCurrentRate($brwid);
			$BidAmt=$database->getTotalBid($brwid,$loanid);
			if($availableAmt < $amount){
				//$form->setError($field, $lang['error']['insuffi_amount']." USD ". number_format($availableAmt,2, '.', ','));
				if($auto_lend) {
					return 3;
				}
				$redirectPayment=true;
			}
			$avint = $database->getAvgBidInterest($brwid, $loanid);
			$loan =$database->getLoanDetailbyLoneID($loanid);
			$field = ($up) ? "pinterest1" : "pinterest";
			if($interest < 0 || $interest == '' ){
				$form->setError($field, $lang['error']['empty_intr_rate']);
			}else if($interest < 0 || !is_numeric($interest)){
				$form->setError($field, $lang['error']['invalid_bidint']);
			}
			else if($interest > $loan['interest'] - $loan['WebFee']){
				$form->setError($field, $lang['error']['lower_bidint']." ". number_format($loan['interest'] - $loan['WebFee'],2, '.', ',')."%");
			}
			else if($interest > round($avint,2) && $avint >0 && $BidAmt >= $damount ){
				$form->setError($field, $lang['error']['lower_bidint']." ". number_format($avint,2, '.', ',')."%");
			}
		if($form->num_errors >0){
			return 3;
		}
		
		if($redirectPayment && $pcart==0 && !$auto_lend) {
			$id = $database->addBidPayment($loanid, $loggedInid, $brwid, $amount, $interest, $up);
			Logger_Array("Add Bid payment",'loanid','lender','borrowerid','amount','int','up',$loanid,$loggedInid, $brwid, $amount, $interest, $up);
			if($id) {
				$_SESSION['bidPaymentId']=$id;
				$_SESSION['LendingCartBid'] = true;
				if($pcart==0) {
					return 2;
				}
			} else {
				$field = ($up) ? "pamount1" : "pamount";
				$form->setError($field, $lang['error']['insuffi_amount']." USD ". number_format($availableAmt,2, '.', ','));
				return 3;
			}
		}
		$bids1=$database->getLoanBids($brwid, $loanid);
		$return1=array();
		if(!empty($bids1)) {
			$return1 = $this->setAcceptAmount($bids1, $damount);
			$bids1 = $return1['bids'];
		}
		
		$database->startDbTxn();
		$bidid=$database->lenderBid($loggedInid, $loanid, $brwid, $amount, $interest);
		Logger_Array("Entry in loanbids Table",'lenderID','loanid','borrowerid','amount','int',$loggedInid, $loanid, $brwid, $amount, $interest);
		if($bidid) {
			$bids=$database->getLoanBids($brwid, $loanid);
			if($bids) {
				$bidAcceptAmt=array();
				if(isset($return1['bidAcceptAmt'])) {
					$bidAcceptAmt = $return1['bidAcceptAmt'];
				}
				$return2 = $this->setAcceptAmount($bids, $damount);
				$bids = $return2['bids'];
				$txnAmt = $return2['bidAcceptAmt'][$bidid] * -1;
				$ret=$database->setTransaction($loggedInid,$txnAmt,'Loan bid',$loanid, $CurrencyRate,LOAN_BID, 0, 0, PLACE_BID, $bidid);
				Logger_Array("Entry in Trasaction Table",'lenderID','trsact amnt','loanid','bidid',$loggedInid,$txnAmt,$loanid,$bidid);
				if($ret) {
					$bidMailRes = $this->bidOutbidDownBidTransaction($brwid, $loanid, $bids, $bidAcceptAmt, $CurrencyRate);
					if(!$bidMailRes) {
						$database->rollbackTxn();
						return 0;
					}
				} else {
					$database->rollbackTxn();
					return 0;
				}
			}
			$statusAfterBid = $this->getStatusBar($brwid,$loanid,5);
			if($StatusBeforeBid < 100 && $statusAfterBid >= 100) {
				$this->SendFullyFundedMail($brwid, $loanid, $loan['applydate']);
			}
			if(!$auto_lend) {
				if($up) {
					$_SESSION['lender_bid_success1']=1;
				} else {
					$_SESSION['lender_bid_success2']=1;
				}
				$_SESSION['lender_bid_success_amt']=$amount;
				$_SESSION['lender_bid_success_int']=$interest;
				$database->commitTxn();
				return 1;
			}else {
				$database->commitTxn();
				$array['loanbid_id']=$bidid;
				return $array;
			}
		} else {
			$database->rollbackTxn();
			return 0;
		}
	}
	function setAcceptAmount($bids, $damount) {
		$totBidAmt = 0;
		$acceptedAmt = 0;
		$bidamount =0;
		$z = 0;
		$date=array();
		$bidAcceptAmt=array();
		if(is_array($bids)) {
			foreach($bids as $key =>$bid) {
				$totBidAmt = round(($totBidAmt + $bid['bidamount']),3);
				if($totBidAmt >= $damount) {
					$acceptedAmt =  round(($damount - ($totBidAmt - $bid['bidamount'])),3);
					if($acceptedAmt < 0) {
						$acceptedAmt =0;
					}
				} else {
					$acceptedAmt = $bid['bidamount'];
				}
				$bids[$z]['acceptedAmt']=$acceptedAmt;
				$bidAcceptAmt[$bid['bidid']]=$acceptedAmt;
				$date[$key] = $bid['biddate'];
				$z++;
			}
			array_multisort($date, SORT_ASC, $bids);
		}
		$return = array();
		$return['bids']=$bids;
		$return['bidAcceptAmt']=$bidAcceptAmt;
		return $return;
	}
	function bidOutbidDownBidTransaction($brwid, $loanid, $bids, $bidAcceptAmt, $CurrencyRate) {
		global $database;
		if(!empty($bidAcceptAmt)) {
			$bname = $database->getNameById($brwid);
			$From=EMAIL_FROM_ADDR;
			$templet="editables/email/hero.html";
			require ("editables/mailtext.php");
			foreach($bids as $row) {
				$lname=trim($row["Firstname"].' '.$row['LastName']);
				$bidamount=$row['bidamount'];
				$bidint=$row['bidint'];
				$acceptedAmt = $row['acceptedAmt'];
				if(isset($bidAcceptAmt[$row['bidid']])) {
					if($bidAcceptAmt[$row['bidid']] > $acceptedAmt) {
						$loanprurl = getLoanprofileUrl($brwid, $loanid);
						if($acceptedAmt == 0) {
							$txnAmt = $bidAcceptAmt[$row['bidid']];
							$ret=$database->setTransaction($row['lenderid'],$txnAmt,'Loan outbid',$loanid, $CurrencyRate,LOAN_OUTBID, 0, 0, 0, $row['bidid']);
							if(!$ret) {
								return 0;
							}
							
							$params['out_bid_amt'] = number_format($bidamount, 2, ".", ",");
							$params['bid_amt'] = number_format($bidamount, 2, ".", ",");
							$params['borrower_link'] = SITE_URL.$loanprurl;
							$params['bname'] = $bname;
							$params['bid_interest'] = number_format($bidint, 2, ".", ",");

							$Subject=$lang['mailtext']['bid_out_sub'];
							$message = $this->formMessage($lang['mailtext']['bid_out_body'], $params);

							$reply=$this->mailSendingHtml($From, $To, $row['email'], $Subject, '', $message, 0, $templet, 3);
						} else {
							$txnAmt = round(($bidAcceptAmt[$row['bidid']] - $acceptedAmt),2);
							$ret=$database->setTransaction($row['lenderid'],$txnAmt,'Loan outbid',$loanid, $CurrencyRate,LOAN_OUTBID, 0, 0, 0, $row['bidid']);
							if(!$ret) {
								return 0;
							}
							$To=$params['lname'] = $lname;
							$params['remain_bid_amt'] = number_format($acceptedAmt, 2, ".", ",");
							$params['bid_amt'] = number_format($bidamount, 2, ".", ",");
							$params['borrower_link'] = SITE_URL.$loanprurl;
							$params['bname'] = $bname;
							$params['bid_interest'] = number_format($bidint, 2, ".", ",");
							$params['out_bid_amt'] = number_format(($bidamount-$acceptedAmt), 2, ".", ",");
							$Subject=$lang['mailtext']['bid_down_sub'];
							$message = $this->formMessage($lang['mailtext']['bid_down_body'], $params);

							$reply=$this->mailSendingHtml($From, '', $row['email'], $Subject, '', $message, 0, $templet, 3);
							
						}
					} else if($bidAcceptAmt[$row['bidid']] < $acceptedAmt) {
						$txnAmt = round(($acceptedAmt - $bidAcceptAmt[$row['bidid']]),2) * -1;
						$ret=$database->setTransaction($row['lenderid'],$txnAmt,'Loan bid',$loanid, $CurrencyRate,LOAN_BID, 0, 0, UPDATE_BID, $row['bidid']);
						if(!$ret) {
							return 0;
						}
					}
				}
			}
		}
		return 1;
	}
	function editbid($loanid, $brwid,$bidid,$amount,$interest)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$biddetail=$database->getBidByBidid($bidid, $this->userid);
		if(empty($biddetail)) {
			return 0;
		}
		$lastbidamt=$biddetail['bidamount'];
		$amount=truncate_num($amount,2);
		$incramtAmt=round(($amount-$lastbidamt),2);
		$lastbidint=number_format($biddetail['bidint'], 2, ".", "");
		$decrementInt=round(($lastbidint-$interest),2);
		$availableAmt = $this->amountToUseForBid($this->userid);
		$damount=$database->getOpenLoanAmount($brwid, $loanid, false);
		$CurrencyRate = $database->getCurrentRate($brwid);
		if(empty($amount) || !is_numeric($amount)){
			$form->setError('pamount', $lang['error']['invalid_amount']);
		}
		else if($incramtAmt <0){
			$form->setError('pamount', $lang['error']['lower_newbidamt']." ".$lastbidamt);
		}
		else if($damount < $amount){
			$form->setError('pamount', $lang['error']['lower_bidamt']." USD ". $damount);
		}
		else if($availableAmt < $incramtAmt){
			$form->setError('pamount', $lang['error']['insuffi_amount']." USD ". $availableAmt);
		}
		if($interest < 0 || !is_numeric($interest)){
			$form->setError('pinterest', $lang['error']['invalid_bidint']);
		}
		else if($decrementInt < 0) {
			$form->setError('pinterest', $lang['error']['lower_newbidint']." ".$lastbidint."%");
		}
		if($form->num_errors > 0){
			return 3;
		}
		if(empty($incramtAmt) && empty($decrementInt)) {
			return 0;
		}
		$bids1=$database->getLoanBids($brwid, $loanid);
		$return1=array();
		if(!empty($bids1)) {
			$return1 = $this->setAcceptAmount($bids1, $damount);
			$bids1 = $return1['bids'];
		}
		$result=$database->editbid($bidid,$amount,$interest);
		Logger_Array("lender edit bid",'userid, bidid, lastbidamt, lastbidint, newbidamt, newbidint', $this->userid, $bidid, $lastbidamt, $lastbidint, $amount, $interest);
		if($result){
			return 0;
		}
		else{
			$bids=$database->getLoanBids($brwid, $loanid);
			if(!empty($bids)) {
				$bidAcceptAmt1=array();
				if(isset($return1['bidAcceptAmt'])) {
					$bidAcceptAmt1 = $return1['bidAcceptAmt'];
				}
				$return2 = $this->setAcceptAmount($bids, $damount);
				$bids = $return2['bids'];
				$this->bidOutbidDownBidTransaction($brwid, $loanid, $bids, $bidAcceptAmt1, $CurrencyRate);
				return 1;
			}
			return 0;
		}
	}
	function otherwithdraw($OtherCurr, $OtherBname,$OtherBAddress, $OtherCity,$OtherCountry, $OtherAno, $OtherName,$amount)
	{
		global $database, $form,$mailer1;
		global $database, $form,$mailer1;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$availAmt = $this->amountToUseForBid($this->userid);
		if(empty($amount)){
			$form->setError("amount", $lang['error']['empty_amount']);
		}
		else if(!eregi("^[0-9/.]", $amount)){
			$form->setError("amount", $lang['error']['invalid_amount']);
		}
		else
		{
			$availAmt = $this->amountToUseForBid($this->userid);
			if($amount>$availAmt)
				$form->setError("amount", $lang['error']['lower_amount']." (USD <b> ".number_format($availAmt, 2, ".", "")."</b>)");
		}
		if(empty($OtherBname)){
			$form->setError("OtherBname", $lang['error']['empty_bankname']);
		}
		if(empty($OtherBAddress)){
			$form->setError("OtherBAddress", $lang['error']['empty_address']);
		}
		if(empty($OtherCity)){
			$form->setError("OtherCity", $lang['error']['empty_bankcity']);
		}
		if(empty($OtherName)){
			$form->setError("OtherName", $lang['error']['empty_accountname']);
		}
		if(empty($OtherAno)==0){
			$form->setError("OtherAno", $lang['error']['empty_accountno']);
		}
		else if(!eregi("^[0-9/.]", $OtherAno) ){
			$form->setError("OtherAno", $lang['error']['invalid_accountno']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$result=$database->otherwithdraw($OtherCurr, $OtherBname,$OtherBAddress, $OtherCity,$OtherCountry, $OtherAno, $OtherName,$amount,$this->userid);
			$amount*= -1;
			$CurrencyRate=0.00;
			$ret=$database->setTransaction($this->userid,$amount,'Funds withdrawal from lender account',0, $CurrencyRate,FUND_WITHDRAW);

			if($result==0){//invalid AMOUNT
				return 2;
			}
			else
			{
				return 1;
			}
		}
	}
	function withdraw($amount, $paypalemail)
	{
		global $database, $form,$mailer1;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$availAmt = $this->amountToUseForBid($this->userid);
		$amount_org = $amount;
		if(empty($amount)){
			$form->setError("amount", $lang['error']['empty_amount']);
		}
		else if(!eregi("^[0-9/.]", $amount)){
			$form->setError("amount", $lang['error']['invalid_amount']);
		}
		else
		{
			$availAmt = $this->amountToUseForBid($this->userid);
			if($amount>$availAmt)
				$form->setError("amount", $lang['error']['lower_amount']." (USD <b> ".number_format($availAmt, 2, ".", "")."</b>)");
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$result=$database->withdraw($amount,$this->userid, $paypalemail);
			$amount*= -1;
			$CurrencyRate=0.00;
			$ret=$database->setTransaction($this->userid,$amount,'Funds withdrawal from lender account',0, $CurrencyRate,FUND_WITHDRAW);

			if($ret==0){//invalid AMOUNT
				return 2;
			}
			else
			{
				///send new mail to admin about the request
				$From=EMAIL_FROM_ADDR;
				$templet="editables/email/simplemail.html";
				require ("editables/mailtext.php");
				$Subject=$lang['mailtext']['withdraw-subject'];
				$To=$params['name'] = ADMIN_EMAIL_ADDR;
				$params['Amount'] = $amount_org;
				$message = $this->formMessage($lang['mailtext']['withdraw-msg'], $params);
				$reply=$this->mailSendingHtml($From, $To, ADMIN_EMAIL_ADDR, $Subject, '', $message, 0, $templet, 3);

				$res = $database->getEmail($this->userid);
				$Subject=$lang['mailtext']['withdraw_request_sub'];
				$To=$params['name'] = $res['name'];
				$params['date'] = date("M j, Y",time());
				$params['amount'] = $amount_org;
				$params['paypal_email'] = $paypalemail;
				$message = $this->formMessage($lang['mailtext']['withdraw_request_body_out'], $params);
				$lenderEmail = $res['email'];
				$reply=$this->mailSendingHtml($From, $To, $lenderEmail, $Subject, '', $message, 0, $templet, 3);

				return 1;
			}
		}
	}
	function PaySimplewithdraw($PaysimpleName, $PaysimpleAddress1,$PaysimpleAddress2, $PaysimpleCity,$PaysimpleState, $PaysimpleZip, $PaysimplePno,$amount)
	{
		global $database, $form,$mailer1;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$availAmt = $this->amountToUseForBid($this->userid);
		$amount_org = $amount;
		if(empty($amount)){
			$form->setError("PaysimpleAmt", $lang['error']['empty_amount']);
		}
		else if(!eregi("^[0-9/.]", $amount)){
			$form->setError("PaysimpleAmt", $lang['error']['invalid_amount']);
		}
		else
		{
			$availAmt = $this->amountToUseForBid($this->userid);
			if($amount>$availAmt)
				$form->setError("PaysimpleAmt", $lang['error']['lower_amount']." (USD <b> ".number_format($availAmt, 2, ".", "")."</b>)");
		}
		if(empty($PaysimplePno)){
			$form->setError("PaysimplePno", $lang['error']['empty_phone']);
		}
		else if(!eregi("^[0-9/.]", $PaysimplePno) ){
			$form->setError("PaysimplePno", $lang['error']['invalid_phone']);
		}
		if(empty($PaysimpleAddress1)){
			$form->setError("PaysimpleAddress1", $lang['error']['empty_address']);
		}
		if(empty($PaysimpleCity)){
			$form->setError("PaysimpleCity", $lang['error']['empty_city']);
		}
		if(empty($PaysimpleState)){
			$form->setError("PaysimpleState", $lang['error']['empty_state']);
		}
		if(empty($PaysimpleZip)){
			$form->setError("PaysimpleZip", $lang['error']['empty_zip']);
		}
		else if(!eregi("^[0-9/.]", $PaysimpleZip)){
			$form->setError("PaysimpleZip", $lang['error']['invalid_zip']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$result=$database->PaySimplewithdraw($PaysimpleName, $PaysimpleAddress1,$PaysimpleAddress2, $PaysimpleCity,$PaysimpleState, $PaysimpleZip, $PaysimplePno,$amount,$this->userid);

			$amount*= -1;
			$CurrencyRate=0.00;
			$result=$database->setTransaction($this->userid,$amount,'Funds withdrawal from lender account',0, $CurrencyRate, FUND_WITHDRAW);

			if($result==0){//invalid AMOUNT
				return 2;
			}
			else
			{
				$From=EMAIL_FROM_ADDR;
				$templet="editables/email/simplemail.html";
				require ("editables/mailtext.php");
				$Subject=$lang['mailtext']['withdraw-subject'];
				$To=$params['name'] = ADMIN_EMAIL_ADDR;
				$params['Amount'] = $amount_org;
				$message = $this->formMessage($lang['mailtext']['withdraw-msg'], $params);
				$reply=$this->mailSendingHtml($From, $To, ADMIN_EMAIL_ADDR, $Subject, '', $message, 0, $templet, 3);

				//mail sent to the lender
				$res = $database->getEmail($this->userid);
				$Subject=$lang['mailtext']['withdraw_request_sub'];
				$params['date'] = date('F d, Y',time());
				$params['amount'] = number_format($amount_org, 2, ".", "");
				$params['address1'] = $PaysimpleAddress1;
				$params['address2'] = $PaysimpleAddress2;
				$params['city'] = $PaysimpleCity;
				$params['state'] = $PaysimpleState;
				$params['zip'] = $PaysimpleZip;
				$message = $this->formMessage($lang['mailtext']['withdraw_request_body_us'], $params);
				$lenderEmail = $res['email'];
				$reply=$this->mailSendingHtml($From, '', $lenderEmail, $Subject, '', $message, 0, $templet, 3);

				return 1;
			}
		}
	}
	function payotherwithdrawadmin($amount,$id,$rowid)
	{
		global $database, $form,$mailer1;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$availAmt = $this->amountToUseForBid($id);
		if(empty($amount)){
			$form->setError("amount", $lang['error']['empty_amount']);
		}
		else if(!eregi("^[0-9/.]", $amount)){
			$form->setError("amount", $lang['error']['invalid_amount']);
		}
		else if($amount > $availAmt){
			$form->setError("amount", $lang['error']['lower_amount']." (USD ".number_format($availAmt, 2, ".", "").")");
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$result=$database->updateotherwithdraw($rowid);
			if($result==0){//invalid AMOUNT
				return 0;
			}
			else{///send new mail to user about the request of payment accepted
				return 1;
			}
		}
	}
	function paysimplewithdrawadmin($amount,$id,$rowid)
	{
		global $database, $form,$mailer1;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$availAmt = $this->amountToUseForBid($id);
		if(empty($amount)){
			$form->setError("amount", $lang['error']['empty_amount']);
		}
		else if(!eregi("^[0-9/.]", $amount)){
			$form->setError("amount", $lang['error']['invalid_amount']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$result=$database->updatepaysimplewithdraw($rowid);
			if($result==0){//invalid AMOUNT
				return 0;
			}
			else{///send new mail to user about the request of payment accepted
				return 1;
			}
		}
	}
	function paywithdraw($amount,$id,$rowid)
	{
		global $database, $form,$mailer1;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$availAmt = $this->amountToUseForBid($id);
		if(empty($amount)){
			$form->setError("amount", $lang['error']['empty_amount']);
		}
		else if(!eregi("^[0-9/.]", $amount)){
			$form->setError("amount", $lang['error']['invalid_amount']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		else
		{
			$amount*= -1;
			$CurrencyRate=0.00;
			$ret=1;// As the amount is already deducted $database->setTransaction($id,$amount,'Funds withdrawal from lender account',0, $CurrencyRate);
			if($ret==1)
				$result=$database->updatewithdraw($rowid);
			if($result==0){//invalid AMOUNT
				return 0;
			}
			else
			{   ///send new mail to user about the request of payment accepted
				$Detail=$database->getEmail($userid);
				$From=EMAIL_FROM_ADDR;
				$templet="editables/email/simplemail.html";
				require ("editables/mailtext.php");
				$Subject=$lang['mailtext']['paywithdraw-subject'];
				$To=$params['name'] = $Detail['name'];
				$params['Amount'] = $amount;
				$message = $this->formMessage($lang['mailtext']['paywithdraw-msg'], $params);
				$reply=$this->mailSendingHtml($From, $To, $Detail['email'], $Subject, '', $message, 0, $templet, 3);
				return 1;
			}
		}
	}
	function promotLoan($uid, $lid, $frnds_emails, $frnds_msg, $amt_req, $amt_need, $interest, $fbrating, $fbrating_count, $location, $borrower_fname, $borrower_lname, $loan_use, $loan_type)
	{
		global $database, $session,$form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if($this->userlevel == LENDER_LEVEL)
		{
			if(empty($frnds_emails))
			{
				$form->setError("emailError", $lang['error']['empty_emails']);
				return 0;
			}
			$email_ids =  explode(",",$frnds_emails);
			for($i=0; $i<count($email_ids); $i++)
			{
				if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", trim($email_ids[$i])))
				{
					$form->setError("emailError", $lang['error']['invalid_emails']);
					return 0;
				}
			}
			if($form->num_errors == 0)
			{
				return $this->sendPromotLoanMail($uid,$lid,$email_ids,$frnds_msg,$amt_req,$amt_need,$interest,$fbrating,$fbrating_count, $location,$borrower_fname,$borrower_lname,$loan_use,$loan_type);
			}
		}
		else if($this->userlevel == GUEST_LEVEL){
			$form->setError("logedinError", $lang['error']['login_sendmail']);
			return 0;
		}
		else
		{
			$form->setError("logedinError", $lang['error']['unautho_sendmail']);
			return 0;
		}
	}
	function get_contacts($email,$pass,$provider)
	{
		global $database, $session,$form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(!$provider)
		{
			$form->setError("emailError", $lang['error']['empty_provider']);
			return 0;
		}
		if(!$email || strlen($email=trim($email))<1)
		{
			$form->setError("emailError", $lang['error']['empty_email']);
			return 0;
		}
		if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", trim($email)))
		{
			$form->setError("emailError", $lang['error']['invalid_email']);
			return 0;
		}
		if(!$pass || strlen($pass)<1)
		{
			$form->setError("emailError", $lang['error']['empty_password']);
			return 0;
		}
		if($form->num_errors == 0)
		{
			include('inviter/openinviter.php');
			$inviter=new OpenInviter();
			$oi_services=$inviter->getPlugins();
			$inviter->startPlugin($provider);
			$internal=$inviter->getInternalError();
			if ($internal)
			{
				$form->setError("emailError", $lang['error']['error_occure']);
				return 0;
			}
			elseif (!$inviter->login($email,$pass))
			{
				$form->setError("emailError", $lang['error']['invalid_userpass']);
				return 0;
			}
			elseif (false===$contacts=$inviter->getMyContacts())
			{
				echo "Unable to get contacts ! Please try again";
				return 0;
			}
			$_SESSION['contacts']=$contacts;
			return 1;

		}
	}
	function invite_frnds($frnds_emails,$frnds_msg, $loanid,$userName,$userEmail,$email_subject, $invitemsg)
	{
		global $database, $session,$form,$validation;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(!$userName || strlen($userName=trim($userName))==0)
			$form->setError("user_name", $lang['error']['invite_user_name']);
				
		if($this->userlevel != LENDER_LEVEL) {
			$form->setError("loginError", $lang['error']['unautho_sendmail']);
		}

		if(empty($frnds_emails)){
				$form->setError("emailError", $lang['error']['empty_emails']);
			}
			if(!empty($frnds_emails)){
			$email_ids =  explode(",",$frnds_emails);
			if(!empty($email_ids)){
			for($i=0; $i<count($email_ids); $i++)
			{
				if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", trim($email_ids[$i])))
				{
					$form->setError("emailError", $lang['error']['invalid_emails']);
				}
			}
		}
	}
	if(!$userEmail || strlen($userEmail=trim($userEmail))==0)
		$form->setError("user_email", $lang['error']['invite_user_email']);
	else
		$validation->checkEmail($userEmail, "user_email");
	if(empty($invitemsg)) {
		$form->setError("invite_message", $lang['error']['empty_invite_msg']);
	}
	if(empty($email_subject)) {
		$form->setError("invite_subject", $lang['error']['empty_invitesub']);
	}
	if(empty($invitemsg)) {
		$form->setError("invite_message", $lang['error']['empty_invite_msg']);
	}
		if($form->num_errors >0){
			$_SESSION['frnds_emails']=$frnds_emails;
			$_SESSION['frnds_msg']=$frnds_msg;
			return 0;
		}
		else
		{
			$lender_name= (!empty($userName)) ? $userName : $this->fullname;
			$lender_email = (!empty($userEmail)) ? $userEmail : null;
			unset($_SESSION['frnds_emails']);
			unset($_SESSION['frnds_msg']);
			$id = $this->userid;
			if(empty($loanid)){
				$rep = $this->sentInviteMails($id,$frnds_emails,$frnds_msg,$lender_name ,$lender_email,$email_subject, $invitemsg);
				return $rep;
			}
			else
			{
				$loanDetail=$database->getLoanDetails($loanid);
				$totBid=$database->getTotalBid($loanDetail['borrowerid'],$loanDetail['loanid']);
				if($loanDetail['reqdamt'] > $totBid)
				{
					$stilneed=number_format(($loanDetail['reqdamt']-$totBid),2, '.', ',');
					$int = number_format(($database->getAvgBidInterest($loanDetail['borrowerid'],$loanDetail['loanid'])), 2, '.', ',');
				}
				else
				{
					$stilneed=0;
					$int = number_format(($loanDetail['interest'] - $loanDetail['WebFee']), 2, '.', ',');
				}
				$reqdamt= number_format($loanDetail['reqdamt'],2, '.', ',');
				$report=$database->loanReport($loanDetail['borrowerid']);
				$f=number_format($report['feedback']);
				$cf=$report['Totalfeedback'];
				$brw=$database->getBorrowerDetails($loanDetail['borrowerid']);
				$location=$brw['City'].', '.$database->mysetCountry($brw['Country']);
				if($loanDetail['tr_loanuse']==null || $loanDetail['tr_loanuse']=="")
					$loan_use=$loanDetail['loanuse'];
				else
					$loan_use=$loanDetail['tr_loanuse'];
				return $this->sendPromotLoanMail($loanDetail['borrowerid'], $loanDetail['loanid'], $email_ids, $frnds_msg, $reqdamt, $stilneed, $int, $f, $cf, $location, $brw['FirstName'], $brw['LastName'], $loan_use, $loanDetail['active'],$lender_name,$lender_email);
			}
		}
	}
	function giftCardOrder($order_type, $order_cost, $cards, $recipients, $tos, $froms, $msgs, $senders, $date)
	{
		global $database, $session;
		traceCalls(__METHOD__, __LINE__);
		$ip=$_SERVER['REMOTE_ADDR'];
		$userid = $this->userid;
		$total_cost =0;
		for($i=0; $i<count($order_cost); $i++)
		{
			$total_cost += $order_cost[$i];
		}
		$res = $database->setGiftTransaction($userid,$order_type, $order_cost, $total_cost, $cards, $recipients, $tos, $froms, $msgs, $senders, $date, $ip);
		if($res)
		{
			header("Location: index.php?p=27");
		}
		else
		{
			echo "There was some problem please try again <a href='microfinance/gift-cards.html'>click here</a>";
		}
	}
	function redeemCard($card_code, $id=0)
	{
		global $database, $session, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$res = $database->CheckGiftCardCode($card_code);
		if($res == 3){
			$form->setError("cardRedeemError", $lang['error']['dup_cardcode']);
			return $res;
		}
		else if($res ==2){
			$form->setError("cardRedeemError", $lang['error']['invalid_cardcode']);
			return $res;
		}
		else if($res ==0){
			$form->setError("cardRedeemError", $lang['error']['invalid_cardcode']);
			return $res;
		}
		else if($res ==1)
		{
			$result = $database->CheckGiftCardClaimed($card_code);
			if($result == 1){
				$form->setError("cardRedeemError", $lang['error']['redemed_cardcode']);
				return 0;
			}
			else
			{
				$exp_date = $database->getGiftCardExpireDate($card_code);
				$currentdate = time();
				if($exp_date < $currentdate){
					$exdate = date ( 'M j, Y', $exp_date);
					$form->setError("cardRedeemError", $lang['error']['expired_cardcode']." ".$exdate);
					return 0;
				}
				else
				{
					$amount = $database->GetGiftCardAmount($card_code);
					if($id == 0){
						$userid  = $this->userid;    /* this id is taken from session in case existing lender wants to redeem gift card  */
					}
					else
					{
						$userid  = $id;   /* this id is taken from register_l function in case new lender registration   */
						$_SESSION['giftRedeemAmt']=$amount;    /* this amount set in session in case new lender registration   */
					}
					$res1 = $database->setTransaction($userid, $amount,'Gift Card Redemption',0, 0, GIFT_REDEEM);
					$res2 = $database->setGiftCardClaimed($card_code, $userid);
					if($res1 == 0){
						$form->setError("cardRedeemError", $lang['error']['error_transaction']);
						return 0;
					}
					else
						return 1;
				}
			}
		}
	}
	public function registerEmail($email)
	{
		global $database, $form, $validation;
		$validation->checkEmail($email, "email");
		if($form->num_errors > 0)
			return 0;
		$result=$database->registerEmail($email);
		if($result==1)
		{
			$_SESSION['registerEmail']=1;
			return 1;
		}
		else
			return 0;
	}
	function forgiveShare($loan_id,$borrower_id, $userid)
	{	
		global $database,$session;
		if(isset($userid)){
			if(!$database->isLenderInThisLoan($loan_id,$userid))
			{
				$_SESSION['forgive']=2; //invalid loan id or lender has not funded for this loan 
				return 0;
			}
			
			if($database->isLenderForgivenThisLoan($loan_id,$userid))
			{
				$_SESSION['forgive']=3; //lender already forgiven this loan 
				return 0;
			}
			$totalLenderReceivedAmount=$database->getLenderReceivedAmountInThisLoan($loan_id,$userid);
			$totalLenderAmountWithInterest=$database->getLenderAmountWithInterestInThisLoan($loan_id,$userid);
			$forgiveAmount=$totalLenderAmountWithInterest-$totalLenderReceivedAmount;
			$exRate=$database->getCurrentRate($borrower_id);
			if($exRate > 0)
				$damount=$forgiveAmount / $exRate;
			else
				$damount=0;/* Exceptional case here we will store doller amount zero */
			$database->startDbTxn();
			$forgiven_loans_id=$database->forgiveShare($loan_id,$borrower_id,$userid,$forgiveAmount,$damount);
			if($forgiven_loans_id !=0)
			{
				$res2=$database->updateScheduleAfterForgive($loan_id,$borrower_id,$forgiveAmount,$forgiven_loans_id);
				if($res2)
				{
					$res3=$database->isAllLenderForgivenThisLoan($loan_id);
					$flag=1;
					if($res3)
					{
						$flag=0;
						$res4=$database->getTotalPayment($borrower_id, $loan_id);
						$remainingShare=$res4['amttotal']-$res4['paidtotal'];
						if($remainingShare > 0)
						{
							if($exRate > 0)
								$damount1=$remainingShare / $exRate;
							else
								$damount1=0;/* Exceptional case here we will store doller amount zero */

							$forgiven_loans_id=$database->forgiveShare($loan_id,$borrower_id,ADMIN_ID,$remainingShare,$damount1);
							if($forgiven_loans_id !=0)
							{
								$res5=$database->updateScheduleAfterForgive($loan_id,$borrower_id,$remainingShare,$forgiven_loans_id);
								if($res5)
								{
									$rest6=$database->loanpaidback($borrower_id,$loan_id);
									if($rest6)
										$flag=1;
								}
								else
								{
									$_SESSION['forgive']=7; /* some problem in updating as repaid loan */
								}
							}
							else
							{
								$_SESSION['forgive']=5; /* some problem in inserting record in forgive_loans table */
							}
						}
						else
							$flag=1;
					}
					if($flag==1)
					{
						$database->commitTxn();
						$this->sendForgiveMailToLender($userid,$borrower_id,$loan_id,$damount);
						$TotalPayment=$database->getTotalPayment($borrower_id,$loan_id);
						$repayAmount=$TotalPayment['amttotal']-$TotalPayment['paidtotal'];
						$schedule=$this->generateScheduleTable($borrower_id,$loan_id, 1);
						//notification email to borrower is commented.
						//$this->sendForgiveMailToBorrower($borrower_id,$loan_id,$repayAmount,$schedule['schedule']);
						$_SESSION['forgive']=1; /* done */
						/* check for referral program */
						$repayment=$database->getTotalPayment($borrower_id, $loan_id);
						$p= $repayment['paidtotal']/$repayment['amttotal']*100;
						$p= number_format($p);
						$this->checkReferralCommission($borrower_id, $p);
						return 1;
					}
					else
					{
						$_SESSION['forgive']=4; /* some problem in forgiving remaining share when all lenders have forgiven */
					}
				}
				else
				{
					$_SESSION['forgive']=6; /* some problem in updating repay schedule */
				}
			}
			else
			{
				$_SESSION['forgive']=5; /* some problem in inserting record in forgive_loans table */
			}
			$database->rollbackTxn();
			return 0;
		}
		else
		{
			$_SESSION['forgive']=8; /* some problem in inserting record in forgive_loans table */
		}
	}
	function forgiveDenied($loanid, $lenderid){
		global $database,$session;
		if(!$database->isLenderInThisLoan($loanid,$lenderid)) { 
				$_SESSION['forgive']=2; /* invalid loan id or lender has not funded for this loan */
				return 0;
		}else if($database->isLenderDeniedForgiveThisLoan($loanid,$lenderid)) { 
				$_SESSION['forgive']=9;/* lender already denied this loan */
				return 0;
		}
		elseif($database->isLenderForgivenThisLoan($loanid,$lenderid)) {
			$_SESSION['forgive']=3; /* lender already forgiven this loan */
			return 0;
		}
		else{
			$no= $database->lenderDenied($loanid, $lenderid);
			if($no===1){
				$_SESSION['loan_denied']=true;
				return 0;
			} 
	
		}
	}
function forgiveReminder(){
		global $database,$session;
		$loans= $database->getLoansForForgiveReminder();
		foreach($loans as $loan){
			$datediff= time()- $loan['time'];
			$datediff= $datediff/(60*60*24);
			$loanprurl = getLoanprofileUrl($loan['borrowerid'], $loan['loanid']);
			$datediff= intval($datediff/FORGIVE_REMINDER_DAYS);
			if($datediff >=1 && $loan['reminder_sent']<FORGIVE_REMINDER_PERIOD ){
				$lenders= $database->getLendersForForgive($loan['loanid'], $loan['lender_denied']);
				$borrowerName=$database->getNameById($loan['borrowerid']);
				$dateDisb=$database->getLoanDisburseDate($loan['loanid']);
				$balance=$database->getTotalPayment($loan['borrowerid'], $loan['loanid']); 
				$outstanding=$balance['amttotal']-$balance['paidtotal'];
				$From=EMAIL_FROM_ADDR;
				$fname='';
				$lname='';
				$bname='';
				$templet="editables/email/simplemail.html";
				require("editables/mailtext.php");
				$params['bname']=$borrowerName;					
				$Subject = $this->formMessage($lang['mailtext']['loan_forgiveness_subj'], $params);
				$params['date']=date('F j, Y',$dateDisb);
				$params['msg']=trim($loan['comment']);
				$params['link'] = SITE_URL.$loanprurl.'?v='.$loan['validation_code'];
				$currencyrate=$database->getCurrentRate($loan['borrowerid']);
				$outstanding=convertToDollar($outstanding, $currencyrate); 
				foreach($lenders as $lender){ 
					$params['lenderid']= $lender['userid'];
					$email=$lender['Email'];
					$params['name'] = trim($lender['FirstName']." ".$lender['LastName']);  
					$params['out_amnt'] = number_format($outstanding,2, '.', ',');
					$params['imgyes'] = SITE_URL."images/yes.png";
					$params['imgno'] = SITE_URL."images/no.png";
					$params['profile_link'] = SITE_URL.$loanprurl;
					$params['link1'] = SITE_URL.$loanprurl.'?v='.$loan['validation_code'].'&lid='.$params['lenderid']."&dntfrg=1";; 
					$message = $this->formMessage($lang['mailtext']['loan_forgiveness_body'], $params); 
					$reply=$this->mailSendingHtml($From, '', $email, $Subject, '', $message, 0, $templet, 3);
				}
				if(!empty($lenders)) {
					$loan['reminder_sent']++;
					$database->updateForgiveReminder($loan['reminder_sent'], $loan['loanid']);
				}
			}
			
		}
	}
	function generateScheduleTable($ud, $ld, $displyall=0, $disburseRate=0)
	{
		global $database;
		$path=  getEditablePath('loanstatn.php');
		include(FULL_PATH."editables/".$path);
		$schedule = $database->getSchedulefromDB($ud, $ld);
		$actualSchedule = $database->getRepaySchedulefromDB($ud, $ld);
		$gracePeriod = $database->gerGracePeriod($ld);
		$UserCurrency = $database->getUserCurrency($ud);
		if(!empty($disburseRate))
			$CurrencyRate=$disburseRate;
		else
			$CurrencyRate = $database->getCurrentRate($ud);
		$rtnArray=array();
		if(empty($schedule))
		{
			$rtnArray['schedule']='';
			$rtnArray['due']=0;
			$rtnArray['amtPaidTillShow']=0;
		}
		else
		{
			if($displyall)
			{
				$tmpcurr=$UserCurrency;
			}
			else
			{
				$tmpcurr='USD';
			}
			$text="<table width = 100% class='zebra-striped'>
			<tr>
				<th>".$lang['loanstatn']['date']."</th>
				<th>".$lang['loanstatn']['due_amount']."</th>
				<th>".$lang['loanstatn']['datepaid']."</th>
				<th>".$lang['loanstatn']['paid_amount']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style:none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['loanstatn']['paidAmt_tooltip']."</span><span class='bottom'></span></span></a></th>
			</tr>";
			$printSchedule=array();
			$paidBalance=0;
			$totalDueAmt=0;
			$totalDueAmtUsd=0;
			$totalPaidAmt=0;
			$totalPaidAmtUsd=0;
			$amtDueTill = 0;
			$amtPaidTill = 0;
			$amtDueTillUsd = 0;
			for($i = 0, $j=0; $i < count($schedule); $i++)
			{
				$totalDueAmt += $schedule[$i]['amount'];
				$printSchedule[$i]['dueAmt']=$schedule[$i]['amount'];
				$printSchedule[$i]['dueDate']=$schedule[$i]['duedate'];
				if($schedule[$i]['duedate'] < time())
				{
					$amtDueTill+=$schedule[$i]['amount'];
				}
				$inst=0;
				$inst=$schedule[$i]['amount'];
				while($paidBalance >0)
				{
					if($inst >0)
					{
						if($inst <= $paidBalance)
						{
							$printSchedule[$i]['sub'][][$actualSchedule[$j-1]['paiddate']]=$inst;
							$paidBalance=number_format(($paidBalance-$inst), 6, '.', '');
							$inst=0;
							break;
						}
						else
						{
							$printSchedule[$i]['sub'][][$actualSchedule[$j-1]['paiddate']]=$paidBalance;
							$inst = number_format(($inst - $paidBalance), 6, '.', '');
							$paidBalance=0;
						}
					}
					else
						break;
				}
				if($paidBalance==0)
				{	
					for($k=0; $j < count($actualSchedule); $j++)
					{	
						if($inst >0)
						{	
							if($inst <= $actualSchedule[$j]['paidamt'])
							{
								$printSchedule[$i]['sub'][][$actualSchedule[$j]['paiddate']]=$inst;
								/*Pranjal Change 26 Jan When there are 2 small payments on the same date the schedule does not show correct data Example loain id 206*/
								//$printSchedule[$i]['sub'][$actualSchedule[$j]['paiddate']]+=$inst;
								$paidBalance=number_format(($actualSchedule[$j]['paidamt']-$inst), 6, '.', '');
								$j++;
								break;
							}
							else
							{
								$printSchedule[$i]['sub'][][$actualSchedule[$j]['paiddate']]=$actualSchedule[$j]['paidamt'];
								$inst = number_format(($inst - $actualSchedule[$j]['paidamt']), 6, '.', '');
							}
						}
						else
						{
							break;
						}
					}
				}
				if($i==(count($schedule)-1) && $paidBalance > 0)
				{
					if(isset($printSchedule[$i]['sub'])) {
						$pos=count($printSchedule[$i]['sub'])-1;
						$printSchedule[$i]['sub'][$pos][$actualSchedule[count($actualSchedule)-1]['paiddate']] +=$paidBalance;
					} else {
						$printSchedule[$i]['sub'][0][$actualSchedule[count($actualSchedule)-1]['paiddate']]=$paidBalance;
					}
				}
			}
			$totalDueAmtUsd += convertToDollar($totalDueAmt ,($CurrencyRate));
			$amtDueTillUsd += convertToDollar($amtDueTill ,($CurrencyRate));
			for($i = 0; $i < count($printSchedule); $i++)
			{	
								
				if($i < $gracePeriod)
				{
					continue;
				}					
				$text= $text. "<tr> ";
				$text=$text."<td style='text-align:left; width:20%'>".date('M d, Y',$printSchedule[$i]['dueDate'])."</td>";
				if($displyall)
				{
					$text=$text."<td style='text-align:left; width:20%'>".number_format(round_local($printSchedule[$i]['dueAmt']), 0, '.', ',')."</td>";
				}
				else
				{
					$text=$text."<td style='text-align:left; width:20%'>".number_format(convertToDollar($printSchedule[$i]['dueAmt'] ,($CurrencyRate)), 2, '.', ',')."</td>";
				}
				if(isset($printSchedule[$i]['sub']))
				{
					$j=0;
					foreach($printSchedule[$i]['sub'] as $sub)
					{
						foreach($sub as $key=>$value)
						{
							$totalPaidAmt +=$value;
							if($key < time())
							{
								$amtPaidTill += $value;
							}
							if($j >0)
							{
								$text= $text. "<tr> ";
								$text=$text."<td style='text-align:left; width:20%'>&nbsp;</td>";
								$text=$text."<td style='text-align:left; width:20%'>&nbsp;</td>";
							}
							$text=$text."<td style='text-align:left; width:20%'>".date('M d, Y',$key)."</td>";
							if($displyall)
							{
								$amtPaidShow=number_format(round_local($value), 0, '.', ',');
								$text=$text."<td style='text-align:left; width:20%'>".$amtPaidShow."</td>";
							}
							else
							{
								$amtPaidShow=convertToDollar($value ,($CurrencyRate));
							}
							if(count($printSchedule[$i]['sub']) > 1)
							{
								if($j%2==0)
									$amtPaidShow= round_up($amtPaidShow,2);
								else
									$amtPaidShow= round_down($amtPaidShow,2);
							}
							if(!$displyall)
							{
								$text=$text."<td style='text-align:left; width:20%'>".number_format($amtPaidShow, 2, '.', ',')."</td>";
							}
							$text=$text." </tr>";
							if($j >0)
							{
								$text= $text. "</tr> ";
							}
							$j++;
						}
					}
				}
				else
				{
					$text=$text."<td style='text-align:left; width:20%'>&nbsp;</td>";
					$text=$text."<td style='text-align:left; width:20%'>&nbsp;</td>";
					 $text=$text." </tr>";
				}		
				
			}
			$totalPaidAmtUsd=convertToDollar($totalPaidAmt ,($CurrencyRate));
			$amtPaidTillUsd=convertToDollar($amtPaidTill ,($CurrencyRate));
			$text=$text."<tfoot>
					<tr>
					<th>".$lang['loanstatn']['tot_amount']."</th>";
					if($displyall)
					{
						$text=$text."<th>".number_format(round_local($totalDueAmt), 0, '.', ',')."</th>";
					}
					else
					{
						$text=$text."<th>".number_format($totalDueAmtUsd, 2, '.', ',')."</th>";
					}
					$text=$text."<th>".$lang['loanstatn']['tot_paid_amount']."</th>";
					if($displyall)
					{
						$text=$text."<th>".number_format(round_local($totalPaidAmt), 0, '.', ',')."</th>";
					}
					else
					{
						$text=$text."<th>".number_format($totalPaidAmtUsd, 2, '.', ',')."</th>";
					}
					$text=$text."</tr></tfoot>";
			$text=$text."</table>";
			$rtnArray['schedule']=$text;
			if($displyall)
			{
				$due=$amtDueTill-$amtPaidTill;
				$amtPaidTillShow=$amtPaidTill;
				$amtRemaining=$totalDueAmt- $totalPaidAmt;
			}
			else
			{
				$due=$amtDueTillUsd-$amtPaidTillUsd;
				$amtPaidTillShow=$amtPaidTillUsd;
				$amtRemaining=$totalDueAmtUsd- $totalPaidAmtUsd;
			}
			if($due < 0 )
				$due = 0;
			if($amtRemaining < 0 )
				$amtRemaining = 0;
			$rtnArray['amtPaidTillShow']=$amtPaidTillShow;
			$rtnArray['amtRemaining']=$amtRemaining;
			$rtnArray['due']=$due;
		}

		return $rtnArray;
	}
	function feedbackReminder()
	{

		global $database,$form;
		$loans=$database->getRepaidLoansForFeedbackReminder();
		foreach($loans as $loan)
		{
			$value= 7 * 24 * 60 *60;
			$reminder=intval($loan['datediff']/$value);
			if($loan['feedback_reminder'] < $reminder)
			{
				$lenders=$database->getLendersForFeedbackReminder($loan['loanid']);
				if(!empty($lenders))
				{
					$bname= $database->getNameById($loan['borrowerid']);
					$repay_date= $database->getRepaidDate($loan['borrowerid'], $loan['loanid']);
					$i=0;
					foreach($lenders as $lender)
					{
						$this->sendFeedbackReminderMailToLender($loan['loanid'], $lender['Email'], $loan['borrowerid'], $bname, $repay_date);
						$i++;
					}
					$database->updateFeedbackReminder($loan['loanid'], $reminder);
					Logger_Array("Feedback Reminder Mails",'Total mails sent, loanid', $i, $loan['loanid']);
				}
			}
		}
	}

	function subscribeLender($email,$fname,$lname)
	{
		require_once 'mail_chimp/MCAPI.class.php';
		require_once 'mail_chimp/config.inc.php'; //contains apikey
		$api = new MCAPI($apikey);
		$merge_vars = array('FNAME'=>$fname, 'LNAME'=>$lname);

		// By default this sends a confirmation email - you will not see new members
		// until the link contained in it is clicked!
		$retval = $api->listSubscribe($listId, $email, $merge_vars);
		if($api->errorCode){
			Logger_Array("Unable to load listSubscribe",'Error Code, Error Message', $api->errorCode, $api->errorMessage);
		} else {
			Logger_Array("Subscribed - look for the confirmation email",'retval',$retval);
		}
	}

	function unSubscribeLender($email)
	{
		require_once 'mail_chimp/MCAPI.class.php';
		require_once 'mail_chimp/config.inc.php'; //contains apikey
		$api = new MCAPI($apikey);
		$retval = $api->listUnsubscribe( $listId,$email);
		if ($api->errorCode)
		{
			Logger_Array("Unable to load listSubscribe",'Error Code, Error Message', $api->errorCode, $api->errorMessage);
		}
		else
		{
			Logger_Array("Unsubscribed",'retval',$retval);
		}
	}
	function sendShareEmail($to_email, $note, $uid, $lid, $email_sub, $loan_use, $sendme)
	{
		global $database, $session,$form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(empty($to_email))
		{
			$form->setError("to_email", $lang['error']['empty_emails']);
			return 0;
		}
		$email_ids =  explode(",",$to_email);
		for($i=0; $i<count($email_ids); $i++)
		{
			if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", trim($email_ids[$i])))
			{
				$form->setError("to_email", $lang['error']['invalid_emails']);
				return 0;
			}
		}
		if($form->num_errors == 0)
		{
			$this->sendShareMail($email_ids, $note, $uid, $lid, $email_sub, $loan_use, $sendme);
			return 1;
		}
	}
	function automaticLending($status, $priority,$interestRate,$interestRateOther, $MaxinterestRate,$MaxinterestRateOther, $confirm_criteria, $userid, $availableAmt)
	{		
		$interestRateOther=str_replace('%', '', $interestRateOther);
		$MaxinterestRateOther=str_replace('%', '', $MaxinterestRateOther);
		global $database, $session,$form;
		$availableAmt = $this->amountToUseForBid($userid);
		$path=  getEditablePath('error.php');
		if(empty($interestRateOther) && ($interestRateOther!=0 || $interestRateOther == '')) {
			$interestRateOther = str_replace('%', '', $interestRate);
		} 
		if(empty($MaxinterestRateOther) && ($MaxinterestRateOther!=0 || $MaxinterestRateOther == '')) {
			$MaxinterestRateOther = str_replace('%', '', $MaxinterestRate);
		}
		// if user entered like 0.1 should we treated as 10%
		if($interestRateOther >0 && $interestRateOther < 1) {
			$interestRateOther = $interestRateOther*100;
		}
		// if user entered like 0.1 should we treated as 10%
		if($MaxinterestRateOther >0 && $MaxinterestRateOther < 1) {
			$MaxinterestRateOther = $MaxinterestRateOther*100;
		}
		include_once("editables/".$path);
		if(empty($status) && $status!=0) {
			$form->setError("status", $lang['error']['empty_status']);
		}
		if(empty($priority)) {
			$form->setError("priority", $lang['error']['empty_priority']);
		}
		if(empty($interestRateOther) && ($interestRateOther!=0 || $interestRateOther == '')) {
				$form->setError("interest_rate", $lang['error']['empty_interest_rate']);
		}
		else if(!is_numeric($interestRateOther) ||  $interestRateOther < 0 ){
			$form->setError("interest_rate", $lang['error']['invalid_interest_rate']);
		}
		if(empty($MaxinterestRateOther) && ($MaxinterestRateOther!=0 || $MaxinterestRateOther == '')) {
				$form->setError("max_interest_rate", $lang['error']['empty_interest_rate']);
		}
		else if(!is_numeric($MaxinterestRateOther) ||  $MaxinterestRateOther < 0 ){
			$form->setError("max_interest_rate", $lang['error']['invalid_interest_rate']);
		}
		else if($MaxinterestRateOther < $interestRateOther ){
			$form->setError("max_interest_rate", $lang['error']['invalid_max_interest']);
		}
		if($form->num_errors > 0) {
			return 0;
		}
		$prevstatus=$database->getAutoLendingStatus($userid);
		$activated=$database->SetAutoLendingSetting($status, $priority,$interestRateOther, $MaxinterestRateOther,$confirm_criteria, $userid, $availableAmt);
		if($activated==1) {
				if($confirm_criteria) {
					$_SESSION['AutoLendCurrentCreditYes']=true;
				}
				if($prevstatus==$status){
					$_SESSION['StatusNotchanged']=1;
				}else if($status) {
					$_SESSION['AutoLendAcitavted']=true;
				}
			$_SESSION['auto_lend']=true;
			return 1;
		}
		return 0;
	}
	function ProcessAutoBidding()
	{	
	global $database;
		$GLOBALS['loanArray']=array();
		$fullyFundedAll = array();
		$lenders=$database->getAllLenderForAutoLend();	
		//Log number of ledners
		Logger_Array("AutoBid---LOG",'No Of lender',count($lenders));
		Logger_Array("AutoBid---LOG",'$lenders','id','lender_id','preference','desired_interest','max_desired_interest','current_allocated','lender_credit','created','bid_time','Active',$lenders);
		$GLOBALS['loanArray']=$database->getAllLoansForAutoLend();
	
		foreach($GLOBALS['loanArray'] as $key => $row) {
			$status = $this->getStatusBar($row['borrowerid'],$row['loanid'],5);
			if($status >=100) {
				unset($GLOBALS['loanArray'][$key]);
			}
		}
		if(!empty($GLOBALS['loanArray'])) {
			shuffle($GLOBALS['loanArray']);
		}
		
		if(!empty($lenders)){
			shuffle($lenders);
			foreach($lenders as $lender) {
			// Log lender id			
			Logger_Array("AutoBid---LOG",'lender ID',$lender['lender_id']);
				$possibleLoans=0;
				$loansToAutolend=array();
				$availAmount=$this->amountToUseForBid($lender['lender_id']);
				if ($availAmount >= AUTO_LEND_AMT) {
					if($lender['current_allocated']==0) {
						$amounToAutoLend=bcsub($availAmount, $lender['lender_credit'],2);
						if($amounToAutoLend >=AUTO_LEND_AMT) {
						$possibleLoans=floor($amounToAutoLend/AUTO_LEND_AMT);
						}
					} else {
					$possibleLoans=floor($availAmount/AUTO_LEND_AMT);
					}
					//Log $possibleLoans = $possibleLoans
					Logger_Array("AutoBid---LOG",'$possibleLoans =',$possibleLoans);
					if($possibleLoans) {
						$loansToAutolend = $database->getSortedLoanForAutoBid($lender['preference'] ,$GLOBALS['loanArray'], $lender['desired_interest'], $lender['max_desired_interest'], $fullyFundedAll);
						
						if($possibleLoans < count($loansToAutolend)) {
						$loansToAutolend=array_slice($loansToAutolend, 0, $possibleLoans);
						}
						//Log print_r(loansToAutolend)
						Logger_Array("AutoBid---LOG",'$loansToAutolend','loanid','reqdamt','interest','interest','WebFee','applydate','borrowerid','intOffer',$loansToAutolend);
						if(!empty($loansToAutolend)) {
						$fullyFundedAll = $this->placeAutobid($lender['preference'], $loansToAutolend, $possibleLoans, $lender['lender_id'], $lender['desired_interest'], $lender['max_desired_interest']);
						}
					}
				}
				
			}
		}
	}
	function placeAutobid($preference, $loansToAutolend, $possibleBids, $lenderId, $desiredInt, $MaxdesiredInt)
	{		
		global $database, $form;
		$processed=array();
		
		Logger_Array("AutoBid---LOG",'laon count line no 5813 ',count($loansToAutolend));
		$loans = $this->getLoansForBid($preference, $loansToAutolend, $processed);
		//Log number of loans - Pranjal
		Logger_Array("AutoBid---LOG",'No of Loans line no 5816',count($loans));
		$fullyFunded=array();
		if(!empty($loans)) {
			while(1) {
				if(count($loans)==1){
				//Log only one loan left
					Logger_Array("AutoBid---LOG",'Log only one loan left Line No','5815');
					if($possibleBids) {
						$totBid=$database->getTotalBid($loans[0]['borrowerid'],$loans[0]['loanid']);
						$reqdamt=$loans[0]['reqdamt'];
						$StillNeeded=bcsub($reqdamt, $totBid, 2);
						$amountcanBid=AUTO_LEND_AMT*$possibleBids;
						if($StillNeeded > 0) {
							if($StillNeeded >= $amountcanBid) {
								$amountTobid = $amountcanBid;
							}else if($StillNeeded < $amountcanBid){
								$amountTobid = $StillNeeded;
							}
							if($MaxdesiredInt < $loans[0]['intOffer']) {
								$intToPlaceBid = $MaxdesiredInt;
							}else {
								$intToPlaceBid = $loans[0]['intOffer'];
							}
							/* Added By Mohit 20-01-14 To get Last manully Bid Detail*/
							if($preference==6){										
								$status = $session->getStatusBar($loan['borrowerid'],$loan['loanid'],5);
								$lastBidDetail=$database->lastBidDetail();
								if(!empty($lastBidDetail)){
										$lastBidAmnt=$lastBidDetail['amnt'];
										$lastBidIntr=$lastBidDetail['intr'];

										if($desiredInt < $lastBidIntr && $lastBidIntr<$MaxdesiredInt){
											$intToPlaceBid=$lastBidIntr;
										}elseif ($desiredInt > $lastBidIntr){
											$intToPlaceBid=$desiredInt;
										}else{
											$intToPlaceBid=$MaxdesiredInt;
										}
										
										$biddedAmnt=($loan['reqdamt']*$status)/100;
										$reqAmnt=$loan['reqdamt']-$biddedAmnt;
										$amountTobid = min($lastBidAmnt, $reqAmnt, AUTO_LEND_AMT);
										
								}
							}/***** End here *****/
							$LoanbidId=$this->placebid($loans[0]['loanid'], $loans[0]['borrowerid'], $amountTobid, $intToPlaceBid, 1, true,$lenderId);
							if(is_array($LoanbidId)) {
									$database->addAutoLoanBid($LoanbidId['loanbid_id'], $lenderId, $loans[0]['borrowerid'], $loans[0]['loanid'], $amountTobid,$intToPlaceBid);
									$possibleBids-=$amountTobid/AUTO_LEND_AMT;
									$processed[]=$loans[0]['loanid'];
							} else {
									$processed[]=$loans[0]['loanid'];
									unset($loans[0]);
							}
						}
					}
				}
				$loans = $this->getLoansForBid($preference, $loansToAutolend, $processed);

				if(empty($loans)) {
					//Log no loans
					Logger_Array("AutoBid---LOG",'No Loan Line No','5854');
					break;
				}
				if(!$possibleBids) {
					//Log no possible bids
					Logger_Array("AutoBid---LOG",'No possible bids','5860');
					break;
				}
			
				if(count($loans) > 1){	
						foreach($loans as $key=>$loan) {
						
						if($possibleBids) {
						
							$status = $this->getStatusBar($loan['borrowerid'],$loan['loanid'],5);
							if($status >=100) {
								unset($loans[$key]);
								$processed[]=$loan['loanid'];
								$fullyFunded[] = $loan['loanid'];
							} else {
									if($MaxdesiredInt < $loan['intOffer']) {
										$intToPlaceBid = $MaxdesiredInt;
									}else{
										$intToPlaceBid = $loan['intOffer'];
									}
									
									/* Added By Mohit 20-01-14 To get Last manully Bid Detail*/
									if($preference==6){										
										$lastBidDetail=$database->lastBidDetail();
										if(!empty($lastBidDetail)){
											    $lastBidAmnt=$lastBidDetail['amnt'];
											    $lastBidIntr=$lastBidDetail['intr'];

												if($desiredInt < $lastBidIntr && $lastBidIntr<$MaxdesiredInt){
													$intToPlaceBid=$lastBidIntr;
												}elseif ($desiredInt > $lastBidIntr){
													$intToPlaceBid=$desiredInt;
												}else{
													$intToPlaceBid=$MaxdesiredInt;
												}
												
												$biddedAmnt=($loan['reqdamt']*$status)/100;
												$reqAmnt=$loan['reqdamt']-$biddedAmnt;
												$amntToLend = min($lastBidAmnt, $reqAmnt, AUTO_LEND_AMT);
										}else{
											$amntToLend=AUTO_LEND_AMT;
											}
									}else{
										 $amntToLend=AUTO_LEND_AMT;
									} /***** End here *****/		
									
									$LoanbidId=$this->placebid($loan['loanid'], $loan['borrowerid'], $amntToLend, $intToPlaceBid, 1, true,$lenderId);
									if(is_array($LoanbidId)) {
										$database->addAutoLoanBid($LoanbidId['loanbid_id'], $lenderId, $loan['borrowerid'], $loan['loanid'], $amntToLend,$intToPlaceBid);	
										Logger_Array("Entry in Autolend Table",'Loan BidID','LenderId','Loan id', 'BorrowerId','Amnt to lend','Intrest',$LoanbidId['loanbid_id'],$lenderId,$loan['loanid'],$loan['borrowerid'],$amntToLend,$intToPlaceBid);
										$possibleBids--;
									} else {
										unset($loans[$key]);
										$processed[]=$loan['loanid'];
									}
							}
						}
					}
				}
				if(!$possibleBids) {
					//Log possible bids 2
					Logger_Array("AutoBid---LOG",'No possible bids2','5926');
					break;
				}
				if(empty($loans)) {
					$loans = $this->getLoansForBid($preference, $loansToAutolend, $processed);
					if(empty($loans)) {
						// Log ending here
						Logger_Array("AutoBid---LOG",'Log Endign here Lien no','5933');
						break;
					}
				}
			}
		}
		//$fullyFunded
		Logger_Array("AutoBid---LOG",'fully funded loan ID line 5940',$fullyFunded);
		return $fullyFunded;
	}
	function getLoansForBid($preference, $loansToAutolend, $processed) {

		$sortedOn='random';
		if($preference==HIGH_OFFER_INTEREST) {
			$sortedOn='intOffer';
		} else if($preference==HIGH_FEEDBCK_RATING) {
			$sortedOn='feedback';
		} else if($preference==HIGH_NO_COMMENTS) {
			$sortedOn='totComment';
		} else if($preference==EXPIRE_SOON) {
			$sortedOn='applydate';
		}
		if($sortedOn=='random') {
			return $loansToAutolend;
		}
		$returnLoans=array();
		$firstElement=-1;
		foreach($loansToAutolend as $loan) {
			if(in_array($loan['loanid'], $processed)) {
				continue;
			}
			if($firstElement==-1) {
				$firstElement = $loan[$sortedOn];
			}
			if($preference==EXPIRE_SOON) {
				$firstdate=date('y-m-d', $firstElement);
				$apply_date=date('y-m-d',$loan[$sortedOn]);
				if($firstdate==$apply_date) {
					$returnLoans[]=$loan;
				}
			} else if($firstElement==$loan[$sortedOn]) {
				$returnLoans[]=$loan;
			}
		}
		shuffle($returnLoans);
		return $returnLoans;
	}
	function RemoveFromCart($cid)
	{
		global $database;
		traceCalls(__METHOD__, __LINE__);
		$return=$database->RemoveFromCart($cid);
		return $return;
	}
	function ProcessCart($userid, $donation = 0) {
		global $database, $session;
		$availableamount=$this->amountToUseForBid($userid);
		$BidsinCart = $database->getBidsFromCart($userid);
		Logger("bids in cart ".serialize($BidsinCart));
		$returnarr = array();
		$donation_details=$database->getDonationFromCart($userid);
		if($donation > 0 && $availableamount > $donation) {
			$donationamt = -1*$donation;
			$database->startDbTxn();
			$res = $database->setTransaction(ADMIN_ID,$donation,'Donation from lender',0,0,DONATION);
			if($res!=0) {
				$res1 = $database->setTransaction($userid,$donationamt,'Donation to Zidisha',0,0,DONATION);
				if($res1!=0) {
					$this->sendDonationMail($userid,$donation);
					$database->commitTxn();
				}else {
					$database->rollbackTxn();
				}
			}else {
				$database->rollbackTxn();
			}
		}
		foreach($donation_details as $donation_detail){
			$donation=$donation_detail['amount'];
			if($donation > 0 && $availableamount >= $donation) {
				$donationamt = -1*$donation;
				$database->startDbTxn();
				$res = $database->setTransaction(ADMIN_ID,$donation,'Donation from lender',0,0,DONATION);
				if($res!=0) {
					$res1 = $database->setTransaction($userid,$donationamt,'Donation to Zidisha',0,0,DONATION);
					if($res1!=0) {
						$database->updateCartStatus($donation_detail['id'], 'COMPLETED');
						$this->sendDonationMail($userid,$donation);
						$database->commitTxn();
						$_SESSION['donation_give']=$donation_detail['id'];
					}else {
						$database->rollbackTxn();
					}
				}else {
					$database->rollbackTxn();
				}
			}
		}
		if(!empty($BidsinCart)) {
			foreach($BidsinCart as $bid) {
				$loanstatus = $database->getUserLoanStatus($bid['borrowerid'], $bid['loanid']);
				if($loanstatus['active'] == LOAN_OPEN) {
					$LoanbidId = $this->placebid($bid['loanid'], $bid['borrowerid'], $bid['bidamt'], $bid['bidint'], 0, true, $userid,1);// last sent argument added so that we can check if the place bid function called by Processcart
					Logger("loanbid id after placebid in processcart \n".serialize($LoanbidId));
					if(is_array($LoanbidId)) {
						$database->updateCartStatus($bid['id'], 'COMPLETED');
						Logger("updating cart status COMPLETED \n");
						$lastCartbid  = $bid['loanid'];
						$lastCartbrwr = $bid['borrowerid'];
						$_SESSION['lender_bid_success1']=1;
						$_SESSION['lender_bid_success_amt']=$bid['bidamt'];
						$_SESSION['lender_bid_success_int']=$bid['bidint'];
						$returnarr['borrowerid'] = $bid['borrowerid'];
						$returnarr['loanid'] = $bid['loanid'];
					}
				}else {
					$database->updateCartStatus($bid['id'], 'EXPIRED');
				}
			}
			
			//$this->sendMixpanelEvent('lend');	
		}
		$GiftcardsinCart = $database->getGiftcardsFromCart($userid);
		$availamount=$this->amountToUseForBid($userid);
		foreach($GiftcardsinCart as $giftcard) {
		Logger("gift cards in carts \n",serialize($giftcard));
			if($availamount >= $giftcard['card_amount']) {
				$database->startDbTxn();
				$amount = $giftcard['card_amount']*-1;
				$txn_id_trans = $database->setTransaction($giftcard['userid'],$amount,'Gift Card Purchase',0,0,GIFT_PURCHAGE,1);
				if($txn_id_trans!=0) {
					sleep(1);
					$txn_id = $database->setTransaction(ADMIN_ID,$giftcard['card_amount'],'Gift Card Purchase',0,0,GIFT_PURCHAGE,1);
					if($txn_id !=0) {
						$res1 = $database->updateGiftTransactionCart($txn_id_trans, $giftcard['txn_id']);
						if($res1===1) {
							$database->updateCartStatus($giftcard['id'], 'COMPLETED');
							$this->sendGiftCardMailsToReciever($giftcard['txn_id']);
							$this->sendGiftCardMailsToSender($giftcard['txn_id']);
							Logger_Array("gift cards process completed in carts",$giftcard);
							$database->commitTxn();
							$_SESSION['gifcardids'][] = $giftcard['id'];
						}else {
							$database->rollbackTxn();
						}
					}else {
						$database->rollbackTxn();
					}
				}else {
					$database->rollbackTxn();
				}
			}else {
				break;
			}
		}
		return $returnarr;
	}
	function ProcessMyCart() {
		global $database, $session;
		$retarr =0;
		$remdonation = $_POST['paypal_donation'];
		$availableamount = truncate_num(round($this->amountToUseForBid($this->userid), 4),2);
		$totAmt = 0;
		$totAmt = $_POST['paypal_donation']+$_POST['paypal_transaction']+$_POST['AmtIncart'];
		if($totAmt > $availableamount) {
			$TotalpaymentOrg = bcsub($_POST['AmtIncart'], $availableamount, 2);
			if($TotalpaymentOrg <= 0) {
				$TotalpaymentOrg = 0;
				$donationamt = bcsub($_POST['paypal_donation'], $availableamount, 2);
				$remdonation = bcsub($totAmt, $availableamount, 2);
				$DonateAmtTocharge = bcsub($_POST['paypal_donation'], $remdonation, 2 );
				if($donationamt > 0 && $availableamount > $donation) {
					$AmtDonate = -1*$donationamt;
					$database->startDbTxn();
					$res = $database->setTransaction(ADMIN_ID,$donationamt,'Donation from lender',0,0,DONATION);
					if($res!=0) {
							$res1 = $database->setTransaction($userid,$AmtDonate,'Donation to Zidisha',0,0,DONATION);
						if($res1!=0) {
							$this->sendDonationMail($userid,$donationamt, $this->userid);
							$database->commitTxn();
						}else {
							$database->rollbackTxn();
						}
					}else {
						$database->rollbackTxn();
					}
				}
			}
			$_SESSION['paypal_amount'] = $TotalpaymentOrg;
			$_SESSION['paypal_donation'] = $remdonation;
			$_SESSION['donated_amt'] = $_POST['donated_amt'];
			if(isset($_POST['lending_cart_paypal'])) {
				$_SESSION['lending_cart_paypal'] = 1;
			}
			header("location:library/paypal/getMoney.php");
			exit;
		}else {
			$retarr = $this->ProcessCart($this->userid, $_POST['paypal_donation']);
		}
		return $retarr;
	}
	function createlendergroup($name, $website, $about_grp, $createdby, $grp_leader, $file) {
		global $database,$form;
		traceCalls(__METHOD__, __LINE__);
		include_once("editables/error.php");
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(!$name || strlen($name=trim($name))==0){
			$form->setError('group_name', $lang['error']['empty_grpname']);
		}
		if(!$about_grp || strlen($about_grp=trim($about_grp))==0){
			$form->setError('about_group', $lang['error']['empty_abt_grp']);
		}
		if($form->num_errors > 0) {
			return 0;
		}
		$grpId = $database->lendergroup($name, $website, $about_grp, $createdby, $grp_leader);
		if($grpId > 0) {
			if(is_uploaded_file($file['group_photo']['tmp_name'])) {
				$path_info = pathinfo($file['group_photo']['name']);
				$ext=$path_info['extension'];
				$group_photo = md5(mt_rand(0, 32).time()).".".$ext;
				$ismoved = move_uploaded_file($file['group_photo']['tmp_name'],USER_IMAGE_DIR.$group_photo);
				$database->setGroupImage($group_photo, $grpId);
			}
			$_SESSION['groupcreated'] = true;
			return $grpId;
		}
		return 0;
	}

//added by Julia 6-11-2013

	function createbgroup($name, $website, $about_grp, $member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10, $member_email10, $createdby, $grp_leader, $file) {
		global $database,$form;
		traceCalls(__METHOD__, __LINE__);
		include_once("editables/error.php");
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if(!$name || strlen($name=trim($name))==0){
			$form->setError('group_name', $lang['error']['empty_grpname']);
		}
		if(!$about_grp || strlen($about_grp=trim($about_grp))==0){
			$form->setError('about_group', $lang['error']['empty_abt_grp']);
		}
		if($form->num_errors > 0) {
			return 0;
		}
		$grpId = $database->bgroup($name, $website, $about_grp,  $member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10, $member_email10, $createdby, $grp_leader);
		if($grpId > 0) {
			if(is_uploaded_file($file['group_photo']['tmp_name'])) {
				$path_info = pathinfo($file['group_photo']['name']);
				$ext=$path_info['extension'];
				$group_photo = md5(mt_rand(0, 32).time()).".".$ext;
				$ismoved = move_uploaded_file($file['group_photo']['tmp_name'],USER_IMAGE_DIR.$group_photo);
				$database->setGroupImage($group_photo, $grpId);
			}
			$_SESSION['groupcreated'] = true;
			return $grpId;
		}
		return 0;
	}


	function joinLendingGroup($grpid, $userid)
	{
		global $database,$form;
		traceCalls(__METHOD__, __LINE__);
		$userlevel = $database->getUserLevelbyid($userid);
		if($userlevel==LENDER_LEVEL) {
			$Ismember = $database->IsmemberOfGroup($userid, $grpid);
			if(!$Ismember) {
				$return = $database->joinlendingGroup($grpid, $userid);
				if($return==DB_OK){
					$_SESSION['grp_joined']=true;
					return 1;
				}
			}else {
				return 2;
			}
		}else {
			return 3;
		}
		return 0;
	}
	function facebook_disconnect() {
		unset($_SESSION['fb_'.FB_APP_ID.'_code']);
		unset($_SESSION['fb_'.FB_APP_ID.'_access_token']);
		unset($_SESSION['fb_'.FB_APP_ID.'_user_id']);
		unset($_SESSION['FB_Detail']);
		unset($_SESSION['FB_Error']);
		unset($_SESSION['FB_Fail_Reason']);
	}
	function facebook_connect($borrowerid=null) {
		include('facebook/facebook.php');
		global $database, $form;
		include_once("editables/error.php");
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$fb_array=array();
		$fb= array();
		$facebook = new Facebook(array('appId'  => FB_APP_ID,'secret' => FB_APP_SECRET));
		$fbuser = $facebook->getUser(); 
		$minFbMonths= $database->getAdminSetting('MIN_FB_MONTHS');
		$minMonthsAgoDate=strtotime(date("Y-m-d H:i:s",time())." -$minFbMonths month"); 
		if($fbuser) {
			try {
				$fb_array['user_profile']= $facebook->api('/me');
				$id= $fb_array['user_profile']['username'];
				$friends = $facebook->api('/me/friends?fields=first_name,last_name,gender,birthday,location,relationship_status');
				$fb_array['user_friends'] = (isset($friends['data'])) ? $friends['data'] : array();
				$fb_array['permissions']= $facebook->api('/me/permissions');
				$posts=$facebook->api('/me/posts?limit=200&until='.$minMonthsAgoDate);
				$fb['posts']= (isset($posts['data'])) ? $posts['data'] : array();
				$k= count($fb['posts']);
				$fb_array['posts']= $fb['posts'][$k-1];
				$fb_array['user_profile']['accessToken']= $facebook->getAccessToken();
				$userid=0;
				if(!empty($this->userid)){
					$userid= $this->userid;
					$fb_array['logoutUrl']= $facebook->getLogoutUrl( array('next' => SITE_URL."index.php?p=13&fb_data=1&fb_disconnect=1"));			
				}else{
					$fb_array['logoutUrl']= $facebook->getLogoutUrl( array('next' => SITE_URL."index.php?p=1&sel=1&fb_data=1&fb_disconnect=1"));			
				}
				$fb_array['loginUrl']=''; 
                                if(empty($fb_array['permissions']['data'][0]['publish_stream']) || empty($fb_array['permissions']['data'][0]['read_stream'])){
					$fb_array['loginUrl'] = $facebook->getLoginUrl(array('canvas' => 1, 'fbconnect' => 0, 'display' => 'popup', 'scope'=> 'email,user_location,publish_stream,read_stream'));
					$fb_array['user_profile']='';
					$_SESSION['FB_Error']= $lang['error']['fb_permissions'];
				}else{  								
					$FB_ID_exist= $database->IsFacebookIdExist($fb_array['user_profile']['id'], $userid); 
					/* Added by Mohit 28-10-13 */			
					$ip=(isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : "";
					$IpExist=$database->IsIpExist($ip,$borrowerid);	
					if($IpExist > 0){
						$_SESSION['FB_Error']= $lang['error']['fb_ineligible'];
						$_SESSION['FB_Fail_Reason']= "This Facebook user is already endorse to the same borrrower with same IP address.";				
					}else if($FB_ID_exist>0){ 
						$existuser= $database->getExistFbUser($fb_array['user_profile']['id'], $userid);
						$fb_array['exist_user']= $existuser;
						$_SESSION['FB_Error']=$lang['error']['fb_already_connected'];
						$_SESSION['FB_Fail_Reason']= "This Facebook account is already connect with another Zidisha account.";
					}else{
						$minFbFrnds= $database->getAdminSetting('MIN_FB_FRNDS');
						if(!empty($fb_array) && (count($fb_array['user_friends']) >= $minFbFrnds) && (strtotime($fb_array['posts']['created_time']) < $minMonthsAgoDate) && !empty($fb_array['posts'])){ 
							$_SESSION['FB_Detail']= true;
							$_SESSION['FB_Error']=false;
							$_SESSION['FB_Fail_Reason']='All looks okay.';
						} elseif(count($fb_array['user_friends']) < $minFbFrnds) {
							Logger_Array("FB LOG - on session friend less",'fb_connect', $fb_array['user_profile']['id']);
							$_SESSION['FB_Error']= $lang['error']['fb_ineligible'];
							$_SESSION['FB_Fail_Reason']= "This Facebook account have less friends as required.";
						}else{
							Logger_Array("FB LOG - on session post less",'fb_connect', $fb_array['user_profile']['id']);
							$_SESSION['FB_Error']= $lang['error']['fb_ineligible'];
							$_SESSION['FB_Fail_Reason']= "This Facebook account does not have old post as required.";
						}
					}
				
				}
				if(empty($fb_array['posts'])){
					$posts=$facebook->api('/me/posts?limit=200');
					$fb['posts']= (isset($posts['data'])) ? $posts['data'] : array();
					$k= count($fb['posts']);
					$fb_array['posts']= $fb['posts'][$k-1];
				}
			} catch (FacebookApiException $e) {
				$fbuser = null;
			}
		}else {
			$fb_array['loginUrl'] = $facebook->getLoginUrl(array('canvas' => 1, 'fbconnect' => 0, 'display' => 'popup', 'scope'=> 'email,user_location,publish_stream,read_stream'));
			$fb_array['logoutUrl']='';		
		}
		return $fb_array;
	}
	function leavegroup($grpid, $userid) {
		global $database,$form;
		traceCalls(__METHOD__, __LINE__);
		$members = $database->getLendingGroupMembers($grpid);
		$grpleader='';
		if(count($members)==2) {
			foreach($members as $member) {
				if($member['member_id']!=$userid) {
					$grpleader = $member['member_id'];
				}
			}
		}
		if(isset($_POST['selectleader'])) {
			$grpleader = $_POST['selectleader'];
		}
		if(!empty($grpleader)) {
			$database->updategrpLeader($grpid, $grpleader);
		}
		$return = $database->leavegroup($grpid, $userid);
			if($return==DB_OK){
				$gname = $database->getLendingGroupnameByid($grpid);
				$_SESSION['grp_leaved_name'] = $gname;
				$_SESSION['grp_leaved']=true;
				return 1;
			}
		return 0;
	}
	  function updatelendergroup($gid,$name, $website, $about_grp, $createdby, $grp_leader, $file){
			global $database,$form;
			traceCalls(__METHOD__, __LINE__);
			include_once("editables/error.php");
			$path=  getEditablePath('error.php');
			include_once("editables/".$path);

			if(!$name || strlen($name=trim($name))==0){
				$form->setError('group_name', $lang['error']['empty_grpname']);
			}
			if(!$about_grp || strlen($about_grp=trim($about_grp))==0){
				$form->setError('about_group', $lang['error']['empty_abt_grp']);
			}
			if($form->num_errors > 0) {
				return 0;
			}
			$result= $database->updatelendergroup($gid, $name, $website, $about_grp, $createdby, $grp_leader);	
			if($result==1){
					if(is_uploaded_file($file['group_photo']['tmp_name'])) {
					$path_info = pathinfo($file['group_photo']['name']);
					$ext=$path_info['extension'];
					$group_photo = md5(mt_rand(0, 32).time()).".".$ext;
					$ismoved = move_uploaded_file($file['group_photo']['tmp_name'],USER_IMAGE_DIR.$group_photo);
					$database->setGroupImage($group_photo, $gid);
				}
				return $result;
	
			}
		}
         
               function updatebgroup($gid,$name, $website, $about_grp,$member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10,$member_email10, $createdby, $grp_leader, $file){
			global $database,$form;
			traceCalls(__METHOD__, __LINE__);
			include_once("editables/error.php");
			$path=  getEditablePath('error.php');
			include_once("editables/".$path);

			if(!$name || strlen($name=trim($name))==0){
				$form->setError('group_name', $lang['error']['empty_grpname']);
			}
			if(!$about_grp || strlen($about_grp=trim($about_grp))==0){
				$form->setError('about_group', $lang['error']['empty_abt_grp']);
			}
			if($form->num_errors > 0) {
				return 0;
			}
			$result= $database->updatebgroup($gid, $name, $website, $about_grp,$member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10,$member_email10, $createdby, $grp_leader);	
			if($result==1){
					if(is_uploaded_file($file['group_photo']['tmp_name'])) {
					$path_info = pathinfo($file['group_photo']['name']);
					$ext=$path_info['extension'];
					$group_photo = md5(mt_rand(0, 32).time()).".".$ext;
					$ismoved = move_uploaded_file($file['group_photo']['tmp_name'],USER_IMAGE_DIR.$group_photo);
					$database->setGroupImage($group_photo, $gid);
				}
				return $result;
	
			}
		}
                
	function transffer_leadership($grpid, $leaderid) {
		global $database,$form;
		traceCalls(__METHOD__, __LINE__);
		if(!empty($leaderid)) {
			$return = $database->updategrpLeader($grpid, $leaderid);
		}
		if($return==DB_OK){
			$gname = $database->getLendingGroupnameByid($grpid);
			$_SESSION['grp_leaved_name'] = $gname;
			$_SESSION['leadership_transffered']=true;
			return 1;
		}
		return 0;
	}
	function updateGrpmsgNotify($grpid, $value, $userid) {
		global $database,$form;
		traceCalls(__METHOD__, __LINE__);
			$return = $database->updateGrpmsgNotify($grpid, $value, $userid);
		if($return==DB_OK){
			return 1;
		}
		return 0;
	}

	function DeactivateAndDonate(){
		global $database;
		$lenders= $database->getExpiredLenderAccounts(LENDER_ACCOUNT_EXPIRE_DURATION);
		if(!empty($lenders)){
			foreach($lenders as $lender){
				if(!empty($lender['userid'])){
					$avl_amount= $this->amountToUseForBid($lender['userid']);
					$this->ConverToDonation($lender['userid'],$avl_amount);
				}
			}
		}
	}

	function DeactivateExpiredGiftCard(){
		global $database;
		$gift_cards= $database->getExpiredGiftCards();
		if(!empty($gift_cards)){
			foreach($gift_cards as $gift_card){
				$this->donate_card($gift_card['id'],$gift_card['card_code'],$gift_card['card_amount']);
			}
		}
	}

	function donate($donation_amt, $date){
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$ip=$_SERVER['REMOTE_ADDR'];
		$userid = $this->userid;
		include_once("editables/error.php");
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		if($donation_amt<1){
			$form->setError('donation', $lang['error']['invalid_donatio']);
			return 0;
		}else{
			$res = $database->setDonationTransaction($userid, $donation_amt, $date, $ip);
			if($res)
			{
				return 1;	
			}
			else
			{
				echo "There was some problem please try again <a href='microfinance/donate.html'>click here</a>";
			}
		}
	}
	/* -------------------Lender Section End----------------------- */


	/* -------------------Partner Section Start----------------------- */

	function register_p($username, $pass1, $pass2, $pname, $address, $city, $country, $email, $emails_notify, $website, $desc, $user_guess, &$id, $language)
	{
		global $database, $form,$validation;
		traceCalls(__METHOD__, __LINE__);
		$validation->validatePartnerReg($username, $pass1, $pass2, $pname, $address, $city, $country, $email, $emails_notify, $desc, $user_guess);

		if($form->num_errors > 0){
			return 0;
		}
		$result=$database->addPartner($username, $pass1, $pname, $address, $city, $country, $email, $emails_notify, $website, $desc, $language);
		$id = $database->getUserId($username);
		if(!empty($id)) {
			$database->IsUserinvited($id, $email); // check if the registered user invited by any other existing user and save it in invitees table for future tracking.
		}
		if($result==0)
		{
			return true;
		}
		return false;
	}
	function editprofile_p($username, $pass1, $pass2, $pname, $address, $city, $country, $email, $emails_notify, $website, $ppostcomment, $desc, $id, $language)
	{
		global $database, $form,$validation;
		traceCalls(__METHOD__, __LINE__);
		$validation->validatePartnerEdit($id, $username, $pass1, $pass2, $pname, $address, $city, $country, $email, $emails_notify, $desc);

		if($form->num_errors > 0){
			return 0;
		}

		$result=$database->updatePartner($username, $pass1, $pname, $address, $city, $country, $email, $emails_notify, $website, $ppostcomment, $desc,$id,$language);
		if($result==0){
			return true;
		}
		return false;
	}

	/* -------------------Partner Section End----------------------- */

	/* -------------------Mail section Start----------------------- */

	
	function mailSendingHtml($From, $To, $email, $Subject, $header, $message,$attachment,$templet,$html,$card_info)
	{
		require_once ("includes/mailsender.php");
		if($this->usersublevel !=READ_ONLY_LEVEL){
			$r = mailSender($From, $To, $email, $Subject, $header, $message,$attachment,$templet,$html,$card_info);
		}
		if(!empty($r))
			return 1;
		return 0;
	}
	function formMessage($msg, $params)
	{	
		foreach($params as $key => $value)
		{
			 $msg = str_replace( '%'.$key.'%', $value, $msg);
		}
		return $msg;
	}
	public function sendBulkMails($emailadd, $selected_radio, $emailmssg, $emailsubject)
	{
		global $database, $form;
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$field = "emailmessage";
		if(!$emailmssg || strlen($emailmssg=trim($emailmssg))==0){
			$form->setError($field, $lang['error']['empty_emailmsg']);
		}
		$field = "emailsubject";
		if(!$emailsubject || strlen($emailsubject=trim($emailsubject))==0){
			$form->setError($field, $lang['error']['empty_emailsub']);
		}
		$field = "emailaddress";
		if($selected_radio == 'Others' && (!$emailadd ||  strlen($emailadd=trim($emailadd))==0)){
			$form->setError($field, $lang['error']['empty_emailids']);
		}
		if($form->num_errors > 0){
			return 0;
		}
		$emailmssg=nl2br($emailmssg);
		if($selected_radio == 'Others')
		{
			$emailids=explode(",",$emailadd);
			foreach($emailids as $rows)
			{
				require ("editables/mailtext.php");
				$otheremail = $rows;
				$From       = EMAIL_FROM_ADDR;
				$templet    = "editables/email/simplemail.html";
				$Subject    = $emailsubjct;
				$To         = $otheremail;
				$reply      = $this->mailSendingHtml($From, $To, $otheremail, $emailsubject, '', $emailmssg, 0, $templet, 3);
			}
			return 1;
		}
		else if($selected_radio == 'Borrower')
		{
			$emailids=$database->getBorrowersEmail();
		}
		else if($selected_radio == 'Lender')
		{
			$emailids=$database->getLendersEmail();
		}
		else if($selected_radio == 'Partner')
		{
			$emailids=$database->getPartnersEmail();
		}
		else if($selected_radio == 'All')
		{
			$emailids=$database->getAllEmails();
		}
		foreach($emailids as $rows)
		{
			require ("editables/mailtext.php");
			$otheremail = $rows['Email'];
			$From       = EMAIL_FROM_ADDR;
			$templet    = "editables/email/simplemail.html";
			$Subject    = $emailsubjct;
			$To         = $otheremail;
			$reply      = $this->mailSendingHtml($From, $To, $otheremail, $emailsubject, '', $emailmssg, 0, $templet, 3);
		}
		return 1;
	}
	function sendPromotLoanMail($uid, $lid, $email_ids, $frnds_msg, $amt_req, $amt_need, $interest, $fbrating, $fbrating_count, $location, $borrower_fname, $borrower_lname, $loan_use, $loan_type,$lender_name=null,$lender_email=null)
	{
		global $database, $session,$form;
		$date=time();
		$ids=$database->saveInviteEmails($this->userid, $this->fullname, $email_ids, $date);
		$frnds_msg = nl2br(stripslashes(strip_tags(trim($frnds_msg))));
		require("editables/mailtext.php");
		if(!empty($lender_email))
			$From=$lender_email;
		else
			$From = EMAIL_FROM_ADDR;
		$emailsubject = $lang['mailtext']['promote_subject'];
		$templet="editables/email/promotemail.html";
		$To = "";
		$promote_info = array();
		$promote_info['user_msg'] = $frnds_msg;
		$promote_info['image_src'] = SITE_URL."library/getimagenew.php?id=$uid&width=300&height=300";
		if($loan_type==LOAN_OPEN)
			$promote_info['lend_image_src'] = SITE_URL."images/layout/border/lend-image.png";
		else
			$promote_info['lend_image_src'] = SITE_URL."images/layout/border/status-image.png";
		$promote_info['borrower_name'] = $borrower_fname." ".$borrower_lname;
		$promote_info['fbrating'] = $fbrating;
		$promote_info['fbrating_count'] = $fbrating_count;
		$promote_info['location'] = $location;
		$promote_info['amount_req'] = $amt_req;
		$promote_info['interest'] = $interest;
		$statusbar=$this->getStatusBar($uid,$lid,1);
		$promote_info['statusbar'] = $statusbar;
		$params = array();
		$params['name'] = $this->fullname;
		$params['stil_need_amt'] = $amt_need;
		$params['borrower_name'] = $borrower_fname;
		if(!empty($lender_name)){
			$param1['name']=$lender_name;
			$params['lender_name'] = $lender_name;
		}
		else{
			$param1['name'] = $this->fullname;
			$params['lender_name'] = $this->fullname;
		}
		$emailsubject = $this->formMessage($emailsubject, $param1);
		for($i=0; $i<count($email_ids); $i++)
		{	
			$loanprurl = getLoanprofileUrl($uid, $lid);
			if(strlen($loan_use) >200)
				$promote_info['loan_use'] = substr($loan_use,0,200).".... <a href='".SITE_URL.$loanprurl."?refid=$ids[$i]'>Read More</a>";
			else
				$promote_info['loan_use'] = $loan_use;
			$promote_info['image_link'] = SITE_URL.$loanprurl."?refid=$ids[$i]";
			$promote_info['site_link'] = SITE_URL.$loanprurl."?refid=$ids[$i]";
			$promote_info['borrower_link'] = SITE_URL.$loanprurl."?refid=$ids[$i]";
			$promote_info['fbrating_link'] = SITE_URL.$loanprurl."?refid=$ids[$i]";
			$promote_info['lend_link'] = SITE_URL.$loanprurl."?refid=$ids[$i]";
			$params['lender_reg_link'] = SITE_URL."index.php?p=1&sel=2&refid=$ids[$i]";
			$params['loan_prof_link'] = SITE_URL.$loanprurl."?refid=$ids[$i]";
			$params['zidisha_link'] = SITE_URL."index.php?refid=$ids[$i]";
			if($loan_type==LOAN_OPEN)
				$emailmssg = $this->formMessage($lang['mailtext']['promote_body1'], $params);
			else if($loan_type==LOAN_FUNDED || $loan_type==LOAN_ACTIVE)
				$emailmssg = $this->formMessage($lang['mailtext']['promote_body2'], $params);
			$emailmssg .= $this->formMessage($lang['mailtext']['promote_body3'], $params);

			/*  0 for no attachment, 2 for HTML mail */

			$reply=$this->mailSendingHtml($From, $To,$email_ids[$i], $emailsubject, '', $emailmssg,0,$templet,2,$promote_info);
		}
		return $reply;
	}
	function sentInviteMails($id,$frnds_emails,$frnds_msg,$lender_name ,$lender_email=null,$email_subject = null, $invitemsg = null)
	{
		global $database, $session,$form;
		traceCalls(__METHOD__, __LINE__);
		$frnds_msg = nl2br(stripslashes(strip_tags(trim($frnds_msg))));
		$invitemsg = nl2br(stripslashes(strip_tags(trim($invitemsg))));
		$email_ids =  explode(",",$frnds_emails);
		$date=time();
		$ids=$database->saveInviteEmails($id,$lender_name, $email_ids, $date);
		require("editables/mailtext.php");
		if(!empty($lender_email))
			$From=$lender_email;
		else
			$From=EMAIL_FROM_ADDR;
		$templet="editables/email/simplemail.html";
		$Subject=$lang['mailtext']['invite_subject'];
		$To = '';
		$params = array();
		$params['name'] = $lender_name;
		$params['user_msg']= $frnds_msg;
		$Subject = $this->formMessage($lang['mailtext']['invite_subject'], $params);
		if(!empty($email_subject) &&  !empty($invitemsg)) {
			$Subject = trim($email_subject);
			for($i=0; $i<count($email_ids); $i++) {
				$params['zidisha_link'] = SITE_URL."index.php?refid=$ids[$i]";
				$invite_link = $this->formMessage($lang['mailtext']['invite_link'], $params);
				$invitemessg = $invitemsg."<br/><br/>".$invite_link;
				$message = $this->formMessage($invitemessg, $params);
				$reply = $this->mailSendingHtml($From, $To, $email_ids[$i], $Subject, '', $message, 0, $templet, 3);
			}
		}
		else {
			for($i=0; $i<count($email_ids); $i++) {
				$params['zidisha_link']= SITE_URL."index.php?refid=$ids[$i]";
				$message = $this->formMessage($lang['mailtext']['invite_body'], $params);
				$reply=$this->mailSendingHtml($From, $To, $email_ids[$i], $Subject, '', $message, 0, $templet, 3);
			}
		}
		return $reply;
	}
	function sendGiftCardMailsToSender($order_id)
	{
		global $database, $session;
		date_default_timezone_set('America/New_York');
		traceCalls(__METHOD__, __LINE__);
		$order_detail = $database->GetOrderDetailSender($order_id);
		if(!empty($order_detail))
		{
			require("editables/mailtext.php");

			$From = EMAIL_FROM_ADDR;
			$emailsubject = $lang['mailtext']['gift_order_subject'];
			$emailmssg = $lang['mailtext']['gift_order_msg_header'];
			$templet="editables/email/hero.html";
			$To = '';
			$rec_email = '';
			foreach($order_detail as $row)
			{	
				
				if($row['sender'] != $rec_email && $rec_email != "")
				{	
					$emailmssg .= $lang['mailtext']['gift_order_msg_footer'];
					$reply=$this->mailSendingHtml($From, $To, $rec_email, $emailsubject, '', $emailmssg, 0, $templet, 3);
					$emailmssg = $lang['mailtext']['gift_order_msg_header'];
					
				}
				$params['card_link'] ="";
				if($row['order_type']=='email') {
					$delv_method = "Email";
				}
				if($row['order_type']=='print') {
					$delv_method = "Self-Print";
					$cardlink = SITE_URL."cardimage.php?id_no=".$row['txn_id']."&card_code=".$row['card_code'];
					$params['card_link'] ="<a href='$cardlink' target='_blank'>Print Gift Card</a>";
				}
				$params['date'] = date("M j, Y",$row['date'])." at ".date("h:i A",$row['date']);
				$params['amount'] = number_format($row['card_amount'], 2, '.', ',');
				$params['delv_method'] = $delv_method;
				$params['to_name'] =  $row['to_name'];
				$params['from_name'] =  $row['from_name'];
				$params['msg'] = $row['message'];
				$params['rec_email'] =  $row['recipient_email'];
				$params['date_sent'] =  date("M j, Y",$row['date'])." at ".date("h:i A",$row['date']);
				$emailmssg .= $this->formMessage($lang['mailtext']['gift_order_msg_body'], $params);
				if($row['order_type'] == 'email')
					$emailmssg .= $this->formMessage($lang['mailtext']['gift_order_msg_body_2'], $params);
				$rec_email = $row['sender'];
				
			}
			/* in following last two lines sending mail to last sender  */
			$emailmssg .= $lang['mailtext']['gift_order_msg_footer'];
			$reply=$this->mailSendingHtml($From, $To, $rec_email, $emailsubject, '', $emailmssg, 0, $templet, 3);
			if($reply)
				Logger_Array("Gift Card order mail sent to sender ",'email, To', $rec_email, $To);
		} else {
			Logger_Array("Gift Card order detail not found for sender",'orderid', $order_id);
		}
		return true;
	}
	function sendGiftCardMailsToReciever($order_id, $id=0)
	{
		global $database, $session;
		traceCalls(__METHOD__, __LINE__);
		$order_detail = $database->GetOrderDetailReciever($order_id);
		require ("editables/mailtext.php");
		if(!empty($order_detail))
		{
			foreach($order_detail as $row)
			{
				$flag=0;
				if($id ==0)
					$flag=1;
				else if($row['id']==$id)
				{
					$flag=1;
				}
				if($flag)
				{
					$card_info = array();
					$card_info['card_amount'] = number_format($row['card_amount']);
					$card_info['to_name'] = $row['to_name'];
					$card_info['from_name'] = $row['from_name'];
					$card_info['message'] = $row['message'];
					$card_info['card_code'] = $row['card_code'];
					$card_info['exp_date'] = date ( 'F j, Y', $row['exp_date']);
					$card_info['card_link'] = SITE_URL."cardimage.php?id_no=".$row['txn_id']."&card_code=".$row['card_code'];
					$From = EMAIL_FROM_ADDR;
					$emailsubject = $lang['mailtext']['gift_card_subject'];
					$templet="editables/email/giftmail.html";
					$To = "";
					$params['link_1'] = SITE_URL."index.php?p=1&sel=2";
					$params['link_2'] = SITE_URL."index.php?p=17";
					$emailmssg = $this->formMessage($lang['mailtext']['gift_card_msg_body'], $params);
																								/*  0 for no attachment, 1 for HTML mail */
					$reply=$this->mailSendingHtml($From, $To, $row['recipient_email'], $emailsubject, '', $emailmssg,0,$templet,1,$card_info);
					if($reply)
						Logger_Array("Gift Card mail sent",'email, To', $row['recipient_email'], $To);
				}
			}
		} else {
			Logger_Array("Gift Card order detail not found for reciever",'orderid', $order_id);
		}
	}
	public function sendFundUploadMail($userid,$amount)
	{
		global $database,$form;
		$Detail=$database->getEmail($userid);
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/hero.html";
		require("editables/mailtext.php");
		$Subject=$lang['mailtext']['lender_upload_amt_sub'];
		$params['amount'] = number_format(truncate_num($amount,2), 2, ".", "");
		$params['zidisha_link'] = SITE_URL;
		$message = $this->formMessage($lang['mailtext']['lender_upload_amt_body'], $params);
		$reply=$this->mailSendingHtml($From, '', $Detail['email'], $Subject, '', $message, 0, $templet, 3);
	}
	public function sendDonationMail($userid,$donation, $email=null, $name=null)
	{
		global $database,$form;
		if($name==null)
		{
			$Detail=$database->getEmail($userid);
			$email=$Detail['email'];
		}
		else
		{
			$To=$params['lname'] =$name;
		}
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/hero.html";
		require("editables/mailtext.php");
		$Subject=$lang['mailtext']['lender_donation_sub'];
		$params['date'] = date("M d, Y ",time());
		$params['donation_amt'] = number_format(truncate_num($donation,2), 2, ".", "");
		$params['zidisha_link'] = SITE_URL;
		$message = $this->formMessage($lang['mailtext']['lender_donation_body'], $params);
		$reply=$this->mailSendingHtml($From, '', $email, $Subject, '', $message, 0, $templet, 3);
		if($reply)
			Logger_Array("Send Donation Mail",'email, userid, name, donation', $email, $userid, $name,$donation);
		else
			Logger_Array("Sorry unable to send donation mail",'email, userid, name, donation', $email, $userid, $name,$donation);
	}
	public function sendDonationReminderMailToAdmin($donation)
	{
		global $database;
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$Subject=$lang['mailtext']['donation_information_sub'];
		$params['donation'] = number_format(truncate_num($donation,2), 2, ".", "");
		$message = $this->formMessage($lang['mailtext']['donation_information_body'], $params);
		$reply=$this->mailSendingHtml($From, 'Admin', ADMIN_EMAIL_ADDR, $Subject, '', $message, 0, $templet, 3);
	}
	public function sendMobileChangeMail($userid)
	{
		global $database,$form;
		$phone_log=$database->getTelephoneNoByUserId($userid);
		$table="<table border=1><tr><th>Sr.no.</th><th>Telephone</th><th>Date</th></tr>";
		$i=1;
		foreach($phone_log as $row)
		{
			$table .="<tr>";
			$table .="<td>".$i."</td>";
			$table .="<td>".$row['phoneno']."</td>";
			$table .="<td>".date("M d, Y ",$row['date'])."</td>";
			$table .="</tr>";
			$i++;
		}
		$table .="</table>";
		$Detail=$database->getEmailB($userid);
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$Subject=$lang['mailtext']['borrower_mobile_change_sub'];
		$To=$params['bname'] = $Detail['name'];
		$params['username'] = $this->username;
		$CountryDetail=$database->getUserCityCountry($userid);
		$params['country'] =$database->mysetCountry($CountryDetail['Country']);
		$params['data'] =$table;
		$message = $this->formMessage($lang['mailtext']['borrower_mobile_change_body'], $params);
		$reply=$this->mailSendingHtml($From, $To, EMAIL_TO_MAIL, $Subject, '', $message, 0, $templet, 3);
	}
	public function sendCommentMails($loanid, $userid, $comment, $cid)
	{
		global $database;
		$lender_email=$database->getLenderEmailByLoanid($loanid);
		$p_detail=$database->getBorrowerPartner($userid);
		$p_email='';
		if(!empty($p_detail['email']) && $p_detail['postcomment'] == 1)
		{
			$p_email=$p_detail['email'];

			$p_name=$p_detail['name'];
		}
		$loanprurl = getLoanprofileUrl($userid, $loanid);
		$res_BEmail=$database->getEmailB($userid);
		$b_email=$res_BEmail['email'];
		$b_name=$res_BEmail['name'];
		$From=EMAIL_FROM_ADDR;
		global $session;
		$imgs = $database->getCommentFile(0,$userid,$cid);
		
		$cmts = $database->getCommentFromId($cid);
                //mohit 25 Oct - incorrect dates being sent in the mail.		
		if(!empty($cmts)){
		
		require ("editables/mailtext.php");
		$p['bname'] = $b_name;
		$emailsubject=  $this->formMessage($lang['mailtext']['comment-subject'], $p);
		$msg = nl2br($comment);
		$imgtag = '';
		for($i = 0; $i < sizeof($imgs); $i++)
		{
			/*  $imgtag .= '<img src="' . 'https://www.zidisha.org/includes/image.php?imgid='.urlencode($imgs[$i]['uploadfile']) . '">';*/
			/*  $imgtag .="<img src='".SITE_URL."includes/image.php?imgid=".urlencode($imgs[$i]['uploadfile'])."'>";*/
			$imgtag .="<a target='_blank' href='".SITE_URL."images/uploadComment/".$imgs[$i]['uploadfile']."'><img src='".SITE_URL."images/uploadComment/".$imgs[$i]['uploadfile']."' width='100' style='border:none'></a>";
			$imgtag .="<br>";
		 }

		//MESSAGE TO ADMIN
		$To=ADMIN_FROM_NAME;
		$adminemail=ADMIN_EMAIL_ADDR;
		$params['name'] = 'Admin';
		$params['link'] = SITE_URL.$loanprurl ;
		$params['message'] = $msg;
		$params['images'] =  $imgtag;
		$params['date'] =  date("M d, Y ",$cmts['pub_date']);
		$params['mname'] = $database->getUserNameById($cmts['senderid']);
		$ulevel=$database->getUserLevel($params['mname']);
		if($ulevel==BORROWER_LEVEL)
			$params['mname']=$database->getNameById($cmts['senderid']);
		$emailmssg=$this->formMessage($lang['mailtext']['comment-msg'], $params);

		$reply=$this->mailSendingHtml($From,$To,$adminemail , $emailsubject, '', $emailmssg, 0, $templet, 3);

		//MESSAGE TO BORROWER
		if($b_email!='' && $cmts['senderid']!=$userid)
		{	

			$templet="editables/email/simplemail.html";
			$To=$b_name;
			$params['zidisha_link']= SITE_URL."index.php";
			$params['name'] = $To;
			$params['link'] = SITE_URL.$loanprurl;
			$emailmssg=$this->formMessage($lang['mailtext']['comment-msg_b'], $params);
			$borroweremail=$b_email;
			$bemailsubject=$lang['mailtext']['comment-subject_b'];
			$reply=$this->mailSendingHtml($From,$To,$borroweremail , $bemailsubject, '', $emailmssg, 0, $templet, 3);
		}
		
		//MESSAGE TO LENDER
		foreach($lender_email as $rows)
		{	
			if($cmts['senderid']!=$rows['userid']) {

				$templet="editables/email/hero.html";
				$lenderemail=$rows['Email'];
				$params['name'] = $To;
				$params['link'] = SITE_URL.$loanprurl ;
				$emailmssg=$this->formMessage($lang['mailtext']['comment-msg'], $params);
				if($lenderemail){
					$reply=$this->mailSendingHtml($From,'',$lenderemail , $emailsubject, '', $emailmssg, 0, $templet, 3);
				}
			}
		}
	   }	

	}
	public function sendTranslateCommentMails($cid, $comment)
	{
		global $database;
		$cmts = $database->getCommentFromId($cid);
		$loanDetail=$database->getLastloan($cmts['receiverid']);
		if(!empty($loanDetail))
		{
			$loanid=$loanDetail['loanid'];
			$userid=$cmts['receiverid'];
			$lender_email=$database->getLenderEmailByLoanid($loanid);
			$p_detail=$database->getBorrowerPartner($userid);
			$res_BEmail=$database->getEmailB($userid);
			$From=EMAIL_FROM_ADDR;

			$templet="editables/email/simplemail.html";
			require ("editables/mailtext.php");

			$To=ADMIN_FROM_NAME;
			$adminemail=ADMIN_EMAIL_ADDR;
			$params['bname'] = $res_BEmail['name'];
			$params['lname'] = 'Admin';
			$loanprurl = getLoanprofileUrl($userid, $loanid);
			$params['link'] = SITE_URL.$loanprurl ;
			$params['comment'] = nl2br($comment);
			$params['date'] =  date("F j, Y",$cmts['pub_date']);
			$params['sender'] = $database->getUserNameById($cmts['senderid']);
			$ulevel=$database->getUserLevel($params['mname']);
			if($ulevel==BORROWER_LEVEL)
				$params['sender']=$database->getNameById($cmts['senderid']);
			$emailsubject=  $this->formMessage($lang['mailtext']['translate_comment_lender_sub'], $params);
			$emailmssg=$this->formMessage($lang['mailtext']['translate_comment_lender_body'], $params);
			$reply=$this->mailSendingHtml($From,$To,$adminemail , $emailsubject, '', $emailmssg, 0, $templet, 3);

			//MESSAGE TO PARTNER
			if(!empty($p_detail['email']) && $p_detail['postcomment'] == 1)
			{
				$params['lname'] = $To= $p_detail['name'];
				$emailmssg=$this->formMessage($lang['mailtext']['translate_comment_lender_body'], $params);
				$reply=$this->mailSendingHtml($From,$To,$p_detail['email'] , $emailsubject, '', $emailmssg, 0, $templet, 3);
			}

			//MESSAGE TO LENDER
			foreach($lender_email as $rows)
			{
				$params['lname']= $To =$rows['FirstName'] .' ' . $rows['LastName'];
				$emailmssg=$this->formMessage($lang['mailtext']['translate_comment_lender_body'], $params);
				if($lenderemail)
					$reply=$this->mailSendingHtml($From,$To,$rows['Email'] , $emailsubject, '', $emailmssg, 0, $templet, 3);
			}
		}
	}
	public function sendForgiveMailToLender($userid,$borrower_id,$loan_id,$damount)
	{
		global $database,$form;
		$Detail=$database->getEmail($userid);
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/hero.html";
		require("editables/mailtext.php");
		$Subject=$lang['mailtext']['forgive_lender_sub'];
		$params['bname'] = $database->getNameById($borrower_id);
		$loanprurl = getLoanprofileUrl($borrower_id, $loan_id);
		$params['borrower_link'] = SITE_URL.$loanprurl;
		$params['amount'] = number_format($damount, 2, ".", "");
		$message = $this->formMessage($lang['mailtext']['forgive_lender_body'], $params);
		$reply=$this->mailSendingHtml($From, '', $Detail['email'], $Subject, '', $message, 0, $templet, 3);
	}
	public function sendForgiveMailToBorrower($borrower_id,$loan_id,$repayAmount,$schedule)
	{
		global $database,$form;
		$Detail=$database->getEmailB($borrower_id);
		$UserCurrency = $database->getUserCurrency($borrower_id);
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$Subject=$lang['mailtext']['forgive_borrower_sub'];
		$To=$params['bname'] = $Detail['name'];
		$loanprurl = getLoanprofileUrl($borrower_id, $loan_id);
		$params['borrower_link'] = SITE_URL.$loanprurl;
		$params['repay_amount'] = $UserCurrency." ".round($repayAmount);
		$params['repay_table'] = $schedule;
		$message = $this->formMessage($lang['mailtext']['forgive_borrower_body'], $params);
		$reply=$this->mailSendingHtml($From, $To, $Detail['email'], $Subject, '', $message, 0, $templet, 3);
	}


	public function sendDefaultedLoanMailToLender($lenderid,$borrower_name,$percent_repaid,$rqst_amt)
	{
		global $database,$form;
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$Detail=$database->getEmail($lenderid);
		$Subject=$lang['mailtext']['default_loan_lender_sub'];
		$params['bname'] = $borrower_name;
		$params['percent_repaid'] = $percent_repaid;
		$params['rqst_amt'] = number_format($rqst_amt, 0, '.', '');
		$message = $this->formMessage($lang['mailtext']['default_loan_lender_body'], $params);
		$reply=$this->mailSendingHtml($From, '', $Detail['email'], $Subject, '', $message, 0, $templet, 3);
	}


	public function sendNewLoanAppMailToLender($loan_id, $lemail, $lname, $borrower_id, $borrower_name, $repay_date)
	{
		global $database,$form;
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/hero.html";
		require("editables/mailtext.php");
		$params['bname'] = $borrower_name;
		$params['repay_date'] = date('F j, Y',$repay_date);
		$loanprurl = getLoanprofileUrl($borrower_id, $loan_id);
		$params['link'] = SITE_URL.$loanprurl;
		$Subject = $this->formMessage($lang['mailtext']['new_loan_app_lender_sub'], $params);
		$header  = $this->formMessage($lang['mailtext']['new_loan_app_lender_sub'], $params);
		$message = $this->formMessage($lang['mailtext']['new_loan_app_lender_body'], $params);
		$reply=$this->mailSendingHtml($From, '', $lemail, $Subject, $header, $message, 0, $templet, 3);
		Logger_Array("New Loan App Mail",'lender email, loanid, borrower id', $lemail, $loan_id, $borrower_id);
	}

	public function sendFeedbackReminderMailToLender($loan_id, $lemail, $borrower_id, $borrower_name, $repay_date)
	{
		global $database,$form;
		$templet="editables/email/hero.html";
		require ("editables/mailtext.php");
		$loanprurl = getLoanprofileUrl($borrowerid, $loanid);
		$params['link'] = SITE_URL.$loanprurl.'#e1' ;
		$params['bname'] = $b_name;
		$params['image_src'] = $database->getProfileImage($borrowerid);
		$Subject = $this->formMessage($lang['mailtext']['RepayFeedback-subject'], $params);
		$header = $this->formMessage($lang['mailtext']['RepayFeedback-msg1'], $params);
		$message = $this->formMessage($lang['mailtext']['RepayFeedback-msg2'], $params);
		$reply=$this->mailSendingHtml($From, '', $lemail, $Subject, $header, $message,0,$templet,3);
	}

	function newLoanApplication()
	{
		/* Function will use only from cron jobs */
		global $database;
		$res=$database->getEvents(NEW_LOAN_APPLICATION);
		foreach($res as $row)
		{
			$event_fields=explode(',', $row['event_fields']);
			$loanid=$event_fields[0];
			$oldLoanid=$event_fields[1];
			$userid=$event_fields[2];
			$bname= $database->getNameById($userid);
			$lenders= $database->getLendersEmailForLoanApp($oldLoanid);
			$repay_date= $database->getRepaidDate($userid, $oldLoanid);
			$i=0;
			foreach($lenders as $lender)
			{
				$this->sendNewLoanAppMailToLender($loanid, $lender['Email'], $lender['FirstName'].' '.$lender['LastName'], $userid, $bname, $repay_date);
				$i++;
			}
			Logger_Array("New Loan App Mails",'Total mails sent, loanid', $i, $loanid);
			$database->updateEvent($row['id']);
		}
	}
	public function sendWelcomeMailToBorrower($userid, $name, $email)
	{
		global $database;
		$language= $database->getPreferredLang($userid);
		$From=EMAIL_FROM_ADDR;
		require("editables/mailtext.php");
		$templet="editables/email/simplemail.html";
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);

		$Subject=$lang['mailtext']['BorrowerReg-subject'];
		$To=$params['name'] = $name;
		$message = $this->formMessage($lang['mailtext']['BorrowerReg-msg'], $params);
		$this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);
		$this->sendContactConfirmation($userid);
	}
	public function sendWelcomeMailToLender($email)
	{
		global $database;
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/hero.html";
		require("editables/mailtext.php");

		$Subject=$lang['mailtext']['LenderReg-subject'];
		$message = $lang['mailtext']['LenderReg-msg'];
		$reply=$this->mailSendingHtml($From, '', $email, $Subject, '', $message, 0, $templet, 3);

	}

	public function sendShareMail($email_ids, $note, $uid, $lid, $email_sub, $loan_use, $sendme)
	{
		global $database,$form;
		$templet="editables/email/hero.html";
		require("editables/mailtext.php");
		$From=EMAIL_FROM_ADDR;
		if(!empty($this->userid)) {
			$Detail=$database->getEmail($this->userid);
			$To = "";
			$params['note'] = (empty($note)) ? "" : nl2br($note)."<br/><br/>";
			$loanprurl = getLoanprofileUrl($uid, $lid);
			$params['zidisha_link']= SITE_URL."index.php";
			$imgtag .="<div style='border: 1px solid #DFDCDC; padding:5px'><img src='".SITE_URL."images/client/".$uid.".jpg' width='100' style='border:none'></div>";
			$params['user_img']= $imgtag;
			$params['loan_use']= $loan_use." <a style='color:#00AEEF' href='".SITE_URL.$loanprurl.">More</a>";
			$message = $this->formMessage($lang['mailtext']['share_email_body'], $params);
			foreach($email_ids as $email_id) {
				$reply_to=$Detail['email'];
				$Frm='"'. $Detail['name'] .'" <'. $Detail['email'] .'>';
				$reply=$this->mailSendingHtml($Frm, $To, $email_id, $email_sub, '', $message, 0, $templet, 3);
				}
			if($sendme) {
				$reply=$this->mailSendingHtml($From, $To, $Detail['email'], $email_sub, '', $message, 0, $templet, 3);
			}
		}
	}
	public function SendFullyFundedMail($brwid, $loanid, $applydate)
	{
		global $database,$form;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$From=EMAIL_FROM_ADDR;
		$Subject=$lang['mailtext']['LoanFunded-subject'];
		if(!empty($brwid)) {
			$deadline=$database->getAdminSetting('deadline');
			$expireTime= ($deadline * 3600 *24);
			$expirydate=$applydate+$expireTime;
			$Detail=$database->getEmailB($brwid );
			$bemail=$Detail['email'];
			$To = "";
			$params['expirydate']=date('F d, Y',  $expirydate);
			$params['bname'] = $Detail['name'];
			$loanprurl = getLoanprofileUrl($brwid, $loanid);
			$params['link']= SITE_URL.$loanprurl;
			$message = $this->formMessage($lang['mailtext']['LoanFunded-body'], $params);
			$reply=$this->mailSendingHtml($From, $To, $bemail, $Subject, '', $message, 0, $templet, 3);
		}
	}
	public function SendLoanConfirmMailToBorrower($brwid, $loanid)
	{
		global $database,$form;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$language = '';
		if(isset($_GET["language"])) {
			$language = $_GET["language"];
		}
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$From = SERVICE_EMAIL_ADDR;
		$reply_to = SERVICE_EMAIL_ADDR;
		$Subject=$lang['mailtext']['LoanPosted-subject'];
		if(!empty($brwid)) {
			$Detail=$database->getEmailB($brwid );
			$bemail=$Detail['email'];
			$To = "";
			$params['deadline']=$database->getAdminSetting('deadline');
			$params['bname'] = $Detail['name'];
			$params['editlink']= SITE_URL.'index.php?p=44';
			$params['loanappliclink']= SITE_URL.'index.php?p=44';
			$loanprurl = getLoanprofileUrl($brwid, $loanid);
			$params['link']= SITE_URL.$loanprurl;
			$message = $this->formMessage($lang['mailtext']['LoanPosted-body'], $params);
			$reply=$this->mailSendingHtml($From, $To, $bemail, $Subject, '', $message, 0, $templet, 3);
		}
	}
	public function SendExpiringLoanMailToBorrower()
	{
		global $database,$session, $form;
		$now=time();
		$deadlinedays=$database->getAdminSetting('deadline');
		$daysToDeduct=$deadlinedays-LOAN_EXPIRE_MAIL_DURATION;
		$timeTocompare = strtotime("- $daysToDeduct days");
		$loansToexpire=$database->getLoansToExpire($timeTocompare);
		$LoansTosend=array();
		foreach($loansToexpire as $loan) {
		$applydate = $loan['applydate'];
		$status = $this->getStatusBar($loan['borrowerid'],$loan['loanid'],5);
		if($now <= strtotime("+ $deadlinedays days", $applydate ) && $status < 100 ) {
				$LoansTosend[]=$loan;
			}

		}
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$From = SERVICE_EMAIL_ADDR;
		$reply_to = SERVICE_EMAIL_ADDR;
		$Subject=$lang['mailtext']['LoanExpiry-subject'];
		foreach($LoansTosend as $loanToexpire) {
			$expireTime = ($deadlinedays * 3600 *24);
			$expirydate = $loanToexpire['applydate']+$expireTime;
			$Detail = $database->getEmailB($loanToexpire['borrowerid']);
			$bemail = $Detail['email'];
			$To = "";
			$params['expirydate']=date('F d, Y',  $expirydate);
			$params['editlink']=SITE_URL.'index.php?p=13';
			$params['loanappliclink']= SITE_URL.'index.php?p=44';
			$params['bname'] = $Detail['name'];
			$message = $this->formMessage($lang['mailtext']['LoanExpiry-body'], $params);
			$reply=$this->mailSendingHtml($From, $To, $bemail, $Subject, '', $message, 0, $templet, 3);
			$database->setExpirymailSent($loanToexpire['loanid']);
		}
		
	}
	public function sendLoanExpiredMail($borrowerid,$loanid)
	{
		global $database,$session, $form;
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$From=SERVICE_EMAIL_ADDR;
		$reply_to = SERVICE_EMAIL_ADDR;
		$Subject=$lang['mailtext']['LoanExpired-subject'];
			$expireTime = ($deadlinedays * 3600 *24);
			$expirydate = $applydate+$expireTime;
			$Detail = $database->getEmailB($borrowerid);
			$bemail = $Detail['email'];
			$To = "";
			$params['expirydate']=date('F d, Y',  $expirydate);
			$params['loanapplicLink']=SITE_URL.'index.php?p=9&inst=1';
			$params['bname'] = $Detail['name'];
			$message = $this->formMessage($lang['mailtext']['LoanExpired-body'], $params);
			$reply=$this->mailSendingHtml($From, $To, $bemail, $Subject, '', $message, 0, $templet, 3);
	}
	public function sendAccountExpiredMail()
	{
		global $database,$session, $form;
		$lenders=$database->getExpiredLenderAccounts(ACCOUNT_EXPIRE_MAIL_DURATION);
		$templet="editables/email/simplemail.html";
		require("editables/mailtext.php");
		$From=SERVICE_EMAIL_ADDR;
		$reply_to = SERVICE_EMAIL_ADDR;
		$Subject=$lang['mailtext']['AccountExpired-subject'];
		$expireTime = strtotime("+ ".ACCOUNT_EXPIRE_DURATION." months");
		foreach($lenders as $lender) {
			$params['lender']=$lender['FirstName']." ".$lender['LastName'];
			$params['site_link']=SITE_URL;
			$lemail=$lender['email'];
			$To = "";
			$params['expired_date']=date('F d, Y',  $expireTime);
			$message = $this->formMessage($lang['mailtext']['AccountExpired-body'], $params);
			$reply=$this->mailSendingHtml($From, $To, $lemail, $Subject, '', $message, 0, $templet, 3);
			$database->setAccountExpiredMailSent($lender['userid']);
		}
	}
	public function sendGrpmsgPostMailtoLenders($grpid, $comment, $cid)
	{
		
		global $database, $session;
		$lenders=$database->getlenderforgrppostemail($grpid);
		$lending_group = $database->getlendingGrouops($grpid);
		
		$From=EMAIL_FROM_ADDR;
		$templet="editables/email/hero.html";
		$imgs = $database->getGrpCommentFile(0, $grpid,$cid);
		$cmts = $database->getgrpCommentFromId($cid);
		$cmntbyname = $database->getUserNameById($cmts['senderid']);
		require ("editables/mailtext.php");
		$params['gname'] = $lending_group['name'];
		$params['cmntbyname'] = $cmntbyname;
		$emailsubject = $this->formMessage($lang['mailtext']['grpcomment-subject'], $params);
		$msg = nl2br($comment);
		$imgtag = '';
		for($i = 0; $i < sizeof($imgs); $i++)
		{
			/*  $imgtag .= '<img src="' . 'https://www.zidisha.org/includes/image.php?imgid='.urlencode($imgs[$i]['uploadfile']) . '">';*/
			/*  $imgtag .="<img src='".SITE_URL."includes/image.php?imgid=".urlencode($imgs[$i]['uploadfile'])."'>";*/
			$imgtag .="<a target='_blank' href='".SITE_URL."images/uploadComment/".$imgs[$i]['uploadfile']."'><img src='".SITE_URL."images/uploadComment/".$imgs[$i]['uploadfile']."' width='100' style='border:none'></a>";
			$imgtag .="<br>";
		 }

		$params['date'] =  date("M d, Y ",$cmts['pub_date']);
		$params['message'] = $comment;
		$params['images'] =  $imgtag;
		foreach($lenders as $rows)
		{	
			
			if($cmts['senderid']!=$rows['userid']) {
				$lenderemail=$rows['Email'];
				$params['lname'] = $To;
				$params['link'] = WEBSITE_ADDRESS.'?p=82&gid='.$grpid;
				$emailmssg=$this->formMessage($lang['mailtext']['grpcomment-msg'], $params);
				if($lenderemail){
					$reply=$this->mailSendingHtml($From,'',$lenderemail , $emailsubject, '', $emailmssg, 0, $templet, 3);
				}
			}
		}

	}
	function SendConfirmaEmailAgain($borrowerid){
		global $database, $session;
		$From=EMAIL_FROM_ADDR;
		$language= $database->getPreferredLang($borrowerid);
		$templet="editables/email/simplemail.html";
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$Subject=$lang['mailtext']['email_verification_sub'];
		$bdetail=$database->getEmailB($borrowerid);
		$To=$params['name'] = trim($bdetail['name']);
		$email = $bdetail['email'];
		$activate_key = $database->getActivationKey($borrowerid);
		$link = SITE_URL."index.php?p=51&ident=$borrowerid&activate=$activate_key";
		$params['verify_link'] = $link;
		$message = $this->formMessage($lang['mailtext']['email_verification_body'], $params);
		$reply = $this->mailSendingHtml($From, $To, $email, $Subject, '', $message, 0, $templet, 3);
		$_SESSION['bEmailVerifiedsentagain']=true;
		return 1;
	}
	function sentBInviteMail($id,$frnd_email,$borrower_name ,$borrower_email,$invite_subject, $invite_message){
		global $database, $session,$form;
		traceCalls(__METHOD__, __LINE__);
		$invite_message = nl2br(stripslashes(strip_tags(trim($invite_message))));
		$date=time();
		$ids=$database->saveBInviteEmail($id,$borrower_name, $frnd_email, $date);
		$language= $database->getPreferredLang($id);
		require("editables/mailtext.php");		
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		if(!empty($borrower_email))
			$From=$borrower_email;
		else
			$From=EMAIL_FROM_ADDR;
		$templet="editables/email/simplemail.html";
		$Subject=trim($invite_subject);
		$To = '';
		$params = array();
		$params['zidisha_link'] = SITE_URL."index.php?p=47&nr=1&refid=$ids";
		$invite_link = $this->formMessage($lang['mailtext']['binvite_link'], $params);
		$invite_message = $invite_message."<br/><br/>".$invite_link;
		$message = $this->formMessage($invite_message, $params);
		$reply = $this->mailSendingHtml($From, $To, $frnd_email, $Subject, '', $message, 0, $templet, 3);
		return $reply;
	}
	function resendEndorsermail($id){
		global $database, $form; 
		$e_details= $database->getEndorserForResendMail($id);
		$validation_code= $e_details['validation_code'];
		$brwr_detail=$database->getUserById($e_details['borrowerid']);
		$From=$brwr_detail['email'];
		$reg_link = SITE_URL."index.php?p=93&vd=$validation_code";
		$params['reg_link']= $reg_link;
		require("editables/mailtext.php");
		$templet="editables/email/simplemail.html";
		$path=  getEditablePath('mailtext.php',$this->userinfo['lang']);
		require ("editables/".$path);
		$e_email= $e_details['e_email'];
		$Subject=$brwr_detail['name']." ".$lang['mailtext']['borrowerEndorser-subject'];
		$To=$params['name'] = $e_details['ename'] ;
		$params['bname']= $brwr_detail['name'];
		$replyTo = SERVICE_EMAIL_ADDR;
		$message = $this->formMessage($lang['mailtext']['BorrowerEndorser-msg'], $params);
		$reply= $this->mailSendingHtml($From, $To, $e_email, $Subject, '', $message, 0, $templet, 3); 
		if($reply)
			return $To;
		else
			return 0;
	}

	function SendRepaymentReminderMails(){
		global $database, $form;
		$borrowers= $database->getBorrowersForRepayReminder();
		$templet="editables/email/simplemail.html";
		$From= SERVICE_EMAIL_ADDR;
		$replyTo = SERVICE_EMAIL_ADDR;
		foreach($borrowers as $borrower){
			$userid=$borrower['userid'];
			$check_reminder= $database->checkForReminder($userid);
			if($check_reminder){
				require("editables/mailtext.php");
				$language= $database->getPreferredLang($userid);
				$path=  getEditablePath('mailtext.php',$language);
				require ("editables/".$path);
				$Subject=$lang['mailtext']['breminder_again_sub'];
				$currency=$database->getUserCurrency($userid);
				$country= $database->getCountryCodeById($userid);
				$telnumber= $database->getPrevMobile($userid);
				$to_number = $this->FormatNumber($telnumber, $country);
				$repay_inst= $database->getRepayment_InstructionsByCountryCode($country);
				$repay_detail= $database->getDueRepaymentDetail($borrower['loanid']);
				$bdetail=$database->getEmailB($userid);
				$params['bname']=$bdetail['name'];
				$params['repay_inst']=$repay_inst['description'];
				$params['currency']= $currency;
				$email=$bdetail['email'];
				$To=$bdetail['name'];
				if($repay_detail['duedate']==$borrower['duedate']){
					if($repay_detail['paidamt']>0 && $repay_detail['paidamt']<$repay_detail['amount']){
						$params['duedate']= date('F d, Y', $repay_detail['duedate']);
						$params['paidamt']= round($repay_detail['paidamt']);
						$params['netdueamt']=round($repay_detail['amount']-$repay_detail['paidamt']);
						$params['dueamt']=$params['netdueamt'];
						$bmsg_advance= $this->formMessage($lang['mailtext']['breminder_advance'], $params);
						$content = $this->formMessage($lang['mailtext']['breminder_sms'], $params);
						$reply= $this->mailSendingHtml($From, $To, $email, $Subject, '', $bmsg_advance, 0, $templet, 3); 
						$sendsms=$this->SendSMS($content, $to_number);
						if($sendsms)
							$database->updateRepaymentReminder($userid, 'repayment_reminder');
					}else{
						$params['duedate']= date('F d, Y', $repay_detail['duedate']);
						$params['dueamt']= round($repay_detail['amount']);
						$bmsg= $this->formMessage($lang['mailtext']['breminder'], $params);
						$content = $this->formMessage($lang['mailtext']['breminder_sms'], $params);$reply= $this->mailSendingHtml($From, $To, $email, $Subject, '', $bmsg,$templet, 0, $replyTo, 3); 
						$sendsms=$this->SendSMS($content, $to_number);
						if($sendsms)
							$database->updateRepaymentReminder($userid, 'repayment_reminder');
					}
				}
				elseif($repay_detail['duedate']<$borrower['duedate']){
					$dueamtdetails= $database->getAlldueAmtToday($borrower['duedate'], $borrower['loanid']);
					$amount= 0;
					$paidamount=0;
					foreach($dueamtdetails as $dueamtdetail){
						$amount+= $dueamtdetail['amount'];
						$paidamount+=$dueamtdetail['paidamt'];
					}
					$netdueamt= $amount- $paidamount;
					$params['netdueamt']=round($netdueamt);
					$params['dueamt']=$params['netdueamt'];
					$params['past_duedate']= date('F d, Y', $repay_detail['duedate']);
					$params['next_duedate']=date('F d, Y', $borrower['duedate']);
					$params['duedate']=$params['next_duedate'];
					$params['current_dueamt']= round($borrower['amount']);
					$params['past_dueamt']= round($netdueamt-$borrower['amount']);
					$bmsg_pastdue= $this->formMessage($lang['mailtext']['breminder_pastdue'], $params);
					$content = $this->formMessage($lang['mailtext']['breminder_sms'], $params);
					$reply= $this->mailSendingHtml($From, $To, $email, $Subject, '', $bmsg_pastdue, 0, $templet, 3);
					$sendsms=$this->SendSMS($content, $to_number);
					if($sendsms)
							$database->updateRepaymentReminder($userid, 'repayment_reminder');
	
				}
			}
		} 
	}
	
	function sendagainRepaymentReminder(){
		global $database, $form;
		$borrowers= $database->getBorrowersForRepayReminder(true);
		$templet="editables/email/simplemail.html";
		$From= SERVICE_EMAIL_ADDR;
		$replyTo = SERVICE_EMAIL_ADDR;
		foreach($borrowers as $borrower){
			$userid=$borrower['userid'];
			$loanid=$borrower['loanid'];
			$check_reminder= $database->checkForReminder($userid, true);
			$isforgive=$database->loanAlreadyInForgiveness($loanid);
			if($check_reminder && $isforgive<1){
				require("editables/mailtext.php");
				$language= $database->getPreferredLang($userid);
				$path=  getEditablePath('mailtext.php',$language);
				require ("editables/".$path);
				$Subject=$lang['mailtext']['breminder_again_sub'];
				$currency=$database->getUserCurrency($userid);
				$country= $database->getCountryCodeById($userid);
				$repay_inst= $database->getRepayment_InstructionsByCountryCode($country);
				$repay_detail= $database->getDueRepaymentDetail($borrower['loanid']);
				$bdetail=$database->getEmailB($userid);
				$params['bname']=$bdetail['name'];
				$params['repay_inst']=$repay_inst['description'];
				$params['currency']= $currency;
				$email=$bdetail['email'];
				$To=$bdetail['name'];
				$dueamtdetails= $database->getAlldueAmtToday($borrower['duedate'], $borrower['loanid']);
				$amount= 0;
				$paidamount=0;
				foreach($dueamtdetails as $dueamtdetail){
					$amount+= $dueamtdetail['amount'];
					$paidamount+=$dueamtdetail['paidamt'];
				}
				$netdueamt= $amount- $paidamount;
				$params['netdueamt']=round($netdueamt);
				$params['duedate']=date('F d, Y', $repay_detail['duedate']);
				$bmsg= $this->formMessage($lang['mailtext']['breminder_again'], $params);
				$reply= $this->mailSendingHtml($From, $To, $email, $Subject, '', $bmsg, 0, $templet, 3);
				if($reply)
						$database->updateRepaymentReminder($userid, 'repayment_reminder');
			}
		}
		
	}

	function sendLoanFirstArrearMail(){
		global $database, $form;
		$borrowers= $database->getLoanArrearBorrowers(FIRST_LOANARREAR_REMINDER);
		$templet="editables/email/simplemail.html";
		$From= SERVICE_EMAIL_ADDR;
		$replyTo = SERVICE_EMAIL_ADDR;
		foreach($borrowers as $borrower){
			$userid=$borrower['userid'];
			$check_reminder= $database->checkLateRepaymentReminder(FIRST_LOANARREAR_REMINDER, $userid);
			if($check_reminder){
				$loanid=$borrower['loanid'];
				$repay_detail= $database->getDueRepaymentDetail($loanid);
				if($repay_detail['duedate']==$borrower['duedate']){
					$installment_detail= $database->isAllInstallmentOnTime($userid, $loanid);
					if($installment_detail['missedInst']<1){
						require("editables/mailtext.php");
						$language= $database->getPreferredLang($userid);
						$path=  getEditablePath('mailtext.php',$language);
						require ("editables/".$path);
						$Subject=$lang['mailtext']['loanarrear_reminder_first_sub'];
						$currency=$database->getUserCurrency($userid);
						$country= $database->getCountryCodeById($userid);
						$repay_inst= $database->getRepayment_InstructionsByCountryCode($country);
						$bdetail=$database->getEmailB($userid);						
						$telnumber= $database->getPrevMobile($userid);
						$to_number = $this->FormatNumber($telnumber, $country);
						$params['bname']=$bdetail['name'];
						$params['repay_inst']=$repay_inst['description'];
						$params['currency']= $currency;
						$params['due_amt']= round($repay_detail['amount']-$repay_detail['paidamt']);
						$params['duedate']= date('F d, Y',$repay_detail['duedate']);
						$email=$bdetail['email'];
						$To=$bdetail['name'];
						$bmsg= $this->formMessage($lang['mailtext']['loanarrear_reminder_first'], $params);
						$content = $this->formMessage($lang['mailtext']['loanarrear_remindersms'], $params);
						$reply= $this->mailSendingHtml($From, $To, $email, $Subject, '', $bmsg, 0, $templet, 3);
						$sendsms=$this->SendSMS($content, $to_number);
						if($sendsms)
							$database->updateRepaymentReminder($userid, 'late_repayment_reminder');
					}
				}
			}
		}
	}
	function sendLoanFinalArrearMail(){
		global $database, $form;
		$borrowers= $database->getLoanArrearBorrowers(FINAL_LOANARREAR_REMINDER);
		$templet="editables/email/simplemail.html";
		$From= SERVICE_EMAIL_ADDR;
		$replyTo = SERVICE_EMAIL_ADDR;
		foreach($borrowers as $borrower){
			$userid=$borrower['userid'];
			$check_reminder= $database->checkLateRepaymentReminder(FINAL_LOANARREAR_REMINDER, $userid);
			if($check_reminder){ 
				$loanid=$borrower['loanid'];
				$repay_detail= $database->getDueRepaymentDetail($loanid);
				if($repay_detail['duedate']==$borrower['duedate']){
					$installment_detail= $database->isAllInstallmentOnTime($userid, $loanid);
					if($installment_detail['missedInst']<2){
						$bdetail=$database->getBorrowerDetails($userid);
						require("editables/mailtext.php");
						$language= $database->getPreferredLang($userid);
						$path=  getEditablePath('mailtext.php',$language);
						require ("editables/".$path);
						$Subject=$lang['mailtext']['loanarrear_reminder_final_sub'];
						$currency=$bdetail['currency'];
						$country= $bdetail['Country'];
						$telnumber= $bdetail['TelMobile'];
						$to_number = $this->FormatNumber($telnumber, $country);
						$repay_inst= $database->getRepayment_InstructionsByCountryCode($country);
						$params['bname']=$bdetail['FirstName']." ".$bdetail['LastName'];
						$params['repay_inst']=$repay_inst['description'];
						$params['currency']= $database->getUserCurrency($userid);
						$params['due_amt']= round($repay_detail['amount']-$repay_detail['paidamt']);
						$params['duedate']= date('F d, Y',$repay_detail['duedate']);
						$email=$bdetail['Email'];
						$To=$bdetail['FirstName']." ".$bdetail['LastName'];
						$params['contacts']='';
						if(!empty($bdetail['family_member1'])){
							$params['contacts'].="\n".$bdetail['family_member1']."\n".$bdetail['family_member2']."\n".$bdetail['family_member3']."\n".$bdetail['neighbor1']."\n".$bdetail['neighbor2']."\n".$bdetail['neighbor3'];
						}
						if(!empty($bdetail['rec_form_offcr_name'])){
							$params['contacts'].="\n".$bdetail['rec_form_offcr_name']." ".$bdetail['rec_form_offcr_num'];
						}
						if(!empty($bdetail['refer_member_name'])){
							$refer_name=$database->getNameById($bdetail['refer_member_name']);
							$refer_number=$database->getPrevMobile($bdetail['refer_member_name']);
							$params['contacts'].="\n".$refer_name." ".$refer_number;
						}
						if(!empty($bdetail['mentor_id'])){
							$volunteer_name=$database->getNameById($bdetail['mentor_id']);
							$volunteer_number=$database->getPrevMobile($bdetail['mentor_id']);
							$params['contacts'].="\n".$volunteer_name;
							if(!empty($volunteer_number)){
								$params['contacts'].=" ".$volunteer_number;
							}
						}
						$invitee= $database->getInvitee($userid);
						if(!empty($invitee)){
							$invitee_name=$database->getNameById($invitee);
							$invitee_number=$database->getPrevMobile($invitee);
							$params['contacts'].="\n".$invitee_name." ".$invitee_number;
						}
						$endorsers=$database->getEndorserRecived($userid);
						if(!empty($endorsers)){
							foreach($endorsers as $endorser){
								$endorser_name=$database->getNameById($endorser['endorserid']);
								$endorser_number=$database->getPrevMobile($endorser['endorserid']);
								$params['contacts'].="\n".$endorser_name." ".$endorser_number;
							}
						}
						if(!empty($bdetail['fb_data'])){
							$fb_data= unserialize(base64_decode($fb_detail['fb_data']));
							if(isset($fb_data['user_friends']['data'])){
								$friends= count($fb_data['user_friends']['data']);
							}else{
								$friends=count($fb_data['user_friends']);
							}
							$params['contacts'].="\n".$friends." friends linked to ".$fb_data['user_profile']['name']." Facebook profile";
						}
						$bmsg= $this->formMessage($lang['mailtext']['loanarrear_reminder_final'], $params);
						$content= $this->formMessage($lang['mailtext']['loanarrear_remindersms_final'], $params);
						$reply= $this->mailSendingHtml($From, $To, $email, $Subject, '', $bmsg, 0, $templet, 3);
						$sendsms= $this->SendSMS($content, $to_number);
						if($sendsms)
							$database->updateRepaymentReminder($userid, 'late_repayment_reminder');
					}
				}
			}
		}
	}
	function SendSMS($content, $to_number){
		$api_key = TELEREVERT_API_KEY;
		$project_id = PROJECTID;
		$phone_id = PHONE_ID;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 
			"https://api.telerivet.com/v1/projects/$project_id/messages/outgoing");
		curl_setopt($curl, CURLOPT_USERPWD, "{$api_key}:");  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
			'content' => $content,
			'phone_id' => $phone_id,
			'to_number' => $to_number,
		), '', '&'));        
		
		// if you get SSL errors, download SSL certs from https://telerivet.com/_media/cacert.pem .
		// curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");    
		
		$json = curl_exec($curl);    
		$network_error = curl_error($curl);    
		curl_close($curl);    
			
		if ($network_error) { 
			return false; // do something with the error message
		} else {
			$res = json_decode($json, true);
			
			if (isset($res['error'])) {
				// API error
				return false;
				// do something with the response
			} else {            
				// success!
				return true;// do something with the response
			}
		}
	}
	function sendMonthlyLoanArrearMail(){
		global $database, $form;
		$borrowers= $database->getLoanArrearBorrowers(MONTHLY_LOANARREAR_REMINDER);
		$templet="editables/email/simplemail.html";
		$From= SERVICE_EMAIL_ADDR;
		$replyTo = SERVICE_EMAIL_ADDR;
		foreach($borrowers as $borrower){
			$userid=$borrower['userid'];
			$loanid=$borrower['loanid'];
			$check_reminder= $database->checkLateRepaymentReminder(MONTHLY_LOANARREAR_REMINDER, $userid);
			$isforgive=$database->loanAlreadyInForgiveness($loanid);
			$loanstatus=$database->getBorrowerCurrentLoanStatus($userid);
			if($check_reminder && $isforgive<1){
				$bdetail=$database->getBorrowerDetails($userid);
				require("editables/mailtext.php");
				$language= $database->getPreferredLang($userid);
				$path=  getEditablePath('mailtext.php',$language);
				require ("editables/".$path);
				$Subject=$lang['mailtext']['loanarrear_reminder_monthly_sub'];
				$telnumber= $bdetail['TelMobile'];
				$country=$bdetail['Country'];
				$repay_inst= $database->getRepayment_InstructionsByCountryCode($country);
				$to_number = $this->FormatNumber($telnumber, $country);
				$params['bname']=$bdetail['FirstName']." ".$bdetail['LastName'];						$params['repay_inst']=$repay_inst['description'];
				$email=$bdetail['Email'];
				$To=$bdetail['FirstName']." ".$bdetail['LastName'];
				$params['contacts']='';
			
				if(!empty($bdetail['family_member1'])){
					$params['contacts'].="\n".$bdetail['family_member1']."\n".$bdetail['family_member2']."\n".$bdetail['family_member3']."\n".$bdetail['neighbor1']."\n".$bdetail['neighbor2']."\n".$bdetail['neighbor3'];
					if(!empty($bdetail['family_member1']))
						$this->sendMailToOtherMediation($bdetail['family_member1'], $country, $loanid, $telnumber, $language, $userid);
					if(!empty($bdetail['family_member2']))
						$this->sendMailToOtherMediation($bdetail['family_member2'], $country, $loanid, $telnumber, $language, $userid);
					if(!empty($bdetail['family_member3']))
						$this->sendMailToOtherMediation($bdetail['family_member3'], $country, $loanid, $telnumber, $language, $userid);
					if(!empty($bdetail['neighbor1']))
						$this->sendMailToOtherMediation($bdetail['neighbor1'], $country, $loanid, $telnumber, $language, $userid);
					if(!empty($bdetail['neighbor2']))
						$this->sendMailToOtherMediation($bdetail['neighbor2'], $country, $loanid, $telnumber, $language, $userid);
					if(!empty($bdetail['neighbor3']))
						$this->sendMailToOtherMediation($bdetail['neighbor3'], $country, $loanid, $telnumber, $language, $userid);
				}
				if(!empty($bdetail['rec_form_offcr_name'])){
					$params['contacts'].="\n".$bdetail['rec_form_offcr_name']." ".$bdetail['rec_form_offcr_num'];
					$this->sendMailToOtherMediation($bdetail['rec_form_offcr_name']." ".$bdetail['rec_form_offcr_num'], $country, $loanid, $telnumber, $language, $userid);
				}
				if(!empty($bdetail['refer_member_name'])){
					$refer_name=$database->getNameById($bdetail['refer_member_name']);
					$refer_number=$database->getPrevMobile($bdetail['refer_member_name']);
					$params['contacts'].="\n".$refer_name." ".$refer_number;
					$this->sendMailToMediation($bdetail['refer_member_name'], $userid, $loanid, $telnumber);
				}
				if($bdetail['borrower_behalf_id']>0){
					$onbehalf_info=$database->getBorrowerbehalfdetail($bdetail['borrower_behalf_id']);
					$params['contacts'].="\n".$onbehalf_info['name']." ".$onbehalf_info['contact_no'];
					$this->sendMailToOtherMediation($bdetail['name']." ".$bdetail['contact_no'], $country, $loanid, $telnumber, $language, $userid);
				}

				if(!empty($bdetail['mentor_id'])){
					$volunteer_name=$database->getNameById($bdetail['mentor_id']);
					$volunteer_number=$database->getPrevMobile($bdetail['mentor_id']);
					$params['contacts'].="\n".$volunteer_name;
					if(!empty($volunteer_number)){
						$params['contacts'].=" ".$volunteer_number;
					}
					$this->sendMailToMediation($bdetail['mentor_id'], $userid, $loanid, $telnumber);
				}
				$invitee= $database->getInvitee($userid);
				if(!empty($invitee)){
					$invitee_name=$database->getNameById($invitee);
					$invitee_number=$database->getPrevMobile($invitee);
					$params['contacts'].="\n".$invitee_name." ".$invitee_number;
					$this->sendMailToMediation($invitee, $userid, $loanid, $telnumber);
				}
				$endorsers=$database->getEndorserRecived($userid);
				if(!empty($endorsers)){
					foreach($endorsers as $endorser){
						$endorser_name=$database->getNameById($endorser['endorserid']);
						$endorser_number=$database->getPrevMobile($endorser['endorserid']);
						$params['contacts'].="\n".$endorser_name." ".$endorser_number;
						$this->sendMailToMediation($endorser['endorserid'], $userid, $loanid, $telnumber);
					}
				}
				if(!empty($bdetail['fb_data'])){
					$fb_data= unserialize(base64_decode($fb_detail['fb_data']));
					if(isset($fb_data['user_friends']['data'])){
						$friends= count($fb_data['user_friends']['data']);
					}else{
						$friends=count($fb_data['user_friends']);
					}
					$params['contacts'].="\n".$friends." friends linked to ".$fb_data['user_profile']['name']." Facebook profile";
				}
				$bmsg= $this->formMessage($lang['mailtext']['loanarrear_reminder_monthly'], $params);
				$content= $this->formMessage($lang['mailtext']['loanarrear_remindersms_monthly'], $params);
				$reply= $this->mailSendingHtml($From, $To, $email, $Subject, '', $bmsg, 0, $templet, 3);
				$sendsms= $this->SendSMS($content, $to_number);
				if($sendsms)
					$database->updateRepaymentReminder($userid, 'late_repayment_reminders');
			}
		}
	}

	function sendMailToMediation($userid, $borrowerid, $loanid, $brwrnumber){
		global $database;
		require("editables/mailtext.php");
		$language= $database->getPreferredLang($userid);
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$templet="editables/email/simplemail.html";
		$From= SERVICE_EMAIL_ADDR;
		$replyTo = SERVICE_EMAIL_ADDR;
		$Subject=$lang['mailtext']['loanarrear_mediation_sub'];
		$user_detail=$database->getEmailB($userid);
		$country=$database->getCountryCodeById($userid);
		$telnumber= $database->getPrevMobile($userid);
		$to_number = $this->FormatNumber($telnumber, $country);
		$due_detail= $database->getDueRepaymentDetail($loanid);
		$duedays= round((time()-$due_detail['duedate'])/(60*60*24));
		$email=$user_detail['email'];
		$params['uname']=$user_detail['name'];
		$params['bnumber']=$brwrnumber;
		$params['bname']=$database->getNamebyId($borrowerid);
		$params['duedays']=$duedays;
		$To=$user_detail['name'];
		$bmsg= $this->formMessage($lang['mailtext']['loanarrear_mediation_mail'], $params);
		$reply= $this->mailSendingHtml($From, $To, $email, $Subject, '', $bmsg, 0, $templet, 3);
		$content= $this->formMessage($lang['mailtext']['loanarrear_mediation_sms'], $params);
		if(!empty($telnumber)){
			$this->SendSMS($content, $to_number);
		}

	}

	function sendMailToOtherMediation($contact, $country, $loanid, $brwrnumber, $language, $borrowerid){
		global $database;
		require("editables/mailtext.php");
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$templet="editables/email/simplemail.html";
		$From= SERVICE_EMAIL_ADDR;
		$replyTo = SERVICE_EMAIL_ADDR;
		$Subject=$lang['mailtext']['loanarrear_mediation_sub'];
		$name=str_replace(str_split('0123456789'),"", $contact);
		$to_number = $this->FormatNumber($contact, $country);

/* comment by Julia 13-12-2013, replaced with FormatNumber function which includes new countries

		$result=preg_replace("/[^0-9]+/", "", $contact);
		if($country=='KE'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+254', STR_PAD_LEFT);
		}
		if($country=='NE'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+227', STR_PAD_LEFT);
		}
		if($country=='SN'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+221', STR_PAD_LEFT);
		}
		if($country=='ID'){
			$to_number = substr($result, -11);
			$to_number=str_pad($to_number, 14, '+62', STR_PAD_LEFT);
		}
		if($country=='BF'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+226', STR_PAD_LEFT);
		}
		if($country=='GN'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+224', STR_PAD_LEFT);
		}
		if($country=='ML'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+223', STR_PAD_LEFT);
		}
		if($country=='BJ'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+229', STR_PAD_LEFT);
		}

*/

		$due_detail= $database->getDueRepaymentDetail($loanid);
		$duedays= round((time()-$due_detail['duedate'])/(60*60*24));
		$params['uname']=$name;
		$params['bnumber']=$brwrnumber;
		$params['bname']=$database->getNamebyId($borrowerid);
		$params['duedays']=$duedays;
		$content= $this->formMessage($lang['mailtext']['loanarrear_mediation_sms'], $params);
		if(!empty($to_number)){
			$this->SendSMS($content, $to_number);
		}

	}

	function FormatNumber($telnumber, $country){
		$result=preg_replace("/[^0-9]+/", "", $telnumber);
		if($country=='KE'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+254', STR_PAD_LEFT);
		}
		if($country=='NE'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+227', STR_PAD_LEFT);
		}
		if($country=='SN'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+221', STR_PAD_LEFT);
		}
		if($country=='ID'){
			$to_number = substr($result, -11);
			$to_number=str_pad($to_number, 14, '+62', STR_PAD_LEFT);
		}
		if($country=='BF'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+226', STR_PAD_LEFT);
		}
		if($country=='GN'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+224', STR_PAD_LEFT);
		}
		if($country=='ML'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+223', STR_PAD_LEFT);
		}
		if($country=='BJ'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+229', STR_PAD_LEFT);
		}
		if($country=='GH'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+233', STR_PAD_LEFT);
		}
		return $to_number;
	}



	/* -------------- added by Julia 13-12-13 --------------- */



	function sendContactConfirmation($userid){
		global $database, $form;
		$templet="editables/email/simplemail.html";
		$bdetail=$database->getBorrowerDetails($userid);
		require("editables/mailtext.php");
		$language= $database->getPreferredLang($userid);
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$telnumber= $bdetail['TelMobile'];
		$country=$bdetail['Country'];
		/* set lang as per julia email 30/12/13 **/
		if($country=='BJ' || $country=='BF' || $country=='GN' || $country=='SN' || $country=='NE'){
			$language='fr';
		}elseif($country=='ID'){
			$language='in';
		}
		
		if(!empty($bdetail['family_member1']) && !empty($telnumber))
			$this->ContactConfirmation($bdetail['family_member1'], $country, $telnumber, $language, $userid);

		if(!empty($bdetail['family_member2']) && !empty($telnumber))
			$this->ContactConfirmation($bdetail['family_member2'], $country, $telnumber, $language, $userid);

		if(!empty($bdetail['family_member3']) && !empty($telnumber))
			$this->ContactConfirmation($bdetail['family_member3'], $country, $telnumber, $language, $userid);

		if(!empty($bdetail['neighbor1']) && !empty($telnumber))
			$this->ContactConfirmation($bdetail['neighbor1'], $country, $telnumber, $language, $userid);

		if(!empty($bdetail['neighbor2']) && !empty($telnumber))
			$this->ContactConfirmation($bdetail['neighbor2'], $country, $telnumber, $language, $userid);

		if(!empty($bdetail['neighbor3']) && !empty($telnumber))
			$this->ContactConfirmation($bdetail['neighbor3'], $country, $telnumber, $language, $userid);		
		
		if(!empty($bdetail['rec_form_offcr_name']) && !empty($telnumber))

			$this->ContactConfirmation($bdetail['rec_form_offcr_name']." ".$bdetail['rec_form_offcr_num'], $country, $telnumber, $language, $userid);

	}


	function ContactConfirmation($contact, $country, $brwrnumber, $language, $borrowerid){
		global $database;
		require("editables/mailtext.php");
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$name=str_replace(str_split('0123456789'),"", $contact);
		$to_number = $this->FormatNumber($contact, $country);
		$params['uname']=$name;
		$params['bnumber']=$brwrnumber;
		$params['bname']=$database->getNamebyId($borrowerid);
		$content= $this->formMessage($lang['mailtext']['contact_confirmation_sms'], $params);
		if(!empty($to_number)){
			$this->SendSMS($content, $to_number);
		}

	}




	/* -------------------Mail section End----------------------- */
	
	/* -------------- added by mohit 22-10-13 ---------- */

	public function sendCommentReplyMail($event_name)
	{
		global $database,$session;
		$res=$database->getEvents($event_name);
		foreach($res as $key=>$value) {
			$data=explode(',',$value['event_fields']);
			$loanid=isset($data[0]) ? $data[0] : ' ';
			$receiverid=isset($data[1]) ? $data[1] : ' ';
			$message=isset($data[2]) ? $data[2] : ' ';
			$cid=isset($data[3]) ? $data[3] : ' ';
			if(!empty($loanid) && !empty($receiverid) && !empty($message) && !empty($cid)) {
				$this->sendCommentMails($loanid, $receiverid, $message, $cid);
				$database->updateEvent($value['id']);
			}
		}
	}// end here
	
	/* -------------- added by mohit 24-10-13 ---------- */
	public function saveRepayReport($id,$name,$number,$date,$note,$borrowerid ,$loanid,$isedit,$mentor)
	{
	global $database;

	$result=$database->saveRepayReport($id,$name,$number,$date,$note,$borrowerid ,$loanid,$isedit,$mentor);
	return $result;
	}
	/* -------------- added by mohit 12-11-13 ---------- */
	public function getVolMentStaffMemList($country=null,$assignedto=null) {
		global $database;
		$result=$database->getVolMentStaffMemList($country,$assignedto);
		return $result; 
	}
	function sharebox_off($id,$set){
		global $session, $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$rtn=$database->sharebox_off($id,$set);
		return $rtn;
	}


	function tmp_trhistory($date3, $date4)
	{
		global $database, $form;
		traceCalls(__METHOD__, __LINE__);
		$path=  getEditablePath('error.php');
		include_once("editables/".$path);
		$date=fixdate;
		if(empty($date3)){
			$form->setError("fromdate", $lang['error']['empty_fromdate']);
		}
		if(empty($date4)){
			$form->setError("todate", $lang['error']['empty_todate']);
		}
		if($form->num_errors > 0)
			return 0;
		$result1=datecompare($date,$date3);
		$result2=datecompare($date3,$date4);
		if(!$result1){
			$form->setError("fromdate", $lang['error']['invalid_fromdate']);
		}
		else if(!$result2){
			$form->setError("todate", $lang['error']['lower_fromdate']);
		}
		if($form->num_errors > 0)
			return 0;
		else
		{
			$_SESSION['date1']=$date3;
			$_SESSION['date2']=$date4;
			return 1;
		}
	}
	
	
//determines a borrower's current credit limit
	function getCurrentCreditLimit($userid,$addCreditearned){
		global $database;
		$firstloan=$database->getBorrowerFirstLoan($userid);
		$loanstatus=$database->getLoanStatus($userid);
		$rate  = $database->getCurrentRate($userid); 
		$invitecredit=$database->getInviteCredit($userid);

		if($loanstatus == LOAN_ACTIVE || $loanstatus == LOAN_FUNDED || $loanstatus == LOAN_OPEN){
//case where borrower has an active loan or fundraising application - we calculate credit limit based on current loan amount

			$loanid= $database->getCurrentLoanid($userid);		
			$ontime = 1; //assume current loan will be repaid on time for purpose of displaying future credit limits

												
		}else{
//case where borrower has repaid one or more loans and has not yet posted an application for a new one - we calculate credit limit based on most recently repaid loan amount
							
			$loanid= $database->getLastRepaidloanId($userid);
			$ontime = $database->isRepaidOntime($userid, $loanid);			

		}
		$loanData= $database->getLoanApplic($loanid);
		$currentloanamt=$loanData['AmountGot'];
		
		if($firstloan==0){
//case where borrower has not yet received first loan disbursement - credit limit should equal admin 1st loan size plus invited borrower credit if applicable
			$val=$database->getAdminSetting('firstLoanValue');

			$invitedstatus=$database->getInvitee($userid);
			$text_length = $database->getTextResponseLength($userid);

			if (!empty($invitedstatus)){
				
				$bonuscredit=100; //adds bonus for new members who were invited by eligible existing members
				

			}elseif (!empty($text_length)){

				if ($text_length >= 40 && $text_length <= 60){

					$bonuscredit=100; //adds bonus of for new members who entered optimal length of text response to 'How did you hear about Zidisha' optional question in application
				
				}else{

					$bonuscredit=0;

				}
			}

			$totalval=$val+$bonuscredit;
			$currentlimit=ceil(convertToNative($totalval, $rate));	
			return $currentlimit;

		} elseif($ontime != 1){

//case where last loan was repaid late - credit limit should equal last loan repaid on time or admin first loan setting, if no loan was ever repaid on time						
			
			$prevamount=$database->getPreviousLoanAmount($userid, $loanid);
			

			if(!empty($prevamount) && $prevamount > 10){

				$currentlimit = $prevamount;

			}

			else{

				$val=$database->getAdminSetting('firstLoanValue');
				$val_local = convertToNative($val, $rate);

				if($addCreditearned == false){

					$currentlimit=$val_local;

				} else {

					$currentlimit=ceil($val_local + $invitecredit);	

				}

			}
			


			return $currentlimit;


		} else {
//case where last loan was repaid on time, we next check whether monthly installment repayment rate meets threshold

			$repayrate= $this->RepaymentRate($userid);
			$minrepayrate=$database->getAdminSetting('MinRepayRate');
			if ($repayrate < $minrepayrate){
//case where last loan repaid on time but monthly installment repayment rate is below admin threshold - loan size stays same				
								
				if($addCreditearned == false){
					$currentlimit=ceil($currentloanamt);
				}else{								
					
																
					$currentlimit=ceil($currentloanamt+$invitecredit);
				}
								
			}else{
//case where last loan repaid on time and overall repayment is above admin threshold - we next check whether the last loan was held long enough to qualify for credit limit increase, with the amount of time loans need to be held and size of increase both dependent on previous loan amount

				$disbdate = $database->getLoanDisburseDate($loanid);
				$currenttime = time();
				$months = $database->IntervalMonths($disbdate, $currenttime);
				$currentloanamt_usd=convertToDollar($currentloanamt, $rate);
				if ($currentloanamt_usd <= 200){

					$timethrshld = $database->getAdminSetting('TimeThrshld');
					$percentincrease = $database->getAdminSetting('secondLoanPercentage');

				}elseif ($currentloanamt_usd <= 1000){

					$timethrshld = $database->getAdminSetting('TimeThrshldMid1');
					$percentincrease = $database->getAdminSetting('nextLoanPercentage');
	
				}elseif ($currentloanamt_usd <= 3000){

					$timethrshld = $database->getAdminSetting('TimeThrshldMid2');
					$percentincrease = $database->getAdminSetting('nextLoanPercentage');
	
				}elseif ($currentloanamt_usd > 3000){

					$timethrshld = $this->getAdminSetting('TimeThrshld_above');
					$percentincrease = $database->getAdminSetting('nextLoanPercentage');
				}							
								
				if($months < $timethrshld) {
//if the loan has not been held long enough then borrower does not yet qualify for credit limit increase
					
					if(!$addCreditearned){

						$currentlimit=ceil($currentloanamt);

					}else{						
		
						$currentlimit=ceil($currentloanamt+$invitecredit);

					}

				} else {
//case where last loan was repaid on time, overall repayment rate is above threshold and loan held for long enough to qualify for credit limit increase

					if($addCreditearned==false){
						
						$currentlimit= ceil(($currentloanamt * $percentincrease) / 100);
										
					}else{


						$currentlimit= ceil(($currentloanamt * $percentincrease) / 100) + $invitecredit;
					
					}				
			
				}
			}			

		
	}

	return $currentlimit;
}


function sendPostData($url, $post){
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	  $result = curl_exec($ch);
	  curl_close($ch);
	  return $result;
	}
	

//checks whether a borrower is eligible to send invites to new borrowers
function isEligibleToInvite($userid){
	
	global $database;

	//borrower must have repaid some amount to Zidisha
	$previous_loan = $database->getLastRepaidloanId($userid);

	//in case where there is no previous loan, checks whether borrower has yet made any payments on current loan
	if(!empty($previous_loan)){

		$paid = $previous_loan;

	} else {

		$loanid = $database->getCurrentLoanid($userid);
		$paid = $database->getTotalPaid($loanid,$userid);

	}

	if (empty($paid) || $paid==0){

		$eligible=0; //not eligible

	} else {

		$brwr_repayrate= $this->RepaymentRate($userid);
		$minrepayrate= $database->getAdminSetting('MinRepayRate');


//determines if member on-time repayment rate meets standard
		if($brwr_repayrate<$minrepayrate){
		
			$eligible=0; //not eligible

		} else {

			$invitedmember= $database->getInviteesWithLoans($userid); //count only those invited members who have raised loans

			if (empty($invitedmember)){

				$eligible = 1;

			} elseif (count($invitedmember)>=100){

				$eligible = 0; //each person can recruit no more than 100 members with loans via invite function

			} else {

				//if more than 10% of invited members do not meet repayment standard then this user is ineligible to invite more
 				$success_rate = $database->getInviteeRepaymentRate($userid);
		
			
				if ($success_rate < 0.9) {

            		$eligible = 0; //not eligible

        		}else{ 

            		$eligible = 1; //eligible
        		}
        	}
        	
        } 
	} 
	
	return $eligible;
}

function getLoginSiftData($event_type,$userid){

	$time=time();
	
	if($event_type=='login_event'){			
		
		$data = array(
			  '$type' => '$login',
			  '$api_key' => SHIFT_SCIENCE_KEY,
			  '$user_id' => $userid,
			  '$session_id' => session_id(),
			  '$login_status' => '$success',
			  '$time' => $time
			);		
	
	}elseif($event_type=='invalid_login_event'){			
				
		$data = array(
			  '$type' => '$login',
			  '$api_key' => SHIFT_SCIENCE_KEY,
			  '$session_id' => session_id(),
			  '$login_status' => '$failure',
			  '$time' => $time
			);		
	
	}elseif($event_type=='logout_event'){	
	
		$data = array(
			'$type' => '$logout',
			'$api_key' => SHIFT_SCIENCE_KEY,
			'$user_id' => $userid,
			'$session_id' => session_id()
			);
	}

	$url_send ="https://api.siftscience.com/v203/events"; 
	$str_data = json_encode($data);	
	$this->sendPostData($url_send, $str_data);
		
}


function getFBSiftData($userid,$facebook_id){

		$time=time();
		$data = array(
			  '$type' => 'facebook_link',
			  '$api_key' => SHIFT_SCIENCE_KEY,
			  '$user_id' => $userid,
			  '$session_id' => session_id(),
			  'facebook_id' => $facebook_id,
			  '$time' => $time	 		  
			);
		
		$url_send ="https://api.siftscience.com/v203/events"; 
		$str_data = json_encode($data);	
		$this->sendPostData($url_send, $str_data);
		
}


function getNewBAccountSiftData($event_type,$userid,$uname=null,$namea=null,$nameb=null,$post=null,$city=null,$country=null,$bnationid=null,$email=null,$mobile=null,$bfamilycont1=null,$bfamilycont2=null,$bfamilycont3=null, $bneighcont1=null,$bneighcont2=null,$bneighcont3=null,$rec_form_offcr_name=null, $rec_form_offcr_num=null, $aboutMe=null,$aboutBusiness=null,$hearaAoutZidisha=null){
	
		$time=time();

		if($event_type=='create_new_account'){

			$typelabel = '$create_account';

		}elseif($event_type=='edit_account'){
			
			$typelabel ='$update_account';

		}

		$data = array(
			  '$type' => $typelabel,
			  '$api_key' => SHIFT_SCIENCE_KEY,
			  '$user_id' => $userid,
			  '$session_id' => session_id(),
			  'username' => $uname,  
			  'first_name' => $namea,
			  'last_name' => $nameb,
			  '$billing_address' =>array(
				  'address' => $post,
				  '$city'      => $city,
				  '$country'   => $country
			   ),
			  'national_id' => $bnationid,
			  '$user_email' => $email,
			  '$phone' => $mobile,
			  'family_contact_1' => $bfamilycont1,
			  'family_contact_' => $bfamilycont2,
			  'family_contact_3' => $bfamilycont3,
			  'neighbor_contact_1' => $bneighcont1,
			  'neighbor_contact_2' => $bneighcont2,
			  'neighbor_contact_3' => $bneighcont3,
			  'community_leader' => $rec_form_offcr_name,
			  'community_leader_phone' => $rec_form_offcr_num,
			  'about_me' => $aboutMe,
			  'about_business' => $aboutBusiness,
			  'hear_about_zidisha' => $hearaAoutZidisha,
			  '$time' => $time			  
			);
		
		$url_send ="https://api.siftscience.com/v203/events"; 
		$str_data = json_encode($data);	
		$this->sendPostData($url_send, $str_data);
		
}

function getBInviteSiftData($userid,$invited_by){

		$time=time();
		$data = array(
			  '$type' => 'borrower_invite',
			  '$api_key' => SHIFT_SCIENCE_KEY,
			  '$user_id' => $userid,
			  '$session_id' => session_id(),
			  'invited_by' => $invited_by,
			  '$time' => $time	 		  
			);
		
		$url_send ="https://api.siftscience.com/v203/events"; 
		$str_data = json_encode($data);	
		$this->sendPostData($url_send, $str_data);
		
}

function getBPaymentSiftData($event_type, $userid, $amount){

	$time=time();

	$data = array(
		'$type' => $event_type,
		'$api_key' => SHIFT_SCIENCE_KEY,
		'$user_id' => $userid,
		'amount' => $amount,
		'$time' => $time
		);

	$url_send ="https://api.siftscience.com/v203/events"; 
	$str_data = json_encode($data);	
	$this->sendPostData($url_send, $str_data);
		
}


function getOnTimePaymentSiftData($userid){

	$time=time();
		
	$data = array(
		'$type' => 'ontime_payment',
		'$api_key' => SHIFT_SCIENCE_KEY,
		'$user_id' => $userid,
		'$is_bad' => false,
		'reasons' => 'High on-time repayment rate',
		'$description' => 'Made payment and historic on-time repayment rate is high',
		'$time' => $time
		);
		
		$url_send ="https://api.siftscience.com/v203/users/".$userid."/labels"; 
		$str_data = json_encode($data);	
		$this->sendPostData($url_send, $str_data);
		
}


function getBCommentSiftData($userid, $comment){

	$time=time();

	$data = array(
		'$type' => 'comment_post',
		'$api_key' => SHIFT_SCIENCE_KEY,
		'$user_id' => $userid,
		'comment' => $comment,
		'$time' => $time
		);

	$url_send ="https://api.siftscience.com/v203/events"; 
	$str_data = json_encode($data);	
	$this->sendPostData($url_send, $str_data);
		
}


function getDeclineSiftData($userid){

		$time=time();
		
		$data = array(
			  '$type' => 'decline',
			  '$api_key' => SHIFT_SCIENCE_KEY,
			  '$user_id' => $userid,
			  '$is_bad' => true,
			  'reasons' => 'Declined',
			  '$description' => 'Borrower application declined',
			  '$time' => $time
		);
		
		$url_send ="https://api.siftscience.com/v203/users/".$userid."/labels"; 
		$str_data = json_encode($data);	
		$this->sendPostData($url_send, $str_data);
		
}

//sends SMS alert to mobile number associated with the account that invited a borrower, to prevent unauthorized invites being sent from member accounts
function sendInviteAlert($inviteeid){
		global $database;
		require("editables/mailtext.php");
		$userid = $database->getInvitee($inviteeid);
		$language= $database->getPreferredLang($userid);
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$user_detail=$database->getEmailB($userid);
		$country=$database->getCountryCodeById($userid);
		$telnumber= $database->getPrevMobile($userid);
		$to_number = $this->FormatNumber($telnumber, $country);
		$params['uname']=$user_detail['name'];
		$params['bnumber']=$database->getPrevMobile($inviteeid);
		$params['bname']=$database->getNamebyId($inviteeid);
		$content= $this->formMessage($lang['mailtext']['invite_alert'], $params);
		if(!empty($telnumber)){
			$this->SendSMS($content, $to_number);
		}
}


function languageSetting($lang_code,$country_code){
		 global $database;
		 $result=$database->languageSetting($lang_code,$country_code);
		 return $result;
}


function sendMixpanelUser(){

	require_once 'extlibs/mixpanel-php-master/lib/Mixpanel.php';

	// get the Mixpanel class instance, replace with your project token
	$mp = Mixpanel::getInstance(MIXPANEL_PROJECT_TOKEN);

	$userid = $this->userid;

	if ($this->userlevel == 1){
		$userlevel = "Borrower";
	}elseif ($this->userlevel == 4){
		$userlevel = "Lender";
	}elseif ($this->userlevel == 9){
		$userlevel = "Admin";
	}else {
		$userlevel = $this->userlevel;
	}
			
	$mp->people->set($userid, array(
    	'$first_name' => $this->fullname,
    	'userlevel' => $userlevel
	));
		

}


function sendMixpanelEvent($event_label){

	require_once 'extlibs/mixpanel-php-master/lib/Mixpanel.php';

	// get the Mixpanel class instance, replace with your project token
	$mp = Mixpanel::getInstance(MIXPANEL_PROJECT_TOKEN);

	if($event_label=='lender signup') {

		$userid = $this->userid;

		$mp->createAlias($userid, array(
	    '$first_name' => "Guest",
	    'userlevel' => GUEST_LEVEL
		));
	}

	// track an event
	$mp->track($event_label); // track an event

}

/***** end here ******/


}
$session=new Session;
$form = new Form;
?>