<?php
include_once("library/session.php");
include_once("error.php");
include_once("./editables/admin.php");
?>
<script type="text/javascript" src="includes/scripts/admin.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<div class='span12'>
<?php
if($session->userlevel != ADMIN_LEVEL)
{	?>
	<div>
		<p><?php echo $lang['admin']['allow']; ?></p>
		<p><?php echo $lang['admin']['Please']; ?><a href="index.php">click here</a><?php echo $lang['admin']['for_more']; ?></p>
	</div>
<?php
}
else
{?>	
	
	
<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['admin']['manage_lang'] ?></h3></div>		  
			  <div style="clear:both"></div>
</div>
		<?php
		$languages=$database->getAllLanguages(1);
		$countries = $database->countryList(true);

		if(!empty($countries))
		{	?>
			<table class="zebra-striped">
				<thead>
					<tr>
						<th width="10%">S. No.</th>
						<th width="15%"><?php echo $lang['admin']['country']; ?></th>
						<th width="15%"><?php echo $lang['admin']['language']; ?></th>							
						<th width="15%"><?php echo $lang['admin']['active']; ?></th>
					</tr>
				</thead>
				<thead>
				<?php 
					$i = 1;									
					foreach($countries as $row)
					{					
					$country=$row[name];
					$country_code=$row[code];	
					
					
					//echo $c_code;					
				echo "<tr align='center'>";
				echo "<td>$i</td><td>$country</td>";										
				echo "<td><form name='frm_mang_lang' method='POST' action='process.php'><select id='lang_opt$i'>";
				foreach($languages as $key=>$value){
					$c_code=$database->getLanguage($country_code);
				?><option value="<?php echo $value['langcode'];?>" <?php if ($c_code == $value['langcode']){echo 'selected';} ?>>
<?php echo $value['lang'];?></option> 
				<?php }		
				echo "</select><input type='hidden' name='count_code' id='count_code$i' value='".$country_code."'><input type='hidden' name='user_guess' value='".generateToken('editlanguage')."'/>
						<td><input type='button' onclick='managelanguage($i)' class ='btn' value='".$lang['admin']['update']."' />
						<span id='lang_update$i' style='display:none'>Updated</span>
						</td>";
				
				echo"</form></td>";
				echo "</tr>";
				$i++;
			}	?>		
			</thead>
		</table>
 <?php }
}?>	
</div>