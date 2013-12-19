<script type="text/javascript" src="includes/scripts/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="includes/scripts/expand.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript">
<!--
	$(function() {
    // --- first section initially expanded:
    $(".expand").toggler();
    // --- Other options:
    //$("h4.expand").toggler({method: "toggle", speed: 0});
    //$("h4.expand").toggler({method: "toggle"});
    //$("h4.expand").toggler({speed: "fast"});
    //$("h4.expand").toggler({method: "fadeToggle"});
    //$("h4.expand").toggler({method: "slideFadeToggle"});
    $("#content").expandAll({trigger: "h4.expand", ref: "div.demo",  speed: 300, oneSwitch: false});
});
//-->
</script>
<?php
	require_once("library/session.php");
	include_once("./editables/faqs.php");
	$path=	getEditablePath('faqs.php');
	include_once("./editables/".$path);
	//echo $lang['faqs']['desc'];

?>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" src="includes/scripts/submain.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" src="includes/scripts/facebox/facebox.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<link href="includes/scripts/facebox/facebox.css?q=<?php echo RANDOM_NUMBER ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="css/default/popup_style.css?q=<?php echo RANDOM_NUMBER ?>" rel="stylesheet">

<!--
$(document).ready(function() {
	$('a[rel*=facebox]').facebox({
		loadingImage : '<?php echo SITE_URL?>includes/scripts/facebox/loading.gif',
		closeImage   : '<?php echo SITE_URL?>includes/scripts/facebox/closelabel.png'
	});
});
//-->
</script>
<div class="row">
	<div class="span16">
		<div id="static" style="text-align:justify">
			<h1><?php echo $lang['faqs']['heading']?></h1>
			<div id="content" style="text-align:justify">
				<div class="demo">
					<table  cellspacing="0" cellpadding="0" width="100%">
						<tbody>
							<tr>
								<td width='300px' class="faq_content" style="border-right:#666 dotted 1px;padding-right:15px;border-bottom:none">
									<h3 style="margin-top:0px"><?php echo $lang['faqs']['basic']?></h3>
									<ul id="acc" class="acc" style="margin-bottom:0px">
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest1'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer1'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest2'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer2'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest3'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer3'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest4'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer4'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest5'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer5'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest47'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer47'];?></div>
										</li>
										<li>
											<?php 
												$params['firstLoanValue']=$database->getAdminSetting('firstLoanValue'); 
											?>
											<span class="expand"><?php echo $lang['faqs']['quest6'];?></span>
											<div class="collapse"><?php $ans6=$session->formMessage($lang['faqs']['answer6'], $params);
											echo $ans6?></div>
										</li>

<li>
											<?php 
												$params['firstLoanValue']=$database->getAdminSetting('firstLoanValue'); 
											?>
											<span class="expand"><?php echo $lang['faqs']['quest52'];?></span>
											<div class="collapse"><?php $ans6=$session->formMessage($lang['faqs']['answer52'], $params);
											echo $ans6?></div>
										</li>

										<li>
											<?php $params['fee']=$database->getAdminSetting('fee'); ?>
											<span class="expand"><?php echo $lang['faqs']['quest7'];?></span>
											<div class="collapse"><?php $ans7=$session->formMessage($lang['faqs']['answer7'], $params);
											echo $ans7?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest8'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer8'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest9'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer9'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest10'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer10'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest11'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer11'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest12'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer12'];?></div>
										</li>
										<li>
											<span class="expand"> <?php echo $lang['faqs']['quest13'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer13'];?></div>
										</li>
										
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest15'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer15'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest49'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer49'];?></div>
										</li>

										<li>
											<span class="expand"><?php echo $lang['faqs']['quest16'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer16'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest17'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer17'];?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest46'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer46'];?></div>
										</li>

<li>
											<span class="expand"><?php echo $lang['faqs']['quest51'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer51'];?></div>
										</li>

									</ul>
								</td>
								<td width='300px' class="faq_content" style="border-right:#666 dotted 1px;padding-right:15px;padding-left:15px;border-bottom:none">
									<h3 style="margin-top:0px"><?php echo $lang['faqs']['lenders'];?></h3>
									<ul id="acc" class="acc" style="margin-bottom:0px">
										
	<li>
											<span class="expand"><?php echo $lang['faqs']['quest45']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer45']?></div>
										</li>	<li>
											<span class="expand"><?php echo $lang['faqs']['quest44']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer44']?></div>
										</li>
<li>
											<span class="expand"><?php echo $lang['faqs']['quest43']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer43']?></div>
										</li>
	<li>
											<span class="expand"><?php echo $lang['faqs']['quest19']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer19']?></div>
										</li>
	<li>
											<span class="expand"><?php echo $lang['faqs']['quest21']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer21']?></div>
										</li>
<li>
											<span class="expand"><?php echo $lang['faqs']['quest18']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer18']?></div>
										</li>
									




										<li>	
											<span class="expand"><?php echo $lang['faqs']['quest20']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer20']?></div>
										</li>
									
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest22']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer22']?></div>
										</li>
										
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest24']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer24']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest14'];?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer14'];?></div>
										</li>
										<li>
											<span class="expand">
											<?php 
											$params['maxRepayPeriod']=$database->getAdminSetting('maxRepayPeriod');
											$params['RescheduleAllow']=$database->getAdminSetting('RescheduleAllow');
											$ans40=$session->formMessage($lang['faqs']['answer40'], $params);
											echo $lang['faqs']['quest40']?></span>
											<div class="collapse">
											<?php echo $ans40 ?></div>
										</li><li>
											<span class="expand"><?php echo $lang['faqs']['quest41']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer41']?></div>
										</li>

										<li>
											<span class="expand"><?php echo $lang['faqs']['quest42']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer42']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest23']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer23']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest48']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer48']?></div>
										</li>
									</ul>
								</td>
								<td width='300px' class="faq_content" style="padding-left:15px;border-bottom:none">
									<h3 style="margin-top:0px"><?php echo $lang['faqs']['borrower']?></h3>
									<ul id="acc" class="acc" style="margin-bottom:0px">
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest25']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer25']?></div>
										</li>


