<?php
include_once("library/session.php");
include_once("./editables/admin.php");
include_once("includes/label.php");
?>
<script type="text/javascript" src="includes/scripts/label.js?q=<?php echo RANDOM_NUMBER ?>"></script>
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
	$label = new label;
	$ref = 0;
	if(isset($_GET['ref'])) {
		$ref = $_GET['ref'];
	}
	if($ref==1)
	{
		$pages= $label->getAllPages();
		$langs= $label->getAllLanguages();
?>
		<div>
			  <div style="float:left"><h3 class="new_subhead"><div align='left' class='static'><h1>Label Translation</h1></div></h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']."&ref=".$_GET['ref'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
		<form  action='' method="POST">
			Select Page: 
			<select id="labelpage" name="labelpage" style="width:auto">
				<option value='0'></option>		
	<?php		foreach($pages as $row)
				{
					echo "<option value='".$row['filename']."'>".$row['pagename']."</option>";
				}	?>
			</select>
			Select Language: 
			<select id="labellang" name="labellang" style="width:auto">
				<option value='0'></option>
		<?php		foreach($langs as $row)
					{
						echo "<option value='".$row['langcode']."'>".$row['lang']."</option>";
					}	?>
			</select>
			<input type="hidden" name="getLabels" id="getLabels">
			<input class='btn' type='submit' name='report' value='Get Labels' /><br/>
		</form>
<?php	if(isset($_POST['getLabels']))
		{
			$page= $_POST['labelpage'];
			$lan= $_POST['labellang'];

			if($page=='0' || $lan=='0')
				echo "<br/><br/><div align='center' style='color:red'>Please select page and language</div>";
			else
			{
				$labels= $label->getLabels($page, $lan);
				$lan1= $label->getLanguagesByCode($lan);
				$pagename= $label->getPageByfilename($page);
				$count= count($labels);
				if(empty($labels))
					echo "<br/><br/><div align='center' style='color:red'>Please upload english file of  $pagename before translating in $lan1</div>";
				else if($labels==-1)
					echo "<br/><br/><div align='center' style='color:red'>English labels of $pagename file has been increased so upload english file again</div>";
				else
				{
					echo "<br/>You are translating labels English to $lan1 of <strong>$pagename</strong> page";
					echo "<br/>Total labels on this page are $count<br/>";
		?>
					<form  action='' method="POST">
						<table width="100%" class="tablewithoutsorter">
							<tr>
									<th align="center" width="48%" height="20px">English Label</th>
									<th align="center" width="48%" height="20px"><?php echo $lan1 ?> Label</th>
									<th align="center" width="4%" height="20px">Action</th>
							</tr>
						<?php	foreach($labels as $row)
								{
									$count=strlen($row['text']);
									if($count <30)
										$height=30;
									else if($count >30 && $count <100)
										$height=80;
									else
										$height=$count/1.6;
									echo "<tr>";
									echo "<td style='padding:2px'><textarea readonly style='width:98%; height:".$height."px; border:none'>".$row['text']."</textarea></td>";
									echo "<td style='padding:2px'><textarea name='".$row['id']."' id='".$row['id']."' style='width:98%; height:".$height."px; border:none' onblur='javascript:saveLabel(this.id)' onfocus='javascript:removeText(this.id)'>".stripslashes($row['transText'])."</textarea></td>";
									echo "<td id='txtHint".$row['id']."' style='vertical-align:middle' align='center'></td>";
									echo "</tr>";
								}
						?>
						</table>
						<input type="hidden" name="language" id="language" value="<?php echo $lan ?>">
						<input type="hidden" name="pagename" id="pagename" value="<?php echo $pagename ?>">
						<input type="hidden" name="totalLabels" id="totalLabels" value="<?php echo count($labels) ?>">
						<input class='btn'  type='submit' name='saveLabels' value='Save Labels' />
					</form>
<?php
				}
			}
		}
		else if(isset($_POST['saveLabels']))
		{
			$labels=array();
			$tot=$_POST['totalLabels'];
			$lan=$_POST['language'];
			$lan1= $label->getLanguagesByCode($lan);
			$pagename=$_POST['pagename'];
			$labels[0]['language']= $lan;
			$i=0;
			foreach($_POST as $key => $value)
			{
				$labels[$i]['id']= $key;
				$labels[$i]['text']= $value;
				$i++;
				if($i==$tot)
					break;
			}
			$res = $label->saveLabels($labels);
			if($res==1)
				echo "<br/><br/><div align='center' style='color:green'>Translated labels of $pagename page in $lan1 has been saved successfully.</div>";
			else
				echo "<br/><br/><div align='center' style='color:red'>Sorry translated labels of $pagename page in $lan1 could not saved. please try again!</div>";
		}
	}
	else if($ref==2)
	{
		$pages= $label->getAllPages();
	?>
		<div>
			  <div style="float:left"><div align='left' class='static'><h1>Upload Editable Files to Database</h1></div></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']."&ref=".$_GET['ref'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
		<form  action='' method="POST">
		Select Page: <select id="labelpage" name="labelpage" style="width:auto">
		<?php
			echo "<option value='0'></option>";
			foreach($pages as $row)
			{
				echo "<option value='".$row['filename']."'>".$row['pagename']."</option>";
			}
		?>
			</select>
			<input class='btn' type='submit' name='uploadFile' value='Upload File' /><br/>
		</form>
	<?php
		if(isset($_POST['uploadFile']))
		{
			$page= $_POST['labelpage'];
			$pagename= $label->getPageByfilename($page);
			if($page=='0')
				echo "<br/><br/><div align='center' style='color:red'>Please select file to upload</div>";
			else
			{
				$res=$label->loadFile($page);
				if($res==1)
					echo "<br/><br/><div align='center' style='color:green'>$pagename file has been uploaded successfully</div>";
				else
					echo "<br/><br/><div align='center' style='color:red'>Sorry $pagename file could not uploaded. please try again!</div>";
			}
		}
	}
	else if($ref==3)
	{
		$pages= $label->getAllPages();
		$langs= $label->getAllLanguages();
	?>
		<div>
			  <div style="float:left"><div align='left' class='static'><h1>Download Editable File from Database</h1></div></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']."&ref=".$_GET['ref']?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
		<form  action='' method="POST">
		Select Page: <select id="labelpage" name="labelpage" style="width:auto">
		<?php
			echo "<option value='0'></option>";
			foreach($pages as $row)
			{
				echo "<option value='".$row['filename']."'>".$row['pagename']."</option>";
			}
		?>
			</select>
		Select Language: <select id="labellang" name="labellang" style="width:auto" >
		<?php
			echo "<option value='0'></option>";
			foreach($langs as $row)
			{
				echo "<option value='".$row['langcode']."'>".$row['lang']."</option>";
			}
		?>
			</select>
			<input class='btn' type='submit' name='downloadFile' value='Download File' /><br/>
		</form>
	<?php
		if(isset($_POST['downloadFile']))
		{
			$page= $_POST['labelpage'];
			$lan= $_POST['labellang'];
			if($page=='0' || $lan=='0')
				echo "<br/><br/><div align='center' style='color:red'>Please select page and language</div>";
			else
			{
				$lan1= $label->getLanguagesByCode($lan);
				$pagename= $label->getPageByfilename($page);
				$res2 = $label->writeFile($page, $lan);
				if($res2==-2)
					echo "<br/><br/><div align='center' style='color:red'>First upload $pagename file and translate all labels of it in $lan1</div>";
				else if($res2==-1)
					echo "<br/><br/><div align='center' style='color:red'>First translate all labels of $pagename file in $lan1</div>";
				else if($res2==1)
					echo "<br/><br/><div align='center' style='color:green'>Editable file for $pagename in $lan1 has been saved successfully</div>";
				else
					echo "<br/><br/><div align='center' style='color:red'>Sorry Editable file for $pagename in $lan1 could not saved. please try again!</div>";
			}

		}
	}
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