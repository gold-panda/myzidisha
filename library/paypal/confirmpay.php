<?php


$req = 'cmd=_notify-synch';
$req .= "&tx=$_GET['tx']";
$req .= "&at=-vdGAsY3fXnFB1tygme2p1arSeo7sFGITn761OYJxw5tG4nTvfrGeivHrxG";

/*$ppCurl = curl_init(); // initialize curl handle
curl_setopt($ppCurl, CURLOPT_POST, true); // set POST method
curl_setopt($ppCurl, CURLOPT_URL, 'https://www.sandbox.paypal.com/cgi-bin/webscr'); // set url
curl_setopt($ppCurl, CURLOPT_POSTFIELDS, $req); // fields to POST
curl_setopt($ppCurl, CURLOPT_RETURNTRANSFER, true); // return var
curl_setopt($ppCurl, CURLOPT_TIMEOUT, 4); // time out after 5 secs
curl_setopt($ppCurl, CURLOPT_FAILONERROR, true);
curl_setopt($ppCurl, CURLOPT_FOLLOWLOCATION, true); // allow redirects
curl_setopt($ppCurl, CURLOPT_FRESH_CONNECT, true); // no caching
$result = curl_exec($ppCurl); // engage!
$curlErrorNum = curl_errno($ppCurl); // save error code; 0=none
$curlErrorText = curl_error($ppCurl); // save error message; ""=none
$header  = curl_multi_getcontent ( $ppCurl);
curl_close($ppCurl);
*/

$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.sandbox.paypal.com/cgi-bin/webscr', 443, $errno, $errstr, 30);
if (!$fp) {
// HTTP ERROR
} else {
	fputs ($fp, $header . $req);
	while (!feof($fp)) {
		$res = fgets ($fp, 1024);
	}
}
echo $res ;
Logger($res, 5);
function Logger( $text, $level ) {
	$gLogError =1;
	$gLogErrorFile = "/var/www/jit/paypal/log1.txt";   
	if($gLogError) {
		$text = date('D M j G:i:s T Y') . "     $text". "\n";
		$file = $gLogErrorFile ;
		ErrorLog( $text, $file );
	}
}

function ErrorLog( $text, $file ) {

	$exists = file_exists( $file );
	$size = $exists ? filesize( $file ) : false;
	if ( !$exists || ( $size !== false && $size + strlen( $text ) < 0x7fffffff ) ) {
		error_log( $text, 3, $file );
	}
	
}
?>