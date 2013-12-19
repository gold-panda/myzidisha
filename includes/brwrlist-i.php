<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/scripts/brwrlist-i.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" src="extlibs/tiny_mce/tiny_mce.js"></script>
<!-- <script type="text/javascript">
	// Default skin
	tinyMCE.init({
		// General options
	mode : "exact",
		elements : "emailmessage",
		theme : "advanced",
        skin : "o2k7",
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|forecolor,backcolor",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
		// Theme options
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

	});
</script> -->
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
if($database->getPartnerStatus($session->userid)==0 && $session->userlevel!=LENDER_LEVEL && $session->userlevel!=ADMIN_LEVEL )
{
	echo "<font style='color:red'>".$lang['brwrlist-i']['inactive_status']."<br /><br />";
	echo $lang['brwrlist-i']['contact_us'];
}
else if($database->getPartnerStatus($session->userid)==1 || $session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL)
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
					<!-- // Anupam 9-Jan-2013 commented photo column  bug# 219 -->
						<!-- <th><?php echo "Borrower"; ?></th> -->
					<!--	<th><?php echo $lang['brwrlist-i']['Location']; ?></th>-->
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
							<!-- // Anupam 9-Jan-2013 commented photo column  bug# 219 -->
							<!-- <td><div align='center'><a href='<?php echo $prurl?>'><img src='library/getimage.php?id=<?php echo $userid;?>&width=100&height=100' border=0/></a></div></td> -->
							<!--<td style='width:40%'><?php echo $about;?></td>-->
														<td><?php echo $fname;?> &nbsp; <?php echo $lname;?></td>

<td><?php echo "$country<br/>$city";?>

<td><?php echo $email?><br/><br/><?php echo $telmob?></td> 

<td><?php echo $rec_form_ofcr_no.'<br/><br/> '.$rec_form_offcr_name;?></td> 

							<td><span style="display:none"><?php echo $completed_on_toSort?></span>
							<?php echo $completed_on;?></td>
							<td><span style="display:none"><?php echo $modDateToSort?></span>
							<?php echo $modDate;?></td>
							<td><?php echo $status;?><br/><br/><a href='index.php?p=7&id=<?php echo $userid;?>'><?php echo $lang['brwrlist-i']['link_Activate'];?></a><br/><br/><br/>
				<?php			if($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL)
								{
									$delete_btn1="<form name='del".$userid."' id='".$userid."' method='post' action='process.php'>".
									"<input name='deleteBorrower' type='hidden' />".
									"<input type='hidden' name='user_guess' value='".generateToken('deleteBorrower')."'/>".
									"<input name='borrowerid' value='$userid' type='hidden' />".
									"<input name='inactiveBorrower' type='hidden' />".
									"<a href='javascript:void(0)' style='color:red' onclick='javascript:mySubmit(2,del$userid);'>".$lang['admin']['delete_button']."</a>".
									"</form>";
						?>
								
									<?php echo $delete_btn1 ?>
						<?php	}	?>
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
				// 18-Jan-2013 Anupam check if borrower have inactive
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
					<a href='<?php echo $prurl?>'><img id="b-activation" src="library/getimagenew.php?id=<?php echo $uid ?>&width=235&height=310" alt="<?php echo $name ?>"/></a>
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

							<!-- 
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['nationId'];?></strong></td>
								<td><?php echo $nationId;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['annual_income'];?></strong></td>
								<td><?php echo $UserCurrency." ".$income;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['community_name_no'];?></strong></td>
								<td><?php echo nl2br($community_name_no);?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['officialName'];?></strong></td>
								<td><?php echo $refName;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['officialNumber'];?></strong></td>
								<td><?php echo $refNumber;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['verifctnComment'];?></strong></td>
								<td><?php echo nl2br($verfComment);?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['lending_inst_name'];?></strong></td>
								<td><?php echo $lending_inst_name;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['lending_inst_add'];?></strong></td>
								<td><?php echo $lending_inst_add;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['lending_inst_phone'];?></strong></td>
								<td><?php echo $lending_inst_phone;?></td>
							</tr>
							<tr>
								<td><strong><?php echo $lang['brwrlist-i']['lending_inst_officer'];?></strong></td>
								<td><?php echo $lending_inst_officer;?></td>
							</tr> -->
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
					<!-- Step 1 Review for Completeness section starts--->
