<?php


$lang['current_credit']['title'] = "Current Credit Limit";

$lang['current_credit']['beginning'] = "This page shows your current credit limit, or the maximum amount you could raise if you were to post a new loan application today. <br/><br/>Please note that your current credit limit is based the amounts you have repaid in the past, and on the on-time repayment performance of each monthly installment due. From time to time, Zidisha may also offer bonus credits for positive contributions to our community, or change the amounts by which credit limits increase for a given level of performance.<br/><br/><br/>

<strong>Your current credit limit is %currencreditlimit%.</strong>

<br/><br/><br/>
Here is how that credit limit was determined:<br/><br/>
1. Base credit limit: %borwrAmtExceptCredit%<br/><br/>
";

$lang['current_credit']['repayrate_insufficient'] = "<i>Your current on-time repayment rate for monthly installments is %brwr_RepayRate%%. In order to qualify for an increase in maximum loan size, you must improve your on-time repayment rate by making future monthly repayment installments on time.  Once your on-time repayment rate for monthly installments reaches %MinRepayRate%%, you will become eligible for a loan size increase.</i>
<br/><br/><br/>
";

$lang['current_credit']['repayrate_sufficient'] = "<i>Your current on-time repayment rate for monthly installments is %brwr_RepayRate%%. In order to remain eligible for an increase in maximum loan size, you must continue to make at least %MinRepayRate%% of your monthly repayment installments on time.</i>
<br/><br/><br/>
";

$lang['current_credit']['first_loan'] = "<i>This is the standard credit limit for the first loan raised through Zidisha.</i>
<br/><br/><br/>
";

$lang['current_credit']['repaid_late'] = "<i>You are not eligible for a credit limit increase because your most recent loan was not repaid on time.  In order to qualify for an increase in maximum loan size, you must repay your next loan on time while maintaining an on-time repayment rate for monthly installments of at least %MinRepayRate%%.</i>
<br/><br/><br/>
";

$lang['current_credit']['time_insufficient'] = "<i>In order to qualify for an increase in maximum loan size, you must hold the current loan for at least %TimeThrshld% months and maintain an on-time repayment rate for monthly installments of at least %MinRepayRate%%.</i>
<br/><br/><br/>
";

$lang['current_credit']['invite_credit'] = "2. <a href='%newmembercrdt_link%'>Bonus Credit For Inviting New Members</a>: %invite_credit%<br/><br/><br/>";

$lang['current_credit']['comment_credit'] = "3. Comment credits earned: %cmntcreditearned%
<br/><br/>
<i>
	Please not that we are no longer offering bonus credits for posting comments.  During the time when we were offering bonus credits for posting comments, you earned an additional credit limit increase of %cmntcreditearned%.
</i>
<br/><br/><br/>
";

$lang['current_credit']['end'] = "<strong>Total Credit Limit Earned: %currencreditlimit%</strong><br/>

<br/><br/><br/>
In order to increase your maximum loan size, you must:<br/><br/>
<ul style='padding: 0 0 0 2.0em;' ><li>Maintain a %MinRepayRate%% on-time repayment rate for all monthly installments since joining Zidisha.</li>
<li>Make the final repayment installment of the current loan on time.</li>
<li>Hold the current loan for at least %TimeThrshld% months.</li></ul>
<br/><br/>
The current credit limit increase progression for Zidisha members who fulfill the above criteria is as follows:<br/><br/>
	<p>%firstLoanVal%%nxtLoanvalue%</p>
<br/>
";
