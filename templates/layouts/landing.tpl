<!DOCTYPE html>

{config_load file='zidisha.conf'}
{include file='_html.tpl'}

<head>
  <title>Zidisha | {block name=title}Default Page Title{/block}</title>
  <meta name="description" content="{block name=description}Peer-to-peer lending across the international wealth divide.{/block}">

  {include file='_meta.tpl'}

  <link href="/static/css/styles.css" rel="stylesheet">
  <script src="/static/js/libs/modernizr-2.6.2.min.js"></script>
</head>

<body>
  <div id="wrapper_content">
      
    {include file='_oldbrowser.tpl'}
    {include file='_social.tpl'}
    {include file='_menu.tpl'}

    <div id="featured">
    	<div class="wrapper">
    		<h1>{block name=title}Default Page Title{/block}</h1>
    	</div>
    </div><!-- /featured -->

    <div class="wrapper">
		  <section class="call_action">
		    <div>
		    	{block name=call_to_action_top}{/block}
		    </div>
		  </section>

    	{block name=content}{/block}
    </div><!-- /wrapper -->

    {block name=more}{/block}

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