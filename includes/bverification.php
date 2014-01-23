<?php  
$identity_verify= '';
$identity_verify_other='';
$participate_verification= '';
$participate_verification_other='';
$eligible= '';
$recomnd_addr_locatable= '';
$recomnd_addr_locatable_other='';
$how_contact= '';
$how_contact_other='';
$commLead_mediate= '';
$commLead_mediate_other='';
$app_know_zidisha= '';
$app_know_zidisha_other='';
$commLead_know_applicant= '';
$commLead_know_applicant_other='';
$commLead_recomnd_sign= '';
$commLead_recomnd_sign_other='';
$additional_comments='';
$complete_later= '';
$conduct_intrvw= 0;
$is_eligible_ByAdmin='';
$bverificationDetail= $database->get_bverification_detail($userid);
if($bverificationDetail['complete_later']=='1'){
	$complete_later='1';
}
if(!empty($bverificationDetail)) {
	$lastloanamount= $database->get_loan_amount($userid);
	$identity_verify= $bverificationDetail['is_identity_verify'];
	if($identity_verify!='1' && $identity_verify!='0' && !empty($identity_verify)){
		$identity_verify_other= stripslashes($identity_verify);
		$identity_verify= '-1';
	}
	$participate_verification= $bverificationDetail['is_participate_verification'];
	if($participate_verification!='1' && $participate_verification!='0' && !empty($participate_verification)){
		$participate_verification_other= stripslashes($participate_verification);
		$participate_verification= '-1';
	}
	$eligible= $bverificationDetail['is_eligible'];

	$recomnd_addr_locatable= $bverificationDetail['is_recomnd_addr_locatable'];
	if($recomnd_addr_locatable!='1' && $recomnd_addr_locatable!='0' && !empty($recomnd_addr_locatable)){
		$recomnd_addr_locatable_other= stripslashes($recomnd_addr_locatable);
		$recomnd_addr_locatable= '-1';
	}
	$how_contact= $bverificationDetail['is_how_contact'];
	if($how_contact!='1' && $how_contact!='0' && !empty($how_contact)){
		$how_contact_other= stripslashes($how_contact);
		$how_contact= '-1';
	}
	$commLead_mediate= $bverificationDetail['is_commLead_mediate'];
	if($commLead_mediate!='1' && $commLead_mediate!='0' && !empty($commLead_mediate)){
		$commLead_mediate_other= stripslashes($commLead_mediate);
		$commLead_mediate= '-1';
	}
	$app_know_zidisha= $bverificationDetail['is_app_know_zidisha'];
	if($app_know_zidisha!='1' && $app_know_zidisha!='0' && !empty($app_know_zidisha)){
		$app_know_zidisha_other= stripslashes($app_know_zidisha);
		$app_know_zidisha= '-1';
	}
	$commLead_know_applicant= $bverificationDetail['is_commLead_know_applicant'];
	if($commLead_know_applicant!='1' && $commLead_know_applicant!='0' && !empty($commLead_know_applicant)){
		$commLead_know_applicant_other= stripslashes($commLead_know_applicant);
		$commLead_know_applicant= '-1';
	}
	$commLead_recomnd_sign= $bverificationDetail['is_commLead_recomnd_sign'];
	if($commLead_recomnd_sign!='1' && $commLead_recomnd_sign!='0' && !empty($commLead_recomnd_sign)){
		$commLead_recomnd_sign_other= stripslashes($commLead_recomnd_sign);
		$commLead_recomnd_sign= '-1';
	}
	$additional_comments= $bverificationDetail['additional_comments'];
	$conduct_intrvw= $bverificationDetail['conduct_intrvw'];
	$is_eligible_ByAdmin= $bverificationDetail['is_eligible_ByAdmin'];
	$verifier_name=$bverificationDetail['verifier_name'];
}
if($is_eligible_ByAdmin!=1 && $is_eligible_ByAdmin!=2){ 
	if($assign_status['Assigned_status']=='2'){
		$is_eligible_ByAdmin= 0;
	}
}

$temp= $form->value('is_identity_verify');
if(isset($temp) && $temp != '')
	$identity_verify=$form->value("is_identity_verify");
$temp= $form->value('is_identity_verify_other');
if(isset($temp) && $temp != '')
	$identity_verify_other=$form->value("is_identity_verify_other");

$temp= $form->value('is_participate_verification');
if(isset($temp) && $temp != '')
	$participate_verification=$form->value("is_participate_verification");
$temp= $form->value('is_participate_verification_other');
if(isset($temp) && $temp != '')
	$participate_verification_other=$form->value("is_participate_verification_other");

$temp= $form->value('is_eligible');
if(isset($temp) && $temp != '')
	$eligible=$form->value("is_eligible");

$temp= $form->value('is_recomnd_addr_locatable');
if(isset($temp) && $temp != '')
	$recomnd_addr_locatable=$form->value("is_recomnd_addr_locatable");
$temp= $form->value('is_recomnd_addr_locatable_other');
if(isset($temp) && $temp != '')
	$recomnd_addr_locatable_other=$form->value("is_recomnd_addr_locatable_other");

$temp= $form->value('is_how_contact');
if(isset($temp) && $temp != '')
	$how_contact=$form->value("is_how_contact");
$temp= $form->value('is_how_contact_other');
if(isset($temp) && $temp != '')
	$how_contact_other=$form->value("is_how_contact_other");

$temp= $form->value('is_commLead_mediate');

if(isset($temp) && $temp != '')
	$commLead_mediate=$form->value("is_commLead_mediate");
$temp= $form->value('is_commLead_mediate_other');
if(isset($temp) && $temp != '')
	$commLead_mediate_other=$form->value("is_commLead_mediate_other");

$temp= $form->value('is_app_know_zidisha');
if(isset($temp) && $temp != '')
	$app_know_zidisha=$form->value("is_app_know_zidisha");
