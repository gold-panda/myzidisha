<?php
include_once("session.php");

/*function test_paypal () {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://www.sandbox.paypal.com/cgi-bin/webscr");
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,    'cmd=_cart&upload=1&business=myloginemail&item_name1=aggregate%20items&amount=10.00') ;
		$result = curl_exec ($ch);
		curl_close ($ch);
	}    

test_paypal() ;*/

if(isset($session->userid) && isset($_POST['item_name_1']) && isset($_POST['amount_1']) && $_POST['amount_1'] != 0){
	//$invoiceid = $database->getNextInvoice($session->userid, stripslashes($_POST['amount_1'],'START');
	$invoiceid = 1;
	$values = "cmd=_cart";
	$values .= "&upload=1";
	$values .= "&business=".PAYPAL_ACCOUNT;
	$values .= "&invoice=".$invoiceid;
	if (isset($_POST['item_name_1']) && isset($_POST['amount_1'])){
		$values .= "&item_name_1=".stripslashes($_POST['item_name_1']);
		$values .= "&amount_1=".stripslashes($_POST['amount_1']);
	}
	get_web_page( PAYPALADDRESS,$values );
}

function get_web_page( $url,$curl_data )
{
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"http://www.sandbox.paypal.com/cgi-bin/webscr");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,    'cmd=_cart&upload=1&business=pranja_1242728885_biz@jaguarinfotech.com&item_name1=aggregate%20items&amount=10.00') ;
		

	
    $content = curl_exec($ch);
	//echo 'hi';
    curl_close($ch);

  //  $header['errno']   = $err;
  //  $header['errmsg']  = $errmsg;
  //  $header['content'] = $content;
    return $header;
}
?>