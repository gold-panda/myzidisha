<script type="text/javascript">
function myDelete(id) 
{  
	var val=confirm("Are you sure! you want to delete");
	if(val)
	{
		document.getElementById("del_campaign"+id).submit();
	}
}
</script>
<?php include_once("library/session.php");
			include_once("./editables/admin.php");
?>
<body>
	
	<div class="span12">
	<?php 
	if($session->userlevel== ADMIN_LEVEL){
		if(isset($_REQUEST['ac']) && strtolower($_REQUEST['ac'])=='edit' && !empty($_REQUEST['id']))
		{ 
			$set=$database->getCampaignById($_REQUEST['id']);
			$id=$set['id'];
			$name=$set['name'];
			$code=$set['code'];
			$max_use=$set['max_use'];
			$value=$set['value'];
			$message=$set['message'];
			$active=$set['active'];
			if($form->num_errors>0)
			{
				$name=$form->value('name');
				$max_use=$form->value('max_use');
				$value=$form->value('value');
				$message=$form->value('message');
				$active=$form->value('active');
			}
			?>
			<form method="post" action="updateprocess.php" >
				<table class="detail" style="width:auto">
					<tbody>
						<tr>
							<td><strong>Referral Code:</strong></td>
							<td>
								<input type="text"  name="code" value="<?php echo $code ?>" /><br/>
							</td>
						</tr>
						<tr>
							<td><strong>Value (USD):</strong></td>
							<td>
								<input type="text"  name="value" value="<?php echo $value ?>" /><br/>
								<?php echo $form->error("value"); ?>
							</td>
						</tr>
						<tr>
							<td><strong>Max use:</strong></td>
							<td>
								<input type="text"  name="max_use" value="<?php echo $max_use?>" /><br/>
								<?php echo $form->error("max_use"); ?>
							</td>
						</tr>
						<tr>
								<td><strong>Message:</strong></td>
								<td><textarea name='message' rows="4" cols="10"><?php echo $message?></textarea><br/>
								<?php echo $form->error("message"); ?></td>
						</tr>
						<tr>
							<td><strong>Status:</strong></td>
							<td><select name='active'>
									<option <?php if ($active==1) echo "selected='selected'"?>   value='1'>Active</option>
									<option  <?php if ($active==0) echo "selected='selected'"?>    value='0'>Inactive</option>
								</select><br/></td>
						</tr>
						<tr>
							<td>
								<input type="hidden" name="update-campaign" />
								<input type="hidden" name="id" value=<?php echo $id?>>
								<input type="hidden" name="user_guess" value="<?php echo generateToken('campaign'); ?>"/>
								<input class="btn" type="submit" value="Update" />
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		<? }
		else
	{
			?>
	
			<h3 class="subhead">Campaign</h3>
			<p>Add campaign</p>
			<?php
			$currencysel=0;
			if(isset($_GET["c"]))
			{
				$currencysel=$_GET["c"];
			}
			?>
			<form method="post" action="process.php">
				<table class="detail" style="width:auto">
					<tbody>
						<?php	if(isset($_SESSION['campaign_succs']))
							{	?>
								<div class="clearfix" style="color:green">
									<?php echo 'Campaign added successfully!'; ?>
								</div>
					<?php	} ?>
						<?php 
							unset($_SESSION['campaign_succs']);
							?>
						<?php	if(isset($_SESSION['update_campaign']))
							{	?>
								<div class="clearfix" style="color:green">
									<?php echo 'Campaign updated successfully!'; ?>
								</div>
					<?php	} ?>
						<?php 
							unset($_SESSION['update_campaign']);
							?>
							<?php	if(isset($_SESSION['del_campaign']))
							{	?>
								<div class="clearfix" style="color:green">
									<?php echo 'Campaign deleted successfully!'; ?>
								</div>
					<?php	} ?>
						<?php 
							unset($_SESSION['del_campaign']);
							?>
						<tr>
							<td><strong>Referral Code:</strong></td>
							<td>
								<input type="text"  name="code" value="<?php echo $form->value("code"); ?>" /><br/>
							</td>
						</tr>
						<tr>
							<td><strong>Value (USD):</strong></td>
							<td>
								<input type="text"  name="value" value="<?php echo $form->value("value"); ?>" /><br/>
								<?php echo $form->error("value"); ?>
							</td>
						</tr>
						<tr>
							<td><strong>Max use:</strong></td>
							<td>
								<input type="text"  name="max_use" value="<?php echo $form->value("max_use"); ?>" /><br/>
								<?php echo $form->error("max_use"); ?>
							</td>
						</tr>
						<tr>
								<td><strong>Message:</strong></td>
								<td><textarea name='message' rows="4" cols="10"><?php echo $form->value("message"); ?></textarea><br/>
								<?php echo $form->error("message"); ?></td>
						</tr>
						<tr>
							<td><strong>Status:</strong></td>
							<td><select name='active'>
									<option value='1'>Active</option>
									<option value='0'>Inactive</option>
								</select><br/></td>
						</tr>
						<tr>
							<td>
								<input type="hidden" name="campaign" />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('campaign'); ?>"/>
								<input class="btn" type="submit" value="Add" />
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<?php
			$set=$database->getCampaign($currencysel);
			if(!empty($set))
			{	?>
				<table class="zebra-striped">
					<thead>
						<tr>
							<th>S. No.</th>
							<th>Code</th>
							<th>Maximum use</th>
							<th>Value (USD)</th>
							<th>Message</th>
							<th>NO. of times used</th>
							<th>Status</th>
							<th>Action</th>

						</tr>
					</thead>
					<tbody>
					<h3 class='subhead'>All Campaign</h3>
				<?php

						$i = 1;
						foreach($set as $row)
						{	
							$editurl=SITE_URL."index.php?p=54&ac=edit&id=".$row['id'];
							$delurl=SITE_URL."index.php?p=54&ac=del&id=".$row['id'];
							$id=$row['id'];
							$name=$row['name'];
							$code=$row['code'];
							$max_use=$row['max_use'];
							$value=$row['value'];
							$message=nl2br($row['message']);
							$active=$row['active'];
							if($row['active']==0)
									$active="Inactive";
							else if($row['active']==1)
								$active="Active";
							$no_used=$database->countUsedbyRefcode($code);
							echo "<tr align='center'>";
							echo "	<td>$i</td>
										<td>$code</td>
										<td>$max_use</td>
										<td>$value</td>
										<td>$message</td>
										<td>$no_used</td>
										<td>$active</td>
										<td>
											<a href='$editurl'>
												<img alt='Edit' title='Edit' src='images/layout/icons/edit.png'/>
											</a>&nbsp;&nbsp;
											<form enctype='multipart/form-data' method='post' action='updateprocess.php' id='del_campaign$id'>
											<a href='javascript:void(0)' onClick='javascript:myDelete(\"$id\")'>
											<input type='hidden' name='ac' value='del'>
											<input type='hidden' name='del-campaign'>
											<input type='hidden' name='user_guess' value='".generateToken('del-campaign')."'>
											<input type='hidden' name='id' value='$id'></form>
											<img alt='Delete' title='Delete' src='images/layout/icons/x.png'/>
											</form>
											</a>
										</td>

										";
							echo "</tr>";
							$i++;
						}	?>
					</tbody>
				</table>
			<?php	
			}	
	}		
}else {
			echo "<div>";
			echo $lang['admin']['allow'];
			echo "<br />";
			echo $lang['admin']['Please'];
			echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
			echo "</div>";
} ?>
	</div>
</body>