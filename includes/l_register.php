<div class="row">
	<form enctype="multipart/form-data" method="post" id="sub-lender" name="sub-lender" action="process.php">
		<table class='detail'>
			<tbody>
				<!-- <tr>
					<td><?php echo $lang['register']['language'];?></td>
					<td>
						<select id="labellang" name="labellang" onchange="javascript:setLanguage(this.value);">
					<?php
							$langs= $database->getActiveLanguages();
							echo "<option value='en'>English</option>";
							foreach($langs as $row)
							{  ?>
								<option value='<?php echo $row['langcode'] ?>' <?php if($language==$row['langcode'])echo "Selected='true'";?>><?php echo $row['lang']?></option>
					<?php	}
						?>
						</select>
					</td>
				</tr>
 -->
				 <tr><td>&nbsp;</td></tr>

<tr>
<?php 
					if($select==6){
						echo "<strong>This page allows you to create a donation account.  Donation accounts differ from regular lender accounts in that lending credit uploaded to these accounts is a tax-deductible donation which cannot be withdrawn or refunded.  <br/><br/>You will be able to control the use of the lending credit uploaded to this account as long as it remains active.  Please see our Terms of Use for more details.</strong>";
?>

<div style="display: none"><INPUT TYPE="Radio" id="DonationAcct" name="DonationAcct" value="1" tabindex="3" checked/></div>

					<?php } ?> </tr>
			<tr>
					<td>

					<?php echo $lang['register']['username'];?><font color="red">*</font></td>
					<td><input type="text" id="busername" name="lusername" maxlength="20" class="inputcmmn-1" value="<?php echo $form->value("lusername"); ?>" /><br/><div id="bunerror"><?php echo $form->error("lusername"); ?></div></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo$lang['register']['ppassword'];?><font color="red">*</font></td>
					<td><input type="password" id="bpass1" name="lpass1" class="inputcmmn-1" value="<?php echo $form->value("lpass1"); ?>" /><br/><div id="passerror"><?php echo $form->error("lpass1"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo$lang['register']['CPassword'];?><font color="red">*</font></td>
					<td><input type="password" id="bpass2" name="lpass2" class="inputcmmn-1" /></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<?php 
						if($select==5)
							echo $lang['register']['lgname'];
						else 
							echo$lang['register']['fname'];?>
					<font color="red">*</font></td>
					<td><input type="text" name="lfname" id="lfname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lfname"); ?>" /><br/><?php echo $form->error("lfname"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
				<?php 	if($select==5) { ?>
				<td><?php echo $lang['register']['lwebsite'];?></td>
				<td><input type="text" id='lwebsite' name="lwebsite" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lwebsite"); ?>" /><br/><?php echo $form->error("lwebsite"); ?></td>
				
				<?php } else { ?>
				<td><?php echo $lang['register']['lname'];?><font color="red">*</font></td>
				<td><input type="text" name="llname" id='llname' maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("llname"); ?>" /><br/><?php echo $form->error("llname"); ?></td>
				<?php }?>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['email'];?><font color="red">*</font></td>
					<td><input type="text" id="bemail" name="lemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("lemail"); ?>" /><br/><div id="emailerror"><?php echo $form->error("lemail"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				 <!-- new row for  city option in lender s account creation-->
                 <tr>
					<td><?php 
					if($select==5)
						echo $lang['register']['lgcity'];
					else
						echo $lang['register']['City'];?><font color="red">*</font></td>
					<td><input type="text" name="lcity" id='lcity' maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lcity"); ?>" /><br/><?php echo $form->error("lcity"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
                <!-- new row for  country option in lender s account creation-->
                <tr>
					<td><?php 
					if($select==5)
						echo $lang['register']['lgcountry'];
					else
						echo $lang['register']['Country'];?><font color="red">*</font></td>
					<td>
						<select id="lcountry" id="lcountry" name="lcountry" >
							<option value='US'>United States</option>
					<?php
								$result1 = $database->countryList();
								$i=0;
								foreach($result1 as $state)
								{	
									if($state['code']=='US')
									{	
										continue;
									}	?>
									<option value='<?php echo $state['code'] ?>' <?php if($form->value("lcountry")==$state['code']) echo "selected" ?>><?php echo $state['name'] ?></option>
						<?php		$i++;
								}
				?>
						</select>
						<br/><?php echo $form->error("lcountry"); ?>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php 
					if($select==5)
						echo $lang['register']['photo_logo'];
					else 
						echo $lang['register']['Photo'];
						?> 
					<?php echo $lang['register']['l_optional'];?>
					<br/><?php echo $lang['register']['photo_msg'];?></td>
					<td><input type="file" name="lphoto" id='lphoto' value="<?php echo $form->value("lphoto"); ?>" />
				<?php	$isPhoto_select=$form->value("isPhoto_select");
					    if(!empty($isPhoto_select))
						{	?>
							<img class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.$isPhoto_select ?>" height="50" width="50" alt=""/>
				<?php	}	?>
					<br/><?php echo $form->error("lphoto"); ?>
					<input type="hidden" name="isPhoto_select" value="<?php echo $form->value("isPhoto_select"); ?>" /></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['A_Yourself_l'];?> <?php echo $lang['register']['l_optional'];?></td>
					<td><textarea class="textareacmmn" name="labout" id="labout" style="height:130px;"><?php echo $form->value("labout"); ?></textarea><br/><br/></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><strong><?php echo $lang['register']['A_Preferences_l'];?></strong></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <!-- new row for choosing lender's mailing list preferences through radio button-->
                 <!-- <tr>
					<td><?php echo $lang['register']['d_mailinglist_preferences'];?></td>
					<td>
						<INPUT TYPE="Radio" id="hide_Amount1" name="hide_Amount1" value="0" tabindex="3" checked/><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="hide_Amount1" value="1" tabindex="4"  /><?php echo $lang['register']['no'];?>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr> -->
				<tr>
					<td><?php echo $lang['register']['d_total_amt'];?></td>
					<td>
						<INPUT TYPE="Radio" id="hide_Amount" name="hide_Amount" value="0" tabindex="3" checked/><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" id="hide_Amount1" name="hide_Amount" value="1" tabindex="4"  /><?php echo $lang['register']['no'];?>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				  <!-- new row for choosing lender's mailing list preferences OVER Loan Repayment is Credited to Account through radio button-->
                 <tr>
					<td><?php echo $lang['register']['d_mailinglist_preferences_onloanRepayment_credited'];?></td>
					<td>
						<INPUT TYPE="Radio" id="loan_repayment_credited" name="loan_repayment_credited" value="1" tabindex="3" checked /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" id='loan_repayment_credited1' name="loan_repayment_credited" value="0" tabindex="4"  /><?php echo $lang['register']['no'];?>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <!-- new row for choosing lender's mailing list preferences OVER COMMENT ON THEIR LOAN through radio button-->
                 <tr>
					<td><?php echo $lang['register']['d_mailinglist_preferences_onloanComment'];?></td>
					<td>
						<INPUT TYPE="Radio" id="loan_comment" name="loan_comment" value="1" tabindex="3" checked /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" id='loan_comment1' name="loan_comment" value="0" tabindex="4"  /><?php echo $lang['register']['no'];?>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['wd_new_loan_app'];?></td>
					<td>
						<INPUT TYPE="Radio" id="loan_app_notify" name="loan_app_notify" value="1" tabindex="3" checked /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" id='loan_app_notify1' name="loan_app_notify" value="0" tabindex="4"  /><?php echo $lang['register']['no'];?>
					</td>
				</tr>
				<!-- New subscribe to monthly newsletter -->
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['mailinglist_preferences_subscribe_newsletter'];?></td>
					<td>
						<INPUT TYPE="Radio" id="subscribe_newsletter" name="subscribe_newsletter" value="1" tabindex="3" checked /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" id='subscribe_newsletter1' name="subscribe_newsletter" value="0" tabindex="4"  /><?php echo $lang['register']['no'];?>
					</td>
				</tr>
				 <tr><td><a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=f5b7c5b570" target='_blank'>View a sample</a></td></tr>

				 <tr><td>&nbsp;</td></tr>

				<tr>
					<td>

<?php echo $lang['register']['card_code'];?>:
					</td>
					<td><input type="text" name="card_code" id="card_code" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("card_code"); ?>" /><br/><?php echo $form->error("card_code"); ?></td>
				</tr>

				 <tr><td>&nbsp;</td></tr>

			<?php $isCampaign= $database->IsActiveCampaign(); 
					
					if($isCampaign){?>
				 <tr> <?php $invalid_referral_code=$form->value("referral_code");
						if(!empty($invalid_referral_code))
							$referral_code_value=$form->value("referral_code");
					else
						if(isset($_GET['rc']))
							$referral_code_value=	$_GET['rc'];
					else
							$referral_code_value='';
					?>
					<td><?php echo $lang['register']['referral_code'];?>:</td>
					<td><input type="text" id="referral_code" name="referral_code" maxlength="20" class="inputcmmn-1" value="<?php echo $referral_code_value; ?>" /><br/><div id="referror"><?php echo $form->error("referral_code"); ?></div></td>
				</tr>
				<?php  }?>
				<!-- <tr><td>&nbsp;</td></tr>
				<tr>
					<td><strong><?php echo $lang['register']['invite'];?></strong></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['enter_emails'];?></td>
					<td><textarea name='frnds_emails' id='frnds_emails' class='textareaEmail' style='width:200px'><?php echo $form->value('frnds_emails'); ?></textarea><br/><?php echo $form->error("emailError"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td></td>
					<td align='left'>
						<a href="javascript:void(0)" onClick="window.open('inviter.php','mywindow','width=400,height=200,left=200,top=200,screenX=0,screenY=100,scrollbars=yes')"><?php echo $lang['register']['import_cont'] ?> </a><br/><br/>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['invite_msg'];?></td>
					<td><textarea name='frnds_msg' id='frnds_msg' class='textareaEmail' style='width:200px' onKeypress='javascript:checkvalue1(this.id,event);' onKeyup='javascript:checkvalue2(this.id);'><?php echo $form->value('frnds_msg'); ?></textarea><br/><?php echo $form->error("frnds_emails"); ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td></td>
					<td>
						<div id='remainchar' style='margin-left: 50px; margin-top:5px;float:left'>2000</div>
						<div style='margin-top:5px;float:left'>&nbsp;<?php echo $lang['register']['chars_remain'] ?></div>
					</td>
				</tr> -->
				<tr><td>&nbsp;</td></tr>
				
				<tr>
					<td colspan=2>
						<div style="float:left;width:295px;"><?php echo $lang['register']['capacha'];?><font color="red">*</font></div><div style="float:right;padding-left:10px"><?php echo  recaptcha_get_html(RECAPCHA_PUBLIC_KEY, $form->error("user_guess")); ?></div>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="detail">
			<tbody>
				<tr><td><b><?php echo $lang['register']['t_c'];?></b></td></tr>
				<tr>
					<td align="center" style="vertical-align:text-top">
						<div align="left" style="border: 1px solid black; padding: 0px 10px 10px; overflow: auto; line-height: 1.5em; width: 90%; height: 130px; background-color: rgb(255, 255, 255);">
				<?php		include_once("./editables/lenderagreement.php");
							$path1=	getEditablePath('lenderagreement.php');
							include_once("./editables/".$path1);
							echo $lang['lenderagreement']['l_tnc'];
				?>		</div>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="left">
						<b><?php echo $lang['register']['a_a'];?>:</b><font color="red">*</font>
						<INPUT TYPE="Radio" name="agree" id="agree" value="1" tabindex="3" /><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="agree" id="agree" value="0" tabindex="4" checked /><?php echo $lang['register']['no'];?>
					</td>
				</tr>
				<tr> <br/><br/>
					<td style="text-align:center">
						<input type="hidden" name="reg-lender" />
						<input type="hidden" name="tnc"  id="tnc" value=0 />
						<input type="hidden" name="member_type"  id="member_type" value="<?php echo $select ?>"/>
						<input class='btn' type="submit" value="<?php echo $lang['register']['Register'];?>" onclick="return verifyTnC()"  />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<script language="JavaScript">
	var ids = new Array('busername', 'bpass1', 'bpass2','lfname', 'llname','bpostadd','lwebsite','lphoto'  ,'bemail','lcity','lcountry','hide_Amount1','hide_Amount1','labout','loan_repayment_credited','loan_repayment_credited1','loan_comment','loan_comment1','loan_app_notify','loan_app_notify1','subscribe_newsletter','subscribe_newsletter1','card_code','referral_code','frnds_emails','frnds_msg','agree');
	var values = new Array('','','','','','','','','','','','','','','','','','','','','','','','','','','');
	var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>