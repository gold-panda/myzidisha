<?php
class Validation
{
	var $error=array();
	
	function validateBorrowerReg($uname, $namea, $nameb, $pass1, $pass2, $post, $city, $country, $email, $mobile,$reffered_by,$income, $about, $bizdesc, $photo, $user_guess, $bnationid, $referrer, $community_name_no, $documents, $repaidPast, $debtFree, $share_update, $onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town,$bfamilycont1, $bfamilycont2, $bfamilycont3, $bneighcont1,$bneighcont2,$bneighcont3,$home_no, $rec_form_offcr_name, $rec_form_offcr_num, $cntct_type, $fb_data, $endorser_name, $endorser_email)
	{ 
		global $form,$database, $session;
		
		$path=	getEditablePath('error.php');
		//Logger_Array("FB LOG - on validation - register",'fb_data', serialize($fb_data));
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		$this->checkUsername($uname, "busername");
		$this->checkPassword($pass1,$pass2, "bpass1");
		$this->checkFirstName($namea, "bfname");
		$this->checkLastName($nameb, "blname");
		$this->checkAddress($post, "bpostadd");
		$this->checkCity($city, "bcity");
		$this->checkCountry($country, "bcountry");
		$limit= $database->getAdminSetting('MinEndorser');
		$params['minendorser']= $limit;
		$empty_endorser= $session->formMessage($lang['error']['empty_endorser'], $params);
		if(!empty($fb_data['user_profile']['id'])){
			$FB_ID_exist= $database->IsFacebookIdExist($fb_data['user_profile']['id']);
		}
		if($country!='BF'){
			$fb_name= $fb_data['user_profile']['name'];
			if(empty($bfamilycont1)){
				$form->setError('bfamilycont1', $this->error['empty_family_cont1']);
			}
			if(empty($bfamilycont2)){
				$form->setError('bfamilycont2', $this->error['empty_family_cont2']);
			}
			if(empty($bfamilycont3)){
				$form->setError('bfamilycont3', $this->error['empty_family_cont3']);
			}
			if(empty($bneighcont1)){
				$form->setError('bneighcont1', $this->error['empty_neigh_cont1']);
			}
			if(empty($bneighcont2)){
				$form->setError('bneighcont2', $this->error['empty_neigh_cont2']);
			}
			if(empty($bneighcont3)){
				$form->setError('bneighcont3', $this->error['empty_neigh_cont3']);
			}
			$checkName = false;
			if(empty($fb_data['user_profile'])){ 
				$form->setError('cntct_type', $this->error['empty_fbdate']);
			}elseif($FB_ID_exist>0){
				$form->setError('cntct_type', $this->error['exist_id']);
				$_SESSION['hide_fbmsg']= true;
			}
			/*elseif(!empty($email) && !empty($fb_data['user_profile']['email']) && !empty($fb_name) && !empty($namea) && !empty($nameb)){
				$fname=stripos($fb_name, $namea);
				$lname=stripos($fb_name, $nameb);
				if($fb_data['user_profile']['email']!= $email && $fname==false && $fname!=0 && $lname==false && $lname!=0){
					$form->setError('cntct_type', $this->error['fb_noteligible']);
					$_SESSION['hide_fbmsg']= true;
				}
			}*/
			//Mohit change 26 Sept 
			// Facebook and Zidisha Account email, name commented as per the julia email
			/*
			elseif(!empty($fb_data['user_profile']['email']) && !empty($email)){
				if (strcasecmp($fb_data['user_profile']['email'], $email) != 0){
						//if($fb_data['user_profile']['email']!= $email){
						//$form->setError('cntct_type', $this->error['fb_noteligible']);
						//$_SESSION['hide_fbmsg']= true;
						$checkName = true;
					}
					else{
						$checkName = false;
					}
				}else{
					$checkName = true;
				}
			
			if($checkName){
				$fname=true;
				$lname=true;
				
				if(!empty($fb_name) && !empty($namea) && !empty($nameb)){
				
					$fname=stripos($fb_name, $namea);
					$lname=stripos($fb_name, $nameb);
					
				}

				if($fname===false && $lname===false){
					$form->setError('cntct_type', $this->error['fb_noteligible']);
					$_SESSION['hide_fbmsg']= true;
				}
			}
			*/

			if(empty($endorser_name) || empty($endorser_email)){ 
				$form->setError('endorser', $empty_endorser);
			}else{
				for($i=0; $i<$limit; $i++){
					if(empty($endorser_name[$i]) || empty($endorser_email[$i])){
						$form->setError('endorser', $empty_endorser);
					}
				}
				for($i=0; $i<10; $i++){
					if(!empty($endorser_email[$i])){ 
						$k= $i+1;
						for($j=$i-1; $j>=0; $j--){
							if(($endorser_email[$i]==$endorser_email[$j]) || ($endorser_email[$i]==$email)){
								$form->setError("endorser_email".$k, $this->error['same_endorser']);	
							}
						}
						$this->checkEmail($endorser_email[$i], "endorser_email".$k);
						$e_emailexist= $database->IsEmailExist($endorser_email[$i]);
						if($e_emailexist==0){
							$e_emailexist= $database->IsEndorseEmailExist($endorser_email[$i]);
						}
						if($e_emailexist>0){ 
							$form->setError("endorser_email".$k, $this->error['exist_endorser']);
						}
					}
				}
			}
		}else{
			if($cntct_type==''){ 
				$form->setError('contact_type', $this->error['empty_contact']);
			}else{
				if($cntct_type!='1'){
					if(empty($bfamilycont1)){
						$form->setError('bfamilycont1', $this->error['empty_family_cont1']);
					}
					if(empty($bfamilycont2)){
						$form->setError('bfamilycont2', $this->error['empty_family_cont2']);
					}
					if(empty($bfamilycont3)){
						$form->setError('bfamilycont3', $this->error['empty_family_cont3']);
					}
					if(empty($bneighcont1)){
						$form->setError('bneighcont1', $this->error['empty_neigh_cont1']);
					}
					if(empty($bneighcont2)){
						$form->setError('bneighcont2', $this->error['empty_neigh_cont2']);
					}
					if(empty($bneighcont3)){
						$form->setError('bneighcont3', $this->error['empty_neigh_cont3']);
					}
				}else{ 
					$fb_name= $fb_data['user_profile']['name'];
					$checkName = false;
					if(empty($fb_data['user_profile'])){ 
						$form->setError('cntct_type', $this->error['empty_fbdate']);
					}elseif($FB_ID_exist>0){
						$form->setError('cntct_type', $this->error['exist_id']);
						$_SESSION['hide_fbmsg']= true;
					}
					/*elseif(!empty($email) && !empty($fb_data['user_profile']['email']) && !empty($fb_name) && !empty($namea) && !empty($nameb)){
						$fname=stripos($fb_name, $namea);
						$lname=stripos($fb_name, $nameb);
						if($fb_data['user_profile']['email']!= $email && $fname==false && $fname!=0 && $lname==false && $lname!=0){
							$form->setError('cntct_type', $this->error['fb_noteligible']);
							$_SESSION['hide_fbmsg']= true;
						}
					}*/
					//Mohit change 26 Sept 
					// Facebook and Zidisha Account email, name commented as per the julia email
					/*
					elseif(!empty($fb_data['user_profile']['email']) && !empty($email)){
						if (strcasecmp($fb_data['user_profile']['email'], $email) != 0){
							//if($fb_data['user_profile']['email']!= $email){
							//$form->setError('cntct_type', $this->error['fb_noteligible']);
							//$_SESSION['hide_fbmsg']= true;
							$checkName = true;
						}
						else{
							$checkName = false;
						}
					}else{
						$checkName = true;
					}
			
			if($checkName){
				$fname=true;
				$lname=true;
				
				if(!empty($fb_name) && !empty($namea) && !empty($nameb)){
					$fname=stripos($fb_name, $namea);
					$lname=stripos($fb_name, $nameb);
				}

				if($fname===false && $lname===false){
					$form->setError('cntct_type', $this->error['fb_noteligible']);
					$_SESSION['hide_fbmsg']= true;
				}
			}
			*/
					
				
					if(empty($endorser_name) || empty($endorser_email)){ 
						$form->setError('endorser', $empty_endorser);
					}else{
						$limit= $database->getAdminSetting('MinEndorser');
						for($i=0; $i<$limit; $i++){
							if(empty($endorser_name[$i]) || empty($endorser_email[$i])){
								$form->setError('endorser', $empty_endorser);
							}
						}
						for($i=0; $i<10; $i++){
							if(!empty($endorser_email[$i])){ 
								$k= $i+1;
								for($j=$i-1; $j>=0; $j--){
									if(($endorser_email[$i]==$endorser_email[$j]) || ($endorser_email[$i]==$email)){
										$form->setError("endorser_email".$k, $this->error['same_endorser']);	
									}
								}
								$this->checkEmail($endorser_email[$i], "endorser_email".$k);
								$e_emailexist= $database->IsEmailExist($endorser_email[$i]);
								if($e_emailexist==0){
									$e_emailexist= $database->IsEndorseEmailExist($endorser_email[$i]);
								}
								if($e_emailexist>0){ 
									$form->setError("endorser_email".$k, $this->error['exist_endorser']);
								}
							}
						}
					}
				}
			}
		}
		if(empty($home_no)) {
			$form->setError('home_no', $this->error['home_no']);
		}
/*     if(empty($lending_inst_name)) {
			$form->setError('lending_institution', $this->error['lending_institution']);
		}
		if(empty($lending_inst_add)) {
			$form->setError('lending_institution_add', $this->error['lending_institution_add']);
		}
		$this->checkLendingInstPhone($lending_inst_phone, "lending_institution_phone");
		if(empty($lending_inst_officer)) {
			$form->setError('lending_institution_officer', $this->error['lending_institution_officer']);
		} */
		$this->checkEmailForBorrower($email, "bemail");
		$this->checkMobile($mobile, "bmobile");
		$MobileExist=$database->IsMobileExist($mobile,$country);
		if(!empty($mobile)) {
			if($MobileExist > 0){
					$form->setError('bmobile', $lang['error']['exist_number']);
			}
		}
		
		/*if(empty($reffered_by)) {
			$form->setError('reffered_by', $lang['error']['reffered_by']);
		}*/
		//$this->checkIncome($income, "bincome");
		$this->checkMyDesc($about, "babout");
		$this->checkBusinessDesc($bizdesc, "bbizdesc");
		$this->checkPhoto($photo, "bphoto");
		//$this->checkTnc($tnc, "tnc");
		$this->checkNationId($bnationid, "bnationid", $country);
		//$this->checkLoanHist($bloanhist, "bloanhist");
		if($country=='BF'){
			if($onbehalf) {
				
				if(!$behalf_name || strlen($behalf_name)<1){
					$form->setError('behalf_name', $lang['error']['empty_behalfname']);
				}
				$this->checkMobile($behalf_number, "behalf_number");
				$this->checkEmail($behalf_email, "behalf_email");
				if(empty($behalf_town)) {
					$form->setError('behalf_town', $lang['error']['empty_behalftown']);
				}
			}
		}
		if(!empty($referrer))
			$this->checkReferrer($referrer, "referrer");
/*
		if(empty($repaidPast)) {
			$form->setError('repaidpast', $lang['error']['repaidpast']);
		}
		if(empty($debtFree)) {
			$form->setError('debtfree', $lang['error']['debtfree']);
		}
		if(empty($share_update)) {
			$form->setError('share_update', $lang['error']['share_update']);
		}
*/
	if($country!='ID'){
		if(empty($rec_form_offcr_num)) {
			$form->setError('rec_form_offcr_num', $lang['error']['empty_rec_form_offcr_num']);
		}else {
			$this->checkMobile($rec_form_offcr_num, 'rec_form_offcr_num');
		}
		if(empty($rec_form_offcr_name)) {
			$form->setError('rec_form_offcr_name', $lang['error']['empty_rec_form_offcr_name']);
		}
	}
		//$this->checkCommunityNameAndNo($community_name_no, "community_name_no");
		$this->checkCapcha($user_guess, "user_guess");
		$this->checkDocuments($documents);
	}
	function validateBorrowerEdit($uname, $namea, $nameb, $pass1, $pass2, $post, $city, $country, $email, $mobile,$reffered_by, $income, $about, $bizdesc, $photo,$bnationid, $community_name_no,$File, $repaidPast, $debtFree, $share_update, $onbehalf, $behalf_name, $behalf_number, $behalf_email, $behalf_town, $submit_type, $uploadedDocs, $bfamilycont1, $bfamilycont2, $bfamilycont3, $bneighcont1, $bneighcont2, $bneighcont3,$home_no, $rec_form_offcr_name, $rec_form_offcr_num, $cntct_type, $fb_data, $endorser_name, $endorser_email,$id) 
	{
		global $form, $database, $session;
		//Logger_Array("FB LOG - on validation - edit",'fb_data', serialize($fb_data).$uname);
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$path=	getEditablePath('register.php');
		include(FULL_PATH."editables/".$path);
		$brwrid = $database->getUserId($uname);
		$bactive = $database->getBorrowerActive($brwrid);
		$this->error=$lang['error'];

		$this->checkUsername($uname, "busername", true);
		if(!empty($pass1))
			$this->checkPassword($pass1,$pass2, "bpass1");
		if(!$bactive) {
			$this->checkAddress($post, "bpostadd");
			$this->checkMobile($mobile, "bmobile");
		}
		//$this->checkIncome($income, "bincome");
		if($submit_type != $lang['register']['savechanges']) {
			$this->checkMyDesc($about, "babout");
			$this->checkBusinessDesc($bizdesc, "bbizdesc");
		}
				
		/*if(empty($reffered_by)) {
				$form->setError('reffered_by', $lang['error']['reffered_by']);
			}
		*/	
		// beacause country name disable on edit profile page by mohit 19-12-13 
		$country=$database->getCountryByBorrowerId($id);
		
		if($country=='BF'){
			if($onbehalf) {
				
				if(!$behalf_name || strlen($behalf_name)<1){
					$form->setError('behalf_name', $lang['error']['empty_behalfname']);
				}
				$this->checkMobile($behalf_number, "behalf_number");
				$this->checkEmail($behalf_email, "behalf_email");
				if(empty($behalf_town)) {
					$form->setError('behalf_town', $lang['error']['empty_behalftown']);
				}
			}
		}
		if($submit_type != $lang['register']['savechanges']) {
			if(!empty($fb_data['user_profile']['id'])){
				$FB_ID_exist= $database->IsFacebookIdExist($fb_data['user_profile']['id'], $session->userid);
				$fb_name= $fb_data['user_profile']['name'];
			}
			$this->checkFirstName($namea, "bfname");
			$this->checkLastName($nameb, "blname");
			$this->checkCity($city, "bcity");
			$this->checkCountry($country, "bcountry");
			$limit= $database->getAdminSetting('MinEndorser');
			$params['minendorser']= $limit;
			$empty_endorser= $session->formMessage($lang['error']['empty_endorser'], $params);
/*
			if(empty($repaidPast)) {
				$form->setError('repaidpast', $lang['error']['repaidpast']);
			}
			if(empty($share_update)) {
				$form->setError('share_update', $lang['error']['debtfree']);
			}if(empty($debtFree)) {
				$form->setError('debtfree', $lang['error']['debtfree']);
			}
*/
			if($country!='BF'){
			if(empty($bfamilycont1)){
				$form->setError('bfamilycont1', $this->error['empty_family_cont1']);
			}
			if(empty($bfamilycont2)){
				$form->setError('bfamilycont2', $this->error['empty_family_cont2']);
			}
			if(empty($bfamilycont3)){
				$form->setError('bfamilycont3', $this->error['empty_family_cont3']);
			}
			if(empty($bneighcont1)){
				$form->setError('bneighcont1', $this->error['empty_neigh_cont1']);
			}
			if(empty($bneighcont2)){
				$form->setError('bneighcont2', $this->error['empty_neigh_cont2']);
			}
			if(empty($bneighcont3)){
				$form->setError('bneighcont3', $this->error['empty_neigh_cont3']);
			}
			$checkName = false;
			if(empty($fb_data['user_profile'])){ 
				$form->setError('cntct_type', $this->error['empty_fbdate']);
			}elseif($FB_ID_exist>0){
				$form->setError('cntct_type', $this->error['exist_id']);
					$_SESSION['hide_fbmsg']= true;
			}
			/*elseif(!empty($email) && !empty($fb_data['user_profile']['email']) && !empty($fb_name)&& !empty($namea) && !empty($nameb)){ 
				$fname=stripos($fb_name, $namea);
				$lname=stripos($fb_name, $nameb);
				if($fb_data['user_profile']['email']!= $email && $fname==false && $fname!=0 && $lname==false && $lname!=0){
					$form->setError('cntct_type', $this->error['fb_noteligible']);
					$_SESSION['hide_fbmsg']= true;
				}
			}*/
			
			//Mohit change 26 Sept 
			// Facebook and Zidisha Account email, name commented as per the julia email
			/*
			elseif(!empty($fb_data['user_profile']['email']) && !empty($email)){
				if (strcasecmp($fb_data['user_profile']['email'], $email) != 0){
					//$form->setError('cntct_type', $this->error['fb_noteligible']);
					//$_SESSION['hide_fbmsg']= true;
					$checkName = true;
				}
				else{
					$checkName = false;
				}
			}else{
				$checkName = true;
			}
			
			if($checkName){
				$fname=true ;
				$lname=true;
				
				if(!empty($fb_name) && !empty($namea) && !empty($nameb)){
					$fname=stripos($fb_name, $namea);
					$lname=stripos($fb_name, $nameb);
				}

				if($fname===false && $lname===false){
					$form->setError('cntct_type', $this->error['fb_noteligible']);
					$_SESSION['hide_fbmsg']= true;
				}
			}
			*/

			if(empty($endorser_name) || empty($endorser_email)){ 
				$form->setError('endorser', $empty_endorser);
			}else{
				for($i=0; $i<$limit; $i++){
					if(empty($endorser_name[$i]) || empty($endorser_email[$i])){
						$form->setError('endorser', $empty_endorser);
					}
				}
				for($i=0; $i<10; $i++){
					if(!empty($endorser_email[$i])){ 
						$k= $i+1;
						for($j=$i-1; $j>=0; $j--){
							if(($endorser_email[$i]==$endorser_email[$j]) || ($endorser_email[$i]==$email)){
								$form->setError("endorser_email".$k, $this->error['same_endorser']);	
							}
						}
						$this->checkEmail($endorser_email[$i], "endorser_email".$k);
						$e_emailexist= $database->IsEmailExist($endorser_email[$i], $session->userid);
						if($e_emailexist==0){
							$e_emailexist= $database->IsEndorseEmailExist($endorser_email[$i], $session->userid);
						}
						if($e_emailexist>0){ 
							$form->setError("endorser_email".$k, $this->error['exist_endorser']);
						}
					}
				}
			}
		  }else{
			if($cntct_type==''){ 
				$form->setError('contact_type', $this->error['empty_contact']);
			}else{
				if($cntct_type!='1'){
					if(empty($bfamilycont1)){
						$form->setError('bfamilycont1', $this->error['empty_family_cont1']);
					}
					if(empty($bfamilycont2)){
						$form->setError('bfamilycont2', $this->error['empty_family_cont2']);
					}
					if(empty($bfamilycont3)){
						$form->setError('bfamilycont3', $this->error['empty_family_cont3']);
					}
					if(empty($bneighcont1)){
						$form->setError('bneighcont1', $this->error['empty_neigh_cont1']);
					}
					if(empty($bneighcont2)){
						$form->setError('bneighcont2', $this->error['empty_neigh_cont2']);
					}
					if(empty($bneighcont3)){
						$form->setError('bneighcont3', $this->error['empty_neigh_cont3']);
					}
				}else{ 
					$checkName = false;
					if(empty($fb_data['user_profile'])){ 
						$form->setError('cntct_type', $this->error['empty_fbdate']);
					}elseif($FB_ID_exist>0){
						$form->setError('cntct_type', $this->error['exist_id']);
						$_SESSION['hide_fbmsg']= true;
					}
					/*elseif(!empty($email) && !empty($fb_data['user_profile']['email']) && !empty($fb_name) && !empty($namea) && !empty($nameb)){
						$fname=stripos($fb_name, $namea);
						$lname=stripos($fb_name, $nameb);
						if($fb_data['user_profile']['email']!= $email && $fname==false && $fname!=0 && $lname==false && $lname!=0){
							$form->setError('cntct_type', $this->error['fb_noteligible']);
							$_SESSION['hide_fbmsg']= true;
						}
					}*/
					//Mohit change 26 Sept 
					// Facebook and Zidisha Account email, name commented as per the julia email
					/*
				elseif(!empty($fb_data['user_profile']['email']) && !empty($email)){
					if (strcasecmp($fb_data['user_profile']['email'], $email) != 0){
						//if($fb_data['user_profile']['email']!= $email){
						//$form->setError('cntct_type', $this->error['fb_noteligible']);
						//$_SESSION['hide_fbmsg']= true;
						$checkName = true;
					}
					else{
						$checkName = false;
					}
				}else{
					$checkName = true;
				}
			
				if($checkName){
					$fname=true;
					$lname=true;
				
					if(!empty($fb_name) && !empty($namea) && !empty($nameb)){
						$fname=stripos($fb_name, $namea);
						$lname=stripos($fb_name, $nameb);
					}

					if($fname===false && $lname===false){
						$form->setError('cntct_type', $this->error['fb_noteligible']);
						$_SESSION['hide_fbmsg']= true;
					}
				}
				*/

					if(empty($endorser_name) || empty($endorser_email)){ 
						$form->setError('endorser', $empty_endorser);
					}else{
						$limit= $database->getAdminSetting('MinEndorser');
						for($i=0; $i<$limit; $i++){
							if(empty($endorser_name[$i]) || empty($endorser_email[$i])){
								$form->setError('endorser', $empty_endorser);
							}
						}
						for($i=0; $i<10; $i++){
							if(!empty($endorser_email[$i])){ 
								$k= $i+1;
								for($j=$i-1; $j>=0; $j--){
									if(($endorser_email[$i]==$endorser_email[$j]) || ($endorser_email[$i]==$email)){
										$form->setError("endorser_email".$k, $this->error['same_endorser']);	
									}
								}
								$this->checkEmail($endorser_email[$i], "endorser_email".$k);
								$e_emailexist= $database->IsEmailExist($endorser_email[$i], $session->userid);
								if($e_emailexist==0){
									$e_emailexist= $database->IsEndorseEmailExist($endorser_email[$i], $session->userid);
								}
								
								if($e_emailexist>0){ 
									$form->setError("endorser_email".$k, $this->error['exist_endorser']);
								}
							}
						}
					}
				}
			}
		  }
			if(empty($home_no)) {
				$form->setError('home_no', $this->error['home_no']);
			}
			/*if(empty($lending_inst_name)) {
				$form->setError('lending_institution', $this->error['lending_institution']);
			}
			if(empty($lending_inst_add)) {
				$form->setError('lending_institution_add', $this->error['lending_institution_add']);
			}
			$this->checkLendingInstPhone($lending_inst_phone, "lending_institution_phone");
			if(empty($lending_inst_officer)) {
				$form->setError('lending_institution_officer', $this->error['lending_institution_officer']);
			} */		
		if ($country!='ID'){
			if(empty($rec_form_offcr_num)) {
				$form->setError('rec_form_offcr_num', $lang['error']['empty_rec_form_offcr_num']);
			}else {
				$this->checkMobile($rec_form_offcr_num, 'rec_form_offcr_num');
			}
			if(empty($rec_form_offcr_name)) {
				$form->setError('rec_form_offcr_name', $lang['error']['empty_rec_form_offcr_name']);
			}
			
			$this->checkDocuments($File,$uploadedDocs);
		}
				
		}
		$userid = $database->getUserId($uname);
		$iscompleteLater = $database->getiscompleteLater($userid);
		if($iscompleteLater) {
			// $this->checkNationId($bnationid, "bnationid", $country, $session->userid);
		}
			$this->checkEmailForBorrower($email, "bemail", $session->userid);
			if(!empty($mobile)) {
				$iscompltlater = $database->getiscompleteLater($session->userid);

				$prevMobile =$database->getPrevMobile($session->userid);
				$prevcountry = $database->getCountryCodeById($session->userid);
				$MobileExist=$database->IsMobileExist($mobile, $country);
				if(!$iscompltlater) {
					if($MobileExist > 0 && (($prevMobile!=$mobile) || $prevcountry!=$country)) {
						$form->setError('bmobile', $lang['error']['exist_number']);
					}
				}else {
					if($MobileExist > 0) {
						$form->setError('bmobile', $lang['error']['exist_number']);
					}
				}

			}
		// because borrowers cannot change their email.by khushboo on 21 Mar 2013 
		/*$prevEmail =$database->getEmailB($session->userid); 
		$Exist=$database->IsEmailExist($email);
		if($submit_type==trim($lang['register']['savechanges'])) {
			$this->checkEmail($email, "bemail");
			if(trim($prevEmail['email'])!=trim($email)) {
				if($Exist>0){
						$form->setError('bemail', $lang['error']['exist_email']);

				}
			}
		}*/
		if(!empty($mobile)) {
			$MobileExist=$database->IsMobileExist($mobile, $country);
			$prevMobile =$database->getPrevMobile($session->userid);
			$prevcountry = $database->getCountryCodeById($session->userid);
			if($submit_type==trim($lang['register']['savechanges'])) {
				if($prevMobile!=$mobile || $prevcountry!=$country) {
				if($MobileExist>0){
							$form->setError('bmobile', $lang['error']['exist_number']);
					}
				}
			}
		}
		// Anupam 13-12-2012 borrower must have photo uploaded check also in edit profile.
		$this->checkPhoto($photo, "bphoto");
		//$this->checkLoanHist($bloanhist, "bloanhist");
		//$this->checkCommunityNameAndNo($community_name_no, "community_name_no");
	}
	function validatePartnerReg($username, $pass1, $pass2, $pname, $address, $city, $country, $email, $emails_notify, $desc, $user_guess)
	{
		global $form,$database;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];

