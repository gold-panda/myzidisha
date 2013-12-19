<?php
include_once("library/session.php");                           // created by chetan
include_once("./editables/admin.php");
include_once("editables/translation.php");
if(isSet($_GET['ref']))
{
	$ref = $_SERVER['HTTP_REFERER'];
	$_SESSION['trans_refer_page'] = $ref;
}
else
	$ref=$_SESSION['trans_refer_page'];
$flag=0;


if($session->userlevel==ADMIN_LEVEL)
	$flag=1;
else if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$flag=1;
} 

	?>
<div class='span12'>
<?php

/*
if($flag==1)
{
*/
	$v=0;
	if(isSet($_GET['v']))
		$v=$_GET['v'];

	if(isSet($_GET['up_id']))
		$up_id=$_GET['up_id'];
	else
		$up_id=0;

	if(isSet($_GET['c_id']))
		$cmntid=$_GET['c_id'];
	if(isSet($_GET['lc_id']))
		$lcid=$_GET['lc_id'];

	if(isSet($_GET['id']))
	{
		$id=$_GET['id'];
		$loanid=$_GET['l_id'];
		$res1=$database->getTranslate($id, $loanid, 0);
		$About = $res1['About'];		
		$BizDesc = $res1['BizDesc'];
		$loanuse= $res1['loanuse'];
		if(isSet($_GET['v']))
		{
			if($v == 1)
			{
				$tr_About = $res1['tr_About'];
				$tr_BizDesc = $res1['tr_BizDesc'];
				$tr_loanuse= $res1['tr_loanuse'];
			}
			else
			{
				
				$tr_About = $form->value('about');
				$tr_BizDesc =$form->value('bizdesc');
				$tr_loanuse = $form->value('loanuse');
			}
		}
		else
		{
			
			$tr_About = $res1['tr_About'];
			$tr_BizDesc = $res1['tr_BizDesc'];
			$tr_loanuse= $res1['tr_loanuse'];
		}
?>
	<div align='left' class='static'><h1><?php echo $lang['translation']['tr_page'] ?></h1></div>
	
<?php if($v==1)
	  {
			echo "<font color='green'><strong>".$lang['translation']['busi_succ']."</strong></font>";
	  }

?>


	<div id="error" align="center"><?php echo $form->error("desc"); ?></div>
	<div id="error" align="center"><?php echo $form->error("updateerr"); ?></div>

<div align="right"><a href="<?php echo $ref; ?>"><?php echo $lang['translation']['return']; ?></a></div>


<br>
	<div align="left"><i><?php echo $lang['translation']['intro'] ?></i></div>
<br>

	<form  action="updateprocess.php?id=<?php echo $id ?>&l_id=<?php echo $loanid ?>" method="POST">

		<br><br>
		<h3 class="subhead"><?php echo $lang['translation']['abt_br'] ?></h3>
		<p style="text-align:justify"><?php echo nl2br($About) ?></p>
		<br>
		<div>
			<strong><?php echo $lang['translation']['edit']; ?></strong><br><TEXTAREA NAME="about" Style="width:680px;height:200px" TABINDEX="8"><?php echo $tr_About; ?></TEXTAREA>
		</div>

		<br><br>
		<h3 class="subhead"><?php echo $lang['translation']['busi_desc'] ?></h3>
		<p style="text-align:justify"><?php echo nl2br($BizDesc) ?></p>
		<br>
		<div>
			<strong><?php echo $lang['translation']['edit']; ?></strong><br><TEXTAREA NAME="bizdesc" Style="width:680px;height:200px" TABINDEX="8"><?php echo $tr_BizDesc; ?></TEXTAREA>
		</div>

		<br><br>		
<h3 class="subhead"><?php echo $lang['translation']['pur_loan'] ?></h3>
		<p style="text-align:justify"><?php echo nl2br($loanuse) ?></p>
		<br>
		<div>
			<strong><?php echo $lang['translation']['edit']; ?></strong><br><TEXTAREA NAME="loanuse" Style="width:680px;height:200px" TABINDEX="8"><?php echo $tr_loanuse; ?></TEXTAREA>
		</div>
		<br><br>
		<div align='center'><input class='btn' name="Submit" type="submit" value="Publish Translation"></div>
		<input type='hidden' name='up_id' value='1' />
		<input type='hidden' name='tr_hidden' id='tr_hidden'/>
		<input type="hidden" name="user_guess" value="<?php echo generateToken('tr_hidden'); ?>"/>
	</form>
<?php
	}
	else if(isSet($_GET['c_id']))
	{
		$res2=$database->getTranslate(0,0, $cmntid);
		$message = $res2['message'];
		if(isSet($_GET['v']))
		{
			if($v == 1)
				$tr_message= $res2['tr_message'];
			else
				$tr_message = $form->value('message');
		}
		else
			$tr_message= $res2['tr_message'];
?>


<div align='left' class='static'><h1><?php echo $lang['translation']['tr_page'] ?></h1></div>
	
<?php if($v==1)
	  {
			echo "<font color='green'><strong>".$lang['translation']['cmnt_succ']."</strong></font>";
	  }

?>


		<div id="error" align="center"><?php echo $form->error("desc"); ?></div>
		<div id="error" align="center"><?php echo $form->error("updateerr"); ?></div>

<div align="right"><a href="<?php echo $ref; ?>"><?php echo $lang['translation']['return']; ?></a></div>


<br>
	<div align="left"><i><?php echo $lang['translation']['intro_cmnt'] ?></i></div>
<br>

		<form  action="updateprocess.php?c_id=<?php echo $cmntid ?>" method="POST">
			<h3 class="subhead"><?php echo $lang['translation']['usr_cmnt'] ?></h3>
			<p style="text-align:justify"><?php echo nl2br($message) ?></p>
			<br><br>
			<div>
				<strong><?php echo $lang['translation']['edit']; ?></strong><br><TEXTAREA NAME="cmnt" Style="width:680px;height:200px" TABINDEX="8"><?php echo $tr_message; ?></TEXTAREA><br><br>
				<div align='center'><input class='btn' name="Submit" type="submit" value="Publish Translation" ></div>
				<input type='hidden' name='tr_hidden' id='tr_hidden'/>
				<input type="hidden" name="user_guess" value="<?php echo generateToken('tr_hidden'); ?>"/>
				<input type='hidden' name='up_id' value='2' />
			</div>
		</form>
<?php	
	}
	else if(isSet($_GET['lc_id']))
	{
		$res3=$database->getTranslate(0,0,0,$lcid);
		$comment = $res3['comment'];
		if(isSet($_GET['v']))
		{
			if($v == 1)
				$tr_comment= $res3['tr_comment'];
			else
				$tr_comment = $form->value('comment');
		}
		else
			$tr_comment= $res3['tr_comment'];
?>
		<h1 align='center'><?php echo$lang['translation']['tr_page']; ?></h1>
		<div align="right"><a href="<?php echo $ref; ?>"><?php echo $lang['translation']['return']; ?></a></div>
		<div id="error" align="center"><?php echo $form->error("desc"); ?></div>
		<div id="error" align="center"><?php echo $form->error("updateerr"); ?></div>
<?php	if($v==1)
			echo "<font color='red'><strong>".$lang['translation']['cmnt_succ']."</strong></font>";
?>
		<form  action="updateprocess.php?lc_id=<?php echo $lcid ?>" method="POST">
			<h3 class="subhead"><?php echo $lang['translation']['lndr_cmnt'] ?></h3>
			<p style="text-align:justify"><?php echo nl2br($comment) ?></p>
			<br><br>
			<div>
				<strong><?php echo $lang['translation']['edit']; ?></strong><br><TEXTAREA NAME="lncmnt" Style="width:680px;height:200px" TABINDEX="8"><?php echo $tr_comment; ?></TEXTAREA><br><br>
				<div align='center'><input class='btn' name="Submit" type="submit" value="Publish Translation" ></div>
				<input type='hidden' name='tr_hidden' id='tr_hidden'/>
				<input type="hidden" name="user_guess" value="<?php echo generateToken('tr_hidden'); ?>"/>
				<input type='hidden' name='up_id' value='3' />
			</div>
			</form>
<?php
	}
	else
	{
		echo "ERROR";
	}
/*  }
else
{
	echo "<div>";
	echo $lang['admin']['allow'];
	echo "<br />";
	echo $lang['admin']['Please'];
	echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
	echo "</div>";
}	*/
?>
</div>