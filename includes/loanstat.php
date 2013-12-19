<?php
	include_once("library/session.php");
	
	$sp=0;
	
	if(isset($_GET['s'])){
		$sp=$_GET['s'];
	}
	if($session->userlevel != ADMIN_LEVEL && $session->userlevel != BORROWER_LEVEL){
		echo "You are not allowed to view this page<br />Click <a href='index.php'>here</a> to continue.
			<br /><br />Webmaster";
	}
	$userid=$session->userid;
	$l=$database->getLoanStatus($userid);
?>
<table border="0" style="width:95%">
	<tr>
		<td><b>Loan Status</b></td>
	</tr>
	<tr>
		<td>
		<?php
			if($l==LOAN_OPEN){//0
				echo "You currently have an open loan. The loan bidding progress is as follows<br /><br />";
			}
			else if($l==LOAN_FUNDED){//1
				//echo "Your loan is fully funded. Your repayment schedule is as follows as per current loan interest rate";
			}
			else if($l==LOAN_ACTIVE){//2
				echo "You are currently repaying a loan. Your repayment progress is as follows";
			}
			else if($l==LOAN_REPAID){//3
				echo "Your loan is fully repaid. Lender have commented on your repayment progess.";
			}
			
		?>
		</td>
	</tr>
	<tr>
		<td>
			<div>
				<?php
				if($l==LOAN_OPEN){
					$bids=$database->getLoanBids($userid);
					
					if(empty($bids)){
						
						echo "You currently do no have any bids for your loan application";
					}
					else{
						echo "<table style='width:95%' border='0'>";
						echo "<tr><td colspan='4'>";
						echo "Total Amount Bid: USD. " .number_format($database->getTotalBid($userid), 2, '.', ',')." <br />";
						echo "Total Amount Bid: Kshs. " .number_format((($database->getTotalBid($userid))*($database->getCurrentRate($userid))), 2, '.', ',')." <br />";
						echo "Average Interest Rate: ".number_format($database->getAvgBidInterest($userid), 2, '.', ',')." %<br />";
						echo "Requested Amount: Kshs. ".$database->getOpenLoanAmount($userid).' (USD. '.($database->getOpenLoanAmount($userid)/$database->getCurrentRate($userid)).")";
						$p=((($database->getTotalBid($userid)*($database->getCurrentRate($userid))))/($database->getOpenLoanAmount($userid)))*100 ;
						$p=number_format($p, 2, ".", ",");
						echo "<br />Raised Percentage: ".$p."%";
						echo "</td></tr>";
						echo "<tr><td colspan='4'>Status";
						echo "<table border='0' width='50%' cellspacing='0'  cellpading='0' style='; height:10px; border:#006600 1px solid'><tr><td width='$p%' bgcolor='#006600'></td><td></td></tr></table>";
						echo "</td></tr>";
						echo "<tr><td colspan='4'><table border='0' width='100%' cellspacing='0'cellpadding='0' style='; height:10px; border:#006600 1px solid'><tr><th>Lender Name</th><th>Bid Amount(USD)</th><th>Bid Amount(KSH)</th><th>Bid Interest</th></tr>";
						foreach($bids as $rows ){
							$lname=$rows["Firstname"].' '.$rows['LastName'];
							$bidamount=$rows['bidamount'];
							$kamount=$bidamount*$database->getCurrentRate($userid);
							$bidint=$rows['bidint'];
							$bidamount=number_format($bidamount, 2, ".",",");
							$kamount=number_format($kamount, 2, ".",",");
							$bidint=number_format($bidint, 2, ".",",");
							echo "<tr>";
							echo "<td>$lname</td>";
							echo "<td style='text-align:right'>$bidamount</td>";
							echo "<td style='text-align:right'>$kamount</td>";
							echo "<td style='text-align:right'>$bidint %</td>";
							echo "</tr>";
						}
						echo "</table</td></tr>";
						if($p >= 100.00){
							echo "<form method='post' action='updateprocess.php'><tr><td>";
							echo "<input type='hidden' name='acceptbids' />";
							echo "<input type='submit' value='Accept Bids' /></td></tr></form>";
						}
						else{
							echo "<form><tr><td colspan='4' align='right'><input type='submit' value='Accept Bids' disabled='disabled' /></td></tr></form>";
						}
	 					echo "</table>";
					}
				}else if($l==LOAN_FUNDED){
							
							echo $userid;
							$lendamount=$database->getLoanAmount($userid);
							echo "Your loan is fully funded by the following lenders";
							echo "<table border='0' width='95%' cellspacing='0'cellpadding='0' style='; height:10px; border:#006600 1px solid'>";
							echo "<tr><th style='background-color:#E2C965'>Lender Name</th><th style='background-color:#E2C965'>Taken Amount(USD)</th><th style='background-color:#E2C965'>Taken Amount(KSH)</th><th style='background-color:#E2C965'>Bid Interest</th></tr>";
							foreach($lendamount as $rows){
							$lname=$rows["Firstname"].' '.$rows['LastName'];
							$bidamount=$rows['givenamount'];
							$kamount=$bidamount*$database->getCurrentRate($userid);
							$bidint=$rows['bidint'];
							$bidamount=number_format($bidamount, 2, ".",",");
							$kamount=number_format($kamount, 2, ".",",");
							$bidint=number_format($bidint, 2, ".",",");
							echo "<tr>";
							echo "<td style='text-align:center'>$lname</td>";
							echo "<td style='text-align:center'>$bidamount</td>";
							echo "<td style='text-align:center'>$kamount</td>";
							echo "<td style='text-align:center'>$bidint %</td>";
							echo "</tr>";
						}
						echo "</table>";
						$lonedata=$database->getLoanfund($userid);
							$loanid=$lonedata['loanid'];
							$amount=$lonedata['Amount'];
							$rate=$lonedata['finalrate'];
							$extraPeriod=$database->getLoanExtraPeriod($userid, $loanid);
							$period=$lonedata['period'];
							$newperiod=$extraPeriod+$period;
							$grace=$lonedata['grace'];
							$fee=$database->getAdminSetting('fee');
							$feeamount=((($newperiod)*$amount*($fee))/1200);
							$feelender=((($newperiod)*$amount*($rate))/1200);
							$tamount=$amount+(($newperiod/12)*(($amount*$rate)+($amount*$fee))/100);

							echo "<table style='width:95%' border='1'>";
							echo "<tr><td colspan='4'>";
							echo "Requested Amount: Kshs. ".$amount.' (USD. '.($amount /($database->getCurrentRate($userid))).")<br />";
							echo "Average Interest Rate To Pay: ".number_format($rate, 2, '.', ',')." % <br />";
							echo "Fee Charged by Website: ".$fee."%<br />";
							echo "Fee Charged by Website: Kshs. ".number_format($feeamount, 2, '.', ',').' (USD. '.number_format(($feeamount /($database->getCurrentRate($userid))), 2, '.', ',').")<br />";
							echo "Interest Charged by lenders: Kshs. ".number_format($feelender, 2, '.', ',').' (USD. '.number_format(($feelender /($database->getCurrentRate($userid))), 2, '.', ',').")<br />";
							echo "Period To Pay: ".$period." <br />";
							echo "Grace Time: ".$grace." <br />";			
							echo "Total Payment:Kshs. ".number_format($tamount, 2, '.', ',').' (USD. '.number_format(($tamount /($database->getCurrentRate($userid))), 2, '.', ',').")<br />";			
							echo "</td></tr></table>";
							echo "Your amount will reach to you within 7 Days. After That Your repayment will start";




				}else if($l==LOAN_ACTIVE){
						
							echo "You are currently repaying a loan. Your repayment progress is as follows";
							$lonedata=$database->getLoanfund($userid);
							$loanid=$lonedata['loanid'];
							$amount=$lonedata['Amount'];
							$rate=$lonedata['finalrate'];
							$period=$lonedata['period'];
							$extraPeriod=$database->getLoanExtraPeriod($userid, $loanid);
							$newperiod=$extraPeriod+$period;
							$grace=$lonedata['grace'];
							echo "Your loan is fully funded. Your repayment schedule is as follows as per current loan interest rate of <b>$rate %</b>";
							$text=$session->getSchedule($amount, $rate, $period, $grace);
							echo $text;

				}
				?>
			</div>
		</td>
	</tr>
</table>