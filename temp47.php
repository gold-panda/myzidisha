<?php
include("library/session.php");
global $database,$session;
$q="UPDATE `transactions` SET  `TrDate` =  '1377061200', `conversionrate` =  '494.75100' WHERE  `transactions`.`id` =234765";
$resultb= $db->query($q);
$q1="UPDATE `transactions` SET  `TrDate` =  '1377061200', `conversionrate` =  '494.75100' WHERE  `transactions`.`id` =234766";
$resultb= $db->query($q1);
$q2="UPDATE `transactions` SET  `TrDate` =  '1377061200', `conversionrate` =  '494.75100' WHERE  `transactions`.`id` =234767";
$resultb= $db->query($q2);
$q3="UPDATE `repaymentschedule_actual` SET  `paiddate` =  '1377061200' WHERE `repaymentschedule_actual`.`id` =11129";
$resultb= $db->query($q3);
$q4="UPDATE `repaymentschedule` SET  `paiddate` =  '1377061200' WHERE  `repaymentschedule`.`id` =33390";
$resultb= $db->query($q4);
$q5="UPDATE `repaymentschedule` SET  `paiddate` =  '1377061200' WHERE  `repaymentschedule`.`id` =33391";
$resultb= $db->query($q5);
echo"completed";
?>