$temp= $form->value('is_app_know_zidisha_other');
if(isset($temp) && $temp != '')
	$app_know_zidisha_other=$form->value("is_app_know_zidisha_other");

$temp= $form->value('is_commLead_know_applicant');
if(isset($temp) && $temp != '')
	$commLead_know_applicant=$form->value("is_commLead_know_applicant");
$temp= $form->value('is_commLead_know_applicant_other');
if(isset($temp) && $temp != '')
	$commLead_know_applicant_other=$form->value("is_commLead_know_applicant_other");

$temp= $form->value('is_commLead_recomnd_sign');
if(isset($temp) && $temp != '')
	$commLead_recomnd_sign=$form->value("is_commLead_recomnd_sign");
$temp= $form->value('is_commLead_recomnd_sign_other');
if(isset($temp) && $temp != '')
	$commLead_recomnd_sign_other=$form->value("is_commLead_recomnd_sign_other");

$temp= $form->value('additional_comments');
if(isset($temp) && $temp != '')
	$additional_comments=$form->value("additional_comments");

$temp= $form->value('is_eligible_ByAdmin');
if(isset($temp) && $temp != '')
	$is_eligible_ByAdmin=$form->value("is_eligible_ByAdmin");

$temp= $form->value('verifier_name');
if(isset($temp) && $temp != '')
	$verifier_name=$form->value("verifier_name");

$temp= $form->value('verifier_name_intrvw');
if(isset($temp) && $temp != '')
	$verifier_name=$form->value("verifier_name_intrvw");

?>
<?php if(isset($_SESSION['bverification_comlater'])) {?>
	<div id='changeSaved' align='center'><font color='green'><?php echo $lang['brwrlist-i']['complete_later'];?></font></div><br/>
<?php unset($_SESSION['bverification_comlater']);
	} 
if(($session->userlevel==ADMIN_LEVEL || $session->userlevel==PARTNER_LEVEL) && isset($_SESSION['display_verification'])){
	$is_eligible_ByAdmin =2;
}?>

