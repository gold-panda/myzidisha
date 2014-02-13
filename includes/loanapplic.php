<?php
include_once("library/session.php");
include_once("./editables/loanapplic.php");
$path=	getEditablePath('loanapplic.php');
include_once("editables/".$path);
$sp=0;

if(isset($_GET['s']))
{
	$sp=$_GET['s'];
}
?>
<script type="text/javascript" src="includes/scripts/submain.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<div class="span12">
<?php
$instrctn=0;
if(isset($_GET['inst'])) {
	$instrctn=$_GET['inst'];
}
if(!$session->logged_in)
{
	echo $lang['loanapplic']['welcome_g'];
}
else
{
	$userid=$session->userid;
	$data=$database->getBorrowerDetails($userid);
	$country=$data['Country'];
	$maxperiodValue_months = $database->getAdminSetting('maxperiodValue');

    if ($country != 'BJ' && $country != 'BF' && $country != 'GN' && $country != 'NE' && $country != 'SN') {
        
        $weekly_inst = 1;

    } else {

    	$weekly_inst = 0;
    }

	if ($weekly_inst == 1) {
		$maxperiodValue = $maxperiodValue_months * (52/12);
	} else {
		$maxperiodValue = $maxperiodValue_months;
	}


	if($session->userlevel != BORROWER_LEVEL)
	{
		echo $lang['loanapplic']['allow']."<br />".$lang['loanapplic']['Click']." <a href='index.php'>here</a> ".$lang['loanapplic']['cont'];
	}
	else if($instrctn) { ?>
	<table class='detail'>
		<tbody>
			<tr>
				<td colspan='2'>
					<h3 class="subhead"><?php echo $lang['loanapplic']['loan_applic'] ?></h3>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan='2'>
					<?php $deadline = $database->getAdminSetting('deadline');?>
					<?php echo $lang['loanapplic']['loanapplic_instrctns1']." ".$deadline." ". $lang['loanapplic']['loanapplic_instrctns2'];
					?>
				</td>
				<td></td>
			</tr>
			<tr height='15px;'><td></td><td></td></tr>
			<tr>
				<td></td>
				<td style="text-align:right">
					<a href="index.php?p=9" class='btn'>Continue</a>
				</td>
			</tr>
		</tbody>
	</table>
<?php	}
	else
	{	
		$active=$database->getLoanStatus($userid);
		$defaulted=false;
		if($active==NO_LOAN) {
			if($database->getDefaultedLoanid($userid)) {
				$defaulted=true;
					}
			}
	if($active == LOAN_ACTIVE || $defaulted)
		{	
			if($sp==4)
			{	
				$deadline = $database->getAdminSetting('deadline');
				?>
				<div class="row">
					<div>
						<table class="detail">
							<tbody>
								<tr>
									<td><strong><?php echo $lang['loanapplic']['loan_applic'] ?></strong></td>
								</tr>
								<tr height='10px'><td></td></tr>
								<tr>
									<td>
								<?php 

									$lastLoan=$database->getLastloan($userid);
									$loanprurl = getLoanprofileUrl($userid,$lastLoan['loanid']);
									$params['loanprofilelink']=SITE_URL.$loanprurl;
									$confirmMsg1=$session->formMessage($lang['loanapplic']['confirm_msg1'], $params);
									echo $confirmMsg1;?>&nbsp;
										<?php echo $deadline."&nbsp;";echo $lang['loanapplic']['confirm_msg2']; ?> 
										
									</td>
								</tr>
							</tbody>
						</table>
					</div><!-- /bid-table -->
				</div><!-- /row -->
	<?php	}
			else
			{
				echo "<p>".$lang['loanapplic']['active_loan_not allow']."</p>";
			}
		}
		else
		{
			$activeted=$database->getBorrowerActive($userid);
			if(!empty($activeted))
			{
				$currency=$database->getUserCurrency($userid);


				if ($currency==KES){

					$amt_step=1000;
					$inst_step=100;

				}elseif ($currency==XOF){

					$amt_step=5000;
					$inst_step=500;

				}elseif ($currency==IDR){

					$amt_step=100000;
					$inst_step=10000;

				}elseif ($currency==ZMW){

					$amt_step=50;
					$inst_step=5;

				}elseif ($currency==GNF){

					$amt_step=50000;
					$inst_step=5000;

				}else{

					$amt_step=10;
					$inst_step=1;
											
				}

				$rate=$database->getCurrentRate($userid);

				if($sp==0)
				{

					//loan application page 1
					$webfee=$database->getAdminSetting('fee');//website fee rate
					
					$maxLoanAppInterest=($database->getAdminSetting('maxLoanAppInterest') + $database->getAdminSetting('fee'));
					
					$usdmaxBorrowerAmt = $session->getCurrentCreditLimit($session->userid,true);   // function created by julia 08-12-13 used by Mohit
					$usdminBorrowerAmt=$database->getAdminSetting('minBorrowerAmt');//website fee rate
					$maxBorrowerAmt= ceil($usdmaxBorrowerAmt); /* It is in native currency */
					$minBorrowerAmt= ceil(convertToNative($usdminBorrowerAmt, $rate));
					$minIns=$session->getMinInstallment($maxBorrowerAmt, $maxperiodValue, $webfee, 0, $weekly_inst);
					$back = 0;
					if (isset($_GET['back']))
					{
						$back = 1;
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
					if($back ==0)
					{

						$loan_amt=$form->value('amount');
						if (empty($loan_amt) || $loan_amt==0){

							$loan_amt=$maxBorrowerAmt;
						}

						$anul_int_rate = $form->value('interest');

						$re_paymnet_per= $form->value('installment_amt');
						if (empty($re_paymnet_per) || $re_paymnet_per==0){

							$re_paymnet_per=maxBorrowerAmt;
						}

						$grace_p= $form->value('gperiod');
						$summary= $form->value('summary');
						$loanuse= $form->value('loanuse');
						$installment_day= $form->value('installment_day');
						$installment_weekday= $form->value('installment_weekday');

					}
					else
					{
						$loan_amt= $_SESSION['la']['amt'];
						$anul_int_rate = $_SESSION['la']['intr'];
						$re_paymnet_per= $_SESSION['la']['iamt'];
						$grace_p= $_SESSION['la']['gp'];
						$summary= $_SESSION['la']['su'];
						$loanuse= $_SESSION['la']['lu'];
						$installment_day= $_SESSION['la']['iday'];
						$installment_weekday= $_SESSION['la']['iwkday'];
					
					}
						
					$loanamt_usd=$loan_amt / $rate;
					if ($loanamt_usd <= 200){

						$timethrshld = $database->getAdminSetting('TimeThrshld');

					}elseif ($loanamt_usd <= 1000){

						$timethrshld = $database->getAdminSetting('TimeThrshldMid1');
	
					}elseif ($loanamt_usd <= 3000){

						$timethrshld = $database->getAdminSetting('TimeThrshldMid2');
	
					}elseif ($loanamt_usd > 3000){

						$timethrshld = $database->getAdminSetting('TimeThrshld_above');
					}	

					if ($weekly_inst==1){

						$min_period=$timethrshld * (52/12);

					} else {

						$min_period=$timethrshld;

					}

					$minIns=$session->getMinInstallment($loan_amt, $maxperiodValue, $anul_int_rate, $grace_p, $weekly_inst);
							
					$minIns_select = ceil($minIns / $inst_step) * $inst_step;

					$maxIns=$loan_amt / $min_period;
		
					


			?>
					<div class="row">
						<div>
							<h3 class="subhead"><?php echo $lang['loanapplic']['loan_applic'] ?></h3>

<br/>
							<form action="process.php" method="post">
							<table class="detail">
								<tbody>
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
								</tbody>
							</table>
							<table class="detail">
								<tbody>
									<tr height='30px'><td colspan='3'></td></tr>	

									<tr>
										<td><strong><?php echo $lang['loanapplic']['loan_amt']; ?>:</strong><br/><br/><?php echo $lang['loanapplic']['loan_limit']." ".$currency." ".number_format($maxBorrowerAmt, 0, '', ',');?>.<br/>
										<?php
										echo"<a href='index.php?p=76' target='_blank' >{$lang['loanapplic']['crditLimit']}</a><br/><br/>";

										echo $lang['loanapplic']['note_amt_pr']; ?>
										
										</td>

										<td style="vertical-align:top">


										<select style="width:120px" name="amount" id="loanAppAmount">

										<option value='<?php echo $maxBorrowerAmt ?>' Selected='selected' ><?php echo $maxBorrowerAmt ?></option>

										<?php

										$amt_range = range($amt_step, $maxBorrowerAmt, $amt_step);

										arsort($amt_range);

										$i=0; 

										foreach($amt_range as $amt_option) {  ?>

											<option value='<?php echo $amt_option ?>' <?php if($form->value("amount")==$amt_option) echo "Selected='true'" ?>><?php echo $amt_option ?></option>

										<?php		$i++;

										}

										?>

										</select>

										</td>

										<td><div id="loanAppAmountError"><?php echo $form->error("amount"); ?></div></td>
										
									</tr>
									<tr height='60px'><td colspan='3'></td></tr>
									<?php
										if(!empty($anul_int_rate)){
											$anul_int_rate=$anul_int_rate;
										}else{
											$anul_int_rate=15;
										}?>

									<tr>

										<td><strong><?php echo $lang['loanapplic']['prop_anul_int_rate'] ?>:</strong><br/><br/><?php echo $lang['loanapplic']['prop_anul_int_rate_desc1']." ".$webfee."%".$lang['loanapplic']['prop_anul_int_rate_desc2']." "; ?></td>

										<td style="vertical-align:top">


										<select style="width:120px" id="loanAppInterest" name="interest">

										<?php

										$int_range = range($webfee, $maxLoanAppInterest);

										arsort($int_range);

										$i=0;

										foreach($int_range as $int_option) {  ?>

										<option value='<?php echo $int_option ?>' <?php if($form->value("interest")==$int_option) echo "Selected='true'" ?>><?php echo $int_option ?>%</option>

										<?php		$i++;

										}

										?>

										</select>

										</td>

										<td style="float:left; display:block; margin-top: 6px;"></td><td><div id="loanAppInterestError"><?php echo $form->error("interest"); ?></div></td>
									</tr>
									
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

									<tr height='60px'><td colspan='3'></td></tr>								
									<tr>
										<td><strong>
											<?php if ($weekly_inst != 1) {

												echo $lang['loanapplic']['monthly_repay_amt'].":</strong><br/><br/>".$lang['loanapplic']['monthly_installment_amt'];
											} else {

												echo $lang['loanapplic']['weekly_repay_amt'].":</strong><br/><br/>".$lang['loanapplic']['weekly_installment_amt'];
												
											} ?>

</td>

										<td style="vertical-align:top">


										<select style="width:120px" name="installment_amt" id="loanAppInstallment">

										<?php

										$inst_range = range($minIns_select, $maxIns, $inst_step);

										arsort($inst_range);

										$i=0; 

										foreach($inst_range as $inst_option) {  ?>

											<option value='<?php echo $inst_option ?>' <?php if($form->value("installment_amt")==$inst_option) echo "Selected='true'" ?>><?php echo $inst_option ?></option>

										<?php		$i++;

										}

										?>

										</select>

										</td>



<!--
										<td style="vertical-align:top"><input style="width:50px" maxlength="10" type="text" name="installment_amt" id="loanAppInstallment" value="<?php echo $re_paymnet_per;?>" /></td>
-->

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

												<select style="width:120px" name="installment_day" id="installment_day">
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
									<tr height='60px'><td colspan='3'></td></tr>

									<!-- loan use summary -->
									<tr>
										<td colspan=3>
											<?php echo $lang['loanapplic']['summary'] ?><br/><br/>
											<input style="width:500px" type="text" name="summary" id="summary" maxlength="50" value="<?php echo $summary;?>" /><br/>
											<div><?php echo $form->error('summary') ?></div>
										</td>
									</tr>
									<tr height='60px'><td colspan='3'></td></tr>


									<!-- loan use -->
									<tr>
										<td colspan=3>
											<?php echo $lang['loanapplic']['use_loan'] ?><br/><br/>
											<textarea name="loanuse" id="loanuse" style="width:600px; height:300px"><?php echo $loanuse;?></textarea><br/>
											<div><?php echo $form->error('loanuse') ?></div>
										</td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>

<!--
									<tr>
										<td colspan=3>
											<strong><?php echo $lang['loanapplic']['t_cond'] ?>:</strong><br/><br/>
											<div align="left" style="border: 1px solid black; padding: 0px 10px 10px; overflow: auto; line-height: 1.5em; width: 90%; height: 130px; background-color: rgb(255, 255, 255);">
												<?php
													include_once("./editables/legalagreement.php");
													$path1=	getEditablePath('legalagreement.php');
													include_once("./editables/".$path1);
													echo $lang['legalagreement']['b_tnc'].$lang['legalagreement']['b_tnc1'];
												?>
											</div>
										</td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>

-->
							<?php	$check = 'checked';
									$check1 = '';
									if($form->value('agree') == 0 && $form->value('agree') != '')
									{
										$check = '';
										$check1 = 'checked ';
									}
									else if($form->value('agree') == 1)
									{
										$check = 'checked ';
										$check1 = '';
									} else if($back!=0)
									{
										$check = 'checked ';
										$check1 = '';
									}
								
							?>

<!-- 
									<tr>
										<td colspan=3><br/>
											<strong><?php echo $lang['loanapplic']['accept'] ?>:</strong>

-->

<div style="display:none">
											<INPUT TYPE="Radio" name="agree" id="agree" value="1" tabindex="3" <?php echo $check ?> />
											<?php echo $lang['loanapplic']['acceptyes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
											<INPUT TYPE="Radio" name="agree" id="agree" value="0" tabindex="4" <?php echo $check1 ?> />
											<?php echo $lang['loanapplic']['not-accept']; echo $form->error('agree') ?>

</div>

<!--
										</td>
									</tr>

-->

									<tr height='15px'><td colspan='3'></td></tr>
									<tr>
										<td colspan=3 style="text-align:right;padding-right:50px">
											<input type="hidden" name="loanapplication" />
											<input type="hidden" name="user_guess" value="<?php echo generateToken('loanapplication'); ?>"/>
											<input class='btn' type="submit" onclick="needToConfirm = false;" value='<?php echo $lang['loanapplic']['buttonnext'];?>'  />
										</td>
									</tr>


								</tbody>
							</table>
							</form>
						</div><!-- /bid-table -->
					</div><!-- /row -->
			<?php
				}
				else if($sp==1)
				{
					//loan schedule page
					$loan=$_SESSION['loanapp'];
					$amount=$loan['amount'];
					$interest = trim(str_replace("%","",$loan['interest']));
					$period=$loan['period'];

					if ($weekly_inst == 1) {
						$timeframe = $lang['loanapplic']['weeks'];
						$graceText = ($grace >1) ? ucwords($lang['loanapplic']['weeks']) : ucwords($lang['loanapplic']['week']); 
						$tot_interest=(($period)/52)*(($amount*($interest/100)));

					} else {											$timeframe = $lang['loanapplic']['months'];
						$graceText = ($grace >1) ? ucwords($lang['loanapplic']['months']) : ucwords($lang['loanapplic']['month']); 
						$tot_interest=(($period)/12)*(($amount*($interest/100)));

					}
					$grace=$loan['grace'];
					$tnc=$loan['tnc'];
					$famt=number_format($amount, 0, ".", ",");
					$installment_amt = number_format($loan['installment_amt'], 0, ".", ",");
					$webfee=$database->getAdminSetting('fee');//website fee rate
					$loneAcceptDate=time();
					$sched=$session->getSchedule($amount, $interest, $period, $grace,$loneAcceptDate,$webfee,$weekly_inst);
					$totalamt=number_format(($amount+$tot_interest), 0, ".", ",");
					$tot_interest_show=number_format($tot_interest, 0, ".", ",");

				?>
					<div class="row">
						<div>
							<h3 class="subhead"><?php echo $lang['loanapplic']['loan_applic_con'] ?></h3>
							
							<br/><br/>

							<p><?php echo $lang['loanapplic']['conferm_shedule'];?></p>

							<br/><br/>

							<form action="process.php" method="post">
							<table class="detail">
								<tbody>
									<tr>
										<td style="width:400px"><strong><?php echo $lang['loanapplic']['amt_req'] ?>:</strong><br /></td>
										<td><?php echo $currency." ".$famt ?></td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>
									<tr>
										<td><strong><?php echo $lang['loanapplic']['prop_anul_int_rate'] ?>:</strong></td>
										<td><?php echo $interest ?>%</td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>

<!-- not displaying grace period anymore 17-12-2013
									<tr>
										<td><strong><?php echo $lang['loanapplic']['grace_period'] ?>:</strong></td>
										<td><?php echo $grace." ".$graceText; ?> </td>
									</tr>

-->

									<tr>
										<td><strong>
										
										<?php if ($weekly_inst != 1) {
											echo $lang['loanapplic']['monthly_repay_amt'];
										} else {

											echo $lang['loanapplic']['weekly_repay_amt'];
										} 

									?>:</strong></td>
										<td><?php echo $currency." ".$installment_amt ?></td>
									</tr>
									<tr height='15px'><td colspan='3'></td></tr>

									<tr>
										<td><strong><?php echo $lang['loanapplic']['repay_period'] ?>:</strong></td>
										<td><?php echo $period." ".$timeframe; ?></td>
									</tr>

									<tr height='15px'><td colspan='3'></td></tr>


									<tr>
										<td><strong><?php echo $lang['loanapplic']['tot_int_fee'] ?>:</strong></td>
										<td><?php echo $currency." ".$tot_interest_show." (".$interest."% ".$lang['loanapplic']['yearly_rate']." ".$period." ".$timeframe.")"; ?></td>
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

							<br/><br/><br/>

							<p>
								<div style="float:left"><input class='btn' type=button value='<?php echo $lang['loanapplic']['Back'];?>' onclick='window.location="index.php?p=9&s=0&back=1"'>
								
								
								</div>
								<div style="float:right">
									<input type="hidden" name="confirmApplication" />
									<input type="hidden" name="user_guess" value="<?php echo generateToken('confirmApplication'); ?>"/>
								
							

								<?php 
									if($tnc)
									{
										echo"<input class='btn' type='submit' value='".$lang['loanapplic']['confermbutton']."' />";
									}
									else
									{ 
										echo $lang['loanapplic']['goback'];
									}
								?>
								</div>
							</p>
							</form>
						</div>
					</div>
	<?php		}
				else if($sp==2)
				{	

				}
			}
			else
			{
				echo $lang['loanapplic']['sucess_msg'];
			}
		}
	}
}?>
</div>
<script language="JavaScript">
	if ($weekly_inst !=1) {
		var ids = new Array('loanAppAmount', 'loanAppInterest','loanAppGracePeriod','loanAppInstallment','installment_day','summary','loanuse');
	} else {
		var ids = new Array('loanAppAmount', 'loanAppInterest','loanAppGracePeriod','loanAppInstallment','installment_weekday','summary','loanuse');
	} 
	var values = new Array('','','','','','','');
	var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>