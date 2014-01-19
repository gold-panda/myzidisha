<?php
include_once("library/session.php");
class Process
{ 
	function Process()
	{		
		
		global $session;
		if($session->usersublevel==READ_ONLY_LEVEL && (isset($_POST['sendbulkmails']) || isset($_POST['emailedTo']))){
			$_SESSION['Readonly']=true;
			if(isset($_SERVER['HTTP_REFERER']))
				header("Location: ".$_SERVER['HTTP_REFERER']);
			else
				header("Location: ".SITE_URL);
			exit;
		}
		$valid= checkToken();
		if(!$valid)
		{
			global $form;
			$_SESSION['invalidForm']=true;
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			if(isset($_SERVER['HTTP_REFERER']))
				header("Location: ".$_SERVER['HTTP_REFERER']);
			else
				header("Location: ".SITE_URL);
			exit;
		}
		if(isset($_POST["reg-borrower"])){
			$this->subRegBorrower();
		}
		else if(isset($_POST["reg-lender"])){
			$this->subRegLender();
		}
		else if(isset($_POST['reg-partner'])){
			$this->subRegPartner();
		}
		else if(isset($_POST["userlogin"])){
			$this->subLogin();
		}
		else if(isset($_POST['loanapplication'])){
			$this->loanApplication();
		}
		else if(isset($_POST['editloanapplication'])){
			$this->editloanApplication();
		}
		else if(isset($_POST['exrate'])){
			$this->exchangeRate();
		}
		else if(isset($_POST['amt_entered'])){
			$this->saveRegistrationFee();
		}
		else if(isset($_POST['confirmApplication'])){
			$this->confirmLoan();
		}
		else if(isset($_POST['lenderbid'])){
			$this->placeBid();
		}
		else if(isset($_POST['lenderbidUp'])){
			$this->placeBidUp();
		}
		else if(isset($_POST['minfundamount'])){
			$this->setMinFund();
		}
		else if(isset($_POST['activatePartner'])){
			$this->activatePartner();
		}
		else if(isset($_POST['deactivatePartner'])){
			$this->deactivatePartner();
		}
		else if(isset($_POST['activateLender'])){
			$this->activateLender();
		}
		else if(isset($_POST['deactivateLender'])){
			$this->deactivateLender();
		}
		else if(isset($_POST['deactivateBorrower'])){
			$this->deactivateBorrower();
		}
		else if(isset($_POST['deleteBorrower'])){
			$this->deleteBorrower();
		}
		else if(isset($_POST['deletePartner'])){
			$this->deletePartner();
		}
		else if(isset($_POST['deleteLender'])){
			$this->deleteLender();
		}
		else if(isset($_POST['makeLoanExpire'])){
			$this->makeLoanExpire();
		}
		else if(isset($_POST['makeLoanActive'])){
			$this->makeLoanActive();
		}
		else if(isset($_POST['sendbulkmails'])){
			$this->sendEmailByAdmin();
		}
		else if(isset($_POST['addpaymenttolender'])){
			$this->addpaymenttolender();
		}
		else if(isset($_POST['adddonationtolender'])){
			$this->adddonationtolender();
		}
		else if(isset($_POST['changePassword'])){
			$this->changePassword();
		}
		else if(isset($_POST['forgiveShare'])){
			$this->forgiveShare();
		}
		else if(isset($_POST['assignedPartner'])){
			$this->assignedPartner();
		}
		else if(isset($_POST['referral'])){
			$this->referral();
		}
		else if(isset($_POST['add-repayment_instruction'])){
			$this->addRePaymentInstruction();
		}
		else if(isset($_POST['sendShareEmail'])){
			$this->sendShareEmail();
		}
		else if(isset($_POST['campaign'])){
			$this->addcampaign();
		}
		else if(isset($_POST['deactivateAccount'])){
			$this->deactivateAndDonate();
		}
		else if(isset($_POST['emailedTo'])){
			$this->emailedTo();
		}
		else if(isset($_POST['AllowForgive'])){
			$this->AllowForgive();
		}
		else if(isset($_POST['automaticLending'])){
			$this->automaticLending();
		}
		else if(isset($_POST['StopCommision'])){
			$this->StopRefferalCommision();
		}else if(isset($_POST['RemoveFromCart'])){
			$this->RemoveFromCart();
		}else if(isset($_POST['ProcessCart'])){
			$this->ProcessCart();
		}else if(isset($_POST['commentcredit'])){
			$this->commentcredit();
		}else if(isset($_POST['ontimerepaycredit'])){
			$this->commentcredit();
		}
		else if(isset($_POST['lendergroup'])){
			$this->SubCreateLenderGroup();
		}
//added by Julia 6-11-2013
		else if(isset($_POST['bgroup'])){
			$this->SubCreateBGroup();
		}
		else if(isset($_POST['joinLendingGroup'])){
			$this->joinLendingGroup();
		}else if(isset($_POST['leavegroup'])){
			$this->leavegroup();
		}else if(isset($_POST['transffer_leadership'])){
			$this->transffer_leadership();
		}else if(isset($_POST['GroupmsgNotify'])){
			$this->updateGrpmsgNotify();
		}else if(isset($_POST['grant_access_co'])){
			$this->grantAccessCo();
		}else if(isset($_POST['grant_remove_co'])){
			$this->grantRemoveCo();
		}else if(isset($_POST['loanbycntry'])){
			$this->getloansbycountry();
		}
		else if(isset($_POST['review_borrower'])){
			$this->review_borrower();
		}
		else if(isset($_POST['verify_borrower'])){
			$this->verify_borrower();
		}
		else if(isset($_POST['remove_payment'])){
			$this->remove_payment();
		}
		else if(isset($_POST['co_org_note'])){
			$this->co_org_note();
		}
		else if(isset($_POST['volunteer_mentor'])){
			$this->getActiveCoOrgUsers();
		}
		else if(isset($_POST['activeborrowers'])){ 
			$this->getactiveborrowers();
		}
		else if(isset($_POST['verify_borrower_ByAdmin'])){ 
			$this->verify_borrower_ByAdmin();
		}
		else if(isset($_POST['reg-endorser'])){  
			$this->subRegEndorser();
		}
		else if(isset($_POST['sendJoinEmail'])){  
			$this->sendJoinshareEmail();
		}
		else if(isset($_POST['brwrinvitecredit'])){ 
			$this->commentcredit();
		}
		elseif(isset($_POST['isdonation'])){
			$this->donate();
		}
		elseif(isset($_POST['vm_city'])){
			$this->getVolunteersByCity();
		}
		elseif(isset($_POST['savedisbursednote'])){
			$this->savedisbursednote();
		}
		else if(isset($_POST['makeConfirmAuth'])){
			$this->makeConfirmAuth();
		}
		else if(isset($_POST['saveRepayReport'])){
			$this->saveRepayReport();
                }else if(isset($_POST['getVolMentStaffMemList'])){
                        $this->getVolMentStaffMemList();
                }
		else{ 
			$this->subLogout();
		}
	}
	function sendEmailByAdmin()
	{
		global $form,$session;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);

