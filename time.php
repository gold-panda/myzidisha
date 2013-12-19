<?php
$date1= time();
echo $date1;
echo "<br>";
echo date('r', $date1);
echo "<br>";
$date2 = strtotime($servertime . 'IST');
echo $date2;
echo "<br>";
echo date('r', $date2);
?>