<?php 
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script type="text/javascript" src="includes/scripts/repayreport.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {		
		$(".repayreportsort").tablesorter({sortList:[[0,0]], widgets: ['zebra'], headers: { 2:{sorter: 'digit'}, 3:{sorter:'digit'},7:{sorter:false}}});
 });	
</script>

<div class='span12'>
<?php
$is_mentor= $database->isBorrowerAlreadyAccess($session->userid);
if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL || ($is_mentor))
{
	$date="";
	$v=0;
	$country="";
	$assignedto="";
/* The below cache code makes the report load faster, but it prevents the report from updating to exclude written-off loans. In order to update the report to exclude the write-offs, comment out the below code, reload the report, then remove the commenting out. - Julia */
	$cache=0;
	if(isset($_GET['cache'])){
		$cache=1;
	}


	if(isset($_GET['c']))
	{
		if(isset($_SESSION['rp_date'])) {
			$date=$_SESSION['rp_date'];
		}else {
			$date = date('m/d/Y',time());
		}

		$country=$_GET['c'];


	if(isset($_GET['a']))
	{

		$assignedto=$_GET['a'];
	}

		if($country=='0' || $date==false)
			echo "<div align='center' style='color:red'>Please select country</div><br/>";
		else {
			$v=1;
			$volunteers= $database->getActiveCoOrgUsersAndStaff($country);
                        foreach($volunteers as $key => $result)
				{	
					$row= $database->getUserById($result['user_id']);
                                        $city = '';
					$TelMobile='';
					if(!empty($row['City'])) {
						$volunteers[$key]['City'] = $row['City'];
					}else {
						$volunteers[$key]['City'] = '';
					}
				}
			$sortedvolunteers = array_sort($volunteers,'City', 'SORT_ASC', 'City');


		}

	}

?>
	<div>
			  <div style="float:left"><div align='left' class='static'><h1>Repayment Report</h1></div></div>

<strong><br /><br />Instructions</strong><br /><br />
<?php echo $lang['admin']['repay_inst']; ?><br /><br /><br />

			<?php if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			</div><? } ?>
			<div style="clear:both"></div>
		</div>
