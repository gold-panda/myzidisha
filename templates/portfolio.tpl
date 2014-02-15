{extends file="landing.tpl"}

{block name=title}{/block}
{block name=description}{/block}

{block name=call_to_action_top}

{/block}

{block name=call_to_action_bottom}

{/block}

{block name=content}

{/block}

{block name=more}

{/block}


<!DOCTYPE html>

<? include('_html.php'); ?>

<head>
  <title>Deliver Theme | Portfolio</title>
  <meta name="description" content="">

  <? include('_meta.php'); ?>

  <link rel="stylesheet" href="styles.css">
  <script src="js/modernizr-2.6.2.min.js"></script>
</head>

<body>
	<div id="wrapper_content">
  	<? include('_oldbrowser.php'); ?>
		<? include('_social.php'); ?>
		<? include('_menu.php'); ?>

    <div id="featured">
    	<div class="wrapper">
    		<h1>Featured Work</h1>
    	</div>
    </div><!-- /featured -->

			<div class="wrapper">
				<section class="call_action">
					<div>
						<h2>Nothing but the best for our Portfolio</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent justo ligula, interdum ut lobortis quis, interdum vitae metus. Proin fringilla metus non nulla cursus, sit amet rutrum est pretium.</p>
					</div>
				</section>
			</div><!-- /wrapper -->
			<div id="works">
				<div class="wrapper">
					<ul class="nav option-set" data-option-key="filter" id="portfolio_nav">
						<li><a data-option-value="*" class="current" href="#">All</a></li>
						<li><a data-option-value=".cat-web-design" href="#">Web Design</a></li>
						<li><a data-option-value=".cat-home-freebies" href="#">Freebies</a></li>
						<li><a data-option-value=".cat-home-print" href="#">Print</a></li>
					</ul>
				</div><!-- /wrapper -->
				<div class="blocks" id="portfolio">
					<div class="wrapper">
						<div id="isotop_container">
							<div class="work isotope-item cat-home-print">
								<h3><a href="portfolio_item.php">Project 1</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_5.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
							<div class="work isotope-item cat-home-freebies">
								<h3><a href="portfolio_item.php">Project 2</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_6.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
							<div class="work isotope-item cat-web-design">
								<h3><a href="portfolio_item.php">Project 3</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_7.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
							<div class="work isotope-item cat-home-print">
								<h3><a href="portfolio_item.php">Project 4</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_2.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
							<div class="work isotope-item cat-web-design ">
								<h3><a href="portfolio_item.php">Project 5</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_3.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
							<div class="work isotope-item cat-home-print">
								<h3><a href="portfolio_item.php">Project 6</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_4.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
							<div class="work isotope-item cat-web-design">
								<h3><a href="portfolio_item.php">Project 7</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_2.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
							<div class="work isotope-item cat-home-freebies">
								<h3><a href="portfolio_item.php">Project 8</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_3.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
							<div class="work isotope-item cat-web-design">
								<h3><a href="portfolio_item.php">Project 9</a></h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit praesent justo ligula.</p>
								<div class="laptop"><a href="portfolio_item.php"><img src="/static/images/pic_project_4.jpg" alt=""><span class="mask"></span></a></div>
							</div><!-- /work -->
						</div><!-- /isotop_container -->
						<div class="clear"></div>
					</div><!-- /wrapper -->
				</div><!-- /blocks -->
			</div><!-- /works -->
			<div class="wrapper">
				<section class="call_action bottom">
					<div>
						<h2>Do you need a Website?</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent justo ligula, interdum ut lobortis quis, interdum vitae metus. Proin fringilla metus non nulla cursus, sit amet rutrum est pretium.</p>
						<p><a href="contact.php" class="btn">Get a FRee Quote</a></p>
					</div>
				</section>
			</div><!-- /wrapper -->
    	</div>
    	
    	<? include('_footer.php'); ?>
			<? include('_scripts.php'); ?>
    </body>
</html>
