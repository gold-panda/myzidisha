<?php 
include_once("library/session.php");                     // created by chetan
?>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{
	$set=$database->getRescheduledLoans();
?>
			<div>
			  <div style="float:left"><div align='left' class='static'><h1>Rescheduled Loans</h1></div></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			<div style="clear:both"></div>
		</div>
	<table class="zebra-striped">
		<thead>
			<tr>
				<th>Sr.<br/>No.</th>
				<th>Borrower &nbsp;</th>
				<th>Reschedule Reason</th>
				<th >Original<br/>Repayment<br/>Period &nbsp;</th>
				<th >New<br/>Repayment<br/>Period</th>
				<th>Rescheduled<br/>Date</th>
			</tr>
		</thead>
		<tbody>
<?php		$i = 1;
			$periodArr=array();
			foreach($set as $row)
			{
				$loan_id=$row['loan_id'];
				$borrower_id=$row['borrower_id'];
				if(!isset($periodArr[$loan_id]))
				{
					$periodArr[$loan_id]['period']=$database->getOriginalLoanPeriod($borrower_id, $loan_id);
				}
				$new_repay_prd=$row['period'];
				$reschedule_reason=nl2br($row['reschedule_reason']);
				$name = $database->getNameById($borrower_id);
				$loanprurl = getLoanprofileUrl($borrower_id, $loan_id);
				$date=$row['date'];
				echo "<tr>";
				echo "<td>$i</td>";
				echo"<td><a href='$loanprurl'>$name</a></td>";
				echo "<td style='max-width:265px;word-wrap: break-word'>$reschedule_reason</td>";
				echo "<td>".$periodArr[$loan_id]['period']."</td>";
				echo "<td>$new_repay_prd</td>";
				echo "<td>".date('M d, Y',$date)."</td>";
				echo "</tr>";
				$periodArr[$loan_id]['period']=$row['period'];
				$i++;
			}	?>
		</tbody>
	</table>
<?php
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