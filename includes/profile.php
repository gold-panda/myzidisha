<?php
	include_once("library/session.php");
	include_once("./editables/profile.php");
	$path=	getEditablePath('profile.php');
	include_once("editables/".$path);
	$reqUrl = $_SERVER['REQUEST_URI'];
	$parsedurl = parse_url($reqUrl);
	// Anupam, handle new seo friendly url, if user id not in GET request
	$exURL = explode('/',$parsedurl['path']);
	$microfinanceexist = in_array('microfinance',$exURL);
	$profileexist = in_array('profile',$exURL);
	if($profileexist && $microfinanceexist) {
		$usernameexist = explode('.',end($exURL));
		if(end($usernameexist)=='html') {
			$unameinurl = substr(end($exURL), 0, -5);
			$_GET['u'] = $database->getUserId($unameinurl);
			if(empty($_GET['u'])) {
				$unameinurl = str_replace('-',' ',$unameinurl);
				$_GET['u'] = $database->getUserId(urldecode($unameinurl));
			}
		}
	}
?>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
<script type="text/javascript" src="scripts\jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	$('#abt_org').click(function() {
		$('p#abt_org_desc').slideToggle("slow");
		var txt = $(this).text();
		if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
			$(this).text("<?php echo $lang['profile']['hide_text']; ?>");
		else
			$(this).text("<?php echo $lang['profile']['disp_text']; ?>");
	});
	$('#busi_desc_org').click(function() {
		$('p#busi_desc_org_desc').slideToggle("slow");
		var txt = $(this).text();
		if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
			$(this).text("<?php echo $lang['profile']['hide_text']; ?>");
		else
			$(this).text("<?php echo $lang['profile']['disp_text']; ?>");
	});
});
</script>
<div class="span12">
<?php
	$activeuser = 0;
	if($session->userlevel==ADMIN_LEVEL)
		$activeuser = 1;
	else if($session->userlevel==LENDER_LEVEL)
	{
		$userid=$session->userid;
		$res=$database->isTranslator($userid);
		if($res==1)
			$activeuser = 1;
	}
	$rightuser = 0;
	if($session->userlevel==ADMIN_LEVEL)
		$rightuser = 1;
	else if($session->userlevel==PARTNER_LEVEL)
		$rightuser = 1;

	$feedback=array(2=>'Favourable',3=>'Neutral',4=>' Un Favourable',);
	$yesno= array(1=>'Yes',0=>'No');
	$getuid=$_GET['u'];
	$ld=0;
	if(isset($_GET['l'])) {
		$ld=$_GET['l'];
	}
	$ud=$getuid;
	$presentid=$session->userid;//working user's id
	$displyall=0;
	/*Error handling related code*/
	if(isset($_GET['err']) && $_GET['err'] != 0)
	{
		include_once("error.php");
		echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
		echo "<font color='white'>".$errorArray[$_GET['err']]."</font>";
		echo "</td></tr></table>";
	}
	if($presentid==$getuid)
	{
		$displyall=1;
	}
	$getulevel=$database->getUserLevelbyid($getuid);
	if(!empty($getuid))
	{
		if($getulevel==PARTNER_LEVEL)
		{

			include_once("includes/p_profile.php");
		}
		else if($getulevel==LENDER_LEVEL)
		{
			include_once("includes/l_profile.php");
		}
		else if($getulevel==BORROWER_LEVEL)
		{
			include_once("includes/b_profile.php");
		}
		else if($getulevel==ADMIN_LEVEL)
		{
			$id=$getuid;
?>
			<h3 class="subhead top"><?php echo $lang['profile']['admin_detail'] ?></h3>
			<div id="user-account">
				<img class ="user-account-img" src="images/profile.png" alt=""/>
				<table class="detail">
					<tbody>
						<tr>
							<td><strong><?php echo $lang['profile']['Name'] ?>:</strong></td>
							<td><?php echo "Administrator"?></td>
						</tr>
					</tbody>
				</table>
			</div>
<?php
		}
	}
	else
	{
		echo"invalid value for any client";
	}
?>
</div><!-- /row -->
<?php 
if($getulevel==BORROWER_LEVEL && empty($_GET['fdb']))
{	?>
	<div class="row">
		<div class="span16">
			<?php
				$fb=0;
				include_once("includes/b_comments.php"); 
			?>
		</div><!-- /span16 -->
	</div><!-- /row -->
	<div style="clear: both;">
		<div align="right" style="margin-right: 40px;">
		<?php $prurl = getUserProfileUrl($id);?>
			<a href="<?php echo $prurl?>?l=<?php echo $ld;?>&fdb=1">View All</a>
		</div>
	</div>
<?php
}	?>