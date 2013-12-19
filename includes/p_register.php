<div class="row">
	<form enctype="multipart/form-data" method="post" action="process.php">
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
 -->				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['username'] ;?></td>
					<td><input type="text" maxlength="20" id="busername" name="pusername" class="inputcmmn-1" value="<?php echo $form->value("pusername"); ?>" /><br/><div id="bunerror"><?php echo $form->error("pusername"); ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['Password'];?></td>
					<td><input type="password" id="bpass1" name="ppass1" class="inputcmmn-1" value="<?php  echo $form->value("ppass1"); ?>" /><br/><div id="passerror"><?php echo $form->error("ppass1"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['CPassword'];?></td>
					<td><input type="password" id="bpass2" name="ppass2" class="inputcmmn-1" value="<?php  echo $form->value("ppass2"); ?>" /></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['pname'];?></td>
					<td><input type="text" maxlength="50" id="pname" name="pname" class="inputcmmn-1" value="<?php  echo $form->value("pname"); ?>" /><br/><div id="pnameerror"><?php echo $form->error("pname"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['Photo'];?>
					<br/><?php echo $lang['register']['photo_msg'];?></td>
					<td><input type="file" id='pphotol' name="pphoto"  value="<?php echo $form->value("lphoto"); ?>" />
					<?php	$isPhoto_select=$form->value("isPhoto_select");
					    if(!empty($isPhoto_select))
						{	?>
							<img class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.$isPhoto_select ?>" height="50" width="50" alt=""/>
				<?php	}	?>
					<br/><?php echo $form->error("pphoto"); ?>
					<input type="hidden" name="isPhoto_select" value="<?php echo $form->value("isPhoto_select"); ?>" />
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['paddress'];?></td>
					<td><textarea name="paddress" id="paddress" class="textareacmmn" ><?php echo $form->value("paddress"); ?></textarea><br/><div id="paddresserror"><?php echo $form->error("paddress"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['City'];?></td>
					<td><input type="text" maxlength="50" id="pcity" name="pcity" class="inputcmmn-1" value="<?php echo $form->value("pcity"); ?>" /><br/><div id="pcityerror"><?php echo $form->error("pcity"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['Country'];?></td>
					<td>
						<select id="pcountry" name="pcountry" >
							<option value='0'>Select Country</option>
					<?php
							$result1 = $database->countryList();
							$i=0;
							foreach($result1 as $state)
							{	?>
								<option value='<?php echo $state['code'] ?>' <?php if($form->value("pcountry")==$state['code']) echo "selected" ?>><?php echo $state['name'] ?></option>
					<?php	}
					?>
						</select>
						<br/><?php echo $form->error("pcountry"); ?>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['email'];?></td>
					<td><input type="text" id="bemail" name="pemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("pemail"); ?>" /><br/><div id="emailerror"><?php echo $form->error("pemail"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['p_emails_notify'];?></td>
					<td><textarea name='emails_notify' id='emails_notify' class='textareaEmail' style='width:200px'><?php echo $form->value('emails_notify'); ?></textarea><br/><?php echo $form->error("emails_notify"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['Website'];?></td>
					<td><input type="text" maxlength="100" id="pwebsite" name="pwebsite" class="inputcmmn-1" value="<?php echo $form->value("pwebsite"); ?>" /><br/><div id="pweberror"><?php echo $form->error("pwebsite"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="3"><?php echo $lang['register']['d_org'];?></td>
				</tr>
				<tr>
					<td colspan="2"><textarea style="width:80%; height:150px" name="pdesc" id='pdesc' ><?php echo $form->value("pdesc"); ?></textarea><br/><div id="pdesc"><?php echo $form->error("pdesc"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan=2>
						<div style="float:left;width:295px;"><?php echo $lang['register']['capacha'];?></div><div style="float:right;padding-left:10px"><?php echo  recaptcha_get_html(RECAPCHA_PUBLIC_KEY, $form->error("user_guess")); ?></div>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="2" style="text-align:right">
						<input type="hidden" name="reg-partner" />
						<input class='btn' type="submit" onclick="needToConfirm = false;" value="<?php echo $lang['register']['Register'];?>" />
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<script language="JavaScript">
	var ids = new Array('busername','bpass1','bpass2','pname', 'pphoto','paddress','pcity','pcountry'  ,'bemail','emails_notify','pwebsite','pdesc');
	var values = new Array('','','','','','','','','','','','');
	var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>