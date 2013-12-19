<script type="text/javascript">
	$(function() {		
		$(".tablesorter_repayment_ins").tablesorter({sortList:[[0,0]], widgets: ['zebra'], headers: { 4:{sorter: false},5:{sorter: false}}});
	});	
function myDelete(id) 
{  
	var val=confirm("Are you sure! you want to delete");
	if(val)
	{
		document.getElementById("del_repayment_instruction"+id).submit();
	}
}
</script>
		<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['admin']['repayment_ins'] ?></h3></div>
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
if(isset($_REQUEST['ac']) && strtolower($_REQUEST['ac'])=='add')
{
?>
	<form enctype="multipart/form-data" method="post" action="process.php">
		<table class='detail'>
			<tr>
				<td><?php echo $lang['admin']['country'] ;?></td>
				<td>
					<select id="pcountry" name="country_code" >
							<option value='ALL'>For All Active Country</option>
					<?php
							$result1 = $database->countryList();
							$i=0;
							foreach($result1 as $state)
							{	?>
								<option value='<?php echo $state['code'] ?>' <?php if($form->value("country_code")==$state['code']) echo "selected" ?>><?php echo $state['name'] ?></option>
					<?php	}
					?>
						</select>
						<br/><?php echo $form->error("country_code"); ?>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td valign='top'><?php echo $lang['admin']['rep_instruction'] ;?></td>
				<td>
					<textarea style="width:80%; height:150px" name="description" ><?php echo $form->value("description"); ?></textarea><br/><div id="pdesc"><?php echo $form->error("description"); ?></div>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td></td>
				<td>
					<input class="btn" type="submit" value="Add">
					<input type="hidden" name="ac" value="add">
					<input type="hidden" name="add-repayment_instruction">
					<input type="hidden" name="user_guess" value="<?php echo generateToken('add-repayment_instruction'); ?>"/>
				</td>
			</tr>
		</table>
	</form>
	
<?php
}
else if(isset($_REQUEST['ac']) && strtolower($_REQUEST['ac'])=='edit' && !empty($_REQUEST['id']))
{
	$set=$database->getRepayment_InstructionsById($_REQUEST['id']);
	if(!empty($set))
	{
		foreach($set as $row)
		{
			$id=$row['id'];
			$country=$row['country_code'];
			$description=$row['description'];

?>
			<form enctype="multipart/form-data" method="post" action="updateprocess.php">
				<table class='detail'>
					<tr>
						<td><?php echo $lang['admin']['country'] ;?></td>
						<td>
							<select id="pcountry" name="country_code" >
									<option value='0'>Select Country</option>
							<?php
									$result1 = $database->countryList();
									$i=0;
									foreach($result1 as $state)
									{	?>
										<option value='<?php echo $state['code'] ?>' <?php if(($form->value("country_code")==$state['code']) || ($country==$state['code'])) echo "selected" ?>><?php echo $state['name'] ?></option>
							<?php	}
							?>
								</select>
								<br/><?php echo $form->error("country_code"); ?>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td valign='top'><?php echo $lang['admin']['rep_instruction'] ;?></td>
						<td>
							<textarea style="width:80%; height:150px" name="description" ><?php echo $description; ?></textarea><br/><div id="pdesc"><?php echo $form->error("description"); ?></div>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td></td>
						<td>
							<input class="btn" type="submit" value="Update">
							<input type="hidden" name="ac" value="edit">
							<input type="hidden" name="id" value="<?php echo $id; ?>">
							<input type="hidden" name="update-repayment_instruction">
							<input type="hidden" name="user_guess" value="<?php echo generateToken('update-repayment_instruction'); ?>"/>
						</td>
					</tr>
				</table>
			</form>
<?php
		}
	}
	else
	{
?>
		<form enctype="multipart/form-data" method="post" action="process.php">
			<table class='detail'>
				<tr>
					<td><?php echo $lang['admin']['country'] ;?></td>
					<td>
						<select id="pcountry" name="country_code" >
								<option value='0'>Select Country</option>
						<?php
								$result1 = $database->countryList();
								$i=0;
								foreach($result1 as $state)
								{	?>
									<option value='<?php echo $state['code'] ?>' <?php if($form->value("country_code")==$state['code']) echo "selected" ?>><?php echo $state['name'] ?></option>
						<?php	}
						?>
							</select>
							<br/><?php echo $form->error("country_code"); ?>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td valign='top'><?php echo $lang['admin']['rep_instruction'] ;?></td>
					<td>
						<textarea style="width:80%; height:150px" name="description" ><?php echo $form->value("description"); ?></textarea><br/><div id="pdesc"><?php echo $form->error("description"); ?></div>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td></td>
					<td>
						<input class="btn" type="submit" value="Add">
						<input type="hidden" name="ac" value="add">
						<input type="hidden" name="add-repayment_instruction">
						<input type="hidden" name="user_guess" value="<?php echo generateToken('add-repayment_instruction'); ?>"/>
					</td>
				</tr>
			</table>
		</form>
	<?php
	}
}
else
{
	$addurl=SITE_URL."index.php?p=11&a=13&ac=add";
	echo '<div style="float:right;padding-right:10px;">
			<a href="'.$addurl.'"><img alt="Add" title="Add" src="images/layout/icons/2.gif"/></a>
		<div style="clear:both"></div>
		</div>';
	$set=$database->getAllRepayment_Instructions();
	if(!empty($set))
	{	?>
		<table class="zebra-striped">
			<thead>
				<tr>
					<th>S. No.</th>
					<th><?php echo $lang['admin']['country']; ?></th>
					<th><?php echo $lang['admin']['rep_instruction']; ?></th>
					<th><?php echo $lang['admin']['action']; ?></th>
				</tr>
			</thead>
			<tbody>
	<?php			$i = 1;
				foreach($set as $row)
				{	

					//Country  Repayment Instructions  
					if(!isset($row['name'])) {
						$row['name'] = 'All Active Countries';
					}
					$country=$row['name'];
					$description=nl2br($row['description']);
					$id=$row['id'];
					$editurl=SITE_URL."index.php?p=11&a=13&ac=edit&id=".$id;
					$delurl=SITE_URL."index.php?p=11&a=13&ac=del&id=".$id;
					echo "<form enctype='multipart/form-data' method='post' action='updateprocess.php' id='del_repayment_instruction$id'>";
					echo "<input type='hidden' name='ac' value='del'>";
					echo "<input type='hidden' name='del-repayment_instruction'>";
					echo "<input type='hidden' name='user_guess' value='".generateToken('del-repayment_instruction')."'>";
					echo "<input type='hidden' name='id' value='$id'></form>";
					echo "<tr align='center'>";
					echo "<td>$i</td><td>$country</a></td><td>$description</td>";
					echo "<td>
							<a href='$editurl'>
								<img alt='Edit' title='Edit' src='images/layout/icons/edit.png'/>
							</a>&nbsp;&nbsp;
							<a href='javascript:void(0)' onClick='javascript:myDelete(\"$id\")'>
								<img alt='Delete' title='Delete' src='images/layout/icons/x.png'/>
							</a>
						</td>";
					echo "</tr>";
					$i++;
				}	?>
			</tbody>
		</table>
	<?php	
	}	
}
?>