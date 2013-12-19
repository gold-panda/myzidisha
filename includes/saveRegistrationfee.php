<?php
//saveRegistrationfee


include("../library/session.php");

	global $session;

        $j=$_GET['k'];
		$amount=$_GET['mamount'.$j];
        $currencyid=$_GET['amtcurrencyid'];
		//echo $amount;
		//echo $currencyid;
		//exit;
		$result=$session->setEditAmount($amount,$currencyid);
		if($result)
		{
         echo 0;
		}
		else
		{
         echo 1;
		}
?>


