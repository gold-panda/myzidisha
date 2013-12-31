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
</script>
<div class='span12'>
<div align='left' class='static'><h1>Facebook Information</h1></div><br/>
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
						<input type="hidden" name="facebook_info" id="facebook_info">
						<input type="hidden" name="user_guess" value="<?php echo generateToken('facebook_info'); ?>"/>
						<input type="hidden" name="row" id="row" value="0">
						<input class='btn' type='submit' name='report' align='right' value='Submit' />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<br/>
	<?php 
	if($v==1){
		$fbusers = $database->getFacebookInfo($date1, $date2);
	?>
		<table class="zebra-striped tablesorter_pending_endorser">
			<thead>
				<tr>
					<th>FB Name</th>
					<th>Email</th>
					<th>First Content</th>
					<th>Number of friends</th>
					<th>Zidisha Name</th>
					<th>Zidisha Email</th>
					<th>Date Linked</th>
					<th>FB Reject Reason</th>
					<th>Account Accepted</th>
					<th>IP Address</th>
					<th>Exist User</th>
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
					$exist_user='';
					if(!empty($fbdata['user_profile']['email'])){
						$email= $fbdata['user_profile']['email'];
					}
					if(!empty($fbdata['posts'])){
						if(isset($fbdata['posts'][0]['story'])){
							$fb_post=$fbdata['posts'][0]['story'].',<br/>'.$fbdata['posts'][0]['created_time'];
						}elseif(isset($fbdata['posts']['story'])){
							$fb_post= $fbdata['posts']['story'].',<br/>'.$fbdata['posts']['created_time'];
						}
					}
					if(!empty($fbdata['exist_user'])){
						$prurl= getUserProfileUrl($fbdata['exist_user']['userid']);
						$exist_user= "<a href='$prurl' target='_blank'>".$fbdata['exist_user']['username']."</a>";
					}
					if($rows['accept']==1){
						$web_acc= 'Yes';
					}else{
						$web_acc= 'No';
					}
					$fb_rej_reason=$rows['fail_reason'];
					?>
						<tr>
							<td><a href="<?php echo 'http://www.facebook.com/'.$fbdata['user_profile']['id']; ?>"><?php echo $fbdata['user_profile']['name']; ?></a></td>
							<td><?php echo $email; ?></td>
							<td><?php echo $fb_post; ?></td>
							<td><?php echo count($fbdata['user_friends']); ?></td>
							<td><a href="index.php?p=7&id=<?php echo $borrowerid;?>"><?php echo $zidisha_name; ?></a></td>
							<td><?php echo $rows['zidisha_email']; ?></td>
							<td><?php echo $link_date; ?></td>
							<td><?php echo $fb_rej_reason; ?></td>
							<td><?php echo $web_acc; ?></td>
							<td><?php echo $rows['ip_address']; ?></td>
							<td><?php echo $exist_user; ?></td>
						</tr>
		<?php	}
	}
}
	?>
	</tbody>
</table>
</div>