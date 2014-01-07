<?php
//constant.php
define("RANDOM_NUMBER", "1287942845");
date_default_timezone_set ('EST');
ini_set('include_path', '/home4/semfundc/public_html/zidisha/extlibs/Pear/:.:/usr/local/lib/php/');
//database constants for connection settings
define("COUNTRY", "US");
define("PATH", "localhost");// host name
define("NAME", "semfundc_zidisha");// database name

define("USER", $_SERVER["ENV_DB_USER"]);// user name for database
define("PASS", $_SERVER["ENV_DB_PASS"]);// user password for database

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
define("LOAN_BID", 16);
define("LOAN_OUTBID", 17);

//Sub Transaction Types
define("REFERRAL_CREDIT", 1);
define("DONATE_BY_ADMIN", 2);
define("PLACE_BID", 3);
define("UPDATE_BID", 4);
define("LOAN_BID_EXPIRED", 5);
define("LOAN_BID_CANCELED", 6);
//2012-12-27 Anupam added two new transaction type so that we can track which fund upload done by admin and by paypal 
define("UPLOADED_BY_ADMIN", 7);
define("UPLOADED_BY_PAYPAL", 8);

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

//auto lending preferences configuration
define("HIGH_FEEDBCK_RATING", 1);
define("EXPIRE_SOON", 2);
define("HIGH_OFFER_INTEREST", 3);
define("HIGH_NO_COMMENTS", 4);
define("LOAN_RANDOM", 5);
// duration in days before we send loan expire mails
define("LOAN_EXPIRE_MAIL_DURATION", 7);
//auto lend amount configuration
define("AUTO_LEND_AMT", 10);


//various time outs for sessions configuration
define("ADMIN_TIMEOUT", 10*10*10);//added timeout for the administrator
define("USER_TIMEOUT", 10*10);
define("GUEST_TIMEOUT", 5);

define("TRACK_VISITORS", true);

define("PAYMENT_GETWAY", 2);


define("COOKIE_EXPIRE", 60*60*24*30);//cookie time out
define("COOKIE_PATH", "/");//cookie path
//websites addresses
define("EMAIL_FROM_NAME", "Zidisha Team");//this used in registration emails as sender name
define("EMAIL_TO_MAIL", "julia@zidisha.org");
define("EMAIL_FROM_ADDR", "service@zidisha.org");//this used in registration emails as sender email address
define("EMAIL_WELCOME", true);

//web administrator email addresses
define("SERVICE_EMAIL_ADDR", "service@zidisha.org");//this used in emails as sender email 
define("ADMIN_FROM_NAME", "Zidisha Administrator");//this used in emails as sender name
define("ADMIN_EMAIL_ADDR", "administrator@zidisha.org");//this used in emails as sender email
define("WEBSITE_ADDRESS", "https://www.zidisha.org/index.php");// this used in emails to again reach to a specific page

// image related configuration
define ('USER_IMAGE_DIR', '/home4/semfundc/public_html/zidisha/images/client/');// default image directory
define ('UPLOAD_COMMENT_IMAGE_DIR', '/home4/semfundc/public_html/zidisha/images/uploadComment/');
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
// TODO: Require environment site URL to end in a slash
Define ('SITE_URL', (substr($_SERVER["ENV_SITE_URL"], -1) === '/') ? $_SERVER["ENV_SITE_URL"] : $_SERVER["ENV_SITE_URL"].'/');

// paths

define("FULL_PATH", $_SERVER["ENV_FULL_PATH"]);//  physical full path for site
define("ROOT_PATH", $_SERVER["ENV_ROOT_PATH"]);//  physical root path for site

define("PEAR_DIR" , FULL_PATH.'extlibs/Pear/');// path of pear directory
define("CACHE_DIR" , FULL_PATH.'cache/');//path of cache directory
define("LOG_PATH", ROOT_PATH.'zidisha_contents/logs/');//  physical root path for site
define ("DOCUMENT_DIR", ROOT_PATH.'zidisha_contents/documents/');// borrower document directory
define ('TMP_IMAGE_DIR', FULL_PATH.'images/tmp/');// tmp image directory
//If using Personal Account on Paypal turn the below to 1. For business account 0
define ("PAYPAL_PERSONAL" , 0);
//Change the value to https://www.paypal.com/cgi-bin/webscr when using actual paypal
//no need to change if using personal account
define("PAYPALADDRESS", 'https://www.paypal.com/cgi-bin/webscr');
//Enter business account email id
define("PAYPAL_ACCOUNT", "service@zidisha.org");

