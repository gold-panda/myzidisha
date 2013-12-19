<?php
include_once("library/session.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Zidisha</title>
<style type="text/css">
body
{
font-family: "arial"; 
font-size:11px;
}
</style>
</head>
<body>
<?php
if(isSet($_GET['v']) && $_GET['v']==0)
{
	if($form->error("passError"))
		echo "<div id='error' align='center'>".$form->error("passError")."</div>";
	else if($form->error("emailError"))
		echo "<div id='error' align='center'>".$form->error("emailError")."</div>";
	else
		echo "<div id='error' align='center'>There is some problem in connecting to server please try again.</div>";
}
else if(isSet($_GET['v']) && $_GET['v']==1)
{
	$flag=1;
	$emailids = $_SESSION['contacts'];
	unset($_SESSION['contacts']);
	$total = count($emailids);
		
	echo "<div id='error' align='center'><font color='green'>You have successfully imported $total contacts.</font></div><br/>";
	echo "<form name='email_form'><table width='100%'>";
	$i=1;
	foreach($emailids as $key => $value)
	{
		if($key == $value)
			$name = "";
		else
			$name = $value;
		$emailid = $key;
		echo "<tr><td width='50px'><input type='checkbox' name='option' value='".$emailid."' checked></td><td width='150px'>".$name."</td><td>".$emailid."</td></tr>";
		$i++;
	}
	echo "</table>";
	echo "<br/><div style='margin-left:100px'><input type='button' value='Continue' onClick='javascript:submitids(".$total.")'></div>";
	echo "</form>";
}
?>
<script language="JavaScript">
function submitids(total)
{
	var e= document.email_form.elements.length;
	var cnt=0;
	var emails='';
	var flag=0;
	for(cnt=0;cnt<e;cnt++)
	{
		if(document.email_form.elements[cnt].name=="option")
		{
			if(document.email_form.elements[cnt].checked)
			{
				if(flag==0)
					emails = emails + document.email_form.elements[cnt].value;	
				else
					emails = emails + ", "+ document.email_form.elements[cnt].value;
				flag=1;
			}
		}
	}
	if(flag==0)
		alert("please select atleast one contact.");
	else
	{
		window.opener.document.getElementById('frnds_emails').value=emails;
		window.close();
	}
}
</script>
<br/>
<?php 
if($flag !=1)
{  ?>
<form action='updateprocess.php' method='POST'>

	<table align='center' cellspacing='2' cellpadding='2' style='border:none;'>
		<tr>
			<td align='right'>
				<label for='provider'>Email Provider</label>
			</td>
			<td height='30px'>
				<select STYLE="width: 145px" name="provider">
					<option value="0"></option>						
					<option value="gmail" <?php if($form->value('provider')=='gmail')echo "Selected='true'"; ?>>Gmail</option>						
					<option value="hotmail" <?php if($form->value('provider')=='hotmail')echo "Selected='true'"; ?>>Hotmail</option>						
					<option value="yahoo" <?php if($form->value('provider')=='yahoo')echo "Selected='true'"; ?>>Yahoo!</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align='right'>
				<label for='email'>Email</label>
			</td>
			<td height='30px'>
				<input type='text' name='email' value='<?php echo $form->value('email'); ?>'>
			</td>
		</tr>
		<tr>
			<td align='right'>
				<label for='password'>Password</label>
			</td>
			<td height='30px'>
				<input type='password' name='password' value='<?php echo $form->value('password'); ?>'><br/><br/>
				</td>
			</tr>			
			<tr>
				<td colspan='2' align='center'>
					<input type='submit' name='import' value='Import Contacts'>
				</td>
			</tr>
	</table>
	<input type='hidden' name='get_contacts' id='get_contacts'>
	<input type='hidden' name='user_guess' value='<?php echo generateToken('get_contacts') ?>'>";
</form>
<?php
}
	?>
</body>
</html>