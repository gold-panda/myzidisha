<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<script type="text/javascript" src="extlibs/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

</script>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{
?>
	<div align='left' class='static'><h1>Send Emails</h1></div>
	<br/><br/>
	<form method="post" action="process.php">
		<table class='detail' style="width:auto">
			<tbody>
				<tr>
					<?php $field = $form->value("radio_useroption") ?>

					<?php if ($form->value("radio_useroption") == "Others" || empty($field)) {?>
						<td><input type="radio" name="radio_useroption" id="radio_other" value="Others" checked="checked" /></td>
					<?php } else {?>
						<td><input type="radio" name="radio_useroption" id="radio_other" value="Others" /></td>
					<?php } ?>
					<td>Custom</td>
					<?php if ($form->value("radio_useroption") == "Borrower") {?>
					<td><input type="radio" name="radio_useroption" id="radio_borrower" value="Borrower" checked="checked"  /></td>
					<?php } else {?>
						<td><input type="radio" name="radio_useroption" id="radio_borrower" value="Borrower" /></td>
					<?php } ?>
					<td>Borrower</td>
					<?php if ($form->value("radio_useroption") == "Lender"){ ?>
						<td><input type="radio" name="radio_useroption" id="radio_lender" value="Lender"  checked="checked"  /></td>
					<?php } else {?>
						<td><input type="radio" name="radio_useroption" id="radio_lender" value="Lender"  /></td>
					<?php } ?>
					<td>Lender</td>
					<?php if ($form->value("radio_useroption") == "Partner") {?>
						<td><input type="radio" name="radio_useroption" id="radio_partner" value="Partner" checked="checked"  /></td>
					<?php  } else {?>
						<td><input type="radio" name="radio_useroption" id="radio_partner" value="Partner" /></td>
					<?php } ?>
					<td>Partner</td>
					<?php if ($form->value("radio_useroption") == "All") {?>
						<td><input type="radio" name="radio_useroption" id="radio_all"  value="All" checked="checked"  /></td>
					<?php } else {?>
						<td><input type="radio" name="radio_useroption" id="radio_all"  value="All" /></td>
					<?php } ?>
					<td>All</td>
					
				</tr>
			</tbody>
		</table>
		<table class='detail'>
			<tbody>

				<tr><td>Email Address (Separate with commas):</td></tr>
				<tr>
					<td>
						<input type="text" name="emailaddress" style="width:350px;" value="<?php echo $form->value("emailaddress"); ?>"/><br/>
						<?php echo $form->error("emailaddress"); ?>
					</td>
				</tr>
				<tr><td></td></tr>

				<tr><td>Subject:</td></tr>
				<tr>
					<td>
						<input type="text" name="emailsubject" style="width:350px;" value="<?php echo $form->value("emailsubject"); ?>"/>
						<?php echo $form->error("emailsubject"); ?>
					</td>
				</tr>
				<tr><td></td></tr>

				<tr><td>Header (Optional, will display in bold above image):</td></tr>
				<tr>
					<td>
						<input type="text" id="emailheader" name="emailheader" style="width:350px;" value="<?php echo $form->value("emailheader"); ?>"/>
						
					</td>
				</tr>
				<tr><td></td></tr>


				<tr><td>Image URL (Optional):</td></tr>
				<tr>
					<td>
						<input type="text" id="image_src" name="image_src" style="width:350px;" value="<?php echo $form->value("image_src"); ?>"/>
					</td>
				</tr>
				<tr><td></td></tr>
						

				<tr><td>Body:</td></tr>
				<tr>
					<td>
						<textarea id="emailmessage" name="emailmessage" style="width:352px;height:170px"><?php echo $form->value("emailmessage"); ?></textarea><br/>
						<?php echo $form->error("emailmessage"); ?>
					</td>
				</tr>
				<tr>
				<tr><td></td></tr>

				<tr><td>Link (Optional):</td></tr>
				<tr>
					<td>
						<input type="text" id="link" name="link" style="width:350px;" value="<?php echo $form->value("link"); ?>"/>
					</td>
				</tr>
				<tr><td></td></tr>

				<tr><td>Anchor (Optional):</td></tr>
				<tr>
					<td>
						<input type="text" id="anchor" name="anchor" style="width:350px;" value="<?php echo $form->value("anchor"); ?>"/>
					</td>
				</tr>
				<tr><td></td></tr>

					<td><input type="hidden" name="sendbulkmails" value='1' /><br/>
						<input type="hidden" name="user_guess" value="<?php echo generateToken('sendbulkmails'); ?>"/><br/>
						<input class='btn' type="submit" name="Send" value="<?php echo $lang['admin']['send']; ?>"/>
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