<?php
$lang['mailtext']['default-subject'] = 'Message from Zidisha';
$lang['mailtext']['ForgotPassowrd-msg'] = 'Dear %name%, <br/><br/>'.
'Your password has been reset to %password%. Please use this to log in once, then change your password under "Edit Profile".<br/><br/>'.
'Thank you again for partnering with us,<br/><br/>'.
'Zidisha Team ';
$lang['mailtext']['ForgotPassowrd-subject'] = 'Message from Zidisha';

//Borrower
$lang['mailtext']['BorrowerReg-msg'] = 'Dear %name%, <br/><br/>Thank you for your application to join Zidisha.<br/><br/>A Zidisha staff member will now review your account, a process that normally takes up to one week.  You will be notified by email when the review is complete.  You may also log in to Zidisha to check the status of your account at any time.<br/><br/>

Regards,<br/><br/>


Zidisha Team';
$lang['mailtext']['BorrowerReg-subject'] = 'Welcome to Zidisha';


//partner
$lang['mailtext']['PartnerReg-msg'] = 'Hi %name%, <br/><br/>'.
'Congratulations! You have successfully created a Zidisha Partner account.<br/><br/>'.
'Once the Zidisha Administrator activates your account, you will be notified by email of your eligibility to activate borrowers. You may also log in to your Zidisha account to check your activation status at any time.<br/><br/>'.
'Thank you,<br/><br/>'.
'Zidisha ';
$lang['mailtext']['PartnerReg-subject'] = 'Welcome to Zidisha';

//lender
$lang['mailtext']['LenderReg-msg'] = 'Dear %name%, <br/><br/>'.
'Thank you for joining the global peer-to-peer microlending movement.<br /><br />We are pioneering the world\'s first online microfinance community to connect lenders to borrowers directly, overcoming previously insurmountable barriers of geography, wealth and circumstance. It\'s an incredibly worthwhile thing to be a part of.<br/><br/>'
.'Go to <a href="%zidisha_link%" target="_blank">Zidisha.org</a> to start making a difference. You can find an entrepreneur to support, invite friends to join, and dialogue with others who share the vision of a world where responsible and motivated people have the opportunity to pursue their goals regardless of their location.<br/><br/>'
.'We look forward to seeing you there.<br/><br/>'
.
'The Zidisha Team ';

$lang['mailtext']['LenderReg-subject'] = 'Welcome to Zidisha';
$lang['mailtext']['RecivedPayment-msg'] = 'Dear %name%, <br/><br/>'.
'%bname% has made a repayment. The share of repaid principal and interest credited to your account is USD %amount%.<br/><br/>'
.'Your lender credit balance is now USD %avail_amount%. '
.'You may use this balance to make a new loan <a href="%lend_link%">here</a>.<br/><br/>'.
'You may view %bname%\'s loan profile page <a href="%link%">here</a>.<br/><br/>'.
'Thank you again for partnering with us,<br/><br/>'.
'The Zidisha Team <br/><br/>'.
'If you no longer wish to receive email notifications when a loan repayment is credited to your account, please log in to your lender account and change your email settings in the Edit Profile page.';

$lang['mailtext']['RecivedPayment-subject'] = 'Zidisha Payment Received';
$lang['mailtext']['RepayFeedback-msg'] = 'Greetings %name%, <br/><br/>'.
'Good news! %bname% has completely repaid the loan that you funded.
<br/><br/>'.
'Please take a moment to share an honest appraisal of your experience <a href=" %link% ">here</a>. Your feedback rating and comment will be recorded on %bname%\'s profile, creating a performance history to facilitate financing of future loans.<br/><br/>'.
'Thanks,<br/><br/>'.
'The Zidisha Team ';

$lang['mailtext']['RepayFeedback-subject'] = 'Zidisha Payment Received';

$lang['mailtext']['AcceptBid-msg'] = "Greetings %name%, <br/><br/>Thank you for your loan to a Zidisha entrepreneur! Your bid to finance USD %amount% of a loan to %bname% at %intr%% annual interest has been accepted. The loan is now fully financed, and will be disbursed to the entrepreneur in local currency. <br/><br/>
Once the loan is disbursed, its final US dollar value and repayment schedule will be posted on %bname%'s loan <a href=' %link% '>profile page</a>. You may view your loan's status and post comments and questions on the loan <a href=' %link% '>profile page</a> at any time.<br/><br/>
Thanks,<br/><br/>
The Zidisha Team";

$lang['mailtext']['AcceptBid-subject'] = 'Zidisha Bid Accepted';
$lang['mailtext']['ActiveBid-msg'] = "Greetings %name%, <br/><br/>Good news! Your loan to %bname% was disbursed on %ddate% in the amount of %amtlocal%. This entrepreneur is now on the way to achieving a brighter financial future, thanks to your support. <br/><br/>
Your loan disbursement is just the beginning. Keep abreast of progress and interact with %bname% via the loan <a href=' %link% '>profile page</a>. We encourage you to use the Comments Forum on this <a href=' %link% '>profile page</a> to post feedback and questions for the borrower throughout the lending period.<br/><br/>
Best wishes,<br/><br/>
The Zidisha Team";

$lang['mailtext']['ActiveBid-subject'] = 'Zidisha Loan Disbursed';
$lang['mailtext']['ActivateBorrower-msg'] = 'Dear %name%, <br/><br/>
Congratulations! Your application to join Zidisha has been approved.<br/><br/>
Zidisha is an internet-based community based on earned trust.  Membership is highly selective, and being accepted into Zidisha is something to take pride in.<br/><br/>
You are now eligible to offer a loan agreement to Zidisha lenders.  To post a loan application on Zidisha, please log in to your member account at <a href="%zidisha_link%" target="_blank">Zidisha.org</a> and follow the instructions.<br/><br/>
IMPORTANT NOTE: If you were invited by another Zidisha member, you may be eligible for a loan size increase bonus.  To receive the bonus, ask the member who invited you to send you an invitation email from the "Invite New Members" page of his or her account before posting your loan application.<br/><br/>
Best wishes,<br/><br/>
Zidisha Team';

$lang['mailtext']['ActivateBorrower-subject'] = 'Zidisha Account Activation';
$lang['mailtext']['ActivatePartner-msg'] = 'Hi %name%, <br/><br/>'.
'Your Zidisha account is now %status%.<br/><br/>'.
'Thank you,<br/><br/>'.
'Zidisha Administrator ';

