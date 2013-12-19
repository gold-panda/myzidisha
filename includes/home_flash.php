<?php 	
include_once("./editables/home.php");
$path=	getEditablePath('home.php');
include_once("./editables/".$path);
?>
<script type="text/javascript">
onload = function() {
	// set up an array of the images
	images = new Array("images/home1.jpg", "images/home2.jpg", "images/home3.jpg", "images/home4.jpg", "images/home5.jpg", "images/home6.jpg", "images/home13.jpg", "images/home14.jpg", "images/home15.jpg");
	
	rand = Math.floor(Math.random()*images.length); // chose a random number, between 0 and the length of the array -1
	img = images[rand]; // set img to the random image's src
	document.getElementById("hero").src = "url("+img+")"; 
	//document.body.style.background = "url("+img+")"; // set the background image
}
</script>
<script type="text/javascript">
onload = function() {
	images = new Array(	// set up an array of the images
		"images/home1.jpg",
		"images/home2.jpg",
		"images/home3.jpg",
		"images/home11.jpg",
		"images/home12.jpg",
		"images/home13.jpg",
		"images/home14.jpg",
		"images/home15.jpg"
	);
	rand = Math.floor(Math.random()*images.length); // chose a random number, between 0 and the length of the array -1
	currentImage = images[rand]; // set img to the random image's src
	document.getElementById("feature").style.backgroundImage = "url("+currentImage+")"; //for div background
	//document.body.style.background = "url("+img+")"; // set the background image
}
</script>
<div class="hero" id="feature">
	<h1><?php echo $lang['home']['hero_text1']?> <span class="blue"><?php echo $lang['home']['hero_text2']?></span></h1>
	<p><?php echo $lang['home']['hero_text3']?></p>
	<div class="banner_btn">
		<!-- a href="microfinance/lend.html"><img src="images/lend-button-3_text.png" height="45" width="226" alt="LEND" /></a -->
	</div>
	<p><img src="images/featured-in.png" alt="As Featured in The Washington Post, Microfinance Focus and on National Public Radio" width="385" height="70" /></p>
</div>