		$this->checkUsername($username, "pusername");
		$this->checkPassword($pass1,$pass2, "ppass1");
		$this->checkName($pname, "pname");
		$this->checkAddress($address, "paddress");
		$this->checkCity($city, "pcity");
		$this->checkCountry($country, "pcountry");
		$this->checkEmail($email, "pemail");
		$Exist=$database->IsEmailExist($email);
			if($Exist>0){
				$form->setError('pemail', $lang['error']['exist_email']);
			}
		$this->checkEmails($emails_notify, "emails_notify");
		$this->checkOrgDesc($desc, "pdesc");
		$this->checkCapcha($user_guess, "user_guess");

	}
	function validatePartnerEdit($id, $username, $pass1, $pass2, $pname, $address, $city, $country, $email, $emails_notify, $desc)
	{
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		$this->checkUsername($username, "pusername", true);
		if(!empty($pass1))
			$this->checkPassword($pass1,$pass2, "ppass1");
		$this->checkName($pname, "pname", $id, true);
		$this->checkAddress($address, "paddress");
		$this->checkCity($city, "pcity");
		$this->checkCountry($country, "pcountry");
		$this->checkEmail($email, "pemail");
		$this->checkEmails($emails_notify, "emails_notify");
		$this->checkOrgDesc($desc, "pdesc");
	}
	function validateLenderReg($username, $pass1, $pass2, $fname, $lname, $email, $frnds_emails, $city, $country, $tnc, $user_guess, $card_code,$referral_code,$member_type)
	{	
		global $form, $database;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		$this->checkUsername($username, "lusername");
		$this->checkPassword($pass1,$pass2, "lpass1");
		if(!isset($member_type) || $member_type!=5){
		$this->checkFirstName($fname, "lfname");
		$this->checkLastName($lname, "llname");
		$this->checkCity($city, "lcity");
		$this->checkCountry($country, "lcountry");
		}else{
			if(!$fname || strlen($fname)<1)
				$form->setError('lfname', $this->error['empty_lgname']);
		}
		$this->checkEmail($email, "lemail");
		$Exist=$database->IsEmailExist($email);
		if($Exist>0){
				$form->setError('lemail', $lang['error']['exist_email']);
		}
		$this->checkEmails($frnds_emails, "emailError");
		$this->checkTnc($tnc, "tnc");
		$this->checkCapcha($user_guess, "user_guess");
		$this->checkGiftCard($card_code, "card_code");
		$this->checkRefferalCode($referral_code, "referral_code");
	}
	function validateLenderEdit($username, $pass1, $pass2, $fname, $lname, $email, $city, $country)
	{
		global $session;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		$this->checkUsername($username, "lusername", true);
		if(!empty($pass1))
			$this->checkPassword($pass1,$pass2, "lpass1");
		$this->checkFirstName($fname, "lfname");
		if($session->usersublevel!=LENDER_GROUP_LEVEL){
			$this->checkLastName($lname, "llname");
			$this->checkCity($city, "lcity");
			}
		$this->checkEmail($email, "lemail");
		$this->checkCountry($country, "lcountry");
	}
	function validateEndorserReg($uname, $namea, $nameb, $pass1, $pass2, $post, $city, $country, $email, $mobile, $user_guess, $bnationid, $home_no, $fb_data, $babout, $bconfdnt){
		global $form,$database;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		$this->checkUsername($uname, "busername");
		$this->checkPassword($pass1,$pass2, "bpass1");
		$this->checkFirstName($namea, "bfname");
		$this->checkLastName($nameb, "blname");
		//$this->checkAddress($post, "bpostadd");
		//$this->checkCity($city, "bcity");
		//$this->checkCountry($country, "bcountry");
		if(!empty($fb_data['user_profile']['id'])){
			$FB_ID_exist= $database->IsFacebookIdExist($fb_data['user_profile']['id']);
			$fb_name= $fb_data['user_profile']['name'];
		}
		$checkName = false;
		if(empty($fb_data['user_profile'])){ 
			$form->setError('cntct_type', $this->error['empty_fbdate']);
		}elseif($FB_ID_exist>0){
			$form->setError('cntct_type', $this->error['exist_id']);
			$_SESSION['hide_fbmsg']= true;
		}/*elseif(!empty($email) && !empty($fb_data['user_profile']['email']) && !empty($fb_name) && !empty($namea) && !empty($nameb)){
			$fname=stripos($fb_name, $namea);
			$lname=stripos($fb_name, $nameb);
			if($fb_data['user_profile']['email']!= $email && $fname==false && $fname!=0 && $lname==false && $lname!=0){
				$form->setError('cntct_type', $this->error['fb_noteligible']);
				$_SESSION['hide_fbmsg']= true;
			}
		}*/
		
		/* By mohit 26-09 */
		// Facebook and Zidisha Account email, name commented as per the julia email
		/*
			elseif(!empty($fb_data['user_profile']['email']) && !empty($email)){
				if (strcasecmp($fb_data['user_profile']['email'], $email) != 0){
					//if($fb_data['user_profile']['email']!= $email){
					//$form->setError('cntct_type', $this->error['fb_noteligible']);
					//$_SESSION['hide_fbmsg']= true;
					$checkName = true;
				}
				else{
					$checkName = false;
				}
			}else{
				$checkName = true;
			}
			
			if($checkName){
				$fname=true;
				$lname=true;
				
				if(!empty($fb_name) && !empty($namea) && !empty($nameb)){
					$fname=stripos($fb_name, $namea);
					$lname=stripos($fb_name, $nameb);
				}

				if($fname===false && $lname===false){
					$form->setError('cntct_type', $this->error['fb_noteligible']);
					$_SESSION['hide_fbmsg']= true;
				}
			}
			*/
		

	/*	if(empty($home_no)) {
			$form->setError('home_no', $this->error['home_no']);
		} */
		if(empty($babout) || strlen($babout)<1){
			$form->setError('babout', $this->error['empty_e_babout']);
		}
		if(empty($bconfdnt) || strlen($bconfdnt)<1){
			$form->setError('bconfdnt', $this->error['empty_e_bconfdnt']);
		}
		$this->checkEmailForBorrower($email, "bemail");
		$this->checkMobile($mobile, "bmobile");
		$MobileExist=$database->IsMobileExist($mobile,$country);
		if(!empty($mobile)) {
			if($MobileExist > 0){
					$form->setError('bmobile', $lang['error']['exist_number']);
			}
		}
	//	$this->checkNationId($bnationid, "bnationid", $country);

	
	}
