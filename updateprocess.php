<?php
include_once("library/session.php");
class updateProcess
{
	function updateProcess()
	{
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
		if(isset($_POST["editborrower"])){
			$this->subEditBorrower();
		}
		else if(isset($_POST["editlender"])){
			$this->subEditLender();
		}
		else if(isset($_POST['editpartner'])){
			$this->subEditPartner();
		}
		else if(isset($_POST['acceptbids'])){
			$this->acceptBids();
		}
		else if(isset($_POST['Payment'])){
			$this->madePayment();
		}
		else if(isset($_POST['repaymentfeedback'])){
			$this->repaymentfeedback();
		}
		else if(isset($_POST['makeLoanDefault'])){
			$this->makeLoanDefault();
		}
		else if(isset($_POST['makeLoanUndoDefault'])){
			$this->makeLoanUndoDefault();
		}
		else if(isset($_POST['cancelloan'])){
			$this->makeLoanCancel();
		}
		else if(isset($_POST['forgetpassword'])){
			$this->forgetpassword();
		}
		else if(isset($_POST['withdraw'])){
			$this->withdraw();
		}
		else if(isset($_POST['paywithdraw'])){
			$this->paywithdraw();
		}
		else if(isset($_POST['PaySimplewithdraw'])){
			$this->paySimplewithdraw();
		}
		else if(isset($_POST['paysimplewithdrawadmin'])){
			$this->paysimplewithdrawadmin();
		}
		else if(isset($_POST['Otherwithdraw'])){
			$this->otherwithdraw();
		}
		else if(isset($_POST['payotherwithdrawadmin'])){
			$this->payotherwithdrawadmin();
		}
		else if(isset($_POST['emailregister'])){
			$this->registerEmail();
		}
		else if(isset($_POST['emailsent'])){
			$this->registerEmailSent();
		}
		else if(isset($_POST['portfolioreport']) || isset($_POST['portfolioreportnew'])){
			$this->pfreport();
		}
		//no longer used - replaced with tmp_trhistory 12-12-2013
		else if(isset($_POST['transactionhistory'])){
			$this->trhistory();
		}
		//no longer used - replaced with tmp_trhistorySummary 12-12-2013
		else if(isset($_POST['transactionhistorySummary'])){
			$this->trhistorySummary();
		}
		else if(isset($_POST['tr_hidden'])){
			$this->getTranslate();
		}
		else if(isset($_POST['translatorhidden'])){
			$this->changeTranslator();
		}
		else if(isset($_POST['translatorlang'])){
			$this->changeTranslatorLang();
		}
		else if(isset($_POST['giftcardorder'])){
			$this->giftCardOrder();
		}
		else if(isset($_POST['redeemCard'])){
			$this->redeemCard();
		}
		else if(isset($_POST['donate_card'])){
			$this->donate_card();
		}
		else if(isset($_POST['promotLoan'])){
			$this->promotLoan();
		}
		else if(isset($_POST['invite_frnds'])){
			$this->invite_frnds();
		}
		else if(isset($_POST['get_contacts'])){
			$this->get_contacts();
		}
		else if(isset($_POST['get_loans'])){
			$this->get_loans();
		}
		else if(isset($_POST['repay_report'])){
			$this->repay_report();
		}
		else if(isset($_POST['declinedBorrower'])){
			$this->declinedBorrower();
		}
		else if(isset($_POST['reScheduleLoan'])){
			$this->reScheduleLoan();
		}
		else if(isset($_POST['update-repayment_instruction'])){
			$this->updateRePaymentInstruction();
		}
		else if(isset($_POST['del-repayment_instruction'])){
			$this->deleteRePaymentInstruction();
		}
		else if(isset($_POST['update-campaign'])){
			$this->updateCampaign();
		}
		else if(isset($_POST['del-campaign'])){
			$this->deletecampaign();
		}
		else if(isset($_POST['outstandingReport'])){
			$this->outstandingReport();
		}
		else if(isset($_POST['updatelendergroup'])){
			$this->updatelendergroup();
		}
		else if(isset($_POST['sendconfirmagain'])){
			$this->SendConfirmaEmailAgain();
		}
		else if(isset($_POST['binvite_frnd'])){
			$this->binvite_frnd();
		}
		else if(isset($_POST['resendendorsermail'])){
			$this->resendEndorsermail();
		}
		else if(isset($_POST['facebook_info'])){
			$this->facebook_info();
		}
		else if(isset($_POST['disbursement_report'])){
			$this->disbursement_report();
		}
		else if(isset($_POST['find_borrower'])){
			$this->find_borrower();
		}
//added by Julia 21-10-2013
		else if(isset($_POST['find_lender'])){
			$this->find_lender();
		}
		else if(isset($_POST['getAllborrowers'])){
			$this->getAllborrowers();
		}
//added by Julia 3-11-2013
		else if(isset($_POST['find_lenderforstaff'])){
			$this->find_lenderforstaff();
		}
// added by mohit 7-11-2013                
                else if(isset($_POST['updatebgroup'])){
			$this->updatebgroup();
		}
//added by Julia 22-11-2013
		else if(isset($_POST['activation_rate'])){
			$this->activation_rate();
		}
		else if(isset($_POST['repayment_rate'])){
			$this->repayment_rate();
		}
        else if(isset($_POST['tmp_transactionhistory'])){
			$this->tmp_trhistory();
		}
		else if(isset($_POST['tmp_transactionhistorySummary'])){
			$this->tmp_trhistorySummary();
		}
		if(isset($_POST["additional_verification"])){
			$this->additional_verification();
		}
		if(isset($_POST["sharebox_off"])){
			$this->sharebox_off();
		}
		else if(isset($_POST['loans_funded'])){
			$this->loans_funded();
		}
		

	}
	function subEditLender()
	{
		global $session, $form;
		$id=$session->userid;
		$_POST_ORG=$_POST;
		if(!isset($_POST['llname']) || empty($_POST['llname']))
			$_POST['llname']='';
		if(!isset($_POST['lwebsite']) || empty($_POST['lwebsite']))
			$_POST['lwebsite']='';
		
		$_POST = sanitize_custom($_POST);
		$result=$session->editprofile_l($_POST["lusername"], $_POST["lpass1"], $_POST["lpass2"], $_POST["lemail"], $_POST["lfname"], $_POST["llname"], $_POST["labout"], $_POST["lphoto"], $_POST["lcity"], $_POST["lcountry"], $_POST["hide_Amount"], $_POST["postcomment"], $id, $_POST["loan_app_notify"], $_POST["loan_repayment_credited"], $_POST["subscribe_newsletter"], $_POST['lwebsite']);

		if($result==0)
		{
			if(is_uploaded_file($_FILES['lphoto']['tmp_name']))
			{
				$img_file = $_FILES['lphoto']['tmp_name'];
				$ext = split( '/', $_FILES['lphoto']['type'] );
				imageUpload($img_file, $ext, $id);
			}
			//Anupam 12-4-2012 changes under url rewrite
			$prurl = getUserProfileUrl($id);
			header("Location: $prurl");
		}
		else
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=13&err=1006");
		}
	}
	function subEditPartner()
	{
		global $session, $form;
		$id=$session->userid;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);

		$result=$session->editprofile_p($_POST['pusername'], $_POST['ppass1'], $_POST['ppass2'], $_POST['pname'], $_POST['paddress'], $_POST['pcity'], $_POST['pcountry'], $_POST['pemail'], $_POST['emails_notify'], $_POST['pwebsite'], $_POST['postcomment'], $_POST['pdesc'], $id, $_POST["labellang"]);
		if($result)
		{
			if(is_uploaded_file($_FILES['pphoto']['tmp_name']))
			{
				$img_file = $_FILES['pphoto']['tmp_name'];
				$ext = split( '/', $_FILES['pphoto']['type'] );
				imageUpload($img_file, $ext, $id);
			}
			$prurl = getUserProfileUrl($id);
			if($_POST["labellang"] !="en")
				$url= SITE_URL.$_POST["labellang"].$prurl;
			else
				$url= SITE_URL.$prurl;;
			header("Location: $url");
		}
		else
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=13&err=1008");
		}
	}
	function subEditBorrower()
	{	
		global $session, $form;
		$id=$session->userid;
		$_POST_ORG=$_POST;
		//Logger_Array("FB LOG - updateprocess start",'fb_data', serialize($_POST['fb_data']).$_POST["busername"]);
		$_POST = sanitize_custom($_POST);
		for($i=1; $i<=10; $i++){
			$endorser_name[]= $_POST['endorser_name'.$i];
			$endorser_email[]= $_POST['endorser_email'.$i];
			$endorser_id[]= $_POST['endorser_id'.$i];
		}
		if($_POST['before_fb_data']=='1'){
			$_SESSION['fb_data']= $_POST;
			header('Location: index.php?p=13&fb_data=1#FB_cntct');
		}else{
		
			if(isset($_FILES['front_national_id']['tmp_name']) && !is_uploaded_file($_FILES['front_national_id']['tmp_name']) && !empty($_POST['isFrntNatid'])) {
				$_FILES['front_national_id']['tmp_name'] = $_POST['isFrntNatid'];
				$_FILES['front_national_id']['name'] = end(explode("/",$_POST['isFrntNatid']));
			}
			if(isset($_FILES['back_national_id']['tmp_name']) && !is_uploaded_file($_FILES['back_national_id']['tmp_name']) && !empty($_POST['isbcktnatid'])) {
				$_FILES['back_national_id']['tmp_name'] = $_POST['isbcktnatid'];
				$_FILES['back_national_id']['name'] = end(explode("/",$_POST['isbcktnatid']));
			}
			if(isset($_FILES['address_proof']['tmp_name']) && !is_uploaded_file($_FILES['address_proof']['tmp_name']) && !empty($_POST['isaddrprf'])) {
				$_FILES['address_proof']['tmp_name'] = $_POST['isaddrprf'];
				$_FILES['address_proof']['name'] = end(explode("/",$_POST['isaddrprf']));
			}
			if(isset($_FILES['legal_declaration']['tmp_name']) && !is_uploaded_file($_FILES['legal_declaration']['tmp_name']) && !empty($_POST['islgldecl'])) {
				$_FILES['legal_declaration']['tmp_name'] = $_POST['islgldecl'];
				$_FILES['legal_declaration']['name'] = end(explode("/",$_POST['islgldecl']));
			}
			if(isset($_FILES['legal_declaration2']['tmp_name']) && !is_uploaded_file($_FILES['legal_declaration2']['tmp_name']) && !empty($_POST['islgldecl2'])) {
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
			
			if (!empty($_POST["uploadfileanchor"])) {
				$result = 2;
			}else{ 
				Logger_Array("FB LOG - updateprocess",'fb_data', serialize($_POST['fb_data']).$_POST["busername"]);
				$result = $session->editprofile_b($_POST["busername"], $_POST["bfname"], $_POST["blname"], $_POST["bpass1"], $_POST["bpass2"], $_POST["bpostadd"], $_POST["bcity"], $_POST["bcountry"], $_POST["bemail"], $_POST["bmobile"], $_POST["reffered_by"],$_POST["bincome"], $_POST["babout"], $_POST["bbizdesc"], $photo, $id, $_POST["bnationid"], $_POST["labellang"], $_POST["community_name_no"], $_FILES, $_POST["abletocomplete"], $_POST["repaidpast"], $_POST["debtfree"], $_POST["share_update"], $_POST["borrower_behalf"], $_POST["behalf_name"], $_POST["behalf_number"], $_POST["behalf_email"],$_POST["behalf_town"],$_POST["borrower_behalf_id"],$_POST['submitform'], $_POST['uploadedDocs'], $_POST['bfamilycont1'],$_POST['bfamilycont2'],$_POST['bfamilycont3'], $_POST['bneighcont1'],$_POST['bneighcont2'],$_POST['bneighcont3'], $_POST['home_no'], $_POST['rec_form_offcr_name'], $_POST['rec_form_offcr_num'],$_POST['refer_member'], $_POST['volunteer_mentor'], $_POST['cntct_type'], $_POST['fb_data'], $endorser_name, $endorser_email, $endorser_id); 
			}
			if($result==0)
			{
				require("editables/register.php");
				$path=  getEditablePath('register.php');
				require ("editables/".$path);
				if($_POST['submitform'] != trim($lang['register']['RegisterComplete'])){
					$_SESSION['bedited'] = true;
				}
				if(is_uploaded_file($_FILES['bphoto']['tmp_name']))
				{	
					$img_file = $_FILES['bphoto']['tmp_name'];
					$ext = split( '/', $_FILES['bphoto']['type'] );
					imageUpload($img_file, $ext, $id);
				}
				else if(!empty($_POST['isPhoto_select']))
				{ 
					$img_file =TMP_IMAGE_DIR.$_POST['isPhoto_select'];
					$ext[1] = end(explode(".", $img_file));
					imageUpload($img_file, $ext, $id);	
				}
				if(isset($_POST["labellang"]) && $_POST["labellang"] !="en")
					$url= SITE_URL.$_POST["labellang"]."/index.php?p=13";
				else if(isset($_GET["language"])) {
					$language = $_GET["language"];
					$url= SITE_URL.$language."/index.php?p=13";
				}else 
					$url= SITE_URL."index.php?p=13";

				if($_POST['submitform'] == trim($lang['register']['RegisterComplete'])) {
					$url= SITE_URL."index.php?p=50";
				}
				header("Location: $url");
			}
			else 
			{
				$_SESSION['value_array'] = $_POST_ORG;
				$_SESSION['error_array'] = $form->getErrorArray();
				$errurl1 = $_SERVER['HTTP_REFERER'];
				if(strstr($errurl1, "fb_join")){
					$errurl= $errurl1;
				}else{
					$errurl= $errurl1."&fb_join=1";
				}
				$supported=array("image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", "application/pdf");
				if(isset($_FILES['bphoto']['type'])){
					$phototype = $_FILES['bphoto']['type'];
				}
				if(isset($_FILES['front_national_id']['type'])){
					$frntidtype = $_FILES['front_national_id']['type'];
				}
				if(isset($_FILES['back_national_id']['type'])){
					$bkidtype = $_FILES['back_national_id']['type'];
				}
				if(isset($_FILES['address_proof']['type'])){
					$addrsype = $_FILES['address_proof']['type'];
				}
				if(isset($_FILES['legal_declaration']['type'])){
					$legalype = $_FILES['legal_declaration']['type'];
				}
				if(isset($_FILES['legal_declaration2']['type'])){
					$legl2type = $_FILES['legal_declaration2']['type'];
				}
				if(isset($_FILES['bphoto']['tmp_name']) && !empty($_FILES['bphoto']['tmp_name']) && in_array($phototype, $supported))
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
				if(isset($_FILES['front_national_id']['tmp_name']) && !empty($_FILES['front_national_id']['tmp_name']) && in_array($frntidtype, $supported))
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
				if(isset($_FILES['back_national_id']['tmp_name']) && !empty($_FILES['back_national_id']['tmp_name']) && in_array($bkidtype, $supported))
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

				if(isset($_FILES['address_proof']['tmp_name']) && !empty($_FILES['address_proof']['tmp_name']) && in_array($addrsype, $supported))
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
				if(isset($_FILES['legal_declaration']['tmp_name']) && !empty($_FILES['legal_declaration']['tmp_name']) && in_array($legalype, $supported))
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
				if(isset($_FILES['legal_declaration2']['tmp_name']) && !empty($_FILES['legal_declaration2']['tmp_name']) && in_array($legl2type, $supported)) {
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
				if($result==1) {
						if(!empty($_SESSION['error_array']['repaidpast'])) {
							$errurl = 'index.php?p=13'."#repaidpasterr";
						} else if(!empty($_SESSION['error_array']['debtfree'])) {
							$errurl = 'index.php?p=13'."#debtfreeerr";
						}else if(!empty($_SESSION['error_array']['share_update'])) {
							$errurl = 'index.php?p=13'."#share_updateerr";
						}else if(!empty($_SESSION['error_array']['behalf_name'])) {
							$errurl = 'index.php?p=13'."#behalf_nameerr";
						}else if(!empty($_SESSION['error_array']['behalf_number'])) {
							$errurl = 'index.php?p=13'."#behalf_numbererr";
						}else if(!empty($_SESSION['error_array']['behalf_email'])) {
							$errurl = 'index.php?p=13'."#behalf_emailerr";
						}else if(!empty($_SESSION['error_array']['behalf_town'])) {
							$errurl = 'index.php?p=13'."#behalf_townerr";
						}else if(!empty($_SESSION['error_array']['busername'])) {
							$errurl = 'index.php?p=13'."#busernameerr";
						}else if(!empty($_SESSION['error_array']['bpass1'])) {
							$errurl = 'index.php?p=13'."#bpass1err";
						}else if(!empty($_SESSION['error_array']['bfname'])) {
							$errurl = 'index.php?p=13'."#bfnameerr";
						}else if(!empty($_SESSION['error_array']['blname'])) {
							$errurl = 'index.php?p=13'."#blnameerr";
						}else if(!empty($_SESSION['error_array']['bphoto'])) {
							$errurl = 'index.php?p=13'."#bphotoerr";
						}else if(!empty($_SESSION['error_array']['bpostadd'])) {
							$errurl = 'index.php?p=13'."#bpostadderr";
						}else if(!empty($_SESSION['error_array']['bcity'])) {
							$errurl = 'index.php?p=13'."#bcityerr";
						}else if(!empty($_SESSION['error_array']['bcountry'])) {
							$errurl = 'index.php?p=13'."#bcountryerr";
						}else if(!empty($_SESSION['error_array']['bnationid'])) {
							$errurl = 'index.php?p=13'."#bnationiderr";
						}/*else if(!empty($_SESSION['error_array']['bloanhist'])) {
							$errurl = 'index.php?p=13'."#bloanhisterr";
						}*/else if(!empty($_SESSION['error_array']['bemail'])) {
							$errurl = 'index.php?p=13'."#bemailerr";
						}else if(!empty($_SESSION['error_array']['bmobile'])) {
							$errurl = 'index.php?p=13'."#bmobileerr";
						}else if(!empty($_SESSION['error_array']['babout'])) {
							$errurl = 'index.php?p=13'."#babouterr";
						}else if(!empty($_SESSION['error_array']['bbizdesc'])) {
							$errurl = 'index.php?p=13'."#bbizdescerr";
						}else if(!empty($_SESSION['error_array']['referrer'])) {
							$errurl = 'index.php?p=13'."#referrererr";
						}else if(!empty($_SESSION['error_array']['front_national_id'])) {
							$errurl = 'index.php?p=13'."#front_national_iderr";
						}else if(!empty($_SESSION['error_array']['back_national_id'])) {
							$errurl = 'index.php?p=13'."#back_national_iderr";
						}else if(!empty($_SESSION['error_array']['address_proof'])) {
							$errurl = 'index.php?p=13'."#address_prooferr";
						}else if(!empty($_SESSION['error_array']['legal_declaration'])) {
							$errurl = 'index.php?p=13'."#legal_declarationerr";
						}else if(!empty($_SESSION['error_array']['home_no'])) {
							$errurl = 'index.php?p=13'."#home_noerr";
						}/*else if(!empty($_SESSION['error_array']['lending_institution'])) {
							$errurl = 'index.php?p=13'."#lending_institutionerr";
						}else if(!empty($_SESSION['error_array']['lending_institution_add'])) {
							$errurl = 'index.php?p=13'."#lending_institution_adderr";
						}else if(!empty($_SESSION['error_array']['lending_institution_phone'])) {
							$errurl = 'index.php?p=13'."#lending_institution_phoneerr";
						}else if(!empty($_SESSION['error_array']['lending_institution_officer'])) {
							$errurl = 'index.php?p=13'."#lending_institution_officererr";
						}*/else if(!empty($_SESSION['error_array']['bfamilycont1'])) {
							$errurl = 'index.php?p=13'."#bfamilycontact1";
						}else if(!empty($_SESSION['error_array']['bfamilycont2'])) {
							$errurl = 'index.php?p=13'."#bfamilycontact2";
						}else if(!empty($_SESSION['error_array']['bfamilycont3'])) {
							$errurl = 'index.php?p=13'."#bfamilycontact3";
						}else if(!empty($_SESSION['error_array']['bneighcont1'])) {
							$errurl = 'index.php?p=13'."#bneighcontact1";
						}else if(!empty($_SESSION['error_array']['bneighcont2'])) {
							$errurl = 'index.php?p=13'."#bneighcontact2";
						}else if(!empty($_SESSION['error_array']['bneighcont3'])) {
							$errurl = 'index.php?p=13'."#bneighcontact3";
						}else if(!empty($_SESSION['error_array']['refer_member'])) {
							$errurl = 'index.php?p=13'."#refer_membererr";
						}else if(!empty($_SESSION['error_array']['volunteer_mentor'])) {
							$errurl = 'index.php?p=13'."#volunteer_mentorerr";
						}


						header("Location: $errurl");
					}else{
						$url = $_SERVER['HTTP_REFERER'];
						if(strstr($url, "fb_join")){
							header("Location: $url".$_POST["uploadfileanchor"]);
						}else{
					// redirect to borrower form after file upload. $_POST["uploadfileanchor"] contains an anchor
						header("Location: $url&fb_join=1".$_POST["uploadfileanchor"]);
						}
					}
				//header('Location: index.php?p=13&err=1007');
			}
		}
	}

	function additional_verification()
	{	
		global $session, $form;
		$id=$session->userid;
		$_POST_ORG=$_POST;
		//Logger_Array("FB LOG - updateprocess start",'fb_data', serialize($_POST['fb_data']).$_POST["busername"]);
		$_POST = sanitize_custom($_POST);
		for($i=1; $i<=10; $i++){
			$endorser_name[]= $_POST['endorser_name'.$i];
			$endorser_email[]= $_POST['endorser_email'.$i];
			$endorser_id[]= $_POST['endorser_id'.$i];
		}
		if($_POST['before_fb_data']=='1'){
			$_SESSION['fb_data']= $_POST;
			header('Location: index.php?p=111&fb_data=1#FB_cntct');
		}else{
		
			if(isset($_FILES['front_national_id']['tmp_name']) && !is_uploaded_file($_FILES['front_national_id']['tmp_name']) && !empty($_POST['isFrntNatid'])) {
				$_FILES['front_national_id']['tmp_name'] = $_POST['isFrntNatid'];
				$_FILES['front_national_id']['name'] = end(explode("/",$_POST['isFrntNatid']));
			}
			
			if(isset($_FILES['address_proof']['tmp_name']) && !is_uploaded_file($_FILES['address_proof']['tmp_name']) && !empty($_POST['isaddrprf'])) {
				$_FILES['address_proof']['tmp_name'] = $_POST['isaddrprf'];
				$_FILES['address_proof']['name'] = end(explode("/",$_POST['isaddrprf']));
			}
			
			if (!empty($_POST["uploadfileanchor"])) {
				$result = 2;
			}else{ 
				Logger_Array("FB LOG - updateprocess",'fb_data', serialize($_POST['fb_data']).$_POST["busername"]);
				$result = $session->additional_verification($id, $_POST["labellang"], $_FILES, $_POST['submitform'], $_POST['uploadedDocs'], $_POST['fb_data'], $endorser_name, $endorser_email, $endorser_id); 
			}
			if($result==0)
			{
				require("editables/register.php");
				$path=  getEditablePath('register.php');
				require ("editables/".$path);
				if($_POST['submitform'] != trim($lang['register']['RegisterComplete'])){
					$_SESSION['bedited'] = true;
				}
				if(isset($_POST["labellang"]) && $_POST["labellang"] !="en")
					$url= SITE_URL.$_POST["labellang"]."/index.php?p=111";
				else if(isset($_GET["language"])) {
					$language = $_GET["language"];
					$url= SITE_URL.$language."/index.php?p=111";
				}else 
					$url= SITE_URL."index.php?p=111";

				if($_POST['submitform'] == trim($lang['register']['RegisterComplete'])) {
					$url= SITE_URL."index.php?p=50";
				}
				header("Location: $url");
			}
			else 
			{
				$_SESSION['value_array'] = $_POST_ORG;
				$_SESSION['error_array'] = $form->getErrorArray();
				$errurl1 = $_SERVER['HTTP_REFERER'];
				if(strstr($errurl1, "fb_join")){
					$errurl= $errurl1;
				}else{
					$errurl= $errurl1."&fb_join=1";
				}
				$supported=array("image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", "application/pdf");
				
				if(isset($_FILES['front_national_id']['type'])){
					$frntidtype = $_FILES['front_national_id']['type'];
				}
				
				if(isset($_FILES['address_proof']['type'])){
					$addrsype = $_FILES['address_proof']['type'];
				}
				
				if(isset($_FILES['front_national_id']['tmp_name']) && !empty($_FILES['front_national_id']['tmp_name']) && in_array($frntidtype, $supported))
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

				if(isset($_FILES['address_proof']['tmp_name']) && !empty($_FILES['address_proof']['tmp_name']) && in_array($addrsype, $supported))
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
				
				if($result==1) {
						if (!empty($_SESSION['error_array']['front_national_id'])) {
							$errurl = 'index.php?p=111'."#front_national_iderr";
						}else if(!empty($_SESSION['error_array']['address_proof'])) {
							$errurl = 'index.php?p=111'."#address_prooferr";
			
						header("Location: $errurl");
					}else{
						$url = $_SERVER['HTTP_REFERER'];
						if(strstr($url, "fb_join")){
							header("Location: $url".$_POST["uploadfileanchor"]);
						}else{
					// redirect to borrower form after file upload. $_POST["uploadfileanchor"] contains an anchor
						header("Location: $url&fb_join=1".$_POST["uploadfileanchor"]);
						}
					}
				}
			}
		}
	}

	function repaymentfeedback()
	{
		global $session, $form;
		                
                $_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		if(!empty($_POST['AddMore']))
			$a=1;
		else if(!empty($_POST['Edit']))
				$a=0;

		$result=$session->repaymentfeedback($_POST['userid'],$_POST['loanid'],$_POST['feedback'],$_POST['comment'],$a,$_POST['commentid']);
		$loanprurl = getLoanprofileUrl($_POST['userid'], $_POST['loanid']);
		if($result==1)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: $loanprurl?cid=".$_POST['commentid']."#e1");
		}
		else if($result==0)
		{
			if($a)
				header("Location: $loanprurl#e1");
			else
				header("Location: $loanprurl#e1");
		}
	}
	function makeLoanDefault()
	{
		global $session,$form;
		$_POST = sanitize_custom($_POST);
		$result=$session->makeLoanDefault($_POST['borrowerid'],$_POST['loanid']);
		if($result==0){
			header("Location: index.php?p=11&a=1&err=1002");
		}
		else{
			header("Location: index.php?p=11&a=1");
		}
	}
	function makeLoanUndoDefault()
	{
		global $session,$form;
		$_POST = sanitize_custom($_POST);
		$result=$session->makeLoanUndoDefault($_POST['borrowerid'],$_POST['loanid']);
		if($result==0){
			header("Location: index.php?p=11&a=1&err=1002");
		}
		else{
			header("Location: index.php?p=11&a=1");
		}
	}
	function makeLoanCancel()
	{
		global $session,$form;
		$_POST = sanitize_custom($_POST);
		$result=$session->makeLoanCancel($_POST['borrowerid'],$_POST['loanid']);
		$loanprurl = getLoanprofileUrl($_POST['borrowerid'],$_POST['loanid']);
		if($result==0){
			header("Location: $loanprurl");
		}
		else{
			header("Location: $loanprurl");
		}
	}
	function forgetpassword()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		if(isset($_POST['forgetusername']))
			$result=$session->forgetpassword($_POST['forgetemail'], $_POST['forgetusername']);
		else
			$result=$session->forgetpassword($_POST['forgetemail']);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=56");
		}
		else if($result==2)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=56&err=1");
		}
		else if($result==1){
			header("Location: index.php?p=56&sel=1");
		}
	}
	function acceptBids()
	{
		global $session, $form, $database;
		$database->startDbTxn();
		$result=$session->acceptBids($_POST['loanid'], $_POST['acceptbid_note']);
		if(!empty($result))
		{
			$r2=$session->processBids($result);
			if($r2)
			{
				$database->commitTxn();
				$session->checkReferralCommission($session->userid, 0);
			}
			else
				$database->rollbackTxn();
		}
		else
			$database->rollbackTxn();
			$_SESSION['value_array']=$_POST;
			$_SESSION['error_array']=$form->getErrorArray();
		header("Location:index.php?p=14&l=".$_POST['loanid']);
	}
	function pfreport()
	{
		global $session, $form;
		$_POST["date2"] = date('m/d/Y',time()); //as we creating pfreport for only current date altough we can post a back date also.
		$result=$session->pfreport($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=23");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			header("Location: index.php?p=23&v=1");
		}
	}
	function trhistory()
	{
		global $session, $form;
		$result=$session->trhistory($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=22");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=22&v=1&ord=ASC&opt=TrDate&c=ALL&type=1");
		}
	}
	//Pranjal Added summary 04 Nov 2013
	function trhistorySummary()
	{
		global $session, $form;
		$result=$session->trhistory($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=108");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=108&v=1&ord=ASC&opt=TrDate&c=ALL&type=1");
		}
	}
	function getTranslate()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$id=$_GET['id'];
		$loanid=$_GET['l_id'];
		$cmntid=$_GET['c_id'];
		$lcid=$_GET['lc_id'];
		$up_id = $_POST["up_id"];
		if($up_id==1)
		{
			$result=$session->getTranslate($_POST['bizdesc'], $_POST['about'], $_POST['loanuse'], 0, $id, $up_id, $loanid);
		}
		if($up_id==2)
		{
			$result=$session->getTranslate(0, 0, 0, $_POST['cmnt'], $cmntid, $up_id, 0);
		}
		if($up_id==3)
		{
			$result=$session->getTranslate(0, 0, 0, $_POST['lncmnt'], 0, $up_id, 0, $lcid);
		}
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=24&id=$id&v=0&up_id=$up_id&l_id=$loanid");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			header("Location: index.php?p=24&id=$id&v=1&up_id=$up_id&l_id=$loanid");
		}
		else if($result==2)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=24&c_id=$cmntid&v=0");
		}
		else if($result==3)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			header("Location: index.php?p=24&c_id=$cmntid&v=1");
		}
		else if($result==4)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=24&lc_id=$lcid&v=0");
		}
		else if($result==5)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			header("Location: index.php?p=24&lc_id=$lcid&v=1");
		}
	}
	function changeTranslator()
	{
		global $database, $form;
		$result= $database->changeTranslator($_POST['uid'], $_POST['active'], $_POST['country'], $search);
		if($result==0)
		{
			$field="activeerr";
			$form->setError($field, "Sorry updation failed");
			header("Location: index.php?p=25&c=".$_POST['country']."&search=".$search."&v=0");
		}
		else
			header("Location: index.php?p=25&c=".$_POST['country']."&search=".$search."&v=1");
	}

	function changeTranslatorLang()
	{
		global $database, $form;
		$_POST = sanitize_custom($_POST);
		$result= $database->changeTranslatorLang($_POST['uid'], $_POST["translang"]);
		if($result==0)
		{
			$field="activeerr";
			$form->setError($field, "Sorry updation failed");
			header("Location: index.php?p=25&v=0");
		}
		else
			header("Location: index.php?p=25&v=1");
	}
	function giftCardOrder()
	{
		global $session;
		//$_POST = sanitize_custom($_POST);
		$cards = count($_POST['giftamt']);
		$result=$session->giftCardOrder($_POST['email_print_radio-1'], $_POST['giftamt'], $cards, $_POST['recmail'], $_POST['toName'], $_POST['fromName'], $_POST['msg'], $_POST['sendmail'], time());
	}
	function redeemCard()
	{
		global $session, $database, $form;
		$_POST = sanitize_custom($_POST);
		$result=$session->redeemCard($_POST['card_code']);
		if($result ==1)
		{
			$amt = $database->GetGiftCardAmount($_POST['card_code']);
			$amt = number_format($amt, 2, '.', ',');
			header("Location: index.php?p=17&v=1&amt=$amt");
		}
		else
		{
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=17&v=0");
		}
	}
	function donate_card()
	{
		global $session, $database, $form;
		$_POST = sanitize_custom($_POST);
		$result=$session->donate_card($_POST['card_id'],$_POST['card_code'],$_POST['card_amt']);
		if($result ==1)
		{
			header("Location: index.php?p=29&v=1");
		}
		else
		{
			header("Location: index.php?p=29&v=0");
		}
	}
	function promotLoan()
	{
		global $session, $database, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$uid = $_GET['uid'];
		$lid = $_GET['lid'];
		$result=$session->promotLoan($uid,$lid,$_POST['frnds_emails'],$_POST['frnds_msg'],$_POST['amt_req'],$_POST['amt_need'],$_POST['interest'],$_POST['fbrating'],$_POST['fbrating_count'], $_POST['location'],$_POST['borrower_fname'],$_POST['borrower_lname'],$_POST['loan_use'],$_POST['loan_type']);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			$loanprurl = getLoanprofileUrl($uid,$lid);
			header("Location: $loanprurl?v=0");
		}
		else if($result==1)
		{
			header("Location: $loanprurl?v=1");
		}
	}
	function invite_frnds()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$result=$session->invite_frnds($_POST['frnds_emails'],'',$_POST['loanid'],$_POST['user_name'],$_POST['user_email'],$_POST['invite_subject'],$_POST['invite_message']);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			if(empty($_POST['loanid']))
				header("Location: index.php?p=30&v=0");
			else
				header("Location: index.php?p=30&l=".$_POST['loanid']."&v=0");
		}
		else if($result==1)
		{
			if(empty($_POST['loanid']))
				header("Location: index.php?p=30&v=1");
			else
				header("Location: index.php?p=30&l=".$_POST['loanid']."&v=1");
		}
	}
	function get_contacts()
	{
		global $session, $form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$result=$session->get_contacts($_POST['email'],$_POST['password'],$_POST['provider']);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: inviter.php?v=0");
		}
		else if($result==1)
		{
			header("Location: inviter.php?v=1");
		}

	}
	function madePayment()
	{	
		global $session,$form, $database;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['paidamt']);
		$database->startDbTxn();
		$result=$session->madePayment($_POST['userid'],$_POST['loanid'],$_POST['paiddate'],$amount);
		if($result==0 || $result==-1)
		{
			$database->rollbackTxn();
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=11&a=5&u=".$_POST['userid']);
		}
		else{
			$res= $database->UpdateExpectedRepayDate($_POST['userid'],$_POST['loanid']);
			$database->commitTxn();
			$repayment=$database->getTotalPayment($_POST['userid'], $_POST['loanid']);
			$p= $repayment['paidtotal']/$repayment['amttotal']*100;
			$p= number_format($p);
			if($p<99)
				$session->checkReferralCommission($_POST['userid'], $p);
			else
				$session->checkReferralCommission($_POST['userid'], 100);
					header("Location: index.php?p=11&a=5&u=".$_POST['userid']);
		}
	}
	function get_loans()
	{	
		global $session,$form, $database;
		$t=$_POST['type'];
		$row=0;
		if(isset($_GET['row']))
			$row=$_GET['row'];
		$pg=1;
		if(isset($_GET['pg']))
			$pg=$_GET['pg'];
		$searchLoan='';
		if(isset($_POST['searchLoan']))
			$searchLoan= sanitize($_POST['searchLoan']);
		if(isset($_POST['sort']))
			$sort= sanitize($_POST['sort']);
		else
			$sort=1;
		if(isset($_POST['randomLoans']))
		{
			$_SESSION['randomLoans']=$_POST['randomLoans'];
		}
		if(isset($_POST['searchSort']))
		{
			if(isset($_POST['fundSort'])){
				$sort=$_POST['fundSort'];
			}
			else if(isset($_POST['activeSort'])){
				$sort=$_POST['activeSort'];
			}
			else{
				$sort=$_POST['endSort'];
			}
		}
		header("Location: index.php?p=2&t=$t&s=$sort&row=$row&pg=$pg&key=".urlencode($searchLoan));
	}
	function repay_report()
	{
		global $session,$form, $database;
		$_POST = sanitize_custom($_POST);
		/*
		if(!empty($_POST["date"]))
			$_SESSION['rp_date']=$_POST["date"];
		else
		*/
			$time = date('m/d/Y',time());
			$_SESSION['rp_date']=$time;
		
		header("Location: index.php?p=31&c=".$_POST['country']."&a=".$_POST['assignedto']);
	}
	
	function declinedBorrower()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$result=$session->declinedBorrower($_POST['userid'],$_POST["dreason"]);
		if(!$result)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
		}
		header("Location: index.php?p=7&id=".$_POST['userid']);
	}
	function reScheduleLoan()
	{

		global $session,$form,$database;
		$_POST_ORG=$_POST;
		
		$_POST = sanitize_custom($_POST);
		$installment_amount = str_replace(",","",$_POST['installment_amount']);
		if(isset($_POST['confirmReScheduleLoan']))
			$confirmReScheduleLoan=1;
		else{
			$confirmReScheduleLoan=0;
			unset($_SESSION['rescheduleDetail']);
		}
		$result = $session->reScheduleLoan($_POST['period'], $installment_amount, $_POST['installment_date'], $_POST['original_period'], $_POST["reschedule_reason"], $confirmReScheduleLoan, $_POST['loanid'],$_POST['propose_type']);
		if($confirmReScheduleLoan==0)
		{
			if(!$result)
			{
				$_SESSION['value_array']=$_POST_ORG;
				$_SESSION['error_array']=$form->getErrorArray();
				header("Location: index.php?p=41&l=".$_POST['loanid']);
			}
			else
			{
				$_SESSION['value_array']=$_POST_ORG;
				$_SESSION['error_array']=$form->getErrorArray();
				header("Location: index.php?p=42&l=".$_POST['loanid']);
			}
		}
		else
		{	
			if(!$result)
			{
				$_SESSION['value_array']=$_POST_ORG;
				$_SESSION['error_array']=$form->getErrorArray();
				$_SESSION['failedResch']=1;
				header("Location: index.php?p=42&l=".$_POST['loanid']);
			}
			else
			{
				$uid=$session->userid;
				$loanprurl = getLoanprofileUrl($uid,$_POST['loanid']);
				$res=$database->UpdateExpectedRepayDate($uid,$_POST['loanid']);
				header("Location: $loanprurl");
			}
		}
	}
	function otherwithdraw()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['amount']);
		$result=$session->otherwithdraw($_POST['OtherCurr'], $_POST['OtherBname'],$_POST['OtherBAddress'], $_POST['OtherCity'],$_POST['OtherCountry'], $_POST['OtherAno'], $_POST['OtherName'],$amount);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17");
		}
		else if($result==1)
		{
			header("Location: index.php?p=17");
		}
		else if($result==2)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17&err=1009");
		}
	}
	function withdraw()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['amount']);
		$result=$session->withdraw($amount, $_POST['paypalemail']);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17");
		}
		else if($result==1)
		{
			header("Location: index.php?p=17&m=1");
		}
		else if($result==2)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17&err=1009");
		}
	}
	function paySimplewithdraw()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['PaysimpleAmt']);
		$result=$session->PaySimplewithdraw($_POST['PaysimpleName'], $_POST['PaysimpleAddress1'],$_POST['PaysimpleAddress2'], $_POST['PaysimpleCity'],$_POST['PaysimpleState'], $_POST['PaysimpleZip'], $_POST['PaysimplePno'], $amount);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17");
		}
		else if($result==1)
		{
			header("Location: index.php?p=17&m=2");
		}
		else if($result==2)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17&err=1009");
		}
	}
	function payotherwithdrawadmin()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['amount']);
		$result=$session->payotherwithdrawadmin($amount,$_POST['lenderid'],$_POST['rowid']);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17");
		}
		else
		{
			header("Location: index.php?p=17");
		}
	}
	function paysimplewithdrawadmin()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['amount']);
		$result=$session->paysimplewithdrawadmin($amount,$_POST['lenderid'],$_POST['rowid']);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17");
		}
		else
		{
			header("Location: index.php?p=17");
		}
	}
	function paywithdraw()
	{
		global $session,$form;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$amount = str_replace(",","",$_POST['amount']);
		$result=$session->paywithdraw($amount,$_POST['lenderid'],$_POST['rowid']);
		if($result==0)
		{
			$_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: index.php?p=17");
		}
		else
		{
			header("Location: index.php?p=17");
		}
	}
    function registerEmail()
	{
        global $session,$form;
        $_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$result= $session->registerEmail($_POST['email']);
		if($result==1)
		{
			header("Location: microfinance/lend.html&r=1");
		}
		else
		{
            $_SESSION['value_array']=$_POST_ORG;
			$_SESSION['error_array']=$form->getErrorArray();
			header("Location: microfinance/lend.html");
		}
    }
    function registerEmailSent()
	{
        global $database, $form;
		$_POST = sanitize_custom($_POST);
        $result= $database->registerEmailSent($_POST['id']);
		if($result==1)
			header("Location: index.php?p=63&s=1");
		else
			header("Location: index.php?p=63");
	}
	function updateRePaymentInstruction()
	{
		global $session, $form;
		$id=$session->userid;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);

		$result=$session->updateRePaymentInstruction($_POST['country_code'], $_POST['description'], $_POST['id']);
		if($result)
		{
			header("Location: index.php?p=11&a=13");
		}
		else
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=11&a=13&ac=edit&id=".$_POST['id']);
		}
	}
	function deleteRePaymentInstruction()
	{
		global $session, $form;
		$id=$session->userid;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);

		$result=$session->deleteRePaymentInstruction($_POST['id']);
		header("Location: index.php?p=11&a=13");
	}
	function updateCampaign()
	{
		global $session, $form;
		$id=$session->userid;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$result=$session->updateCampaign($_POST['code'],$_POST['value'],$_POST['max_use'], $_POST['message'],$_POST['active'],$_POST['id']);
		if($result)
		{
			header("Location: index.php?p=54");
		}
		else
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=54&ac=edit&id=".$_POST['id']);
		}
	}
	function deletecampaign()
	{
		global $session, $form;
		$id=$session->userid;
		$_POST_ORG=$_POST;
		$_POST = sanitize_custom($_POST);
		$result=$session->deletecampaign($_POST['id']);
		header("Location: index.php?p=54");
	}
	function outstandingReport()
	{
		global $session, $form;
		$result=$session->outstandingReport($_POST["oustandDate"]);
		
		if($result==0)
		{
			
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=72");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			header("Location: index.php?p=72&v=1");
		}
	}
       // Added by Mohit 07-11-2013
        function updatebgroup()
        {
            global $session, $form;
            $gid = $_POST["updatebgroup"];
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
                
            $result=$session->updatebgroup($gid,$name,$website,$about_grp,$member_name1, $member_email1, $member_name2, $member_email2, $member_name3, $member_email3, $member_name4, $member_email4, $member_name5, $member_email5, $member_name6, $member_email6, $member_name7, $member_email7, $member_name8, $member_email8, $member_name9, $member_email9, $member_name10, $member_email10, $session->userid, $session->userid, $_FILES);
            if($result==1){
            $_SESSION['updategroup'] = true;
            header("Location: index.php?p=106&gid=$gid");
            }else {
            header("Location: index.php?p=106&gid=$gid");
            }
            
        }
	function updatelendergroup()
	{
		global $session, $form;
		$result=$session->updatelendergroup($_POST["updatelendergroup"],$_POST["group_name"],$_POST["website"],$_POST["about_group"], $session->userid, $session->userid, $_FILES);
		if($result==1){
		$_SESSION['updategroup'] = true;
		$gid = $_POST["updatelendergroup"];
		header("Location: index.php?p=82&gid=$gid");
		}
	}
	function SendConfirmaEmailAgain() {
		global $session, $form;
		$result=$session->SendConfirmaEmailAgain($session->userid);
		if($result==1){
			$_SESSION['SendConfirmaEmailAgain'] = true;
			header("Location: index.php?p=50");
		}
	}
	function binvite_frnd(){
		global $session, $form;
		$_POST_ORG=$_POST; 
		$_POST = sanitize_custom($_POST);
		$result=$session->binvite_frnd($_POST['frnd_email'], $_POST['user_name'],$_POST['user_email'],$_POST['invite_subject'],$_POST['invite_message']);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST_ORG;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=96&v=0");
		}
		else if($result==1)
		{
			header("Location: index.php?p=96&v=1");
		}	
	}
	function resendEndorsermail(){
		global $session, $form;
		$result=$session->resendEndorsermail($_POST['id']);
		if($result){
			$_SESSION['resend_endorser']= $result;
			header("Location: index.php?p=50");
		}
	}
	function facebook_info(){
		global $session, $form;
		$result=$session->facebook_info($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=98");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=98&v=1");
		}
	}
	function disbursement_report(){
		global $session,$form, $database;
		$_POST = sanitize_custom($_POST);
		header("Location: index.php?p=99&c=".$_POST['country']);
	}
	function find_borrower(){
		if(!empty($_POST['search'])){
			$search=$_POST['search'];
		}else{
			$search='';
		}
		header("Location: index.php?p=102&c=".$_POST['country']."&brwr=".$_POST['borrower_type']."&search=".$search);
	}