$lang['mailtext']['ActivatePartner-subject'] = 'Your Zidisha Account';
$lang['mailtext']['DeactivateBorrower-msg'] = 'Hi %name%, <br/><br/>'.
'Your Zidisha account is now %status%.<br/><br/>'.
'Thank you,<br/><br/>'.
'Zidisha Administrator ';

$lang['mailtext']['paywithdraw-msg'] = 'Hello %name%, <br/><br/>'.
'You have successfully withdrawn %Amount% from your Zidisha account. The funds have been deposited in your PayPal account. <br/><br/>'.
'Thank you for your generous support and partnership with us,<br/><br/>'.
'Zidisha Team ';

$lang['mailtext']['paywithdraw-subject'] = 'Message from Zidisha';
$lang['mailtext']['withdraw-msg'] = 'Hi Admin <br/><br/>'.
' There is a withdraw requested of %Amount%<br/>'.
'Thank you,<br/><br/>'.
'Zidisha Administrator ';

$lang['mailtext']['withdraw-subject'] = 'withdraw request';

$lang['mailtext']['comment-msg'] = '%mname% comments on %date%<br/><br/>'.
'%message% <br/><br/>'.
'We welcome your participation. Please click <a href=" %link% ">here</a> to view the comment and post a response.<br/><br/>'.
'If this comment was posted in a local language, please stay tuned! We will send another email notification when a translation is available.<br/><br/>'.
'Best wishes,<br/><br/>'.
'The Zidisha Team <br/> <br/> %images% <br/> <br/>'.
'If you no longer wish to receive email notifications when comments are posted on your loans, please log in to your lender account and change your email settings in the Edit Profile page.';
$lang['mailtext']['comment-subject'] = 'New Message: %bname%';

$lang['mailtext']['comment-msg_b'] = 'Dear %name%,<br/><br/>
You have a new message on your Zidisha loan page.<br/><br/>
%mname% posted on %date%<br/><br/>
%message%<br/><br/>'.
'Please log in to your account at <a href="%zidisha_link%" target="_blank">www.zidisha.org</a> and click "Post a comment" to respond to this message.<br/><br/>'.
'Thank you,<br/><br/>'.
'Zidisha Team <br/> <br/> %images% <br/> <br/>';
$lang['mailtext']['comment-subject_b'] = 'You Received a Message at Zidisha';

$lang['mailtext']['gift_order_subject'] = 'Your Zidisha Gift Card Order Confirmation';
$lang['mailtext']['gift_order_msg_header'] = 'Thanks for your gift card purchase! Please review your order details below : <br/><br/>';

$lang['mailtext']['gift_order_msg_body'] = 'Order Placed on : %date% <br/>'.
'Gift Card Amount : %amount% <br/>'.
'Delivery Method : %delv_method%      %card_link%<br/>'.
'To : %to_name% <br/>'.
'From : %from_name% <br/>'.
'Message : %msg% <br/><br/>';
$lang['mailtext']['gift_order_msg_body_2'] = 'This gift card was emailed to %rec_email% on %date_sent%. <br/><br/>';

$lang['mailtext']['gift_order_msg_footer'] = 'If you have any questions or concerns regarding this purchase, please contact us by replying to this email at service@zidisha.org. <br/><br/>'.
'Best regards,<br/><br/>'.
'The Zidisha Team';

$lang['mailtext']['gift_card_subject'] = 'You received a Zidisha gift card !';

$lang['mailtext']['gift_card_msg_body'] = "If you do not yet have a Zidisha lender account, you may redeem this gift card by entering the redemption code in the <a href='%link_1%' target='_blank'>New Account</a> page at our website.<br/><br/>".
"If you are already registered as a lender, you may enter the redemption code in the <a href='%link_2%' target='_blank'>Add or Withdraw Funds</a> page when logged in to credit the gift card value to your account.<br/><br/>".
"If you have any questions or concerns, please contact us by replying to this email at service@zidisha.org. Thanks and happy lending!<br/><br/>".
"The Zidisha Team<br/>";

$lang['mailtext']['promote_subject'] = "%name% wants you to check out Zidisha!";

$lang['mailtext']['promote_body1'] ="There's only $%stil_need_amt% still needed to complete funding of %borrower_name%'s loan. Help %lender_name% fund this loan today! To register as a lender, go to %lender_reg_link%.<br/><br/>";

$lang['mailtext']['promote_body2'] ="%name% wants you to check out this entrepreneur's loan page. To view it, follow the link %loan_prof_link%. <br/><br/>";

$lang['mailtext']['promote_body3'] ="Zidisha.org is a US-based nonprofit that lets you make microloans for as little as $1 to a small business owner from around the world. What makes Zidisha really unique is that you can communicate directly with the individual you funded via each loan's discussion forum on the Zidisha.org website. 100% of your funds go to the borrower and you receive monthly repayments with interest as the loan is repaid. It’s a great way to fight poverty while getting to know and talk to a real person in a developing country of your choice.<br/><br/>".
"Check out <a href='%zidisha_link%' target='_blank'>www.zidisha.org</a> to learn more.<br/><br/>".
"The Zidisha Team";

$lang['mailtext']['invite_subject'] = "%name% wants you to check out Zidisha!";


$lang['mailtext']['invite_body1'] = "Zidisha.org is a nonprofit that lets you make microloans for as little as $1 to a small business owner from around the world. What makes Zidisha really unique is that you can communicate directly with the individual you funded via each loan's discussion forum on the Zidisha.org website. 100% of your funds go to the borrower and you receive monthly repayments with interest as the loan is repaid. It's a great way to fight poverty while getting to know and talk to a real person in a developing country of your choice.
";

$lang['mailtext']['invite_body'] ="%user_msg%<br/><br/>".
"Zidisha.org is a US-based nonprofit that lets you make microloans for as little as $1 to a small business owner from around the world. What makes Zidisha really unique is that you can communicate directly with the individual you funded via each loan's discussion forum on the Zidisha.org website. 100% of your funds go to the borrower and you receive monthly repayments with interest as the loan is repaid. It's a great way to fight poverty while getting to know and talk to a real person in a developing country of your choice.<br/><br/>".
"Check out <a href='%zidisha_link%' target='_blank'>www.zidisha.org</a> to learn more.<br/><br/>".
"The Zidisha Team";

