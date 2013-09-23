<?php

  $srv_base = '/'; 
  include_once '../libs/pwd.inc.php';
  include_once '../libs/media.inc.php';

  $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Error '.mysqli_error($link));

  $errors = array();

  if(isset($_POST['update']) and $_POST['update'] == '1') {
    unset($_POST['update']);

    foreach ($_POST as $id => $authorized) {
      $query = sprintf("UPDATE photoes SET authorized=%s WHERE id=%s LIMIT 1",
        intval($authorized), intval($id));

      if ($link->query($query) !== TRUE) {
        array_push($errors, sprintf('ID:%s to state %s', $id, $authorized));
      }
    }
    exit(0);
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Gallery Manager</title>

    <meta http-equiv="Content-Type" content="text/html">
    <meta charset="utf-8">
    <meta name="author" content="Martin Brugnara <martin.brugnara@gmail.com">

    <meta name="description" content="Camping Punta Indiani - Gallery Manager">

    <link rel="stylesheet" media="all" type="text/css" href="/static/css/bootstrap.min.css">
    <link rel="stylesheet" media="all" type="text/css" href="/static/css/fixes.css">
    <link rel="stylesheet" media="all" type="text/css" href="/static/css/blueimp-gallery.min.css">
    <link rel="stylesheet" media="all" type="text/css" href="/static/css/bootstrap-switch.min.css">

    <link rel="stylesheet" media="all" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu:300,500,700&subset=latin">
  </head>
  <body class="container">
    <section name="gallery">
      <div class="well well-small">
        <center>
          <button class="btn btn-inverse input-xxlarge" type="button" data-action="reset">RESET</button>
          <button class="btn btn-success input-xxlarge" type="button" data-action="save">SAVE</button>
        </center>
      </div>

        <?php
          $query = "SELECT id, name, file_name, uploader_email, album, authorized, uploaded_datetime FROM photoes ORDER BY album ASC, authorized DESC, uploaded_datetime";
          if ($stmt = $link->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($id, $name, $file_name, $uploader_email, $album, $authorized, $uploaded_datetime);

            $last_album = FALSE;
            while ($stmt->fetch()) {
              if ($album !== $last_album){
                if ($last_album !== FALSE) {
                  print('</ul>');
                }
                $last_album = $album;
                $album_index = 0;
                printf('<h3>%s&nbsp;<button class="btn btn-small btn-primary" data-action="show">Show all</button> <button class="btn btn-small btn-danger" data-action="hide">Hide all</button></h3>',strlen($album) != 0 ? $album : 'No name');
                print('<ul class="thumbnails">');
              }
              print('<li class="span2"><center>');
              printf('<small class="">%s</small>', $uploaded_datetime);
              printf('<a href="%s" data-uploader="%s" data-name="%s" title="%s" target="_blank">',
                $big_path.$file_name.$big_ext, 
                $uploader_email,
                $name,
                'Camping Punta Indiani-'.ucwords($album).'-'.(++$album_index)
              );
              printf('<img src="%s" class="img-rounded img-polaroid" alt="%s">',
                $ico_path.$file_name.$ico_ext,
                $name
              );
              print('</a>');
              print('<div class="make-switch switch-small" data-off="danger">');
              printf('<input type="checkbox" name="%s" data-init="%s" %s>',
                $id,
                $authorized,
                $authorized == 1 ? 'checked' : ''
              );
              print('</div>');
              print('</center></li>');
            }
            print('</ul>'); //otherwise last album will be not closed;
            $stmt->close();
          } else {
            print('Fuck!!! There is an error in the query!');
          }
          $link->close();
        ?>
      <div class="well well-small">
        <center>
          <button class="btn btn-inverse input-xxlarge" type="button" data-action="reset">RESET</button>
          <button class="btn btn-success input-xxlarge" type="button" data-action="save">SAVE</button>
        </center>
      </div>
    </section>

    <div id="blueimp-gallery" class="blueimp-gallery">
      <div class="slides"></div>
      <h3 class="title"></h3>
      <a class="prev">‹</a>
      <a class="next">›</a>
      <a class="close">×</a>
      <a class="play-pause"></a>
      <ol class="indicator"></ol>
    </div>

    <!-- Footer -->
    <div class="container">
      <hr>
      <footer>
        <span class="centered"><p>&copy; Camping Punta Indiani snc - Martin Brugnara - 2014</p></span>
      </footer>
    </div>

    <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/js/ajax.js"></script>
    <script type="text/javascript" src="/static/js/jquery.blueimp-gallery.min.js"></script>
    <script type="text/javascript" src="/static/js/bootstrap-switch.min.js"></script>
    <script type="text/javascript" src="/static/js/admin.js"></script>
  </body>
</html>
