<?php
require_once('../constant.php');
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
require_once("../session.php");
global $db;
$invoice = $ipn_data_array[UMinvoice];
if($invoice == 0)
{
	echo "There was a problem processing your transaction. Please contact admin to complete the transaction.";
}
else
{
	$txn_status = $database->getTransactionStatus($invoice);
	Logger_Array("cvError_txn_status",$txn_status,$invoice);
	if(strtoupper($txn_status) == 'START')
	{
		$ret = $database->saveRawIPNPaySimple($ipn_serialized);
		
		$q = "select * from ! where ipn_data_serialized = ?";
		$row = $db->getRow($q, array('paysimple_ipn_raw_log', $ipn_serialized));
		
		Logger( $ipn_serialized, $level )	;
		
		if($ipn_data_array[UMstatus] == "Approved" && $ipn_data_array[UMerrorcode] == 00000)
		{
			$database->updatePaySimpleTxn($invoice,$row['id']);		
			echo "Your payment transaction has been completed, and funds credited to your lender account.  Please click <a href='../../index.php?p=16&u=".$session->userid."'><strong>here</strong></a> to view your current account status.";
		
		}
		else
		{
			Logger( 'invalid IPN from PaySimple', $level )	;		
		}
	}
	else
	{
		echo "Your payment transaction has been completed, and funds credited to your lender account.  Please click <a href='../../index.php?p=16&u=".$session->userid."'><strong>here</strong></a> to view your current account status.";
	}
}
?>
