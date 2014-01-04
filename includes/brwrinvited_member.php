<script type="text/javascript" src="includes/scripts/eepztooltip.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("#stay-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
	$("#stay-target-2").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
});
</script>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>

<?php
include_once("./editables/invite.php");
$path=	getEditablePath('invite.php');
include_once("editables/".$path);

$userid=$session->userid;
$binvitecredit=$database->getcreditsettingbyCountry($session->userinfo['country'],3);
$currency= $database->getUserCurrency($userid);
$params['currency']= $currency;
$params['binvite_credit']= number_format($binvitecredit['loanamt_limit']);
$minrepayrate=$database->getAdminSetting('MinRepayRate');
$params['minreapayrate']= $minrepayrate;
$binvited_msg= $session->formMessage($lang['invite']['binvited_msg'], $params);
$invitedmember= $database->getInvitedMember($userid);
$success_percentage = $database->getInviteeRepaymentRate($userid);
$success_rate = number_format(($success_percentage)*100);
$TotBonus=$database->getInviteCredit($userid);

?>
<div class='span12'>
<div align='left' class='static'><h1><?php echo $lang['invite']['binvited'] ?></h1></div><br/>
<?php echo $binvited_msg; ?><br/><br/><br/>

<strong><?php echo $lang['invite']['success_rate'];?>: <a  style='margin-left:0px;cursor:pointer;' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['invite']['success_rate_tooltip'];?></span><span class='bottom'></span></span></a>&nbsp&nbsp&nbsp&nbsp<?php echo $success_rate; ?>%</strong>

<br/><br/>

<strong><?php echo $lang['invite']['bonus_earned'];?>: <a  style='margin-left:0px;cursor:pointer;' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['invite']['bonus_earned_tooltip'];?></span><span class='bottom'></span></span></a>&nbsp&nbsp&nbsp&nbsp<?php echo $currency.' '.number_format($TotBonus); ?></strong>

<br/><br/><br/>


<table class="zebra-striped tablesorter_pending_endorser">
		<thead>
			<tr>
				<th><?php echo $lang['invite']['name'] ?></th>
				<th><?php echo $lang['invite']['email'] ?></th>
				<th><?php echo $lang['invite']['status'] ?></th>
				<th><?php echo $lang['invite']['repay_rate'] ?></th>
				<th><?php echo $lang['invite']['bonus_credit'] ?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($invitedmember as $rows) {
				if($rows['invitee_id']==0){
					$name='';
					$status=$lang['invite']['invit_not_acc'];
					$brwrrepayratep='';
					$bonus=0;
					$invite_acc='';
				}else{
					$bdetail=$database->getBorrowerDetails($rows['invitee_id']);
					$borrowerid = $bdetail['userid'];
					$last_loan=$database->getLastloan($borrowerid);
					$invite_acc= date('d M, Y', $rows['date']);
					if(empty($last_loan)){
						$flag=1;
						$name = trim($bdetail['FirstName']." ". $bdetail['LastName']);
						if(!empty($bdetail['fb_data'])){
							$endorsed_comp= $database->IsEndorsedComplete($borrowerid);
							$minendorser= $database->getAdminSetting('MinEndorser');
							if($endorsed_comp<$minendorser)
								$flag=0;
						}
						$assigned_to=$bdetail['Assigned_to']; 
						$assigned_status=$bdetail['Assigned_status'];
						$BorrowerReports=$database->getBorrowerReports($borrowerid);
						$emailed_on=$BorrowerReports['sent_on'];
						$replyto=$BorrowerReports['replyto'];
						if($bdetail['iscomplete_later']==1 && $flag==1){
							$status=$lang['invite']['app_not_sub'];
						}elseif($assigned_status==-1){
							$status=$lang['invite']['app_pend_ver'];
						}elseif($assigned_status==-2 && $assigned_to!=0){ 
							$partner=$database->getNameById($assignedTo);
							$Assigned_date=date('M d, Y', $assignedDate);					
							$status='Sent to '.$partner.' on '.$Assigned_date;
						}elseif($assigned_to ==0 && !empty($BorrowerReports) && $assigned_status!=1){
							$Emailed_date=date('M d, Y',$emailed_on);
							if($rows['LastModified']>$emailed_on){ var_dump('hgjkh');
								$status = $lang['invite']['app_pend_review'];
							}
							else{
								$status=$lang['invite']['app_email'].$Emailed_date;
							}
						}else if($assigned_status ==2){
							$status=$lang['invite']['app_decline'];
						}elseif($assigned_status==0) {
							$status = $lang['invite']['app_pend_review'];
						}else{
							$status=$lang['invite']['no_loan'];
						}
						$brwrrepayratep='';
						$bonus=0;
					}else{
						$loan_status= $database->getBorrowerCurrentLoanStatus($borrowerid);
						$ontime= $database->isAllInstallmentOnTime($borrowerid, $last_loan['loanid']);
						$loanurl= getLoanprofileUrl($borrowerid, $last_loan['loanid']);
						$name = "<a href='$loanurl'>".trim($bdetail['FirstName']." ". $bdetail['LastName'])."</a>";
						if($loan_status==0){
							$status=$lang['invite']['fundraising_loan'];
						}elseif($ontime['missedInst']==0 && $loan_status=2){
							$status=$lang['invite']['ontime_loan'];
						}elseif($ontime['missedInst']!=0 && $loan_status=2){
							$status=$lang['invite']['due_loan'];
						}
						$brwrrepayrate= $session->RepaymentRate($borrowerid);
						$brwrrepayratep=number_format($brwrrepayrate)."%";
						if($brwrrepayrate>=$minrepayrate)
							$bonus=$binvitecredit['loanamt_limit'];
						else
							$bonus=0;

					}
				}?>
				<tr>
					<td><?php echo $name?></td>
					<td><?php echo $rows['email']?></td>
					<td><?php echo $status; ?></td>
					<td><?php echo $brwrrepayratep; ?></td>
					<td><?php echo $currency.' '.number_format($bonus); ?></td>
				</tr>
	<?php	}
		?>
		</tbody>
	</table>

</div>