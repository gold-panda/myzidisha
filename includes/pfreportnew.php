<?php
include_once("library/session.php");
include_once("./editables/admin.php");
ini_set('max_execution_time', 900);
?>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#date1").datepicker();
		$("#date2").datepicker();
	});
</script>
<script type="text/javascript">
    function submitform () {
        var frm = document.getElementById("pfreport");
        frm.submit();
    }
	
</script>

	<?php 
	if(!isset($_GET['v'])) { ?>
	<script type="text/javascript">
		<!--
			window.onload = submitform;
		//-->
		</script>	
	<?php } ?>
<div class='span12'>
<?php
if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL)
{
	?>
		<div class="subhead2">
			  <div style="float:left">	<div align='left' class='static'><h1>Portfolio Report</h1></div></div>
			  <?php if($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><?php } ?>
			  <div style="clear:both"></div>
		</div>
	<form  action='updateprocess.php' method="POST" id="pfreport">
<?php	$v=0;
		if(isset($_GET["v"]))
		{
			$v=$_GET["v"];
		}
		if($v==1)
		{
			$date1=$_SESSION['value_array']['date1'];
			$date2=$_SESSION['value_array']['date2'];
		}
		else
		{
			$date1=$form->value("date1");
			$date2=$form->value("date2");
		}
		
?>
		<input name="date1" id="date1" type="hidden" value='01/01/2009'/>
		<!-- To Date:&nbsp&nbsp<input  name="date2" id="date2"type="text"value='<?php echo $date2 ;?>'/> -->
		<input type="hidden" size="3" name="portfolioreportnew" id="portfolioreportnew">&nbsp&nbsp
		<input type="hidden" name="user_guess" value="<?php echo generateToken('portfolioreportnew'); ?>"/>
		<!-- <input class='btn' type='submit' name='report' value='Report' /><br/><br/><br/> --> 
		<?php echo $form->error("fromdate"); ?>
		<?php echo $form->error("todate"); ?>
		<?php echo $form->error("wrongdate1"); ?>
		<?php echo $form->error("wrongdate2"); ?>
<?php	if($v==1)
		{
			$date1=$_SESSION['value_array']['date1'];
			$date2=$_SESSION['value_array']['date2'];
			$set=$database->pfreportnew($date1, $date2);
			logger('pfreport after cal in database');
			$dateArr2  = explode("/",$date2);
			$date4=mktime(0,0,0,(int)$dateArr2[0],(int)$dateArr2[1],(int)$dateArr2[2]);
			if(!empty($set))
			{
				logger('pfreport not empty'.count($set));
				$aging[1]['aging']='PAR 0-30 days';
				$aging[1]['risk']=$database->getAdminSetting('PAR-0-30-DAYS');
				$aging[1]['totPrinOut']=0;
				$aging[2]['aging']='PAR 31-60 days';
				$aging[2]['risk']=$database->getAdminSetting('PAR-31-60-DAYS');
				$aging[2]['totPrinOut']=0;
				$aging[3]['aging']='PAR 61-90 days';
				$aging[3]['risk']=$database->getAdminSetting('PAR-61-90-DAYS');
				$aging[3]['totPrinOut']=0;
				$aging[4]['aging']='PAR 91-180 days';
				$aging[4]['risk']=$database->getAdminSetting('PAR-91-180-DAYS');
				$aging[4]['totPrinOut']=0;
				$aging[5]['aging']='PAR over 180 days';
				$aging[5]['risk']=$database->getAdminSetting('PAR-181-OVER-DAYS');
				$aging[5]['totPrinOut']=0;
				$allTotalPrinOutInUsdCountries=0;
				ob_start(); 
				foreach($set as $key=>$value)
				{
					logger('pfreport display in foreach');
					$currency=$value['currency'];
					logger('currency from set'.$currency);
					$currencyName=$database->getCurrencyNameByCurrencyId($currency);
					logger('currencyname from set'.$currencyName);
					$rate=$database->getExRateByDate($date4, $currency);
					logger('rate from set'.$rate);
					$country1=$database->mysetCountry($key);
					logger('country from set'.$country1);
					echo "<center><h3 class='subhead'>Report for Country ".$country1." in ".$currencyName."</h3></center>";
?>
					<table class="zebra-striped">
						<thead>
							<tr style="background-color:#E0F3F4">
								<th width='150px' >Aging</th>
								<th width="190px">Principal outstanding</th>
								<th width="105px">Risk rating</th>
								<th width="145px">LLR</th>
							</tr>
						</thead>
						<tbody>
<?php
							$totLLR=0;
							for($i=1; $i<6; $i++)
							{logger('pfreport display in for loop'.$country1);
						?>
								<script type="text/javascript">
									$(document).ready(function() {
									$('#agingDiv<?php echo $key.$i?>').click(function() {
											$('#agingDetailDiv<?php echo $key.$i?>').slideToggle("slow");
											$(this).toggleClass("smallactive"); return false;
									});
								});
								</script>
						<?php
								$color='#FFFFFF';
								if($i%2==0)
									$color='#EDF5FF';
								$prinOut=number_format($value[$i]['prinOut'],0);
								logger('printout'.$prinOut);
								$LLR=(($value[$i]['prinOut'] * $aging[$i]['risk'])/100);
								logger('llr'.$LLR);
								$totLLR +=$LLR;
								logger('totalllr'.$totLLR);
								$LLR=number_format($LLR,0);
					?>
								<tr style="background-color:<?php echo $color ?>">
								<td colspan=4>
									<div>
					<?php			if(isset($value[$i]['loans']) && !empty($value[$i]['loans']))
									{	logger('valueloans'.$value[$i]['loans']);?>
										<div class="smallarrow" id="agingDiv<?php echo $key.$i?>" style="cursor:pointer; float:left;width:129px;border-right:1px #ffffff solid;padding-left:20px;color:blue"><?php echo $aging[$i]['aging'] ?></div>
					<?php			}
									else
									{	logger('in else valueloans'.$value[$i]['loans']);?>
										<div style="float:left;width:129px;border-right:1px #ffffff solid;padding-left:20px;"><?php echo $aging[$i]['aging'] ?></div>
					<?php			}	?>
										<div style="float:left;width:188px;border-right:1px #ffffff solid;padding-left:5px"><?php echo $prinOut." (".$currencyName.")"; ?></div>
										<div style="float:left;width:106px;border-right:1px #ffffff solid;padding-left:5px"><?php echo $aging[$i]['risk'] ?></div>
										<div style="float:left;padding-left:25px"><?php echo $LLR." (".$currencyName.")"; ?></div>
										<div style="clear:both"></div>
									</div>
					<?php
									if(isset($value[$i]['loans']) && !empty($value[$i]['loans']))
									{logger('nextvalueloans'.$value[$i]['loans']);
	?>									<div id= "agingDetailDiv<?php echo $key.$i?>" style="display:none; border:1px solid #544E4F; margin-top:10px">
											<table class="zebra-striped">
												<thead>
													<tr>
														<th>Borrower</th>
														<th>Due days</th>
														<th>Principal outstanding</th>
														<th>Risk rating</th>
														<th>LLR</th>
													</tr>
												</thead>
												<tbody>
									<?php
												foreach($value[$i]['loans'] as $row)
												{
													$prinOut=number_format($row['prinOut'],0);
													logger('printoutforeach'.$prinOut);
													$LLR=(($row['prinOut'] * $aging[$i]['risk'])/100);
													$LLR=number_format($LLR,0);
													logger('llrforeach'.$LLR);
													echo '<tr>';
													echo "<td><a href='".SITE_URL."index.php?p=11&a=5&u=".$row['userid']."'>".$row['bname']."</td>";
													echo "<td>".$row['days']."</td>";
													echo "<td>".$prinOut." (".$currencyName.")</td>";
													echo "<td>".$aging[$i]['risk']."</td>";
													echo "<td>".$LLR." (".$currencyName.")</td>";
													echo '</tr>';
												}
									?>
												</tbody>
											</table>
										</div>
										<div style="clear:both"></div>
	<?php
									}
	?>							</td>
								</tr>
	<?php					}
?>
						</tbody>
						<tfoot>
<?php
							$totalPrinOut=number_format($value['totPrinOut'],0);
							logger('totalprint out'.$totalPrinOut);
							$allTotalPrinOut=number_format($value['allTotPrinOut'],0);
							logger('allTotalPrinOut'.$allTotalPrinOut);
							$LLRratio=($totLLR / $value['allTotPrinOut'])*100;
							logger('LLRratio'.$LLRratio);
							$totLLR=number_format($totLLR,0);
							logger('totLLR'.$totLLR);
							$LLRratio=number_format($LLRratio,2);
							logger('LLRratio'.$LLRratio);
							echo '<tr>';
							echo "<td><strong>Total</strong></td>";
							echo "<td><strong>".$totalPrinOut." (".$currencyName.")</strong></td>";
							echo "<td></td>";
							echo "<td><strong>".$totLLR." (".$currencyName.")</strong></td>";
							echo '</tr>';
							echo '<tr>';
							echo "<td colspan=4><strong>Total Principal Outstanding ".$allTotalPrinOut." (".$currencyName.")</strong></td>";
							echo '</tr>';
							echo '<tr>';
							echo "<td colspan=4><strong>Total LLR Ratio ".$LLRratio."%</strong></td>";
							echo '</tr>';
?>
						</tfoot>
					</table>
					<?php echo "<center><h3 class='subhead'>Report for Country ".$country1." in USD</h3></center>"; ?>
					<table class="zebra-striped">
						<thead>
							<tr style="background-color:#E0F3F4">
								<th width="150px">Aging</th>
								<th width="190px">Principal outstanding</th>
								<th width="105px">Risk rating</th>
								<th width="145px">LLR</th>
							</tr>
						</thead>
						<tbody>
					<?php
							$totLLR=0;
							for($i=1; $i<6; $i++)
							{logger('pfreport in 2 for loop'.$country1);
					?>
								<script type="text/javascript">
									$(document).ready(function() {
									$('#agingUSDDiv<?php echo $key.$i?>').click(function() {
											$('#agingDetailUSDDiv<?php echo $key.$i?>').slideToggle("slow");
											$(this).toggleClass("smallactive"); return false;
									});
								});
								</script>
						<?php
								$color='#FFFFFF';
								if($i%2==0)
									$color='#EDF5FF';
								$prinOutInUsd=$value[$i]['prinOut'] / $rate;
								logger('prinOutInUsd2'.$prinOutInUsd);
								$aging[$i]['totPrinOut'] +=$prinOutInUsd;
								$LLR=(($prinOutInUsd * $aging[$i]['risk'])/100);
								logger('LLR2'.$LLR);
								$totLLR +=$LLR;
								logger('totLLR2'.$totLLR);
								$prinOutInUsd=number_format($prinOutInUsd,0);
								$LLR=number_format($LLR,0);
					?>
								<tr style="background-color:<?php echo $color ?>">
								<td colspan=4>
									<div>
					<?php			if(isset($value[$i]['loans']) && !empty($value[$i]['loans']))
									{	?>
										<div class="smallarrow" id="agingUSDDiv<?php echo $key.$i?>" style="cursor:pointer; float:left;width:129px;border-right:1px #ffffff solid;padding-left:20px;color:blue"><?php echo $aging[$i]['aging'] ?></div>
					<?php			}
									else
									{	?>
										<div style="float:left;width:129px;border-right:1px #ffffff solid;padding-left:20px;"><?php echo $aging[$i]['aging'] ?></div>
					<?php			}	?>
										<div style="float:left;width:188px;border-right:1px #ffffff solid;padding-left:5px"><?php echo $prinOutInUsd." (USD)"; ?></div>
										<div style="float:left;width:106px;border-right:1px #ffffff solid;padding-left:5px"><?php echo $aging[$i]['risk'] ?></div>
										<div style="float:left;padding-left:5px"><?php echo $LLR." (USD)"; ?></div>
										<div style="clear:both"></div>
									</div>
					<?php
									if(isset($value[$i]['loans']) && !empty($value[$i]['loans']))
									{
					?>					<div id= "agingDetailUSDDiv<?php echo $key.$i?>" style="display:none; border:1px solid #544E4F; margin-top:10px">
											<table class="zebra-striped">
												<thead>
													<tr>
														<th>Borrower</th>
														<th>Due days</th>
														<th>Principal outstanding</th>
														<th>Risk rating</th>
														<th>LLR</th>
													</tr>
												</thead>
												<tbody>
									<?php
												foreach($value[$i]['loans'] as $row)
												{
													$prinOutInUsd=$row['prinOut'] / $rate;
													$LLR=(($prinOutInUsd * $aging[$i]['risk'])/100);
													$prinOutInUsd=number_format($prinOutInUsd,0);
													$LLR=number_format($LLR,0);
													echo '<tr>';
													echo "<td><a href='".SITE_URL."index.php?p=11&a=5&u=".$row['userid']."'>".$row['bname']."</td>";
													echo "<td>".$row['days']."</td>";
													echo "<td>".$prinOutInUsd." (USD)</td>";
													echo "<td>".$aging[$i]['risk']."</td>";
													echo "<td>".$LLR." (USD)</td>";
													echo '</tr>';
												}
									?>
												</tbody>
											</table>
										</div>
										<div style="clear:both"></div>
					<?php
									}
					?>			</td>
								</tr>
					<?php	}
					?>
						</tbody>
						<tfoot>
					<?php
							$totalPrinOutInUsd=$value['totPrinOut'] / $rate;
							logger('totalPrinOutInUsd2'.$totalPrinOutInUsd);
							$allTotalPrinOutInUsd=$value['allTotPrinOut'] / $rate;
							logger('allTotalPrinOutInUsd2'.$allTotalPrinOutInUsd);
							$allTotalPrinOutInUsdCountries +=$allTotalPrinOutInUsd;
							logger('allTotalPrinOutInUsdCountries'.$allTotalPrinOutInUsdCountries);
							$LLRratio=($totLLR / $allTotalPrinOutInUsd)*100;
							$totalPrinOutInUsd=number_format($totalPrinOutInUsd,0);
							$allTotalPrinOutInUsd=number_format($allTotalPrinOutInUsd,0);
							$totLLR=number_format($totLLR,0);
							$LLRratio=number_format($LLRratio,2);
							echo '<tr>';
							echo "<td><strong>Total</strong></td>";
							echo "<td><strong>".$totalPrinOutInUsd." (USD)</strong></td>";
							echo "<td></td>";
							echo "<td><strong>".$totLLR." (USD)</strong></td>";
							echo '</tr>';
							echo '<tr>';
							echo "<td colspan=4><strong>Total Principal Outstanding ".$allTotalPrinOutInUsd." (USD)</strong></td>";
							echo '</tr>';
							echo '<tr>';
							echo "<td colspan=4><strong>Total LLR Ratio ".$LLRratio."%</strong></td>";
							echo '</tr>';
					?>
						</tfoot>
					</table>
					<br/>
<?php
				}
?>
				<center><h3 class='subhead'>Report for All Countries in USD</h3></center>
				<table class="zebra-striped">
					<thead>
						<tr style="background-color:#E0F3F4">
							<th>Aging</th>
							<th>Principal outstanding</th>
							<th>Risk rating</th>
							<th>LLR</th>
						</tr>
					</thead>
					<tbody>
<?php
						$LLRratioCountries=0;
						$totalPrinOutInUsdCountries=0;
						for($i=1; $i<6; $i++)
						{logger('for loop in all country');
							$color='#FFFFFF';
							if($i%2==0)
								$color='#EDF5FF';
							$totPrinOutInUsd=$aging[$i]['totPrinOut'];
							logger('totPrinOutInUsd3'.$totPrinOutInUsd);
							$totalPrinOutInUsdCountries +=$totPrinOutInUsd;
							logger('totalPrinOutInUsdCountries3'.$totalPrinOutInUsdCountries);
							$LLR=(($totPrinOutInUsd * $aging[$i]['risk'])/100);
							$LLRratioCountries +=$LLR;
							logger('LLRratioCountries'.$LLRratioCountries);
							$totPrinOutInUsd=number_format($totPrinOutInUsd,0);
							logger('totPrinOutInUsd4'.$totPrinOutInUsd);
							$LLR=number_format($LLR,0);
							echo '<tr style="background-color:'.$color.'">';
								echo "<td>".$aging[$i]['aging']."</td>";
								echo "<td>".$totPrinOutInUsd." (USD)</td>";
								echo "<td>".$aging[$i]['risk']."</td>";
								echo "<td align='center'>".$LLR." (USD)</td>";
							echo '</tr>';
						}
?>
					</tbody>
					<tfoot>
<?php
						$LLRratio=($LLRratioCountries / $allTotalPrinOutInUsdCountries)*100;
						$LLRratioCountries=number_format($LLRratioCountries,0);
						$totalPrinOutInUsdCountries=number_format($totalPrinOutInUsdCountries,0);
						$allTotalPrinOutInUsdCountries=number_format($allTotalPrinOutInUsdCountries,0);
						$LLRratio=number_format($LLRratio,2);
						echo '<tr>';
						echo "<td><strong>Total</strong></td>";
						echo "<td><strong>".$totalPrinOutInUsdCountries." (USD)</strong></td>";
						echo "<td></td>";
						echo "<td><strong>".$LLRratioCountries." (USD)</strong></td>";
						echo '</tr>';
						echo '<tr>';
						echo "<td colspan=4><strong>Total Principal Outstanding for all countries ".$allTotalPrinOutInUsdCountries." (USD)</strong></td>";
						echo '</tr>';
						echo '<tr>';
						echo "<td colspan=4><strong>Total LLR Ratio for all countries ".$LLRratio."%</strong></td>";
						echo '</tr>';
?>
					</tfoot>
				</table>
<?php
			}
			else
			{
				echo "<br/><br/><strong>No Data</strong>";
			}
		}
		?>
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
}	
echo ob_get_clean(); 
?>
</div>
