<?php 
include_once("library/session.php");
$url=SITE_URL;
if (isset($_GET['ident']) && isset($_GET['activate'])) 
{
	$userId= $_GET['ident'];
	$activateKey= $_GET['activate'];
	$thekey =	$database->getActivationKey($userId);
	if($thekey===$activateKey)
	{
		$res= $database->emailVerified($userId);
		if($res) {
			$result=$session->loginAsUser($userId);
			$userDetail= $database->getUserById($userId);
			if($userDetail['userlevel']==BORROWER_LEVEL) {
				$_SESSION['bEmailVerified']=true;
				$url .='index.php?p=1&sel=4&t=1';
			} elseif($userDetail['userlevel']==LENDER_LEVEL) {
				$session->sendWelcomeMailToLender($userDetail['name'], $userDetail['email']);
				if(!empty($userDetail['subscribe_newsletter']))
					$session->subscribeLender($userDetail['email'], $userDetail['FirstName'], $userDetail['LastName']);
				$_SESSION['lEmailVerified']=true;
				$url .='index.php?p=1&sel=4&t=2';
			}
			elseif($userDetail['userlevel']==PARTNER_LEVEL) {
				$session->sendWelcomeMailToPartner($userDetail['name'], $userDetail['email']);
				$_SESSION['pEmailVerified']=true;
				$url .='index.php?p=1&sel=4&t=3';
			}
		}
	}
}
$session->redirect($url);