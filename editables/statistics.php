<?php
$lang['statistics']['report_for_all']='Report for All Countries';
$lang['statistics']['report_for_single']='Report for Country';
$lang['statistics']['pb_ontime']='Paying back on time';
$lang['statistics']['pb_ontime_tooltip']='Loans that have not been rescheduled and are fully up to date or less than 31 days behind on scheduled repayments, within a margin of USD 10.';
$lang['statistics']['pb_ontime_resch']='Paying back on time (rescheduled)';
$lang['statistics']['pb_ontime_resch_tooltip']='Loans that have been rescheduled and are fully up to date or less than 31 days behind on the rescheduled repayments, within a margin of USD 10.';

$lang['statistics']['pb_31-90']='Paying back 31 - 90 days late';
$lang['statistics']['pb_31-90_tooltip']='Loans that have not been rescheduled and are between 31 and 90 days behind on scheduled repayments, within a margin of USD 10.';
$lang['statistics']['pb_31-90_resch']='Paying back 31 – 90 days late (rescheduled)';
$lang['statistics']['pb_31-90_resch_tooltip']='Loans that have been rescheduled and are between 31 and 90 days behind on the rescheduled repayments, within a margin of USD 10.';

$lang['statistics']['pb_91-180']='Paying back 91 - 180 days late';
$lang['statistics']['pb_91-180_tooltip']='Loans that have not been rescheduled and are between 91 and 180 days behind on scheduled repayments, within a margin of USD 10.';
$lang['statistics']['pb_91-180_resch']='Paying back 91 - 180 days late (rescheduled)';
$lang['statistics']['pb_91-180_resch_tooltip']='Loans that have been rescheduled and are between 91 and 180 days behind on the rescheduled repayments, within a margin of USD 10.';

$lang['statistics']['pb_180-over']='Paying back over 180 days late';
$lang['statistics']['pb_180-over_tooltip']='Loans that have not been rescheduled and are more than 180 days behind on scheduled repayments, within a margin of USD 10.';
$lang['statistics']['pb_180-over_resch']='Paying back over 180 days late (rescheduled)';
$lang['statistics']['pb_180-over_resch_tooltip']='Loans that have been rescheduled and are more than 180 days behind on the rescheduled repayments, within a margin of USD 10.';

$lang['statistics']['zidisha_stat']='Zidisha Statistics';
$lang['statistics']['sel_country']='Display Statistics for';
$lang['statistics']['all_country']='All Countries';
$lang['statistics']['cum_stat']='Cumulative Statistics';
$lang['statistics']['cum_stat_tooltip']='These statistics apply to all lending activity that has taken place on Zidisha.org.';
$lang['statistics']['loan_raised']='Loans raised';
$lang['statistics']['loan_raised_tooltip']='The cumulative US Dollar amount of loans fully funded by lenders and accepted by borrowers.';
$lang['statistics']['busi_fin']='Businesses financed';
$lang['statistics']['busi_fin_tooltip']='The cumulative number of loans fully funded by lenders and accepted by borrowers.';
$lang['statistics']['avg_lend_intr']='Average lender interest';
$lang['statistics']['avg_lend_intr_tooltip']='The weighted average lender interest rate of all loans fully funded by lenders and accepted by borrowers, expressed as a flat percentage of loan principal per year the loan is held.';
$lang['statistics']['write_off_rate']='Write-off Rate';
$lang['statistics']['write_off_rate_tooltip']='The write-off rate is calculated as the dollar volume of principal that has either been deemed unrecoverable or has not been repaid six months after the end of its loan term, divided by the total dollar volume of principal whose loan term has expired six months ago or more. <br/><br/>Note: As this calculation includes only ended loans, a more meaningful indicator of the principal risk of active loans is the percentage of active loans that are paying back late, displayed below.';
$lang['statistics']['repay_rate']='Repayment rate';
$lang['statistics']['repay_rate_tooltip']='Percentage of ended loans (no longer paying back) which have been repaid, measured in dollar volume rather than units.  For loans that are not fully paid back at the end of a loan term, collection efforts may be pursued for six additional months before a loan is written off.  The repayment rate is therefore calculated as the dollar volume of principal that has been repaid six months after the end of its loan term, divided by the total dollar volume of principal whose loan term has expired six months ago or more.  <br/><br/>Note: As this definition applies only to ended loans, a more meaningful indicator of the principal risk of active loans is the percentage of active loans that are paying back late, displayed below.';
$lang['statistics']['total_lenders']='Number of registered lenders';
$lang['statistics']['total_countries']='Countries represented by Zidisha members';
$lang['statistics']['act_loan_stat']='Active Loan Statistics';
$lang['statistics']['act_loan_stat_tooltip']='These statistics apply only to loans that are currently outstanding: those that have been disbursed and are paying back.';
$lang['statistics']['category']='Category';
$lang['statistics']['prin_out']='Principal outstanding';
$lang['statistics']['prin_out_tooltip']='The principal portion of loan funds that are still held by borrowers: i.e. the portion of loan principal that has been disbursed and not yet repaid.  Note that this does not include interest owed.';
$lang['statistics']['percent_total']='Percentage of Total';
$lang['statistics']['percent_total_tooltip']='The total principal outstanding in each category, as a percentage of the total principal outstanding in the country.';
$lang['statistics']['total_borrower']="Number of registered borrowers";
$lang['statistics']['writeOff_amount']="Loans written off";
$lang['statistics']['writeOff_amount_tooltip']='The cumulative US Dollar amount of all loans that have been written off since Zidisha\'s founding in 2009.  <br /><br />All loan amounts not repaid six months after their final repayment due dates are written off.  In addition, loans that are deemed unlikely to be recovered may also be written off before six months have passed after their final repayment due dates.';
$lang['statistics']['historical_loss_rate']="Percent of ended loans that have been written off";
$lang['statistics']['historical_loss_rate_tooltip']="The value of all loans that have been written off since Zidisha's founding, divided by the value of all loans that were due to be fully repaid since Zidisha's founding.";
$lang['statistics']['end_repay_rate']="Percent of ended loans that have been repaid";
$lang['statistics']['end_repay_rate_tooltip']="The total amount that has been repaid for all ended loans, divided by the total amount that was due to be repaid for all ended loans.  <br /><br />For the purpose of this calculation, ended loans include all loans whose final repayment installment due date has passed, even if the borrower is still actively repaying.";
$lang['statistics']['repay_late_rate']="Percent of ended loans repaying late";
$lang['statistics']['repay_late_rate_tooltip']="The total amount that is still outstanding with borrowers for all ended loans, divided by the total amount that was due to be repaid for all ended loans. For the purpose of this calculation, ended loans include all loans whose final repayment installment due date has passed.";
$lang['statistics']['end_forgive_rate']="Percent of ended loans that have been forgiven";
$lang['statistics']['end_forgive_rate_tooltip']="The value of all loan amounts forgiven by lenders, divided by the value of all ended loans.  For this calculation, ended loans include all loans whose final repayment due date has passed.";
?>