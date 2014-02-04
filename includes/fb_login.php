<link rel="stylesheet" href="css/default/redesign.css" type="text/css" media="screen">
<script type="text/javascript" src="includes/scripts/jquery.simplemodal.js"></script>
<link rel="stylesheet" href="css/default/jquery.simplemodal.css" type="text/css" media="screen">
<script type="text/javascript" src="includes/scripts/redesign.js"></script>
<?php
	include_once ("library/database.php");
	include_once ("facebook/facebook.php");
	
	// checking if user has been tried to connect his facebook account with already existing Zidisha account and if there are any errors
	if($session->facebook_connect_modal = 1 && $form->error("username") && $form->error("password") )
	{
		?>
			
			<script>
				$(function(){
					show_facebook_modal();
				})
			</script>
			
		<?php
		
		unset($session->facebook_connect_modal);
		
		
	}
	
	$facebook = new Facebook(array('appId'  => FB_APP_ID,'secret' => FB_APP_SECRET));
	
	// get user id
	$uid = $facebook->getUser();
	
	// check whether user exist in database
	$check_user = $database->getExistFbUser($uid);
	
	// if user tries to log in
	if ($_GET['code'])
	{
		// if user exist with facebook connect
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
			
			
		}
		// if user doesn't exist
		else
		{
			$session->facebook_connect_modal = 1;
		?>
			<script>
				$(function(){
					show_facebook_modal();
				})
			</script>
		<?php
		}
	}
	
?>
<!-- Sign in -->
<div class="title">Sign in</div>
<div class="custom_login">
	<!-- Sign in with facebook -->
	<div class="login_fb">
		<p class="heading">Sign in using Facebook</p>
		<p class="description">
			Skip the forms by signing in with your Facebook account.
		</p>
		<a class="facebook_button" href="<?= $facebook->getLoginUrl(array('redirect_uri' => SITE_URL.'index.php?p=116')) ?>"><img src="images/login_with_facebook.png" width="280" height="55" /></a>
	</div>
	<!-- Separator -->
	<div class="separator">
		<div class="circle">OR</div>
		<div class="line"></div>
	</div>
	<!-- Default login -->
	<div class="default_login">
		<p class="heading">Sign in using our form</p>
		<!-- if there are any errors -->
		<p>
			<?php
				if ($session->facebook_connect_modal != 1){
					echo $form->error("username");
					echo $form->error("password");
				}
			?>
		</p>
		<form class="default_login_form" method="post" action="process.php">
			<input type="hidden" name="userlogin" />
			<input type="hidden" name="facebook_id" value="<?php echo $uid; ?>" />
			<p><input type="text" name="username" placeholder="username or email" /></p>
			<p><input type="password" name="password" placeholder="password" /></p>
			<p><label class="checkbox">Remember me<input type="checkbox"/></label></p>
			<p class="custom_margin" style="clear:both;"><button type="submit" class="btn square">Go</button></p>
			<p class="custom_margin"><a style="color:gray" href="index.php?p=56">Forgot your password?</a></p>
			<p class="custom_margin">Not a member? <a style="color:#FF8B00;font-weight:bold;font-size:14px;" href="index.php?p=1&amp;sel=1">Join</a></p>
		</form>
	</div>
</div>

<!-- ***POP UP*** Facebook connect to existing account -->
<div id="basic-modal-content" class="facebook_connect" align="left">
	<p class="terms_of_use_modal blue_color uppercase">Facebook connect to an existing Zidisha.org account</p>
	<p><?php echo $form->error("username"); ?> <?php echo $form->error("password"); ?></p>
</div>