$lang['mailtext']['reinvite_subject'] = "Reminder- %name% wants you to check out Zidisha!";

$lang['mailtext']['reinvite_body'] ="Zidisha.org is a US-based nonprofit that lets you make microloans for as little as $1 to a small business owner from around the world. What makes Zidisha really unique is that you can communicate directly with the individual you funded via each loan's discussion forum on the Zidisha.org website. 100% of your funds go to the borrower and you receive monthly repayments with interest as the loan is repaid. It's a great way to fight poverty while getting to know and talk to a real person in a developing country of your choice.<br/><br/>".
"Check out <a href='%zidisha_link%' target='_blank'>www.zidisha.org</a> to learn more.<br/><br/>".
"The Zidisha Team";

$lang['mailtext']['withdraw_request_sub'] = "Withdrawal request confirmation";

$lang['mailtext']['withdraw_request_body_us'] = "Dear %name%,<br/><br/>".
"This is to confirm receipt of your request on %date% to withdraw USD %amount% from your Zidisha lender account. We will mail a check for the amount requested to your address:<br/><br/>".
"%address1% %address2%<br/>".
"%city%, %state% %zip% USA<br/><br/>".
"Should you have any questions concerning this transaction, please don't hesitate to contact us at service@zidisha.org.<br/><br/>".
"Best wishes!<br/><br/>".
"Zidisha Team";

$lang['mailtext']['withdraw_request_body_out'] = "Dear %name%,<br/><br/>".
"This is to confirm receipt of your request on %date% to withdraw USD %amount% from your Zidisha lender account. The amount requested will be transferred to your PayPal account at %paypal_email% shortly.<br/><br/>".
"Should you have any questions concerning this transaction, please don't hesitate to contact us at service@zidisha.org.<br/><br/>".
"Best wishes,<br/><br/>".
"The Zidisha Team";

$lang['mailtext']['bid_out_sub'] = "Outbid Notification";

$lang['mailtext']['bid_down_sub'] = "Outbid Notification";

$lang['mailtext']['bid_out_body'] = "Dear %lname%,<br/><br/>".
"This is a notification that your bid to fund USD %bid_amt% of the loan for <a href='%borrower_link%' target='_blank'>%bname%</a> at %bid_interest%% interest has been outbid by another lender who proposed a lower interest rate. The amount outbid of USD %out_bid_amt% has been returned to your lender account, and you may use it to fund another loan or to bid again on this one.<br/><br/>".
"Loan bids may be partially or fully outbid when the total value of lender bids exceeds the amount needed for the loan. In these cases, only the amount originally requested by the borrower is accepted, and bids at the lowest interest rates are retained. You may bid again on <a href='%borrower_link%' target='_blank'>%bname%</a>'s loan by proposing a lower interest rate.<br/><br/>".
"Best wishes,<br/><br/>".
"The Zidisha Team";

$lang['mailtext']['bid_down_body'] = "Dear %lname%,<br/><br/>".
"This is a notification that USD %out_bid_amt% of your bid to fund USD %bid_amt% of the loan for <a href='%borrower_link%' target='_blank'>%bname%</a> at %bid_interest%% interest has been outbid by another lender who proposed a lower interest rate. The remaining value of your bid for this loan is USD %remain_bid_amt%. The amount outbid of USD %out_bid_amt% has been returned to your lender account, and you may use it to fund another loan or to bid again on this one.<br/><br/>".
"Loan bids may be partially or fully outbid when the total value of lender bids exceeds the amount needed for the loan. In these cases, only the amount originally requested by the borrower is accepted, and bids at the lowest interest rates are retained. You may bid again on <a href='%borrower_link%' target='_blank'>%bname%</a>'s loan by proposing a lower interest rate.<br/><br/>".
"Best wishes,<br/><br/>".
"The Zidisha Team";

$lang['mailtext']['lender_donation_sub'] = "Zidisha Donation Receipt";
$lang['mailtext']['lender_donation_body'] = "Zidisha Inc.<br/>".
"21900 Muirfield Circle #302<br/>".
"Sterling, Virginia 20164<br/><br/>".
"Dear %lname%,<br/><br/>".
"Thank you for your donation of $%donation_amt% to Zidisha Inc. on %date%<br/><br/>".

"Zidisha Inc. is a 501(c)(3) charitable organization per the United States Internal Revenue Service, and did not provide any goods or services in exchange for your donation.  Our Employment Identification Number (EIN) is 80-049-4876.<br/><br/>".

"This letter may be used as a receipt for tax purposes.  Should you have any questions or concerns, please do not hesitate to contact us by replying to this email.<br/><br/>".

"Sincerely,<br/><br/>".

"Julia Kurnia<br/><br/>".

"Director, Zidisha Inc.<br/><br/>".

"<a href='%zidisha_link%' target='_blank'>www.zidisha.org</a>";

$lang['mailtext']['lender_upload_amt_sub'] = "Lender credit confirmation";
$lang['mailtext']['lender_upload_amt_body'] = "Dear %lname%,<br/><br/>".
"Thank you for your lender funds upload! The amount received, USD %amount%, has been credited to your Zidisha account and is available for lending at any time.<br/><br/>".
"Should you have any questions concerning this transaction, please don't hesitate to contact us at service@zidisha.org.<br/><br/>".
"Kind regards,<br/><br/>".
"The Zidisha Team";

$lang['mailtext']['borrower_mobile_change_sub'] = "Borrower Telephone Number Change Alert";
$lang['mailtext']['borrower_mobile_change_body'] = "Dear Admin,<br/><br/>".
"Borrower %bname% (%username%) of %country% recently changed his/her Telephone Number. The log of all changes made to the telephone number is following-<br/><br/>".
"%data%<br/><br/>".
"Thank you";

