<?php
ini_set('display_errors', 1);
date_default_timezone_set ('GMT');
define("USER", "root");// user name for database
define("COUNTRY", "US");
define("PASS", "");// user password for database
define("PATH", "localhost");// host name
define("NAME", "zidisha");// database name
//database table name constants
define("TBL_USERS", "users");
define("TBL_PARTNER", "partners");
define("TBL_LENDERS", "lenders");
define("TBL_BORROWER", "borrowers");
define("TBL_Admin", "usecurity");
define("TBL_ACTIVE_USERS", "active_users");
define("TBL_ACTIVE_GUESTS", "active_guests");
define ("TBL_LOANAPPLIC", "loanapplic");

//Transaction Types

define("FUND_UPLOAD", 1);
define("FUND_WITHDRAW", 2);
define("LOAN_SENT_LENDER", 3);
define("LOAN_BACK_LENDER", 4);
define("FEE", 5);
define("DISBURSEMENT", 6);
define("LOAN_BACK", 7);
define("GIFT_REDEEM", 8);
define("GIFT_PURCHAGE", 9);
define("GIFT_DONATE", 10);
define("DONATION", 11);
define("PAYPAL_FEE", 12);
define("REFERRAL_DEBIT", 13);
define("REGISTRATION_FEE", 14);
define("REFERRAL_CODE", 15);


//Sub Transaction Types

define("REFERRAL_CREDIT", 1);
define("DONATE_BY_ADMIN", 2);

//user level configuration
define("ADMIN_LEVEL", 9);
define("ADMIN_ID", 92);
define("PARTNER_LEVEL", 6);
define("LENDER_LEVEL", 4);
define("BORROWER_LEVEL", 1);
define("GUEST_LEVEL", 0);

define("COMMENT_TRANS", 0);
define("COMMENT_COMM", 1);
//user sub level configuration
define("READ_ONLY_LEVEL", 1);
define("LENDER_INDIVIDUAL_LEVEL", 2);
define("LENDER_GROUP_LEVEL", 3);
//user level to  text representation.
define("ADMIN_NAME", "Administrator");
define("LENDER_NAME", "Lender");
define("BORROWER_NAME", "Borrower");
define("GUEST_NAME", "Guest");
//loan stage configuration
define("NO_LOAN", 4);
define("LOAN_OPEN", 0);
define("LOAN_FUNDED", 1);
define("LOAN_ACTIVE", 2);
define("LOAN_REPAID", 3);
define("LOAN_DEFAULTED", 5);
define("LOAN_CANCELED", 6);
define("LOAN_EXPIRED", 7);
//various time outs for sessions configuration
define("ADMIN_TIMEOUT", 10*10*10);//added timeout for the administrator
define("USER_TIMEOUT", 10*10);
define("GUEST_TIMEOUT", 5);

define("TRACK_VISITORS", true);

define("PAYMENT_GETWAY", 2);


define("COOKIE_EXPIRE", 60*60*24*100);//cookie time out
define("COOKIE_PATH", "/");//cookie path
//websites addresses
define("EMAIL_FROM_NAME", "Test System");//this used in registration emails as sender name
define("EMAIL_TO_MAIL", "test@testsystem.org");
define("EMAIL_FROM_ADDR", "noreply@testsystem.org");//this used in registration emails as sender email address
define("EMAIL_WELCOME", true);

//web administrator email addresses
define("ADMIN_FROM_NAME", "Zidisha Administrator");//this used in emails as sender name
define("ADMIN_EMAIL_ADDR", "administrator@zidisha.org");//this used in emails as sender email
define("WEBSITE_ADDRESS", "http://localhost/zidisha_new/index.php");// this used in emails to again reach to a specific page

// image related configuration
define ('USER_IMAGE_DIR', 'C:/wamp/www/zidisha_new/images/client/');// default image directory
define ('UPLOAD_COMMENT_IMAGE_DIR', 'C:/wamp/www/zidisha_new/images/uploadComment/');
define("D_IMG", "images/client/dimg.gif");//default image path configuration
define ('TNSIZE' , 100);
define ('ALLWDSIZE' , 2000000);
define ('FILEEXT' , 'jpg, jpeg, gif');// allowed image file type for upload 'jpg, jpeg, gif'
define ('WATERMARK_SNAPS' , 1);
Define ('WATERMARK_TEXT_FONT',	'1'); // font size of water mark 1 / 2 / 3 / 4 / 5
Define ('TEXT_SHADOW',			'0'); // Showing a shadow with water mark text 1 - yes / 0 - no
Define ('TEXT_COLOR', '#000000');// water mark text colour
Define ('WATERMARK_ALIGN_H','right'); // water mark horizontal alignment  left / right / center
Define ('WATERMARK_ALIGN_V', 'bottom'); //water mark vertical alignment  top / bottom / center
Define ('WATERMARK_MARGIN',		10);// water mark margin
Define ('WATERMARK_TEXT',		'');// text displayed on uploaded pics
Define ('DEFAULT_IMAGE', 'file:dimg.jpg');
Define ('SITE_URL', 'http://localhost/zidisha_new/');


