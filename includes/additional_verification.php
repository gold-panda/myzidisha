<link href="library/tooltips/btnew.css" rel="stylesheet" type="text/css" />
<?php
$showShareBox=0;
$fbmsg_hide=0;
$web_acc=0;
$fb_fail_reason='Borrower Edit Profile : '.isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : '';
if(!empty($form->values)){
	$_SESSION['fb_data']=$form->values;
}
if(isset($_REQUEST['fb_data'])){
	$form->values= $_SESSION['fb_data'];
}
if(isset($_SESSION['FB_Detail']) && !isset($_SESSION['hide_fbmsg'])){
	$web_acc=1;
	$fb_fail_reason='Borrower Edit Profile Ok : '.isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : '';
}
$fb_reload=false;
if(isset($_REQUEST['code'])){ 
	$fb_reload=true;
	echo"<script type='text/javascript'>window.close();
	window.opener.location.reload();
	</script>";
}
if(isset($_REQUEST['error_reason'])){
	$fb_reload=true;
	echo"<script type='text/javascript'>window.close();
	window.opener.location.reload();</script>";
}
if(isset($_REQUEST['fb_disconnect'])) { 
	$session->facebook_disconnect();	
	$fb_reload=true;
	echo"<script type='text/javascript'>window.close();window.opener.location.reload();</script>";
}
if($fb_reload) {
	echo"<script type='text/javascript'>
	var url = window.location.href;    
	url += '\/#FB_cntct\/';
	window.location.href = url;
	</script>";
}
if(isset($_SESSION['hide_fbmsg'])){
	$fbmsg_hide=1;
	unset($_SESSION['hide_fbmsg']);
}
$id=$session->userid;
$data=$database->getBorrowerDetails($id); 
$language=$data['lang'];
$fname=$data['FirstName'];
$lname=$data['LastName'];
$name=$fname.' '.$lname;
$fronNationalid = $data['frontNationalId'];
$addresProof = $data['addressProof'];
$docuploaded['fronNationalid'] = $fronNationalid;
$docuploaded['addresProof'] = $addresProof;
$borrowerActive = $data['Active'];
$iscomplete_later = $data['iscomplete_later'];
$rec_form_offcr_name = $data['rec_form_offcr_name'];
$rec_form_offcr_num = $data['rec_form_offcr_num'];
$facebook_id=$data['facebook_id'];

$endorser_name1='';
$endorser_email1='';
$endorser_id1='';
$endorser_name2='';
$endorser_email2='';
$endorser_id2='';
$endorser_name3='';
$endorser_email3='';
$endorser_id3='';
$endorser_name4='';
$endorser_email4='';
$endorser_id4='';
$endorser_name5='';
$endorser_email5='';
$endorser_id5='';
$endorser_name6='';
$endorser_email6='';
$endorser_id6='';
$endorser_name7='';
$endorser_email7='';
$endorser_id7='';
$endorser_name8='';
$endorser_email8='';
$endorser_id8='';
$endorser_name9='';
$endorser_email9='';
$endorser_id9='';
$endorser_name10='';
$endorser_email10='';
$endorser_id10='';
$endorser_details= $database->getEndorserDetail($id);
if(!empty($endorser_details)){
	$k=1;
	foreach($endorser_details as $endorser_detail){
		${'endorser_name'.$k}= $endorser_detail['ename'];
		${'endorser_email'.$k}= $endorser_detail['e_email'];
		${'endorser_id'.$k}= $endorser_detail['id'];
		$k++;
	}
}
$save_fb_data= $data['fb_data'];
if(!empty($facebook_id)){
	//$fbData= unserialize($save_fb_data);
	$_SESSION['FB_Detail']=true;
	$_SESSION['FB_Error']=false;
}
$temp = $form->value("labellang");
if(isset($temp) && $temp != '')
	$language=$form->value("labellang");

$temp=$form->value("rec_form_offcr_name");
if(isset($temp) && $temp != '')
	$rec_form_offcr_name = $form->value("rec_form_offcr_name");

$temp=$form->value("rec_form_offcr_num");
if(isset($temp) && $temp != '')
	$rec_form_offcr_num = $form->value("rec_form_offcr_num");

