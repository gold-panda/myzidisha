<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Deliver Theme | Shortcodes With Sidebar</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;"> 
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<meta name="format-detection" content="telephone=no">

        <link rel="stylesheet" href="styles.css">

        <script src="js/modernizr-2.6.2.min.js"></script>
		<script type="text/javascript" >var base_url='http://<?php echo $_SERVER['HTTP_HOST'].substr(str_replace(basename($_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']), 0, -1);?>/'</script>
    </head>
    <body>
    	<div id="wrapper_content">
	        <!--[if lt IE 7]>
	            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	        <![endif]-->
			<div class="wrapper">
		        <header>
		        	<a href="index.php" id="logo" class="ir">Website</a>
			        	<ul class="socials">
			        		<li><a target="_blank" href="https://twitter.com/bestpsdfreebies" class="ir twitter">twitter</a></li>
			        		<li><a target="_blank" href="https://www.facebook.com/bestpsdfreebies" class="ir facebook">facebook</a></li>
			        		<li><a target="_blank" href="http://feeds.feedburner.com/bestpsdfreebies" class="ir rss">rss</a></li>
			        		<li><a target="_blank" href="http://www.pinterest.com/mjreimer/psd-freebies/" class="ir pinterest">pinterest</a></li>
			        		<li><a target="_blank" href="https://plus.google.com/102784875057987299787/posts" class="ir google">google+</a></li>
			        		<li><a target="_blank" href="http://dribbble.com/bestpsdfreebies" class="ir dribbble">dribbble</a></li>
			        		<li><a href="#" class="ir linkedin">linkedin</a></li>
			        		<li><a href="#" class="ir flickr">flickr</a></li>
			        		<li><a href="#" class="ir youtube">youtube</a></li>
			        	</ul>
			        </header>
	        </div><!-- /wrapper -->
		    <div id="menu">
				<div class="wrapper">
					<div id="menu_trigger" class="mobile">menu</div>
		        	<nav>
			        		<ul>
			        			<li><a href="index.php">Home</a></li>
								<li><a href="portfolio.php">Portfolio</a></li>
								<li><a href="about.php">About</a></li>
								<li><a href="services.php">Services</a></li>
								<li><a href="archive.php">Archive</a></li>
								<li><a href="blog.php">Blog</a></li>
								<li>
									<a class="active_sub" href="shortcodes-with-sidebar.php">Other Pages <span class="drops"></span></a>
									<div class="drop">
										<div class="top"></div>
										<ul>
											<li><a href="process.php">Our Process</a></li>
											<li>
												<a href="shortcodes-full-width.php">Full Width & Shortcodes <span class="drops"></span></a>
												<div class="drop">
													<ul>
														<li><a href="shortcodes-full-width.php">Buttons</a></li>
														<li><a href="shortcodes-full-width.php">Tabs & Toggles</a></li>
														<li><a href="shortcodes-full-width.php">Alerts</a></li>
														<li class="last"><a href="shortcodes-full-width.php">Columns</a></li>
													</ul>
												</div>
											</li>
											<li class="last"><a href="404.php">404 Page</a></li>
										</ul>
									</div>
								</li>
								<li><a href="contact.php">Contact Us</a></li>
			        		</ul>
			        	</nav>
		        	<div class="search">
		        		<form method="post" action="#" id="search_form">
		        			<fieldset>
		        				<input type="text" id="search_text" placeholder="Search">
		        				<input type="submit" value="GO" id="search_submit">
		        				<p class="btn_search">search</p>
		        			</fieldset>
		        		</form>
		        	</div>
		        	<div class="clearfix"></div>
				</div><!-- /wrapper -->
		    </div>
	        <div id="featured">
	        	<div class="wrapper">
	        		<h1>Sidebar & Shortcodes</h1>
	        	</div>
	        </div><!-- /featured -->
			<div class="wrapper blog_page">
				<div id="content" class="not_single">
					<article class="post">
						<div class="entry">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac, laoreet enim. Proin eleifend urna in lorem fringilla faucibus. Cras non interdum magna, id pretium tellus. Duis ligula purus, facilisis vel vehicula in, laoreet ut eros.</p>
							<p class="space">&nbsp;</p>
							<h3>Buttons</h3>
							<a href="#" class="btn small blue">Button</a><a href="#" class="btn small red">Button</a><a href="#" class="btn small grey">Button</a><a href="#" class="btn small">Button</a><a href="#" class="btn small purple">Button</a><a href="#" class="btn small yellow">Button</a><a href="#" class="btn small aqua">Button</a><a href="#" class="btn small black">Button</a>
							<a href="#" class="btn blue">Button</a><a href="#" class="btn red">Button</a><a href="#" class="btn grey">Button</a><a href="#" class="btn">Button</a><a href="#" class="btn purple">Button</a><a href="#" class="btn yellow">Button</a><a href="#" class="btn aqua">Button</a><a href="#" class="btn black">Button</a>
							<a href="#" class="btn large blue">Button</a><a href="#" class="btn large red">Button</a><a href="#" class="btn large grey">Button</a><a href="#" class="btn large">Button</a><a href="#" class="btn large purple">Button</a><a href="#" class="btn large yellow">Button</a><a href="#" class="btn large aqua">Button</a><a href="#" class="btn large black">Button</a>
							<p class="space">&nbsp;</p>
							<h3>Tabs</h3>
							<div class="tabs_box">
								<ul class="tabs">
									<li class="active"><a href="#tab_1">Tab 1</a></li>
									<li><a href="#tab_2">Tab 2</a></li>
									<li><a href="#tab_3">Tab 3</a></li>
								</ul>
								<div class="box active" id="tab_1">
									<p>tab 1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
								</div>
								<div class="box" id="tab_2">
									<p>tab 2. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
								</div>
								<div class="box" id="tab_3">
									<p>tab 3. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
								</div>
							</div>
							<div class="tabs_box">
								<ul class="tabs">
									<li class="active"><a href="#tab_2_1">Tab 1</a></li>
									<li><a href="#tab_2_2">Tab 2</a></li>
								</ul>
								<div class="box active" id="tab_2_1">
									<p>tab 2-1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
								</div>
								<div class="box" id="tab_2_2">
									<p>tab 2-2. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
								</div>
							</div>
							<p class="space">&nbsp;</p>
							<h3>Toggles</h3>
							<div class="toggle">
								<div class="item current">
									<div class="header"><h4>Toggle 1</h4></div>
									<div class="box">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
									</div>
								</div>
								<div class="item">
									<div class="header"><h4>Toggle 2</h4></div>
									<div class="box">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
									</div>
								</div>
								<div class="item">
									<div class="header"><h4>Toggle 3</h4></div>
									<div class="box">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent tristique aliquam diam. Proin pretium ipsum non metus tincidunt, a vestibulum augue rhoncus. Phasellus lacinia pulvinar dolor sodales fringilla. Sed ac felis condimentum, tristique turpis ac laoreet enim.</p>
									</div>
								</div>
							</div>
							<p class="space">&nbsp;</p>
							<h3>Alerts</h3>
							<div class="alert_box">
								<p>Standard alert message.</p>
							</div>
							<div class="alert_box warning">
								<p>Warning alert message. You might want to do something here.</p>
							</div>
							<div class="alert_box error">
								<p>Error alert message. Something is wrong.</p>
							</div>
							<div class="alert_box success">
								<p>Success alert message. Congrats user.</p>
							</div>
							<div class="alert_box information">
								<p>Information alert message. Just a heads up.</p>
							</div>
							<p class="space">&nbsp;</p>
							<h3>Columns</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer egestas erat sit amet nisi mattis, sed bibendum quam ultricies. Suspendisse eget auctor quam dolor. Aliquam sapien ante, condimentum quis ipsum quis, scelerisque porta nisl. Nulla facilisi. Maecenas non rutrum felis. Aenean sed felis sit amet nisi feugiat laoreet duis sit amet lectus vitae massa laoreet hendrerit. Maecenas tempor aliquet viverra. Fusce quis consequat eros, fermentum malesuada urna. Suspendisse consectetur elit a rutrum lacinia. </p>
							<p class="space">&nbsp;</p>
							<div class="col col_1_2">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet convallis ut, tempus porta arcu. Phasellus accumsan facilisis viverra. Sed a velit sit amet nunc aliquam consectetur.</p>
							</div>
							<div class="col col_1_2 last">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet convallis ut, tempus porta arcu. Phasellus accumsan facilisis viverra. Sed a velit sit amet nunc aliquam consectetur.</p>
							</div>
							<div class="clearfix"></div>
							<p class="space">&nbsp;</p>
							<div class="col col_1_3">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet convallis ut, tempus porta arcu. Phasellus accumsan.</p>
							</div>
							<div class="col col_1_3">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet convallis ut, tempus porta arcu. Phasellus accumsan.</p>
							</div>
							<div class="col col_1_3 last">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet convallis ut, tempus porta arcu. Phasellus accumsan.</p>
							</div>
							<div class="clearfix"></div>
							<p class="space">&nbsp;</p>
							<div class="col col_1_4">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet.</p>
							</div>
							<div class="col col_1_4">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet.</p>
							</div>
							<div class="col col_1_4">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet.</p>
							</div>
							<div class="col col_1_4 last">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sodales purus vel nunc elementum rhoncus. Praesent neque metus, mollis sit amet.</p>
							</div>
							<div class="clearfix"></div>
							<p class="empty">&nbsp;</p>
						</div>
					</article>
				</div><!-- /content -->
				<aside id="sidebar">
					<div class="sidebar_widgets">
						<div class="widget widget_text">
							<h3 class="widgettitle">About Deliver Theme</h3>
							<div class="textwidget">
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent justo ligula, interdum ut lobortis quis, interdum vitae metus. Proin fringilla metus non nulla cursus, sit amet rutrum est pretium.</p>
							</div>
						</div>
						<div class="widget widget_popular">
							<h3 class="widgettitle">Popular Posts</h3>
							<ul>
								<li><a href="blog_item.php">New Premium WordPress Theme - Blaco</a></li>
								<li><a href="blog_item.php">7 Strategies to Market Your Business Online</a></li>
								<li><a href="blog_item.php">Top Web Trends of 2013</a></li>
								<li><a href="blog_item.php">Typical Day of a Designer</a></li>
								<li><a href="blog_item.php">How Important is Twitter</a></li>
							</ul>
						</div>
						<div class="widget widget_flickr">
							<h3 class="widgettitle">Gallery Widget</h3>
							<ul>
								<li><a href="/static/images/pic_blog_1.png" title="Flickr Widget"><img src="/static/images/pic_flickr_1.jpg" alt=""></a></li>
								<li><a href="/static/images/pic_blog_2.png" title="Flickr Widget"><img src="/static/images/pic_flickr_1.jpg" alt=""></a></li>
								<li><a href="/static/images/pic_blog_1.png" title="Flickr Widget"><img src="/static/images/pic_flickr_1.jpg" alt=""></a></li>
								<li><a href="/static/images/pic_blog_2.png" title="Flickr Widget"><img src="/static/images/pic_flickr_1.jpg" alt=""></a></li>
								<li><a href="/static/images/pic_blog_1.png" title="Flickr Widget"><img src="/static/images/pic_flickr_1.jpg" alt=""></a></li>
								<li><a href="/static/images/pic_blog_2.png" title="Flickr Widget"><img src="/static/images/pic_flickr_1.jpg" alt=""></a></li>
								<li><a href="/static/images/pic_blog_1.png" title="Flickr Widget"><img src="/static/images/pic_flickr_1.jpg" alt=""></a></li>
								<li><a href="/static/images/pic_blog_2.png" title="Flickr Widget"><img src="/static/images/pic_flickr_1.jpg" alt=""></a></li>
							</ul>
						</div>
						<div class="widget widget_twitter">
							<h3 class="widgettitle">Twitter Widget</h3>
							<ul><li></li>
							</ul>
						</div>
					</div>
				</aside><!-- /sidebar -->
				<div class="clearfix"></div>
			</div><!-- /wrapper -->
    	</div>
    	
    	<div class="wrapper">
	    	<footer>
	    		<section class="top">
	    			<div class="info">
			        	<a href="#" class="ir" id="logo_footer">Website</a>
			        	<ul class="socials">
			        		<li><a target="_blank" href="https://twitter.com/bestpsdfreebies" class="ir twitter">twitter</a></li>
			        		<li><a target="_blank" href="https://www.facebook.com/bestpsdfreebies" class="ir facebook">facebook</a></li>
			        		<li><a target="_blank" href="http://feeds.feedburner.com/bestpsdfreebies" class="ir rss">rss</a></li>
			        		<li><a target="_blank" href="http://www.pinterest.com/mjreimer/psd-freebies/" class="ir pinterest">pinterest</a></li>
			        	</ul>
	    				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit mivitae. Vestibulum gravida quam quis nunc rutrum placerat. Proin eu mi vitae neque veh interdum id nec turpis nam auctor faucibus sollicitudin.</p>
	    			</div>
	    			<div class="widgets">
	    				<div class="widget widget_text">
	    					<h3 class="widgettitle">Contact info</h3>
	    					<div class="textwidget">
	    						<p>222 Avenue C South<br>Saskatoon, SK <br>Canada S7K 2N5</p>
	    						<p>info@deliver.ca</p>
	    						<h4>1.306.222.3456</h4>
	    					</div>
	    				</div>
	    				<div class="widget widget_menu">
	    					<h3 class="widgettitle">Quick Links</h3>
			        		<ul>
								<li><a href="portfolio.php">Portfolio</a></li>
								<li><a href="about.php">About</a></li>
								<li><a href="archive.php">Archive</a></li>
								<li><a href="blog.php">Blog</a></li>
								<li><a href="services.php">Services</a></li>
								<li><a href="contact.php">Contact Us</a></li>
			        		</ul>
	    				</div>
	    				<div class="widget widget_newsletter">
	    					<h3 class="widgettitle">Newsletter</h3>
	    					<div class="textwidget">
	    						<p>Lorem ipsum dolor sit amet dolor consectetur adipiscing elit.</p>
				        		<form method="post" action="#">
				        			<fieldset>
				        				<input type="text" placeholder="Email">
				        				<input type="submit" value="OK">
				        			</fieldset>
				        		</form>
	    					</div>
	    				</div>
	    			</div>
	    		</section><!-- /top -->
	    		<section class="bottom">
	    			<p class="copyrights">Copyright 2013 Deliver. All Rights Reserved.</p>
	    			<ul>
	    				<li><a href="about.php">About</a></li>
	    				<li><a href="#">Privacy Policy</a></li>
	    				<li><a href="contact.php">Contact</a></li>
	    			</ul>
	    		</section><!-- /bottom -->
	    	</footer><!-- /footer -->
		</div><!-- /wrapper -->

        <script src="js/jquery.js"></script>
        <script src="js/jquery.isotope.min.js"></script>
        <script src="js/jquery.placeholder.js"></script>
        <script src="js/jquery.flexslider-min.js"></script>
        <script src="js/jquery.magnific.popup.min.js"></script>
        <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="js/jquery.magnific.popup.min.js"></script>

        <script src="js/main.js"></script>
    </body>
</html>
