<?php
include_once("./editables/contacts.php");
$path=	getEditablePath('contacts.php');
include_once("./editables/".$path);
?>
<div class="span12">
	<div id="static">
	<?php echo $lang['contacts']['desc']; ?>	
	</div>
</div>