function validateActivateBorrower($pcomment, $ofclName, $OfclNumber)
	{
		global $form,$database;
		$path =	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);

		if(!$pcomment || strlen($pcomment)<1){
			$form->setError("comment", $lang['error']['prev_loancomment']);
		}
		if(!$ofclName || strlen($ofclName)<1){
			$form->setError("refOfficial_name", $lang['error']['officialName']);
		}
		
		if(!$OfclNumber || strlen($OfclNumber)<1){
			$form->setError('refOfficial_number', $lang['error']['empty_mobile']);
		}
		else if(!eregi("^([0-9])", $OfclNumber)){
			$form->setError('refOfficial_number', $lang['error']['invalid_mobile']);
		}
	}
	function validateRePaymentInstruction($country_code, $description)
	{
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		if($country_code!='ALL') {
			$this->checkCountry($country_code, "country_code");
		}
		$this->checkOrgDesc($description, "description");
	}
	function checkUsername($uname, $field, $edit=false)
	{
		global $form,$database;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		if(!$uname || strlen($uname)==0){
			$form->setError($field, $this->error['empty_username']);
		}
		else if(strlen($uname)<5){
			$form->setError($field, $this->error['short_username']);
		}
		else if(!eregi("^([0-9a-z])", $uname)){
			$form->setError($field, $this->error['invalid_username']);
		}
		else if(eregi(" ", $uname) && !$edit){
			$form->setError($field, $this->error['invalid_space']);
		}
		else 
		{
			if(!$edit)
			{
				if($database->usernameTaken($uname) > 0){
					$form->setError($field, $this->error['taken_username']);
				}
			}
		}
	}
	function checkPassword($pass1, $pass2, $field)
	{
		global $form;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		if(!$pass1 || strlen($pass1)==0){
			$form->setError($field, $this->error['empty_password']);
		}
		else if(strlen($pass1)<7){
			$form->setError($field, $this->error['short_password']);
		}
		else if($pass1 != trim($pass2)){
			$form->setError($field, $this->error['notmatch_password']);
		}
	}
	function checkFirstName($namea, $field)
	{
		global $form;
		if(!$namea || strlen($namea)<1){
			$form->setError($field, $this->error['empty_fname']);
		}
	}
	function checkLastName($nameb, $field)
	{
		global $form;
		if(!$nameb || strlen($nameb)<1){
			$form->setError($field, $this->error['empty_lname']);
		}
	}
	function checkName($name, $field, $id=0, $edit=false)
	{
		global $form,$database;
		if(!$name || strlen($name)<1){
			$form->setError($field, $this->error['empty_partnername']);
		}
		else
		{
			if($edit)
			{
				$cname=$database->getPartnerSelfName($id);
				if($cname!=$name)
					$edit=false;
			}
			if(!$edit)
			{
				if($database->checkPartnerName($name)==1)
				{
					$form->setError($field, $this->error['taken_partnername']);
				}
			}
		}
	}
	function checkAddress($post, $field)
	{
		global $form;
		if(!$post || strlen($post)<1){
			$form->setError($field, $this->error['empty_postal']);
		}
	}
	function checkCity($city, $field)
	{
		global $form;
		if(!$city || strlen($city)<1){
			$form->setError($field, $this->error['empty_city']);
		}
	}
	function checkCountry($country, $field)
	{
		global $form;
		if($country=='0'){
			$form->setError($field, $this->error['select_country']);
		}
	}
	function checkEmail($email, $field)
	{
		global $form;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		if(!$email || strlen($email)<1){ 
			$form->setError($field, $this->error['empty_email']);
		}
		else if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", $email)){
			$form->setError($field, $this->error['invalid_email']);
		}
	}
	function checkEmails($emails, $field)
	{
		global $form;
		if(strlen($emails)>0)
		{
			$email_ids =  explode(",",$emails);
			for($i=0; $i<count($email_ids); $i++)
			{
				if(!eregi("^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$", trim($email_ids[$i])))
				{
					$form->setError($field, $this->error['invalid_emails']);
				}
			}
		}
	}
	function checkMobile($mobile, $field)
	{
		global $form;
		if(!$mobile || strlen($mobile)<1){
			$form->setError($field, $this->error['empty_mobile']);
		}
		else if(!eregi("^([0-9])", $mobile)){
			$form->setError($field, $this->error['invalid_mobile']);
		}
	}
	function checkIncome($income, $field)
	{
		global $form;
		if(!$income || strlen($income)<1){
			$form->setError($field, $this->error['empty_income']);
		}
		else if(!eregi("^[0-9][0-9]", $income)){
			$form->setError($field, $this->error['invalid_income']);
		}
	}
	function checkMyDesc($about, $field)
	{
		global $form;
		if(!$about || strlen(trim($about))<1){
			$form->setError($field, $this->error['empty_intro']);
		}else if(strlen(trim($about)) <500) {
			$form->setError($field, $this->error['min_length_comment_b']);
		}
	}
	function checkBusinessDesc($bizdesc, $field)
	{
		global $form;
		if(!$bizdesc || strlen(trim($bizdesc))<1){
			$form->setError($field, $this->error['empty_busidesc']);
		}else if(strlen(trim($bizdesc)) <500) {
			$form->setError($field, $this->error['min_length_comment_b']);
		}

	}
	function checkOrgDesc($bizdesc, $field)
	{
		global $form;
		if(!$bizdesc || strlen($bizdesc)<1){
			$form->setError($field, $this->error['empty_intro']);
		}
	}
	function checkPhoto($photo, $field)
	{
		global $form;
		if(empty($photo)){
			$form->setError($field, $this->error['empty_photo']);
		}
	}
	function checkTnc($tnc, $field)
	{
		global $form;
		if(!$tnc){
			$form->setError($field, $this->error['empty_tnc']);
		}
	}
	function checkNationId($bnationid, $field, $country, $userid=0)
	{
		global $form, $database;
		if(!$bnationid || strlen($bnationid)<1){
			$form->setError($field, $this->error['empty_nationalid']);
			return;
		}
			$isnationidexist= $database->IsNationIdExist($bnationid, $country, $userid);
			if($isnationidexist>0){
				$form->setError($field, $this->error['exist_nationId']);
			}
	}
	/*function checkLoanHist($bloanhist, $field)
	{
		global $form;
		if(!$bloanhist || strlen($bloanhist)<1){
			$form->setError($field, $this->error['empty_loanhist']);
		}
	}*/
	function checkReferrer($referrer, $field)
	{
		global $form,$database;
		$res=$database->checkReferrer($referrer);
		if(!$res){
			$form->setError($field, $this->error['invalid_referrer']);
		}
	}
	function checkCommunityNameAndNo($community_name_no, $field)
	{
		global $form;
		if(!$community_name_no || strlen($community_name_no)<1){
			$form->setError($field, $this->error['empty_community_name_no']);
		}
	}
	function checkCapcha($user_guess, $field)
	{
		global $form;
		require_once('recaptcha/recaptchalib.php');
		$resp = recaptcha_check_answer (RECAPCHA_PRIVATE_KEY, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
			$form->setError($field, $resp->error);
		}
	}
	function checkGiftCard($card_code, $field)
	{
		global $form,$database;
		if(strlen($card_code) > 0)
		{
			$flag1=0;
			$res = $database->CheckGiftCardCode($card_code);
			if($res == 0 || $res == 2)
			{
				$form->setError($field, $this->error['invalid_cardcode']);
				$flag1=1;
			}
			if($flag1==0)
			{
				$res1 = $database->CheckGiftCardClaimed($card_code);
				if($res1 == 1)
				{
					$form->setError($field, $this->error['redeemed_cardcode']);
					$flag1=1;
				}
			}
			if($flag1==0)
			{
				$res2 = $database->CheckGiftCardExpired($card_code);
				if($res2 == 1)
				{
					$exp_date = $database->GetGiftCardExpireDate($card_code);
					$exdate = date ( 'M d, Y', $exp_date);
					$form->setError($field, $this->error['expired_cardcode']." ".$exdate);
				}
			}
		}
	}
	function checkAmount($amount, $field)
	{
		global $form;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		if(!$amount || strlen($amount)<0)
		{
			$form->setError($field, $lang['error']['invalid_amount']);
		}
		else if(!is_numeric($amount))
		{
			$form->setError($field, $lang['error']['invalid_amount']);
		}
	}
	function checkDated($date, $field)
	{
		global $form;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		if(!$date || strlen($date)<0)
		{
			$form->setError($field, $lang['error']['invalid_date']);
		}
	}
	function checkDocuments($documents, $uploadedDocs = null)
	{
		global $form;
		$supported=array("image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/x-png", "application/pdf");
		$allowedSize=2097152; // 2MB
		if(is_uploaded_file($documents['front_national_id']['tmp_name']) || !empty($documents['front_national_id']['tmp_name']))
		{	
			$type='';
			if(isset($documents['front_national_id']['type'])) {
				$type=$documents['front_national_id']['type'];
			}
			if(!in_array($type, $supported) && !$this->checkdocextension($documents['front_national_id']['name']))
			{	
				$form->setError("front_national_id", $this->error['invalid_document']);
			}
			else if(isset($documents['front_national_id']['size']) && $documents['front_national_id']['size'] > $allowedSize)
			{
				$form->setError("front_national_id", $this->error['maxsize_document']);
			}
		}else if(empty($uploadedDocs['0'])){
				$form->setError("front_national_id", $this->error['missing_nationalid_doc']);
		}
		/*if(is_uploaded_file($documents['back_national_id']['tmp_name']) || !empty($documents['back_national_id']['tmp_name']))
		{
			$type=$documents['back_national_id']['type'];
			if(!in_array($type, $supported) && !$this->checkdocextension($documents['back_national_id']['name']))
			{
				$form->setError("back_national_id", $this->error['invalid_document']);
			}
			else if($documents['back_national_id']['size'] > $allowedSize)
			{
				$form->setError("back_national_id", $this->error['maxsize_document']);
			}
		}else if(empty($uploadedDocs['1'])){
				$form->setError("back_national_id", $this->error['missing_nationalid_bkdoc']);
		}*/
		if(is_uploaded_file($documents['address_proof']['tmp_name']) || !empty($documents['address_proof']['tmp_name']))
		{
			$type=$documents['address_proof']['type'];
			if(!in_array($type, $supported) && !$this->checkdocextension($documents['address_proof']['name']))
			{
				$form->setError("address_proof", $this->error['invalid_document']);
			}
			else if($documents['address_proof']['size'] > $allowedSize)
			{
				$form->setError("address_proof", $this->error['maxsize_document']);
			}
		}else if(empty($uploadedDocs['2'])){
				$form->setError("address_proof", $this->error['missing_recform']);
		}
		/*if(is_uploaded_file($documents['legal_declaration']['tmp_name']) || !empty($documents['legal_declaration']['tmp_name']))
		{
			$type=$documents['legal_declaration']['type'];
			if(!in_array($type, $supported) && !$this->checkdocextension($documents['legal_declaration']['name']))
			{
				$form->setError("legal_declaration", $this->error['invalid_document']);
			}
			else if($documents['legal_declaration']['size'] > $allowedSize)
			{
				$form->setError("legal_declaration", $this->error['maxsize_document']);
			}
		}else if(empty($uploadedDocs['3'])){
			$form->setError("legal_declaration", $this->error['missing_leglcon']);
		} */
		
		if(!$this->checkdocextension($documents['legal_declaration2']['name']) && !empty($documents['legal_declaration2']['name']) && empty($uploadedDocs['4'])) {
			$form->setError("legal_declaration2", $this->error['invalid_document']);
		}
	}
	function setCustomError($field, $err)
	{
		global $form;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$form->setError($field, $lang['error'][$err]);
	}
	function checkRefferalCode($referral_code, $field)
	{	
		global $form,$database;
		if(strlen($referral_code) > 0)
		{
			$flag1=0;
			$res = $database->CheckReferralCode($referral_code);
			if($res == 0)
			{
				$form->setError($field, $this->error['invalid_referral_code']);
				$flag1=1;
			}
			if($flag1==0)
			{
				$res1 = $database->CheckRefferalcodeMaxUsed($referral_code);
				if($res1 == 0)
				{
					$form->setError($field, $this->error['max_used_referral_code']);
					$flag1=1;
				}
			}
			
			if($flag1==0)
			{
				$res2 = $database->CheckReferralCodeStatus($referral_code);
				if($res2 == 0){
				$form->setError($field, $this->error['inactive_referral_code']);
				$flag1=1;
				}
			}
			
			
			if($flag1==0)
			{ 
				$res3= $database->CheckReferralCodeUseRepeat($referral_code);
				if($res3== 0){
				$form->setError($field, $this->error['referral_code_used']);
				$flag1=1;
				}
			}
			
			if($flag1==0)
			{
				$res4= $database->CheckIpadress($referral_code);
				
				if($res4== 0){
				$form->setError($field, $this->error['referral_code_used']);
				$flag1=1;
				}
		}
		}
	}

