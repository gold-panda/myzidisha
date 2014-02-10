<?php 
include_once("library/session.php");                     // created by chetan
include_once("./editables/admin.php");
include_once("./editables/invite.php");
include_once("./editables/mailtext.php");
$path=	getEditablePath('invite.php');
include_once("editables/".$path);
$path=	getEditablePath('mailtext.php');
include_once("editables/".$path);
?>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<div class='span12'>
<?php
$invalidLoan=true;
if($session->userlevel==LENDER_LEVEL || empty($session->userid))
{
	if(isSet($_GET['v']) && $_GET['v']==1)
	{
		echo "<div id='error' align='center'><font color='green'>".$lang['invite']['email_sent']."</font></div>";
	}
	$loginErr=$form->error("loginError");
	if(!empty($loginErr))
	{
		echo "<div id='error' align='center'><font color='red'>".$loginErr."</font></div>";
	}
	$loanid=0;
	$invalidLoan=false;
	$text1=$lang['invite']['enter_emails'];
	if(isset($_GET['l']))
	{
		$loanid=$_GET['l'];
		$loanDetail=$database->getLoanDetails($loanid);
		if(!empty($loanDetail))
		{
			$userDetail=$database->getUserById($loanDetail['borrowerid']);
				$text1=$lang['invite']['enter_emails_1']." ".$userDetail['firstname']."'s loan.";
		}
		else
		{
			$invalidLoan=true;
		}
	}

	if(!$invalidLoan)
	{
		$frnds_emails=$form->value('frnds_emails');
		$lender = $database->getEmail($session->userid);
		$name = $lender['name'];
		$lemail = $lender['email'];
		$subject = $lang['invite']['enter_subject']." ".$name;
		$invite_body = $lang['mailtext']['invite_body_l'];
		if($form->value('user_name')) {
			$invite_body = $form->value('invite_body_l');
		}
		if($form->value('user_name')) {
			$lemail = $form->value('user_email');
		}
		if($form->value('user_name')) {
			$name = $form->value('user_name');
		}
		
?>
	<div align='left' class='static'><h1><?php echo $lang['invite']['invite_friends'] ?></h1></div>
	<form  action='updateprocess.php' method="POST">
		<table class='detail'>
			<tbody>
				<tr>
					<td><?php echo $text1 ?></td>
					<td><textarea name='frnds_emails' id='frnds_emails' class='textareaEmail' style='width:300px' ><?php echo $frnds_emails; ?></textarea><br/><div id="error"><?php echo $form->error("emailError"); ?></div></td>
				</tr>
				<!--
				<tr>
					<td></td>
					<td>
						<a href="javascript:void(0)" onClick="window.open('inviter.php','mywindow','width=400,height=200,left=200,top=200,screenX=0,screenY=100,scrollbars=yes')"><?php echo $lang['invite']['import_contacts'] ?></a>
					</td>
				</tr>
				-->
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
						<input type='text' style='width:300px' name='user_email' id='user_email' value="<?php echo $lemail; ?>"><br/>
							<?php echo $form->error('user_email');?>
					</td>
				</tr>
				<tr><td><br/></td></tr>
				<tr>
					<td><?php echo $lang['invite']['subject'] ?></td>
					<td>
						<input type='text' style='width:300px' name='invite_subject' id='invite_subject' value="<?php echo $subject; ?>"><br/>
							<?php echo $form->error('invite_subject');?>
					</td>
				</tr>
				<tr><td><br/></td></tr>
				<tr>
					<td><?php echo $lang['invite']['invite_message'] ?></td>
					<td>
						<textarea style="width:300px;height:280px" name='invite_message' id='invite_message'><?php echo $invite_body; ?></textarea>
							<?php echo $form->error('invite_message');?>
					</td>
				</tr>
				<tr>
					<td colspan=2 style='padding-right:25px;text-align:right'>
						<input type='hidden' name='invite_frnds' id='invite_frnds'>
						<input type="hidden" name="user_guess" value="<?php echo generateToken('invite_frnds'); ?>"/>
						<input type='hidden' name='loanid' value='<?php echo $loanid;?>'><br/><br/>
						<input class='btn' type='submit' value='Send Invite'>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
	}
}
if($invalidLoan)
{
	echo "<div>";
	echo $lang['admin']['allow'];
	echo "<br />";
	echo $lang['admin']['Please'];
	echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
	echo "</div>";
}	?>
</div>