$lang['mailtext']['forgive_lender_sub'] = "Confirmation from Zidisha";
$lang['mailtext']['forgive_lender_body'] = "Dear %lname%,<br/><br/>Thank you for forgiving your share in remaining repayments by <a href='%borrower_link%' target='_blank'>%bname%</a>. The remaining amount owed by %bname% has been reduced by USD %amount%.<br/><br/>".
"Best wishes,<br/><br/>".
"The Zidisha Team";
$lang['mailtext']['forgive_borrower_sub'] = "Message from Zidisha";
$lang['mailtext']['forgive_borrower_body'] = "Dear %bname%,<br/><br/>Good news! One of the lenders who participated in funding your loan has forgiven his or her share of remaining repayments. This reduces the total amount remaining due on your Zidisha loan to %repay_amount%. Your updated repayment schedule is as follows:<br/><br/>".
"%repay_table%<br/><br/>".
"If you should have any questions, please do not hesitate to contact us at service@zidisha.org.<br/><br/>".
"Best wishes,<br/><br/>".
"Zidisha Team";
$lang['mailtext']['borrower_verification_request_sub'] = "Verification request";
$lang['mailtext']['borrower_verification_request__body'] = "Dear %pname%,<br/><br/>Zidisha Inc. has posted a new borrower account for verification at %activation_link%<br/><br/>".
"Thank you,<br/><br/>".
"Zidisha Team";
$lang['mailtext']['reschedule_lender_sub'] = "Message from Zidisha";

$lang['mailtext']['reschedule_amtreduced'] = "Dear %lname%,<br/><br/>%bname% has reduced the current loan repayment installment amounts to USD %newinstallment% per month. The total interest due on %bname%'s loan has been increased, such that lenders will receive the same annual interest to which they had agreed when funding the loan over the longer period the loan is held.You may view the new repayment schedule <a href='%repayschdule_url%#repayschedule' target='_blank' >here</a><br/><br/>

Zidisha borrowers may extend their original repayment periods if circumstances beyond their control prevent them from meeting the expected repayment schedule. %bname% has explained the need for rescheduling as follows:<br/><br/> 
%comment%<br/><br/>
If this was written in a local language, we will email you shortly with an English translation.<br/><br/>
Should you have any questions or comments concerning the loan rescheduling, please do not hesitate to contact us at service@zidisha.org.<br/><br/>
Best wishes,<br/><br/>
The Zidisha Team";

$lang['mailtext']['reschedule_amtincreased'] = 'Dear %lname%,<br/>%bname% has increased the current loan repayment installment amounts to USD %newinstallment% per month. The total interest due on %bname%\'s loan has been reduced, such that lenders will receive the same annual interest to which they had agreed when funding the loan over the shorter period the loan is held.
<br/><br/>

Zidisha borrowers may increase monthly repayment amounts if they find that they are able to repay their loans more quickly than originally anticipated.  %bname%  has explained the reason for this change as follows:<br/><br/>
%comment%<br/><br/>
If this was written in a local language, we will email you shortly with an English translation.<br/><br/>
Should you have any questions or comments concerning the loan rescheduling, please do not hesitate to contact us at service@zidisha.org.<br/><br/>
Best wishes,<br/><br/>
The Zidisha Team';

$lang['mailtext']['reschedule_graceperd'] = 'Dear %lname%,<br/>%bname% has been accorded a grace period of %grace_acorded%, during which no repayments will be due. The total interest due on %bname%\'s loan has been increased such that lenders will receive the same annual interest to which they had agreed when funding the loan, over the additional %grace_acorded% that the loan is held.<br/><br/>
Zidisha borrowers may extend their original repayment periods if circumstances beyond their control prevent them from meeting the expected repayment schedule. %bname% has explained the need for rescheduling as follows:<br/><br/> 
%comment%<br/><br/>
If this was written in a local language, we will email you shortly with an English translation.<br/><br/>
Should you have any questions or comments concerning the loan rescheduling, please do not hesitate to contact us at service@zidisha.org.<br/><br/>
Best wishes,<br/><br/>
The Zidisha Team
';


$lang['mailtext']['reschedule_lender_body'] = "Dear %lname%,<br/><br/>%bname% has agreed to extend repayment of the current loan to %new_repay_period% months. Interest will accrue to lenders over the extended period at the same annual rate as originally accepted by the borrower.<br/><br/>".
"Zidisha borrowers may extend their original repayment periods if circumstances beyond their control prevent them from meeting the expected repayment schedule. %bname% has explained the need for rescheduling as follows:<br/><br/>".
"%comment%<br/><br/>".
"If this was written in a local language, we will email you shortly with an English translation.<br/><br/>".
"Should you have any questions or comments concerning the loan rescheduling, please do not hesitate to contact us at service@zidisha.org.<br/><br/>".
"Best wishes,<br/><br/>".
"The Zidisha Team";
$lang['mailtext']['reschedule_comment_lender_sub'] = "Translation notification";
$lang['mailtext']['reschedule_comment_lender_body'] = "Dear %lname%,<br/><br/>Please find below a translation of %bname%'s explanation of the need to reschedule repayments.<br/><br/>".
"%comment%<br/><br/>".
"Zidisha borrowers may extend their original repayment periods if circumstances beyond their control prevent them from meeting the expected repayment schedule. On %date%, %bname% agreed to extend repayment of the current loan to %new_repay_period% months. Interest will accrue to lenders over the extended period at the same annual rate as originally accepted by the borrower.<br/><br/>".
"Should you have any questions or comments concerning the loan rescheduling, please do not hesitate to contact us at service@zidisha.org.<br/><br/>".
"Best wishes,<br/><br/>".
"The Zidisha Team<br/><br/>
If you no longer wish to receive email notifications when comments are posted on your loans, please log in to your lender account and change your email settings in the Edit Profile page.";
$lang['mailtext']['translate_comment_lender_sub'] = "Translation of New Message: %bname%";
$lang['mailtext']['translate_comment_lender_body'] = "Dear %lname%,<br/><br/>Please find below a translation of the message that was posted on %bname%'s loan page by %sender% on %date%. <br/><br/>".
"%comment%<br/><br/>".
"We welcome your participation. Please click <a href=' %link% '>here</a> to view the comment and post a response.<br/><br/>".
"Best wishes,<br/><br/>".
"The Zidisha Team<br/><br/><br/>
If you no longer wish to receive email notifications when comments are posted on your loans, please log in to your lender account and change your email settings in the Edit Profile page.";
$lang['mailtext']['default_loan_lender_sub'] = "Message from Zidisha";
$lang['mailtext']['default_loan_lender_body'] = "Dear %lname%,<br/><br/>This is a notification that the amount remaining outstanding on %bname%'s loan has been written off. To date, %bname% has repaid %percent_repaid% of the USD %rqst_amt% funded.<br/><br/>".
"Zidisha's loan write-off policy allows for collection efforts to be pursued for six additional months after a loan's last scheduled repayment installment. If no repayments are received during this period, the loan is written off for accounting and reporting purposes. However, collection efforts may still be pursued, and any amounts recovered will be credited to lenders after a loan is written off.<br/><br/>".
"Should you have any questions or comments concerning this loan, please do not hesitate to contact us at service@zidisha.org.<br/><br/>".
"Best regards,<br/><br/>".
"The Zidisha Team";
$lang['mailtext']['new_loan_app_lender_sub'] = "%bname% has posted a new loan application!";
$lang['mailtext']['new_loan_app_lender_body'] = "Dear %lname%,<br/><br/>%bname% has posted a new loan application! This borrower fully repaid the loan you funded on %repay_date%.<br/><br/>".
"You may view and bid on %bname%'s current loan request at %link%<br/><br/>".
"Should you have any questions or comments concerning this loan, please do not hesitate to contact us at service@zidisha.org.<br/><br/>".
"Thanks for your partnership, and best wishes.<br/><br/>".
"The Zidisha Team";
$lang['mailtext']['feedback_reminder_lender_sub'] = "Reminder: Please leave feedback for %bname%";
$lang['mailtext']['feedback_reminder_lender_body'] = "Dear %lname%,<br/><br/>%bname% fully repaid the loan you funded on %repay_date%. Please take a moment to assign a feedback rating to this loan transaction at <a href=' %link% '>%link%</a>.<br/><br/>".
"Your feedback rating will be recorded on %bname%'s Zidisha profile, creating a performance record that will facilitate the financing of future loans.<br/><br/>".
"Should you have any questions, please do not hesitate to contact us at service@zidisha.org.<br/><br/>".
"Thanks for your partnership, and best wishes.<br/><br/>".
"The Zidisha Team";

