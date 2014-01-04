<?php

	error_log("enviro name:" . $_SERVER['env_name']);

/*	if (isset($_GET["p"])) {
		error_log("value of GET['p']: " . $_GET["p"]);
	} else {
		error_log("value of GET['p'] not set");
	}
*/

	include("library/session.php");

	// error_log("FOOO");
	
	//Anupam 22-11-201 redirect https://www.zidisha.org/index.php to https://www.zidisha.org/
	if ($_SERVER['REQUEST_URI']=='/index.php')
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".SITE_URL);
	}
	global $database,$session;
	RedirectLoanprofileurl();
	RedirectUserprofileurl();
	$language = '';
	if(isset($_GET["language"])) {
		$language = $_GET["language"];
	}
	$language1="English";
	if($language!='')
		$language1= $database->getLanguageByCode($language);
	include_once("./editables/menu.php");
	$path=	getEditablePath('menu.php');
	include_once("./editables/".$path);
	include_once("./editables/loginform.php");
	$path=	getEditablePath('loginform.php');
	include_once("./editables/".$path);
	$page=0;
	if(isset($_GET["p"])){
		$page=$_GET["p"];
	}

	// error_log("value of $page: " . $page);

	if($page==1001)
	{
		header("location:landing_page/fullyfunded.html");
		exit;
	}
	if($page==2 || $page==65 || $page==64|| $page==26|| $page==67|| $page==38|| $page==6|| $page==47|| $page==48|| $page==3|| $page==4|| $page==62|| $page==69) {
		if(isset($_SERVER['HTTP_REFERER'])) {
			$HTTP_REFERER=$_SERVER['HTTP_REFERER'];
		}else {
			$HTTP_REFERER='';
		}
		logger("Requesturl ".$_SERVER['REQUEST_URI']." HTTP_REFERER".$HTTP_REFERER);

	}
	if(isset($_GET["refid"])){
		$refid=$_GET["refid"];
		$database->updateInviteVisitor($refid);
	}
	
	/*
		functon for resending mails to invited people who have not visited our site

	*/
	//$session->sendMonthlyLoanArrearMail();
//$session->sendLoanFinalArrearMail();
//	$session->sendLoanFirstArrearMail();
	//$session->sendagainRepaymentReminder();
	//$session->SendRepaymentReminderMails();
	//$session->DeactivateAndDonate();
	//$session->SendExpiringLoanMailToBorrower();
	//$session->sendAccountExpiredMail();
	//$session->reInvite_frnds();
	//$session->feedbackReminder();
//	$session->DeactivateExpiredGiftCard();
	
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php if($language=='') {?>
		<BASE href="<?php echo SITE_URL?>">
	<?php }else { ?>
		<BASE href="<?php echo SITE_URL.$language?>/">
	<?php } ?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Zidisha Microfinance is the first peer-to-peer microlending service to offer direct interaction between lenders and borrowers across international borders."/>
	<meta name="author" content=""/>
	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
		<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--Free Trial Font Script-->
	<!--<script type="text/javascript" src="http://fast.fonts.com/jsapi/bfbca778-9cdd-41d5-befe-e91a01baadf2.js"></script>-->
	<!--Paid Font Script-->
	<!--<script type="text/javascript" src="https://fast.fonts.com/jsapi/0929098d-fa4b-407d-bb59-a9c929284820.js"></script>-->
	<!--Paid Font CSS-->

	<link href="https://fast.fonts.com/cssapi/0929098d-fa4b-407d-bb59-a9c929284820.css" rel="stylesheet" type="text/css" />
	<!-- Script For FireBug-->
	<!--<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>-->
	<!-- Le styles -->
	<link href="css/default/main.css" rel="stylesheet"/>
	<!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="images/favicon.ico"/>
	<!-- <link rel="apple-touch-icon" href="images/apple-touch-icon.png"/>
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png"/>
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png"/> -->

	<!-- old site script and css files -->
	<script type="text/javascript" src="includes/scripts/jquery.js" ></script>
	<script type="text/javascript" src="includes/scripts/jquery.tablesorter.js?q=<?php echo RANDOM_NUMBER ?>"></script>
	
	<!-- script to load randomly selected hero image -->
	<script type="text/javascript" src="includes/scripts/facebox/facebox.js?q=<?php echo RANDOM_NUMBER ?>"></script>
	<link href="includes/scripts/facebox/facebox.css?q=<?php echo RANDOM_NUMBER ?>" media="screen" rel="stylesheet" type="text/css" />
	<link href="css/default/popup_style.css?q=<?php echo RANDOM_NUMBER ?>" rel="stylesheet"/>
	<?php include_once("includes/title.php"); ?>
	<script type="text/javascript">
		   function pwdFocus() {
				$('#textfield').hide();
				$('#pwdfield').show();
				$('#pwdfield').focus();
			}

			function pwdBlur() {
				if ($('#pwdfield').attr('value') == '') {
					$('#pwdfield').hide();
					$('#textfield').show();
				}
			}
	</script>
	<script type="text/javascript">
	<!--
		function loginfocus(str) {
			if (str == "<?php echo $lang['loginform']['username_login']?>") {
					document.getElementById('username').value="";
					document.getElementById('username').style.fontStyle="normal";
				}
		}
		function loginblur(str) {
			if (str == "") {
					document.getElementById('username').value="<?php echo $lang['loginform']['username_login']?>";
				}
		}
	//-->
	</script>
	
