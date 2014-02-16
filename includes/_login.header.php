<?php 

  include_once("library/session.php");
  global $database, $session;

  // Grabs translated strings for login form.
  include_once("./editables/loginform.php");
  $path=  getEditablePath('loginform.php');
  include_once("./editables/".$path);

  if(empty($session->userid))

      { ?>
        <div id="login" align="right">

          <p><strong><a href="/index.php?p=116">Log In</a></strong></p>

            <p style="text-align:right">
              <?php   
                  $Lendingcart = $database->getLendingCart(); 
                  if(!empty($Lendingcart)) {
              ?>
              <a href='index.php?p=75'>Lending Cart</a>
              <?php } ?>
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
          
          <div style="margin-top:10px">

            <?php if($session->userlevel==PARTNER_LEVEL)
              {
                echo "<a href='index.php?p=7'>".$lang['loginform']['pending_app']."</a>";
              }
            else if($session->userlevel==LENDER_LEVEL)
              {
                echo"<a href='index.php?p=75'>Lending Cart</a>";
                echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                echo "<a href='index.php?p=119'>My Account</a>";
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

            &nbsp;&nbsp;&nbsp;&nbsp; <a href='process.php'><?php echo $lang['loginform']['Logout']?></a>

          </div> <!-- /margin-top:10px -->
        </div> <!-- /welcome -->
  <?php } ?>