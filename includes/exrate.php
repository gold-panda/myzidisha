<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['admin']['exrate'] ?></h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']."&a=".$_GET['a']?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
	</div>
<p><?php echo $lang['admin']['Exchange_Rate']?></p>
<?php
$currencysel=0;
if(isset($_GET["c"]))
{
	$currencysel=$_GET["c"];
}
?>
<form method="get" action="#" name="form_currency">
	<table class="detail" style="width:auto">
		<tbody>
			<tr>
				<td><strong><?php echo $lang['admin']['select_currency'];?>:</strong></td>
				<td>
					<select name="c" id="c" class="selectcmmn" onChange="javascript:mySubmit(1,0);" style="width:auto">
			<?php		$currency=$database->getAllCurrency(1);
						$tempcurrency=$form->value("currency");
						if(!empty($tempcurrency))
							$currencysel=$tempcurrency;
						if(!empty($currency))
						{
							foreach($currency as $currencyrow)
							{	?>
								<option value="<?php echo $currencyrow['id']  ; ?>"<?php if($currencysel==$currencyrow['id'])echo "Selected='true'"; ?>><?php echo $currencyrow['currencyname']." in ".$currencyrow['country'];?></option>
				<?php 		}
						}	?>
					</select>
					<input type="hidden" name="p" value='11' />
					<input type="hidden" name="a" value='4' />
				</td>
			</tr>
		</tbody>
	</table>
</form>
<form method="post" action="process.php">
	<table class="detail" style="width:auto">
		<tbody>
			<tr>
				<td><strong><?php echo $lang['admin']['new_rate'];?>:</strong></td>
				<td>
					<input type="text" maxlength="10" name="exrateamt" value="<?php echo $form->value("exrateamt"); ?>" /><br/>
					<?php echo $form->error("exrateamt"); ?>
				</td>
				<td>
					<input type="hidden" name="exrate" />
					<input type="hidden" name="user_guess" value="<?php echo generateToken('exrate'); ?>"/>
					<input type="hidden" name="currency" value='<?php echo $currencysel ; ?>' />
					<input class="btn" type="submit" value=<?php echo $lang['admin']['save'];?> />
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php
$set=$database->getExchangeRate($currencysel);
if(!empty($set))
{	?>
	<table class="zebra-striped">
		<thead>
			<tr>
				<th>S. No.</th>
				<th><?php echo $lang['admin']['rate']; ?></th>
				<th><?php echo $lang['admin']['from']; ?></th>
				<th><?php echo $lang['admin']['to']; ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
			$i = 1;
			foreach($set as $row)
			{
				$rate=$row['rate'];
				$from=$row['start'];
				$to=$row['stop'];
				if(strlen($to=trim($to))<1)
				{
					$to='-';
				}
				echo "<tr align='center'>";
				echo "<td>$i</td><td>$rate</td><td>$from</td><td>$to</td>";
				echo "</tr>";
				$i++;
			}	?>
		</tbody>
	</table>
<?php	
}	?>