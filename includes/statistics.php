<?php 
	include_once("library/session.php");
	include_once("./editables/statistics.php");
	$path=	getEditablePath('statistics.php');
	include_once("editables/".$path);

?>
<style type="text/css"> 	
	@import url(library/tooltips/btnew.css);
</style>
<?php
	$c ='';
	if(isset($_GET['c']) && $_GET['c'] !="")
	{
		$c=$_GET['c'];
	}
	$countries=$database->getVerifiedPartnerCountries();
	if($c=='')
		$str=$lang['statistics']['report_for_all'];
	else
		$str=$lang['statistics']['report_for_single'].' '.$countries[$c];

	$date= time();
	$loanStats= $database->getStatistics('loanStatistics', $date, $c);
	$cumulativeStats= $database->getStatistics('cumulativeStatistics', $date, $c);

	if(!empty($loanStats) && !empty($cumulativeStats)){
		$loanStatistics= unserialize($loanStats);
		$cumulativeStatistics= unserialize($cumulativeStats);
	}else{ 
		
		$loanStatistics=$database->getActiveLoanStatistics($c); 
		$cumulativeStatistics=$database->getCumulativeLoanStatistics($c);
		
		$database->setStatistics('loanStatistics', serialize($loanStatistics), $c);
		$database->setStatistics('cumulativeStatistics', serialize($cumulativeStatistics), $c);
	}
	$cat=array();
	$cat[1]['cat1']=$lang['statistics']['pb_ontime']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['statistics']['pb_ontime_tooltip']."</span><span class='bottom'></span></span></a>";

	$cat[1]['cat2']=$lang['statistics']['pb_ontime_resch']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['statistics']['pb_ontime_resch_tooltip']."</span><span class='bottom'></span></span></a>";

	$cat[2]['cat1']=$lang['statistics']['pb_31-90']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['statistics']['pb_31-90_tooltip']."</span><span class='bottom'></span></span></a>";

	$cat[2]['cat2']=$lang['statistics']['pb_31-90_resch']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['statistics']['pb_31-90_resch_tooltip']."</span><span class='bottom'></span></span></a>";
	
	$cat[3]['cat1']=$lang['statistics']['pb_91-180']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['statistics']['pb_91-180_tooltip']."</span><span class='bottom'></span></span></a>";

	$cat[3]['cat2']=$lang['statistics']['pb_91-180_resch']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['statistics']['pb_91-180_resch_tooltip']."</span><span class='bottom'></span></span></a>";
	
	$cat[4]['cat1']=$lang['statistics']['pb_180-over']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['statistics']['pb_180-over_tooltip']."</span><span class='bottom'></span></span></a>";

	$cat[4]['cat2']=$lang['statistics']['pb_180-over_resch']." <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'>".$lang['statistics']['pb_180-over_resch_tooltip']."</span><span class='bottom'></span></span></a>";
