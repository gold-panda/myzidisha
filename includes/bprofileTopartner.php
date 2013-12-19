<table class="detail">
	<tbody>
		<tr>
			<td width="220px"><strong><?php echo $lang['profile']['firstName'] ?>:</strong></td>
			<td><?php echo $fname;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td width="220px"><strong><?php echo $lang['profile']['lastName'] ?>:</strong></td>
			<td><?php echo $lname;?></td>		
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['Contact_no'] ?>:</strong></td>
			<td><?php echo $telmobile;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['Email'] ?>:</strong></td>
			<td><?php echo $email;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['address'] ?>:</strong></td>
			<td><?php echo $padd;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['home_location'] ?>:</strong></td>
			<td><?php echo $home_location;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['City'] ?>:</strong></td>
			<td><?php echo $bcity;?></td>
		</tr>
		<tr>
			<td><strong><?php echo $lang['profile']['Country'] ?>:</strong></td>
			<td><?php echo $database->mysetCountry($country);?></td>
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
		</tr>
		<tr><td></td></tr>
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
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['family_member1'] ?>:</strong></td>
			<td><?php echo $family_member1;?></td>
		</tr>
		<tr><td></td></tr>
			<td><strong><?php echo $lang['profile']['family_member2'] ?>:</strong></td>
			<td><?php echo $family_member2;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['family_member3'] ?>:</strong></td>
			<td><?php echo $family_member3;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['neighbor1'] ?>:</strong></td>
			<td><?php echo $neighbor1;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['neighbor2'] ?>:</strong></td>
			<td><?php echo $neighbor2;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['neighbor3'] ?>:</strong></td>
			<td><?php echo $neighbor3;?></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td><strong><?php echo $lang['profile']['community_leader'] ?>:</strong></td>
			<?php if(!empty($recom_name)){ ?>
			<td><?php echo $recom_name.','.$recom_number ?></td>
			<?php } ?>
		</tr>
		<tr><td></td></tr>
		<?php
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
						echo "<a href='$e_profile'>".$endorse_detail['ename']."</a>, ".$e_number."<br/>";
					}
				}?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>