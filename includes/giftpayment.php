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
include_once("./editables/order-tnc.php");
$path=	getEditablePath('order-tnc.php');
include_once("editables/".$path);
if(isset($_SESSION['order_id']))
{
	$paypalTranFeeOrg= $database->getAdminSetting('PaypalTransaction');
	$order_id = $_SESSION['order_id'];
	$order_amount = $database->GetOrderAmount($order_id);
	$paypal_amount=$order_amount['amount'];
	if($paypal_amount >0)
	{
		$paypal_donation =($paypal_amount * 15/100);
		$paypalTranFee= $database->getAdminSetting('PaypalTransaction');
		$paypalTranAmount=(($paypal_amount * $paypalTranFee)/100);
		$totalAmt2= $paypal_amount + $paypalTranAmount + $paypal_donation;
		$totalAmt2= number_format($totalAmt2, 2,'.','');
		$totalAmt1= $paypal_amount + $paypal_donation;
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
	<strong><?php echo $lang['order-tnc']['option']." ".$option; ?>: <?php echo $lang['order-tnc']['option1_heading']; ?></strong><br/><br/>
	<?php echo $lang['order-tnc']['option1_desc1']; ?><br/><br/>
	<?php $option++; ?>
	<table class='detail' style="width:auto">
		<tbody>
			<tr>
				<td><?php echo $lang['order-tnc']['gift_amount'];?> (USD):</td>
				<td><?php echo number_format($paypal_amount, 2,'.',''); ?><input type="hidden" size=5 name="echeck_amount" id="echeck_amount" value="<?php echo number_format($paypal_amount, 2,'.',''); ?>"></td>
			</tr>
			<tr>
				<td><?php echo $lang['order-tnc']['donation'];?> (USD): 
				<img src='library/tooltips/help.png' class="donation-tooltip-target tooltip-target" id="donation-target-1" style='border-style:none;display:inline' />
				<div class="tooltip-content tooltip-content" id="donation-content-1">
					<span class="tooltip">
						<span class="tooltipTop"></span>
						<span class="tooltipMiddle" >
							<?php echo $lang['order-tnc']['donation_tooltip'];?>
							<p class="auditedreportlink">
								<a  href="includes/financial_report.php" rel="facebox"><?php echo $lang['order-tnc']['financial_report']?></a>
							</p>
						</span>	
						<span class="tooltipBottom"></span>
					</span>
				</div>
				</td>
				<td><input style="width:50px" type="text" size=5 name="gift_echeck_donation" id="gift_echeck_donation" value="<?php echo number_format($paypal_donation, 2,'.',''); ?>"></td>
			</tr>
			<tr>
				<td><br/></td>
			</tr>
			<tr>
				<td><?php echo $lang['order-tnc']['total_amount'];?>:</td>
				<td id="echeck_tot_amt">USD <?php echo $totalAmt1; ?></td>
			</tr>
			<tr>
				<td><br/></td>
			</tr>
			<tr>
				<td></td>
				<td align="center"><input class='btn' type="submit" value=<?php echo $lang['order-tnc']['next'];?>><input type="hidden" name="gift_payment_by_echeck" ></td>
			</tr>
		</tbody>
	</table>
	</form>
	<br/><br/>
	<?php } ?>
	<form name='ordertnc' id='ordertnc' action='library/paypal/getMoneyGift.php' method='post'>
	<strong><?php if(USE_E_CHEQUE) { echo $lang['order-tnc']['option']." ".$option.":";} ?> <?php echo $lang['order-tnc']['option2_heading']; ?></strong><br/><br/>
	<?php echo $lang['order-tnc']['option2_desc1']; ?><br/>
	<?php echo $lang['order-tnc']['option2_desc4']; ?><br/><br/>
	<?php $option++; ?>
	<table class='detail' style="width:auto">
		<tbody>
			<tr>
				<td><?php echo $lang['order-tnc']['gift_amount'];?> (USD):</td>
				<td><?php echo number_format($paypal_amount, 2,'.',''); ?><input type="hidden" size=5 name="paypal_amount" id="paypal_amount" value="<?php echo number_format($paypal_amount, 2,'.',''); ?>"></td>
			</tr>	
			<tr>
				<td><?php echo $lang['order-tnc']['transaction_fee'];?> (USD): <a  style='margin-left:0px;cursor:pointer;' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['order-tnc']['option2_desc2']; ?></span><span class='bottom'></span></span></a></td>
				<td><?php echo number_format($paypalTranAmount, 2,'.',''); ?><input type="hidden" size=5 name="paypal_trans" id="paypal_trans" value="<?php echo number_format($paypalTranAmount, 2,'.',''); ?>"></td>
			</tr>
			<tr>
				<td><?php echo $lang['order-tnc']['donation'];?> (USD): 
				<img src='library/tooltips/help.png' class="donation1-tooltip-target tooltip-target" id="donation1-target-1" style='border-style:none;display:inline' />
				<div class="tooltip-content tooltip-content" id="donation1-content-1">
					<span class="tooltip">
						<span class="tooltipTop"></span>
						<span class="tooltipMiddle" >
							<?php echo $lang['order-tnc']['donation_tooltip'];?>
							<p class="auditedreportlink">
								<a  href="includes/financial_report.php" rel="facebox"><?php echo $lang['order-tnc']['financial_report']?></a>
							</p>
						</span>	
						<span class="tooltipBottom"></span>
					</span>
				</div>
				<br/>
				</td>
				<td><input style="width:50px" type="text" size=5 name="gift_paypal_donation" id="gift_paypal_donation" value="<?php echo number_format($paypal_donation, 2,'.',''); ?>"></td>
			</tr>
			<tr>
				<td><br/></td>
			</tr>
			<tr>
				<td><?php echo $lang['order-tnc']['total_amount'];?>:</td>
				<td id="paypal_tot_amt">USD <?php echo $totalAmt2; ?></td>
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