		$reply = $session->sendBulkMails($_POST['emailaddress'], $_POST['radio_useroption'], $_POST['emailmessage'], $_POST['emailsubject']);
		if(!$reply){
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location:./index.php?p=20");
            exit;
		}
		if($reply){
			header('Location:./index.php?p=20');
            exit;
		}
	}
	function subLogout()
	{
		global $session;
		$session->logout();
		$ref = "index.php";
		header("Location: $ref");
		exit;
	}
	function subLogin()
	{	
		global $session, $form, $database;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$remember=false;
		if(isset($_POST["remember"])){
			$remember=true;
		}
		$result=$session->login($_POST["username"], $_POST["password"], $remember);
		if(isset($_SERVER['HTTP_REFERER'])) {
			$ref =$_SERVER['HTTP_REFERER'];
		}else {
			$ref=SITE_URL;
		}
		if($result==0){
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: $ref");
            exit;
		}
		else if($result==1)
		{	
			if($session->userlevel==LENDER_LEVEL)
			{	
				$page=array('0','3','4','47','48');
				$url=parse_url($ref);
				$paramerter='';
				if(isset($url['query'])) {
					parse_str($url['query'],$paramerter); 
				}
				if(isset($_COOKIE['lndngcrtfrntlogdin'])) {
					if(isset($_GET["language"]))
						{
							$language = $_GET["language"];
							$ref=SITE_URL.$language.'/index.php?p=75';
						}
						else
							$ref=SITE_URL.'index.php?p=75';
						$database->setUseridByCookievalinCart($_COOKIE['lndngcrtfrntlogdin']);
					setcookie('lndngcrtfrntlogdin', '', 1);
					header("Location: $ref");
					exit;
				}
				else if(isset($paramerter['p']))
				{
					if(in_array($paramerter['p'],$page))
					{
						if(isset($_GET["language"]))
						{
							$language = $_GET["language"];
							$ref=SITE_URL.$language.'/index.php?p=19';
						}
						else
							$ref=SITE_URL.'index.php?p=19';
					}else{
						$ref=SITE_URL.'index.php?p=1&sel=4&t=2';	
					}
				}
				else
				{
					
					if(isset($_GET["language"]))
					{
						$language = $_GET["language"];
						$ref=SITE_URL.$language.'/index.php?p=19';
					}
					//12-19-2012 Anupam checks if isset($paramerter['fg']) if it is logging in for forgiving his loan he should redirected to the page he comes from 
					else if(!isset($paramerter['fg'])) {
						$ref=SITE_URL.'index.php?p=19';
					}
				}
			}
			if($session->userlevel==BORROWER_LEVEL)
			{
				$ref= SITE_URL."index.php?p=50";
			}
			if($session->userlevel==ADMIN_LEVEL){ 
				$page=array('0','3','4','47','48');
				$url=parse_url($ref);
				if(isset($url['query'])) {
					parse_str($url['query'],$paramerter); 
				}
				
			if(isset($paramerter['p']))
				{
					if(in_array($paramerter['p'],$page))
					{	
						if(isset($_GET["language"]))
						{
							$language = $_GET["language"];
							$ref=SITE_URL.$language.'/index.php?p=7&type=3&ord=DESC';
						}
						else
							$ref=SITE_URL.'index.php?p=7&type=3&ord=DESC';
					}
				}else{
					if(isset($_GET["language"]))
					{
						$language = $_GET["language"];
						$ref=SITE_URL.$language.'/index.php?p=7&type=3&ord=DESC';
					}
					else
						$ref=SITE_URL.'index.php?p=7&type=3&ord=DESC';
				} 
			}
			if(isset($_SESSION['language']))
			{
				$len1= strlen($ref);
				$len2= strlen(SITE_URL);
				$len3= $len1-$len2;
				$ref1= substr($ref, $len2, $len3);
				$refArr= explode("/",$ref1);
				$language= $_SESSION['language'];
				if(strlen($refArr[0])!=2)
					$ref= SITE_URL.$language."/".$ref1;
				else
				{
					$ref2= substr($ref1, 2, $len3-2);
					$ref= SITE_URL.$language.$ref2;
				}
			}
			header("Location: $ref");
            exit;
		} 
	}
	function subRegLender()
	{
		global $session, $form;
		$id = 0;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$user_guess = '';
		if (isset($_POST['recaptcha_response_field'])) {
			$user_guess = sanitize($_POST['recaptcha_response_field']);
		}
		if (!isset($_POST['referral_code'])) {
			$_POST['referral_code']= '';
		}
		if (!isset($_POST['lwebsite'])) {
			$_POST['lwebsite']= '';
		}
		if (!isset($_POST['frnds_emails'])) {
			$_POST['frnds_emails']= '';
		}
		if (!isset($_POST['frnds_msg'])) {
			$_POST['frnds_msg']= '';
		}
		$result=$session->register_l($_POST["lusername"], $_POST["lpass1"], $_POST["lpass2"], $_POST["lemail"], $_POST["lfname"], $_POST["llname"], $_POST["labout"], $_POST["lphoto"], $_POST["lcity"], $_POST["lcountry"], $_POST["hide_Amount"], $_POST["loan_comment"], $_POST["tnc"], $user_guess, $id, $_POST["card_code"], $_POST['frnds_emails'], $_POST['frnds_msg'], $_POST["loan_app_notify"], $_POST["loan_repayment_credited"], $_POST["subscribe_newsletter"],$_POST['referral_code'],$_POST['lwebsite'],$_POST['member_type']);
		if($result==0)
		{
			if(is_uploaded_file($_FILES['lphoto']['tmp_name']))
			{
				$img_file = $_FILES['lphoto']['tmp_name'];
				$ext = split( '/', $_FILES['lphoto']['type'] );
				imageUpload($img_file, $ext, $id);
			}
			else if(!empty($_POST['isPhoto_select']))
			{
				$img_file =TMP_IMAGE_DIR.$_POST['isPhoto_select'];
				$ext[1] = end(explode(".", $img_file));
				imageUpload($img_file, $ext, $id);	
			}
			$_POST["username"]=$_POST["lusername"];
			$_POST["password"]=$_POST["lpass1"];
			$this->subLogin();
			exit;
		}
		else
		{
			$_SESSION['value_array'] = $_POST_ORG;
			if(!empty($_FILES['lphoto']['tmp_name']))
			{
				chmod($_FILES['lphoto']['tmp_name'], 0644);
				$time=time();
				if($_FILES['lphoto']['tmp_name']=="image/gif")
					$photo=$time.".gif";
				else if($_FILES['lphoto']['tmp_name']=="image/jpeg" || $_FILES['lphoto']['tmp_name']=="image/pjpeg")
					$photo=$time.".jpeg";
				else if($_FILES['lphoto']['tmp_name']=="image/png" || $_FILES['lphoto']['tmp_name']=="image/x-png")
					$photo=$time.".png";
				else
					$photo=$_FILES['lphoto']['name'];
				move_uploaded_file($_FILES['lphoto']['tmp_name'],TMP_IMAGE_DIR.$photo);
				$_SESSION['value_array']['isPhoto_select']=$photo;
			}			
			$_SESSION['error_array'] = $form->getErrorArray();
			
			if(isset($_POST['member_type']) && $_POST['member_type']==5)
				header("Location: index.php?p=1&sel=5");
			else
				header("Location: index.php?p=1&sel=2");
            exit;
		}
	}
	function subRegPartner()
	{
		global $session, $form;
		$id = 0;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$user_guess = '';
		if (isset($_POST['recaptcha_response_field'])) {
			$user_guess = sanitize($_POST['recaptcha_response_field']);
		}
		$result=$session->register_p($_POST['pusername'], $_POST['ppass1'], $_POST['ppass2'], $_POST['pname'], $_POST['paddress'], $_POST['pcity'], $_POST['pcountry'], $_POST['pemail'], $_POST['emails_notify'], $_POST['pwebsite'], $_POST['pdesc'], $user_guess, $id, $_POST["labellang"]);
		if($result)
		{
			if(is_uploaded_file($_FILES['pphoto']['tmp_name']))
			{
				$img_file = $_FILES['pphoto']['tmp_name'];
				$ext = split( '/', $_FILES['pphoto']['type'] );
				imageUpload($img_file, $ext, $id);
			}
			else if(!empty($_POST['isPhoto_select']))
			{
				$img_file =TMP_IMAGE_DIR.$_POST['isPhoto_select'];
				$ext[1] = end(explode(".", $img_file));
				imageUpload($img_file, $ext, $id);	
			}
			header("Location: index.php?p=1&sel=4&t=3");
            exit;
		}
		else
		{
			$_SESSION['value_array'] = $_POST_ORG;
			if(!empty($_FILES['pphoto']['tmp_name']))
			{
				chmod($_FILES['pphoto']['tmp_name'], 0644);
				$time=time();
				if($_FILES['pphoto']['tmp_name']=="image/gif")
					$photo=$time.".gif";
				else if($_FILES['pphoto']['tmp_name']=="image/jpeg" || $_FILES['pphoto']['tmp_name']=="image/pjpeg")
					$photo=$time.".jpeg";
				else if($_FILES['pphoto']['tmp_name']=="image/png" || $_FILES['pphoto']['tmp_name']=="image/x-png")
					$photo=$time.".png";
				else
					$photo=$_FILES['pphoto']['name'];
				move_uploaded_file($_FILES['pphoto']['tmp_name'],TMP_IMAGE_DIR.$photo);
				$_SESSION['value_array']['isPhoto_select']=$photo;
			}
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=1&sel=3&lang=".$_POST["labellang"]);
            exit;
		}
	}
	function subRegBorrower()
	{ 
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST); 
		$id = 0;
		$user_guess = '';
		for($i=1; $i<=10; $i++){
			$endorser_name[]= $_POST['endorser_name'.$i];
			$endorser_email[]= $_POST['endorser_email'.$i];
		}
		if($_POST['before_fb_data']=='1'){
			$_SESSION['fb_data']= $_POST;
			header('Location: index.php?p=1&sel=1&fb_data=1#FB_cntct');
            exit;
		}else{
			if (isset($_POST['recaptcha_response_field'])) {
				$user_guess = $_POST['recaptcha_response_field'];
			}
			if(!is_uploaded_file($_FILES['front_national_id']['tmp_name']) && !empty($_POST['isFrntNatid'])) {
				$_FILES['front_national_id']['tmp_name'] = $_POST['isFrntNatid'];
				$_FILES['front_national_id']['name'] = end(explode("/",$_POST['isFrntNatid']));
			}
			if(!is_uploaded_file($_FILES['back_national_id']['tmp_name']) && !empty($_POST['isbcktnatid'])) {
				$_FILES['back_national_id']['tmp_name'] = $_POST['isbcktnatid'];
				$_FILES['back_national_id']['name'] = end(explode("/",$_POST['isbcktnatid']));
			}
			if(!is_uploaded_file($_FILES['address_proof']['tmp_name']) && !empty($_POST['isaddrprf'])) {
				$_FILES['address_proof']['tmp_name'] = $_POST['isaddrprf'];
				$_FILES['address_proof']['name'] = end(explode("/",$_POST['isaddrprf']));
			}
			if(!is_uploaded_file($_FILES['legal_declaration']['tmp_name']) && !empty($_POST['islgldecl'])) {
				$_FILES['legal_declaration']['tmp_name'] = $_POST['islgldecl'];
				$_FILES['legal_declaration']['name'] = end(explode("/",$_POST['islgldecl']));
			}
			if(!is_uploaded_file($_FILES['legal_declaration2']['tmp_name']) && !empty($_POST['islgldecl2'])) {
				$_FILES['legal_declaration2']['tmp_name'] = $_POST['islgldecl2'];
				$_FILES['legal_declaration2']['name'] = end(explode("/",$_POST['islgldecl2']));
			}
			$photo=$_POST['isPhoto_select'];
			if(is_uploaded_file($_FILES['bphoto']['tmp_name'])) {
				$photo=$_FILES['bphoto']['tmp_name'];
			}
			if(!isset($_POST['repaidpast'])) {
				$repaidPast = 0;
			}else {
				$repaidPast = $_POST['repaidpast'];
			}
			if(!isset($_POST['debtfree'])) {
				$debtFree = 0;
			}else {
				$debtFree = $_POST['debtfree'];
			}
			if(!isset($_POST['share_update'])) {
				$share_update = 0;
			}else {
				$share_update = $_POST['share_update'];
			}
			if(isset($_GET['language']) && !empty($_GET['language'])){
				$language=$_GET['language'];
			}else{
				$language='en';
			}
			
			if (!empty($_POST["uploadfileanchor"])) {
				$result = 2;
			}
			else{ 

				$result = $session->register_b($_POST["busername"], $_POST["bfname"], $_POST["blname"], $_POST["bpass1"], $_POST["bpass2"], $_POST["bpostadd"], $_POST["bcity"], $_POST["bcountry"], $_POST["bemail"], $_POST["bmobile"],$_POST["reffered_by"],$_POST["bincome"], $_POST["babout"], $_POST["bbizdesc"], $photo, $share_update, $user_guess, $id, $_POST["bnationid"],  $language, $_POST["referrer"], $_POST["community_name_no"], $_FILES, $_POST['submitform'], $repaidPast, $debtFree, $_POST["borrower_behalf"], $_POST["behalf_name"], $_POST["behalf_number"], $_POST["behalf_email"], $_POST["behalf_town"], $_POST['bfamilycont1'],$_POST['bfamilycont2'], $_POST['bfamilycont3'],$_POST['bneighcont1'],$_POST['bneighcont2'], $_POST['bneighcont3'], $_POST['home_no'], $_POST['rec_form_offcr_name'], $_POST['rec_form_offcr_num'], $_POST['refer_member'], $_POST['volunteer_mentor'], $_POST['cntct_type'], $_POST['fb_data'], $endorser_name, $endorser_email);
				
			}
			if($result==0)
			{ 
				
				if(is_uploaded_file($_FILES['bphoto']['tmp_name']))
				{
					$img_file = $_FILES['bphoto']['tmp_name'];
					$ext = split( '/', $_FILES['bphoto']['type']);
					imageUpload($img_file, $ext, $id);
				}
				else if(!empty($_POST['isPhoto_select']))
				{
					$img_file =TMP_IMAGE_DIR.$_POST['isPhoto_select'];
					$ext[1] = end(explode(".", $img_file));
					imageUpload($img_file, $ext, $id);	
				}
				$_POST["username"]=$_POST["busername"];
				$_POST["password"]=$_POST["bpass1"];
				$this->subLogin();
				header('Location: index.php?p=50');
                exit;
			}
			else
			{	
				$supported=array("image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", "application/pdf");
				$_SESSION['value_array'] = $_POST_ORG;
				$phototype = $_FILES['bphoto']['type'];
				$frntidtype = $_FILES['front_national_id']['type'];
				$bkidtype = $_FILES['back_national_id']['type'];
				$addrsype = $_FILES['address_proof']['type'];
				$legalype = $_FILES['legal_declaration']['type'];
				$legl2type = $_FILES['legal_declaration2']['type'];
				if(!empty($_FILES['bphoto']['tmp_name']) && in_array($phototype, $supported))
				{
					chmod($_FILES['bphoto']['tmp_name'], 0644);
					$time=time();
					if($_FILES['bphoto']['tmp_name']=="image/gif")
						$photo=$time.".gif";
					else if($_FILES['bphoto']['tmp_name']=="image/jpeg" || $_FILES['bphoto']['tmp_name']=="image/pjpeg")
						$photo=$time.".jpeg";
					else if($_FILES['bphoto']['tmp_name']=="image/png" || $_FILES['bphoto']['tmp_name']=="image/x-png")
						$photo=$time.".png";
					else
						$photo=$_FILES['bphoto']['name'];
					move_uploaded_file($_FILES['bphoto']['tmp_name'],TMP_IMAGE_DIR.$photo);
					$_SESSION['value_array']['isPhoto_select']=$photo;
				}
				if(!empty($_FILES['front_national_id']['tmp_name']) && in_array($frntidtype, $supported))
				{
					chmod($_FILES['front_national_id']['tmp_name'], 0644);
					$time=time();
					if($_FILES['front_national_id']['tmp_name']=="image/gif")
						$frntnatid=$time.".gif";
					else if($_FILES['front_national_id']['tmp_name']=="image/jpeg" || $_FILES['front_national_id']['tmp_name']=="image/pjpeg")
						$frntnatid=$time.".jpeg";
					else if($_FILES['front_national_id']['tmp_name']=="image/png" || $_FILES['front_national_id']['tmp_name']=="image/x-png")
						$frntnatid=$time.".png";
					else
						$frntnatid=$_FILES['front_national_id']['name'];
					move_uploaded_file($_FILES['front_national_id']['tmp_name'],TMP_IMAGE_DIR.$frntnatid);
					$_SESSION['value_array']['isFrntNatid']=TMP_IMAGE_DIR.$frntnatid;
				}
				
				if(!empty($_FILES['back_national_id']['tmp_name']) && in_array($bkidtype , $supported))
				{
					chmod($_FILES['back_national_id']['tmp_name'], 0644);
					$time=time();
					if($_FILES['back_national_id']['tmp_name']=="image/gif")
						$bcktnatid=$time.".gif";
					else if($_FILES['back_national_id']['tmp_name']=="image/jpeg" || $_FILES['back_national_id']['tmp_name']=="image/pjpeg")
						$bcktnatid=$time.".jpeg";
					else if($_FILES['back_national_id']['tmp_name']=="image/png" || $_FILES['back_national_id']['tmp_name']=="image/x-png")
						$bcktnatid=$time.".png";
					else
						$bcktnatid=$_FILES['back_national_id']['name'];
					move_uploaded_file($_FILES['back_national_id']['tmp_name'],TMP_IMAGE_DIR.$bcktnatid);
					$_SESSION['value_array']['isbcktnatid']=TMP_IMAGE_DIR.$bcktnatid;
				}

				if(!empty($_FILES['address_proof']['tmp_name']) && in_array($addrsype, $supported))
				{
					chmod($_FILES['address_proof']['tmp_name'], 0644);
					$time=time();
					if($_FILES['address_proof']['tmp_name']=="image/gif")
						$addrprf=$time.".gif";
					else if($_FILES['address_proof']['tmp_name']=="image/jpeg" || $_FILES['address_proof']['tmp_name']=="image/pjpeg")
						$addrprf=$time.".jpeg";
					else if($_FILES['address_proof']['tmp_name']=="image/png" || $_FILES['address_proof']['tmp_name']=="image/x-png")
						$addrprf=$time.".png";
					else
						$addrprf=$_FILES['address_proof']['name'];
					move_uploaded_file($_FILES['address_proof']['tmp_name'],TMP_IMAGE_DIR.$addrprf);
					$_SESSION['value_array']['isaddrprf']=TMP_IMAGE_DIR.$addrprf;
				}
				if(!empty($_FILES['legal_declaration']['tmp_name']) && in_array($legalype , $supported))
				{
					chmod($_FILES['legal_declaration']['tmp_name'], 0644);
					$time=time();
					if($_FILES['legal_declaration']['tmp_name']=="image/gif")
						$lgldecl=$time.".gif";
					else if($_FILES['legal_declaration']['tmp_name']=="image/jpeg" || $_FILES['legal_declaration']['tmp_name']=="image/pjpeg")
						$lgldecl=$time.".jpeg";
					else if($_FILES['legal_declaration']['tmp_name']=="image/png" || $_FILES['legal_declaration']['tmp_name']=="image/x-png")
						$lgldecl=$time.".png";
					else
						$lgldecl=$_FILES['legal_declaration']['name'];
					move_uploaded_file($_FILES['legal_declaration']['tmp_name'],TMP_IMAGE_DIR.$lgldecl);
					$_SESSION['value_array']['islgldecl']=TMP_IMAGE_DIR.$lgldecl;
					
				}
				if(!empty($_FILES['legal_declaration2']['tmp_name']) && in_array($legl2type, $supported))
				{
					chmod($_FILES['legal_declaration2']['tmp_name'], 0644);
					$time=time();
					if($_FILES['legal_declaration2']['tmp_name']=="image/gif")
						$lgldecl2=$time.".gif";
					else if($_FILES['legal_declaration2']['tmp_name']=="image/jpeg" || $_FILES['legal_declaration2']['tmp_name']=="image/pjpeg")
						$lgldecl2=$time.".jpeg";
					else if($_FILES['legal_declaration2']['tmp_name']=="image/png" || $_FILES['legal_declaration2']['tmp_name']=="image/x-png")
						$lgldecl2=$time.".png";
					else
						$lgldecl2=$_FILES['legal_declaration2']['name'];
					move_uploaded_file($_FILES['legal_declaration2']['tmp_name'],TMP_IMAGE_DIR.$lgldecl2);
					$_SESSION['value_array']['islgldecl2']=TMP_IMAGE_DIR.$lgldecl2;
				}
				$_SESSION['error_array'] = $form->getErrorArray();
				if($result==1) {
					$errurl1 = $_SERVER['HTTP_REFERER'];
					if(strstr($errurl1, "fb_join")){
						$errurl= $errurl1;
					}else{
						$errurl= $errurl1."&fb_join=1";
					}
					if(!empty($_SESSION['error_array']['repaidpast'])) {
						$errurl = $errurl."#repaidpasterr";
					} else if(!empty($_SESSION['error_array']['debtfree'])) {
						$errurl = $errurl."#debtfreeerr";
					}else if(!empty($_SESSION['error_array']['share_update'])) {
						$errurl = $errurl."#share_updateerr";
					}else if(!empty($_SESSION['error_array']['behalf_name'])) {
						$errurl = $errurl."#behalf_nameerr";
					}else if(!empty($_SESSION['error_array']['behalf_number'])) {
						$errurl = $errurl."#behalf_numbererr";
					}else if(!empty($_SESSION['error_array']['behalf_email'])) {
						$errurl = $errurl."#behalf_emailerr";
					}else if(!empty($_SESSION['error_array']['behalf_town'])) {
						$errurl = $errurl."#behalf_townerr";
					}else if(!empty($_SESSION['error_array']['busername'])) {
						$errurl = $errurl."#busernameerr";
					}else if(!empty($_SESSION['error_array']['bpass1'])) {
						$errurl = $errurl."#bpass1err";
					}else if(!empty($_SESSION['error_array']['bfname'])) {
						$errurl = $errurl."#bfnameerr";
					}else if(!empty($_SESSION['error_array']['blname'])) {
						$errurl = $errurl."#blnameerr";
					}else if(!empty($_SESSION['error_array']['bphoto'])) {
						$errurl = $errurl."#bphotoerr";
					}else if(!empty($_SESSION['error_array']['bpostadd'])) {
						$errurl = $errurl."#bpostadderr";
					}else if(!empty($_SESSION['error_array']['bcity'])) {
						$errurl = $errurl."#bcityerr";
					}else if(!empty($_SESSION['error_array']['bcountry'])) {
						$errurl = $errurl."#bcountryerr";
					}else if(!empty($_SESSION['error_array']['bnationid'])) {
						$errurl = $errurl."#bnationiderr";
					}/*else if(!empty($_SESSION['error_array']['bloanhist'])) {
						$errurl = $errurl."#bloanhisterr";
					}*/else if(!empty($_SESSION['error_array']['bemail'])) {
						$errurl = $errurl."#bemailerr";
					}else if(!empty($_SESSION['error_array']['bmobile'])) {
						$errurl = $errurl."#bmobileerr";
					}else if(!empty($_SESSION['error_array']['reffered_by'])) {
						$errurl = $errurl."#breffered_by";
					}else if(!empty($_SESSION['error_array']['babout'])) {
						$errurl = $errurl."#babouterr";
					}else if(!empty($_SESSION['error_array']['bbizdesc'])) {
						$errurl = $errurl."#bbizdescerr";
					}else if(!empty($_SESSION['error_array']['referrer'])) {
						$errurl = $errurl."#referrererr";
					}else if(!empty($_SESSION['error_array']['front_national_id'])) {
						$errurl = $errurl."#front_national_iderr";
					}else if(!empty($_SESSION['error_array']['back_national_id'])) {
						$errurl = $errurl."#back_national_iderr";
					}else if(!empty($_SESSION['error_array']['address_proof'])) {
						$errurl = $errurl."#address_prooferr";
					}else if(!empty($_SESSION['error_array']['legal_declaration'])) {
						$errurl = $errurl."#legal_declarationerr";
					}else if(!empty($_SESSION['error_array']['user_guess'])) {
						$errurl = $errurl."#recaptcha_response_fielderr";
					}else if(!empty($_SESSION['error_array']['bfamilycont'])) {
						$errurl = $errurl."#bfamilycontact";
					}else if(!empty($_SESSION['error_array']['bneighcont'])) {
						$errurl = $errurl."#bneighcontact";
					}else if(!empty($_SESSION['error_array']['home_no'])) {
						$errurl = $errurl."#home_noerr";
					}/*else if(!empty($_SESSION['error_array']['lending_institution'])) {
						$errurl = $errurl."#lending_institutionerr";
					}else if(!empty($_SESSION['error_array']['lending_institution_add'])) {
						$errurl = $errurl."#lending_institution_adderr";
					}else if(!empty($_SESSION['error_array']['lending_institution_phone'])) {
						$errurl = $errurl."#lending_institution_phoneerr";
					}else if(!empty($_SESSION['error_array']['lending_institution_officer'])) {
						$errurl = $errurl."#lending_institution_officererr";
					}*/
					else if(!empty($_SESSION['error_array']['refer_member'])) {
						$errurl = $errurl."#refer_membererr";
					}
					else if(!empty($_SESSION['error_array']['volunteer_mentor'])) {
						$errurl = $errurl."#volunteer_mentorerr";
					}
					header("Location: $errurl");
                    exit;
				}
				else {
						$url = $_SERVER['HTTP_REFERER'];
						if(strstr($url, "fb_join")){
							header("Location: $url".$_POST["uploadfileanchor"]);
						}else{
					// redirect to borrower form after file upload. $_POST["uploadfileanchor"] contains an anchor
     						header("Location: $url&fb_join=1".$_POST["uploadfileanchor"]);
						}
						exit;
				}
			}
		}
	}

	function subRegEndorser(){
		global $session, $form; 
		$_POST = sanitize_custom($_POST);
		$id=0;
		if($_POST['before_fb_data']=='1'){ 
			$_SESSION['fb_data']= $_POST;
			$url1 = $_SERVER['HTTP_REFERER'];
			if(strstr($url1, "fb_data")){
				$url= $url1;
			}else{
				$url= $url1."&fb_data=1";
			}
			header("Location: $url");
			exit;
		}else{
			$result= $session->register_e($_POST["busername"], $_POST["bfname"], $_POST["blname"], $_POST["bpass1"], $_POST["bpass2"], $_POST["bpostadd"], $_POST["bcity"], $_POST["bcountry"], $_POST["bemail"], $_POST["bmobile"], $user_guess, $id, $_POST["bnationid"], $_POST['home_no'], $_POST['fb_data'], $_POST['validation_code'], $_POST['babout'], $_POST['bconfdnt'],$_POST['e_candisplay']);
			if($result==0)
			{
				$_POST["username"]=$_POST["busername"];
				$_POST["password"]=$_POST["bpass1"];
				$this->subLogin();
				header('Location: index.php?p=50');
				exit;
			}else{
				$_SESSION['value_array'] = $_POST;
				$_SESSION['error_array'] = $form->getErrorArray();
				$errurl1 = $_SERVER['HTTP_REFERER'];
				if(strstr($errurl1, "fb_join")){
					$errurl= $errurl1;
				}else{
					$errurl= $errurl1."&fb_join=1";
				}
				header("Location: $errurl");
                exit;
			}
		}
	}
	function deactivateBorrower()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$c=$_POST['countryCode'];
		$b_type=$_POST['brwr_type'];
		$s=$_POST['search'];
		$result=$session->deactivateBorrower($_POST['borrowerid'],$_POST['set']);
		if($result){
			header("Location: index.php?p=102&c=$c&brwr=$b_type&search=$s");
		}
		else{
			header("Location: index.php?p=102&c=$c&brwr=$b_type&search=$s");
		}
        exit;
	}

	function activateLender()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$result=$session->activateLender($_POST['lenderid']);
		if($result){
			header("Location: index.php?p=11&a=3&v=1");
		}
		else{
			header("Location: index.php?p=11&a=3&v=0");
		}
        exit;
	}
	function deactivateLender()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$result=$session->deactivateLender($_POST['lenderid']);
		if($result){
			header("Location: index.php?p=11&a=3&v=2");
		}
		else{
			header("Location: index.php?p=11&a=3&v=0");
		}
        exit;
	}
	function activatePartner()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$result=$session->activatePartner($_POST['partnerid']);
		if($result){
			header("Location: index.php?p=11&a=2&v=1");
		}
		else{
			header("Location: index.php?p=11&a=2&v=0");
		}
        exit;
	}
	function deactivatePartner()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$result=$session->deactivatePartner($_POST['partnerid']);
		if($result){
			header("Location: index.php?p=11&a=2&v=2");
		}
		else{
			header("Location: index.php?p=11&a=2&v=0");
		}
        exit;
	}
	function loanApplication()
	{
		global $database,$session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$_POST['amount'] = str_replace(",","",$_POST['amount']);
		$interest = trim(str_replace("%","",$_POST['interest']));
		$_SESSION['la']['amt'] = $_POST['amount'];
		$_SESSION['la']['intr'] = $_POST['interest'];
		$_SESSION['la']['iamt'] = $_POST['installment_amt'];
		$_SESSION['la']['gp'] = $_POST['gperiod'];
		$_SESSION['la']['su'] = $_POST['summary'];
		$_SESSION['la']['lu'] = $_POST['loanuse'];
		$_SESSION['la']['iday'] = $_POST['installment_day'];
		$_SESSION['la']['iwkday'] = $_POST['installment_weekday'];

		$isLoanOpen=$database->loanIsAlreadyOpen($_SESSION['userid']);	// To check loan is already LOAN_OPEN,LOAN_FUNDED,LOAN_ACTIVE
		if($isLoanOpen>0){
			unset($_SESSION['loanapp']);
			header("Location: index.php?p=50");
			exit;
		}else{
		$result=$session->loanApplication($_POST['amount'], $interest, $_POST['installment_amt'], $_POST['gperiod'], $_POST['summary'], $_POST['loanuse'],$_POST['agree'],$_POST['installment_day'],$_POST['installment_weekday']);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
		    header("Location: index.php?p=9");
			exit;
		}
		else if($result==1)
		{
			if(isset($_SESSION['loanapp'])){
				unset($_SESSION['loanapp']);
			}
			$loan=array();
			$loan['amount']=$_POST['amount'];
			$loan['interest']=$_POST['interest'];
			$loan['installment_amt']=$_POST['installment_amt'];
			if (!empty ($_SESSION['la']['iwkday'])) {
				$weekly_inst = 1;
			} else {
				$weekly_inst =0;
			}
			$total_months=$session->getTotalMonthByInstallments($_POST['amount'], $_POST['installment_amt'], $_POST['interest'],$_POST['gperiod'], $weekly_inst);
			$loan['period']=$total_months;
			$loan['grace']=$_POST['gperiod'];
			$loan['summary']=$_POST['summary'];
			$loan['loanuse']=$_POST['loanuse'];
			$loan['tnc']=$_POST['agree'];
			$loan['iday']=$_POST['installment_day'];
			$loan['iwkday']=$_POST['installment_weekday'];
			$_SESSION['loanapp']=$loan;
			header("Location: index.php?p=9&s=1");
			exit;
		}
	  } // end here	
	}
	function editloanApplication()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		if(isset($_POST['editLoanApplicConfirm'])) {
			$_POST['amount'] = str_replace(",","",$_POST['amount']);
			$interest = trim(str_replace("%","",$_POST['interest']));
			$inst_amount = trim(str_replace("%","",$_POST['installment_amt']));
			$inst_day = $_POST['installment_day'];
			$inst_weekday = $_POST['installment_weekday'];
			$gperiod = $_POST['gperiod'];
			$repay_period=$_POST['repay_period'];
			$result=$session->editLoanApplication($_POST['loanid'], $_POST['amount'], $interest, $_POST['loanuse'], $inst_amount, $inst_day, $inst_weekday,$gperiod,0,$repay_period);

			if($result==0)
			{
				$_SESSION['value_array']=$_POST_ORG;
				$_SESSION['error_array']=$form->getErrorArray();
				header("Location: index.php?p=44");
				exit;
			}
			else if($result==1)
			{
				header("Location: index.php?p=44&s=1");
                exit;
			}
		} else {
			$result=$session->editLoanApplication($_POST['loanid'], $_POST['amount'], $_POST['interest'], $_POST['loanuse'], $_POST['installment_amt'], $_POST['installment_day'], $_POST['installment_weekday'], $_POST['gperiod'],1);
			if($result == 1) {
				$_SESSION['la']['editamt'] = $_POST['amount'];
				$_SESSION['la']['editintr'] = $_POST['interest'];
				$_SESSION['la']['editloanuse'] = $_POST['loanuse'];
				$_SESSION['la']['edit_inst_day'] = $_POST['installment_day'];
				$_SESSION['la']['edit_inst_weekday'] = $_POST['installment_weekday'];

				$_SESSION['la']['installment_amt'] = $_POST['installment_amt'];
				$_SESSION['la']['loanid'] = $_POST['loanid'];
				$_SESSION['la']['gperiod'] = $_POST['gperiod'];
				header("Location: index.php?p=44&s=2");
			}else {
				$_SESSION['value_array']=$_POST_ORG;
				$_SESSION['error_array']=$form->getErrorArray();
				header("Location: index.php?p=44");
			}
			exit;
		}
	}
	function confirmLoan()
	{
		global $database, $session;
		if(isset($_SESSION['loanapp']))
		{
			$loan=$_SESSION['loanapp'];
			$amount=$loan['amount'];
			$amount = str_replace(",","",$amount);
			$interest = trim(str_replace("%","",$loan['interest']));
			$period=$loan['period'];
			$grace=$loan['grace'];
			$summary=$loan['summary'];
			$loanuse=$loan['loanuse'];
			$tnc=$loan['tnc'];
			$loan_installmentDate=$loan['iday'];
			$loan_installmentDay=$loan['iwkday'];
			$result=$session->confirmLoanApp($amount, $interest, $period, $grace, $summary, $loanuse,$tnc,$loan_installmentDate, $loan_installmentDay);
			if($result){
				header("Location:index.php?p=9&s=4");
			}
			else{
				header("Location: index.php?p=9&s=1");
			}
			exit;
			# NOTE: YOU NEED TO EXIT AFTER REDIRECT, WHERE IS THERE A RETURN STATEMENT?
			return $result;
		}
		else
		{	$prurl = getUserProfileUrl($session->userid);
			header("Location: $prurl");
			exit;
		}		
	}
	function exchangeRate()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$result=$session->setExchangeRate($_POST['exrateamt'],$_POST['currency']);
		if($result==0){
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=11&a=4&c=".$_POST['currency']);
			exit;
		}
		if($result==1){
			header("Location: index.php?p=11&a=4&c=".$_POST['currency']);
			exit;
		}
	}
	function saveRegistrationFee()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['amount']);
		$result=$session->saveRegistrationFee($_POST['currency'], $amount);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=21");
			exit;
		}
		if($result){
			header("Location:index.php?p=21");
			exit;
		}
	}
	function setMinFund()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['mamount']);
		$result=$session->setMinFund($amount);
		if($result==3)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=11&a=6");
			exit;
		}
		else
		{
			header("Location: index.php?p=11");
			exit;
		}
	}	
	function deleteBorrower()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$result=$session->deleteBorrower($_POST['borrowerid']);
		if(isset($_POST['inactiveBorrower'])){
			header("Location: index.php?p=7");
		}
		else{
			header("Location: index.php?p=11&a=1");
		}
        exit;
	}
	function deletePartner()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$result=$session->deletePartner($_POST['partnerid']);
		if($result){
			header("Location: index.php?p=11&a=2");
		}
		else{
			header("Location: index.php?p=11&a=2");
		}
		exit;
	}
	function deleteLender()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$result=$session->deleteLender($_POST['lenderid']);
		if($result){
			header("Location: index.php?p=11&a=3");
		}
		else{
			header("Location: index.php?p=11&a=3");
		}
		exit;
	}
	function makeLoanActive()
	{	
		global $session, $database;
		$_POST = sanitize_custom($_POST);
		$reg_fee=0;
		if(isset($_POST['reg_fee']))
		{
			$reg_fee = str_replace(",","",$_POST['reg_fee']);
		}
		$admin_amount = str_replace(",","",$_POST['amount']);
		$date_disbursed = strtotime($_POST['disb_date']);
		if(empty($date_disbursed)) {
			$date_disbursed = time();
		}
		$database->startDbTxn();
		$result=$session->updateActiveLoan($_POST['borrowerid'], $_POST['loanid'],$admin_amount, $reg_fee, $date_disbursed);
		$url=$_SERVER['HTTP_REFERER'];
		if($result==1){
			$database->commitTxn();
			header("Location: $url");
		}
		else if($result==2){
			$database->rollbackTxn();
			header("Location: $url&err1=1");
		}
		else{
			$database->rollbackTxn();
			header("Location: $url&err=1001");
		}
		exit;
		# WHY IS THERE A RETURN STATEMENT AFTER THE HEADER (REDIRECT)?
		return $result;
	}
	function makeLoanExpire()
	{	
		global $session, $database;
		$_POST = sanitize_custom($_POST);
		$database->startDbTxn();
		$result = $database->setExpired($_POST['borrowerid'], $_POST['loanid']);
		if($result==0){
			$database->rollbackTxn();
			header("Location: index.php?p=11&a=1&err=1001");
			exit;
		} else {
			$database->commitTxn();
			header("Location: index.php?p=11&a=1");
			exit;
		}
	}
	function placeBid()
	{
		global $session, $form, $database;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = $_POST['pamount'];
		$interest = trim(str_replace("%","",$_POST['pinterest']));
		$loanprurl = getLoanprofileUrl($_POST['bid'],$_POST['lid']);
		if(!empty($_POST['bidid'])){
			$database->startDbTxn();
			$result=$session->editbid($_POST['lid'], $_POST['bid'], $_POST['bidid'],$amount,$interest);
			if($result==1) {
				$database->commitTxn();
			} else {
				$database->rollbackTxn();
			}
		}
		else{
			$result=$session->placeBid($_POST['lid'], $_POST['bid'], $amount, $interest);
		}
		if($result==3){
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: $loanprurl#e3");
			exit;
		}
		else if($result==0){
			header("Location: $loanprurl#e3");
			exit;
		}
		else if($result==1){
			header("Location: $loanprurl#e3");
			exit;
		}
		else if($result==2){
			header("Location: index.php?p=75");
			exit;
		}
	}
	function placeBidUp()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = $_POST['pamount1'];
		$interest = trim(str_replace("%","",$_POST['pinterest1']));
		$bid=sanitize($_POST['bid']);//borrower id
		$lid=sanitize($_POST['lid']);
		$result=$session->placeBid($lid, $bid, $amount, $interest, 1);
		$loanprurl = getLoanprofileUrl($bid,$lid);
		if($result==3)
		{
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: $loanprurl#e5");
			exit;
		}
		else if($result==0){
			header("Location: $loanprurl#e5");
			exit;
		}
		else if($result==1){
			header("Location: $loanprurl#e5");
			exit;
		}
		else if($result==2){
			header("Location: index.php?p=75");
			exit;
		}
	}	
	function addpaymenttolender()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST["amount"]);
		$donation = str_replace(",","",$_POST["donation"]);
		$ret = $session->addpaymenttolender($_POST["userid"],$amount,$donation, $_POST['auto_lending']);
		if($ret==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=60");
			exit;
		}
		else
		{
			header("Location: index.php?p=60&t=1");
			exit;
		}
	}
	function adddonationtolender()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$donationamt = str_replace(",","",$_POST["donationamt"]);
		$ret = $session->adddonationtolender($_POST["name"], $_POST["email"],$donationamt);
		if($ret==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=60");
			exit;
		}
		else
		{
			header("Location: index.php?p=60&t=2");
			exit;
		}
	}
	function changePassword()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$ret = $session->changePassword($_POST["userid"],$_POST["password"],$_POST["cpassword"]);
		if($ret==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
		}
		header("Location: index.php?p=39");
        exit;
	}
	function forgiveShare()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$borrower_id=$_POST['ud'];
		$ret = $session->forgiveShare($_POST['loan_id'],$_POST['ud'], $session->userid);
		$loanprurl = getLoanprofileUrl($_POST['ud'],$_POST['loan_id']);
		$url = $loanprurl;
		if(isset($_SESSION['forgive']))
		{	
			if($_SESSION['forgive']==1)
			{	
				unset($_SESSION['forgive']);
				$url ='index.php?p=40';
			}
			else if($_SESSION['forgive']==8)
			{
				$url =$loanprurl."?fg=yes";
			}
			
		}

		header("Location:$url");
        exit;
	}
	function assignedPartner()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$ret = $session->assignedPartner($_POST['partnerid'],$_POST['borrowerid']);
		if(!$ret)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
		}
		header("Location: index.php?p=7&id=".$_POST['borrowerid']);
		exit;
	}
	function referral()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$refCommission = str_replace(",","",$_POST['refCommission']);
		$ret = $session->referral($_POST['country'],$refCommission, $_POST['refPercent']);
		if(!$ret)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
		}
		header("Location: index.php?p=49&c=".$_POST['country']);
		exit;
	}
	function addRePaymentInstruction()
	{
		global $session, $form;
		$id = 0;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
	
		$result=$session->addRePaymentInstruction($_POST['country_code'], $_POST['description']);
		if($result)
		{
			header("Location: index.php?p=11&a=13");
			exit;
		}
		else
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=11&a=13&ac=add");
			exit;
		}
	}
	function sendShareEmail()
	{

		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$ret = $session->sendShareEmail($_POST["to_email"],$_POST["note"],$_POST["uid"],$_POST["lid"], $_POST["email_sub"],$_POST["loan_use"], $_POST["sendme"]);
		$loanprurl = getLoanprofileUrl($_POST["uid"],$_POST["lid"]);
		if($ret==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
		}else{
			$_SESSION['ShareEmailSent']=true;
		}
		$_SESSION['shareEmailValidate']=($_POST["formbidpos"]==1) ? 1:2;
		if($_POST["formbidpos"]==1)
			header("Location: $loanprurl#e5");
		else
			header("Location: $loanprurl#e3");
		exit;
	}
		function addcampaign()
	{
			
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$result=$session->setCampaign($_POST['code'],$_POST['value'],$_POST['max_use'],$_POST['message'],$_POST['active']);
		if($result==0){
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=54");
			exit;
		}
		if($result==1){
			$_SESSION['campaign_succs']='Campaign added succesfully !';
			header("Location: index.php?p=54");
			exit;
		}
	}
	function deactivateAndDonate()
		{
			global $session;
			$_POST = sanitize_custom($_POST);
			$donate=$session->ConverToDonation($_POST['lenderid'],$_POST['availAmt']);
			header("Location: index.php?p=53");
			exit;
		}
	function emailedTo()
		{
			global $session, $form;
			$_POST = sanitize_custom($_POST);
			$emailed=$session->emailedTo($_POST['borrowerid'],$_POST['emailaddress'],$_POST['ccaddress'],$_POST['replyTo'],$_POST['emailsubject'],$_POST['emailmessage'], $_POST['sendername']);				$errurl="index.php?p=7&id=".$_POST['borrowerid'];
			if(!$emailed)
			{
				$_SESSION['value_array']=$_POST;
				$_SESSION['error_array']=$form->getErrorArray();
				if(!empty($_SESSION['error_array']['sendername'])) {
					$errurl = $errurl."#sendername";
				}
			}
			header("Location: $errurl");
			exit;
		}
		function AllowForgive()
		{
			global $session, $form;
			$_POST = sanitize_custom($_POST);
			$allowed=$session->AllowForgive($_POST['loan_id'],$_POST['comment']);
			if($allowed)
			{
				$_SESSION['value_array']=$_POST;
				$_SESSION['error_array']=$form->getErrorArray();
			}
			header("Location: index.php?p=73");
			exit;
		}
		function automaticLending() {	
			global $session, $form;
			$_POST = sanitize_custom($_POST);
			$userid=$session->userid; 
			$availableAmt = $session->amountToUseForBid($userid);
			$availableAmt = truncate_num($availableAmt,2);
			$autoLend=$session->automaticLending($_POST['status'], $_POST['priority'],$_POST['interest_rate'], $_POST['interest_rate_other'],$_POST['max_interest_rate'], $_POST['max_interest_rate_other'], $_POST['confirm_criteria'],$userid, $availableAmt);
			if($autoLend==0) {
				$_SESSION['value_array']=$_POST;
				$_SESSION['error_array']=$form->getErrorArray();
			}
			header("Location: index.php?p=74");
			exit;
		}
		function StopRefferalCommision()
		{
			global $session, $form;
			$_POST = sanitize_custom($_POST);
			$country=$_POST['country'];
			$stopped=$session->StopRefferalCommision($country);
			if($stopped==0)
			{
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
			}
			$c=$_GET['c'];
			header("Location: index.php?p=49&c=$c");
			exit;
		}
		function RemoveFromCart()
		{
			global $session;
			$_POST = sanitize_custom($_POST);
			$result=$session->RemoveFromCart($_POST['Cartid']);
			if($result){
				header("Location: index.php?p=75&s=1");
			}
			else{
				header("Location: index.php?p=75&s=0");
			}
			exit;
		}
		function ProcessCart()
		{
			global $session;
			$_POST = sanitize_custom($_POST);
			$result=$session->ProcessMyCart($session->userid, $_POST['paypal_donation']);
			Logger("ProcessCart PayNow \n".serialize($result)."session lender bid success \n".$_SESSION['lender_bid_success1']);
			if(isset($_SESSION['lender_bid_success1']) && isset($result['borrowerid'])) {
				$loanprurl = getLoanprofileUrl($result['borrowerid'],$result['loanid']);
				header("Location: $loanprurl#e5");
			}else if(isset($_SESSION['gifcardids'])){
				header("Location: index.php?p=28");
			}else {
				header("Location: index.php?p=75");
			}
			exit;
		}
		function commentcredit()
		{	
			global $session, $form;
			$_POST = sanitize_custom($_POST);
			if($_POST['type'] == 1) {
				$redirect_to=77;
				$result=$session->saveCreditSetting($_POST['country'], $_POST['loanamtlimit'], $_POST['charlimit'], $_POST['commentlimit'], $_POST['type']);
			}else if($_POST['type'] == 2) {
				$redirect_to = 78;
				$result=$session->saveCreditSetting($_POST['country'], $_POST['loanamtlimit'], '', '', $_POST['type']);
			}else if($_POST['type'] == 3) {
				$result=$session->saveCreditSetting($_POST['country'], $_POST['loanamtlimit'], '', '', $_POST['type']);
			}
			if($result==0){
				echo 0;
			}
			if($result==1){
				echo 1;
			}
		}
	function SubCreateLenderGroup() {
		global $session, $form;
		$id = 0;
		$name=$_POST['group_name'];
		$website=$_POST['website'];
		$about_grp=$_POST['about_group'];
		if(!empty($session->userid)) {
			$gid= $session->createlendergroup($name, $website, $about_grp, $session->userid, $session->userid, $_FILES); 
			if($gid==0) {
				$_SESSION['value_array']=$_POST;
				$_SESSION['error_array']=$form->getErrorArray();
				header("Location: index.php?p=81");
				exit;
			}
		}else {
			$_SESSION['usernotloggedin']=true;
		}
		header("Location: index.php?p=82&gid=$gid");
		exit;
	}

