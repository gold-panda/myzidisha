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

//print_r($ipn_data_array);

// Store IPN data serialized for RAW data storage later
$ipn_serialized = serialize($ipn_data_array);


// Store RAW IPN log in the DB
require_once("../session.php");
$ret = $database->saveRawIPNPaySimple($ipn_serialized);

global $db;
$q = "select * from ! where ipn_data_serialized = ?";
$row = $db->getRow($q, array('paysimple_ipn_raw_log', $ipn_serialized));

//update code goes here utkarsh
	$invoice = $ipn_data_array[UMinvoice];
	
	require_once("../session.php");
	$database->updatePaySimpleRejTxn($invoice,$row['id']);
	

echo "Your transaction could not be processed because your credit card was not recognized.  Please reenter your credit card information and try again.<br/>Please click on <a href='getGiftMoney.php'><strong>next</strong></a> to proceed";
//echo "Your transaction could not be processed because your credit card was not recognized.  Please reenter your credit card information and try again.<br/>Please Click on <a href='https://www.usaepay.com/interface/epayform/4416WBll38CA9xsLK0X84u7R063cP47B/sale/?UMamount=".$ipn_data_array[]."&UMinvoice=".$ipn_data_array[UMinvioce]."&UMdescription=Lender Account Funds Upload'><strong>next</strong></a> to proceed";
?>
