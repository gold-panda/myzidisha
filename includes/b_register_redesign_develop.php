<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<link href="library/tooltips/btnew.css" rel="stylesheet" type="text/css" />
<?php
if(empty($session->userid)){
	$showShareBox=0;
	$fbmsg_hide=0;
	$web_acc=0;
	$fb_fail_reason=isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : ''.'During Facebook Account linked to Zidisha,Borrower close the Registration page';
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
		<p class="register_info"><?php echo $lang['register']['borrower_inst']; ?></p>
		<form enctype="multipart/form-data" id="sub-borrower" name="sub-borrower" method="post" action="process.php">
			
			<div class="holder_342 group">
				<p class="blue_color uppercase formTitle"><?php echo $lang['register']['create_account']; ?></p>

				<!-- country -->
				<br/>
				<label>
					<?php echo $lang['register']['Country']?>
					<a id="bcountryerr"></a>
				</label>
				<div class="arrow_hider">
					<select id="bcountry" class="custom_select" name="bcountry"  onchange="needToConfirm = false;checkreferal(this.value); show_tele_cntct(this.value);submitform1();">
						<option value='0'>Select Country</option>
						<?php	$result1 = $database->countryList(true);
								$i=0;
								foreach($result1 as $state)
								{	?>
									<option value='<?php echo $state['code'] ?>' <?php if($form->value("bcountry")==$state['code']) echo "selected" ?>><?php echo $state['name'] ?></option>
						<?php	}	?>
					</select>
				</div>
				<br/><?php echo $form->error("bcountry"); ?>
			</div>

				<!-- Hidden info for BF -->
				<?php
					$brwrBehalf = $form->value('borrower_behalf');
					if($form->value("bcountry")!='BF') {
						$brwrBehalf=0;
				}?>
				<div class="group" style="margin-bottom:0px; <?php if($form->value("bcountry")!='BF') echo 'display:none'; else echo 'display:block'; ?>" id= "brwr_behalf" >
					<a id="borrower_behalferr"></a>
					<label><?php echo $lang['register']['borrower_behalf'];?></label>

					<!-- option 1 -->
					<div class="radio_s no_left">
						<input type="radio" id="borrower_behalf" name="borrower_behalf" onclick ="document.getElementById('borwr_behalf_section').style.display = 'none';" class="inputcmmn-1" value="0" <?php if($brwrBehalf=='0' || !isset($brwrBehalf)) echo"checked";?> />
						<span class="left"><?php echo $lang['register']['borrower_behalf1'];?></span>
					</div>	
					<div class="radio_s no_left">
						<input type="radio" id="borrower_behalf" name="borrower_behalf" onclick ="document.getElementById('borwr_behalf_section').style.display = '';" class="inputcmmn-1" value="1" <?php if($brwrBehalf!='0') echo"checked";?> />
						<span class="left"><?php echo $lang['register']['borrower_behalf2'];?></span>
					</div>
					<br/><div id=""><?php echo $form->error("borrower_behalf"); ?></div>

				</div>

				<?php $display = '';
				if($brwrBehalf=='0') {
						
						$display='display:none';
				}?> 
				<div class="holder_342 group">
					<div id='borwr_behalf_section' style="clear:both;margin-top:25px;text-decoration:none;<?php echo $display?>" >
						<a href="https://sites.google.com/site/zidishavolunteermentor/working-with-new-applicants" target="_blank"><?php echo $lang['register']['behalf_guideline'];?>
						
						<!-- field one -->
						<label>
							<?php echo $lang['register']['behalf_name'];?>
							<a id="behalf_nameerr"></a>
						</label>
						<input type="text" id="behalf_name" name="behalf_name" value="<?php echo $form->value("behalf_name"); ?>"/>
						<br/>
						<div id="behalf_name"><?php echo $form->error("behalf_name"); ?></div>

						<!-- field two -->
						<label>
							<?php echo $lang['register']['behalf_number'];?>
							<a id="behalf_numbererr"></a>
						</label>
						<input type="text" id="behalf_number" name="behalf_number" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("behalf_number"); ?>" />
						<br/>
						<div id=""><?php echo $form->error("behalf_number"); ?></div>

						<!-- field three -->
						<label>
							<?php echo $lang['register']['behalf_email'];?>
							<a id="behalf_emailerr"></a>
						</label>
						<input type="text" id="behalf_email" name="behalf_email" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("behalf_email"); ?>" />
						<br/>
						<div id="behalf_email"><?php echo $form->error("behalf_email"); ?></div>

						<!-- field four -->
						<label>
							<?php echo $lang['register']['behalf_town'];?>
							<a id="behalf_townerr"></a>
						</label>
						<input type="text" id="behalf_town" name="behalf_town" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("behalf_town"); ?>" />
						<br/>
						<div id="behalf_town"><?php echo $form->error("behalf_town"); ?></div>
					</div>
				</div>

			<div class="holder_342 group">
				<!-- facebook button -->

				<div id="fb_mandatory" style="<?php if($form->value("bcountry")!='BF')echo "display:''"; else echo "display:none"; ?>" >
					<br/>
					<label>
						<?php echo $lang['register']['facebook_mandatory']; ?>
						<?php echo $form->error("cntct_type"); ?>
					</label>
						
					<?php $fbData=$session->facebook_connect();
						if(!empty($fbData['user_profile']['id'])){
							$database->saveFacebookInfo($fbData['user_profile']['id'], serialize($fbData), $web_acc, 0, '', $fb_fail_reason);
						}
						if($fbData['loginUrl']==''){ 
							$showShareBox=1;
							
							if(isset($_REQUEST['fb_join']) || $_SESSION['FB_Error']!=false){ 
								$showShareBox=0;
							} ?>
					
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="javascript:login_popup('<?php echo $fbData['logoutUrl']?>');return false;"><img src="images/f_disconnect.jpg"/></a>
						
						<?php }else{?>
								<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="login_popup('<?php echo $fbData['loginUrl']?>');" ><img src="images/facebook-connect.png"/></a>
						<?php }?>
						
				</div>

				<div style="<?php if(($form->value("bcountry")!='BF') && $fbmsg_hide!=1)echo "display:block"; else echo "display:none"; ?>" >
					<?php 
					if(isset($_SESSION['FB_Detail'])){
						echo "<div align='center'><font color=green><strong>Your Facebook account is now linked to Zidisha.</strong></font></div><br/>";
					}
						
					if(isset($_SESSION['FB_Error'])){
						echo $_SESSION['FB_Error'];
					}?>
				</div>


				<!-- Create username -->
				<br/>
				<label><?php echo $lang['register']['endorser_uname'];?><a name="busernameerr" id="busernameerr"></a></label>
				<input type="text" id="busername" name="busername" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("busername"); ?>" />
				<br/>
				<div id="bunerror"><?php echo $form->error("busername"); ?></div>

				<!-- Create password -->
				<br/>
				<label><?php echo $lang['register']['ppassword'];?><a id="bpass1err"></a></label>
				<input type="password" id="bpass1" name="bpass1" class="inputcmmn-1" value="<?php echo $form->value("bpass1"); ?>" />
				<br/>
				<div id="passerror"><?php echo $form->error("bpass1"); ?></div>

				<!-- E-mail address -->
				<br/>
				<label><?php echo $lang['register']['email'];?><a id="bemailerr"></a></label>
				<input type="text" id="bemail" name="bemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("bemail"); ?>" />
				<br/>
				<div id="emailerror"><?php echo $form->error("bemail"); ?></div>
			</div>

			<!-- hr tag and beginning Contact Info -->
			
			<div class="holder_522 group">
				<br/>
				<hr/>
				<p class="blue_color uppercase formTitle"><?php echo $lang['register']['contact_info']; ?></p>

				<!-- First name -->
				<br/>
				<label><?php echo $lang['register']['b_fname'];?><a id="bfnameerr"></a></label>
				<input type="text" name="bfname" id='bfname' maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("bfname"); ?>"/>
				<br/>
				<?php echo $form->error("bfname"); ?>

				<!-- Last name -->
				<br/>
				<label><?php echo $lang['register']['b_lname'];?><a id="blnameerr"></a></label>
				<input type="text" name="blname" id="blname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("blname"); ?>"/>
				<br/>
				<?php echo $form->error("blname"); ?>

				<!-- Neighborhood -->
				<br/>
				<label><?php echo $lang['register']['paddress'];?><a id="bpostadderr"></a></label>
				<input type="text" name="bpostadd" id='bpostadd' class="inputcmmn-1" value="<?php echo $form->value("bpostadd"); ?>"/>
				<br/>
				<?php echo $form->error("bpostadd"); ?>

				<?php $params['padd_ex']= $_SERVER['REQUEST_URI'].'#ResidentialaddrExample'; 
				$home_no_label= $session->formMessage($lang['register']['home_no'], $params); ?>

				<!-- House number -->
				<br/>
				<label><?php echo $home_no_label;?><a id="home_noerr"></a></label>
				<textarea name="home_no" id='home_no' class="textareacmmn" ><?php echo $form->value("home_no"); ?></textarea>
				<br/>
				<?php echo $form->error("home_no"); ?>

				<!-- City or village -->
				<br/>
				<label><?php echo $lang['register']['City'];?><a id="bcityerr"></a></label>
				<input type="text" name="bcity" id="bcity" maxlength="50" class="inputcmmn-1" value="<?php echo $form->value("bcity"); ?>"/>
				<br/>
				<?php echo $form->error("bcity"); ?>

				<!-- National ID Number -->
				<br/>
				<label><?php echo $lang['register']['nationid'];?><a id="bnationiderr"></a></label>
				<input type="text" name="bnationid" id='bnationid' maxlength="50" class="inputcmmn-1" value="<?php echo $form->value("bnationid"); ?>" />
				<br/>
				<?php echo $form->error("bnationid"); ?>

				<!-- Phone/mobile number -->
				<br/>
				<label>
					<?php 
					
					if($form->value("bcountry")=='KE') {
						echo "Please enter your Safaricom mobile phone number. This must be a Safaricom number registered under your own name.";
					} else if($form->value("bcountry")=='GH') {
					echo "Please enter your MTN mobile phone number. This must be an MTN number registered under your own name.";
					} else {
					echo $lang['register']['tel_mob_no'];
					}
					?><a id="bmobileerr"></a>
				</label>
				<input type="text" id="bmobile" name="bmobile" maxlength="15" class="inputcmmn-1" value="<?php echo $form->value("bmobile"); ?>" />
				<br/>
				<div id="mobileerror"><?php echo $form->error("bmobile"); ?></div>
				

			<!--  Start References -->

			<div class="holder_522 group">
				<hr/>
				<p class="blue_color uppercase formTitle"><?php echo $lang['register']['references']; ?></p>
		
				<!-- member name -->
				<br/>
				<label><?php echo $lang['register']['reffered_member'];?><a id="refer_membererr"></a></label>
				<div class="arrow_hider_big">
					<select id="refer_member" class="custom_select" name="refer_member"><option value='0'>None</option>
					<?php foreach($borrowers as $borrower){ ?>
						<option value="<?php echo $borrower['userid']?>" <?php if($form->value("refer_member")==$borrower['userid']) echo "Selected";?>><?php echo $borrower['FirstName']." ".$borrower['LastName']." (".$borrower['City'].")";?></option>
					<?php } ?>		
					</select>
				</div>
				<br/>
				<div id="refer_membererror"><?php echo $form->error("refer_member"); ?></div>

				<!-- town or village located -->
				<br/>
				<label><?php echo $lang['register']['nearest_city'];?><a id="volunteer_mentorerr"></a></label>
				<div class="arrow_hider_big">
					<select class="custom_select" id="vm_city" name="vm_city" onchange="get_volunteers(this.value)">
						<?php 
							if(!empty($vmcities)){?>
						<?php	for($x=0;$x<count($vmcities);$x++){?>
									<option value="<?php echo $vmcities[$x]?>" <?php if(strcasecmp($form->value("vm_city"), $vmcities[$x])==0) echo "Selected"?>><?php echo $vmcities[$x];?></option>
						<?php	}
							}?>
					</select>
				</div>

				<!-- Volunteer mentor -->
				<br/>
				<label><?php echo $lang['register']['volunteer_mentor'];?></label>
				<div class="arrow_hider_big">
					<select id="volunteer_mentor" class="custom_select" name="volunteer_mentor">
						<?php  if(!empty($vmByCity)){
								foreach($vmByCity as $key=>$row){
									$name= $database->getNameById($key);?>
									<option value='<?php echo $key ?>' <?php if($form->value("volunteer_mentor")==$key){ echo 'selected'; } ?>><?php echo $name ?></option>
						<?php	}
							} else { ?>		
								<option></option>
						<?php	} ?>
						</select>
					<br/><div id="volunteer_mentorerror"><?php echo $form->error("volunteer_mentor"); ?></div>
				</div>

				<!-- Sign reconform name -->
				<br/>
				<label>
					<?php echo $lang['register']['sign_recomform_name'];?>
					<a id="sign_recomform_nameerr"></a>
				</label>
				<input type="text" id="rec_form_offcr_name" name="rec_form_offcr_name"  class="inputcmmn-1" value="<?php echo $form->value("rec_form_offcr_name"); ?>" />
				<br/>
				<div id="sign_recomform"><?php echo $form->error("rec_form_offcr_name"); ?></div>

				<!-- Sign reconform num -->
				<br/>
				<label>
					<?php echo $lang['register']['sign_recomform_num'];?>
					<a id="sign_recomform_numerr"></a>
				</label>
				<input type="text" id="rec_form_offcr_num" name="rec_form_offcr_num"  class="inputcmmn-1" value="<?php echo $form->value("rec_form_offcr_num"); ?>" />
				<br/>
				<div id="sign_recomform"><?php echo $form->error("rec_form_offcr_num"); ?></div>

				<!-- Contact type -->
				<div style="<?php if($form->value("bcountry")!='BF') echo 'display:none'; else echo 'display:block';?>" id="contact_type" >
					<br/>
					<label>
						<?php echo $lang['register']['contact_type']?>
						<a id="cntct_type"></a>
					</label>
					<br/><?php echo $form->error("contact_type"); ?>
				</div>

					<!-- facebook_optional -->
					<div style="<?php if($form->value("bcountry")!='BF') echo 'display:none'; else echo "display:''";?>" id="facebook_optional">
						<div class="radio_s no_left">
							<input type="radio" name="cntct_type" id="FB_cntct" value="1" onclick="needToConfirm = false;open_contact(this.value);submitform1();" <?php if($form->value("cntct_type")=='1' || isset($_SESSION['FB_Detail'])) echo"checked"; ?>>
							<div>
								<div><?php echo $lang['register']['FB_contact']." (This must be your own, actively used Facebook account.)";?></div>
								<div id="fb_instruction" style="margin: 15px 0 0 15px; <?php if($form->value("cntct_type")=='1')echo "display:block;"; else echo "display:none;"; ?>"><?php echo $lang['register']['fb_instruction']; ?></div><br/>
								<?php echo $form->error("cntct_type"); ?>
							</div>
							<div>
								<?php
								if($fbData['loginUrl']==''){ ?>
									<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="javascript:login_popup('<?php echo $fbData['logoutUrl']?>');return false;" style="<?php if($form->value("cntct_type")=='1' || isset($_SESSION['FB_Detail']))echo "display:''"; else echo "display:none"; ?>"><img src="images/f_disconnect.jpg"/></a>
								<?php }else{?>
									<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="login_popup('<?php echo $fbData['loginUrl']?>');" style="<?php if($form->value("cntct_type")=='1' || isset($_SESSION['FB_Detail']))echo "display:block"; else echo "display:none"; ?>"><img src="images/facebook-connect.png"/></a>
								<?php }?>
							</div>
							<div  style="<?php if($form->value("bcountry")!='BF' || $fbmsg_hide==1) echo 'display:none'; else echo 'display:block';?>" id="facebook_result">
								<?php 
									if(isset($_SESSION['FB_Detail'])){
										echo "<div align='center'><font color=green><strong>Your Facebook account is now linked to Zidisha.</strong></font></div><br/>";
									}
									if(isset($_SESSION['FB_Error'])){
										echo $_SESSION['FB_Error'];
								}?>
							</div>
						</div>
					</div>

			</div>


			<div class="holder_522 group" id="telephone_contact" style="<?php if($form->value("bcountry")!='BF') echo 'display:none'; else echo 'display:block';?>">
				<div class="radio_s no_left">
					<input type="radio" name="cntct_type" id="tel_cntct" onclick="open_contact(this.value);" value="0" <?php if($form->value("cntct_type")=='0') echo"checked";?>>
					<span class="left" style="color:#444444;" ><?php echo $lang['register']['tel_contact']?></span>
				</div> 
			</div>

			<div class="holder_522 group" style="<?php if($form->value("bcountry")!='BF')echo "display:block"; else echo "display:none"; ?> margin-top: 25px;" id="tele_contacts">

				<br/>
				<label><?php echo $lang['register']['family_contact']?><a id="bfamilycontact"></a></label>

				<!-- family 1 -->
				<br/>
				<label><?php echo $lang['register']['family_contact1']?>:<a id="bfamilycontact1"></a></label>
				<input type="text" name="bfamilycont1" id='bfamilycont1' class="textareacmmn" ><?php echo $form->value("bfamilycont1"); ?></textarea>
				<br/>
				<?php echo $form->error("bfamilycont1"); ?>

				<!-- family 2 -->
				<br/>
				<label><?php echo $lang['register']['family_contact2']?>:<a id="bfamilycontact2"></a></label>
				<input type="text" name="bfamilycont2" id='bfamilycont2' class="textareacmmn" ><?php echo $form->value("bfamilycont2"); ?></textarea>
				<br/>
				<?php echo $form->error("bfamilycont2"); ?>

				<!-- family 3 -->
				<br/>
				<label><?php echo $lang['register']['family_contact3']?>:<a id="bfamilycontact3"></a></label>
				<input type="text" name="bfamilycont3" id='bfamilycont3' class="textareacmmn" ><?php echo $form->value("bfamilycont3"); ?></textarea>
				<br/>
				<?php echo $form->error("bfamilycont3"); ?>

				<!-- info text -->
				<br/>
				<label><?php echo $lang['register']['neigh_contact'];?><a id="bneighcontact"></a></label>

				<!-- neighboor 1 -->
				<br/>
				<label><?php echo $lang['register']['neigh_contact1']?>:<a id="bneighcontact1"></a></label>
				<input type="text" name="bneighcont1" id='bneighcont1' class="textareacmmn" ><?php echo $form->value("bneighcont1"); ?></textarea>
				<br/>
				<?php echo $form->error("bneighcont1"); ?>

				<!-- neighboor 2 -->
				<br/>
				<label><?php echo $lang['register']['neigh_contact2']?>:<a id="bneighcontact2"></a></label>
				<input type="text" name="bneighcont2" id='bneighcont2' class="textareacmmn" ><?php echo $form->value("bneighcont2"); ?></textarea>
				<br/>
				<?php echo $form->error("bneighcont2"); ?>

				<!-- neighboor 3 -->
				<br/>
				<label><?php echo $lang['register']['neigh_contact3']?>:<a id="bneighcontact3"></a></label>
				<input type="text" name="bneighcont3" id='bneighcont3' class="textareacmmn" ><?php echo $form->value("bneighcont3"); ?></textarea>
				<br/>
				<?php echo $form->error("bneighcont3"); ?>
			

				<!-- hear about zidisha -->
				<br/>
				<label><?php echo $lang['register']['reffered_by'];?><a id="breffered_by"></a></label>
				<textarea name="reffered_by" id='reffered_by' class="textareacmmn" style="height:130px;"><?php echo $form->value("reffered_by"); ?></textarea><br/>
				<div id="reffered_by_err"><?php echo $form->error("reffered_by"); ?>
				</div>
			</div>

			<!-- Start Public Profile -->
			<br/>
			<hr/>
			<div class="holder_522 group">
				<p class="blue_color uppercase formTitle"><?php echo $lang['register']['public_profile']; ?></p>

				<div>

				<!-- Upload a photo -->
				<br/>
				<label>
					<?php echo $lang['register']['photo_note']; ?><br/>
				</label>

				<div>
					<div style="float:left;padding-top:10px;">
						<?php $photolabel = $lang['register']['upload_photo'];
						$isPhoto_select=$form->value("isPhoto_select");
							if(!empty($isPhoto_select))
							{ $photolabel = $lang['register']['upload_diffphoto'];
							?>
								<img style="float:none" class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.$isPhoto_select ?>" height="50" width="50" alt=""/>
						<?php }else if( ! empty($fbData['user_profile']['id'])){
							
										echo "<img style='max-width:200px;' src='https://graph.facebook.com/".$fbData['user_profile']['id']."/picture?width=9999&height=9999' />";
										$photolabel = $lang['register']['upload_diffphoto'];
									}
						
						?>
						
						
					</div>
					

					<div class='fileType_hide'>
						<input type="file" name="bphoto" id="bphoto"   value="<?php echo $form->value("bphoto"); ?>" onchange="uploadfile(this)"/>
					</div>
								

					<div class="customfiletype selectPhoto" onclick="getbphoto()">
						<span><?php echo $photolabel?></span>
					</div>
					<div style="clear:both"></div>
					<div  id="bphoto_file"></div>
				</div>
				<span><?php echo $lang['register']['allowed'];?></span>
				<div id="bphoto_err">
					<?php echo $form->error("bphoto"); ?>
				</div>
				<input type="hidden" name="isPhoto_select" value="<?php echo $form->value("isPhoto_select"); ?>" />
			
				<!-- about yourself -->
				<br/>
				<label><?php echo $lang['register']['A_Yourself'];?><a id="babouterr"></a></label>
				<textarea name="babout" id='babout' class="textareacmmn" style="height:130px;"><?php echo $form->value("babout"); ?></textarea><br/>
				<div id="babout_err"><?php echo $form->error("babout"); ?></div>

				<!-- your business -->
				<br/>
				<label><?php echo $lang['register']['bdescription'];?><a id="bbizdescerr"></a></label>
				<textarea name="bbizdesc" id='bbizdesc' class="textareacmmn" style="height:130px;"><?php echo $form->value("bbizdesc"); ?></textarea><br/>
				<div id="brbizdesc_err"><?php echo $form->error("bbizdesc"); ?></div>

				<?php
					$displayreferrer = '';
					if($countries[0] == '0') {
							$displayreferrer = 'display:none';
					}	
				?>
				<div id='referrerRow' style="<?php echo $displayreferrer?>" >
					<label><?php echo $lang['register']['referrer_name'];?><a id="referrererr"></a></label>
					<input type="text" id="referrer" name="referrer" class="inputcmmn-1" value="<?php echo $form->value("referrer"); ?>" />
					<br/>
					<div id="error"><?php echo $form->error("referrer"); ?></div>					
				</div>
				
			</div>

			<!-- start terms -->
			<br/>
			<hr/>
			<div class="group">
				<p class="terms_of_use blue_color uppercase formTitle"><?php echo $lang['register']['t_c'];?></p>
				<br/>
				<div id="basic-modal-content" align="left">
					<p class="terms_of_use_modal blue_color uppercase"><?php echo $lang['register']['t_c'];?></p>
					<?php
						include_once("./editables/legalagreement.php");
						$path1=	getEditablePath('legalagreement.php');
						include_once("./editables/".$path1);
						echo $lang['legalagreement']['b_tnc'];
					?>
				</div>
			
			<!-- Accept terms -->
			<div class="group">

				<label><INPUT TYPE="checkbox" name="agree" id="agree" value="1" tabindex="3" /><?php echo $lang['register']['a_a1']; ?> <a class="terms_of_use_action" href="#"><?php echo $lang['register']['a_a']; ?></a>.</label>
			
			<br/>
			</div>
			<br/><br/>
			</div>

				<!-- CAPTCHA no longer displayed -->
				<div style="display:none;">
					<label><?php echo $lang['register']['capacha'];?></label>
					<div style="margin-top:20px"><?php echo  recaptcha_get_html(RECAPCHA_PUBLIC_KEY, $form->error("user_guess")); ?></div>
					<a id="recaptcha_response_fielderr"></a>
				</div>

				<!-- submit final form -->
				<div class="group" style="margin-top:25px;">
					<input type="hidden" name="reg-borrower" />
					<input type="hidden" name="tnc" id="tnc" value=0 />	
					<input type="hidden" name="uploadfileanchor" id="uploadfileanchor" />
					<input type="hidden" name="before_fb_data" id="before_fb_data" />
					<input type="hidden" name="fb_data" id="fb_data" value='<?php echo urlencode(addslashes(serialize($fbData))); ?>'/>	
					
					<input type="submit" name='submitform' id="submit_btn" class="btn" align="center" value="<?php echo $lang['register']['RegisterComplete'];?>" onclick="return verifyTnC();"  />
				
					<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

					<a id="saveLaterLink" href="#"><?php echo $lang['register']['Registerlater'];?></a>

				</div>

			</div>
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
				//document.getElementById('endorser').style.display='';
				document.getElementById('brwr_behalf').style.display='none';
				document.getElementById('facebook_optional').style.display='none';
				document.getElementById('facebook_result').style.display='none';
				document.getElementById('fb_mandatory').style.display='';
				$('#FB_cntct').attr('checked',true);
		}else{
				document.getElementById("telephone_contact").style.display = '';
				document.getElementById("tele_contacts").style.display = 'none';
				document.getElementById("contact_type").style.display = '';
				//document.getElementById('endorser').style.display='none';
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

	<script type="text/javascript">
// A $( document ).ready() block.
// http://learn.jquery.com/using-jquery-core/document-ready/
//
		$( document ).ready(function() {

  			$('#saveLaterLink').click(function(e){
    			e.preventDefault();
    			needToConfirm = false;
   		 		$('#sub-borrower').submit();
  			});

		});

	</script>
<?php 
}else{
		echo "You have already created an account. You may access your account using the login form above.";
	} ?>