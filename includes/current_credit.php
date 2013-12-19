<?php
require_once("library/session.php");
include_once("./editables/current_credit.php");
$path =	getEditablePath('current_credit.php');
include_once("./editables/".$path);
?>
<div class='span12'>
<div align='left' class='static'><h1><?php echo $lang['current_credit']['title']; ?></h1></div>
<br/>
<?php 

if($session->userlevel==BORROWER_LEVEL) {
	$userid = $session->userid;
	$islastrepaid = $database->getLastRepaidloanId($userid);
	$currentloan = $database->getCurrentLoanid($userid);
	$rate  = $database->getCurrentRate($userid); 
	if(empty($currentloan) && $islastrepaid) {
		$currentloan = $islastrepaid;
	}
	$lastRepaidStatus= $database->getUserLoanStatus($userid,$islastrepaid);
	$borrowerid=  $session->userid;
	$loanCountArray=$database->getLoanCount($userid,true); 
	$loanCounts= $loanCountArray[0]; 
	$firstLoanpercent = $database->getAdminSetting('firstLoanPercentage');
	$SecondLoanpercent = $database->getAdminSetting('secondLoanPercentage');
	$nextLoanpercent = $database->getAdminSetting('nextLoanPercentage');
	$firstLoanVal = $database->getAdminSetting('firstLoanValue');
	$SecondLoanVal = $database->getAdminSetting('secondLoanValue');
	$ThirdLoanVal = $database->getAdminSetting('thirdLoanValue');
	$nextLoanVal = $database->getAdminSetting('nextLoanValue');
	$latePayments = $database->isAllInstallmentOnTime($userid,$currentloan);
	$max_amt = convertToNative($database->getSettingsValue('nextLoanValue'), $rate);
	$tmpcurr = $database->getUserCurrency($userid);			
	$Ontimecreditdetail = $database->getcreditLimitbyuser($userid, 2);
	$commentcreditdetail = $database->getcreditLimitbyuser($userid, 1);
	$loanstatus=$database->getLoanStatus($userid);
	$creditCurrent = $database->CreditonCurrentLoan($userid, $currentloan, 1);
	$ontimecreditearned = $database->CreditonCurrentLoan($userid, $currentloan, 2);
	$invitecredit=$database->getInviteCredit($userid);
	$duedate = $database->getLastduedatebyloanid($userid, $currentloan);
	$repayduedate = date("F d Y",$duedate);
	if(empty($Ontimecreditdetail))
		$Ontimecreditdetail = 0;
	if(empty($commentcreditdetail)) {
		$commentcreditdetail = 0;
	} 
	$brwr_repayrate= $session->RepaymentRate($userid);
	if($loanstatus == LOAN_ACTIVE || $loanstatus == LOAN_OPEN || $islastrepaid) {
		$allloans= $database->getBorrowerRepaidLoans($userid); 
		$k=2;
		$params['nxtLoanvalue']='';
		if(!empty($islastrepaid) && $lastRepaidStatus['active']==LOAN_DEFAULTED){
			$params['nxtLoanvalue']='';
			$params['firstLoanVal']='';
		}else{
			if(!empty($allloans))
			if($allloans[0]['loancount']>0){
				$k=1;
				foreach($allloans as $allloan){ 
					if($k==1){
						$loanRepaidDate= date('M d, Y',$database->getLoanRepaidDate($allloan['loanid'], $userid));
						$loanprofileurl = getLoanprofileUrl($userid,$allloan['loanid']);
						$params['firstLoanVal']='1.'." ".$tmpcurr." ".number_format($allloan['AmountGot'], 0, ".", ",")." (<a href='$loanprofileurl' >Repaid ".$loanRepaidDate."</a>)";
					}else{ 
						$loanRepaidDate= date('M d, Y',$database->getLoanRepaidDate($allloan['loanid'], $userid));
						$loanprofileurl = getLoanprofileUrl($userid,$allloan['loanid']);
						$params['nxtLoanvalue'].="<br/>".$k.". ".$tmpcurr." ".number_format($allloan['AmountGot'], 0, ".", ",")." (<a href='$loanprofileurl' >Repaid ".$loanRepaidDate."</a>)";
					}
					$k++;
				}
			}elseif(empty($currentloan)){ 
				$firstLoanValue=$database->getAdminSetting('firstLoanValue');
				$value=$firstLoanValue;
				$params['firstLoanVal']='1.'." ".$tmpcurr.' '.number_format(convertToNative($firstLoanValue, $rate), 0, ".", ",");
			}
			$flag=0;
			if(!empty($currentloan) && $currentloan!=$islastrepaid){
					$res= $database->getTotalPayment($userid, $currentloan);
					if($res['amttotal']==0) /*  this case is when loan is funded and schedule is not yet generatetd */
						$repaidPercent= 0;
					else
						$repaidPercent = $res['paidtotal']/$res['amttotal']*100;

					$repaidPercent = round($repaidPercent,0);
					$loanprofileurl = getLoanprofileUrl($userid,$currentloan);
					$loanDisburseDate=date('M d, Y',$database->getLoanDisburseDate($currentloan));
					$loanData= $database->getLoanApplic($currentloan);					

					if($loanCounts==0){ 
						$k=1;
						if($loanstatus == LOAN_FUNDED || $loanstatus == LOAN_OPEN){

							$params['firstLoanVal']='1.'." ".$tmpcurr." ".number_format($loanData['AmountGot'], 0, ".", ",")." (<a href='$loanprofileurl' >Fundraising Loan</a>)";
						}else{

							$params['firstLoanVal']='1.'." ".$tmpcurr." ".number_format($loanData['AmountGot'], 0, ".", ",")." (<a href='$loanprofileurl' >Disbursed ".$loanDisburseDate.", ".$repaidPercent."% repaid</a>)";
						}
					}else{

						if($loanstatus == LOAN_FUNDED || $loanstatus == LOAN_OPEN){

						$params['nxtLoanvalue'].="<br/>".$k.". ".$tmpcurr." ".number_format($loanData['AmountGot'], 0, ".", ",")." (<a href='$loanprofileurl' >Fundraising Loan</a>)";

						}else{


						$params['nxtLoanvalue'].="<br/>".$k.". ".$tmpcurr." ".number_format($loanData['AmountGot'], 0, ".", ",")." (<a href='$loanprofileurl' >Disbursed ".$loanDisburseDate.", ".$repaidPercent."% repaid</a>)";
						}
					}
					$k++;
			}
			if(!empty($currentloan)){ 
				$borwrAmtExceptCredit = $session->getCurrentCreditLimit($userid,false);   // function created by julia 08-12-13 used by Mohit
				$maxborwAmtNextLoan =$session->getCurrentCreditLimit($userid,true);
				$value_local= $borwrAmtExceptCredit;
				$params['nxtLoanvalue'].="<br/>".$k.". ".$tmpcurr.' '.number_format($maxborwAmtNextLoan, 0, ".", ",");
				$k++;
				$value= convertToDollar($value_local, $rate);
				$currentLoanamtusd= convertToDollar($loanData['AmountGot'], $rate);
			}
			for($i=$k; $i<=12; $i++){

				if($value <= 200){
					$value= $session->getNextLoanValue($value, $SecondLoanpercent);
					if($i==2){
						$loanusdvalue= $SecondLoanVal;
					}else{
						$loanusdvalue= $ThirdLoanVal;
					}
					if($loanusdvalue<$value){
						$value= $loanusdvalue;
					}
					$val= number_format(convertToNative($value, $rate), 0, ".", ",");

					$params['nxtLoanvalue'].="<br/>".$i.". ".$tmpcurr.' '.$val;
				}else{
					$value= $session->getNextLoanValue($value, $nextLoanpercent);
					$local_value=convertToNative($value, $rate);

					if($local_value >$max_amt){
						$val= number_format($max_amt, 0, ".", ",");
						$params['nxtLoanvalue'].="<br/>".$i.". and thereafter ".$tmpcurr.' '.$val;
						break;
					}else{
						$val= number_format($local_value, 0, ".", ",");
						$params['nxtLoanvalue'].="<br/>".$i.". ".$tmpcurr.' '.$val;
					}
				}
			}
	}
		if($latePayments['missedInst'] == 0 || empty($latePayments['missedInst'])) {
			$currentcreditlimit=$maxborwAmtNextLoan;

			if($loanstatus == LOAN_FUNDED || $loanstatus == LOAN_OPEN){

				$params['currencreditlimit'] = $tmpcurr.' '.number_format($loanData['AmountGot'], 0, ".", ",");

			}else{

				$params['currencreditlimit'] = $tmpcurr.' '.number_format($currentcreditlimit, 0, ".", ",");
			}
			$params['borwrAmtExceptCredit'] = $tmpcurr.' '.number_format($borwrAmtExceptCredit, 0, ".", ",");
			$params['firstLoanPercentage'] = $tmpcurr.' '.number_format($firstLoanpercent, 0, ".", ",");
			$params['secondLoanPercentage'] = $tmpcurr.' '.number_format($SecondLoanpercent, 0, ".", ",");
			$params['nextLoanPercentage'] = $tmpcurr.' '.number_format($nextLoanpercent, 0, ".", ",");
			$params['firstLoanValue'] = $tmpcurr.' '.number_format($firstLoanVal*$rate, 0, ".", ",");
			$params['secondLoanValue'] = $tmpcurr.' '.number_format($SecondLoanVal*$rate, 0, ".", ",");
			$params['nextLoanValue'] = $tmpcurr.' '.number_format($nextLoanVal*$rate, 0, ".", ",");
			$params['ontimecreditearned'] = $tmpcurr.' '.number_format($ontimecreditearned['credit'], 0, ".", ",");
			$params['ontimecredit']= $tmpcurr.' '.number_format($Ontimecreditdetail['loanamt_limit'], 0, ".", ",");
			if(empty($creditCurrent['credit']))
				$creditCurrent['credit'] = 0;
			$params['cmntcreditearned']= $tmpcurr.' '.number_format($creditCurrent['credit'], 0, ".", ",");
			$params['commentscredit']= $tmpcurr.' '.number_format($commentcreditdetail['loanamt_limit'], 0, ".", ",");
			$params['commentcharlimit']= $commentcreditdetail['character_limit'];
			$params['commentposted']= $creditCurrent['commentposted'];
			$params['MinRepayRate']=$database->getAdminSetting('MinRepayRate');
			$params['brwr_RepayRate']=number_format($brwr_repayrate);
			$params['newmembercrdt_link']='index.php?p=97';
			$params['invite_credit']= $tmpcurr.' '.number_format($invitecredit);

		} else { 
			$currentcreditlimit=$maxborwAmtNextLoan;

			if($loanstatus == LOAN_FUNDED || $loanstatus == LOAN_OPEN){

				$params['currencreditlimit'] = $tmpcurr.' '.number_format($loanData['AmountGot'], 0, ".", ",");

			}else{

				$params['currencreditlimit'] = $tmpcurr.' '.number_format($currentcreditlimit, 0, ".", ",");
			}
			$params['borwrAmtExceptCredit'] = $tmpcurr.' '.number_format($borwrAmtExceptCredit, 0, ".", ",");
			$params['firstLoanPercentage'] = $tmpcurr.' '.number_format($firstLoanpercent, 0, ".", ",");
			$params['secondLoanPercentage'] = $tmpcurr.' '.number_format($SecondLoanpercent, 0, ".", ",");
			$params['nextLoanPercentage'] = $tmpcurr.' '.number_format($nextLoanpercent, 0, ".", ",");
			$params['firstLoanValue'] = $tmpcurr.' '.number_format($firstLoanVal*$rate, 0, ".", ",");
			$params['secondLoanValue'] = $tmpcurr.' '.number_format($SecondLoanVal*$rate, 0, ".", ",");
			$params['nextLoanValue'] = $tmpcurr.' '.number_format($nextLoanVal*$rate, 0, ".", ",");
			$params['ontimecreditearned'] = $tmpcurr.' '.number_format($ontimecreditearned['credit'], 0, ".", ",");
			$params['ontimecredit']= $tmpcurr.' '.number_format($Ontimecreditdetail['loanamt_limit'], 0, ".", ",");
			if(empty($creditCurrent['credit']))
				$creditCurrent['credit'] = 0;
			$params['cmntcreditearned']= $tmpcurr.' '.number_format($creditCurrent['credit'], 0, ".", ",");
			$params['commentscredit']= $tmpcurr.' '.number_format($commentcreditdetail['loanamt_limit'], 0, ".", ",");
			$params['commentcharlimit']= $commentcreditdetail['character_limit'];
			$params['commentposted']= $creditCurrent['commentposted'];
			$params['MinRepayRate']=$database->getAdminSetting('MinRepayRate');
			$params['brwr_RepayRate']=number_format($brwr_repayrate);
			$params['newmembercrdt_link']='index.php?p=97';
			$params['invite_credit']= $tmpcurr.' '.number_format($invitecredit);
			
		}


/* ----------------- notes definition section added by Julia 13-12-2013 ---------------- */


		$firstloan=$database->getBorrowerFirstLoan($userid);
		$disbdate = $database->getLoanDisburseDate($currentloan);
		$currenttime = time();
		$months = $database->IntervalMonths($disbdate, $currenttime);
		$loanData= $database->getLoanApplic($currentloan);
		$currentloanamt=$loanData['AmountGot'];
		$currentloanamt_usd=convertToDollar($currentloanamt, $rate);
		if ($currentloanamt_usd <= 200){

			$timethrshld = $database->getAdminSetting('TimeThrshld');

		}elseif ($currentloanamt_usd <= 1000){

			$timethrshld = $database->getAdminSetting('TimeThrshldMid1');
	
		}elseif ($currentloanamt_usd <= 3000){

			$timethrshld = $database->getAdminSetting('TimeThrshldMid2');
	
		}elseif ($currentloanamt_usd > 3000){

			$timethrshld = $database->getAdminSetting('TimeThrshld_above');
		}	

		$params['TimeThrshld']=$timethrshld;

		if($firstloan==0){
			$note = $session->formMessage($lang['current_credit']['first_loan'], $params);

		} elseif(empty($currentloan) && $islastrepaid) {

			$ontime = $database->isRepaidOntime($userid, $islastrepaid);	
			if($ontime != 1){
				$note = $session->formMessage($lang['current_credit']['repaid_late'], $params);
			}

		} elseif($months < $timethrshld) {

			$note = $session->formMessage($lang['current_credit']['time_insufficient'], $params);

		} elseif($brwr_repayrate<$database->getAdminSetting('MinRepayRate')){

			$note = $session->formMessage($lang['current_credit']['repayrate_insufficient'], $params);
		}else{
			$note = $session->formMessage($lang['current_credit']['repayrate_sufficient'], $params);
		}



/* ------------- end notes definition section ------------------ */


		$beginning = $session->formMessage($lang['current_credit']['beginning'], $params);
		$invite_credit = $session->formMessage($lang['current_credit']['invite_credit'], $params);
		$comment_credit = $session->formMessage($lang['current_credit']['comment_credit'], $params);
		$end = $session->formMessage($lang['current_credit']['end'], $params);


		echo $beginning;

		echo $note;

		echo $invite_credit;
	
		if ($creditCurrent['credit'] > 0){

			echo $comment_credit;

		}

		echo $end;

	}

}
?>
</div>