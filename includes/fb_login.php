<?php
	include("../library/database.php");
	include_once ('../facebook/facebook.php');

	$facebook = new Facebook(array('appId'  => FB_APP_ID,'secret' => FB_APP_SECRET));
	
	// login url
	echo '<a href="'.$facebook->getLoginUrl(array('redirect_uri' => SITE_URL.'includes/fb_login.php')).'">Login with facebook</a>';
	
	// get user id
	$uid = $facebook->getUser();
	
	// check whether user exist in database
	$check_user = $database->IsFacebookIdExist($uid);
	
	// if user exist
	if($check_user)
	{
		// todo
		echo '<br />User exist';
	}
	// if user doesn't exist
	else
	{
		// todo
		echo '<br />User doesn\'t exist';
	}
?>