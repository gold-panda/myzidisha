<script type="text/javascript" src="includes/scripts/getborrowers.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript">
<!--
			$(function() {		
			$(".tablesorter_community_org").tablesorter({sortList:[[0,0]], widgets: ['zebra'],headers: { 5:{sorter: false}} });
		});	

//-->
</script>




<?php
if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL ){
if(isset($_SESSION['grant_access'])){
		$AccesGrantedto = $database->getNameById($_SESSION['granted_accessto']);
		echo "<div align='center'><font color=green><b>Volunteer Mentor access has been granted to $AccesGrantedto.</b></font></div>";
		unset($_SESSION['grant_access']);
	}
if(isset($_SESSION['grant_remove'])){
		$Accesremovedfrm = $database->getNameById($_SESSION['removed_accessfrm']);
		echo "<div align='center'><font color=green><b>Volunteer Mentor access has been removed from $Accesremovedfrm.</b></font></div>";
		unset($_SESSION['grant_remove']);
	}
if(isset($_SESSION['grant_already_access'])){
		echo "<div align='center'><font color=green><b>Volunteer Mentor access has been already granted</b></font></div>";
		unset($_SESSION['grant_already_access']);
	}
$countrycode = '';
if(isset($_GET['c'])) {
	$countrycode = $_GET['c'];
	
	$coorgborrowers= $database->getAllCoOrgBorrowers($countrycode);

	$borrowersByCountry= $database->getUsersByCountry($countrycode);
}

?>
<div class="span12">
			  <div style="float:left; padding-bottom: 29px;" class="static"><h1>Volunteer Mentors</h1></div>
			  <?php if($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
<div id="getborrowers" name="comm_org" class='span12'>
	<form action="process.php" method="post">
	<table class="detail" style="width:auto">
		<tbody>
			<tr>
				<td style="padding-bottom: 13px;padding-right:24px;" ><strong>Select Country:</strong></td>
				<td style="padding-bottom: 13px;">
					<select name="c" id="c" class="selectcmmn" onChange="CobyCountry(this.id)" style="min-width: 2px">
					<option Value='index.php?p=84'>Select Country</option>
					<?php	$result1 = $database->countryList(true);
						$tempcountry=$form->value("country");
						if(!empty($tempcountry))
							$country=$tempcountry;
						if(!empty($result1))
						{	
							foreach($result1 as $state)
							{	?>
								<option value="index.php?p=84&c=<?php echo $state['code']; ?>"<?php if($countrycode==$state['code'])echo "Selected='true'"; ?>><?php echo $state['name'];?></option>
					<?php 	}
						}	?>
							
					</select>
				<br/><br/></td>
			</tr>
			<tr>
				<td><strong>Grant Volunteer Mentor Access To:</strong></td>
				<td><div id="borrowersbycountry">
				<select name="brwrbycountry">
				<option Value='index.php?p=84'>Select Name</option>
			<?php if(!empty($borrowersByCountry))
					foreach($borrowersByCountry as $CountryBorrowers){?>
					<option value="<?php echo $CountryBorrowers['userid']; ?>"><?php echo htmlentities($CountryBorrowers['name'].' ('.$CountryBorrowers['City'].')') ?></option>
			<?php }else{ ?>
					<option></option>
			<?php	} ?>
				</select></div></td>

				<td></td>
				<td><input class='btn' type='submit' value='Grant Access' name='grant_access_co'></td>

			</tr>
			<tr>

			</tr>
		</tbody>
	</table>
	</form>
<div class="subhead2"><br/><br/>
	<div><h3 class="new_subhead">Current Volunteer Mentors</h3></div>
</div>
<?php
if(!empty($coorgborrowers)){
echo"<table class='zebra-striped tablesorter_community_org'>";
						echo"
						<thead>
							<th>Name</th>
							<th>City / Village</th>
							<th>Telephone Number</th>
							<th>Email</th>
							<th>Number of Assigned Members</th>
							<th>Notes</th>
						</thead>";
					echo"<tbody>";
						foreach($coorgborrowers as $co_organizer) {

									$co_detail = $database->getUserById($co_organizer['user_id']);
									$name=$co_detail['firstname'].' '.$co_detail['lastname'];
									$gdate=date('M d, Y',$co_organizer['grant_date']);
									
									$city = $co_detail['City'];
									$telephone=$co_detail['TelMobile'];
									$email= $co_detail['email'];
									$borrowerid=$co_organizer['user_id'];	
								
									$prurl= getUserProfileUrl($borrowerid);

									$note = stripslashes($co_organizer['note']);
															$vm_member_details= $database->getMentorAssignedmember($borrowerid);
			$total_assigned= count($vm_member_details);
			
									echo"<tr>";
									echo"<form action='process.php' method='post' name='the_form".$borrowerid."'>";
									echo"<td><a href='$prurl'>$name</a></td>";
									echo"<td>$city</a></td>";
									echo"<td>$telephone</a></td>";
									echo"<td>$email</a></td>";

									echo"<td>$total_assigned</a></td>";



									$editlink = '';
									if(!empty($note)) 
									$editlink = "<a href='javascript:void()' onclick='editnotes($borrowerid)'>edit</a>";
									echo "<td>
									<textarea name='note'  id='note$borrowerid' col='6' row='6' style='height: 80px'>$note</textarea>
									<input type='button' name='save' class='btn' id='save$borrowerid' value='Save' onclick='savecodetail($borrowerid)'>
									</td>";
									echo"<td>
									<span	id='response$borrowerid'></span>
									</td>";
									echo"<input name='isedit' type = 'hidden' value='0' id = 'isedit$borrowerid'>";
									echo"<td><input type='hidden' value='$borrowerid' name='grant_remove_co'></td>";
									echo"<td><a href='javascript:{}' onclick='document.forms.the_form".$borrowerid.".submit();'>Remove Volunteer Mentor Access</a></td>";
									echo"</form>";
									echo"</tr>";
								}?>
						</tbody>
				</table>
<?php
}else{
	echo "No Volunteer Mentor Found";
}
?>
</div>
<?php
} 
?>
</div>
<script type= "text/javascript">
function CobyCountry(dropdown){
		var selObj = document.getElementById(dropdown);
		var selIndex = selObj.selectedIndex;
		var baseURL  = selObj.options[selIndex].value;
		top.location.href = baseURL;
		return true;
	}
function editnotes(id) {
			var text = document.getElementById('editdiv'+id).innerHTML;
			document.getElementById("note"+id).value=text;
			document.getElementById("isedit"+id).value=1;
		}
</script>