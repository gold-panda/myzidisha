<?php
	include_once("../session.php");
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
<?php
$bodyLoad=0;
if(isset($_SESSION['order_id']))
{
	$time = time();
	$custom = md5($time);
	$order_id = $_SESSION['order_id'];
	unset($_SESSION['order_id']);
	Logger_Array("cvError", 'order_id before adding paypal transaction', $order_id);
	if(isset($_POST['gift_paypal_donation']))
	{
		$donation=$_POST['gift_paypal_donation'];
		if(!empty($donation) && $donation > 0)
		{
			$database->setGiftDonation($order_id, $donation);
		}
	}
	$order_amount = $database->GetOrderAmount($order_id);
	$paypal_amount=$order_amount['amount'];
	
	Logger_Array("cvError", 'paypal_amount before adding paypal transaction', $paypal_amount);
	if($paypal_amount >0)
	{
		$paypal_donation =$order_amount['donation'];
		$paypalTranFee= $database->getAdminSetting('PaypalTransaction');
		$paypalTranAmount=(($paypal_amount * $paypalTranFee)/100);
		$totalAmt= $paypal_amount + $paypalTranAmount + $paypal_donation;
		$totalAmt= number_format($totalAmt, 2,'.','');
		$userid=0;
		if(!empty($session->userid))
			$userid=$session->userid;
		$invoiceid = $database->addNewPayPalTxn($userid, $paypal_amount, $paypalTranAmount, $paypal_donation, $totalAmt, 'START', $custom, 'gift');
		Logger_Array("cvError", 'invoiceid after adding paypal transaction', $invoiceid);
		if(!empty($invoiceid))
		{
			$bodyLoad=1;
			$res = $database->setInvoiceId($order_id,$invoiceid);
		}
	}
}
if($bodyLoad)
{	?>
	<body onload="validate_form(document.form1)">
	<div style="font-family:verdana,arial,helvetica,sans-serif;font-size:11px">
<center>
	Please wait while your transaction is being processed.
<center>
</div>
<?php
}
else
{	?>
	<body>
	<div style="font-family:verdana,arial,helvetica,sans-serif;font-size:11px">
<center>
	Some problem occurred please try again<br/><br/>
<center>
</div>
<?php
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

</body>
</html>