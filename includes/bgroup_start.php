<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<html>
<body>
	<div class="span12">
	<div style="float:right"><a href="index.php?p=104">Back to Borrowing Groups</a></div>
	<br/><br/>
	<?php 	if(empty($session->userid)) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>In order to continue, please <a href='javascript:void' onclick='getloginfocus()'>log in</a> or <a href='index.php?p=1&sel=2'>create a member account</a></strong>.</div>";
		unset($_SESSION['usernotloggedin']);
	} else if($session->userlevel==BORROWER_LEVEL || $session->userlevel==ADMIN_LEVEL){?>
		<div align='left' id='static'><h1>Start a New Borrowing Group</h1></div>
		<br/>
		<form action="process.php" method="POST" enctype="multipart/form-data">
			<table class='detail'>
				<tr height="20px"></tr>
				<tr>
					<td>Name of Group</td>
					<td> 
						<input type='text' style="width:300px" name='group_name' id='name' value=""><br/>
						<div id="error"><?php echo $form->error("group_name"); ?></div>
					</td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Website or Facebook (Optional)</td> 
					<td> <input type="text" style="width:300px"  name='website'></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Photo or Logo (Optional)</td> <td> 
					<input type='FILE' value="" name='group_photo'></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>About This Group</td> <td> <textarea style="width:300px;height:220px" name='about_group'></textarea><br/>
					<div id="error"><?php echo $form->error("about_group"); ?></div>
					</td>
				</tr>
				<tr height="20px"></tr></table>

<p>Please enter the names and email addresses of up to ten Zidisha members whom you would like to invite to join your group.</p>
<p>Be very careful to invite only people you know well and trust: these members' repayment performance will determine your Borrowing Group's reputation, and once you have invited a member, you may not remove that member from your group.</p>
<table class='detail'>
					<tr><?php echo $form->error("endorser"); ?></tr>

					<tr><td>&nbsp;</td></tr>

					<tr><td>Member 1 Name</td><td>Member 1 Email</td></tr>
					<tr><td><input type="text" name="member_name1" value="<?php echo $form->value("member_name1"); ?>"/><?php echo $form->error("member_name1"); ?></td><td><input type="text" name="member_email1" value="<?php echo $form->value("member_email1"); ?>"/><?php echo $form->error("member_email1"); ?></td></tr>
																<tr><td>Member 2 Name</td><td>Member 2 Email</td></tr>
					<tr><td><input type="text" name="member_name2" value="<?php echo $form->value("member_name2"); ?>"/><?php echo $form->error("member_name2"); ?></td><td><input type="text" name="member_email2" value="<?php echo $form->value("member_email2"); ?>"/><?php echo $form->error("member_email2"); ?></td></tr>

					<tr><td>Member 3 Name</td><td>Member 3 Email</td></tr>
					<tr><td><input type="text" name="member_name3" value="<?php echo $form->value("member_name3"); ?>"/><?php echo $form->error("member_name3"); ?></td><td><input type="text" name="member_email3" value="<?php echo $form->value("member_email3"); ?>"/><?php echo $form->error("member_email3"); ?></td></tr>

					<tr><td>Member 4 Name</td><td>Member 4 Email</td></tr>
					<tr><td><input type="text" name="member_name4" value="<?php echo $form->value("member_name4"); ?>"/><?php echo $form->error("member_name4"); ?></td><td><input type="text" name="member_email4" value="<?php echo $form->value("member_email4"); ?>"/><?php echo $form->error("member_email4"); ?></td></tr>

					<tr><td>Member 5 Name</td><td>Member 5 Email</td></tr>
					<tr><td><input type="text" name="member_name5" value="<?php echo $form->value("member_name5"); ?>"/><?php echo $form->error("member_name5"); ?></td><td><input type="text" name="member_email5" value="<?php echo $form->value("member_email5"); ?>"/><?php echo $form->error("member_email5"); ?></td></tr>

					<tr><td>Member 6 Name</td><td>Member 6 Email</td></tr>
					<tr><td><input type="text" name="member_name6" value="<?php echo $form->value("member_name6"); ?>"/><?php echo $form->error("member_name6"); ?></td><td><input type="text" name="member_email6" value="<?php echo $form->value("member_email6"); ?>"/><?php echo $form->error("member_email6"); ?></td></tr>

					<tr><td>Member 7 Name</td><td>Member 7 Email</td></tr>
					<tr><td><input type="text" name="member_name7" value="<?php echo $form->value("member_name7"); ?>"/><?php echo $form->error("member_name7"); ?></td><td><input type="text" name="member_email7" value="<?php echo $form->value("member_email7"); ?>"/><?php echo $form->error("member_email7"); ?></td></tr>

					<tr><td>Member 8 Name</td><td>Member 8 Email</td></tr>
					<tr><td><input type="text" name="member_name8" value="<?php echo $form->value("member_name8"); ?>"/><?php echo $form->error("member_name8"); ?></td><td><input type="text" name="member_email8" value="<?php echo $form->value("member_email8"); ?>"/><?php echo $form->error("member_email8"); ?></td></tr>

					<tr><td>Member 9 Name</td><td>Member 9 Email</td></tr>
					<tr><td><input type="text" name="member_name9" value="<?php echo $form->value("member_name9"); ?>"/><?php echo $form->error("member_name9"); ?></td><td><input type="text" name="member_email9" value="<?php echo $form->value("member_email9"); ?>"/><?php echo $form->error("member_email9"); ?></td></tr>

					<tr><td>Member 10 Name</td><td>Member 10 Email</td></tr>
					<tr><td><input type="text" name="member_name10" value="<?php echo $form->value("member_name10"); ?>"/><?php echo $form->error("member_name10"); ?></td><td><input type="text" name="member_email10" value="<?php echo $form->value("member_email10"); ?>"/><?php echo $form->error("member_email10"); ?></td></tr>

				<tr height="40px"></tr>

				<tr>
				<input type='hidden' name='bgroup' value='1'>
					<td></td>
					<td><input class='btn' type='submit' value='Publish Group' name='create'></td>
					
				</tr>
			</table>
		</form>
	</div>
	<?php }else {
				echo "<div>";
				echo $lang['admin']['allow'];
				echo "<br />";				echo "</div>";
			}?>
</body>
</html>
	<script type="text/javascript">
	<!--
		function getloginfocus() {
			document.getElementById("username").focus();
		}
	
	//-->
	</script>
