{extends file="content.tpl"}

{block name=title}Our Blog{/block}
{block name=description}{/block}

{block name=content}

{/block}

{block name=sidebar}

{/block}




<!DOCTYPE html>

<? include('_html.php'); ?>

<head>
  <title>Deliver Theme | Blog</title>
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
    		<h1>Our Blog</h1>
    	</div>
    </div><!-- /featured -->

	<div class="wrapper blog_page">
		<div id="content" class="not_single">
			<article class="post">
				<div class="image">
					<div class="flexslider">
						<ul class="slides">
							<li><img src="/static/images/pic_blog_2.png" alt=""></li>
							<li><img src="/static/images/pic_blog_1.png" alt=""></li>
							<li><img src="/static/images/pic_blog_2.png" alt=""></li>
						</ul>
					</div>
				</div>
				<div class="title">
					<h2><a href="blog_item.php">7 Strategies to Market Your Business Online</a></h2>
				</div>
				<div class="metas">
					<span class="date">July 3, 2013</span>
					<span class="author"><a href="#">Michael Reimer</a></span>
					<span class="tags"><a href="#">Marketing</a>, <a href="#">News</a></span>
					<span class="comments"><a href="#">2 Comments</a></span>
				</div>
				<div class="entry">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sed nisi eget lectus iaculis congue. Nullam eleifend congue turpis, vel scelerisque massa fermentum ac ... </p>
					<div class="clearfix"></div>
				</div>
			</article>
			<article class="post">
				<div class="image">
					<img src="/static/images/pic_blog_1.png" alt="">
				</div>
				<div class="title">
					<h2><a href="blog_item.php">New Premium WordPress Theme - Blaco</a></h2>
				</div>
				<div class="metas">
					<span class="date">July 3, 2013</span>
					<span class="author"><a href="#">Michael Reimer</a></span>
					<span class="tags"><a href="#">Marketing</a>, <a href="#">News</a></span>
					<span class="comments"><a href="#">2 Comments</a></span>
				</div>
				<div class="entry">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sed nisi eget lectus iaculis congue. Nullam eleifend congue turpis, vel scelerisque massa fermentum ac ... </p>
					<div class="clearfix"></div>
				</div>
			</article>
			<article class="post">
				<div class="image">
					<div class="video-embed">
						<iframe src="http://player.vimeo.com/video/70975460" width="750" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
					</div>
				</div>
				<div class="title">
					<h2><a href="blog_item.php">Top Web Trends of 2013</a></h2>
				</div>
				<div class="metas">
					<span class="date">July 3, 2013</span>
					<span class="author"><a href="#">Michael Reimer</a></span>
					<span class="tags"><a href="#">Marketing</a>, <a href="#">News</a></span>
					<span class="comments"><a href="#">2 Comments</a></span>
				</div>
				<div class="entry">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sed nisi eget lectus iaculis congue. Nullam eleifend congue turpis, vel scelerisque massa fermentum ac ... </p>
					<div class="clearfix"></div>
				</div>
			</article>
			<article class="post">
				<div class="title">
					<h2><a href="blog_item.php">Typical Day of a Designer</a></h2>
				</div>
				<div class="metas">
					<span class="date">July 3, 2013</span>
					<span class="author"><a href="#">Michael Reimer</a></span>
					<span class="tags"><a href="#">Marketing</a>, <a href="#">News</a></span>
					<span class="comments"><a href="#">2 Comments</a></span>
				</div>
				<div class="entry">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sed nisi eget lectus iaculis congue. Nullam eleifend congue turpis, vel scelerisque massa fermentum ac ... </p>
					<div class="clearfix"></div>
				</div>
			</article>
			<article class="post">
				<div class="image">
					<img src="/static/images/pic_blog_1.png" alt="">
				</div>
				<div class="title">
					<h2><a href="blog_item.php">How Important is Twitter?</a></h2>
				</div>
				<div class="metas">
					<span class="date">July 3, 2013</span>
					<span class="author"><a href="#">Michael Reimer</a></span>
					<span class="tags"><a href="#">Marketing</a>, <a href="#">News</a></span>
					<span class="comments"><a href="#">2 Comments</a></span>
				</div>
				<div class="entry">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sed nisi eget lectus iaculis congue. Nullam eleifend congue turpis, vel scelerisque massa fermentum ac ... </p>
					<div class="clearfix"></div>
				</div>
			</article>
			<div class="pagination">
				<span class="current">1</span>
				<a href="#">2</a>
				<a href="#">3</a>
				<a href="#">4</a>
			</div>
		</div><!-- /content -->
		<aside id="sidebar">
			<div class="sidebar_widgets">
				<div class="widget widget_text">
					<h3 class="widgettitle">About Deliver Theme</h3>
					<div class="textwidget">
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent justo ligula, interdum ut lobortis quis, interdum vitae metus. Proin fringilla metus non nulla cursus, sit amet <a href="#">rutrum</a> est pretium.</p>
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
	
	<? include('_footer.php'); ?>
	<? include('_scripts.php'); ?>

</body>
</html>