$lang['mailtext']['donation_information_sub'] = "Some one made donation";
$lang['mailtext']['donation_information_body'] = "Dear Admin,<br/><br/>".
"Some one purchased a gift card(s) and he/she was not logged in also he/she made a donation USD %donation% via paypal.<br/><br/>".
"Please update his/her donation by admin panel.<br/><br/>".
"Thank you";
$lang['mailtext']['borrower_referral_sub'] = "%applicant_name% reference confirmation";
$lang['mailtext']['borrower_referral_body_1'] = "Dear %bname%,<br/><br/>".
"%applicant_name% has applied to Zidisha and cited you as a reference. In accordance with the current terms of the Referral Program, a commission of %amount% will be credited to your account when %applicant_name% has successfully raised a loan through Zidisha.<br/><br/>".
"You may view more information about the Zidisha Referral Program at %referral_link%. Should you have any questions, please contact us at service@zidisha.org.<br/><br/>".
"Thanks,<br/><br/>".
"Zidisha Team";
$lang['mailtext']['borrower_referral_body_2'] = "Dear %bname%,<br/><br/>".
"%applicant_name% has applied to Zidisha and cited you as a reference. In accordance with the current terms of the Referral Program, a commission of %amount% will be credited to your account when %applicant_name% has repaid %repaid_percent%% of the first loan raised through Zidisha.<br/><br/>".
"You may view more information about the Zidisha Referral Program at %referral_link%. Should you have any questions, please contact us at service@zidisha.org.<br/><br/>".
"Thanks,<br/><br/>".
"Zidisha Team";
$lang['mailtext']['borrower_referral_admin_sub'] = "Borrower Referral Notification";
$lang['mailtext']['borrower_referral_admin_body_1'] = "Dear Admin,<br/><br/>".
"No active loan for %bname% so site could not credit the commission. Other details are-<br/><br/>".
"Applicant- %applicant_name%<br/>".
"Commission Amount- %commission%<br/><br/>".
"However Site will try again on each repayment of applicant to credit the commission in %bname%'s account.<br/><br/>".
"Thank you";
$lang['mailtext']['borrower_referral_admin_body_2'] = "Dear Admin,<br/><br/>".
"Borrower referral credited to %bname%'s account and his/her loan was fully repaid. Referral details are- <br/><br/>".
"Applicant- %applicant_name%<br/>".
"Commission Amount- %commission%<br/>".
"Amount credited- %credit_amount%<br/>".
"Balance due- %due_amount%<br/><br/>".
"Thank you";
$lang['mailtext']['loan_disburse_sub'] = "Loan disbursement confirmation";
$lang['mailtext']['loan_disburse_body'] = "Dear %bname% ,<br/><br/>This is to confirm disbursement of your Zidisha loan in the amount of %disb_amt%. If this is your first Zidisha loan, the new client registration fee of %reg_fee_amt% was deducted from your loan disbursement for a net payment of %net_amt%.<br/><br/>".
"To view your repayment schedule please log into your account at <a href='%zidisha_link%' target='_blank'>www.zidisha.org</a> and click on \"Repayment Schedule\"<br/><br/>".
"%repay_ins%<br/><br/>".
"Now, we'd like to ask for your help. Please log in to your account at <a href='%zidisha_link%' target='_blank'>www.zidisha.org</a> and click \"Post a Comment\". Then type a comment to let lenders know exactly what you have been able to purchase with the loan, and how it has helped you. Regular communication with lenders regarding the results of their loan will help establish good relations such that that they will be happy to lend to you again in the future.<br/><br/>".
"Should you have any questions, please do not hesitate to contact us at service@zidisha.org.<br/><br/>".
"We wish you much success in your endeavor.<br/><br/>".
"Zidisha Team";
$lang['mailtext']['email_verification_sub'] = "Please confirm your email address";
$lang['mailtext']['email_verification_body'] = "Thank you for creating a Zidisha account! To confirm your email address, simply click the confirmation link at the bottom of this email.<br/><br/>".
"%verify_link%<br/>
You may also paste the link into the address bar of your internet browser and press Enter or Return to complete the confirmation.";
$lang['mailtext']['share_email_body'] = "%note%<a href='%zidisha_link%' target='_blank'>www.zidisha.org</a><br/><br/>".
"<div style='background-color:#F7F7F7;border: 1px solid #DFDCDC;width:300px'><table><tbody><tr><td>%user_img%</td><td><div style='width:160px;margin-left:5px;text-align:justify'>%loan_use%</div></td></tr></tbody></table></div>";
$lang['mailtext']['loan_forgiveness_subj']='Your loan to %bname%';
$lang['mailtext']['loan_forgiveness_body']='Dear %name%,<br/><br/>
We are writing to inform you of difficulties experienced by %bname%, whose loan you funded on %date%. You may choose to forgive %bname%\'s loan using the button below.<br/><br/>
%msg%<br/><br/>
In exceptional cases, Zidisha offers lenders the option to forgive loans to borrowers who have experienced an unexpected misfortune which affects their ability to repay the loan. In these cases, each lender has the option to forgive his or her share of the loan. Such decisions will remain anonymous and are completely at each lender\'s discretion.<br/><br/>
Should you decide to forgive this loan, you will be declining to receive further repayments from %bname%, and the loan\'s outstanding balance of USD %out_amnt% will be reduced by the amount that had been remaining due to you under the original loan agreement.<br/><br/>
Would you like to forgive your share of this loan?<br/><br/>
<a class=\'btn\' href=\'%link%\' target=\'_blank\'><img alt=\'Forgive.\' src=\'%imgyes%\' ></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<br/><a class=\'btn\' href=\'%link1%\' target=\'_blank\'><img alt=\'Do Not Forgive\' src=\'%imgno%\'></a><br/>
<a href=\'%profile_link%\' target=\'_blank\'>View Loan Profile</a><br/><br/>
Best wishes,<br/><br/>
Zidisha Team';
$lang['mailtext']['DeclineBorrower-subject']='Message from Zidisha';
$lang['mailtext']['DeclineBorrower-msg']='Dear %name%, <br/><br/>'.
'We regret to inform you that your Zidisha account cannot be activated, because we were unable to confirm that your account meets all required criteria for a Zidisha loan.<br/><br/>
Best wishes,<br/><br/>'.
'Zidisha Team';
$lang['mailtext']['LoanFunded-subject']='Loan funding confirmation email';
$lang['mailtext']['LoanFunded-body']='Dear %bname%, <br/><br/>
Congratulations!  Your loan application is fully funded.<br/><br/>
You may accept the loan bids and receive the loan disbursement at any time before your application expires on %expirydate%. <br/><br/>
To accept the bids, please go to your <a href="%link%">loan application page</a> and log in to your member account.Then click on the "Accept Bids" button in your loan profile page.<br/><br/>
Please do not hesitate to contact us at service@zidisha.org if you desire assistance.
<br/><br/>
Best wishes,<br/><br/>
Zidisha Team';
$lang['mailtext']['LoanPosted-subject']='Your Loan Application Has Been Posted';
$lang['mailtext']['LoanPosted-body']='Dear %bname%,<br/><br/> 
Congratulations!  Your loan application has been posted for funding.  Click <a href=" %link% ">here</a> to view your loan application page.<br/><br/>
Please note that your application will be posted for a maximum of %deadline%  days, or until it is fully funded and you choose to accept the bids raised. You may edit your loan application page at any time using the <a href="%editlink%">Edit Profile</a> and <a href="%loanappliclink%">Loan Application</a>  pages.<br/><br/>
Please do not hesitate to contact us at service@zidisha.org if you desire assistance.<br/><br/>
Best of luck in your endeavor,<br/><br/>
Zidisha Team';
$lang['mailtext']['LoanExpiry-subject']='Your Loan Application Will Expire Soon';
$lang['mailtext']['LoanExpiry-body']='Dear %bname%,<br/><br/>
This is a notification that your Zidisha loan application is due to expire on %expirydate%.  If your proposed loan amount is not fully funded by that date, it will expire and all bids raised will be returned to lenders.<br/><br/>
Zidisha provides a platform whereby our members can raise loans by proposing mutually beneficial terms to lenders.  Lenders choose from many competing applications, and your loan will only be funded if it succeeds in appealing to lenders.  Here are some tips members have used to make their loan applications more attractive to lenders:<br/><br/>
<ol>
	<li>
		Ensure that your photo is of good quality. Most people prefer to have lending relationships with people whose photos are clear, close up, and smiling. Photos that are not well lit, too far away or not smiling are less attractive to lenders. You may change your photo by uploading a new one in the <a href="%editlink%">Edit Profile</a> page.	
	</li><br/> 
	<li>
		Consider your offered interest rate. Most lenders try to ensure their loan funds earn enough interest to compensate for their money transfer costs and inflation, so that their value is preserved for future borrowers who will receive the funds that you repay.  You may increase your offered interest in the <a href="%loanappliclink%">Loan Application</a> page after your loan application is posted for funding.
	</li><br/> 
	<li>
		Make sure that you include enough detail in your "Use of Loan" description.  Lenders want to know exactly what items will be bought with the loan, and exactly how this will help your business grow.  You may edit this field in the <a href="%loanappliclink%">Loan Application</a> page as well.
	</li>