function validateEmailedTo($borrowerid,$emailaddress,$ccaddress,$replyTo,$emailsubject,$emailmessage, $sendername)
	{	
		global $form,$database;
		if(!$emailaddress || strlen($emailaddress)<0)
		{
			$field='emailaddress';
			$form->setError($field, 'Please enter recipient email');
		}else{
			$this->checkEmail($emailaddress, "emailaddress");
		}
		if(!$replyTo|| strlen($replyTo)<0)
		{	
			$form->setError('replyTo', 'Please enter replyTo');
		}else{
			$this->checkEmail($replyTo, "replyTo");
		}
		if(!$emailsubject|| strlen($emailsubject)<0)
		{
			$form->setError('emailsubject', 'Please enter email subject');
		}
		if(!$emailmessage|| strlen($emailmessage)<0)
		{
			$form->setError('emailmessage', 'Please enter email message');
		}
		if(!$sendername|| strlen($sendername)<0)
		{
			$form->setError('sendername', 'Please enter name');
		}
	
	}
	function checkdocextension($docname)
	{	
		
		global $form,$database;
		$allow =	array("gif", "jpeg", "jpg", "pjpeg", "png",  "pdf");
		$ext = end(explode('.', $docname));
		if(in_array($ext, $allow)) {
			return true;
		}
		return false;
	}
	/*function checkLendingInstPhone($lending_inst_phone, $field)
	{
		global $form;
		if(!$lending_inst_phone || strlen($lending_inst_phone)<1){
			$form->setError($field, $this->error['lending_institution_phone']);
		}
		else if(!eregi("^([0-9])", $lending_inst_phone)){
			$form->setError($field, $this->error['invalid_lending_institution_phone']);
		}
	}*/
	function verify_borrower($identity_verify, $identity_verify_other, $participate_verification, $participate_verification_other, $app_know_zidisha, $app_know_zidisha_other, $how_contact, $how_contact_other, $recomnd_addr_locatable, $recomnd_addr_locatable_other, $commLead_know_applicant, $commLead_know_applicant_other , $commLead_recomnd_sign, $commLead_recomnd_sign_other, $commLead_mediate, $commLead_mediate_other, $eligible, $verifier_name){
		global $form;
		$path=	getEditablePath('error.php');
		include(FULL_PATH."editables/".$path);
		$this->error=$lang['error'];
		if(($identity_verify==-1 || $identity_verify=='') && empty($identity_verify_other)){
			$form->setError('is_identity_verify', $this->error['verify_borrower']);
		}
		if(($participate_verification==-1 || $participate_verification=='') && empty($participate_verification_other)){

			$form->setError('is_participate_verification', $this->error['verify_borrower']);
		}
		if(($app_know_zidisha==-1 || $app_know_zidisha=='') && empty($app_know_zidisha_other)){
			$form->setError('is_app_know_zidisha', $this->error['verify_borrower']);
		}
		if(($how_contact==-1 || $how_contact=='') && empty($how_contact_other)){
			$form->setError('is_how_contact', $this->error['verify_borrower']);
		}
		if(($recomnd_addr_locatable==-1 || $recomnd_addr_locatable=='') && empty($recomnd_addr_locatable_other)){
			$form->setError('is_recomnd_addr_locatable', $this->error['verify_borrower']);
		}
		if(($commLead_know_applicant==-1 || $commLead_know_applicant=='') && empty($commLead_know_applicant_other)){
			$form->setError('is_commLead_know_applicant', $this->error['verify_borrower']);
		}
		if(($commLead_recomnd_sign==-1 || $commLead_recomnd_sign=='') && empty($commLead_recomnd_sign_other)){
			$form->setError('is_commLead_recomnd_sign', $this->error['verify_borrower']);
		}
		if(($commLead_mediate==-1 || $commLead_mediate=='') && empty($commLead_mediate_other)){
			$form->setError('is_commLead_mediate', $this->error['verify_borrower']);
		}
		if($eligible==''){
			$form->setError('is_eligible', $this->error['verify_borrower']);
		}
		if(empty($verifier_name)){
			$form->setError('verifier_name_intrvw', $this->error['empty_name']);
		}
	}

	function checkEmailForBorrower($email, $field, $userid=0){
		global $form, $database;
		$this->checkEmail($email, $field);
		$emptyemailerr= $form->error($field);
		if(empty($emptyemailerr)){
			$Exist=$database->IsEmailExist($email, $userid);
			if($Exist>0){
					$form->setError($field, $this->error['exist_email']);
			}
		}
	}

	
}

$validation=new Validation;