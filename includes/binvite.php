<?php 
$language=$database->getPreferredLang($session->userid);
require("editables/mailtext.php");
$path=  getEditablePath('mailtext.php',$language);
require ("editables/".$path);

require("editables/invite.php");
$path=	getEditablePath('invite.php', $language);
require("editables/".$path);

$userid = $session->userid;
$minrepayrate= $database->getAdminSetting('MinRepayRate');
$binvitecredit=$database->getcreditsettingbyCountry($session->userinfo['country'],3);
if(empty($binvitecredit)){
	$params['binvite_credit']=0;
}else{
	$params['binvite_credit']= $binvitecredit['loanamt_limit'];
}
$params['minreapayrate']= $database->getAdminSetting('MinRepayRate');
$eligible = $session->isEligibleToInvite($userid);
$currency  = $database->getUserCurrency($userid); 
$params['invited_link']= 'index.php?p=97';
$params['currency']= $currency;
$binvite_inst= $session->formMessage($lang['invite']['binvite_inst'], $params);
$binvite_eligible= $session->formMessage($lang['invite']['eligible'], $params);
$binvite_noteligible= $session->formMessage($lang['invite']['not_eligible'], $params);
$params['binvite_link']= SITE_URL.'microfinance/borrow.html';
$binvite_link= $session->formMessage($lang['invite']['binvite_link'], $params);
$borrower = $database->getEmailB($session->userid);
$name = $borrower['name'];
$bemail = $borrower['email'];
$subject = $lang['invite']['enter_subject']." ".$name;
$invite_body = $lang['mailtext']['invite_body1'];
if($form->value('user_name')) {
	$invite_body = $form->value('invite_body1');
}
if($form->value('user_name')) {
	$lemail = $form->value('user_email');
}
if($form->value('user_name')) {
	$name = $form->value('user_name');
}
if(isSet($_GET['v']) && $_GET['v']==1)
{
	echo "<div id='error' align='center'><font color='green'>".$lang['invite']['email_sent']."</font></div>";
}
$loginErr=$form->error("loginError");
if(!empty($loginErr))
{
	echo "<div id='error' align='center'><font color='red'>".$loginErr."</font></div>";
}
?>
<div class='span12'>
<div align='left' class='static'><h1><?php echo $lang['invite']['binvite_frnds'] ?></h1></div>
<?php echo $binvite_inst; ?><br/><br/>
<?php 

if($eligible==1){

	echo $binvite_eligible; ?>

	<br/><br/><br/>

	<form  action='updateprocess.php' method="POST">
	<table class='detail'>
		<tbody>
			<tr>
				<td style="padding-right:150px;"><?php echo $lang['invite']['binvite_email']; ?></td>
				<td><input type="text" name='frnd_email' id='frnd_email' value="<?php echo $form->value("frnd_email"); ?>" style="width:300px;"/><br/><div id="error"><?php echo $form->error("emailError"); ?></div></td>
			</tr>
			<tr><td><br/></td></tr>
			<tr>
				<td><?php echo $lang['invite']['enter_user_name'] ?></td>
				<td>
					<input type='text' style='width:300px' name='user_name' id='user_name' value="<?php echo $name;?>"><br/>
					<?php echo $form->error('user_name');?>
				</td>
			</tr>
			<tr><td><br/></td></tr>
			<tr>
				<td><?php echo $lang['invite']['enter_user_email'] ?></td>
				<td>
					<input type='text' style='width:300px' name='user_email' id='user_email' value="<?php echo $bemail; ?>"><br/>
						<?php echo $form->error('user_email');?>
				</td>
			</tr>
			<tr><td><br/></td></tr>
			<tr>
				<td><?php echo $lang['invite']['bsubject'] ?></td>
				<td>
					<input type='text' style='width:300px' name='invite_subject' id='invite_subject' value="<?php echo $form->value('invite_subject'); ?>"><br/>
						<?php echo $form->error('invite_subject');?>
				</td>
			</tr>
			<tr><td><br/></td></tr>
			<tr>
				<td><?php echo $lang['invite']['binvite_message'] ?></td>
				<td>
					<textarea style="width:300px;height:280px" name='invite_message' id='invite_message'><?php echo $form->value('invite_message'); ?></textarea><br/>
					<?php echo $form->error('invite_message');?>
				</td>
			</tr>
			<tr><td><br/></td></tr>
			<tr>
				<td><?php echo $lang['invite']['binvite_footer']; ?></td>
				<td style="width:300px;" >
					<?php echo $binvite_link; ?>
				</td>
			</tr>
			<tr><td><br/></td></tr>
			<tr>
				<td colspan=2 style='padding-right:25px;text-align:center'>
					<input type='hidden' name='binvite_frnd' id='binvite_frnd'>
					<input type="hidden" name="user_guess" value="<?php echo generateToken('invite_frnds'); ?>"/>
					<input class='btn' type='submit' value='Send Invite'>
				</td>
			</tr>
		</tbody>
	</table>
	</form>
<?php
}else{
	echo $binvite_noteligible;
} ?>
</div>