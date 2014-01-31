<link rel="stylesheet" href="css/default/redesign.css" type="text/css" media="screen">
<?php
	include_once ("library/database.php");
	include_once ("facebook/facebook.php");
	
	$facebook = new Facebook(array('appId'  => FB_APP_ID,'secret' => FB_APP_SECRET));
	
	// get user id
	$uid = $facebook->getUser();
	
	// check whether user exist in database
	$check_user = $database->getExistFbUser($uid);
	
	// if user tries to log in
	if ($_GET['code'])
	{
		// if user exist
		if($check_user)
		{
			$session->userinfo  = $check_user;
			$session->username  = $_SESSION['username'] = $session->userinfo['username'];
			$session->fullname  = $session->userinfo['name'];
			$session->userid    = $_SESSION['userid'] = $session->userinfo['userid'];
			$session->userlevel = $session->userinfo['userlevel'];
			$session->usersublevel = $_SESSION['sublevel'] = $session->userinfo['sublevel'];
			
		?>
			<script>
				window.location.assign('<?php echo SITE_URL ?>');
			</script>
		<?php
			die();
			
		}
		// if user doesn't exist
		else
		{
			// todo
			echo '<p style="width:100%;font-size:20px;clear:both;">Email doesn\'t exist in our database, so you should register</p>';
		}
	}
	
?>
<div class="title">Sign in</div>
<div class="custom_login">
	<div class="login_fb">
		<p class="heading">Sign in using Facebook</p>
		<p class="description">
			Skip the forms by signing in with your Facebook account.
		</p>
		<a href="<?= $facebook->getLoginUrl(array('redirect_uri' => SITE_URL.'index.php?p=116')) ?>"><img src="images/login_with_facebook.png" width="280" height="55" /></a>
	</div>
	<div class="separator">
		<div class="circle">OR</div>
		<div class="line"></div>
	</div>
	<div class="default_login">
		<p class="heading">Sign in using our form</p>
		<form method="post" action="process.php">
			<p><input type="text" name="username" placeholder="username or email" /></p>
			<p><input type="password" name="password" placeholder="password" /></p>
			<p><label class="checkbox">Remember me<input type="checkbox"/></label></p>
			<p style="clear:both;"><button type="submit" class="btn square">Go</button></p>
			<p><a style="color:gray" href="index.php?p=56">Forgot your password?</a></p>
			<p>Not a member? <a style="color:#FF8B00;font-weight:bold;font-size:14px;" href="index.php?p=1&amp;sel=1">Join</a></p>
		</form>
	</div>
</div>