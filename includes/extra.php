<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{
	if(isset($_POST['deleteCache']))
	{
		$dir="cache";
		$iterator = new RecursiveDirectoryIterator($dir);
		foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file)
		{
			$path_info = pathinfo($file);
			if($path_info['extension']=='dat' || $path_info['extension']=='DAT')
				unlink($file->getPathname());
		}
		$count=0;
		foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file)
		{
			$path_info = pathinfo($file);
			if($path_info['extension']=='dat' || $path_info['extension']=='DAT')
				$count++;
		}
		if($count==0)
			echo "<div align='center' style='color:green'>Cache has been deleted successfully.</div>";
		else
			echo "<div align='center' style='color:red'>Cache could not deleted, please try again.</div>";
	}
	if(isset($_POST['createIndexer']))
	{
		include_once("library/indexer.php");
		$res=createIndex();
		if($res >3)
			echo "<div align='center' style='color:green'>Indexer has been created successfully.</div>";
		else
			echo "<div align='center' style='color:red'>Indexer could not created, please try again.</div>";
	}
?>
<div align='left' class='static'><h1>Welcome to Site Management</h1></div>
<form  action='' method="POST">
	<table >
		<tr>
			<td width="100px">Delete Cache:</td>
			<td><input type="hidden" name="deleteCache"><input class='btn' type="submit" value="Delete Cache"></td>
		</tr>
	</table>
</form>
<br/><br/>
<form  action='' method="POST">
	<table>
		<tr>
			<td width="100px">Create Indexer:</td>
			<td><input type="hidden" name="createIndexer"><input class='btn' type="submit" value="Create Indexer"></td>
		</tr>
	</table>
</form>
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