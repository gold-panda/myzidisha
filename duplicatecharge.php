<?php
include_once("library/session.php");
global $database,$session;
$row['paypal_tran_fee'] =3.50;
$row['userid'] = 2558;
$row['total_amount'] = 105.50;
$row['donation'] = 2.0;
$database->startDbTxn();
	$res1= $database->setTransaction($row['userid'],$row['total_amount'],'Funds upload to lender account',0,0,FUND_UPLOAD);
	sleep(1);
	if($res1===1)
	{
		$paypal_tran_fee= $row['paypal_tran_fee'] *-1;
		if($row['paypal_tran_fee'] > 0) {
			$res2= $database->setTransaction($row['userid'],$paypal_tran_fee,'Paypal transaction fee',0,0,PAYPAL_FEE);
		}else {
			$res2=1;
		}
		
		sleep(1);
		if($res2===1)
		{
			if($row['paypal_tran_fee'] > 0) {
				$res3= $database->setTransaction(ADMIN_ID,$row['paypal_tran_fee'],'Lender transaction fee',0,0,PAYPAL_FEE);
			}else {
				$res3 = 1;
			}
			sleep(1);
			if($res3===1)
			{
				$return = false;
				if($row['donation'] >0)
				{	

					$donationamt= $row['donation'] *-1;
					$res4= $database->setTransaction($row['userid'],$donationamt,'Donation to Zidisha',0,0,DONATION);
					sleep(1);
					if($res4===1)
					{
						$res5= $database->setTransaction(ADMIN_ID,$row['donation'],'Donation from lender',0,0,DONATION);
						if($res5===1)
						{
							$database->commitTxn();
							echo "complete";
							$return = true;
						}
						else
							$database->rollbackTxn();
					}
					else
						$database->rollbackTxn();
				}
				else
				{
					$database->commitTxn();
					echo "complete";
					$return = true;

				}
			
			}
			else
				$database->rollbackTxn();
		}
		else
			$database->rollbackTxn();
	}
	else
		$database->rollbackTxn();
?>