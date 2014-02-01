<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once "library/session.php";
require_once "library/constant.php";
require_once "library/database.php";

					$deat="julia@zidisha.org";
					$From="service@zidisha.org";
					//$templet="editables/email/hero.html";
					require ("editables/mailtext.php");

					$borrowerid=12778;
					$loanid=4106;

					$params['bname'] = $database->getNameById($borrowerid);
					$params['profile_link'] = "https://www.zidisha.org/microfinance/loan/Halboucedric/4106.html";
					$params['image_src'] = $database->getProfileImage($borrowerid);
					//$Subject = $session->formMessage($lang['mailtext']['AcceptBid-subject'], $params);
					//$message = $session->formMessage($lang['mailtext']['AcceptBid-msg2'], $params);
					//$header = $session->formMessage($lang['mailtext']['AcceptBid-msg1'], $params);
					
					//$reply=$session->mailSendingHtml($From, '', $deat, $Subject, $header, $message,0,$templet,3,$params);
				
			$templet="editables/email/hero_template_images.html";
			$Subject=$lang['mailtext']['LenderReg-subject'];
		$header = $lang['mailtext']['LenderReg-msg1'];
		$message = $lang['mailtext']['LenderReg-msg'];
		$reply=$session->mailSendingHtml($From, '', $deat, $Subject, '', $message, 0, $templet, 3);

	
				

?>