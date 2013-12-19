<?php 
	$id=$getuid;
	$data=$database->getBorrowerDetails($id);
	$fname=$data['FirstName'];
	$lname=$data['LastName'];
	$name=$fname.' '.$lname;
	$padd=$data['PAddress'];
	$bcity=$data['City']; 
	$country=$data['Country'];
	$nationid=$data['nationId'];
	$loanhist=$data['loanHist'];
	$telmobile=$data['TelMobile'];
	$reffered_by=$data['reffered_by'];
	$email=$data['Email'];
	$family_member1 = $data['family_member1'];
	$family_member2 = $data['family_member2'];
	$family_member3 = $data['family_member3'];
	$neighbor1 = $data['neighbor1'];
	$neighbor2 = $data['neighbor2'];
	$neighbor3 = $data['neighbor3'];
	$recom_name= $data['rec_form_offcr_name'];
	$recom_number= $data['rec_form_offcr_num'];
	$refer_id = $data['refer_member_name'];
	$mentor_id = $data['mentor_id'];
	if(!empty($mentor_id)){
		$vm_level= $database->getUserLevelbyid($mentor_id);
		$vmcurrentloanid= $database->getCurrentLoanid($mentor_id);
		if($vm_level==BORROWER_LEVEL && !empty($vmcurrentloanid)){
			$vm_url= getLoanprofileUrl($mentor_id,$vmcurrentloanid);
		}else{
			$vm_url = getUserProfileUrl($mentor_id);
		}
		$vm_name= $database->getNameById($mentor_id);
	}
	$fb_data= unserialize(base64_decode($data['fb_data']));
	if($data['tr_About']==null || $data['tr_About']=="")
		$about=nl2br($data['About']);
	else
		$about=nl2br($data['tr_About']);
	if($data['tr_BizDesc']==null || $data['tr_BizDesc']=="")
		$desc=nl2br($data['BizDesc']);
	else
		$desc=nl2br($data['tr_BizDesc']);
	$username=$data['username'];
	$activeloanid=$data['activeLoanID'];
	$currRate=$database->getCurrentRate($id);
	$UserCurrency = $database->getUserCurrency($id);
	$profile=$database->getBorrowerPartner($id);
	$part_verify_comnt= $database->getPartnerVerificationComment($id); 
	$home_location= $data['home_location'];
	$behalf_id=$data['borrower_behalf_id'];
	$is_volunteer= $database->isBorrowerAlreadyAccess($id); 
	$nonactive=0;
	$is_mentor= $database->isBorrowerAlreadyAccess($session->userid);
	if(!empty($profile)){
		$partid=$profile['userid'];
		$partprofile=getUserProfileUrl($partid);
		$partname=$profile['name'];
		$partweb=$profile['website'];
		$act='';
	}else{
		$nonactive=1;
		$act="Not Activated Yet";
	}
	if(!empty($_GET['fdb']))
	{
		if($_GET['fdb']==1)
		{   ?>
			<div class="row">
				<?php
					$fb=1;
					include_once("includes/b_comments.php");
				?>
			</div><!-- /row -->
<?php   }
		else if($_GET['fdb']==2)
		{   ?>
			<div class="row">
				<?php
					include_once("includes/feedbacks.php"); ?>
			</div><!-- /row -->
<?php   }
		else if($_GET['fdb']==3)
		{   ?>
			<div class="row">
				<?php
					include_once("includes/endorsement.php"); ?>
			</div><!-- /row -->
<?php   }

	}
	else
	{   		if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL){ ?>


		<h3 class="subhead top"><?php echo $lang['profile']['b_detail'] ?></h3>
		<div id="user-account" style="position:relative;">
			<?php if (file_exists(USER_IMAGE_DIR.$id.".jpg")){ ?>
			<img class ="user-account-img" src="library/getimagenew.php?id=<?php echo $id;?>&width=293&height=380" alt="" style="position:absolute;right:0;"/>
			<?php } ?>
		<!--	<h4><?php echo $lang['profile']['current_loan_info'] ?></h4>-->
<?php       $lastaloan=$database->getLastloan($id);
			$tpm=2;
			if(!empty($lastaloan))
			{				
				$viewprevloan='';
				$allloans= $database->getBorrowerRepaidLoans($id);
				if(isset($allloans[0]['loancount'])){
					if($allloans[0]['loancount'] >0){
						$viewprevloan=$lang['profile']['viewprevloan'];
					}
					if($allloans[0]['loancount']==1 && $allloans[0]['loanid']==$lastaloan['loanid']){
						$viewprevloan='';
					}
				}
				$disabled= 'disabled';
				$activeloanid=$lastaloan['loanid'];
				$currenlonAmt=convertToNative($lastaloan['reqdamt'],$currRate);
				$dcurrenlonAmt=$lastaloan['reqdamt'];
				$bot1 = '';
				$bot2 = '';
				$active_on=date('d M, Y', $database->getborrowerActivatedDate($id));
				/*if($lastaloan['active']==LOAN_OPEN){
					$bot1 = $lang['profile']['bid_close'];
					$bot2 = date('M d, Y',$lastaloan['applydate'] + ($database->getAdminSetting('deadline') * 24 * 60 * 60 ));
					$totBid=$database->getTotalBid($id,$activeloanid);
					//$tpm=1;
				}else if($lastaloan['active']==LOAN_FUNDED){
					$bot1 = $lang['profile']['funded_on'];
					$bot2 = date('M d, Y',$lastaloan['AcceptDate']);
				}else if($lastaloan['active']==LOAN_ACTIVE){
					$bot1 = $lang['profile']['Active_on'];
					$bot2 = "<a href='index.php?p=7&id=".$id."'>".date('M d, Y',$lastaloan['AcceptDate'])."</a>";
				}else if($lastaloan['active']==LOAN_REPAID){
					$bot1 = $lang['profile']['repaid_comp'];
				}else if($lastaloan['active']==LOAN_DEFAULTED){
					$bot1 = $lang['profile']['default_on'];
					$bot2 = date('M d, Y',$lastaloan['expires']);
				}else if($lastaloan['active']==LOAN_CANCELED){
					$bot1 = $lang['profile']['cancel_on'];
					$bot2 = date('M d, Y',$lastaloan['expires']);
				}else if($lastaloan['active']==LOAN_EXPIRED){
					$bot1 = $lang['profile']['app_expire_on'];
					$bot2 = date('M d, Y',$lastaloan['expires']);
				}*/
			}
			else
			{
				$disabled= '';
				$tpm=0;
				$bot1 = $lang['profile']['loan_app'];
				$bot2 = $lang['profile']['no_loan_app'];
			}
?>
			<table class="detail" style="width:335px">
				<tbody>
					<tr>
						<td><strong><?php echo $lang['profile']['Active_on']; ?></strong></td>
						<td><?php echo $active_on; ?></td>
					</tr>
	<?php           if(!$tpm)
					{   ?>
						<tr>
							<td style="width:150px"><strong><?php echo $bot1; ?>:</strong></td>
							<td><?php echo $bot2; ?></td>
						</tr>
	<?php           }
					else
					{   ?>
					<!--	<tr>
							<td><strong><?php echo $lang['profile']['amt_req'] ?>:</strong></td>
							<td>USD <?php echo number_format($dcurrenlonAmt, 0, '.', ','); ?></td>
						</tr>--->
	<?php               if($tpm==1)
						{   ?>
							<tr>
								<td><strong><?php echo $lang['profile']['amt_bid'] ?>:</strong></td>
								<td>USD <?php echo number_format($totBid, 0, '.', ',');?></td>
							</tr>
							<tr>
								<td><strong><?php echo $bot1; ?>:</strong></td>
								<td><?php echo $bot2; ?></td>
							</tr>
							<?php $loanprurl = getLoanprofileUrl($id,$activeloanid);?>
							<tr><td colspan=2 align="center"><strong><a href='<?php echo $loanprurl ?>#e5'><div id='button' align='center'> </div></a></strong></td></tr>
							<tr>
								<td><strong><?php echo $lang['profile']['Country'] ?>:</strong></td>
								<td><?php echo $database->mysetCountry($country);?></td>
							</tr>
	<?php               }
						else if($tpm==2)
						{   ?>
							<tr>
								<td><strong><?php echo $bot1; ?></strong></td>
								<td><?php echo $bot2; ?></td>
							</tr>
							
				</tbody>
			</table>
			<table>
				<tbody>
							
						<tr>
							<td><strong><?php echo $lang['profile']['loansraised']; ?></strong></td>
						</tr>
						<tr>
							<td>
							<div id="loansraised" class="span16">
								<table class="detail" style="width:350px;">
									<tbody>
									<?php $borrower_loans= $database->getAllLoansOfBorrower($id);
										foreach($borrower_loans as $borrower_loan){
										$loanDisburseDate=date('M Y',$database->getLoanDisburseDate($borrower_loan['loanid']))." -";
										if($borrower_loan['active']==LOAN_ACTIVE){
											$loanRepaidDate=date('M Y',$database->getfutureRepaidDate($borrower_loan['loanid']));
										}elseif($borrower_loan['active']==LOAN_FUNDED){
											$loanRepaidDate='';
											$loanDisburseDate='';
										}elseif($borrower_loan['active']==LOAN_OPEN){
											$loanRepaidDate='Fundraising Loan';
											$loanDisburseDate='';
										}else{
											$loanRepaidDate= date('M Y',$database->getLoanRepaidDate($borrower_loan['loanid'], $ud));
										}
										$amountGot=number_format(convertToDollar($borrower_loan['AmountGot'],($currRate)), 2, ".", "");
										$loanprofileurl = getLoanprofileUrl($id,$borrower_loan['loanid']);
									?>
										<tr><td>USD&nbsp;<?php echo $amountGot?></td><td><?php echo $loanDisburseDate; ?> <?php echo $loanRepaidDate; ?></td><td><a href="<?php echo $loanprofileurl; ?>">View Loan Profile</a></td>
										</tr>
										<tr height="5px;"></tr>
									<?php }
									?>
						<!--	<?php $loanprurl = getLoanprofileUrl($id,$activeloanid);?>
							<tr><td colspan=2 align="center"><strong><a href='<?php echo $loanprurl?>'> <?php echo $lang['profile']['loan_detail']?></a></strong></td></tr>-->
	<?php               } ?>
									</tbody>
								</table>
							</div>
							</td>
						</tr>
				<!--	<tr>
							<td><div id="viewprevloan" style="cursor:pointer;" ><strong><a><?php echo $viewprevloan; ?></a></strong></div></td><td></td>
						</tr>
						<tr><td>
								<div id="viewprevloan_desc" style="display:none;" class="span16">
								<table class="detail" style="width:350px;">
									<tbody>
									<?php foreach($allloans as $allloan){
										if($allloan['loanid']!=$activeloanid){ 
										$loanDisburseDate=date('M Y',$database->getLoanDisburseDate($allloan['loanid']));
										$loanRepaidDate= date('M Y',$database->getLoanRepaidDate($allloan['loanid'], $ud));
										$amountGot=number_format(convertToDollar($allloan['AmountGot'],($currRate)), 2, ".", "");
										$loanprofileurl = getLoanprofileUrl($id,$allloan['loanid']);
									?>
										<tr><td>USD&nbsp;<?php echo $amountGot?></td><td><?php echo $loanDisburseDate; ?> - <?php echo $loanRepaidDate; ?></td><td><a href="<?php echo $loanprofileurl; ?>">View Loan Profile</a></td>
										</tr>
										<tr></tr>
									<?php }
									}
									?>
									</tbody>
								</table>
								</div>
							</td>
						</tr>--->
		<?php			}   ?>

				</tbody>
			</table>
<?php    /*   if($activeuser == 1)
			{
				echo "<p align='right'><a href='index.php?p=24&id=".$id."&l_id=".$activeloanid."&ref=1'>translation</a></p>";
			}
			if($about ==$data['tr_About'])
			{
				echo "<p align='right'><a id='abt_org' href='javascript:void(0)'>".$lang['profile']['disp_text']."</a></p>";
				echo "<p id='abt_org_desc' style='display:none;text-align:justify;'>".$data['About']."</p>";
			} */
?>
			<div style="float:left;clear:both">
		<!--	<h4><?php echo $lang['profile']['myprofile'] ?></h4> -->
		<?php	if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL) { 
					include_once("includes/bprofileToadmin.php");
				}elseif($session->userlevel==PARTNER_LEVEL || $is_mentor){
					include_once("includes/bprofileTopartner.php");
				}else {?>
				<table class="detail">
					<tbody>
						<tr>
							<td width="220px"><strong><?php echo $lang['profile']['Name'] ?>:</strong></td>
							<td><?php echo $name;?></td>
						</tr>
						<?php if($displyall){ ?>
						<tr>
							<td><strong><?php echo $lang['profile']['User_Name'] ?>:</strong></td>
							<td><?php echo $username;?></td>
						</tr>
						<tr>
							<td><strong><?php echo $lang['profile']['address'] ?>:</strong></td>
							<td><?php echo $padd;?></td>
						</tr>
						<tr>
							<td><strong><?php echo $lang['profile']['Email'] ?>:</strong></td>
							<td><?php echo $email;?></td>
						</tr>
						<tr>
							<td><strong><?php echo $lang['profile']['Contact_no'] ?>:</strong></td>
							<td><?php echo $telmobile;?></td>
						</tr>
						<?php } ?>
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
						<tr>
							<td><strong><?php echo $lang['profile']['loanhist'] ?>:</strong></td>
							<td><?php echo $loanhist;?></td>
						</tr>
						<?php } ?>
			<?php
							$brwl=$database->getLastloan($id);
							if(empty($brwl)){
								echo "<tr><td colspan=2>No Loan Application Yet </td></tr>";
							}
							$report=$database->loanReport($id);

							$ldate=$report['sincedate'];
							$countt=$report['NoOfLone'];
							$lamount=$report['Total'];
						/*  $damount=convertToDollar($lamount,$currRate);    */
							$damount=$report['Total_us'];
							$CPaidOntime=$report['PaidonTime'];
							$PaidOntime=$report['AmtPaidonTime'];
						/*  $dPaidOntime=convertToDollar($PaidOntime,$currRate);   */
							$dPaidOntime=$report['AmtPaidonTime_us'];
							$CPaidLate=$report['late'];
							$PaidLate=$report['Amtlate'];
							$dPaidLate=$report['Amtlate_us'];
						/*  $dPaidLate=convertToDollar($PaidLate,$currRate);   */
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
							$cf=$report['Totalfeedback'];
				?>
						<tr>
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
							<td><?php echo number_format($f); ?>% Positive <?php if($cf>1){?>(<a href="<?php echo $prurl?>?fdb=2"><?php echo 'New Member' ?></a>)<?php }else	echo '(New Member)'; ?></td>
						</tr>
						<tr>
							<td><strong><?php echo $lang['profile']['volunteer_mentor'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['profile']['tooltip_mentor'] ?></span><span class='bottom'></span></span></a></strong></td>
							<?php if(!empty($mentor_id)){?>
							<td><a href="<?php echo $vm_url ?>"><?php echo $vm_name; ?></a><?php }else	echo ' '; ?></td>
						</tr>
					</tbody>
				</table>
				<?php if($is_volunteer){ 
					$vm_member_details= $database->getMentorAssignedmember($id);
					$params['vm_member']= count($vm_member_details);
					$vm_member_text= $session->formMessage($lang['profile']['self_vm'], $params);
				?>
						<div id="viewassignedmember" style="cursor:pointer;" >
								<img style='float:left' class='starimg' src="images/star.png" />&nbsp;&nbsp;&nbsp;<?php echo $vm_member_text; ?>

								<a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['profile']['tooltip_mentor'] ?></span><span class='bottom'></span></span></a><br/>
						</div><br/>
						<div id="viewassignedmember_desc" style="display:none;" class="span16">
							<table class="detail" style="width:350px;">
								<tbody>
								<?php foreach($vm_member_details as $vm_member_detail){
									$member_loanid=$database->getCurrentLoanid($vm_member_detail['userid']);
									if(empty($member_loanid)){
										$member_url= getUserProfileUrl($vm_member_detail['userid']);
									}else{
										$member_url = getLoanprofileUrl($vm_member_detail['userid'],$member_loanid);
									}
								?>
									<tr><td width="200px;"></td><td><a href="<?php echo $member_url?>" target="_blank"><?php echo $vm_member_detail['FirstName']." ".$vm_member_detail['LastName']; ?></a></td>
									</tr>
									<tr></tr>
								<?php 
								}
								?>
								</tbody>
							</table>
						</div>
				<?php }   ?>
			<?php }?>
			</div>

	<!---		<div style="float:right">
				<h4><?php echo $lang['profile']['Partner_prof'] ?><a style='cursor:pointer' class='zz'>&nbsp<span class='tooltip'><span class='top'></span>
		<span class='middle'><?php echo $lang['profile']['tooltip_field_part'];?></span><span class='bottom'></span></span></a>&nbsp;</h4>
				<table class="detail" style="width:280px">
		<?php       if($nonactive==1){ 
					$partnername = $database->getUserNameById($partid);
					$prurl = getUserProfileUrl($partid);
					?>
						<tr>
							<td><?php echo "<a href='$prurl'>$partname </a>";?><br/><?php echo "<a href='http://$partweb'>$partweb</a>";?></td>
							<td align="right"><a href='$prurl'><img src="library/getimagenew.php?id=<?php echo $partid;?>&width=75&height=75"></a></td>
						</tr>
		<?php       }else{?>
						<tr>
							<td></td>
							<td><?php echo $act;?></td>
						</tr>
		<?php       }?>
				</table>
			</div>-->
<!--		<?php
			$repayment_instruction=$database->getRepayment_InstructionsByCountryCode($country);
			if(!empty($repayment_instruction))
			{
				echo '<h4>'.$lang['profile']['b_repayment_instructions'].'</h4>';
			?>
				<p style="text-align:justify;"><?php echo $repayment_instruction['description']; ?></p>
	<?php   }
			else
			{
				echo "No Repayment Instructions";
			}
			if($activeuser == 1)
			{
				echo "<p align='right'><a href='index.php?p=24&id=".$id."&l_id=".$activeloanid."&ref=1'>translation</a></p>";
			}
			if($desc == $data['tr_BizDesc'])
			{
				echo "<p align='right'><a id='busi_desc_org' href='javascript:void(0)'>".$lang['profile']['disp_text']."</a></p>";
				echo "<p id='busi_desc_org_desc' style='display:none;text-align:justify;'>".$data['BizDesc']."</p>";
			}
?>--->
		</div>
<?php
	}

} ?>

<script type="text/javascript">
	$('#viewassignedmember').click(function() {
		$('#viewassignedmember_desc').slideToggle("slow");
	});
	
	$('#viewprevloan').click(function() {
		$('#viewprevloan_desc').slideToggle("slow");
	});
</script>