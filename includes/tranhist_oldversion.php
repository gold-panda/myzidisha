<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<SCRIPT src="includes/scripts/paging.js?q=<?php echo RANDOM_NUMBER ?>" type="text/javascript"></SCRIPT>
<script type="text/javascript">
$(function() {		
		$(".tablesorter_trhistry").tablesorter({sortList:[[0,0]], widgets: ['zebra'], headers: { 1:{sorter: false},2:{sorter: false},3:{sorter: false},4:{sorter: false}}});
});	
$(document).ready(function(){
	$("#date1").datepicker();
	$("#date2").datepicker();
	$('#totalHistory').click(function() {
		$('#totalHistoryDetails').slideToggle();
	});
});
</script>

<div class='span12'>
<?php
if($session->userlevel==LENDER_LEVEL)
{
	$userid1=$session->userid;
	$res=$database->isTranslator($userid1);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL) {
	$c='ALL';
	$v=0;
	$opt='TrDate';
	$ord="ASC";
	$image = "images/layout/table_show/asc.gif";
	if(isset($_GET["v"])) {
		$v=$_GET["v"];
	}
	if(!empty($_GET["c"])) {
		$c=$_GET["c"];
	}
	if(isset($_GET["opt"])) {
		$opt=$_GET["opt"];
	}
	if(isset($_GET["ord"])) {
		$ord=$_GET["ord"];
	}
	if($ord=='ASC') {
		$image = "images/layout/table_show/desc.gif";
	}
	if(isset($_SESSION['date1']) ||isset($_SESSION['date2'])) {
		$date1=$_SESSION['date1'];
		$date2=$_SESSION['date2'];
	}
	else {
		$date1=$form->value("date1");
		$date2=$form->value("date2");
	}
	$ordClass="headerSortDown";
	if(isset($_GET["ord"]) && $_GET["ord"]=='DESC')	{
		$ord='DESC';
		$ordClass="headerSortUp";
	}
	$type=1;
	if(isset($_GET["type"])){
		$type=$_GET["type"];
	}
?>
	<div class="subhead2">
		<div style="float:left"><div align='left' class='static'><h1>Transaction History Details</h1></div></div>
		<?php if($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL ){?>
			<div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			</div>
		<?php } ?>
		<div style="clear:both"></div>
	</div>
	<form action='updateprocess.php' method="POST">
		<table class="detail">
			<tbody>
				<tr>
					<td><strong>From Date:</strong></td>
					<td><input style="width:auto" name="date1" id="date1" type="text" value='<?php echo $date1 ;?>'/><br/><?php echo $form->error("fromdate"); ?></td>
					<td><strong>To Date:</strong></td>
					<td><input style="width:auto"  name="date2" id="date2"type="text" value='<?php echo $date2 ;?>' /><br/><?php echo $form->error("todate"); ?></td>
					<td>
						<input type="hidden" name="transactionhistory" id="transactionhistory">
						<input type="hidden" name="user_guess" value="<?php echo generateToken('transactionhistory'); ?>"/>
						<input type="hidden" name="row" id="row" value="0">
						<input class='btn' type='submit' name='report' align='right' value='Submit' />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<br>
	
<?php
	if($v==1) {
		$set=$database->trhistory($date1, $date2,$ord,$opt);
		logger('trhistory after cal in database');
		if(!empty($set)) {
			logger('trhistory not empty'.count($set));
?>

<!-- totals section moved to includes/tranhistSummary.php 5-11-2013

	<div align="right" >
		<input id="totalHistory" class='btn' type="button" value="Show Total History">
	</div>

-->
		<?php
			//$set1=$database->trhistorytotal($date1, $date2);
			//logger('set1 from database'.count($set1));
			//2012-Dec-17 Anupam ,removed Anonymous fee income as requested  2012-Dec-17 email by Julia
			//$feeByAnonymous = $database->getFeeByAnonymous(2235, $date1, $date2);
			$bCountries=$database->getBorrowerCountries();
			$openLoanIds = $database->getNonDisbursedLoanIds();
			
			
			logger('openLoanIds from database');
			for($k=0; $k<count($bCountries); $k++) {
				$bCountries[$k]['disb_amt_total']=0;
				$bCountries[$k]['ln_bck_amt_total']=0;
				$bCountries[$k]['fee_amt_total']=0;
				$bCountries[$k]['referral_amt_total']=0;
				$bCountries[$k]['reg_fee_amt_total']=0;
				$bCountries[$k]['ln_bck_intamt_total']=0;
				$bCountries[$k]['ln_bck_pamt_total']=0;
			}
			/* Initialized all variables with 0 */
			$fund_up_amt = $fund_wd_amt = $ln_snt_lndr_amt = $ln_bck_lndr_amt = $fee_amt = $disb_amt = $ln_bck_amt = $gift_redeem_amt = $gift_purchage_amt = $gift_donate_amt = $donation_amt = $donation_amt_reduce = $transaction_fee =$transaction_fee_reduce = $tranamt = $referral_amt = $reg_fee = $referral_redeem_amt = $transaction_fee_amt= $ln_bid_lndr_amt = $ConvrtedTodonation_amt = $UploadedbyPaypal= $UploadedbychcekorBank =0;

			$desc1 = "Lender Funds Uploads";
			$desc2 = "Lender Funds Withdrawals";
			$desc3 = "Loan Disbursements Deducted from Lender Accounts";
			$desc4 = "Loan Repayments Credited to Lenders";
			$desc5 = "Borrower Transaction Fees";
			$desc6 = "Loan Principal Disbursements";
			$desc7 = "Total Repayments Received Including Transaction Fees";
			$desc8 = "Gift Card Redemptions";
			$desc9 = "Gift Card Purchases";
			$desc10 = "Expired Gift Cards Converted to Donations";
			$desc11 = "Lender Donations";
			$desc12 = "Lender Transaction Fees";
			$desc13 = "Referral Program Credits to Borrowers";
			$desc14 = "Borrower Registration Fees";
			$desc15 = "Referral Code Redemptions";
			//Anupam 1-9-2013 removed as requested by Julia in email "Update request"
			//$desc16 = "Borrower Repayments (Minus Transaction Fees)";
			$desc17 = "Loan Bids Deducted from Lender Accounts";
			//$desc18 = "Borrower Transaction Fees: Anonymous Loan Fund";
			$desc19 = "Expired Lender Accounts Converted to Donations";
			$desc20 = "Total Payments from Lenders (check or bank transfer)";
			$desc21 = "Total Payments from Lenders (PayPal)";
			$desc22 = 'Principal Repayment';
			$desc23 = 'Interest Payment';
			$count=1;
			
			 ?>
			
			<div id="totalHistoryDetails" style="display:none;">
				<h3>Bookkeeping Totals</h3><br/>
				 <table class="zebra-striped">
					<thead>
						<tr>
							<th>Transaction Type</th>
							<th>Transaction Amount</th>
						</tr>
					</thead>
					<tbody>
			<?php	echo '<tr>';
						echo "<td>$desc1</td>";
						/* $donation_amt_reduce and $transaction_fee_reduce are -ve so we are adding for subtracting it */
						$fund_up_amt= $fund_up_amt + $donation_amt_reduce + $transaction_fee_reduce;	
						
						echo "<td>USD ".number_format($fund_up_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc2</td>";
						echo "<td>USD ".number_format($fund_wd_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc9</td>";
						echo "<td>USD ".number_format($gift_purchage_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc12</td>";
						echo "<td>USD ".number_format($transaction_fee_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc11</td>";
						echo "<td>USD ".number_format($donation_amt,2)."</td>";
					echo '</tr>';
					echo'<tr>';
						echo"<td>$desc19</td>";
						echo"<td> USD ".number_format($ConvrtedTodonation_amt, 2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc10</td>";
						echo "<td>USD ".number_format($gift_donate_amt,2)."</td>";
					echo '</tr>';
										echo '<tr>';
						echo "<td>$desc6</td>";
						echo "<td>USD ".number_format($disb_amt,2);
						foreach($bCountries as $bcont) {
							if($bcont['disb_amt_total'] !=0) {
								echo "<br/>".$bcont['Currency']." ".number_format(round_local($bcont['disb_amt_total']),0)." (".$bcont['name'].")";
							}
						}
						echo "</td>";
					echo '</tr>';
					//Anupam 1-9-2013 removed as requested by Julia in email "Update request" 

					/*echo '<tr>';
						echo "<td>$desc16</td>";
						echo "<td>USD ".number_format(($ln_bck_amt-$fee_amt), 2);
						foreach($bCountries as $bcont) {
							$b_prin_int= round(($bcont['ln_bck_amt_total']- $bcont['fee_amt_total']),4);
							if($b_prin_int !=0) {
								echo "<br/>".$bcont['Currency']." ".number_format(round_local($b_prin_int),0)." (".$bcont['name'].")";
							}
						}
						echo "</td>";
					echo '</tr>';*/
					echo '<tr>';
						echo "<td>$desc14</td>";
						echo "<td>USD ".number_format($reg_fee,2);
						foreach($bCountries as $bcont) {
							if($bcont['reg_fee_amt_total'] !=0) {
								echo "<br/>".$bcont['Currency']." ".number_format(round_local($bcont['reg_fee_amt_total']),0)." (".$bcont['name'].")";
							}
						}
						echo "</td>";
					echo '</tr>';
					echo'<tr>';
						echo"<td>$desc22</td><td>";
						foreach($bCountries as $bcont) {
							if($bcont['ln_bck_pamt_total'] !=0) {
								echo "<br/>".$bcont['Currency']." ".number_format(round_local($bcont['ln_bck_pamt_total']),0)." (".$bcont['name'].")";
							}
						}
						echo"</td>";
					echo '</tr>';
					echo'<tr>';
						echo"<td>$desc23</td><td>";
						foreach($bCountries as $bcont) {
							if($bcont['ln_bck_intamt_total'] !=0) {
								echo "<br/>".$bcont['Currency']." ".number_format(round_local($bcont['ln_bck_intamt_total']),0)." (".$bcont['name'].")";
							}
						}
					echo"</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc5</td>";
						echo "<td>USD ".number_format($fee_amt,2);
						foreach($bCountries as $bcont) {
							if($bcont['fee_amt_total'] !=0) {
								echo "<br/>".$bcont['Currency']." ".number_format($bcont['fee_amt_total'],0)." (".$bcont['name'].")";
							}
						}
						echo "</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc13</td>";
						echo "<td>USD ".number_format($referral_amt,2);
						foreach($bCountries as $bcont) {
							if($bcont['referral_amt_total'] !=0) {
								echo "<br/>".$bcont['Currency']." ".number_format(round_local($bcont['referral_amt_total']),0)." (".$bcont['name'].")";
							}
						}
						echo "</td>";
					echo '</tr>';
			?>		</tbody>
				</table>
				<a href="" name="totalhistry" id="totalhistry"></a>
				<h3>Other Totals</h3><br/>
				<table class="zebra-striped">
					<thead>
						<tr>
							<th>Transaction Type</th>
							<th>Transaction Amount</th>
						</tr>
					</thead>
					<tbody>
			<?php	
					
					echo'<tr>';
						echo"<td>$desc21</td>";
						echo"<td> USD ".number_format($UploadedbyPaypal, 2)."</td>";
					echo '</tr>';
					echo'<tr>';
						echo"<td>$desc20</td>";
						echo"<td> USD ".number_format($UploadedbychcekorBank, 2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc8</td>";
						echo "<td>USD ".number_format($gift_redeem_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc15</td>";
						echo "<td>USD ".number_format($referral_redeem_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc4</td>";
						echo "<td>USD ".number_format($ln_bck_lndr_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc17</td>";
						echo "<td>USD ".number_format($ln_bid_lndr_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc3</td>";
						echo "<td>USD ".number_format($ln_snt_lndr_amt,2)."</td>";
					echo '</tr>';
					echo '<tr>';
						echo "<td>$desc7</td>";
						echo "<td>USD	".number_format($ln_bck_amt,2);
						foreach($bCountries as $bcont) {
							if($bcont['ln_bck_amt_total'] !=0) {
								echo "<br/>".$bcont['Currency']." ".number_format(round_local($bcont['ln_bck_amt_total']),0)." (".$bcont['name'].")";
							}
						}
						echo "</td>";
					echo '</tr>';
					/*echo'<tr>';
						echo"<td>$desc18</td>";
						echo"<td> USD ".number_format($feeByAnonymous, 2)."</td>";
					echo '</tr>';
					*/
			?>		</tbody>
				</table>
			</div>
	<br/>
		<table id="transtable" class="zebra-striped tablesorter_trhistry">
				<thead>
					<tr>
						<th width='80px' align='left'>Date</th>
						<!-- intentionally made <td> tag instead of <th> below, to remove effect of tablesorter just a tricky fix  -->
						<td><strong>Country</strong>
							<select id="country" name="country" onchange='OnChange(this.id);'>
					<?php       $result1 = $database->countryList(true);
								$result2 = array(array('code'=>'ALL', 'name'=>'All Countries'), array('code'=>'US', 'name'=>'United States'));
								$result1 = array_merge($result2, $result1);
								foreach($result1 as $cont) { ?>
									<option value="index.php?p=22&v=1&c=<?php echo $cont['code']; ?>" <?php if($c==$cont['code']) echo "Selected='true'";?>><?php echo $cont['code']?></option>
					<?php       }   ?>
							</select>
						</td>
						<th>Transaction <br>Amount (local)</th>
						<th>Transaction <br>Amount (USD)</th>
						<th>Transaction <br>Principal</th>
						<th>Transaction <br>Interest</th>
						<th>Transaction<br>Description</th>
						<th>Transaction <br>Id</th>
					</tr>
				</thead>
				<tbody>
		<?php		$count1=0;
					$transactionIds=array();
					foreach($set as $row) {
						logger('trhistory display in foreach');
						$userLevel = $database->getUserLevelbyid($row['userid']);
						$date=date('M d, Y',$row['TrDate']);
						$dateTosort = $row['TrDate'];
						$userid=$row['userid'];
						$username=$row['username'];
						if($userid>0) {
							$prurl = getUserProfileUrl($userid);
						}
						$desc = '';
						$country = '';
						$txnamtinus='';
						$txnamt='';
						$txnprincipal = '';
						$txnIntr = '';
						/*Txn description and country stuff start */
						if($row['txn_type'] == FUND_UPLOAD) {
							if($row['txn_sub_type']==UPLOADED_BY_PAYPAL) {
								$desc = "Payment from <a href='$prurl'>$username</a> (PayPal)" ;
							}else if($row['txn_sub_type']==UPLOADED_BY_ADMIN){
								$desc = "Payment from <a href='$prurl'>$username</a> (check or bank transfer)" ;
							}else {
								$desc = "Payment from <a href='$prurl'>$username</a>" ;
							}
							$country = "US";
						} elseif($row['txn_type'] == FUND_WITHDRAW) {
							$desc = "Lender funds withdrawal from <a href='$prurl'>$username</a>";
							$country = "US";
						} elseif($row['txn_type'] == LOAN_SENT_LENDER) {
						   $desc = "Loan from <a href='$prurl'>$username</a> ";
						   $country = "US";
						} elseif($row['txn_type'] == LOAN_BID) {
						   $desc = "Bid on loan from <a href='$prurl'>$username</a> ";
						   $country = "US";
						} elseif($row['txn_type'] == LOAN_OUTBID) {
							if($row['txn_sub_type'] == LOAN_BID_EXPIRED) {
								$desc = "Credit back: expired loan bid to <a href='$prurl'>$username</a> ";
							} else if($row['txn_sub_type'] == LOAN_BID_CANCELED) {
								$desc = "Credit back: cancelled loan bid to <a href='$prurl'>$username</a> ";
							} else {
								$desc = "Credit back from bid on loan to <a href='$prurl'>$username</a> ";
							}
						   $country = "US";
						} elseif($row['txn_type'] == LOAN_BACK_LENDER) {
							if($row['txn_sub_type']==REFERRAL_CREDIT) {
								$desc = "Referral Program Credit to <a href='$prurl'>$username</a>";
							} else {
								$desc = "Loan repayment credit to <a href='$prurl'>$username</a>";
							}
							$country = "US";
						} elseif($row['txn_type'] == FEE) {
							$uname = $database->getNameById($row['userid']);
							$desc ="Borrower Transaction Fee from "."<a href='$prurl'>$uname</a>";
							$country = $row['country'];
							$txnamt=number_format(round_local(($row['amount'] * $row['conversionrate'])),0);
							$txnamt=$txnamt." (".$row['currency'].")";
							$txnamtinus=number_format($row['amount'],2);
						} elseif($row['txn_type'] == DISBURSEMENT) {
							$desc = "Loan Principal Disbursement to <a href='$prurl'>$username</a> ";
							$country = $row['country'];
						} elseif($row['txn_type'] == LOAN_BACK) {
							$desc = "Loan repayment installment from <a href='$prurl'>$username</a>";
							$country = $row['country'];
							//2012-12-27 Anupam below code added to show interest and principal amount in the loan installment 
							$ratio = $database->getPrincipalRatio($row['loanid'],$row['TrDate']);
							$resdate = $database->getRescheduleDateByLoan($row['loanid']);
							$paymentonsameday=0;
							if(!empty($resdate)){
								if(abs($resdate-$row['TrDate'])< 86400) {
									$ratio = $database->getCurrentPrincipalRatio($row['loanid']);
									$paymentonsameday = 1;
								}
							}
							logger('trhistory display in foreach2 in loanback');
							$rate=$database->getExRateById($row['TrDate'],$row['userid']);
							$pInCurrInstallment = $row['amount']*$ratio;
							$interest=$row['amount']-$pInCurrInstallment;
							$lonedata=$database->getLoanfund($row['userid'], $row['loanid']);
							$finalrate=$lonedata['finalrate'];
							$webfee=$lonedata['WebFee'];
							$value = $interest/($finalrate + $webfee);
							$int=0;
							$int=$value * $finalrate;
							$fee = $value*$webfee;
							$feetxn = $database->getprevfeetxn($row['id']);
							$brwrtrfee = $feetxn['amount']*$feetxn['conversionrate'];
							logger('trhistory display in foreach3 in loanback');
							if(abs($fee-$brwrtrfee) >1) {
								$int=$row['amount']-$pInCurrInstallment-$brwrtrfee;
								if($int<0) {
									$borrowerid = $row['userid'];
									$paidd = $row['TrDate'];
									$loanid = $row['loanid'];
									$amount = $row['amount'];
									$totalOldFee = $database->getWebsiteFeeTotalbydate($loanid,$feetxn['id']);
									/*  get loanapplic data(row) of this loan id    */
									$lonedata=$database->getLoanfund($borrowerid, $loanid);
									$loanAmt=$lonedata['AmountGot']; /*The amount entered by admin on disbursement */
									$rate=$lonedata['finalrate'];   /*  Avearege interest rate of all lenders   */
									$feerate = $lonedata['WebFee'];
									$extra_period = $database->getLoanExtraPeriod($borrowerid, $loanid);
									$period=$lonedata['period'] + $extra_period;    /* Actual repayment perieds which do not includes grace periods */
									$grace=$lonedata['grace'];  /* grace periods before repayment starts */

									$feelender=((($period)*$loanAmt*($rate))/1200); /* total interest amount of lenders for this loan */
									$feeamount_org=((($period)*$loanAmt*($feerate))/1200);/* zidisha fee amount for this loan */
									$tamount_org=$loanAmt + $feelender + $feeamount_org; /* Total amount to be pay for by borrower */

									$totalPayment = $database->getTotalPaymentbydate($borrowerid, $loanid,$row['id']);
									$forgiveAmount= $database->getForgiveAmountbydate($borrowerid,$loanid,$paidd);
									$feeamount = round(((($feeamount_org - $totalOldFee) * $amount) / ($tamount_org - $totalPayment['paidtotal'] - $forgiveAmount)),4);
									$tamount=$loanAmt + $feelender;
									$pInCurrInstallment = ($loanAmt/$tamount) * ($amount-$feeamount);
									
									$int = $amount-$pInCurrInstallment-$feeamount; 
									if(abs($feeamount-$brwrtrfee)>1) {
										$int = $amount-$pInCurrInstallment-$brwrtrfee;
										if($int<0) {
											$int=0;
										}
									}
								}
							}
							$txnprincipal = number_format(round_local($pInCurrInstallment),0)." (".$row['currency'].")";
							$txnIntr = number_format(round_local($int),0)." (".$row['currency'].")";														
						} elseif($row['txn_type'] == GIFT_REDEEM) {
							$desc = "Gift Card Redemption by <a href='$prurl'>$username</a>";
							$country = "US";
						} elseif($row['txn_type'] == GIFT_PURCHAGE) {
							$txnamtinus = number_format($row['amount'],2);
							if($row['userid']==0) {
								$desc = "Gift Card Purchase from non logged in user";
							}else {
								$uname = $database->getNameById($row['userid']);
								$desc = "Gift Card Purchase from ". 
									"<a href='$prurl'>$uname</a>";
							}
							$country = "US";
						} elseif($row['txn_type'] == GIFT_DONATE) {
							$desc = "Gift Card Donation";
							$country = "US";
							$txnamtinus = number_format($row['amount'],2);
						} elseif($row['txn_type'] == DONATION && isset($row['txn_sub_type']) && $row['txn_sub_type'] != DONATE_BY_ADMIN) {
							$txnamtinus = number_format($row['amount'],2);
							if($txnamtinus >0) {
								$desc = "Lender Donation to Zidisha";
								$email=$database->getDonationEmailByTransactionId($row['id']);
								if(!empty($email)) {
									$desc = "Lender Donation to Zidisha from ".$email;
								}
							} else {
								$desc = "Lender Donation from <a href='$prurl'>$username</a>";
							}
							$country = "US";
						} elseif($row['txn_type'] == PAYPAL_FEE) {
							$txnamtinus = number_format($row['amount'],2);
							$desc = "Lender Transaction Fee from <a href='$prurl'>$username</a>";
							if($txnamtinus >0) {
								$desc = "Lender Transaction Fee to Zidisha";
							}
							$country = "US";
						} elseif($row['txn_type'] == REFERRAL_DEBIT) {
							$desc ="Referral Program Debit from Admin";
							$country = $row['country'];
							$txnamt=number_format(round_local(($row['amount'] * $row['conversionrate'])),0);
							$txnamt=$txnamt." (".$row['currency'].")";
							$txnamtinus=number_format($row['amount'],2);
						} elseif($row['txn_type'] == REGISTRATION_FEE) {
							if($row['amount'] >0) {
								$desc ="Registration Fee";
								$country = "US";
								$txnamtinus=number_format(($row['amount'] / $row['conversionrate']),2);
							} else {
								
								$desc = "Registration Fee from <a href='$prurl'>$username</a>";
								$country = $row['country'];
							}
						} elseif($row['txn_type'] == REFERRAL_CODE) {
							$desc = "Referral Code Redemption by <a href='$prurl'>$username</a>" ;
							$country = "US";
						}else if(isset($row['txn_type']) && $row['txn_type'] == 'AmountCredited') {
							$desc = "Lending credit to <a href='$prurl'>$username</a>" ;
							$country = "US";
						}elseif($row['txn_type'] == DONATION && isset($row['txn_sub_type']) && $row['txn_sub_type'] == DONATE_BY_ADMIN) {
							$txnamtinus = number_format($row['amount'],2);
							$desc = "Expired Lender Account Converted to Donation";
							$country = "US"; 
						}
						logger('trhistory display txn description end');
							
						/*Txn description and country stuff end */
						//if($row['userlevel']==LENDER_LEVEL) 
						if($userLevel==LENDER_LEVEL) 
						{
							$txnamtinus=number_format($row['amount'],2);
							$txnid = "<a href='index.php?p=16&u=$userid' target='_blank'>".$row['id']."</a>";
						} 
						//else if($row['userlevel']==BORROWER_LEVEL )
						else if($userLevel ==BORROWER_LEVEL )	{
							$txnamt=number_format(round_local($row['amount']),0);
							$txnamt=$txnamt." (".$row['currency'].")";
							$txnamtinus=number_format(($row['amount'] / $row['conversionrate']),2);
							$loanid = $row['loanid'];
							$txnid = "<a href='index.php?p=37&l=$loanid&u=$userid' target='_blank'>".$row['id']."</a>";
						} 
						//else if($row['userlevel']==null || $row['userlevel']=="") 
						else if($userLevel==null || $userLevel==""){
							$txnamtinus=number_format($row['amount'],2);
							$txnid = $row['id'];
						}else {
							$txnid = $row['id'];
						}
						if($row['txn_type'] == FEE) {
							$loanid = $row['loanid'];
							$txnid = "<a href='index.php?p=37&l=$loanid&u=$userid' target='_blank'>".$row['id']."</a>";
						}
						logger('trhistory display txnid'.$txnid);
						if(strtoupper($c)=="ALL" || strtoupper($c)==strtoupper($country)) {
							$txnamt = str_replace('-', '', $txnamt);
							$txnamtinus = str_replace('-', '', $txnamtinus);//we just remove the (-) negative sign if any amount had,just to display as positive amount.
							echo '<tr>';
							echo "<td><span style='display:none'>$dateTosort</span>$date</td>";
							echo "<td>$country</td>";
							echo "<td>$txnamt</td>";
							echo "
							<td>$txnamtinus</td>";
							echo "<td>$txnprincipal</td>";
							echo "<td>$txnIntr</td>";
							echo "<td>$desc</td>";
							echo "<td>".$txnid."</td>";
							echo '</tr>';
							$count1++;
							$transactionIds[]=$row['id'];
						}

					}   
?>
				</tbody>
			</table>
				<!--<div><a href = 'javascript:void()' id='showallrecords' onclick="pager.showAll()">View all</a>
			<a href = 'javascript:void()' id='showlessrecords' style="display:none" onclick="showless()">View Less</a>
			</div> --->
			<div id="pageNavPosition" align='center'></div>
			<!--- Now Total records link will be display on top --->
			<div align='right'><font color='blue'>Total Records  <?php echo $count1; ?></font></div>
			<script type="text/javascript">
				var pager = new Pager('transtable', 100000000000); 
				pager.init();
				//pager.showPageNav('pager', 'pageNavPosition');
				pager.showPage(1);
			</script>
			<br/><br/>
			

			<a href="#"> Back to top </a>
<?php   } else {
			echo "<strong>No Data</strong>";
		}
	}   ?>
	<script language="javascript">
	function tablesort(opt,c,type)
	{
		<?php 
			$type =0;
			if(isset($_GET['type'])) 
				$type =$_GET['type'];
		?>
		if(type ==  <?php echo $type ?>){		
			if('ASC'=='<?php echo $ord; ?>'){
				window.location = 'index.php?p=22&v=1&ord=DESC&opt='+opt+'&c='+c+'&type='+type
			}
			else{
				window.location = 'index.php?p=22&v=1&ord=ASC&opt='+opt+'&c='+c+'&type='+type
			}		
		}else{
				window.location = 'index.php?p=22&v=1&ord=ASC&opt='+opt+'&c='+c+'&type='+type
		}
	}
	</script>
	<script language="javascript">
	function OnChange(dropdown)
	{
		var selObj = document.getElementById(dropdown);
		var selIndex = selObj.selectedIndex;
		var baseURL  = selObj.options[selIndex].value;
		top.location.href = baseURL;
		return true;
	}
	</script>
<?php
}
else
{
	echo "<div>";
	echo $lang['admin']['allow'];
	echo "<br />";
	echo $lang['admin']['Please'];
	echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
	echo "</div>";
}   ?>
</div>
<script type="text/javascript">
<!--
	function showless() {
		document.getElementById('showallrecords').style.display='';
		document.getElementById('showlessrecords').style.display='none';
		document.getElementById('pageNavPosition').style.display='';
		pager.showPage(1);
	}
//-->
</script>