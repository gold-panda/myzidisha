<script type="text/javascript" src="includes/scripts/savebrwrdetail.js"></script>
<table class="detail" width="315px;">
	<tbody>


		<tr>
			<td width="220px"><strong><?php echo $lang['profile']['firstName'] ?>:</strong></td>
			<td>
				<div id="bfirstname" name="bname" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="bfirstnameHide" name="bfirstnameHide"> <?php echo $fname;?></div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="Editbfirstname" style="display:none; overflow: hidden; "> 
					<textarea name="bfirstnameValue" id="bfirstnameValue"><?php echo $fname;?></textarea> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbfirstnameValue" name="#sEditbfirstnameValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbfirstnameValue" name="cEditbfirstnameValue" >
				</div>
			</td>
		
		</tr>
		<tr>
			<td width="220px"><strong><?php echo $lang['profile']['lastName'] ?>:</strong></td>
			<td>
				<div id="blastname" name="bname" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="blastnameHide" name="blastnameHide"> <?php echo $lname;?></div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="Editblastname" style="display:none; overflow: hidden; "> 
					<textarea name="blastnameValue" id="blastnameValue"><?php echo $lname;?></textarea> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditblastnameValue" name="#sEditblastnameValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditblastnameValue" name="cEditblastnameValue" >
				</div>
			</td>
		
		</tr>
		<?php 
		if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL){ ?>
		<tr>
			<td><strong><?php echo $lang['profile']['Contact_no'] ?>:</strong></td>
			<td>
			<div id="btelmobile" name="btelmobile" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="btelmobileHide" name="btelmobileHide"> <?php echo $telmobile;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editbtelmobile" style="display:none; overflow: hidden; "> 
				<input type="text" name="btelmobileValue" id="btelmobileValue" value="<?php echo $telmobile;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbtelmobileValue" name="#sEditbtelmobileValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbtelmobileValue" name="cEditbtelmobileValue" >
			</div>
		</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['Email'] ?>:</strong></td>
			<td>
			<div id="bemail" name="bemail" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="bemailHide" name="bemailHide"> <?php echo $email;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editbemail" style="display:none; overflow: hidden; "> 
				<input type="text" name="bemailValue" id="bemailValue" value="<?php echo $email;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbemailValue" name="#sEditbemailValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbemailValue" name="cEditbemailValue" >
			</div>
		</td>
		</tr>
	<!--	<tr>
			<td><strong><?php echo $lang['profile']['User_Name'] ?>:</strong></td>
			<td>
				<?php echo $username?>
			</td>
		</tr> -->
		<tr>
			<td><strong><?php echo $lang['profile']['address'] ?>:</strong></td>
			<td>
			<div id="bpostaddr" name="bpostaddr" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="bpostaddrHide" name="bpostaddrHide"> <?php echo $padd;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editbpostaddr" style="display:none; overflow: hidden; "> 
				<textarea name="bpostaddrValue" id="bpostaddrValue"><?php echo $padd;?></textarea> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbpostaddrValue" name="#sEditbpostaddrValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbpostaddrValue" name="cEditbpostaddrValue" >
			</div>
		</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['home_location'] ?>:</strong></td>
			<td><?php echo $home_location;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['City'] ?>:</strong></td>
			<td><div id="bcity" name="bcity" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="bcityHide" name="bcityHide"><?php echo $bcity;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editbcity" style="display:none; overflow: hidden; "> 
				<input type="text" name="bcityValue" id="bcityValue" value="<?php echo $bcity;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbcityValue" name="#sEditbcityValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbcityValue" name="cEditbcityValue" >
			</div>
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['Country'] ?>:</strong></td>
			<td>
			<div id="bcountry" name="bcountry" style="overflow: hidden;" > 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="bcountryHide" name="bcountryHide"><?php echo $database->mysetCountry($country);?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit" <?php echo $disabled; ?> >
					</td>
				</tr>
				</table>
			</div>
			<?php 
				$countries = $database->countryList(true);
				$option ='';
				foreach($countries as $countrycode){
					$countryname = $countrycode['name'];
					$countryid = $countrycode['code'];
					$option.="<option value='$countryid'>$countryname</option>";
				}
			?>
			<div id="Editbcountry" style="display:none; overflow: hidden; "> 

				<select name='bcountryValue' id='bcountryValue'><option value='0'>Select Country</option><?php echo $option?></select>
				
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbcountryValue" name="#sEditbcountryValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbcountryValue" name="cEditbcountryValue" >
			</div>
			</td>
		</tr>
		<?php  if($rightuser == 1){  ?>
		<tr>
			<td><strong><?php echo $lang['profile']['nationid'] ?>:</strong></td>
			<td><?php echo $nationid;?></td>
		</tr>
		<tr><td></td></tr>
		<?php } ?>
		<tr>
		<td><strong><?php echo $lang['profile']['copynationid'] ?>:</strong></td>
		<td><strong><a href="<?php echo SITE_URL.'download.php?u='.$id.'&doc=frontNationalId'; ?>"><?php echo $lang['profile']['dwn_front_nation_id'];?></a></strong><br/><strong><a href="<?php echo SITE_URL.'download.php?u='.$id.'&doc=backNationalId'; ?>"><?php echo $lang['profile']['dwn_back_nation_id'];?></a></strong></td>
		</tr>
		<tr><td></td></tr>
		<tr>
		<td><strong><?php echo $lang['profile']['recommondation'] ?>:</strong></td>
		<td><strong><a href="<?php echo SITE_URL.'download.php?u='.$id.'&doc=addressProof'; ?>"><?php echo $lang['profile']['dwn_address_proof'];?></a></strong></td>
		</tr>
		<tr><td></td></tr>
		<tr>
		<td><strong><?php echo $lang['profile']['contract1&2'] ?>:</strong></td>
		<td><strong><a href="<?php echo SITE_URL.'download.php?u='.$id.'&doc=legalDeclaration'; ?>"><?php echo $lang['profile']['dwn_legal_dec'];?></a></strong><br/><strong><a href="<?php echo SITE_URL.'download.php?u='.$id.'&doc=legal_declaration2'; ?>"><?php echo $lang['profile']['dwn_legal2_dec'];?></a></strong></td>
		</tr>
		</tbody>
	</table>
	</div>
	<div style="float:left;clear:both;width:710px;" >
		<h4><?php echo $lang['profile']['abount_borrower'] ?>:</h4>
		<?php echo $about;?>
		<br/><br/>
		<h4><?php echo $lang['profile']['business_des'] ?>:</h4>
		<?php echo $desc;?>
		<br/><br/>
	<table class="detail" >
		<tbody>
		<h3 class="subhead top"><?php echo $lang['profile']['additional_cntct'] ?></h3>
		<?php $postedby_detail= $database->getBorrowerbehalfdetail($behalf_id);?>
		<tr>
			<td width="220px" ><strong><?php echo $lang['profile']['posted_by'] ?>:</strong></td>
		<?php if($behalf_id>0){?>
			<td><?php echo $postedby_detail['name'].', '.$postedby_detail['town'].', '.$postedby_detail['email'].', '.$postedby_detail['contact_no'];?></td>
		<?php } ?>
		</tr>
		<tr><td></td></tr>
		<?php  if($rightuser == 1){  ?>
		<tr>
			<td width="220px" ><strong><?php echo $lang['profile']['loanhist'] ?>:</strong></td>
			<td><?php echo $loanhist;?></td>
		</tr>
		<tr><td></td></tr>
		<?php } ?>
		<tr>
			<?php if(!empty($part_verify_comnt[0]['comment']))
					$part_comment=$part_verify_comnt[0]['comment']; 
				  else
					  $part_comment=''; ?>
			<td width="220px" ><strong><?php echo $lang['profile']['partner_verification'] ?>:</strong></td>
		<?php if(!empty($profile)){?>
			<td><?php echo "<a href='$partprofile'>".$partname."</a><br/>".$part_comment;?></td>
		<?php } ?>
		</tr>
		
		<tr><td></td></tr>
			<tr>
				<td><strong><?php echo $lang['profile']['reffered_by'] ?></strong></td>
				<td><?php echo nl2br($reffered_by); ?></td>
			</tr>
		<tr><td></td></tr>


		<tr>
			<?php 	if(!empty($refer_id)){
						$ref_name = $database->getNameById($refer_id);
						$ref_number= $database->getPrevMobile($refer_id);
						$ref_profile= getUserProfileUrl($refer_id);
						$refer= "<a href='$ref_profile'>".$ref_name."</a>, ".$ref_number;
					}else{
						$refer='';
					}
			?>
			<td width="220px" ><strong><?php echo $lang['profile']['refername'] ?>:</strong></td>
			<td><?php echo $refer; ?></td>
		<!--	<td>
			<div id="refername" name="refername" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="refernameHide" name="refernameHide"><?php echo $ref_name;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editrefername" style="display:none; overflow: hidden; "> 
				<?php 
				$contryCode = $database->getCountryCodeById($id);
				$borrowers = $database->getActiveBorrowersByCountry($contryCode);
				$options = '';
				if(!empty($borrowers))
					{
						foreach($borrowers as $result)
						{	
							$city = '';
							if(!empty($result['City'])) {
								$city = " (".$result['City'].")";
							}
							if(!empty($result['TelMobile'])) {
								$TelMobile = " (".$result['TelMobile'].")";
							}
							$options.=  "<option value='".$result['userid']."'>".htmlentities($result['FirstName']." ".$result['LastName'].$TelMobile)."</option>";
						}
					 } 
			?>
				<select name="refernameValue" id="refernameValue"><option value='0'>Select Referer</option><?php echo $options?></select>
				
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditrefernameValue" name="#sEditrefernameValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditrefernameValue" name="cEditrefernameValue" >
			</div>
			</td>
		</tr>--->
		</tr>
		
		<tr><td></td></tr>
		
		<tr>
		
		<?php $invitee= $database->getInvitee($id);
			if(!empty($invitee)){
				$inviteename= $database->getNameById($invitee);
				$inviteenumber= $database->getPrevMobile($invitee);
				$inviteeurl= getUserProfileUrl($invitee);
				$invitedby= "<a href='$inviteeurl'>".$inviteename."</a>";
				if(!empty($inviteenumber))
					$invitedby.= ', '.$inviteenumber;
			}else{
				$invitedby='';
			}?>
		<tr>
			<td><strong><?php echo $lang['profile']['invited'] ?>:</strong></td>
			<td><?php echo $invitedby; ?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<?php 	if(!empty($mentor_id)){
						$mentor = $database->getNameById($mentor_id);
						$mentor_number= $database->getPrevMobile($mentor_id);
						$mentor_profile= getUserProfileUrl($mentor_id);
						$vol_mentor= "<a href='$mentor_profile'>".$mentor."</a>, ".$mentor_number;
					}else{
						$vol_mentor='';
					}
			?>
			<td width="220px" ><strong><?php echo $lang['profile']['mentor'] ?>:</strong></td>
			<td><?php echo $vol_mentor ; ?></td>
		<!--	<td>
			<div id="mentor" name="mentor" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="mentorHide" name="mentorHide"><?php echo $mentor;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<?php 
				$contryCode = $database->getCountryCodeById($id);
				$volunters = $database->getAllCoOrgBorrowers($contryCode);
				$option ='';
				foreach($volunters as $volunteer){
					$vol_name = $database->getNameById($volunteer['user_id']);
					$vol_id = $volunteer['user_id'];
					$option.="<option value='$vol_id'>$vol_name</option>";
				}
			?>
			<div id="Editmentor" style="display:none; overflow: hidden; "> 

				<select name='mentorValue' id='mentorValue'><option value='0'>Select Mentor</option><?php echo $option?></select>
				
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditmentorValue" name="#sEditmentorValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditmentorValue" name="cEditmentorValue" >
			</div>
			</td>--->
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['family_member1'] ?>:</strong></td>
			<td>
			<div id="bfamily1" name="bfamily1" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="bfamily1Hide" name="bfamily1Hide"><?php echo $family_member1;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editbfamily1" style="display:none; overflow: hidden; "> 
				<input type="text" name="bfamily1Value" id="bfamily1Value" value="<?php echo $family_member1;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbfamily1Value" name="#sEditbfamily1Value"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbfamily1Value" name="cEditbfamily1Value" >
			</div>
			
			</td>
		</tr>
			<td><strong><?php echo $lang['profile']['family_member2'] ?>:</strong></td>
			<td>
				<div id="bfamily2" name="bfamily2" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="bfamily2Hide" name="bfamily2Hide"> <?php echo $family_member2;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editbfamily2" style="display:none; overflow: hidden; "> 
				<input type="text" name="bfamily2Value" id="bfamily2Value" value="<?php echo $family_member2;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbfamily2Value" name="#sEditbfamily2Value"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbfamily2Value" name="cEditbfamily2Value" >
			</div>
				
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['family_member3'] ?>:</strong></td>
			<td>
				<div id="bfamily3" name="bfamily3" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="bfamily3Hide" name="bfamily3Hide"> <?php echo $family_member3;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editbfamily3" style="display:none; overflow: hidden; "> 
				<input type="text" name="bfamily3Value" id="bfamily3Value" value="<?php echo $family_member3;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditbfamily3Value" name="#sEditbfamily3Value"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditbfamily3Value" name="cEditbfamily3Value" >
			</div>
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['neighbor1'] ?>:</strong></td>
			<td>
			<div id="neighbor1" name="neighbor1" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="neighbor1Hide" name="neighbor1Hide"><?php echo $neighbor1;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editneighbor1" style="display:none; overflow: hidden; "> 
				<input type="text" name="neighbor1Value" id="neighbor1Value" value="<?php echo $neighbor1;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditneighbor1Value" name="#sEditneighbor1Value"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditneighbor1Value" name="cEditneighbor1Value" >
			</div>
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['neighbor2'] ?>:</strong></td>
			<td>
			<div id="neighbor2" name="neighbor2" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="neighbor2Hide" name="neighbor2Hide"><?php echo $neighbor2;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editneighbor2" style="display:none; overflow: hidden; "> 
				<input type="text" name="neighbor2Value" id="neighbor2Value" value="<?php echo $neighbor2;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditneighbor2Value" name="#sEditneighbor2Value"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditneighbor2Value" name="cEditneighbor2Value" >
			</div>
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['neighbor3'] ?>:</strong></td>
			<td>
			<div id="neighbor3" name="neighbor3" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="neighbor3Hide" name="neighbor3Hide"><?php echo $neighbor3;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="Editneighbor3" style="display:none; overflow: hidden; "> 
				<input type="text" name="neighbor3Value" id="neighbor3Value" value="<?php echo $neighbor3;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditneighbor3Value" name="#sEditneighbor3Value"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditneighbor3Value" name="cEditneighbor3Value" >
			</div>
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['community_leader'] ?>:</strong></td>
			<?php if(!empty($recom_name)){ ?>
			<td><?php echo $recom_name.','.$recom_number ?></td>
			<?php } ?>
	<!--		<td>
			<div id="recomName" name="recomName" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="recomNameHide" name="recomNameHide"><?php echo $recom_name;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="EditrecomName" style="display:none; overflow: hidden; "> 
				<input type="text" name="recomNameValue" id="recomNameValue" value="<?php echo $recom_name;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditrecomNameValue" name="#sEditrecomNameValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditrecomNameValue" name="cEditrecomNameValue" >
			</div>
			</td>-->
		</tr>
		<tr><td></td></tr>

<!--		<tr>
			<td><strong><?php echo $lang['profile']['reco_number'] ?>:</strong></td>
			<td>
			<div id="recoNumber" name="recoNumber" style="overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
					<div id="recoNumberHide" name="recoNumberHide"><?php echo $recom_number;?></div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr>
				</table>
			</div>
			<div id="EditrecoNumber" style="display:none; overflow: hidden; "> 
				<input type="text" name="recoNumberValue" id="recoNumberValue" value="<?php echo $recom_number;?>" /> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditrecoNumberValue" name="#sEditrecoNumberValue"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditrecoNumberValue" name="cEditrecoNumberValue" >
			</div>
			</td>
		</tr>--->
		<?php /*
		$isendorser= $database->IsBorrowerEndorser($id);
		if($isendorser>0){ 
			$endorsedbrwr= $database->getBrwrDetailFrmEndorser($id);
			$params['brwrprurl']=getUserProfileUrl($endorsedbrwr['borrowerid']);
			$params['bname']=$database->getNameById($endorsedbrwr['borrowerid']);
			$eKnowBrwr=$session->formMessage($lang['profile']['e_know_brwr'], $params);
			$eCnfdntBrwr=$session->formMessage($lang['profile']['e_cnfdnt_brwr'], $params);*/
			if(!empty($fb_data)){
				if(isset($fb_data['user_friends']['data'])){
					$no_of_friends= count($fb_data['user_friends']['data']);
				}else{
					$no_of_friends= count($fb_data['user_friends']);
				}?>		
		<tr>
			<td><strong><?php echo $lang['profile']['online_identity']; ?>:</strong></td>
			<td><a href="<?php echo 'index.php?p=91&userid='.$id; ?>"><?php echo $lang['profile']['view_fb_data']?></a></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['endorsed'];?>:</strong></td>
			<td>
				<?php $endorse_details= $database->getEndorserRecived($id); 
					foreach($endorse_details as $endorse_detail){
					if(!empty($endorse_detail['endorserid'])){
						$e_profile = getUserProfileUrl($endorse_detail['endorserid']);
						$e_number= $database->getPrevMobile($endorse_detail['endorserid']);
						$endorsedbrwr= $database->getBrwrDetailFrmEndorser($endorse_detail['endorserid']);

						echo "<a href='$e_profile'>".$endorse_detail['ename']."</a>, ".$e_number."<br/>".$endorsedbrwr['e_know_brwr']."<br/>".$endorsedbrwr['e_cnfdnt_brwr']."<br/><br/>";
											}
				}?>
			</td>
		</tr>
		<?php } ?>
	<!--	<tr>
			<td><strong><?php echo $eKnowBrwr ?></strong></td>
			<td><?php echo $endorsedbrwr['e_know_brwr'];?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $eCnfdntBrwr ?></strong></td>
			<td><?php echo $endorsedbrwr['e_cnfdnt_brwr'];?></td>
		</tr>---->
<?php	 /*
			$brwl=$database->getLastloan($id);
			if(empty($brwl)){
				echo "<tr><td colspan=2>No Loan Application Yet </td></tr>";
			}
			$report=$database->loanReport($id);

			$ldate=$report['sincedate'];
			$countt=$report['NoOfLone'];
			$lamount=$report['Total'];
		  $damount=convertToDollar($lamount,$currRate);    
			$damount=$report['Total_us'];
			$CPaidOntime=$report['PaidonTime'];
			$PaidOntime=$report['AmtPaidonTime'];
		  $dPaidOntime=convertToDollar($PaidOntime,$currRate);   
			$dPaidOntime=$report['AmtPaidonTime_us'];
			$CPaidLate=$report['late'];
			$PaidLate=$report['Amtlate'];
			$dPaidLate=$report['Amtlate_us'];
		  $dPaidLate=convertToDollar($PaidLate,$currRate);   
			$CDefalted =0;
			if(isset($report['Deflted'])) {
				$CDefalted=$report['Deflted'];
			}
			$Defaulted=0;
			if(isset($report['AmtDeflted'])) {
				$Defaulted=$report['AmtDeflted'];
			}
			$dDefaulted=0;
			if(isset($report['AmtDeflted_us'])) {
				$dDefaulted=$report['AmtDeflted_us'];
			}
			$f=$report['feedback'];
			$cf=$report['Totalfeedback']; */
?>
	<!--	<tr>
			<td><strong><?php echo $lang['profile']['brw_since_date'] ?>:</strong></td>
			<td><?php echo date("M d, Y ", $ldate);?></td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['No_Loan_dis'] ?>:</strong></td>
			<td><?php echo $countt;?></td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['total_val_Loan_dis'] ?>:</strong></td>
			<td>USD <?php echo number_format($damount, 0, ".", ",");?> (<?php echo number_format($lamount, 0, ".", ",") . ' '. $UserCurrency ;?>)</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['loan_repaid_ontime'] ?>:</strong></td>
			<td><?php echo $CPaidOntime;?></td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['total_loan_repaid'] ?>:</strong></td>
			<td>USD <?php echo number_format($dPaidOntime, 0, ".", ",");?> (<?php echo number_format($PaidOntime, 0, ".", ",") . ' '. $UserCurrency ;?>)</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['loan_repaid_late'] ?>:</strong></td>
			<td><?php echo $CPaidLate;?></td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['total_loan_repaid_late'] ?>:</strong></td>
			<td>USD <?php echo number_format($dPaidLate, 0, ".", ",");?> (<?php echo number_format($PaidLate, 0, ".", ",") . ' '. $UserCurrency ;?>)</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['loan_defaulted'] ?>:</strong></td>
			<td><?php echo $CDefalted;?></td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['total_loan_deflt'] ?>:</strong></td>
			<td>USD <?php echo number_format($dDefaulted, 0, ".", ",");?> (<?php echo number_format($Defaulted, 0, ".", ",") . ' '. $UserCurrency ;?>)</td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['fbrating'] ?>:</strong> <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['profile']['tooltip_feed_rating'];?></span><span class='bottom'></span></span></a></td>
			<?php $prurl = getUserProfileUrl($id);?>
			<td><?php echo number_format($f)."% Positive (<a href='$prurl?fdb=2'>".$cf."</a>)";?></td>
		
		</tr>
		<?php if(!empty($part_verify_comnt[0]['comment'])){?>
				<tr>
					<td><strong><?php echo $lang['profile']['verification_comnt'] ?>:</strong></td>
					<td><?php echo $part_verify_comnt[0]['comment']; ?></td>
				</tr>
		<?php } ?> -->
		
			<input type='hidden' id='borrower_id' value="<?php echo $id?>">
		<?php } ?>
	</tbody>
</table>