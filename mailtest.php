<?php
$to      = 'a.sharma@jaguarinfotech.com';
$subject = 'the subject';
$message = 'Don\'t worry, this is only a test.


When in the course of human events it becomes necessary for one people to separate from another and, among the powers of the earth, assume...


Thanks for reading!


"\"';
$headers = 'From: noreply@zidisha.org' . "\r\n" .
    'Reply-To: noreply@zidisha.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>