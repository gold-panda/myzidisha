<?php
$id=$session->userid;
$data=$database->getLenderDetails($id);
$fname=$data['FirstName'];
$lname=$data['LastName'];
$name=$fname.' '.$lname;
$email=$data['Email'];
$desc=$data['About'];
$username=$data['username'];
$emailcomment=$data['emailcomment'];
$loan_app_notify=$data['loan_app_notify'];
$email_loan_repayment=$data['email_loan_repayment'];
$subscribe_newsletter=$data['subscribe_newsletter'];
$hideamt=$data['hide_Amount'];
$website=$data['website'];

//$hideamt1=$data['sendMail'];
$city=$data['City'];
$country=$data['Country'];
$lpass1='';
$temp = $form->value("lusername");
if(isset($temp) && $temp != '')
	$username=$form->value("lusername");
$temp = $form->value("lpass1");
if(isset($temp) && $temp != '')
	$lpass1=$form->value("lpass1");
$temp = $form->value("lfname");
if(isset($temp) && $temp != '')
	$fname=$form->value("lfname");
$temp = $form->value("llname");
if(isset($temp) && $temp != '')
	$lname=$form->value("llname");
$temp = $form->value("lemail");
if(isset($temp) && $temp != '')
	$email=$form->value("lemail");
$temp = $form->value("labout");
if(isset($temp) && $temp != '')
	$desc=$form->value("labout");
$temp = $form->value("lcity");
if(isset($temp) && $temp != '')
	$city=$form->value("lcity");
$temp = $form->value("lcountry");
if(isset($temp) && $temp != '')
	$country=$form->value("lcountry");
