<script type="text/javascript" src="includes/scripts/eepztooltip.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("#stay-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
		$("#don-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
		$("#don1-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
		$("#don2-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});

			
	});
</script>
<?php
include_once("library/session.php");
include_once("./editables/withdraw.php");
$path=	getEditablePath('withdraw.php');
include_once("editables/".$path);
?>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
<div class='span12'>
<?php
if(isset($_SESSION['success'])){
	echo "<div align='center'><font color=green><b>Thank you! Your payment has been processed and funds credited to your lender account.  If you made a donation, we have sent a receipt which may be used for tax purposes to your registered email address.</b></font></div>";
	unset($_SESSION['success']);
}
if(!$session->checkLogin())
{
	echo "Please login to add or withdraw funds";
	exit;
}
if(isset($_GET['err']) && $_GET['err'] >0)
{
	echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
	echo "<font color='white'>".$errorArray[$_GET['err']]."</font>";
	echo "</td></tr></table>";
}
if($session->userlevel == LENDER_LEVEL)
{
	$country=$database->getCountryCodeById($session->userid);
	$availAmt = $session->amountToUseForBid($userid);
	$availAmt = truncate_num(round($availAmt, 4),2);
	$investAmtDisplay = $database->amountInActiveBidsDisplay($session->userid);

	$m=0;
	if(isset($_GET["m"])){
		$m=$_GET["m"];
	}
	if($m==1){
		echo '<div class="clearfix" style="color:green">';
		echo $lang['withdraw']['withdraw_non_US'];
		echo "</div>";
	}
	if($m==2){
		  echo '<div class="clearfix" style="color:green">';
		  echo $lang['withdraw']['withdraw_US'];
		  echo "</div>";
	}
?>
	<div align='left' class='static'><h1><?php echo $lang['withdraw']['add_withdraw_funds'] ?></h1></div>
	<p><?php echo $lang['withdraw']['total_avl_amt'].": USD ".number_format($availAmt, 2, ".", ","); ?></p>
	<!--
<p><?php echo $lang['withdraw']['amt_invested'].": USD ".number_format($investAmtDisplay, 2, ".", ","); ?></p>
	-->
	<p>&nbsp;</p>
	<h3 class="subhead"><?php echo $lang['withdraw']['add_funds'] ?></h3>
	<!-- <p><?php echo $lang['withdraw']['add_fund_below'];?></p> -->	
	<p>&nbsp;</p>
<?php
	$j=1;
	if(USE_PAYPAL)
	{
		$paypalTranFeeOrg= $database->getAdminSetting('PaypalTransaction');
		$paypalTranFee= number_format($paypalTranFeeOrg, 2, ".", "");
		$paypalTotal= number_format(($paypalTranFeeOrg + 115), 2, ".", "");
?>
		<h3 class="subhead"><?php echo $lang['withdraw']['option']." ".$j.": ".$lang['withdraw']['option4_new'] ?></h3>
		<?php $j++; ?>
		<p><?php echo $lang['withdraw']['paypal_desc1_new'];?><br/><br/><?php echo $lang['withdraw']['paypal_desc2_new2'] ?></p>
		<form action="library/paypal/getMoney.php" method="post">
			<table class='detail' style="width:auto">
				<tbody>
					<tr>
						<td><?php echo $lang['withdraw']['upload_amt'];?>:</td>
						<td><input type="text" size=5 name="paypal_amount" id="paypal_amount" value="100.00" onblur="javascript:fillDonation()" autocomplete=off></td>
					</tr>
					<tr>
						<td><?php echo $lang['withdraw']['tran_fee'];?>: <a  style='margin-left:0px;cursor:pointer;' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['withdraw']['paypal_desc1_new2'];?> </span><span class='bottom'></span></span></a></td>
						<td><input type="text" size=5 name="paypal_transaction" id="paypal_transaction" value="<?php echo $paypalTranFee; ?>" autocomplete=off readonly="true"></td>
					</tr>
					<tr>
						<td><?php echo $lang['withdraw']['donation_amt'];?>: 
						<img src='library/tooltips/help.png' class="stay-tooltip-target tooltip-target" id="stay-target-1" style='border-style:none;display:inline'/>
						<div class="stay-tooltip-content tooltip-content" id="stay-content-1">
							<span class="tooltip">
								<span class="tooltipTop"></span>
								<span class="tooltipMiddle" >
								<?php echo $lang['withdraw']['donation_tooltip'];?>
									<p class="auditedreportlink">
										<a  href="includes/financial_report.php" rel="facebox"><?php echo $lang['withdraw']['financial_report']?></a>
									</p>
								</span>	
								<span class="tooltipBottom"></span>
							</span>
						</div>
						<br/>

	











	

						</a></td>
						<td><input type="text" size=5 name="paypal_donation" id="paypal_donation" value="15.00"></td>
					</tr>
					<tr>
						<td><br/></td>
					</tr>
					<tr>
						<td><?php echo $lang['withdraw']['tot_amt'];?>: USD</td>
						<td id="paypal_tot_amt"><?php echo $paypalTotal; ?></td>
					</tr>
					<tr>
						<td><br/></td>
					</tr>
					<tr>
						<td></td>
						<td align="center"><input type="hidden" name="addPayment"><input type="hidden" name="user_guess" value="<?php echo generateToken('addPayment'); ?>"/><input type="hidden" name="paypal_trans" id="paypal_trans" value="<?php echo $database->getAdminSetting('PaypalTransaction');?>">
						<input class='btn' type="submit" value=<?php echo $lang['withdraw']['next'];?>></td>
					</tr>
				</tbody>
			</table>
		</form>
<?php	}
	if($country=='US')
	{	?>
		<?php if(USE_E_CHEQUE) { ?>
		<h3 class="subhead"><?php echo $lang['withdraw']['option']." ".$j.": ".$lang['withdraw']['option1'] ?></h3>
		<?php $j++; ?>
		<p><?php echo $lang['withdraw']['upload_desc1'];?></p>
		<form action="index.php?p=33" method="post">
			<table class='detail' style="width:auto">
				<tbody>
					<tr>
						<td><?php echo $lang['withdraw']['upload_amt'];?>:</td>
						<td><input type="text" size=5 name="amount" id="upload_amount" value="100.00" onblur="javascript:fillDonation()" autocomplete=off></td>
					</tr>
					<tr>
						<td><?php echo $lang['withdraw']['donation_amt'];?>: 
							<img src='library/tooltips/help.png' class="don-tooltip-target tooltip-target" id="don-target-1" style='border-style:none;display:inline' />
							<div class="tooltip-content tooltip-content" id="don-content-1">
								<span class="tooltip">
									<span class="tooltipTop"></span>
									<span class="tooltipMiddle" >
										<?php echo $lang['withdraw']['donation_tooltip'];?>
									<p class="auditedreportlink">
										<a  href="includes/financial_report.php" rel="facebox"><?php echo $lang['withdraw']['financial_report']?></a>
									</p>
									</span>	
									<span class="tooltipBottom"></span>
								</span>
							</div><br/>
						</td>
						<td><input type="text" size=5 name="donation" id="donation_amount" value="15.00" onblur="javascript:setTotal()"></td>
					</tr>
					<tr>
					<td><br/></td>
					</tr>
					<tr>
						<td><?php echo $lang['withdraw']['tot_amt'];?>: USD</td>
						<td id="tot_amt">115.00</td>
					</tr>
					<tr>
						<td><br/></td>
					</tr>
					<tr>
						<td></td>
						<td align="center"><input type="hidden" name="addPayment"><input type="hidden" name="user_guess" value="<?php echo generateToken('addPayment'); ?>"/>
						<input class='btn' type="submit" value=<?php echo $lang['withdraw']['next'];?>></td>
					</tr>
				</tbody>
			</table>
		</form>
		<?php } ?>
		<?php if(USE_PAPER_CHEQUE) { ?>
		<h3 class="subhead"><?php echo $lang['withdraw']['option']." ".$j.": ".$lang['withdraw']['option2'] ?></h3>
		<?php $j++; ?>
		<?php echo $lang['withdraw']['check_desc1'];?>  
		<p>&nbsp;</p>
		<?php } ?>
<?php
	} ?>

<h3 class="subhead"><?php echo $lang['withdraw']['option']." ".$j.": ".$lang['withdraw']['other_pmt_option1'] ?></h3>
	<?php $j++; ?>
	<?php echo $lang['withdraw']['other_pmt_tool_tip1'];?> 
	<p>&nbsp;</p>

	<h3 class="subhead"><?php echo $lang['withdraw']['option']." ".$j.": ".$lang['withdraw']['option3'] ?></h3>
	<?php $j++; ?>
	<?php echo $lang['withdraw']['transfer_desc1'];?> 
	<p>&nbsp;</p>

<h3 class="subhead"><?php echo $lang['withdraw']['rdn_gift_card'] ?></h3>

	<a href="microfinance/gift-cards.html"><font size='2'><?php echo $lang['withdraw']['purchase_card'];?></font></a>

	<br>
	<form action="updateprocess.php" method="post">
<?php		if(isset($_GET['v']))
		{
			$v = $_GET['v'];
			$error = $_SESSION['error_array']['cardRedeemError'];
			if($v == 0)
				echo "<font color='red'>*** ".$error."</font><br/><br/>";
			if($v == 1)
				echo "<font color='green'>Congratulations! USD ".$_GET['amt']." has been credited to your lender account.</font><br/><br/>";
		}	?>
		<table class="detail">
			<tbody>
				<tr>
					<td><?php echo $lang['withdraw']['enter_code'];?>:</td>
					<td><input type="text" name="card_code" value="<?php echo $form->value("card_code"); ?>"></td>
					<td><input type="hidden" name="redeemCard" id="redeemCard">
					<input type="hidden" name="user_guess" value="<?php echo generateToken('redeemCard'); ?>"/>
					<input class='btn' type="submit" value="Submit"></td>
				</tr>
			</tbody>
		</table>
	</form>





	<p>&nbsp;</p>
	<h3 class="subhead"><?php echo $lang['withdraw']['withdraw_fund'] ?></h3>
<br />
	<p><?php echo $lang['withdraw']['total_avl_amt'].": USD ".number_format($availAmt, 2, ".", ","); ?></p><br />

<?php
	if($country != COUNTRY)
	{
		$withdrawAmt = $database->getwithdraw($session->userid);
	}
	else
	{
		$withdrawAmt = $database->getpaysimplewithdraw($session->userid);
	}
	if($country != COUNTRY)
	{	?>
		<form action="updateprocess.php" method="post">
			<table class='detail'>
				<tbody>
					<tr>
						<td><?php echo $lang['withdraw']['paypal_email'];?></td>
						<td>
							<input type="text" name="paypalemail" value="<?php echo $form->value("paypalemail"); ?>">
							<div id="berror"><?php echo $form->error("paypalemail"); ?></div>
						</td>
					</tr>
					<tr>
						<td><?php echo $lang['withdraw']['amt'];?>:</td>
						<td>
							<input type="text" name="amount" value="<?php echo $form->value("amount"); ?>">
							<div id="berror"><?php echo $form->error("amount"); ?></div>
						</td>
						<td>
							<input type="hidden" name="withdraw">
							<input type="hidden" name="user_guess" value="<?php echo generateToken('withdraw'); ?>"/>
							<input class='btn' type="submit" value=<?php echo $lang['withdraw']['withdraw'];?>>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
<?php	}
	else
	{	?>
		<form action="updateprocess.php" method="post">
			<table class='detail'>
				<tbody>
					<tr>
						<td><?php echo $lang['withdraw']['fname_lname'];?>:</td>
						<td><input type="text" id="PaysimpleName" name="PaysimpleName"/></td>
					</tr>
					<tr>
						<td>* <?php echo $lang['withdraw']['add1'];?>:</td>
						<td>
							<input type="text" id="PaysimpleAddress1" name="PaysimpleAddress1" value="<?php echo $form->value("PaysimpleAddress1"); ?>"/>
							<div id="berror"><?php echo $form->error("PaysimpleAddress1"); ?></div>
						</td>
					</tr>
					<tr>
						<td><?php echo $lang['withdraw']['add2'];?>:</td>
						<td>
							<input type="text" id="PaysimpleAddress2" name="PaysimpleAddress2" value="<?php echo $form->value("PaysimpleAddress2"); ?>"/>
						</td>
					</tr>
					<tr>
						<td>* <?php echo $lang['withdraw']['city_state'];?>:</td>
						<td>
							<input type="text" id="PaysimpleCity" name="PaysimpleCity" value="<?php echo $form->value("PaysimpleCity"); ?>"/>
							<div id="berror"><?php echo $form->error("PaysimpleCity"); ?></div>
						</td>
					<tr>
						<td></td>
						<td>
							<select id="PaysimpleState" name="PaysimpleState" style="width:147px">
					<?php
							$result1 = $database->stateList();
							$i=0;
							foreach($result1 as $state)
							{
								echo "<option value='".$state['code']."'>".$state['name']."</option>";
							}
					?>
							</select>
						</td>
					</tr>
					<tr>
						<td>* <?php echo $lang['withdraw']['zipcode'];?>:</td>
						<td>
							<input type="text" id="PaysimpleZip" name="PaysimpleZip" value="<?php echo $form->value("PaysimpleZip"); ?>"/>
							<div id="berror"><?php echo $form->error("PaysimpleZip"); ?></div>
						</td>
					</tr>
					<tr>
						<td>* <?php echo $lang['withdraw']['phone'];?>:</td>
						<td>
							<input type="text" id="PaysimplePno" name="PaysimplePno" value="<?php echo $form->value("PaysimplePno"); ?>"/>
							<div id="berror"><?php echo $form->error("PaysimplePno"); ?>
						</td>
					</tr>
					<tr>
						<td>* <?php echo $lang['withdraw']['withdra_amt'];?>:</td>
						<td>
							<input type="text" id="PaysimpleAmt" name="PaysimpleAmt" value="<?php echo $form->value("PaysimpleAmt"); ?>"/>
							<div id="berror"><?php echo $form->error("PaysimpleAmt"); ?></div>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<br/>
							<input type="hidden" name="PaySimplewithdraw">
							<input type="hidden" name="user_guess" value="<?php echo generateToken('PaySimplewithdraw'); ?>"/>
							<input class='btn' type="submit" name="Withdraw" value="Withdraw" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
<?php	
	}
}
else if($session->userlevel  == ADMIN_LEVEL )
{	
	if(isset($_REQUEST["paymentsel"])){
		$paymentselect=$_REQUEST["paymentsel"];
	}
	else{
		$paymentselect = "paypal";
	}
?>
			<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead">Lender Withdrawal Requests</h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
	<select name="paymenttype" id="paymenttype" class="selectcmmn" onchange="window.location='index.php?p=17&paymentsel='+(this).value">
		<option></option>
		<option value="paypal"<?php if($paymentselect=="paypal")echo "Selected='true'"; ?> selected="true">Outside US</option>
		<option value="paysimple"<?php if($paymentselect=="paysimple")echo "Selected='true'"; ?>>US</option>
	</select><br/><br/>
<?php
	if($paymentselect == "paypal")
	{
		$set=$database->getwithdraw();
		//$set=$database->getotherwithdraw();
	}
	else
	{
		$set = $database->getpaysimplewithdraw();
	}
	if(empty($set))
	{
		echo $lang['withdraw']['nowithdraw'];
	}
	else
	{	?>
		<table class="zebra-striped">
			<thead>
				<tr>
			<?php	if($paymentselect == "paypal")
					{
						echo "<th>".$lang['withdraw']['date']."</th>";
						echo "<th>Lender</th>";
						echo "<th>PayPal Email</th>";
						echo "<th>Withdrawal Amount</th>";
						echo "<th></th>";
					}
					else
					{
						echo "<th>".$lang['withdraw']['date']."</th>";
						echo "<th>Lender</th>";
						echo "<th>".$lang['withdraw']['add1']."</th>";
						echo "<th>".$lang['withdraw']['add2']."</th>";
						echo "<th>".$lang['withdraw']['city']."</th>";
						echo "<th>".$lang['withdraw']['state']."</th>";
						echo "<th>".$lang['withdraw']['zip']."</th>";
						echo "<th>".$lang['withdraw']['phone']."</th>";
						echo "<th>Withdrawal Amount</th>";
						echo "<th></th>";
					}	?>
				</tr>
			</thead>
			<tbody>
		<?php	foreach($set as $row )
				{	
					$userid=$row['userid'];
//added by Julia 24 Oct 2013
					$Detail=$database->getEmail($userid);
					$city=$Detail['City'];
					$country =$Detail['Country'];								$email=$Detail['email'];
					if($paymentselect == "paypal")
					{
						$date=date('M j, Y', $row['date']);
						$Detail=$database->getEmail($userid);
						$name=$Detail['name'];;
						$paypalemail = $row['paypalemail'];
						$email=$Detail['email'];
					}
					else
					{
						$date=date('M j, Y', $row['date1']);
						$name=$row['name'];
						$address=$row['address1'];
					}
					$rowid=$row['id'];
					$userid=$row['userid'];
					$amount=$row['amount'];
					$amt_display=number_format($amount, "2", ".", ",");
					$active=$row['paid'];
					if($active==0)
					{
						if($paymentselect == "paypal")
						{
							$active1=
								"<form method='post' action='updateprocess.php'>".
								"<input name='paywithdraw' type='hidden' />".
								"<input type='hidden' name='user_guess' value='".generateToken('paywithdraw')."'/>".
								"<input name='lenderid' value='$userid' type='hidden' />".
								"<input name='rowid' value='$rowid' type='hidden' />".
								"<input name='amount' value='$amount' type='hidden' />".
								"<input type='submit' value=".$lang['withdraw']['pay']." />".
								"</form>";

						}
						else
						{
							$active1=
								"<form method='post' action='updateprocess.php'>".
								"<input name='paysimplewithdrawadmin' type='hidden' />".
								"<input type='hidden' name='user_guess' value='".generateToken('paysimplewithdrawadmin')."'/>".
								"<input name='lenderid' value='$userid' type='hidden' />".
								"<input name='rowid' value='$rowid' type='hidden' />".
								"<input name='amount' value='$amount' type='hidden' />".
								"<input type='submit' value=".$lang['withdraw']['pay']." />".
								"</form>";
						}
					}
					else
					{
						$active1=$lang['withdraw']['Accepted'];
					}
					echo '<tr>';
						echo "<td>$date</td>";
						$prurl = getUserProfileUrl($userid);
						echo "<td><a href='$prurl'>".$name."</a><br/><br/>$city, $country<br/><br/>$email</td>";
						if($paymentselect == "paypal"){
							echo "<td>".$paypalemail."</td>";
						}
						else{
							echo "<td>$address</td>";
							echo "<td>".$row['address2']."</td>";
							echo "<td>".$row['city']."</td>";
							echo "<td>".$row['state']."</td>";
							echo "<td>".$row['zip']."</td>";
							echo "<td>".$row['phoneno']."</td>";
						}
						echo "<td>$amt_display<br/><br/><a href='index.php?p=16&u=$userid' target='_blank'>Transaction History</a></td>";
						echo "<td width='5%'>$active1</td>";
					echo '</tr>';
				}	?>
			</tbody>
		</table>
<?php	
	}
}
?>
</div>