<?php
include_once("library/session.php");
$loggedinId=$session->userid;
$download=false;

if(isset($_GET['u']) && isset($_GET['doc']) && ($_GET['doc']=='frontNationalId' || $_GET['doc']=='backNationalId' || $_GET['doc']=='addressProof' || $_GET['doc']=='legalDeclaration' || $_GET['doc']=='legal_declaration2')) {
	$uid=$_GET['u'];
	if ($loggedinId==ADMIN_ID || $database->isBorrowerAssignedToThisPartner($uid, $loggedinId) || $uid ==$loggedinId){
		$download=true;
	}
	if ($download) {
		$result=$database->getBorrowerById($uid);
		$dir='';
		if (!empty($result['frontNationalId']) && $_GET['doc']=='frontNationalId') {
			$dir= DOCUMENT_DIR.$result['frontNationalId'];
		} elseif (!empty($result['backNationalId']) && $_GET['doc']=='backNationalId') {
			$dir= DOCUMENT_DIR.$result['backNationalId'];
		} elseif (!empty($result['addressProof']) && $_GET['doc']=='addressProof') {
			$dir= DOCUMENT_DIR.$result['addressProof'];
		}
		elseif (!empty($result['legalDeclaration']) && $_GET['doc']=='legalDeclaration') {
			$dir= DOCUMENT_DIR.$result['legalDeclaration'];
		}
		elseif (!empty($result['legalDeclaration']) && $_GET['doc']=='legal_declaration2') {
			$dir= DOCUMENT_DIR.$result['legal_declaration2'];
		}
		if (!empty($dir)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($dir));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($dir));
			ob_clean();
			flush();
			readfile($dir);
		}
	}
}
exit;