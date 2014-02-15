<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <?php if($language=='') {?>
    <BASE href="<?php echo SITE_URL?>">
  <?php }else { ?>
    <BASE href="<?php echo SITE_URL.$language?>/">
  <?php } ?>

  <?php include_once("includes/title.php"); ?>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="Peer-to-peer lending across the international wealth divide."/>
  <meta name="author" content=""/>

  <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <link rel="shortcut icon" href="images/favicon.ico"/>

  <!-- CSS Files -->
  <link href="css/default/main.css" rel="stylesheet"/>
  <link href="css/default/popup_style.css?q=<?php echo RANDOM_NUMBER ?>" rel="stylesheet"/>
  <link href="https://fast.fonts.com/cssapi/0929098d-fa4b-407d-bb59-a9c929284820.css" rel="stylesheet" type="text/css" />
  <link href="includes/scripts/facebox/facebox.css?q=<?php echo RANDOM_NUMBER ?>" media="screen" rel="stylesheet" type="text/css" />

  <!-- old site script and css files -->
  <script type="text/javascript" src="includes/scripts/jquery.js" ></script>
  <script type="text/javascript" src="includes/scripts/jquery.tablesorter.js?q=<?php echo RANDOM_NUMBER ?>"></script>
  
  <!-- script to load randomly selected hero image -->
  <script type="text/javascript" src="includes/scripts/facebox/facebox.js?q=<?php echo RANDOM_NUMBER ?>"></script>
  <script type="text/javascript" src="includes/scripts/facebox/facebox.js?q=<?php echo RANDOM_NUMBER ?>"></script>

  <?php include_once("includes/_js_inline_load.php"); ?>

</head>
<body>

<div class="container">
  <div id="top-right">
    <?php   include("includes/_toplinks.php"); ?>
    
    <div style="clear:both"></div>

    <?php   include("includes/_lang.selector.php"); ?>

    <div style="clear:both"></div>

    <?php   include("includes/_login.header.php"); ?>
  </div><!-- #top-right -->

  
  <div style="clear:both"></div>

  <div id="logo"><h1><a href="./">Zidisha</a></h1></div>

  <?php include("includes/_CSRF_warning.php"); ?>
  
  <?php include("includes/_nav.main.php"); ?>
  
  <?php if($page==0){ include_once("includes/home_flash.php");} ?>
  
  <div class="row">