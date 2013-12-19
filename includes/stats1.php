<?php
	include_once("library/session.php");
	include_once("./editables/home.php");
	$path=	getEditablePath('home.php');
	include_once("editables/".$path);
	$part=0;
	if(isset($_GET["part"]))
	{
		$part=$_GET["part"];
	}
?>
<?php
if($showOutImpact)
{	?>
	<h2><?php echo $lang['home']['ourimpact'];?></h2>
	<p><strong><span><?php echo $lang['home']['tamtl'];?>:</span> USD <?php echo  number_format($database->totalAmountLent(),0, '.', ','); ?></p>
	<!--<p><span><?php echo $lang['home']['avgintrl'];?>:</span> <?php echo  number_format($database->avgLendRate(), 2, '.', ','); ?>%</p>-->
	<p><span><?php echo $lang['home']['bussf'];?>:</span> <?php echo  $database->businessFinanced(); ?></p>
	<!--<p><span><?php echo $lang['home']['repayment'];?>:</span> <?php echo  number_format($database->getRepayRate(), 2, '.', ','); ?>%</p>-->
	<p><span><?php echo $lang['home']['member_worldwide'];?>:</span> <?php echo $database->getAllLenderCount()+$database->getAllBorrowersCount(); ?></p>
	<p><span><a href="index.php?p=43"><?php echo $lang['home']['more_statistics'];?></a></span></strong></p>
	<br/>
	<span class="fb-widget">
		<div class="fb-like" data-send="true" data-layout="button_count" data-width="150" data-show-faces="false"></div>
	</span>
	<div class="button-facebook">
		<iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.zidisha.org%2Findex.php&amp;layout=standard&amp;show_faces=false&amp;width=160&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:40px;" allowTransparency="true"></iframe>
	</div>
	<div class="button-twitter">
		<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo SITE_URL?>" data-count="horizontal" data-via="ZidishaInc">Tweet</a><script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
	</div><br/>
	<div class="widget">
		<script src="https://platform.linkedin.com/in.js" type="text/javascript"></script>
		<script type="IN/Share" data-url="<?php echo SITE_URL?>" data-counter="right"></script>
	</div><br/>
	<div class="widget">
		<g:plusone size="tall" annotation="none" href="<?php echo SITE_URL;?>"></g:plusone>
		<script type="text/javascript">
		(function()
		{
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();
		</script>
	</div>
<?php if($session->userlevel==LENDER_LEVEL || empty($session->userid)){?>
	<div class="widget" style="margin-top:15px;">
		<span class="email"><a href="index.php?p=30" style="text-decoration:none">Email</a></span>
	</div>
<?php
	}

}?>
<div style="margin-top:45px;">
	<script type="text/javascript">gnp_url = 'zidisha-inc'; gnp_num ='1';</script> 
	<script src="https://greatnonprofits.org/js/badge_stars.js" type="text/javascript"></script> 
	<noscript>
		<a href="https://greatnonprofits.org/organizations/reviews/zidisha-inc/?badge=1"><img alt="Review ZIDISHA INC. on Great Nonprofits" title="Review ZIDISHA INC. on Great Nonprofits" src="https://greatnonprofits.org/images/great-nonprofits.gif"></a>
	</noscript>
</div>