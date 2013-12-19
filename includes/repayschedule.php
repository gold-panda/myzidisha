<?php
include_once("library/session.php");
include_once("./editables/loanstatn.php");
$path=	getEditablePath('loanstatn.php');
include_once("editables/".$path);
?>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
<script type="text/javascript">
	$(document).ready(function() {
	$('#repay_schd_heading').click(function() {
			$('#repay_schd_desc').slideToggle("slow");
			$(this).toggleClass("active"); return false;
	});
});
</script>
<div class="span12">
<?php
// allow admin to view repayment schedule as logged-in borrower can view. 
if($session->userlevel  == BORROWER_LEVEL || $session->userlevel  == ADMIN_LEVEL || $session->userlevel  == LENDER_LEVEL || $session->userlevel  == PARTNER_LEVEL)
{
	$ud=$session->userid;
	if(isset($_GET['l']))
	{
		$ld=$_GET['l'];
	}
	if($session->userlevel  == ADMIN_LEVEL || $session->userlevel  == LENDER_LEVEL || $session->userlevel  == PARTNER_LEVEL) {
		if(isset($_GET['u']))
			$ud = $_GET['u'];
	}
	$brw2 = $database->getLoanDetails($ld);
	$amount=$brw2['AmountGot'];
	$disburseDate=$database->getLoanDisburseDate($ld);
	$UserCurrency = $database->getUserCurrency($ud);
	$UserCurrencyName = $database->getUserCurrencyName($ud);
	$tmpcurr=$UserCurrency;

	$CurrencyRate = $database->getCurrentRate($ud);
	$bfrstloan=$database->getBorrowerFirstLoan($ud);

	$lonedata=$database->getLoanfund($ud, $ld);

	$rate=$lonedata['finalrate'];
	$period=$lonedata['period'];
	$gperiod=$lonedata['grace'];
	$fee=$lonedata['WebFee'];
	$webfee=$brw2['WebFee'];
	$extraPeriod=$database->getLoanExtraPeriod($ud, $ld);
	$newperiod=$extraPeriod+$period;
	$feeamount=((($newperiod)*$amount*($fee))/1200);
	$feelender=((($newperiod)*$amount*($rate))/1200);
	$interestrate = $database->getAvgBidInterest($ud, $ld);
	if($gperiod <2)
		$gperiodText=$lang['loanstatn']['month'];
	else
		$gperiodText=$lang['loanstatn']['months'];
	if($period <2)
		$periodText=$lang['loanstatn']['month'];
	else
		$periodText=$lang['loanstatn']['months'];

	$lamount=convertToNative($brw2['reqdamt'], $CurrencyRate);
	$interest=$brw2['interest'] - $webfee;
	$totToPayBack = 0;
	$totFee = 0;
	if($brw2['active']==LOAN_OPEN || $brw2['active']==LOAN_FUNDED){
		$totToPayBack = $lamount +($lamount * ($newperiod)* ($interest + $webfee))/1200;
		$totFee = $interest + $webfee ;

	}
	else{
		$totToPayBack = $brw2['AmountGot'] +($brw2['AmountGot'] * $newperiod * ($interestrate + $webfee))/1200;

		$totFee = $interestrate + $webfee ;

	}
	if(!$bfrstloan)
	{
		 $currency_amt=$database->getReg_CurrencyAmount($ud);
		 foreach($currency_amt as $row)
		 {
			$b_reg_fee_native=number_format($row['Amount'],2);
		 }
	}
	$interestrate = number_format($database->getAvgBidInterest($ud, $ld), 2, '.', ',');
	if($brw2['active']==LOAN_FUNDED)
	{
		$loneAcceptDate=time();
		$interestrate=number_format($interestrate, 2, '.', ','); 	
		$sched=$session->getSchedule($lamount, $interestrate + $webfee, $period, $gperiod,$loneAcceptDate,$webfee, $extraPeriod);
		
?>
		<div class="row">
			<div>
				<h3 class="subhead"><?php echo $lang['loanstatn']['repament_schedule'] ?></h3>
				<?php echo $sched; ?>
			</div><!-- /bid-table -->
		</div><!-- /row -->
<?php
	}
	else if($brw2['active'] == LOAN_ACTIVE || $brw2['active']==LOAN_DEFAULTED || $brw2['active']==LOAN_REPAID)
	{
		$schedule = $session->generateScheduleTable($ud, $ld, 1);
		if(empty($schedule['schedule']))
		{
		}
		else
		{
	?>
			<div class="row">
				<div>
					<h3 class="subhead"><?php echo $lang['loanstatn']['repament_schedule'] ?></h3>
					<br/>
					<p><strong><?php echo $lang['loanstatn']['amt_past_due']." ".date("d M Y ",time()).": ".number_format(round_local($schedule['due']), 0, '.', ',')."</b> ".$UserCurrencyName; ?></strong></p>
					<br/>
					<p><?php echo $lang['loanstatn']['amt_remain_topay'].": ".number_format(round_local($schedule['amtRemaining']), 0, '.', ',')."</b> ".$UserCurrencyName; ?></p>
					<br/>
					<p><a href="index.php?p=71&u=<?php echo $ud ?>"><?php echo $lang['loginform']['view_repay_ins'] ?></a></p>
					<br/>
					<table class="detail">
						<tbody>
							<tr>
								<td width="250"><strong><?php echo $lang['loanstatn']['loan_pri_disb'] ?>:</strong></td>
								<td><?php echo number_format($brw2['AmountGot'],0,'',',')." ".$tmpcurr; ?></td>
							</tr>
							<tr>
								<td width="250"><strong><?php echo $lang['loanstatn']['date_disb'] ?>:</strong></td>
								<td><?php echo date('M d, Y',$disburseDate); ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['pd'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_pd'] ?></span><span class='bottom'></span></span></a></strong></td>
								<td><?php echo $period ?> <?php echo $periodText ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['gpd'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_gpd']?></span><span class='bottom'></span></span></a></strong></td>
								<td><?php echo $gperiod ?> <?php echo $gperiodText ?></td>
							</tr>
							<?php if(!$bfrstloan){	?>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['b_reg_fee'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_webfee']?></span><span class='bottom'></span></span></a></strong></td>
								<td><?php echo $b_reg_fee_native." ".$tmpcurr; ?></td>
							</tr>
							<?php }	?>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['tot_int_due_lend'] ?>:</strong></td>
								<td><?php echo number_format(round_local($feelender),0, '.', ',')." ".$tmpcurr." (".$interestrate."%)"; ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['br_trn_fee'] ?>:</strong></td>
								<td><?php echo number_format(round_local($feeamount), 0, '.', ',')." ".$tmpcurr." (".number_format($webfee, 2,'.',',')."%)"; ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['tba'] ?>:</strong></td>
								<td><?php echo number_format(round_local($totToPayBack), 0)." ".$tmpcurr." (". number_format( $totFee , 2, '.', ',')."%)"; ?></td>
							</tr>
							<!--<tr>
								<td><strong><?php echo $lang['loanstatn']['repay_due']." ".date("M d, Y ",time()) ?>:</strong></td>
								<td><?php echo number_format(round_local($schedule['due']), 0, '.', ',')." ".$tmpcurr; ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['totrepay_due']." ".date("M d, Y ",time()) ?>:</strong></td>
								<td><?php echo number_format(round_local($schedule['amtPaidTillShow']), 0, '.', ',')."</b> ".$tmpcurr; ?></td>
							</tr>-->
						</tbody>
					</table>
					<?php echo $schedule['schedule']; ?>
				</div><!-- /bid-table -->
			</div><!-- /row -->			
<?php	}
	}
	else
	{
		echo "No Transaction Yet";
	}
}
?>
</div>