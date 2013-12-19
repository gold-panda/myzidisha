<?php
include_once("library/session.php");
include_once("./editables/withdraw.php");
$path=	getEditablePath('withdraw.php');
include_once("editables/".$path);
?>
<div class="span12">
	<?php
	if(isset($_SESSION['success'])){
		echo "<div align='center'><font color=green><strong>Thank you for your donation. We have sent a receipt which may be used for tax purposes to given email address.</strong></font></div>";
		unset($_SESSION['success']);
	}
	if(isset($_SESSION['notloggedin'])){
		echo "<div align='center'><font color=red><strong>You are not loggedin please login with your lender account.</strong></font></div>";
		unset($_SESSION['notloggedin']);
	}
	$option=1;
?>
	
	<div align='left' class='static'><h1>Donate</h1></div>
	<p>Zidisha is a nonprofit organization. We rely on voluntary contributions from our lenders and supporters to help cover operating expenses such as web hosting, bank fees, telephone costs, regulatory fees and ongoing development of our web platform.</p>
	
	<p>We do not charge any service fee to lenders, but instead ask that those who participate contribute what they can afford to support Zidisha's growth. Zidisha is a 501(c)(3) nonprofit organization, and your donation is tax deductible in the United States.</p>
	<br/>
	<a href="includes/financial_report.php" rel="facebox"><?php echo $lang['withdraw']['financial_report']?></a>
	<br/><br/>
	<strong>Option <?php echo $option; $option++; ?>: Donate Via Credit Card or PayPal</strong><br/><br/>
	<p>Residents of all countries may make donations via PayPal or credit card here:</p><br/>
	<form action="process.php" method="post">
		<table class = "detail">
		<tr>
			<td>
				<?php echo $lang['withdraw']['donate_amt'];?>
			</td>
			<td>
				<input type="text" name="donation" id = "paypal_donation"><br/>
				<?php echo $form->error("donation"); ?>
			</td>
		</tr>
		<tr height="10px"></tr>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="addPayment">
				<input type="hidden" size="5" name="paypal_amount" id="paypal_amount" value="0.00"  autocomplete="off">
				<input type="hidden" size="5" name="paypal_transaction" id="paypal_transaction" value="0.00" autocomplete="off" readonly="true">
				<input type="hidden" name="addPayment">
				<input type="hidden" name="paypal_trans" id="paypal_trans" value="0.0">
				<input type="hidden" value="0.00" name="paypal_amount" id="hidden_paypal_amount">
				<input type="hidden" name="isdonation" value='1'>
				<input class="btn" type="submit" value="Continue">
			</td>
		</tr>
		</table>
	</form>
	
	<!---
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> <input type="hidden" name="cmd" value="_s-xclick"> <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7----- "> <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"> <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"> </form> --> 
	<br/><br/>
	<strong>Option <?php echo $option; $option++; ?>: Donate Via Bank Transfer</strong><br/><br/>
	<p>Please note that if you choose this option, both the sending bank and our receiving bank will deduct transfer fees from the amount credited to Zidisha. The fee amounts vary depending on the sending bank, and can add up to USD 50 or more for a single transaction.</p>
	<p>Please instruct your bank to transfer the desired amount to Zidisha Incorporated, with recipient bank details as follows:</p>
	<p>Name of Bank: Wells Fargo Bank<br/>Wire Routing Transit Number: 121000248<br/>SWIFT Code: WFBIUS6S<br/>City, State: San Francisco, California, United States<br/>Account Number: 2000053250148<br/>Title of Account: Zidisha Inc.</p>
	<p>Then send an email to service@zidisha.org to let us know that the transfer is intended as a donation.</p>
	<br/>
	<?php if(USE_E_CHEQUE) { ?>
	<strong>Option <?php echo $option; $option++; ?>: Donate Via ACH Debit (E-Check)</strong><br/><br/>
	<p>You may transfer your donation via e-check or an automated debit from your US bank account by entering the amount you wish to donate below. The payment will be processed by Check Gateway, a secure third-party payment gateway.</p>
	<form action="index.php?p=33" method="post">
	<table class='detail' style='width:auto;'>
		<tbody>
			<tr>
				<td>E-Check Donation Amount:</td>
				<td><input type="text" size=5 name="donation" id="donation_amount" value="" autocomplete=off></td>
				<td align="center"><input type="hidden" name="addDonation">
				<input class='btn' type="submit" value="Next"></td>
			</tr>
		</tbody>
	</table>
	</form>
	<?php } ?>
	<?php if(USE_PAPER_CHEQUE) { ?>
	<strong>Option <?php echo $option; $option++; ?>: Donate Via Check</strong><br/><br/>
	<p>Holders of United States bank accounts may also donate by check.  To make a donation by check, please mail a check made out to Zidisha Inc. to our address below, making sure to indicate on the check that the payment is a donation.</p>
	<p>Zidisha Inc.<br/>21900 Muirfield Circle, #302<br/>Sterling, VA 20164 USA</p>
	<br/>
	<?php } ?>
	
	
	<p>Thanks so much for your support!</p>
</div>