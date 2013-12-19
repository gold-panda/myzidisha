<?php 
include_once("library/session.php");// created by chetan
include_once("./editables/admin.php");
include_once("./editables/managetrans.php");
?>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{	
echo "<form  action='updateprocess.php' method='POST'>";
$v=0;
if(isset($_GET["v"]))
{
	$v=$_GET["v"];
}
if($v==1)
{
	$oustandDate=$_SESSION['value_array']['oustandDate'];
}
else
{
	$oustandDate=$form->value("oustandDate");
}
?>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#oustandDate").datepicker();
});
</script>
<script type="text/javascript">
$(function() {		
		$(".outstanding_reports").tablesorter({sortList:[[0,0]], widgets: ['zebra'], });
	});	

	
</script>
			<div class="subhead2"><?php 
							if(isset($oustandDate)&&!empty($oustandDate))
								$reports_On=date('M d, Y',strtotime($oustandDate));
							else
								$reports_On='';
				?>
			  <div style="float:left"><div align='left' class='static'><h1><?php echo 'Outstanding Reports as on '.$reports_On ?></h1></div></div>
			  <div style="clear:both"></div>
		</div>

<?php 

?>
		To Date:&nbsp&nbsp<input  type="text" name="oustandDate" id="oustandDate"type="text" value='<?php echo $oustandDate ;?>'/>
		<input type="hidden" size="3" name="outstandingReport" id="portfolioreportnew">&nbsp&nbsp
		<input type="hidden" name="user_guess" value="<?php echo generateToken('outstandingReport'); ?>"/>
		<input class='btn' type='submit' name='report' value='Report' /><br/><br/><br/>
		<?php echo $form->error("oustandDate"); ?>
		</form>
	<?php	if($v==1)
	{ $res=$database->outstandinReports($oustandDate);	
	?>
	<table class="zebra-striped outstanding_reports">
		<thead>
			<tr>
				<th><?php echo 'Borrower Name';?> </th>
				<th><?php echo 'Country';?></th>
				<th><?php echo 'Principal Disbursed (USD)';?></th>
				<th><?php echo 'Date Disbursed';?></th>
				<th><?php echo 'Principal (USD)';?></th>
				<th><?php echo 'Interest (USD)';?></th>
				<th><?php echo 'Fee (USD)';?></th>
				<th><?php echo 'Combined (P+I+F)';?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach($res as $row)
			{
				$uid = $row['borrowerid'];
				$loanid=$row['loanid'];
				$name = $row['FirstName']." ".$row['LastName'];
				$country = $row['Country'];
				$combinedAmt = $row['CombinedAmtUsd'];
				$principleAmtUsd = number_format($row['principleAmtUsd'], 3, ".", ",");
				$InterestAmtUsd = number_format($row['InterestAmtUsd'], 3, ".", ",");
				$feeAmtUsd=number_format($row['feeAmtUsd'], 3, ".", ",");
				$AmountDisburedUsd=number_format($row['AmountDisbUsd'], 3, ".", ",");
				$DateDisb = date('M d, Y',$row['DisbDate']);
				echo '<tr>';
					echo "<td width='100px'><a href='index.php?p=14&u=$uid&l=$loanid'>$name</a></td>";
					echo "<td width='100px'>$country</td>";
					echo "<td width='100px'>$AmountDisburedUsd</td>";
					echo "<td width='100px'>$DateDisb</td>";
					echo "<td width='100px'>$principleAmtUsd</td>";
					echo "<td width='100px'>$InterestAmtUsd</td>";
					echo "<td width='100px'>$feeAmtUsd</td>";
					echo "<td width='100px'>$combinedAmt</td>";
			echo '</tr>';
			}	?>
		</tbody>
	</table>
		<script language="javascript">
			function sub(page)
			{
			window.location = 'index.php?p=25&page='+page;
			}
		</script>
<?php
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
	}
?>
</div>