<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
<?php
include_once("library/session.php");
include_once("./editables/loginform.php");
$path=	getEditablePath('loginform.php');
include_once("editables/".$path);

$display='';
if(isset($_GET['t']) && $_GET['t']='1'){
	$display='none';
}

global $database;

$invitedstatus=$database->getInvitee($session->userid);
$binvitecredit=$database->getcreditsettingbyCountry($session->userinfo['country'],3);
$binvitecredit = $binvitecredit['loanamt_limit'];
$currency  = $database->getUserCurrency($session->userid); 
$brwr_repayrate= $session->RepaymentRate($session->userid);
$eligible = $session->isEligibleToInvite($session->userid);


?>
<div class="span12">
	<div class="row">
		<?php if(isset($_SESSION['bEmailVerifiedsentagain'])) {
					echo $lang['loginform']['emailconf_sent'];
					unset($_SESSION['bEmailVerifiedsentagain']);
				}
				if(isset($_SESSION['resend_endorser'])){
					echo $lang['loginform']['endorse_sent'];
					unset($_SESSION['resend_endorser']);
				}?>
		<div>
			<h3 class="subhead" style="display:<?php echo $display; ?>;"><?php echo $lang['loginform']['welcome'] ?></h3>

		<?php if($session->userlevel==BORROWER_LEVEL)
			{	
				$endorser= $database->IsEndorser($session->userid); 
				$defaultLoanid=$database->getDefaultedLoanid($userid);

				if($endorser==1){ 
					$borrowerid= $database->getBorrowerOfEndorser($session->userid); 
					$borrowername=$database->getNameById($borrowerid);
					$params['bname'] = $borrowername;
					$text = $session->formMessage($lang['loginform']['endorser_msg'], $params);
					echo $text;
				}else{
				
					$brwrandLoandetail = $database->getBrwrAndLoanStatus($session->userid);
					$tmpcurr = $database->getUserCurrency($userid);
					
					if($session->userinfo['emailVerified']==0) {
							$params['emailaddrs'] = $session->userinfo['email'];
							$text = $session->formMessage($lang['loginform']['emailntVerified'], $params);
							echo $text;
							?>
							<form style='display:inline' name='sendconfirmagain' method="post" action="updateprocess.php">
								<input type="hidden" name="sendconfirmagain">
								<input type="hidden" name="user_guess" value="<?php echo generateToken('sendconfirmagain'); ?>"/>
								<a href='javascript:void()' onclick="document.sendconfirmagain.submit();"><strong><?php echo $lang['loginform']['sendconfirmagain']?></strong></a>
								<br/>
							</form>
							<?php 	
							echo $lang['loginform']['emailntVerified1'];

						}else if($brwrandLoandetail['loanActive'] == LOAN_ACTIVE || $defaultLoanid) {
						$loanprurl = getLoanprofileUrl($userid, $brwrandLoandetail['loanid']);
						
						if($brwrandLoandetail['overdue'] > 0 || $defaultLoanid) {
							$loanid=$brwrandLoandetail['loanid'];
							$userid=$session->userid;
							$params['pastbalance'] = $tmpcurr." ".$brwrandLoandetail['overdueAmt'];
							$params['loanprofile'] = SITE_URL.$loanprurl."#acceptbids";
							$params['repaymentschedule'] = SITE_URL."index.php?p=37&l=$loanid";
							$text = $session->formMessage($lang['loginform']['Loanoverdue'], $params);
							echo $text;
						} else if($brwrandLoandetail['overdue'] == 0) {
									$loanid=$brwrandLoandetail['loanid'];
									$userid=$session->userid;
									$params['loanprofile'] = SITE_URL.$loanprurl."#acceptbids";
									$params['percentrepaid'] = $brwrandLoandetail['repaidPercent'].'%';
									$params['datedisbursed'] = date('F d, Y',$brwrandLoandetail['datedisbursed']);
									$params['repaymentschedule'] = SITE_URL."index.php?p=37&l=$loanid";
									$text = $session->formMessage($lang['loginform']['LoanNotoverdue'], $params);
									echo $text;
						}

					}else if($brwrandLoandetail['loanActive'] == LOAN_REPAID) {
						echo $lang['loginform']['LoanRepaid'];

					}else if($brwrandLoandetail['loanActive'] == LOAN_FUNDED) {
							echo $lang['loginform']['LoanNotdisbursed'];
					}else if($brwrandLoandetail['loanActive'] == LOAN_OPEN && $brwrandLoandetail['loanActive']!='') {
							if($brwrandLoandetail['percentFunded']< 100) {
								$loanid=$brwrandLoandetail['loanid'];
								$userid=$session->userid;
								$params['percentFunded'] = $brwrandLoandetail['percentFunded'];
								$loanprurl = getLoanprofileUrl($userid, $loanid);
								$params['loanprofile'] = SITE_URL.$loanprurl;
								$text = $session->formMessage($lang['loginform']['LoanPosted'], $params);
								echo $text;
							} 
						if($brwrandLoandetail['percentFunded'] >= 100) {
							$loanid=$brwrandLoandetail['loanid'];
							$userid=$session->userid;
							$params['loanprofile'] = SITE_URL."index.php?p=14&u=$userid&l=$loanid#acceptbids";
							$text = $session->formMessage($lang['loginform']['LoanFunded'], $params);
							echo $text;
						}

					} else {
						if(isset($brwrandLoandetail['iscomplete_later']) && $brwrandLoandetail['iscomplete_later']==1 && $brwrandLoandetail['brwrActive']==0) {
							echo $lang['loginform']['profile_uncomplete'];
						}else if(isset($brwrandLoandetail['iscomplete_later']) && $brwrandLoandetail['iscomplete_later']==0 && $brwrandLoandetail['brwrActive']==0) { 
							$country= $session->userinfo['country'];
							$fb_connect= $database->IsFacebook_connect($session->userid);
							$endorse_complete= $database->IsEndorsedComplete($session->userid);
							$minendorser= $database->getAdminSetting('MinEndorser');
							if($endorse_complete<$minendorser && $fb_connect){
								$endorse_details= $database->getEndorserDetail($session->userid);
								$params['minendorser'] = $minendorser;
								$text = $session->formMessage($lang['loginform']['endorse_nocomp'], $params);
								echo $text;
							?>
							<table>
								<thead>
									<tr>
										<th>Name</th>
										<th>Email</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
						<?php	foreach($endorse_details as $endorse_detail){
									if($endorse_detail['endorserid']==''){ 
									$id=$endorse_detail['id'];?>
										<form name='resendendorsermail' id="resendendorsermail<?php echo $id?>" method="post" action="updateprocess.php">
										<input type="hidden" name="resendendorsermail"/>
										<input type="hidden" name="id" value="<?php echo $id; ?>"/>
										<input type="hidden" name="user_guess" value="<?php echo generateToken('resendendorsermail'); ?>"/>
					<?php				$recived="Endorsement Not Received&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a
									href='javascript:void()' onclick='resendEndorsermail($id);'>Resend Email</a>";
									?>
										</form>
					<?php			}
									else
										$recived='Endorsement Received';
					?>					
										<tr><td><?php echo $endorse_detail['ename'];?></td><td><?php echo $endorse_detail['e_email']; ?></td><td><?php echo $recived; ?></td></tr>
										
						<?php	} ?>
								</tbody>
							</table>
				<?php		}else{

								$details=$database->getBorrowerById($session->userid); 	
								$assignedStatus=$details['Assigned_status'];

								if($assignedStatus!=2) { //if submitted but not yet activated, and not yet declined

									$last_modified=$details['LastModified'];
									$BorrowerReports=$database->getBorrowerReports($session->userid);
									$emailed_on=$BorrowerReports['sent_on'];

									//if the application is pending review by us 
									if(empty($emailed_on) || $last_modified > $emailed_on){ 
						
										$params['emailaddrs'] = $session->userinfo['email'];
										$profile_noaccepted = $session->formMessage($lang['loginform']['profile_noaccepted'], $params);
										echo $profile_noaccepted;

									//if the application is pending additional information requested by us
									} else {

										$params['message'] = $BorrowerReports['message'];
										$incomplete_message = $session->formMessage($lang['loginform']['incomplete_message'], $params);
										echo $incomplete_message;

									}
									

								}else{ //case of declined account

									echo $lang['loginform']['profile_declined'];
								}
							}
						}  
						else if($brwrandLoandetail['brwrActive']==1 ) {

							if(empty($invitedstatus)){ //if our site does not recognize this member as having been invited

								echo $lang['loginform']['invite_alert']; //display alert that they must have formal invite sent in order to qualify for bonus

							} else {
							
								echo $lang['loginform']['firstLoanNotPosted']; //display simple invite to post a loan application

							}
						}

					}

				}
				$mentor_id= $database->getBorrowerVolnteerMentor($session->userid);
				$volunteer_mentor=$database->getUserById($mentor_id);
				if($volunteer_mentor['userlevel']==BORROWER_LEVEL){
					$loanid= $database->getCurrentLoanid($mentor_id);
					if(empty($loanid))
						$link=getUserProfileUrl($mentor_id);
					else
						$link=getLoanprofileUrl($mentor_id,$loanid);
				}else{
					$link=getUserProfileUrl($mentor_id);
				}

				if($eligible==1){
	
					echo '<br/><br/><br/>'.$lang['loginform']['invite_frnd_msg1'].' '.number_format($brwr_repayrate).'%.  '.$lang['loginform']['invite_frnd_msg2'].'<br/><br/><div align="center"><a href="index.php?p=96"><input type="button" value="'.$lang['loginform']['invite_now'].'" class="btn"></a></div>';

				}

				echo '<br/><br/><br/>';

				if (!empty ($volunteer_mentor['TelMobile'])){
					echo "<br/><br/><br/>".$lang['loginform']['volunteer_text']."<a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['loginform']['tooltip_volunteer']."</span><span class='bottom'></span></span></a>";
					echo"<br/><br/><strong>".$lang['loginform']['name']."</strong>&nbsp;&nbsp;&nbsp;<a href='$link'>".$volunteer_mentor['name']."</a>";
					echo"<br/><strong>".$lang['loginform']['Telephone']."</strong>&nbsp;&nbsp;&nbsp;<a href='$link'>".$volunteer_mentor['TelMobile']."</a>";
				}

				echo '<br/><br/><br/>';

				echo"<br/><br/><br/><br/>".$lang['loginform']['do_more']."<br/><br/><br/>";
							
		}
		
		?>
		</div>
	</div>
</div>
<script>
function resendEndorsermail(id){
	document.getElementById('resendendorsermail'+id).submit();
}
</script>