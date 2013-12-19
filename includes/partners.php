<script type="text/javascript">
	$(function() {		
		$(".tablesorter_partners").tablesorter({sortList:[[0,0]], widgets: ['zebra'], });
	});	
</script>
<?php
if(isset($_GET['v']))
{
	if($_GET['v']==1)
		echo "<p style='color:green;font-weight:bold;text-align:center'>".$lang['admin']['partner_activated']."</p>";
	if($_GET['v']==2)
		echo "<p style='color:green;font-weight:bold;text-align:center'>".$lang['admin']['partner_deactivated']."</p>";
	else
		echo "<p style='color:red;font-weight:bold;text-align:center'>".$lang['admin']['error_msg']."</p>";
}
$set=$database->getAllpartners();
?>
		<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['admin']['all_partners'] ?></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']."&a=".$_GET['a']?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>

<?php
if(!empty($set))
{	?>
	<table class="zebra-striped tablesorter_partners">
		<thead>
			<tr>
				<th><?php echo $lang['admin']['Name'];?></th>
				<th><?php echo $lang['admin']['Location'];?></th>
				<th><?php echo $lang['admin']['Post_Address'];?></th>
				<th><?php echo $lang['admin']['website'];?></th>
				<th><?php echo $lang['admin']['activate_button'];?></th>
			</tr>
		</thead>
		<tbody>
<?php
			foreach($set as $row )
			{
				$userid=$row['userid'];
				$name=$row['name'];
				$post=$row['postaddress'];
				$city=$row['city'];
				$country=$row['country'];
				$active=$row['active'];
				$active1="";
				if($active==0)
				{
					$active1="<form name='activeform".$userid."' method='post' action='process.php'>".
					"<input name='activatePartner' type='hidden' />".
					"<input type='hidden' name='user_guess' value='".generateToken('activatePartner')."'/>".
					"<input name='partnerid' value='$userid' type='hidden' />".
					"<a href='javascript:void(0)' style='color:red' onclick='document.forms.activeform".$userid.".submit()'>".$lang['admin']['activate_button']." </a>".
					"</form>";
				}
				else
				{
					$active1=//"<a href=#>Deactivate</a>";
					"<form name='deactiveform".$userid."' method='post' action='process.php'>".
					"<input name='deactivatePartner' type='hidden' />".
					"<input type='hidden' name='user_guess' value='".generateToken('deactivatePartner')."'/>".
					"<input name='partnerid' value='$userid' type='hidden' />".
					"<a href='javascript:void(0)' style='color:red' onclick='document.forms.deactiveform".$userid.".submit()'>".$lang['admin']['deactivate_button']."</a>".
					"</form>";
				}
				$delete_btn2="<form name='del".$userid."' method='post' action='process.php'>".
				"<input name='deletePartner' type='hidden' />".
				"<input type='hidden' name='user_guess' value='".generateToken('deletePartner')."'/>".
				"<input name='partnerid' value='$userid' type='hidden' />".
				"<a href='javascript:void(0)' style='color:red' onclick='javascript:mySubmit(3,del$userid);'>".$lang['admin']['delete_button']."</a>".
				"</form>";
				/* 3 for calling javascript function for delettion a  partner */

				$website=$row['website'];
				echo '<tr>';
					echo "<td>$name</td>";
					echo "<td>$city<br />$country</td>";
					echo "<td>$post</td>";
					echo "<td>$website</td>";
					if($database->isDeleteablePartner($userid))
						echo "<td>$active1<br>$delete_btn2</td>";
					else
						echo "<td>$active1</td>";
				echo '</tr>';
			}	?>
		</tbody>
	</table>
<?php
} ?>