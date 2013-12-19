<?php
include('library/session.php');
global $session;

$now = date('y-m-d H:i:s', time());
$templet="editables/email/simplemail.html";
$Subject="Test mail At ".$now;
$To= 'Anupam Bharti';
$email = "anupam@codefire.in";
$From=EMAIL_FROM_ADDR;
$headers = "From:" . $From;
$message = "Hello! This is a simple email message.";
//$reply = $session->mailSending($From, $To, $email, $Subject, $message,$templet);
?>