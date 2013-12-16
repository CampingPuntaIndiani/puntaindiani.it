<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
  
  Utils::load_dict();

  print(join($GLOBALS['dict']->page->{$_SESSION['lang']}->pet_disclaimer, '<br>'));
?>
