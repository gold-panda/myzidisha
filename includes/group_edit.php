<?php
 if(isset($_GET['gid'])){
 $gid = $_GET['gid'];
 $grp_details= $database->getlendingGrouops($gid);
 $userid=$session->userid;
 $gname = $grp_details['name'];
 $gwebsite = $grp_details['website'];
 $grpimg = urlencode($grp_details['image']);
 $abt_grp = $grp_details['about_grp'];
 $grp_leader= $grp_details['grp_leader'];
}
?>
<html>
<body>
<div class="span12">
<div style="float:right"><a href="index.php?p=80">Back to Lending Groups</a></div>
<br/><br/>
<?php 	if(empty($session->userid)) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>In order to continue, please <a href='javascript:void' onclick='getloginfocus()'>log in</a> or <a href='index.php?p=1&sel=2'>create a member account</a></strong>.</div>";
		unset($_SESSION['usernotloggedin']);
	}
else if($grp_leader===$session->userid){?>
		<div align='left' id='static'><h1>Edit Lending Group</h1></div>
		<BR/>
		<form action="updateprocess.php" method="POST" enctype="multipart/form-data">
			<table class='detail'>
				<tr height="20px"></tr>
				<tr>
					<td>Name of Group</td>
					<td> 
						<input type='text' style="width:300px" name='group_name' id='name' value="<?php echo $gname; ?>"><br/>
						<div id="error"><?php echo $form->error("group_name"); ?></div>
					</td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Website (Optional)</td> 
					<td> <input type="text" style="width:300px"  name='website' value="<?php echo $gwebsite; ?>"></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Photo or Logo</td> <td>
					<input type='FILE' name='group_photo'><img src = <?php echo SITE_URL."images/client/".$grpimg?> width='80px'></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>About This Group</td> <td> <textarea style="width:300px;height:220px" name='about_group' ><?php echo $abt_grp; ?></textarea><br/>
					<div id="error"><?php echo $form->error("about_group"); ?></div>
					</td>
				</tr>
				
				<tr height="20px"></tr>
				<tr>
				<input type='hidden' name='updatelendergroup' value="<?php echo $gid; ?>" >
					<td></td>
					<td><input class='btn' type='submit' value='Save Changes' name='create'></td>
					
				</tr>
			</table>
		</form>
	</div>
	<?php }else {
				echo "<div>";
				echo $lang['admin']['allow'];
				echo "<br />";
				echo $lang['admin']['Please'];
				echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
				echo "</div>";
			}?>
</body>
</html>