<?php if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL){ ?>
		<form  action='updateprocess.php' method="POST">
			<!-- To Date:&nbsp<input  name="date" id="date" type="text"value='<?php echo $date ;?>'/>	 -->   
                        &nbsp&nbsp
			Show Loans in Country:&nbsp
                        <select id="country" name="country" onchange="getVolMentStaffMemList(this.value);" >
	<?php		$result1 = $database->countryListWithAll(true);
				echo "<option value='0'>Select a country</option>";
				foreach($result1 as $cont)
				{?>
					<option value='<?php echo $cont['code']?>' <?php if($country==$cont['code']) echo "Selected='true'";?>><?php echo $cont['name']?></option>
	<?php		}	?>
			</select>
<!-- added by Julia 7-11-2013 -->
			<br/><br/><br/>
			Show Loans Assigned To:&nbsp
			<!-- Update by Mohit 13-11-13 ----> 
                        <select id="assignedto" name="assignedto" ></select>                 
                        <img src="images/layout/icons/ajax-loader.gif" id="loading_image" style="display:none" >
                        <input type="hidden" size="3" name="repay_report" id="repay_report">
			<input type="hidden" name="user_guess" value="<?php echo generateToken('repay_report'); ?>"/>
                        <div style="margin-top:-30px;margin-left:400px;"><input class='btn' type='submit' name='report' value='Report' /></div><br/>
		</form>
		<br/>
		<br/>
<?php }
	if($v==1)
	{
		$current_date= time();
		$set_data= $database->getStatistics('RepayReport', $current_date, $country);
		
		if(!empty($set_data) && $cache!=1 && $is_mentor || ($assignedto!=0 && $assignedto!='')){

			$set=$database->repay_report($date, $country, $assignedto);

		}elseif(!empty($set_data) && $cache!=1 && !$is_mentor && ($assignedto==0 || $assignedto=='')){
			$set= unserialize($set_data);

		}else {
			$set=$database->repay_report($date, $country, $assignedto);
			if(!$is_mentor && ($assignedto==0 || $assignedto==''))
				$database->setStatistics('RepayReport', serialize($set), $country);
		}

		$dateArr  = explode("/",$date);
		$date1=mktime(0,0,0,(int)$dateArr[0],(int)$dateArr[1],(int)$dateArr[2]);
		$repayThresholdAmt = $database->getAdminSetting('RepaymentReportThrshld');
		if(empty($set))
			echo "<strong>No Data</strong>";		
                else 
		{                              
			for($j=0; $j<count($set); $j++)
			{
				$currency=$database->getUserCurrency($set[$j][0]['userid']);
				$currencyid = $database->getCurrencyIdByCountryCode($set[$j][0]['country']);
				$rate=$database->getExRateByDate($date1, $currencyid);
				$country1=$database->mysetCountry($set[$j][0]['country']);
				echo "<center><h3 class='subhead'>Report for Country ".$country1."</h3></center>";
				?>
				
				<table class="zebra-striped repayreportsort">
					<thead>
						<tr>
							<th>Member Name</th>
							<th>Location</th>
							<th>Amount Past Due (<?php echo $currency?>)</th>
							<th>Past Due Since</th>
							<!-- <th>Telephone Number</th> -->
							<th>Community Leader</th>
							<th>Volunteer Mentor or Staff Assigned</th>
							<th>Next Follow-Up</th>
							<th>Notes</th>
						</tr>
					</thead>
					<tbody>
                                         <?php  $i=1;
						$dueamtTot=0;
						foreach($set[$j] as $row)
						{	
							$brwrnumbr = $row['TelMobile'];
							$loanid = $row['loanid'];
							$userid = $row['userid'];
							$dynamic_data= $database->getRepayDynamicData($userid);                                                                                                      
                                                        $note = stripslashes($dynamic_data['note']);
							$refname = stripslashes($dynamic_data['rec_form_offcr_name']);
							$refnumbr = $dynamic_data['rec_form_offcr_num'];
							$volunteer_mentor=$database->getUserById($dynamic_data['mentor_id']);
							$volunteer_status=$database->isBorrowerAlreadyAccess($dynamic_data['mentor_id']);
                                                        
// added by Julia 7-11-2013                                                                                                                                   
							$res=$database->isTranslator($dynamic_data['mentor_id']);
                                                        if($res==1) {
                                                                $is_staff=1;
                                                        } else {
                                                                $is_staff=0;
                                                                }

							$volunteer_name='';
							$volunteer_mobile='';
							if(!empty($volunteer_mentor['name'])){
								$volunteer_name= $volunteer_mentor['name'];
							}
							if(!empty($volunteer_mentor['TelMobile'])){
								$volunteer_mobile= $volunteer_mentor['TelMobile'];
							}

//added by Julia 7-11-2013
							if($is_staff == 1) {
								$volunteer_name.=" (Staff)";
							}

							elseif(!$volunteer_status && !empty($volunteer_name)) {
								$volunteer_name.=" (Deactivated)";
							}
							$repaydate='';
							$repaydateTosort='';
							if(!empty($dynamic_data['expected_repaydate'])) {
								$repaydateTosort = $dynamic_data['expected_repaydate'];
								$repaydate = date("m/d/Y",$dynamic_data['expected_repaydate']);
							}
//added by Julia 31-10-2013
							$prurl=getUserProfileUrl($userid);
							$loanprurl = getLoanprofileUrl($row['userid'], $row['loanid']);
							//Logger("REPAYRPT-VIEW: : refOfficial_name=". $refname ."refOfficial_number". $refname . "id = ".$userid. "logged in user: ". $session->userid ."notes: ".$note."repayment date: ".$repaydate."\n");							
							$bname="<a href='".$loanprurl."'>".$row['bname']."</a>";
                                                        $duedatetosort = $row['duedate'];
							$duedate=date("M j, Y",$row['duedate']);
							$dueamt= $row['totamt']-$row['totpaidamt'];
							$dueamtUSD = $dueamt/$rate;
							 // Added by mohit 12-11-13
                                                if($dynamic_data['mentor_id']==$assignedto && !empty($assignedto))
                                                    {    
                                                        if($dueamt<=0 || $dueamtUSD < $repayThresholdAmt)
								continue;
							$dueamtTot +=$dueamt;
							$dueamtToSort = number_format(round_local($dueamt), 2, '.', '');
							$dueamt=number_format(round_local($dueamt), 0, '.', ',');
							$bcity= $row['city'];
							$address= $row['Paddress'];
                                                                       
							echo "<tr id='rowid$userid' >";
							echo "<td>Member Name:<br/><br/>$bname<br/><br/><br/>Member Telephone:<br/><br/>$brwrnumbr</td>";
							echo"<td><span style='display:none'>$bcity</span>$bcity<br/><br/>$address</td>";
							echo "<td><span style='display:none'>$dueamtToSort</span>Amount Past Due:<br/><br/> &nbsp;$currency&nbsp;$dueamt</td>";
							echo "<td><span style='display:none'>$duedatetosort</span>Past Due Since:<br/><br/>$duedate<br/><br/><br/><a href=index.php?p=37&l=$loanid&u=$userid>View Repayment Schedule</a></td>";
							//echo "<td>$brwrnumbr</td>";
							echo "<td><div>Community Leader Name:<br/><br/></div>";?>
							<?php
								$navigate_away_ids[] = 'refname'.$userid;
								$navigate_away_ids_values[] = '';
							?>
							<input type='text' style='width: 100px;' id="<?php echo 'refname'.$userid?>" value="<?php echo $refname ?>"  >
						<?php 
								$navigate_away_ids[] = 'refnumber'.$userid;
								$navigate_away_ids_values[] = '';
								$save_buttons_ids[] = 'saveButtonDiv'.$userid;
								echo"<div ><br/><br/>Community Leader Telephone:<br/><br/></div>
									<input type='text' style='width: 100px;'  id='refnumber$userid' value='$refnumbr' ><br/><br/><br><strong><a href='".$prurl."'>View Other Contacts</a></strong><br/><br/>
									<div id='saveButtonDiv$userid' style='margin-left: 90px;'><input type='button' name='save' class='btn' id='save$userid' value='Save Changes' getHeight();' onclick='saverefdetail($userid)'><br/><span	id='response$userid'></span></div>	
								</td>";
							?>
							<?php 
								$navigate_away_ids[] = 'volunteer_mentor'.$userid;
								$navigate_away_ids_values[] = '';
							?>
							   <td>Assigned To:<br/><br/><div id="<?php echo "volunteer_name".$userid ?>"><?php echo $volunteer_name;?></div><br/>

							<?php if(!empty($volunteer_mentor['TelMobile'])) {
							echo "Volunteer Mentor Telephone:<br/><br/>";
							} ?>

							<div id="<?php echo "volunteer_no".$userid ?>"><?php echo $volunteer_mobile; ?></div><br/>Assign to New VM or Staff:<br/><select id="<?php echo "volunteer_mentor".$userid?>" name="volunteer_mentor" style='width:165px;' >
							<option value='0'>Select</option>
                                                            
						<?php 
                                                foreach($sortedvolunteers as $volunteers){        // julia changes updated by mohit on date 06-11-13
								$rows= $database->getUserById($volunteers['user_id']); ?>
                                                                    
								<option value="<?php echo $volunteers['user_id']?>" <?php if($dynamic_data['mentor_id']==$volunteers['user_id']) echo "Selected";?>><?php 
                                                                    echo $rows['City'].": ".$rows['name'].", tel ".$rows['TelMobile'];?>
                                                                </option>
						<?php	} ?>		
								
								</select>
</td>
                                                   <?php
							$navigate_away_ids[] = 'exdate'.$userid;
							$navigate_away_ids_values[] = '';
							echo "<td><span style='display:none'>$repaydateTosort</span>
							Next Follow-Up:<br/><br/>&nbsp;<input   style='width: 70px;'name='exdate'  id='exdate$userid' type='text' value='$repaydate' /></div><br/><br/><br><strong><a href='".$loanprurl."'>Post Comment for Lenders</a></strong><br/><br/></td>";
							$editlink = '';
							if(!empty($note)) 
								$editlink = "<a href='javascript:void(0)' onclick='editnotes($userid)'>edit</a>";

							$navigate_away_ids[] = 'note'.$userid;
							$navigate_away_ids_values[] = '';
							echo "<td><div id ='editdiv$userid'>$note</div><div id='editlink$userid'>$editlink</div><br/>
							<textarea name='note'  id='note$userid' col='6' row='6' style='height: 80px'  ></textarea>
									<input type='hidden' name='borrowerid' id='borrower$userid' value='$userid'>
									<input type='hidden' name='loanid' id='loan$userid' value='$loanid'>

								</td>";
							echo"<input name='isedit' type = 'hidden' value='0' id = 'isedit$userid'>";
							echo '</tr>';
                                                        } else if($assignedto==0){                                                           
                                                                if($dueamt<=0 || $dueamtUSD < $repayThresholdAmt)
                                                                         continue;
                                                                 $dueamtTot +=$dueamt;
                                                                 $dueamtToSort = number_format(round_local($dueamt), 2, '.', '');
                                                                 $dueamt=number_format(round_local($dueamt), 0, '.', ',');
                                                                 $bcity= $row['city'];
                                                                 $address= $row['Paddress'];

                                                                 echo "<tr id='rowid$userid' >";
                                                                 echo "<td>Member Name:<br/><br/>$bname<br/><br/><br/>Member Telephone:<br/><br/>$brwrnumbr</td>";
                                                                 echo"<td><span style='display:none'>$bcity</span>$bcity<br/><br/>$address</td>";
                                                                 echo "<td><span style='display:none'>$dueamtToSort</span>Amount Past Due:<br/><br/> &nbsp;$currency&nbsp;$dueamt</td>";
                                                                 echo "<td><span style='display:none'>$duedatetosort</span>Past Due Since:<br/><br/>$duedate<br/><br/><br/><a href=index.php?p=37&l=$loanid&u=$userid>View Repayment Schedule</a></td>";
                                                                 //echo "<td>$brwrnumbr</td>";
                                                                 echo "<td><div>Community Leader Name:<br/><br/></div>";?>
                                                                 <?php
                                                                         $navigate_away_ids[] = 'refname'.$userid;
                                                                         $navigate_away_ids_values[] = '';
                                                                 ?>
                                                                 <input type='text' style='width: 100px;' id="<?php echo 'refname'.$userid?>" value="<?php echo $refname ?>"  >
                                                         <?php 
                                                                         $navigate_away_ids[] = 'refnumber'.$userid;
                                                                         $navigate_away_ids_values[] = '';
                                                                         $save_buttons_ids[] = 'saveButtonDiv'.$userid;
                                                                         echo"<div ><br/><br/>Community Leader Telephone:<br/><br/></div>
                                                                                 <input type='text' style='width: 100px;'  id='refnumber$userid' value='$refnumbr' ><br/><br/><br><strong><a href='".$prurl."'>View Other Contacts</a></strong><br/><br/>
                                                                                 <div id='saveButtonDiv$userid' style='margin-left: 90px;'><input type='button' name='save' class='btn' id='save$userid' value='Save Changes' getHeight();' onclick='saverefdetail($userid)'><br/><span	id='response$userid'></span></div>	
                                                                         </td>";
                                                                 ?>
                                                                 <?php 
                                                                         $navigate_away_ids[] = 'volunteer_mentor'.$userid;
                                                                         $navigate_away_ids_values[] = '';
                                                                 ?>
                                                                    <td>Assigned To:<br/><br/><div id="<?php echo "volunteer_name".$userid ?>"><?php echo $volunteer_name;?></div><br/>

                                                                 <?php if(!empty($volunteer_mentor['TelMobile'])) {
                                                                 echo "Volunteer Mentor Telephone:<br/><br/>";
                                                                 } ?>

                                                                 <div id="<?php echo "volunteer_no".$userid ?>"><?php echo $volunteer_mobile; ?></div><br/>Assign to New VM or Staff:<br/><select id="<?php echo "volunteer_mentor".$userid?>" name="volunteer_mentor" style='width:165px;' >
                                                                 <option value='0'>Select</option>

                                                         <?php 
                                                         foreach($sortedvolunteers as $volunteers){        // julia changes updated by mohit on date 06-11-13
                                                                         $rows= $database->getUserById($volunteers['user_id']); ?>

                                                                         <option value="<?php echo $volunteers['user_id']?>" <?php if($dynamic_data['mentor_id']==$volunteers['user_id']) echo "Selected";?>><?php 
                                                                             echo $rows['City'].": ".$rows['name'].", tel ".$rows['TelMobile'];?>
                                                                         </option>
                                                         <?php	} ?>		

                                                                         </select>
         </td>
                                                            <?php
                                                                 $navigate_away_ids[] = 'exdate'.$userid;
                                                                 $navigate_away_ids_values[] = '';
                                                                 echo "<td><span style='display:none'>$repaydateTosort</span>
                                                                 Next Follow-Up:<br/><br/>&nbsp;<input   style='width: 70px;'name='exdate'  id='exdate$userid' type='text' value='$repaydate' /></div><br/><br/><br><strong><a href='".$loanprurl."'>Post Comment for Lenders</a></strong><br/><br/></td>";
                                                                 $editlink = '';
                                                                 if(!empty($note)) 
                                                                         $editlink = "<a href='javascript:void(0)' onclick='editnotes($userid)'>edit</a>";

                                                                 $navigate_away_ids[] = 'note'.$userid;
                                                                 $navigate_away_ids_values[] = '';
                                                                 echo "<td><div id ='editdiv$userid'>$note</div><div id='editlink$userid'>$editlink</div><br/>
                                                                 <textarea name='note'  id='note$userid' col='6' row='6' style='height: 80px'  ></textarea>
                                                                                 <input type='hidden' name='borrowerid' id='borrower$userid' value='$userid'>
                                                                                 <input type='hidden' name='loanid' id='loan$userid' value='$loanid'>

                                                                         </td>";
                                                                 echo"<input name='isedit' type = 'hidden' value='0' id = 'isedit$userid'>";
                                                                 echo '</tr>';

                                                        }
                                                       $i++;                                                                                           
                                                    }?>
						<tr style="display:none" class="odd">
							<td><a href="javascript:void(0)">fake name</a></td>
							<td><span style="display:none">24000.00</span>24,000</td>
							<td><span style="display:none">1338124710</span>May 27, 2012</td><td>779595532</td><td>
							<div>Name</div>
							<input type="text" style="width: 100px;"  value="fake" >
							<div>Telephone number</div>
							<input type="text" style="width: 100px;"  value="99999999">	
							</td><td><input style="width: 70px;" name="exdate" type="text" value="07/31/2012"><span style="display:none">1343692800</span></td><td><textarea name="note"  id="note188" col="6" row="6" style="height: 80px">fake row content in textarea</textarea>
							</td><td>
							<span></span>
							</td>
						</tr>
					</tbody>
				</table>
				<strong>Total Amount Overdue <?php echo number_format(round_local($dueamtTot), 0, '.', ',')." (".$currency.")"; ?></strong><br/><br/>

<strong>Recommendation Forms are no longer accepted from the following Community Leaders:</strong><br /><br />

<strong>Kenya:</strong><br /><br />
Elias Konde, tel. 0720588318<br />
Pst. John Opiyo, tel. 0720823379<br /> 
Pst. George Ombati, tel. 0778420402 or 0778640604<br />
Euphraim Kasinga, tel. 0716771316, 0728723251 or 0771885224<br />
Muguu Tupu Foundation, tel. 0705693769<br />
nicholas opondo, 0719772097<br />
Rev. Livingstone Kipkemei, tel. 0723993844<br />
George Kamunge, tel. 0771271963<br />
Pastor Charles Maingi, tel. 0789277834<br />
Steven owuor, tel. 0733690245<br />
Jane Kamau, tel. 0724336072<br />
john mark opiyo, tel. 0720823379<br />
Zablon Mongare, tel. 0789497103<br />
ken mbogori, tel. 0708340380<br />
MOSES SHIKANGA, tel. 0751896723 or 0751896723<br />
peter thairu, tel. 0722374008<br />
jackson n m shoka, tel. 0724026381<br />
ERICK OLOO, tel. 0733690245<br />


<br /><br /><strong>Senegal:</strong><br /><br />
Samba Lagatte Ndiaye, tel. 763841458<br />
Seynabou Sylla, tel. 775135171<br />
<br />
	<?php	}
	    }
	}
}
else
{
	echo "<div>";
	echo $lang['admin']['allow'];
	echo "<br />";
	echo $lang['admin']['Please'];
	echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
	echo "</div>";
}	?>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#date").datepicker({
			showOn: "button"
		});
	<?php 
		if(!empty($set)) {
			for($j=0; $j<count($set); $j++) {
				foreach($set[$j] as $row) { ?>
					$("#exdate<?php echo $row['userid']?>").datepicker();
	<?php		}
			}
		}?>
	});
	var rowid= new Array()
		function editnotes(id) {
			var text = document.getElementById('editdiv'+id).innerHTML;
			document.getElementById("note"+id).value=text;
			document.getElementById("isedit"+id).value=1;
		}



