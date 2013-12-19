<?php 
include_once("library/session.php");
include_once("./editables/admin.php");
include_once("editables/".$path);
date_default_timezone_set ('EST');
?>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
<script type="text/javascript" src="includes/scripts/submain.js"></script>
<div class='span12'>

<?php	if($session->userlevel==LENDER_LEVEL) {
			$userid=$session->userid; 
			$availableAmt = number_format(truncate_num(round($session->amountToUseForBid($userid), 4), 2) , 2, '.', ',');
			$availAmt = number_format($availableAmt, 1, ".", ",");
			$Auto_lendSetting=$database->getAutoLendingsetting($userid);
			$desired_interest=$Auto_lendSetting['desired_interest'];
			$max_desired_interest=$Auto_lendSetting['max_desired_interest'];
			$preference=$Auto_lendSetting['preference'];
			$currnt_allocated=$Auto_lendSetting['current_allocated'];
			$active=$Auto_lendSetting['Active'];
			
			
			$temp=$form->value('confirm_criteria');
			if(isset($temp) && $temp != '')
				$currnt_allocated=$form->value('confirm_criteria');
			$temp=$form->value('priority');
			if(isset($temp) && $temp != '')
				$preference=$form->value('priority');

			if(!empty($form->errors)) {
				$temp=$form->value('interest_rate_other');
				if(isset($temp) && $temp != '')
					$desired_interest=$form->value('interest_rate_other');
					$desired_interestt=$form->value('interest_rate_other');
				if(empty($desired_interestt)) {
					$desired_interest=$form->value('interest_rate');
				}

				$temp=$form->value('max_interest_rate_other');
				if(isset($temp) && $temp != '')
					$max_desired_interest=$form->value('max_interest_rate_other');
					$max_desired_interestt=$form->value('max_interest_rate_other');
				if(empty($max_desired_interestt)) {
					$max_desired_interest=$form->value('max_interest_rate');
				}
			}
			$temp=$form->error('Active');
			if(isset($temp) && $temp != '')
				$active=$form->value('Active');
			
		?>
	<div align='left' class='static'><h2>Automated Lending</h2></div>
		<br/>
		<p>
			The automated lending tool allows you to maximize your impact by continuously re-lending your available lender credit.
			&nbsp;&nbsp;
			<a style='cursor:pointer' class='tt'>
				Learn More
				<span class='tooltip'>
				<span class='top'></span>
					<span class='middle'>
						
						When you activate automated lending, the credit available in your lender account will be automatically bid toward new fundraising loans according to the preferences you select.  Automated bidding takes place once every 24 hours, in increments of $<?php echo AUTO_LEND_AMT?>.  When you upload additional funds to your lender account, you will be offered the choice to lend them to the entrepreneur of your choice, or to have them automatically lent according to your selected parameters.  You may deactivate automated lending at any time.
					</span>
					<span class='bottom'></span>
				</span>
			</a>
		</p>
<strong>Important note:</strong> The automated lending tool may allocate all of your lending credit to just one or a small number of loans.  Selecting a wide range of acceptable interest rates, and selecting "Choose loans at random" in the allocation preferences below, will make it more likely that your lending credit will distributed over a larger number of loans.
		<br/><br/>
				<?php $CreditAvailable=$session->amountToUseForBid($session->userid);
							if(isset($_SESSION['auto_lend'])) {
							if(isset($_SESSION['AutoLendAcitavted'])) {
								$activated='Automated lending has been activated for your account.';
							}else {
								$activated='Automated lending has been deactivated for your account.';
							}
							if(isset($_SESSION['StatusNotchanged'])) {
								$text='Your preferences have been saved.';
							}else
						if(isset($_SESSION['AutoLendCurrentCreditYes']) && isset($_SESSION['AutoLendAcitavted'])) {
							$CreditAvail=number_format(truncate_num(round($CreditAvailable, 4), 2) , 2, '.', ',');
							$text=$activated."<br/><br/>"
							."Your credit available for automated lending is USD ".$CreditAvail.". This balance will be automatically bid in $10 increments once every 24 hours.";
						} else if(isset($_SESSION['AutoLendAcitavted'])) {
							$CreditAvail=number_format(truncate_num(round($CreditAvailable, 4), 2) , 2, '.', ',');
							$text=$activated."<br/><br/>"
							."Your automated lending preferences will be applied to loan repayments that are credited to your account in the future. Automated lending will not apply to your current credit balance of USD ".$CreditAvail.".";
						
						}else if(empty($text))	{
								$text=$activated;
							}
						?>
							<script type="text/javascript">
							$(document).ready(function() {
								$('a[rel*=facebox]').facebox({

									loadingImage : '<?php echo SITE_URL?>scripts/facebox/loading.gif',
									closeImage   : 'none',
								});
								jQuery.facebox({ div: '#AutoLendActivated' });
								$('#facebox a.close').hide(); 
							});
						</script>
							
					<?php }
							unset($_SESSION['StatusNotchanged']);
							unset($_SESSION['AutoLendAcitavted']);
							unset($_SESSION['auto_lend']);
							unset($_SESSION['AutoLendCurrentCreditYes']);
							unset($_SESSION['AutoLendCurrentCreditNo']);
						?>
		<form action='process.php' method='post' name='AutoLending' id='AutoLending'>
			<table class='detail'>
				<tbody>
					<!-- <tr><td><strong class='subhead' >Automated Lending Preferences</strong></td></tr> -->
					<tr>
						<td><input type='radio' id="status_active" <?php if($active == 1)
						echo "checked='true'"; ?> name='status' value='1'>Activate automated lending.</td>
					</tr>
					<tr>
						<td><input type='radio' id="status_inactive" <?php if($active == 0)
						echo "checked='true'"; ?>  name='status' value='0'>Deactivate automated lending.</td>
					</tr>
					<tr>
						<td></td><td></td>
					</tr>
					<tr height='25px'><td colspan='3'></td></tr>
					<tr>
						<td>
							 Please specify your minimum desired interest rate.
							<a style='cursor:pointer' class='tt'>
								<img src='library/tooltips/help.png' style='border-style: none;' />
								<span class='tooltip'>
								<span class='top'></span>
									<span class='middle'>
										This is the minimum interest rate at which your available balance will be bid.  If there are no fundraising loan applications that are offering your minimum interest rate, then your balance will not be bid.
									</span>
									<span class='bottom'></span>
								</span>
							</a>
						</td>
					</tr>
					<tr>
						<td><input id="interest_rate1" type='radio' <?php if($desired_interest==0)
						echo "checked='true'"; ?> name='interest_rate' value='0' onclick="ResetOther()">0%</td>
					</tr>
					<tr>
						<td><input id="interest_rate2" type='radio' <?php if($desired_interest==3)
						echo "checked='true'"; ?>  name='interest_rate' value='3' onclick="ResetOther()">3%</td>
					</tr><tr>
						<td><input id="interest_rate3" type='radio' <?php if( $desired_interest==5)
						echo "checked='true'"; ?> name='interest_rate' value='5' onclick="ResetOther()">5%</td>
					</tr>
					<tr>
						<td><input id="interest_rate4" type='radio' <?php if($desired_interest==10)
						echo "checked='true'"; ?>  name='interest_rate' value='10' onclick="ResetOther()">10%</td>
					</tr>
					<tr>
						<td><?php 
								$Selectedother='false';
								if($desired_interest!=0 && $desired_interest!=3 && $desired_interest!=5 && $desired_interest!=10 ){
									$desired_interst1 = $desired_interest;
									if(is_numeric($desired_interst1)) {
									$explodedInt = explode('.', $desired_interst1);
										if($explodedInt['1'] > 0) {
											$desired_interst1=number_format($desired_interst1, 1, '.', ',')."%";
										}else {
											$desired_interst1=number_format($desired_interst1, 0, '.', ',')."%";
										}

									}
									$Selectedother='checked';
								} else if(!is_numeric($desired_interest)) {
									$desired_interst1 = $desired_interest;
									$Selectedother='checked';
								}
							?>

							<input type='radio' <?php echo $Selectedother; ?>  name='interest_rate' value='' id='InterestRateOther'>Other
							<span id='otheramount' style="margin-left:10px;">
								<input type='text'  id='desired_interest_rate' onfocus="setChecked()" name='interest_rate_other' value="<?php if(isset($desired_interst1) )
									echo $desired_interst1;
									?>">
								<br/>
								<div id="interest_rate_err"><?php echo $form->error("interest_rate"); ?></div>
							</span>
						</td>
					</tr>
						<tr height='25px'><td colspan='3'></td></tr>
					<tr>
						<td>
							Please specify your maximum desired interest rate.
							<a style='cursor:pointer' class='tt'>
								<img src='library/tooltips/help.png' style='border-style: none;' />
								<span class='tooltip'>
								<span class='top'></span>
									<span class='middle'>
										This is the maximum interest rate at which your available balance will be bid.
									</span>
									<span class='bottom'></span>
								</span>
							</a>
						</td>
					</tr>
					<tr>
						<td><input id="max_interest_rate1" type='radio' <?php if($max_desired_interest==0)
						echo "checked='true'"; ?> name='max_interest_rate' value='0' onclick="ResetOtherMax()">0%</td>
					</tr>
					<tr>
						<td><input id="max_interest_rate2" type='radio' <?php if($max_desired_interest==3)
						echo "checked='true'"; ?>  name='max_interest_rate' value='3' onclick="ResetOtherMax()">3%</td>
					</tr><tr>
						<td><input id="max_interest_rate3" type='radio' <?php if($max_desired_interest==5)
						echo "checked='true'"; ?> name='max_interest_rate' value='5' onclick="ResetOtherMax()">5%</td>
					</tr>
					<tr>
						<td><input id="max_interest_rate4" type='radio' <?php if($max_desired_interest==10)
						echo "checked='true'"; ?>  name='max_interest_rate' value='10' onclick="ResetOtherMax()">10%</td>
					</tr>
					<tr>
						<td>
							<?php 
								$Selectedother='false';
								if($max_desired_interest!=0 && $max_desired_interest!=3 && $max_desired_interest!=5 && $max_desired_interest!=10 ){
									$max_desired_interest1 = $max_desired_interest;
									if(is_numeric($max_desired_interest1)) {
										$exploded = explode('.', $max_desired_interest1);
										if($exploded['1'] > 0) {
											$max_desired_interest1=number_format($max_desired_interest1, 1, '.', ',')."%";
										}else {
											$max_desired_interest1=number_format($max_desired_interest1, 0, '.', ',')."%";
										}

									}
									$Selectedother='checked';
								} else if(!is_numeric($max_desired_interest)) {
									$max_desired_interest1 = $max_desired_interest;
									$Selectedother='checked';
								}
							?>

							<input  type='radio' <?php echo $Selectedother; ?>  name='max_interest_rate' value='' id='MaxInterestRateOther'>Other
							<span id='otheramount' style="margin-left:10px;">
								<input  type='text' id='max_desired_interest_rate' onfocus="setCheckedMax()" name='max_interest_rate_other' 
								value="<?php if(isset($max_desired_interest1))
									echo $max_desired_interest1;
									?>">
								<br/>
								<div id="max_interest_rate_err"><?php echo $form->error("max_interest_rate"); ?></div>
							</span>
						</td>
					</tr>
					<tr height='25px'><td colspan='3'></td></tr>
					<tr>
						<td>
							 How would you like your funds to be allocated?
							<a style='cursor:pointer' class='tt'>
								<img src='library/tooltips/help.png' style='border-style: none;' />
								<span class='tooltip'>
									<span class='top'></span>
									<span class='middle'>
										Your funds will be bid automatically toward loan applications that meet the criteria you specify here. In the event that more than one loan meets the specified criteria, a loan will be chosen at random from among them for each $<?php echo AUTO_LEND_AMT?> invested.</span>
									<span class='bottom'></span>
								</span>
							</a>
						</td>
					</tr>
					<tr style="margin-top:20px;">
						<td><input id="priority1" type='radio'  name='priority' <?php if($preference ==HIGH_FEEDBCK_RATING || $preference =='')
						echo "checked='true'"; ?>  value="<?php echo HIGH_FEEDBCK_RATING ?>">Prioritize applicants with highest feedback rating.</td>
					</tr>
					<tr>
						<td><input id="priority2" type='radio' name='priority'<?php if($preference ==EXPIRE_SOON )
						echo "checked='true'"; ?> value="<?php echo EXPIRE_SOON ?>">Prioritize loans expiring soonest.</td>
					</tr>
					<tr>
						<td><input id="priority3" type='radio' name='priority' <?php if($preference ==HIGH_OFFER_INTEREST)
						echo "checked='true'"; ?> value="<?php echo HIGH_OFFER_INTEREST ?>">Prioritize loans with highest offered interest rates.</td>
					</tr>
					<tr>
						<td><input id="priority4" type='radio' name='priority' <?php if($preference ==HIGH_NO_COMMENTS )
						echo "checked='true'"; ?>   value="<?php echo HIGH_NO_COMMENTS ?>">Prioritize applicants with highest number of comments posted.</td>
					</tr>
					<tr>
						<td>
							<input id="priority5" type='radio' name='priority'  <?php if($preference ==LOAN_RANDOM )
						echo "checked='true'"; ?>  value="<?php echo LOAN_RANDOM ?>">Choose loans at random.<br/>
								<?php echo $form->error('priority');?>
						</td>
					</tr>
					<tr height='25px'><td colspan='3'></td></tr>
					<?php if($CreditAvailable < AUTO_LEND_AMT) {
								$hideLastQust='display:none';
							} else {
								$hideLastQust='display:block';
							}?>
					<tr style="<?php echo $hideLastQust?>" >
						<td>
							 Would you like your current lender balance of USD <?php echo $availAmt?>  to be automatically allocated to fundraising loans according to these criteria?
						</td>
					</tr>
					
					<tr style="<?php echo $hideLastQust?>">
						<td><input id="currnt_allocated_yes" type='radio' <?php if($currnt_allocated == 1 || $currnt_allocated==null)
						echo "checked='true'"; ?> name='confirm_criteria' value='1'>Yes, apply automated lending to both my current balance and to future repayments that are credited to my account.</td>
					</tr>
					<tr style="<?php echo $hideLastQust?>">
						<td><input id="currnt_allocated_no" type='radio' <?php if($currnt_allocated == 0 && $currnt_allocated!=null)
						echo "checked='true'"; ?> name='confirm_criteria' value='0'>No, apply automated lending only to future repayments and leave my current balance available for manual lending.</td>
					</tr>
						<tr height='25px' style="<?php echo $hideLastQust?>" ><td colspan='3'></td></tr>
					<tr>
						<input type="hidden" name="automaticLending" />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('automaticLending'); ?>"/>
						<td><input type='submit'  name='save_preference' value='Save Preferences' onclick="needToConfirm = false;" class='btn'></td>
					</tr>

				</tbody>
			</table>
		</form>	
		<div class="" id="AutoLendActivated" style="background-color:#E3E5EA;display:none">
			<div class='autolend_space'>
			<div></div>
				<div class='auto_lend_text'>
					<?php if(isset($text))echo $text;?> 
				</div><br/>
				<div align="center" >
					<a href="javascript:void(0)" onclick="$.facebox.close();" class="">
						<input type="button" class='AutoLendBtn'  value="OK">
					</a>	
				</div>
			</div>
		</div>
	<?php }?>
<script type="text/javascript">
<!--
	function ResetOther() {
		document.getElementById("desired_interest_rate").value='';
		$("#interest_rate_err").html("");
	}
	function ResetOtherMax() {
		document.getElementById("max_desired_interest_rate").value='';
		$("#max_interest_rate_err").html("");
	}
//-->
	function setChecked() {
		document.getElementById("InterestRateOther").checked=true;
	}
	function setCheckedMax() {
		document.getElementById("MaxInterestRateOther").checked=true;
	}

</script>
<script language="JavaScript">
  var ids = new Array('status_active','status_inactive','interest_rate1','interest_rate2','interest_rate3','interest_rate4','InterestRateOther','desired_interest_rate','max_interest_rate1','max_interest_rate2','max_interest_rate3','max_interest_rate4','MaxInterestRateOther','max_desired_interest_rate','priority1','priority2','priority3','priority4','priority5','currnt_allocated_yes','currnt_allocated_no');
  var values = new Array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
  
var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>