<?php if($session->userlevel==ADMIN_LEVEL || $session->userlevel==PARTNER_LEVEL){ ?>
<form id="bverify_by_admin" name="bverify_by_admin" action="process.php" method="post">
	<table class='detail'>
		<tbody>
			<tr>
				<td>
					<h3 class="subhead"><?php echo $lang['brwrlist-i']['step2']; ?></h3>
					<?php $padding_top = '0px'; ?><a id="is_eligible_ByAdminerr"></a>

<br/>
1.  Review the below list of other Zidisha accounts that were created from the same <strong>IP address</strong> as this applicant.<br/><br/>
If there are any red flags, such as too many accounts being created in a short space of time from the same IP address, or other accounts from this IP address being declined or in arrears, then please ensure a telephone interview is conducted before activating this applicant.

<br/><br/><br/>


	<?php 
		$ip =  $database->getUserIP($userid);
		if(!empty($ip)){

	?>
	<table class="zebra-striped tablesorter_pending_borrowers">
	<thead>
		<tr>
		<th>Member Name</th>
		<th>Account Created</th>
		<th>Member Status</th>
		<th>Repayment Report Notes</th>
		</tr>
	</thead>
	<tbody>

	<?php
			$ip_set = $database->getAllIPUsers($ip); 
			foreach($ip_set as $row) {
					$clborrowerid = $row['userid'];
					$prurl = getUserProfileUrl($clborrowerid);
					$brwrandLoandetail = $database->getBrwrAndLoanStatus($clborrowerid);
					$zidisha_name= $database->getNameById($clborrowerid);
					$defaultLoanid=$database->getDefaultedLoanid($clborrowerid);
					$assignedStatus=$row['Assigned_status'];
						if($assignedStatus==1) {
							$activationdate=$database->getborrowerActivatedDate($clborrowerid);

							if(!empty($activationdate)){
								$activate_date=date('M d, Y', $activationdate);
							}else{
								$activate_date='';
							}
							if($brwrandLoandetail['overdue'] > 0 || $defaultLoanid) {
								$status1 = '<font color="red">Loan in Arrears</font>';
							}else{
								$status1 = "Not in Arrears";
							}		

							$status = "<span style='display:none'>$activationdate</span>Activated on $activate_date";
														}
						else if($assignedStatus==-1) {
							$status = 'Pending Verification';
							$status1 = "";
						}	
						else if($assignedStatus==2) {
							$status = '<font color="red">Declined</font>';
							$status1 = "";
						}
						elseif($assignedStatus==0) {
							$status = 'Pending Review';
							$status1 = "";
						}
						else {
							$status = '';
							$status1 = "";
						}
					$link='index.php?p=7&id='.$clborrowerid;
					$loanid=$brwrandLoandetail['loanid'];
					$repaysched= 'index.php?p=37&l='.$loanid.'&u='.$clborrowerid;
					$dynamic_data= $database->getRepayDynamicData($clborrowerid);                                                                                                      
               				$note = stripslashes($dynamic_data['note']);
					$link_date= date('d M Y', $row['date']);
					$endorsedbrwr= $database->getBrwrDetailFrmEndorser($clborrowerid);
					if(!empty($endorsedbrwr)){
						$endorsedname=$database->getNameById($endorsedbrwr['userid']);
						$status2 = $endorsedname;
					}
	?>
	<tr>
		<td>
		<?php echo "<a href='$prurl'>$zidisha_name</a>"; ?>
		</td>
		<td>
		<?php echo $link_date; ?>
		</td>
		<td>
		<?php echo "<a href='$link'>$status</a>"; ?><br/>
		<?php echo "<a href='$repaysched'>$status1</a>"; ?><br/>
		<?php echo $status2; ?>
		</td>
		<td>
		<?php echo $note; ?>
		</td>

	<?php	}
	} else {
		echo "<i>IP Address not recorded</i>";
	}

 ?>
	
	</tbody>
	</table>

<br/><br/><br/><br/>


2.  Review the following list of all applicants and members whose recommending <strong>Community Leader</strong> has the same phone number as this applicant's Community Leader.  

<br/><br/>If any applicants recommended by this Community Leader were declined, then do not activate this applicant without a telephone interview.  <br/><br/>
If the Recommendation Form was signed by a Community Leader that has been involved in fraudulent applications in the past, then please decline this applicant without an interview.

<br/><br/><br/>

	<table class="zebra-striped tablesorter_pending_borrowers">
	<thead>
		<tr>
		<th>Member Name</th>
		<th>Community Leader Name</th>
		<th>Community Leader Telephone</th>
		<th>Member Status</th>
		<th>Repayment Report Notes</th>
		</tr>
	</thead>
	<tbody>

	<?php 
if (!empty($rec_form_ofcr_no)) {
	$cl_borrowers=$database->getCLBorrowers($rec_form_ofcr_no);

	foreach ($cl_borrowers as $row) {

		$clborrowerid = $row['userid'];
		$prurl = getUserProfileUrl($clborrowerid);
		$firstname = $row['FirstName'];
		$lastname = $row['LastName'];
		$clname = $row['rec_form_offcr_name'];
		$brwrandLoandetail = $database->getBrwrAndLoanStatus($clborrowerid);
		$defaultLoanid=$database->getDefaultedLoanid($clborrowerid);
		$assignedStatus=$row['Assigned_status'];
		if($assignedStatus==1) {
			$activationdate=$database->getborrowerActivatedDate($clborrowerid);

			if(!empty($activationdate)){
				$activate_date=date('M d, Y', $activationdate);
			}else{
				$activate_date='';
			}
				if($brwrandLoandetail['overdue'] > 0 || $defaultLoanid) {
					$status1 = '<font color="red">Loan in Arrears</font>';
				}else{
					$status1 = "Not in Arrears";
				}		

			$status = "<span style='display:none'>$activationdate</span>Activated on $activate_date";
														}
		else if($assignedStatus==-1) {
			$status = 'Pending Verification';
			$status1 = "";
		}	
		else if($assignedStatus==2) {
			$status = '<font color="red">Declined</font>';
			$status1 = "";
		}
		elseif($assignedStatus==0) {
			$status = 'Pending Review';
			$status1 = "";
		}
		else {
			$status = '';
			$status1 = "";
		}
		$link='index.php?p=7&id='.$clborrowerid;
		$loanid=$brwrandLoandetail['loanid'];
		$repaysched= 'index.php?p=37&l='.$loanid.'&u='.$clborrowerid;
		$dynamic_data= $database->getRepayDynamicData($clborrowerid);                                                                                                      
        $note = stripslashes($dynamic_data['note']);


	?>
	<tr>
		<td>
		<?php echo "<a href='$prurl'>$firstname $lastname</a>"; ?>
		</td>
		<td>
		<?php echo $clname; ?>
		</td>
		<td>
		<?php echo $rec_form_ofcr_no; ?>
		</td>
		<td>
		<?php echo "<a href='$link'>$status</a>"; ?><br/>
		<?php echo "<a href='$repaysched'>$status1</a>"; ?>
		</td>
		<td>
		<?php echo $note; ?>
		</td>

	<?php	} 
} ?>
	
	</tbody>
	</table>

<br/><br/><br /><br/>


3.  Use the link below to check the <strong>Facebook account</strong> information submitted by the applicant.  

<br/><br/>Make sure the Facebook account link is still valid and the information appears consistent with that in the application.  Note that using a nickname in Facebook is not by itself grounds for ineligibility, but the gender and general biographical information should match.

<br/><br/><br/>
 
<table class='detail'>

	 <?php 

	if(!empty($fb_data)){

		if(isset($fb_data['user_friends']['data'])){
 
 			$no_of_friends= count($fb_data['user_friends']['data']);
 			
 		}else{
			
			$no_of_friends= count($fb_data['user_friends']);
 
 		}

 	} ?>
 
 	<tr>
 
 		<td>

 		<strong>Name:</strong>  <?php echo $fb_data['user_profile']['name'];?><br/><br/>

 		<strong>Friends:</strong>  <?php echo $no_of_friends; ?><br/><br/><br/>
 
 		<strong><a href="<?php echo 'http://www.facebook.com/'.$fb_data['user_profile']['id']; ?>" target="_blank">View Facebook Profile</a></strong>

 		</td>
 	

	</tr>

</table>              

<br/><br/><br/><br/>


4.  Review the below <strong>profile text</strong> to ensure it appears to have been genuinely written by the applicant.  If in doubt, paste an excerpt from the profile text into an internet search engine to ensure the content has not been copied from any other source.  If the content has been copied, the applicant is permanently disqualified. Select "This applicant is not eligible" below and include a note to please not reactivate this application.

<br/><br/><br/>

<?php 

	echo "<p><i>How did you hear about Zidisha?  </i>".$reffered_by; 
	
	echo "<p><i>About Me:  </i>".$about;
	
	echo"</p>";
	
	echo "<p><i>About My Business:  </i>".$bizdesc;
	
	echo"</p>";
													
?>

<br/><br/><br/><br/>



<?php 

$sift_score_num = $database->getSiftScore($userid);
$sift_score = (number_format($sift_score_num*100));
$sift_profile = "https://siftscience.com/console/users/".$userid;


if (!empty($sift_score) && $sift_score >50 && $sift_score <85){

	?>
	5. This applicant has a <strong>Sift Score</strong> of <strong><?php echo $sift_score; ?></strong>, indicating an unusually high level of risk. <br/><br/>

	Please review this applicant's Sift Science profile at the link below.  If you notice any red flags, please conduct a telephone interview or request a second opinion before activating this applicant.<br/><br/>

	<?php echo "<a href='".$sift_profile."' target='_blank'>View Sift Science profile</a>"; 

} elseif (!empty($sift_score) && $sift_score >=85){

	?>
	5. This applicant has a <strong>Sift Score</strong> of <strong><?php echo $sift_score; ?></strong>, indicating a very high level of risk. <br/><br/>

	Please consult the director before activating this applicant.<br/><br/>

	<?php echo "<a href='".$sift_profile."' target='_blank'>View Sift Science profile</a>"; 

} elseif (!empty($sift_score)){

	?>
	5. This applicant has a <strong>Sift Score</strong> of <strong><?php echo $sift_score; ?></strong>, indicating a low level of risk. <br/><br/>

	You may proceed without further review of the Sift Science score.
	<br/><br/><br/>

	<?php echo "<a href='".$sift_profile."' target='_blank'>View Sift Science profile</a>"; 

} ?>


<br/><br/><br/><br/>

<?php

$endorse_details= $database->getEndorserRecived($userid); 

if (!empty($endorse_details)){ ?>
	
	6.  Please review the <strong>endorsement(s)</strong> received by this applicant below. If you have reason to doubt that the responses are genuine (for example, the wording is too similar, too many from the same IP address, or the responses are not consistent with the information in the rest of the application) then please ensure a telephone interview is conducted before activating this applicant.<br/><br/>
	If the endorsements are obviously not genuine (for example, more than three from the same IP address or the exact same wording across multiple endorsements) then please decline this applicant without an interview.
	
	<br/><br/><br/>

	<?php 
	
	foreach($endorse_details as $endorse_detail){
		
		if(!empty($endorse_detail['endorserid'])){
			$e_profile = getUserProfileUrl($endorse_detail['endorserid']);
			$e_number= $database->getPrevMobile($endorse_detail['endorserid']);
			$e_ip_address = $database->getUserIP($endorse_detail['endorserid']);
 
			$endorsedbrwr= $database->getBrwrDetailFrmEndorser($endorse_detail['endorserid']);

			echo "<a href='$e_profile'>".$endorse_detail['ename']."</a>, ".$e_number."<br/>".$endorsedbrwr['e_know_brwr']."<br/>".$endorsedbrwr['e_cnfdnt_brwr']."<br/>IP Address:".$e_ip_address."<br/><br/>";
			}
	}
} ?>

<br/><br/><br/><br/>

					<strong>Please select one of the following:</strong><br/><br/>
					<input type='radio' name='is_eligible_ByAdmin' id="is_eligible_yes" value='1' onclick="show_no_text(this.value)"  <?php 	if($is_eligible_ByAdmin== '1')
							echo "checked";
					?>>My review of the application did not find any red flags or reason for ineligibility. Activate this applicant now.<br /><br />
					<input type='radio' name='is_eligible_ByAdmin' id='is_eligible_no' value='0' onclick="show_no_text(this.value)" <?php 	if($is_eligible_ByAdmin== '0')
							echo "checked";
					?>>This applicant is not eligible to join Zidisha.  Decline this applicant now. (Please explain the reason below.)<br /><br />
					<input type='radio' name='is_eligible_ByAdmin' id="is_eligible_intrvw" value='2' onclick="show_no_text(this.value)" <?php 	if($is_eligible_ByAdmin== '2')
							echo "checked";
					?>>I will conduct a telephone interview before activating this application.					<?php echo $form->error("is_eligible_ByAdmin"); ?><br/><br/>
					 Enter Your Name Here:<br/><br/>
					<input type="text" name="verifier_name" value="<?php echo $verifier_name; ?>" id="verifier_name" <?php if($is_eligible_ByAdmin==2) echo 'disabled'; ?> />
					<?php echo $form->error("verifier_name"); ?>
				</td>
			</tr><br/>
			<tr>
				<td>
					<textarea rows="10" cols="40" name="eligible_no_reason" id="eligible_no_reason" class="textareacmmn" style="<?php if($is_eligible_ByAdmin== '0') echo 'display'; else echo 'display:none';?>"><?php echo $details['declined_reason']; ?></textarea><?php echo $form->error("eligible_no_reason"); ?>
				</td>
			</tr>
			<tr>
				<td >
					<input type="hidden" name="borrowerid" value='<?php echo $userid?>' />
					<input type="hidden" name="verify_borrower_ByAdmin" />
					<input type="hidden" name="user_guess" value="<?php echo generateToken('verify_borrower_ByAdmin'); ?>"/>
					<input type='submit' class='btn' name='submit_bverification_ByAdmin' value='<?php echo $lang['brwrlist-i']['bverify_ByPartner']?>' <?php if($is_eligible_ByAdmin==2) echo 'disabled'; ?> id= 'submit_bverification_ByAdmin'>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php } 
