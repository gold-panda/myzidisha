<?php
include_once("library/session.php");
include_once("./editables/loanstatn.php");
$path=	getEditablePath('loanstatn.php');
date_default_timezone_set ('EST');
include_once("editables/".$path);
$RequestUrl = $_SERVER['REQUEST_URI'];


//URL rewriting for SEO
$parsedurl = parse_url($RequestUrl);
$exURL = explode('/',$parsedurl['path']);
$microfinanceexist = in_array('microfinance',$exURL);
$loanexist = in_array('loan',$exURL);
if($loanexist && $microfinanceexist) {
	$usernameexist = explode('.',end($exURL));
	if(end($usernameexist)=='html') {
		$arrlen = count($exURL);
		$unameinurl = $exURL[$arrlen-2];
		$unameinurl = str_replace('-',' ',$unameinurl);
		$_GET['u'] = $database->getUserId($unameinurl);
		$loanidinurl = substr(end($exURL), 0, -5);
		$_GET['l'] = $loanidinurl;
	}
} 


$ld =0;
if(isset($_GET['l']))
{
	$ld=$_GET['l']; //gets the profile for the loan ID specified in the URL
	if(isset($_GET['u']) && empty($ld)) //if a borrower views their own loan profile page while logged in
	{
		$lastLoan=$database->getLastloan($_GET['u']); //get the current or most recent loan for the logged in borrower
		if(!empty($lastLoan))
			$ld=$_GET['l']=$lastLoan['loanid'];
	}
}
else
{

	echo $lang['loanstatn']['loan_p_not_gen']; //loan ID in URL does not exist
	exit();
}
?>


<link href="css/default/popup_style.css?q=<?php echo RANDOM_NUMBER ?>" rel="stylesheet">
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>


