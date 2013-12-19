<?php
	include_once("library/session.php");
?>
<script type="text/javascript" src="includes/scripts/payment.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<?php

echo "<div id='pleasewait' style='margin-top:20px' align='center'>
We are processing your transaction. Please do not refresh the page or navigate back while the transaction is being processed.<br/><br/></div>";
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
else if(isset($_POST['addDonation']))
{
	$UMemail= sanitize($_POST['UMemail']);
	$donation= sanitize($_POST['donation']);
	$paymentamt= sanitize($_POST['paymentamt']);
	$orderamt= $paymentamt + $donation;
}
else if(isset($_POST['giftPayment']))
{
	$UMemail= sanitize($_POST['UMemail']);
	$paymentamt= sanitize($_POST['paymentamt']);
	$donation= sanitize($_POST['donation']);
	$orderamt= $paymentamt + $donation;
}
else if(isset($_POST['bid_payment_by_echeck']))
{
	$donation= sanitize($_POST['donation']);
	$paymentamt= sanitize($_POST['paymentamt']);
	$orderamt= $paymentamt + $donation;
}
else{
	echo "We were unable to process the transaction. Please verify that the information entered is accurate and try again, or complete the transaction using a different payment method.";
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
	echo "We were unable to process the transaction. Please verify that the information entered is accurate and try again, or complete the transaction using a different payment method.";
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
				if($paymentamt > 0)
				{
					$session->sendFundUploadMail($userid,$paymentamt);
				}
				$_SESSION['success']=1;
				echo "<SCRIPT type='text/javascript'>
				paymentRedirect();
				</SCRIPT>";
			}
			else{
				$database->rollbackTxn();

				echo "Your payment was completed successfully, but an error occured while attempting to credit your account. Please contact us at service@zidisha.org to complete the crediting of your account.";
				exit;
			}
		}
		else if(isset($_POST['addDonation']))
		{
			if($rtn==0)
			{
				$database->startDbTxn();
				$rest= $database->updatePaySimpleTxnForDonation($invoiceid,$rawid,$donation,1,$UMname,$UMemail);
				if($rest==0)
					$rtn=1;
			}
			if($rtn==0){
				$database->commitTxn();
				if($donation > 0)
				{
					$session->sendDonationMail($userid,$donation,$UMemail,$UMname);
				}
				$_SESSION['success']=1;
				echo "<SCRIPT type='text/javascript'>
				donationRedirect();
				</SCRIPT>";
			}
			else{
				$database->rollbackTxn();

				echo "Your payment was completed successfully, but an error occured while attempting to credit your account. Please contact us at service@zidisha.org to complete the crediting of your account.";
				exit;
			}
		}
		else if(isset($_POST['giftPayment']))
		{
			if($rtn==0)
			{
				$database->startDbTxn();
				$res4 = $database->updatePaySimpleTxnForGift($invoiceid,$rawid, $paymentamt, $donation, $UMname,$UMemail);
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
				if($donation > 0)
				{
					$session->sendDonationMail($userid,$donation,$UMemail,$UMname);
				}
			}
			if($rtn==0)
			{
				unset($_SESSION['order_id']);
				$database->commitTxn();
				echo "<SCRIPT type='text/javascript'>
				hdrRedirect();
				</SCRIPT>";
			}
			else
			{
				$database->rollbackTxn();
				echo "Your payment was completed successfully, but an error occured while attempting to credit your account. Please contact us at service@zidisha.org to complete the crediting of your account.";
				exit;
			}
		}
		else if(isset($_POST['bid_payment_by_echeck']))
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
				if($paymentamt > 0)
				{
					$session->sendFundUploadMail($userid,$paymentamt);
				}
				$bidData= $database->getBidDetail($_SESSION['bidPaymentId'], $userid);
				$_SESSION['bidPaymentSuccess']=1;
				echo "<SCRIPT type='text/javascript'>
				bidRedirect(".$bidData['loanid'].", ".$bidData['borrowerid'].", ".$bidData['bidup'].");
				</SCRIPT>";
			}
			else{
				$database->rollbackTxn();
				unset($_SESSION['bidPaymentId']);
				echo "Your payment was completed successfully, but an error occured while attempting to credit your account. Please contact us at service@zidisha.org to complete the crediting of your account.";
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

			echo "<b>Transaction Result:</b> FAILED<br/><br/>";
			echo "<font color='red'>We were unable to process the transaction. Please verify that the information entered is accurate and try again, or complete the transaction using a different payment method. To correct the information and retry please click <a href='index.php?p=33'><strong>here</strong></a>, or to try at a later time please click <a href='index.php?p=17'><strong>here</strong></a></font>";
		}
		else if(isset($_POST['addDonation']))
		{
			$database->updatePaySimpleTxnForDonation($invoiceid,$rawid,$donation,2,$UMname,$UMemail);
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();

			echo "<b>Transaction Result:</b> FAILED<br/><br/>";
			echo "<font color='red'>We were unable to process the transaction. Please verify that the information entered is accurate and try again, or complete the transaction using a different payment method. To correct the information and retry please click <a href='index.php?p=33'><strong>here</strong></a>, or to try at a later time please click <a href='index.php?p=38'><strong>here</strong></a></font>";
		}
		else if(isset($_POST['giftPayment']))
		{
			$database->updatePaySimpleRejTxn($invoiceid,$rawid);
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();

			echo "<b>Transaction Result:</b> FAILED<br/><br/>";
			echo "<font color='red'>We were unable to process the transaction. Please verify that the information entered is accurate and try again, or complete the transaction using a different payment method. To correct the information and retry please click <a href='index.php?p=33'><strong>here</strong></a>, or to try at a later time please click <a href='index.php?p=17'><strong>here</strong></a></font>";
		}
		else if(isset($_POST['bid_payment_by_echeck']))
		{
			$database->updatePaySimpleTxn($invoiceid,$rawid,$orderamt, $donation,2);
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();

			echo "<b>Transaction Result:</b> FAILED<br/><br/>";
			echo "<font color='red'>We were unable to process the transaction. Please verify that the information entered is accurate and try again, or complete the transaction using a different payment method. To correct the information and retry please click <a href='index.php?p=33'><strong>here</strong></a>, or to try at a later time please click <a href='index.php?p=52'><strong>here</strong></a></font>";
		}
	}
}
else
{
	echo "We were unable to process the transaction. Please verify that the information entered is accurate and try again, or complete the transaction using a different payment method.";
}
?>