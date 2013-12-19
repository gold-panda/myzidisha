<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{
	$lenders = $database->getAllLenders();
	if(isset($_GET['t']) && $_GET['t']==1)
		echo "<div align='center'><font color='green'><strong>Payment Added Successfully.</strong></font></div><br/><br/>";
	else if(isset($_GET['t'])&& $_GET['t']==2)
		echo "<div align='center'><font color='green'><strong>Donation Added Successfully.</strong></font></div><br/><br/>";
?>
			<div class="subhead2">
				<div style="float:left"><h3 class="new_subhead">Add Lending Credit</h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><?php } ?>
			  <div style="clear:both"></div>
		</div>


	<form action="process.php" method="post">
		<table class='detail'>
			<tbody>
				<tr>
					<td width='200px'><strong>Lender Username:</strong></td>
					<td>
						<select name="userid" id="userid">
							<option value="0">Select Username</option>
							<?php for($i=0; $i<count($lenders); $i++){ ?>
								<option value="<?php echo $lenders[$i]['userid']?>" <?php if($lenders[$i]['userid']==$form->value("userid")) echo "selected" ?>><?php echo $lenders[$i]['username']?></option>
							<?php } ?>
						</select>
					</td>
					<td><?php echo $form->error("userid") ?></td>
				</tr>
				<tr>
					<td><strong>Lending Credit Amount:</strong></td>
					<td><input type="text" name="amount" id="amount" value="<?php echo $form->value("amount") ?>"></td>
					<td><?php echo $form->error("amount") ?></td>
				</tr>
				<tr>
					<td><strong>Lender Donation Amount:</strong></td>
					<td><input type="text" name="donation" id="donation" value="<?php echo $form->value("donation") ?>"></td>
					<td><?php echo $form->error("donation") ?></td>
				</tr>
				<tr>
					<?php	
						$selected=$form->value("auto_lending");
					?>
					<td><strong>Enable automated lending for new lender credit amount:</strong></td>
					<td>
						<input type="radio" name="auto_lending" value='1' <?php if($selected==1)
						echo "checked='true'"?>>Yes
						<input type="radio" name="auto_lending" value='0' <?php if($selected==0)
						echo "checked='true'"?>>No
					</td>
					<td><?php echo $form->error("auto_lending") ?></td>
				</tr>
				<tr>
					<td></td>
					<td><br/>
					<input class='btn' type="Submit" value="Add Payment">
					<input type="hidden" name="addpaymenttolender">
					<input type="hidden" name="user_guess" value="<?php echo generateToken('addpaymenttolender'); ?>"/>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<h3 class='subhead'>Add Other Donation</h3>
	<form action="process.php" method="post">
		<table class='detail'>
			<tbody>
				<tr>
					<td width='200px'><strong>Donor Name:</strong></td>
					<td><input type="text" name="name" id="name" value="<?php echo $form->value("name") ?>"></td>
					<td><?php echo $form->error("name") ?></td>
				</tr>
				<tr>
					<td><strong>Donor Email:</strong></td>
					<td><input type="text" name="email" id="email" value="<?php echo $form->value("email") ?>"></td>
					<td><?php echo $form->error("email") ?></td>
				</tr>
				<tr>
					<td><strong>Donation Amount:</strong></td>
					<td><input type="text" name="donationamt" id="donationamt" value="<?php echo $form->value("donationamt") ?>"></td>
					<td><?php echo $form->error("donationamt") ?></td>
				</tr>
				<tr>
					<td></td>
					<td><br/>
					<input class='btn' type="Submit" value="Add Donation">
					<input type="hidden" name="adddonationtolender">
					<input type="hidden" name="user_guess" value="<?php echo generateToken('adddonationtolender'); ?>"/>
					</td>
				</tr>
			</tbody>
		</table>
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
}	?>
</div>