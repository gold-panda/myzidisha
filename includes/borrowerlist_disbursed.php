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
		$q="SELECT email,borrowers.userid  FROM `borrowers` join transactions on `borrowers`.userid= transactions.userid WHERE `Active` = 1 AND txn_type=". DISBURSEMENT."$where ORDER BY TrDate DESC limit $limit";
		$content='';
		//$content="Borrower emails\n";
		$result=$db->getAll($q);
		//ob_end_clean();
		foreach($result as $row) {
			$content .= $row['email'].","."<br/>";
		}
		echo $content;
		//header("Content-type: text/csv");
		//header("Content-Disposition: attachment; filename=file.csv");
		//header("Pragma: no-cache");
		//header("Expires: 0");
		echo $content;
		exit;
	}else if(!empty($_POST)) {
		echo"<font color='red'>You are not authorized to view this page!</font>";
	}

?>
<div align='left' class='static'><h1>Borrowers Email list who Received loan disbursements:</h1></div>
	<form method="post" action="">
		Numbers of Borrower:<br/>
		<input type="text" name="brwr_limit" value='100'><br/><br/>
		<input type="submit" name='downloadcsv' class='btn'>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#datefrom").datepicker();
</script>