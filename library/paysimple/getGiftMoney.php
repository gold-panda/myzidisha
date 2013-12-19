<?php
	include_once("../session.php");
?>

<?php
	
	if(isset($_SESSION['order_id']))
	{
		$time = time();
		$custom = md5($time);
		$order_id = $_SESSION['order_id'];		
		$order_amount = $database->GetOrderAmount($order_id);	
		$amount=$order_amount['amount'];
		Logger_Array("cvError_amount",$amount);
		if(!isset($session->userid))
			$userid=0;
		else
			$userid=$session->userid;		
		$invoiceid = $database->addNewPaySimpleTxn($userid, $amount,'START', $custom);		
		Logger_Array("cvError_invoiceid",$invoiceid);
		$res = $database->setInvoiceId($order_id,$invoiceid);
		Logger_Array("cvError_res",$res);
		if($res !=1)
		{
			echo "There was some problem please try again <a href='index.php?p=26'>click here</a>";
		}	
		$amount =  number_format($amount, 2, '.', ',');
	}
	else
	{
		echo "Error Occured";
		exit;
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Your Company Online BillPay Form</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_goToURL() { //v3.0
var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
</head>
<body onLoad="MM_goToURL('parent','https://www.usaepay.com/interface/epayform/1L1g49ZtE4q4Wz7GZee91379way70Jku/?UMamount= <?php echo $amount ?>&UMinvoice=<?php echo $invoiceid ?>&UMdescription=Gift Card Purchase');return document.MM_returnValue">
You are being redirected to the payment page. If you don't see the page in 10 seconds <a href="https://www.usaepay.com/interface/epayform/1L1g49ZtE4q4Wz7GZee91379way70Jku/?UMamount=<?php echo $amount ?>&UMinvoice=<?php echo $invoiceid ?>&UMdescription=Gift Card Purchase'">click here</a>.
</body>
</html>