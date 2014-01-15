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

			<!-- username -->
			<label><?php echo $lang['register']['username'];?><span class="red">*</span></label>
			<input type="text" id="busername" name="lusername" maxlength="20" class="inputcmmn-1" value="<?php echo $form->value("lusername"); ?>" />
			<br/>
			<div id="bunerror"><?php echo $form->error("lusername"); ?></div>

			<!-- Create password -->
			<label><?php echo$lang['register']['ppassword'];?><span class="red">*</span></label>
			<input type="password" id="bpass1" name="lpass1" class="inputcmmn-1" value="<?php echo $form->value("lpass1"); ?>" />
			<br/>
			<div id="passerror"><?php echo $form->error("lpass1"); ?></div>

			<!-- Confirm pssword -->
			<label><?php echo$lang['register']['CPassword'];?><span class="red">*</span></label>
			<input type="password" id="bpass2" name="lpass2" class="inputcmmn-1" />

			<!-- First name -->
			<label>
				<?php 
					if($select==5) {
						echo $lang['register']['lgname'];
					} else {
						echo$lang['register']['fname'];
					}
				?>
				<span class="red">*</span>
			</label>
			<input type="text" name="lfname" id="lfname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lfname"); ?>" />
			<br/>
			<?php echo $form->error("lfname"); ?>

			<!-- Last name -->
			<?php if($select==5) { ?>
				<label><?php echo $lang['register']['lwebsite'];?></label>
				<input type="text" id='lwebsite' name="lwebsite" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lwebsite"); ?>" />
				<br/>
				<?php echo $form->error("lwebsite"); ?>
			<?php } else { ?>
				<label><?php echo $lang['register']['lname'];?><span class="red">*</span></label>
				<input type="text" name="llname" id='llname' maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("llname"); ?>" />
				<br/>
				<?php echo $form->error("llname"); ?>
			<?php }?>

			<!-- E-mail address -->
			<label><?php echo $lang['register']['email'];?><span class="red">*</span></label>
			<input type="text" id="bemail" name="lemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("lemail"); ?>" />
			<br/>
			<div id="emailerror"><?php echo $form->error("lemail"); ?></div>

			<!-- City -->
			<label>
				<?php if($select==5) {
					echo $lang['register']['lgcity'];
				} else {
					echo $lang['register']['City'];
				} ?>
				<span class="red">*</span>
			</label>
			<input type="text" name="lcity" id='lcity' maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("lcity"); ?>" />
			<br/>
			<?php echo $form->error("lcity"); ?>

			<!-- Country -->
			<label>
				<?php if($select==5) {
					echo $lang['register']['lgcountry'];
				} else {
					echo $lang['register']['Country'];
				} ?>
				<span class="red">*</span>
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

			<!-- Photom -->
			<?php 
				if($select==5)
					echo $lang['register']['photo_logo'];
				else 
					echo $lang['register']['Photo'];
			?> 
			<?php echo $lang['register']['l_optional']; ?>
			<br/>
			<?php echo $lang['register']['photo_msg'];?>
			<input type="file" name="lphoto" id='lphoto' value="<?php echo $form->value("lphoto"); ?>" />
			<?php	$isPhoto_select=$form->value("isPhoto_select");
			    if(!empty($isPhoto_select))
				{	?>
					<img class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.$isPhoto_select ?>" height="50" width="50" alt=""/>
			<?php	}	?>
			<br/><?php echo $form->error("lphoto"); ?>
			<input type="hidden" name="isPhoto_select" value="<?php echo $form->value("isPhoto_select"); ?>" />

			<!-- About yourself -->
			<label><?php echo $lang['register']['A_Yourself_l'];?> <?php echo $lang['register']['l_optional'];?></label>
			<textarea class="textareacmmn" name="labout" id="labout" ><?php echo $form->value("labout"); ?></textarea>
		</div>

		<hr/>
		<div class="holder_342 group">
			<p class="blue_color uppercase formTitle"><?php echo $lang['register']['A_Preferences_l'];?></p>
			
			<!-- radio 1 -->
			<label><?php echo $lang['register']['d_total_amt'];?></label>
			<div class="radio_s">
				<INPUT TYPE="Radio" id="hide_Amount" class="left" name="hide_Amount" value="0" tabindex="3" checked />
				<span class="left"><?php echo $lang['register']['yes'];?></span>
			</div>	
			<div class="radio_s">
				<INPUT TYPE="Radio" id="hide_Amount1" class="left" name="hide_Amount" value="1" tabindex="4"  />
				<span class="left"><?php echo $lang['register']['no'];?></span>
			</div>		

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