<?php
include_once("./editables/legal_info.php");
$path=	getEditablePath('legal_info.php');
include_once("./editables/".$path);
?>
<div class="span12">
	<div id="static" style="text-align:justify;">
	<?php echo $lang['legal_info']['desc']; ?>	
	</div>
</div>
