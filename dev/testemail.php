<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once "library/session.php";
require_once "library/constant.php";
require_once "library/database.php";

					$deat="julia@zidisha.org";
					$From="service@zidisha.org";
					$templet="editables/email/hero.html";
					require ("editables/mailtext.php");
					$To= $params['name'] = $deat;
					$params['bname'] = "John Deer";
					$params['link'] = SITE_URL;
					$params['lend_link'] = WEBSITE_ADDRESS.'?p=2';
					$params['image_src'] = $database->getProfileImage(16004);
					$Subject = $session->formMessage($lang['mailtext']['AcceptBid-subject'], $params);
					$message = $session->formMessage($lang['mailtext']['AcceptBid-msg2'], $params);
					$header = $session->formMessage($lang['mailtext']['AcceptBid-msg1'], $params);
					$reply=$session->mailSendingHtml($From, $To, $deat, $Subject, $header, $message,0,$templet,3,$params);
				
				

?>