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
if($session->userlevel  == BORROWER_LEVEL && isset($_SESSION['rescheduleDetail']))
{
	if(isset($_SESSION['failedResch'])){
		echo "<div align='left'><font color=red><b>Sorry we were unable to reschedule youn loan. Please contact us at service@zidisha.org for furthur assistance.</b></font></div>";
		unset($_SESSION['failedResch']);
	}
	
	$installment_amount=$_SESSION['rescheduleDetail']['installment_amount'];
	$installment_date=$_SESSION['rescheduleDetail']['installment_date'];
	$new_repay_period=$_SESSION['rescheduleDetail']['possible_periods'];
	$original_period=$_SESSION['rescheduleDetail']['original_period'];
	$reschedule_reason=$_SESSION['rescheduleDetail']['reschedule_reason'];
	$ud=$session->userid;
	if(isset($_GET['l']))
	{
		$ld=$_GET['l'];
	}
	$brw2 = $database->getLoanDetails($ld);
	if($brw2['active'] == LOAN_ACTIVE)
	{
		$amount=$brw2['AmountGot'];
		$tmpcurr = $database->getUserCurrency($ud);
		$rate=$brw2['finalrate'];
		$extraPeriod=$database->getLoanExtraPeriod($ud, $ld);
		$period=$brw2['period'];
		$newPeriod=$period+$extraPeriod;
		$gperiod=$brw2['grace'];
		$fee=$brw2['WebFee'];
		$weekly_inst=$brw2['weekly_inst'];
		if($weekly_inst == 1) {
			$conversion=5200;
			if($gperiod <2)
				$gperiodText=$lang['loanstatn']['week'];
			else
				$gperiodText=$lang['loanstatn']['weeks'];
			if($period <2)
				$periodText=$lang['loanstatn']['week'];
			else
				$periodText=$lang['loanstatn']['weeks'];
		} else {
			$conversion=1200;
			if($gperiod <2)
				$gperiodText=$lang['loanstatn']['month'];
			else
				$gperiodText=$lang['loanstatn']['months'];
			if($period <2)
				$periodText=$lang['loanstatn']['month'];
			else
				$periodText=$lang['loanstatn']['months'];
		}
		$feeamount=((($new_repay_period)*$amount*($fee))/$conversion);
		$feelender=((($new_repay_period)*$amount*($rate))/$conversion);
		$totFee=$feeamount + $feelender;
		$feeamountOrg=((($newPeriod)*$amount*($fee))/$conversion);
		$feelenderOrg=((($newPeriod)*$amount*($rate))/$conversion);
		$totFeeOrg=$feeamountOrg + $feelenderOrg;
		$totalamt=$database->getTotalPayment($ud, $ld);
		$schedule = $session->generateReSchedule($ud, $ld,$new_repay_period, $installment_amount, $installment_date);
		$totalamtdueNew= $schedule[0]['fullTotal'];
		$interestrate = $database->getAvgBidInterest($ud, $ld);
		$totIntr=$interestrate + $fee;


		if(empty($schedule))
		{
		}
		else
		{	?>
			<div class="row">
				<div>
					<h3 class="subhead"><?php echo $lang['loanstatn']['re_schedule'] ?></h3>
					<p><strong><?php echo $lang['loanstatn']['imp_note'] ?>: <?php echo $lang['loanstatn']['reschedule_note'] ?></strong></p>
					<br/>
					<table class="detail">
						<tbody>
							<tr>
								<td width="250"><strong><?php echo $lang['loanstatn']['loan_pri_disb'] ?>:</strong></td>
								<td><?php echo number_format(round_local($brw2['AmountGot']),0,'.',',')." ".$tmpcurr; ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['org_pd'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_pd'] ?></span><span class='bottom'></span></span></a></strong></td>
								<td><?php echo $period ?> <?php echo $periodText ?></td>
							</tr>
							<!-- <tr>
								<td><strong><?php echo $lang['loanstatn']['gpd'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_gpd']?></span><span class='bottom'></span></span></a></strong></td>
								<td><?php echo $gperiod ?> <?php echo $gperiodText ?></td>
							</tr> -->
							<tr>
								<td><strong><?php echo $lang['loanstatn']['tot_int_and_fee'] ?>:</strong></td>
								<td><?php echo number_format(round_local($totFeeOrg),0, '.', ',')." ".$tmpcurr." (".number_format($totIntr, 0,'.',',')."% ".$lang['loanstatn']['intrst_rate_text']. ' '.$period .' '.$periodText.")"; ?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['new_pd'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_pd'] ?></span><span class='bottom'></span></span></a></strong></td>
								<td><?php echo $new_repay_period ?> <?php echo $periodText ?></td>
							</tr>
							<tr><td colspan=2><br/></td></tr>
							<tr>
								<td><strong><?php echo $lang['loanstatn']['tot_int_and_fee_new'] ?>:</strong></td>
								<td><?php echo number_format(round_local($totFee),0, '.', ',')." ".$tmpcurr." (".number_format($totIntr, 0,'.',',')."%
								".$lang['loanstatn']['intrst_rate_text']. ' '.$new_repay_period .' '.$periodText.")"; ?></td>
							</tr>
							<tr>
									<td><strong><?php echo $lang['loanstatn']['tot_repay_due_new'] ?>:</strong></td>
									<td><?php echo number_format(round_local($totalamtdueNew),0, '.', ','); ?></td>
								</tr>
							<tr><td colspan=2><br/></td></tr>
							<tr>
								<td colspan=2><strong><?php echo $lang['loanstatn']['new_repay_schedule'] ?>:</strong></td>
							</tr>
						</tbody>
					</table>
					<table class='zebra-striped'>
						<thead>
							<tr>
								<th><strong><?php echo $lang['loanstatn']['date'] ?></strong></th>
								<th><strong><?php echo $lang['loanstatn']['due_amount'] ?> (USD)</strong></th>
								<th><strong><?php echo $lang['loanstatn']['datepaid'] ?></strong></th>
								<th><strong><?php echo $lang['loanstatn']['paid_amount'] ?> (USD)</strong></th>
							</tr>
						</thead>
						<tbody>
				<?php		$amtDueTill = 0;
							$amtPaidTill = 0;
							$amtDue =0;
							$amtPaid =0;
							for($i = 0; $i < count($schedule) ; $i++  )
							{
								$tempama=$schedule[$i]['amount'] ;
								$tempama1=$schedule[$i]['paidamt'];
								$dp='';
								if(isset($schedule[$i]['paiddate']))
									$dp = date('M d, Y',$schedule[$i]['paiddate']);
								$amtpd='';
								if(isset($schedule[$i]['paidamt']))
								{
									$amtpd =number_format(round_local($tempama1), 0, '.', ',');
									$amtPaid += $tempama1;
								}
								$amtDue += $tempama;
							?>
								<tr>
									<td><?php echo date('M d, Y',$schedule[$i]['duedate']);?></td>
									<td><?php echo number_format(round_local($tempama), 0, '.', ',');?></td>
									<td><?php echo $dp;?></td>
									<td><?php echo $amtpd;?></td>
								</tr>
					<?php	}	?>
						</tbody>
						<tfoot>
							<tr>
								<th><?php echo $lang['loanstatn']['tot_amount'];?></th>
								<th><?php echo number_format(round_local($amtDue), 0, '.', ',');?></th>
								<th><?php echo $lang['loanstatn']['tot_paid_amount'];?></th>
								<th><?php echo number_format(round_local($amtPaid), 0, '.', ',');?></th>
							</tr>
						</tfoot>
					</table>
					<form method="post" action="updateprocess.php" onsubmit="disablemultiplesubmit();">
			<?php		$_SESSION['value_array']=$form->values;
						$_SESSION['error_array']=$form->getErrorArray();
			?>
					<table class="detail">
						<tbody>
							<tr style='display:none'>
								<td>
									<input type='hidden' name='original_period' value='<?php echo $original_period;?>'>
									<input type='hidden' name='new_period' value='<?php echo $new_repay_period;?>' />
									<input type='hidden' name='period' value='<?php echo $period;?>'>
									<input type='hidden' name='loanid' value='<?php echo $ld;?>'>
									<input type='hidden' name='reScheduleLoan' value='reScheduleLoan' >
									<input type="hidden" name="user_guess" value="<?php echo generateToken('reScheduleLoan'); ?>"/>
									<input type='hidden' name='confirmReScheduleLoan' >
									<textarea name='reschedule_reason' rows='6' cols='70' style='visiblity:hidden'><?php echo $reschedule_reason;?></textarea>
								</td>
							</tr>
							<tr>
								<td><input class='btn' type=button value='<?php echo $lang['loanstatn']['r_go_back'];?>' onclick='window.location="index.php?p=41&l=<?php echo $ld;?>"'></td>
								<td><input class='btn' id='reschedule_submit' type='submit' value='<?php echo $lang['loanstatn']['r_confirm'];?>' onclick="this.disabled=1 this.form.submit();"></td>
							</tr>
						</tbody>
					</table>
					</form>
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
<script type="text/javascript">
<!--
	function disablemultiplesubmit() {
		document.getElementById("reschedule_submit").disabled = true;
	}
//-->
</script>