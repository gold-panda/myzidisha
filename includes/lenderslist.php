<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#datefrom").datepicker({ maxDate: new Date });
});
</script>
<div class='span12'>
<?php
 	if(!empty($_POST) && $session->userlevel == ADMIN_LEVEL) {
		$limit = 100;
		$where='';
		if(isset($_POST['datefrom']) && !empty($_POST['datefrom'])) {
			$date = strtotime($_POST['datefrom']);
			$where = "AND users.regdate > '{$date}'";
		}
		if(isset($_POST['brwr_limit']) && $_POST['brwr_limit'] > 0) {
			$limit  = $_POST['brwr_limit'];
		}
		$q="SELECT email FROM `lenders` ORDER BY userid DESC limit $limit";
		$content='';
		
		$result=$db->getAll($q);
		//ob_end_clean();
		foreach($result as $row) {
			$content .= "<li>".$row['email'].","."</li><br/>";
			$content1 .= $row['email'].","."<br/>";

		}
		
		echo "<ol>".$content."</ol>";

		echo $content1;

		//header("Content-type: text/csv");
		//header("Content-Disposition: attachment; filename=file.csv");
		//header("Pragma: no-cache");
		//header("Expires: 0");

		exit;
	}else if(!empty($_POST)) {
		echo"<font color='red'>You are not authorized to view this page!</font>";
	}

?>
<div align='left' class='static'><h1>Last Joined Lenders Email list:</h1></div>
	<form method="post" action="">
		Numbers of Lenders:<br/>
		<input type="text" name="brwr_limit" value='100'><br/><br/>
		<input type="submit" name='downloadcsv' class='btn'>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#datefrom").datepicker();
</script>