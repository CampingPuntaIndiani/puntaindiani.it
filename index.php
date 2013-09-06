<?php
# Martin Brugnara 2014
# martin.brugnara@gmail.com
# Camping Punta Indiani snc

$GLOBALS['valid_req'] = TRUE;

include_once 'libs/Utils.php';

if(isset($_POST['select']) and $_POST['select'] === TRUE) {
    include_once('./pages/selectd.php');
    exit(1);
} 

Utils::update_env();  

if ($GLOBALS['ajax'] === FALSE)
    include_once('./pages/header.php');

include_once('./pages/'.$_SESSION['page'].'.php');

if ($GLOBALS['ajax'] === FALSE)
    include_once('./pages/footer.php');

?>
