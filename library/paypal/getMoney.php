<?php
ob_start();
	include_once("../session.php");
	if(empty($session->userid)&& !isset($_POST['isdonation'])) {
		$_SESSION['notloggedinuser'] = true;
		header("Location: ".SITE_URL."index.php?p=75");
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Payment</title>
<script type="text/javascript">

function validate_form(thisform)
{
	document.form1.submit();
}
</script>
</head>
<body onload="validate_form(document.form1)">
<?php
	$time = time();
	$custom = md5($time);
	$paypal_amount=0;
	$paypal_donation=0;
	$donated_amt=0;
	if(isset($_SESSION['paypal_amount'])) { 
		$_POST['paypal_amount'] = $_SESSION['paypal_amount'];
		$_POST['paypal_donation'] = $_SESSION['paypal_donation'];
		$_POST['donated_amt'] = $_SESSION['donated_amt'];
		unset($_SESSION['paypal_donation']);
		unset($_SESSION['paypal_amount']);
		unset($_SESSION['donated_amt']);
	}
	if(isset($_SESSION['lending_cart_paypal'])) {
		
		$_POST['lending_cart_paypal'] = $_SESSION['lending_cart_paypal'];
		unset($_SESSION['lending_cart_paypal']);
	}

	if(isset($_POST['bid_paypal_amount']))
	{
		$paypal_amount = strip_tags(trim($_POST['bid_paypal_amount']));
		$paypal_donation = strip_tags(trim($_POST['bid_paypal_donation']));
	}
	else
	{
		$paypal_amount = strip_tags(trim($_POST['paypal_amount']));
		$paypal_donation = strip_tags(trim($_POST['paypal_donation']));
		$donated_amt= strip_tags(trim($_POST['donated_amt']));
	}
	$paypalTranFee= $database->getAdminSetting('PaypalTransaction');
	if($donated_amt>0){
		$paypalTranAmount=(($donated_amt * $paypalTranFee)/100);
	}elseif(isset($_POST['addPayment'])){
		$paypalTranAmount=(($_POST['paypal_amount'] * $paypalTranFee)/100);
	}else{
		$paypalTranAmount=0;
	}
	$totalAmt= $paypal_amount + $paypalTranAmount + $paypal_donation;
	$totalAmt= number_format($totalAmt, 2,'.','');
	$invoiceid = $database->addNewPayPalTxn($session->userid, $paypal_amount, $paypalTranAmount, $paypal_donation, $totalAmt, 'START', $custom);
	
	if(isset($_POST['bid_paypal_amount']))
	{
		$database->setBidInvoice($_SESSION['bidPaymentId'], $invoiceid);
	}

	if(isset($_POST['lending_cart_paypal']))
	{	
		Logger_Array("lending cart payment",$_POST['lending_cart_paypal']);
		$database->setLendingCartInvoice($invoiceid);
		$_SESSION['PayLendingCart'] = 1;
	}
?>
<form name='form1' id='form1' action=<?= PAYPALADDRESS ?> method="post">
  <input type="hidden" name="cmd" value="_cart">
  <input type="hidden" name="upload" value="1">
  <input type="hidden" name="business" value=<?=PAYPAL_ACCOUNT ?> >
  <input type="hidden" name="item_name_1" value="Add to Zidisha Account">
  <input type="hidden" name="amount_1" value="<?php echo $totalAmt ?>">
  <input type="hidden" name="invoice" value= <?php echo $invoiceid ?> >
  <input type="hidden" name="custom" value= <?php echo $custom ?> >

</form>
<div style="font-family:verdana,arial,helvetica,sans-serif;font-size:11px">
<center>
	Please wait while your transaction is being processed.
<center>
</div>
</body>
</html>