<script type="text/javascript">
	$(document).ready(function() {
		$('#setLanguage').click(function() {
		$('#languages').slideToggle("slow");
	});
	$('#langPointer').click(function() {
		$('#languages').slideToggle("slow");
	});
		$('a[rel*=facebox]').facebox({
		loadingImage : '<?php echo SITE_URL?>includes/scripts/facebox/loading.gif',
		closeImage   : '<?php echo SITE_URL?>includes/scripts/facebox/closelabel.png'
	  });
	
	/* $('#password-clear').show();
	 $('#password-password').hide();
	$('#password-clear').focus(function() {
		$('#password-clear').hide();
		$('#password-password').show();
		$('#password-password').focus();
	});
	$('#password-password').blur(function() {
		if($('#password-password').val() == '') {
			$('#password-clear').show();
			$('#password-password').hide();
		}
	});*/
	/*$('.login-input').each(function() {
		var default_value = this.value;
		$(this).focus(function() {
			if(this.value == default_value) {
				this.value = '';
				 $(this).css("font-style","normal");
			}
		});
		$(this).blur(function() {
			if(this.value == '') {
				this.value = default_value;
				 $(this).css("font-style","italic");
			}
		});
	});*/

});

</script>
<script type="text/javascript">
	var siteurl = "<?php echo SITE_URL ?>";
	<?php
		$rqstUri=$_SERVER['REQUEST_URI'];
		$rqstUri=  str_replace("/zidisha","",$rqstUri);
		$pos= stripos($rqstUri, 'index');
		$m_pos=stripos($rqstUri, 'microfinance');
		if($pos >0)
		{
			$rqstUri= substr($rqstUri, $pos, strlen($rqstUri));
		}
		else if($m_pos>0){
			$rqstUri= substr($rqstUri, $m_pos, strlen($rqstUri));
		}else
		{
			$rqstUri='';
		}
		$count=1;
	?>
	var rqstUri = "<?php echo $rqstUri ?>";
</script>
	
<?php $langfrmIP='';
if($language==''){
	if(!isset($_SESSION['CodeByIp'])) {
		$country = getCountryCodeByIP();
		
	}
	if(isset($country['code']) && $country['code']!='') {
		$_SESSION['CodeByIp'] = $country['code'];
		if($country['code']=='SN' || $country['code']=='BF' || $country['code']=='BJ' || $country['code']=='GN' || $country['code']=='HT' || $country['code']=='NE' || $country['code'] == 'FR' ) {
		$langfrmIP ='fr';
	}else if($country['code']=='ID') {
		$langfrmIP ='in';
	}
}
	
} ?>
<script type="text/javascript">
	if("<?php echo $langfrmIP ?>" != "") {
	<!--	
		setLanguage("<?php echo $langfrmIP?>");	
	//-->
}
<!--
	function setLanguage(lan){
		if(lan=='en'){
			url= siteurl+rqstUri;
			window.location=url;
		}else{
			url= siteurl+lan+'/'+rqstUri;
			window.location=url;
		}
	}

