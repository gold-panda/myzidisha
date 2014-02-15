<script type="text/javascript" src="includes/scripts/eepztooltip.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("#stay-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
	$("#stay-target-2").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
});
	$(function() {		
		$(".tablesorter_pending_borrowers").tablesorter({sortList:[[0,1]], widgets: ['zebra']});
	});	
</script>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>

<?php
include_once("./editables/invite.php");
$path=	getEditablePath('invite.php');
include_once("editables/".$path);

$userid=$session->userid;
$invite_details = $database->getLenderImpact($userid);
$invitedmember= $database->getInvitedMember($userid);

?>
<div class='span12'>
<div align='left' class='static'><h1>Track My Invites</h1></div><br/>
		
<table class = 'detail'>
	<tr>
		<td width="25%">Invites Sent:</td>
		<td>
			<?php echo $invite_details['invite_sent'];?>						
		</td>
		<td>
			<strong><a href="index.php?p=30" style="font-size:16px">Send Invite</a></strong>
		</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td>Invites Accepted:</td>
		<td><?php echo $invite_details['invite_accptd']?></td>
		<td></td>
	</tr>
</table>
	
<br/><br/><br/>

<table class="zebra-striped tablesorter_pending_borrowers">
		<thead>
			<tr>
				<th>Date Invited</th>
				<th><?php echo $lang['invite']['email'] ?></th>
				<th><?php echo $lang['invite']['status'] ?></th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($invitedmember as $rows) {
				if($rows['invitee_id']==0){
					$status = $lang['invite']['invit_not_acc'];
				}else{
					$invitee_id = $rows['invitee_id'];
					$join_date = $database->getJoinDate($invitee_id);
					//$status = $rows['invitee_id']; //
					$join_date_disp = date('F d, Y', $join_date);
					$status = "Joined Zidisha on ".$join_date_disp;
				}?>

				<tr>
					<td>
						<span style='display:none'><?php echo $rows['date'] ?></span>
						<?php echo date('F d, Y',$rows['date']) ?>
					</td>
					<td><?php echo $rows['email']?></td>
					<td><?php echo $status; ?></td>
				</tr>
	<?php	}
		?>
		</tbody>
	</table>

</div>