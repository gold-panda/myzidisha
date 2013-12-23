<?php
include_once("library/session.php");
include_once("./editables/loanstatn.php");
$path=	getEditablePath('loanstatn.php');
include_once("editables/".$path);
?>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
<div class="span12">
<?php echo $form->error("notreschedule"); ?>
<?php
if($session->userlevel  == BORROWER_LEVEL)
{
	$ud=$session->userid;
	$ld=0;
	if(isset($_GET['l']))
	{
		$ld=$_GET['l'];
	}
	$status = $database->canBorrowerReSchedule($ud, $ld);
	if($status)
	{
		$brw2 = $database->getLoanDetails($ld);
		$totalamt=$database->getTotalPayment($ud, $ld);
		$amtTotal = $totalamt['amttotal'];
		$totalamtdue=$totalamt['amttotal']-$totalamt['paidtotal'];
		$amount=$brw2['AmountGot'];
		$tmpcurr = $database->getUserCurrency($ud);
		$rate=$brw2['finalrate'];
		$period=$brw2['period'];
		$extraPeriod=$database->getLoanExtraPeriod($ud, $ld);
		$newperiod=$extraPeriod+$period;
		$original_period=$brw2['original_period'];
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
		$feeamount=((($newperiod)*$amount*($fee))/$conversion);
		$feelender=((($newperiod)*$amount*($rate))/$conversion);
		$totFee=$feeamount + $feelender;
		$interestrate = $database->getAvgBidInterest($ud, $ld);
		$totIntr=$interestrate + $fee;
		$maxperiodValue_months = $database->getAdminSetting('maxRepayPeriod');
		if ($weekly_inst == 1) {
			$maxperiodValue = $maxperiodValue_months * (52/12);
		} else {
			$maxperiodValue = $maxperiodValue_months;
		}
		$totalrate = $rate + $fee;
		$possibleIns = $session->getMinInstallmentForReschedule($ud, $ld, $amount, $totalrate);
		if($brw2['active'] == LOAN_ACTIVE)
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
						<p><?php echo $lang['loanstatn']['reschedule_text']; ?></p>
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
									<td><?php echo number_format(round_local($totFee),0, '.', ',')." ".$tmpcurr." (".number_format($totIntr, 0,'.',',')."% ".$lang['loanstatn']['intrst_rate_text']. ' '.$period .' '.$periodText.")"; ?></td>
								</tr>
								<tr>
									<td><strong><?php echo $lang['loanstatn']['tot_repay_due_orig'] ?>:</strong></td>
									<td><?php echo number_format(round_local($amtTotal),0, '.', ',')." ".$tmpcurr; ?></td>
								</tr>
								<tr><td colspan=2><br/></td></tr>
								<tr>
									<td colspan=2><strong><?php echo $lang['loanstatn']['org_repay_schedule'] ?>:</strong></td>
								</tr>
							</tbody>
						</table>

						<?php echo $schedule['schedule']; ?>

						<form method="post" action="updateprocess.php">
						<?php $rescdule_allow=$database->getAdminSetting('RescheduleAllow');
									$gracePeriodAllow=$database->getAdminSetting('maxGraceperiodValue');

/* comment by Julia to remove grace period option 11-11-2013
									if($gracePeriodAllow>1){
										echo $lang['loanstatn']['reshdl_note1'].$gracePeriodAllow.' months'.$lang['loanstatn']['reshdl_note2'];
										}else{
										echo $lang['loanstatn']['reshdl_note1'].$gracePeriodAllow.' month'.$lang['loanstatn']['reshdl_note2'];
									}


						?>
						<?php 
							echo "<br/><br/>";
							if($rescdule_allow>1)
								$times=$rescdule_allow.' times.';
							else
								$times=' one time.';
								echo $lang['loanstatn']['rescheduleLimit'].' '.$times;
								echo"<br/><br/>";

*/

						?>

						<table class="detail">
							<tbody><?php 
										$pro_type=$form->value("propose_type");
										$dateselct=0;
										if(!empty($pro_type))
											$dateselct=$pro_type;?>


								<tr style = "display:none">

									<td width='450px;'><strong><?php echo $lang['loanstatn']['selectone']?></strong></td>
									<td>
										<select name='propose_type' id="propose_type" onChange='show_porpose_type(this.value)'>
											<option value='';>
											</option>
											<option value=1 <?php if($dateselct==1) echo "selected='selected'"?>>
												<?php echo $lang['loanstatn']['proposegrperiod']?>
											</option>
											<option value=2 <?php 

/* if($dateselct==2) */

echo "selected='selected'"?>>
												<?php echo $lang['loanstatn']['redinstllment']?>
											</option>
										</select>



										<?php echo $form->error("propose_type");?>



									</td>
									<td><div></div></td>
								</tr>
								<tr><td><br/></td></tr>



								<?php 
									$instllmnt_amt_err=$form->error("installment_amount");

/* 
									if(!empty($instllmnt_amt_err))

*/
										$display='';

/*

									else if($pro_type==2)
										$display='';
									else 
										$display='display:none';

*/
						?>
								<tr id='reduce_amount' style="<?php echo $display?>">
									<td><strong>
										<?php 
										if ($weekly_inst==1){
											
											echo $lang['loanstatn']['installment_amount_wks']." ".$maxperiodValue." ".$lang['loanstatn']['installment_amount2_wks']." ".$possibleIns.".";
										
										}else{
											
											echo $lang['loanstatn']['installment_amount']." ".$maxperiodValue." ".$lang['loanstatn']['installment_amount2']." ".$possibleIns.".";
										
										} ?>
									</strong></td>
									<td>
										<input type='text' id='installment_amount' name='installment_amount' maxlength='10' style='width:106px' class='inputcmmn-1' value='<?php echo $form->value("installment_amount"); ?>' />
								</td>
							</tr>
							<tr id='reduce_amount_err' ><td></td><td colspan='2'><div><?php echo $form->error("installment_amount"); ?></div></td></tr>


								<?php 
								$instllmnt_date_err=$form->error("installment_date");
								if(!empty($instllmnt_date_err))
									$display1='';
								else if($pro_type==1)
									$display1='';
								else
									$display1='display:none';
								?>
								<tr id='propose_gp' style="<?php echo $display1?>">
									<td><strong><?php echo $lang['loanstatn']['installment_date'] ?>:</strong></td>
									<td>
										<?php	
												$allDates= $session->getRescheduleDates($ld);
												$tempdate=$form->value("installment_date");
												$datesel=0;
												if(!empty($tempdate))
													$datesel=$tempdate;
										?>
										<select name="installment_date" id="installment_date" style="width:116px">
											<option value="">Select Period</option>
											<?php 
												foreach($allDates['rescheduleDates'] as $date)
												{	?>
													<option value="<?php echo $date; ?>"<?php if($datesel==$date)echo "Selected='true'"; ?>><?php echo date('M d, Y',$date);?>
													</option>
										<?php 	}	?>
										</select>
									</td>
									<td><div><?php echo $form->error("installment_date"); ?></div></td>
								</tr>
								<tr><td><br/></td></tr>
								<tr>
									<td colspan=3><strong><?php echo $lang['loanstatn']['reason_reschedule'] ?>:</strong></td>
								</tr>
								<tr>
									<td colspan=3><textarea name='reschedule_reason' id="reschedule_reason" style='max-width:620px;width:620px;height:100px'><?php echo $form->value("reschedule_reason");?></textarea></td>
								</tr>
								<tr>
									<td colspan=3><div><?php echo $form->error("reschedule_reason");?></div></td>
								</tr>
								<tr>
									<td colspan=3><strong>Please note:</strong> <?php echo $lang['loanstatn']['submit_text'] ?></td>
								</tr>
								<tr><td><br/></td></tr>
								<tr>
									<input type='hidden' name='period' value='<?php echo $period; ?>'>
									<input type='hidden' name='original_period' value='<?php echo $original_period; ?>'>
									<input type='hidden' name='loanid' value='<?php echo $ld;?>'>
									<input type='hidden' name='reScheduleLoan' value='reScheduleLoan' >
									<input type="hidden" name="user_guess" value="<?php echo generateToken('reScheduleLoan'); ?>"/>
									<td colspan=3><input class='btn' type='submit' onclick="needToConfirm = false;"  value='<?php echo $lang['loanstatn']['r_submit']; ?>'></td>
								</tr>
							</tbody>
						</table>
						</form>
					</div><!-- /bid-table -->
				</div><!-- /row -->
		<?php
			}
		}
		else
		{
			echo "No Transaction Yet";
		}
	}
}
?>
</div>
<script type="text/javascript">
<!--
	function show_porpose_type(type){
		if(type==2){
				document.getElementById("reduce_amount").style.display="";
				document.getElementById("reduce_amount_err").style.display="";
				document.getElementById("propose_gp").style.display="none";
				document.getElementById("installment_date").value=''
		}else if (type==1){
				document.getElementById("reduce_amount").style.display="none";
				document.getElementById("reduce_amount_err").style.display="none";
				document.getElementById("installment_amount").value="";
				document.getElementById("propose_gp").style.display="";

		}else{
				document.getElementById("reduce_amount").style.display="none";
				document.getElementById("propose_gp").style.display="none";
				document.getElementById("installment_date").value="";
				document.getElementById("installment_date").value="";
		}
	}
//-->
</script>
<script language="JavaScript">
	var ids = new Array('propose_type','installment_date','reschedule_reason');
	var values = new Array('','','');
	var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>