<?php
 if(isset($_GET['gid'])){
 $gid = $_GET['gid'];
 $grp_details= $database->getlendingGrouops($gid);
 $userid=$session->userid;
 $gname = $grp_details['name'];
 $gwebsite = $grp_details['website'];
 $grpimg = urlencode($grp_details['image']);
 $abt_grp = $grp_details['about_grp'];
 $member_name1 = $grp_details['member_name1'];
 $member_email1 = $grp_details['member_email1'];
 $member_name2 = $grp_details['member_name2'];
 $member_email2 = $grp_details['member_email2'];
 $member_name3 = $grp_details['member_name3'];
 $member_email3 = $grp_details['member_email3'];
 $member_name4 = $grp_details['member_name4'];
 $member_email4 = $grp_details['member_email4'];
 $member_name5 = $grp_details['member_name5'];
 $member_email5 = $grp_details['member_email5'];
 $member_name6 = $grp_details['member_name6'];
 $member_email6 = $grp_details['member_email6'];
 $member_name7 = $grp_details['member_name7'];
 $member_email7 = $grp_details['member_email7'];
 $member_name8 = $grp_details['member_name8'];
 $member_email8 = $grp_details['member_email8'];
 $member_name9 = $grp_details['member_name9'];
 $member_email9 = $grp_details['member_email9'];
 $member_name10 = $grp_details['member_name10'];
 $member_email10 = $grp_details['member_email10'];
 $grp_leader= $grp_details['grp_leader'];

 $temp = $form->value("member_name1");
 if(isset($temp) && $temp != '')
 $member_name1=$form->value("member_name1");
 
 $temp = $form->value("member_email1");
 if(isset($temp) && $temp != '')
 $member_email1=$form->value("member_email1");
 
 $temp = $form->value("member_name2");
 if(isset($temp) && $temp != '')
 $member_name2=$form->value("member_name2");
 
 $temp = $form->value("member_email2");
 if(isset($temp) && $temp != '')
 $member_email2=$form->value("member_email2");
 
 $temp = $form->value("member_name3");
 if(isset($temp) && $temp != '')
 $member_name3=$form->value("member_name3");
 
 $temp = $form->value("member_email3");
 if(isset($temp) && $temp != '')
 $member_email3=$form->value("member_email3");
 
 $temp = $form->value("member_name4");
 if(isset($temp) && $temp != '')
 $member_name4=$form->value("member_name4");
 
 $temp = $form->value("member_email4");
 if(isset($temp) && $temp != '')
 $member_email4=$form->value("member_email4");
 
 $temp = $form->value("member_name5");
 if(isset($temp) && $temp != '')
 $member_name5=$form->value("member_name5");
 
 $temp = $form->value("member_email5");
 if(isset($temp) && $temp != '')
 $member_email5=$form->value("member_email5");
 
  $temp = $form->value("member_name6");
 if(isset($temp) && $temp != '')
 $member_name6=$form->value("member_name6");
 
 $temp = $form->value("member_email6");
 if(isset($temp) && $temp != '')
 $member_email6=$form->value("member_email6");
 
 $temp = $form->value("member_name7");
 if(isset($temp) && $temp != '')
 $member_name7=$form->value("member_name7");
 
 $temp = $form->value("member_email7");
 if(isset($temp) && $temp != '')
 $member_email7=$form->value("member_email7");

 $temp = $form->value("member_name8");
 if(isset($temp) && $temp != '')
 $member_name8=$form->value("member_name8");
 
 $temp = $form->value("member_email8");
 if(isset($temp) && $temp != '')
 $member_email8=$form->value("member_email8");
  
 $temp = $form->value("member_name9");
 if(isset($temp) && $temp != '')
 $member_name9=$form->value("member_name9");
 
 $temp = $form->value("member_email9");
 if(isset($temp) && $temp != '')
 $member_email9=$form->value("member_email9");
 
  $temp = $form->value("member_name10");
 if(isset($temp) && $temp != '')
 $member_name10=$form->value("member_name10");
 
 $temp = $form->value("member_email10");
 if(isset($temp) && $temp != '')
 $member_email10=$form->value("member_email10");
 
}
?>
<html>
<body>
<div class="span12">
<div style="float:right"><a href="index.php?p=104">Back to Borrowing Groups</a></div>
<br/><br/>
<?php 	if(empty($session->userid)) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>In order to continue, please <a href='javascript:void' onclick='getloginfocus()'>log in</a> or <a href='index.php?p=1&sel=2'>create a member account</a></strong>.</div>";
		unset($_SESSION['usernotloggedin']);
	}