//-->
</script>

<!-- start Google Analytics code -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-23722503-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<!-- end Google Analytics code -->

<!-- start SiftScience code -->

<script type="text/javascript">

  var _user_id = "<?php echo $session->userid ?>"; // TODO: Set to the user's ID, username, email address, or '' if not yet known

  var _sift = _sift || []; _sift.push(['_setAccount', '946aa02e41']); _sift.push(['_setUserId', _user_id]); _sift.push(['_trackPageview']); (function() { function ls() { var e = document.createElement('script'); e.type = 'text/javascript'; e.async = true; e.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.siftscience.com/s.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(e, s); } if (window.attachEvent) { window.attachEvent('onload', ls); } else { window.addEventListener('load', ls, false); }})();
</script>

<!-- end SiftScience code -->

</head>
<body>

	<div class="container">
			<div id="top-right">
				<div id='top-links' style="float:right;">
					
<a href="microfinance/press.html"><?php echo $lang['menu']['zidisha_in_news'] ?></a> &nbsp;<span>|</span>&nbsp;

<a href="http://p2p-microlending-blog.zidisha.org/" target="_blank"><?php echo $lang['menu']['blog'] ?></a> &nbsp;<span>|</span>&nbsp;

<a href="http://www.amazon.com/Venture-Collection-Microfinance-Stories-ebook/dp/B009JC6V12" target="_blank"><?php echo $lang['menu']['ebook'] ?></a> &nbsp;<span>|</span>&nbsp;

<a href="microfinance/microfinance.html"><?php echo $lang['menu']['abt_microfinance'] ?></a> &nbsp;<span>|</span>&nbsp;

<a href="https://www.zidisha.org/index.php?p=80">Lending Groups</a> &nbsp;<span>|</span>&nbsp;

<!--
					<a href="microfinance/gift-cards.html"><?php echo $lang['menu']['gift_cards'] ?></a> &nbsp;<span>|</span>&nbsp; 
-->
					 
<!-- 
<a href="microfinance/newsletter.html"><?php echo $lang['menu']['newsletter'] ?></a> &nbsp;<span>|</span>&nbsp; 
-->
					   
					<!-- <a href="intern.html"><?php echo $lang['menu']['interns'] ?></a> &nbsp;<span>|</span>&nbsp;  -->

<!-- <a href="microfinance/donate.html"><?php echo $lang['menu']['donate'] ?></a> &nbsp;<span>|</span>&nbsp; -->

