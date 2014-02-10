<script type="text/javascript" src="includes/scripts/expand.js"></script>
<style type="text/css">
.colleps a{}
 .colleps a:hover, a:active, a:focus {
  border-style:solid;
   outline:0 none
}
.expand{
  }
  .open , .closed{
          padding-left:10px;
  }
  .expand a:link, .expand a:visited {
        background: url(images/arrow-up.png) no-repeat 0% 65%;
  }
  .expand a.open:link, .expand a.open:visited {
 background: url(images/arrow-down.png) no-repeat 0% 65%
}
</style>
<script type="text/javascript">

$(function() {
    // --- first section initially expanded:
    $(".expand").toggler();
    $("#content").expandAll({trigger: "h4.expand", ref: "div.demo",  speed: 300, oneSwitch: false});
});

</script>
<?php
require_once("library/session.php");
include_once("./editables/loginform.php");
$path=        getEditablePath('loginform.php');
include_once("./editables/".$path);
$part=0;//sets the default part for the login to either login table or profile links
                        //chnge to check for if(logged_in)
if($session->logged_in)
{
        $userid=$session->userid;
        $prurl = getUserProfileUrl($session->userid);
        $co_access= $database->isBorrowerAlreadyAccess($session->userid);
        if($session->userlevel==PARTNER_LEVEL)
        { 
?>        
                <h2><?php echo $lang['loginform']['my_account'];?></h2>
                <p><strong><a href="<?php echo $prurl?>"><?php echo $lang['loginform']['profile'] ?></a></p>
                <p><a href="index.php?p=13"><?php echo $lang['loginform']['eprofile'] ?></a></p>
                <p><a href="index.php?p=7"><?php echo $lang['loginform']['search_b'] ?></a></p>
                <p><a href="index.php?p=8&prt=1"><?php echo $lang['loginform']['act_borrower'] ?></a></p>
                <p><a href="index.php?p=87"><?php echo $lang['loginform']['decliened_borrower'] ?></a></strong></p>
                <?php if($co_access==1) {
                                $country_code=$database->getCountryCodeById($session->userid); 
                ?>
                <div class="demo">
                        <h3 class="expand "><?php echo $lang['loginform']['mentor_page']?></h3>
                        <div class="collapse small_link_col">
                                <ul>
                                <li><a href="index.php?p=8"><?php echo $lang['loginform']['volunteer_mentor1'] ?></a></li>        
                                <li><a href="index.php?p=31&c=<?php echo $country_code?>"><?php echo $lang['loginform']['volunteer_mentor2'] ?></a></li>
                <?php if($country_code=='KE'){?>
                                <li><a href="https://sites.google.com/site/zidishavolunteermentor/" target="_blank"><?php echo $lang['loginform']['volunteer_guide_line'] ?></a></li>
                <?php }?>

                                </ul>
                        </div>
                </div>
                <?php }?>
                
                <p>&nbsp;</p>

                <h2><?php echo $lang['loginform']['mystatistics'] ?></h2>
                <p><strong><span class="blue"><?php echo $lang['loginform']['active_status'] ?>:</span>
                <?php
                        $active=$database->getPartnerStatus($session->userid);
                        if($active==0){
                                echo $lang['loginform']['Inactive'];
                        }
                        else if($active==1){
                                echo $lang['loginform']['Active'];
                        }
                ?>
                </p>
                <p><span class="blue"><?php echo $lang['loginform']['sponsored_bus'] ?>:</span> <?php echo $database->getActiveBCount($session->userid);?></strong></p>
                <p>&nbsp;</p>
<?php
        }

        //left panel menu for lender account 
        if($session->userlevel==LENDER_LEVEL)
        {
                $active_investamtDisplay=$database->amountInActiveBidsDisplay($session->userid);
                $active_investamtFunded=$database->amountInActiveBidsFunded($session->userid);
                $myimapctSec = $database->getMyImpact($session->userid);
?>
                <h2><?php echo $lang['loginform']['my_account'];?></h2>
                <p><strong><a href="index.php?p=19&u=<?php echo $session->userid;?>"><?php echo $lang['loginform']['myportfolio'] ?></a></p>
                <p><a href="<?php echo $prurl?>"><?php echo $lang['loginform']['profile'] ?></a></p>
                <p><a href="index.php?p=13"><?php echo $lang['loginform']['eprofile'] ?></a></p>
                <p><a href="index.php?p=16&u=<?php echo $session->userid;?>"><?php echo $lang['loginform']['tran_hist'] ?></a></p>
                <p><a href="index.php?p=17"><?php echo $lang['loginform']['lender_withdraw'] ?></a></p>
                <p><a href="index.php?p=74"><?php echo $lang['loginform']['auto_lend'] ?></a></p>
                <p><a href="index.php?p=30"><?php echo $lang['loginform']['invite_frnds'] ?></a></p>
                <p><a href='index.php?p=80'><?php echo $lang['loginform']['lendingGroups']?></a></strong></p>
                <?php if($co_access==1) {
                        $country_code=$database->getCountryCodeById($session->userid); 
                ?>
                <div class="demo">
                        <h3 class="expand "><?php echo $lang['loginform']['mentor_page']?></h3>
                        <div class="collapse small_link_col">
                                <ul>
                                <li><a href="index.php?p=8"><?php echo $lang['loginform']['volunteer_mentor1'] ?></a></li>                <li><a href="index.php?p=31&c=<?php echo $country_code?>"><?php echo $lang['loginform']['volunteer_mentor2'] ?></a></li>
                <?php if($country_code=='KE'){?>
                                <li><a href="https://sites.google.com/site/zidishavolunteermentor/" target="_blank"><?php echo $lang['loginform']['volunteer_guide_line'] ?></a></li>
                <?php }?>
                                </ul>
                        </div>
                </div>
                <?php }?>

                <!-- Volunteer account access pages -->
                <?php
                
                $staff_access=$database->isTranslator($session->userid);
                
                if($staff_access == 1) {
                    $country_code=$database->getCountryCodeById($session->userid); 
                    ?>
                    <div class="demo">
                    <h3 class="expand "><?php echo $lang['loginform']['virtual_volunteers']?></h3>
                        <div class="collapse small_link_col">

                        <ul> 
                            <li><a href="https://sites.google.com/a/zidisha.org/zidisha-staff/home/country-liaison-intern-guide" target="_blank"><?php echo $lang['loginform']['volunteer_guide'];?></a></li>
                            <li><a href="http://zidisha.org/forum/categories/volunteer-conversations.26/" target="_blank"><?php echo $lang['loginform']['volunteer_forum'];?></a></li>                                   
                            <li><a href="index.php?p=102"><?php echo $lang['loginform']['find_brwr'] ?></a></li>
                            <li><a href="index.php?p=99"><?php echo $lang['loginform']['pending_disbursed'];?></a></li>
                            <li><a href="index.php?p=11&a=1&type=1&ord=ASC"><?php echo $lang['loginform']['view_b'];?></a></li>
                            <li><a href="index.php?p=84"><?php echo $lang['loginform']['community_organizer'] ?></a></li>
                            <li><a href="index.php?p=25">Activate Volunteers</a></li>
                            <li><a href="index.php?p=39"><?php echo $lang['loginform']['change_password'];?></a></li>
                            <li><a href="index.php?p=11&a=3&type=1&ord=ASC"><?php echo $lang['loginform']['view_l'];?></a></li>
                            <li><a href="index.php?p=31"><?php echo $lang['loginform']['repay_report'];?></a></li>
                            <li><a href="index.php?p=23"><?php echo $lang['loginform']['pfreport'];?></a></li>
                        </ul>
                        </div>
                    </div>
                <?php }?>


                <p>&nbsp;</p>

                <!-- My Statistics section for lender accounts -->
                <h2><?php echo $lang['loginform']['mystatistics'] ?></h2>
                <p><strong><span><?php echo $lang['loginform']['total_avl_amt'] ?>:</span> USD 
                <?php 
                        $amtUseforbid = round($session->amountToUseForBid($userid), 4);
                        $amtincart = $database->getAmtinLendingcart($userid);
                        if(isset($_SESSION['Nodonationincart'])) {
                                $creditavail = $amtUseforbid - $amtincart['amt'];
                        }else {
                                $creditavail = $amtUseforbid - $amtincart['amt'] - $amtincart['donation'];
                        }
                        if($creditavail<0) {
                                $creditavail = 0;
                        }
                        echo number_format(truncate_num($creditavail, 4, 2) , 2, '.', ','); 

                ?></p>
                <p><?php
                        $creditincart = $amtUseforbid - $creditavail;
                        if($creditincart>0){
                                        ?><tr style="height:6px"><td></td></tr>
                                        <?php echo "<td width='250px'>Credit In Lending Cart: </td><td>USD ".number_format($creditincart, 2, ".", ",")."</td>";
                                        }                
                ?></p>
                <p><span><?php echo $lang['loginform']['amount_invested'] ?>:</span> USD <?php 
                        $amount_invested = explode('.', number_format($database->totalAmountLend($userid)+$active_investamtDisplay,2, '.', ','));        
                        echo  $amount_invested['0'];         
                 ?></p>
                <p><span><?php echo $lang['loginform']['AmtLentByInvitee'] ?>:</span> USD <?php 
                        $AmtLentByInvitee = explode('.', number_format($myimapctSec['invite_AmtLent'],2, '.', ','));        
                        echo  $AmtLentByInvitee['0'];
                         ?></p>
                <p><span><?php echo $lang['loginform']['AmtLentByGiftrecp'] ?>:</span><br/> USD <?php 
                        $AmtLentByGiftrecp = explode('.', number_format($myimapctSec['Giftrecp_AmtLent'],2, '.', ','));        
                        echo  $AmtLentByGiftrecp['0']; 
                ?></p>
                <p><span><?php echo $lang['loginform']['LenderTotalImpact'] ?>:</span> USD <?php 
                        $TotalImpact = explode('.', number_format($database->totalAmountLend($userid)+$active_investamtDisplay+$myimapctSec['invite_AmtLent'] + $myimapctSec['Giftrecp_AmtLent'],2, '.', ','));        
                        echo  $TotalImpact['0'];
                        ?></strong></p>
                <p>&nbsp;</p>
<?php
        }

        //left panel menu for borrower account
        if($session->userlevel==BORROWER_LEVEL)
        {
                $lastLoan=$database->getLastloan($session->userid);
                $row = $database->getBorrowerDetails($userid);
?>                
                <h2><?php echo $lang['loginform']['my_account'];?></h2>
                
                <p><strong><a href="index.php?p=50"><?php echo $lang['loginform']['welcome_page'] ?></a></strong></p>
                <p><strong><a href="index.php?p=37&l=<?php echo $lastLoan['loanid']; ?>"><?php echo $lang['loginform']['ac_detail'] ?></a></strong></p>

                <p><strong><a href="index.php?p=71&u=<?php echo $userid;?>"><?php echo $lang['loginform']['b_repayment_instructions'] ?></a></strong></p>
                
                <?php $loanstatus=$database->getLoanStatus($userid);
                         if($loanstatus == LOAN_ACTIVE || $loanstatus == LOAN_OPEN || $loanstatus == NO_LOAN) {
                ?>
                <p><strong><a href="index.php?p=76"><?php echo $lang['loginform']['credit_limit'] ?></a></strong></p>
                <?php } ?>
                <p><strong><a href="index.php?p=96"><?php echo $lang['loginform']['binvite_new'] ?></a></strong></p>
                <p><strong><a href="index.php?p=97"><?php echo $lang['loginform']['binvited_members'] ?></a></strong></p>
                <?php if(isset($lastLoan['loanid'])) {
                $loanprurl = getLoanprofileUrl($userid, $lastLoan['loanid']);                        
                ?>
                <p><strong><a href="<?php echo $loanprurl?>"><?php echo $lang['loginform']['view_crnt_loan'] ?></a></strong></p>
                <?php } ?>
                <p><strong><a href="index.php?p=13"><?php echo $lang['loginform']['eprofile'] ?></a></strong></p>
                <p><strong><a href="index.php?p=111"><?php echo $lang['loginform']['additional_verification'] ?></a></strong></p>
                
                <?php     
                $loan_status=$database->getBorrowerCurrentLoanStatus($userid);
                if($loan_status==LOAN_ACTIVE)
                {                                
                                                $status=$database->canBorrowerReSchedule($userid,$lastLoan['loanid']);
                                                if($status)
                                                {        
                                                        $count=$database->LastReScheduleLimit($userid,$lastLoan['loanid']);                // update by mohit on date 3-11-13 to prevent reschedule loan                        
                                                        $rescheduleResult=$database->getRescheduleDataByLoanId($lastLoan['loanid']);                                                
                                                        $isActive=$database->rschdIsActive($rescheduleResult['date'],$lastLoan['loanid'],$userid);
                                                        if($count>0){
                                                                        if($isActive==0){
                                                                        }else{?>
                                                                        <p><strong><a href="index.php?p=41&l=<?php echo $lastLoan['loanid']; ?>"><?php echo $lang['loginform']['reschedule'];?></a></strong></p>
                                                               <?php }                                                        
                                                        }else{ ?>
                                                                <p><strong><a href="index.php?p=41&l=<?php echo $lastLoan['loanid']; ?>"><?php echo $lang['loginform']['reschedule'];?></a></strong></p>
                                                   <?php } 
                                                }
                }        ?>
<?php           $referDetail=$database->getReferrals($row['Country'], false);
                if(!empty($referDetail))
                {        ?>
                                <p><strong><a href="index.php?p=49"><?php echo $lang['loginform']['referral_program'] ?></a></strong></p>
<?php        }        ?>
                
                <!-- Volunteer Mentor access pages -->
                <?php if($co_access==1) {
                        $country_code=$database->getCountryCodeById($session->userid);         
                ?>
                <div class="demo">
                        <h3 class="expand "><?php echo $lang['loginform']['mentor_page']?></h3>
                        <div class="collapse small_link_col">
                                <ul>
                                <li><a href="index.php?p=8"><?php echo $lang['loginform']['volunteer_mentor1'] ?></a></li>                <li><a href="index.php?p=31&c=<?php echo $country_code?>"><?php echo $lang['loginform']['volunteer_mentor2'] ?></a></li>
                <?php if($country_code=='KE'){?>
                                <li><a href="https://sites.google.com/site/zidishavolunteermentor/" target="_blank"><?php echo $lang['loginform']['volunteer_guide_line'] ?></a></li>
                <?php }?>

                                </ul>
                        </div>
                </div>
                <?php }?><br/>
                
        <?php
        }

        //left panel menu options for admin account
        if($session->userlevel==ADMIN_LEVEL)
        {
        ?>
                <h2><?php echo $lang['loginform']['Administrator'];?></h2>
                <div class="colleps" style="height:300px;overflow:auto">
                        <div class="demo">
                                <h3 class="expand ">Manage Borrowers</h3>
                                <div class="collapse small_link_col">
                                   <ul> 
                                   <li><a href="index.php?p=102"><?php echo $lang['loginform']['find_brwr'] ?></a></li>
                                   <li><a href="index.php?p=7&type=3&ord=DESC"><?php echo $lang['loginform']['search_b'];?></a></li>
                                    <li><a href="index.php?p=99"><?php echo $lang['loginform']['pending_disbursed'];?></a></li>
                                   <li><a href="index.php?p=11&a=1&type=1&ord=ASC"><?php echo $lang['loginform']['view_b'];?></a></li>
                                   <li><a href="index.php?p=45"><?php echo $lang['loginform']['rescheduled_loans'];?></a></li>
                                   <li><a href="index.php?p=73"><?php echo $lang['loginform']['loan_forgiveness'] ?></a></li>
                                   <li><a href="index.php?p=84"><?php echo $lang['loginform']['community_organizer'] ?></a></li>
                                   <li><a href="index.php?p=39"><?php echo $lang['loginform']['change_password'];?></a></li>
                                  </ul>
                                </div>
                        </div>
                        <div style='margin-top:10px;' class="demo">
                                <h3 class="expand">Manage Lenders</h3>
                                        <div class="collapse small_link_col">
                                                <ul>
                                                        <li><a href="index.php?p=11&a=3&type=1&ord=ASC"><?php echo $lang['loginform']['view_l'];?></a></li>

                                                        <li><a href="index.php?p=17"><?php echo $lang['loginform']['admin_withdraw'];?></a></li>
                                                        <li><a href="index.php?p=63"><?php echo $lang['loginform']['nr'];?></a></li>
                                                        <li><a href="index.php?p=29"><?php echo $lang['loginform']['mng_exp_card'];?></a></li>
                                                        <li><a href="index.php?p=53"><?php echo $lang['loginform']['lender_credit'] ?></a></li>
                                                        <li><a href="index.php?p=25">Activate Volunteers</a></li>
                                                        <li><a href="index.php?p=20">Send Emails</a></li>
                                                        
                                                </ul>
                                                </div>
                         </div>
                         <div style='margin-top:10px;' class="demo">
                                                   <h3 class="expand">Translation</h3>
                                        <div class="collapse small_link_col">
                                                <ul>
                                                        <li><a href="index.php?p=32&ref=2"><?php echo $lang['loginform']['upload_files'];?></a></li>
                                                        <li><a href="index.php?p=32&ref=1"><?php echo $lang['loginform']['trans_label'];?></a></li>
                                                        <li><a href="index.php?p=32&ref=3"><?php echo $lang['loginform']['download_files'];?></a></li>
                                                        <li><a href="index.php?p=35"><?php echo $lang['loginform']['manage_lang'];?></a></li>
                                                </ul>
                                        </div>
                         </div>
       
                         <div style='margin-top:10px;' class="demo">
                                <h3 class="expand">Reports</h3>
                                        <div class="collapse small_link_col">
                                                <ul>
                                                        <li><a href="index.php?p=22">Transaction History Details</a></li>                                                                                                        <li><a href="index.php?p=108">Transaction History Totals</a></li>
                                                        <li><a href="index.php?p=31"><?php echo $lang['loginform']['repay_report'];?></a></li>
                                                        <li><a href="index.php?p=23"><?php echo $lang['loginform']['pfreport'];?></a></li>
                                                        <li><a href="index.php?p=114">Loans Funded</a></li>
                                                        <li><a href="index.php?p=117">Lender Invite Report</a></li>
                                                        <li><a href="index.php?p=112">Borrower Invite Report</a></li>
                                                        <li><a href="index.php?p=109">New Member Activation Rate</a></li>
                                                        <li><a href="index.php?p=110">New Member Repayment Rate</a></li>
                
                                                
                                                </ul>
                                  </div>
                         </div>
                        
                </div>
                

<?php
        }
                
 
}
?>