?>
<div class="row">
	<form enctype="multipart/form-data" method="post" id="sub-lender" name="sub-lender" action="updateprocess.php">
		<table class='detail'>
			<tbody>
				<tr>
					<td><div align='left' class='static'><h1><?php echo $lang['register']['update'] ?></h1></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['username'];?></td>
					<td><input type="text" readonly="readonly" name="lusername" maxlength="20" class="inputcmmn-1" value="<?php echo $username; ?>" /><br/><div id="bunerror"><?php echo $form->error("lusername"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo$lang['register']['NewPassword'];?></td>
					<td><input type="password" id="bpass1" name="lpass1" class="inputcmmn-1" value="<?php echo $lpass1; ?>" /><br/><div id="passerror"><?php echo $form->error("lpass1"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo$lang['register']['CNewPassword'];?></td>
					<td><input type="password" id="bpass2" name="lpass2" class="inputcmmn-1" /></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>

					<td><?php 
					if($session->usersublevel==LENDER_GROUP_LEVEL)
						echo $lang['register']['lgname'];
					else
						echo$lang['register']['fname'];?></td>
					<td><input type="text" name="lfname" id="lfname"  maxlength="15" class="inputcmmn-1" value="<?php echo $fname; ?>" /><br/><?php echo $form->error("lfname"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<?php 	if($session->usersublevel==LENDER_GROUP_LEVEL) { ?>
				<td><?php echo $lang['register']['lwebsite'];?></td>
				<td><input type="text" name="lwebsite" id="lwebsite" maxlength="25" class="inputcmmn-1" value="<?php echo $website ?>" /><br/><?php echo $form->error("lwebsite"); ?></td>
				<?php } 
				else { ?>
					<td><?php echo $lang['register']['lname'];?></td>
					<td><input type="text" name="llname" maxlength="15" class="inputcmmn-1" value="<?php echo $lname; ?>" /><br/><?php echo $form->error("llname"); ?></td>
					<?php }?>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['email'];?></td>
					<td><input type="text" id="bemail" name="lemail" maxlength="30" class="inputcmmn-1"  value="<?php echo $email; ?>" /><br/><div id="emailerror"><?php echo $form->error("lemail"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
			  <!--new row for editing lender s city and country-->
                <!-- new row for  city option in lender s account creation-->
                 <tr>
					<td><?php echo $lang['register']['City'];?></td>
					<td><input type="text" name="lcity" id="lcity" maxlength="25" class="inputcmmn-1" value="<?php echo $city; ?>" /><br/><?php echo $form->error("lcity"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
                <!-- new row for  country option in lender s account creation-->
                  <tr>
					<td><?php echo $lang['register']['Country'];?></td>
					<td>
						<select id="lcountry" name="lcountry" >
				<?php	$result1 = $database->countryList();
						$i=0;
						foreach($result1 as $state)
						{
							if($state['code'] == $country)
								echo "<option value='".$state['code']."' selected>".$state['name']."</option>";
							else
								echo "<option value='".$state['code']."'>".$state['name']."</option>";
						}
				?>
						</select>
						<br/><?php echo $form->error("lcountry"); ?>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php 
				if($session->usersublevel==LENDER_GROUP_LEVEL)
					echo $lang['register']['photo_logo'];
				else	
					echo $lang['register']['Photo'];?> <?php echo $lang['register']['l_optional'];?>
					</td>
					<td><input type="file" name="lphoto" id='lphoto' maxlength="15"  value="<?php echo $form->value("lphoto"); ?>" /><br/><?php echo $lang['register']['photo_msg'];?><br/><?php echo $form->error("lphoto"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td style="vertical-align:top"><?php echo $lang['register']['A_Yourself_l'];?></td>
					<td><textarea class="textareacmmn" name="labout" id="labout" style="height:130px;"><?php echo $desc; ?></textarea></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				  <tr>
					<td><strong><?php echo $lang['register']['A_Preferences_l'];?></strong></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
              <!--new row for choosing lender's mailing list option through radio button-->

				<!-- <tr>
					<td><?php echo $lang['register']['d_mailinglist_preferences'];?></td>
					<td>
						<?php if($hideamt1==0){?>
						<INPUT TYPE="Radio" id="hide_Amount1" name="hide_Amount1" value="0" tabindex="3" checked/><?php echo $lang['register']['yes'];?>&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="hide_Amount1" value="1" tabindex="4" /><?php echo $lang['register']['no'];?>
						<?php }else{?>
						<INPUT TYPE="Radio" id="hide_Amount1" name="hide_Amount1" value="0" tabindex="3" /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="hide_Amount1" value="1" tabindex="4" checked/><?php echo $lang['register']['no'];?>
						<?php } ?>
					</td>
				</tr>
                <tr><td>&nbsp;</td></tr> -->
				<tr>
					<td><?php echo $lang['register']['d_total_amt'];?></td>
					<td>
						<?php if($hideamt==0){?>
						<INPUT TYPE="Radio" id="hide_Amount" name="hide_Amount" value="0" tabindex="3" checked/><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" id='hide_Amount1' name="hide_Amount" value="1" tabindex="4" /><?php echo $lang['register']['no'];?>
						<?php }else{?>
						<INPUT TYPE="Radio" id="hide_Amount" name="hide_Amount" value="0" tabindex="3" /><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="hide_Amount" value="1" tabindex="4" checked/><?php echo $lang['register']['no'];?>
						<?php } ?>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				   <!-- new row for choosing lender's mailing list preferences OVER Loan Repayment is Credited to Account through radio button-->
				 <tr>
					<td><?php echo $lang['register']['d_mailinglist_preferences_onloanRepayment_credited'];?></td>
					<td>
						<?php if($email_loan_repayment==1){?>
						<INPUT TYPE="Radio" id="loan_repayment_credited" name="loan_repayment_credited" value="1" tabindex="3" checked/><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" id='loan_repayment_credited1' name="loan_repayment_credited" value="0" tabindex="4" /><?php echo $lang['register']['no'];?>
						<?php }else{?>
						<INPUT TYPE="Radio" id="loan_repayment_credited" name="loan_repayment_credited" value="1" tabindex="3" /><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="loan_repayment_credited" value="0" tabindex="4" checked/><?php echo $lang['register']['no'];?>
						<?php } ?>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
                <tr>
					<td><?php echo $lang['register']['d_mailinglist_preferences_onloanComment'];?></td>
					<td>
						<?php if($emailcomment==0){?>
						<INPUT type="Radio" id="postcomment" name="postcomment" value="1" tabindex="3" /><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT type="Radio" id="postcomment1" name="postcomment" value="0" tabindex="4" checked/><?php echo $lang['register']['no'];?>
						<?php } else { ?>
						<INPUT type="Radio" id="postcomment2" name="postcomment" value="1" tabindex="3" checked /><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT type="Radio" id="postcomment3" name="postcomment" value="0" tabindex="4" /><?php echo $lang['register']['no'];?>
						<?php } ?>
					</td>
                </tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['wd_new_loan_app'];?></td>
					<td>
						<?php if($loan_app_notify==0){?>
						<INPUT type="Radio" id="loan_app_notify" name="loan_app_notify" value="1" tabindex="3" /><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT type="Radio" id="loan_app_notify1" name="loan_app_notify" value="0" tabindex="4" checked/><?php echo $lang['register']['no'];?>
						<?php } else { ?>
						<INPUT type="Radio" id="loan_app_notif2" name="loan_app_notify" value="1" tabindex="3" checked /><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT type="Radio" id="loan_app_notif3" name="loan_app_notify" value="0" tabindex="4" /><?php echo $lang['register']['no'];?>
						<?php } ?>
					</td>
                </tr>
				 <tr><td>&nbsp;</td></tr>
				 <!-- New subscribe to monthly newsletter -->
				 <tr>
					<td><?php echo $lang['register']['mailinglist_preferences_subscribe_newsletter'];?></td>
					<td>
						<?php if($subscribe_newsletter==1){?>
						<INPUT TYPE="Radio" id="subscribe_newsletter" name="subscribe_newsletter" value="1" tabindex="3" checked/><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="subscribe_newsletter" value="0" tabindex="4" /><?php echo $lang['register']['no'];?>
						<?php }else{?>
						<INPUT TYPE="Radio" id="subscribe_newsletter" name="subscribe_newsletter" value="1" tabindex="3" /><?php echo $lang['register']['yes'];?>
						&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="subscribe_newsletter" value="0" tabindex="4" checked/>
						<?php echo $lang['register']['no'];?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=f5b7c5b570" target='_blank'>View a sample</a>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="2" style='text-align:center'>
						<input type="hidden" name="editlender" />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('editlender'); ?>"/>
						<input type="hidden" name="id" value="<?php echo $id ;?>" />
						<input class='btn' type="submit" onclick="needToConfirm = false;"  value="<?php echo $lang['register']['updatebut'];?>"  />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<script language="JavaScript">
	var ids = new Array('bpass1', 'bpass2', 'lfname','llname','lphoto','bemail','lcity','lcountry','hide_Amount','hide_Amount1','loan_repayment_credited','loan_repayment_credited','','bincome','babout','bbizdesc','front_national_id','back_national_id','address_proof','legal_declaration','postcomment','postcomment1','postcomment2','postcomment3','loan_app_notify','loan_app_notify1','loan_app_notify2','loan_app_notify3','labout','lwebsite');
	var values = new Array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
	var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>