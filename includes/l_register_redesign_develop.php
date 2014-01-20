<div class="row">
	<form enctype="multipart/form-data" method="post" id="sub-lender" name="sub-lender" action="process.php">
		<div>
			<?php if($select==6) {
				echo "<strong>This page allows you to create a donation account.  Donation accounts differ from regular lender accounts in that lending credit uploaded to these accounts is a tax-deductible donation which cannot be withdrawn or refunded.  <br/><br/>You will be able to control the use of the lending credit uploaded to this account as long as it remains active.  Please see our Terms of Use for more details.</strong>";
			?>
			<div style="display: none"><INPUT TYPE="Radio" id="DonationAcct" name="DonationAcct" value="1" tabindex="3" checked/></div>
			<?php } ?>
		</div>
		<div class="holder_342 group">
			<br/>
			
			<!-- username -->
			<br/>
			<label><?php echo $lang['register']['username'];?></label>
			<input type="text" id="busername" name="lusername" maxlength="20" class="inputcmmn-1" value="<?php echo $form->value("lusername"); ?>" />
			<br/>
			<div id="bunerror"><?php echo $form->error("lusername"); ?></div>

			<!-- Create password -->
			<br/>
			<label><?php echo$lang['register']['ppassword'];?></label>
			<input type="password" id="bpass1" name="lpass1" class="inputcmmn-1" value="<?php echo $form->value("lpass1"); ?>" />
			<br/>
			<div id="passerror"><?php echo $form->error("lpass1"); ?></div>

			<!-- Confirm pssword -->
			<br/>
			<label><?php echo$lang['register']['CPassword'];?></label>
			<input type="password" id="bpass2" name="lpass2" class="inputcmmn-1" />

			<!-- First name 
			<br/>
			<label>
				<?php 
					if($select==5) {
						echo $lang['register']['lgname'];
					} else {
						echo$lang['register']['fname'];
					}
				?>
				
			</label>
			<input type="text" name="lfname" id="lfname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lfname"); ?>" />
			<br/>
			<?php echo $form->error("lfname"); ?>

			
			<br/>
			<?php if($select==5) { ?>
				<label><?php echo $lang['register']['lwebsite'];?></label>
				<input type="text" id='lwebsite' name="lwebsite" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lwebsite"); ?>" />
				<br/>
				<?php echo $form->error("lwebsite"); ?>
			<?php } else { ?>
				<label><?php echo $lang['register']['lname'];?></label>
				<input type="text" name="llname" id='llname' maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("llname"); ?>" />
				<br/>
				<?php echo $form->error("llname"); ?>
			<?php }?>

			-->

			<!-- E-mail address -->
			<br/>
			<label><?php echo $lang['register']['email'];?></label>
			<input type="text" id="bemail" name="lemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("lemail"); ?>" />
			<br/>
			<div id="emailerror"><?php echo $form->error("lemail"); ?></div>

			<!-- Country -->
			<br/>
			<label>
				<?php if($select==5) {
					echo $lang['register']['lgcountry'];
				} else {
					echo $lang['register']['Country'];
				} ?>
				
			</label>
			<div class="arrow_hider">
				<select id="lcountry" class="custom_select" name="lcountry" >
					<option value='US'>United States</option>
					<?php
						$result1 = $database->countryList();
						$i=0;
						foreach($result1 as $state)
						{	
							if($state['code']=='US')
							{	
								continue;
							}	
					?>
						<option value='<?php echo $state['code'] ?>' <?php if($form->value("lcountry")==$state['code']) echo "selected" ?>><?php echo $state['name'] ?></option>
					<?php  $i++; 
						} 
					?>
				</select>
			</div>
			<br/><?php echo $form->error("lcountry"); ?>

			<!-- Photo -->
			<div style="display:none">
				<?php 
					if($select==5)
						echo $lang['register']['photo_logo'];
					else 
						echo $lang['register']['Photo'];
				?> 
				<br/>
				<?php $photolabel = $lang['register']['upload_photo']; ?>
				<?php	$isPhoto_select=$form->value("isPhoto_select");
				    if(!empty($isPhoto_select))
					{	?>
						<img class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.$isPhoto_select ?>" height="50" width="50" alt=""/>
				<?php	}	?>

				<div class='fileType_hide'>
					<input type="file" name="lphoto" id='lphoto' value="<?php echo $form->value("lphoto"); ?>" />
				</div>

				<div class="customfiletype" onclick="getbphoto()">
					<span><?php echo $photolabel?></span>
				</div>

				<div style="clear:both"></div>
				<?php echo $lang['register']['l_optional']; ?>
				<?php echo $lang['register']['photo_msg'];?>

				<br/><?php echo $form->error("lphoto"); ?>

				<input type="hidden" name="isPhoto_select" value="<?php echo $form->value("isPhoto_select"); ?>" />
			</div>
		</div>

		<div style="display:none;" class="holder_522 group">
			<!-- About yourself -->
			<br/>
			<label><?php echo $lang['register']['A_Yourself_l'];?><?php echo $lang['register']['l_optional'];?></label>
			<textarea class="textareacmmn" name="labout" id="labout" ><?php echo $form->value("labout"); ?></textarea>
		</div>
		<div class="holder_342 group">
		</div>
		<div style="display:none;" class="holder_342 group">
			<p class="blue_color uppercase formTitle"><?php echo $lang['register']['A_Preferences_l'];?></p>
			
			<!-- radio 1 -->
			<br/><label><?php echo $lang['register']['d_total_amt'];?></label>
			<div class="radio_s">
				<INPUT TYPE="Radio" id="hide_Amount" class="left" name="hide_Amount" value="0" tabindex="3" checked />
				<span class="left"><?php echo $lang['register']['yes'];?></span>
			</div>	
			<div class="radio_s">
				<INPUT TYPE="Radio" id="hide_Amount1" class="left" name="hide_Amount" value="1" tabindex="4"  />
				<span class="left"><?php echo $lang['register']['no'];?></span>
			</div>	

			<!-- radio 2 -->
			<br/><label><?php echo $lang['register']['d_mailinglist_preferences_onloanRepayment_credited']; ?></label>
			<div class="radio_s">
				<INPUT TYPE="Radio" id="loan_repayment_credited" name="loan_repayment_credited" value="1" tabindex="3" checked />
				<span class="left"><?php echo $lang['register']['yes'];?></span>
			</div>	
			<div class="radio_s">
				<INPUT TYPE="Radio" id='loan_repayment_credited1' name="loan_repayment_credited" value="0" tabindex="4"  />
				<span class="left"><?php echo $lang['register']['no'];?></span>
			</div>	

			<!-- radio 3 -->
			<br/><label><?php echo $lang['register']['d_mailinglist_preferences_onloanComment']; ?></label>
			<div class="radio_s">
				<INPUT TYPE="Radio" id="loan_comment" name="loan_comment" value="1" tabindex="3" checked />
				<span class="left"><?php echo $lang['register']['yes'];?></span>
			</div>	
			<div class="radio_s">
				<INPUT TYPE="Radio" id='loan_comment1' name="loan_comment" value="0" tabindex="4"  />
				<span class="left"><?php echo $lang['register']['no'];?></span>
			</div>	

			<!-- radio 4 -->
			<br/><label><?php echo $lang['register']['wd_new_loan_app']; ?></label>
			<div class="radio_s">
				<INPUT TYPE="Radio" id="loan_app_notify" name="loan_app_notify" value="1" tabindex="3" checked />
				<span class="left"><?php echo $lang['register']['yes'];?></span>
			</div>	
			<div class="radio_s">
				<INPUT TYPE="Radio" id='loan_app_notify1' name="loan_app_notify" value="0" tabindex="4"  />
				<span class="left"><?php echo $lang['register']['no'];?></span>
			</div>

			<!-- radio 5 -->
			<br/><label>
				<?php echo $lang['register']['mailinglist_preferences_subscribe_newsletter']; ?><br/>
				<a href="http://us1.campaign-archive2.com/?u=c8b5366ff36890ecbc3bf00cc&id=f5b7c5b570" target='_blank'>View a sample</a>
			</label>
			<div class="radio_s">
				<INPUT TYPE="Radio" id="subscribe_newsletter" name="subscribe_newsletter" value="1" tabindex="3" checked />
				<span class="left"><?php echo $lang['register']['yes'];?></span>
			</div>	
			<div class="radio_s">
				<INPUT TYPE="Radio" id='subscribe_newsletter1' name="subscribe_newsletter" value="0" tabindex="4"  />
				<span class="left"><?php echo $lang['register']['no'];?></span>
			</div>
		</div>
		<div style="display:none;" class="holder_522 group">

			<!-- gift card -->
			<br/><label><?php echo $lang['register']['card_code']; ?></label>
			<input type="text" name="card_code" id="card_code" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("card_code"); ?>" />
			<br/>
			<?php echo $form->error("card_code"); ?>

			<!-- referral code -->
			<?php $isCampaign= $database->IsActiveCampaign(); 
			
			if($isCampaign){?>
		 		<?php $invalid_referral_code=$form->value("referral_code");
				if(!empty($invalid_referral_code))
					$referral_code_value=$form->value("referral_code");
			else
				if(isset($_GET['rc']))
					$referral_code_value=	$_GET['rc'];
			else
					$referral_code_value='';
			?>
			<br/><label><?php echo $lang['register']['referral_code']; ?></label>
			<input type="text" id="referral_code" name="referral_code" maxlength="20" class="inputcmmn-1" value="<?php echo $referral_code_value; ?>" />
			<br/>
			<div id="referror"><?php echo $form->error("referral_code"); ?></div>
			<?php  }?>
		</div>

		<!-- captcha -->
		<div style="display:none;">
			<label><?php echo $lang['register']['capacha'];?><span class="red">*</span></label>
			<?php echo  recaptcha_get_html(RECAPCHA_PUBLIC_KEY, $form->error("user_guess")); ?>
		</div><br/><br/>

		<div class="holder_645 group">

			<!-- terms -->
			<br/>
			<p class="blue_color uppercase formTitle"><?php echo $lang['register']['t_c'];?></p>
			<div class="terms_of_use">
				<?php
					include_once("./editables/lenderagreement.php");
					$path1=	getEditablePath('lenderagreement.php');
					include_once("./editables/".$path1);
					echo $lang['lenderagreement']['l_tnc'];
				?>		
			</div>

			<div style="display:none;">
				<label><?php echo $lang['register']['capacha'];?></label>
				<div style="margin-top:20px"><?php echo  recaptcha_get_html(RECAPCHA_PUBLIC_KEY, $form->error("user_guess")); ?></div>
				<a id="recaptcha_response_fielderr"></a>
			</div>
			<br/><br/>

			<!-- Accept terms -->
			<label><?php echo $lang['register']['a_a']; ?><span class="red">*</span></label>
			<div class="radio_s">
				<INPUT TYPE="Radio" name="agree" id="agree" value="1" tabindex="3" />
				<span class="left"><?php echo $lang['register']['yes'];?></span>
			</div>	
			<div class="radio_s">
				<INPUT TYPE="Radio" name="agree" id="agree" value="0" tabindex="4" checked />
				<span class="left"><?php echo $lang['register']['no'];?></span>
			</div>
			<br/><br/>

			<!-- Register button -->
			<input type="hidden" name="reg-lender" />
			<input type="hidden" name="tnc"  id="tnc" value=0 />
			<input type="hidden" name="member_type"  id="member_type" value="<?php echo $select ?>"/>
			<input style="align:center" class='btn' id="lender_submit_btn" type="submit" value="Join" onclick="return verifyTnC()"  />
		</div>
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