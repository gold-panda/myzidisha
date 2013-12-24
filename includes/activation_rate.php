<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#date1").datepicker();
	$("#date2").datepicker();
	$('#totalHistory').click(function() {
		$('#totalHistoryDetails').slideToggle();
	});
});
$(function() {		
	$(".tablesorter_pending_borrowers").tablesorter({sortList:[[2,1]], widgets: ['zebra']});
	});	
</script>
<div class='span12'>
<div align='left' class='static'><h1>New Member Activation Rate</h1></div><br/>

<?php 
if($session->userlevel==ADMIN_LEVEL ) {
	$v=0;
	if(isset($_GET["v"])) {
		$v=$_GET["v"];
	}
	if(isset($_SESSION['date1']) ||isset($_SESSION['date2'])) {
		$date1=$_SESSION['date1'];
		$date2=$_SESSION['date2'];
	}
	else {
		$date1=$form->value("date1");
		$date2=$form->value("date2");
	}


?>
	<form action='updateprocess.php' method="POST">
		<table class="detail">
			<tbody>
				<tr>
					<td><strong>From Date:</strong></td>
					<td><input style="width:auto" name="date1" id="date1" type="text" value='<?php echo $date1 ;?>'/><br/><?php echo $form->error("fromdate"); ?></td>
					<td><strong>To Date:</strong></td>
					<td><input style="width:auto"  name="date2" id="date2"type="text" value='<?php echo $date2 ;?>' /><br/><?php echo $form->error("todate"); ?></td>
					<td>
						<input type="hidden" name="activation_rate" id="activation_rate">
						<input type="hidden" name="user_guess" value="<?php echo generateToken('activation_rate'); ?>"/>
						<input type="hidden" name="row" id="row" value="0">
						<input class='btn' type='submit' name='report' align='right' value='Submit' />
					</td>
				</tr>
			</tbody>
		</table>
	</form><br/>
	<?php 
	if($v==1){
		$fbusers = $database->getActivationInfo($date1, $date2);
		$showingRes =count($fbusers);
		?>
		<p>Viewing <?php echo $showingRes?> Results.</p>


		<table class="zebra-striped tablesorter_pending_borrowers">
			<thead>
				<tr>
					<th>FB Profile</th>
					<th>Zidisha Profile</th>
					<th>Date FB Linked</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				foreach($fbusers as $rows) {
					$fbdata = unserialize($rows['facebook_data']);
					$borrowerid = $rows['userid'];
					$zidisha_name= $database->getNameById($borrowerid);
					$link_date= date('d M Y', $rows['date']);
					$fb_post='';
					$email='';
					$is_endorser=$database->IsEndorser($borrowerid);
					$details=$database->getBorrowerById($borrowerid);
					$assignedStatus=$details['Assigned_status'];
					$reason=$rows['fail_reason'];
					$last_modified=$details['LastModified'];
					$last_mod_date=date('M d, Y', $last_modified); 	

					$exist_user='';
					if(!empty($fbdata['exist_user'])){
						$prurl= getUserProfileUrl($fbdata['exist_user']['userid']);
						$exist_user= "<a href='$prurl' target='_blank'>".$fbdata['exist_user']['username']."</a>";
					}
					if(!empty($is_endorser)){
						$status1= 'Endorser';
					} else {
						$status1= 'Not Endorser';
					} 
					if($rows['accept']==1){

						if($assignedStatus==1) {
							$activationdate=$database->getborrowerActivatedDate($borrowerid);
							if(!empty($activationdate)){
								$activate_date=date('M d, Y', $activationdate);
							}else{
								$activate_date='';
							}
							$status = "<span style='display:none'>$activationdate</span>Activated on $activate_date";
							$status1 = '';	

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
					}else{
						$status= 'FB link rejected';
					}?>
						<tr>

							<td><a href="<?php echo 'http://www.facebook.com/'.$fbdata['user_profile']['id']; ?>"><?php echo $fbdata['user_profile']['name']; ?></a></td>

							<td><a href="index.php?p=7&id=<?php echo $borrowerid;?>"><?php echo $zidisha_name; ?></a></td>

							
							<td><?php echo $link_date; ?></td>

							<td><?php echo $status; ?><br/>

							<?php if (!empty($reason)){
								echo $reason; 
								echo $exist_user; 
							} ?>

							<br/><?php echo $status1; ?><br/>

							</td>

						</tr>
		<?php	}
	}
}
	?>
	</tbody>
</table>
</div>