//modified by Julia 23-10-2013
	function find_lender(){
		if(!empty($_POST['search'])){
			$search=$_POST['search'];
		}else{
			$search='';
		}
		header("Location: index.php?p=11&a=3&type=1&ord=ASC&c=".$_POST['country']."&search=".$search);
	}

//added by Julia 3-11-2013
	function find_lenderforstaff(){
		if(!empty($_POST['search'])){
			$search=$_POST['search'];
		}else{
			$search='';
		}
		header("Location: index.php?p=25&c=".$_POST['country']."&search=".$search);
	}

	function getAllborrowers(){
		if(!empty($_POST['search'])){
			$search=$_POST['search'];
		}else{
			$search='';
		}
		header("Location: index.php?p=11&a=1&type=".$_POST['type']."&ord=".$_POST['ord']."&c=".$_POST['country']."&brwr=".$_POST['borrower_type']."&search=".$search);
	}

	function activation_rate(){
		global $session, $form;
		$result=$session->activation_rate($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=109");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=109&v=1");
		}
	}

	function repayment_rate(){
		global $session, $form;
		$result=$session->repayment_rate($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=110");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();

			if(!empty($_POST['fb'])){
				$fb=$_POST['fb'];
			}else{
				$fb='';
			}

			if(!empty($_POST['invite'])){
				$invite=$_POST['invite'];
			}else{
				$invite='';
			}

			if(!empty($_POST['text'])){
				$text=$_POST['text'];
			}else{
				$text='';
			}
			header("Location: index.php?p=110&v=1&fb=".$fb."&invite=".$invite."&text=".$text);


		}
	}

	function sharebox_off()
	{
		global $session;
		$_POST = sanitize_custom($_POST);
		$loanprurl = getLoanprofileUrl($_POST['borrowerid'],$_POST['loanid']);
		$result=$session->sharebox_off($_POST['lenderid'],$_POST['sharebox_off']);
		if($result){
			echo $loanprurl;
		}
		else{
			echo $loanprurl;
		}
	}

	function tmp_trhistory()
	{
		global $session, $form;
		$result=$session->tmp_trhistory($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=22");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=22&v=1&ord=ASC&opt=TrDate&c=ALL&type=1");
		}
	}
	function tmp_trhistorySummary()
	{
		global $session, $form;
		$result=$session->trhistory($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=108");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=108&v=1&ord=ASC&opt=TrDate&c=ALL&type=1");
		}
	}


	function loans_funded(){
		global $session, $form;
		$result=$session->loans_funded($_POST["date1"], $_POST["date2"]);
		if($result==0)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();
			header("Location: index.php?p=114");
		}
		else if($result==1)
		{
			$_SESSION['value_array'] = $_POST;
			$_SESSION['error_array'] = $form->getErrorArray();

			header("Location: index.php?p=114&v=1");


		}
	}









};
$updateprocess=new updateProcess;
?>