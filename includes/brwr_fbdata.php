<?php
if(isset($_REQUEST['userid'])){
	$userid= $_REQUEST['userid'];
	$fb_detail= $database->getFacebookdata($userid); 
	$fb_data= unserialize(base64_decode($fb_detail['fb_data']));
	if(isset($fb_data['user_friends']['data'])){
		$friends= $fb_data['user_friends']['data'];
	}else{
		$friends=$fb_data['user_friends'];
	}
	$fb_post= $fb_detail['fb_post']; 
	$post_id= explode("_", $fb_post);
	?> 
	<div class="span12">
	<strong><a href="<?php echo 'http://www.facebook.com/'.$fb_data['user_profile']['id']; ?>" target="_blank"><?php echo $fb_data['user_profile']['name']; ?></a></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if(!empty($post_id)){?>
		<strong><a href="<?php echo 'http://www.facebook.com/'.$post_id[0].'/posts/'.$post_id[1] ?>" target="_blank">View Facebook Post</a></strong><br/><br/>
	<?php } ?>
	<table class="Detail">
		<tbody>
			<?php foreach($friends as $fb_friends){
				if(isset($fb_friends['first_name'])){
					$name= $fb_friends['first_name']." ".$fb_friends['last_name'];
				}else{
					$name=$fb_friends['name'];
				}
			?>
			<tr>
			<td><?php echo $name;?></td>
			<td><a href="<?php echo 'https://www.facebook.com/'.$fb_friends['id'] ?>" target="_blank"><?php echo $fb_friends['id']; ?></a></td>
			</tr>
<?php	}?>
		</tbody>
	</table>
	</div>
<?php }
?>