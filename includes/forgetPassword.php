<?php
include_once("library/session.php");
include_once("./editables/forgetPassword.php");
$path=	getEditablePath('forgetPassword.php');
include_once("editables/".$path);
include_once("error.php");
?>
<div class="span12">
<?php
	$part=0;//sets the default part for the login to either login table or profile links
				//chnge to check for if(logged_in)
	if($session->logged_in){
		$part=1;
	}
	if($part==0)
	{
		$select=0;
		if(isset($_GET["sel"])){
			$select=$_GET["sel"];
		}
		$err=0;
		if(isset($_GET["err"])){
			$err=$_GET["err"];
		}
		
		if($select==0)
		{
			echo "<div align='center' style='color:red'>".$form->error("forgeterror")."</div>";
			$email_err = $form->error("forgetemail");
			if(!empty($email_err)) {
				$err=0;
			}
			if($err==0)
			{
	?>
				<form method="post" action="updateprocess.php">
				<table class='detail' style="width:auto">
					<tbody>
						<tr>
							<td colspan=2><b><?php echo $lang['forgetPassword']['f_pass']?></b></td>
						</tr>
						<tr>
							<td colspan=2><?php echo $lang['forgetPassword']['reg_email_id']?></td>
						</tr>
						<tr>
							<td width="170px"><input  name="forgetemail" type="text"  value="<?php echo $form->value("forgetemail"); ?>" /></td>
							<td><div style="font-size:9px"><?php echo $form->error("forgetemail"); ?></div></td>
						</tr>
						<tr><td><br/></td></tr>
						<tr>
							<td>
								<input type="hidden" name="forgetpassword" />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('forgetpassword'); ?>"/>
								<input class='btn' type="submit" value="<?php echo $lang['forgetPassword']['Send_Pass']?>"/>
							</td>
						</tr>
					</tbody>
				</table>		
				</form>
	<?php	}
			else
			{
				
				$usernames= $database->getUserNamesByEmail($form->value("forgetemail"));
	?>
				<form method="post" action="updateprocess.php">
				<table class='detail' style="width:auto">
					<tbody>
						<tr>
							<td colspan=2><b><?php echo $lang['forgetPassword']['f_pass']?></b></td>
						</tr>					
						<tr>
							<td colspan=2><?php echo $lang['forgetPassword']['reg_email_id']?></td>
						</tr>
						<tr>
							<td><input  name="forgetemail" type="text"  value="<?php echo $form->value("forgetemail"); ?>" /></td>
							<td><div style="font-size:9px"><?php echo $form->error("forgetemail"); ?></div></td>
						</tr>
						<tr>
							<td colspan=2><?php echo $lang['forgetPassword']['Username']?></td>
						</tr>

						<tr>
							<td width="170px"><select id="forgetusername" name="forgetusername" style="width:140px">
									<option value='<?php echo GUEST_NAME ?>'></option>
							<?php foreach($usernames as $row){  ?>
									<option value='<?php echo $row['username'] ?>' <?php if($form->value("forgetusername")==$row['username'])echo "Selected"; ?>><?php echo $row['username'] ?></option>
							<?php }  ?>
									</select></td>
							<td><div style="font-size:9px"><?php echo $form->error("forgetusername"); ?></div></td>
						</tr>
						<tr><td><br/></td></tr>
						<tr>
							<td>
								<input type="hidden" name="forgetpassword" />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('forgetpassword'); ?>"/>
								<input class='btn' type="submit" value="<?php echo $lang['forgetPassword']['Send_Pass']?>"/>
							</td>
						</tr>
					</tbody>
				</table>		
				</form>
	<?php	}
		}
		else if($select==1)
		{
			echo $lang['forgetPassword']['newpass_ok'];
		}
	}
	else if($part==1)
	{
		echo $lang['forgetPassword']['editpass_ok'];
	}	?>
</div>