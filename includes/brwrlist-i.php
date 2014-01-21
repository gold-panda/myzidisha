<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/scripts/brwrlist-i.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" src="extlibs/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
	$(function() {		
		$(".tablesorter_borrowers").tablesorter({sortList:[[4,1]], widgets: ['zebra']});
	});	
</script>
<script type="text/javascript">
function reAssign(reAssignVal)
{
	if(reAssignVal==0)
		return true;
	else
	{
		var val=confirm("Are you sure! you want to send verification request for declined borrower");
		if(val)
			return true;
		else
			return false;
	}
}
function mySubmit(a,id)
{
	if(a == 2){
		var val=confirm("Are you sure! you want to delete borrower");
		if(val)
			id.submit();
		else
			alert("You have decided not to delete borrower !");
	}
}
</script>
<?php 
include_once("library/session.php");
include_once("./editables/brwrlist-i.php");
include_once("./editables/admin.php");
$path=  getEditablePath('mailtext.php');
include_once("editables/".$path);
// setting variables for sorting
$ord="ASC";
$sort='FirstName';
	if($session->userlevel==PARTNER_LEVEL) {
		$sort = 'Assigned_date';
		$ord="DESC";
	}
	$ordClass="headerSortDown";
	if(isset($_GET["ord"]) && $_GET["ord"]=='DESC')
	{
	$ord='DESC';
	$ordClass="headerSortUp";
	}
	$type=1;
	if(isset($_GET["type"]))
	{
	$type=$_GET["type"];
	}
	if($type==2)
		$sort='Assigned_date';
	else if($type==3)
		$sort='completed_on';
	else if($type==4)
		$sort='country';
	else if($type==5)
		$sort='LastModified';

