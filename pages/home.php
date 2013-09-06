<?php
    if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE))
        return;
    Utils::load_dict();

    print('<article>'.join('<br>',$GLOBALS['dict']->page->{$_SESSION['lang']}->presentation).'</article>');

?>