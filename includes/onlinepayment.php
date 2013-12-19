<?php
	include_once("library/session.php");
	if(isset($_POST['addPayment']) || $form->value('addPayment')=='addPayment')
	{
		if(isset($_POST['addPayment']))
		{
			$upload_amount= $_POST['amount'];
			$donation= $_POST['donation'];
		}
		else
		{
			$upload_amount= $form->value('paymentamt');
			$donation= $form->value('donation');
		}
		$totalamt= $upload_amount+$donation;
		if($upload_amount=='')
		{
			echo "<script type='text/javascript'>window.location='index.php?p=17'</script>";
		}
		if($donation=='')
			$donation=0;
		$orderdesc= "Lender Account Funds Upload";
		$date= time();
		$orderdate= date("M j, Y",$date);
	}
	else if(isset($_POST['addDonation']) || $form->value('addDonation')=='addDonation')
	{
		if(isset($_POST['addDonation']))
		{
			$upload_amount=0;
			$donation= $_POST['donation'];
		}
		else
		{
			$upload_amount=0;
			$donation= $form->value('donation');
		}
		$totalamt= $donation + $upload_amount;
		if($donation=='' || $donation==0)
		{
			echo "<script type='text/javascript'>window.location='microfinance/donate.html'</script>";
		}
		$orderdesc= "Lender Donation";
		$date= time();
		$orderdate= date("M j, Y",$date);
	}
	else if(isset($_SESSION['order_id']))
	{
		$order_id = $_SESSION['order_id'];
		if(isset($_POST['gift_payment_by_echeck']))
		{
			if(isset($_POST['gift_echeck_donation']) && is_numeric($_POST['gift_echeck_donation']))
			{
				$donation=$_POST['gift_echeck_donation'];
				if(!empty($donation) && $donation > 0)
				{
					$database->setGiftDonation($order_id, $donation);
				}
			}
		}
		$order_amount = $database->GetOrderAmount($order_id);
		$upload_amount = $order_amount['amount'];
		$donation = $order_amount['donation'];
		$totalamt= $upload_amount+$donation;
		if($upload_amount==0)
		{
			echo "Error Occured Please try again";
			exit;
		}
		$orderdesc= "Gift Card Purchase";
		$date= time();
		$orderdate= date("M j, Y",$date);
	}
	else if(isset($_SESSION['bidPaymentId']))
	{
		$bidPaymentId = $_SESSION['bidPaymentId'];
		if(isset($_POST['bid_payment_by_echeck']))
		{
			$upload_amount= $_POST['bid_echeck_amount'];
			$donation= $_POST['bid_echeck_donation'];
		}
		else
		{
			$upload_amount= $form->value('paymentamt');
			$donation= $form->value('donation');
		}
		$totalamt= $upload_amount+$donation;
		if($upload_amount=='')
		{
			echo "<script type='text/javascript'>window.location='index.php?p=52'</script>";
		}
		if($donation=='')
			$donation=0;
		$orderdesc= "Lender Account Funds Upload";
		$date= time();
		$orderdate= date("M j, Y",$date);
	}
	else
	{
		echo "Error Occured Please try again";
		exit;
	}
