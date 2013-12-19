<?php
	include_once("library/session.php");
	include_once("./editables/withdraw.php");
	include_once("error.php");

 if(isset($_GET['err']) && $_GET['err'] >0){
		echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
		echo "<font color='white'>".$errorArray[$_GET['err']]."</font>";
		echo "</td></tr></table>";
 }
if($session->userlevel  == LENDER_LEVEL  ){
	
	$availAmt = $session->amountToUseForBid($userid);
	$investAmt = $database->amountInActiveBids($session->userid);
?>
<table width="100%" border="0">
<tr><td width="50%">
<b><?php echo $lang['payment']['add_fund'];?></b>
<form action="library/paypal/getMoney.php" method="post">  
  <input type="text" name="amount" value=""> 
  <input type="submit" value=<?php echo $lang['payment']['add'];?>> Amount must be as 100.00 
</form> 
<br/><br/><br/>
</td></tr>
<tr><td width="100%">
<?php
echo ''.$lang['payment']['total_avl_amt'].' USD '.number_format($availAmt, 2, ".", ",");
echo ' <br/>Amount invested in different active bids: USD ' . number_format($investAmt, 2, ".", ",") . '<br/><br/>';
?>
</td></tr>
<tr><td width="100%">
<?php 
	echo "<h3>".$lang['payment']['widrawal_status']."</h3>";
$withdrawAmt = $database->getwithdraw($session->userid);
	if($withdrawAmt){ ?>
		<table cellspacing="1" width="99%" class="tablesorter {sortlist: [[0,0],[4,0]]}">
		<thead>
		<tr>
		<th><? echo $lang['payment']['date'];?></th>
		<th><? echo $lang['payment']['amount'];?></th>
		<th></th>
		</tr>
		</thead>
		<tbody>
	<?php
			foreach( $withdrawAmt as $rows ){//id  userid  amount  date  paid 
								
			echo "<tr>";
				echo "<td style='text-align:center'>".date('m/d/Y', $rows['date'])."</td>";
				echo "<td style='text-align:center'>".number_format($rows['amount'], 2, ".", ",")."</td>";
				if($rows['paid'] == 0)
					echo "<td style='text-align:center'>" .$lang['payment']['Pending']."</td>";
				else
					echo "<td style='text-align:center'>".$lang['payment']['Completed']."</td>";
				
			echo "</tr>";
			}
	?>
	</tbody>
		</table>
		<?php 
	}
	
	?>
</td></tr>
<tr><td width="50%">
<b><?php echo $lang['payment']['withdraw_fund'];?></b>
<form action="updateprocess.php" method="post">  
  <input type="text" name="amount" value="<?php echo $form->value("amount"); ?>"> 
  <input type="hidden" name="withdraw">
  <input type="submit" value=<?php echo $lang['payment']['withdraw'];?>>  
</form> <div id="berror"><?php echo $form->error("amount"); ?></div>
<br/><br/><br/>

</td></tr></table>

<?php
	}else if($session->userlevel  == ADMIN_LEVEL ){
	echo "<h3>".$lang['payment']['pending_Payments']."</h3>";
	$set=$database->getwithdraw();
		if(empty($set)){
		echo "There are no withdraw listed";
		}else{							
		?>
		<table cellspacing="1" width="99%" class="tablesorter {sortlist: [[0,0],[4,0]]}">
		<thead>
		<tr>
		<th><? echo $lang['payment']['date'];?></th>
		<th><? echo $lang['payment']['name'];?></th>
		<th><? echo $lang['payment']['amount'];?></th>
		<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($set as $row ){//userid  amount  date  paid  
		$date=date('m/d/Y', $row['date']);
		$rowid=$row['id'];
		$userid=$row['userid'];
		$Detail=$database->getEmail($userid);
		$name=$Detail['name'];
		$email=$Detail['email'];
		$amount=number_format($row['amount'], 2, ".", ",");
		$active=$row['paid'];
		if($active==0){
			$active1=
			"<form method='post' action='updateprocess.php'>".
			"<input name='paywithdraw' type='hidden' />".
			"<input name='lenderid' value='$userid' type='hidden' />".
			"<input name='rowid' value='$rowid' type='hidden' />".
			"<input name='amount' value='$amount' type='hidden' />".
			"<input type='submit' value=".$lang['payment']['pay']." />".
			"</form>";
		}else{
			$active1=$lang['payment']['Accepted'];
		}
																
		echo '<tr>';
		echo "<td>$date</td>";
		echo "<td><a href='index.php?p=12&u=".$userid."'>$name</a></td>";
		echo "<td>$amount</td>";
		echo "<td width='5%'>$active1</td>";
		echo '</tr>';
		}
		?>
		</tbody>
		</table>
		<?php

		}
		}
?>