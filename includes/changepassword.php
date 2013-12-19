<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<div class='span12'>
<?php
if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL)
{
	if(isset($_SESSION['pchange']))
	{
		unset($_SESSION['pchange']);
		echo "<div align='center'><font color='green'><strong>Password changed successfully.</strong></font></div><br/><br/>";
	}
	$users = $database->getAllUsers();
?>
		<div>
			  <div style="float:left"><div align='left' class='static'><h1>Change Password</h1></div></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
<form action="process.php" method="post">
	<table class='detail'>
		<tr>
			<td><strong>Select User:</strong></td>
			<td>
				<select name="userid" id="userid">
					<option value="0">Select User</option>
					<?php for($i=0; $i<count($users); $i++){ ?>
						<option value="<?php echo $users[$i]['userid']?>" <?php if($users[$i]['userid']==$form->value("userid")) echo "selected" ?>><?php echo $users[$i]['username']?></option>
					<?php } ?>
				</select>
			</td>
			<td><?php echo $form->error("userid") ?></td>
		</tr>
		<tr>
			<td><strong>Password:</strong></td>
			<td><input type="text" name="password" id="password" size="24" value="<?php echo $form->value("password") ?>"></td>
			<td><?php echo $form->error("password") ?></td>
		</tr>
		<tr>
			<td><strong>Confirm Password:</strong></td>
			<td><input type="text" name="cpassword" id="cpassword" size="24" value="<?php echo $form->value("cpassword") ?>"></td>
			<td><?php echo $form->error("cpassword") ?></td>
		</tr>
		<tr>
			<td></td>
			<td><br/>
				<input class='btn' type="Submit" value="Change Password">
				<input type="hidden" name="changePassword">
				<input type="hidden" name="user_guess" value="<?php echo generateToken('changePassword'); ?>"/>
			</td>
		</tr>
	</table>
</form>
<?php
}
else
{
	echo "<div>";
	echo $lang['admin']['allow'];
	echo "<br />";
	echo $lang['admin']['Please'];
	echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
	echo "</div>";
}	?>
</div>