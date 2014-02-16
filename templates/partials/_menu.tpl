<div id="menu">
  <div class="wrapper">
    <div id="menu_trigger" class="mobile">menu</div>
      <nav>
        <ul>
          <li><a href="microfinance/lend.html">Lend</a></li>
          <li><a href="microfinance/borrow.html">Borrow</a></li>
          <li>
            <a href="shortcodes-with-sidebar.php">
              Learn More <span class="drops"></span></a>
            
            <div class="drop">
              <div class="top"></div>
              <ul>
                <li><a href="microfinance/faq.html">FAQ</a></li>
                <li><a href="microfinance/how-it-works.html">How it Works</a></li>
                <li><a href="microfinance/why-zidisha.html">Why Zidisha?</a></li>
                <li><a href="microfinance/press.html">Press</a></li>
                <li><a href="http://www.amazon.com/Venture-Collection-Microfinance-Stories-ebook/dp/B009JC6V12">Book</a></li>
                <!--
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
                -->
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      
      <div class="search">
        <form method="post" action="updateprocess.php" id="search_form">
          <fieldset>
          <input type="text" name="searchLoan" placeholder="Search">
          <input type="hidden" name="searchSort">
          <input type='hidden' name='get_loans'/>
          <input type="hidden" name="user_guess" value="{php} echo generateToken('get_loans'); {/php}"/>
                        
          <input id="goSubmit" type="submit" value="GO" id="search_submit">
          <p class="btn_search">search</p>
          </fieldset>
        </form>
      </div>
      
      <div class="clearfix"></div>
  </div><!-- /wrapper -->
</div><!-- /menu -->