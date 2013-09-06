<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;

  Utils::load_dict();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Camping Punta Indiani snc</title>

    <meta http-equiv="Content-Type" content="text/html">
    <meta charset="utf-8">
    <meta name="author" content="Martin Brugnara <martin.brugnara@gmail.com">

    <meta name="google-site-verification" content="h7NhDkvsryR3wPb1KDHoRfajA7moBLGymNKtzpgerJ8"/>

    <meta name="description" content="Camping Punta Indiani - Italy - Trentino - Lago Caldonazzo - main site">

    <link rel="stylesheet" media="all" type="text/css" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" media="all" type="text/css" href="/static/css/fixes.css">

    <link rel="stylesheet" media="all" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu:300,500,700&subset=latin">

  </head>
  <body>
    <nav class="navbar navbar-fixed-top" id="topbar">
      <div class="navbar-inner">
        <!-- collapse support -->
        <button class="btn btn-navbar" data-target=".nav-collapse" data-toggle="collapse" type="button">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <!-- Standard view -->
        <div class="nav-collapse collapse">
          <ul class="nav pages">
            <li><a href="#" class="logo"><img src="static/img/logo.png" alt="Camping Punta Indiani snc Logo"></a></li>
            <li><a href="#" class="brand">Camping Punta Indiani</a></li>
            <li class="divider-vertical"></li>

            <li class="<?=($_SESSION['page']=="home"?"active":"")?>"><a href="/?page=home" data-page="home"><i class="icon-home"></i><?php echo $GLOBALS['dict']->menu->{$_SESSION['lang']}->home; ?></a></li>
            <li class="<?=($_SESSION['page']=="prices"?"active":"")?>"><a href="/?page=prices" data-page="prices"><?=$GLOBALS['dict']->menu->{$_SESSION['lang']}->prices?></a></li>
            <li class="<?=($_SESSION['page']=="map"?"active":"")?>"><a href="/?page=camping_map" data-page="camping_map"><?=$GLOBALS['dict']->menu->{$_SESSION['lang']}->map?></a></li>
            <li class="<?=($_SESSION['page']=="location"?"active":"")?>"><a href="/?page=route" data-page="route"><?=$GLOBALS['dict']->menu->{$_SESSION['lang']}->location?></a></li>
            <li class="<?=($_SESSION['page']=="gallery"?"active":"")?>"><a href="/?page=gallery" data-page="gallery"><?=$GLOBALS['dict']->menu->{$_SESSION['lang']}->gallery?></a></li>
            <li class="<?=($_SESSION['page']=="offers"?"active":"")?>"><a href="/?page=offers" data-page="offers"><?=$GLOBALS['dict']->menu->{$_SESSION['lang']}->offers?></a></li>
            <li class="<?=($_SESSION['page']=="surroundings"?"active":"")?>"><a href="/?page=surroundings" data-page="surroundings"><?=$GLOBALS['dict']->menu->{$_SESSION['lang']}->surroundings?></a></li>
          </ul>
         
          <ul class="nav pull-right langs">
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-globe"></i>&nbsp;<?=$GLOBALS['dict']->menu->{$_SESSION['lang']}->languages?>&nbsp;<b class="caret"></b></a>
              <ul class="dropdown-menu">
              <?php foreach($GLOBALS['dict']->langs->all as $l){ ?>
                <li><a href="/?lang=<?=$l?>"><i class="flag flag-<?=$l?>"></i>&nbsp;<?=$GLOBALS['dict']->langs->{$l}?></a></li>
              <?php } ?>
              </ul>
            </li>
            <!-- noscript lang support -->
            <noscript>
              <!-- if no js, no drop down available -> fallback on linear layout -->
              <li class="divider-vertical"></li>
              <?php foreach($GLOBALS['dict']->langs->all as $l): ?>
                <li><a href="/?lang=<?=$l?>"><i class="flag flag-<?=$l?>"></i>&nbsp;<?=$GLOBALS['dict']->langs->{$l}?></a></li>
              <?php endforeach; ?>
            </noscript>
            <!-- end noscript lang support -->
            <li class="divider-vertical"></li>
            <li class="<?=($_SESSION['page']=="booking"?"active":"")?>"><a href="/?page=booking" data-page="booking"><?=$GLOBALS['dict']->menu->{$_SESSION['lang']}->book?></a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Carousel -->
    <section id="head_carousel" class="container carousel slide"> 
      <!-- Carousel items -->
      <div class="carousel-inner">
      <?php $c_fst = true; ?>
      <?php foreach($GLOBALS['dict']->carousel as $slide): ?>
        <div class="item <?=$c_fst?'active':''?>">
          <img alt="<?=$slide->url?>" src="<?=$slide->url?>">
        </div>
        <?php $c_fst=false;?>
      <?php endforeach; ?>
      </div>
      <!-- loading -->
      <div class="loading hide"><img src="static/img/black.png"/></div>
      <!-- Carousel nav -->
      <a class="carousel-control left" href="#head_carousel" data-slide="prev">&lsaquo;</a>
      <a class="carousel-control right" href="#head_carousel" data-slide="next">&rsaquo;</a>
    </section>
    
    <!--  Support for non js browser -->
    <noscript>
      <div class="container">
        <div class="alert">
          <p>It seems that Your browser does not support JavaScript</p>
          <p>We recommend <a href="http://www.mozilla.org/en-US/" target="_blank">Mozilla Firefox</a> for a better visual and functional experience</p>
        </div>
      </div>
    </noscript>

    <!-- Page entry point -->
    <section class="container" name="body">