// paths
define("FULL_PATH", 'C:/wamp/www/zidisha_new/');//  physical full path for site
define("ROOT_PATH", 'C:/wamp/www/');//  physical root path for site
define("PEAR_DIR" , FULL_PATH.'extlibs/Pear/');// path of pear directory
define("CACHE_DIR" , FULL_PATH.'cache/');//path of cache directory
define("LOG_PATH", ROOT_PATH.'zidisha_contents/logs/');//  physical root path for site
define ("DOCUMENT_DIR", ROOT_PATH.'zidisha_contents/documents/');// borrower document directory
define ('TMP_IMAGE_DIR', FULL_PATH.'images/tmp/');// tmp image directory

//If using Personal Account on Paypal turn the below to 1. For business account 0
define ("PAYPAL_PERSONAL" , 0);
//Change the value to https://www.paypal.com/cgi-bin/webscr when using actual paypal
//no need to change if using personal account
define("PAYPALADDRESS", 'https://sandbox.paypal.com/cgi-bin/webscr');
//Enter business account email id
define("PAYPAL_ACCOUNT", "saran_1309244270_biz@codefire.in");

define("USE_PAYPAL", 1);
define("USE_PAPER_CHEQUE",true);
/*
use curl for paypal
*/

$curl_validation = false;

/*
SSL Settings :
Set true/fase if your sandbox server or your production server run on SSL
This will cause the IPN to run over SSL as well.  While this isn't critical because
IPN doesn't include highly sensitive data, it's still recommended when available.
*/
$sandbox_ssl = true;
$production_ssl = true;

/* payment gateway*/
//This file can be placed inside include folder
/* Config file for FirstData Payment Gateway */
define("FDAPI_URL","https://ws.merchanttest.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");
// URL for test store test payment.
//############## OR ###############
//define("FDAPI_URL", "https://ws.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");// URL for live store for actual payment.
define("FD_USERPWD", "WS1909555842._.1:hFTW5b5D");
//Replace WSXXXXXXXXX._.1:XXXXXXXX with actual username:password
define("FD_SSLCERT", FULL_PATH."certificate_test/WS1001249660._.1.pem");
//replace WSXXXXXXXX._.1.pem with actual pem file
define("FD_SSLKEY", FULL_PATH."certificate_test/WS1001249660._.1.key");
//replace WSXXXXXXXXX._.1.key with actual key file.
define("FD_SSLKEYPASSWD", "ckp_1287731569");
//Replace "ckp_1284657964" key with your store key . You can download all these from your store after loged into your account

define("ACH_URL","https://epn.checkgateway.com/epnpublic/achxml.aspx");
define("ACH_LOGIN","999999");
define("ACH_PASS","demoacct");
//donation type
define("ECHECK",1);
define("PAYPAL",2);

//events
define("NEW_LOAN_APPLICATION",1);
/*
	Events fields
	1. new loan id
	2. old loan id
	3. borrower id
*/
define("IS_LOCALHOST",true);
define("fixdate", "12/31/2008");
define("PAGINATION", 100);
define("RESENDINGDAYS", 30);
define("EXTEND_PERIOD_MULTIPLY",6);
define("RECAPCHA_PUBLIC_KEY","6Lej9M0SAAAAAJ6LQfRiraYHaa_t_QFpLDrOOv5j");
define("RECAPCHA_PRIVATE_KEY","6Lej9M0SAAAAAEgOXV_21hTUzoYRlckoeANlMHhc");
define("COOKIE_SECURE",false);
define("MAIL_CHIMP_API_KEY","bf45ecd13320a538e685744547ca9d39-us4");
define("MAIL_CHIMP_LIST_ID","a85a6c06ac");
define("MAIL_CHIMP_EMAIL","saran@codefire.in");
?>