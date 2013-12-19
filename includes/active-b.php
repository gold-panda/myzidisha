<?php
include_once("library/session.php");
include_once("./editables/admin.php");
include_once("./editables/active-b.php");
?>
<div class='span12'>
<?php
if($session->userlevel != PARTNER_LEVEL && $session->userlevel != ADMIN_LEVEL && $session->userlevel != BORROWER_LEVEL && $session->userlevel != LENDER_LEVEL)
{
	Logger("UNauthorized access activated partner".$session->userid);
	echo $lang['active-b']['not_allowed '];
}
$co_access= $database->isBorrowerAlreadyAccess($session->userid);

if($session->userlevel == BORROWER_LEVEL && $co_access==0){
	
	Logger("Non-loggedin Or Uauthorized access activated partner".$session->userid);
	echo $lang['active-b']['not_allowed '];
}
else
{
?>
<?php
	$page=1;
	$limit=500;
	$prt=0;
	if(isset($_GET['page']) && !empty($_GET['page']))
	{
		$page = $_GET['page'];
	}
	$start=($page - 1) * $limit;
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
		$sort='editDate';
	else if($type==3)
		$sort='Country';
	else if($type==4)
		$sort='lastVisited';
	if(isset($_GET["prt"]))
	{
		$prt=$_GET["prt"];
	}
	$count=$database->activeBorrowersCount($session->userlevel, $session->userid, $prt);
	$profile=$database->activeBorrowers($sort, $ord, $session->userlevel, $session->userid, $start, $limit,$prt);
	if(empty($profile))
	{
		echo $lang['active-b']['no_bussiness'];
	}
	else
	{	
?>		
		<div class="subhead2">
			  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['active-b']['act_borrower'] ?></h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
			</div>
		<table id="transtable" class="zebra-striped">
			<thead>
				<tr>
					<th class="header <?php if($type==1) echo $ordClass;?>" onClick="tablesort(1)"><?php echo "Borrower"; ?></th>
					<th  class="header <?php if($type==3) echo $ordClass;?>" onClick="tablesort(3)"><?php echo $lang['active-b']['location'] ;?></th>
					<th  class="header <?php if($type==2) echo $ordClass;?>" onClick="tablesort(2)"><?php echo $lang['active-b']['status'] ;?></th>
					<?php if($session->userlevel == PARTNER_LEVEL || $session->userlevel == ADMIN_LEVEL){ ?>
					<th  class="header <?php if($type==4) echo $ordClass;?>" onClick="tablesort(4)"><?php echo $lang['active-b']['lastVisited'] ;?></th>
					<?php } ?>
					<th  class="header <?php if($type==5) echo $ordClass;?>" onClick="tablesort(5)"><?php echo $lang['active-b']['notes'];?></th>
					
				</tr>
			</thead>
			<tbody>
<?php		foreach($profile as $rows)
			{
				$userid=$rows['userid'];
				$fname=$rows['FirstName'];
				$lname=$rows['LastName'];
				$username=$database->getUserNameById($userid);
				$address=$rows['PAddress'];
				$city=$rows['City'];
				$country=$database->mysetCountry($rows['Country']);
				$telmob=$rows['TelMobile'];
				$email=$rows['Email'];
				$income=$rows['AnnualIncome'];
				$about=$rows['About'];
				$photo=$rows['Photo'];
				$partner=$database->getNameById($rows['PartnerId']);
				$activate_date=date('M d, Y', $rows['editDate']);
				$lastvisited='';
				if(!empty($rows['lastVisited'])) {
					$lastvisited = date("m/d/Y",$rows['lastVisited']);
				}
				$prurl= getUserProfileUrl($userid);
				/*$community_org= $database->isBorrowerAlreadyAccess($session->userid);
				if($community_org){
					$link= getUserProfileUrl($userid);
				}else{
					$link='index.php?p=7&id='.$userid;
				}*/
				$link='index.php?p=7&id='.$userid;
				$admin_notes = $rows['admin_notes'];
				echo '<tr>';
				//echo "<td style='width:50%'>$about</td>";
				echo "<td><a href='$prurl'>$fname $lname</a><br /><br />Username: $username<br /><br />$address<br />$telmob<br />$email<br /></td>";
				echo "<td><div align='center'>$country<br/>$city</div></td>";
				echo "<td><a href='$link'>$activate_date</a></td>";
				if($session->userlevel == PARTNER_LEVEL || $session->userlevel == ADMIN_LEVEL){
				echo "<td><input type='text' name='lastvisited' id='lastVisited$userid'  value = '$lastvisited'></td>";
				}else {
					echo "<input type='hidden' name='lastvisited' id='lastVisited$userid'  value = '$lastvisited'>";
				}
				echo "<td><textarea name='borrower_notes' id='borrower_notes$userid' col='6' row='6' style='height: 80px'>$admin_notes</textarea>
				<input type='button' name='save' class='btn' id='save$userid' value='Save' onclick='saveborrowerdetail($userid)'></td>";
			
				
				echo"<td>
						<span id='response$userid'></span>
					</td>";
				echo '</tr>';
			}
	?>		</tbody>
		</table>
		<?php
			if(!empty($profile) && $count > $limit)
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
			window.location = 'index.php?p=8&page='+page;
		}
	function tablesort(type)
	{
		<?php 
			$type =0;
			if(isset($_GET['type'])) 
				$type =$_GET['type'];
			$prt =0;
			if(isset($_GET['prt'])) 
				$prt =$_GET['prt'];
		?>
		var  prt = "<?php echo $prt?>";
		if(type ==  <?php echo $type ?>){
			if('ASC'=='<?php echo $ord; ?>')
			{
				window.location = 'index.php?p=8&type='+type+'&ord=DESC&prt='+prt;
			}
			else
			{
				window.location = 'index.php?p=8&type='+type+'&ord=ASC&prt='+prt;
			}		
		}else{
			window.location = 'index.php?p=8&type='+type+'&ord=ASC&prt='+prt;
		}
	}
		</script>
<?php	
	}
}	?>
</div>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/scripts/borrower_detail.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
	<?php 
		if(!empty($profile)) {
				foreach($profile as $row) { ?>
					$("#lastVisited<?php echo $row['userid']?>").datepicker();
		<?php }
		}
		?>
	});
</script>