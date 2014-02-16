<?php
  /* Please set here for showing login form, stats and our impact */
  $showOutImpact=0;
  if($page==0 || $page==2 || $page==26 || $page==27 || $page==46 || $page==33 || $page==64 || $page==38 || $page==6 || $page==43 || $page==62 || $page==1 || $page==13 || $page==65 || $page==67 || $page==56 || $page==5 || $page==14 || $page==12 || $page==24 || $page==40 || $page==59 || $page==11 || $page==14 || $page==19 || $page==16 || $page==17 || $page==30 || $page==9 || $page==37 || $page==44 || $page==41 || $page==7 || $page==8  || $page==49 || $page==71 || $page==50 || $page==52 || $page==53  || $page==54 || $page==72 || $page==73 || $page==74 || $page==75 || $page==76 || $page==77 || $page==78 || $page==79 || $page==80 || $page==81 || $page==82 || $page==83 || $page==84|| $page==85 || $page==86 || $page==87 || $page==88 || $page==89 || $page==90 || $page==91 || $page==92 || $page==93 || $page==94 || $page==95 || $page==96 || $page==97 || $page==98 || $page==99 || $page==101 || $page==102 || $page==115 || $page==116 || $page==119 || $page==120 || $page==121)
  {
    $showOutImpact=1;
    if(!empty($session->userid) && ($page==14))
      $showOutImpact=0;
  }

  // Load sidebar on these pages. 
  if($page==2 || $page==12 || $page==14 || $page==37 || $page==41 || $page==42 || $page==9 || $page==13 || $page==44 || $page==7 || $page==20 || $page==8 || $page==60 || $page==17 || $page==21 || $page==63 || $page==23 || $page==26 || $page==27 || $page==46 || $page==33 || $page==64 || $page==38 || $page==6 || $page==43 || $page==62 || $page==1 || $page==65 || $page==67 || $page==56 || $page==5 || $page==31 || $page==32 || $page==22 || $page==25 || $page==29 || $page==35 || $page==39 || $page==36 || $page==45 || $page==19 || $page==16 || $page==30 || $page==24 || $page==40 || $page==59 || $page==11  || $page==49 || $page==71 || $page==50 || $page==52 || $page==53  || $page==54 || $page==72 || $page==73 || $page==74 || $page==75 || $page==76 || $page==77  || $page==78 || $page==79 || $page==80 || $page==81 || $page==82 || $page==83 || $page==84 || $page==85|| $page==86 || $page==87 || $page==88|| $page==89 || $page==90 || $page==91 || $page==92 || $page==93 || $page==94 || $page==95 || $page==96 || $page==97 || $page==98 || $page==99 || $page==101 || $page==102 || $page==103 || $page==104 || $page==105 || $page==106 || $page==107 || $page==108 || $page==109 || $page==110 || $page==111 || $page==112 || $page==113 || $page==114 || $page==115 || $page==116 || $page==117 || $page==118 || $page==119 || $page==120 || $page==121)
  {
    // If the $page == 1 and $_GET['sel'] == 1 or 2. Also the page is different from 116 -  don't load left sidebar 
    if( ! ( ( isset($_GET['sel']) && $page == 1 ) && ($_GET['sel'] == 1 || $_GET['sel'] == 2) ) && $page != '116' && $page != '14' && $page != '26')
    {
      echo "<div class='span4'>";
      include_once("includes/loginform.php");
      include_once("includes/stats.php");
      echo "</div>";
    }
  }
?>