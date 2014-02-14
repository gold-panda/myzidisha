<?php

require_once ("library/init.php");
require_once 'extlibs/vendor/autoload.php';
require_once 'extlibs/sendwithus_php-master/lib/API.php';
use sendwithus\API;

function clearPost($post_val) // remove email header injects
{
  $injection_strings = array(
  "'apparently-to' i",
  "'bcc:' i",
  "'cc:' i",
  "'to:' i",
  "'boundary=' i",
  "'charset:' i",
  "'content-disposition' i",
  "'content-type' i",
  "'content-transfer-encoding' i",
  "'errors-to' i",
  "'in-reply-to' i",
  "'message-id' i",
  "'mime-version' i",
  "'multipart/mixed' i",
  "'multipart/alternative' i",
  "'multipart/related' i",
  "'reply-to:' i",
  "'x-mailer' i",
  "'x-sender' i",
  "'x-uidl' i"
  );

  $replace_strings = array(
  "apparently_to",
  "bcc_:",
  "cc_:",
  "to_:",
  "boundary_=",
  "charset_:",
  "content_disposition",
  "content_type",
  "content_transfer_encoding",
  "errors_to",
  "in_reply_to",
  "message_id",
  "mime_version",
  "multipart_mixed",
  "multipart_alternative",
  "multipart_related",
  "reply_to:",
  "x_mailer",
  "x_sender",
  "x_uidl"
  );


  $post_val = preg_replace($injection_strings, $replace_strings, $post_val);


  return $post_val;
}


function forReadFile($templet) {
// For PHP 5 and up
//$handle = fopen("http://www.example.com/", "rb");
$handle = fopen($templet, "rb");
$contents = stream_get_contents($handle);
fclose($handle);
//echo $contents;
return $contents ;

}