//added by Julia 6-11-2013

	function SubCreateBGroup() {
		global $session, $form;
       		$id = 0;
		$name=$_POST['group_name'];
		$website=$_POST['website'];
		$about_grp=$_POST['about_group'];
		$member_name1=$_POST['member_name1'];
		$member_email1=$_POST['member_email1'];
		$member_name2=$_POST['member_name2'];
		$member_email2=$_POST['member_email2'];
		$member_name3=$_POST['member_name3'];
		$member_email3=$_POST['member_email3'];
		$member_name4=$_POST['member_name4'];
		$member_email4=$_POST['member_email4'];
		$member_name5=$_POST['member_name5'];
		$member_email5=$_POST['member_email5'];
		$member_name6=$_POST['member_name6'];
		$member_email6=$_POST['member_email6'];
		$member_name7=$_POST['member_name7'];
		$member_email7=$_POST['member_email7'];
		$member_name8=$_POST['member_name8'];
		$member_email8=$_POST['member_email8'];
		$member_name9=$_POST['member_name9'];
		$member_email9=$_POST['member_email9'];
		$member_name10=$_POST['member_name10'];
		$member_email10=$_POST['member_email10'];
		if(!empty($session->userid)) {
			// changes by julia : updated by mohit  on date 07-11-13  
                        $gid= $session->createbgroup($name, $website, $about_grp, $member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10, $member_email10, $session->userid, $session->userid, $_FILES); 
			if($gid==0) {
				$_SESSION['value_array']=$_POST;
				$_SESSION['error_array']=$form->getErrorArray();
				header("Location: index.php?p=105");
				exit;
			}
		}else {
			$_SESSION['usernotloggedin']=true;
		}
		header("Location: index.php?p=107&gid=$gid");
		exit;
	}



	function joinLendingGroup() {
		global $session, $form;
		$grpid = $_POST['groupid'];
		if(!empty($session->userid)) {
		$result= $session->joinLendingGroup($grpid, $session->userid); 
			if($result==2) {
				$_SESSION['alreadyjoined']=true;	
			}else if($result==3) {
				$_SESSION['notlender']=true;
			}
		} else {
			$_SESSION['usernotloggedin']=true;
		}
		header("Location: index.php?p=82&gid=$grpid");
		exit;
	}
	function leavegroup() {
		global $session, $form;
		$grpid = $_POST['groupid'];
		if(!empty($session->userid)) {
		$result= $session->leavegroup($grpid, $session->userid); 
			if($result==2) {
				$_SESSION['alreadyjoined']=true;	
			}else if($result==3) {
				$_SESSION['notlender']=true;
			}
		} else {
			$_SESSION['usernotloggedin']=true;
		}
		$url=$_SERVER['HTTP_REFERER'];
		header("Location: $url");
        exit;
	}
	function transffer_leadership() {
		global $session, $form;
		$grpid = $_POST['groupid'];
		if(!empty($session->userid)) {
			$result= $session->transffer_leadership($grpid, $_POST['selectleader']); 
		} 
		$url=$_SERVER['HTTP_REFERER'];
		header("Location: $url");
		exit;
	}
	function updateGrpmsgNotify() {
		global $session, $form;
		$value = $_POST['value'];
		$result= $session->updateGrpmsgNotify($_POST['grpid'],$value, $session->userid); 
		if($result) {
			echo 1;
		}else {
			echo 0;
		}
	}

	function grantAccessCo(){ 
		global $session, $form, $database;
		$country=$_POST['c'];
		$brwrid=$_POST['brwrbycountry'];
		$result= $session->isBorrowerAlreadyAccess($brwrid);
		if($result){
			$url=$_SERVER['HTTP_REFERER'];
			header("Location: $url" );
			exit;	
		}
		else{
		$res= $session->grantAccessCo($brwrid);
			if($res){
				$url=$_SERVER['HTTP_REFERER'];
				header("Location: $url" );
				exit;
			}
		}
	}
	function grantRemoveCo(){
		
		global $session, $form, $database;
		$borrowerid= $_POST['grant_remove_co'];
		$res= $session->grantRemoveCo($borrowerid);
		if($res){
			$url=$_SERVER['HTTP_REFERER'];
			header("Location: $url" );
			exit;
		}
	}
	function getloansbycountry() {
		global $session, $form, $database;
		$country= $_POST['loanbycntry'];
		$res= $database->getloansbycountry($country);
		$options = '';
		if(!empty($res))
			{
				foreach($res as $result)
				{	
					$city = '';
					if(!empty($result['City'])) {
						$city = " (".$result['City'].")";
					}
					$options.=  "<option value='".$result['loanid']."'>".htmlentities($result['FirstName']." ".$result['LastName'].$city)."</option>";
				}
			 }
		echo "<select name='loan_id' style='min-width: 300px'>$options</select>";
	}
	function review_borrower() {
		global $session;
		if(!isset($_POST['is_photo_clear'])) {
			$_POST['is_photo_clear']='0';
		}
		if(!isset($_POST['is_desc_clear'])) {
			$_POST['is_desc_clear']='0';
		}
		if(!isset($_POST['is_addr_locatable'])) {
			$_POST['is_addr_locatable']='0';
		}
		if(!isset($_POST['is_number_provided'])) {
			$_POST['is_number_provided']='0';
		}
		if(!isset($_POST['is_pending_mediation'])) {
			$_POST['is_pending_mediation']='0';
		}
		$res = $session->review_borrower($_POST['borrowerid'], $_POST['is_photo_clear'], $_POST['is_desc_clear'], $_POST['is_addr_locatable'], $_POST['is_number_provided'], $_POST['is_photo_clear_other'],$_POST['is_desc_clear_other'],$_POST['is_addr_locatable_other'],$_POST['is_number_provided_other'],$_POST['is_pending_mediation'],$_POST['is_pending_mediation_other']);

		header("location: index.php?p=7&id=".$_POST['borrowerid']."#review_message");
		exit;

	}
	function verify_borrower() { 
		global $session, $form;
		$_POST_ORG= $_POST;
		if(!isset($_POST['is_identity_verify'])){
			$_POST['is_identity_verify']= '';
		}
		if(!isset($_POST['is_participate_verification'])){
			$_POST['is_participate_verification']= '';
		}
		if(!isset($_POST['is_app_know_zidisha'])){
			$_POST['is_app_know_zidisha']= '';
		}
		if(!isset($_POST['is_how_contact'])){
			$_POST['is_how_contact']= '';
		}
		if(!isset($_POST['is_recomnd_addr_locatable'])){
			$_POST['is_recomnd_addr_locatable']= '';
		}
		if(!isset($_POST['is_commLead_know_applicant'])){
			$_POST['is_commLead_know_applicant']= '';
		}
		if(!isset($_POST['is_commLead_recomnd_sign'])){
			$_POST['is_commLead_recomnd_sign']= '';
		}
		if(!isset($_POST['is_commLead_mediate'])){
			$_POST['is_commLead_mediate']= '';
		}
		if(!isset($_POST['is_eligible'])){
			$_POST['is_eligible']= '';
		}
		$res = $session->verify_borrower($_POST['is_identity_verify'], $_POST['is_identity_verify_other'], $_POST['is_participate_verification'], $_POST['is_participate_verification_other'], $_POST['is_app_know_zidisha'], $_POST['is_app_know_zidisha_other'], $_POST['is_how_contact'], $_POST['is_how_contact_other'], $_POST['is_recomnd_addr_locatable'], $_POST['is_recomnd_addr_locatable_other'], $_POST['is_commLead_know_applicant'], $_POST['is_commLead_know_applicant_other'] , $_POST['is_commLead_recomnd_sign'], $_POST['is_commLead_recomnd_sign_other'], $_POST['is_commLead_mediate'], $_POST['is_commLead_mediate_other'], $_POST['is_eligible'], $_POST['additional_comments'], $_POST['borrowerid'], $_POST['submit_bverification'], $_POST['complete_later'], $_POST['verifier_name_intrvw']); 
		if($res==0){
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray(); 
			$_SESSION['display_verification'] = true; 
			$errurl = 'index.php?p=7&id='.$_POST["borrowerid"];
			if(!empty($_SESSION['error_array']['is_identity_verify']) || !empty($_SESSION['error_array']['is_identity_verify_other'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_identity_verify_err";
			}
			elseif(!empty($_SESSION['error_array']['is_participate_verification']) || !empty($_SESSION['error_array']['is_participate_verification_other'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_participate_verification_err";
			}
			elseif(!empty($_SESSION['error_array']['is_app_know_zidisha']) || !empty($_SESSION['error_array']['is_app_know_zidisha_other'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_app_know_zidisha_err";
			}
			elseif(!empty($_SESSION['error_array']['is_how_contact']) || !empty($_SESSION['error_array']['is_how_contact_other'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_how_contact_err";
			}
			elseif(!empty($_SESSION['error_array']['is_recomnd_addr_locatable']) || !empty($_SESSION['error_array']['is_recomnd_addr_locatable_other'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_recomnd_addr_locatable_err";
			}
			elseif(!empty($_SESSION['error_array']['is_commLead_know_applicant']) || !empty($_SESSION['error_array']['is_commLead_know_applicant_other'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_commLead_know_applicant_err";
			}
			elseif(!empty($_SESSION['error_array']['is_commLead_recomnd_sign']) || !empty($_SESSION['error_array']['is_commLead_recomnd_sign_other'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_commLead_recomnd_sign_err";
			}
			elseif(!empty($_SESSION['error_array']['is_commLead_mediate']) || !empty($_SESSION['error_array']['is_commLead_mediate_other'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_commLead_mediate_err";
			}
			elseif(!empty($_SESSION['error_array']['is_eligible'])) {
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"]."#is_eligible_err";
			}
			header("Location: $errurl");
			exit;
		}elseif($res==2){
			$_SESSION['bverification_comlater']= true;
			header("Location:./index.php?p=7&id=".$_POST['borrowerid']."#changeSaved");
			exit;
		}else{
			header("Location:./index.php?p=7&s=1");
			exit;
		}
		
	}
	function remove_payment() {
		global $session;
		if($session->userlevel==ADMIN_LEVEL) {
			$res = $session->remove_payment($_POST['payment_id']);
			if(!$res) {
				$_SESSION['repayment_not_removed'] = 1;
			}else {
				$_SESSION['repayment_removed'] = 1;
			}
		}else {
			$_SESSION['not_athorized'] = 1;
		}
		header("location: index.php?p=86");
		exit;
	}
	function co_org_note(){
		global $session;
		$id= $_POST['id'];
		$note= $_POST['note'];
		$result= $session->co_org_note($id, $note);
		if($result === 1) {
			echo 1;
		}
		else
			echo 0;
	}
	function getactiveborrowers(){
		global $session, $form, $database;
		$country= $_POST['activeborrowers'];
		$res= $database->getActiveBorrowersByCountry($country); 
		$options = '';
		if(!empty($res))
			{
			foreach($res as $result)
				{	
					$city = '';
					if(!empty($result['City'])) {
						$city = $result['City'];
					}
					if(!empty($result['TelMobile'])) {
						$TelMobile = $result['TelMobile'];
					}
					$detail=$result['FirstName']." ".$result['LastName'].' ('.$city.", tel ".$TelMobile.')';
					$options.=  "<option value='".$result['userid']."'>".$detail."</option>";
				}
			 } 

		echo "<option value='0'>None</option>$options";
		exit;
	}
	function getActiveCoOrgUsers(){
		global $session, $form, $database;
		$country= $_POST['volunteer_mentor'];
		$res= $database->getActiveCoOrgUsers($country); 
		$options = '';
		if(!empty($res))
			{
				
				foreach($res as $key => $result)
				{	
					$row= $database->getUserById($result['user_id']);
					$city = '';
					$TelMobile='';
					if(!empty($row['City'])) {
						$res[$key]['City'] = $row['City'];
					}else {
						$res[$key]['City'] = '';
					}
					if(!empty($row['TelMobile'])) {
						$res[$key]['TelMobile'] = $row['TelMobile'];
					}else {
						$res[$key]['TelMobile']='';
					}

				}
				$res = array_sort($res,'City', 'SORT_ASC', 'country');
				foreach($res as $result)
				{	
					$row= $database->getUserById($result['user_id']);
					$city = $result['City'];
					$TelMobile=$result['TelMobile'];
					$options.=  "<option value='".$result['user_id']."'>".htmlentities($city.': '.$row['name'].', tel '.$TelMobile)."</option>";
				}
				echo "$options";
			 }
		else{
			echo "<option value=''>None</option>";
		}
	}

	function verify_borrower_ByAdmin(){ 
		global $session, $form, $database;
		if(!isset($_POST['is_eligible_ByAdmin'])){
			$_POST['is_eligible_ByAdmin']='';
		}
		$res= $session->verify_borrower_ByAdmin($_POST['is_eligible_ByAdmin'], $_POST['eligible_no_reason'], $_POST['borrowerid'], $_POST['submit_bverification_ByAdmin'], $_POST['verifier_name']);
		if($res==0){
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray(); 
			if(isset($_SESSION['Declined'])){
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"];
			}else{
				$errurl = 'index.php?p=7&id='.$_POST["borrowerid"].'#is_eligible_ByAdminerr';
			}
			header("Location: $errurl");
			exit;
		}else{
			header("Location:./index.php?p=7&s=1");
			exit;
		}
	
	}

	function sendJoinshareEmail(){
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$ret = $session->sendJoinshareEmail($_POST["to_email"],$_POST["note"], $_POST["email_sub"], $_POST["sendme"]);
		$url = $_SERVER['HTTP_REFERER'];
		if($ret==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
		}else{
			$_SESSION['ShareEmailSent']=true;
		}
		$_SESSION['shareEmailValidate']=($_POST["formbidpos"]==1) ? 1:2;
		header("Location: $url");
		exit;
	}
	function donate(){
		global $session, $form;
		$result=$session->donate($_POST['donation'], time());
		if($result==0){
			$url = $_SERVER['HTTP_REFERER'];
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: $url");
			exit;
		}else{
			header("Location: index.php?p=75");
			exit;
		}
	}
	function getVolunteersByCity(){
		global $database;
		$city=$_POST['vm_city'];
		$volunteers= $database->getVolunteersByCity($city);
		$options='';
		foreach($volunteers as $key=>$row)
		{	
			$name= $database->getNameById($key);
			$options.=  "<option value='".$key."'>".$name."</option>";
		}
		echo "$options";
	}
	function savedisbursednote(){
		global $database;
		$note=$database->savedisbursednote($_POST['userid'], $_POST['loanid'], $_POST['note']);
		echo $note;
	}

	//  for authorization on pendinf disbursement by Mohit
	function makeConfirmAuth()
	{
	global $session, $database;
	
	$_POST = sanitize_custom($_POST);
		
		$date_auth = strtotime($_POST['auth_date']);
		
		if(empty($date_auth)) {
			$date_auth = time();
		}
		
		$result=$database->updateAuthStatus($date_auth,$_POST['borrowerid'], $_POST['loanid'],$_POST['pamount']);
	
		$url=$_SERVER['HTTP_REFERER'];
		
		if($result==1){
			$database->commitTxn();
			header("Location: $url");
			exit;
		}
		else{
			$database->rollbackTxn();
			header("Location: $url&err=1001");
			exit;
		}

	}
// Added by mohit 24-10-13 to save repayment report notes
	public function saveRepayReport()
	{
		global $session;
		$date = strtotime($_POST["date"]);
		$result=$session->saveRepayReport($_POST["q"],$_POST["name"],$_POST["number"],$date,$_POST["note"],$_POST["borrowerid"],$_POST["loanid"],$_POST["isedit"],$_POST["mentor"]);
		echo $result;
	} // end here
        //
// added by mohit 12-11-13  Option to filter results by Volunteer Mentor / staff member assigned     
        public function getVolMentStaffMemList()
        {   
            global $session;
            $result=$session->getVolMentStaffMemList($_POST['cid'],$_POST['assignedto']);
            echo $result;
        }

};
$process=new Process;
?>