</ol>
<br/><br/>
Best wishes,<br/><br/>
Zidisha Team';

$lang['mailtext']['LoanExpired-subject']='Your Loan Application Has Expired';
$lang['mailtext']['LoanExpired-body']='Dear %bname%,<br/><br/>
This is a notification that your Zidisha loan application has expired without being fully funded, and the loan bids raised have been returned to lenders.<br/><br/>
You may post a new loan application at any time using the <a href="%loanapplicLink%">Loan Application</a> page of your member account.<br/><br/>
Should you have any questions or difficulties, please do not hesitate to contact us at service@zidisha.org.<br/><br/>
Best wishes,<br/><br/>
Zidisha Team';
$lang['mailtext']['AccountExpired-subject']='Zidisha account expiration notification';
$lang['mailtext']['AccountExpired-body']='Dear %lender%,<br/><br/>
We noticed that you have not logged into your account at Zidisha.org for over one year.  We\'re sorry that lending with Zidisha did not work out for you, and would sincerely welcome any feedback you would care to share regarding why you have not come back.<br/><br/>
Should you desire to maintain access to your lender credit balance, simply log in to your member account at <a href="%site_link%">Zidisha.org</a> at any time within the next month. If you do not wish to keep your account open, you need not do anything: we will close your account and convert any remaining lender credit to a donation on %expired_date%.<br/><br/>
Thanks so much for having participated in our lending community, and for helping to extend life-changing opportunities to some of the world\'s most marginalized entrepreneurs.<br/><br/>
Best regards,<br/><br/>
Zidisha Team';
$lang['mailtext']['invite_link'] = "Check out <a href='%zidisha_link%' target='_blank'>www.zidisha.org</a> to learn more.";

