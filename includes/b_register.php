<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<link href="library/tooltips/btnew.css" rel="stylesheet" type="text/css" />
<?php
if(empty($session->userid)){
	$showShareBox=0;
	$fbmsg_hide=0;
	$web_acc=0;
	$fb_fail_reason=isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : ''.' : Borrower Registration';
	if(!empty($form->values)){
		$_SESSION['fb_data']=$form->values;
	}
	if(isset($_REQUEST['fb_data'])){
		if(isset($_SESSION['fb_data'])){
			$form->values= $_SESSION['fb_data'];
		}
	}
	if(isset($_SESSION['FB_Detail']) && !isset($_SESSION['hide_fbmsg'])){
		$web_acc=1;
		$fb_fail_reason=isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : ''.' : Borrower Registration Ok';
	}
	$fb_reload=false;
	$fb_join_share= false;
	if(isset($_REQUEST['code'])) { 
		echo"<script type='text/javascript'>window.close();window.opener.location.reload();
		</script>";
	}
	if(isset($_REQUEST['error_reason'])) {
		$fb_reload=true;
		echo"<script type='text/javascript'>window.close();needToConfirm= false;
		window.opener.location.reload();</script>";
	}
	if(isset($_REQUEST['fb_disconnect'])) { 
		$session->facebook_disconnect();	
		$fb_reload=true;
		echo"<script type='text/javascript'>window.close();needToConfirm= false;window.opener.location.reload();</script>";
	} 
	if($fb_reload) { 
		echo"<script type='text/javascript'>needToConfirm= false;
		var url = window.location.href;    
		url += '\/#FB_cntct\/';
		window.location.href = url;
		</script>";
	}
	if(isset($_SESSION['hide_fbmsg'])){
		$fbmsg_hide=1;
		unset($_SESSION['hide_fbmsg']);
	}
		$ReferralCountries = $database->getReferralCountries();
		$countries = explode(',', $ReferralCountries);
		$borrowers=array();
		$volunteers = array();
		if($form->value("bcountry")!='0' && $form->value("bcountry")!=''){
			$country= $form->value("bcountry");
			$borrowers= $database->getActiveBorrowersByCountry($country);
			$volunteers= $database->getAllvolunteers($country);
			$vm_cities=$database->getVolunteersCity($volunteers[0]['volunteer_id']);		
			sort($vm_cities);
			for($x=0;$x<count($vm_cities);$x++){
				$mentorbycity=$database->getVolunteersByCity($vm_cities[$x]);
				if(!empty($mentorbycity)){
					$vmcities[]=$vm_cities[$x];
				}
			}
			$vmByCity=$database->getVolunteersByCity($vmcities[0]);
			
			for($x=0;$x<count($vmcities);$x++){
				if($form->value("vm_city")!='' && strcasecmp($form->value("vm_city"),$vmcities[$x])==0){
					$vmcity=$form->value("vm_city");
					$vmByCity=$database->getVolunteersByCity($vmcity);
				}
			}
		}
	?>
	<div class="row">
		<form enctype="multipart/form-data" id="sub-borrower" name="sub-borrower" method="post" action="process.php">

			<table class='detail'>
				<tbody>
					<tr height="60px" >
						<td colspan='2' ><?php echo $lang['register']['borrower_inst']; ?></td>
					</tr>
					<tr height="45px"><td>&nbsp;</td></tr>
					<tr>
						<td width="460px;"><?php echo $lang['register']['Country']?><a id="bcountryerr"></a></td>
						<td>
							<select id="bcountry" name="bcountry"  onchange="needToConfirm = false;checkreferal(this.value); show_tele_cntct(this.value);submitform1();">
								<option value='0'>Select Country</option>
						<?php	$result1 = $database->countryList(true);
								$i=0;
								foreach($result1 as $state)
								{	?>
									<option value='<?php echo $state['code'] ?>' <?php if($form->value("bcountry")==$state['code']) echo "selected" ?>><?php echo $state['name'] ?></option>
						<?php	}	?>
							</select>
							<br/><?php echo $form->error("bcountry"); ?>
						</td>
					</tr>
					<tr height="10px"><td>&nbsp;</td></tr>
					<tr id="fb_mandatory" style="<?php if($form->value("bcountry")!='BF')echo "display:''"; else echo "display:none"; ?>" >
						<td><?php echo $lang['register']['facebook_mandatory']; ?>
						<?php echo $form->error("cntct_type"); ?>
						</td>
						
						<td>
						<?php $fbData=$session->facebook_connect();
							if(!empty($fbData['user_profile']['id'])){
									$database->saveFacebookInfo($fbData['user_profile']['id'], serialize($fbData), $web_acc, 0, '', $fb_fail_reason);
							}
							if($fbData['loginUrl']==''){ 
								$showShareBox=1;
								if(isset($_REQUEST['fb_join']) || $_SESSION['FB_Error']!=false){ 
									$showShareBox=0;
								}
								?>
								<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="javascript:login_popup('<?php echo $fbData['logoutUrl']?>');return false;"><img src="images/f_disconnect.jpg"/></a>
							<?php }else{?>
								<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="login_popup('<?php echo $fbData['loginUrl']?>');" ><img src="images/facebook-connect.png"/></a>
						<?php }?>
						</td>
					</tr>

					<tr style="<?php if(($form->value("bcountry")!='BF') && $fbmsg_hide!=1)echo "display:block"; else echo "display:none"; ?>" >
					<td><?php 
						if(isset($_SESSION['FB_Detail'])){
							echo "<div align='center'><font color=green><strong>Your Facebook account is now linked to Zidisha.</strong></font></div><br/>";
						}
						if(isset($_SESSION['FB_Error'])){
							echo $_SESSION['FB_Error'];
						}?></td>
					</tr>
				</tbody>
			</table>
			<?php
			$brwrBehalf = $form->value('borrower_behalf');
			if($form->value("bcountry")!='BF') {
				$brwrBehalf=0;
			}?>
			<table class='detail' style="margin-bottom:0px; <?php if($form->value("bcountry")!='BF') echo 'display:none'; else echo 'display:block';?>" id= "brwr_behalf">
				<tbody>
					<a id="borrower_behalferr"></a>
					<tr ><td colspan='2'><?php echo $lang['register']['borrower_behalf'];?></td></tr>
					<tr>
						<td colspan='2'><input type="radio" id="borrower_behalf" name="borrower_behalf" onclick ="document.getElementById('borwr_behalf_section').style.display = 'none';" class="inputcmmn-1" value="0" <?php if($brwrBehalf=='0' || !isset($brwrBehalf)) echo"checked";?>/><?php echo $lang['register']['borrower_behalf1'];?>
					</tr>
					<tr>
						<td colspan='2' width="463px"><input type="radio" id="borrower_behalf" name="borrower_behalf" onclick ="document.getElementById('borwr_behalf_section').style.display = '';" class="inputcmmn-1" value="1" <?php if($brwrBehalf!='0') echo"checked";?>/><?php echo $lang['register']['borrower_behalf2'];?></td>
						<br/><div id=""><?php echo $form->error("borrower_behalf"); ?></div></td>
					</tr>
					<tr height="10px"><td>&nbsp;</td></tr>
					<tr>
						<?php $display = '';
						if($brwrBehalf=='0') {
								
								$display='display:none';
						}?>
						<td colspan="2" id='borwr_behalf_section' style="text-decoration:none;<?php echo $display?>">
							<table class="detail">
								<tr>
									<td></td>
									<td><a href="https://sites.google.com/site/zidishavolunteermentor/working-with-new-applicants" target="_blank"><?php echo $lang['register']['behalf_guideline'];?></td>
								</tr>
								<tr height="15px;"></tr>
								<tr>
									<td width='20px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td width="462px"><?php echo $lang['register']['behalf_name'];?>
									<a id="behalf_nameerr"></a></td>
									<td><input type="text" id="behalf_name" name="behalf_name" value="<?php echo $form->value("behalf_name"); ?>"/>
									<br/><div id="behalf_name"><?php echo $form->error("behalf_name"); ?></div></td>
								</tr>
								<tr height="10px"><td>&nbsp;</td></tr>
								<tr>
									<td width='20px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td width="430px"><?php echo $lang['register']['behalf_number'];?><a id="behalf_numbererr"></a></td>
									<td><input type="text" id="behalf_number" name="behalf_number" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("behalf_number"); ?>" /><br/><div id=""><?php echo $form->error("behalf_number"); ?></div></td>
								</tr>
								<tr height="10px"><td>&nbsp;</td></tr>
								<tr>
									<td width='20px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td width="430px"><?php echo $lang['register']['behalf_email'];?><a id="behalf_emailerr"></a></td>
									<td><input type="text" id="behalf_email" name="behalf_email" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("behalf_email"); ?>" /><br/><div id="behalf_email"><?php echo $form->error("behalf_email"); ?></div></td>
								</tr>
								<tr height="10px"><td>&nbsp;</td></tr>
								<tr>
									<td width='20px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td width="430px"><?php echo $lang['register']['behalf_town'];?><a id="behalf_townerr"></a></td>
									<td><input type="text" id="behalf_town" name="behalf_town" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("behalf_town"); ?>" /><br/><div id="behalf_town"><?php echo $form->error("behalf_town"); ?></div></td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="detail">
				<tbody>

<!-- commenting out language section & eligibility questions 

					<tr>
						<td><?php echo $lang['register']['language'];?></td>
						<td>
							<select id="labellang" name="labellang" onchange="javascript:setLanguage(this.value);">
					<?php		$langs= $database->getActiveLanguages();
								echo "<option value='en'>English</option>";
								foreach($langs as $row)
								{  ?>
									<option value='<?php echo $row['langcode'] ?>' <?php if($language==$row['langcode'])echo "Selected='true'";?>><?php echo $row['lang']?></option>
					<?php		} ?>
							</select>
						</td>
					</tr> 
					 <tr><td>&nbsp;</td></tr>
					<tr>
						<td colspan='2'><?php echo $lang['register']['ordertojoin'];?></td>
						<td></td>
						<td></td>
					</tr>
					<tr height="10px"><td>&nbsp;</td></tr>
					<a id="repaidpasterr"></a>
					<tr><?php $repaidpast = $form->value("repaidpast");
							$padding_top = '';
							$repaidpast_err = $form->error("repaidpast");
							if(!empty($repaidpast_err)) {
								$padding_top = 'padding-top:25px;';
							}?>					
						<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">1.</td>
						<td width="460px"><?php echo $lang['register']['ordertojoin1'];?></td>
						
						<td><input type="radio" id="repaidpast" name="repaidpast"  onclick="checkeligibility(this.id)" class="inputcmmn-1" value="1" <?php if($repaidpast) echo "checked";?>/><?php echo $lang['register']['yes'];?>
						<input type="radio" id="repaidpast" name="repaidpast"  onclick="checkeligibility(this.id)" class="inputcmmn-1" value="0" <?php if($repaidpast=='0') echo "checked";?>/><?php echo $lang['register']['no'];?>
						<br/><div id="repaidpast_err"><?php echo $form->error("repaidpast"); ?></div></td>
					</tr>
					<tr height="10px"><td>&nbsp;</td></tr>
					<a id="debtfreeerr"></a>
					<tr><?php $debtfree = $form->value("debtfree");
							$padding_top = '';
							$debtfree_err = $form->error("debtfree");
							if(!empty($debtfree_err)) {
								$padding_top = 'padding-top:25px;';
							}?>
						<td width="30px" style="text-align:right;vertical-align: top;<?php echo $padding_top?>">2.</td>
						<td><?php echo $lang['register']['ordertojoin2'];?></td>
						<td><input type="radio" id="debtfree" name="debtfree"  onclick="checkeligibility(this.id)" class="inputcmmn-1" value="1" <?php if($debtfree) echo "checked";?>/><?php echo $lang['register']['yes'];?>
						<input type="radio" id="debtfree" name="debtfree" onclick="checkeligibility(this.id)" class="inputcmmn-1" value="0" <?php if($debtfree=='0') echo "checked";?>/><?php echo $lang['register']['no'];?>
						<br/><div id="debtfree_err"><?php echo $form->error("debtfree"); ?></div></td>
					</tr>
					<tr height="10px"><td>&nbsp;</td></tr>
					<a id="share_updateerr"></a>
					<tr><?php $share_update = $form->value("share_update");
							$padding_top = '';
							$share_update_err = $form->error("share_update");
							if(!empty($share_update_err)) {
								$padding_top = 'padding-top:20px;';
							}
						?>
						<td width="30px" style="text-align:right;vertical-align:top;<?php echo $padding_top?>">3.</td>
						<td><?php echo $lang['register']['ordertojoin3'];?></td>
						<td><input type="radio" id="share_update" name="share_update"  onclick="checkeligibility(this.id)" class="inputcmmn-1" value="1" <?php if($share_update) echo "checked";?>/><?php echo $lang['register']['yes'];?>
						<input type="radio" id="share_update" name="share_update" onclick="checkeligibility(this.id)" class="inputcmmn-1" value="0" <?php if($share_update=='0') echo "checked";?>/><?php echo $lang['register']['no'];?>
						<br/><div id="share_update_err"><?php echo $form->error("share_update"); ?></div></td>
					</tr>
				</tbody>
			</table>
end commenting out language section & eligibility questions -->


			<table class="detail">
				<tbody>


					<tr>
						<td><?php echo $lang['register']['endorser_uname'];?><a name="busernameerr" id="busernameerr"></a>
						</td>
						<td><input type="text" id="busername" name="busername" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("busername"); ?>" /><br/><div id="bunerror"><?php echo $form->error("busername"); ?></div></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['ppassword'];?><a id="bpass1err"></a></td>
						<td><input type="password" id="bpass1" name="bpass1" class="inputcmmn-1" value="<?php echo $form->value("bpass1"); ?>" /><br/><div id="passerror"><?php echo $form->error("bpass1"); ?></div></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					<tr>
						<td><?php echo $lang['register']['CPassword'];?></td>
						<td><input type="password" id="bpass2" name="bpass2" class="inputcmmn-1" value="<?php echo $form->value("bpass2"); ?>"/></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['b_fname'];?><a id="bfnameerr"></a></td>
						<td><input type="text" name="bfname" id='bfname' maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("bfname"); ?>"/><br/><?php echo $form->error("bfname"); ?></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					<tr>
						<td><?php echo $lang['register']['b_lname'];?><a id="blnameerr"></a></td>
						<td><input type="text" name="blname" id="blname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("blname"); ?>"/><br/><?php echo $form->error("blname"); ?></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['photo_note']?><a id="bphotoerr"></a>
</td>
						<td>
						<div>
						<div>
							<div style="float:left;padding-top:10px;">
								<?php $photolabel = $lang['register']['upload_photo'];
								$isPhoto_select=$form->value("isPhoto_select");
									if(!empty($isPhoto_select))
									{ $photolabel = $lang['register']['upload_diffphoto'];
									?>
										<img style="float:none" class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.$isPhoto_select ?>" height="50" width="50" alt=""/>
								<?php } ?>
							</div>
							<!--[if lt IE 9]>
								<div>
								<input type="file" name="bphoto" id="bphoto"   value="<?php echo $form->value("bphoto"); ?>" onchange="uploadfile(this)"/>
								</div>
								<![endif]-->
							<!--[if !IE]> -->
								<div class='fileType_hide'>
								<input type="file" name="bphoto" id="bphoto"   value="<?php echo $form->value("bphoto"); ?>" onchange="uploadfile(this)"/>
								</div>
								<div  class="customfiletype" onclick="getbphoto()"><?php echo $photolabel?></div>
								<div style="clear:both"></div>
								<div  id="bphoto_file"></div>
							<!-- <![endif]-->

						</div>
						<br/><span><?php echo $lang['register']['allowed'];?></span><br/><div id="bphoto_err"><?php echo $form->error("bphoto"); ?></div>
						<input type="hidden" name="isPhoto_select" value="<?php echo $form->value("isPhoto_select"); ?>" />
						</td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<?php $params['padd_ex']= $_SERVER['REQUEST_URI'].'#ResidentialaddrExample'; 
							 $paddress= $session->formMessage($lang['register']['paddress'], $params); ?>
						<td><?php echo $paddress;?><a id="bpostadderr"></a></td>
						<td><textarea name="bpostadd" id='bpostadd' class="textareacmmn" ><?php echo $form->value("bpostadd"); ?></textarea><br/><?php echo $form->error("bpostadd"); ?></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['home_no'];?><a id="home_noerr"></a></td>
						<td><textarea name="home_no" id='home_no' class="textareacmmn" ><?php echo $form->value("home_no"); ?></textarea><br/><?php echo $form->error("home_no"); ?></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					<tr>
						<td><?php echo $lang['register']['City'];?><a id="bcityerr"></a></td>
						<td><input type="text" name="bcity" id="bcity" maxlength="50" class="inputcmmn-1" value="<?php echo $form->value("bcity"); ?>"/><br/><?php echo $form->error("bcity"); ?></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['nationid'];?><a id="bnationiderr"></a></td>
						<td><input type="text" name="bnationid" id='bnationid' maxlength="50" class="inputcmmn-1" value="<?php echo $form->value("bnationid"); ?>" /><br/><?php echo $form->error("bnationid"); ?></td>
					</tr>

					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php 
//modified by Julia 1-11-2013
						if($form->value("bcountry")=='KE') {
							echo "Please enter your Safaricom mobile phone number. This must be a Safaricom number registered under your own name.";
						}else if($form->value("bcountry")=='GH') {
						echo "Please enter your MTN mobile phone number. This must be an MTN number registered under your own name.";
						}else {
						echo $lang['register']['tel_mob_no'];
						}
						?><a id="bmobileerr"></a></td>
						<td><input type="text" id="bmobile" name="bmobile" maxlength="15" class="inputcmmn-1" value="<?php echo $form->value("bmobile"); ?>" /><br/><div id="mobileerror"><?php echo $form->error("bmobile"); ?></div></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['email'];?><a id="bemailerr"></a></td>
						<td><input type="text" id="bemail" name="bemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("bemail"); ?>" /><br/><div id="emailerror"><?php echo $form->error("bemail"); ?></td>
					</tr>

					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['reffered_member'];?><a id="refer_membererr"></a></td>
						<td><select id="refer_member" name="refer_member"><option value='0'>None</option>
				<?php   foreach($borrowers as $borrower){ ?>
									<option value="<?php echo $borrower['userid']?>" <?php if($form->value("refer_member")==$borrower['userid']) echo "Selected";?>><?php echo $borrower['FirstName']." ".$borrower['LastName']." (".$borrower['City'].")";?></option>
				<?php	} ?>		</select><br/><div id="refer_membererror"><?php echo $form->error("refer_member"); ?></div></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['nearest_city'];?><a id="volunteer_mentorerr"></a></td>
						<td><select id="vm_city" name="vm_city" onchange="get_volunteers(this.value)">
				<?php 
							if(!empty($vmcities)){?>
				<?php			for($x=0;$x<count($vmcities);$x++){?>
									<option value="<?php echo $vmcities[$x]?>" <?php if(strcasecmp($form->value("vm_city"), $vmcities[$x])==0) echo "Selected"?>><?php echo $vmcities[$x];?></option>
				<?php			}
							}?></td></tr>
					 <tr><td>&nbsp;</td></tr>

							<tr><td><?php echo $lang['register']['volunteer_mentor'];?>
								<a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['register']['tooltip_mentor'] ?></span><span class='bottom'></span></span></a></strong></td><td><select id="volunteer_mentor" name="volunteer_mentor">
				<?php  if(!empty($vmByCity)){
						/*		foreach($volunteers as $key => $result)
								{	
									$row= $database->getUserById($result['user_id']);
									$city = '';
									$TelMobile='';
									if(!empty($row['City'])) {
										$volunteers[$key]['City'] = $row['City'];
									}else {
										$volunteers[$key]['City'] = '';
									}
									if(!empty($row['TelMobile'])) {
										$volunteers[$key]['TelMobile'] = $row['TelMobile'];
									}else {
										$volunteers[$key]['TelMobile']='';
									}

								}
								$res = array_sort($volunteers,'City', 'SORT_ASC', 'country');*/
								foreach($vmByCity as $key=>$row){
									$name= $database->getNameById($key);?>
									<option value='<?php echo $key ?>' <?php if($form->value("volunteer_mentor")==$key){ echo 'selected'; } ?>><?php echo $name ?></option>
				<?php			}
					}else{?>		
						<option></option>
			<?php	} ?>
						</select><br/><div id="volunteer_mentorerror"><?php echo $form->error("volunteer_mentor"); ?></div></td>
					</tr>


					 <tr style="<?php if($form->value("bcountry")!='BF') echo 'display:none'; else echo 'display:block';?>" id="contact_type" >
						<td colspan='2'><?php echo $lang['register']['contact_type']?><a id="cntct_type"></a><br/><?php echo $form->error("contact_type"); ?></td>
						<td></td>
					</tr>
					<tr><td>&nbsp;</td></tr> 
					<tr style="<?php if($form->value("bcountry")!='BF') echo 'display:none'; else echo "display:''";?>" id="facebook_optional">
						<td>
						<input type="radio" name="cntct_type" id="FB_cntct" value="1" onclick="needToConfirm = false;open_contact(this.value);submitform1();" <?php if($form->value("cntct_type")=='1' || isset($_SESSION['FB_Detail'])) echo"checked"; ?>>
						<?php echo $lang['register']['FB_contact']." (This must be your own, actively used Facebook account.)";?><br/><br/></div><div id="fb_instruction" style="<?php if($form->value("cntct_type")=='1')echo "display:block;"; else echo "display:none;"; ?>"><?php echo $lang['register']['fb_instruction']; ?></div>
						<?php echo $form->error("cntct_type"); ?>
						</td>
						
						<td>
						<?php
							if($fbData['loginUrl']==''){ ?>
								<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="javascript:login_popup('<?php echo $fbData['logoutUrl']?>');return false;" style="<?php if($form->value("cntct_type")=='1' || isset($_SESSION['FB_Detail']))echo "display:''"; else echo "display:none"; ?>"><img src="images/f_disconnect.jpg"/></a>
							<?php }else{?>
								<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="login_popup('<?php echo $fbData['loginUrl']?>');" style="<?php if($form->value("cntct_type")=='1' || isset($_SESSION['FB_Detail']))echo "display:block"; else echo "display:none"; ?>"><img src="images/facebook-connect.png"/></a>
						<?php }?>
						</td>
					</tr>
					<tr style="<?php if($form->value("bcountry")!='BF' || $fbmsg_hide==1) echo 'display:none'; else echo 'display:block';?>" id="facebook_result">
					<td><?php 
						if(isset($_SESSION['FB_Detail'])){
							echo "<div align='center'><font color=green><strong>Your Facebook account is now linked to Zidisha.</strong></font></div><br/>";
						}
						if(isset($_SESSION['FB_Error'])){
							echo $_SESSION['FB_Error'];
						}?></td>
					</tr>
					
				</table>

				<table class="detail">
					<tr>
						<td id="telephone_contact" style="<?php if($form->value("bcountry")!='BF') echo 'display:none'; else echo 'display:block';?>"><input type="radio" name="cntct_type" id="tel_cntct" onclick="open_contact(this.value);" value="0" <?php if($form->value("cntct_type")=='0') echo"checked";?>><?php echo $lang['register']['tel_contact']?></td>
						<td></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
				</table>
				<table class="detail" style="<?php if($form->value("bcountry")!='BF')echo "display:block"; else echo "display:none"; ?>" id="tele_contacts">
					 <tr>
						<td colspan='2'><?php echo $lang['register']['family_contact']?><a id="bfamilycontact"></a></td>
						<td></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td><?php echo $lang['register']['family_contact1']?>:<a id="bfamilycontact1"></a></td>
						<td><textarea name="bfamilycont1" id='bfamilycont1' class="textareacmmn" ><?php echo $form->value("bfamilycont1"); ?></textarea><br/><?php echo $form->error("bfamilycont1"); ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td><?php echo $lang['register']['family_contact2']?>:<a id="bfamilycontact2"></a></td>
						<td><textarea name="bfamilycont2" id='bfamilycont2' class="textareacmmn" ><?php echo $form->value("bfamilycont2"); ?></textarea><br/><?php echo $form->error("bfamilycont2"); ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td><?php echo $lang['register']['family_contact3']?>:<a id="bfamilycontact3"></a></td>
						<td><textarea name="bfamilycont3" id='bfamilycont3' class="textareacmmn" ><?php echo $form->value("bfamilycont3"); ?></textarea><br/><?php echo $form->error("bfamilycont3"); ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td colspan='2'><?php echo $lang['register']['neigh_contact'];?><a id="bneighcontact"></a></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['neigh_contact1']?>:<a id="bneighcontact1"></a></td>
						<td><textarea name="bneighcont1" id='bneighcont1' class="textareacmmn" ><?php echo $form->value("bneighcont1"); ?></textarea><br/><?php echo $form->error("bneighcont1"); ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['neigh_contact2']?>:<a id="bneighcontact2"></a></td>
						<td><textarea name="bneighcont2" id='bneighcont2' class="textareacmmn" ><?php echo $form->value("bneighcont2"); ?></textarea><br/><?php echo $form->error("bneighcont2"); ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['neigh_contact3']?>:<a id="bneighcontact3"></a></td>
						<td><textarea name="bneighcont3" id='bneighcont3' class="textareacmmn" ><?php echo $form->value("bneighcont3"); ?></textarea><br/><?php echo $form->error("bneighcont3"); ?></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
				</table>
				<table class="detail">
					<tr>
						<td><?php echo $lang['register']['A_Yourself'];?><a id="babouterr"></a></td>
						<td><textarea name="babout" id='babout' class="textareacmmn" style="height:130px;"><?php echo $form->value("babout"); ?></textarea><br/>
						<div id="babout_err"><?php echo $form->error("babout"); ?></div></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td><?php echo $lang['register']['bdescription'];?><a id="bbizdescerr"></a></td>
						<td><textarea name="bbizdesc" id='bbizdesc' class="textareacmmn" style="height:130px;"><?php echo $form->value("bbizdesc"); ?></textarea><br/>
						<div id="brbizdesc_err"><?php echo $form->error("bbizdesc"); ?></div></td>
					</tr>
					<tr><td>&nbsp;</td></tr>

				<tr>
					<td><?php echo $lang['register']['reffered_by'];?><a id="breffered_by"></a></td>
					<td>
					<textarea name="reffered_by" id='reffered_by' class="textareacmmn" style="height:130px;"><?php echo $reffer_by?></textarea><br/>
					<div id="reffered_by_err"><?php echo $form->error("reffered_by"); ?></div>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>

					<?php
						$displayreferrer = '';
						if($countries[0] == '0') {
								$displayreferrer = 'display:none';
						}
							
					?>
					<tr id='referrerRow' style="<?php echo $displayreferrer?>">
						<td><?php echo $lang['register']['referrer_name'];?><a id="referrererr"></a></td>
						<td><input type="text" id="referrer" name="referrer" class="inputcmmn-1" value="<?php echo $form->value("referrer"); ?>" /><br/><div id="error"><?php echo $form->error("referrer"); ?></div></td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr>
						<td>
							<?php echo $lang['register']['front_national_id'];?>
							<a id="front_national_iderr"></a>
						</td>
						<td>
						<div>
							<div style="float:left;padding-top:10px;">
							<?php	$FrntNatidlabel = $lang['register']['upload_file'];
							$isFrntNatid = $form->value("isFrntNatid");
							$frntnatidex = explode('.',$isFrntNatid); 
									?>
									<?php if(end($frntnatidex)=='pdf') {
										$FrntNatidlabel = $lang['register']['upload_diff_file'];
										echo end(explode('/',$isFrntNatid));
									} 
							else if(!empty($isFrntNatid))
								{	$FrntNatidlabel = $lang['register']['upload_diff_file'];?>
									<img style="float:none" class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.end(explode('/',$isFrntNatid)) ?>" height="50" width="50" alt=""/>
							<?php } ?>
							</div>
							<!--[if lt IE 9]>
								<div>
									<input type="file" name="front_national_id" id="front_national_id" onchange="uploadfile(this)"/>
								</div>
								<![endif]-->
							<!--[if !IE]> -->
								<div class="fileType_hide">
									<input type="file" name="front_national_id" id="front_national_id" onchange="uploadfile(this)"/>
								</div>
								<div class="customfiletype" onclick="getfrontNationalId()"><?php echo $FrntNatidlabel?></div>
								<div style="clear:both"></div>
							<!-- <![endif]-->
							<div  id="front_national_id_file"></div>
							<br/><span><?php echo $lang['register']['allowed'];?></span><br/><div id="front_national_id_err"><?php echo $form->error("front_national_id"); ?></div>
						
						<input type="hidden" name="isFrntNatid" value="<?php echo $form->value("isFrntNatid"); ?>" />
					</div>
					</td>
					</tr>

					 <tr><td>&nbsp;</td></tr>

					 <tr style="<?php if($form->value("bcountry")=='ID')echo "display:none"; ?>" >
						<td>
							<?php echo $lang['register']['address_proof'];?>
							<a id="address_prooferr"></a>
						</td>
						<td style="vertical-align:top;margin-top:0">
							<div>
								<div style="float:left;padding-top:10px;">
								<?php	$addrprflabel = $lang['register']['upload_file'];
									$isaddrprf=$form->value("isaddrprf");
									$addrprfex = explode('.',$isaddrprf);
									?>
										<?php if(end($addrprfex)=='pdf') {
											$addrprflabel = $lang['register']['upload_diff_file'];
											echo end(explode('/',$isaddrprf));
										} 
								else 
								 if(!empty($isaddrprf)) {	
									 
									 $addrprflabel = $lang['register']['upload_diff_file'];?>
										<img style="float:none" class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.end(explode('/',$isaddrprf)) ?>" height="50" width="50" alt=""/>
								<?php	}	?>
								</div>
								<!--[if lt IE 9]>
									<div>
										<input type="file" name="address_proof" id="address_proof" value="" onchange="uploadfile(this)"/>
									</div>
								<![endif]-->
								<!--[if !IE]> -->
									<div class="fileType_hide">
										<input type="file" name="address_proof" id="address_proof" value="" onchange="uploadfile(this)"/>
									</div>
									<div class="customfiletype" onclick="getAddressProof()">
										<?php echo $addrprflabel?>
									</div>
									<div style="clear:both"></div>
								<!-- <![endif]-->
									<div  id="address_proof_file"></div>			
									<br/><span><?php echo $lang['register']['allowed'];?></span><br/><div id="address_proof_err"><?php echo $form->error("address_proof"); ?></div>
									<input type="hidden" name="isaddrprf" value="<?php echo $form->value("isaddrprf"); ?>" />
								</div>
						</td>
					</tr>
					 <tr><td>&nbsp;</td></tr>
					 <tr style="<?php if($form->value("bcountry")=='ID')echo "display:none"; ?>" >


						<td><?php echo $lang['register']['sign_recomform_name'];?>:<a id="sign_recomform_nameerr"></a></td>
						<td><input type="text" id="rec_form_offcr_name" name="rec_form_offcr_name"  class="inputcmmn-1" value="<?php echo $form->value("rec_form_offcr_name"); ?>" /><br/><div id="sign_recomform"><?php echo $form->error("rec_form_offcr_name"); ?></div></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr style="<?php if($form->value("bcountry")=='ID')echo "display:none"; ?>" >

						<td><?php echo $lang['register']['sign_recomform_num'];?>:<a id="sign_recomform_numerr"></a></td>
						<td><input type="text" id="rec_form_offcr_num" name="rec_form_offcr_num"  class="inputcmmn-1" value="<?php echo $form->value("rec_form_offcr_num"); ?>" /><br/><div id="sign_recomform"><?php echo $form->error("rec_form_offcr_num"); ?></div></td>
					</tr>
					<tr><td>&nbsp;</td></tr>

</div>
				<?php $params['minendorser']= $database->getAdminSetting('MinEndorser');
					  $endoresr_text= $session->formMessage($lang['register']['endorser'], $params);
				?>
				<table class="detail" style="<?php if($form->value("cntct_type")=='1' || $form->value("bcountry")!='BF')echo "display:block";elseif(isset($_SESSION['FB_Detail'])) echo "display:block"; else echo "display:none"; ?>" id="endorser" name="endorser" >
					<tr><td ><?php echo $endoresr_text; ?><?php echo $form->error("endorser"); ?></td></tr>
					<tr><td>&nbsp;</td></tr>

					<tr><td><?php echo $lang['register']['endorser1_name']?></td><td><?php echo $lang['register']['endorser1_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name1" value="<?php echo $form->value("endorser_name1"); ?>"/><?php echo $form->error("endorser_name1"); ?></td><td><input type="text" name="endorser_email1" value="<?php echo $form->value("endorser_email1"); ?>"/><?php echo $form->error("endorser_email1"); ?></td></tr>

					<tr><td><?php echo $lang['register']['endorser2_name']?></td><td><?php echo $lang['register']['endorser2_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name2" value="<?php echo $form->value("endorser_name2"); ?>"/><?php echo $form->error("endorser_name2"); ?></td><td><input type="text" name="endorser_email2" value="<?php echo $form->value("endorser_email2"); ?>"/><?php echo $form->error("endorser_email2"); ?></td></tr>

					<tr><td><?php echo $lang['register']['endorser3_name']?></td><td><?php echo $lang['register']['endorser3_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name3"/ value="<?php echo $form->value("endorser_name3"); ?>" ><?php echo $form->error("endorser_name3"); ?></td><td><input type="text" name="endorser_email3" value="<?php echo $form->value("endorser_email3"); ?>" /><?php echo $form->error("endorser_email3"); ?></td></tr>

					<tr><td><?php echo $lang['register']['endorser4_name']?></td><td><?php echo $lang['register']['endorser4_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name4" value="<?php echo $form->value("endorser_name4"); ?>" /><?php echo $form->error("endorser_name4"); ?></td><td><input type="text" name="endorser_email4" value="<?php echo $form->value("endorser_email4"); ?>"/><?php echo $form->error("endorser_email4"); ?></td></tr>

					<tr><td><?php echo $lang['register']['endorser5_name']?></td><td><?php echo $lang['register']['endorser5_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name5" value="<?php echo $form->value("endorser_name5"); ?>"/><?php echo $form->error("endorser_name5"); ?></td><td><input type="text" name="endorser_email5" value="<?php echo $form->value("endorser_email5"); ?>" /><?php echo $form->error("endorser_email5"); ?></td></tr>

					<tr><td><?php echo $lang['register']['endorser6_name']?></td><td><?php echo $lang['register']['endorser6_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name6" value="<?php echo $form->value("endorser_name6"); ?>" /><?php echo $form->error("endorser_name6"); ?></td><td><input type="text" name="endorser_email6" value="<?php echo $form->value("endorser_email6"); ?>" /><?php echo $form->error("endorser_email6"); ?></td></tr>

					<tr><td><?php echo $lang['register']['endorser7_name']?></td><td><?php echo $lang['register']['endorser7_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name7" value="<?php echo $form->value("endorser_name7"); ?>" /><?php echo $form->error("endorser_name7"); ?></td><td><input type="text" name="endorser_email7" value="<?php echo $form->value("endorser_email7"); ?>"/><?php echo $form->error("endorser_email7"); ?></td></tr>

					<tr><td><?php echo $lang['register']['endorser8_name']?></td><td><?php echo $lang['register']['endorser8_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name8" value="<?php echo $form->value("endorser_name8"); ?>"/><?php echo $form->error("endorser_name8"); ?></td><td><input type="text" name="endorser_email8" value="<?php echo $form->value("endorser_email8"); ?>" /><?php echo $form->error("endorser_email8"); ?></td></tr>
					<tr><td><?php echo $lang['register']['endorser9_name']?></td><td><?php echo $lang['register']['endorser9_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name9" value="<?php echo $form->value("endorser_name9"); ?>"/><?php echo $form->error("endorser_name9"); ?></td><td><input type="text" name="endorser_email9" value="<?php echo $form->value("endorser_email9"); ?>"/><?php echo $form->error("endorser_email9"); ?></td></tr>
					<tr><td><?php echo $lang['register']['endorser10_name']?></td><td><?php echo $lang['register']['endorser10_email']?></td></tr>
					<tr><td><input type="text" name="endorser_name10" value="<?php echo $form->value("endorser_name10"); ?>"/><?php echo $form->error("endorser_name10"); ?></td><td><input type="text" name="endorser_email10" value="<?php echo $form->value("endorser_email10"); ?>"/><?php echo $form->error("endorser_email10"); ?></td></tr>
				</table>
					<tr><td></td></tr>

				</tbody>
			</table>
			<table class="detail">
				<tbody>
					<tr><br/><br/>
						<td><strong><?php echo $lang['register']['t_c'];?></strong><br/></td>
					</tr>
					<tr>
						<td align="center" style="vertical-align:text-top">
							<div align="left" style="border: 1px solid black; padding: 0px 10px 10px; overflow: auto; line-height: 1.5em; width: 90%; height: 130px; background-color: rgb(255, 255, 255);">
							<?php
								include_once("./editables/legalagreement.php");
								$path1=	getEditablePath('legalagreement.php');
								include_once("./editables/".$path1);
								echo $lang['legalagreement']['b_tnc'];
							?>
							</div>
						</td>
					</tr>
					<tr><td></td></tr>
<!--
					<tr>
						<td align="left">

							<strong><?php echo $lang['register']['a_a'];?>:</strong>
							<INPUT TYPE="Radio" name="agree" id="agree" value="1" tabindex="3" />
							<?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
							<INPUT TYPE="Radio" name="agree" id="agree" value="0" tabindex="4" />
							<?php echo $lang['register']['no'];?>

						</td>
					</tr> 
-->
					<tr><td></td></tr>

					 <tr>
						<td colspan=2>
							<div style="float:left;width:295px;"><?php echo $lang['register']['capacha'];?></div><div style="float:left;padding-left:10px"><?php echo  recaptcha_get_html(RECAPCHA_PUBLIC_KEY, $form->error("user_guess")); ?></div>
							<a id="recaptcha_response_fielderr"></a>
						</td>
					</tr>


					<tr style='height:20px;'><td></td></tr>

					<tr style='height:20px;'><td></td></tr>

					<tr> 
						<td style="text-align:center;">
							<input type="hidden" name="reg-borrower" />
							<input type="hidden" name="tnc" id="tnc" value=0 />	
							<input type="hidden" name="uploadfileanchor" id="uploadfileanchor" />
							<input type="hidden" name="before_fb_data" id="before_fb_data" />
							<input type="hidden" name="fb_data" id="fb_data" value='<?php echo urlencode(addslashes(serialize($fbData))); ?>'/>
							<input type="submit" name='submitform'  id='borrowersubmitform' class="btn" value="<?php echo $lang['register']['Registerlater'];?>" onclick="needToConfirm = false;" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="submit" name='submitform' class="btn" value="<?php echo $lang['register']['RegisterComplete'];?>" onclick="needToConfirm = false;"  />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<?php 
	$bidMessage="You have successfully linked your Facebook account.";
	$post_link= SITE_URL;
	$share_msg= "Just joined Zidisha.";
	$sharephoto= SITE_URL."images/fb_logo.jpg"; 
	$short_url = "https%3A%2F%2Fwww.zidisha.org";
	?>
	<div id="shareForm" style="display:none">
			<div style="width:100%"  align="center">
				<div align="center" id="container">
					<div id="top_strip"></div><!--top_strip closed -->
					<div id="mid_strip">
						<div id="containt">
							<div id="upper" class="padding_prop" style="padding-top:5px;">
								<div class="left">
									<div class="thankyou_text left">Thank you!</div>
									<div class="upper_text right"><?php echo $bidMessage; ?></div>
									<div class="clear"></div>
								</div><!--left closed -->
								<!--right closed -->
								<div class="clear"></div>
							</div><!--upper closed -->
							<div id="lower" class="padding_prop">
								<div>
									<div class="left news_text">Now Share The News</div>
									<div class="left" style="padding-top:5px;">
										<div class="block2 shareTab1 <?php if(!isset($_SESSION['shareEmailValidate'])) { echo 'tab2'; } ?>" onClick="showBox(1);" style="cursor:pointer">
											<div  class="tab_space2" align="center">
												<img src="images/layout/popup/fb.png" border="0" />
										   </div>
										</div>
										<div class="block shareTab2" onClick="showBox(2);"  style="cursor:pointer">
											<div  class="tab_space" align="center">
												<img src="images/layout/popup/twitter.png"  border="0"/>
											</div>
										</div>
										<div  class="block shareTab3 <?php if(isset($_SESSION['shareEmailValidate'])) { echo 'tab'; } ?>" onClick="showBox(3);"  style="cursor:pointer">
											<div  class="tab_space" align="center">
												<img src="images/layout/popup/mail.png" />
											</div>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								<div id="slant">&nbsp;</div><!--slant closed -->
								<div id="data">
									<div class="left testi">
										<div class="black_small_text"><?php echo $share_msg; ?></div>
										<div class="link_text"><a href="<?php echo SITE_URL;?>">www.zidisha.org</a></div>
										<div align="left" id="bubble">
											<div class="space">
												<div class="left">
													<img src="images/logo.png"/>
												</div>
												<div class="left testi_text">
													<span><?php echo $share_msg; ?></span>
													&nbsp;<span class="link_text" style="font-style:normal;"><a target="_blank" class="link_text" href='<?php echo $post_link?>'>More</a></span>
												</div>
												<div class="clear"></div>
											</div><!--space closed -->
										</div><!--bubble closed -->
									</div><!--testi closed -->
									<div class="right form">
										<div class="shareTab1Detail" style="<?php if(isset($_SESSION['shareEmailValidate'])) { echo 'display:none'; } ?>">
											<div style="padding-top:20px">
												<a href="javascript:void(0)" onClick="fbshare();"><img src="images/layout/popup/fb_button.png" /></a>
											</div>
											<div style="padding-top:63px;width:200px;text-align:right">
												<a href="javascript:void(0)" onclick="$.facebox.close();" class=''>
												<img src="images/layout/popup/nothanks_button.png" /></a>
											</div>
										</div>
										<div class="shareTab2Detail" style="display:none">
											<table class="form_text" align="center" cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td valign="top" class="paddign_right_prop">NOW SHARE THE NEWS</td>
												</tr>
												<tr>
													<td style="padding-top:5px;"> 
														<textarea id="twtTextShare" name="twtTextShare" class="textarea_box2  textarea_text twtTextShare"><?php echo $share_msg; ?></textarea>
													</td>
												</tr>
												<tr>
													<td align="center" style="padding-top:10px;">
														<a href="javascript:void(0)" onClick="twtshare();"><img src="images/layout/popup/tweet_button.png" /></a>
													</td>
												</tr>
											</table>
										</div>
										<div class="shareTab3Detail" style="<?php if(!isset($_SESSION['shareEmailValidate']) || isset($_SESSION['ShareEmailSent'])) {echo 'display:none';} ?>">
											<form name="bidform1" action="process.php" method="post">
												<table class="form_text" align="center" cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td valign="top" class="paddign_right_prop">TO</td>
														<td>
															<input name="to_email" type="text" class="input_box" value="<?php echo $form->value('to_email'); ?>"/>
															<div><?php echo $form->error('to_email'); ?></div>
														</td>
													</tr>
													<tr><td class="top_padding_prop">&nbsp;</td></tr>
													<tr>
														<td valign="top" class="paddign_right_prop">NOTE</td>
														<td>
															<textarea name="note" class="textarea_box"><?php echo $form->value('note'); ?></textarea>
															<div><?php echo $form->error('note'); ?></div>
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
														<td style="padding-top:10px;">
															<div>
																<div class="left">
																	<div class="left"><input name="sendme" type="checkbox" /></div>
																	<div class="right" style="font-family:Arial;margin-top:3px;">Send me a copy</div>
																	<div class="clear"></div>
																</div>
																<div class="right" style="margin-right:5px;">
																	<input type="hidden" id="sendJoinEmail" name="sendJoinEmail" />
																	<input type="hidden" name="user_guess" value="<?php echo generateToken('sendJoinEmail'); ?>"/>
																	<input type="hidden" name="formbidpos" value="<?php echo $showShareBox ?>" />
																	<input type="hidden" name="email_sub" value="<?php echo $share_msg ?>" />
																	<?php unset($_SESSION['shareEmailValidate']);?>
																	<input type="image" src="images/layout/popup/send_button.png" name="Send"/>
																</div>
																<div class="clear"></div>
															</div>
														</td>
													</tr>
												</table>
											</form>
										</div>
										<div class='mail_sent_section' >
												<?php 
														if(isset($_SESSION['ShareEmailSent'])){ 
														echo"<div class='black_text_emailSent'>";
															echo"Your email has been sent.";
															unset($_SESSION['ShareEmailSent']);
														echo"</div>";
														echo"<div style='margin-top:30px'><input type='image' src='images/layout/popup/send_another_button.png' name='Send Again' onClick='showBox(3)' value=' '></div>";
													}?>
										</div>
									</div><!--form closed -->
									<div class="clear"></div><!--clear closed -->
								</div><!--data closed -->
							</div><!--lower closed -->
						</div><!--containt closed -->
					</div><!--mid_strip closed -->
					<div id="bottom_strip"></div><!--bottom_strip closed -->
				</div><!--container closed -->
			</div>		
		</div>
		
	<script language="JavaScript">
	  function checkreferal(country) { 
		  var v_array = [ "<?php if(isset($countries[0])) echo $countries[0] ?>" , "<?php if(isset($countries[1])) echo $countries[1]?>", "<?php if(isset($countries[2])) echo $countries[2]?>", "<?php if(isset($countries[3])) echo $countries[3]?>"];
			
			if($.inArray(country,v_array) !=-1) {
				document.getElementById("referrerRow").style.display = '';
			}else {
				document.getElementById("referrerRow").style.display = 'none';

			}
	  }
	function checkeligibility(id) {
		if (id=='debtfree') {
			if(!document.getElementById('repaidpast').checked) {
				document.getElementById('repaidpast_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';
				//alert('You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.')
			//document.getElementById('repaidpast').checked=true;
			}else {
				document.getElementById('repaidpast_err').innerHTML='';
				document.getElementById('debtfree_err').innerHTML='';
			}
		}
		else if (id=='share_update') {
			if(!document.getElementById('repaidpast').checked) {
				document.getElementById('repaidpast_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';

			//document.getElementById('repaidpast').checked=true;
			}else if(!document.getElementById('debtfree').checked) {
				document.getElementById('debtfree_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';
			}else {
				document.getElementById('repaidpast_err').innerHTML='';
				document.getElementById('debtfree_err').innerHTML='';
			}
		} else if(id=='repaidpast') {
			if(document.getElementById('repaidpast').checked) {
				document.getElementById('repaidpast_err').innerHTML='';
			}
		}
	}

	function validateborwrReg() {
		needToConfirm = false;
		var adresspr = document.getElementById('address_proof').value;
		var Frntid = document.getElementById('front_national_id').value;
		var legaldec = document.getElementById('legal_declaration').value;
		var bphoto = document.getElementById('bphoto').value;
		var ret = true;
		var errurl='';
			if(!legaldec || 0 === legaldec.length) {
			<?php if(empty($islgldecl)) { ?>
			document.getElementById('legal_declaration_err').innerHTML='<font color="red">Please upload a copy of the Legal Contract.</font>';
			//alert('Please upload a copy of the Recommendation Form.')
			errurl = "index.php?p=1&sel=1&lang="+"#legal_declaration_err";
			ret = false;
			<?php } ?>
		}else {
			document.getElementById('legal_declaration_err').innerHTML='';
		}


		if(!adresspr || 0 === adresspr.length) {
			<?php if(empty($isaddrprf)) { ?>
				document.getElementById('address_proof_err').innerHTML='<font color="red">Please upload a copy of the Recommendation Form.</font>';
				//alert('Please upload a copy of the Recommendation Form.')
				errurl = "index.php?p=1&sel=1&lang="+"#address_proof_err";
				ret = false;
			<?php }?>
		}else {
			document.getElementById('address_proof_err').innerHTML='';
		}


		if(!Frntid || 0 === Frntid.length) {
			<?php if(empty($isFrntNatid)) { ?>
				document.getElementById('front_national_id_err').innerHTML='<font color="red">Please upload a copy of the front of your national identification card.</font>';
				ret = false;
				errurl = "index.php?p=1&sel=1&lang="+"#front_national_id_err";
			<?php } ?>
			//alert('Please upload a copy of the front of your national identification card.')
			
		}else {
				document.getElementById('front_national_id_err').innerHTML='';
		}



		if(!bphoto || 0 === bphoto.length) {
			<?php if(empty($isPhoto_select)) { ?>
				document.getElementById('bphoto_err').innerHTML='<font color="red">Please upload a photo.</font>';
				errurl = "index.php?p=1&sel=1&lang="+"#bphoto_err";
				ret = false;
			<?php }?>
			//alert('Please upload a copy of the Recommendation Form.')
			
		}else {
			document.getElementById('bphoto_err').innerHTML='';
		}

		if(!document.getElementById('repaidpast').checked || !document.getElementById('debtfree').checked || !document.getElementById('share_update').checked) {
			document.getElementById('share_update_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';
				//alert('You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.')
				errurl = "index.php?p=1&sel=1&lang="+"#share_update_err";
				ret = false;
			//document.getElementById('repaidpast').checked=true;
		}else {
			document.getElementById('share_update_err').innerHTML='';
		}

		window.location = errurl;
		return ret;
	}

	  $('#bcountry').bind('change', function(event) {
		
		var countrycode = $('#bcountry').val();
		var data = "activeborrowers="+countrycode;
		$.ajax({
			url: 'process.php',
			type: 'post',
			//dataType: 'json',
			data: data,
			success: function(data, textStatus) {
				$("#refer_member").html(data);
			}
			//alert(data);
		});
	  });

	  $('#bcountry').bind('change', function(event) {
		
		var countrycode = $('#bcountry').val();
		var data = "volunteer_mentor="+countrycode;
		$.ajax({
			url: 'process.php',
			type: 'post',
			//dataType: 'json',
			data: data,
			success: function(data, textStatus) {
				$("#volunteer_mentor").html(data);
			}
			//alert(data);
		});
	  });

	  function open_contact(value){
		  if(value!=1){
			$('.facebook-auth').hide();
			document.getElementById('tele_contacts').style.display='';
			document.getElementById('FB_cntct_button').style.display='none';
			document.getElementById('fb_instruction').style.display='none';
			document.getElementById('endorser').style.display='none';
		  }else{
			$('.facebook-auth').show();
			document.getElementById('tele_contacts').style.display='none';
			document.getElementById('FB_cntct_button').style.display='';
			document.getElementById('fb_instruction').style.display='';
			document.getElementById('endorser').style.display='';
		  }
	  }
	if(<?php echo $showShareBox; ?>){
		jQuery.facebox({ div: '#shareForm' });
	}
	function showBox(box)
	{
		if(box==1) {
			$('.shareTab1').addClass('tab2');
			$('.shareTab2').removeClass('tab');
			$('.shareTab3').removeClass('tab');
			$('.shareTab1Detail').show();
			$('.shareTab2Detail').hide();
			$('.shareTab3Detail').hide();
			$('.mail_sent_section').hide();
		}
		else if(box==2) {
			$('.shareTab2').addClass('tab');
			$('.shareTab3').removeClass('tab');
			$('.shareTab1').removeClass('tab2');
			$('.shareTab2Detail').show();
			$('.shareTab3Detail').hide();
			$('.shareTab1Detail').hide();
			$('.mail_sent_section').hide();
		}
		else {
			$('.shareTab3').addClass('tab');
			$('.shareTab1').removeClass('tab2');
			$('.shareTab2').removeClass('tab');
			$('.shareTab3Detail').show();
			$('.shareTab1Detail').hide();
			$('.shareTab2Detail').hide();
			$('.mail_sent_section').hide();
		}
		
	}
	var twtUrl= "<?php echo $post_link; ?>";
	function fbshare()
	{
		var fburl="http://www.facebook.com/sharer.php?s=100&p[title]=<?php echo urlencode($share_msg);?>&p[url]=<?php echo $short_url; ?>&p[images][0]=<?php echo $sharephoto; ?>";
		window.open(fburl,'','width=600,height=450,left=200,top=200');
	}
	function twtshare()
	{
		$('.twtTextShare').each(function(){
		   twttext = $(this).val();
		});
		var twitterParams = { 
			url: twtUrl, 
			text: twttext 
		}; 
		var twturl="http://twitter.com/share?" + $.param(twitterParams);
		window.open(twturl,'','width=600,height=450,left=200,top=200');
	}		
	</script><script language="JavaScript">
		var newwindow;
		function login_popup(url) {
		 var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
		 screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
		 outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
		 outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
		 width    = 500,
		 height   = 500,
		 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
		 top_win  = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
		 features = (
		  'width=' + width +
		  ',height=' + height +
		  ',left=' + left +
		  ',top=' + top_win+
		  ',scroll=yes'
		 );
		 if(url=='fs' || url=='undefined') {
		  newwindow=window.open('login/','',features);
		  } else {
		  newwindow=window.open(url,'',features);
		 }
		 if (window.focus) {
		  newwindow.focus()
		 }
		 return false;
		}
	</script>
	<script language="JavaScript">
	function submitform1()
	{
		document.getElementById("before_fb_data").value = "1";
		document.forms["sub-borrower"].submit();
	}
	function show_tele_cntct(country){
		if(country!='BF'){
				document.getElementById("telephone_contact").style.display = 'none';
				document.getElementById("tele_contacts").style.display = '';
				document.getElementById("contact_type").style.display = 'none';
				document.getElementById('endorser').style.display='';
				document.getElementById('brwr_behalf').style.display='none';
				document.getElementById('facebook_optional').style.display='none';
				document.getElementById('facebook_result').style.display='none';
				document.getElementById('fb_mandatory').style.display='';
				$('#FB_cntct').attr('checked',true);
		}else{
				document.getElementById("telephone_contact").style.display = '';
				document.getElementById("tele_contacts").style.display = 'none';
				document.getElementById("contact_type").style.display = '';
				document.getElementById('endorser').style.display='none';
				document.getElementById('brwr_behalf').style.display='';
				document.getElementById('facebook_optional').style.display='';
				document.getElementById('facebook_result').style.display='';
				document.getElementById('fb_mandatory').style.display='none';
				$('#FB_cntct').attr('checked',false);
		}
	}
	</script>

	<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
	<div id='ResidentialaddrExample' style="display:none">
	<div class="instruction_space" style="margin-top:10px;height:160px">
		<div class="instruction_text"><?php echo $lang['register']['addresexample']?></div>
	</div>
	</div>
	<script language="JavaScript">
		var ids = new Array('busername', 'bpass1', 'bpass2','bfname','blname', 'bphoto','bpostadd','bcity','bcountry','bnationid','bloanhist','community_name_no','bemail','bmobile','bincome','babout','bbizdesc','referrer','front_national_id','back_national_id','address_proof','legal_declaration','agree');
		var values = new Array('','','','','','','','','','','','','','','','','','','','','');
		var needToConfirm = true;
		populateArrays();

	function get_volunteers(vm_city){
		if (window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}
		else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			document.getElementById("volunteer_mentor").innerHTML=xmlhttp.responseText;
			}
		}
		var a="vm_city="+vm_city;
		xmlhttp.open("POST","process.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Content-length",a.length);
		xmlhttp.setRequestHeader("Connection","close");
		xmlhttp.send(a);
	}
	</script>
<?php 
}else{
		echo "You have already registered.";
	} ?>