function mailSender ( $hdr_from, $hdr_to, $email, $subject, $header, $body, $attachment='',$templet = 0,$html=0, $tag=0, $info=0 ,$replyTo=null) {
  global $database,$session;
  Logger("ZDISHAEMAILSENTTEST");

  $body_original = $body;
  $body2 = $info['emailmssg2'];
  $body3 = $info['emailmssg3'];

/*
This is a wrapper function for sending emails
  $hdr_from  - THe from address to be kept in the header
  $hdr_to    - The to name and address to be kept in the Header
  $email     - Email address to which the mail to be sent
  $subject   - Subject of the email
  $body      - The body of the email
  $attachment - Mail Attachment
*/
  if(!defined('ECHO_EMAILS')) {
    define('ECHO_EMAILS', false);
  }
  if(!defined('HTTP_METHOD')) {
    define('HTTP_METHOD', 'http://');
  }
  if(!defined('DOC_ROOT')) {
    define('DOC_ROOT','/i/');
  }
  if(!defined('MAIL_TYPE')) {
    define('MAIL_TYPE', 'mail');
  }
  $encodeArray=array('en'=>'UTF-8', 'fr'=>'iso-8859-1');
  /* Construct the header portion */

  /* clear html injects Begin */



  if(!empty($templet)) {
    $templet = forReadFile($templet);
  } else {
    $templet = forReadFile("editables/email/simplemail.html");
  }
  if($html==2)
  {
    $templet = str_replace('%user_msg%',$info['user_msg'],$templet);
    $templet = str_replace('%image_link%',$info['image_link'],$templet);
    $templet = str_replace('%site_link%',$info['site_link'],$templet);
    $templet = str_replace('%image_src%',$info['image_src'],$templet);
    $templet = str_replace('%lend_image_src%',$info['lend_image_src'],$templet);
    $templet = str_replace('%borrower_link%',$info['borrower_link'],$templet);
    $templet = str_replace('%borrower_name%',$info['borrower_name'],$templet);
    $templet = str_replace('%fbrating%',$info['fbrating'],$templet);
    $templet = str_replace('%fbrating_count%',$info['fbrating_count'],$templet);
    $templet = str_replace('%fbrating_link%',$info['fbrating_link'],$templet);
    $templet = str_replace('%location%',$info['location'],$templet);
    $templet = str_replace('%loan_use%',$info['loan_use'],$templet);
    $templet = str_replace('%lend_link%',$info['lend_link'],$templet);
    $templet = str_replace('%amount_req%',$info['amount_req'],$templet);
    $templet = str_replace('%interest%',$info['interest'],$templet);
    $templet = str_replace('%statusbar%',$info['statusbar'],$templet);
    $templet = str_replace('%content_mail%',$body,$templet);

  }
  else if($html==3)
  {
    error_log('HTML: ' . $html);
    $templet = str_replace('%header%',$header,$templet);
    $templet = str_replace('%content_mail%',$body,$templet);

    if (!empty($info['image_src'])){

      $templet = str_replace('%image_src%', '<img class="" id="mainImage" src="'.$info['image_src'].'" style="width:100%; cursor:auto" width="100%">', $templet);

    }else{

      $templet = str_replace('%image_src%','',$templet);

    }

    if (!empty($info['link']) && !empty($info['anchor'])){

      $templet = str_replace('%linked_text%', "<a href='".$info['link']."'>".$info['anchor']."</a>", $templet);

    }else{

      $templet = str_replace('%linked_text%','',$templet);

    }

    if (!empty($info['footer'])){

      $footer = $info['footer'];

    }else{

      $footer = "View our latest loan projects here!";

    }

    if (!empty($info['button_url'])){

      $button_url = $info['button_url'];

    }else{

      $button_url = "https://www.zidisha.org/microfinance/lend.html";

    }

    if (!empty($info['button_text'])){

      $button_text = $info['button_text'];

    }else{

      $button_text = "View Loans";

    }

    if (empty($tag)){
      $tag = ACCOUNT_NOTIFICATIONS_TAG;
    }

    if ($tag == ACCOUNT_NOTIFICATIONS_TAG){
      $template = SENDWITHUS_TEMPLATE_ACCOUNT;
    }
    elseif($tag == COMMENT_NOTIFICATIONS_TAG){
      $template = SENDWITHUS_TEMPLATE_COMMENTS;
    }
    elseif($tag == NEWS_TAG){
      $template = SENDWITHUS_TEMPLATE_NEWS;
    }
    elseif($tag == NEW_THIS_WEEK_TAG){
      $template = SENDWITHUS_TEMPLATE_3FEATURES;
    }

  }
  $hdr_from = stripslashes(clearPost($hdr_from));
  $hdr_to = stripslashes(clearPost($hdr_to));
  if($replyTo!=null){
    $replyTo=stripslashes(clearPost($replyTo));
  }
  $email = clearPost($email);
  $subject = clearPost($subject);
  $body = clearPost($body);


  /* Html inject removed */

  include_once (PEAR_DIR.'Mail/mime.php');

  $siteurl = HTTP_METHOD . $_SERVER['SERVER_NAME'] . DOC_ROOT;

  global $bannerURL, $config, $smarty;

  $crlf = chr(10);  // as required in the PEAR manuals for use with PEAR mail. We use chr(10) instead of /n, because /n was displayed as the last line of the email.
  $uname = $database->getUserNamesByEmail($email);
  $cc = '';
  if(count($uname) > 1) {
    Logger("Multiple users found on same email ".$email);
  }else {
      $ulevel = $database->getUserLevel($uname[0]['username']);
      $brwrid = $database->getUserId($uname[0]['username']);
      if($ulevel==BORROWER_LEVEL) {
        $behalfid = $database->getborrowerbehalfid($brwrid);
        if($behalfid > 0) {
          $behalfdetail = $database->getBorrowerbehalfdetail($behalfid);
          $cc = $behalfdetail['email'];
        }
      }
  }
  $headers = array (
    'From'      => $hdr_from,
    'Subject'   => stripslashes($subject),
    'Reply-To'=>$replyTo,
    'Cc'=>$cc
  );
  $mime = new Mail_mime($crlf);
  $language = "en";
  if(isset($_GET["language"])) {
    $language = $_GET["language"];
  }
  /* modify the encoding in mine with what is given for chosen language */
  $mime->_build_params['text_encoding'] = '7bit';//get_lang('mail_text_encoding');
  $mime->_build_params['html_encoding'] = '7bit'; //get_lang('mail_html_encoding');
  $mime->_build_params['html_charset'] = (isset($encodeArray[$language])) ? $encodeArray[$language] : $encodeArray['en'];//get_lang('mail_html_charset');
  $mime->_build_params['text_charset'] = (isset($encodeArray[$language])) ? $encodeArray[$language] : $encodeArray['en'];//get_lang('mail_text_charset');
  $mime->_build_params['head_charset'] = (isset($encodeArray[$language])) ? $encodeArray[$language] : $encodeArray['en'];// get_lang('mail_head_charset');
  if ($html) {

    $body = str_replace('#content#', $body,$templet);

  }

  $siteurl = str_replace('cronjobs/','',HTTP_METHOD . $_SERVER['SERVER_NAME'] . DOC_ROOT) ;

  $body = str_replace('#link#', $siteurl,$body);

  $body = str_replace('#SiteUrl#', $siteurl,$body);



    $parserfile = 'css_parser.php';
    require_once($parserfile);

    $cssparser =  new cssParser();
    //$css is css stylesheet string
    //$cssparser->ParseStr($css);
    $cssfile =FULL_PATH.'css/default/style.css';

    $cssparser->parseFile($cssfile);

    $htmlholder = new htmlholder($body);

    $htmlholder->replaceCSS($cssparser->codestr_holder);

    $page = $htmlholder->out();

    $page = str_replace('#SiteUrl#', $siteurl,$page);

    $mime->setHTMLBody($page);




  if (!is_array($attachment) ) {
    $attach_files = explode(',',$attachment);
  } else {
    $attach_files = $attachment;
  }
  if (count($attach_files) > 0) {
    foreach ($attach_files as $file) {
      if ($file != '') {
        $mime->addAttachment("../emailimages/".$file);
      }
    }
  }

  $body = $mime->get();
  $hdrs = $mime->headers($headers);

    $params = false;

  if (MAIL_TYPE == 'smtp') {

    $params['host'] = SMTP_HOST;
    $params['port'] = SMTP_PORT;
    $params['auth'] = (SMTP_AUTH=='1') ? true : false ;
    $params['username'] = SMTP_USER;
    $params['password'] = SMTP_PASS;
  }

  if ( 1 ) {
    $mail_type = 'mail';
  }
  else {
    $mail_type = MAIL_TYPE;
  }


  if (ECHO_EMAILS === true)
  {
    echo $email . "<br/>";
    print_r($hdrs);
    echo "<br/>" . $body . "<br/>";
    $rc = 1;
  }
  else
  {
    $sendwithus_api = new API(SENDWITHUS_API_KEY);

    if (empty ($body2)){
      $email_data = array(
        'subject' => $headers['Subject'],
        'header' => $header,
        'image_src' => $info['image_src'],
        'content' => $body_original,
        'link' => array(
            'text' => $info['anchor'],
            'url' => $info['link']
        ),
        'footer' => $footer,
        'button' => array(
            'url' => $button_url,
            'text' => $button_text
        ),
      );
    } else {
      $email_data = array(
        'subject' => $headers['Subject'],
        'header' => $header,
        'image_src' => $info['image_src'],
        'content' => $body_original,
        'link' => array(
            'text' => $info['anchor'],
            'url' => $info['link']
        ),
        'image_src2' => $info['image_src2'],
        'content2' => $body2,
        'link2' => array(
            'text2' => $info['anchor2'],
            'url2' => $info['link2']
        ),
        'image_src3' => $info['image_src3'],
        'content3' => $body3,
        'link3' => array(
            'text3' => $info['anchor3'],
            'url3' => $info['link3']
        ),
        'footer' => $footer,
        'button' => array(
            'url' => $button_url,
            'text' => $button_text
        )
      );
      $template = SENDWITHUS_TEMPLATE_3FEATURES;
    }

    $result = $sendwithus_api->send(
        $template,
        array(  // Recipient information
        'address' => $email
        ),
        $email_data
      );

  }
  return $result;

}
?>