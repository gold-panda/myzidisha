<?php 
$pid=$session->userid;
$pdetails=$database->getPartnerDetails($pid);
$data=$pdetails;
$language=$data['lang'];
$name=$data['name'];
$postadd=$data['PostAddress'];
$email=$data['email'];
$city=$data['City'];
$country=$data['Country'];
$email=$data['email'];
$emails_notify=$data['emails_notify'];
$website=$data['Website'];
$desc=$data['Description'];
$username=$data['username'];
$active=$data['Active'];

 $post_comment=$data['postcomment'];

$temp = $form->value("labellang");
if(isset($temp) && $temp != '')
	$language=$form->value("labellang");
$temp = $form->value("pusername");
if(isset($temp) && $temp != '')
	$username=$form->value("pusername");
$temp = $form->value("ppass1");
$pass1="";
if(isset($temp) && $temp != '')
	$pass1=$form->value("ppass1");

$temp = $form->value("ppass2");
$ppass2="";
if(isset($temp) && $temp != '')
	$ppass2=$form->value("ppass2");
$temp = $form->value("pname");
if(isset($temp) && $temp != '')
	$name=$form->value("pname");

$temp = $form->value("paddress");
if(isset($temp) && $temp != '')
	$postadd=$form->value("paddress");

$temp = $form->value("pcity");
if(isset($temp) && $temp != '')
	$city=$form->value("pcity");

$temp = $form->value("pcountry");
if(isset($temp) && $temp != '')
	$country=$form->value("pcountry");

$temp = $form->value("pemail");
if(isset($temp) && $temp != '')
	$email=$form->value("pemail");

$temp = $form->value("pwebsite");
if(isset($temp) && $temp != '')
	$website=$form->value("pwebsite");

$temp = $form->value("pdesc");
if(isset($temp) && $temp != '')
	$desc=$form->value("pdesc");

$temp = $form->value("emails_notify");
if(isset($temp) && $temp != '')
	$emails_notify=$form->value("emails_notify");
