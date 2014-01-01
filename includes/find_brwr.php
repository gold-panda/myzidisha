<div class='span12'>
<div><div style="float:left"><div align='left' class='static'><h1>Find Borrower</h1></div></div>
<div style="clear:both"></div></div><br/>
<?php 
if($session->userlevel==LENDER_LEVEL)
{
	$lenderid=$session->userid;
	$res=$database->isTranslator($lenderid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL){
	$country='';
	$brwr_type='';
	$search='';
	if(isset($_GET['c'])){
		$country= $_GET['c'];
	}
	if(isset($_GET['brwr'])){
		$brwr_type= $_GET['brwr'];
	}
	if(isset($_GET['search'])){
		$search= $_GET['search'];
	}
	$profile= $database->find_borrowers($country, $brwr_type, $search);
?>
<form  action='updateprocess.php' method="POST">
	Select Country:&nbsp
			<select id="country" name="country" >
	<?php		$result1 = $database->countryListWithAll(true);
				foreach($result1 as $cont)
				{?>
					<option value='<?php echo $cont['code']?>' <?php if($country==$cont['code']) echo "Selected='true'";?>><?php echo $cont['name']?></option>
	<?php		}	?>
			</select><br/><br/><br/><br/>
	Select Status:&nbsp;&nbsp;&nbsp;
			<select id="borrower_type" name="borrower_type" >
				<option value='all' <?php if($brwr_type=='all') echo "Selected='true'";?>>All</option>
				<option value='endorser' <?php if($brwr_type=='endorser') echo "Selected='true'";?> >Endorser Account</option>
				<option value='pndng_sub' <?php if($brwr_type=='pndng_sub') echo "Selected='true'";?> >Pending Submission</option>
				<option value='pndng_act' <?php if($brwr_type=='pndng_act') echo "Selected='true'";?> >Pending Activation</option>
				<option value='decline' <?php if($brwr_type=='decline') echo "Selected='true'";?>>Declined</option>
				<option value='active' <?php if($brwr_type=='active') echo "Selected='true'";?>>Active</option>
			</select><br/><br/><br/><br/>
	Search for a first name, last name, email or phone number: <input type="text" name="search" maxlength="100" value="<?php echo $search; ?>"/><br/><br/><br/><br/>
	<input type="hidden" name="find_borrower">
	<center><input type='submit' name='submit' class='btn' value='Submit'/></center><br/><br/>
</form>
<?php 
	if(!empty($profile)){ ?>
		<table id="transtable" class="zebra-striped">
			<thead>
				<tr>
					<th>Borrower</th>
					<th>Location</th>
					<th>Status</th>
					<th>Last Visited</th>
					<th>Notes</th>
					
				</tr>
			</thead>
			<tbody>
<?php	foreach($profile as $rows){
			$userid=$rows['userid'];
			$fname=$rows['FirstName'];
			$lname=$rows['LastName'];
			$username=$database->getUserNameById($userid);
			$address=$rows['PAddress'];
			$city=$rows['City'];
			$country=$database->mysetCountry($rows['Country']);
			$telmob=$rows['TelMobile'];
			$email=$rows['Email'];
//added by Julia 22-10-2013
			$is_endorser=$database->IsEndorser($userid);
			$details=$database->getBorrowerById($userid); 						
			$assignedStatus=$details['Assigned_status'];
			$last_modified=$details['LastModified'];
			$last_mod_date=date('M d, Y', $last_modified);
				if($assignedStatus==1) {
					$activationdate=$database->getborrowerActivatedDate($userid);
					if(!empty($activationdate)){
					$activate_date=date('M d, Y', $activationdate);
					}else{
					$activate_date='';
					}
					$status = "<span style='display:none'>$activationdate</span>Activated on $activate_date";	

				}
				else if($assignedStatus==-1) {
					$status = 'Pending Verification';
				}	

				else if($assignedStatus==2) {
					$status = 'Declined';
				}

				elseif($assignedStatus==0) {
					$status = 'Pending Review';
				}
				else {
					$status = '';
				}

			$is_completelater=$database->getiscompleteLater($userid);
			$active=$rows['Active'];
			$lastvisited='';
			if(!empty($rows['lastVisited'])) {
				$lastvisited = date("m/d/Y",$rows['lastVisited']);
			}
			$prurl= getUserProfileUrl($userid);
			$link='index.php?p=7&id='.$userid;
			$admin_notes = $rows['admin_notes'];
			echo '<tr>';
			echo "<td><a href='$prurl'>$fname $lname</a><br /><br />Username: $username<br /><br />$address<br />$telmob<br />$email<br /></td>";
			echo "<td><div align='center'>$country<br/>$city</div></td>";
			echo "<td>";
//added by Julia 22-10-2013
			if(!empty($is_endorser)){
				echo "Endorser";
			}elseif(!empty($is_completelater)){
				echo "<span style='display:none'>$last_modified</span>Pending Submission<br/><br/>Last Modified  $last_mod_date";
			}elseif(!empty($status)){
				echo "$status";
			}else{
				echo "";
			}
			echo "<br/><br/><a href='$link'>Activation Page</a><br/><br/>";
			if(!empty($details['frontNationalId'])){
				echo "ID Uploaded";
			}
			// added by mohit 11-10-13
		/*removed by Julia 16-10-2013, moved to bprofileToadmin.php

			if($brwr_type=='pndng_act' || $brwr_type=='active'){

			if($active==0){

		
						$active1="<form name='activeform".$userid."' method='post' action='process.php'>".
						"<input name='deactivateBorrower' type='hidden' />".
						"<input name='countryCode' type='hidden' value='".$_GET['c']."' />".
						"<input name='brwr_type' type='hidden' value='".$brwr_type."' />".
						"<input name='search' type='hidden' value='".$search."' />".
						"<input type='hidden' name='user_guess' value='".generateToken('deactivateBorrower')."'/>".
						"<input name='borrowerid' value='$userid' type='hidden' />".
						"<input name='set' value = 1 type='hidden' />".
						"<a href='javascript:void(0)' style='color:red' onclick='document.forms.activeform".$userid.".submit()'>Activate Account</a>".
						"</form>";

		
					}
					else{
						$active1=//"<a href=#>Deactivate</a>";
						"<form name='deactiveform".$userid."'method='post' action='process.php'>".
						"<input name='deactivateBorrower' type='hidden' />".
						"<input name='countryCode' type='hidden' value='".$_GET['c']."' />".
						"<input name='brwr_type' type='hidden' value='".$brwr_type."' />".
						"<input name='search' type='hidden' value='".$search."' />".
						"<input type='hidden' name='user_guess' value='".generateToken('deactivateBorrower')."'/>".
						"<input name='borrowerid' value='$userid' type='hidden' />".
						"<input name='set' value = 0 type='hidden' />".
						"<a href='javascript:void(0)' style='color:red' onclick='document.forms.deactiveform".$userid.".submit()'>Deactivate Account</a>".
						"</form>";
					}
				echo $active1;
					  }
			*/
			echo "</td>";
			// end here
			echo "<td><span style='display:none'>$lastvisited</span><input type='text' name='lastvisited' id='lastVisited$userid'  value = '$lastvisited'></td>";
			echo "<td><textarea name='borrower_notes' id='borrower_notes$userid' col='6' row='6' style='height: 80px'>$admin_notes</textarea>
			<input type='button' name='save' class='btn' id='save$userid' value='Save' onclick='saveborrowerdetail($userid)'></td>";
			echo"<td>
					<span id='response$userid'></span>
				</td>";
			echo '</tr>';
		}
?>		</tbody>
	</table>
<?php
	}elseif(isset($_GET['c'])){
		echo "There is no borrower in this category";
	}
} ?>
</div>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/scripts/borrower_detail.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
	<?php 
		if(!empty($profile)) {
				foreach($profile as $row) { ?>
					$("#lastVisited<?php echo $row['userid']?>").datepicker();
		<?php }
		}
		?>
	});
	$(function() {		
		$(".zebra-striped").tablesorter({sortList:[[0,0]], widgets: ['zebra'], headers: { 2:{sorter: 'digit'}, 3:{sorter:'digit'}, 4:{sorter:false}}});
 });	
</script>