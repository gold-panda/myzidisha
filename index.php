<?php
ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
	global $database,$session;
	require_once("library/session.php");
	require_once("library/constant.php");
	
	//Anupam 22-11-201 redirect https://www.zidisha.org/index.php to https://www.zidisha.org/
	if ($_SERVER['REQUEST_URI']=='/index.php')
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".SITE_URL);
	}

	RedirectLoanprofileurl();
	RedirectUserprofileurl();
	$language = '';
	if(isset($_GET["language"])) {
		$language = $_GET["language"];
	}
	$language1="English";
	if($language!='')
		$language1= $database->getLanguageByCode($language);

	// Grabs translated strings for menu.
	include_once("./editables/menu.php");
	$path=	getEditablePath('menu.php');
	include_once("./editables/".$path);
	
	$page=0;
	if(isset($_GET["p"])){
		$page=$_GET["p"];
	}

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
?>

<?php
	// Rewrite so word for microfinance is included in URL. Should have been done with htaccess. TODO. 
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

	// Get language setting by IP address
	$langfrmIP='';
  if($language==''){
    if(!isset($_SESSION['CodeByIp'])) {
      $country = getCountryCodeByIP();
    }    

    if(isset($country['code']) && $country['code']!='') {
		  $_SESSION['CodeByIp'] = $country['code'];
		  if($country['code']=='SN' || $country['code']=='BF' || $country['code']=='BJ' || $country['code']=='GN' || $country['code']=='HT' || $country['code']=='NE' || $country['code'] == 'FR' ) {
		  	$langfrmIP ='fr';
		 	}
		 	else if($country['code']=='ID') {
		    $langfrmIP ='in';
		 	}
		}
	}
?>

<?php
	// Load Smarty
	require_once('extlibs/smarty/Zidisha.php'); 

	// If it's a new style page, use this:
	$smarty->assign('body_class', 'default');

	if ($page == 0) {
		$smarty->display('home.tpl');
	} elseif ($page == 3) {
		$smarty->assign('body_class', 'how-it-works');
		$smarty->display('how-works.tpl');
	//} elseif ($page==4  ){
	//	$lang = $session->getTranslatedLabels("faqs");
	//	$smarty->assign("lang", $lang);
	//	$smarty->assign('body_class', 'faqs');
	//	$smarty->display('faqs.tpl');
	} 
	elseif ($page == 5) {
		$smarty->assign('body_class', 'terms-of-use');
		$smarty->display('terms_of_use.tpl');
	} elseif ($page == 6) {
		$smarty->assign('body_class', 'contact');	
		$smarty->display('contact.tpl');
	} elseif ($page == 48) {
		$smarty->assign('body_class', 'why-zidisha');
		$smarty->display('why_zidisha.tpl');
	} elseif ($page == 67) {
		$smarty->assign('body_class', 'interns');
		$smarty->display('interns.tpl');
	}

	// TODO - DESIGN PENDING
	// page=2 / loaners
	// page=14  / loaner profile page



	// if new style page stop otherwise load old page includes
	if ($smarty->get_template_vars('body_class')) {
		return;
	} else {
		include("includes/_oldheader.php");
		include("includes/_statslogic.php");
	}


	if($page==1)
	{
		// Jordan: if we want to develop a new functionality, it will be better to use copy of original files.
		include_once("includes/register_redesign_develop.php");
	}

	// Search Results Pages
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

	else if($page==4)
	{
		include_once("includes/faqs.php");
	}
	else if($page==7)
	{
		include_once("includes/inactive-b.php");
	}
	else if($page==9)
	{
		include_once("includes/loanapplic.php");
	}
	else if($page==11)
	{
		include_once("includes/admin.php");
	}
	//redirects to borrower, lender or partner profile
	else if($page==12)
	{
		include_once("includes/profile.php");
	}
	else if($page==13)
	{
		include_once("includes/editprofile.php");
	}
	else if($page==14)
	{ // Loaner profles
		include_once("includes/loanstatn.php");
	}
	else if($page==16)
	{
		include_once("includes/payment.php");
	}
	else if($page==17)
	{
		include_once("includes/withdraw.php");
	}
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
	else If($page==41)
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
	else If($page==114)
	{
		include_once("includes/loans_funded.php");
	}
	else If($page==115)
	{
		include_once("includes/manage_language.php");
	}
	else if($page==116)
	{
		require_once("includes/fb_login.php");
	}
	else if($page==117)
	{
		require_once("includes/invite_report_lenders.php");
	}
	else if($page==118)
	{
		require_once("includes/email_invitors_lenders.php");
	}
	else if($page==119)
	{
		require_once("includes/l_comments.php");
	}
	else if($page==120)
	{
		require_once("includes/lender_invited_member.php");
	}
	else if($page==121)
	{
		require_once("includes/lender_gift_cards.php");
	}

	// LOAD THE OLD FOOTER
	include("includes/_oldfooter.php");
?>