<?php 
	$is_photo_clear= '';
	$is_desc_clear= '';
	$is_number_provided= '';
	$is_nat_id_uploaded= '';
	$is_rec_form_uploaded= '';
	$is_pending_mediation= '';
	$is_rec_form_offcr_name= '';
	$is_addr_locatable= '';
	$is_addr_locatable_other= '';
	$is_photo_clear_other= '';
	$is_desc_clear_other= '';
	$is_number_provided_other= '';
	$is_nat_id_uploaded_other= '';
	$is_rec_form_uploaded_other= '';
	$is_pending_mediation_other= '';
	$is_rec_form_offcr_name_other= '';
	$breviewDetail= $database->getBorrowerReviewDetail($userid); 
	if(!empty($breviewDetail)){
		$is_photo_clear= $breviewDetail['is_photo_clear'];
		$is_desc_clear= $breviewDetail['is_desc_clear'];
		$is_addr_locatable= $breviewDetail['is_addr_locatable'];
		$is_number_provided= $breviewDetail['is_number_provided'];
		$is_nat_id_uploaded= $breviewDetail['is_nat_id_uploaded'];
		$is_rec_form_uploaded= $breviewDetail['is_rec_form_uploaded'];
		$is_pending_mediation= $breviewDetail['is_pending_mediation'];
		$is_rec_form_offcr_name= $breviewDetail['is_rec_form_offcr_name'];
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
		if($is_nat_id_uploaded!='1' && $is_nat_id_uploaded!='0' && !empty($is_nat_id_uploaded)){
			$is_nat_id_uploaded_other= stripslashes($is_nat_id_uploaded);
			$is_nat_id_uploaded= '-1';
		}
		if($is_rec_form_uploaded!='1' && $is_rec_form_uploaded!='0' && !empty($is_rec_form_uploaded)){
			$is_rec_form_uploaded_other= stripslashes($is_rec_form_uploaded);
			$is_rec_form_uploaded= '-1';
		}
		if($is_pending_mediation!='1' && $is_pending_mediation!='0' && !empty($is_pending_mediation)){
			$is_pending_mediation_other= stripslashes($is_pending_mediation);
			$is_pending_mediation= '-1';
		}
		if($is_rec_form_offcr_name!='1' && $is_rec_form_offcr_name!='0' && !empty($is_rec_form_offcr_name)){
			$is_rec_form_offcr_name_other= stripslashes($is_rec_form_offcr_name);
			$is_rec_form_offcr_name= '-1';
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
										<!-- <td><strong><?php echo $lang['brwrlist-i']['PostAddress'];?></strong></td> -->
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
										<!-- <td><strong><?php echo $lang['brwrlist-i']['PostAddress'];?></strong></td> -->
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
									<tr height='15px'></tr>


									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">4.</td>
										<td>Please download the national identity card copy below, and ensure that it is legible and matches the applicant's name.<br/>
Has the applicant uploaded a legible copy of a government-issued identity card that matches his or her name?</td>
									</tr>
									<tr><td></td></tr>
									<?php if(!empty($details['frontNationalId'])) { ?>
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
									<tr>
										<td></td>
										<td>
											<input type='radio' name='is_nat_id_uploaded' value='1' <?php 	if($is_nat_id_uploaded== '1') echo "checked";?> onclick="showtext(this.value, 'is_nat_id_uploaded_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
											<input type='radio' name='is_nat_id_uploaded' id='is_photo_clear_no' value='0' <?php 	if($is_nat_id_uploaded== '0') echo "checked";?> onclick="showtext(this.value, 'is_nat_id_uploaded_other_text')">No
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<table class='detail'>
												<tr>
													<td ><input type='radio' name='is_nat_id_uploaded' id='is_nat_id_uploaded_other' value='-1' <?php 	if($is_nat_id_uploaded== '-1') echo "checked";?> onclick="showtext(this.value, 'is_nat_id_uploaded_other_text')"><span >Other (Please explain):</span></td>
													<td>
														<textarea name="is_nat_id_uploaded_other" id="is_nat_id_uploaded_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_nat_id_uploaded== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_nat_id_uploaded_other; ?></textarea>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									
									<tr height='15px'></tr>
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">5.</td>

<td>

<?php if(!empty($details['addressProof'])) {?>

		Open the Recommendation Form submitted by <?php  echo trim($fname." ".$lname)?>: 	<strong><a href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=addressProof'; ?>" onclick="needToConfirm = false;">Open Recommendation Form</a></strong><br/><br/>

		Confirm that the Recommendation Form is <strong>signed and stamped by the leader</strong> of a school, a religious institution or social organization. Examples of leaders who meet this criteria are the headmaster of a primary or secondary school, president of a university, pastor, priest, imam, director of a registered NGO, president of a registered cooperative or association, or the director of a large company. Recommendation forms may not be completed by government officials, or by private individuals who are not the leaders of recognized community institutions.<br/><br/>

		<?php echo $lang['brwrlist-i']['is_rec_form_uploaded']; ?>

<?php } else { ?>

		Next, use the link below to check the Facebook account information submitted.  Make sure the Facebook account link is still valid and the information appears consistent with that in the application.  Note that using a nickname in Facebook is not by itself grounds for ineligibility, but the gender and general biographical information should match.<br/><br/>

<table class='detail'>
												<?php if(!empty($fb_data)){
													if(isset($fb_data['user_friends']['data'])){
														$no_of_friends= count($fb_data['user_friends']['data']);
													}else{
														$no_of_friends= count($fb_data['user_friends']);
													}
												} ?>
												<tr>
												<td><?php echo $lang['brwrlist-i']['bhlf_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fb_data['user_profile']['name'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<?php echo $lang['brwrlist-i']['fb_friends'];?>&nbsp;&nbsp;
												<?php echo $no_of_friends; ?><br/><br/>
												<a href="<?php echo 'index.php?p=91&userid='.$userid; ?>"><?php echo $lang['brwrlist-i']['view_fb_data']?></a></td>

	</tr>
</table>							
												Is the Facebook information valid and consistent with the application?			

<?php } ?>

								</td>
										
									</tr>
									<tr>
									<td></td>
									<td colspan=2> <br/><br/>
										<input type='radio' name='is_rec_form_uploaded' id='is_rec_form_uploaded_yes' value='1' <?php 	if($is_rec_form_uploaded== '1') echo "checked";?> onclick="showtext(this.value, 'is_rec_form_uploaded_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
										<input type='radio' name='is_rec_form_uploaded' id='is_rec_form_uploaded_no' value='0' <?php 	if($is_rec_form_uploaded== '0') echo "checked";?> onclick="showtext(this.value, 'is_rec_form_uploaded_other_text')">No
									</td>
								</tr>
								<tr>
										<td></td>
										<td>
											<table class='detail'>
												<tr>
													<td ><input type='radio' name='is_rec_form_uploaded' id='is_rec_form_uploaded_other' value='-1' <?php 	if($is_rec_form_uploaded== '-1') echo "checked";?> onclick="showtext(this.value, 'is_rec_form_uploaded_other_text')"><span >Other (Please explain):</span></td>
													<td>
														<textarea name="is_rec_form_uploaded_other" id="is_rec_form_uploaded_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_rec_form_uploaded== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_rec_form_uploaded_other; ?></textarea>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								
									<tr height='15px'></tr>
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">6.</td>
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
									<tr>
										<td width="30px" style="text-align:right; vertical-align: top;<?php echo $padding_top?>">7.</td>

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
										

<!-- commented out loan contract check 5 Dec 2013

										<td><?php echo $lang['brwrlist-i']['is_rec_form_offcr_name']; ?></td>
										
									</tr>
							<?php if(!empty($details['legalDeclaration'])) {?>
							<tr>
								<td></td>
								<td colspan=2><strong><a href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=legalDeclaration'; ?>" onclick="needToConfirm = false;"><?php echo $lang['brwrlist-i']['dwn_legal_dec'];?></a></strong></td>
							</tr>
							<?php } if(!empty($details['legal_declaration2'])) {?>
							<tr>
								<td></td>
								<td colspan=2>
									<strong><a href="<?php echo SITE_URL.'download.php?u='.$userid.'&doc=legal_declaration2'; ?>" onclick="needToConfirm = false;"><?php echo $lang['brwrlist-i']['dwn_legal2_dec'];?></a></strong><br/><br/>
								</td>
							</tr>
								<?php }?>
							<tr height="15px;"></tr>
							<tr>
								<td></td>
								<td colspan='2'>
									<input type='radio' name='is_rec_form_offcr_name' id='is_rec_form_offcr_name_yes' value='1' <?php 	if($is_rec_form_offcr_name== '1') echo "checked";?> onclick="showtext(this.value, 'is_rec_form_offcr_name_other_text')">Yes&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<input type='radio' name='is_rec_form_offcr_name' id='is_rec_form_offcr_name_no' value='0' <?php 	if($is_rec_form_offcr_name== '0') echo "checked";?> onclick="showtext(this.value, 'is_rec_form_offcr_name_other_text')">No
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<table class='detail'>
										<tr>
											<td ><input type='radio' name='is_rec_form_offcr_name' id='is_rec_form_offcr_name_other' value='-1' <?php 	if($is_rec_form_offcr_name== '-1') echo "checked";?> onclick="showtext(this.value, 'is_rec_form_offcr_name_other_text')"><span >Other (Please explain):</span></td>
											<td>
												<textarea name="is_rec_form_offcr_name_other" id="is_rec_form_offcr_name_other_text" rows='10' cols='40' class="textareacmmn" style="<?php if($is_rec_form_offcr_name== '-1') echo 'display'; else echo 'display:none'; ?>"><?php echo $is_rec_form_offcr_name_other; ?></textarea>
											</td>
										</tr>
									</table>
								</td>
							</tr>
end loan contract check commenting out -->


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
										if($breview['is_nat_id_uploaded']==0){
											$emailApplicantBody.= $lang['mailtext']['if_nat_id_missing-msg']."<br/>";
										}

										if($breview['is_rec_form_uploaded']==0){
											$emailApplicantBody.= $lang['mailtext']['if_rec_form_missing-msg']."<br/>";
										}
										if($breview['is_pending_mediation']==0){
											$emailApplicantBody.= $lang['mailtext']['if_pending_mediation-msg']."<br/>";
										}
/*										if($breview['is_rec_form_offcr_name']==0){
											$emailApplicantBody.= $lang['mailtext']['if_contr_form_missing-msg']."<br/>";
										}
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
													<input type="text" name="sendername" style="width:350px;" value="<?php echo $breviewSendername; ?>"/><br/>
													<?php echo $form->error("sendername"); ?>
												</td>
											</tr>
											<tr><td><input class='btn' type="submit" name="Send" value="<?php echo $lang['admin']['send']; ?>"/></td></tr>
										</tbody>
									</table>
								</form>
				<?php unset($_SESSION['review_not_complete']); } ?>
							<!-- Step 1 Review for Completeness section ends--->

							<!-- Step 2 verification section starts--->
							<?php 

//if($details['Assigned_to']==5023 || $session->userid== $details['Assigned_to']){
								include('bverification.php');

//							}
							?>
							<!-- Step 2 verification section ends--->		
	


							
<!--						<?php echo "<h3 class='subhead'></h3>";}	?>

						<h3 class="subhead"><?php echo $lang['brwrlist-i']['About']." ".$name; ?></h3>
						<p style="text-align:justify;"><?php echo $about ?></p>

						<h3 class="subhead"><?php echo $lang['brwrlist-i']['loanhist']; ?></h3>
						<p style="text-align:justify;"><?php echo $loanHist ?></p>

						<h3 class="subhead"><?php echo $lang['brwrlist-i']['BusinessDesc']; ?></h3>
						<p style="text-align:justify;"><?php echo $bizdesc ?></p>

-->


		<?php		/*	if($session->userlevel==ADMIN_LEVEL)
								{
									
						?>		
							</div>

	<?php 			$results=$database->getPartnerCommentby($uid,$partnetId,0);
					if(!empty($results))
					{	?>
						<div class="row">
							<h3 class="subhead"><?php echo $lang['brwrlist-i']['FeedbackDis']; ?></h3>
							<table class="detail">
								<thead>
									<tr>
										<th width="65px"><?php echo $lang['brwrlist-i']['Date_Loan'];?></th>
										<th width="65px"><?php echo $lang['brwrlist-i']['Amount'];?></th>
										<th width="75px"><?php echo $lang['brwrlist-i']['lpaid'];?></th>
										<th width="60px"><?php echo $lang['brwrlist-i']['ontime'];?></th>
										<th width="75px"><?php echo $lang['brwrlist-i']['feedback'];?></th>
										<th><?php echo $lang['brwrlist-i']['comment'];?></th>
										<th width="40px"><?php echo $lang['brwrlist-i']['Edit'];?></th>
									</tr>
								</thead>
								<tbody>
							<?php 	foreach($results as $rew1)
									{
										// id  partid  userid  date  amount  lpaid  ontime  feedback  comment
										$commentid=$rew1['id1'];
										$commentdate=date('M d, Y', $rew1['date']);
										$commentamount=$rew1['amount'];
										if($rew1['lpaid']==0)
											$commentlp="No";
										else
											$commentlp="Yes";
										//$commentlp=$rew1['lpaid'];
										//$commentlp='<img src="'.SITE_URL.'images/layout/icons/'.$rew1['lpaid'].'.gif">';
										if($rew1['ontime']==0)
											$commentont="No";
										else
											$commentont="Yes";
										//$commentont=$rew1['ontime'];
										//$commentont='<img src="images/layout/icons/'.$rew1['ontime'].'.gif">';
										$commentf='<img src="images/layout/icons/'.$rew1['feedback'].'.gif">';
										$comment1=$rew1['comment'];
										//$f=strlen($comment1);///
										//if($f>50)
										//$comment1= substr($rew1['comment'], 0,50)."...&nbsp;<a href='index.php?p=7&id=$uid&cid=$commentid'>more</a>";
						?>
										<tr>
											<td><?php echo $commentdate;?></td>
											<td><?php echo $commentamount;?></td>
											<td><?php echo $commentlp;?></td>
											<td><?php echo $commentont;?></td>
											<td><?php echo $commentf;?></td>
											<td><?php echo $comment1;?></td>
											<td><a href='index.php?p=7&id=<?php echo $userid.'&cid='.$commentid.'#e2';?>'><img src="images/layout/icons/edit.png" border=0/></a></td>
										</tr>
						<?php 		}	?>
								</tbody>
							</table>
						</div>
			<?php 	}
					if($details['Assigned_status']==2)
					{	?>
						<div class="row">
							<h3 class="subhead"><?php echo $lang['brwrlist-i']['dec_details']; ?></h3>
							<table class="detail">
								<thead>
									<tr>
										<th><?php echo $lang['brwrlist-i']['date'];?></th>
										<th><?php echo $lang['brwrlist-i']['dec_by'];?></th>
										<th><?php echo $lang['brwrlist-i']['dec_reason'];?></th>
										<th><?php echo $lang['brwrlist-i']['Edit'];?></th>
									</tr>
								</thead>
								<tbody>
						<?php		$date=date('M d, Y', $details['Assigned_date']);
									$partner=$database->getNameById($details['Assigned_to']);
									$commentdec=$details['declined_reason'];
									if($did!=0)
									{
										$editcommentdec=$commentdec;
									}
									else
										$editcommentdec="";
						?>
									<tr>
										<td><?php echo $date;?></td>
										<td><?php echo $partner;?></td>
										<td><?php echo $commentdec;?></td>
										<td><a href='index.php?p=7&id=<?php echo $userid.'&did=1#e2';?>'><img src="images/layout/icons/edit.png" border=0/></a></td>
									</tr>
								</tbody>
							</table>
						</div>
			<?php 	}
					if(1)
					{	?>
						<!-- <table class='detail'>
							<tbody>

							
								<tr>
									<td>
<br /><br /><b><?php echo $lang['brwrlist-i']['is_this_applicant'] ?></b></td>
									<td>
										<div id="eligible-tab">
											<input name="email_print_radio-1" id="email_print_radio-1" value="yes" <?php if($form->value("activateBorrower")) echo 'checked="checked"'; ?> type="radio"><?php echo $lang['brwrlist-i']['yes'] ?>
											<input name="email_print_radio-1" id="email_print_radio-1" value="no" <?php if($form->value("declinedBorrower")) echo 'checked="checked"'; ?> type="radio"><?php echo $lang['brwrlist-i']['no'] ?>
										</div>
									</td>
								</tr>
							</tbody>
						</table> -->
			<?php	}
					$ontime=1;
					$lpaid=1;
					if($cid > 0)
					{
						//get detail of particular comment by comment id
						$resultsrow=$database->getPartnerCommentby($uid,$partnetId,$cid,0);
						if(!empty($resultsrow))
						{
							//id  partid  userid  date  amount  lpaid  ontime  feedback  comment
							$ldate=date("m/d/Y", $resultsrow['date']);
							$lamount=number_format($resultsrow['amount'], 0, ".","");
							$lpaid=$resultsrow['lpaid'];
							$ontime=$resultsrow['ontime'];
							$feedback=$resultsrow['feedback'];
							$comment=$resultsrow['comment'];
							$lendername=$resultsrow['lender'];
						}
					} 
					$lamount="";
					$ldate="";
					$lendername="";
					$feedback="";
					$comment="";
					$refoficialname = '';
					$refoficialno = '';
					if(!isset($editcommentdec)) {
						$editcommentdec="";
					}
					$temp = $form->value("date");
					if(isset($temp) && $temp != '')
						$ldate=$form->value("date");
					$temp = $form->value("loanamount");
					if(isset($temp) && $temp != '')
						$lamount=$form->value("loanamount");
					$temp = $form->value("loanpaid");
					if(isset($temp) && $temp != '')
						$lpaid=$form->value("loanpaid");
					$temp = $form->value("ontimepaid");
					if(isset($temp) && $temp != '')
						$ontime=$form->value("ontimepaid");
					$temp = $form->value("feedback");
					if(isset($temp) && $temp != '')
						$feedback=$form->value("feedback");
					$temp = $form->value("comment");
					if(isset($temp) && $temp != '')
						$comment=$form->value("comment");
					$temp = $form->value("lendername");
					if(isset($temp) && $temp != '')
						$lendername=$form->value("lendername");

					$temp = $form->value("refOfficial_name");
					if(isset($temp) && $temp != '')
						$refoficialname = $form->value("refOfficial_name");
					
					$temp = $form->value("refOfficial_number");
					if(isset($temp) && $temp != '')
						$refoficialno=$form->value("refOfficial_number");

			?>
		<!-- 		<div id='approveDiv' style="<?php if(!$form->value("activateBorrower") && $cid == 0 ) echo 'display:none'; ?>">
					<form method='post' action='updateprocess.php'>
							<table class='detail'>
								<tbody>
									<tr>
										<td ><strong><font style='font-size:20'><a name='e2'></a><?php echo $lang['brwrlist-i']['Amount_lone']. ' (' .$UserCurrency.')' ?></font></strong></td>
										<td><input id="loanamount" name="loanamount" type="text" value="<?php echo $lamount;?>"/></td>
										<td><?php echo $form->error('loanamount'); ?></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td><strong><font style='font-size:20'><?php echo $lang['brwrlist-i']['Date_desc']; ?></font></b></td>
										<td><input id="datepicker"  name="date" type="text"value='<?php echo $ldate ;?>' autocomplete="off" />
											<script type="text/javascript">
											$(document).ready(function(){
													$("#datepicker").datepicker();
													$('#ui-datepicker-div').hide();
											});

$(function() {		
	$(".tablesorter_pending_borrowers").tablesorter({sortList:[[2,1]], widgets: ['zebra']});
	});	

											//
											</script>
										</td>
										<td><?php echo $form->error('date'); ?></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td><strong><font style='font-size:20'><?php echo $lang['brwrlist-i']['lender']; ?></font></strong></td>
										<td><input id="lendername" name="lendername" type="text" value="<?php echo $lendername;?>"/></td>
										<td><?php echo $form->error('lendername'); ?></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td><strong><font style='font-size:20'><?php echo $lang['brwrlist-i']['Lone_repaid']; ?></font></strong></td>
										<td>
											<input type="radio" id='loanpaid' name="loanpaid" value=1 <?php if($lpaid==1)echo "checked='yes'"?>/> <?php echo $lang['brwrlist-i']['yes'];?>
											<input type="radio" id='loanpaid' name="loanpaid" value=0 <?php if($lpaid==0)echo "checked='yes'"?>/><?php echo $lang['brwrlist-i']['no'];?>
										</td>
										<td><div id="loanpaiderror"><?php echo $form->error('loanpaid'); ?></div></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td><strong><font style='font-size:20'><?php echo $lang['brwrlist-i']['Lone_repaid_time']; ?></font></strong></td>
										<td>
											<input type="radio" name="ontimepaid" value=1 <?php if($ontime==1)echo "checked='yes'"?>/><?php echo $lang['brwrlist-i']['yes'];?>
											<input type="radio" name="ontimepaid" value=0 <?php if($ontime==0)echo "checked='yes'"?>/><?php echo $lang['brwrlist-i']['no'];?>
										</td>
										<td><div id="ontimepaiderror"><?php echo $form->error('ontimepaid'); ?></div></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td><br /><strong><?php echo $lang['brwrlist-i']['Transaction_Feedback_Rating']; ?></strong></td>
										<td>
											<SELECT id ='feedback' NAME="feedback">
												<OPTION VALUE="2" <?php if($feedback==2)echo "selected='true'"?>><?php echo $lang['brwrlist-i']['first'];?>
												<OPTION VALUE="3"  <?php if($feedback==3)echo "selected='true'"?>><?php echo $lang['brwrlist-i']['second'];?>
												<OPTION VALUE="4"  <?php if($feedback==4)echo "selected='true'"?>><?php echo $lang['brwrlist-i']['third'];?>
											</SELECT><br />
										</td>
										<td><div id="feedbackerror"><?php echo $form->error('feedback'); ?></div></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td><strong><?php echo $lang['brwrlist-i']['Partner_Comment']; ?></strong></td>
										<td colspan=2><textarea  rows=10 cols=40 id='pcomment' name='comment'><?php echo $comment;?></textarea><br /><div id="pcommenterror"><?php echo $form->error('comment'); ?></div></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td ><strong><font style='font-size:20'><a name='e2'></a><?php echo $lang['brwrlist-i']['officialName'] ?></font></strong></td>
										<td><input id="officialName" name="refOfficial_name" type="text" value="<?php echo $refoficialname;?>"/></td>
										<td><?php echo $form->error('refOfficial_name'); ?></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td ><strong><font style='font-size:20'><a name='e2'></a><?php echo $lang['brwrlist-i']['officialNumber'] ?></font></strong></td>
										<td><input id="officialnumber" name="refOfficial_number" type="text" value="<?php echo $refoficialno;?>"/></td>
										<td><?php echo $form->error('refOfficial_number'); ?></td>
									</tr>
									<tr height="12px"></tr>
									<tr>
										<td style='text-align:center' colspan='2'>
											<br/><br/>
											<input type='hidden' name='activateBorrower' value="1"/>
											<input type="hidden" name="user_guess" value="<?php echo generateToken('activateBorrower'); ?>"/>
											<input type='hidden' name='userid' value='<?php echo $uid;?>' />
											<input type='hidden' name='commentid' value='<?php echo $cid;?>' />
											<?php 	if(($cid > 0)&&(!$m)) {?>
												<input class='btn' type='submit' name='Edit' value='Save' />&nbsp&nbsp&nbsp
											<?php 	}else{if($e==0) {?>
												<input class='btn' type='submit' name='Activate' value='<?php echo $lang['brwrlist-i']['btnActivate']; ?>' />
												&nbsp&nbsp&nbsp
											<?php }if (!$m){?>
												<input class='btn' type='submit' name='AddMore' value='<?php echo $lang['brwrlist-i']['btnaddmore']; ?>' />
											<?php }} ?>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
					<div id='declineDiv' style="<?php if(!$form->value("declinedBorrower") && $did == 0 ) echo 'display:none'; ?>">
						<form method='post' action='updateprocess.php'>
							<table class='detail'>
								<tbody>
									<tr>
										<td width="30%"><b><?php echo $lang['brwrlist-i']['declined_reason']; ?></b></td>
										<td colspan=2><textarea style="width:460px;height:100px" id='dreason' name='dreason'><?php echo $editcommentdec?></textarea><br /><div id="dreasonerror"><?php echo $form->error('dreason'); ?></div></td>
									</tr>
									<tr>
										<td><input type='hidden' name='declinedBorrower' value="1"/><input type="hidden" name="user_guess" value="<?php echo generateToken('declinedBorrower'); ?>"/><input type='hidden' name='userid' value='<?php echo $uid;?>' /></td>
										<td><input class='btn' type='submit' name='Submit' value='<?php if($did==0) echo "Submit"; else echo "Update"; ?>' /></td>
									</tr>
								</tbody>
							</table>
						</form>
					</div> -->
				</div>
	<?php	}*/
		}
		else
		{
			echo $lang['brwrlist-i']['borrower_list_tried']." ".$lang['brwrlist-i']['notify_web'];
		}
	}

}
?>
	<script type="text/javascript">
	<!--
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
	//-->
	</script>
<script language="JavaScript">
  var ids = new Array('is_photo_clear_yes','is_photo_clear_no', 'is_photo_clear_other', 'is_addr_locatable_yes','is_addr_locatable_no','is_addr_locatable_other','is_nat_id_uploaded_yes','is_nat_id_uploaded_no','is_nat_id_uploaded_other','is_rec_form_uploaded_yes','is_rec_form_uploaded_no','is_rec_form_uploaded_other','is_pending_mediation_yes','is_pending_mediation_no','is_pending_mediation_other', 'is_rec_form_offcr_name_yes','is_rec_form_offcr_name_no','is_rec_form_offcr_name_other','is_adrs_verfd_by_brwr_yes','is_adrs_verfd_by_brwr_no','is_adrs_verfd_by_brwr_other','is_adrs_verfd_by_brwr_other_text','is_witness_locatable_yes','is_witness_locatable_no','is_witness_locatable_other','is_witness_locatable_other_text','is_loan_addr_locatable_yes','is_loan_addr_locatable_no','is_loan_addr_locatable_other','is_loan_addr_locatable_other_text','is_neigh_addr_locatable_yes','is_neigh_addr_locatable_no','is_neigh_addr_locatable_other','is_neigh_addr_locatable_other_text','is_loan_history_yes','is_loan_history_no','is_loan_history_other','is_loan_history_other_text','lastloanamount','is_repaid_on_full_yes','is_repaid_on_full_no','is_repaid_on_full_other','is_repaid_on_full_other_text','is_repaid_on_time_yes','is_repaid_on_time_no','is_repaid_on_time_other','is_repaid_on_time_other_text','is_recomnd_addr_locatable_yes','is_recomnd_addr_locatable_no','is_recomnd_addr_locatable_other','is_recomnd_addr_locatable_other_text','is_recomnd_sign_yes','is_recomnd_sign_no','is_recomnd_sign_other','is_recomnd_sign_other_text');
  var values = new Array('', '', '', '', '', '', '', '', '', '', '', '', '','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','', '','','','');
  var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>
<script type="text/javascript">
<!--
	function showtext(value, str) { 
		if(value=='-1'){
			document.getElementById(str).style.display ='';
		}
		else{
			document.getElementById(str).style.display ='none';
		}
	}

//-->
</script>