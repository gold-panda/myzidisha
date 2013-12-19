<html>
<head>
<script type="text/javascript" src="includes/scripts/giftajax.js?q=<?php echo RANDOM_NUMBER ?>"></script>
</head>
</html>
<?php
require_once('library/constant.php');
//print_r($_POST);
// Read the post from paysimple system and add 'cmd'   
$req = 'cmd=_notify-validate';   
  
// Store each $_POST value in a NVP string: 1 string encoded and 1 string decoded   
$ipn_email = '';  
$ipn_data_array = array();
foreach ($_POST as $key => $value)   
{   
 $value = urlencode(stripslashes($value));   
 $req .= "&" . $key . "=" . $value;   
 $ipn_email .= $key . " = " . urldecode($value) . '<br />';  
 $ipn_data_array[$key] = urldecode($value);
}


// Store IPN data serialized for RAW data storage later
$ipn_serialized = serialize($ipn_data_array);

// Store RAW IPN log in the DB
require_once("library/session.php");
$invoice = $ipn_data_array[UMinvoice];
if($invoice == 0)
{
	echo "There was a problem processing your transaction. Please contact admin to complete the transaction.";
}
else
{
	global $db;
	$txn_status = $database->getTransactionStatus($invoice);
	Logger_Array("cvError_txn_status",$txn_status,$invoice);
	if(strtoupper($txn_status) == 'START')
	{
		$ret = $database->saveRawIPNPaySimple($ipn_serialized);
		Logger_Array("cvError_ret",$ret);
		
		$q = "select * from ! where ipn_data_serialized = ?";
		$row = $db->getRow($q, array('paysimple_ipn_raw_log', $ipn_serialized));
		Logger_Array("cvError_row",$row);
		Logger( $ipn_serialized, $level )	;
		
		if($ipn_data_array[UMstatus] == "Approved" && $ipn_data_array[UMerrorcode] == 00000)
		{
			$res4 = $database->updatePaySimpleTxnForGift($invoice,$row['id']);
			Logger_Array("cvError_res4",$res4);
			$order_id = $database->getOrderIdByInvoiceid($invoice);
			Logger_Array("cvError_order_id",$order_id);
			$_SESSION['orderid']=$order_id;
			$res1 = $database->updateGiftTransaction($order_id);   /*   1 for paysimple transaction approve  */
				Logger_Array("cvError_res1",$res1);
			$res2 = $session->sendGiftCardMailsToSender($order_id);
				Logger_Array("cvError_res2",$res2);
			$res3 = $session->sendGiftCardMailsToReciever($order_id);
				Logger_Array("cvError_res3",$res3);
			echo "<SCRIPT type='text/javascript'>
					hdrRedirect();     
					</SCRIPT>";   /*encript id and invoiceid*/
		}
		else
		{
			Logger( 'invalid IPN from PaySimple', $level )	;
		}
	}
	else
	{
		echo "Your transaction has been already completed.<br>";
		echo "click <a href='index.php?p=28'>here</a> to order review.<br>";
		echo "click <a href='index.php'>here</a> to go home page.";
		//header('Location: index.php?p=28');
	}
}
?>
