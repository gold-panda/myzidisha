<!DOCTYPE html>

{config_load file='zidisha.conf'}
{include file='_html.tpl'}

<head>
  <title>{block name=title}Default Page Title{/block}</title>
  <meta name="description" content="{block name=description}Peer-to-peer lending across the international wealth divide.{/block}">

  {include file='_meta.tpl'}

  <link href="/static/css/styles.css" rel="stylesheet">
  <script src="/static/js/libs/modernizr-2.6.2.min.js"></script>
  <link rel="alternate" type="application/rss+xml" title="Zidisha: P2P Microfinance » Feed" href="http://p2p-microlending-blog.zidisha.org/feed/">
</head>

<body class="home">
  <div id="wrapper_content">
      
      {include file='_oldbrowser.tpl'}

      <div id="home_header">
        <div class="slider-banner">
          {block name=slides}{/block}
        </div>

		{nocache}
		  {include file='_header.tpl'}
		  {include file='_menu.tpl'}
		{/nocache}
      </div><!-- /home_header -->

      {block name=content}{/block}

      <div class="wrapper">
        <section class="call_action bottom">
          <div>
            {block name=call_to_action_bottom}{/block}
          </div>
        </section>
      </div><!-- /wrapper -->

  </div><!-- /wrapper_content -->

  {include file='_footer.tpl'}
  {include file='_scripts.tpl'}

</body>
</html>