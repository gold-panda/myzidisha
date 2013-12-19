<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['admin']['all_currency'] ?></h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']."&a=".$_GET['a'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
<?php
$currencyid=0;
if(isset($_POST["cu"])&&isset($_POST["cu"]))
{
	$currencyid=$_POST["cu"];
	$activ=$_POST["ac"];
	$database->activateNewCurrency($currencyid,$activ);
	if(isset($_SESSION['Readonly'])) {
						unset($_SESSION['Readonly']);
						echo "<div style='width:100%; background-color:red;color:white;text-align:center'>This is a read-only account, no changes can be made with this user!</div>";
				}
}
$set=$database->getAllCurrency(0);
if(!empty($set))
{	?>
	<table class="zebra-striped">
		<thead>
			<tr>
				<th>S. No.</th>
				<th><?php echo $lang['admin']['country']; ?></th>
				<th><?php echo $lang['admin']['capital']; ?></th>
				<th><?php echo $lang['admin']['currencyname']; ?></th>
				<th><?php echo $lang['admin']['currency']; ?></th>
				<th><?php echo $lang['admin']['active']; ?></th>
			</tr>
		</thead>
		<tbody>
<?php
			$i = 1;//id  country  capital  currencyname  Currency  active  
			foreach($set as $row)
			{	
				$country=$row['country'];
				$capital=$row['capital'];
				$currencyname=$row['currencyname'];
				$Currency=$row['Currency'];
				$active=$row['active'];
				$id=$row['id'];
				echo "<tr align='center'>";
				echo "<td>$i</td><td>$country</td><td>$capital</td><td>$currencyname</td><td>$Currency</td>";
				echo "<td><form method='POST' action=''><input type='hidden' name='p' value='11' />
				<input type='hidden' name='a' value='11' /><input type='hidden' name='cu' value='".$id."' />";
				if($active==1)
				{
					echo "<input id='sEditMinAmount' border='0' type='image' name='sEditMinAmount' value='Submit'  alt='Active' title='Active' src='images/layout/icons/tick.png'/><input type='hidden' name='ac' value='0' />";
				}
				else
				{
					echo "<input id='sEditMinAmount' border='0' type='image' name='sEditMinAmount' alt='InActive' value='Submit' title='InActive' src='images/layout/icons/x.png'/><input type='hidden' name='ac' value='1' />";
				}
				echo"</form></td>";
				echo "</tr>";
				$i++;
			}	?>
		</tbody>
	</table>
<?php
}	?>