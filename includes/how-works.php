<?php
include_once("./editables/how-works.php");
$path=	getEditablePath('how-works.php');
include_once("./editables/".$path);
?>
<div class="span16">
	<div id="static" style="text-align:justify">
	<?php echo $lang['how-works']['desc']; ?>	
	</div>
</div>