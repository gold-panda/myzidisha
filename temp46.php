<?php
include("library/session.php");
global $database,$session;
$q1= "SET SESSION SQL_BIG_SELECTS=1";
$res= $db->query($q1);
$q="SELECT rs.userid, rs.loanid, rs.duedate, rs.amount, rs.paidamt FROM ! as rs join ! as br on rs.userid=br.userid  WHERE rs.amount > ?  AND ((rs.amount-rs.paidamt)>?*(select rate from excrate where currency=br.currency order by id desc limit 1) || paidamt is null)  AND `duedate`<(UNIX_TIMESTAMP() -?*24*60*60) group by rs.loanid";
$repay_borrower= $db->getAll($q, array('repaymentschedule', 'borrowers', 0, REPAYMENT_AMT_THRESHOLD, 30));
$templet="editables/email/simplemail.html";
$From= SERVICE_EMAIL_ADDR;
$replyTo = SERVICE_EMAIL_ADDR;
$Subject='Past Due Loan Mediation Requested';
$count=1;
foreach($repay_borrower as $borrower){
	$userid=$borrower['userid'];
	$loanid=$borrower['loanid'];
	$bdetail=$database->getBorrowerDetails($userid);
	$telnumber= $bdetail['TelMobile'];
	$country=$bdetail['Country'];
	$q="select late_repayment_reminders from ! where userid=?";
	$check_reminder= $db->getOne($q, array('borrowers_extn', $userid));
	$isforgive=$database->loanAlreadyInForgiveness($loanid);
	$q2="SELECT active FROM ! WHERE loanid = ?";
	$loanstatus = $db->getOne($q2,array('loanapplic', $loanid));
	if($isforgive<1 && $loanstatus!=LOAN_DEFAULTED && $loanstatus!=LOAN_REPAID && $check_reminder!='2'){
		require("editables/mailtext.php");
		$language= $database->getPreferredLang($userid);
		$path=  getEditablePath('mailtext.php',$language);
		require ("editables/".$path);
		$repay_inst= $database->getRepayment_InstructionsByCountryCode($country);
		$result=preg_replace("/[^0-9]+/", "", $telnumber);
		if($country=='KE'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+254', STR_PAD_LEFT);
		}
		if($country=='NE'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+227', STR_PAD_LEFT);
		}
		if($country=='SN'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+221', STR_PAD_LEFT);
		}
		if($country=='ID'){
			$to_number = substr($result, -11);
			$to_number=str_pad($to_number, 14, '+62', STR_PAD_LEFT);
		}
		if($country=='BF'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+226', STR_PAD_LEFT);
		}
		if($country=='GN'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+224', STR_PAD_LEFT);
		}
		if($country=='ML'){
			$to_number = substr($result, -9);
			$to_number=str_pad($to_number, 13, '+223', STR_PAD_LEFT);
		}
		if($country=='BJ'){
			$to_number = substr($result, -8);
			$to_number=str_pad($to_number, 12, '+229', STR_PAD_LEFT);
		}
		$params['bname']=$bdetail['FirstName']." ".$bdetail['LastName'];						$params['repay_inst']=$repay_inst['description'];
		$email=$bdetail['Email'];
		$To=$bdetail['FirstName']." ".$bdetail['LastName'];
		$params['contacts']='';
		if(!empty($bdetail['family_member1'])){
			$params['contacts'].="\n".$bdetail['family_member1']."\n".$bdetail['family_member2']."\n".$bdetail['family_member3']."\n".$bdetail['neighbor1']."\n".$bdetail['neighbor2']."\n".$bdetail['neighbor3'];
			if(!empty($bdetail['family_member1']))
				$session->sendMailToOtherMediation($bdetail['family_member1'], $country, $loanid, $telnumber, $language, $userid);
			if(!empty($bdetail['family_member2']))
				$session->sendMailToOtherMediation($bdetail['family_member2'], $country, $loanid, $telnumber, $language, $userid);
			if(!empty($bdetail['family_member3']))
				$session->sendMailToOtherMediation($bdetail['family_member3'], $country, $loanid, $telnumber, $language, $userid);
			if(!empty($bdetail['neighbor1']))
				$session->sendMailToOtherMediation($bdetail['neighbor1'], $country, $loanid, $telnumber, $language, $userid);
			if(!empty($bdetail['neighbor2']))
				$session->sendMailToOtherMediation($bdetail['neighbor2'], $country, $loanid, $telnumber, $language, $userid);
			if(!empty($bdetail['neighbor3']))
				$session->sendMailToOtherMediation($bdetail['neighbor3'], $country, $loanid, $telnumber, $language, $userid);
		}
		if(!empty($bdetail['rec_form_offcr_name'])){
			$params['contacts'].="\n".$bdetail['rec_form_offcr_name']." ".$bdetail['rec_form_offcr_num'];
			$session->sendMailToOtherMediation($bdetail['rec_form_offcr_name']." ".$bdetail['rec_form_offcr_num'], $country, $loanid, $telnumber, $language, $userid);
		}
		if(!empty($bdetail['refer_member_name'])){
			$refer_name=$database->getNameById($bdetail['refer_member_name']);
			$refer_number=$database->getPrevMobile($bdetail['refer_member_name']);
			$params['contacts'].="\n".$refer_name." ".$refer_number;
			$session->sendMailToMediation($bdetail['refer_member_name'], $userid, $loanid, $telnumber);
			
		}
		if(!empty($bdetail['mentor_id'])){
			$volunteer_name=$database->getNameById($bdetail['mentor_id']);
			$volunteer_number=$database->getPrevMobile($bdetail['mentor_id']);
			$params['contacts'].="\n".$volunteer_name;
			if(!empty($volunteer_number)){
				$params['contacts'].=" ".$volunteer_number;
			}
			$session->sendMailToMediation($bdetail['mentor_id'], $userid, $loanid, $telnumber);
		}
		$invitee= $database->getInvitee($userid);
		if(!empty($invitee)){
			$invitee_name=$database->getNameById($invitee);
			$invitee_number=$database->getPrevMobile($invitee);
			$params['contacts'].="\n".$invitee_name." ".$invitee_number;
			$session->sendMailToMediation($invitee, $userid, $loanid, $telnumber);
			
		}
		$endorsers=$database->getEndorserRecived($userid);
		if(!empty($endorsers)){
			foreach($endorsers as $endorser){
				$endorser_name=$database->getNameById($endorser['endorserid']);
				$endorser_number=$database->getPrevMobile($endorser['endorserid']);
				$params['contacts'].="\n".$endorser_name." ".$endorser_number;
				$session->sendMailToMediation($endorser['endorserid'], $userid, $loanid, $telnumber);
				
			}
		}
		if(!empty($bdetail['fb_data'])){
			$fb_data= unserialize(base64_decode($fb_detail['fb_data']));
			if(isset($fb_data['user_friends']['data'])){
				$friends= count($fb_data['user_friends']['data']);
			}else{
				$friends=count($fb_data['user_friends']);
			}
			$params['contacts'].="\n".$friends." friends linked to ".$fb_data['user_profile']['name']." Facebook profile";
		}
		$bmsg= $session->formMessage($lang['mailtext']['loanarrear_reminder_monthly'], $params);
		$content= $session->formMessage($lang['mailtext']['loanarrear_remindersms_monthly'], $params);
		$reply= $session->mailSending($From, $To, $email, $Subject, $bmsg,$templet, $replyTo);
		if($reply){
			$q="update ! set late_repayment_reminders=? where userid=?";
			$res= $db->query($q, array('borrowers_extn', 2, $userid));
		}
		$session->SendSMS($content, $to_number);
		$count++;
	}
}
echo $count;
echo 'complete';
?>