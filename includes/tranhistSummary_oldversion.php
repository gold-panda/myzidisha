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
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
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
		<div style="float:left"><div align='left' class='static'><h1>Transaction History Totals</h1></div></div>
		<?php if($session->userlevel==ADMIN_LEVEL ){?>

<!-- 
			<div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			</div>

-->

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
						<input type="hidden" name="transactionhistorySummary" id="transactionhistorySummary">
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
		//$set=$database->trhistory($date1, $date2,$ord,$opt);
		//logger('trhistory after cal in database');
		if(1) {
			//logger('trhistory not empty'.count($set));
?>
<!--
	<div align="right" >
		<input id="totalHistory" class='btn' type="button" value="Show Total History">
	</div>
-->
		<?php
			$set1=$database->trhistorytotal($date1, $date2);
			logger('set1 from database'.count($set1));
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
			foreach($set1 as $row1) {
				//logger('set1 in foreach');
					$userLevel = $database->getUserLevelbyid($row1['userid']);
					//if($row1['userlevel']==BORROWER_LEVEL) 
					if( $userLevel == BORROWER_LEVEL){
						$tranamt=round(($row1['amount'] / $row1['conversionrate']),4);
					} else {
						$tranamt=$row1['amount'];
					}
					//logger('tranamt calculate');
					if($row1['txn_type'] == FUND_UPLOAD) {
						$fund_up_amt += $tranamt;
						//logger('fund upload');
					} elseif($row1['txn_type'] == FUND_WITHDRAW) {
						$fund_wd_amt += $tranamt;
						//logger('fund withdraw');
					} elseif($row1['txn_type'] == LOAN_SENT_LENDER || $row1['txn_type'] == LOAN_BID || $row1['txn_type'] == LOAN_OUTBID) {
						//logger('other transaction'.$count);
						if(($row1['txn_type'] == LOAN_BID || $row1['txn_type'] == LOAN_OUTBID) && in_array($row1['loanid'], $openLoanIds)) {
							$ln_bid_lndr_amt += $tranamt;
						} else {
							$ln_snt_lndr_amt += $tranamt;
						}
					} elseif($row1['txn_type'] == LOAN_BACK_LENDER) {
						$ln_bck_lndr_amt += $tranamt;
						//logger('loan back lender');
					} elseif($row1['txn_type'] == FEE) {
						$fee_amt += $tranamt;
						$bCountry=$database->getBorrowerCountryByLoanid($row1['loanid']);
						for($k=0; $k<count($bCountries); $k++) {
							if($bCountries[$k]['Country']==$bCountry) {
								$bCountries[$k]['fee_amt_total'] +=$row1['amount'] * $row1['conversionrate'];
							}
						}
					} elseif($row1['txn_type'] == DISBURSEMENT) {
						$disb_amt += $tranamt;
						//logger('DISBURSEMENT');
						 for($k=0; $k<count($bCountries); $k++) {
							if($bCountries[$k]['Country']==$row1['Country']) {
								$bCountries[$k]['disb_amt_total'] +=$row1['amount'];
							}
						}
					} elseif($row1['txn_type'] == LOAN_BACK) {
						$ln_bck_amt += $tranamt;
						//logger('LOAN_BACK');
						for($k=0; $k<count($bCountries); $k++) {
							if($bCountries[$k]['Country']==$row1['Country']) {
								$bCountries[$k]['ln_bck_amt_total'] +=$row1['amount'];
							}
						}
						$ratio = $database->getPrincipalRatio($row1['loanid'], $row1['TrDate']);
						$resdate = $database->getRescheduleDateByLoan($row1['loanid']);
						$paymentonsameday1=0;
						if(!empty($resdate)){
							//logger('resdate');
							if(abs($resdate-$row1['TrDate'])< 86400) {
								$ratio = $database->getCurrentPrincipalRatio($row1['loanid']);
								$paymentonsameday1 = 1;
							}
						}
						$rate=$database->getExRateById($row1['TrDate'],$row1['userid']);
						$PrincipalInCurrInstallment = $row1['amount']*$ratio;	
						//logger('PrincipalInCurrInstallment');

						$interest=$row1['amount']-$PrincipalInCurrInstallment;
						$lonedata=$database->getLoanfund($row1['userid'], $row1['loanid']);
						$finalrate=$lonedata['finalrate'];
						$webfee=$lonedata['WebFee'];
						$value = $interest/($finalrate + $webfee);
						$int=$value * $finalrate;
						$fee = $value*$webfee;
							$feetxn = $database->getprevfeetxn($row1['id']);
							$brwrtrfee = $feetxn['amount']*$feetxn['conversionrate'];
							if(abs($fee-$brwrtrfee) >1) {
								$int=$row1['amount']-$PrincipalInCurrInstallment-$brwrtrfee;
								if($int<0) {
									$borrowerid = $row1['userid'];
									$paidd = $row1['TrDate'];
									$loanid = $row1['loanid'];
									$amount = $row1['amount'];
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
									
									$totalPayment = $database->getTotalPaymentbydate($borrowerid, $loanid,$row1['id']);
									$forgiveAmount= $database->getForgiveAmountbydate($borrowerid,$loanid,$paidd);
									$feeamount = round(((($feeamount_org - $totalOldFee) * $amount) / ($tamount_org - $totalPayment['paidtotal'] - $forgiveAmount)),4);
									$tamount=$loanAmt + $feelender;
									$PrincipalInCurrInstallment = ($loanAmt/$tamount) * ($amount-$feeamount);
									$int = $amount-$PrincipalInCurrInstallment-$feeamount; 
									if(abs($feeamount-$brwrtrfee)>1) {
										$int = $amount-$PrincipalInCurrInstallment-$brwrtrfee;
										if($int<0) {
											$int=0;
										}
									}
								}
							}
						
						for($k=0; $k<count($bCountries); $k++) {
							if($bCountries[$k]['Country']==$row1['Country']) {
								$bCountries[$k]['ln_bck_intamt_total'] +=$int;
								$bCountries[$k]['ln_bck_pamt_total'] +=$PrincipalInCurrInstallment;
								
							}
						}
					
					} elseif($row1['txn_type'] == GIFT_REDEEM) {
						$gift_redeem_amt += $tranamt;
					} elseif($row1['txn_type'] == GIFT_PURCHAGE) {
						if($tranamt > 0) {
							$gift_purchage_amt += $tranamt;
						}
					} elseif($row1['txn_type'] == GIFT_DONATE) {
						$gift_donate_amt += $tranamt;
					} elseif($row1['txn_type'] == DONATION && $row1['txn_sub_type']!=DONATE_BY_ADMIN) {
						if($tranamt >0) {
							$donation_amt += $tranamt;
						} else {
							$donation_amt_reduce +=$tranamt;
						}
					}
					
					elseif($row1['txn_type'] == PAYPAL_FEE){
						if($tranamt >0) {
							$transaction_fee_amt += $tranamt;
						} else {
							$transaction_fee_reduce +=$tranamt;
						}
					}
					elseif($row1['txn_type'] == REFERRAL_DEBIT) {
						$referral_amt += $tranamt;
						$bCountry=$database->getBorrowerCountryByLoanid($row1['loanid']);
						for($k=0; $k<count($bCountries); $k++) {
							if($bCountries[$k]['Country']==$bCountry) {
								$bCountries[$k]['referral_amt_total'] +=$row1['amount'] * $row1['conversionrate'];
							}
						}
					} elseif($row1['txn_type'] == REGISTRATION_FEE) {
						if($tranamt >0) {
							$reg_fee += round(($tranamt / $row1['conversionrate']),4);
						}
						if($row1['amount'] < 0) {
							$bCountry=$database->getBorrowerCountryByLoanid($row1['loanid']);
							for($k=0; $k<count($bCountries); $k++) {
								if($bCountries[$k]['Country']==$bCountry) {
									$bCountries[$k]['reg_fee_amt_total'] +=(-1 * $row1['amount']);
								}
							}
						}
					} elseif($row1['txn_type'] == REFERRAL_CODE) {
						$referral_redeem_amt += $tranamt;
					}
					elseif($row1['txn_type'] == DONATION && $row1['txn_sub_type']==DONATE_BY_ADMIN) {
						if($tranamt >0) {
							$ConvrtedTodonation_amt += $tranamt;
						}
					}
					if($row1['txn_type'] == FUND_UPLOAD && $row1['txn_sub_type'] == UPLOADED_BY_ADMIN) {
						$UploadedbychcekorBank += $tranamt;
					}
					if($row1['txn_type'] == FUND_UPLOAD && $row1['txn_sub_type'] == UPLOADED_BY_PAYPAL) {
						$UploadedbyPaypal += $tranamt;
					} 
					//logger('foreach complete set1 '.$count.' time');
					//$count++;
					//echo $count;

				
			 }  
			 //echo 'hi'; exit;
			
			 ?>
	
<!--		
			<div id="totalHistoryDetails" style="display:none;">
-->
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
<!--			</div>
-->
	<br/>

		
			<div id="pageNavPosition" align='center'></div>
			<!--- Now Total records link will be display on top --->

			<script type="text/javascript">
				var pager = new Pager('transtable', 100000000000); 
				pager.init();
				//pager.showPageNav('pager', 'pageNavPosition');
				pager.showPage(1);
			</script>
			<br/><br/>
			

			
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
				window.location = 'index.php?p=108&v=1&ord=DESC&opt='+opt+'&c='+c+'&type='+type
			}
			else{
				window.location = 'index.php?p=108&v=1&ord=ASC&opt='+opt+'&c='+c+'&type='+type
			}		
		}else{
				window.location = 'index.php?p=108&v=1&ord=ASC&opt='+opt+'&c='+c+'&type='+type
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