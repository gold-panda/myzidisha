<?php
    //API Key - see http://admin.mailchimp.com/account/api
    $apikey = MAIL_CHIMP_API_KEY;
    
    // A List Id to run examples against. use lists() to view all
    // Also, login to MC account, go to List, then List Tools, and look for the List ID entry
    $listId = MAIL_CHIMP_LIST_ID;
    
    // A Campaign Id to run examples against. use campaigns() to view all
    $campaignId = 'YOUR MAILCHIMP CAMPAIGN ID - see campaigns() method';

    //some email addresses used in the examples:
    $my_email = MAIL_CHIMP_EMAIL;
    
    //just used in xml-rpc examples
    $apiUrl = 'http://api.mailchimp.com/1.3/';
    
?>
