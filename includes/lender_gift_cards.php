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
$cards= $database->getLenderGiftCards($userid);
$invite_details = $database->getLenderImpact($userid);

?>
<div class='span12'>
<div align='left' class='static'><h1>Track My Gift Cards</h1></div><br/>
		
<table class = 'detail'>
	<tr>
		<td width="40%">Gift Cards Gifted:</td>
		<td>
			<?php echo $invite_details['gift_card_purchased'];?>						
		</td>
	<td>
		<strong><a href="microfinance/gift-cards.html" style="font-size:16px">Give a Gift Card</a></strong>
	</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td>
			Gift Cards Redeemed by My Recipients:
		</td>
		<td><?php echo $invite_details['gift_card_redeemed'];?></td>
	</tr>
</table>
	
<br/><br/><br/>

<table class="zebra-striped tablesorter_pending_borrowers">
		<thead>
			<tr>
				<th>Date Gifted</th>
				<th>Name</th>
				<th>Delivery Method</th>
				<th>Recipient Email</th>
				<th>Amount</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($cards as $rows) {
				if($rows['claimed']==1){
					$status = "Redeemed";
				}else{
					$status = "Not Yet Redeemed";

				}?>

				<tr>
					<td>
						<span style='display:none'><?php echo $rows['date'] ?></span>
						<?php echo date('F d, Y',$rows['date']) ?>
					</td>
					<td><?php echo $rows['to_name'] ?></td>
					<td><?php echo ucfirst($rows['order_type']) ?></td>
					<td><?php echo $rows['recipient_email']?></td>
					<td><?php echo "$".number_format($rows['card_amount']) ?></td>
					<td><?php echo $status; ?></td>
				</tr>
	<?php	}
		?>
		</tbody>
	</table>

</div>