<a href="http://zidisha.org/forum/"><?php echo $lang['menu']['user_forum'] ?></a> &nbsp;<span>|</span>&nbsp; <a href="microfinance/contact.html"><?php echo $lang['menu']['contact_us'] ?></a> &nbsp;<span>|</span>&nbsp; <a href="javascript:void(0)" id="setLanguage"><?php echo $language1 ?></a> <a style="position:relative;top:-2px;left:-6px" href="javascript:void(0)" id="langPointer"><img border='0' style='cursor:pointer' src='images/layout/table_show/asc.gif' alt=''/></a>
				</div>
				<div style="clear:both"></div>
				<div id='languages' class="top-language" style="display:none">
					<?php
						$langs= $database->getActiveLanguages();
						echo "<ul>";
						echo "<li><a href='javascript:void(0)' onclick='javascript:setLanguage(\"en\");' style='color:gray'>English</a></li>";
						foreach($langs as $row)
						{
							echo "<li><a href='javascript:void(0)' onclick='javascript:setLanguage(\"".$row['langcode']."\");' style='color:gray'>".$row['lang']."</a></li>";
						}
						echo "</ul>";
					?>
				  </div>
				  <div style="clear:both"></div>
	
	<?php	if(empty($session->userid))
			{	?>
				<!-- 31-10-2012 Anupam, Ebook link (requested to remove) -->
				<!-- 				<div style='float:left;margin-top:20px;margin-left:200px;'><strong><a href="http://www.amazon.com/Venture-Collection-Microfinance-Stories-ebook/dp/B009JC6V12/ref=sr_1_13?s=digital-text&amp;ie=UTF8&amp;qid=1349104493&amp;sr=1-13&amp;keywords=microfinance" target="_blank"><?php echo $lang['loginform']['ebooklink']?></a></strong></div> -->
				<div id="login" align="left">
					<form method="post" action="process.php" class="login-form">
						<div align="right">
							<span class="login-label">Login</span>
							<input class="login-field" type="text" name="username" id="username" value="<?php echo $lang['loginform']['username_login']?>" onfocus='loginfocus(this.value)' onblur='loginblur(this.value)'/>
							
							
							<input class="login-field" type="text" id= "textfield" name="textpassword"  value="<?php echo $lang['loginform']['pwd_login']?>" onfocus="pwdFocus()"/><input class="login-field" id="pwdfield" style="display:none" type="password" name="password" value="" onblur="pwdBlur()" />

							<input type="hidden" name="userlogin" />
							<input type="hidden" name="user_guess" value="<?php echo generateToken('userlogin'); ?>"/>
							<button type="submit" class="btn square">Go</button><br/>
							<div><?php echo $form->error("username"); ?> <?php echo $form->error("password"); ?></div>
						</div>
						<p style="text-align:right">
						<?php 	$Lendingcart = $database->getLendingCart(); 
								if(!empty($Lendingcart)) {
						?>
						<a href='index.php?p=75'><img src='images/layout/icons/cart.gif'> Lending Cart</a>
						<?php } ?>
						<input type="checkbox" id="remember" name="remember" /><label for="remember"><?php echo $lang['loginform']['rme'];?></label> &nbsp;|&nbsp; <a style="color:gray" href="index.php?p=56"><?php echo $lang['loginform']['fypassword'];?></a> &nbsp;|&nbsp; <?php echo $lang['loginform']['not_a_member'];?>&nbsp;&nbsp;<a style='color:#FF8B00;font-weight:bold;font-size:14px;' href="index.php?p=1&amp;sel=0"><?php echo $lang['loginform']['join_today'];?></a></p>
					</form>
				</div>
				<script type="text/javascript">
				<!--
					document.getElementById('pwdfield').setAttribute( "autocomplete", "off" );
					document.getElementById('textfield').setAttribute( "autocomplete", "off" );
				//-->
				</script>
	<?php	}
			else
			{ 	?> 
				<!--<div style='float:left;margin-top:20px;margin-left:200px;'><strong><a href="http://www.amazon.com/Venture-Collection-Microfinance-Stories-ebook/dp/B009JC6V12/ref=sr_1_13?s=digital-text&ie=UTF8&qid=1349104493&amp;sr=1-13&amp;keywords=microfinance" target="_blank"><?php echo $lang['loginform']['ebooklink']?></a></strong></div> -->
				<div id="welcome">
					<?php $prurl = getUserProfileUrl($session->userid);?>
					<h4><a style="color:#000000" href="<?php echo $prurl?>">Hi, <?php echo $session->fullname; ?></a></h4>
					<div style="clear:both"></div>
					<div style="margin-top:10px">
				<?php	if($session->userlevel==PARTNER_LEVEL)
						{
							echo "<a href='index.php?p=7'>".$lang['loginform']['pending_app']."</a>";
						}
						else if($session->userlevel==LENDER_LEVEL)
						{
							echo"<a href='index.php?p=75'><img src='images/layout/icons/cart.gif'> Lending Cart</a>";
							echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<a href='index.php?p=19&u=".$session->userid."'>".$lang['loginform']['myportfolio']."</a>";
						}
						else if($session->userlevel==BORROWER_LEVEL)
						{
							$lastLoan=$database->getLastloan($session->userid);
							if(isset($lastLoan['loanid']))
							{	$loanprurl = getLoanprofileUrl($session->userid,$lastLoan['loanid']);
								echo "<a href='$loanprurl'>".$lang['loginform']['my_profile']."</a>";
							}
						}
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;<a href='process.php'><?php echo $lang['loginform']['Logout']?></a>
						
					</div>
				</div>
	<?php	}	?>
			</div>

			<div style="clear:both"></div>
			<div id="logo"><h1><a href="./">Zidisha</a></h1></div>
			<div>
			<?php if(isset($_SESSION['Readonly'])) {
						unset($_SESSION['Readonly']);
						echo "<div style='width:100%; background-color:red;color:white;text-align:center'>This is a read-only account, no changes can be made with this user!</div>";
				}	?>
			<?php if(isset($_SESSION['invalidForm'])) {
						unset($_SESSION['invalidForm']);
						echo "<div style='width:100%; background-color:red;color:white;text-align:center'>CSRF token invalid please try again</div>";
				}	?>
			</div>
			<div id="nav">
				<table class="nav-table">
					<tr>
						<td><a class="<?php if($page==0) echo 'current';?>" href="./"><?php echo $lang['menu']['home'] ?></a></td>
						<td><a class="<?php if($page==2) echo 'current';?>" href="microfinance/lend.html"><?php echo $lang['menu']['Lend'] ?></a></td>
						<td><a class="<?php if($page==47) echo 'current';?>" href="microfinance/borrow.html"><?php echo $lang['menu']['borrow'] ?></a></td>
					<td><a class="<?php if($page==67) echo 'current';?>" href="microfinance/intern.html"><?php echo $lang['menu']['interns'] ?></a></td>
						<td><a class="<?php if($page==48) echo 'current';?>" href="microfinance/why-zidisha.html"><?php echo $lang['menu']['why_zidisha'] ?></a></td>
						<td><a class="<?php if($page==3) echo 'current';?>" href="microfinance/how-it-works.html"><?php echo $lang['menu']['h_it_w'] ?></a></td>
						<td><a class="<?php if($page==4) echo 'current';?>" href="microfinance/faq.html"><?php echo $lang['menu']['FAQ'] ?></a></td>
	
					</tr>
				</table>
			</div>
		
