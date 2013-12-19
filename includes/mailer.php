<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<script type="text/javascript" src="extlibs/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	// Default skin
	/*tinyMCE.init({
		// General options
		mode : "exact",
		elements : "emailmessage",
		theme : "advanced",
        skin : "o2k7",
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|forecolor,backcolor",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
		// Theme options
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,


	});*/

</script>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{
?>
	<div align='left' class='static'><h1><?php echo $lang['admin']['send_email_to'] ?></h1></div>
	<form method="post" action="process.php">
		<table class='detail' style="width:auto">
			<tbody>
				<tr>
					<?php $field = $form->value("radio_useroption") ?>
					<?php if ($form->value("radio_useroption") == "Borrower" || empty($field) ) {?>
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
					<?php if ($form->value("radio_useroption") == "Others") {?>
						<td><input type="radio" name="radio_useroption" id="radio_other" value="Others" checked="checked" /></td>
					<?php } else {?>
						<td><input type="radio" name="radio_useroption" id="radio_other" value="Others" /></td>
					<?php } ?>
					<td>Others</td>
				</tr>
			</tbody>
		</table>
		<table class='detail'>
			<tbody>
				<tr><td><?php echo $lang['admin']['email_address']; ?></td></tr>
				<tr>
					<td>
						<input type="text" name="emailaddress" style="width:350px;" value="<?php echo $form->value("emailaddress"); ?>"/><br/>
						<?php echo $form->error("emailaddress"); ?>
					</td>
				</tr>
				<tr><td></td></tr>
				<tr><td><?php echo $lang['admin']['email_subject']; ?></td></tr>
				<tr>
					<td>
						<input type="text" name="emailsubject" style="width:350px;" value="<?php echo $form->value("emailsubject"); ?>"/>
						<input type="hidden" name="sendbulkmails" value='1' />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('sendbulkmails'); ?>"/><br/>
						<?php echo $form->error("emailsubject"); ?>
					</td>
				</tr>
				<tr><td></td></tr>
				<tr><td><?php echo $lang['admin']['email_message']; ?></td></tr>
				<tr>
					<td>
						<textarea id="emailmessage" name="emailmessage" style="width:352px;height:170px"><?php echo $form->value("emailmessage"); ?></textarea><br/>
						<?php echo $form->error("emailmessage"); ?>
					</td>
				</tr>
				<tr><td><input class='btn' type="submit" name="Send" value="<?php echo $lang['admin']['send']; ?>"/></td></tr>
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