?>
<div class="row">
	<form enctype="multipart/form-data" method="post" action="updateprocess.php">
		<table class='detail'>
			<tbody>
				<tr>
					<td><div align='left' class='static'><h1><?php echo $lang['register']['update'] ?></h1></div></td>
				</tr>
				<!-- <tr>
					<td><?php echo $lang['register']['language'];?></td>
					<td>
						<select id="labellang" name="labellang">
					<?php	$langs= $database->getActiveLanguages();
							echo "<option value='en'>English</option>";
							foreach($langs as $row)
							{  ?>
								<option value='<?php echo $row['langcode'] ?>' <?php if($language==$row['langcode'])echo "Selected='true'";?>><?php echo $row['lang']?></option>
					<?php	}	?>
						</select>
					</td>
				</tr> -->
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['username'] ;?></td>
					<td><input type="text" maxlength="20" readonly="readonly" name="pusername" class="inputcmmn-1" value="<?php echo $username; ?>" /><br/><div id="bunerror"><?php echo $form->error("pusername"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['Password'];?></td>
					<td><input type="password" id="bpass1" name="ppass1" class="inputcmmn-1" value="<?php  echo $pass1; ?>" /><br/><div id="passerror"><?php echo $form->error("ppass1"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['CPassword'];?></td>
					<td><input type="password" id="bpass2" name="ppass2" class="inputcmmn-1" value="<?php  echo $ppass2; ?>" /></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
                <tr>
					<td><?php echo $lang['register']['Photo'];?>
					<br/><?php echo $lang['register']['photo_msg'];?></td>
					<td><input type="file" name="pphoto" id='pphoto' maxlength="15"  value="<?php echo $form->value("pphoto"); ?>" /><br/><?php echo $form->error("pphoto"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['pname'];?></td>
					<td><input type="text" maxlength="30" id="pname" name="pname" class="inputcmmn-1" value="<?php  echo $name; ?>" /><br/><div id="pnameerror"><?php echo $form->error("pname"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['paddress'];?></td>
					<td><textarea name="paddress" id="paddress" class="textareacmmn" ><?php echo $postadd; ?></textarea></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['City'];?></td>
					<td><input type="text" maxlength="20" id="pcity" name="pcity" class="inputcmmn-1" value="<?php echo $city; ?>" /><br/><div id="pcityerror"><?php echo $form->error("pcity"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
		<!--	<tr>
					<td><label for="pcity"><?php echo $lang['register']['Country']?></label></td>
					<td><input type="text" maxlength="20" id="pcountry" name="pcountry" class="inputcmmn-1" value="<?php echo $country; ?>" /></td>
					<td><div id="pcityerror"><?php echo $form->error("pcountry"); ?></div></td>
				</tr>  -->

                <!-- new row for  country option in lender s account creation-->
				<tr>
					<td><?php echo $lang['register']['Country'];?></td>
					<td>
						<select id="pcountry" name="pcountry" >
					<?php	$result1 = $database->countryList();
							$i=0;
							foreach($result1 as $state)
							{
								if($state['code'] == $country)
									echo "<option value='".$state['code']."' selected>".$state['name']."</option>";
								else
									echo "<option value='".$state['code']."'>".$state['name']."</option>";
							} ?>
						</select>
						<br/><?php echo $form->error("pcountry"); ?>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['email'];?></td>
					<td><input type="text" id="bemail" name="pemail" maxlength="30" class="inputcmmn-1"  value="<?php echo $email; ?>" /><br/><div id="emailerror"><?php echo $form->error("pemail"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['p_emails_notify'];?></td>
					<td><textarea name='emails_notify' id='emails_notify' class='textareaEmail' style='width:200px'><?php echo $emails_notify; ?></textarea><br/><div id="emailerror"><?php echo $form->error("emails_notify"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['Website'];?></td>
					<td><input type="text" maxlength="30" id="pwebsite" name="pwebsite" class="inputcmmn-1" value="<?php echo $website; ?>" /><br/><div id="pweberror"><?php echo $form->error("pwebsite"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['partner_commentpost'];?></td>
					<td>
						<?php if($post_comment==0){?>
						<INPUT type="Radio" name="postcomment" id="postcomment" value="1" tabindex="3" /><?php echo $lang['register']['yes'];?>&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT type="Radio" name="postcomment" id="postcomment1" value="0" tabindex="4" checked/><?php echo $lang['register']['no'];?>
						<?php } else { ?>
						<INPUT type="Radio" name="postcomment" id="postcomment2" value="1" tabindex="3" checked/><?php echo $lang['register']['yes'];?>&nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT type="Radio" name="postcomment" id="postcomment3" value="0" tabindex="4" /><?php echo $lang['register']['no'];?>
						<?php } ?>
					</td>
                </tr>
				 <tr><td>&nbsp;</td></tr>
                <tr>
					<td colspan="2"><label for="pdesc"><?php echo $lang['register']['d_org'];?></label><br/><br/></td>
				</tr>
				<tr>
					<td colspan="2"><textarea style="width:680px; height:150px" name="pdesc" id="pdesc" ><?php echo $desc; ?></textarea><br/><br/></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:right">
						<input type="hidden" name="editpartner" />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('editpartner'); ?>"/>
						<input type="hidden" name="id" value="<?php echo $pid ;?>" />
						<input	class='btn' onclick="needToConfirm = false;" type="submit" value="<?php echo $lang['register']['updatebut'];?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
<script language="JavaScript">
	var ids = new Array('bpass1', 'bpass2', 'pphoto','pname','paddress','pcity','pcountry','bemail','emails_notify','pwebsite','postcomment','postcomment1','postcomment2','postcomment3','pdesc');
	var values = new Array('','','','','','','','','','','','','','','');
	var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>