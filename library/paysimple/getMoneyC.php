<?php
	include_once("library/session.php");
?>
<script type="text/javascript" src="includes/scripts/payment.js"></script>
<?php

echo "<div id='pleasewait' style='margin-top:20px' align='center'>
Please do not refresh the page and do not hit the back botton while processing transaction.<br/><br/></div>";
$time = time();
$custom = md5($time); 
$UMname= sanitize($_POST['UMname']); 
$UMaddress= sanitize($_POST['UMaddress']); 
$UMcity= sanitize($_POST['UMcity']); 
$UMstate= sanitize($_POST['UMstate']); 
$UMzip= sanitize($_POST['UMzip']); 
$UMphone= sanitize($_POST['UMphone']); 
//$UMbirthday= sanitize($_POST['UMbirthday']); 
$UMroutno= sanitize($_POST['UMroutno']); 
$UMaccountno= sanitize($_POST['UMaccountno']); 
//$UMssn= sanitize($_POST['UMssn']); 
if(isset($_POST['addPayment']))
{
	$donation= sanitize($_POST['donation']); 
	$paymentamt= sanitize($_POST['paymentamt']); 
	$orderamt= $paymentamt + $donation;
}
else if(isset($_POST['giftPayment']))
{
	$paymentamt= sanitize($_POST['paymentamt']); 
	$orderamt= $paymentamt;
}
else{
	echo "error occurred. Error code-101";
	exit;
}

if(!isset($session->userid))
	$userid=0;
else
	$userid=$session->userid;
$rtn=0;

$invoiceid = $database->addNewPaySimpleTxn($userid, $orderamt,'START', $custom);
Logger_Array("invoiceid",$invoiceid);
if($invoiceid==0)
	$rtn=1;
if(isset($_POST['giftPayment']))
{
	$order_id = $_SESSION['order_id'];
	if($rtn==0){
		$res = $database->setInvoiceId($order_id,$invoiceid);
		Logger_Array("setInvoiceId",$res);
		if($res==0)
			$rtn=1;
	}
}
$url=ACH_URL;
$login=ACH_LOGIN;
$password=ACH_PASS;
$refnum=$invoiceid;
$body = '<?xml version="1.0"?>	
			<ACH>
				<Method>Debit</Method>
				<Version>1.4.2</Version>
				<Login>'.$login.'</Login>
				<Password>'.$password.'</Password>
				<ReferenceNumber>'.$refnum.'</ReferenceNumber>
				<Amount>'.number_format($orderamt, 2, '.', ',').'</Amount>
				<RoutingNumber>'.$UMroutno.'</RoutingNumber>
				<AccountNumber>'.$UMaccountno.'</AccountNumber>
				<Name>'.$UMname.'</Name>				
				<Address1>'.$UMaddress.'</Address1>
				<City>'.$UMcity.'</City>
				<State>'.$UMstate.'</State>
				<Zip>'.$UMzip.'</Zip>
				<Phone>'.$UMphone.'</Phone>
			</ACH>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	// return response
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt ( $ch , CURLOPT_SSL_VERIFYPEER, 0 );
curl_setopt ( $ch , CURLOPT_SSL_VERIFYHOST, 0 );
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

if($rtn==0){
	$result = curl_exec($ch);
	$xml = simplexml_load_string($result);
}
else
{
	echo "Some probem occurred please try again. Error code-102";
	Logger_Array("before curl execution",$rtn);
	curl_close($ch);
	exit;
}
Logger_Array("transaction result original",$result);
Logger_Array("transaction result in array",$xml);
curl_close($ch);
$txn_status = $database->getTransactionStatus($invoiceid);