<li>
											<span class="expand"><?php echo $lang['faqs']['quest50']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer50']?></div>
										</li>

										<li>
											<span class="expand"><?php echo $lang['faqs']['quest26']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer26']?></div>
										</li>
										<li>
										<?php 
										$params['fee']=$database->getAdminSetting('fee');
										$ans36=$session->formMessage($lang['faqs']['answer36'], $params);
											?>
											<span class="expand"><?php echo $lang['faqs']['quest36']?></span>
											<div class="collapse"><?php echo $ans36?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest37']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer37']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest27']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer27']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest28']?></span>
											<div class="collapse">
											<?php 
												$params['firstLoanPercentage']=$database->getAdminSetting('firstLoanPercentage');
												$params['minBorrowerAmt']=$database->getAdminSetting('minBorrowerAmt');
												$params['secondLoanPercentage']=$database->getAdminSetting('secondLoanPercentage');
												$params['nextLoanPercentage']=$database->getAdminSetting('nextLoanPercentage');
												$params['firstLoanValue']=$database->getAdminSetting('firstLoanValue');
												$params['secondLoanValue']=$database->getAdminSetting('secondLoanValue');
												$params['nextLoanValue']=$database->getAdminSetting('nextLoanValue');
												$params['MinRepayRate']=$database->getAdminSetting('MinRepayRate');
												$params['TimeThrshld_under']=$database->getAdminSetting('TimeThrshld');
												$params['TimeThrshld_above']=$database->getAdminSetting('TimeThrshld_above');
												$value=$params['firstLoanValue'];
												for($i=2; $i<=10; $i++){
													if($i==2 || $i==3){
														$value= $session->getNextLoanValue($value, $params['secondLoanPercentage']);
														$params[$i.'nxtLoanvalue']= $value;
													}else{
														$value= $session->getNextLoanValue($value, $params['nextLoanPercentage']);
														$val1= number_format($value, 0, ".", ",");
														$params[$i.'nxtLoanvalue']= $val1;
													}
												}
												$ans28=$session->formMessage($lang['faqs']['answer28'], $params);
											echo $ans28?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest29']?></span>
											<div class="collapse"><?php 
											$params['fee']=$database->getAdminSetting('fee');
											$params['maxLoanAppInterest']=($database->getAdminSetting('maxLoanAppInterest')+ $database->getAdminSetting('fee'));
											$ans29=$session->formMessage($lang['faqs']['answer29'], $params);
											echo $ans29;?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest30']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer30']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest31']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer31']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest32']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer32']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest33']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer33']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest34']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer34']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest35']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer35']?></div>
										</li>
									
										
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest38']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer38']?></div>
										</li>
										<li>
											<span class="expand"><?php echo $lang['faqs']['quest39']?></span>
											<div class="collapse"><?php echo $lang['faqs']['answer39']?></div>
										</li>



									</ul>
								</td>
							</tr>
						<tbody>
					</table>
					<hr/>
				</div>
			</div>
			<?php echo $lang['faqs']['quest_not_covered']?>
			<h4 style="margin-top:60px;" class="type14"><?php echo $lang['faqs']['legal_notice']?></h4>
			<?php echo $lang['faqs']['legal_notice_desc']?>
		</div><!-- /static -->
	</div><!-- /span16 -->
</div><!-- /row -->