?>
<script type="text/javascript" src="includes/scripts/payment.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<div class="span12">
	<h4 align='center'>Upload Funds Via ACH Debit (E-Check)</h4>
	Please note that this payment option is only available for United States bank accounts. <br/>
	<form name="epayform" method="post" action="index.php?p=34">
	<table class='detail'>
		<tbody>
			<tr>
				<td colspan="2"><h3>Order Summary:</h3></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Transaction Date:</font></td>
				<td bgcolor="#f0f0f0"><?php echo $orderdate ?></td>
			</tr>
		<?php if(!isset($_POST['addDonation']) && !$form->value('addDonation')){   ?>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2"><?php echo $orderdesc?>: USD</font></td>
				<td bgcolor="#f0f0f0"><?php echo number_format($upload_amount, 2, '.', ',') ?></td>
			</tr>
		<?php }  ?>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Donation to Zidisha: USD</font></td>
				<td bgcolor="#f0f0f0"><?php echo number_format($donation, 2, '.', ',') ?></td>
			</tr>
		<?php if(!isset($_POST['addDonation']) && !$form->value('addDonation')){   ?>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Total Payment: USD</font></td>
				<td bgcolor="#f0f0f0"><?php echo number_format($totalamt, 2, '.', ',') ?></td>
			</tr>
		<?php }  ?>
			<tr>
				<td colspan="2"><h3>Billing Information:</h3></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Full Name:</font></td>
				<td bgcolor="#f0f0f0"><input type="text" value="<?php echo $form->value('UMname') ?>" size="25" name="UMname"></td>
			</tr>
		<?php if(!isset($_POST['addPayment']) && !$form->value('addPayment') && !isset($_POST['bid_payment_by_echeck']) && !$form->value('bid_payment_by_echeck')){   ?>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Email Address:</font></td>
				<td bgcolor="#f0f0f0"><input type="text" value="<?php echo $form->value('UMemail') ?>" size="25" name="UMemail"></td>
			</tr>
		<?php }  ?>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Address:
				</font></td>
				<td bgcolor="#f0f0f0"><input type="text" value="<?php echo $form->value('UMaddress') ?>" size="25" name="UMaddress"></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">City:</font></td>
				<td bgcolor="#f0f0f0"><input type="text" value="<?php echo $form->value('UMcity') ?>" size="25" name="UMcity"></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">State:</font></td>
				<td bgcolor="#f0f0f0"><input type="text" maxlength="2" value="<?php echo $form->value('UMstate') ?>" size="25" name="UMstate"></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Zip Code:</font></td>
				<td bgcolor="#f0f0f0"><input type="text" maxlength="9" value="<?php echo $form->value('UMzip') ?>" size="25" name="UMzip"></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Area Code and Telephone Number:</font></td>
				<td bgcolor="#f0f0f0"><input type="text" maxlength="20" value="<?php echo $form->value('UMphone') ?>" size="25" name="UMphone"></td>
			</tr>

			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Routing Number:</font></td>
				<td bgcolor="#f0f0f0"><input type="text" value="<?php echo $form->value('UMroutno') ?>" size="25" name="UMroutno"></td>
			</tr>
			<tr>
				<td bgcolor="#f0f0f0" style="text-align:right"><font size="2">Account Number:</font></td>
				<td bgcolor="#f0f0f0"><input type="text" value="<?php echo $form->value('UMaccountno') ?>" size="25" name="UMaccountno"></td>
			</tr>

			<tr><td bgcolor="#f0f0f0" colspan=2></td></tr>
			<tr>
				<td bgcolor="#f0f0f0" colspan="2">
					<p align="center">
			<?php	if(isset($_POST['addPayment']) || $form->value('addPayment')=='addPayment'){    ?>
					<input type="hidden" name="donation" value="<?php echo $donation ?>">
					<input type="hidden" name="addPayment" value="addPayment">
			<?php	}else if(isset($_POST['addDonation']) || $form->value('addDonation')=='addDonation'){    ?>
					<input type="hidden" name="donation" value="<?php echo $donation ?>">
					<input type="hidden" name="addDonation" value="addDonation">
			<?php	}elseif(isset($_POST['bid_payment_by_echeck']) || $form->value('bid_payment_by_echeck')=='bid_payment_by_echeck'){    ?>
					<input type="hidden" name="donation" value="<?php echo $donation ?>">
					<input type="hidden" name="bid_payment_by_echeck" value="bid_payment_by_echeck">
			<?php	}else {   ?>
					<input type="hidden" name="donation" value="<?php echo $donation ?>">
					<input type="hidden" name="giftPayment" value="giftPayment">
			<?php	}   ?>
					<input type="hidden" name="paymentamt" value="<?php echo $upload_amount ?>">
					<input type="hidden" name="orderdesc" value="<?php echo $orderdesc ?>">

					<input class='btn' type="button" onclick="return verify();" value="Process Payment" name="submitbutton"></p>
					<hr>
				</td>
			</tr>
			<tr>
				<td bgcolor="#ffffff" colspan="2">
					The routing number or ABA number is the first nine numbers in the lower left-hand corner of a check.  The account number will be the number immediately following the routing number, and before the check number.
					<div align="center">
						<table width="100%" cellspacing="1" border="0">
							<tbody>
								<tr>
									<td><p align="right"><img border="0" align="absmiddle" src="images/layout/icons/routing_and_account_no.png"></p></td>
									<td><p align="right"><img border="0" align="absmiddle" src="images/layout/icons/padonly.jpg"></p></td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		<tbody>
	</table>
	</form>
</div>