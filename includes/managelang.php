<?php 
include_once("library/session.php");                     // created by chetan
include_once("./editables/admin.php");
?>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{	
	if(isset($_POST['langid']))
	{
		$langid= $_POST['langid'];
		$act= $_POST['ac'];
		$res=$database->setActiveLanguage($langid, $act);
		if($res==1)
			echo "<div align='center' style='color:green'>Your preferences is saved</div>";
		else
			echo "<div align='center' style='color:red'>Sorry some problem occurred please try again</div>";
	}
	$set=$database->getAllLanguages();
	?>
	<div>
			  <div style="float:left"><h3 class="new_subhead"><div align='left' class='static'><h1>Language Selection</h1></div></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
	<table class="zebra-striped">
		<thead>
			<tr>
				<th width="20px">S. No.</th>
				<th width="80px">Language</th>
				<th width="20px">Language Code</th>
				<th width="20px">Active</th>
			</tr>
		</thead>
		<tbody>
<?php
			$i = 1;
			foreach($set as $row)
			{
				$langcode=$row['langcode'];
				$language=$row['lang'];
				$active=$row['active'];
				$id=$row['id'];
				echo "<tr align='center'>";
				echo "<td>$i</td><td>$language</td><td>$langcode</td>";
				echo "<td><form method='post' action=''>
							<input type='hidden' name='langid' value='".$id."' />";
							if($active==1)
							{
								echo "<input border='0' type='image' value='Submit'  alt='Active' title='Active' src='images/layout/icons/tick.png'/>
								<input type='hidden' name='ac' value='0' />";
							}
							else
							{
								echo "<input border='0' type='image' alt='InActive' value='Submit' title='InActive' src='images/layout/icons/x.png'/>
								<input type='hidden' name='ac' value='1' />";
							}
				echo"</form></td>";
				echo "</tr>";
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
}
?>
</div>