<?php 
include_once("library/session.php");// created by chetan
include_once("./editables/admin.php");
include_once("./editables/managetrans.php");
?>

<!-- Note that we are now using this page not to activate translators but instead to assign access to some of the admin pages to the lender accounts of our volunteer staff. Julia, 3-11-2013 -->

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
	$v=0;
	if(isSet($_GET['v'])) {
		$v = $_GET['v'];
	}
	$page=1;
	$limit=500;
	if(isset($_GET['page']) && !empty($_GET['page']))
	{
		$page = $_GET['page'];
	}
	$start=($page - 1) * $limit;
	$count=$database->getAllLenderCount();

?>


			<div>
			  <div style="float:left"><div align='left' class='static'><h1>Activate Volunteers</h1></div></div>
			  <?php if($session->userlevel==LENDER_LEVEL || $session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>

<?php 
	echo $form->error("activeerr");
	if($v==1)
		echo "<font color='red'><b>".$lang['managetrans']['pref_succ']."</b></font>";

?>
	<br/><strong>Current Volunteers</strong><br/><br/>
<?php

	$staff=$database->getAllStaff();
	$countstaff = count($staff);
	?>
	<p>Total Active Staff: <?php echo $countstaff?></p>

	<?php
	foreach ($staff as $row) {

		$name = $row['FirstName']." ".$row['LastName'];
		$userid = $row['userid'];
		$prurl = getUserProfileUrl($userid);
		echo "<a href='$prurl'>$name</a><br/>";

	}



?>


<script type="text/javascript">
	$(function() {		
		$(".tablesorter_lenders").tablesorter({sortList:[[0,0]], widgets: ['zebra'], });
	});	
</script>
<?php
if(isset($_GET['v']))
{
	if($_GET['v']==1)
		echo "";
	else if($_GET['v']==2)
		echo "";
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
	<input type="hidden" name="find_lenderforstaff">
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
				<th>Volunteer Status</th>
			</tr>
		</thead>
		<tbody>
	<?php



			foreach($setlender as $row )
			{
				$userid=$row['userid'];
				$uid=$row['userid'];
				$firstname=$row['FirstName'];
				$lastname=$row['LastName'];
				$city=$row['City'];
				$country = $database->mysetCountry($row['Country']);
				$email=$row['Email'];
				$joinDatetosort=$row['regdate'];
				$joinDate=date('F d, Y', $joinDatetosort);
				$username=$row['username'];
				$prurl = getUserProfileUrl($userid);
				$res=$database->isTranslator($userid);
				if($res==1) {
					$active=1;
				} else {
					$active=0;
				}

				echo '<tr>';
					echo "<td><a href='$prurl'>$firstname $lastname</a><br/><br/>Username: $username<br/><br/>Email: $email</td>";
					echo "<td>$city<br /><br/>$country</td>";
					echo "<td><span style='display:none'>$joinDatetosort</span>
					$joinDate</td>";

					?>
					
					<td>
							<form method='post' action='updateprocess.php'>
								<input type='hidden' name='uid' value='<?php echo $uid; ?>' />
								<input type='hidden' name='active' value='<?php echo $active; ?>' />
								<input type='hidden' name='translatorhidden' id='translatorhidden' />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('translatorhidden'); ?>"/>
						<?php	if($active==1) { ?>
									<span style='display:none'>1</span><input type='image' alt='Active' title='Deactivate Volunteer' src='images/layout/icons/tick.png'/>

						<?php } ?>
						<?php   if($active==0) { ?>
									<span style='display:none'>0</span><input type='image' alt='Deactive' title='Activate Volunteer' src='images/layout/icons/x.png'/>
						<?php } ?>
							</form>
						</td>
				</tr>
			<?php }
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

	</script>
<?php
}	?>


<!-- old version of page commented out by Julia 3-11-2013

	<table class="zebra-striped tablesorter_managetrans">
		<thead>
			<tr>
				<th><?php echo $lang['managetrans']['srno'];?> </th>
				<th><?php echo $lang['managetrans']['name'];?></th>
				<th><?php echo $lang['managetrans']['email'];?></th>
				<th><?php echo $lang['managetrans']['languages'];?></th>
				<th><?php echo $lang['managetrans']['active'];?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			$i=1+$start;
			foreach($res as $row)
			{
				$uid = $row['userid'];
				$name = $row['FirstName']." ".$row['LastName'];
				$email = $row['Email'];
				$active = $row['isTranslator'];
				$translang = $row['trans_Lang'];
				echo '<tr>';
						echo "<td>$i</td>";
						echo "<td width='100px'>$name</td>";
						echo "<td width='100px'>$email</td>";
					?>
						<td width='275'>
							<form method='post' action='updateprocess.php'>
								<input type='text' name='translang' id='translang' value='<?php echo $translang; ?>' size='15' style="width:auto"/>
								<input class='btn' type='submit' name='submit' value='ok' />
								<input type='hidden' name='translatorlang' id='translatorhidden' />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('translatorlang'); ?>"/>
								<input type='hidden' name='uid' value='<?php echo $uid; ?>' />
							</form>
						</td>
						<td>
							<form method='post' action='updateprocess.php'>
								<input type='hidden' name='uid' value='<?php echo $uid; ?>' />
								<input type='hidden' name='active' value='<?php echo $active; ?>' />
								<input type='hidden' name='translatorhidden' id='translatorhidden' />
								<input type="hidden" name="user_guess" value="<?php echo generateToken('translatorhidden'); ?>"/>
						<?php	if($active==1) { ?>
									<span style='display:none'>1</span><input type='image' alt='Active' title='Deactivate Translator' src='images/layout/icons/tick.png'/>

						<?php } ?>
						<?php   if($active==0) { ?>
									<span style='display:none'>0</span><input type='image' alt='Deactive' title='Activate Translator' src='images/layout/icons/x.png'/>
						<?php } ?>
							</form>
						</td>
	<?php	echo '</tr>';
				//echo '<tr>';
				$i++;
			}	?>
		</tbody>
	</table>
	<?php
		if(!empty($res) && $count > $limit)
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

end commenting out 3-11-2013 -->

	<script language="javascript">
	function sub(page)
	{
		window.location = 'index.php?p=25&page='+page;
	}
	</script>
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
?>
</div>

