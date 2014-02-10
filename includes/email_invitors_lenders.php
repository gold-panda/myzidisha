<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>

<div class='span12'>
<?php
 	if(!empty($_POST) && $session->userlevel == ADMIN_LEVEL) {
		$limit = 100;
		$where='';

		if(isset($_POST['brwr_limit']) && $_POST['brwr_limit'] > 0) {
			$limit  = $_POST['brwr_limit'];
		}
		$content='';
		//$content="Borrower emails\n";
		$result= $database->inviteReportLenders();
		//ob_end_clean();
		foreach($result as $row) {
			$borrowerid = $row['userid'];
			$email = $row['Email'];
			$content .= $email.","."<br/>";
		}
		echo $content;
		//header("Content-type: text/csv");
		//header("Content-Disposition: attachment; filename=file.csv");
		//header("Pragma: no-cache");
		//header("Expires: 0");
		exit;
	}

?>
<div align='left' class='static'><h1>Members Who Have Invited Others:</h1></div>
	<br/><br/>
	<form method="post" action="">
		Maximum Emails:<br/><br/>
		<input type="text" name="brwr_limit" value='100'><br/><br/>
		<input type="submit" name='downloadcsv' class='btn'>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#datefrom").datepicker();
</script>