<?php	if($page==0)
		{
			include_once("includes/home_flash.php");
		}
?>
		<div class="row">
	<?php
	/* Please set here for showing login form, stats and our impact */
			$showOutImpact=0;
			if($page==2 || $page==0 || $page==26 || $page==27 || $page==46 || $page==33 || $page==64 || $page==38 || $page==6 || $page==43 || $page==62 || $page==1 || $page==13 || $page==65 || $page==67 || $page==56 || $page==5 || $page==14 || $page==12 || $page==24 || $page==40 || $page==59 || $page==11 || $page==14 || $page==19 || $page==16 || $page==17 || $page==30 || $page==9 || $page==37 || $page==44 || $page==41 || $page==7 || $page==8  || $page==49 || $page==71 || $page==50 || $page==52 || $page==53  || $page==54 || $page==72 || $page==73 || $page==74 || $page==75 || $page==76 || $page==77 || $page==78 || $page==79 || $page==80 || $page==81 || $page==82 || $page==83 || $page==84|| $page==85 || $page==86 || $page==87 || $page==88 || $page==89 || $page==90 || $page==91 || $page==92 || $page==93 || $page==94 || $page==95 || $page==96 || $page==97 || $page==98 || $page==99 || $page==101 || $page==102)
			{
				$showOutImpact=1;
				if(!empty($session->userid) && ($page==14))
					$showOutImpact=0;
			}
			if($page==2 || $page==12 || $page==14 || $page==37 || $page==41 || $page==42 || $page==9 || $page==13 || $page==44 || $page==7 || $page==20 || $page==8 || $page==60 || $page==17 || $page==21 || $page==63 || $page==23 || $page==26 || $page==27 || $page==46 || $page==33 || $page==64 || $page==38 || $page==6 || $page==43 || $page==62 || $page==1 || $page==65 || $page==67 || $page==56 || $page==5 || $page==31 || $page==32 || $page==22 || $page==25 || $page==29 || $page==35 || $page==39 || $page==36 || $page==45 || $page==19 || $page==16 || $page==30 || $page==24 || $page==40 || $page==59 || $page==11  || $page==49 || $page==71 || $page==50 || $page==52 || $page==53  || $page==54 || $page==72 || $page==73 || $page==74 || $page==75 || $page==76 || $page==77  || $page==78 || $page==79 || $page==80 || $page==81 || $page==82 || $page==83 || $page==84 || $page==85|| $page==86 || $page==87 || $page==88|| $page==89 || $page==90 || $page==91 || $page==92 || $page==93 || $page==94 || $page==95 || $page==96 || $page==97 || $page==98 || $page==99 || $page==101 || $page==102 || $page==103 || $page==104 || $page==105 || $page==106 || $page==107 || $page==108 || $page==109 || $page==110 || $page==111 || $page==112 || $page==113)
			{
				echo "<div class='span4'>";
				include_once("includes/loginform.php");
				include_once("includes/stats.php");
				echo "</div>";
			}
	/* Please set above for showing login form, stats and our impact */
			if($page==0)
			{
				include_once("includes/home.php");
			}
			else if($page==1)
			{
				include_once("includes/register.php");
			}
			else if($page==2)
			{
				if(isset($_GET['u']))
				{
					if($_GET['u'] != 0)
					{
						if(!$session->logged_in)
							header("location:index.php?p=1");
					}
				}
				include_once("includes/loaners.php");
			}
			else if($page==3)
			{
				include_once("includes/how-works.php");
			}
			else if($page==4)
			{
				include_once("includes/faqs.php");
			}
			else if($page==5)
			{
				include_once("includes/legal_info.php");
			}
			else if($page==6)
			{
				include_once("includes/contacts.php");
			}
			else if($page==7)
			{
				include_once("includes/inactive-b.php");
			}
			else if($page==8)
			{
				include_once("includes/active-b.php");
			}
			else if($page==9)
			{
				include_once("includes/loanapplic.php");
			}
			else if($page==10)
			{
				include_once("includes/loanstat.php");
			}
			else if($page==11)
			{
				include_once("includes/admin.php");
			}
			else if($page==12)
			{
				include_once("includes/profile.php");
			}
			else if($page==13)
			{
				include_once("includes/editprofile.php");
			}
			else if($page==14)
			{
				include_once("includes/loanstatn.php");
			}
			else if($page==15)
			{
				include_once("includes/loanstatnew.php");
			}
			else if($page==16)
			{
				include_once("includes/payment.php");
			}
			else if($page==17)
			{
				include_once("includes/withdraw.php");
			}
			/* else If($page==18)
			{
				include_once("editables/lender_terms.php");
			} */
			else If($page==19)
			{
				include_once("includes/loan_status.php");
			}
			else If($page==20)
			{
				include_once("includes/mailer.php");
			}
			else If($page==21)
			{
				include_once("includes/registrationfee.php");
			}
			else If($page==22)
			{
				include_once("includes/tranhist.php");
			}
			else If($page==23)
			{
				include_once("includes/pfreportnew.php");
			}
			else If($page==24)
			{
				include_once("includes/translation.php");
			}
			else If($page==25)
			{
				include_once("includes/managetrans.php");
			}
			else If($page==26)
			{
				include_once("includes/giftcard.php");
			}
			else If($page==27)
			{
				include_once("includes/order-tnc.php");
			}
			else If($page==28)
			{
				include_once("includes/showgiftcard.php");
			}
			else If($page==29)
			{
				include_once("includes/showexpiregiftcard.php");
			}
			else If($page==30)
			{
				include_once("includes/invite.php");
			}
			else If($page==28912784)
			{
				include_once("library/paysimple/giftapprove.php");
			}
			else If($page==31)
			{
				include_once("includes/repayreport.php");
			}
			else If($page==32)
			{
				include_once("includes/translabel.php");
			}
			else If($page==33)
			{
				include_once("includes/onlinepayment.php");
			}
			else If($page==34)
			{
				include_once("library/paysimple/getMoney.php");
			}
			else If($page==35)
			{
				include_once("includes/managelang.php");
			}
			else If($page==36)
			{
				include_once("includes/extra.php");
			}
			else If($page==37)
			{
				include_once("includes/repayschedule.php");
			}
			else If($page==38)
			{
				include_once("includes/donation.php");
			}
			else If($page==39)
			{
				include_once("includes/changepassword.php");
			}
			else If($page==40)
			{
				include_once("includes/forgive_complete.php");
			}
		
			elseIf($page==41)
			{
				include_once("includes/reschedule.php");
			}
			else If($page==42)
			{
				include_once("includes/confirm_reschedule.php");
			}
			else If($page==43)
			{
				include_once("includes/statistics.php");
			}
			else If($page==44)
			{
				include_once("includes/editloanapplic.php");
			}
			else If($page==45)
			{
				include_once("includes/rescheduledloans.php");
			}
			else If($page==46)
			{
				include_once("includes/giftpayment.php");
			}
			else If($page==47)
			{
				include_once("includes/borrow.php");
			}
			else If($page==48)
			{
				include_once("includes/why-zidisha.php");
			}
			else If($page==49)
			{
				include_once("includes/referral.php");
			}
			else If($page==50)
			{
				include_once("includes/welcome.php");
			}
			else If($page==51)
			{
				include_once("includes/emailVerify.php");
			}
			else If($page==52)
			{	
				include_once("includes/bidpayment.php");
			}
			else If($page==53)
			{
				include_once("includes/lenderCredit.php");
			}
			else If($page==54)
			{
				include_once("includes/campaign.php");
			}
			else if($page==56)
			{
				include_once("includes/forgetPassword.php");
			}
			else if($page==59)
			{
				include_once("includes/paypaldetails.php");
			}
			else if($page==60)
			{
				include_once("includes/addpayment.php");
			}
			else if($page==61)
			{
				include_once("includes/getCommentUpload.php");
			}
			else if($page==62)
			{
				include_once("includes/about.php");
			}
			else if($page==63)
			{
				include_once("includes/adminMore.php");
			}
			else if($page==64)
			{
				include_once("includes/newsletter.php");
			}
			else if($page==65)
			{
				include_once("includes/news.php");
			}
			else if($page==66)
			{
				include_once("includes/test.php");
			}
			else If($page==67)
			{
				include_once("includes/interns.php");
			}
			else If($page==68)
			{
				include_once("includes/getinvolved.php");
			}
			else If($page==69)
			{
				include_once("includes/testimonials.php");
			}
			else If($page==70)
			{
				include_once("includes/testimonials_borrower.php");
			}
			else If($page==71)
			{
				include_once("includes/repayment_instructions.php");
			}
			else If($page==72)
			{
				include_once("includes/outstanding_reports.php");
			}
			else If($page==73)
			{
				include_once("includes/loan_forgive.php");
			}
			else If($page==74)
			{
				include_once("includes/auto_lending.php");
			}
			else If($page==75)
			{
				include_once("includes/Lendingcart.php");
			}
			else If($page==76)
			{
				include_once("includes/current_credit.php");
			}
			else If($page==77)
			{
				include_once("includes/comments_credit.php");
			}
			else If($page==78)
			{
				include_once("includes/lender_total_impact.php");
			}
			else If($page==79)
			{
				include_once("includes/abt_microfinance.php");
			}
			else If($page==80)
			{
				include_once("includes/lendinggroup.php");
			}
			else If($page==81)
			{
				include_once("includes/startlendinggroup.php");
			}
			else If($page==82)
			{
				include_once("includes/group_profile.php");
			}
			else If($page==83)
			{
				include_once("includes/group_edit.php");
			}
			else If($page==84)
			{
				include_once("includes/community_organizers.php");
			}
			else If($page==85)
			{
				include_once("includes/pending_email_confirm.php");
			}
			else If($page==86)
			{
				include_once("includes/repay_revert.php");
			}
			else If($page==87)
			{
				include_once("includes/brwr_declined.php");
			}
			else If($page==88)
			{
				include_once("includes/borrowerlist.php");
			}
			else If($page==89)
			{
				include_once("includes/borrowerlist_disbursed.php");
			}
			else If($page==90)
			{
				include_once("includes/lenderslist.php");
			}
			else If($page==91)
			{
				include_once("includes/brwr_fbdata.php");
			}
			else If($page==92)
			{
				include_once("includes/fb_terms.php");
			}
			else If($page==93)
			{
				include_once("includes/endorser_reg.php");
			}
			else If($page==94)
			{
				include_once("includes/pending_endorsement.php");
			}
			else If($page==95)
			{
				include_once("includes/endorser.php");
			}
			else If($page==96)
			{
				include_once("includes/binvite.php");
			}
			else If($page==97)
			{
				include_once("includes/brwrinvited_member.php");
			}
			else If($page==98)
			{
				include_once("includes/facebook_info.php");
			}
			else If($page==99)
			{
				include_once("includes/pending_disbursement.php");
			}
			else If($page==100)
			{
				include_once("font.php");
			}
			else If($page==101)
			{
				include_once("includes/adminSetting.php");
			}
			else If($page==102)
			{
				include_once("includes/find_brwr.php");
			}
			else If($page==103)
			{
				include_once("editables/donatebirthday.php");
			}
			else If($page==104)
			{
				include_once("includes/bgroup.php");
			}
			else If($page==105)
			{
				include_once("includes/bgroup_start.php");
			}
			else If($page==106)
			{
				include_once("includes/bgroup_edit.php");
			}
			else If($page==107)
			{
				include_once("includes/bgroup_profile.php");
			}
			else If($page==108)
			{
				include_once("includes/tranhistSummary.php");
			}
			else If($page==109)
			{
				include_once("includes/activation_rate.php");
			}
			else If($page==110)
			{
				include_once("includes/repayment_rate.php");
			}
			else If($page==111)
			{
				include_once("includes/additional_verification.php");
			}
			else If($page==112)
			{
				include_once("includes/invite_report.php");
			}
			else If($page==113)
			{
				include_once("includes/email_invitors.php");
			}


		?>
		</div>
		<!-- Example row of columns -->
		<footer>
			<a href="./"><?php echo $lang['menu']['home'] ?></a> &nbsp;|&nbsp;
			<a href="index.php?p=5"><?php echo $lang['menu']['terms_use'] ?></a> &nbsp;|&nbsp;
			
