<?php

include("../library/session.php");
foreach ($_GET as $key => $value){
	if($key == 'mamount'){
		$result=$session->setMinFund($value);
		if($result==3){
			echo 1;
		}
		else{
			echo 0;
		}
	}
	else if($key == 'maxbamount'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
				}
			}
			
		$result= $database->setAdminSetting('maxBorrowerAmt', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'fee'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
				}
			}
			
		$result= $database->setAdminSetting('Fee', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'mbamount'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
				}
			}
			
		$result= $database->setAdminSetting('minBorrowerAmt', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'deadline'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
				}
			}
			
		$result= $database->setAdminSetting('deadline', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'flpercent'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
				}
			}
			
		$result= $database->setAdminSetting('firstLoanPercentage', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'slpercent'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
				}
			}
			
		$result= $database->setAdminSetting('secondLoanPercentage', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'nlpercent'){
		if(!$value || strlen($value=trim($value))<0){
				echo 1; //error
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
				}
			}
			
		$result= $database->setAdminSetting('nextLoanPercentage', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'risk1percent'){
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('PAR-0-30-DAYS', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'risk2percent'){
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('PAR-31-60-DAYS', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'risk3percent'){
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('PAR-61-90-DAYS', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'risk4percent'){
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('PAR-91-180-DAYS', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'risk5percent'){
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('PAR-181-OVER-DAYS', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'latethreshold'){
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('LATENESS_THRESHOLD', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'reschallow'){
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('RescheduleAllow', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'paypalTran'){
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('PaypalTransaction', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'first_loanValue') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('firstLoanValue', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'second_loanValue') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('secondLoanValue', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'next_loanValue') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('nextLoanValue', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}

		else if($key == 'maxPeriodValue') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('maxPeriodValue', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'maxGraceperiodValue') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('maxGraceperiodValue', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'maxLoanAppGraceperiod') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('maxLoanAppGracePeriod', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'maxLoanAppInterest') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('maxLoanAppInterest', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}	
	else if($key == 'maxRepayPeriod') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('maxRepayPeriod', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	} else if($key == 'RepaymentReportThrshld') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('RepaymentReportThrshld', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'TimeThrshld') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('TimeThrshld', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'TimeThrshld_above') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('TimeThrshld_above', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'TimeThrshldMid1') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('TimeThrshldMid1', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'TimeThrshldMid2') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('TimeThrshldMid2', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'MinRepayRate') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('MinRepayRate', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'Nodonationincart'){
		$_SESSION['Nodonationincart'] = $value;
	}
	else if($key == 'third_loanValue') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('thirdLoanValue', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'MinFbFrnds') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('MIN_FB_FRNDS', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'MinFbMonths') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('MIN_FB_MONTHS', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'MinEndorser') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('MinEndorser', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'AgainReminderDays') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('AgainReminderDays', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
	else if($key == 'AgainReminderAmt') { 
		if($value=='' || strlen($value=trim($value))<0){
				echo 1; //error
				exit;
			}
			else{
				if(!is_numeric($value)){
					echo 1; //error
					exit;
				}
			}
			
		$result= $database->setAdminSetting('AgainReminderAmt', $value);
		if($result){
			echo 0;
		}
		else{
			echo 1;
		}
	}
}
if(isset($_GET['loanAppAmount']) && isset($_GET['loanAppInterest']) && isset($_GET['loanAppGracePeriod'])) {

	$loanAppAmount = (int)(trim(str_replace(",","",$_GET['loanAppAmount'])));
	$loanAppInterest = (int)(trim(str_replace("%","",$_GET['loanAppInterest'])));
	$loanAppGracePeriod = (int)(trim($_GET['loanAppGracePeriod']));
	$maxperiodValue_months = $database->getAdminSetting('maxperiodValue');

	if(isset($_GET['installment_weekday'])) {

		$maxperiodValue = $maxperiodValue_months * (52/12);
		$weekly_inst = 1;

	} else {

		$maxperiodValue = $maxperiodValue_months;
		$weekly_inst = 0;

	}

	$minIns=$session->getMinInstallment($loanAppAmount, $maxperiodValue, $loanAppInterest, $loanAppGracePeriod, $weekly_inst);
	echo $minIns;
}
if(isset($_GET['checkLoanAppAmount'])) {
	$path=	getEditablePath('error.php');
	include_once("../editables/".$path);
	$loanAppAmount = trim(str_replace(",","",$_GET['checkLoanAppAmount']));
	$currency=$database->getUserCurrency($session->userid);
	$rate=$database->getCurrentRate($session->userid);
	//$usdmaxBorrowerAmt=$database->getAdminSetting('maxBorrowerAmt');//website fee rate		

	$loanstatus=$database->getLoanStatus($session->userid);
	if($loanstatus == LOAN_OPEN){					
						$usdmaxBorrowerAmt = $session->getCurrentCreditLimit($session->userid,false); // function created by julia 12-02-14 used by Mohit
					}else{
						$usdmaxBorrowerAmt = $session->getCurrentCreditLimit($session->userid,true); // function created by julia 08-12-13 used by Mohit
					}
	
	$maxBorrowerAmt= ceil($usdmaxBorrowerAmt); /* It is in native currency */
	$usdminBorrowerAmt=$database->getAdminSetting('minBorrowerAmt');//website fee rate
	$minBorrowerAmt= ceil(convertToNative($usdminBorrowerAmt, $rate));
	if(empty($loanAppAmount) || !is_numeric($loanAppAmount)){
		echo $lang['error']['invalid_loanamt'];
	} else if($loanAppAmount > $maxBorrowerAmt) {
		echo $lang['error']['lower_loanamt']." ".$currency." ".$maxBorrowerAmt;
	} else if($loanAppAmount < $minBorrowerAmt) {
		echo $lang['error']['greater_loanamt']." ".$currency." ".$minBorrowerAmt;
	}else {
		echo "";
	}
}
if(isset($_GET['checkLoanAppInterest'])) {
	$path=	getEditablePath('error.php');
	include_once("../editables/".$path);
	$loanAppInterest = trim(str_replace(",","",$_GET['checkLoanAppInterest']));
	$fee = $database->getAdminSetting('fee');
	$maxLoanAppInterest= $database->getAdminSetting('maxLoanAppInterest');
	if(empty($loanAppInterest) || !is_numeric($loanAppInterest)){
		echo $lang['error']['invalid_interest'];
	} else if($loanAppInterest < $fee) {
		echo $lang['error']['greater_interest']." ". $fee."%";
	} else if($loanAppInterest > ($fee+$maxLoanAppInterest)) {
		echo $lang['error']['lower_interest']." ". ($fee+$maxLoanAppInterest)."%";
	} else {
		echo "";
	}
}
if(isset($_GET['checkLoanAppGracePeriod'])) {
	$path=	getEditablePath('error.php');
	include_once("../editables/".$path);
	$loanAppGracePeriod = trim(str_replace(",","",$_GET['checkLoanAppGracePeriod']));
	$maxLoanAppGracePeriod= $database->getAdminSetting('maxLoanAppGracePeriod');
	if(!is_numeric($loanAppGracePeriod)){
		echo $lang['error']['invalid_gracetime'];
	} else if($loanAppGracePeriod > $maxLoanAppGracePeriod) {
		echo $lang['error']['max_gracetime']." ".$maxLoanAppGracePeriod." ".$lang['error']['months'];
	} else {
		echo "";
	}
}
?>