define("USE_PAYPAL", 1);
/*
use curl for paypal
*/
//enable check payment type
define("USE_PAPER_CHEQUE",true);
define("USE_E_CHEQUE",false);
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
//define("FDAPI_URL","https://ws.merchanttest.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");
// URL for test store test payment.
//############## OR ###############
define("FDAPI_URL", "https://ws.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl");// URL for live store for actual payment.
define("FD_USERPWD", "WS1001249660._.1:yyMHneqN");
//Replace WSXXXXXXXXX._.1:XXXXXXXX with actual username:password
define("FD_SSLCERT", FULL_PATH."certificate/WS1001249660._.1.pem");
//replace WSXXXXXXXX._.1.pem with actual pem file
define("FD_SSLKEY", FULL_PATH."certificate/WS1001249660._.1.key");
//replace WSXXXXXXXXX._.1.key with actual key file.
define("FD_SSLKEYPASSWD", "ckp_1288192815");
//Replace "ckp_1284657964" key with your store key . You can download all these from your store after loged into your account
define("ACH_URL","https://epn.checkgateway.com/epnpublic/achxml.aspx");
define("ACH_LOGIN","501398");
define("ACH_PASS","whfkyndk");


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
define("IS_LOCALHOST",false);
define("fixdate" , "12/31/2008");
define ("PAGINATION", 100);
define("RESENDINGDAYS", 30); // send reminder for invites after these many days
define("EXTEND_PERIOD_MULTIPLY",6);
define("RECAPCHA_PUBLIC_KEY","6Lej9M0SAAAAAJ6LQfRiraYHaa_t_QFpLDrOOv5j");
define("RECAPCHA_PRIVATE_KEY","6Lej9M0SAAAAAEgOXV_21hTUzoYRlckoeANlMHhc");
// THIS NEEDS TO BE FALSE FOR LOGINS TO WORK LOCALLY.
// In theory, it is set as false in constant_local.php, but you cannot define something twice.
// So the true value remains, and thus there is the bug on the local environment.
define("COOKIE_SECURE", $_SERVER["ENV_COOKIE_SECURE"]);
define("MAIL_CHIMP_API_KEY","78d5f8a7b16869bcdedad6eb696435e6-us1");
define("MAIL_CHIMP_LIST_ID","d2aeae704b");
define("MAIL_CHIMP_EMAIL","julia@zidisha.org");
// duration in months we sent mail those lender who have not login for  x months .
define("ACCOUNT_EXPIRE_MAIL_DURATION", 12);
define("LENDER_ACCOUNT_EXPIRE_DURATION", 13);
// duration in months account will expire of lenders from today.
define("ACCOUNT_EXPIRE_DURATION", 1);
// duration in days within borrower can pay their installments we will treat as ontime repayment.
// duration in days within borrower can pay their installments we will treat as ontime repayment.
define("REPAYMENT_THRESHOLD", 10);
// amount in USD within borrower can pay their installments we will treat as ontime full repayment.
define("REPAYMENT_AMT_THRESHOLD", 5);
define("FORGIVE_REMINDER_DAYS", 8);
define("FORGIVE_REMINDER_PERIOD", 4);
//admin setting for two types of loan one is below of amount and second is above
define("AMTTHRESHOLD_LIMIT", 3000);
define("AMTTHRESHOLD_BELOW_LIMIT", 200);	// define by mohit on date 29-11-13 to add setin gon loan pplication page
define("AMTTHRESHOLD_MEDIUM_LIMIT", 1000);
define("FB_APP_ID", "325782300883543");
define("FB_APP_SECRET", "7da30e5106e2139e40f304c62ac1ba78");
define("FIRST_LOANARREAR_REMINDER", "4");
define("FINAL_LOANARREAR_REMINDER", "14");
define("MONTHLY_LOANARREAR_REMINDER", "30");

/*Events names*/
 define("COMMENT_POST_EVENT", "11"); // Added by mohit on 22-10-13
/* Telerevert api settings move here from session.php file by mohit on date 28-11-13*/
define("TELEREVERT_API_KEY","DA7Z7NAFTAED7TQH3G3PRZGK7NMR2WAE");
define("PROJECTID","PJ3e913d700ca3a696ac6b1a324c0943b3");
define("PHONE_ID","PN6a83fb89665d4447");	// update the phone id as per julia mail on 27-11-13
define("SHIFT_SCIENCE_KEY","e33ffd8ca8b19175"); // Shift Science API KEY
 