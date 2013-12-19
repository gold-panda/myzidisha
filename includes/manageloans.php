<script type="text/javascript">
	$(function() {		
		$(".tablesorter_loans").tablesorter({sortList:[[0,0]], widgets: ['zebra'], headers: { 4:{sorter: false},5:{sorter: false}}});
	});	
</script>
		<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['admin']['manage_loans'] ?></h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']."&a=".$_GET['a'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
<?php
$loanid=0;
if(isset($_POST["ld"]) && isset($_POST["ac"]))
{	
	$loanid=$_POST["ld"];
	$activ=$_POST["ac"];
	$loanstatus=$_POST["ls"];
	$borrowerid=$_POST["uid"];
	$res=$database->ac_detivateLoan($loanid,$activ,$loanstatus,$borrowerid);
	if(!$res)
	{
		echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
		echo "<font color='white'>".$lang['admin']['unable_to_activate']."</font>";
		echo "</td></tr></table>";
	}
}
$set=$database->getAllBorroweres_Active_Deactive();
if(!empty($set))
{	?>
	<table class="zebra-striped tablesorter_loans">
		<thead>
			<tr>
				<th>S. No.</th>
				<th><?php echo $lang['admin']['Name']; ?></th>
				<th><?php echo $lang['admin']['loanamt']; ?></th>
				<th><?php echo $lang['admin']['LoanStatus']; ?></th>
				<th><?php echo $lang['admin']['LoanView']; ?></th>
				<th><?php echo $lang['admin']['activate']; ?></th>
			</tr>
		</thead>
		<tbody>
<?php			$i = 1;
			foreach($set as $row)
			{	//FirstName  LastName  loanid  borrowerid  Amount  active  reqdamt  adminDelete  
				$Name=$row['FirstName']." ".$row['LastName'];
				$borrowerid=$row['borrowerid'];
				$Amount=$row['Amount'];
				$reqdamt=$row['reqdamt'];// doller
				$loanstage=$row['active'];
				if($row['active']==LOAN_OPEN){	//0
					$loanstage=$lang['admin']['LOAN_OPEN'];
				}/*
				else if($row['active']==LOAN_FUNDED){	//1

					$loanstage=$lang['admin']['LOAN_FUNDED'];
				}
				else if($row['active']==LOAN_ACTIVE){
					$loanstage=$lang['admin']['LOAN_ACTIVE'];
				}
				else if($row['active']==LOAN_REPAID){
					$loanstage=$lang['admin']['LOAN_REPAID'];
				}
				else if($row['active']==LOAN_CANCELED){
					$loanstage=$lang['admin']['LOAN_CANCELED'];
				}
				else if($row['active']==LOAN_DEFAULTED){
					$loanstage=$lang['admin']['LOAN_DEFAULTED'];
				}
				else if($row['active']==LOAN_EXPIRED){
					$loanstage=$lang['admin']['LOAN_EXPIRED'];
				}
				*/
				$deleted=$row['adminDelete'];
				$id=$row['loanid'];
				echo "<tr align='center'>";
				echo "<td>$i</td><td><a href='index.php?p=12&u=$borrowerid'>$Name</a></td><td>$reqdamt </td>";
				echo"<td>$loanstage</td>";
				if($deleted==0)
				{
					echo"<td><a href='index.php?p=14&u=$borrowerid&l=$id'>".$lang['admin']['LoanView']."</a></td>";
				}
				else{
					echo"<td>".$lang['admin']['LoanView']."</td>";
				}
				echo "<td><form method='post' action=''><input type='hidden' name='p' value='11' />
				<input type='hidden' name='a' value='12' /><input type='hidden' name='uid' value='".$borrowerid."' /><input type='hidden' name='ld' value='".$id."'	/><input type='hidden' name='ls' value='".$row['active']."' />";
				
				if($deleted==0){
					echo "<input border='0' type='image' name='sEditMinAmount' value='Submit'  alt='Active' title='Active' src='images/layout/icons/tick.png'/>
					<input type='hidden' name='ac' value='1' />";
				}
				else{
					echo "<input border='0' type='image' name='sEditMinAmount' alt='Deativeted' value='Submit' title='Deativeted' src='images/layout/icons/x.png'/>
					<input type='hidden' name='ac' value='0' />";
				}
				echo"</form></td>";
				echo "</tr>";
				$i++;
			}	?>
		</tbody>
	</table>
<?php	
}	?>