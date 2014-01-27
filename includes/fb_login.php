<?php
	include("../library/database.php");
	include_once ('../facebook/facebook.php');

	$facebook = new Facebook(array('appId'  => FB_APP_ID,'secret' => FB_APP_SECRET));
	echo '<a href="'.$facebook->getLoginUrl().'">Login with facebook</a>';
	$uid = $facebook->getUser();
	echo $uid;
	//$check_user = IsFacebookIdExist
?>