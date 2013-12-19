<?php
include_once("../library/session.php");
include_once("../editables/loanstatn.php");
$path=	getEditablePath('loanstatn.php');
include_once("../editables/".$path);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Zidisha</title>
<style type="text/css">
#forgiveText
{
	font-family: "verdana,arial,helvetica,sans-serif"; 
	font-size:14px;
}
</style>
</head>
<body>
<?php 
	if(isset($_GET['loanid']))
		$loan_id=$_GET['loanid'];
	else
		$loan_id=0;
	if(isset($_GET['ud']))
		$borrower_id=$_GET['ud'];
	else
		$borrower_id=0;

	?>
<form action="process.php" method="post">
	<table  class='detail' style="padding:15px;">
		<tbody>
			<tr height='10px'>
			</tr>
			<tr style="font-size:16px;">
				<td colspan=2><div id='forgiveText' style="text-align:justify"><?php echo $lang['loanstatn']['forgive_confirmation']?></div></td>
			</tr>
			<tr height='10px'>
			</tr>
			<tr>
				<td><input type='submit' name='import' value='<?php echo $lang['loanstatn']['forgive_accept'] ?>'><input type="hidden" name="ud" value="<?php echo $borrower_id; ?>"><input type="hidden" name="loan_id" value="<?php echo $loan_id; ?>"><input type='hidden' name='forgiveShare' ><input type="hidden" name="user_guess" value="<?php echo generateToken('forgiveShare'); ?>"/></td>
				<td><input type="button" value="Cancel" onclick="$.facebox.close();"></td>
			</tr>
		</tbody>
	</table>
</form>
</body>
</html>
