<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<html>
<body>
	<div class="span12">
	<div style="float:right"><a href="index.php?p=80">Back to Lending Groups</a></div>
	<br/><br/>
	<?php 	if(empty($session->userid)) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>In order to continue, please <a href='javascript:void' onclick='getloginfocus()'>log in</a> or <a href='index.php?p=1&sel=2'>create a member account</a></strong>.</div>";
		unset($_SESSION['usernotloggedin']);
	} else if($session->userlevel==LENDER_LEVEL){?>
		<div align='left' id='static'><h1>Start a New Lending Group</h1></div>
		<br/>
		<form action="process.php" method="POST" enctype="multipart/form-data">
			<table class='detail'>
				<tr height="20px"></tr>
				<tr>
					<td>Name of Group</td>
					<td> 
						<input type='text' style="width:300px" name='group_name' id='name' value=""><br/>
						<div id="error"><?php echo $form->error("group_name"); ?></div>
					</td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Website (Optional)</td> 
					<td> <input type="text" style="width:300px"  name='website'></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Photo or Logo</td> <td> 
					<input type='FILE' value="" name='group_photo'></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>About This Group</td> <td> <textarea style="width:300px;height:220px" name='about_group'></textarea><br/>
					<div id="error"><?php echo $form->error("about_group"); ?></div>
					</td>
				</tr>
				<tr height="20px"></tr>
				<tr>
				<input type='hidden' name='lendergroup' value='1'>
					<td></td>
					<td><input class='btn' type='submit' value='Publish Group' name='create'></td>
					
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
	<script type="text/javascript">
	<!--
		function getloginfocus() {
			document.getElementById("username").focus();
		}
	
	//-->
	</script>