else if($grp_leader===$session->userid || $session->userlevel==ADMIN_LEVEL){?>
		<div align='left' id='static'><h1>Edit Borrowing Group</h1></div>
		<BR/>
		<form action="updateprocess.php" method="POST" enctype="multipart/form-data">
			<table class='detail'>
				<tr height="20px"></tr>
				<tr>
					<td>Name of Group</td>
					<td> 
						<input type='text' style="width:300px" name='group_name' id='name' value="<?php echo $gname; ?>"><br/>
						<div id="error"><?php echo $form->error("group_name"); ?></div>
					</td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Website or Facebook (Optional)</td> 
					<td> <input type="text" style="width:300px"  name='website' value="<?php echo $gwebsite; ?>"></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Photo or Logo (Optional)</td> <td>
					<input type='FILE' name='group_photo'><img src = <?php echo SITE_URL."images/client/".$grpimg?> width='80px'></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>About This Group</td> <td> <textarea style="width:300px;height:220px" name='about_group' ><?php echo $abt_grp; ?></textarea><br/>
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
					<tr><td><input type="text" name="member_name1" value="<?php echo $member_name1; ?>"/><?php echo $form->error("member_name1"); ?></td><td><input type="text" name="member_email1" value="<?php echo $member_email1; ?>"/><?php echo $form->error("member_email1"); ?></td></tr>
					<tr><td>Member 2 Name</td><td>Member 2 Email</td></tr>
					<tr><td><input type="text" name="member_name2" value="<?php echo $member_name2; ?>"/><?php echo $form->error("member_name2"); ?></td><td><input type="text" name="member_email2" value="<?php echo $member_email2; ?>"/><?php echo $form->error("member_email2"); ?></td></tr>

					<tr><td>Member 3 Name</td><td>Member 3 Email</td></tr>
					<tr><td><input type="text" name="member_name3" value="<?php echo $member_name3; ?>"/><?php echo $form->error("member_name3"); ?></td><td><input type="text" name="member_email3" value="<?php echo $member_email3; ?>"/><?php echo $form->error("member_email3"); ?></td></tr>

					<tr><td>Member 4 Name</td><td>Member 4 Email</td></tr>
					<tr><td><input type="text" name="member_name4" value="<?php echo $member_name4; ?>"/><?php echo $form->error("member_name4"); ?></td><td><input type="text" name="member_email4" value="<?php echo $member_email4; ?>"/><?php echo $form->error("member_email4"); ?></td></tr>

					<tr><td>Member 5 Name</td><td>Member 5 Email</td></tr>
					<tr><td><input type="text" name="member_name5" value="<?php echo $member_name5; ?>"/><?php echo $form->error("member_name5"); ?></td><td><input type="text" name="member_email5" value="<?php echo $member_email5; ?>"/><?php echo $form->error("member_email5"); ?></td></tr>

					<tr><td>Member 6 Name</td><td>Member 6 Email</td></tr>
					<tr><td><input type="text" name="member_name6" value="<?php echo $member_name6; ?>"/><?php echo $form->error("member_name6"); ?></td><td><input type="text" name="member_email6" value="<?php echo $member_email6; ?>"/><?php echo $form->error("member_email6"); ?></td></tr>

					<tr><td>Member 7 Name</td><td>Member 7 Email</td></tr>
					<tr><td><input type="text" name="member_name7" value="<?php echo $member_name8; ?>"/><?php echo $form->error("member_name7"); ?></td><td><input type="text" name="member_email7" value="<?php echo $member_email7; ?>"/><?php echo $form->error("member_email7"); ?></td></tr>

					<tr><td>Member 8 Name</td><td>Member 8 Email</td></tr>
					<tr><td><input type="text" name="member_name8" value="<?php echo $member_name8; ?>"/><?php echo $form->error("member_name8"); ?></td><td><input type="text" name="member_email8" value="<?php echo $member_email8; ?>"/><?php echo $form->error("member_email8"); ?></td></tr>

					<tr><td>Member 9 Name</td><td>Member 9 Email</td></tr>
					<tr><td><input type="text" name="member_name9" value="<?php echo $member_name9 ?>"/><?php echo $form->error("member_name9"); ?></td><td><input type="text" name="member_email9" value="<?php echo $member_email9; ?>"/><?php echo $form->error("member_email9"); ?></td></tr>

					<tr><td>Member 10 Name</td><td>Member 10 Email</td></tr>
					<tr><td><input type="text" name="member_name10" value="<?php echo $member_name10; ?>"/><?php echo $form->error("member_name10"); ?></td><td><input type="text" name="member_email10" value="<?php echo $member_email10; ?>"/><?php echo $form->error("member_email10"); ?></td></tr>

				<tr height="40px"></tr>

				<tr>
				<input type='hidden' name='updatebgroup' value="<?php echo $gid; ?>" >
					<td></td>
					<td><input class='btn' type='submit' value='Update Changes' name='create'></td>
					
				</tr>
			</table>
		</form>
	</div>
	<?php }else {
				echo "<div>";
				echo $lang['admin']['allow'];
				echo "<br />";
				echo $lang['admin']['Please'];
				echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
				echo "</div>";
			}?>
</body>
</html>

