<?php include_once("./editables/feedback.php");?>

<a href="#" id="searchlink" rel="subcontent">
<img src="./images/front_page/feedback4.gif" border=0></a>&nbsp;
<DIV id="subcontent" style="position:absolute; visibility: hidden; border: 5px solid #ADDFFF; background: width: 470px; padding: 8px;">
<font><b><?php echo $lang['feedback']['feedback_'];?></b></font>
<form name="feedback" method="POST" action='http://localhost/zidisha/updatefeedback.php'>
<div> <TEXTAREA NAME="txtcomment" ROWS=2 COLS=30 TABINDEX="8"></TEXTAREA></div>
&nbsp;<input name="Submit" type="submit" value="Submit" onClick="return check();">
<input type='hidden' name='<?php echo $lang['feedback']['feedback_'];?>' />
<input type='hidden' name='userid' value='<?php echo $id;?>' />
<input type='hidden' name='senderid' value='<?php echo$session->userid;?>' />
</form>
</DIV>
<script type="text/javascript">
//Call dropdowncontent.init("anchorID", "positionString", glideduration, "revealBehavior") at the end of the page:

dropdowncontent.init("searchlink", "right-bottom", 500, "onclick")
</script>