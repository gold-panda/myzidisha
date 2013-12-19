<?php
$page=0;
if(isset($_GET['p'])) {
	$page=$_GET['p'];
}
$a="";
if(isset($_GET['a'])) {
	$a=$_GET['a'];
}
$ref="";
if(isset($_GET['ref'])) {
	$ref=$_GET['ref'];
}
if($page==7){
	echo"
	<div class='instruction_space'>
	        <div class='instruction_title'>Pending Activation:</div>
			<div class='instruction_text'>
				This page lists all borrower accounts that have been created but not yet activated by a partner or staff. To activate or decline an account, click \"Enter Report\".  
			</div>
	</div>";
}else if($page==8){
echo"
	<div class='instruction_space'>
	        <div class='instruction_title'>Activated Borrowers:</div>
			<div class='instruction_text'> 
				This page lists all borrower accounts that have been activated by a partner or admin.  Click the \"Activated By\" link to view and edit partner activation data and comments.
			</div>
	</div>
			";
}
else if($page=='11'&&$a=='1'){
	echo'
	<div class="instruction_space">
	        <div class="instruction_title">Enter Repayments:</div>
	          <div class="instruction_text">To post repayments, click on the link Enter Repayment and enter on that page the amount received and date paid.  To write off a loan, click "Write Off" and the loan will be displayed as having been written off on the website.  Click "Undo Writeoff" to record payments received after a loan is written off.  To deactivate a borrower account such that he or she can no longer post loan applications, click "Deactivate."</div>
	</div>';
}
else if($page=='11'&&$a=='12'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Manage Loans:</div> 
		<div class="instruction_text">
			To deactivate a fundraising loan application, click the green check mark next to the name of the borrower.
		</div>';
}
else if($page=='49'){
	echo"
<div class='instruction_space'>
	<div class='instruction_title'>Borrower Referrals: </div>
	<div class='instruction_text'>
		The borrower referral program compensates active Zidisha clients for referring new clients to Zidisha.  To earn a commission, the referring client must have the new applicant enter the referring client's Zidisha username in the registration form when creating an account.  To initiate or edit a borrower referral commission program in a given country, select the desired country, enter the local currency commission amount in local currency, and the percentage of the new client's loan that must be repaid in order for the referring client to earn a commission.  (If 0% is entered, the referring client will earn the commission as soon as the loan application is funded and bids accepted by the new client.  If 50%, the referring client will earn the commission when the new client's loan is 50% repaid.)  Commissions are paid in the form of automated credits to the referring client's outstanding loan balance, except in cases where the commission due exceeds the client's outstanding loan balance - in which case an automated email notification is sent to admin with the amount due to the borrower, so that admin can make the payment in cash per the terms of the referral program.
	</div>
</div>";
}
else if($page=='45'){
	echo"
	<div class='instruction_space'>
		<div class='instruction_title'>Rescheduled Loans:</div>
		<div class='instruction_text'>
			These are loans that have been rescheduled by borrowers.  Borrowers pay additional interest and transaction fees, at the same annualized rates as the original loan, over the extended repayment period.  Admin may edit the maximum number of times borrowers can reschedule their loans in the \"Other Settings\" page.
		</div>
	</div>";
}
else if($page=='11'&&$a=='13'){
	echo"
<div class='instruction_space'>
	<div class='instruction_title'>Repayment Instructions: </div>
	<div class='instruction_text'>
		This page allows entry and editing of customized repayment instructions for each country, which are included in disbursement confirmation emails sent to borrowers and displayed in borrowers' accounts.
	</div>
</div>";
}
else if($page=='84'){
	echo"
<div class='instruction_space'>
	<div class='instruction_title'>Volunteer Mentors: </div>
	<div class='instruction_text'>
		This page allows you to grant Volunteer Mentor access to selected members who have agreed to assist other members in their country as Volunteer Mentors. Volunteer Mentor access will allow them to view all the information in the Active Borrowers and Repayment Report pages of the admin interface for the list of members that has been assigned to them, and to add and delete content in the 'Notes' column of these pages.
	</div>
</div>";
}

/**********Managae Lenders Section starts**********/
else if($page=='11'&&$a=='3'){
	echo"
	<div class='instruction_space'>
		<div class='instruction_title'>View Lenders:</div>
		<div class='instruction_text'>
		This page lists all lender accounts that have been created.  Admin may deactivate accounts, or delete duplicate or test accounts, via the links on this page.
		</div>
	</div>";
}
else if($page=='60'){
	echo"
	<div class='instruction_space'>
		<div class='instruction_title'>Enter Lender Payments: </div>This page allows manual entry of lender fund upload and donation payments received by check or bank wire.  (Payments made through the website's Lending Cart are automatically credited to lender accounts and should not be entered here.)
		</div>
	</div>";
}
else if($page=='17'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Lender Withdrawal Requests:  </div>
			This page lists pending lender withdrawal requests.  Lender withdrawals are not automated; withdrawal requests listed here should be paid manually.  Use the drop-down menu to view withdrawal requests in the US (which are paid by check) and outside the US (which are paid by PayPal).  Once a pending withdrawal has been paid, click the link "Pay" and the site will record it as "Accepted and Paid".
		</div>
	</div>';
}
else if($page=='63'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Notification Requests:</div> 
			Whenever all loan applications on the site are fully funded, the "Lend" page will display a form where lenders who wish to be informed when loans are again available for funding can enter their email addresses.  Those email addresses will be displayed on this page.  Admin can manually send a notification to these addresses, then record the notification in the "Email Sent" column.
		</div>
	</div>';
}
else if($page=='29'){
	echo'
<div class="instruction_space">
	<div class="instruction_title">Manage Gift Cards:</div>
		This page lists the status of all gift cards that have been purchased on the site.  The original card may be viewed by clicking on the redemption code.  Cards sent by email and not received may be resent to the original recipient using the "Resend Card" link.  Per the gift card terms and conditions, unredeemed cards convert to donations after one year.  Admin converts the gift cards to donations manually using this page.
	</div>
</div>';
}
else if($page=='11'&&$a=='2'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">View Partners:</div>
		<div class="instruction_text">
			This page lists all partner accounts that have been created.  Admin may activate, deactivate and delete partner accounts here.
		</div>
	</div>';
}
else if($page=='20'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Send Emails:  </div>
		<div class="instruction_text">
			This page allows admin to send emails from <a href="mailto:noreply@zidisha.org">noreply@zidisha.org </a> to all lenders, all borrowers or all Zidisha members.
	</div>
</div>';
}
else if($page=='39'){
	echo'
<div class="instruction_space">
		<div class="instruction_title">Change Password:  </div>
			<div class="instruction_text">
				This page allows admin to reset passwords for users by selecting the username from the drop-down	menu.  Usernames may be looked up using the Find Borrower and Find Lender pages.
		</div>
</div>';
}
/************Manage Lenders Section Ends********************/

/************Translation Section Start****************************/
else if($page=='25'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Activate Volunteers: </div> 
			<div class="instruction_text">
				To assign volunteer access to a lender account, click the red X next to the lender account to change it to a checkmark.  To remove volunteer access from the account, click the green plus sign to change it to an X.		</div>
	</div>';
}
else if($page=='32'&&$ref=='2'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Upload Pages for Translation:  </div>
			<div class="instruction_text">
				To translate pages of the website, first upload the most recent version of desired page from the website database here.
		</div>
	</div>';
}
else if($page=='32'&&$ref=='1'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Translate Labels:</div>
			<div class="instruction_text">
				After the desired page has been uploaded, items on the page may be translated here.  After the translated labels are saved, admin must download them using the "Download Translated Labels" page before they will display on the website.
		</div>
	</div>';
}
else if($page=='32'&&$ref=='3'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Download Translated Labels:</div>
			<div class="instruction_text">
				Once a page of the website has been translated, download the translation to the website here.
		</div>
	</div>';
}
else if($page=='35'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Activate Languages: </div>
		<div class="instruction_text">
				Use this page to activate a new language on the site, after a translation has been created using the "Upload Pages for Translation", "Translate Labels" and "Download Translated Labels" pages.<br/>Activating the language will make the translation accessible to the public from the drop-down language menu.
		</div>
	</div>';
}
/************Translation Section Ends****************************/
/************Website setting Starts****************************/
else if($page=='11'&&$a=='11'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Activate Currency: </div>
		<div class="instruction_text">
			Use this page to activate or deactivate borrowers\' currencies.
		</div>
	</div>';
}
else if($page=='11'&&$a=='4'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Exchange Rate:</div>
		<div class="instruction_text">
			Use this page to update exchange rates for borrowers\' currencies.  Exchange rates entered here are applied to all currency conversions made on the website.
			</div>
	</div>';
}
/************Website setting Ends****************************/
/************Extra reports Section Starts****************************/
else if($page=='22'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Transaction History:</div> 
		<div class="instruction_text">
			This report lists all transactions processed through the website over a selected time period, in both USD and local currency.
		</div>
	</div>';
}
else if($page=='31'){
	echo'
	<div class="instruction_space">
		<div class="instruction_title">Repayment Report: </div>
			<div class="instruction_text">
				This report lists all repayment amounts currently past due over a threshold amount set by the website admin.  The amount due is the total amount, principal plus interest, that a client should have repaid as of the report date, minus amounts actually paid as of that date.  Use this page to track the status of loans in arrears, and assign them to Volunteer Mentors or staff.
		</div>
	</div>';
}
else if($page=='23'){
	echo'
<div class="instruction_space">
	<div class="instruction_title">Portfolio Report: </div><br/>
	<div class="instruction_text">
		This report displays the current outstanding principal, Portfolio At Risk, Loan Loss Reserve and Loan Loss Reserve Ratio in each country.<br/>

		Outstanding principal is the total amount of principal that has been disbursed to clients and not yet repaid.  It does not include accrued interest, and does include amounts that are outstanding with clients but not yet due to be repaid.  It is not the repayment amount that we are currently supposed to be collecting from clients.  Rather, it allows our directors and evaluators to know how much of our loan capital is outstanding with clients as loans that will be repaid.<br/>
		
		The Portfolio At Risk (PAR) is the portion of the outstanding principal that corresponds to overdue loans.  For example, if an institution has a total principal outstanding of $10,000, and two of the loans fall behind in their repayments each with $500 in principal still outstanding, then the total PAR would be $1,000.  PAR is further divided into categories based on how late the loan is: in arrears for 1-30 days, 31-60 days, etc.<br/>

		The Loan Loss Reserve (LLR) is a measurement that allows managers and evaluators to judge the quality of the loan portfolio, in terms of how likely it is that the outstanding principal will be repaid.  The likelihood of a loan being repaid is considered to diminish the longer the loan has been in arrears, so the length of time in arrears is used to estimate the risk that the outstanding principal will be lost.  A risk estimate of 10% of outstanding principal on loans 31-60 days late, 25% of outstanding principal on loans 61-90 days late, 50% of outstanding principal on loans 91-180 days late, and 100% of outstanding principal on loans above 180 days late means we estimate that 10% of principal held by clients who have been in arrears for 31-90 days will ultimately be lost, 25% of principal held by clients who have been in arrears for 61-90 days will be lost, etc.  Loans that have been in arrears over an established number of days (180 in Zidisha\'s case) are written off in the institution’s accounts and financial reports, though recovery efforts should still continue.  The total Loan Loss Reserve is the PAR measurement above, weighted by the aforementioned risk measurements.  For example, if the PAR in the example above consists of oneloan 40 days late and one loan 100 days late, and the institution is using the aforementioned risk measurements, then the Loan Loss Reserve = ($500 * 10%) + ($500 * 50%) = $50 + $250 = $300.<br/>

		The Loan Loss Reserve ratio is the LLR divided by the total outstanding principal, and should be a conservative estimate for the percentage of outstanding principal that will likely be lost to defaulting clients.  Traditional microfinance institutions use it to set appropriate interest rates, since the amount of principal lost must be compensated by interest income if the value of the loan fund is to be preserved.  It is also used to compare the quality of loan portfolios between various institutions, and that of the same institution as it evolves over time.  In the above example, the loan loss reserve ratio is $300 / $10 000 = .03 or 3%.  This institution would need to incorporate an additional 3% into their annual interest rate in order to maintain the value of their loan fund against expected defaults.
	</div>
</div>
';
}
?>