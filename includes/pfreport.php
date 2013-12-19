<?php
include_once("library/session.php");   // created by chetan
include_once("./editables/admin.php");
?>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#date1").datepicker();
	$("#date2").datepicker();
});
</script>
<div class="span12">
<?php
if($session->userlevel==ADMIN_LEVEL)
{     ?>

	<h1>Portfolio Report</h1>
	<form  action='updateprocess.php' method="POST">
<?php	if(isset($_GET["v"]))
		{
			$v=$_GET["v"];
		}
		if($v==1)
		{
			$date1=$_SESSION['value_array']['date1'];
			$date2=$_SESSION['value_array']['date2'];
		}
		else
		{
			$date1=$form->value("date1");
			$date2=$form->value("date2");
		}
	?>
		<input name="date1" id="date1" type="hidden" value='01/01/2009'/>To Date:
		<input  name="date2" id="date2"type="text"value='<?php echo $date2 ;?>'/>
		<input type="hidden" size="3" name="portfolioreport" id="portfolioreport">
		<input type="hidden" name="user_guess" value="<?php echo generateToken('portfolioreport'); ?>"/>
		<input class='btn' type='submit' name='report' value='Report' /><br/>
		<?php echo $form->error("fromdate"); ?>
		<?php echo $form->error("todate"); ?><br/>
		<?php echo $form->error("wrongdate1"); ?>
		<?php echo $form->error("wrongdate2"); ?>
		<br/>
		<br/>
<?php	if($v==1)
		{
			$date1=$_SESSION['value_array']['date1'];
			$date2=$_SESSION['value_array']['date2'];
			$set=$database->pfreport($date1, $date2);
			if(count($set)==0)
				echo "<B>No Data</B>";
			else
			{
				echo "<br>";
		?>
			<table class='detail'>
				<thead>
					<tr>
						<th>Country&nbsp;</th>
						<th>Currency&nbsp;</th>
						<th>Loan Disbursed&nbsp;</th>
						<th>Principal Repaid&nbsp;</th>
						<th>Amount Outstanding&nbsp;</th>
					</tr>
				</thead>
				<tbody>
		<?php		$loandisbtotal=0;
					$prirpdinustotal=0;
					$amtoutinustotal=0;
					$loandisbinustotal =0;
					foreach($set as $row)
					{
						$country=$row['CN'];
						$Currency=$row['CR'];
						$loandisb=number_format((-1) * $row['Amt'],2);
						$loandisbinus=number_format((-1) * $row['UsAmt'],2);
						$prirpd=number_format($row['AmtNew'],2);
						$prirpdinus=number_format($row['UsAmtNew'],2);
						$amtout=number_format(((-1 * $row['Amt'])-$row['AmtNew']),2);
						$amtoutinus=number_format(( (-1 * $row['UsAmt'])-$row['UsAmtNew']),2);
						$loandisbinustotal+= (-1 * $row['UsAmt']);
						$prirpdinustotal+=$row['UsAmtNew'];
						$amtoutinustotal += ((-1 * $row['UsAmt'])-$row['UsAmtNew']);

						echo '<tr>';
							echo "<td>$country</td>";
							echo "<td>$Currency</td>";
							echo "<td>$loandisb</td>";
							echo "<td>$prirpd</td>";
							echo "<td>$amtout</td>";
						echo '</tr>';
						echo '<tr>';
							echo "<td>$country</td>";
							echo "<td>USD</td>";
							echo "<td>$loandisbinus</td>";
							echo "<td>$prirpdinus</td>";
							echo "<td>$amtoutinus</td>";
						echo '</tr>';
					}	?>
				</tbody>
				<tfoot>
			<?php	echo '<tr>';
						echo "<td><b>Total</td>";
						echo "<td><b>USD</td>";
						echo "<td><b>" . number_format( $loandisbinustotal, 2) . "</td>";
						echo "<td><b>".number_format($prirpdinustotal,2)."</td>";
						echo "<td><b>".number_format($amtoutinustotal, 2)."</td>";
					echo '</tr>';
			?>
				</tfoot>
			</table>
<?php		}
		}?>
	</form>
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
}
?>
</div>