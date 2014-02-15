<?php if(empty($session->userid))

      { ?>
        <!-- 31-10-2012 Anupam, Ebook link (requested to remove) -->
        <!--        <div style='float:left;margin-top:20px;margin-left:200px;'><strong><a href="http://www.amazon.com/Venture-Collection-Microfinance-Stories-ebook/dp/B009JC6V12/ref=sr_1_13?s=digital-text&amp;ie=UTF8&amp;qid=1349104493&amp;sr=1-13&amp;keywords=microfinance" target="_blank"><?php echo $lang['loginform']['ebooklink']?></a></strong></div> -->
        <div id="login" align="left">
          <form method="post" action="process.php" class="login-form">
            <div align="right">
              <span class="login-label">Login</span>
              <input class="login-field" type="text" name="username" id="username" value="<?php echo $lang['loginform']['username_login']?>" onfocus='loginfocus(this.value)' onblur='loginblur(this.value)'/>
              
              
              <input class="login-field" type="text" id= "textfield" name="textpassword"  value="<?php echo $lang['loginform']['pwd_login']?>" onfocus="pwdFocus()"/><input class="login-field" id="pwdfield" style="display:none" type="password" name="password" value="" onblur="pwdBlur()" />

              <input type="hidden" name="userlogin" />
              <input type="hidden" name="user_guess" value="<?php echo generateToken('userlogin'); ?>"/>
              <button type="submit" class="btn square">Go</button><br/>
              <div><?php echo $form->error("username"); ?> <?php echo $form->error("password"); ?></div>
            </div>

            <p style="text-align:right">
              <?php   $Lendingcart = $database->getLendingCart(); 
                  if(!empty($Lendingcart)) {
              ?>
              <a href='index.php?p=75'><img src='images/layout/icons/cart.gif'> Lending Cart</a>
              <?php } ?>
              <input type="checkbox" id="remember" name="remember" /><label for="remember"><?php echo $lang['loginform']['rme'];?></label> &nbsp;|&nbsp; <a style="color:gray" href="index.php?p=56"><?php echo $lang['loginform']['fypassword'];?></a> &nbsp;|&nbsp; <?php echo $lang['loginform']['not_a_member'];?>&nbsp;&nbsp;<a style='color:#FF8B00;font-weight:bold;font-size:14px;' href="index.php?p=1&amp;sel=2"><?php echo $lang['loginform']['join_today'];?></a>
            </p>
          </form>
        </div><!-- #login -->

        <script type="text/javascript">
        <!--
          document.getElementById('pwdfield').setAttribute( "autocomplete", "off" );
          document.getElementById('textfield').setAttribute( "autocomplete", "off" );
        //-->
        </script>
  <?php }
      else
      {   ?> 
        <!--<div style='float:left;margin-top:20px;margin-left:200px;'><strong><a href="http://www.amazon.com/Venture-Collection-Microfinance-Stories-ebook/dp/B009JC6V12/ref=sr_1_13?s=digital-text&ie=UTF8&qid=1349104493&amp;sr=1-13&amp;keywords=microfinance" target="_blank"><?php echo $lang['loginform']['ebooklink']?></a></strong></div> -->
        <div id="welcome">
          <?php $prurl = getUserProfileUrl($session->userid);
          $username=$database->getUserNameById($session->userid); ?>
          <h4><a style="color:#000000" href="<?php echo $prurl?>"><?php echo $lang['loginform']['hi']; ?>, <?php echo $username; ?></a></h4>

          <div style="clear:both"></div>
          
          <div style="margin-top:10px">
        <?php if($session->userlevel==PARTNER_LEVEL)
            {
              echo "<a href='index.php?p=7'>".$lang['loginform']['pending_app']."</a>";
            }
            else if($session->userlevel==LENDER_LEVEL)
            {
              echo"<a href='index.php?p=75'><img src='images/layout/icons/cart.gif'> Lending Cart</a>";
              echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
              echo "<a href='index.php?p=19&u=".$session->userid."'>".$lang['loginform']['myportfolio']."</a>";
            }
            else if($session->userlevel==BORROWER_LEVEL)
            {
              $lastLoan=$database->getLastloan($session->userid);
              if(isset($lastLoan['loanid']))
              { $loanprurl = getLoanprofileUrl($session->userid,$lastLoan['loanid']);
                echo "<a href='$loanprurl'>".$lang['loginform']['my_profile']."</a>";
              }
            }
        ?>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href='process.php'><?php echo $lang['loginform']['Logout']?></a>
            
          </div>
        </div>
  <?php } ?>