?>
<div class="span12">
	<div align='left' class='static'><h1><?php echo $lang['statistics']['zidisha_stat'] ?></h1></div>
	<table class='detail' style="width:auto">
		<tbody>
			<tr>
				<br />
				<td><?php echo $lang['statistics']['sel_country'] ?>:</td>
				<td>
					<select id="country" name="country" onchange="window.location='index.php?p=43&c='+(this).value">
					<option value=''><?php echo $lang['statistics']['all_country'] ?></option>
		<?php		foreach($countries as $key => $value)
					{	?>
						<option value='<?php echo $key ?>' <?php if($c==$key) echo "selected" ?>><?php echo $value ?></option>
		<?php		}	?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>

	<br />

	<h4><?php echo $lang['statistics']['cum_stat'] ?> <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['cum_stat_tooltip'] ?></span><span class='bottom'></span></span></a></h4>
	<br /><?php echo $str ?><br/><br/>
	<table class='detail' style="width:auto">
		<tbody>
			<tr><td><?php echo $lang['statistics']['loan_raised'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['loan_raised_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td>USD <?php echo number_format($cumulativeStatistics['lraised'], 0, ".", ","); ?></td></tr>
			<tr><td><br/><?php echo $lang['statistics']['busi_fin'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['busi_fin_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>

			<tr><td><?php echo number_format($cumulativeStatistics['bfinanced'], 0, ".", ","); ?></td></tr>

<?php	if($c=='')
				{	?>
					<tr><td><br/><?php echo $lang['statistics']['total_lenders'] ?>: </td></tr>
					<tr><td><?php echo number_format($cumulativeStatistics['lenders'], 0, ".", ","); ?></td></tr>
					<tr><td><br/><?php echo $lang['statistics']['total_borrower'] ?>: </td></tr>
					<tr><td><?php echo number_format($cumulativeStatistics['borrower'], 0, ".", ","); ?></td></tr>
					<tr><td><br/><?php echo $lang['statistics']['total_countries'] ?>: </td></tr>
					<tr><td><?php echo number_format($cumulativeStatistics['countries'], 0, ".", ","); ?></td></tr>
		<?php	}	?>


			<tr><td><br/><?php echo $lang['statistics']['avg_lend_intr'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['avg_lend_intr_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td><?php echo number_format($cumulativeStatistics['avgintr'], 2, ".", ","); ?>%</td></tr>
	<!--		<tr><td><br/><?php echo $lang['statistics']['writeOff_amount'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['writeOff_amount_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td><?php echo 'USD '.number_format($cumulativeStatistics['default_amount'], 0, ".", ","); ?></td></tr>
	-->

			<tr><td><br/><?php echo $lang['statistics']['end_repay_rate'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['end_repay_rate_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td><?php echo number_format($cumulativeStatistics['end_repaid_rate'], 2, ".", ","); ?>%</td></tr>

			<tr><td><br/><?php echo $lang['statistics']['repay_late_rate'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['repay_late_rate_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td><?php echo number_format($cumulativeStatistics['repay_late_rate'], 2, ".", ","); ?>%</td></tr>

			<tr><td><br/><?php echo $lang['statistics']['end_forgive_rate'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['end_forgive_rate_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td><?php echo number_format($cumulativeStatistics['end_forgive_rate'], 2, ".", ","); ?>%</td></tr>

			<tr><td><br/><?php echo $lang['statistics']['historical_loss_rate'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['historical_loss_rate_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td><?php echo number_format($cumulativeStatistics['hist_loss'], 2, ".", ","); ?>%</td></tr>

<!--			<tr><td><br/><?php echo $lang['statistics']['write_off_rate'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['write_off_rate_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td><?php echo number_format($cumulativeStatistics['defaultRate'], 2, ".", ","); ?>%</td></tr>

			<tr><td><br/><?php echo $lang['statistics']['repay_rate'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['repay_rate_tooltip'] ?></span><span class='bottom'></span></span></a></td></tr>
			<tr><td><?php echo number_format($cumulativeStatistics['repayRate'], 2, ".", ","); ?>%</td></tr>
-->	

		</tbody>
	</table>
	<br /><br />
	<h4><?php echo $lang['statistics']['act_loan_stat'] ?> <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['act_loan_stat_tooltip'] ?></span><span class='bottom'></span></span></a></h4>
	<?php echo $str ?><br/><br/>
	<table class="zebra-striped">
		<thead>
			<tr>
				<th><?php echo $lang['statistics']['category'] ?></th>
				<th><?php echo $lang['statistics']['prin_out'] ?> <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['prin_out_tooltip'] ?></span><span class='bottom'></span></span></a></th>
				<th><?php echo $lang['statistics']['percent_total'] ?> <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['statistics']['percent_total_tooltip'] ?></span><span class='bottom'></span></span></a></th>
			</tr>
		</thead>
		<tbody>
<?php
			for($i=1; $i<5; $i++)
			{
				if($i==1)
					$prinOut=$loanStatistics[$i]['prinOut'] + ($loanStatistics['allTotPrinOut'] - $loanStatistics['totPrinOut']);
				else
					$prinOut=$loanStatistics[$i]['prinOut'];
				if($i==1)
					$prinOutResch=$loanStatistics[$i]['prinOutResch'] + ($loanStatistics['allTotPrinOutResch'] - $loanStatistics['totPrinOutResch']);
				else
					$prinOutResch=$loanStatistics[$i]['prinOutResch'];
				$prinOutInUsd=number_format($prinOut,0);
				$prinOutReschInUsd=number_format($prinOutResch,0);
				$prinOutInUsdPercent=0;
				$prinOutReschInUsdPercent=0;
				if($loanStatistics['allTotPrinOut'] >0)
					$prinOutInUsdPercent=($prinOut / ($loanStatistics['allTotPrinOut'] + $loanStatistics['allTotPrinOutResch'])) * 100;
				if($loanStatistics['allTotPrinOutResch'] >0)
					$prinOutReschInUsdPercent=($prinOutResch / ($loanStatistics['allTotPrinOut'] + $loanStatistics['allTotPrinOutResch'])) * 100;
				$prinOutInUsdPercent=number_format($prinOutInUsdPercent,2);
				$prinOutInReschUsdPercent=number_format($prinOutReschInUsdPercent,2);
				echo '<tr>';
				echo "<td width='350px'>".$cat[$i]['cat1']."</td>";
				echo "<td>".$prinOutInUsd." (USD)</td>";
				echo "<td>".$prinOutInUsdPercent."%</td>";
				echo '</tr>';
				echo '<tr>';
				echo "<td>".$cat[$i]['cat2']."</td>";
				echo "<td>".$prinOutReschInUsd." (USD)</td>";
				echo "<td>".$prinOutInReschUsdPercent."%</td>";
				echo '</tr>';
			}
?>
		</tbody>
		<tfoot>
<?php
			$totprinOutInUsd=number_format($loanStatistics['allTotPrinOut'] + $loanStatistics['allTotPrinOutResch'],0);
			echo '<tr>';			
			echo "<td><b>Total</b></td>";
			echo "<td><b>".$totprinOutInUsd." (USD)</b></td>";
			echo "<td>100%</td>";
			echo '</tr>';
?>
		</tfoot>
	</table>
</div>