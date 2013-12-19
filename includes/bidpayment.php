<script type="text/javascript" src="includes/scripts/eepztooltip.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("#donation-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
		$("#donation1-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});

			
	});
</script>
<?php
include_once("library/session.php");
include_once("./editables/order-tnc.php");
$path=	getEditablePath('order-tnc.php');
include_once("editables/".$path);
include_once("./editables/withdraw.php");
$path=	getEditablePath('withdraw.php');
include_once("editables/".$path);
if(isset($_SESSION['bidPaymentId']) && $session->userlevel == LENDER_LEVEL)
{
	$paypalTranFeeOrg= $database->getAdminSetting('PaypalTransaction');
	$bidPaymentId = $_SESSION['bidPaymentId'];
	$order_amount = $database->getBidAmount($bidPaymentId, $session->userid);
	$availableAmt = truncate_num(round($session->amountToUseForBid($session->userid), 4), 2);
	$paypal_amount=bcsub($order_amount, $availableAmt, 4);
	if($paypal_amount >0)
	{
		$paypal_donation =bcmul($paypal_amount ,15/100,4);
		$paypalTranFee= $paypalTranFeeOrg;
		$paypalTranAmount=((bcmul($paypal_amount , $paypalTranFee,4))/100);
		$totalAmt2= bcadd(bcadd($paypal_amount , $paypalTranAmount ,4) ,$paypal_donation,4);
		$totalAmt2= number_format($totalAmt2, 2,'.','');
		$totalAmt1= bcadd($paypal_amount , $paypal_donation,4);
		$totalAmt1= number_format($totalAmt1, 2,'.','');
		$option=1;
?>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<style type="text/css"> 	
	@import url(library/tooltips/btnew.css);
</style>
<div class="span12">
	<?php if(USE_E_CHEQUE) { ?>
	<strong><?php echo $lang['order-tnc']['payment_option']; ?></strong><br/><br/>
	<form name='ordertnc' id='ordertnc' action='index.php?p=33' method='post'>
	<!-- <p><?php echo $lang['withdraw']['add_fund_below'];?></p> -->
	<br/>
	<strong><?php echo $lang['order-tnc']['option']." ".$option; ?>: <?php echo $lang['order-tnc']['option1_heading']; ?></strong><br/><br/>
	<?php echo $lang['order-tnc']['option1_desc1']; ?><br/><br/>
	<?php $option++; ?>
	<table class='detail' style="width:auto">
		<tbody>
			<tr>
				<td><?php echo $lang['order-tnc']['add_credit'];?>:</td>
				<td><input style="width:50px" type="text" size=5 name="bid_echeck_amount" id="bid_echeck_amount" value="<?php echo number_format($paypal_amount, 2,'.',''); ?>"></td>
			</tr>
			<tr>
				<td><?php echo $lang['order-tnc']['donation'];?>: 
				<img src='library/tooltips/help.png' class="donation-tooltip-target tooltip-target" id="donation-target-1" style='border-style:none;display:inline' />
				<div class="tooltip-content tooltip-content" id="donation-content-1">
					<span class="tooltip">
						<span class="tooltipTop"></span>
						<span class="tooltipMiddle" >
							<?php echo $lang['withdraw']['donation_tooltip'];?>
							<p class="auditedreportlink">
								<a  href="includes/financial_report.php" rel="facebox"><?php echo $lang['order-tnc']['financial_report']?></a>
							</p>
						</span>	
						<span class="tooltipBottom"></span>
					</span>
				</div>
				</td>
				<td><input style="width:50px" type="text" size=5 name="bid_echeck_donation" id="bid_echeck_donation" value="<?php echo number_format($paypal_donation, 2,'.',''); ?>"></td>
			</tr>
			<tr>
				<td><br/></td>
			</tr>
			<tr>
				<td><?php echo $lang['order-tnc']['total_amount'];?>: USD</td>
				<td id="bid_echeck_tot_amt"><?php echo $totalAmt1; ?></td>
			</tr>
			<tr>
				<td><br/></td>
			</tr>
			<tr>
				<td></td>
				<td align="center"><input class='btn' type="submit" value=<?php echo $lang['order-tnc']['next'];?>><input type="hidden" name="bid_payment_by_echeck" ></td>
			</tr>
		</tbody>
	</table>
	</form>
	<br/><br/>
	<?php } ?>
	<form name='ordertnc' id='ordertnc' action='library/paypal/getMoney.php' method='post'>
	<strong><?php if(USE_E_CHEQUE) { echo $lang['order-tnc']['option']." ".$option.":";} ?> <?php echo $lang['order-tnc']['option2_heading']; ?></strong><br/><br/>
	<?php echo $lang['order-tnc']['option2_desc1']; ?><br/>
	<?php echo $lang['order-tnc']['option2_desc4']; ?><br/><br/>
	<?php $option++; ?>
	<table class='detail' style="width:auto">
		<tbody>
			<tr>
				<td><?php echo $lang['order-tnc']['add_credit'];?>:</td>
				<td><input style="width:50px" type="text" size=5 name="bid_paypal_amount" id="bid_paypal_amount" value="<?php echo number_format($paypal_amount, 2,'.',''); ?>"></td>
			</tr>	
			<tr>
				<td><?php echo $lang['order-tnc']['transaction_fee'];?>: <a  style='margin-left:0px;cursor:pointer;' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['order-tnc']['option2_desc2']; ?> <?php echo $paypalTranFeeOrg."%"; ?> <?php echo $lang['order-tnc']['option2_desc5']; ?><br/><br/><?php echo $lang['order-tnc']['option2_desc6']; ?></span><span class='bottom'></span></span></a></td>
				<td><div id="bid_paypal_trans_div"><?php echo number_format($paypalTranAmount, 2,'.',''); ?></div><input type="hidden" size=5 name="bid_paypal_trans" id="bid_paypal_trans" value="<?php echo number_format($paypalTranAmount, 2,'.',''); ?>"><input type="hidden" size=5 name="paypal_trans" id="paypal_trans" value="<?php echo $paypalTranFeeOrg; ?>"></td>
			</tr>
			<tr>
				<td><?php echo $lang['order-tnc']['donation'];?>: 
				<img src='library/tooltips/help.png' class="donation1-tooltip-target tooltip-target" id="donation1-target-1" style='border-style:none;display:inline' />
				<div class="tooltip-content tooltip-content" id="donation1-content-1">
					<span class="tooltip">
						<span class="tooltipTop"></span>
						<span class="tooltipMiddle" >
							<?php echo $lang['withdraw']['donation_tooltip'];?>
							<p class="auditedreportlink">
								<a  href="includes/financial_report.php" rel="facebox"><?php echo $lang['order-tnc']['financial_report']?></a>
							</p>
						</span>	
						<span class="tooltipBottom"></span>
					</span>
				</div>
				</td>
				<td><input style="width:50px" type="text" size=5 name="bid_paypal_donation" id="bid_paypal_donation" value="<?php echo number_format($paypal_donation, 2,'.',''); ?>"></td>
			</tr>
			<tr>
				<td><br/></td>
			</tr>
			<tr>
				<td><?php echo $lang['order-tnc']['total_amount'];?>: USD</td>
				<td id="bid_paypal_tot_amt"><?php echo $totalAmt2; ?></td>
			</tr>
			<tr>
				<td><br/></td>
			</tr>
			<tr>
				<td></td>
				<td align="center"><input class='btn' type="submit" value=<?php echo $lang['order-tnc']['next'];?>></td>
			</tr>
		</tbody>
	</table>
	</form>
</div>
<?php
	}
}
?>