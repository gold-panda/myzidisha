<script type="text/javascript">
	$(function() {		
		$(".tablesorter_lenders").tablesorter({sortList:[[0,0]], widgets: ['zebra'], });
	});	
</script>
<?php
if($session->userlevel==LENDER_LEVEL)
{
	$userid1=$session->userid;
	$res=$database->isTranslator($userid1);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL){ 

if(isset($_GET['v']))
{
	if($_GET['v']==1)
		echo "<p style='color:green;font-weight:bold;text-align:center'>".$lang['admin']['lender_activated']."</p>";
	else if($_GET['v']==2)
		echo "<p style='color:green;font-weight:bold;text-align:center'>".$lang['admin']['lender_deactivated']."</p>";
	else if($_GET['v']==0)
		echo "<p style='color:red;font-weight:bold;text-align:center'>".$lang['admin']['error_msg']."</p>";
}
$page=1;
$limit=5000;
if(isset($_GET['page']) && !empty($_GET['page']))
{
	$page = $_GET['page'];
}
$ord="ASC";
$ordClass="headerSortDown";
if(isset($_GET["ord"]) && $_GET["ord"]=='DESC')
{
	$ord='DESC';
	$ordClass="headerSortUp";
}
$type=1;
if(isset($_GET["type"]))
{
	$type=$_GET["type"];
}
$sort='FirstName';
if($type==2)
	$sort='City';
else if($type==3)
	$sort='Email';
else if($type==4)
	$sort='regdate';
$start=($page - 1) * $limit;
$count=$database->getAllLenderCount();
//added by Julia 23-10-2013
	$country='';
	$search='';
	if(isset($_GET['c'])){
		$country= $_GET['c'];
	}
	if(isset($_GET['search'])){
		$search= $_GET['search'];
	}
$setlender=$database->find_lenders($sort, $ord, $start, $limit, $country, $search);

?>
		<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['admin']['all_lenders'] ?></h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']."&a=".$_GET['a'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
	</div>
<?php
$set=$session->getTotalLenderAmount();
$autolendingLender = $database->getAutoLendingLender();
$total_credit = $set['total'];
$total_active = $database->getActiveLenderCount();
$total_recent = $database->getRecentLenderCount();
?>
Total Lenders: <?php echo number_format($count, 0, "", ","); ?><br/><br/>
Active Lenders: <?php echo number_format($total_active, 0, "", ","); ?><br/><br/>
Logged In During Past 2 Months: <?php echo number_format($total_recent, 0, "", ","); ?><br/><br/>
Number of Lenders Using Automated Lending: <?php echo $autolendingLender; ?><br/><br/>
Total Lender Credit Available: USD <?php echo number_format($total_credit, 0, "", ","); ?><br/><br/>
<!-- added by Julia 21-10-2013 -->
<br/><br/><br/><br/>
	<form  action='updateprocess.php' method="POST">
<!-- added by Julia 23 Oct 2013 -->
	<div style="font-size:15px">Select Country:<br/><br/></div>
			<select id="country" name="country" >
			<option value='AA'>All Countries</option>
	<?php		$result1 = $database->countryList();
				foreach($result1 as $cont)
				{?>
					<option value='<?php echo $cont['code']?>' <?php if($country==$cont['code']) echo "Selected='true'";?>><?php echo $cont['name']?></option>
	<?php		}	?>
			</select><br/><br/><br/><br/>

	<div style="font-size:15px">Search for a lender username, first name, last name, email or city:<br/><br/></div>
 	<input type="text" name="search" maxlength="100" value="<?php echo $search; ?>"/><br/><br/><br/>
	<input type="hidden" name="find_lender">
	<center><input type='submit' name='submit' class='btn' value='Submit'/></center><br/><br/>
</form>


<?php
if(!empty($setlender))
{	
	$showingRes =count($setlender);
	?>
	<p>Viewing <?php echo $showingRes?> Results.</p>

	<table class="zebra-striped tablesorter_lenders">
		<thead>
			<tr>
				<th>Lender</th>
				<th><?php echo $lang['admin']['Location'];?></th>
				<th>Date Joined</th>
				<th>Last Login</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
	<?php
			foreach($setlender as $row )
			{
				$userid=$row['userid'];
				$firstname=$row['FirstName'];
				$lastname=$row['LastName'];
				$city=$row['City'];
				$country = $database->mysetCountry($row['Country']);
				$email=$row['Email'];
//added by Julia 23 Oct 2013
				$joinDatetosort=$row['regdate'];
				$joinDate=date('F d, Y', $joinDatetosort);
				$lastLogin=$row['last_login'];
				if(!empty($lastLogin)) {
					$lastLogin=date("M d, Y H:i", $lastLogin);
				}

				$active=$row['Active'];
				$availableAmt = $row['totalamt'];
				$availAmt = number_format($availableAmt, 2, ".", ",");

				$active1='';
				$username=$row['username'];
				$prurl = getUserProfileUrl($userid);
				if($active==0)
				{
					$active1="Deactivated<br/><br/><form name='activeform".$userid."' method='post' action='process.php'>".
					"<input name='activateLender' type='hidden' />".
					"<input type='hidden' name='user_guess' value='".generateToken('activateLender')."'/>".
					"<input name='lenderid' value='$userid' type='hidden' />".
					"<a href='javascript:void(0)' style='color:red' onclick='document.forms.activeform".$userid.".submit()'>".$lang['admin']['activate_button']."</a>".
					"</form>";
				}
				else
				{
					$active1=//"<a href=#>Deactivate</a>";
					"Active<br/><br/><form name='deactiveform".$userid."' method='post' action='process.php'>".
					"<input name='deactivateLender' type='hidden' />".
					"<input type='hidden' name='user_guess' value='".generateToken('deactivateLender')."'/>".
					"<input name='lenderid' value='$userid' type='hidden' />".
					"<a href='javascript:void(0)' style='color:red' onclick='document.forms.deactiveform".$userid.".submit()'>".$lang['admin']['deactivate_button']."</a>".
					"</form>";
				}

				$delete_btn3="<form name='del".$userid."' method='post' action='process.php'>".
				"<input name='deleteLender' type='hidden' />".
				"<input type='hidden' name='user_guess' value='".generateToken('deleteLender')."'/>".
				"<input name='lenderid' value='$userid' type='hidden' />".
				"<a href='javascript:void(0)' style='color:red' onclick='javascript:mySubmit(4,del$userid);'>".$lang['admin']['delete_button']."</a>".
				"</form>";

				/* 4 for calling javascript function for delettion a  lender */
				echo '<tr>';
					echo "<td><a href='$prurl'>$firstname $lastname</a><br/><br/>Username: $username<br/><br/>Email: $email</td>";
					echo "<td>$city<br /><br/>$country</td>";
					echo "<td><span style='display:none'>$joinDatetosort</span>
					$joinDate</td>";
					echo "<td>$lastLogin<br/><br/><a href='index.php?p=16&u=$userid' target='_blank'>Transaction History</a></td>";
					if($database->isDeleteableLender($userid))
						echo "<td>$active1<br>$delete_btn3</td>";
					else
						echo "<td>$active1</td>";
				echo '</tr>';
			}
	?>
		</tbody>
	</table>
	<?php
		if(!empty($setlender) && $count > $limit)
		{
			$last = ceil($count/$limit);
	?>
			<div align="center">
				<div class="pagination">
					<ul>
						<?php if($page !=1){?>
						<li><a href="javascript: void(0);" onClick="sub(1);"><?php echo $lang['admin']['first'] ?></a></li>
						<?php }else{?>
						<li class="disabled"><?php echo $lang['admin']['first'] ?></li>
						<?php }if($page >1){?>
						<li><a href="javascript: void(0);" onClick="sub(<?php echo ($page-1); ?>);">&larr; <?php echo $lang['admin']['previous'] ?></a></li>
						<?php }else{?>
						<li class="prev disabled">&larr; <?php echo $lang['admin']['previous'] ?></li>
						<?php }?>
	<?php				for($m=0, $n=1; $m<$count; $m=$m+$limit, $n++)
						{	?>
							<li class="<?php if(($n)==$page) echo'active'?>"><a href="javascript: void(0);" onClick="sub(<?php echo ($n); ?>);"><?php echo ($n); ?></a></li>
	<?php				}	?>
						<?php if(($page * $limit) < $count){?>
						<li class="next"><a href="javascript: void(0);" onClick="sub(<?php echo ($page+1); ?>);"><?php echo $lang['admin']['next'] ?> &rarr;</a></li>
						<li class="last"><a href="javascript: void(0);" onClick="sub(<?php echo $last; ?>);"><?php echo $lang['admin']['last'] ?></a></li>
						<?php }else{?>
						<li class="disabled"><?php echo $lang['admin']['next'] ?> &rarr;</li>
						<li class="disabled last"><?php echo $lang['admin']['last'] ?></li>
						<?php }?>
					</ul>
				</div>
			</div>
	<?php }   ?>
	</form>
	<script language="javascript">
	function sub(page)
	{
		type='<?php echo $type; ?>';
		ord='<?php echo $ord; ?>';
		window.location = 'index.php?p=11&a=3&type='+type+'&ord='+ord+'&page='+page;
	}
/* comment by Julia 23 Oct 2013
	function tablesort(type)
	{
		
		<?php 
			$type =0;
			if(isset($_GET['type'])) 
				$type =$_GET['type'];
		?>
		if(type ==  <?php echo $type ?>){
			if('ASC'=='<?php echo $ord; ?>'){
				window.location = 'index.php?p=11&a=3&type='+type+'&ord=DESC';
			}
			else{
				window.location = 'index.php?p=11&a=3&type='+type+'&ord=ASC';
			}
		}else{
				window.location = 'index.php?p=11&a=3&type='+type+'&ord=ASC';
		}
	}
*/
	</script>
<?php   }
}	?>