//gets a list of inactivated borrowers for the parners to activate
$uid=0;
if(isset($_GET['id']))
{
	$uid=$_GET['id'];
}
$cid=0;
if(isset($_GET['cid']))
{
	$cid=$_GET['cid'];
}
$did=0;
if(isset($_GET['did']))
{
	$did=$_GET['did'];
}
$UserCurrency = $database->getUserCurrency($uid);
if($database->getPartnerStatus($session->userid)==0 && $session->userlevel!=ADMIN_LEVEL )
{
	echo "<font style='color:red'>".$lang['brwrlist-i']['inactive_status']."<br /><br />";
	echo $lang['brwrlist-i']['contact_us'];
}
else if($database->getPartnerStatus($session->userid)==1 || $session->userlevel==ADMIN_LEVEL)
{
	if(!$uid || $uid==0)
	{		
		$list=$database->getInactiveBorrowers($sort, $ord, $session->userlevel, $session->userid); 
		if(empty($list))
		{
			echo $lang['brwrlist-i']['borrower_view'];
		}
		else
		{	
			foreach($list as $row){
				if($row['Created']>'1370995200'){ 
					$user_fb= $row['fb_data'];
					if(!empty($user_fb)){
						$endorser_count= $database->IsEndorsedComplete($row['userid']);
						if($endorser_count>=$database->getAdminSetting('MinEndorser')){
							$list1[]=$row;
						}
					}else{
						$list1[]= $row;
					}
				}else{
					$list1[]= $row;
				}
			}
			?>
			<p><?php echo $lang['brwrlist-i']['borrowers_activation_process']."<br/><br/>".$lang['brwrlist-i']['org']; ?></p>
			<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['brwrlist-i']['inact_borrower'] ?></h3></div>
			  <?php if($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;'href="includes/instructions.php?p=<?php echo $_GET['p']?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
			</div>
			<table class="zebra-striped tablesorter_borrowers">
				<thead>
					<tr>
					
						<th>Name</th>

						<th>Location</th>

						<th>Contacts</th>

						<th>Community Leader</th>

						<th><?php echo $lang['brwrlist-i']['completed_on']; ?></th>
						<th><?php echo $lang['brwrlist-i']['date_modified']; ?></th>
						<th><?php echo $lang['brwrlist-i']['status'] ;?></th>
			
					</tr>
				</thead>
				<tbody>
		<?php		 foreach($list1 as $rows)
					{	
						
						$userid=$rows['userid'];
						$fname=$rows['FirstName'];
						$lname=$rows['LastName'];
						$address=$rows['PAddress'];
						$city=$rows['City'];
						$country=$database->mysetCountry($rows['Country']);
						$BorrowerReports=$database->getBorrowerReports($userid);
						$emailed_on=$BorrowerReports['sent_on'];
						$replyto=$BorrowerReports['replyto'];
						$telmob=$rows['TelMobile'];
						$email=$rows['Email'];
						$income=$rows['AnnualIncome'];
						$about=$rows['About'];
						$photo=$rows['Photo'];
						$assigned_to=$rows['Assigned_to']; 
						$assigned_status=$rows['Assigned_status'];
						$completed_on = date('M d, Y', $rows['completed_on']);
						$completed_on_toSort = $rows['completed_on'];
						$status='Pending review';
						$lending_inst_officer_name = $rows['lending_inst_officer'];
						$Posted_By = $rows['postedby'];
						$rec_form_offcr_name = $rows['rec_form_offcr_name'];
						$lending_inst_phone= $rows['lending_inst_phone'];
						$rec_form_ofcr_no = $rows['rec_form_offcr_num'];
																	if($assigned_status == -2 && $assigned_to !=0)
						{
							$partner=$database->getNameById($assigned_to);
							$Assigned_date=date('M d, Y', $rows['Assigned_date']);
							$status='Sent to '.$partner.' on '.$Assigned_date;
						}else if ($assigned_status==-1) {
							$status = 'Pending Verification';
						}	
						else if($assigned_to ==0 && !empty($BorrowerReports))
						{		
								$Emailed_date=date('M d, Y',$emailed_on);
								if($rows['LastModified']>$emailed_on){ 
									$status = 'Pending Review';
								}
								else{
									$status=$replyto." emailed on ".$Emailed_date;
								}
								
						}
						else if($assigned_status ==2)
						{
							$partner=$database->getNameById($assigned_to);
							$Assigned_date=date('M d, Y', $rows['Assigned_date']);
							$status='<font color=red>Declined by '.$partner.' on '.$Assigned_date.'</font>';
							
						}
						$date=date('M d, Y', $rows['regdate']);
						$modDate = date('M d, Y', $rows['LastModified']);
						$modDateToSort = $rows['LastModified'];
					?>
						<tr>
							
							<td><?php echo $fname;?> &nbsp; <?php echo $lname;?></td>

							<td><?php echo "$country<br/>$city";?>

							<td><?php echo $email?><br/><br/><?php echo $telmob?></td> 

							<td><?php echo $rec_form_ofcr_no.'<br/><br/> '.$rec_form_offcr_name;?></td> 

							<td><span style="display:none"><?php echo $completed_on_toSort?></span>
							<?php echo $completed_on;?></td>
							
							<td><span style="display:none"><?php echo $modDateToSort?></span>
							<?php echo $modDate;?></td>
							
							<td><?php echo $status;?><br/><br/><a href='index.php?p=7&id=<?php echo $userid;?>'><?php echo $lang['brwrlist-i']['link_Activate'];?></a><br/><br/><br/>	
							</td>
						</tr>
			<?php	}	?>
				</tbody>
			</table>
<?php 	}
	}
	else if($uid > 0)
	{ 
		$assignedTo=$database->isBorrowerAssignedToThisPartner($uid, $session->userid);
		if($assignedTo)
		{	
			$result=$database->getBorrowerById($uid); 
			if(empty($result))
			{
				echo $lang['brwrlist-i']['borrower_list_tried']." ";
				echo $lang['brwrlist-i']['notify_web'];
			}
			else
			{	
				$referraldetail = $database->referraldetailByborrower($uid);
				$completersname = '';
				$completersphone = '';
				$completersemail = '';
				$completerstown = '';
				if($result['borrower_behalf_id'] > 0) {
					$behalf_detail = $database->getBorrowerbehalfdetail($result['borrower_behalf_id']);
					if(!empty($behalf_detail)) {
						$completersname = $behalf_detail['name'];
						$completersphone = $behalf_detail['contact_no'];
						$completersemail = $behalf_detail['email'];
						$completerstown = $behalf_detail['town'];
					}
				}
				$refName = '';
				$refNumber = '';
				if(!empty($referraldetail['refOfficial_name'])) {
					$refName = $referraldetail['refOfficial_name'];
				}
				if(!empty($referraldetail['refOfficial_number'])) {
					$refNumber = $referraldetail['refOfficial_number'];
				}
				$verfComment ='';
				if(!empty($referraldetail['comment'])) {
					$verfComment = $referraldetail['comment'];
				}
				$details=$result; 
				$userid=$details['userid'];
				$fname=$details['FirstName'];
				$lname=$details['LastName'];
				$name = $fname." ".$lname;
				$address=$details['PAddress'];
				$city=$details['City'];
				$country=$database->mysetCountry($details['Country']);
				$BorrowerReports=$database->getBorrowerReports($userid);
				$emailed_on=$BorrowerReports['sent_on'];
				$replyto=$BorrowerReports['replyto'];
				$telmob=$details['TelMobile'];
				$email=$details['Email'];
				$income=number_format($details['AnnualIncome'], 0, ".", "");
				$about=$details['About'];
				$photo=$details['Photo'];
				$data=$database->getBorrowerDetails($userid);
				$reffered_by=$data['reffered_by'];
				$bizdesc=$details['BizDesc'];
				$pactive=$details['PartnerId'];
				$nationId=$details['nationId'];
				$loanHist=$details['loanHist'];
				$familycont1= $details['family_member1'];
				$familycont2= $details['family_member2'];
				$familycont3= $details['family_member3'];
				$neighcont1= $details['neighbor1'];
				$neighcont2= $details['neighbor2'];
				$neighcont3= $details['neighbor3'];
				$fb_data= unserialize(base64_decode($details['fb_data']));
				$pid=$session->userid;
				$partnetId=$details['PartnerId'];
				$community_name_no=$details['communityNameNo'];
				$home_no=$details['home_location'];
				$lending_inst_name= $details['lending_inst_name'];
				$lending_inst_add= $details['lending_inst_add'];
				$lending_inst_phone= $details['lending_inst_phone'];
				$lending_inst_officer= $details['lending_inst_officer'];
				$reAssign=0;
				$assignedStatus=$details['Assigned_status'];
				$assignedTo=$details['Assigned_to'];
				$assignedDate=$details['Assigned_date'];
				$rec_form_ofcr_name = $details['rec_form_offcr_name'];
				$rec_form_ofcr_no = $details['rec_form_offcr_num'];
				if(!empty($fb_data)){
					$endorse_details= $database->getEndorserDetail($userid);
				}
				$prurl = getUserProfileUrl($userid);

				if($assignedStatus==2)
					$reAssign=1;
				$e=0;
				$m=0;
				if(($assignedStatus==1))
				{	
					$e=1;
					if($details['PartnerId']!=$pid && $pid !=ADMIN_ID)
					$m=1;
					$status = 'activated';
				}
				
				if($assignedStatus==1) {
					$status = 'Active';
				}elseif($assignedStatus==-2 && $assignedTo!=0){ 
					$partner=$database->getNameById($assignedTo);
					$Assigned_date=date('M d, Y', $assignedDate);					
					$status='Sent to '.$partner.' on '.$Assigned_date;
				}else 
					if($assignedStatus==-1) {
					$status = 'Pending Verification';
				}	
				else if($assignedTo ==0 && !empty($BorrowerReports))
				{		
						$Emailed_date=date('M d, Y',$emailed_on);
						if($details['LastModified']>$emailed_on){ 
							$status = 'Pending Review';
						}
						else{
							$status=" emailed on ".$Emailed_date;
						}
						
				}else if($assignedStatus==2) {
					$status = 'Decline';
				}elseif($assignedStatus==0) {
					$status = 'Pending Review';
				}

				?>
				<div id="b-profile">

				<?php if (file_exists(USER_IMAGE_DIR.$uid.".jpg")){ ?>
			
					<a href='<?php echo $prurl?>'><img id="b-activation" src="library/getimagenew.php?id=<?php echo $uid ?>&width=235&height=310" alt="<?php echo $name ?>"/></a>
				
				<?php } else if( ! empty($fb_data)){ //case where borrower has not uploaded own photo but has linked FB account, use FB profile
							
					echo "<img class='user-account-img' img style='max-width:200px;' src='https://graph.facebook.com/".$fb_data['user_profile']['id']."/picture?width=9999&height=9999' style='position:absolute;right:0' />";
				} ?>

					<table class='detail' style="width:430px">
						<tbody>
							<tr>
								<td style="width:280px"><strong>Applicant Name:</strong></td>
								<td><?php echo $fname. " ".$lname;?></a></td>
							</tr>


							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['Tphone'];?></strong></td>
								<td><?php echo $telmob;?></td>
							</tr>

<tr>
								<td><strong><?php echo $lang['brwrlist-i']['Email'];?></strong></td>
								<td><?php echo $email;?></td>
							</tr>
							
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['City'];?></strong></td>
								<td><?php echo $city;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['country'];?></strong></td>
								<td><?php echo $country;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['application_status'];?>:</strong></td>
								<td><?php echo $status;?></td>
							</tr>
							
							<tr height="10px"></tr>
							<tr>
								<td colspan=2><strong><?php echo $lang['brwrlist-i']['onbehalf']?>:</strong></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['bhlf_name'];?></strong></td>
								<td><?php echo $completersname;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['Location'];?>:</strong></td>
								<td><?php echo $completerstown;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['Tphone'];?></strong></td>
								<td><?php echo $completersphone;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['Email'];?></strong></td>
								<td><?php echo $completersemail;?></td>
							</tr>

						</tbody>
					</table>
					<div class="row" style="clear:both">
					<!-- Step 1 Review for Completeness section starts -->
<?php 
	$is_photo_clear= '';
	$is_desc_clear= '';
	$is_number_provided= '';
	$is_nat_id_uploaded= '';
	$is_pending_mediation= '';
	$is_rec_form_offcr_name= '';
	$is_addr_locatable= '';
	$is_addr_locatable_other= '';
	$is_photo_clear_other= '';
	$is_desc_clear_other= '';
	$is_number_provided_other= '';
	$is_nat_id_uploaded_other= '';
	$is_pending_mediation_other= '';
	$is_rec_form_offcr_name_other= '';
	$breviewDetail= $database->getBorrowerReviewDetail($userid); 
	if(!empty($breviewDetail)){
		$is_photo_clear= $breviewDetail['is_photo_clear'];
		$is_desc_clear= $breviewDetail['is_desc_clear'];
		$is_addr_locatable= $breviewDetail['is_addr_locatable'];
		$is_number_provided= $breviewDetail['is_number_provided'];
		$is_pending_mediation= $breviewDetail['is_pending_mediation'];
		if($is_photo_clear!='1' && $is_photo_clear!='0' && !empty($is_photo_clear)){
			$is_photo_clear_other= $is_photo_clear;
			$is_photo_clear= '-1';
		}
		if($is_desc_clear!='1' && $is_desc_clear!='0' && !empty($is_desc_clear)){
			$is_desc_clear_other= stripslashes($is_desc_clear);
			$is_desc_clear= '-1';
		}
		if($is_number_provided!='1' && $is_number_provided!='0' && !empty($is_number_provided)){
			$is_number_provided_other= stripslashes($is_number_provided);
			$is_number_provided= '-1';
		}
		if($is_addr_locatable!='1' && $is_addr_locatable!='0' && !empty($is_addr_locatable)){
			$is_addr_locatable_other= stripslashes($is_addr_locatable);
			$is_addr_locatable= '-1';
		}
		if($is_pending_mediation!='1' && $is_pending_mediation!='0' && !empty($is_pending_mediation)){
			$is_pending_mediation_other= stripslashes($is_pending_mediation);
			$is_pending_mediation= '-1';
		}
	}
?>
						<h3 class="subhead"><?php echo $lang['brwrlist-i']['step1']; ?></h3>
						<?php $padding_top = '0px'; ?>
												<strong>Please select a response to each of the questions below, then click "Submit Review for Completeness."</strong><br/><br/>
						<form action='process.php' method='post'>
							<table class='detail'>
								<tbody>
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">
											1.
										</td>

										<td><?php echo $lang['brwrlist-i']['is_photo_clear']; ?></td>


																			</tr>
									<tr height='10px'></tr>
									<tr>
										<td></td>
										<td>
											<input type='radio' name='is_photo_clear' id='is_photo_clear_yes' value='1' <?php 	if($is_photo_clear== '1') echo "checked";?> onclick="showtext(this.value, 'is_photo_clear_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
											<input type='radio' name='is_photo_clear' id='is_photo_clear_no' value='0' <?php 	if($is_photo_clear== '0') echo "checked";?> onclick="showtext(this.value, 'is_photo_clear_other_text')">No
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<table class='detail'>
												<tr>
													<td ><input type='radio' name='is_photo_clear' id='is_photo_clear_other' value='-1' <?php 	if($is_photo_clear== '-1') echo "checked";?> onclick="showtext(this.value, 'is_photo_clear_other_text')"><span >Other (Please explain):</span></td>
													<td>
														<textarea name="is_photo_clear_other" id="is_photo_clear_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_photo_clear== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_photo_clear_other; ?></textarea>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr height='15px'></tr>
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">
											2.
										</td>
										<td width='450px'>
											<?php echo $lang['brwrlist-i']['is_desc_clear']; ?>
										</td>
									</tr>
									 <tr>
										<td></td>
										
										<td><?php

											echo "<p><i>How did you hear about Zidisha?  </i>".$reffered_by; 
											echo "<p><i>About Me:  </i>".$about;
											echo"</p>";
											echo "<p><i>About My Business:  </i>".$bizdesc;
											echo"</p>";
													
										?></td>
										
									</tr>
									<tr height='10px'></tr>
									<tr>
										<td></td>
										<td>
											<input type='radio' name='is_desc_clear' id='is_desc_clear_yes' value='1' <?php 	if($is_desc_clear== '1') echo "checked";?> onclick="showtext(this.value, 'is_desc_clear_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
											<input type='radio' name='is_desc_clear' id='is_desc_clear_no' value='0' <?php 	if($is_desc_clear== '0') echo "checked";?> onclick="showtext(this.value, 'is_desc_clear_other_text')">No
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<table class='detail'>
												<tr>
													<td ><input type='radio' name='is_desc_clear' id='is_desc_clear_other' value='-1' <?php 	if($is_desc_clear== '-1') echo "checked";?> onclick="showtext(this.value, 'is_desc_clear_other_text')"><span >Other (Please explain):</span></td>
													<td>
														<textarea name="is_desc_clear_other" id="is_desc_clear_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_desc_clear== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_desc_clear_other; ?></textarea>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr height='15px'></tr>
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">
											3.
										</td>
										<td>
											<?php echo $lang['brwrlist-i']['is_addr_locatable']; ?>
										</td>
									</tr>
									 <tr>
										<td></td>
										<td><?php 
											echo "<br/>";
											echo $address;
											echo"<br/>";
											echo $home_no;
											echo"<br/>";
											echo$city." ".$country;
													
										?></td>
										
									</tr> 
									<tr height='10px'></tr>
									<tr>
										<td></td>
										<td>
											<input type='radio' name='is_addr_locatable' id='is_addr_locatable_yes' value='1' <?php 	if($is_addr_locatable== '1') echo "checked";?> onclick="showtext(this.value, 'is_addr_locatable_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
											<input type='radio' name='is_addr_locatable' id='is_addr_locatable_no' value='0' <?php 	if($is_addr_locatable== '0') echo "checked";?> onclick="showtext(this.value, 'is_addr_locatable_other_text')">No
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<table class='detail'>
												<tr>
													<td ><input type='radio' name='is_addr_locatable' id='is_addr_locatable_other' value='-1' <?php 	if($is_addr_locatable== '-1') echo "checked";?> onclick="showtext(this.value, 'is_addr_locatable_other_text')"><span >Other (Please explain):</span></td>
													<td>
														<textarea name="is_addr_locatable_other" id="is_addr_locatable_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_addr_locatable== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_addr_locatable_other; ?></textarea>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">4.</td>

										<td><?php  echo $lang['brwrlist-i']['is_number_provided']; ?></td>
									</tr>
									<tr height='15px'></tr>
									<tr>
										<td></td>
																							<td>
														<strong>
															<?php echo $lang['brwrlist-i']['familycont1'];?>:  
														</strong>
				<?php echo $familycont1;?>
													</td>
												</tr>
												<tr>	
													<td></td>
		<td>
														<strong>
															<?php echo $lang['brwrlist-i']['familycont2'];?>:  
														</strong>
				<?php echo $familycont2;?>
													</td>
												</tr>
												<tr>	
													<td></td>
		<td>
														<strong>
															<?php echo $lang['brwrlist-i']['familycont3'];?>:  
														</strong>
				<?php echo $familycont3;?>
													</td>
												</tr>
												<tr>	
													<td></td>
		<td>
														<strong>
															<?php echo $lang['brwrlist-i']['neighcont1'];?>:  
														</strong>
				<?php echo $neighcont1;?>
													</td>
												</tr>
												<tr>	
													<td></td>
		<td>
														<strong>
															<?php echo $lang['brwrlist-i']['neighcont2'];?>:  
														</strong>
				<?php echo $neighcont2;?>
													</td>
												</tr>
												<tr>	
													<td></td>
		<td>
														<strong>
															<?php echo $lang['brwrlist-i']['neighcont3'];?>:  
														</strong>
				<?php echo $neighcont3;?>
													</td>


			</td>
		</tr>

										
									</tr>
								<tr>
									<td></td>
									<td colspan=2> <br/><br/>

														<input type='radio' name='is_number_provided' id='is_number_provided_yes' value='1' <?php 	if($is_number_provided== '1') echo "checked";?> onclick="showtext(this.value, 'is_number_provided_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
														<input type='radio' name='is_number_provided' id='is_number_provided_no' value='0' <?php 	if($is_number_provided== '0') echo "checked";?> onclick="showtext(this.value, 'is_number_provided_other_text')">No
													</td>
													<td>
													</td>
												</tr>

										<tr>
										<td></td>
									
										<td>
											<table class='detail'>
												<tr>
													<td ><input type='radio' name='is_number_provided' id='is_number_provided_other' value='-1' <?php 	if($is_number_provided== '-1') echo "checked";?> onclick="showtext(this.value, 'is_number_provided_other_text')"><span >Other (Please explain):</span></td>
													<td>
														<textarea name="is_number_provided_other" id="is_number_provided_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_number_provided== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_number_provided_other; ?></textarea>
													</td>
												</tr>
											</table>

									<tr height='15px'></tr>
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">5.</td>
										<td>
Next, review the following list of all applicants and members whose recommending Community Leader has the same phone number as this applicant's Community Leader.  If any members recommended by this Community Leader are <strong>in arrears</strong>, use the email form below to inform the applicant that all members recommended by the leader must be current with loan repayments before we can accept the recommendation.<br/><br/>

	<table class="zebra-striped tablesorter_pending_borrowers">
	<thead>
		<tr>
		<th>Member Name</th>
		<th>Community Leader Name</th>
		<th>Community Leader Telephone</th>
		<th>Member Status</th>
		<th>Repayment Report Notes</th>
		</tr>
	</thead>
	<tbody>

	<?php 

if (!empty($rec_form_ofcr_no)) {

	$cl_borrowers=$database->getCLBorrowers($rec_form_ofcr_no);

	foreach ($cl_borrowers as $row) {

		$clborrowerid = $row['userid'];
		$prurl = getUserProfileUrl($clborrowerid);
		$firstname = $row['FirstName'];
		$lastname = $row['LastName'];
		$clname = $row['rec_form_offcr_name'];
		$brwrandLoandetail = $database->getBrwrAndLoanStatus($clborrowerid);
		$defaultLoanid=$database->getDefaultedLoanid($clborrowerid);
		$assignedStatus=$row['Assigned_status'];
		if($assignedStatus==1) {
			$activationdate=$database->getborrowerActivatedDate($clborrowerid);

			if(!empty($activationdate)){
				$activate_date=date('M d, Y', $activationdate);
			}else{
				$activate_date='';
			}
				if($brwrandLoandetail['overdue'] > 0 || $defaultLoanid) {
					$status1 = '<font color="red">Loan in Arrears</font>';
				}else{
					$status1 = "Not in Arrears";
				}		

			$status = "<span style='display:none'>$activationdate</span>Activated on $activate_date";
														}
		else if($assignedStatus==-1) {
			$status = 'Pending Verification';
			$status1 = "";
		}	
		else if($assignedStatus==2) {
			$status = '<font color="red">Declined</font>';
			$status1 = "";
		}
		elseif($assignedStatus==0) {
			$status = 'Pending Review';
			$status1 = "";
		}
		else {
			$status = '';
			$status1 = "";
		}
		$link='index.php?p=7&id='.$clborrowerid;
		$loanid=$brwrandLoandetail['loanid'];
		$repaysched= 'index.php?p=37&l='.$loanid.'&u='.$clborrowerid;
		$dynamic_data= $database->getRepayDynamicData($clborrowerid);                                                                                                      
                $note = stripslashes($dynamic_data['note']);


	?>
	<tr>
		<td>
		<?php echo "<a href='$prurl'>$firstname $lastname</a>"; ?>
		</td>
		<td>
		<?php echo $clname; ?>
		</td>
		<td>
		<?php echo $rec_form_ofcr_no; ?>
		</td>
		<td>
		<?php echo "<a href='$link'>$status</a>"; ?><br/>
		<?php echo "<a href='$repaysched'>$status1</a>"; ?>
		</td>
		<td>
		<?php echo $note; ?>
		</td>

	<?php	} 
} ?>
	
	</tbody>
	</table>
<br/><br/>

<?php echo $lang['brwrlist-i']['is_pending_mediation']; ?></td>


			</td>
		</tr>

										
									</tr>
								<tr>
									<td></td>
									<td colspan=2> <br/><br/>
										<input type='radio' name='is_pending_mediation' id='is_pending_mediation_yes' value='1' <?php 	if($is_pending_mediation== '1') echo "checked";?> onclick="showtext(this.value, 'is_pending_mediation_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
										<input type='radio' name='is_pending_mediation' id='is_pending_mediation_no' value='0' <?php 	if($is_pending_mediation== '0') echo "checked";?> onclick="showtext(this.value, 'is_pending_mediation_other_text')">No
									</td>
								</tr>
								<tr>
										<td></td>
										<td>
											<table class='detail'>
												<tr>
													<td ><input type='radio' name='is_pending_mediation' id='is_pending_mediation_other' value='-1' <?php 	if($is_pending_mediation== '-1') echo "checked";?> onclick="showtext(this.value, 'is_pending_mediation_other_text')"><span >Other (Please explain):</span></td>
													<td>
														<textarea name="is_pending_mediation_other" id="is_pending_mediation_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_pending_mediation== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_pending_mediation_other; ?></textarea>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr height='15px'></tr>

<!-- commented out national ID check 11 Jan 2014

									<?php if(!empty($details['frontNationalId'])) { ?>
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">7.</td>
										<td>Please download the national identity card copy below, and ensure that it is legible and matches the applicant's name.<br/>
Has the applicant uploaded a legible copy of a government-issued identity card that matches his or her name?</td>
									</tr>
									<tr><td></td></tr>
									<?php }
									if(!empty($details['frontNationalId'])) { ?>
									<tr>
										<td></td>
										<td colspan=2><strong><a href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=frontNationalId'; ?>" onclick="needToConfirm = false;">
											<?php echo $lang['brwrlist-i']['dwn_front_nation_id'];?></a></strong>
										</td>
									</tr>
									<?php }if(!empty($details['backNationalId'])) {?>
									<tr>
										<td></td>
										<td colspan=2><strong><a href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=backNationalId'; ?>" onclick="needToConfirm = false;">
											<?php echo $lang['brwrlist-i']['dwn_back_nation_id'];?></a></strong>
<?php } ?>

<br/><br/>

</td>
										
									</tr> 
									<tr height='10px'></tr>

									<tr style="<?php if (!empty($details['frontNationalId']))echo "display:''"; else echo "display:none"; ?>" >
										<td></td>
										<td>
											<input type='radio' name='is_nat_id_uploaded' value='1' <?php 	if($is_nat_id_uploaded== '1' || empty($details['frontNationalId'])) echo "checked";?> onclick="showtext(this.value, 'is_nat_id_uploaded_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
											<input type='radio' name='is_nat_id_uploaded' id='is_photo_clear_no' value='0' <?php 	if($is_nat_id_uploaded== '0' && !empty($details['frontNationalId'])) echo "checked";?> onclick="showtext(this.value, 'is_nat_id_uploaded_other_text')">No
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<table class='detail'>
												<tr>
													<td ><input type='radio' name='is_nat_id_uploaded' id='is_nat_id_uploaded_other' value='-1' <?php 	if($is_nat_id_uploaded== '-1' && !empty($details['frontNationalId'])) echo "checked";?> onclick="showtext(this.value, 'is_nat_id_uploaded_other_text')"><span >Other (Please explain):</span></td>
													<td>
														<textarea name="is_nat_id_uploaded_other" id="is_nat_id_uploaded_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_nat_id_uploaded== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_nat_id_uploaded_other; ?></textarea>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									
									<tr height='15px'></tr>
									</table>

end commented out national ID check 11 Jan 2014 -->



								<tr height='20px'></tr>
									<tr>
										<td></td>
										<td>
							<?php		if($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL){?>
											<input type="hidden" name="borrowerid" value='<?php echo $userid?>' />
											<input type="hidden" name="review_borrower" />
											<input type="hidden" name="user_guess" value="<?php echo generateToken('review_borrower'); ?>"/>
											<input type="hidden" name="review_borrower" />
											<input type='submit' name='review_complete' value='Submit Review for Completeness' onclick="needToConfirm = false;" class='btn'>
							<?php			} ?>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
						<?php	$isreviewcomplt = $database->is_borrowerReviewComplete($userid);
								$breviewcomplt= $database->BReviewComplt($userid); 
							if($breviewcomplt) { ?>
							<div style='text-align:center;font-size:16px;color:green;'  id="review_message"><strong>Step 1: Review for Completeness is complete. An Admin or Partner will now proceed to Step 2: Verification.</strong></div><br/>	
						<?php } ?>
						<?php if(isset($_SESSION['review_not_complete'])) { ?>
							<div style='text-align:center;font-size:16px;color:green;'  id="review_message"><strong>Please use the form below to contact the applicant and request the missing information.</strong></div><br/><br/>
						<?php } ?>
						<?php	
							
						if(($breviewcomplt) && ($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL)) {
							$partners=$database->getAllActivePartners();
							?>
						<table> 
							<tr>
								<td>
									If this applicant is located in Burkina Faso or Niger, please assign Step 2: Verification to a partner here.   
								</td>
								<td>
									<form action="process.php" method="post" onsubmit="return reAssign(<?php echo $reAssign ?>)">
													<select name="partnerid" id="partnerid">
														<option value="0">Select Partner</option>
														<?php for($i=0; $i<count($partners); $i++){ 
													$ulevel = $database->getUserLevelbyid($partners[$i]['userid']);
														if($ulevel==LENDER_LEVEL || $ulevel==ADMIN_LEVEL) {
																	continue;
															}

														?>
														<option value="<?php echo $partners[$i]['userid']?>"><?php echo $partners[$i]['name']?></option>
													<?php } ?>
													</select>
													<?php 
													echo $form->error("partnerid".$userid) ?><br/><br/>
													<input class='btn' type="Submit" value="Assign">
													<input type="hidden" name="borrowerid" value="<?php echo $userid?>">
													<input type="hidden" name="assignedPartner">
													<input type="hidden" name="user_guess" value="<?php echo generateToken('assignedPartner'); ?>"/>
									</form>
								</td>
							</tr>
						</table>
						<br/><br/>
			<?php } $assign_status= $database->getBAssignStatus($userid);
					if($assign_status['Assigned_status']==-2 && $assign_status['Assigned_to']!='5023' && ($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL)){ 
						$name= $database->getPartnerSelfName($assign_status['Assigned_to']);?>
						<div style='text-align:center;font-size:16px;'><strong>This application has been sent to <?php echo $name;?> for verification.</strong></div><br/>
			<?php	} if(!empty($isreviewcomplt)){?>
						<h3 class="subhead">Email Applicant</h3>
								<?php $borrowerReport=$database->getBorrowerAllReports($userid); 
								
							if(!empty($borrowerReport)){
								foreach($borrowerReport as $breport) {
									?>
										<table class="newdetail" cellspacing="0" cellpadding="8">
										<tr>
											<td width="100px" class="newdetail_text">Recipient Email:</td><td><?php echo $breport['recipient']?></td>
										</tr>
										<tr>
											<td class="newdetail_text">CC Emails:</td><td>
											<?php 
														$ccEmails=$breport['cc'];
														echo str_replace( ',',' , ',$ccEmails);
												?>
											</td>
										</tr>
										<tr>
											<td class="newdetail_text">Reply-To:</td><td><?php echo $breport['replyto']?></td>
										</tr>
										<tr>
											<td class="newdetail_text">Subject :</td><td><?php echo nl2br($breport['subject']) ?></td>
										</tr>
										<tr>
											<td  class="newdetail_text">Message :</td><td><?php echo nl2br($breport['message'])?></td>
										</tr>
										<tr>
											<td  class="newdetail_text">Sent date :</td><td><?php echo date('M d , Y',$breport['sent_on'])?></td>
										</tr>
										</table>


									<?php }
									}
									?>
									<?php 
									$breview = $database->is_borrowerReviewComplete($userid);
									$emailApplicantBody = ''; 
									$emailBody='';
									if(!empty($breview)) {
										
										if($breview['is_photo_clear']==0) {
											$emailApplicantBody.=$lang['mailtext']['if_photo_missing-msg']."<br/>";
											
										}
										if($breview['is_desc_clear']==0) {
										$emailApplicantBody.=$lang['mailtext']['if_is_desc_clear-msg']."<br/>";

										}
										if($breview['is_addr_locatable']==0){
											$emailApplicantBody.= $lang['mailtext']['if_addrs_missing-msg']."<br/>";
										}
										if($breview['is_number_provided']==0){
											$emailApplicantBody.= $lang['mailtext']['if_tel_num_missing-msg']."<br/>";
										}
										if($breview['is_pending_mediation']==0){
											$emailApplicantBody.= $lang['mailtext']['if_pending_mediation-msg']."<br/>";
										}
/*										
*/
									$bname = trim($fname." ".$lname);
									$formSender = $form->value("sendername");
									$breviewSendername = '';
									if(!empty($formSender)) {
										$breviewSendername = $formSender;
									}
									$params['bname'] = $bname;
									$params['reviewmsgbody'] = $emailApplicantBody;
									$emailBody = $session->formMessage($lang['mailtext']['breview-msg'], $params);
									}
									$formemailmsg = $form->value("emailmessage");
									if(!empty($formemailmsg)) {
										$emailBody = $formemailmsg;
									}
									$formReplyto = $form->value("replyTo");
									$reply_to = SERVICE_EMAIL_ADDR;
									if(!empty($formReplyto)) {
										$reply_to = $formReplyto;
									}
									$formCC = $form->value("ccaddress");
									$breviewCC = '';
									if(!empty($formCC)) {
										$breviewCC = $formCC;
									}else if(!empty($completersemail)) {
										$breviewCC = $completersemail;
									}
									$formSubject = $form->value("emailsubject");
									$reviewSbj = 'Your Zidisha Application';
									if(!empty($formSubject)) {
										$reviewSbj = $formSubject;
									}
									

								?>	
								<form method="post" action="process.php">
									<table class='detail' >
										<tbody>
											<tr><td>Recipient Email:<br /></td></tr>
											<tr>
												<td><?php $emailAdrrErr=$form->value("emailaddress");
														?>
													<input type="text" name="emailaddress" style="width:350px;" value="<?php 
															if(!empty($emailAdrrErr))
																echo $emailAdrrErr;
															else
																echo $email;
															?>"/><br/>
													<?php echo $form->error("emailaddress"); ?>
												</td>
											</tr>
											<tr><td></td></tr>
												<tr><td>CC:<br /></td></tr>
											<tr>
												<td>
													<input type="text" name="ccaddress" style="width:350px;" value="<?php  echo $breviewCC ?>"/><br/>
													<?php echo $form->error("ccaddress"); ?>
												</td>
											</tr>
											<tr><td></td></tr>
											<tr><td>Reply To:<br /></td></tr>
											<tr>
												<td>
													<input type="text" name="replyTo" style="width:350px;" value="<?php echo $reply_to?>"/><br/>
													
													<?php 
														echo $form->error("replyTo"); ?>
												</td>
											</tr>
											<tr><td></td></tr>
											<tr><td>Subject:<br /></td></tr>
											<tr>
												<td>
													<input type="text" name="emailsubject" style="width:350px;" value="<?php echo $reviewSbj; ?>"/>
													<input type="hidden" name="emailedTo" value='1' />
													<input type="hidden" name="borrowerid" value="<?php echo $userid?>">
													<input type="hidden" name="user_guess" value="<?php echo generateToken('emailedTo'); ?>"/><br/>
													<?php echo $form->error("emailsubject"); ?>
												</td>
											</tr>
											<tr><td></td></tr>
											<tr><td>Default Message:  <br />(Please modify the default text as necessary to indicate what is needed to complete the application, and add your name to the signature line. You may change the language in the upper right of the screen to display the default message in French or Indonesian.)<br /></td></tr>
											<tr>
												<td>
													<textarea id="emailmessage" name="emailmessage" style="width:352px;height:170px;"><?php echo strip_tags($emailBody);
													?></textarea><br/>
													<?php echo $form->error("emailmessage"); ?>
												</td>
											</tr>
											<tr><td></td></tr>
											<tr><td>Enter Your Name Here:</td></tr>
											<tr>
												<td><a id="sendername"></a>
													<input type="text" name="sendername" style="width:350px;" value="<?php echo $session->fullname; ?>"/><br/>
													<?php echo $form->error("sendername"); ?>
												</td>
											</tr>
											<tr><td><input class='btn' type="submit" name="Send" value="<?php echo $lang['admin']['send']; ?>"/></td></tr>
										</tbody>
									</table>
								</form>
				<?php unset($_SESSION['review_not_complete']); } ?>
							<!-- Step 1 Review for Completeness section ends -->

							<!-- Step 2 verification section starts -->
							<?php 


								include('bverification.php');

							}
							?>
							<!-- Step 2 verification section ends -->		

		<?php		
		}
		else
		{
			echo $lang['brwrlist-i']['borrower_list_tried']." ".$lang['brwrlist-i']['notify_web'];
		}
	}

}
?>
	<script type="text/javascript">
	
function tablesort(type)
	{
		<?php 
			$type =0;
			if(isset($_GET['type'])) 
				$type =$_GET['type'];
		?>
		if(type ==  <?php echo $type ?>){		
			if('ASC'=='<?php echo $ord; ?>'){
				window.location = 'index.php?p=7&type='+type+'&ord=DESC';
			}
			else{
				window.location = 'index.php?p=7&type='+type+'&ord=ASC';
			}		
		}else{
				window.location = 'index.php?p=7&type='+type+'&ord=ASC';
		}
	}
	
	</script>
<script language="JavaScript">
  var ids = new Array('is_photo_clear_yes','is_photo_clear_no', 'is_photo_clear_other', 'is_addr_locatable_yes','is_addr_locatable_no','is_addr_locatable_other','is_pending_mediation_yes','is_pending_mediation_no','is_pending_mediation_other', 'is_rec_form_offcr_name_yes','is_rec_form_offcr_name_no','is_rec_form_offcr_name_other','is_adrs_verfd_by_brwr_yes','is_adrs_verfd_by_brwr_no','is_adrs_verfd_by_brwr_other','is_adrs_verfd_by_brwr_other_text','is_witness_locatable_yes','is_witness_locatable_no','is_witness_locatable_other','is_witness_locatable_other_text','is_loan_addr_locatable_yes','is_loan_addr_locatable_no','is_loan_addr_locatable_other','is_loan_addr_locatable_other_text','is_neigh_addr_locatable_yes','is_neigh_addr_locatable_no','is_neigh_addr_locatable_other','is_neigh_addr_locatable_other_text','is_loan_history_yes','is_loan_history_no','is_loan_history_other','is_loan_history_other_text','lastloanamount','is_repaid_on_full_yes','is_repaid_on_full_no','is_repaid_on_full_other','is_repaid_on_full_other_text','is_repaid_on_time_yes','is_repaid_on_time_no','is_repaid_on_time_other','is_repaid_on_time_other_text','is_recomnd_addr_locatable_yes','is_recomnd_addr_locatable_no','is_recomnd_addr_locatable_other','is_recomnd_addr_locatable_other_text','is_recomnd_sign_yes','is_recomnd_sign_no','is_recomnd_sign_other','is_recomnd_sign_other_text');
  var values = new Array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
  var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>
<script type="text/javascript">

	function showtext(value, str) { 
		if(value=='-1'){
			document.getElementById(str).style.display ='';
		}
		else{
			document.getElementById(str).style.display ='none';
		}
	}


</script>