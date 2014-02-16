<?php 

  include_once("library/session.php");
  global $database, $session;

  if(empty($session->userid))

      { ?>
        <div id="login" align="left">

          <p href="/index.php?p=116">Login</p>

            <p style="text-align:right">
              <?php   
                  $Lendingcart = $database->getLendingCart(); 
                  if(!empty($Lendingcart)) {
              ?>
              <a href='index.php?p=75'><img src='images/layout/icons/cart.gif'> Lending Cart</a>
              <?php } ?>
              <input type="checkbox" id="remember" name="remember" /><label for="remember"><?php echo $lang['loginform']['rme'];?></label> &nbsp;|&nbsp; <a style="color:gray" href="index.php?p=56"><?php echo $lang['loginform']['fypassword'];?></a> &nbsp;|&nbsp; <?php echo $lang['loginform']['not_a_member'];?>&nbsp;&nbsp;<a style='color:#FF8B00;font-weight:bold;font-size:14px;' href="index.php?p=1&amp;sel=2"><?php echo $lang['loginform']['join_today'];?></a>
            </p>
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
            
          </div> <!-- /margin-top:10px -->
        </div> <!-- /welcome -->
  <?php } ?>