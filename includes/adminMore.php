<?php
include_once("library/session.php");
include_once("error.php");
include_once("./editables/adminMore.php");
?>
<div class='span12'>
<?php
	if($session->userlevel == ADMIN_LEVEL)
	{	
		$set = $database->getRegisteredEmails();
?>
			<div>
			  <div style="float:left"><div align='left' class='static'><h1><?php echo $lang['adminMore']['er'] ?></h1></div></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
<?php				
	if(empty($set))
	{
		echo $lang['adminMore']['nomails'];
	}
	else
	{	?>
		<table class="zebra-striped">
			<thead>
				<tr>
					<th><?php echo $lang['adminMore']['ea'];?></th>
					<th><?php echo $lang['adminMore']['es'];?></th>
				</tr>
			</thead>
			<tbody>
	<?php		foreach($set as $row )
				{
					echo "<tr>";
					echo "<td>".$row['email']."</td>";
					echo "<td>";
					if($row['posted'] == 0)
					{
						$id = $row['id'];
						echo "<form method='post' action='updateprocess.php'>".
								"<input name='emailsent' type='hidden' />".
								"<input type='hidden' name='user_guess' value='".generateToken('emailsent')."'/>".
								"<input name='id' value='$id' type='hidden' />".
								"<input type='submit' value='Mailed' />".
							"</form>";
					}
					else
						echo "Email Sent";
					echo "</td>";
					echo "</tr>";
				}	?>
			</body>	
		</table>
<?php
	}
}	?>	
</div>