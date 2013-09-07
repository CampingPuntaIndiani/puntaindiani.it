<?php
  $tot = $_GET['items'] or 4;
  $lh = 30;
  $css_a = array();
  $i=0;
  for($step=0; $step <= 100; $step+=100/$tot) {
    array_push($css_a, ceil($step).'% {margin-top: -'.($lh*$i).'px;}');
    $i++;
  }
  array_push($css_a, "100% {margin-top: 0;}");
  $css = ''.join($css_a);
?>
    
@-webkit-keyframes ticker {
  <?=$css?>
}
@-moz-keyframes ticker {
  <?=$css?>
}
@-ms-keyframes ticker {
  <?=$css?>
}
@keyframes ticker {
  <?=$css?>
}