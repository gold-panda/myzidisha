<?php
	require_once("library/session.php");
	$path=	getEditablePath('getinvolved.php');
	include_once("./editables/".$path);
	echo $lang['getinvolved']['desc'];
?>