$lang['mailtext']['grpcomment-subject'] = 'New Message: %gname% Lending Group';
$lang['mailtext']['grpcomment-msg'] = '%cmntbyname% comments on %date%<br/><br/>'.
'%message% <br/><br/>'.
'We welcome your participation. Please click <a href=" %link% ">here</a> to view the comment and post a response.<br/><br/>'.
'Thank you again for partnering with us!<br/><br/>'.
'Best wishes,<br/><br/>'.
'Zidisha Team <br/> <br/> %images% <br/> <br/>'.
'If you would no longer like to receive email notifications when new messages are posted at the %gname% Lending Group\'s Message Board, please change your notification preferences in the <a href="%link%">%gname% Lending Group profile page</a>.';
$lang['mailtext']['breview-msg'] = 'Dear %bname%,

Thank you for your application to join Zidisha.  We will need the following information in order to complete your application:

%reviewmsgbody%<br/><br/>
Please add this information directly to your profile by logging into your member account and using the "Edit Profile" page, then resubmit the profile to Zidisha.

Once again thank you for your application to join Zidisha.

Best regards,';
$lang['mailtext']['if_photo_missing-msg'] = 'A photo that shows your face clearly.

';
$lang['mailtext']['if_addrs_missing-msg'] = 'A precise residential address, including house number or plot number and detailed directions to your home.

';
$lang['mailtext']['if_tel_num_missing-msg'] = '';

$lang['mailtext']['if_nat_id_missing-msg'] = 'A legible copy of your national identity card.

';
$lang['mailtext']['if_rec_form_missing-msg'] = 'Recommendation Form downloaded from our website that has been signed by the leader of a religious institution, a school or a social organization in your community.

';
$lang['mailtext']['if_pending_mediation-msg']='One or more members recommended by the Community Leader who signed your Recommendation Form are in arrears.  The arrears must be resolved before we can accept further recommendations from this leader.

';
$lang['mailtext']['if_contr_form_missing-msg'] = 'The names and telephone numbers of three family members and three neighbors or business associates.

';
$lang['mailtext']['if_is_desc_clear-msg']='Please complete the "About Me" and "About My Business" sections with thorough descriptions in your own words.

';
$lang['mailtext']['borrowerEndorser-subject']='Endorsement Request';
$lang['mailtext']['BorrowerEndorser-msg']= 'Dear %name%,<br/><br/>

%bname% has requested your endorsement for an application to join Zidisha, a web-based lending platform for affordable personal and business growth loans.<br/><br/>

Your endorsement serves only to confirm the identity and trustworthy reputation of %bname%.  You are not acting as a guarantor for any loans that %bname% may receive from Zidisha.<br/><br/>

 To endorse %bname%\'s application, please click on this link or copy and paste it into your browser:<br/><br/>
%reg_link%<br/><br/> 
 Best regards,
 <br/><br/> 
 Zidisha Team';

$lang['mailtext']['share_join_email_body']="%note%<br/><a href='%zidisha_link%'>www.zidisha.org</a><br/>";
$lang['mailtext']['binvite_link'] = "Go to <a href='https://www.zidisha.org/microfinance/borrow.html'>https://www.zidisha.org/microfinance/borrow.html</a> to accept this invite or to learn more.";
$lang['mailtext']['breminder_advance'] = "Dear %bname%,<br/><br/>
This is a courtesy reminder that your monthly loan repayment installment will be due on %duedate%.<br/><br/>
You currently have an advance payment credit of %currency% %paidamt% .This will be credited toward your balance due on %duedate%, for a net amount of %currency% %netdueamt% due on %duedate%.<br/><br/>
%repay_inst%<br/><br/>
Please ensure that the due payment is made promptly, and contact us in case of difficulty.<br/><br/>
Thank you,<br/><br/>
Zidisha Team";
$lang['mailtext']['breminder_pastdue']='Dear %bname%,<br/><br/>
This is a courtesy reminder that your monthly loan repayment installment will be due on %next_duedate%.<br/><br/>
You currently have a past due balance of %currency% %past_dueamt%. This will be added to your balance due on %next_duedate%, for a total amount of %currency% %netdueamt% due on %next_duedate%.<br/>
%repay_inst%<br/><br/>
Please ensure that the due payment is made promptly, and contact us in case of difficulty.<br/><br/>
Thank you,<br/><br/>
Zidisha Team';
$lang['mailtext']['breminder']='Dear %bname%,<br/><br/>
This is a courtesy reminder that your monthly loan repayment installment will be due on %duedate% in the amount of %currency% %dueamt%.<br/>
%repay_inst%<br/><br/>
Please ensure that the due payment is made promptly, and contact us in case of difficulty.<br/><br/>
Thank you,<br/><br/>
Zidisha Team';
$lang['mailtext']['breminder_again']='Dear %bname%,<br/><br/>
This is a notification of your Zidisha loan repayment balance of %currency% %netdueamt%, which has been past due since %duedate%, has not been received to date.<br/><br/>
%repay_inst%<br/><br/>
Please deposit the past due amount immediately.  If you are experiencing difficulty or believe you have received this message in error, please contact us at service@zidisha.org.<br/><br/>
Thank you,<br/><br/> 
Zidisha Team';
$lang['mailtext']['breminder_sms']='This is a courtesy reminder that your monthly loan repayment installment of %currency% %dueamt% will be due on %duedate%.';
$lang['mailtext']['loanarrear_reminder_first']="Dear %bname%,<br/><br/>
This is notification that we did not receive your loan repayment installment of %currency% %due_amt%, which was due on %duedate%.<br/><br/>
Please make the past due payment immediately following the instructions below.<br/><br/>
%repay_inst%<br/><br/>
Thank you,<br/><br/>
Zidisha team<br/><br/>";
$lang['mailtext']['loanarrear_remindersms']="Dear %bname%, we did not receive your loan repayment of %currency% %due_amt%, which was due on %duedate%. Please make this payment immediately. Thank you, Zidisha team";
$lang['mailtext']['loanarrear_reminder_final']="Dear %bname%,<br/><br/>
This is a final notice that we did not receive your loan repayment installment of %currency% %due_amt%, which was due on %duedate%.<br/><br/>
Please make the past due payment immediately following the instructions below. If you are unable to make the past due payment immediately, you may use the 'Reschedule Loan' page of your member account at Zidisha.org to propose an alternative repayment schedule to lenders.<br/><br/>
If you do not reschedule and we do not receive the past due amount, then we will contact and request mediation from members of your community, including but not limited to the individuals whose contacts you provided in support of your loan application:<br/>
%contacts%<br/><br/>%repay_inst%<br/><br/>
Thank you,<br/><br/>
Zidisha team
";
$lang['mailtext']['loanarrear_remindersms_final']="Dear %bname%, this is a final notice of your outstanding loan repayment of %currency% %due_amt%, which was due on %duedate%.

