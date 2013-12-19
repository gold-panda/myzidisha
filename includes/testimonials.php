<?php
include_once("./editables/testimonials.php");
$path=	getEditablePath('testimonials.php');
include_once("./editables/".$path);
?>
<div class="span16">
	<div id="static" style="text-align:justify">
	<?php echo $lang['testimonials']['desc']; ?>	
	</div>
</div>
