<script type="text/javascript" src="includes/scripts/payment.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<div class='span12'>
<?php
	include_once("library/session.php");
	
	if(isset($_GET['tx']) && isset($_GET['st']) && $_GET['st'] == 'Completed')
	{
		$rtn= $database->updatePayPalTxn( $_GET['tx'], $_GET['amt'],$_GET['st'], $_GET['cm']);
		Logger( "In Paypaldetails \n".serialize($rtn));
		if(!empty($rtn) && $rtn['txn_type']=='fund')
		{
			$availAmt = $session->amountToUseForBid($session->userid);
			$availAmt = truncate_num(round($availAmt, 4),2);
			$bidData=array();
			$processCart = array();
			/*if(isset($_SESSION['bidPaymentId'])) {
				$bidData= $database->getBidDetail($_SESSION['bidPaymentId'], $session->userid);
			}
			else{
				$bidData= $database->getBidDetailByCustom($_GET['cm']);
			}*/
			
			if(isset($_SESSION['PayLendingCart'])) {

					$processCart = $database->getcartDetailByCustom($_GET['cm']);
			}
			Logger( "In Paypaldetails PayLendingCart session \n".$_SESSION['PayLendingCart']);
			Logger( "In Paypaldetails data in processCart\n".serialize($processCart));
			if(empty($processCart)) {
				Logger( "redirecting to fund upload confirm page \n");
				echo "<div>Thank you! Your payment has been processed and funds credited to your lender account. If you made a donation, a donation receipt has been sent to the email address registered with your account.</div>";
				echo "<br/><br/>";
				echo "<div align='left'>You now have USD ".number_format($availAmt, 2, ".", ",")." available for lending.  <a href='microfinance/lend.html'>Make a loan</a></div>";
			}
			if(!empty($bidData)) {
				$_SESSION['bidPaymentSuccess']=1;
				echo "<SCRIPT type='text/javascript'>
					bidRedirect(".$bidData['loanid'].", ".$bidData['borrowerid'].", ".$bidData['bidup'].");
					</SCRIPT>";
			}
			if(!empty($processCart)) {
				if(is_array($processCart)) {
					Logger( "In Paypaldetails redirecting to bid page\n");
					$_SESSION['lender_bid_success1']=1;
					$_SESSION['lender_bid_success_amt']=$processCart['bidamt'];
					$_SESSION['lender_bid_success_int']=$processCart['bidint'];
					echo "<SCRIPT type='text/javascript'>
						bidRedirect(".$processCart['loanid'].", ".$processCart['borrowerid'].", ".
							'1'.");     
					</SCRIPT>"; 
				}else {
					Logger( "In Paypaldetails redirecting to giftcard page \n".$_GET['cm']);
					$_SESSION['PaidGiftcardCart'] = $_GET['cm'];
						echo "<SCRIPT type='text/javascript'>
								hdrRedirect();     
							</SCRIPT>"; 
				}
				
			}
		}
		elseif(!empty($rtn) && $rtn['txn_type']=='gift')
		{
			echo "<SCRIPT type='text/javascript'>
				hdrRedirect();     
				</SCRIPT>"; 
		}
		else
		{
			Logger_Array("cvError", 'paypal transaction update failed, transaction-id', $_GET['tx']);
			echo "<div align='center'><font color=green><b>Thank you! The transaction is not yet complete. We will update your <a href='index.php?p=16&u=$session->userid'>Account</a> when the transaction is completed by Paypal.</b></font></div>";
			echo "<br/><br/>";
			echo "<div align='left'><b>Paypal Transaction Id:</b> ".$_GET['tx']."</div>";
		}
	}	
	else if(isset($_GET['tx']))
	{
		echo "<div align='center'><font color=green><b>Thank you! The transaction is not yet complete. We will update your <a href='index.php?p=16&u=$session->userid'>Account</a> when the transaction is completed by Paypal.</b></font></div>";
		echo "<br/><br/>";
		echo "<div align='left'><b>Paypal Transaction Id:</b> ".$_GET['tx']."</div>";
	}
?>
</div>