<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" src="includes/scripts/submain.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" src="includes/scripts/eepztooltip.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("#stay-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
		$("#intr-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
		$("#intr1-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
		$("#intr2-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});

			
	});
</script>


<?php
//social share modal box that displays upon completion of a loan bid
$showShareBox=0;
$userid = $session->userid;
$sharebox_preference = $database->sharebox_preference($userid); //check if lender has selected not to display the sharebox anymore

if((isset($_SESSION['lender_bid_success1']) || isset($_SESSION['lender_bid_success2']) || isset($_SESSION['shareEmailValidate'])) && $sharebox_preference != 1) {
	$showShareBox=1;
	if(isset($_SESSION['shareEmailValidate'])) {
		$formbidpos=$_SESSION['shareEmailValidate'];
	}	
	if(isset($_SESSION['lender_bid_success1']))
		$showShareBox=1;
	elseif(isset($_SESSION['lender_bid_success2']))
		$showShareBox=2;
	else
		$showShareBox=$formbidpos;
} ?>


<script type="text/javascript">
	$(document).ready(function() {
	$('#busi_desc_org').click(function() {
			$('p#busi_desc_org_desc').slideToggle("slow");
			var txt = $(this).text();
			if(txt == "<?php echo $lang['loanstatn']['disp_text']; ?>")
				$(this).text("<?php echo $lang['loanstatn']['hide_text']; ?>");
			else
				$(this).text("<?php echo $lang['loanstatn']['disp_text']; ?>");
	});
	$('#loan_use_org').click(function() {
			$('#loan_use_org_desc').slideToggle("slow");
			var txt = $(this).text();
			if(txt == "<?php echo $lang['loanstatn']['disp_text']; ?>")
				$(this).text("<?php echo $lang['loanstatn']['hide_text']; ?>");
			else
				$(this).text("<?php echo $lang['loanstatn']['disp_text']; ?>");
	});
	$('#about_org').click(function() {
			$('#about_org_desc').slideToggle("slow");
			var txt = $(this).text();
			if(txt == "<?php echo $lang['loanstatn']['disp_text']; ?>")
				$(this).text("<?php echo $lang['loanstatn']['hide_text']; ?>");
			else
				$(this).text("<?php echo $lang['loanstatn']['disp_text']; ?>");
	});
	$('#funding_bids').click(function() {
		$('#funding_bids_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less");
	});
	$('#repay_sched').click(function() {
		$('#repay_sched_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less");
	});
	$('#lend_funding').click(function() {
		$('#lend_funding_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less");
	});
	$('#lend_feedback').click(function() {
		$('#lend_feedback_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less");
	});
	$('#viewprevloan').click(function() {
		$('#viewprevloan_desc').slideToggle("slow");
	});
	$('#viewassignedmember').click(function() {
		$('#viewassignedmember_desc').slideToggle("slow");
	});
	<?php if($showShareBox != 0) { ?>
		jQuery.facebox({ div: '#shareForm' });
	<?php } ?>
});

function showBox(box)
{
	if(box==1) {
		$('.shareTab1').addClass('tab2');
		$('.shareTab2').removeClass('tab');
		$('.shareTab3').removeClass('tab');
		$('.shareTab1Detail').show();
		$('.shareTab2Detail').hide();
		$('.shareTab3Detail').hide();
		$('.mail_sent_section').hide();
	}
	else if(box==2) {
		$('.shareTab2').addClass('tab');
		$('.shareTab3').removeClass('tab');
		$('.shareTab1').removeClass('tab2');
		$('.shareTab2Detail').show();
		$('.shareTab3Detail').hide();
		$('.shareTab1Detail').hide();
		$('.mail_sent_section').hide();
	}
	else {
		$('.shareTab3').addClass('tab');
		$('.shareTab1').removeClass('tab2');
		$('.shareTab2').removeClass('tab');
		$('.shareTab3Detail').show();
		$('.shareTab1Detail').hide();
		$('.shareTab2Detail').hide();
		$('.mail_sent_section').hide();
	}
	
}
</script>
<script type="text/javascript">
	$(function() {
		$(".tablesorter_funding").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
		$(".tablesorter_bidding").tablesorter({sortList:[[0,1]], widgets: ['zebra']});
	});
</script>
<body onload="submit_form(document.bidform1)">

<div class="span16" style="position:relative;">


<?php
//lender loan forgiveness opt-in or opt-out 
$loanid=$_GET['l'];
$ud=$_GET['u'];
if(isset($_GET['v'])){
	$v=$_GET['v'];
	$vd=$database->checkValidationCode($loanid, $ud);
	if($vd===$v){
		if(isset($_GET['lid'])&& !isset($_GET['dntfrg'])){ 
			$res=$session->forgiveDenied($loanid, $_GET['lid']);
		}else if(isset($_GET['dntfrg'])) {
				?>
				<a href="<?php echo $RequestUrl?>#donotforgive" rel='facebox' id='forgive_no'></a>
				<script type="text/javascript">
					$(document).ready(function() {
						$('#forgive_no').trigger('click');
					});
				</script>
		<?php }
		else{	
	?>			<a href="includes/forgive.php?loanid=<?php echo "$loanid&ud=$ud"?>" rel='facebox' id='forgive_yes'></a>
				<script type="text/javascript">
					$(document).ready(function() {
						$('#forgive_yes').trigger('click');
					});
				</script>
	
<?php
			}

		}
} ?>

<script type="text/javascript">
<!--
	function submit_form(form)
	{	
	<?php 
		if(isset($_SESSION['bidPaymentSuccess']))
		{
			 if(!empty($pamount1)) {?>
				document.bidform1.submit();
			<?php 
			 }else 
			{ ?>
				document.bidform.submit();
		<?php 
			} 
	}
?>
}
//-->

</script>
<?php unset($_SESSION['bidPaymentSuccess']);?>
<script type="text/javascript">
<!--
	function forgive_popup(lid, ud) {
	var newWin= window.open('includes/forgive.php?loanid='+lid+'&ud='+ud+'','forgivewindow','width=400,height=200,left=200,top=200,screenX=100,screenY=100,scrollbars=yes');
		if(newWin == null || typeof(newWin)=="undefined" || !newWin) {
			$.facebox({ div: '#pop-upblocked' });
		} else {
			newWin.onload = function() {
				setTimeout(function() {
					if (newWin.screenX === 0)
						$.facebox({ div: '#pop-upblocked' });
				}, 0);
			};
		}
	}
//-->

</script>
<div class="" id="pop-upblocked" style="background-color:#E3E5EA;display:none">
	<div class='autolend_space'>
	<div></div>
		<div class='auto_lend_text'>
			<?php echo $lang['loanstatn']['pop-upblocked_text'];?>
		</div><br/>
		<div align="right" style="padding-right:40px">
		</div>
	</div>
</div>
<script language="JavaScript">
  var ids = new Array('feedback', 'pcomment', 'txtcomment');
  var values = new Array('','', '');
  var needToConfirm = true;

</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>

<?php

if(isset($_GET['fg']) && $_GET['fg']=='yes' && !empty($session->userid)){
	$session->forgiveShare($loanid, $ud, $session->userid);
}
if(isset($_SESSION['forgive']))
{	
	$forgive=$_SESSION['forgive'];
	unset($_SESSION['forgive']);
	if($forgive==1)
	{
		echo "<div align='center'><font color=green><b>You have successfully forgiven this loan. </b></font></div>";
	}
	else if($forgive==2)
	{
		echo "<div align='center'><font color=red><b></b></font></div>";
	}
	else if($forgive==3)
	{
		echo "<div align='center'><font color=red><b>You have already forgiven this loan.</b></font></div>";
	}
	else if($forgive==8)
	{
		echo "<div align='center'><font color=red><b>Please log in to continue.</b></font></div>";
	}
	else if($forgive==9)
	{ 	
		echo "<div align='center'><font color=green><strong>Thank you for your response. You will no longer receive invitations to forgive this loan.</strong></font></div><br/>";
	}
	else
	{
		echo "<div align='center'><font color=red><b></b></font></div>";
	}

	echo "<br/>";
}
if(isset($_SESSION['loan_denied'])){
	echo "<div align='center'><font color=green><strong>Thank you for your response. You will no longer receive invitations to forgive this loan.</strong></font></div><br/>";
	unset($_SESSION['loan_denied']);
}


$brw2 = $database->getLoanDetails($ld); //gets database row containing details on this loan ID
if(empty($brw2))
{
	echo "";
}
else
{
	$ud=0;
	if(isset($_GET['u']))
	{
		$ud=$_GET['u'];
		if($ud != $brw2['borrowerid'])
			$ud=$brw2['borrowerid'];
	}
	else
	{
		$ud = $brw2['borrowerid'];
	}


	//currency exchange rates to use in dollar display of loan amounts
	$CurrencyRate = $database->getCurrentRate($ud); //today's Fx rate
	$disburseRate = $database->getExRateByLoanId($ld); // Fx rate as of date this loan was disbursed
	if(empty($disburseRate))
		$disburseRate = $CurrencyRate;

	$tmpcurr="USD"; //default display currency is USD
	$show_localcurrency=0; //default not to display local currency amounts
	if($ud==$session->userid) //if logged in user is borrower viewing their own loan profile
	{
		$show_localcurrency=1; //display amounts in local currency
		$tmpcurr = $database->getUserCurrency($ud);
	}


	//borrower data set
	$brw=$database->getBorrowerDetails($ud); //gets database rows with details pertaining to the borrower
	$prurl = getUserProfileUrl($ud);
	$name=$brw['FirstName'].' '.$brw['LastName'];
	$location=$brw['City'].', '.$database->mysetCountry($brw['Country']);
	$imagesrc=$database->getProfileImage($ud);
	//$imagesrc = "https://www.zidisha.org/library/getimagenew.php?id=9209"; //for testing only
	$fb_data = unserialize(base64_decode($brw['fb_data'])); //Facebook data of this borrower
	$is_volunteer= $database->isBorrowerAlreadyAccess($ud); //checks if this borrower is a Volunteer Mentor


	//data for member who invited this borrower if any
	$invitor= $database->getInvitee($ud);
	$invcurrentloanid= $database->getCurrentLoanid($invitor);
	if(!empty($invcurrentloanid)){
		$invitorname= $database->getNameById($invitor);
		$invitorurl= getLoanprofileUrl($invitor);
		$invitedby= "<a href='$invitorurl'>".$invitorname."</a>";
		$inv_repayrate = $session->repayRateDisplay($invitor);
	}


	//data for volunteer mentor assigned to this borrower if any
	$mentor_id=$brw['mentor_id']; //the volunteer mentor assigned to this borrower
	$mentor_currentloanid= $database->getCurrentLoanid($mentor_id);
	if(!empty($mentor_currentloanid)){
		$mentor_name= $database->getNameById($mentor_id);
		$mentor_url= getLoanprofileUrl($mentor_id);
		$mentor= "<a href='$mentor_url'>".$mentor_name."</a>";
		$mentor_repayrate = $session->repayRateDisplay($mentor_id);
	}


	//endorsements
	$candisplay= $database->canDisplayEndorser($ud); //whether we have permission to display endorsements for this borrower
	if(!empty($candisplay)){ 
		$endorsement="<a href='".$prurl."?>?fdb=3'>".$lang['loanstatn']['view_endorse']."</a>";
	} 


	//on-time repayment score
	$repayrate_disp = $session->repayRateDisplay($ud);

	//feedback score
	$report=$database->loanReport($ud);
	$feedback=$database->getPartnerComment($ud); //set of feedback left by lenders
	$f=$report['feedback'];
	if ($f==''){
		$f=0;
	}
	$cf=$report['Totalfeedback'];


	//code for showing label to view previous loans if any
	$viewprevloan='';
	$allloans= $database->getBorrowerRepaidLoans($ud);
	if(isset($allloans[0]['loancount'])){
		if($allloans[0]['loancount'] >0){
			$viewprevloan=$lang['loanstatn']['viewprevloan'];
		}
		if($allloans[0]['loancount']==1 && $allloans[0]['loanid']==$ld){
			$viewprevloan='';
		}
	}


	//if translation has been posted for profile content, display translation instead of original
	if($brw['tr_BizDesc']==null || $brw['tr_BizDesc']=="")
		$biz=$brw['BizDesc'];
	else
		$biz=$brw['tr_BizDesc'];
	if($brw['tr_About']==null || $brw['tr_About']=="")
		$about=$brw['About'];
	else
		$about=$brw['tr_About'];


	//fundraising loan application expiration date
	$bot = '';
	if($brw2['active']==LOAN_OPEN)
		$bot = date('F d, Y',$brw2['applydate'] + ($database->getAdminSetting('deadline') * 24 * 60 * 60 ));
	else
		$bot = 'Closed';


	//fundraising application amount requested and raised
	$totBid=$database->getTotalBid($ud,$ld);
	if($brw2['reqdamt'] > $totBid)
		$stilneed=$brw2['reqdamt']-$totBid;
	else
		$stilneed=0;


	//set of data for this loan
	$loanid=$brw2['loanid']; //current loan ID
	$disburseDate=$database->getLoanDisburseDate($loanid);
	$webfee=$brw2['WebFee'];//service fee rate

	if($brw2['tr_loanuse']==null || $brw2['tr_loanuse']=="")
		$loanuse=$brw2['loanuse'];
	else
		$loanuse=$brw2['tr_loanuse'];

	if(!empty($brw2['summary'])){
		if($brw2['tr_summary']==null || $brw2['tr_summary']=="")
			$summary=$brw2['summary'];
		else
			$summary=$brw2['tr_summary'];

		if(strlen($summary) >70){
			$summary=substr($summary, 0, strpos($summary, ' ', 70))."...";
	    }
	}else{
		$summary=substr($loanuse, 0, strpos($loanuse, ' ', 70))."...";
	}

	$weekly_inst=$brw2['weekly_inst']; //if set to 1, installments are due weekly, otherwise monthly
	$interest=$brw2['interest'] - $webfee;
	$interest1=number_format($interest, 2, ".", ",");
	$extraPeriod=$database->getLoanExtraPeriod($ud, $loanid);
	$period=$brw2['period'];
	$newperiod=$period+$extraPeriod;
	$gperiod=$grace=$brw2['grace'];
	$lamount=convertToNative($brw2['reqdamt'], $CurrencyRate);
	$damount= $brw2['reqdamt'];
	$damountX= number_format($damount, 2, ".", ",");

	if($weekly_inst == 1) {
		$conversion=5200;
		if($gperiod <2)
			$gperiodText=$lang['loanstatn']['week'];
		else
			$gperiodText=$lang['loanstatn']['weeks'];
		if($period <2)
			$periodText=$lang['loanstatn']['week'];
		else
			$periodText=$lang['loanstatn']['weeks'];
	} else {
		$conversion=1200;
		if($gperiod <2)
			$gperiodText=$lang['loanstatn']['month'];
		else
			$gperiodText=$lang['loanstatn']['months'];
		if($period <2)
			$periodText=$lang['loanstatn']['month'];
		else
			$periodText=$lang['loanstatn']['months'];
	}

	$bids=$database->getLoanBids($ud, $ld);
	$totBid=$database->getTotalBid($ud,$ld);
	$interestrate = $database->getAvgBidInterest($ud, $ld);
	$totToPayBack = 0;
	$totFee = 0;
	if($brw2['active']==LOAN_OPEN || $brw2['active']==LOAN_FUNDED || $brw2['active']==LOAN_EXPIRED || $brw2['active']==LOAN_CANCELED)
	{	$totToPayBack = $lamount +($lamount * ($newperiod)* ($interest + $webfee))/$conversion;
		$totFee = $interest + $webfee ;
		$maxInterestRate=$interest1;
		if($totBid >= $brw2['reqdamt'])
		{
			$maxInterestRate=$interestrate;
			$totFee = $interestrate + $webfee ;
			$totToPayBack = $lamount +($lamount * ($newperiod)* ($interestrate + $webfee))/$conversion;
		}

	}
	else
	{
		$totToPayBack = $brw2['AmountGot'] +($brw2['AmountGot'] * $newperiod * ($interestrate + $webfee))/$conversion;
		$totFee = $interestrate + $webfee ;

	}

	$bfrstloan=$database->getBorrowerFirstLoan($ud); //include new borrower registration fee if this is the first loan raised
	if(!$bfrstloan)
	{
		 $currency_amt=$database->getReg_CurrencyAmount($ud);
		 foreach($currency_amt as $row)
		 {
			$currency=$row['currency'];
			$amount=$row['Amount'];
			$amtinusd=convertToDollar($amount,$CurrencyRate);
			$b_reg_fee=number_format($amtinusd,2);
			$b_reg_fee_native=number_format($amount,0);
		 }
	}
	else
	{
		$amtinusd = 0;
	}
	$totToPayBackinUSD = convertToDollar($totToPayBack, $CurrencyRate);
	$totToPayBackWithRegFee = $totToPayBackinUSD + $amtinusd;
	$showLoanDetail=0;
	if($brw2['active'] == LOAN_ACTIVE || $brw2['active']==LOAN_DEFAULTED || $brw2['active']==LOAN_REPAID)
	{
		$showLoanDetail=2; //display information for a disbursed loan
	}
	else if($brw2['active'] == LOAN_OPEN || $brw2['active']==LOAN_FUNDED)
	{
		$showLoanDetail=1; //display information for a loan not yet disbursed
	}
	$feeamount=((($newperiod)*$brw2['AmountGot']*($webfee))/$conversion);
	$feelender=((($newperiod)*$brw2['AmountGot']*($brw2['finalrate']))/$conversion);

	$pamount1=$form->value('pamount1');
	$pinterest1=$form->value('pinterest1');
	$pamount=$form->value('pamount');
	$pinterest=$form->value('pinterest');

	if(isset($_SESSION['bidPaymentSuccess']))
	{	
		$bidData= $database->getBidDetail($_SESSION['bidPaymentId'], $session->userid);
		if($bidData['bidup']) {
			$pamount1=$bidData['bidamt'];
			$pinterest1=$bidData['bidint'];
		}
		else{
			$pamount=$bidData['bidamt'];
			$pinterest=$bidData['bidint'];
		}
		unset($_SESSION['bidPaymentId']);
	}

	//credits the user who posted translations
	$translate_user_id= $database->getTranslateUser($loanid);
	$translate_user_name= $database->getUserNameById($translate_user_id);
	$translator_level= $database->getUserLevelbyid($translate_user_id);
	$translator_loanid= $database->getCurrentLoanid($translate_user_id);
	if($translator_level==BORROWER_LEVEL && !empty($translator_loanid)){
		$translator_url= getLoanprofileUrl($translate_user_id,$translator_loanid);
	}else{
		$translator_url = getUserProfileUrl($translate_user_id);
	}
	?>

	
<div class="span16">
	<div id="static">
		<!-- removing summary title for now
		<h1><?php echo $summary; ?></h1>
		-->
		<?php echo "<br/>" ?>
	</div>
</div>
	

	<div class="span10 left column">
		<!-- profile image -->
		<img class="span10" style="border:none;" src="<?php echo $imagesrc ?>" alt="<?php echo $name ?>" />
	

		<!-- My Story -->
		<div class="row">
			<div class="span10">
				<h3 class="subhead"><?php echo $lang['loanstatn']['b_story']; ?></h3>
				<p style="text-align:justify;"><?php echo nl2br($about) ?></p>
		<?php	if(!empty($session->userid))
				{
					if(!empty($brw['tr_About'])){
						$translation='Edit translation';
					}else{
						$translation='Add translation';
					}
					echo "<p align='right'><a href='index.php?p=24&id=".$ud."&l_id=".$ld."&ref=1'>".$translation."</a></p>";
				}
				if($about == $brw['tr_About'])
				{
					echo "<p align='right'><a id='about_org' href='javascript:void(0)'>".$lang['loanstatn']['disp_text']."</a></p>";
					echo "<p id='about_org_desc' style='display:none;text-align:justify;'>".nl2br($brw['About'])."</p>";
				}

		?>

		<!-- About My Business -->
		<h3 class="subhead"><?php echo $lang['loanstatn']['b_business'] ?></h3>
		<p style="text-align:justify;"><?php echo nl2br($biz) ?></p>
<?php	if(!empty($session->userid)){
			if(!empty($brw['tr_BizDesc'])){
				$translation='Edit translation';
			}else{
				$translation='Add translation';
			}
			echo "<p align='right'><a href='index.php?p=24&id=".$ud."&l_id=".$ld."&ref=1'>".$translation."</a></p>";
		}
		if($biz == $brw['tr_BizDesc'])
		{
			echo "<p align='right'><a id='busi_desc_org' href='javascript:void(0)'>".$lang['loanstatn']['disp_text']."</a></p>";
			echo "<p id='busi_desc_org_desc' style='display:none;text-align:justify;'>".nl2br($brw['BizDesc'])."</p>";
		}
?>
		
		<!-- use of loan -->
		<h3 class="subhead"><?php echo $lang['loanstatn']['b_about_loan'] ?></h3>
	
		<tr>
			<td colspan=2 style="text-align:justify;line-height:18px">
				<?php echo $loanuse ?>
			</td>
		</tr>
			<tr>
				<td colspan=2>
						<?php	if(!empty($session->userid)){
									if(!empty($brw2['tr_loanuse'])){
										$translation='Edit translation';
									}else{
										$translation='Add translation';
									}
									echo "<p align='right'><a href='index.php?p=24&id=".$ud."&l_id=".$ld."&ref=1'>".$translation."</a></p>";
								}

								//credits member who translated this profile
								if($about == $brw['tr_About'] || $biz == $brw['tr_BizDesc']|| $loanuse == $brw2['tr_loanuse'])
								{
									if(empty($translate_user_name)){
										echo "<p align='right'><a id='loan_use_org' href='javascript:void(0)'>".$lang['loanstatn']['disp_text']."</a></p>";
									}else{
										echo "<p align='right'><i>".$lang['loanstatn']['translate_by']." <a href='$translator_url' style='font-style: italic;'>".$translate_user_name."</a></i>&nbsp&nbsp&nbsp&nbsp&nbsp<a id='loan_use_org' href='javascript:void(0)'>".$lang['loanstatn']['disp_text']."</a></p>";
									}
									echo "<p id='loan_use_org_desc' style='display:none;text-align:justify;'>".$brw2['loanuse']."</p>";
								}
						?>


						<!-- report violation -->
						<?php
						echo "<br/><br/><br/>";
						echo "<p align='right'><a href='mailto:service@zidisha.org?subject=Report Profile: ".$name."'>".$lang['loanstatn']['report_violation']."</a></p>";
						?>
				</td>
			</tr>

			</div>
		</div><!-- /row -->

<?php
if($brw2['active']==LOAN_OPEN )
{	?>
	<div class="row">
		
			<div class="bid-table" id="retval">
				

		<!-- Borrower funded loan bid acceptance section starts -->

		<?php	if(($session->userlevel == BORROWER_LEVEL) && ($show_localcurrency) && !empty($bids))
				{
					$p = $database->getTotalBid($ud,$ld) / $damount;
					if($p >= 1 && $brw2['active'] < LOAN_FUNDED)
					{
						$loneAcceptDate=time();
						$sched=$session->getSchedule($lamount, $interestrate + $webfee, $period, $gperiod,$loneAcceptDate,$webfee, $weekly_inst);
						$currency=$database->getUserCurrency($ud);

						if ($country=='BF' || $country=='GN'){

							$bid_note = $lang['loanstatn']['acceptBid_note_BF'];

						} elseif ($country=='ID'){

							$bid_note = $lang['loanstatn']['acceptBid_note_ID'];

						} else {

							$bid_note = $lang['loanstatn']['acceptBid_note'];

						}

						?>
						
						<br/><br/>

						<?php echo "<a name='acceptbids' id='acceptbids'></a>"; ?>

						<br/><br/>
						
						<strong><?php echo $lang['loanstatn']['accept_instructions'];?></strong>

						<br/><br/><br/>

						

						<table class="detail_new" cellspacing="0" cellpadding="8" >
							<tbody>
				
							<tr>
							
								<td style="width:250px"><strong><?php echo $lang['loanstatn']['requested'] ?>:</strong></td>
						
								<td>
								
								<?php echo $tmpcurr." ".number_format(round_local($brw2['AmountGot']),0,'.',',');

								?>
								</td>
				
							</tr>

							<tr>
								
								<td><strong><?php echo $lang['loanstatn']['pd'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_pd'] ?></span><span class='bottom'></span></span></a></strong></td>
					
								<td><?php echo $period ?> <?php echo $periodText ?></td>
							
							</tr>
				
							<tr>
					
								<td><strong><?php echo $lang['loanstatn']['final_intr_rate'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['final_intr_rate_tooltip']?></span><span class='bottom'></span></span></a></strong></td>
					
								<td><?php echo number_format($maxInterestRate, 2, '.', ',') ?>%</td>
							
							</tr>
							
							<tr>
								
								<td><strong><?php echo $lang['loanstatn']['webfee'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_atf']?></span><span class='bottom'></span></span></a></strong></td>
					
								<td><?php echo number_format($webfee, 2, '.', ','); ?>%</td>
							
							</tr>
				
							<?php if(!$bfrstloan){	?>
				
							<tr>
								
								<td><strong><?php echo $lang['loanstatn']['b_reg_fee'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_webfee']?></span><span class='bottom'></span></span></a></strong></td>
					
								<td>
				
								<?php echo $tmpcurr." ".$b_reg_fee_native; ?>
					
								</td>
				
							</tr>
							
							<?php }	?>

							<tr>
							
								<td><strong><?php echo $lang['loanstatn']['tot_int_fee'] ?>:</strong></td>
					
								<td>
				
								<?php echo $tmpcurr." ".number_format(round_local($totToPayBack-($brw2['AmountGot'])), 0)." (". number_format( $totFee , 2, '.', ',')."% annual rate for ".$period." ".$periodText.")";

								?>
					
								</td>
							
							</tr>

							<tr>
							
								<td><strong><?php echo $lang['loanstatn']['tba'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_tba']?></span><span class='bottom'></span></span></a></strong></td>
					
								<td>
				
								<?php echo $tmpcurr." ".number_format(round_local($totToPayBack), 0);

								?>
					
								</td>
							
							</tr>
				
				
						</tbody>
					</table>

					<br/><br/><br/>

					<div align="center"><strong><?php echo $lang['loanstatn']['repayment_sched']; ?></strong></div>

					<br/><br/>
		
					<?php echo $sched; ?>

					
					<br/><br/><br/>


						<table>
							<form method='post' action='updateprocess.php'>
							<tr>
								<td><?php echo $bid_note; ?></td>
								<td width='100px;'><textarea rows="5" cols="60" id="acceptbid_note" name="acceptbid_note"><?php echo $form->value('acceptbid_note'); ?></textarea><br/><?php echo $form->error('acceptBid_note'); ?></td>
							</tr>
						
							<tr>
								<td height='20px;'></td><td></td>
							</tr>

							<tr><td></td><td align='center'>
								<input type='hidden' name='acceptbids' />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('acceptbids'); ?>"/>
								<input type='hidden' name='loanid' value="<?php echo $ld ?>"/>
								<input style='float:center' class='btn' type='submit' value="<?php echo $lang['loanstatn']['accept_bids'] ?>" />
							</td></tr>
						</form></table>
						
			<?php	} 

			// Borrower funded loan bid acceptance section ends 


					else
					{?>
						<table><tr><td style="text-align:right"><input type='submit' value="<?php echo $lang['loanstatn']['accept_bids'] ?>" disabled='disabled'/></td></tr></table>
			<?php	}
				}
				else if(empty($bids))
				{

					echo "<p align='left'>".$lang['loanstatn']['nobid']."</p>";
				}



				?>
			</div><!-- /bid-table -->
		
	</div><!-- /row -->
<?php
}

//feedback for ended loans
if($brw2['active'] == LOAN_REPAID)
{
	//loan feedback system as loan is fully paid, display only to lenders or partners of this borrower

	$show=0;
	$noadd=0;
	$vid=$session->userid;
	if($session->userlevel == LENDER_LEVEL) {
		$show=$database->isMyLender($ud,$vid);
	}
	else if($session->userlevel == PARTNER_LEVEL) {
		$show=$database->isMyPartner($ud,$vid);
	}

	$results=$database->getPartnerCommentby($ud,0,0,$ld);//$userid,$partid,$cid,$loanid=0//pid must be zero and a ld is must
	$cid=0;
	if((!empty($_REQUEST['cid'])))
		$cid=$_REQUEST['cid'];
?>
	<div class="row">
		
			<div class="bid-table">

		<?php	if(!empty($results))
				{	?>

				<h3 class="subhead"><?php echo $lang['loanstatn']['feedback'] ?><p id="lend_feedback" class="view-more-less">View Less</p></h3>
				<div id="lend_feedback_desc">

					<table class="zebra-striped">
						<thead>
							<tr>
								<th width="100px"><strong><?php echo $lang['loanstatn']['date_comment'] ?></strong></th>
								<th width="100px"><strong><?php echo $lang['loanstatn']['by'] ?></strong></th>
								<th width="100px"><strong><?php echo $lang['loanstatn']['fbrating'] ?></strong></th>
								<th><strong><?php echo $lang['loanstatn']['comment'] ?></strong></th>
								<th><strong><?php echo $lang['loanstatn']['edit'] ?></strong></th>
						</thead>
						<tbody>
							<?php
								foreach($results as $rew1)
								{
									// id  partid  userid  date  amount  lpaid  ontime  feedback  comment
									$commentid=$rew1['id1'];
									$commentdate=date("M d, Y", $rew1['editDate']);
									$commentby=$rew1['partid'];
									if($commentby==$vid)
										$noadd=1;//check for only a single feed back by a single client
									/*$commentf='<img src="images/layout/icons/'.$rew1['feedback'].'.gif">';*/
                                                                        if($rew1['feedback']==3)
										$commentf= "Neutral";
									else if($rew1['feedback']==4)
										$commentf= "Negative";
									else
										$commentf= "Positive";
									$comment1=$rew1['comment'];
									$lenderUsername = $database->getUserNameById($rew1['senderid']);
									//$f=strlen($comment1);///
									//if($f>50)
										//$comment1= substr($rew1['comment'], 0,50)."...&nbsp;<a href='index.php?p=14&u=$ud&l=$ld&cid=$commentid'>more</a>";
							?>
									<tr>
										<td><?php echo $commentdate;?></td>
								<!--	<td><a href='index.php?p=12&u=<?php echo $commentby;?>'><img src='library/getimagenew.php?id=<?php echo		$commentby;?>&width=50&height=50' border=0/></a></td>  -->
										<?php $prurl = getUserProfileUrl($commentby);?>
										<td><a href='<?php echo $prurl ?>'><?php echo $lenderUsername ?></a></td>
										<td><?php echo $commentf;?></td>
										<td><?php echo $comment1;?></td>
										<td>
									<?php	if($commentby==$vid)
											{	?>
											<?php $loanprurl = getLoanprofileUrl($ud,$ld);?>
												<a href="<?php echo $loanprurl.'?cid='.$commentid.'#e1';?>"><img src="images/layout/icons/edit.png" border=0/></a>
									<?php	}	?>
										</td>
									</tr>
						<?php	}	?>
						</tbody>
					</table>
		<?php	}
				if($show)
				{
					//check for my lender or my partner only
					if($cid > 0)
					{
						//get detail of particular comment by comment id
						$resultsrow=$database->getPartnerCommentby($ud,0,$cid,$ld);//pid must be zero and a ld AND cid is must,
						if(!empty($resultsrow))
						{
							//id  partid  userid  date  amount  lpaid  ontime  feedback  comment
							$ldate1=date("M d, Y", $resultsrow['editDate']);
						}
						$feedback1=$resultsrow['feedback'];
						$comment2=$resultsrow['comment'];
					}
					$temp = $form->value("feedback");
					if(!isset($feedback1)) {
						$feedback1=0;
					}
					if(isset($temp) && $temp != '')
						$feedback1=$form->value("feedback");
					$temp = $form->value("comment");
					if(!isset($comment2)) {
						$comment2="";
					}
					if(isset($temp) && $temp != '')
						$comment2=$form->value("comment");
					echo '<a name="e1" ></a>';
					if(!$noadd || ($cid > 0))
					{   
						if($loginError = $form->error('feedback_userid'))
						{
							echo "<tr><td><div>".$loginError."</div><td></tr>";
						}
			?>			<form method='post' action='updateprocess.php'>
							<table border='0'>
								<tr>
									<td><b><?php echo $lang['loanstatn']['rating']; ?></b></td>
									<td>
										<SELECT id ='feedback' NAME="feedback">
											<OPTION VALUE="2" 
											<?php 
											if(isset($feedback1) && $feedback1=='2')  
												{echo "selected='true'"; }
											?>><?php echo $lang['loanstatn']['select_feb'];?>
											<OPTION VALUE="3"  <?php if(isset($feedback1) && $feedback1=='3')  
												{echo "selected='true'"; }
											?>><?php echo $lang['loanstatn']['select_ntl'];?>
											<OPTION VALUE="4"  <?php if(isset($feedback1) && $feedback1=='4')  
												{echo "selected='true'"; }
											?>><?php echo $lang['loanstatn']['select_unfeb'];?>
										</SELECT>
									</td>
									<td><div id="feedbackerror"><?php echo $form->error('feedback'); ?></div></td>
								</tr>
								<tr>
									<td><b><?php echo $lang['loanstatn']['comment'] ?></b></td>
									<td><textarea  rows=10 cols=40 id='pcomment' name='comment'><?php if(isset($_SESSION['pcomment'])){ echo $_SESSION['pcomment']; } else {echo $comment2; } ?></textarea></td>
									<td><div id="pcommenterror"><?php echo $form->error('comment'); ?></div></td>
								</tr>
								<tr>
									<td align='center' colspan='3'>
										<input type='hidden' name='repaymentfeedback' />
										<input type="hidden" name="user_guess" value="<?php echo generateToken('repaymentfeedback'); ?>"/>
										<input type='hidden' name='userid' value='<?php echo $ud;?>' />
										<input type='hidden' name='loanid' value='<?php echo $ld;?>' />
										<input type='hidden' name='commentid' value='<?php echo $cid;?>' />
								<?php	if(($cid > 0)){ ?><input class='btn' type='submit' name='Edit' value='<?php echo $lang['loanstatn']['update'] ?>' /><?php }
										else if(!$noadd){?><input class='btn' type='submit' name='AddMore' value='<?php echo $lang['loanstatn']['button_save'] ?>' onclick="needToConfirm = false;"/><?php }?>
									</td>
								</tr>
							</table>
						</form>
			<?php	}

				}	?>

			</div><!-- /bid-table -->
	</div><!-- /row -->
		

<?php
}	?>


		<!-- comment section -->
		<div class="row" style="align:left">
			<div id="comment-section">
				<?php
					echo "<br/><br/>";
					$fb=0;
						include_once("./editables/profile.php");
						$path=	getEditablePath('profile.php');
						include_once("editables/".$path);
						include_once("includes/b_comments.php");
				?>
				
			</div>
		
		</div>



<!-- share box script -->
<?php
}
?>
<?php
	//if($showShareBox != 0)
	//{	
		if($_SESSION['lender_bid_success_amt'] == 0){
			$title = "  Share!";
			$bidMessage =$brw['FirstName']." needs only $".number_format($stilneed, 2, '.', ',')." more to be fully funded.<br/><br/>
			Help ".$brw['FirstName']." raise the remaining amount by inviting others to match your loan contribution.";

		} elseif($stilneed > 0) {
			$title = "Thank You!";
			$bidmsg ="Now ".$brw['FirstName']." needs only $".number_format($stilneed, 2, '.', ',')." more to be fully funded.<br/><br/>
			Help ".$brw['FirstName']." raise the remaining amount by inviting others to match your loan contribution.";
			$bidMessage= "Your loan of $".number_format($_SESSION['lender_bid_success_amt'], 2, ".", ",")." has been credited to ".$brw['FirstName']."'s loan application.<br />".$bidmsg."";

		}else {
			$title = "Thank You!";
			$bidmsg ="Thanks to you, ".$brw['FirstName']."'s loan application is 100% funded!";
			$bidMessage= "Your loan of $".number_format($_SESSION['lender_bid_success_amt'], 2, ".", ",")." has been credited to ".$brw['FirstName']."'s loan application.<br />".$bidmsg."";

		}
		$newstext="Help raise ".$brw['FirstName']."'s loan";
		$tweetmadeLoan= "Help me raise a loan for ".$brw['FirstName']." in ".$database->mysetCountry($brw['Country'])."."." $".number_format($stilneed)." still needed!";
		$madeLoan= "Help me raise a loan for ".$brw['FirstName']." in ".$database->mysetCountry($brw['Country'])."!";
		$short_url = "https%3A%2F%2Fwww.zidisha.org%2Findex.php%3Fp%3D14%26u%3D".$ud."%26l%3D".$ld;
		$sharephoto= $imagesrc;
		$loan_use= substr($loanuse, 0, 90);
		$loanprurl = getLoanprofileUrl($ud,$ld);
		$twtUrl = SITE_URL.$loanprurl;
	?>

	<script type="text/javascript">
		var twtUrl= "<?php echo $twtUrl; ?>";
		function fbshare()
		{
			var fburl="http://www.facebook.com/sharer.php?s=100&p[url]=<?php echo $short_url; ?>&p[images][0]=<?php echo urlencode($sharephoto); ?>";
			window.open(fburl,'','width=600,height=450,left=200,top=200');
		}
		function twtshare()
		{
			$('.twtTextShare').each(function(){
			   twttext = $(this).val();
			});
			var twitterParams = { 
				url: twtUrl, 
				text: twttext 
			}; 
			var twturl="http://twitter.com/share?" + $.param(twitterParams);
			window.open(twturl,'','width=600,height=450,left=200,top=200');
		}
	</script>
	<div id="shareForm" style="display:none">
		<div style="width:100%"  align="center">
			<div align="center" id="container">
				<div id="top_strip"></div><!--top_strip closed -->
				<div id="mid_strip">
					<div id="containt">
						<div id="upper" class="padding_prop" style="padding-top:5px;">
							<div class="left">
								<div class="thankyou_text left" style="margin-top:20px; margin-left:10px"><?php echo $title; ?></div>
								<div class="upper_text right"><?php echo $bidMessage; ?></div>
								<div class="clear"></div>
							</div><!--left closed -->
							<!--right closed -->
							<div class="clear"></div>
						</div><!--upper closed -->
						<div id="lower" class="padding_prop">
							<div>
								<div class="left news_text" style="margin-left:30px"><?php echo $newstext; ?></div>
								<div class="left" style="padding-top:5px;">
									<div class="block2 shareTab1 <?php if(!isset($_SESSION['shareEmailValidate'])) { echo 'tab2'; } ?>" onClick="showBox(1);" style="cursor:pointer">
										<div  class="tab_space2" align="center">
											<img src="images/layout/popup/fb.png" border="0" />
									   </div>
									</div>
									<div class="block shareTab2" onClick="showBox(2);"  style="cursor:pointer">
										<div  class="tab_space" align="center">
											<img src="images/layout/popup/twitter.png"  border="0"/>
										</div>
									</div>
									<div  class="block shareTab3 <?php if(isset($_SESSION['shareEmailValidate'])) { echo 'tab'; } ?>" onClick="showBox(3);"  style="cursor:pointer">
										<div  class="tab_space" align="center">
											<img src="images/layout/popup/mail.png" />
										</div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							<div id="slant">&nbsp;</div><!--slant closed -->
							<div id="data">
								<div class="left testi">
									<div class="link_text"><a href="<?php echo SITE_URL;?>">www.zidisha.org</a></div>
									<div align="left" id="bubble">
										<div class="space">
											<div class="left">
												<?php if (!empty($imagesrc)){ ?>
													<img src="<?php echo $imagesrc; ?>" style="max-height:100px;max-width:130px" alt="<?php echo $name ?>"/>
												<?php } ?>
											</div>
											<div class="left testi_text">
												<span><?php echo $loan_use; ?></span>
												&nbsp;<span class="link_text" style="font-style:normal;"><a target="_blank" class="link_text" href='<?php echo $loanprurl?>'>More</a></span>
											</div>
											<div class="clear"></div>
										</div><!--space closed -->
									</div><!--bubble closed -->
								</div><!--testi closed -->
								<div class="right form">
									<div class="shareTab1Detail" style="<?php if(isset($_SESSION['shareEmailValidate'])) { echo 'display:none'; } ?>">
										<div style="padding-top:20px">
											<a href="javascript:void(0)" onClick="fbshare();"><img src="images/layout/popup/fb_button.png" /></a>
										</div>
										<div style="padding-top:63px;width:200px;text-align:left">

											<strong>
											<a href="javascript:void(0)" onclick="$.facebox.close();" class=''>
											No Thanks</a></strong>

											<br/><br/>
											<a href='javascript:void(0)' onclick='sharebox_off_submit(<?php echo $userid;?>,<?php echo $ud;?>,<?php echo $ld;?>,1)'>Do Not Display Share Invite Again</a>

										</div>
									</div>
									<div class="shareTab2Detail" style="display:none">
										<table class="form_text" align="center" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td valign="top" class="paddign_right_prop"><?php echo $newstext; ?></td>
											</tr>
											<tr>
												<td style="padding-top:5px;"> 
													<textarea id="twtTextShare" name="twtTextShare" class="textarea_box2  textarea_text twtTextShare"><?php echo $tweetmadeLoan; ?></textarea>
												</td>
											</tr>
											<tr>
												<td align="center" style="padding-top:10px;">
													<a href="javascript:void(0)" onClick="twtshare();"><img src="images/layout/popup/tweet_button.png" /></a>
												</td>
											</tr>
										</table>
									</div>

									<div class="shareTab3Detail" style="<?php if(!isset($_SESSION['shareEmailValidate']) || isset($_SESSION['ShareEmailSent'])) {echo 'display:none';} ?>">
										<form name="bidform1" action="process.php" method="post">
											<table class="form_text" align="center" cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td valign="top" class="paddign_right_prop">TO</td>
													<td>
														<input name="to_email" type="text" class="input_box" value="<?php echo $form->value('to_email'); ?>"/>
														<div><?php echo $form->error('to_email'); ?></div>
													</td>
												</tr>
												<tr><td class="top_padding_prop">&nbsp;</td></tr>
												<tr>
													<td valign="top" class="paddign_right_prop">NOTE</td>
													<td>
														<textarea name="note" class="textarea_box"><?php echo $form->value('note'); ?></textarea>
														<div><?php echo $form->error('note'); ?></div>
													</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td style="padding-top:10px;">
														<div>
															<div class="left">
																<div class="left"><input name="sendme" type="checkbox" /></div>
																<div class="right" style="font-family:Arial;margin-top:3px;">Send me a copy</div>
																<div class="clear"></div>
															</div>
															<div class="right" style="margin-right:5px;">
																<input type="hidden" id="sendShareEmail" name="sendShareEmail" />
																<input type="hidden" name="user_guess" value="<?php echo generateToken('sendShareEmail'); ?>"/>
																<input type="hidden" name="uid" value="<?php echo $ud ?>" />
																<input type="hidden" name="lid" value="<?php echo $loanid ?>" />
																<?php 
																	unset($_SESSION['lender_bid_success1']);
																	unset($_SESSION['lender_bid_success2']);
																	?>
																<input type="hidden" name="formbidpos" value="<?php echo $showShareBox ?>" />
																<input type="hidden" name="email_sub" value="<?php echo $madeLoan ?>" />
																<input type="hidden" name="loan_use" value="<?php echo $loan_use ?>" />
																<?php unset($_SESSION['shareEmailValidate']);?>
																<input type="image" src="images/layout/popup/send_button.png" name="Send"/>
															</div>
															<div class="clear"></div>
														</div>
													</td>
												</tr>
											</table>
										</form>
									</div>
									<div class='mail_sent_section' >
											<?php 
													if(isset($_SESSION['ShareEmailSent'])){ 
													echo"<div class='black_text_emailSent'>";
														echo"Your email has been sent.";
														unset($_SESSION['ShareEmailSent']);
													echo"</div>";
													echo"<div style='margin-top:30px'><input type='image' src='images/layout/popup/send_another_button.png' name='Send Again' onClick='showBox(3)' value=' '></div>";
												}?>
									</div>
								</div><!--form closed -->
								<div class="clear"></div><!--clear closed -->
							</div><!--data closed -->
						</div><!--lower closed -->
					</div><!--containt closed -->
				</div><!--mid_strip closed -->
				<div id="bottom_strip"></div><!--bottom_strip closed -->
			</div><!--container closed -->
		</div>	<!-- /style="width:100%" -->	
	</div> <!-- /shareform -->
 </body>
<?php
//	}
?>
<!-- end share box -->


<!-- loan forgiveness confirmation -->
<div id='donotforgive' style='display:none;' >
	<table  class='detail' style="padding:15px;width:470px">
		<tbody>
			<tr height='10px'>
			</tr>
			<tr style="font-size:16px;">
				<td colspan=2><div id='forgiveText' style="text-align:justify"><?php echo $lang['loanstatn']['donotforgive_confirmation']?></div></td>
			</tr>
			<tr height='10px'>
			</tr>
				<?php 
					$originUrl = parse_url($RequestUrl);
					$originUrlpath = $originUrl['path'];
					$qStr = $originUrl['query']; //$_SERVER['QUERY_STRING']
					$key="dntfrg";
					parse_str($qStr,$originUrl);
					$redir_url = $originUrlpath.'?'.http_build_query(array_diff_key($originUrl,array($key=>"1")));
					unset($originUrl['v']);
					unset($originUrl['lid']);
					unset($originUrl['dntfrg']);
					$cancel = $originUrlpath;
				?>	
				<td><a href="<?php echo $redir_url?>" class='btn'>Confirm that I do not wish to forgive this loan</a></td>
				<td><a href="<?php echo $cancel?>" class='btn' onclick="$.facebox.close();">Cancel</a></td>
			<tr>
			</tr>
		</tbody>
	</table>
</div> <!-- /donotforgive -->


</div><!-- /span10 -->

	<div class="span5 right_column">

		<div id="loan-profile" class="loan-profile">

				<h3><?php echo $name ?></h3>
			
				<strong><?php echo $location ?></strong>

				<br/><br/>
				
				<?php echo $session->getStatusBar($ud,$ld); ?>
								
				<!-- start lender bid form -->
				<?php if($brw2['active']==LOAN_OPEN && ($session->userlevel  == LENDER_LEVEL || empty($session->userid)))
					{ ?>


				    <form id='bidform1' name="bidform1" action="process.php" method="post" style='margin-top: 20px;'>
				    <?php if($loginError = $form->error('bid_userid1')){ echo "<div>".$loginError."</div><br/>";}?>

				    <script type="text/javascript">
				        function fillAmount1()
				            {
				                document.bidform1.pamount1.value="<?php echo number_format($stilneed, 2, '.', ''); ?>";
				            }
				     </script>
				
					<!-- lender bid amount -->
					
					<tr>
						<td>
				            <label style="width:auto" for="pamount1">
				            	<?php echo $lang['loanstatn']['loan_amount'] ?>
				            </label>
				        	
		                    <input class="medium" id="pamount1" name="pamount1" size="20" type="text"  value="<?php echo $pamount1; ?>">
		                    <div id="pamounterr1"><?php echo $form->error('pamount1'); ?></div>
		           		
		        
							<!-- lender bid interest -->
							<br/>
							<label style="width:auto" for="pinterest1">
								<?php echo $lang['loanstatn']['prop_intr'];?> 
								<img src='library/tooltips/help.png' class="intr2-tooltip-target tooltip-target" id="intr2-target-1" style='border-style:none;display:inline' />
								<div class="tooltip-content tooltip-content" id="intr2-content-1">
									<span class="tooltip">
										<span class="tooltipTop"></span>
										<span class="tooltipMiddle" >
											<?php echo $lang['loanstatn']['tooltip_bid_int'];?>
											<p class="auditedreportlink">
												<a href="includes/flatinterestrate.php" rel="facebox"><?php echo $lang['loanstatn']['flatintrest_diff']?></a>				
											</p>
										</span>	
										<span class="tooltipBottom"></span>
									</span>
								</div>
							</label>
						
							<select class"medium" style="width:150px" id="pinterest1" name="pinterest1">
								<?php
								$int_range = range(0, $maxInterestRate);
								$i=0;
								foreach($int_range as $int_option) {  ?>
									<option value='<?php echo $int_option; ?>' <?php if($form->value("$pinterest1")==$int_option) echo "Selected='true'" ?>><?php echo $int_option ?>%</option>
									<?php		
									$i++;
								} ?>
							</select>

							<?php	if(isset($_SESSION['lender_bid_success1']))
									{	?>
										<div class="clearfix" style="color:green">
											<?php if($stilneed > 0) {
												echo $lang['loanstatn']['bid_success']; 
											} else { 
												echo $lang['loanstatn']['bid_success_funded'];
											}?>

										</div>
							<?php	} 

							if($stilneed > 0){ ?>
				                <div class="clearfix">
				                	<a href="javascript:void(0)" onClick='fillAmount1();'><strong><br/>Complete <?php echo $brw['FirstName'] ?>'s Loan (USD <?php echo number_format($stilneed, 2, '.', '') ?>)</strong></a>
				                </div>
				            <?php } ?>

				            <div class="clearfix"></div>

							<input type="hidden" id="lenderbidUp" name="lenderbidUp" value="" />
							<input type="hidden" name="user_guess" value="<?php echo generateToken('lenderbidUp'); ?>"/>
							<input type="hidden" id="borrowerid1" name="bid" value="<?php echo $ud ?>" />
							<input type="hidden" name="lid" value="<?php echo $loanid ?>" />
							<input  class="btn" type="submit" onclick="needToConfirm = false;" value="<?php echo $lang['loanstatn']['lend'];?>"  />
							<br/>
							<p  style="padding-left:150px"><a  href="<?php echo $RequestUrl?>#shareForm" rel="facebox"><strong>Share</strong></a></p>

						<div style="clear:both"></div>
					</form>

		<?php	} ?>
	
		</div> <!-- /loan-profile -->

		<!-- about this borrower -->
		<div id="loan-profile" class="loan-profile">
			
			<h4 colspan="2"><?php echo $lang['loanstatn']['about']." ".$name; ?></h4>
			
			<table>
				<tbody>

					<tr>
						<td>
							<strong><br/>On-Time Repayments: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_RepayRate'] ?></span><span class='bottom'></span></span></a></strong>
						</td>
						<td>
							<br/>
							<?php echo $repayrate_disp; ?>
						</td>
					</tr>

				<?php if($viewprevloan!=''){ ?>
					<tr>
						<td></td><td><div id="viewprevloan" style="cursor:pointer;" ><a><?php echo $viewprevloan; ?></a></div></td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="viewprevloan_desc" style="display:none;">
								<?php foreach($allloans as $allloan){
									if($allloan['loanid']!=$ld){ 
										$loanDisburseDate=date('M Y',$database->getLoanDisburseDate($allloan['loanid']));
										$loanRepaidDate= date('M Y',$database->getLoanRepaidDate($allloan['loanid'], $ud));
										$amountGot=number_format(convertToDollar($allloan['AmountGot'],($CurrencyRate)), 2, ".", "");
										$loanprofileurl = getLoanprofileUrl($ud,$allloan['loanid']);

										echo "<a href='".$loanprofileurl."'> USD ".$amountGot."&nbsp&nbsp&nbsp".$loanDisburseDate." - ".$loanRepaidDate."</a><br/><br/>"; 
									}
								}
								?>
							</div>
						</td>
					</tr>

				<?php } ?>

					<tr>
						<td>
							<strong><?php echo $lang['loanstatn']['fbrating'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_feed_rating'] ?></span><span class='bottom'></span></span></a></strong>
						</td>
						<td>
							<?php 
							if(!empty($feedback) && ($cf != 1)){
								$prurl = getUserProfileUrl($ud);
								echo "<br/><br/>".number_format($f); ?>% Positive&nbsp;(<?php echo $cf-1; ?>)<br/><br/><a href="<?php echo $prurl?>?fdb=2">View Lender Feedback</a>
							<?php } elseif(!$bfrstloan){ 
								echo 'No Feedback (New Member)'; 
							} else {
								echo 'No Feedback'; 
							} ?>
						</td>
					</tr>
					<tr>
						<td> 
							<strong> 
							<?php echo $lang['loanstatn']['online_identity'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_online'] ?></span><span class='bottom'></span></span></a></strong>
						</td>
						<td>
							<?php if(!empty($fb_data)){
								echo "<a href='http://www.facebook.com/".$fb_data['user_profile']['id']."' target='_blank'>".$lang['loanstatn']['view_fb']."</a>";
							} else{
								echo $lang['loanstatn']['not_provided'];
							}
							?>
						</td>
					</tr> 

					
					<!-- displays whether or not borrower has provided copy of national ID -->
					<tr>
						<td> 
							<strong> <?php echo $lang['loanstatn']['nationalid'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_nationalid'] ?></span><span class='bottom'></span></span></a></strong>
						</td>
						<td>
							<?php if(empty($brw['frontNationalId'])){
								echo $lang['loanstatn']['not_provided'];
							} else {
								echo $lang['loanstatn']['provided'];
							} ?>
						</td>
					</tr> 
					<tr><td></td></tr>


					<!-- displays whether or not borrower has provided Recommendation Form -->
					<tr>
						<td> 
							<strong> <?php echo $lang['loanstatn']['recommendation'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_recommendation'] ?></span><span class='bottom'></span></span></a></strong>
						</td>
						<td>
							<?php if(empty($brw['addressProof'])){
								echo $lang['loanstatn']['not_provided'];
							} else {
								echo $lang['loanstatn']['provided'];
							} ?>
						</td>
					</tr> 


					<!-- displays the member who invited this borrower -->
					<?php if(!empty($invitedby)){
					?>
					<tr>
						<td>
							<strong><?php echo $lang['loanstatn']['invited_by'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_invited'] ?></span><span class='bottom'></span></span></a></strong></td>
							</strong>
						</td>
						<td>
							<?php echo $invitedby."<br/>".$inv_repayrate; ?>
						</td>
					</tr>
					<?php } ?>

					<!-- Volunteer Mentor -->
					<?php if(!empty($mentor)){
					?>
					<tr>
						<td>
							<strong><?php echo $lang['loanstatn']['volunteer_mentor'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_mentor'] ?></span><span class='bottom'></span></span></a></strong></td>
							</strong>
						</td>
						<td>
							<?php echo $mentor."<br/>".$mentor_repayrate; ?>
						</td>
					</tr>
					<?php } ?>

				<!-- endorsements -->
				<tr>
					<td> 
						<strong><?php echo $lang['loanstatn']['public_endorse'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_endorse'] ?></span><span class='bottom'></span></span></a></strong>
					</td>
						
					<td>
						<?php if(!empty($candisplay)){ ?>

							<a href="<?php echo $prurl?>?fdb=3"><?php echo $lang['loanstatn']['view_endorse']?></a>
						
						<?php } else {

							echo $lang['loanstatn']['not_provided'];
						}
					?>

					</td>
				</tr>

			</tbody>
		</table>
		
		<?php if($is_volunteer){ 
			$vm_member_details= $database->getMentorAssignedmember($ud);
			$params['vm_member']= count($vm_member_details);
			$vm_member_text= $session->formMessage($lang['loanstatn']['self_vm'], $params);
			?>
				<div id="viewassignedmember" style="cursor:pointer;" >
						<img style='float:left' class='starimg' src="images/star.png" />&nbsp;&nbsp;&nbsp;<?php echo $vm_member_text; ?>

						<a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_mentor'] ?></span><span class='bottom'></span></span></a><br/>
				</div><br/>
				<div id="viewassignedmember_desc" style="display:none;" class="span16">
					<table class="detail">
						<tbody>
						<?php foreach($vm_member_details as $vm_member_detail){
							$member_loanid=$database->getCurrentLoanid($vm_member_detail['userid']);
							if(empty($member_loanid)){
								$member_url= getUserProfileUrl($vm_member_detail['userid']);
							}else{
								$member_url = getLoanprofileUrl($vm_member_detail['userid'],$member_loanid);
							}
						?>
							<tr><td></td><td><a href="<?php echo $member_url ?>" target="_blank"><?php echo $vm_member_detail['FirstName']." ".$vm_member_detail['LastName']; ?></a></td>
							</tr>
							<tr></tr>
						<?php 
						}
						?>
						</tbody>
					</table>
				</div>
		<?php }   ?>

				</tbody>
			</table>
		</div> <!-- /loan-profile -->

		<div id="loan-profile" class="loan-profile">

			<h4><?php echo $lang['loanstatn']['about_loan']; ?></h4>
			
				<table>
					<tbody>
						<tr height="15">
							<td colspan="2">
							</td>
						</tr>
						<?php if($showLoanDetail==1){ ?>
						<tr>
							<td><strong><?php echo $lang['loanstatn']['bid_close'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['biding_close'] ?></span><span class='bottom'></span></span></a></strong></td>
							<td><?php echo $bot ?></td>
						</tr>
						<tr height="15">
							<td colspan="2">
							</td>
						</tr>
						<tr>
							<td style="width:250px"><strong><?php echo $lang['loanstatn']['requested'] ?>:</strong></td>
							<td style="width:205px">USD <?php echo $damountX ?></td>
						</tr>
						<?php }if($showLoanDetail==2){ ?>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['loan_pri_disb'] ?>:</strong></td>
							<td>
						<?php	if($show_localcurrency)
									echo $tmpcurr." ".number_format(round_local($brw2['AmountGot']),0,'.',',');
								else
									echo $tmpcurr." ".number_format(convertToDollar($brw2['AmountGot'], $disburseRate),2,'.',',');
						?>
							</td>
						</tr>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['date_disb'] ?>:</strong></td>
							<td><?php echo date('M d, Y',$disburseDate); ?></td>
						</tr>
						<?php } ?>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['pd'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_pd'] ?></span><span class='bottom'></span></span></a></strong></td>
							<td><?php echo $period ?> <?php echo $periodText ?></td>
						</tr>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['gpd'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_gpd']?></span><span class='bottom'></span></span></a></strong></td>
							<td><?php echo $gperiod ?> <?php echo $gperiodText ?></td>
						</tr>
						<?php if($showLoanDetail==1){ ?>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['max_intr_rate'] ?>: 
							<img src='library/tooltips/help.png' class="intr-tooltip-target tooltip-target" id="intr-target-1" style='border-style:none;display:inline' />
							<div class="tooltip-content tooltip-content" id="intr-content-1">
							<span class="tooltip">
								<span class="tooltipTop"></span>
								<span class="tooltipMiddle" >
								<?php echo $lang['loanstatn']['tooltip_rli'];?>
									<p class="auditedreportlink">
										<a href="includes/flatinterestrate.php" rel="facebox"><?php echo $lang['loanstatn']['flatintrest_diff']?></a>
									</p>
								</span>	
								<span class="tooltipBottom"></span>
							</span>
							</div>
							</strong></td>
							<td><?php echo number_format($maxInterestRate, 2, '.', ',') ?>%</td>
						</tr>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['webfee'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_atf']?></span><span class='bottom'></span></span></a></strong></td>
							<td><?php echo number_format($webfee, 2, '.', ','); ?>%</td>
						</tr>
						<?php } ?>
						<?php if(!$bfrstloan){	?>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['b_reg_fee'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_webfee']?></span><span class='bottom'></span></span></a></strong></td>
							<td>
						<?php	if($show_localcurrency && $showLoanDetail==2)
									echo $b_reg_fee_native;
								else
									echo "USD ". $b_reg_fee;
						?>
							</td>
						</tr>
						<?php }	?>
						<?php if($showLoanDetail==1){ ?>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['tba'] ?>:</strong></td>
							<td>USD <?php echo number_format($totToPayBackinUSD, 2)." (". number_format( $totFee , 2, '.', ',') ?>%)</td>
						</tr>
						<?php }	?>
						<?php if($showLoanDetail==2){ ?>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['tot_int_due_lend'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_tot_int_due_lend']?></span><span class='bottom'></span></span></a></strong></td>
							<td>
						<?php	if($show_localcurrency)
									echo $tmpcurr." ".number_format(round_local($feelender),0, '.', ',')." (".number_format($interestrate,2, '.', ',')."%)";
								else
									echo $tmpcurr." ".number_format(convertToDollar($feelender ,($disburseRate)),2, '.', ','). " (".number_format($interestrate,2, '.', ',')."%)";
						?>
							</td>
						</tr>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>

						<tr>
							<td><strong><?php echo $lang['loanstatn']['br_trn_fee'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_br_trn_fee']?></span><span class='bottom'></span></span></a></strong></td>
							<td>
						<?php	if($show_localcurrency)
									echo $tmpcurr." ".number_format(round_local($feeamount), 0, '.', ',')." (".number_format($webfee, 2,'.',',')."%)";
								else
									echo $tmpcurr." ".number_format(convertToDollar($feeamount ,($disburseRate)), 2, '.', ',')." (".number_format($webfee, 2,'.',',')."%)";
						?>
							</td>
						</tr>

						<tr height="15">
							<td colspan="2">
							</td>
						</tr>
						
						<tr>
							<td><strong><?php echo $lang['loanstatn']['tba'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_tba']?></span><span class='bottom'></span></span></a></strong></td>
							<td>
						<?php	if($show_localcurrency)
									echo $tmpcurr." ".number_format(round_local($totToPayBack), 0)." (". number_format( $totFee , 2, '.', ',')."%)";
								else
									echo $tmpcurr." ".number_format(convertToDollar($totToPayBack ,($disburseRate)), 2)." (". number_format( $totFee , 2, '.', ',')."%)";
						?>
							</td>
						</tr>
						<?php }	?>
						
					</tbody>
				</table>

		</div> <!-- /loan-profile -->

		<div id="loan-profile" class="loan-profile">

			<div class="row">
			
				<div class="bid-table" id="retval">
				<?php if($brw2['active']==LOAN_OPEN )
				{	?>
					<h4><?php echo $lang['loanstatn']['funding_bids'] ?></h4>
					<div id="funding_bids_desc">
		
						<table class="zebra-striped" style="padding:8px 16px 8px 0px">
							<thead>
								<tr>
									<th><strong><?php echo $lang['loanstatn']['date_comment'] ?></strong></th>
									<th><strong><?php echo $lang['loanstatn']['lender'] ?></strong></th>
									<th><strong><?php echo $lang['loanstatn']['amt_accept'] ?> (USD)</strong></th>
									<th><strong><?php echo $lang['loanstatn']['lender_int'] ?></strong></th>
								</tr>
							</thead>
							<tbody>
							<?php
							if(!empty($bids))
							{
								$i=0;
								$totBidAmt = 0;
								$totBidAmt1 = 0;
								$acceptedAmt = 0;
								$z = 0;
								$col = 1;
								foreach($bids as $rows1)
								{
									$bids[$z]['color']=$col;
									$bidamount1=$rows1['bidamount'];
									$totBidAmt1 += $bidamount1;
									if($totBidAmt1 >= $damount)
									{
										$acceptedAmt1 =  $damount - ($totBidAmt1 - $bidamount1);
										if($acceptedAmt1 < 0)
											$acceptedAmt1 =0;
									}
									else
									{
										$acceptedAmt1 = $bidamount1;
									}
									$bids[$z]['acceptedAmt']=$acceptedAmt1;

									if($totBidAmt1 >= $damount)
									{
										$col = 0;
									}
									$z++;
								}
								$date=array();
								foreach ($bids as $key => $row)
									$date[$key] = $row['biddate'];
								array_multisort($date, SORT_ASC, $bids);
								foreach($bids as $rows)
								{
									$bidddid=$rows['bidid'];
									$brrid=$rows['borrowerid'];
									$lendid=$rows['lenderid'];
									$lname=trim($rows["Firstname"].' '.$rows['LastName']);
									$sublevel=$database->getUserSublevelById($lendid);
									if($sublevel==LENDER_GROUP_LEVEL)
										$lusername=$lname;
									else
										$lusername=$rows['username'];
									$karma_score = number_format($database->getKarmaScore($lendid));
									$karma_tooltip = $lang['profile']['karma_tooltip'];
									$bidamount=$rows['bidamount'];
									$kamount=convertToNative($bidamount, $CurrencyRate);
									$bidint=$rows['bidint'];
									$biddate=$rows['biddate'];
									$acceptedAmt = $rows['acceptedAmt'];
									$totBidAmt += $bidamount;
									$lendprurl = getUserProfileUrl($lendid);
									if($rows['color']==0)
										$colour='; color:#CCBBBB';
									else
										$colour='; color:##3D3D3D';

									echo "<tr>";
									echo "<td>".date('M d', $biddate)."</td>";
									echo "<td><a href='$lendprurl'>$lusername</a> <br/><a style='cursor:pointer' class='tt'>($karma_score)<span class='tooltip'><span class='top'></span><span class='middle'>$karma_tooltip</span><span class='bottom'></span></span></a>
									</td>";
									if($lendid==$session->userid)
									{
										$name1 = 'bidamt' .$i;
										$name2 = 'bidint' .$i;
										$name3 = 'bidid' .$i;
										$error1 = 'erramt'.$i;
										$error2 = 'errint'.$i;

									echo "<td>".number_format($acceptedAmt, 2, '.',',') ."<input type='hidden' size=5 name=$name1 id=$name1 value='".number_format($acceptedAmt, 2, '.','')."'/><br /><div id=$error1 name=$error1></div></td>";
									echo "<td>".number_format($bidint, 2, '.',',')."<input type='hidden' size=2 name=$name2  id=$name2 value='".number_format($bidint, 2, '.','')."'/>%<br /><div id=$error2 name=$error2></div></td>";
									echo "<td><input type='hidden' size=2 name=$name3 id=$name3 value='".$bidddid."'/><img SRC='images/layout/icons/edit.png' alt='Edit bid' style='cursor:pointer' title='Edit My Bid'></td>";

										$i=++$i;
									}
									else
									{
										echo "<td>".number_format($acceptedAmt, 2, '.',',')."</td>";
										echo "<td>".number_format($bidint, 2, '.',',')."%</td>";
										echo "<td>&nbsp</td>";
									}
									echo "</tr>";
								}
							}
							?>
							</tbody>
						</table>
					</div>

					<a name='e3'></a>

					<p><strong><?php echo $lang['loanstatn']['total_bids'] ?>:</strong>	USD <?php echo number_format($totBid, 2, '.', ',') ?></p>
					<p><strong><?php echo $lang['loanstatn']['amt_stil_need'] ?>:</strong>	USD <?php echo number_format($stilneed, 2, '.', ',') ?></p>
				
					<script type="text/javascript">
						function fillAmount()
						{
							document.bidform.pamount.value="<?php echo number_format($stilneed, 2, '.', ''); ?>";
						}
					</script>

					<a name="e6" ></a>
	
					<?php $val = $form->value('bidid'); ?>
					<form id='bidform' name="bidform" action="process.php" method="post">
						<input type="hidden" id="editBidAmount" name="editBidAmount" value="<?php echo $form->value('editBidAmount') ?>" />
						<?php if(empty($val)){ ?>
						<div id='editBidMsg' style='font-weight:bold'></div><br/>
						<?php }else{ ?>
						<div id='editBidMsg' style='font-weight:bold'><?php echo $lang['loanstatn']['edit_bid1']; ?> <a onclick='setNewBid()' style='cursor:pointer'><?php echo $lang['loanstatn']['here'] ?></a> <?php echo $lang['loanstatn']['edit_bid2'] ?></div><br/>
						<?php } ?>
						<?php if($loginError = $form->error('bid_userid')){ echo "<div>".$loginError."</div><br/>";}?>
							
						<label style="width:auto" for="pamount">
							<?php echo $lang['loanstatn']['loan_amount'] ?> 
						</label>
						
						<input class="medium" id="pamount" name="pamount" size="20" type="text" value="<?php echo $pamount; ?>">
						
						<div class="input inputex" id="pamounterr" style="margin-left:0px;margin-top:10px;">
							<?php echo $form->error('pamount'); ?>
						</div>
						<br/>
						<label style="width:auto" for="pinterest1">
						
							<?php echo $lang['loanstatn']['prop_intr'];?> 
							
							<img src='library/tooltips/help.png' class="intr2-tooltip-target tooltip-target" id="intr2-target-1" style='border-style:none;display:inline' />
							<div class="tooltip-content tooltip-content" id="intr2-content-1">
								<span class="tooltip">
									<span class="tooltipTop"></span>
									<span class="tooltipMiddle" >
										<?php echo $lang['loanstatn']['tooltip_bid_int'];?>
										<p class="auditedreportlink">
											<a href="includes/flatinterestrate.php" rel="facebox"><?php echo $lang['loanstatn']['flatintrest_diff']?></a>				
										</p>
									</span>	
									<span class="tooltipBottom"></span>
								</span>
							</div>
						</label>

						<!-- drop-down menu for lenders to select interest rate -->
						<select class"medium" style="width:150px" id="pinterest" name="pinterest">
								<?php
								$int_range = range(0, $maxInterestRate);
								$i=0;
								foreach($int_range as $int_option) {  ?>
									<option value='<?php echo $int_option; ?>' <?php if($form->value("$pinterest1")==$int_option) echo "Selected='true'" ?>><?php echo $int_option ?>%</option>
									<?php		
									$i++;
								} ?>
						</select>
						<br/><br/>
						<div class="input inputex" id="pintrerr"><?php echo $form->error('pinterest'); ?></div>
						
				<?php	if(isset($_SESSION['lender_bid_success2']))
					{	 ?>
						<div class="clearfix" style="color:green">
							<?php if($stilneed > 0) {
									echo $lang['loanstatn']['bid_success']; 
								} else { 
									echo $lang['loanstatn']['bid_success_funded'];
								}?>						
						</div>
			<?php	} ?>
				<?php	if($stilneed > 0){ ?>
						<div class="clearfix">
							<a href="javascript:void(0)" onClick='fillAmount();'><strong>Complete <?php echo $brw['FirstName'] ?>’s Loan (USD <?php echo number_format($stilneed, 2, '.', '') ?>)</strong></a>
						</div>
						<?php } ?>
						<input type="hidden" id="lenderbid" name="lenderbid" value="" />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('lenderbid'); ?>"/>
						<input type="hidden" id="bidid" name="bidid" value="<?php echo $form->value('bidid'); ?>" />
						<input type="hidden" id="borrowerid" name="bid" value="<?php echo $ud ?>" />
						<input type="hidden" name="lid" value="<?php echo $loanid ?>" />
						<?php if(empty($val)){?>
							<input class="btn" type="submit" id="act" value="<?php echo $lang['loanstatn']['lend'];?>" />
						<?php if($showShareBox==2) { ?>
							<p  style="padding-left:130px"><a  href="<?php echo $RequestUrl?>#shareForm" rel="facebox"><span class="btn_share">Share</span></a></p>
						<?php } ?>
						<?php }else{ ?>
							<input class="btn" type="submit" id="act" value="<?php echo $lang['loanstatn']['bid_save'];?>" />
						<?php } ?>
					</form>
				
				<?php } elseif($brw2['active']==LOAN_FUNDED || $brw2['active'] == LOAN_ACTIVE || $brw2['active']==LOAN_DEFAULTED || $brw2['active']==LOAN_REPAID)
				{	
				$lendamount=$database->getLoanAmount($ud, $ld);
				if(!empty($lendamount))
				{?>
					<h4 class="subhead"><?php echo $lang['loanstatn']['funding'] ?></h4>
						<div id="lend_funding_desc">
							<table class="zebra-striped tablesorter_funding">
								<thead>
									<tr>
										<th><strong><?php echo $lang['loanstatn']['lender_name'] ?></strong></th>
										<th><strong><?php echo $lang['loanstatn']['amt_accept'] ?> (USD)</strong></th>
										<th><strong><?php echo $lang['loanstatn']['lender_int'] ?></strong></th>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach($lendamount as $rows)
										{
											$leid=$rows["lenderid"];
											$lname=$rows["Firstname"].' '.$rows['LastName'];
											$lusername=$rows['username'];
											$bidamount=$rows['givenamount'];
											$sublevel=$database->getUserSublevelById($leid);
											if($sublevel==LENDER_GROUP_LEVEL)
												$lusername=$lname;
											$karma_score = number_format($database->getKarmaScore($leid));
											$karma_tooltip = $lang['profile']['karma_tooltip'];
											$kamount=$rows['bidamount'];
											$bidint=$rows['bidint'];
											$leprurl = getUserProfileUrl($leid);
											$lamt = convertToDollar($brw2['AmountGot'] ,($CurrencyRate));
											$percentFinanced = ($bidamount* 100)/$lamt ;
											echo "<tr>";
												echo "<td>
													<a href='$lendprurl'>$lusername</a> <br/><a style='cursor:pointer' class='tt'>($karma_score)<span class='tooltip'><span class='top'></span><span class='middle'>$karma_tooltip</span><span class='bottom'></span></span></a>
												</td>";
												echo "<td>".number_format($bidamount, 2, ".",",")."</td>";
												echo "<td>".number_format($bidint, 2, ".",",")." %</td>";
											echo "</tr>";
										}
									?>
								</tbody>
							</table>
						</div>
					
				<?php
					} 
				} ?>
				</div><!-- /bid-table -->
			</div> <!-- /row -->
		</div> <!-- /loan-profile -->


		<!-- repayment schedule -->
		<?php
		if($brw2['active'] == LOAN_ACTIVE || $brw2['active']==LOAN_DEFAULTED || $brw2['active']==LOAN_REPAID)
		{ 
			$schedule = $session->generateScheduleTable($ud, $ld, $show_localcurrency, $disburseRate);
			if(!empty($schedule['schedule']))
			{ ?>
					<div id="loan-profile" class="loan-profile">
						<div class="row">
						
							<div>
								<a name='repayschedule' id='repayschedule'></a>
								<h4 class="subhead"><?php echo $lang['loanstatn']['repament_schedule'] ?></h4>
								<div id="repay_sched_desc">
									<table class="detail">
										<tbody>
											<tr>
												<td colspan="2"><br/><strong><?php echo $lang['loanstatn']['repay_due']." ".date("M d, Y",time())?>:</strong>&nbsp&nbsp<?php echo $tmpcurr." ".number_format($schedule['due'], 0, '', ','); ?></td>
											</tr>
											<tr>
												<td colspan="2"><strong><?php echo $lang['loanstatn']['totrepay_due']." ".date("M d, Y",time()) ?>:</strong>&nbsp&nbsp<?php
													if($show_localcurrency)
														echo $tmpcurr." ".number_format(round_local($schedule['amtPaidTillShow']), 0, '.', ',');
													else
														echo $tmpcurr." ".number_format($schedule['amtPaidTillShow'], 0, '', ',');
													?>
												</td>
											</tr>
										<?php	if($brw2['active'] == LOAN_ACTIVE && $session->userlevel==LENDER_LEVEL && $database->isLenderInThisLoan($ld,$session->userid))
												{
													$totalForgivenLenders=$database->totalForgivenLendersThisLoan($ld);
													if($totalForgivenLenders >0)
													{
														if($totalForgivenLenders ==1)
															$strText1=convertNumber2word($totalForgivenLenders)." lender has forgiven this loan.";
														else
															$strText1=convertNumber2word($totalForgivenLenders)." lenders have forgiven this loan.";
														echo "<tr><td colspan=2><br/>".$strText1."</td></tr>";
													}
										?>
									<?php	if(!$database->isLenderForgivenThisLoan($ld,$session->userid) && $database->isInForgiveLoan($ld))
													{
														?>
														<tr><td colspan=2><br/><strong><a href="includes/forgive.php?loanid=<?php echo "$ld&ud=$ud"?>" rel='facebox'><?php echo $lang['loanstatn']['forgive_my_share'] ?></a></strong> <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loanstatn']['tooltip_forgive'] ?></span><span class='bottom'></span></span></a></td></tr>
									<?php
													}
												}
												$rescheduleResult=$database->getRescheduleDataByLoanId($ld);
												if(!empty($rescheduleResult))
												{
													echo "<tr><td colspan=2><br/>This loan was rescheduled on ".date('M j, Y',$rescheduleResult['date'])."</td></tr>";
												}
												else if($brw2['active'] == LOAN_REPAID && $session->userlevel==LENDER_LEVEL && $database->isLenderInThisLoan($ld,$session->userid))
												{
													$totalForgivenLenders=$database->totalForgivenLendersThisLoan($ld);
													if($totalForgivenLenders >0)
													{
														if($totalForgivenLenders ==1)
															$strText1=convertNumber2word($totalForgivenLenders)." lender has forgiven this loan.";
														else
															$strText1=convertNumber2word($totalForgivenLenders)." lenders have forgiven this loan.";
														echo "<tr><td colspan=2><br/>".$strText1."</td></tr>";
													}
												}
											?>
										</tbody>
									</table>
									
									<?php echo $schedule['schedule']; ?>
									
							</div> <!-- /repay_sched_desc -->
						
						</div><!-- /bid-table -->
						
					</div><!-- /row -->
				
			</div> <!-- loan-profile -->
	<?php
			}
		} ?>

	</div> <!-- /span5 -->
</div>
</div><!-- /row -->



</div>
</div> <!-- span16 -->