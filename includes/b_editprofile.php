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
$padd=$data['PAddress'];
$city=$data['City'];
$country=$data['Country'];
$telmobile=$data['TelMobile'];
$reffer_by=$data['reffered_by'];
$email=$data['Email'];
$bincome=$data['AnnualIncome'];
$bfamilycontact1= $data['family_member1'];
$bfamilycontact2= $data['family_member2'];
$bfamilycontact3= $data['family_member3'];
$bneighcontact1= $data['neighbor1'];
$bneighcontact2= $data['neighbor2'];
$bneighcontact3= $data['neighbor3'];
$about=$data['About'];
$desc=$data['BizDesc'];
$username=$data['username'];
$currencysel=$data['currency'];
$bnationid=$data['nationId'];
//$bloanhist=$data['loanHist'];
$lastRepaid = $data['islastrepaid'];
$debtFree = $data['isdebtfree'];
$community_name_no=$data['communityNameNo'];
$fronNationalid = $data['frontNationalId'];
$backNationalId = $data['backNationalId'];
$addresProof = $data['addressProof'];
$legalDeclaration = $data['legalDeclaration'];
$legalDeclaration2 = $data['legalDeclaration'];
$docuploaded['fronNationalid'] = $fronNationalid;
$docuploaded['addresProof'] = $addresProof;
$docuploaded['legalDeclaration'] = $legalDeclaration;
$docuploaded['legal_declaration2'] = $data['legal_declaration2'];
$docuploaded['backNationalId'] = $data['backNationalId'];
$share_update = $data['share_update'];
$borrower_behalf_id = $data['borrower_behalf_id'];
$borrowerActive = $data['Active'];
$iscomplete_later = $data['iscomplete_later'];
$behalf_name='';
$behalf_contact='';
$behalf_email ='';
$behalf_town='';
if($borrower_behalf_id > 0) {
	$brwrbehaldetail = $database->getBorrowerbehalfdetail($borrower_behalf_id);
	$behalf_name = $brwrbehaldetail['name'];
	$behalf_contact = $brwrbehaldetail['contact_no'];
	$behalf_email = $brwrbehaldetail['email'];
	$behalf_town = $brwrbehaldetail['town'];
}
$home_no= $data['home_location'];
/*$lending_inst_name= $data['lending_inst_name'];
$lending_inst_add= $data['lending_inst_add']; 
$lending_inst_phone= $data['lending_inst_phone'];
$lending_inst_officer= $data['lending_inst_officer'];*/
$rec_form_offcr_name = $data['rec_form_offcr_name'];
$rec_form_offcr_num = $data['rec_form_offcr_num'];
$refer_member = $data['refer_member_name'];
$volunteer_mentor= $data['mentor_id'];
if(!empty($volunteer_mentor)){
	$volunteer_mentor_city=$database->getUserCityCountry($volunteer_mentor);
	$save_vm_city=$volunteer_mentor_city['City'];
}
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
$cntct_type='';
if(!empty($facebook_id)){
	$cntct_type='1';
}elseif(!empty($bfamilycontact1)){
	$cntct_type=0;
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
$temp = $form->value("busername");
if(isset($temp) && $temp != '')
	$username=$form->value("busername");
$temp = $form->value("bpass1");
$pass1="";
if(isset($temp) && $temp != '')
	$pass1=$form->value("bpass1");
$temp = $form->value("bfname");
if(isset($temp) && $temp != '')
	$fname=$form->value("bfname");
$temp = $form->value("blname");
if(isset($temp) && $temp != '')
	$lname=$form->value("blname");

$temp = $form->value("bpostadd");
if(isset($temp) && $temp != '')
	$padd=$form->value("bpostadd");

$temp = $form->value("bcity");
if(isset($temp) && $temp != '')
	$city=$form->value("bcity");

$temp = $form->value("bcountry");
if(isset($temp) && $temp != '')
	$country=$form->value("bcountry");

$temp = $form->value("bemail");
if(isset($temp) && $temp != '')
	$email=$form->value("bemail");

$temp = $form->value("bmobile");
if(isset($temp) && $temp != '')
	$telmobile=$form->value("bmobile");

$temp = $form->value("reffered_by");
if(isset($temp) && $temp != '')
	$reffer_by=$form->value("reffered_by");

$temp = $form->value("bincome");
if(isset($temp) && $temp != '')
	$bincome=$form->value("bincome");

$temp = $form->value("bfamilycont1");
if(isset($temp) && $temp != '')
	$bfamilycontact1=$form->value("bfamilycont1");

$temp = $form->value("bfamilycont2");
if(isset($temp) && $temp != '')
	$bfamilycontact2=$form->value("bfamilycont2");

$temp = $form->value("bfamilycont3");
if(isset($temp) && $temp != '')
	$bfamilycontact3=$form->value("bfamilycont3");

$temp = $form->value("bneighcont1");
if(isset($temp) && $temp != '')
	$bneighcontact1=$form->value("bneighcont1");

$temp = $form->value("bneighcont2");
if(isset($temp) && $temp != '')
	$bneighcontact2=$form->value("bneighcont2");

$temp = $form->value("bneighcont3");
if(isset($temp) && $temp != '')
	$bneighcontact3=$form->value("bneighcont3");

$temp = $form->value("babout");
if(isset($temp) && $temp != '')
	$about=$form->value("babout");

$temp = $form->value("bbizdesc");
if(isset($temp) && $temp != '')
	$desc=$form->value("bbizdesc");

$temp=$form->value("currency");
if(isset($temp) && $temp != '')
	$currencysel=$form->value("currency");

$temp=$form->value("bnationid");
if(isset($temp) && $temp != '')
	$bnationid=$form->value("bnationid");

/*$temp=$form->value("bloanhist");
if(isset($temp) && $temp != '')
	$bloanhist=$form->value("bloanhist");*/

$temp=$form->value("community_name_no");
if(isset($temp) && $temp != '')
	$community_name_no=$form->value("community_name_no");

$temp=$form->value("community_name_no");
if(isset($temp) && $temp != '')
	$community_name_no=$form->value("community_name_no");

$temp=$form->value("repaidpast");
if(isset($temp) && $temp != '')
	$lastRepaid=$form->value("repaidpast");

$temp=$form->value("debtfree");
if(isset($temp) && $temp != '')
	$debtFree = $form->value("debtfree");
	
$temp=$form->value("share_update");
if(isset($temp) && $temp != '')
	$share_update = $form->value("share_update");

$temp=$form->value("behalf_name");
if(isset($temp) && $temp != '')
	$behalf_name = $form->value("behalf_name");

$temp=$form->value("behalf_number");
if(isset($temp) && $temp != '')
	$behalf_contact = $form->value("behalf_number");

$temp=$form->value("behalf_email");
if(isset($temp) && $temp != '')
	$behalf_email = $form->value("behalf_email");

$temp=$form->value("behalf_town");
if(isset($temp) && $temp != '')
	$behalf_town = $form->value("behalf_town");

$temp=$form->value("home_no");
if(isset($temp) && $temp != '')
	$home_no = $form->value("home_no");

/*$temp=$form->value("lending_inst_name");
if(isset($temp) && $temp != '')
	$lending_inst_name = $form->value("lending_inst_name");

$temp=$form->value("lending_inst_add");
if(isset($temp) && $temp != '')
	$lending_inst_add = $form->value("lending_inst_add");

$temp=$form->value("lending_inst_phone");
if(isset($temp) && $temp != '')
	$lending_inst_phone = $form->value("lending_inst_phone");

$temp=$form->value("lending_inst_officer");
if(isset($temp) && $temp != '')
	$lending_inst_officer = $form->value("lending_inst_officer"); */

$temp=$form->value("rec_form_offcr_name");
if(isset($temp) && $temp != '')
	$rec_form_offcr_name = $form->value("rec_form_offcr_name");

$temp=$form->value("rec_form_offcr_num");
if(isset($temp) && $temp != '')
	$rec_form_offcr_num = $form->value("rec_form_offcr_num");

$temp=$form->value("refer_member");
if(isset($temp) && $temp != '')
	$refer_member = $form->value("refer_member");

$temp=$form->value("volunteer_mentor");
if(isset($temp) && $temp != '')
	$volunteer_mentor = $form->value("volunteer_mentor");

$temp=$form->value("cntct_type");
if(isset($temp) && $temp != '')
	$cntct_type = $form->value("cntct_type");

$temp=$form->value("endorser_name1");
if(isset($temp) && $temp != '')
	$endorser_name1 = $form->value("endorser_name1");

$temp=$form->value("endorser_name2");
if(isset($temp) && $temp != '')
	$endorser_name2 = $form->value("endorser_name2");

$temp=$form->value("endorser_name3");
if(isset($temp) && $temp != '') 
	$endorser_name3 = $form->value("endorser_name3");

$temp=$form->value("endorser_name4");
if(isset($temp) && $temp != '')
	$endorser_name4 = $form->value("endorser_name4");

$temp=$form->value("endorser_name5");
if(isset($temp) && $temp != '')
	$endorser_name5 = $form->value("endorser_name5");

$temp=$form->value("endorser_name6");
if(isset($temp) && $temp != '')
	$endorser_name6 = $form->value("endorser_name6");

$temp=$form->value("endorser_name7");
if(isset($temp) && $temp != '')
	$endorser_name7 = $form->value("endorser_name7");

$temp=$form->value("endorser_name8");
if(isset($temp) && $temp != '')
	$endorser_name8 = $form->value("endorser_name8");

$temp=$form->value("endorser_name9");
if(isset($temp) && $temp != '')
	$endorser_name9 = $form->value("endorser_name9");

$temp=$form->value("endorser_name10");
if(isset($temp) && $temp != '')
	$endorser_name10 = $form->value("endorser_name10");

$temp=$form->value("endorser_email1");
if(isset($temp) && $temp != '')
	$endorser_email1 = $form->value("endorser_email1");

$temp=$form->value("endorser_email2");
if(isset($temp) && $temp != '')
	$endorser_email2 = $form->value("endorser_email2");

$temp=$form->value("endorser_email3");
if(isset($temp) && $temp != '')
	$endorser_email3 = $form->value("endorser_email3");

$temp=$form->value("endorser_email4");
if(isset($temp) && $temp != '')
	$endorser_email4 = $form->value("endorser_email4");

$temp=$form->value("endorser_email5");
if(isset($temp) && $temp != '')
	$endorser_email5 = $form->value("endorser_email5");

$temp=$form->value("endorser_email6");
if(isset($temp) && $temp != '')
	$endorser_email6 = $form->value("endorser_email6");

$temp=$form->value("endorser_email7");
if(isset($temp) && $temp != '')
	$endorser_email7 = $form->value("endorser_email7");

$temp=$form->value("endorser_email8");
if(isset($temp) && $temp != '')
	$endorser_email8 = $form->value("endorser_email8");

$temp=$form->value("endorser_email9");
if(isset($temp) && $temp != '')
	$endorser_email9 = $form->value("endorser_email9");

$temp=$form->value("endorser_email10");
if(isset($temp) && $temp != '')
	$endorser_email10 = $form->value("endorser_email10");

$temp=$form->value("vm_city");
if(isset($temp) && $temp != '')
	$save_vm_city = $form->value("vm_city");

if($form->value("cntct_type")=='' && isset($_SESSION['FB_Detail'])){
	$cntct_type = '1';
}

if($country!='0'){
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
	$count=0;
	for($x=0;$x<count($vmcities);$x++){
		$cityadded=0;
		if($save_vm_city!='' && strcasecmp($save_vm_city,$vmcities[$x])==0){
			$vmByCity=$database->getVolunteersByCity($save_vm_city);
			foreach($vmByCity as $key=>$row){
				if($key== $volunteer_mentor){
					$count++;
				}				
			}
			if($count==0)
				$vmByCity[$volunteer_mentor]='';
		}
	}
	for($x=0;$x<count($vmcities);$x++){
		$cityadded=0;
		if($save_vm_city!='' && strcasecmp($save_vm_city,$vmcities[$x])==0){
			$cityadded++;
		}
	}		
	if($cityadded==0){
		$vmcities[]=$save_vm_city;
		$vmByCity=$database->getVolunteersByCity($save_vm_city);
		foreach($vmByCity as $key=>$row){
			if($key== $volunteer_mentor){
				$count++;
			}				
		}
		if($count==0)
			$vmByCity[$volunteer_mentor]='';
	}
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
<div align='left' class='static'><h1><?php echo $lang['register']['update'] ?></h1></div>

<?php if(isset($_SESSION['bedited'])) {?>
	<div id='error' align='center'><font color='green'><?php echo $lang['register']['edited'];?></font></div><br/>
<?php unset($_SESSION['bedited']);
	} ?>
	<form enctype="multipart/form-data" id="sub-borrower" name="sub-borrower" method="post" action="updateprocess.php">
		<input type="hidden" id="labellang" name="labellang" value="<?php echo $language; ?>" />
		<table class='detail'>
			<tbody>
				<!-- <tr>
					<td><?php echo $lang['register']['language'];?></td>
					<td>
						<select id="labellang" name="labellang">
					<?php	$langs= $database->getActiveLanguages();
							echo "<option value='en'>English</option>";
							foreach($langs as $row)
							{  ?>
								<option value='<?php echo $row['langcode'] ?>' <?php if($language==$row['langcode'])echo "Selected='true'";?>><?php echo $row['lang']?></option>
					<?php	}	?>
						</select>
					</td>
				</tr> -->
				<tr>
					<td colspan="2"><?php echo $lang['register']['Country'];?></td>
					<td>
						<select id="bcountry" name="bcountry" class="selectcmmn" disabled="disabled" onchange="needToConfirm = false;show_tele_cntct(this.value);submitform1();">
					<?php	$result1 = $database->countryList(true);
							$i=0;
							echo"<option value='0'>Select Country</option>";
							foreach($result1 as $state)
							{
								if($country==$state['code'])
									echo "<option value='".$state['code'] . "' Selected='true' >".$state['name']."</option>";
								else
									echo "<option value='".$state['code'] . "'>".$state['name']."</option>";
							}
					?>
						</select>
						<br/><?php echo $form->error("bcountry"); ?>
						<a id="bcountryerr"></a>
					</td>
				</tr>
				<tr height="10px"><td>&nbsp;</td></tr>
<!-- modified by Julia to require FB for all countries except BF 1-11-2013 -->
				<tr id="fb_mandatory" style="<?php if($country!='BF')echo "display:''"; else echo "display:none"; ?>" >
					<td colspan="2" ><?php echo $lang['register']['facebook_mandatory']; ?>
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
				<tr style="<?php if(($country!='BF') && $fbmsg_hide!=1)echo "display:''"; else echo "display:none"; ?>" >
				<td colspan='2'><?php 
					if(isset($_SESSION['FB_Detail'])){
						echo "<div align='center'><font color=green><strong>Your Facebook account is now linked to Zidisha.</strong></font></div><br/>";
					}
					if(isset($_SESSION['FB_Error'])){
						echo $_SESSION['FB_Error'];
					}?></td>
				</tr>
<!--
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td colspan='2'><?php echo $lang['register']['ordertojoin'];?></td>
					<td></td>
					<td></td>
				</tr>
				<tr height="10px"><td>&nbsp;</td></tr>
				<tr><?php 
						$padding_top = '';
						$repaidpast_err = $form->error("repaidpast");
						if(!empty($repaidpast_err)) {
							$padding_top = 'padding-top:25px;';
						}
					?>
					<td width="30px" style="text-align:right;vertical-align:top;<?php echo $padding_top?>">1.</td>
					<td><?php echo $lang['register']['ordertojoin1'];?>
					<a id="repaidpasterr"></a></td>
					<td><input type="radio" id="repaidpast" name="repaidpast"  onclick="checkeligibility()" class="inputcmmn-1" value="1" <?php if($lastRepaid) echo"checked";?> <?php echo $disabled?>/><?php echo $lang['register']['yes'];?>
					<input type="radio" id="repaidpast" name="repaidpast"  onclick="checkeligibility()" class="inputcmmn-1" value="0" <?php if(!$lastRepaid) echo"checked";?> <?php echo $disabled?>/><?php echo $lang['register']['no'];?>
					<br/><div id="repaidpast"><?php echo $form->error("repaidpast"); ?></div></td>
				</tr>
				<tr height="10px"><td>&nbsp;</td></tr>
				<tr><?php 
						$padding_top = '';
						$debtfree_err = $form->error("debtfree");
						if(!empty($debtfree_err)) {
							$padding_top = 'padding-top:25px;';
						}
					?>
					<td width="30px" style="text-align:right;vertical-align:top;<?php echo $padding_top?>">2.</td>
					<td><?php echo $lang['register']['ordertojoin2'];?>
					<a id="debtfreeerr"></a></td>
					<td><input type="radio" id="debtfree" name="debtfree"  onclick="checkeligibility()" class="inputcmmn-1" value="1" <?php if($debtFree) echo"checked";?> <?php echo $disabled?>/><?php echo $lang['register']['yes'];?>
					<input type="radio" id="debtfree" name="debtfree" onclick="checkeligibility()" class="inputcmmn-1" value="0" <?php if(!$debtFree) echo"checked";?> <?php echo $disabled?>/><?php echo $lang['register']['no'];?>
					<br/><div id="debtfree"><?php echo $form->error("debtfree"); ?></div></td>
				</tr>
				<tr height="10px"><td>&nbsp;</td></tr>
				<tr>
					<?php 
						$padding_top = '';
						$share_update_err = $form->error("share_update");
						if(!empty($share_update_err)) {
							$padding_top = 'padding-top:25px;';
						}
					?>
					<td width="30px" style="text-align:right;vertical-align:top;<?php echo $padding_top?>">3.</td>
					<td width="460px" ><?php echo $lang['register']['ordertojoin3'];?>
					<a id="share_updateerr"></a></td>
					<td><input type="radio" id="share_update" name="share_update"  onclick="checkeligibility(this.id)" class="inputcmmn-1" value="1" <?php if($share_update) echo "checked";?> <?php echo $disabled?>/><?php echo $lang['register']['yes'];?>
					<input type="radio" id="share_update" name="share_update" onclick="checkeligibility(this.id)" class="inputcmmn-1" value="0" <?php if($share_update=='0') echo "checked";?> <?php echo $disabled?>/><?php echo $lang['register']['no'];?>
					<br/><div id="share_update_err"><?php echo $form->error("share_update"); ?></div></td>
				</tr>
			</tbody>
		</table>
-->
		<table class='detail' style="<?php if($country!='BF') echo 'display:none'; else echo 'display:block';?>" id= "brwr_behalf">
			<tbody>
				<tr ><td colspan='2'><?php echo $lang['register']['borrower_behalf'];?></td></tr>
				<tr height="10px"><td>&nbsp;</td></tr>
				<tr>
					<td colspan='2'><input type="radio" id="borrower_behalf" name="borrower_behalf" onclick ="document.getElementById('borwr_behalf_section').style.display = 'none';" class="inputcmmn-1" value="0" <?php $brwrBehalf = $form->value('borrower_behalf');if($brwrBehalf=='0' || !isset($brwrBehalf) || $borrower_behalf_id == '0') echo"checked";?>/><?php echo $lang['register']['borrower_behalf1'];?>
				</tr>
				<tr>
					<td colspan='2'><input type="radio" id="borrower_behalf" name="borrower_behalf" onclick ="document.getElementById('borwr_behalf_section').style.display = '';" class="inputcmmn-1" value="1" <?php if($brwrBehalf==1 || $borrower_behalf_id > 0) echo"checked";?>/><?php echo $lang['register']['borrower_behalf2'];?></td>
					<br/><div id=""><?php echo $form->error("borrower_behalf"); ?></div></td>
				</tr>
				<tr height="10px"><td>&nbsp;</td></tr>
				<tr>
					<?php $display = 'display:none';
						if($brwrBehalf || $borrower_behalf_id > 0) {
							$display='block';
						}?>
						<td colspan="2" id='borwr_behalf_section' style="text-decoration:none;<?php echo $display?>">
						<table class="detail">
							<tr>
								<td><a href="https://sites.google.com/site/zidishavolunteermentor/working-with-new-applicants" target="_blank"><?php echo $lang['register']['behalf_guideline'];?></td>
							</tr>
							<tr height="15px;"></tr>
							<tr>
								<td ><?php echo $lang['register']['behalf_name'];?>
								<a id="behalf_nameerr"></a>
								</td>
								<td><input type="text" id="behalf_name" name="behalf_name" value="<?php echo $behalf_name;?>"></textarea>
								<br/><div id="behalf_name"><?php echo $form->error("behalf_name"); ?></div></td>
							</tr>
							<tr height="10px"><td>&nbsp;</td></tr>
							<tr>
								<td width="460px">
									<?php echo $lang['register']['behalf_number'];?>
									<a id="behalf_numbererr"></a>
								</td>
								<td><input type="text" id="behalf_number" name="behalf_number" maxlength="100" class="inputcmmn-1" value="<?php echo $behalf_contact; ?>" /><br/><div id=""><?php echo $form->error("behalf_number"); ?></div></td>
							</tr>
							<tr height="10px"><td>&nbsp;</td></tr>
							<tr>
								<td width="460px">
									<?php echo $lang['register']['behalf_email'];?>
									<a id="behalf_emailerr"></a>
								</td>
								<td><input type="text" id="behalf_email" name="behalf_email" maxlength="100" class="inputcmmn-1" value="<?php echo $behalf_email; ?>" /><br/><div id="behalf_email"><?php echo $form->error("behalf_email"); ?></div></td>
							</tr>
							<tr height="10px"><td>&nbsp;</td></tr>
							<tr>
								<td width="460px">
									<?php echo $lang['register']['behalf_town'];?>
									<a id="behalf_townerr"></a>
								</td>
								<td><input type="text" id="behalf_town" name="behalf_town" maxlength="100" class="inputcmmn-1" value="<?php echo $behalf_town; ?>" /><br/><div id="behalf_town"><?php echo $form->error("behalf_town"); ?></div></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table class='detail'>


				<tr height="10px"><td>&nbsp;</td></tr>
				<tr>
					<td ><?php echo $lang['register']['endorser_uname'];?><a id="busernameerr"></a>
					</td>
					<td><input type="text"  name="busername" maxlength="100" class="inputcmmn-1" readonly="readonly" value="<?php echo $username ; ?>" /><br/><div id="bunerror"><?php echo $form->error("busername"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<?php echo $lang['register']['ppassword'];?>
						<a id="bpass1err"></a>
					</td>
					<td><input type="password" id="bpass1" name="bpass1" class="inputcmmn-1" value="<?php echo $pass1; ?>" /><br/><div id="passerror"><?php echo $form->error("bpass1"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				
				<tr>
					<td>
						<?php echo $lang['register']['b_fname'];?>
						<a id="bfnameerr"></a>
					</td>
					<td><input type="text" name="bfname" id='bfname' maxlength="25" class="inputcmmn-1" value="<?php echo $fname; ?>" <?php echo $disabled?>/><br/><?php echo $form->error("bfname"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<?php echo $lang['register']['b_lname'];?>
						<a id="blnameerr"></a>
					</td>
					<td><input type="text" name="blname" id="blname" maxlength="25" class="inputcmmn-1" value="<?php echo $lname; ?>" <?php echo $disabled?>/><br/><?php echo $form->error("blname"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<?php echo $lang['register']['photo_note']?>
						<a id="bphotoerr"></a>
					</td>
					<td>
					<div>
						<div style="float:left">
							<?php $photolabel = $lang['register']['upload_photo'];
							$isPhoto_select=$form->value("isPhoto_select");
							$prevphoto='';
								if(!empty($isPhoto_select))
								{ 
									$photolabel = $lang['register']['upload_photo'];
									$prevphoto = $isPhoto_select;
								?>
									<img style="float:none" class="user-account-img" src="<?php echo SITE_URL.'images/tmp/'.$isPhoto_select ?>" height="60" width="60" alt=""/>
							<?php } else if (file_exists(USER_IMAGE_DIR.$id.".jpg")){ 
									
									$photolabel = $lang['register']['upload_diffphoto'];
									$prevphoto = $id.".jpg";
									?>
										<img style="float:none" class ="user-account-img" src="library/getimagenew.php?id=<?php echo $id;?>&width=100&height=130" alt="" />
							
							<?php } else if( ! empty($facebook_id)){
							
										echo "<img style='max-width:200px;' src='https://graph.facebook.com/".$facebook_id."/picture?width=9999&height=9999' />";
										$photolabel = $lang['register']['upload_diffphoto'];
									}
							?> 
						</div>
						<!--[if lt IE 9]>
							<div>
								<input type="file" name="bphoto" id="bphoto" maxlength="15"  value="<?php echo $form->value("bphoto"); ?>" onchange="uploadfile(this)" />
							</div>
							<![endif]-->
						<!--[if !IE]> -->
							<div class="fileType_hide">
								<input type="file" name="bphoto" id="bphoto" maxlength="15"  value="<?php echo $form->value("bphoto"); ?>" onchange="uploadfile(this)" />
							</div>
							<div style="float:left" class="customfiletype" onclick="getbphoto()"><?php echo $photolabel?></div>
							<div style="clear:both"></div>
						<!-- <![endif]-->

						<div id="bphoto_file"></div><br/>
					</div>
						<br/><span><?php echo $lang['register']['allowed'];?></span><br/><div id="bphoto_err"><?php echo $form->error("bphoto"); ?></div>
						<?php $uploadedphoto = $form->value("bphoto");
								if(empty($uploadedphoto)) {
									$photovalue = $prevphoto;
								}else {
									$photovalue = $uploadedphoto;
								}
						?>
						<input type="hidden" name="isPhoto_select" value="<?php echo $photovalue; ?>" />
				</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['paddress'];?>
						<a id="bpostadderr"></a>
					</td>
					<td><textarea name="bpostadd" id="bpostadd" class="textareacmmn" <?php echo $disabled?>><?php echo $padd ; ?></textarea><br/><?php echo $form->error("bpostadd"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td>
						<?php $params['padd_ex']= $_SERVER['REQUEST_URI'].'#ResidentialaddrExample'; 
						 $home_no_label= $session->formMessage($lang['register']['home_no'], $params); ?>
						
						<?php echo $home_no_label;?>
						<a id="home_noerr"></a>
					</td>
					<td><textarea name="home_no" id='home_no' class="textareacmmn" <?php echo $disabled?>><?php echo $home_no; ?></textarea><br/><?php echo $form->error("home_no"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<?php echo $lang['register']['City'];?>
						<a id="bcityerr"></a>
					</td>
					<td><input type="text" name="bcity" id="bcity" maxlength="25" class="inputcmmn-1" value="<?php echo $city; ?>" <?php echo $disabled?> /><br/><?php echo $form->error("bcity"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <?php if($iscomplete_later) {?>
				 <tr>
					<td><?php echo $lang['register']['nationid'];?></td>
					<td><input type="text" name="bnationid" id="bnationid" maxlength="50" class="inputcmmn-1" value="<?php echo $bnationid; ?>" /><br/><?php echo $form->error("bnationid"); ?></td>
				</tr> 
				<?php } ?>
				<!-- <tr><td>&nbsp;</td></tr>
				<tr>
					<td style="vertical-align:text-top">
						<?php echo $lang['register']['loanhist'];?>
						<a id="bloanhisterr"></a>
					</td>
					<td><textarea name="bloanhist" id="bloanhist" class="textareacmmn" style="height:130px;" <?php echo $disabled?>><?php echo $bloanhist; ?></textarea><br/><?php echo $form->error("bloanhist"); ?></td>
				</tr> 
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['lending_institution'];?>
					<a id="lending_institutionerr"></a>
					</td>
					<td><input type="text" name="lending_institution" id='lending_institution' maxlength="25" class="inputcmmn-1" value="<?php echo $lending_inst_name; ?>" <?php echo $disabled?>/><br/><?php echo $form->error("lending_institution"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td>
						<?php echo $lang['register']['lending_institution_add'];?>
						<a id="lending_institution_adderr"></a>
					</td>
					<td><textarea name="lending_institution_add" id='lending_institution_add' class="textareacmmn" <?php echo $disabled?>><?php echo $lending_inst_add; ?></textarea><br/><?php echo $form->error("lending_institution_add"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td>
						<?php echo $lang['register']['lending_institution_phone'];?>
						<a id="lending_institution_phoneerr"></a>
					</td>
					<td><input type="text" id="lending_institution_phone" name="lending_institution_phone" maxlength="15" class="inputcmmn-1" value="<?php echo $lending_inst_phone; ?>" <?php echo $disabled?>/><br/><div id="lending_institution_phone"><?php echo $form->error("lending_institution_phone"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td>
						<?php echo $lang['register']['lending_institution_officer'];?>
						<a id="lending_institution_officererr"></a>
					</td>
					<td><input type="text" name="lending_institution_officer" id='lending_institution_officer' maxlength="25" class="inputcmmn-1" value="<?php echo $lending_inst_officer; ?>" <?php echo $disabled?>/><br/><?php echo $form->error("lending_institution_officer"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <!-- <tr>
					<td><?php echo $lang['register']['community_name_no'];?><font color="red">*</font></td>
					<td><textarea name="community_name_no" id="community_name_no" class="textareacmmn" style="height:130px;"><?php echo $community_name_no; ?></textarea><br/><?php echo $form->error("community_name_no"); ?></td>
				</tr> -->
				 <tr><td>&nbsp;</td></tr>

				<tr>
					<td>
																	<?php 
						//display different phone number instructions for countries where we use mobile payments
						if($form->value("bcountry")=='KE') {

							echo $lang['register']['tel_safaricom'];

						}else if($form->value("bcountry")=='GH' || $form->value("bcountry")=='ZM') {
							
							echo $lang['register']['tel_mtn'];
						
						}else {
							
							echo $lang['register']['tel_mob_no'];
						
						}
						?><a id="bmobileerr"></a>
					</td>
					<td><input type="text" id="bmobile" name="bmobile" maxlength="15" class="inputcmmn-1" value="<?php echo $telmobile; ?>" <?php echo $disabled?>/><br/><div id="mobileerror"><?php echo $form->error("bmobile"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<?php echo $lang['register']['email'];?>
						<a id="bemailerr"></a>
					</td>

					<td><input type="text" id="bemail" name="bemail" maxlength="100" class="inputcmmn-1"  value="<?php echo $email; ?>"/><br/><div id="emailerror"><?php echo $form->error("bemail"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				

				<tr>
					<td>
						<?php echo $lang['register']['reffered_member'];?>
						<a id="refer_membererr"></a>
					</td>
					<td><select id="refer_member" name="refer_member" <?php echo $disabled?>><option value='0'>None</option>
					<?php   if(!empty($borrowers)){
								foreach($borrowers as $borrower){ ?>
								<option value="<?php echo $borrower['userid']?>" <?php if($refer_member==$borrower['userid']) echo "Selected";?>><?php echo $borrower['FirstName']." ".$borrower['LastName']." (".$borrower['City'].")";?></option>
			<?php		}
					}?></select><br/><div id="refer_membererror"><?php echo $form->error("refer_member"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['nearest_city'];?><a id="volunteer_mentorerr"></a></td>
					<td><select id="vm_city" name="vm_city" onchange="get_volunteers(this.value)">
					<?php 
						if(!empty($vmcities)){?>
			<?php			for($x=0;$x<count($vmcities);$x++){?>
								<option value="<?php echo $vmcities[$x]?>" <?php if(strcasecmp($save_vm_city,$vmcities[$x])==0) echo "Selected"?>><?php echo $vmcities[$x];?></option>
			<?php			}
						}?>?></td>
				</tr>
					 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['volunteer_mentor'];?><a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['register']['tooltip_mentor'] ?></span><span class='bottom'></span></span></a></strong></td>
					<td><select id="volunteer_mentor" name="volunteer_mentor">
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
									<option value='<?php echo $key ?>' <?php if($volunteer_mentor==$key){ echo 'selected'; } ?>><?php echo $name ?></option>
				<?php			}
						 }else{?>		
							<option></option>
					<?php	} ?>
						</select><br/><div id="volunteer_mentorerror"><?php echo $form->error("volunteer_mentor"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>

								<tr>
					<td><?php echo $lang['register']['sign_recomform_name'];?>:<a id="sign_recomform_nameerr"></a></td>
					<td><input type="text" id="rec_form_offcr_name" name="rec_form_offcr_name"  class="inputcmmn-1" value="<?php echo $rec_form_offcr_name?>" /><br/><div id="sign_recomform"><?php echo $form->error("rec_form_offcr_name"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>

					<td><?php echo $lang['register']['sign_recomform_num'];?>:<a id="sign_recomform_numerr"></a></td>
					<td><input type="text" id="rec_form_offcr_num" name="rec_form_offcr_num"  class="inputcmmn-1" value="<?php echo $rec_form_offcr_num ?>" /><br/><div id="sign_recomform"><?php echo $form->error("rec_form_offcr_num"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>


	<!------ Modify by Mohit on date 20-12-13 --->			
	<?php 
	if($cntct_type=='1') {?>			
				<tr><td>&nbsp;</td></tr>			
				<tr style="<?php if($country!='BF') echo 'display:none'; else echo "display:''";?>" id="facebook_optional" >
					<td>
					<input type="radio" name="cntct_type" id="FB_cntct" value="1" onclick="needToConfirm = false;open_contact(this.value);submitform1();" <?php if($cntct_type=='1' || isset($_SESSION['FB_Detail'])) echo"checked";?> <?php echo $disabled; ?>>
							
					<?php echo $lang['register']['FB_contact']." (This must be an actively used account in your own name.)";?><br/><br/>
				</div>
					<div id="fb_instruction" style="<?php if($cntct_type=='1')echo "display:block;"; else echo "display:none;"; ?>"><?php echo $lang['register']['fb_instruction']; ?></div>
					<?php echo $form->error("cntct_type"); ?>
					</td>		
					<td>
						<?php 
						if(!empty($facebook_id)){?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" style="<?php if($cntct_type=='1' || isset($_SESSION['FB_Detail']))echo "display:block"; else echo "display:none"; ?>"><img src="images/facebook-connect.png"/></a>
						<?}elseif($fbData['loginUrl']==''){ ?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="javascript:login_popup('<?php echo $fbData['logoutUrl']?>');return false;" style="<?php if($cntct_type=='1' || isset($_SESSION['FB_Detail']))echo "display:block"; else echo "display:none"; ?>"><img src="images/f_disconnect.jpg"/></a>
						<?php }else{?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="login_popup('<?php echo $fbData['loginUrl']?>');" style="<?php if($cntct_type=='1' || isset($_SESSION['FB_Detail']))echo "display:block"; else echo "display:none"; ?>"><img src="images/facebook-connect.png"/></a>
					<?php }?>
					</td>
				</tr>
				<tr style="<?php if($country!='BF' || $fbmsg_hide==1) echo 'display:none'; else echo 'display:block';?>" id="facebook_result" >
				<td><?php 
					if(isset($_SESSION['FB_Detail'])){
						echo "<div align='center'><font color=green><strong>Your Facebook account is now linked to Zidisha.</strong></font></div><br/>";
					}
					if(isset($_SESSION['FB_Error'])){
						echo $_SESSION['FB_Error'];
					}?></td>
				</tr>	  
			</table>

	<?php /*** Added if condition by mohit on date 5-12-13 ***/
	}elseif($cntct_type=='0'){?>	
			<table class="detail">
				<tr>
					<td id="telephone_contact" style="<?php if($country!='BF') echo 'display:none'; else echo 'display:block';?>"><input type="radio" name="cntct_type" id="tel_cntct" onclick="open_contact(this.value);" value="0" <?php if($cntct_type=='0') echo"checked";?> <?php echo $disabled; ?>><?php echo $lang['register']['tel_contact']?></td>
					<td></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
	<!--------------- Modify by Mohit on date 20-12-13 ------------->		
	<?php } else {?>
	
	<tr style="<?php if($country!='BF') echo 'display:none'; else echo 'display:block';?>" id="contact_type">
					<td colspan='2'><?php echo $lang['register']['contact_type']?><a id="cntct_type"></a><br/><?php echo $form->error("contact_type"); ?></td>
					<td></td>
				</tr>
				<tr><td>&nbsp;</td></tr>			
				<tr style="<?php if($country!='BF') echo 'display:none'; else echo "display:''";?>" id="facebook_optional" >
					<td>
					<input type="radio" name="cntct_type" id="FB_cntct" value="1" onclick="needToConfirm = false;open_contact(this.value);submitform1();" <?php if($cntct_type=='1' || isset($_SESSION['FB_Detail'])) echo"checked";?> <?php echo $disabled; ?>>
							
					<?php echo $lang['register']['FB_contact']." (This must be an actively used account in your own name.)";?><br/><br/>
				</div>
					<div id="fb_instruction" style="<?php if($cntct_type=='1')echo "display:block;"; else echo "display:none;"; ?>"><?php echo $lang['register']['fb_instruction']; ?></div>
					<?php echo $form->error("cntct_type"); ?>
					</td>		
					<td>
						<?php 
						if(!empty($facebook_id)){?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" style="<?php if($cntct_type=='1' || isset($_SESSION['FB_Detail']))echo "display:block"; else echo "display:none"; ?>"><img src="images/facebook-connect.png"/></a>
						<?}elseif($fbData['loginUrl']==''){ ?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="javascript:login_popup('<?php echo $fbData['logoutUrl']?>');return false;" style="<?php if($cntct_type=='1' || isset($_SESSION['FB_Detail']))echo "display:block"; else echo "display:none"; ?>"><img src="images/f_disconnect.jpg"/></a>
						<?php }else{?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="login_popup('<?php echo $fbData['loginUrl']?>');" style="<?php if($cntct_type=='1' || isset($_SESSION['FB_Detail']))echo "display:block"; else echo "display:none"; ?>"><img src="images/facebook-connect.png"/></a>
					<?php }?>
					</td>
				</tr>
				<tr style="<?php if($country!='BF' || $fbmsg_hide==1) echo 'display:none'; else echo 'display:block';?>" id="facebook_result" >
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
					<td id="telephone_contact" style="<?php if($country!='BF') echo 'display:none'; else echo 'display:block';?>"><input type="radio" name="cntct_type" id="tel_cntct" onclick="open_contact(this.value);" value="0" <?php if($cntct_type=='0') echo"checked";?> <?php echo $disabled; ?>><?php echo $lang['register']['tel_contact']?></td>
					<td></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
	
	<?php }?>
<!--------------- end here ------------->


	
			<table class="detail" style="<?php if($cntct_type=='0' || $country!='BF')echo "display:block"; else echo "display:none"; ?>" id="tele_contacts">
				<tr>
					<td colspan='2'><?php echo $lang['register']['family_contact']?><a id="bfamilycontact"></a></td>
					<td></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['family_contact1']?><a id="bfamilycontact1"></a></td>
					<td><textarea name="bfamilycont1" id='bfamilycont1' class="textareacmmn" <?php echo $disabled?>><?php echo $bfamilycontact1; ?></textarea><br/><?php echo $form->error("bfamilycont1"); ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['family_contact2']?><a id="bfamilycontact2"></a></td>
					<td><textarea name="bfamilycont2" id='bfamilycont2' class="textareacmmn" <?php echo $disabled?>><?php echo $bfamilycontact2; ?></textarea><br/><?php echo $form->error("bfamilycont2"); ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['family_contact3']?><a id="bfamilycontact3"></a></td>
					<td><textarea name="bfamilycont3" id='bfamilycont3' class="textareacmmn" <?php echo $disabled?>><?php echo $bfamilycontact3; ?></textarea><br/><?php echo $form->error("bfamilycont3"); ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan='2'><?php echo $lang['register']['neigh_contact'];?><a id="bneighcontact"></a></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['neigh_contact1']?><a id="bneighcontact1"></a></td>
					<td><textarea name="bneighcont1" id='bneighcont1' class="textareacmmn" <?php echo $disabled?>><?php echo $bneighcontact1; ?></textarea><br/><?php echo $form->error("bneighcont1"); ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['neigh_contact2']?><a id="bneighcontact2"></a></td>
					<td><textarea name="bneighcont2" id='bneighcont2' class="textareacmmn" <?php echo $disabled?>><?php echo $bneighcontact2; ?></textarea><br/><?php echo $form->error("bneighcont2"); ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['neigh_contact3']?><a id="bneighcontact3"></a></td>
					<td><textarea name="bneighcont3" id='bneighcont3' class="textareacmmn" <?php echo $disabled?>><?php echo $bneighcontact3; ?></textarea><br/><?php echo $form->error("bneighcont3"); ?></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
			<table class="detail">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td style="vertical-align:text-top">
						<?php echo $lang['register']['A_Yourself'];?>
						<a id="babouterr"></a>
					</td>
					<?php if($borrowerActive!=1) {
							$aboutmeId = 'babout';
						} else {
							$aboutmeId = 'baboutActive';
						}?>
					<td><textarea name="babout" id='<?php echo $aboutmeId ?>' class="textareacmmn" style="height:130px;"><?php echo $about; ?></textarea><br/><div id="babout_err"><?php echo $form->error("babout"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td style="vertical-align:text-top">
						<?php echo $lang['register']['bdescription'];?>
						<a id="bbizdescerr"></a>
					</td>
					<td><?php 
						if($borrowerActive!=1) {
							$bbizId = 'bbizdesc';
						} else {
							$bbizId = 'bbizdescActive';
						}?>
						<textarea name="bbizdesc" id="<?php echo $bbizId?>" class="textareacmmn" style="height:130px;"><?php echo $desc ; ?></textarea><br/>
						<div id="brbizdesc_err"><?php echo $form->error("bbizdesc"); ?></div>
					</td>
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

				<!-- moved ID & recommendation form to optional verification page 27-12-13 
							
				 <tr>
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

							<?php if($borrowerActive!=1) {?>
							
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
				 <tr><td>&nbsp;</td></tr>
				 <tr>

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
							<?php if($borrowerActive!=1) {?>
							
								<div>
									<input type="file" name="address_proof" id="address_proof" value="" onchange="uploadfile(this)"/>
								</div>
								
								<div class="fileType_hide">
									<input type="file" name="address_proof" id="address_proof" value="" onchange="uploadfile(this)"/>
								</div>
								<?php } ?>
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
				<tr><td>&nbsp;</td></tr>

				end moved ID & recommendation form to optional verification page 27-12-13 -->

<!-- endorsement section no longer required 

			<?php $params['minendorser']= $database->getAdminSetting('MinEndorser');
				  $endoresr_text= $session->formMessage($lang['register']['endorser'], $params);
			?>
			<table class="detail" style="<?php if($cntct_type=='1' || $country!='BF')echo "display:block";elseif(isset($_SESSION['FB_Detail'])) echo "display:block"; else echo "display:none"; ?>" id="endorser" name="endorser" >
				<tr><td ><?php echo $endoresr_text; ?><?php echo $form->error("endorser"); ?></td></tr>
				<tr><td>&nbsp;</td></tr>

				<tr><td><?php echo $lang['register']['endorser1_name']?></td><td><?php echo $lang['register']['endorser1_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name1" value="<?php echo $endorser_name1; ?>" <?php echo $disabled?>/><?php echo $form->error("endorser_name1"); ?></td><td><input type="text" name="endorser_email1" value="<?php echo $endorser_email1; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id1" value="<?php echo $endorser_id1; ?>" /><?php echo $form->error("endorser_email1"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser2_name']?></td><td><?php echo $lang['register']['endorser2_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name2" value="<?php echo $endorser_name2; ?>" <?php echo $disabled?>/><?php echo $form->error("endorser_name2"); ?></td><td><input type="text" name="endorser_email2" value="<?php echo $endorser_email2; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id2" value="<?php echo $endorser_id2; ?>" /><?php echo $form->error("endorser_email2"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser3_name']?></td><td><?php echo $lang['register']['endorser3_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name3"/ value="<?php echo $endorser_name3; ?>" <?php echo $disabled?> ><?php echo $form->error("endorser_name3"); ?></td><td><input type="text" name="endorser_email3" value="<?php echo $endorser_email3; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id3" value="<?php echo $endorser_id3; ?>" /><?php echo $form->error("endorser_email3"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser4_name']?></td><td><?php echo $lang['register']['endorser4_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name4" value="<?php echo $endorser_name4; ?>" <?php echo $disabled?> /><?php echo $form->error("endorser_name4"); ?></td><td><input type="text" name="endorser_email4" value="<?php echo $endorser_email4; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id4" value="<?php echo $endorser_id4; ?>"/><?php echo $form->error("endorser_email4"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser5_name']?></td><td><?php echo $lang['register']['endorser5_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name5" value="<?php echo $endorser_name5; ?>" <?php echo $disabled?> /><?php echo $form->error("endorser_name5"); ?></td><td><input type="text" name="endorser_email5" value="<?php echo $endorser_email5; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id5" value="<?php echo $endorser_id5; ?>"/><?php echo $form->error("endorser_email5"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser6_name']?></td><td><?php echo $lang['register']['endorser6_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name6" value="<?php echo $endorser_name6; ?>" <?php echo $disabled?> /><?php echo $form->error("endorser_name6"); ?></td><td><input type="text" name="endorser_email6" value="<?php echo $endorser_email6; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id6" value="<?php echo $endorser_id6; ?>" /><?php echo $form->error("endorser_email6"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser7_name']?></td><td><?php echo $lang['register']['endorser7_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name7" value="<?php echo $endorser_name7; ?>" <?php echo $disabled?> /><?php echo $form->error("endorser_name7"); ?></td><td><input type="text" name="endorser_email7" value="<?php echo $endorser_email7; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id7" value="<?php echo $endorser_id7; ?>" /><?php echo $form->error("endorser_email7"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser8_name']?></td><td><?php echo $lang['register']['endorser8_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name8" value="<?php echo $endorser_name8; ?>" <?php echo $disabled?> /><?php echo $form->error("endorser_name8"); ?></td><td><input type="text" name="endorser_email8" value="<?php echo $endorser_email8; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id8" value="<?php echo $endorser_id8; ?>" /><?php echo $form->error("endorser_email8"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser9_name']?></td><td><?php echo $lang['register']['endorser9_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name9" value="<?php echo $endorser_name9; ?>" <?php echo $disabled?> /><?php echo $form->error("endorser_name9"); ?></td><td><input type="text" name="endorser_email9" value="<?php echo $endorser_email9; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id9" value="<?php echo $endorser_id9; ?>"/><?php echo $form->error("endorser_email9"); ?></td></tr>

				<tr><td><?php echo $lang['register']['endorser10_name']?></td><td><?php echo $lang['register']['endorser10_email']?></td></tr>
				<tr><td><input type="text" name="endorser_name10" value="<?php echo $endorser_name10; ?>" <?php echo $disabled?> /><?php echo $form->error("endorser_name10"); ?></td><td><input type="text" name="endorser_email10" value="<?php echo $endorser_email10; ?>" <?php echo $disabled?> /><input type="hidden" name="endorser_id10" value="<?php echo $endorser_id10; ?>" /><?php echo $form->error("endorser_email10"); ?></td></tr>
			</table>
<br/><br/><br/>
end endorsement section -->


					<tr><br/><br/>
						<td><strong>Terms of Use</strong></td>
					</tr>
					<br/>

					<tr>
						<td align="center" style="vertical-align:text-top">
							<div align="left" style="border: 1px solid black; padding: 0px 10px 10px; overflow: auto; line-height: 1.5em; width: 144%; height: 130px; background-color: rgb(255, 255, 255);">
							<?php
								include_once("./editables/legalagreement.php");
								include_once("./editables/register.php");
								$path1=	getEditablePath('legalagreement.php');
								include_once("./editables/".$path1);
								$path1=	getEditablePath('register.php');
								include_once("./editables/".$path1);
								echo $lang['legalagreement']['b_tnc'];
							?>
							</div>
						</td>
					</tr>

				<br/><br/><br/>					

				<tr>
					<td colspan="2" style="text-align:left;">
						<input type="hidden" name="uploadedDocs[]" value="<?php echo $docuploaded['fronNationalid']?>" />
						<input type="hidden" name="uploadedDocs[]" value="<?php echo $docuploaded['backNationalId']?>" />
						<input type="hidden" name="uploadedDocs[]" value="<?php echo $docuploaded['addresProof']	?>" />
						<input type="hidden" name="uploadedDocs[]" value="<?php echo $docuploaded['legalDeclaration']?>" />
						<input type="hidden" name="uploadedDocs[]" value="<?php echo $docuploaded['legal_declaration2']?>" />
						<input type="hidden" name="editborrower" />
						<input type="hidden" name="borrower_behalf_id" value="<?php echo $borrower_behalf_id?>";/>
						<input type="hidden" name="before_fb_data" id="before_fb_data" />
						<input type="hidden" name="fb_data" id="fb_data" value='<?php if(!empty($save_fb_data)) echo urlencode(addslashes(base64_decode($save_fb_data))); else echo urlencode(addslashes(serialize($fbData))); ?>'/>
						<?php Logger_Array("FB LOG - on b_editprofile",'fbData1',serialize($fbData).$username); ?>
						<input type="hidden" name="uploadfileanchor" id="uploadfileanchor" />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('editborrower'); ?>"/>
						<input type="hidden" name="id" value="<?php echo $id ;?>"/>
						<br/><br/><br/>
						<?php if($borrowerActive!=1) {?>	
						
						<input type="submit"   name='submitform' align='center' onclick="needToConfirm = false;" value="<?php echo $lang['register']['RegisterComplete'];?>"  class='btn'/>
						

						<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

						<a id="saveLaterLink" href="#"><?php echo $lang['register']['Registerlater'];?></a>
					
						<!-- 
						<div align="left" style="margin-top: -32px;"><input  type="submit" name='submitform' id='borrowersubmitform' onclick="needToConfirm = false;" value="<?php echo $lang['register']['Registerlater'];?>"  class='btn'/></div>
						-->

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
<script type="text/javascript">

	$( document ).ready(function() {

  		$('#saveLaterLink').click(function(e){
    		e.preventDefault();
    		needToConfirm = false;
   		 	$('#sub-borrower').submit();
  		});

	});

</script>
<div id='ResidentialaddrExample' style="display:none">
<div class="instruction_space" style="margin-top:10px;height:160px">
	<div class="instruction_text"><?php echo $lang['register']['addresexample']?></div>
</div>
</div>