<a href="microfinance/lend.html"><?php echo $lang['menu']['Lend'] ?></a> &nbsp;|&nbsp;
			<a href="microfinance/borrow.html"><?php echo $lang['menu']['borrow'] ?></a>  &nbsp;|&nbsp;

<a href="https://www.zidisha.org/microfinance/intern.html"><?php echo $lang['menu']['interns'] ?></a>  &nbsp;|&nbsp;

<a href="https://www.zidisha.org/index.php?p=80">Lending Groups</a> &nbsp;<span>|</span>&nbsp;

<a href="microfinance/press.html"><?php echo $lang['menu']['zidisha_in_news'] ?></a> &nbsp;<span>|</span>&nbsp;

<a href="http://p2p-microlending-blog.zidisha.org/" target="_blank"><?php echo $lang['menu']['blog'] ?></a> &nbsp;<span>|</span>&nbsp;

<a href="http://www.amazon.com/Venture-Collection-Microfinance-Stories-ebook/dp/B009JC6V12" target="_blank"><?php echo $lang['menu']['ebook'] ?></a> &nbsp;<span>|</span>&nbsp;

<a href="https://www.zidisha.org/forum/"><?php echo $lang['menu']['user_forum'] ?></a> &nbsp;<span>|</span>&nbsp; 

