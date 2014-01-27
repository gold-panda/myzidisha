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
{	
	$date=time();
	$totalAmountLent=$database->getStatistics('totalAmountLent', $date);
	$businessFinanced=$database->getStatistics('businessFinanced', $date);
	$AllLenderCount=$database->getStatistics('AllLenderCount', $date);
	$AllBorrowersCount=$database->getStatistics('AllBorrowersCount', $date);
	if(empty($totalAmountLent) && empty($businessFinanced) && empty($AllLenderCount) && empty($AllBorrowersCount)){
		$totalAmountLent=$database->totalAmountLent();
		$businessFinanced=$database->businessFinanced();
		$AllLenderCount=$database->getAllLenderCount();
		$AllBorrowersCount=$database->getAllBorrowersCount();

		$database->setStatistics('totalAmountLent', $totalAmountLent);
		$database->setStatistics('businessFinanced', $businessFinanced);
		$database->setStatistics('AllLenderCount', $AllLenderCount);
		$database->setStatistics('AllBorrowersCount', $AllBorrowersCount);
	}
	?>
	<h2><?php echo $lang['home']['ourimpact'];?></h2>
	<p><strong><span><?php echo $lang['home']['tamtl'];?>:</span> USD <?php echo  number_format($totalAmountLent,0, '.', ','); ?></strong></p>
	<!--<p><span><?php echo $lang['home']['avgintrl'];?>:</span> <?php echo  number_format($database->avgLendRate(), 2, '.', ','); ?>%</p>-->
	<p><strong><span><?php echo $lang['home']['bussf'];?>:</span> <?php echo  $businessFinanced; ?></strong></p>
	<!--<p><span><?php echo $lang['home']['repayment'];?>:</span> <?php //echo  number_format($database->getRepayRate(), 2, '.', ','); ?>%</p>-->
	<p><strong><span><?php echo $lang['home']['member_worldwide'];?>:</span> <?php echo $AllLenderCount +$AllBorrowersCount; ?></strong></p>
	<p><strong><span><a href="index.php?p=43"><?php echo $lang['home']['more_statistics'];?></a></span></strong></p>
	<br/>
	
	<!-- Facebook widget -->
	<div class="button-facebook">
		<iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.zidisha.org%2Findex.php&amp;layout=standard&amp;show_faces=false&amp;width=160&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:40px;"></iframe>
	</div>

	<!-- Twitter widget -->
	<div class="button-twitter">
		<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo SITE_URL?>" data-count="horizontal" data-via="ZidishaInc">Tweet</a><script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
	</div><br/>

	<!-- removing other social share icons for now 25 Jan 2014
	<div class="widget">
		<script src="https://platform.linkedin.com/in.js" type="text/javascript"></script>
		<script type="IN/Share" data-url="https%3A%2F%2Fwww.zidisha.org%2Findex.php%3Fp%3D0" data-counter="right"></script>
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
<?php if(!empty($session->userid)){?>
	<div class="widget" style="margin-top:15px;">
		<span class="email"><a href="index.php?p=30" style="text-decoration:none">Email</a></span>
	</div>
<?php
	} ?>

-->


<?php }?>
<?php $page='';
if(isset($_GET['p'])) {
	$page = $_GET['p'];
} if($page!=14 && $page!=12) { ?>
<div style="margin-top:45px;width:188px">
<h5 style='text-align:left'><?php echo $lang['home']['connect_zidisha']?></h5>
	<ul id="list-footer-connect">
		<li><a id="footer-lending_group" target="_blank" href="index.php?p=80"><span class="icon-footer-lending_group"></span><?php echo $lang['menu']['join_lending_group'] ?></a></li>
		<li><a id="footer-facebook" target="_blank" href="http://www.facebook.com/ZidishaInc?sk=wall"><span class="icon-footer-facebook"></span><?php echo $lang['menu']['join_fb'] ?></a></li>
	    <li><a id="footer-twitter" target="_blank" href="http://twitter.com/#!/zidishainc"><span class="icon-footer-twitter"></span><?php echo $lang['menu']['join_twt'] ?></a></li>
		<li><a id="footer-team" target="_blank" href="http://p2p-microlending-blog.zidisha.org/"><span class="icon-footer-blog"></span><?php echo $lang['home']['check_blog'] ?></a></li>
		<li><a id="footer-interns" href="microfinance/team.html"><span class="icon-footer-interns"></span><?php echo $lang['menu']['meet_the_team'] ?></a></li>
	</ul>

</div> 
<?php } ?>