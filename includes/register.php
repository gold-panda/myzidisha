<?php include_once("library/session.php");
include_once("./editables/admin.php");
require_once('library/recaptcha/recaptchalib.php');
$select=0;
if(isset($_GET["sel"])){
	$select=$_GET["sel"];
}
$t=0;
if(isset($_GET["t"])){
	$t=$_GET["t"];
}
$url = "index.php?p=1&sel=".$select;
$language="en";
if(isset($_GET["lang"])){
	$language=$_GET["lang"];
}
if(isset($_GET["language"])){
	$language = $_GET["language"];
}
include_once("./editables/register.php");
$path=	getEditablePath('register.php');
include_once("./editables/".$path);
?>
<script type="text/javascript" src="includes/scripts/submain.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>

<div class="span12">
<?php

if(empty($t))
{	?>
	<div align='left' class='static'><h1><?php echo $lang['register']['join'] ?></h1></div>
	<div>
	<?php if($select==0)
		{
			echo "<br/>".$lang['register']['select_type']."<br/><br/>";
		}?>
	</div>
	<table>
		<tr>
			<td><strong><?php echo $lang['register']['user_type'];?></strong></td>
			<td>
				<select name="usertype" id="usertype" class="selectcmmn"													onchange="window.location='index.php?p=1&sel='+(this).value+'&lang=<?php echo $language ?>'">
					<option></option>
					<option value="1"<?php if($select==1)echo "Selected='true'"; ?>><?php echo $lang['register']['Borrower'];?></option>
					<option value="2"<?php if($select==2)echo "Selected='true'"; ?>><?php echo $lang['register']['Lender'];?></option>
					<!-- <option value="5"<?php if($select==5)echo "Selected='true'"; ?>><?php echo $lang['register']['LenderGroup'];?></option> -->
					<!-- <option value="3"<?php if($select==3)echo "Selected='true'"; ?>><?php echo $lang['register']['Partner'];?></option> -->
				</select>
			</td>
		</tr>
	</table>
<?php
}
if($select==1)
{
	include_once("b_register.php");
}
else if($select==2 || $select==5)
{
	include_once("l_register.php");
}
else if($select==3)
{
	include_once("p_register.php");
}
else if($select==4)
{
	if($t==1)
	{
		//borrower registration successfull
?>		
<?php
		//if(isset($_SESSION['bEmailVerified'])){
			//echo "<div align='center'><font color=green><b>Thank you! Your email address is now verified.</b></font></div>";
			//unset($_SESSION['bEmailVerified']);
?>
			<h3 style="padding-left:17px;"><?php echo $lang['register']['t_u'];?></h3><br />
			&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lang['register']['email_verify']; ?><br /><br />
			<!--<?php echo $lang['register']['member_of']; ?><br /><br />
			<?php echo $lang['register']['confirming'];?><br />
			<?php echo $lang['register']['find_partner'];?><br /><br />-->
			<?php echo $lang['register']['wadmin'];?>
			<?php include("includes/welcome.php")?>
<?php
		//}
		if(isset($_SESSION['bEmailVerifiedPending'])){
			//echo "<div align='left'><font color=green><b>Thank you. We have sent a confirmation message to your email address.  To complete creation of your account, please confirm your email address using the link in the confirmation message.</b></font></div>";
			unset($_SESSION['bEmailVerifiedPending']);
		}
	}
	else if($t==2)
	{
		//lenderregistration successfull
		if(isset($_SESSION['camp_id']) || strlen($_SESSION['camp_id'])>1)
		{
		$camp_id=$_SESSION['camp_id'];
		$msg= $database->getCampMsgbyId($camp_id);
		unset($_SESSION['camp_id']);
		}
	if($_SESSION['giftRedeemResult'] == 1) {
			echo "<p><font color='blue'>Congratulations! USD ".number_format($_SESSION['giftRedeemAmt'])." has been credited to your lender account.</font></p>";
			unset($_SESSION['giftRedeemResult']);
		} else {
			echo "<p>".$_SESSION['giftRedeemError']."</p>";
			unset($_SESSION['giftRedeemError']);
		}

		//if(isset($_SESSION['lEmailVerified'])){
			//echo "<div align='center'><font color=green><b>Thank you! Your email address is now verified.</b></font></div>";
		//	unset($_SESSION['lEmailVerified']);
	?>
			<?php echo $lang['register']['member_of']; ?><br /><br />
			
			<?php if(isset($msg)){
				echo $msg; 
				echo"<br /><br/>"; 
			}?>
			<?php echo $lang['register']['view_open_loanapplication'];?>
			<a href="https://www.zidisha.org/microfinance/lend.html"><?php echo $lang['register']['open_loanapplication'] ?></a>
			<?php echo $lang['register']['current_borrower_profile']; ?>
			<?php echo $lang['register']['c_borrower_profile']; ?>
			<?php echo $lang['register']['postcomment_ques']; ?> <br /><br />
			<?php echo $lang['register']['p_contact_us'];?>
			<a href="microfinance/contact.html"><?php echo $lang['register']['contact_us'] ?></a>
			<?php echo $lang['register']['wd_question_feedback']; ?><br /><br />
			<?php echo $lang['register']['wadmin'];?>

<?php
		/*}
		if(isset($_SESSION['lEmailVerifiedPending'])){
			echo "<div align='left'><font color=green><b>Thank you. We have sent a confirmation message to your email address.  To complete creation of your account, please confirm your email address using the link in the confirmation message.</b></font></div>";
			unset($_SESSION['lEmailVerifiedPending']);
		}*/
	?>
		
<?php
	}
	else if($t==3)
	{
		//partner registration successfull
		//if(isset($_SESSION['pEmailVerified'])){
		//echo "<div align='center'><font color=green><b>Thank you! Your email address is now verified.</b></font></div>";
		//unset($_SESSION['pEmailVerified']);
?>
			<h3><?php echo $lang['register']['t_u'];?></h3><br />
			<?php echo $lang['register']['member_of']; ?><br /><br />
			<?php echo $lang['register']['confirming'];?><br />
			<?php echo $lang['register']['find_partner'];?><br /><br />
			<?php echo $lang['register']['t_u'];?>,<br />
			<?php echo $lang['register']['wadmin'];?>


<?php	//}
		if(isset($_SESSION['pEmailVerifiedPending'])){
			echo "<div align='left'><font color=green><b>Thank you. We have sent a confirmation message to your email address.  To complete creation of your account, please confirm your email address using the link in the confirmation message.</b></font></div>";
			unset($_SESSION['pEmailVerifiedPending']);
		}
	}
	else
	{
		//error
?> 		
		<h3><?php echo $lang['register']['sorry'];?></h3><br />
		<?php echo $lang['register']['error'];?><br /><br />
		<?php echo $lang['register']['t_u'];?>,<br />
		<?php echo $lang['register']['wadmin'];?>
		<?php echo "<div class='blankDiv'></div>"; ?>
<?php
	}
}
?>
</div>