<a href="microfinance/newsletter.html"><?php echo $lang['menu']['newsletter'] ?></a> &nbsp;<span>|</span>&nbsp;

					<a href="microfinance/gift-cards.html"><?php echo $lang['menu']['gift_cards'] ?></a> &nbsp;<span>|</span>&nbsp; 
					 

<a href="microfinance/donate.html"><?php echo $lang['menu']['donate'] ?></a> &nbsp;<span>|</span>&nbsp; 

<a href="microfinance/contact.html"><?php echo $lang['menu']['contact_us'] ?></a>


<!--



<a href="microfinance/microfinance.html"><?php echo $lang['menu']['abt_microfinance'] ?></a> &nbsp;<span>|</span>&nbsp;

-->


			<p>
				<em>
					Zidisha Microfinance is the first peer-to-peer microlending service to offer direct interaction between lenders and borrowers across international borders. We are a United States 501(c)(3) nonprofit organization.
				</em><br/><br/>
				<em>&copy; 2009 - 2014 Zidisha Inc.</em>
			</p>
		</footer>

		<div style="clear:both"></div>
	</div> <!-- /container -->
	<!-- 30-10-2012 Anupam, Google Code for Remarketing tag -->
	<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
	<script type="text/javascript">
	/* <![CDATA[ */
		var google_conversion_id = 1005464495;
		var google_conversion_label = "KMo7CNGm6gMQr9e43wM";
		var google_custom_params = window.google_tag_params;
		var google_remarketing_only = true;
	/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
		<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1005464495/?value=0&amp;label=KMo7CNGm6gMQr9e43wM&amp;guid=ON&amp;script=0"/>
		</div>
	</noscript>
</body>
</html>