for ($i=1; $i<=10; $i++)
  {
  	$var="endorser_name{$i}";
	$temp=$form->value("endorser_name{$i}");
	if(isset($temp) && $temp != '')
	$$var = $form->value("endorser_name{$i}");
  }

 for ($i=1; $i<=10; $i++)
  {
  	$var="endorser_email{$i}";
	$temp=$form->value("endorser_email{$i}");
	if(isset($temp) && $temp != '')
	$$var = $form->value("endorser_email{$i}");
  }


if($form->value("cntct_type")=='' && isset($_SESSION['FB_Detail'])){
	$cntct_type = '1';
}

$disabled='';
if($borrowerActive==1) {
	$disabled = 'disabled';
}
?>
<?php
	include_once("./editables/register.php");
	$path1=	getEditablePath('register.php');
	include_once("./editables/".$path1);
								
?>
<div class="row">
<div align='left' class='static'><h1><?php echo $lang['register']['additional_verification'] ?></h1></div>
<tr><td>&nbsp;</td></tr>

<?php if(isset($_SESSION['bedited'])) {?>
	<div id='error' align='center'><font color='green'><?php echo $lang['register']['edited'];?></font></div><br/>
<?php unset($_SESSION['bedited']);
	} ?>
	
<tr><td>&nbsp;</td></tr>
<div align='left' class='static'><p><?php echo $lang['register']['av_instructions'] ?></p></div>

	<table class='detail' style="width:auto">
	<form enctype="multipart/form-data" id="additional_verification" name="additional_verification" method="post" action="updateprocess.php">

				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>

			<tr><td colspan="3"><h3 class="subhead"><?php echo $lang['register']['step_one'] ?></h3></td></tr>

				<tr id="fb_mandatory" style="<?php if(empty($facebook_id))echo "display:''"; else echo "display:none"; ?>" >
					<td><?php echo $lang['register']['facebook_mandatory']; ?>
					<?php echo $form->error("cntct_type"); ?>
					</td>
					
					<td>
					<?php $fbData=$session->facebook_connect();
							if(!empty($fbData['user_profile']['id']) && empty($facebook_id)){
								$database->saveFacebookInfo($fbData['user_profile']['id'], serialize($fbData), $web_acc, $session->userid, $email,$fb_fail_reason);
							}
							if(!empty($facebook_id)){?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button"><img src="images/facebook-connect.png"/></a>
						<?}elseif($fbData['loginUrl']==''){ 
							$showShareBox=1;
							if(isset($_REQUEST['fb_join'])|| $_SESSION['FB_Error']!=false){ 
							$showShareBox=0;
							}?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="javascript:login_popup('<?php echo $fbData['logoutUrl']?>');return false;"><img src="images/f_disconnect.jpg"/></a>
						<?php }else{?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="login_popup('<?php echo $fbData['loginUrl']?>');"><img src="images/facebook-connect.png"/></a>
						<?php }?>
					</td>
				</tr>
				<tr style="<?php if($fbmsg_hide!=1)echo "display:''"; else echo "display:none"; ?>" >
				<td><?php 
					
					if(isset($_SESSION['FB_Error'])){
						echo $_SESSION['FB_Error'];
					}?></td>
				</tr>
				<tr>
					<td colspan='3'>
					<?php if (isset($_SESSION['FB_Detail']) || !empty($facebook_id)) echo "<div align='center'><font color=green><strong>".$lang['register']['step_one_complete']."</font></div>" ?>
					</td>
				</tr>

				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				
				<tr><td colspan="3"><h3 class="subhead"><?php echo $lang['register']['step_two'] ?></h3></td></tr>
				
				 <tr style="<?php if (empty($data['frontNationalId'])) echo "display:''"; else echo "display:none"; ?>" >
							 
					<td>
						<?php echo $lang['register']['front_national_id'];?>
						<a id="front_national_iderr"></a>
					</td>
					<td>
					<div width="100%">
						<div style="float:left">
							<?php	
								$FrntNatidlabel = $lang['register']['upload_file'];
								$isFrntNatid = $form->value("isFrntNatid");
								$frntnatidex = explode('.',$isFrntNatid); 
							?>
							<?php if(end($frntnatidex)=='pdf') {
										$FrntNatidlabel = $lang['register']['upload_diff_file'];
										echo end(explode('/',$isFrntNatid));
									} 
								else if(!empty($isFrntNatid))
								{	$FrntNatidlabel = $lang['register']['upload_diff_file'];?>
									<img style="float:none" class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.end(explode('/',$isFrntNatid)) ?>" height="60" width="60" alt=""/>
							<?php }
							else if(!empty($data['frontNationalId'])) 
									{
										$FrntNatidlabel = $lang['register']['upload_diff_file'];
									?>
								<?php 
									$frntnatid = $data['frontNationalId'];
									$frntnatidex = explode('.',$frntnatid); 
								?>
								<?php if(end($frntnatidex)=='pdf') {
										echo"<strong>
									<a href=".SITE_URL."download.php?u=$id&doc=frontNationalId';";
							
										echo $lang['register']['dwnldView']."</a></strong>";
								} else {?>
								<strong>
									<a href="<?php echo SITE_URL.'download.php?u='.$id.'&doc=frontNationalId'; ?>">
										<img style="float:none" class="user-account-img" src="library/getdocImage.php?name=<?php echo $data['frontNationalId'] ?>&width=80&height=80" alt="Download and View"/>
								<?php } ?>
								</a>
							</strong>
							<?php } ?>
							</div>

							<?php if(empty($isFrntNatid)) {?>
							
							<div>
								<input type="file" name="front_national_id" id="front_national_id" value="C:\fakepath\ok" onchange="uploadfile(this)"/>
							</div>
							
								<div class="fileType_hide">
									<input type="file" name="front_national_id" id="front_national_id" value="C:\fakepath\ok" onchange="uploadfile(this)"/>
								</div>
							<?php } ?>
								<div class="customfiletype" onclick="getfrontNationalId()"><?php echo $FrntNatidlabel?></div>
								<div style="clear:both"></div>
							
							<div  id="front_national_id_file"></div>
							<br/>
						</div>
						<div id="front_national_id_err"><?php echo $form->error("front_national_id"); ?>
						</div>
						<span style="width:100%;"><?php echo $lang['register']['allowed']; ?></span><br/><span></span><br/>
						<input type="hidden" name="isFrntNatid" value="<?php echo $form->value("isFrntNatid"); ?>" />
					</td>
				</tr>
				<tr>
					<td colspan='3'>
					<?php if (!empty($data['frontNationalId'])) echo "<div align='center'><font color=green><strong>".$lang['register']['step_two_complete']."</font></div>" ?>
					</td>
				</tr>

				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				

				<tr><td colspan="3"><h3 class="subhead"><?php echo $lang['register']['step_three'] ?></h3></td></tr>
				
					<tr style="<?php if(empty($addresProof)) echo "display:''"; else echo "display:none"; ?>" >
							 
					<td>
						<?php echo $lang['register']['cl_name']; ?>:
					</td>
					<td><?php echo $rec_form_offcr_name; ?>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>

				<tr style="<?php if(empty($addresProof)) echo "display:''"; else echo "display:none"; ?>" >

					<td>
						<?php echo $lang['register']['cl_tel']; ?>:
					</td>
					<td><?php echo $rec_form_offcr_num; ?>
					</td>
				</tr>

				<tr style="<?php if(empty($addresProof)) echo "display:''"; else echo "display:none"; ?>" >

					<td>
						<?php echo $lang['register']['address_proof'];?>
						<a id="address_prooferr"></a>
					</td>
					<td>
					<div>
						<div style="float:left">
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
								<img style="float:none" class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.end(explode('/',$isaddrprf)) ?>" height="60" width="60" alt=""/>
							<?php }	else
							if(!empty($data['addressProof'])) {
								$addrprflabel = $lang['register']['upload_diff_file'];
							?>
							<?php $adressprf = $data['addressProof'];
								$adressprfex = explode('.',$adressprf);
								?>
								<?php if(end($adressprfex)=='pdf') {
									echo $data['addressProof']."<br/>";
									echo"<strong><a href='".SITE_URL."download.php?u=$id&doc=addressProof'; >";
									echo $lang['register']['dwnldView']."</a></strong>";
								} else {?>
								<strong><a href="<?php echo SITE_URL.'download.php?u='.$id.'&doc=addressProof'; ?>">
									<img style="float:none" class="user-account-img" src="library/getdocImage.php?name=<?php echo $data['addressProof'] ?>&width=80&height=80" alt="Download and View"/>
								</a></strong>
							<?php } ?>
						<?php } ?>
						</div>
							
								<div>
									<input type="file" name="address_proof" id="address_proof" value="" onchange="uploadfile(this)"/>
								</div>
								
								<div class="fileType_hide">
									<input type="file" name="address_proof" id="address_proof" value="" onchange="uploadfile(this)"/>
								</div>

								<div class="customfiletype" onclick="getAddressProof()"><?php echo $addrprflabel?></div>
								<div  id="address_proof_file"></div>
								<div style="clear:both"></div>
							
							<br/><span><?php echo $lang['register']['allowed']; ?></span>
							
					</div>
					<div id="address_proof_err"><?php echo $form->error("address_proof"); ?>
					<input type="hidden" name="isaddrprf" value="<?php echo $form->value("isaddrprf"); ?>" />
					</div>
					</td>
				</tr>
				<tr>
					<td colspan='3'>
					<?php if (!empty($addresProof)) echo "<div align='center'><font color=green><strong>".$lang['register']['step_three_complete']."</font></div>" ?>
					</td>
				</tr>

				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
				
			
				<tr><td colspan="3"><h3 class="subhead"><?php echo $lang['register']['step_four'] ?></h3></td></tr>
				
				<?php $params['minendorser']= $database->getAdminSetting('MinEndorser');
				  $endoresr_text= $session->formMessage($lang['register']['endorser'], $params);
				?>

				<div id="endorser" name="endorser" >
				<tr><td colspan="2"><?php echo $endoresr_text; ?><?php echo $form->error("endorser"); ?></td></tr>
				<tr><td>&nbsp;</td></tr>

				<tr><td><?php echo $lang['register']['endorser1_name']?></td><td><?php echo $lang['register']['endorser1_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name1" value="<?php echo $endorser_name1;?>" <?php if(!empty($endorser_name1)) echo $disabled; ?>" /><?php echo $form->error("endorser_name1"); ?></td><td><input type="text" name="endorser_email1" value="<?php echo $endorser_email1; ?>" <?php if(!empty($endorser_email1)) echo $disabled?> /><input type="hidden" name="endorser_id1" value="<?php echo $endorser_id1; ?>" /><?php echo $form->error("endorser_email1"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser2_name']?></td><td><?php echo $lang['register']['endorser2_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name2" value="<?php echo $endorser_name2; ?>" <?php if(!empty($endorser_name2)) echo $disabled?>/><?php echo $form->error("endorser_name2"); ?></td><td><input type="text" name="endorser_email2" value="<?php echo $endorser_email2; ?>" <?php if(!empty($endorser_email2)) echo $disabled?> /><input type="hidden" name="endorser_id2" value="<?php echo $endorser_id2; ?>" /><?php echo $form->error("endorser_email2"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser3_name']?></td><td><?php echo $lang['register']['endorser3_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name3"/ value="<?php echo $endorser_name3; ?>" <?php if(!empty($endorser_name3)) echo $disabled?> ><?php echo $form->error("endorser_name3"); ?></td><td><input type="text" name="endorser_email3" value="<?php echo $endorser_email3; ?>" <?php if(!empty($endorser_email3)) echo $disabled?> /><input type="hidden" name="endorser_id3" value="<?php echo $endorser_id3; ?>" /><?php echo $form->error("endorser_email3"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser4_name']?></td><td><?php echo $lang['register']['endorser4_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name4" value="<?php echo $endorser_name4; ?>" <?php if(!empty($endorser_name4)) echo $disabled?> /><?php echo $form->error("endorser_name4"); ?></td><td><input type="text" name="endorser_email4" value="<?php echo $endorser_email4; ?>" <?php if(!empty($endorser_email4)) echo $disabled?> /><input type="hidden" name="endorser_id4" value="<?php echo $endorser_id4; ?>"/><?php echo $form->error("endorser_email4"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser5_name']?></td><td><?php echo $lang['register']['endorser5_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name5" value="<?php echo $endorser_name5; ?>" <?php if(!empty($endorser_name5)) echo $disabled?> /><?php echo $form->error("endorser_name5"); ?></td><td><input type="text" name="endorser_email5" value="<?php echo $endorser_email5; ?>" <?php if(!empty($endorser_email5)) echo $disabled?> /><input type="hidden" name="endorser_id5" value="<?php echo $endorser_id5; ?>"/><?php echo $form->error("endorser_email5"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser6_name']?></td><td><?php echo $lang['register']['endorser6_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name6" value="<?php echo $endorser_name6; ?>" <?php if(!empty($endorser_name6)) echo $disabled?> /><?php echo $form->error("endorser_name6"); ?></td><td><input type="text" name="endorser_email6" value="<?php echo $endorser_email6; ?>" <?php if(!empty($endorser_email6)) echo $disabled?> /><input type="hidden" name="endorser_id6" value="<?php echo $endorser_id6; ?>" /><?php echo $form->error("endorser_email6"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser7_name']?></td><td><?php echo $lang['register']['endorser7_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name7" value="<?php echo $endorser_name7; ?>" <?php if(!empty($endorser_name7)) echo $disabled?> /><?php echo $form->error("endorser_name7"); ?></td><td><input type="text" name="endorser_email7" value="<?php echo $endorser_email7; ?>" <?php if(!empty($endorser_email7)) echo $disabled?> /><input type="hidden" name="endorser_id7" value="<?php echo $endorser_id7; ?>" /><?php echo $form->error("endorser_email7"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser8_name']?></td><td><?php echo $lang['register']['endorser8_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name8" value="<?php echo $endorser_name8; ?>" <?php if(!empty($endorser_name8)) echo $disabled?> /><?php echo $form->error("endorser_name8"); ?></td><td><input type="text" name="endorser_email8" value="<?php echo $endorser_email8; ?>" <?php if(!empty($endorser_email8)) echo $disabled?> /><input type="hidden" name="endorser_id8" value="<?php echo $endorser_id8; ?>" /><?php echo $form->error("endorser_email8"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser9_name']?></td><td><?php echo $lang['register']['endorser9_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name9" value="<?php echo $endorser_name9; ?>" <?php if(!empty($endorser_name9)) echo $disabled?> /><?php echo $form->error("endorser_name9"); ?></td><td><input type="text" name="endorser_email9" value="<?php echo $endorser_email9; ?>" <?php if(!empty($endorser_email9)) echo $disabled?> /><input type="hidden" name="endorser_id9" value="<?php echo $endorser_id9; ?>"/><?php echo $form->error("endorser_email9"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser10_name']?></td><td><?php echo $lang['register']['endorser10_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name10" value="<?php echo $endorser_name10; ?>" <?php if(!empty($endorser_name10)) echo $disabled?> /><?php echo $form->error("endorser_name10"); ?></td><td><input type="text" name="endorser_email10" value="<?php echo $endorser_email10; ?>" <?php if(!empty($endorser_email10)) echo $disabled?> /><input type="hidden" name="endorser_id10" value="<?php echo $endorser_id10; ?>" /><?php echo $form->error("endorser_email10"); ?></td></tr>

				</div>

				<tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>

				<tr>
					<td style="text-align:center;">
						<input type="hidden" name="uploadedDocs[]" value="<?php echo $docuploaded['fronNationalid']?>" />
						<input type="hidden" name="uploadedDocs[]" value="<?php echo $docuploaded['addresProof']	?>" />
						<input type="hidden" name="additional_verification" />
						<input type="hidden" name="before_fb_data" id="before_fb_data" />
						<input type="hidden" name="fb_data" id="fb_data" value='<?php if(!empty($save_fb_data)) echo urlencode(addslashes(base64_decode($save_fb_data))); else echo urlencode(addslashes(serialize($fbData))); ?>'/>
						<?php Logger_Array("FB LOG - on b_editprofile",'fbData1',serialize($fbData).$username); ?>
						<input type="hidden" name="uploadfileanchor" id="uploadfileanchor" />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('additional_verification'); ?>"/>
						<input type="hidden" name="id" value="<?php echo $id ;?>"/>
						<?php if($borrowerActive!=1) {?>	
						
						<input  type="submit" name='submitform' id='borrowersubmitform' onclick="needToConfirm = false;" value="<?php echo $lang['register']['Registerlater'];?>"  class='btn'/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit"   name='submitform' onclick="needToConfirm = false;" value="<?php echo $lang['register']['RegisterComplete'];?>"  class='btn'/>
					<?php } else {?>
						<input type="submit"   name='submitform' id='borrowersubmitform' onclick="needToConfirm = false;" value="<?php 
						echo $lang['register']['savechanges'];?>"  class='btn'/>
					<?php } ?>
					</td>
					<td></td>
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
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
	var ids = new Array('bpass1', 'bpass2', 'bfname','blname','bphoto','bpostadd','bcity','bcountry','bnationid','bloanhist','community_name_no','bemail','bmobile','bincome','babout','bbizdesc','front_national_id','back_national_id','address_proof','legal_declaration', 'bfamilycont', 'bneighcont');
	var values = new Array('','','','','','','','','','','','','','','','','','','','', '', '');
	var needToConfirm = true;
</script>
<script language="JavaScript">
populateArrays();
function validateborwredit() {
	needToConfirm = false;
	var adresspr = document.getElementById('address_proof').value;
	var Frntid = document.getElementById('front_national_id').value;
	var legaldec = document.getElementById('legal_declaration').value;
	if(!document.getElementById('repaidpast').checked || !document.getElementById('debtfree').checked
		|| !document.getElementById('share_update').checked) {
		document.getElementById('share_update_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';
			//alert('You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.')
		return false;
		//document.getElementById('repaidpast').checked=true;
	}else {
		document.getElementById('share_update_err').innerHTML='';
	}
<?php if(!isset($fronNationalid) || empty($fronNationalid)) { ?>
		if(!Frntid || 0 === Frntid.length) {
			document.getElementById('front_national_id_err').innerHTML='<font color="red">Please upload a copy of the front of your national identification card.</font>';
			//alert('Please upload a copy of the front of your national identification card.')
			return false;
		}else {
			document.getElementById('front_national_id_err').innerHTML='';
		}
<?php } ?>
<?php if(!isset($addresProof) || empty($addresProof)) { ?>	
		if(!adresspr || 0 === adresspr.length) {
			document.getElementById('address_proof_err').innerHTML='<font color="red">Please upload a copy of the Recommendation Form.</font>';
			//alert('Please upload a copy of the Recommendation Form.')
			return false;
		}else {
			document.getElementById('address_proof_err').innerHTML='';;
		}
<?php }?>

<?php if(!isset($legalDeclaration) || empty($legalDeclaration)) { ?>	
		if(!legaldec || 0 === legaldec.length) {
			document.getElementById('legal_declaration_err').innerHTML='<font color="red">Please upload a copy of the Recommendation Form.</font>';
			//alert('Please upload a copy of the Recommendation Form.')
			return false;
		}else {
			document.getElementById('legal_declaration_err').innerHTML='';;
		}
<?php }?>
	
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
		document.getElementById('fb_instruction').style.display='none';
		document.getElementById('endorser').style.display='none';
		document.getElementById('facebook_result').style.display='none';
	  }else{
		$('.facebook-auth').show();
		document.getElementById('tele_contacts').style.display='none';
		document.getElementById('fb_instruction').style.display='';
		document.getElementById('endorser').style.display='';
		document.getElementById('facebook_result').style.display='';
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
<script>

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
<div id='ResidentialaddrExample' style="display:none">
<div class="instruction_space" style="margin-top:10px;height:160px">
	<div class="instruction_text"><?php echo $lang['register']['addresexample']?></div>
</div>
</div>