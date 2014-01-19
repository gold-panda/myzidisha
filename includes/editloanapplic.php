<script type="text/javascript" src="includes/scripts/submain.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<?php
include_once("library/session.php");
include_once("./editables/loanapplic.php");
$path=	getEditablePath('loanapplic.php');
include_once("editables/".$path);
?>
<div class="span12">
<?php
$sp=0;
if(isset($_GET['s']))
{
	$sp=$_GET['s'];
}
if(!$session->logged_in)
{
	echo $lang['loanapplic']['welcome_g']."<br /><br />".$lang['loanapplic']['required_reg']."
		".$lang['loanapplic']['pls_login']." <a href='index.php?p=1'>register</a> ".$lang['loanapplic']['cont'];
}
else
{
	$userid=$session->userid;
	$country=$database->getCountryCodeById($userid);
//countries Kenya, Ghana & Indonesia transitioned to weekly installments 19-12-2013
	if ($country == 'KE' || $country == 'GH' || $country == 'ID')  {
		$weekly_inst = 1;
	}
	$maxperiodValue_months = $database->getAdminSetting('maxperiodValue');
	if ($weekly_inst == 1) {
		$maxperiodValue = $maxperiodValue_months * (52/12);
	} else {
		$maxperiodValue = $maxperiodValue_months;
	}
	if($session->userlevel != BORROWER_LEVEL)
	{
		echo $lang['loanapplic']['allow']."<br />".$lang['loanapplic']['Click']." <a href='index.php'>here</a> ".$lang['loanapplic']['cont'];
	}
	else
	{
		$active=$database->getLoanStatus($userid);
		if($active == LOAN_FUNDED || $active == LOAN_ACTIVE )
		{
			if($sp==4)
			{	?>
				<table class="detail">
					<tbody>
						<tr>
							<td><strong><?php echo $lang['loanapplic']['loan_applic'] ?></strong></td>
						</tr>
						<tr>
							<td>
								<?php echo $lang['loanapplic']['thank_note'];?>&nbsp;<b><?php echo $lang['loanapplic']['open'];?></b>.
								<?php echo $lang['loanapplic']['best_wish']; ?><br /><br />
								<?php echo $lang['loanapplic']['org'];?>
							</td>
						</tr>
					</tbody>
				</table>
	<?php	}
			else
			{
				echo $lang['loanapplic']['active_loan_not allow'];
			}
		}
		else
		{
			$activeted=$database->getBorrowerActive($userid);
			if(!empty($activeted))
			{
				$currency=$database->getUserCurrency($userid);
				$rate=$database->getCurrentRate($userid);

				if($sp==0)
				{
					$loandata=$database->getLastloan($userid);
					$amount=number_format($loandata['Amount'],0,'.','');
					if($form->value('amount'))
						$amount=$form->value('amount');
					$interest=number_format($loandata['interest'],0,'.','')."%";
					if($form->value('interest'))
						$interest=$form->value('interest');
					$summary=$loandata['summary'];
					if($form->value('summary'))
						$summary=$form->value('summary');
					$loanuse=$loandata['loanuse'];
					if($form->value('loanuse'))
						$loanuse=$form->value('loanuse');
					$loanid=$loandata['loanid'];
					$installment_day = $loandata['installment_day'];
					if($form->value('installment_day'))
						$installment_day = $form->value('installment_day');
					$installment_weekday = $loandata['installment_weekday'];
					if($form->value('installment_weekday'))
						$installment_weekday = $form->value('installment_weekday');
					$grace_p = $loandata['grace'];
					if($form->value('gperiod'))
						$grace_p = $form->value('gperiod');
					$webfee=number_format($loandata['WebFee'],0,'.','');//website fee rate
					$maxLoanAppInterest=($database->getAdminSetting('maxLoanAppInterest') + $database->getAdminSetting('fee'));
					$usdmaxBorrowerAmt = $session->getCurrentCreditLimit($session->userid,true);//website fee rate
					$usdminBorrowerAmt=$database->getAdminSetting('minBorrowerAmt');//website fee rate
					$maxBorrowerAmt= ceil($usdmaxBorrowerAmt); /* It is in native currency */
					$minBorrowerAmt= ceil(convertToNative($usdminBorrowerAmt, $rate));
					$minIns=$session->getMinInstallment($maxBorrowerAmt, $maxperiodValue, $webfee, 0, $weekly_inst);
					$inst_amount = $loandata['Amount']/($loandata['period'] - $loandata['grace']);
					$re_paymnet_per = ceil($inst_amount);
					$back = 0;
					if (isset($_GET['back']))
					{
						$back = 1;
					}
					if($back == 1) {
						$installment_day = $_SESSION['la']['edit_inst_day'];
						$installment_weekday = $_SESSION['la']['edit_inst_weekday'];
						$amount = $_SESSION['la']['editamt'];
						$interest = $_SESSION['la']['editintr'];
						$summary = $_SESSION['su']['editsummary'];
						$loanuse = $_SESSION['la']['editloanuse'];
						$re_paymnet_per = $_SESSION['la']['installment_amt'];
						$grace_p = $_SESSION['la']['gperiod'];
					}
					$bfrstloan=$database->getBorrowerFirstLoan($userid);
					if(!$bfrstloan)
					{
						$currency_amt=$database->getReg_CurrencyAmount($userid);
						foreach($currency_amt as $row)
						{
							$currency1=$row['currency'];
							$amount_reg=$row['Amount'];
							$amt=number_format($amount_reg, 0, ".", "");
						}
					}
	?>				<div class='row'>
						<div>
							<form action="process.php" method="post">
							<table class="detail">
								<tbody>
									<tr>
										<td colspan="3"><h3 class='subhead'><?php echo $lang['loanapplic']['edit_loan_applic'];?></h3></td>
									</tr>
									<tr>
										<td colspan="3">
										<?php	echo $lang['loanapplic']['publish_app'];
												echo "<br/><br/>";
												if(!$bfrstloan && $amount_reg>0) {
													echo $lang['loanapplic']['reg_fee_currency']." ".$currency." ".$amt." ".$lang['loanapplic']['reg_fee_curr'];
												}
										?>
										</td>
									</tr>
									<tr height='60px'><td colspan='3'></td></tr>
									<tr>
										<td><strong><?php echo $lang['loanapplic']['loan_amt']; ?>:</strong><br/><br/><?php echo $lang['loanapplic']['loan_limit']." ".$currency." ".number_format($maxBorrowerAmt, 0, '', ',');?>.<br/>
										<?php
										echo"<a href='index.php?p=76' target='_blank' >{$lang['loanapplic']['crditLimit']}</a><br/><br/>";

echo $lang['loanapplic']['note_amt_pr']; ?>
										
										</td>
										<td style="vertical-align:top"><input style="width:50px" maxlength="10" type="text" name="amount" id="loanAppAmount"  value="<?php echo $amount; ?>"/></td>
										<td><div id="loanAppAmountError"><?php echo $form->error("amount"); ?>&nbsp;</div></td>
									</tr>
									<tr height='60px'><td colspan='3'></td></tr>
									<tr>
										<td><strong><?php echo $lang['loanapplic']['prop_anul_int_rate'] ?>:</strong><br/><br/><?php echo $lang['loanapplic']['prop_anul_int_rate_desc1']." ".$webfee."%".$lang['loanapplic']['prop_anul_int_rate_desc2']." "; ?></td>
										<td style="vertical-align:top">


											<select style="width:60px" id="loanAppInterest" name="interest">

											<?php $int_range = range($webfee, $maxLoanAppInterest);

											arsort($int_range);

											$i=0;

											foreach($int_range as $int_option) {  ?>

												<option value='<?php echo $int_option ?>' <?php if($interest==$int_option) echo "Selected='true'" ?>><?php echo $int_option ?>%</option>

												<?php		$i++;

											} ?>

											</select>

										</td>
										<td style="float:left; display:block; margin-top: 6px;"></td><td><div id="loanAppInterestError"><?php echo $form->error("interest"); ?></div></td>
									</tr>
									<tr height='60px'><td colspan='3'></td></tr>
									<!-- comment by Julia to no longer display this option 11-11-2013

<tr height='60px'><td colspan='3'></td></tr>

									<tr>
										<td><strong><?php echo $lang['loanapplic']['grace_period'] ?>:</strong><br/><br/><?php echo $lang['loanapplic']['grace_period_desc'].' '.$database->getAdminSetting('maxLoanAppGracePeriod').' month.'; ?></td>

										<td style="vertical-align:top">

-->

										<div style="display:none">

											<input style="width:50px" maxlength="2" type="text" name="gperiod" id="loanAppGracePeriod" value="1"/>

										</div>

<!--

</td>
										<td><div id="loanAppGracePeriodError"><?php echo $form->error("gperiod"); ?></div></td>

</div>
									</tr>

-->
									<tr>
										<td><strong>

											<?php if ($weekly_inst != 1) {

												echo $lang['loanapplic']['monthly_repay_amt'].":</strong><br/><br/>".$lang['loanapplic']['installment_amt']." ".$maxperiodValue." ".$lang['loanapplic']['installment_amt2']." ".$currency." <span id='minLoanInsCalculated'>".number_format($minIns, 0, '', ',')."</span>.";
											} else {

												echo $lang['loanapplic']['weekly_repay_amt'].":</strong><br/><br/>".$lang['loanapplic']['weekly_installment_amt']." ".$maxperiodValue." ".$lang['loanapplic']['weekly_installment_amt2']." ".$currency." <span id='minLoanInsCalculated'>".number_format($minIns, 0, '', ',')."</span>.";

											} ?>

										<td style="vertical-align:top"><input style="width:50px" maxlength="10" type="text" name="installment_amt" id="loanAppInstallment" value="<?php echo $re_paymnet_per;?>" /></td>
										<td><div id="loanAppInstallmentError"><?php echo $form->error("installment_amt"); ?></div></td>
									</tr>
									<tr height='60px'><td colspan='3'></td></tr>
									<tr>
										<td><strong>
											<?php if ($weekly_inst != 1) {

												echo $lang['loanapplic']['repay_date'].":</strong><br/><br/>".$lang['loanapplic']['installment_day'];

											} else {

												echo $lang['loanapplic']['repay_day'].":</strong><br/><br/>".$lang['loanapplic']['installment_weekday'];

											} ?>






										</td>
										<td style="vertical-align:top">


											<?php if ($weekly_inst != 1){ ?>

												<select style="width:60px" name="installment_day" id="installment_day">
													<option></option>
												<?php for($i=1;$i<=31;$i++){?>
													<option <?php if($installment_day==$i) echo"selected='selected'";?>value=<?php echo $i?>><?php echo $i?></option>
												 <?php } ?>
												</select>
											<?php } else { ?>

												<select style="width:120px" name="installment_weekday" id="installment_weekday">
																										<option></option> 
													<option value='1' <?php if($installment_weekday==1) echo "Selected='true'";?>><?php echo $lang['loanapplic']['monday']; ?></option>
													<option value='2' <?php if($installment_weekday==2) echo "Selected='true'";?>><?php echo $lang['loanapplic']['tuesday']; ?></option>													<option value='3' <?php if($installment_weekday==3) echo "Selected='true'";?>><?php echo $lang['loanapplic']['wednesday']; ?></option>													<option value='4' <?php if($installment_weekday==4) echo "Selected='true'";?>><?php echo $lang['loanapplic']['thursday']; ?></option>													<option value='5' <?php if($installment_weekday==5) echo "Selected='true'";?>><?php echo $lang['loanapplic']['friday']; ?></option>													<option value='6' <?php if($installment_weekday==6) echo "Selected='true'";?>><?php echo $lang['loanapplic']['saturday']; ?></option>													<option value='0' <?php if($installment_weekday==7) echo "Selected='true'";?>><?php echo $lang['loanapplic']['sunday']; ?></option>
												</select>
											<?php } ?>



										<td><?php echo $form->error("installment_day"); ?></td>
									</tr>

									<!-- summary -->
									<tr height='60px'><td colspan='3'></td></tr>
									<tr>
										<td colspan="3">
											<br /><?php echo $lang['loanapplic']['summary'];?><br /><br/>
											<textarea name="summary" style="width:100%; height:50px" maxlength="200"><?php	echo $summary; ?></textarea>
											<br />
											<?php echo $form->error('summary') ?>
										</td>
									</tr>
									
									<!-- loan use -->
									<tr height='60px'><td colspan='3'></td></tr>
									<tr>
										<td colspan="3">
											<br /><?php echo $lang['loanapplic']['use_loan'];?><br /><br/>
											<textarea name="loanuse" style="width:100%; height:300px"><?php	echo $loanuse; ?></textarea>
											<br />
											<?php echo $form->error('loanuse') ?>
										</td>
									</tr>
									
									<tr>
										<td style='text-align:right' colspan="3">
											<input type="hidden" name="editloanapplication" />
											<input type="hidden" name="user_guess" value="<?php echo generateToken('editloanapplication'); ?>"/>
											<input class='btn' type="hidden" name="loanid" value='<?php echo $loanid;?>'/>&nbsp&nbsp&nbsp
											<input class ='btn' type="submit" value='<?php echo $lang['loanapplic']['update'];?>'  />
										</td>
									</tr>

								</tbody>
							</table>
							</form>
						</div>
					</div>
<?php			}
				else if($sp==1)
				{
					echo $lang['loanapplic']['updateApp'];
				}else if($sp==2) { 
					$editamount = $_SESSION['la']['editamt'];
					$editintr = $_SESSION['la']['editintr'];
					$editsummary = $_SESSION['la']['editsummary'];
					$editloanuse = $_SESSION['la']['editloanuse'];
					$ld = $_SESSION['la']['loanid'];
					$gperiod = $_SESSION['la']['gperiod'];
					$famt=number_format($editamount, 0, ".", ",");
					$editinterest = trim(str_replace("%","",$editintr));
					$loandetails = $database->getLoanDetails($ld);
					$webfee=$database->getAdminSetting('fee');//website fee rate
					$installment = $_SESSION['la']['installment_amt'];
					$installment_amt = number_format($installment, 0, ".", ",");
					$newperiod=$session->getTotalMonthByInstallments($editamount, $installment, $editinterest,$gperiod, $weekly_inst);
					if ($weekly_inst == 1) {
						$timeframe = $lang['loanapplic']['weeks'];
						$graceText = ($grace >1) ? ucwords($lang['loanapplic']['weeks']) : ucwords($lang['loanapplic']['week']); 
						$tot_interest=(($newperiod)/52)*(($editamount*($editintr/100)));

					} else {											$timeframe = $lang['loanapplic']['months'];
						$graceText = ($grace >1) ? ucwords($lang['loanapplic']['months']) : ucwords($lang['loanapplic']['month']); 
						$tot_interest=(($newperiod)/12)*(($editamount*($editintr/100)));

					}
					$sched = $session->getSchedule($editamount, $editintr, $newperiod, $gperiod,time(),$webfee,$weekly_inst);
					$totalamt=number_format(($editamount+$tot_interest), 0, ".", ",");
					$tot_interest_show=number_format($tot_interest, 0, ".", ",");
					?>
				<div class="row">
						<div>
							<h3 class="subhead"><?php echo $lang['loanapplic']['loan_applic_con'] ?></h3>
							<p><?php echo $lang['loanapplic']['conferm_shedule'];?></p>
							<form action="process.php" method="post">
							<table class="detail">
								<tbody>
									<tr>
										<td style="width:400px"><strong><?php echo $lang['loanapplic']['amt_req'] ?>:</strong><br /><?php echo $lang['loanapplic']['note_amt_pr'];?></td>
										<td><?php echo $currency." ".$famt ?></td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>
									<tr>
										<td><strong><?php echo $lang['loanapplic']['prop_anul_int_rate'] ?>:</strong></td>
										<td><?php echo $editinterest ?>%</td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>
														<tr>
										<td><strong>
										
										<?php if ($weekly_inst != 1) {
											echo $lang['loanapplic']['monthly_repay_amt'];
										} else {

											echo $lang['loanapplic']['weekly_repay_amt'];
										} 

									?>:</strong></td>
										<td><?php echo $currency." ".$installment_amt; ?></td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>

									<tr>
										<td><strong><?php echo $lang['loanapplic']['repay_period'] ?>:</strong></td>
										<td><?php echo $newperiod." ".$timeframe; ?></td>
									</tr>

									<tr height='15px'><td colspan='3'></td></tr>


									<tr>
										<td><strong><?php echo $lang['loanapplic']['tot_int_fee'] ?>:</strong></td>
										<td><?php echo $currency." ".$tot_interest_show." (".$editinterest."% ".$lang['loanapplic']['yearly_rate']." ".$newperiod." ".$timeframe.")"; ?></td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>
									<tr>
										<td><strong><?php echo $lang['loanapplic']['tot_repay_due'] ?>:</strong></td>
										<td><?php echo $currency." ".$totalamt ?></td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>
								</tbody>
							</table>
							<p><?php echo $lang['loanapplic']['shedule_assume']; ?></p>
							<?php echo $sched; ?>
							<p> 
								<div style="float:left"><input class='btn' type=button value='<?php echo $lang['loanapplic']['Back'];?>' onclick='window.location="index.php?p=44&back=1"'>
							</div>
							<div style="float:right">
								<input type="hidden" name="amount" value="<?php echo $_SESSION['la']['editamt'];?>" />
								<input type="hidden" name="interest" value="<?php echo $_SESSION['la']['editintr'];?>"/>
								<input type="hidden" name="loanid" value="<?php echo $_SESSION['la']['loanid'];?>" />
								<input type="hidden" name="summary" value="<?php echo $_SESSION['la']['editsummary'];?>"/>
								<input type="hidden" name="loanuse" value="<?php echo $_SESSION['la']['editloanuse'];?>"/>
								<input type="hidden" name="repay_period" value="<?php echo $newperiod;?>"/>
								<input type="hidden" name="gperiod" value="<?php echo $_SESSION['la']['gperiod'];?>"/>
								<input type="hidden" name="installment_day" value="<?php echo $_SESSION['la']['edit_inst_day'];?>"/>
								<input type="hidden" name="installment_weekday" value="<?php echo $_SESSION['la']['edit_inst_weekday'];?>"/>
								<input type="hidden" name="installment_amt" value="<?php echo $_SESSION['la']['installment_amt'];?>"/>
								<input type="hidden" name="editLoanApplicConfirm" value=''/>
								<input type="hidden" name="editloanapplication" value='' />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('editloanapplication'); ?>"/>
								<?php 
										echo"<input class='btn' type='submit' value='".$lang['loanapplic']['confermbutton']."' />";
								?>
								</div>
							</p>
							</form>
						</div><!-- /bid-table -->
					</div><!-- /row -->

				<?php }
			}
			else
			{
				echo $lang['loanapplic']['sucess_msg'];
			}
		}
	}
}
?>
<div id='howmuchcaniborrow' style='display:none'>
	<div  style='padding:15px;'>
		<div> <?php echo $text?></div>
	</div>
</div>