$display_verification ='';
if($session->userlevel==ADMIN_LEVEL || $session->userlevel==LENDER_LEVEL){
$display_verification = 'display:none';
}
if($is_eligible_ByAdmin==2){
	$display_verification = '';
}?>
<form method="post" name="brwr_verification" style=<?php echo $display_verification;?> id="brwr_verification" action="process.php">
	<h3 class='subhead'><h3 class="subhead">Telephone Interview</h3>
						<?php $padding_top = '0px'; ?></h3><a id="brwr_verification_admin"></a>
	<strong>
		Please select a response to each of the questions below.  To save a partially completed Verification Report, click "Save and Complete Later."  When the verification is complete, click "Submit Final Report."
	</strong>
	<br/><br/><br/>
	<h3>Applicant Verification</h3>
	<table class='detail'>
		<tbody>
			<tr height="15px"></tr>
			<tr>
				<td width='25px' style="text-align:right; vertical-align: top;">1.</td>
				<td>
					Contact <?php echo trim($fname." ".$lname);?> at the telephone number provided, <strong><?php echo $telmob;?>.</strong> Introduce yourself and let the applicant know you will be asking some questions about his or her Zidisha application.<br/><br/>
				<td>				
			<tr>
			<tr>
				<td width='30px'></td>
				<td>
				
				Ask the applicant just one of the following questions to verify his or her identity:<br/><br/>
				<ul>
				<li>What is your residential address, including house or plot number? <br/>(Correct answer: 
					<?php 
						echo $address;
						echo", ";
						echo $home_no;
					?>)<br/><br/></li>
				<li>What is your date of birth?<br/>(<a href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=frontNationalId'; ?>">
											<?php echo $lang['brwrlist-i']['dwn_front_nation_id'];?></a> and ensure that it matches the date of birth recorded there.)<br/><br/></li>
				<li>What is your national identity number?<br/>(<a href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=frontNationalId'; ?>">
											<?php echo $lang['brwrlist-i']['dwn_front_nation_id'];?></a> and ensure that it matches the national identity number recorded there.)<br/><br/></li>
				</ul>
				<td>				
			<tr>
			<tr>
				<td>
				</td>
				<td><a id="is_identity_verify_err"></a>
					Was <?php echo trim($fname." ".$lname);?> able to answer the identity verification question correctly?<br/><br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_identity_verify_other_text')" name='is_identity_verify' value='1' id="is_identity_verify_yes" <?php 
						if($identity_verify== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_identity_verify_other_text')" name='is_identity_verify' id="is_identity_verify_no"value='0' <?php 	if($identity_verify== '0')
							echo "checked";
					?>>No
					<br/>
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_identity_verify_other_text')" name='is_identity_verify' id="is_identity_verify_other" value='-1' <?php 
								if($identity_verify== '-1')
										echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_identity_verify"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_identity_verify_other" id="is_identity_verify_other_text" class="textareacmmn" style="<?php if($identity_verify== '-1') echo 'display'; else echo 'display:none';?>"><?php
									echo $identity_verify_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>

		
			<tr height="15px"></tr>
			<tr>
				<td width='25px' style="text-align:right; vertical-align: top;">2.</td>
				<td>Ask the applicant just one of the following questions to verify his or her participation in the application process:<br/><br/>
				<ul>
				<li>When did you sign the Zidisha loan contract? <br/>(Open the <a target='_blank' href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=legalDeclaration'; ?>">first page</a> and <a target='_blank' href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=legal_declaration2'; ?>">second page</a> of the Loan Contract, and ensure that the date given by the applicant broadly matches the contract signature date.)<br/><br/></li>
				<li>Who signed the contract as a witness?<br/>(Open the <a target='_blank' href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=legalDeclaration'; ?>">first page</a> and <a target='_blank' href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=legal_declaration2'; ?>">second page</a> of the Loan Contract, and ensure that the information matches the witness name in the signature page.)<br/><br/></li>
				<li>What is your email address? <br/>(Correct answer: 
					<?php echo $email?>)<br/><br/></li>
				<li>What is your Zidisha account username? <br/>(Correct answer: 
					<?php 
				$username=$database->getUserNameById($userid);
				echo $username?>)<br/><br/></li>
				<li>Please describe the photo you uploaded to your Zidisha profile. <br/>(Ensure that what the applicant describes is consistent with the profile photo above.)<br/><br/></li>
				</ul>
				<a id="is_participate_verification_err"></a>	
				Was <?php echo trim($fname." ".$lname);?> able to answer the participation verification question correctly?<br/><br/>
				<input type='radio' onclick="hideerrormsg(this.value, 'is_participate_verification_other_text')" name='is_participate_verification' id="is_participate_verification_yes" value='1' <?php				if($participate_verification== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
				<input type='radio' onclick="showerrormsg('is_participate_verification_other_text')" onclick="showerrormsg()" name='is_participate_verification' id="is_participate_verification_no" value='0' <?php			if($participate_verification== '0')
						echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_participate_verification_other_text')" name='is_participate_verification' id="is_participate_verification_other" value='-1' <?php 
								if($participate_verification== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_participate_verification"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_participate_verification_other" id="is_participate_verification_other_text" class="textareacmmn" style="<?php if($participate_verification== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $participate_verification_other;
								?></textarea><?php echo $form->error("is_participate_verification_other"); ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>





			<tr>
				<td width='25px' style="text-align:right; vertical-align: top;">3.</td>

				<td>
					Ask the applicant one of the following questions to verify his or her understanding of how Zidisha works:<br/><br/>
				<ul>
				<li>How can you access the Zidisha website?<br/><br/></li>

				<li>Where does Zidisha loan money come from?<br/><br/></li>
				<li>What is a Zidisha Volunteer Mentor?<br/><br/></li>
				<li>What are Zidisha fees and interest rates? <br/> <br/></li>
				<li>How can Zidisha members increase their credit limit?<br/><br/></li>
				

				</ul><a id="is_app_know_zidisha_err"></a>
				Was <?php echo trim($fname." ".$lname);?> able to answer the question with correct information about Zidisha?<br/><br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_app_know_zidisha_other_text')" name='is_app_know_zidisha' id='is_app_know_zidisha_yes' value='1' <?php 		if($app_know_zidisha== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_app_know_zidisha_other_text')" name='is_app_know_zidisha' id='is_app_know_zidisha_no' value='0' <?php 		if($app_know_zidisha== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_app_know_zidisha_other_text')" name='is_app_know_zidisha' id='is_app_know_zidisha_other' value='-1' <?php 
								if($app_know_zidisha== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_app_know_zidisha"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_app_know_zidisha_other" id="is_app_know_zidisha_other_text" class="textareacmmn" style="<?php if($app_know_zidisha== '-1') echo 'display'; else echo 'display:none'; ?>" ><?php 
									echo $app_know_zidisha_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>







			<tr height="15px"></tr>
			<tr>
				<td style="text-align:right; vertical-align: top;">4.</td>
				<td>
						When the above information is verified, thank the applicant and let him or her know how they may contact us with questions or concerns.<br/><br/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><a id="is_how_contact_err"></a>
					Have you ensured the applicant knows how to contact us with questions or concerns?<br/><br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_how_contact_other_text')" name='is_how_contact' id='is_how_contact_yes' value='1' <?php if($how_contact== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_how_contact_other_text')" name='is_how_contact' id="is_how_contact_no" value='0' <?php 			if($how_contact== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_how_contact_other_text')" name='is_how_contact' id="is_how_contact_other" value='-1' <?php 
								if($how_contact== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_how_contact"); ?></td>
							<td>
								<textarea  rows="10" cols="40" name="is_how_contact_other" id="is_how_contact_other_text" class="textareacmmn" style="<?php if($how_contact== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $how_contact_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>

<!--
			<tr height='15px'></tr>
			<tr>
				<td colspan='2'><h3>Loan Officer Verification</h3></td>
				<td>
					
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					Contact <?php echo trim($fname." ".$lname);?>'s most recent loan officer <strong><?php echo $lending_inst_officer?></strong> of the lending institution <strong><?php echo $lending_inst_name?></strong> at the telephone number <strong><?php echo $lending_inst_phone?></strong>
				</td>
			</tr>
			<tr>
				<td style="text-align:right; vertical-align: top;">1.</td>
				<td>
					Introduce yourself, and ask <?php echo $lending_inst_officer?> to confirm for security purposes the address of <?php echo $lending_inst_name?> Ensure that it matches the address provided by the applicant:<br/>
					<strong><?php echo $lending_inst_add?></strong>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					Does the address cited by <?php echo $lending_inst_officer?> match the address provided by the applicant above?<br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_eligible_other_text')" name='is_eligible' id="is_eligible_yes" value='1' <?php 	if($eligible== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_eligible_other_text')" onclick="showerrormsg()" name='is_eligible' id='is_eligible_no' value='0' <?php 	if($eligible== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_eligible_other_text')" name='is_eligible' id='is_eligible_other' value='-1' <?php 
							if($eligible== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_eligible"); ?></td>
							<td>
								<textarea  rows="10" cols="40" name="is_eligible_other" id="is_eligible_other_text" class="textareacmmn" style="<?php if($eligible== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $eligible_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr height="15px"></tr>
			<tr>
				<td style="text-align:right; vertical-align: top;">2.
				</td>
				<td>
					Ask <?php echo $lending_inst_officer?> to confirm the <?php echo trim($fname." ".$lname);?>'s residential address.  Ensure that it matches the residential address provided by the applicant:<br/>
					<?php 
						echo $address;
						echo"<br/>";
						echo $home_no;
						echo"<br/>";
						echo$city." ".$country;
					?><br/>
					Does the address cited by <?php echo $lending_inst_officer?> match at minimum the general neighborhood of the residential address provided by the applicant above?<br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_know_applicant_other_text')" name='is_commLead_know_applicant' id='is_commLead_know_applicant_yes' value='1' <?php 
						if($commLead_know_applicant== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_commLead_know_applicant_other_text')" name='is_commLead_know_applicant' id='is_commLead_know_applicant_no' value='0' <?php 
						if($commLead_know_applicant== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_know_applicant_other_text')" name='is_commLead_know_applicant' id='is_commLead_know_applicant_other' value='-1' <?php 
							if($commLead_know_applicant== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_commLead_know_applicant"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_commLead_know_applicant_other" id="is_commLead_know_applicant_other_text" class="textareacmmn" style="<?php if($commLead_know_applicant== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $commLead_know_applicant_other;
								?></textarea><?php echo $form->error("is_commLead_know_applicant_other"); ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr height="15px"></tr>
			<tr>
				<td style="text-align:right; vertical-align: top;">3.</td>
				<td>
						Ask <?php echo $lending_inst_officer?> to confirm the <?php echo trim($fname." ".$lname);?>'s most recent loan amount, dates, and whether it was repaid on time and in full.  Ensure that the confirmation is broadly consistent with the most recent loan cited by the applicant:<br/>
						<strong><?php echo $loanHist?></strong>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					Is the loan history cited by <?php echo $lending_inst_officer?> broadly consistent with that cited by the applicant?<br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_how_contact_other_text')" name='is_how_contact' id='is_how_contact_yes' value='1' <?php if($how_contact== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_how_contact_other_text')" name='is_how_contact' id="is_how_contact_no" value='0' <?php 			if($how_contact== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_how_contact_other_text')" name='is_how_contact' id="is_how_contact_other" value='-1' <?php 
								if($how_contact== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_how_contact"); ?></td>
							<td>
								<textarea  rows="10" cols="40" name="is_how_contact_other" id="is_how_contact_other_text" class="textareacmmn" style="<?php if($how_contact== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $how_contact_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					Please enter the local currency amount of the applicant's most recent loan:
				</td>
			</tr>
			<?php $currency_name = $database->getUserCurrencyName($userid);?>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td width='275px'><span ><?php echo $currency_name?>:</span></td>
							<td>
								<input type='text' name='lastloanamount' id="lastloanamount" value="<?php 
								echo $lastloanamount;?>"><?php echo $form->error("lastloanamount"); ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					Was <?php echo trim($fname." ".$lname)?>'s most recent loan repaid in full?<br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_mediate_other_text')" name='is_commLead_mediate' id='is_commLead_mediate_yes' value='1' <?php 		if($commLead_mediate== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio'  onclick="showerrormsg('is_commLead_mediate_other_text')" name='is_commLead_mediate' id='is_commLead_mediate_no' value='0' <?php
						if($commLead_mediate== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_mediate_other_text')" name='is_commLead_mediate' id='is_commLead_mediate_other' value='-1' <?php 
								if($commLead_mediate== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_commLead_mediate"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_commLead_mediate_other" id="is_commLead_mediate_other_text" class="textareacmmn" style="<?php if($commLead_mediate== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $commLead_mediate_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					Was <?php  echo trim($fname." ".$lname)?>'s most recent loan repaid on time?<br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_app_know_zidisha_other_text')" name='is_app_know_zidisha' id='is_app_know_zidisha_yes' value='1' <?php 		if($app_know_zidisha== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_app_know_zidisha_other_text')" name='is_app_know_zidisha' id='is_app_know_zidisha_no' value='0' <?php 		if($app_know_zidisha== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_app_know_zidisha_other_text')" name='is_app_know_zidisha' id='is_app_know_zidisha_other' value='-1' <?php 
								if($app_know_zidisha== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_app_know_zidisha"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_app_know_zidisha_other" id="is_app_know_zidisha_other_text" class="textareacmmn" style="<?php if($app_know_zidisha== '-1') echo 'display'; else echo 'display:none'; ?>" ><?php 
									echo $app_know_zidisha_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>

-->

			<tr>
				<td colspan='2'><h3>Community Leader Verification</h3></td>
				<td></td>
		
			</tr>
		<tr height="15px"></tr>
			<tr>
				<td style="text-align:right; vertical-align: top;">1.
				</td>
				<td>
					Open the Recommendation Form submitted by <?php  echo trim($fname." ".$lname)?>: <?php if(!empty($details['addressProof'])) {?>
					<strong>
						<a href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=addressProof'; ?>">Open Recommendation Form</a>
					</strong>
					<?php } ?>
<br/><br/>

					 Contact the community leader who signed the Recommendation Form, <?php echo $rec_form_ofcr_name?>, at the telephone number provided, <?php echo $rec_form_ofcr_no?>.

<br/><br/>

Introduce yourself, and ask the community leader to confirm the address of his or her institution.  Ensure that it matches the address written in the Recommendation Form.<br/>
					
				</td>
			</tr>
			<tr>
				<td></td>
				<td><a id="is_recomnd_addr_locatable_err"></a>
					Does the institution address cited by the community leader match the institution address provided in the Recommendation Form?<br/><br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_recomnd_addr_locatable_other_text')" name='is_recomnd_addr_locatable' id='is_recomnd_addr_locatable_yes' value='1' <?php 
						if($recomnd_addr_locatable== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_recomnd_addr_locatable_other_text')" name='is_recomnd_addr_locatablet' id='is_recomnd_addr_locatable_no' value='0' <?php 
						if($recomnd_addr_locatable== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_recomnd_addr_locatable_other_text')" name='is_recomnd_addr_locatable' id='is_recomnd_addr_locatable_other' value='-1' <?php 
							if($recomnd_addr_locatable== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_recomnd_addr_locatable"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_recomnd_addr_locatable_other" id="is_recomnd_addr_locatable_other_text" class="textareacmmn" style="<?php if($recomnd_addr_locatable== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $recomnd_addr_locatable_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>


			<tr height="15px"></tr>

			<tr>
				<td width='25px' style="text-align:right; vertical-align: top;">
					2.
				</td>
				<td>
					
Ask the community leader to tell you how they know the applicant, and what makes them confident that the applicant will participate responsibly in the Zidisha lending community.<br/>
					
				</td>
			</tr>
			<tr>
				<td></td>
				<td><a id="is_commLead_know_applicant_err"></a>
					Was the leader able to tell you about the applicant?<br/><br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_know_applicant_other_text')"  name='is_commLead_know_applicant' id='is_commLead_know_applicant_yes' value='1' <?php 
						if($commLead_know_applicant== '1')
							echo "checked";
					?>>Yes &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_commLead_know_applicant_other_text')" name='is_commLead_know_applicant' id='is_commLead_know_applicant_no' value='0' <?php 
						if($commLead_know_applicant== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value,'is_commLead_know_applicant_other_text')" name='is_commLead_know_applicant' id='is_commLead_know_applicant_other' value='-1' <?php 
								if($commLead_know_applicant== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_commLead_know_applicant"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_commLead_know_applicant_other" id="is_commLead_know_applicant_other_text" class="textareacmmn" style="<?php if($commLead_know_applicant== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $commLead_know_applicant_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr height="15px"></tr>
			<tr>
				<td width='25px' style="text-align:right; vertical-align: top;">
					3.
				</td>
				<td>
					Ask the community leader to confirm that he or she signed the Recommendation Form for this applicant.<br/>
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
				<td><a id="is_commLead_recomnd_sign_err"></a>
					Does the community leader confirm that he or she signed the form?<br/><br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_recomnd_sign_other_text')" name='is_commLead_recomnd_sign' id="is_commLead_recomnd_sign_yes" value='1' <?php if($commLead_recomnd_sign== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' onclick="showerrormsg('is_commLead_recomnd_sign_other_text')" name='is_commLead_recomnd_sign' id="is_commLead_recomnd_sign_no" value='0' <?php 			if($commLead_recomnd_sign== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_recomnd_sign_other_text')" name='is_commLead_recomnd_sign' id="is_commLead_recomnd_sign_other" value='-1' <?php 
								if($commLead_recomnd_sign== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_commLead_recomnd_sign"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_commLead_recomnd_sign_other" id="is_commLead_recomnd_sign_other_text" class="textareacmmn" style="<?php if($commLead_recomnd_sign== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $commLead_recomnd_sign_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>

	<tr height='15px'></tr>


			<tr>
				<td width='25px' style="text-align:right; vertical-align: top;">
					4.
				</td>
				<td>
					Ask the community leader to confirm that he/she  is willing to mediate in the event of any difficulty contacting the applicant or recovering the loan.<br/>
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
				<td><a id="is_commLead_mediate_err"></a>
					Does the community leader confirm that he/she is willing to mediate?<br/><br/>
					<input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_mediate_other_text')" name='is_commLead_mediate' id='is_commLead_mediate_yes' value='1' <?php 		if($commLead_mediate== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio'  onclick="showerrormsg('is_commLead_mediate_other_text')" name='is_commLead_mediate' id='is_commLead_mediate_no' value='0' <?php
						if($commLead_mediate== '0')
							echo "checked";
					?>>No
				</td>
			</tr>
			<tr height="10px"></tr>
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td ><input type='radio' onclick="hideerrormsg(this.value, 'is_commLead_mediate_other_text')" name='is_commLead_mediate' id='is_commLead_mediate_other' value='-1' <?php 
								if($commLead_mediate== '-1')
									echo "checked";
							?>><span >Other (Please explain):</span><?php echo $form->error("is_commLead_mediate"); ?></td>
							<td>
								<textarea rows="10" cols="40" name="is_commLead_mediate_other" id="is_commLead_mediate_other_text" class="textareacmmn" style="<?php if($commLead_mediate== '-1') echo 'display'; else echo 'display:none'; ?>"><?php 
									echo $commLead_mediate_other;
								?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr>
				<td colspan='2'><h3>Other Comments</h3></td>
				<td>
					
				</td>
			</tr>

			<tr>
				<td style="text-align:right; vertical-align: top;"></td>
				<td>
					
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td><a id="is_eligible_err"></a>
					1. Based on the information provided, is this applicant eligible to join Zidisha?<br/><br/>
					<input type='radio' name='is_eligible' id="is_eligible_yes" value='1' <?php 	if($eligible== '1')
							echo "checked";
					?>>Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
					<input type='radio' name='is_eligible' id='is_eligible_no' value='0' <?php 	if($eligible== '0')
							echo "checked";
					?>>No (Please explain below)
<!--
<input type='radio' onclick="hideerrormsg(this.value, 'is_eligible_other_text')" name='is_eligible' id='is_eligible_other' value='-1' <?php 
							if($eligible== '-1')
									echo "checked";
							?>>

<span >Other (Please explain):</span>

-->

	<?php echo $form->error("is_eligible"); ?></td>
							
			</tr>

			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr>
							<td style='width:275px'>2. Additional Comments Regarding This Application (Optional):</td>
							<td>
								<textarea rows="10" cols="40" name="additional_comments" id="additional_comments" class="textareacmmn"><?php 
									echo $additional_comments;
								?></textarea>
							</td>
						</tr>
						<tr>
						<td>Enter Your Name Here:</td>
						<td><input type="text" name="verifier_name_intrvw" value="<?php echo $verifier_name; ?>" id="verifier_name_intrvw"/>
						<?php echo $form->error("verifier_name_intrvw"); ?></td>
						</tr>
					</table>
				</td>
			</tr>
				
			<tr>
				<td></td>
				<td>
					<table class='detail'>
						<tr style="display:none" id='noteligiblemsg'>
							<td colspan='2'>
								<strong>
									Based on the information you entered, this applicant is not eligible for a Zidisha loan. You may submit this report without conducting further verifications.
								</strong><br/><br/>
							</td>
						</tr>
						<tr>
							<td >
								<?php 
								$bassignedstatus = $database->getAssignedStatus($userid);
								?>
								<input type="hidden" name="bassignedstatus" id="bassignedstatus" value='<?php echo $bassignedstatus?>' />	
								<input type="hidden" name="borrowerid" value='<?php echo $userid?>' />
								<input type="hidden" name="verify_borrower" />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('verify_borrower'); ?>"/>
								<input type="hidden" name="verify_borrower" />
								<input type="hidden" name="complete_later" value="<?php echo $complete_later; ?>">
								<input type='submit' class='btn' name='submit_bverification' onclick="needToConfirm = false;" value='<?php echo $lang['brwrlist-i']['comlete_later']?>'>
							</td>
							<td>
								<input type='submit' class='btn' name='submit_bverification' onclick="needToConfirm = false;" value='<?php echo $lang['brwrlist-i']['submit_report']?>'>
							</td>
						</tr>
					</table>
				</td>
			</tr>

		</tbody>
	</table>
</form>
<?php unset($_SESSION['display_verification']);?>

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
<script type="text/javascript">
<!--
	function showerrormsg(str) {
		document.getElementById(str).style.display ='none';
		document.getElementById('noteligiblemsg').style.display ='';
		window.location.hash = '#noteligiblemsg';
	}
	function hideerrormsg(value, str) { 
		if(value=='-1'){
			document.getElementById(str).style.display ='';
		}
		else{
			document.getElementById(str).style.display ='none';
		}
		document.getElementById('noteligiblemsg').style.display ='none';
	}

	function show_no_text(value){
		if(value=='0'){
			document.getElementById('eligible_no_reason').style.display ='';
			document.getElementById('brwr_verification').style.display ='none';
			document.getElementById('submit_bverification_ByAdmin').disabled =false;
			document.getElementById('verifier_name').disabled =false;
		}else if(value=='1'){
			document.getElementById('eligible_no_reason').style.display ='none';
			document.getElementById('brwr_verification').style.display ='none';
			document.getElementById('submit_bverification_ByAdmin').disabled =false;
			document.getElementById('verifier_name').disabled =false;
		}else if(value=='2'){
			document.getElementById('eligible_no_reason').style.display ='none';
			document.getElementById('brwr_verification').style.display ='';
			document.getElementById('submit_bverification_ByAdmin').disabled =true;
			document.getElementById('verifier_name').disabled =true;
		}
	}
//-->
$("#brwr_verification :input").click(function (){
		var status = $('#bassignedstatus').val();	
		if(status!=-1 && status!=1 && status!=-2) {
			alert('Please complete Step 1: Review for Completeness above before commencing verification.');
		}
});

</script>
