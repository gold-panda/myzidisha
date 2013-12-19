<?php
if($_REQUEST['param'] == 'test')
	echo $_REQUEST['param1'];
else if($_REQUEST['param'] == 'test1')
{
	$arr = array ('retval'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
	echo json_encode($arr);
}
?>

