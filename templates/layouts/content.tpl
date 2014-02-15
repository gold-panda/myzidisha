<!DOCTYPE html>

{config_load file='zidisha.conf'}
{include file='_html.tpl'}

<head>
  <title>Zidisha | {block name=title}Default Page Title{/block}</title>
  <meta name="description" content="{block name=description}Peer-to-peer lending across the international wealth divide.{/block}">

  {include file='_meta.tpl'}

  <link href="/static/css/styles.css" rel="stylesheet">
  <script src="/static/js/libs/modernizr-2.6.2.min.js"></script>
  <script type="text/javascript" >var base_url='http://<?php echo $_SERVER['HTTP_HOST'].substr(str_replace(basename($_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']), 0, -1);?>/'</script>
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

    <div class="wrapper {block name=classname}{/block}">
      <div id="content" class="not_single">
        <article class="post">
          <div class="entry">

            {block name=content}{/block}
            
            <div class="clearfix"></div>
          </div><!-- /entry -->
        </article>
      </div><!-- /content -->

      <aside id="sidebar">
        <div class="sidebar_widgets">

          {block name=sidebar}{/block}

        </div>
      </aside><!-- /sidebar -->
      
      <div class="clearfix"></div>
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