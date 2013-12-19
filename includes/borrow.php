<?php
include_once("./editables/borrow.php");
$path=	getEditablePath('borrow.php');
include_once("./editables/".$path);
$regfeedetail = $database->getAllregFee();
?>
<div class="span16">
	<div id="static" style="text-align:justify">
	<?php 
	$params['firstLoanPercentage']=$database->getAdminSetting('firstLoanPercentage');
	$params['secondLoanPercentage']=$database->getAdminSetting('secondLoanPercentage');
	$params['nextLoanPercentage']=$database->getAdminSetting('nextLoanPercentage');
	$params['firstLoanValue']=$database->getAdminSetting('firstLoanValue');
	$params['secondLoanValue']=$database->getAdminSetting('secondLoanValue');
	$params['nextLoanValue']=$database->getAdminSetting('nextLoanValue');
	$params['fee']=$database->getAdminSetting('fee');
	$params['maxLoanAppInterest']=($database->getAdminSetting('maxLoanAppInterest') + $database->getAdminSetting('fee'));
	$params['MinRepayRate']=$database->getAdminSetting('MinRepayRate');
	$params['TimeThrshld_under']=$database->getAdminSetting('TimeThrshld');
	$params['TimeThrshld_above']=$database->getAdminSetting('TimeThrshld_above');
	$params['fees_link']=$_SERVER['REQUEST_URI'].'#currentFee';
	$value=$params['firstLoanValue'];
	for($i=2; $i<=10; $i++){
		if($i==2 || $i==3){
			$value= $session->getNextLoanValue($value, $params['secondLoanPercentage']);
			$params[$i.'nxtLoanvalue']= $value;
		}else{
			$value= $session->getNextLoanValue($value, $params['nextLoanPercentage']);
			$val1= number_format($value, 0, ".", ",");
			$params[$i.'nxtLoanvalue']= $val1;
		}
	}
	$borrowText=$session->formMessage($lang['borrow']['desc'], $params);
	echo $borrowText; ?>	
	</div>
</div>
<div style="display:none" id="currentFee">
	<div class='instruction_space' style="height:200px;">
		<div class='instruction_title'>Current registration fees:</div>
		<div class='instruction_text'>
			<?php foreach($regfeedetail as $regfee) {
					$country = $regfee['country'];
					$currencyname = $regfee['currency_name'];
					$amount = $regfee['Amount'];
					echo"<p style='font-size:10pt'>$country: &nbsp;&nbsp;$currencyname&nbsp;$amount</p>";
			} 					
			?>
		</div>
	</div>
</div>