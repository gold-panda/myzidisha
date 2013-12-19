<?php
include_once("library/session.php");
include_once("./editables/admin.php");
$showShareBox=0;
?>
<?php
	$gid = 0;
	if(isset($_GET['gid'])) {
		$gid = $_GET['gid'];
	}
	$userid=$session->userid;
	$useremail = $session->userinfo['email'];
	$grpdetails = $database->getlendingGrouops($gid);
	$gname = $grpdetails['name'];
	$gwebsite = $grpdetails['website'];
	$grpimg = urlencode($grpdetails['image']);
	$abt_grp = stripslashes($grpdetails['about_grp']);
	$grp_leader= $grpdetails['grp_leader'];

 	
?>
<div class='span12'>
<div style="float:right"><a href="index.php?p=104">Back to Borrowing Groups</a></div>


<?php 

if(isset($_SESSION['usernotloggedin'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Please <a href='javascript:void' onclick='getloginfocus()'>log in</a> to join this group</a></strong>.</div>";
		unset($_SESSION['usernotloggedin']);
} ?>
<?php if(isset($_SESSION['alreadyjoined'])) {
		echo"<div style='text-align:center;font-size:16px;color:red;'><strong>You are already a member of this group</a></strong>.</div>";
		unset($_SESSION['alreadyjoined']);
} ?>
<?php if(isset($_SESSION['grp_joined'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Congratulations! You are now a member of the $gname Borrowing Group.</a></strong></div>";
		unset($_SESSION['grp_joined']);
} ?>
<?php if(isset($_SESSION['groupcreated'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Congratulations!
You have successfully created the $gname Borrowing Group.</a></strong></div>";
		unset($_SESSION['groupcreated']);
} ?>
<?php if(isset($_SESSION['leadernotselected'])) {
		echo"<div style='text-align:center;font-size:16px;color:red;'><strong>Please transfer leadership before leaving this group.</a></strong></div>";
		unset($_SESSION['leadernotselected']);
} ?>
<?php if(isset($_SESSION['updategroup'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Your changes have been saved.</a></strong></div>";
		unset($_SESSION['updategroup']);
} ?><br/>
	<h3 STYLE="DISPLAY:INLINE"><?php echo $gname?></h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($grp_leader===$session->userid || $session->userlevel==ADMIN_LEVEL){ 
		echo '<a href="index.php?p=106&gid='.$gid.'">Edit Group</a>'; 
		
	}
	?>

	<div>
		<?php if (file_exists(USER_IMAGE_DIR.$grpimg)){ ?> 
		<div style="float:right">
			<img src = <?php echo SITE_URL."images/client/".$grpimg?> width='330px'	>
		</div>
		<?php } ?> 
		<div style="float:left">
			<table class="detail">
				<tbody>	
					<tr height="30px"></tr>
					<tr>
						<td style='width:50px'><strong>Website:</strong></td>
						<?php 
							$website = $gwebsite;
							$parsed_url = parse_url($gwebsite);
							if(!isset($parsed_url['scheme'])) {
								$website = "http://" . $gwebsite;
							  }	
						?>
						<td><a href='<?php echo $website?>' target='_blank'><?php echo $gwebsite;?></a></td>
					</tr>
					<tr height="20px"></tr>
					<tr>
						<td><strong>About Group:</strong></td>
						<td style="width:200px;"><?php echo nl2br($abt_grp);?></td>
					</tr>
					<!-- <tr>
						<td><strong><?php echo $lang['profile']['date_active'] ?>:</strong></td>
						<td><?php echo $activedate;?></td>
					</tr> -->

		<?php 
			$disablejoin = '';
			$members = $database->getLendingGroupMembers($gid);
			$investedamt = 0;
			$active_investamtDisplay = 0;
			foreach($members as $member) {
				$mids[] = $member['member_id'];
				$investedamt += $database->totalAmountLend($member['member_id']);
				$active_investamtDisplay += $database->amountInActiveBidsDisplay($member['member_id']);
			}
			$grpTotalImpact = 0;
			if(!empty($mids)) {
				$ids = implode(',', $mids);
				$gImpact = $database->getGroupImpact($ids);
				$grpTotalImpact = number_format($active_investamtDisplay + $investedamt + $gImpact['invite_AmtLent']+$gImpact['Giftrecp_AmtLent'], 2, '.', ',');
			}
			
				?><tr height="20px"></tr>
				<tr>
					<td style="width:150px"><strong>Total Group Impact:</strong></td>
					<td>USD <?php echo $grpTotalImpact?></td>
				</tr>
					</tbody>
			</table>
<br/><br/><br/>


			<form method="post" action="process.php">
				<input type="hidden" name="groupid" value="<?php echo $gid?>">
			<?php 

			$isinvited = $database->IsInvitedToGroup($email, $gid);
				
			if($isinvited > 0) {

				 echo"<input type='hidden' name='joinLendingGroup'>";
				 echo"<input type='submit' class='btn' value='Join this Group'>";
			} 

			?>
			</form>
		

		</div>

		<div style="clear:both"></div>
				<br/>
				<br/>
				<?php if(!empty($members )) { 
				echo"<table class='detail'>";
				echo "<h3 class='subhead'>Members</h3>";
				foreach($members as $member) {
					$memberid = $member['member_id'];
					$username = $member['username'];
					// Anupam 10-Jan-2013 we are no more showing total individual impact in lending groups as per 'Quick repair: Lending team impacts' email
					/*$impact = $database->getMyImpact($memberid);
					$total_invested=$database->totalAmountLend($memberid);
					$active_investamtDisplay = $database->amountInActiveBidsDisplay($memberid);
					$totlat_impact = number_format($active_investamtDisplay + $total_invested + $impact['invite_AmtLent']+$impact['Giftrecp_AmtLent'], 2, '.', ',');*/
					$groupleader='';
					$trnferleader='';
					$prurl = getUserProfileUrl($memberid);
					if($member['member_id']==$grp_leader) {
						$groupleader = ' (Group Leader)';
					}
					if($session->userid == $grp_leader && $grp_leader == $member['member_id']) {
						$trnferleader = "<a href='includes/transffer_leader.php?gid=$gid' rel='facebox'>Transfer Leadership</a>";	
					}
					echo"<tr><td width='200px'>
								<a href='$prurl' target='_blank'>$username</a>$groupleader
							</td>
							<td>
								$trnferleader
							</td>
						</tr>";		
				}
		?>
		</table>
		<?php } ?>

	</div>
<div style='margin-top:15px;'>
<br/><br/>
	Would you like to receive email notifications when new messages are posted at the <?php echo $gname?> Lending Group's Message Board?
	<?php 
		$grpmsgnotify = $database->getlendergroupnotify($session->userid,$gid);
		
	?>
	<input type="radio" value='<?php echo $gid?>' name="GroupmsgBoardNotification" id='GroupmsgNotify_yes' <?php if($grpmsgnotify!='0') echo 'checked';?>>Yes
	<input type="radio" value='0' name="GroupmsgBoardNotification" id='GroupmsgNotify_no' <?php if($grpmsgnotify=='0') echo 'checked';
	?>>No
	&nbsp;&nbsp;&nbsp;&nbsp;<span id='response' ></span>
</div>
<br/><br/>
<?php 
		$fb=0;

			include_once("./editables/profile.php");
			$path=	getEditablePath('profile.php');
			include_once("editables/".$path);
			include_once("includes/group_comments.php");
?>
</div>

<script type="text/javascript">
	<!--
		function getloginfocus() {
			document.getElementById("username").focus();
		}
	
	//-->
	</script>
<script type="text/javascript">
<!--
$(document).ready(function(){
	$("#GroupmsgNotify_yes").click(
		function(event){
			var value=$("#GroupmsgNotify_yes").val();
			var userid="<?php echo $session->userid; ?>";
			document.getElementById("response").innerHTML="<img src='images/layout/icons/ajax-loader.gif' border='0' alt=''>";
			var data = "value="+value+"&userid="+userid+"&GroupmsgNotify="+'true'+"&grpid="+"<?php echo $gid?>";
			$.ajax({
				url: 'process.php',
				type: 'post',
				dataType: 'json',
				data: data,
				success: function() {
					
					document.getElementById("response").innerHTML="<font color=green>saved</font>";
				}
			});
		}
	);
	$("#GroupmsgNotify_no").click(
		function(event){
			var value=$("#GroupmsgNotify_no").val();
			var userid="<?php echo $session->userid; ?>";
			var data = "value="+value+"&userid="+userid+"&GroupmsgNotify="+'true'+"&grpid="+"<?php echo $gid?>";
			document.getElementById("response").innerHTML="<img src='images/layout/icons/ajax-loader.gif' border='0' alt=''>";
			$.ajax({
				url: 'process.php',
				type: 'post',
				dataType: 'json',
				data: data,
				success: function() {
					document.getElementById("response").innerHTML="<font color=green>saved</font>";
				}
			});
		}
	);
});
//-->
</script>