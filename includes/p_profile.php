<?php
	//$pid=$session->userid;
	$pid=$getuid;
	$pdetails=$database->getPartnerDetails($pid);
	$data=$pdetails;
	$name=$data['name'];
	$postadd=$data['PostAddress'];
	$email=$data['email'];
	$city=$data['City'];
	$country=$data['Country'];
	$website=$data['Website'];
	$desc=nl2br($data['Description']);
	$username=$data['username'];
	$active=$data['Active'];
	$activedate=$data['activedate'];
	
	if(!empty($activedate)){
		$activedate=date("M d, Y", $activedate);
	}else{
		$activedate="InActive";
	}
?>
	<h3 class="subhead top"><?php echo $lang['profile']['verify_partner'] ?></h3>
	<div id="user-account">
		<?php if (file_exists(USER_IMAGE_DIR.$pid.".jpg")){ ?> 
		<img class ="user-account-img" src="library/getimagenew.php?id=<?php echo $pid;?>&width=180&height=180" alt="" />
		<?php } ?> 
		<table class="detail">
			<tbody>
				<tr>
					<td><strong><?php echo $lang['profile']['Name'] ?>:</strong></td>
					<td><?php echo $name;?></td>
				</tr>
				<?php if($displyall){	?>
				<tr>
					<td><strong><?php echo $lang['profile']['address'] ?>:</strong></td>
					<td><?php echo $postadd;?></td>
				</tr>
				<tr>
					<td><strong><?php echo $lang['profile']['Email'] ?>:</strong></td>
					<td><?php echo $email;?></td>
				</tr>
				<?php }	?>
				<tr>
					<td><strong><?php echo $lang['profile']['City'] ?>:</strong></td>
					<td><?php echo $city;?></td>
				</tr>
				<tr>
					<td><strong><?php echo $lang['profile']['Country'] ?>:</strong></td>
					<td><?php echo $database->mysetCountry($country);?></td>
				</tr>
				<tr>
					<td><strong><?php echo $lang['profile']['website'] ?>:</strong></td>
					<td><a href='http://<?php echo $website ?>' target='_blank'><?php echo  $website ?></a></td>
				</tr>
				<?php if($displyall){	?>
				<tr>
					<td><strong><?php echo $lang['profile']['active'] ?>:</strong></td>
					<td>
			<?php		if ($active==0){
							echo "No";
						}
						else{
							echo "Yes";
						} 
			?>		</td>
				</tr>
				<?php }	?>
				<!-- <tr>
					<td><strong><?php echo $lang['profile']['date_active'] ?>:</strong></td>
					<td><?php echo $activedate;?></td>
				</tr> -->
			</tbody>
		</table>
		<h4><?php echo $lang['profile']['discp'] ?></h4>
        <p><?php echo $desc; ?></p>
	</div>