if(strtoupper($txn_status) == 'START')
{	
	if($rtn==0){
		$rawid = $database->saveRawIPNPaySimple($result);	
		if($rawid==0)
			$rtn=1;
	}
	$res= $xml['Success'];
	
	if(strtoupper($res)==strtoupper('TRUE'))
	{
		if(isset($_POST['addPayment']))
		{
			if($rtn==0)
			{
				$database->startDbTxn();
				$rest= $database->updatePaySimpleTxn($invoiceid,$rawid,$orderamt, $donation,1);	
				if($rest==0)
					$rtn=1;
			}
			if($rtn==0){
				$database->commitTxn();
				if($donation > 0)
				{
					$session->sendDonationMail($userid,$donation);
				}
				$_SESSION['success']=1;
				echo "<SCRIPT type='text/javascript'>
				paymentRedirect();     
				</SCRIPT>";
			}
			else{
				$database->rollbackTxn();
				echo "Your payment transaction has been completed <font color='red'>but some problem occurred in site please contact to zidisha for further processing</font>";
				exit;
			}
		}
		else if(isset($_POST['giftPayment']))
		{
			if($rtn==0)
			{
				$database->startDbTxn();
				$res4 = $database->updatePaySimpleTxnForGift($invoiceid,$rawid);
				if($res4==0)
					$rtn=1;
			}
			$order_id = $database->getOrderIdByInvoiceid($invoiceid);			
			$_SESSION['orderid']=$order_id;
			if($rtn==0)
			{
				$res1 = $database->updateGiftTransaction($order_id);   
				if($res1==0)
					$rtn=1;
			}
			if($rtn==0)
			{
				$res2 = $session->sendGiftCardMailsToSender($order_id);
				$res3 = $session->sendGiftCardMailsToReciever($order_id);
			}			
			if($rtn==0)
			{
				$database->commitTxn();
				echo "<SCRIPT type='text/javascript'>
				hdrRedirect();     
				</SCRIPT>";  
			}
			else
			{
				$database->rollbackTxn();
				echo "Your payment transaction has been completed <font color='red'>but some problem occurred in site please contact to zidisha for further processing</font>";
				exit;
			}
		}
		
	}
	else
	{
		$TxnStatus= $xml['Status'];
		$Message= $xml->Message;
		if(isset($_POST['addPayment']))
		{
			$database->updatePaySimpleTxn($invoiceid,$rawid,$orderamt, $donation,2);
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();

			echo "<h4>Transaction Response from Check Gateway</h4>";
			if(strlen($TxnStatus) >1)
				echo "<b>Transaction Result:</b> ".$TxnStatus."<br/>";
			else
				echo "<b>Transaction Result:</b> FAILED<br/>";
	
			$ErrorMessage= $xml->Message;
			if(strlen($ErrorMessage) >1)
				echo "<b>Error Message:</b> ".$ErrorMessage."<br/>";
			
			echo "<br/><br/><br/>";
			echo "<font color='red'>Your transaction could not be processed due to above error return by payment gateway. To correct the data and retry please click <a href='index.php?p=33'><strong>here</strong></a>, or to try at a later time please click <a href='index.php?p=17'><strong>here</strong></a></font>";
		}
		else if(isset($_POST['giftPayment']))
		{
			$database->updatePaySimpleRejTxn($invoiceid,$rawid);
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();

			echo "<h4>Transaction Response from Check Gateway</h4>";
			if(strlen($TxnStatus) >1)
				echo "<b>Transaction Result:</b> ".$TxnStatus."<br/>";
			else
				echo "<b>Transaction Result:</b> FAILED<br/>";
	
			$ErrorMessage= $xml->Message;
			if(strlen($ErrorMessage) >1)
				echo "<b>Error Message:</b> ".$ErrorMessage."<br/>";

			echo "<br/><br/><br/>";
			echo "<font color='red'>Your transaction could not be processed due to above error return by payment gateway. To correct the data and retry please click <a href='index.php?p=33'><strong>here</strong></a>, or to try at a later time please click <a href='index.php?p=17'><strong>here</strong></a></font>";
		}			
	}
}
else
{
	echo "Some probem occurred please try again. Error code-103";
}
?>