Please send make this payment immediately following the bank deposit instructions in your Zidisha.org member account. If you are unable to make the past due payment immediately, you may use the 'Reschedule Loan' page of your member account at Zidisha.org to propose an alternative repayment schedule to lenders.

If you do not reschedule and we do not receive the past due amount, then we will contact and request mediation from members of your community, including but not limited to the individuals whose contacts you provided in support of your loan application:
%contacts%

Thank you, Zidisha Team";
$lang['mailtext']['loanarrear_reminder_monthly']="Dear %bname%,<br/><br/>
This is notification that, in accordance with the terms of the Loan Contract, we have requested mediation from one or more of the following individuals regarding your past due loan balance.<br/>
%contacts%<br/><br/>
Please make the past due payment immediately following the instructions below. If you are unable to make the past due payment immediately, you may use the 'Reschedule Loan' page of your member account at Zidisha.org to propose an alternative repayment schedule to lenders.<br/><br/>
If you do not reschedule and we do not receive the past due amount, then we will continue to contact and request mediation from members of your community.<br/><br/>
%repay_inst%<br/><br/>
Thank you,<br/><br/>
Zidisha Team";
$lang['mailtext']['loanarrear_remindersms_monthly']="Dear %bname%,

This is notification that, in accordance with the terms of the Loan Contract, we have requested mediation from one or more of the following individuals regarding your past due loan balance.
%contacts%

Please send make this payment immediately following the bank deposit instructions in your Zidisha.org member account. If you are unable to make the past due payment immediately, you may use the 'Reschedule Loan' page of your member account at Zidisha.org to propose an alternative repayment schedule to lenders.

If you do not reschedule and we do not receive the past due amount, then we will continue to contact and request mediation from members of your community. Thank you, Zidisha Team";
$lang['mailtext']['loanarrear_mediation_mail']="Dear %uname%,<br/><br/>
You had invited or endorsed %bname%'s application to join our organization, Zidisha Microfinance. %bname% is now %duedays% days in arrears on the loan taken from Zidisha.<br/><br/>
Can you please contact %bname% at %bnumber% and help us find out why we have not received the past due loan repayments?<br/><br/>
You may contact us by replying to this email. Thanks very much for your help.<br/><br/>
Zidisha Team";
$lang['mailtext']['loanarrear_mediation_sub']='Mediation request from Zidisha';
$lang['mailtext']['loanarrear_mediation_sms']="Dear %uname%, %bname% provided your contacts in support of an application to join our organization, Zidisha Microfinance.  %bname% is now %duedays% days in arrears on the loan taken from Zidisha. Can you please contact %bname% at %bnumber% and help us find out why we have not received the past due loan repayments? Please reply to this number by SMS text. Thank you, Zidisha Team";
$lang['mailtext']['loanarrear_reminder_monthly_sub']='Past Due Loan Mediation Requested';
$lang['mailtext']['loanarrear_reminder_final_sub']='Past Due Loan Final Notice';
$lang['mailtext']['loanarrear_reminder_first_sub']='Past Due Loan Notification';
$lang['mailtext']['breminder_again_sub']='Reminder from Zidisha';



//SMS confirmation sent to member who invited new applicant 

$lang['mailtext']['invite_alert']="Dear %uname%, your Zidisha account was used to issue an invite bonus to %bname% of tel. %bnumber%. The repayment performance of %bname% will now affect your own credit limit. If you did not authorize this invite, please inform us by SMS reply to this number. Thank you.";


//confirmation sent to contacts of new applicants 

$lang['mailtext']['contact_confirmation_sms']="Dear %uname%, %bname% of tel. %bnumber% has shared your contacts in an application to join the Zidisha.org online lending community. We would like to confirm with you that %bname% can be trusted to repay loans. If you do not know or do not recommend %bname%, please inform us by SMS reply to this number. Thank you.";



//email and SMS sent out to borrowers when payment is received

$lang['mailtext']['payment_receipt'] = "Dear %bname%,<br/><br/>
Thank you for your loan repayment of %currency% %bpaidamt%. This amount has been credited to your Zidisha account balance.<br/><br/>
Best wishes,<br/><br/>
Zidisha Team";

$lang['mailtext']['payment_receipt_subject'] = "Zidisha Payment Received";

$lang['mailtext']['payment_receipt_sms'] = "Your payment of %currency% %bpaidamt% has been credited to your Zidisha account.";


//sent when borrower is eligible to invite others

$lang['mailtext']['eligible_invite']="Dear %bname%,<br/><br/>
Congratulations! Your on-time repayment rate is high enough to qualify for the Zidisha invite program. You may give and receive credit limit bonuses for each new member you invite to join Zidisha as long as you remain eligible.<br/><br/>
To learn more, go to <a href='%zidisha_link%' target='_blank'>www.zidisha.org</a> and log into your member account.<br/><br/>
Best wishes,<br/><br/>
Zidisha Team";

$lang['mailtext']['eligible_invite_subject'] = "Zidisha Invite Bonuses";

$lang['mailtext']['eligible_invite_sms']="Congratulations! You now qualify for the Zidisha invite program. To participate, login to your account at Zidisha.org and follow the invite instructions.";


?>