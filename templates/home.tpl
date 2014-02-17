{extends file="homepage.tpl"}

{block name=title}Zidisha: Join the global P2P microlending movement{/block}
{block name=description}Peer-to-peer lending across the international wealth divide.{/block}

{block name=slides}
<ul class="slides">
  <li class="slide" style="background-image:url(/static/images/flickr/visionking.jpg);">
    <div class="info">
      <h2>Lend <strong>Pherister</strong> $200 for a new school</h2>
      <p>and join the global <strong>person-to-person</strong> microlending movement.</p>
      <a href="/microfinance/lend.html" class="btn blue large">View Our Entrepreneurs</a>
    </div>
    <div class='bg'></div>
  </li>
  <li class="slide" style="background-image:url(/static/images/flickr/melita.jpg);">
    <div class="info">
      <h2>Lend <strong>Melita</strong> $100 for a diary cow</h2>
      <p>and join the global <strong>person-to-person</strong> microlending movement.</p>
      <a href="/microfinance/lend.html" class="btn blue large">View Our Entrepreneurs</a>
    </div>
    <div class='bg'></div>
  </li>
  <li class="slide" style="background-image:url(/static/images/flickr/mary.jpg);">
    <div class="info"> 
      <h2>Lend <strong>Mary</strong> $50 for a delivery wagon</h2>
      <p>and join the global <strong>person-to-person</strong> microlending movement.</p>
      <a href="/microfinance/lend.html" class="btn blue large">View Our Entrepreneurs</a>
    </div>
    <div class='bg'></div>
  </li>
</ul>
{/block}

{block name=content}
<div class="wrapper">
  <section class="call_action">
    <div>
      <h2>P2P lending across the international wealth divide.</h2>
      <p>We are pioneering the first online microlending community to connect lenders and borrowers directly across international borders - overcoming previously insurmountable barriers of geography, wealth and circumstance.</p>
    </div>
  </section>

  <div id="content" class="full">
    <article class="post">
      <div class="entry">
        <div class="col col_1_3">
          <h3>The Innovation</h3>
          <p>People in developing countries support their families with their own small businesses. They need loans in order to grow - but local banks charge exorbitant interest rates.<br/><br/>

          We bypass expensive local banks and connect lenders and borrowers directly. The result is a fairly priced loan - and a friendship that transcends geography.</p>
        </div>

        <div class="col col_2_3 last" style="align:center">
          <h3>How It Works</h3>
        </div>
        <div class="col col_2_3 last">
          <img src="/static/images/worksdirect.png" alt="" style="padding:20px 0 0 0;" />
        </div>
        <div class="clearfix"></div>
          {php} include_once("includes/how-works_infographic.php"); {/php}
        
      </div>
    </article>
  </div>
</div><!-- /wrapper -->
{/block}

{block name=call_to_action_bottom}
<div class="clearfix"></div>
<section class="call_action"></section>
<h2>Become A Lender</h2>
<p>Create a lender account to start making a difference. You can explore entrepreneur stories, find a loan project to support, and connect with others who share the vision of a world where responsible and motivated people have the opportunity to pursue their goals regardless of their location.
</p>
<p><a href="/index.php?p=1&sel=2" class="btn">Join</a></p>
{/block}
