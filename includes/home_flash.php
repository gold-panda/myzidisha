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
	if(currentImage == "images/home1.jpg"){
		herotext1=$('#herotext1').text('Lend Thilor');
		herotext2=$('#herotext2').text('$20 for new fabric');
	}
	else if(currentImage == "images/home2.jpg"){
		$('#herotext1').text('Lend Melita');
		$('#herotext2').text('$100 for a dairy cow');
	}
	else if(currentImage == "images/home3.jpg"){
		$('#herotext1').text('Lend Ruth');
		$('#herotext2').text('$10 for fresh vegetables');
	}
	else if(currentImage == "images/home11.jpg"){
		$('#herotext1').text('Lend Pherister');
		$('#herotext2').text('$200 for a new schoolroom');
	}
	else if(currentImage == "images/home12.jpg"){
		$('#herotext1').text('Lend Peter');
		$('#herotext2').text('$60 for a sewing machine');
	}
	else if(currentImage == "images/home13.jpg"){
		$('#herotext1').text('Lend David');
		$('#herotext2').text('$30 for flour and sugar');
	}
	else if(currentImage == "images/home14.jpg"){
		$('#herotext1').text('Lend Albake');
		$('#herotext2').text('$20 to make silver bracelets');
	}
	else if(currentImage == "images/home15.jpg"){
		$('#herotext1').text('Lend Justine');
		$('#herotext2').text('$60 to publish her book');
	}
	document.getElementById("feature").style.backgroundImage = "url("+currentImage+")"; //for div background
	//document.body.style.background = "url("+img+")"; // set the background image
}
</script>
<a href="microfinance/lend.html" style="text-decoration : none">
	<div class="hero" id="feature">
	<h1><div id="herotext1"></div>
	<span class="blue"><div id="herotext2"></div></span></h1>
	<!--
	<div class="banner_btn">
		<a href="microfinance/lend.html"><img src="images/lend-button-3_text.png" height="45" width="226" alt="LEND" /></a>
	</div>
	-->
	<p><?php echo $lang['home']['hero_text3']?>
	
	<img src="images/featured-in.png" alt="As Featured in The Washington Post, Microfinance Focus and on National Public Radio" width="385" height="70" /></p>
	
</div>
</a>