</script>
<?php 
if(!empty($navigate_away_ids)){
	$nids = implode(',',$navigate_away_ids); 
	$nids = "'".str_replace(',',"','", $nids)."'";
}
if(!empty($navigate_away_ids_values)){
	$nids_val = implode(',',$navigate_away_ids_values);
	$nids_val = "'".str_replace(',',"','", $nids_val)."'";
}
if(!empty($save_buttons_ids)){
	$bids_val = implode(',',$save_buttons_ids);
	$buttonids = "'".str_replace(',',"','", $bids_val)."'";
}

?>
<script language="JavaScript">

  var ids = new Array(<?php echo $nids?>);
  var values = new Array(<?php echo $nids_val?>);
  var bids = new Array(<?php echo $buttonids?>);
var needToConfirm = true;

</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
  window.onbeforeunload = confirmChange;

function setSaveButtonPosition(){ 
	for (var i = 0; i < bids.length; i++){
		var m = bids[i].match(/\d+/g);
		var height = document.getElementById('rowid'+m).offsetHeight;
		var margin_height = height-120;
		document.getElementById('saveButtonDiv'+m).style.marginTop =  margin_height + "px";
	}
  }
 window.onload = setSaveButtonPosition;

jQuery(document).ready(function(){
   var country='<?php echo $country;?>';
   var assign_to='<?php echo $assignedto;?>';      
   getVolMentStaffMemList(country,assign_to);
    
});
 
</script>
