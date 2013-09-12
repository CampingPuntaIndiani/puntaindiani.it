<?php

  $srv_base = '/'; 
  include_once '../libs/db.inc.php';
  include_once '../libs/media.inc.php';

  $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Error '.mysqli_error($link));

  if(isset($_POST['sent']) and $_POST['sent'] == 1) {
    unset($_POST['sent']);
    $queryes = aray();
    foreach ($_POST as $id => $authorized) {
      array_push($queryes, 
        sprintf("UPDATE photoes SET authorized=%s WHERE id=%s LIMIT 1",
          intval($id), intval($authorized))
      );
    }
    // verificare e testare
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



    <link rel="stylesheet" media="all" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu:300,500,700&subset=latin">
  </head>
  <body class="container">
    <section name="gallery">
      <form action="#" method="POST">
        <input type="hidden" name="sent" value="1" /> 
      <button type="reset" class="btn btn-inverse">Reset</button><button type="submit" class="btn btn-success">Save</button>
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
              printf('<h3>%s&nbsp;<h3>',$album);
              print('<ul class="thumbnails">');
            }
            print('<li class="span2">');
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
            printf('<small class="">%s</small>', $uploaded_datetime);
            printf('<input class="pull-right" type="checkbox" name="%s" %s/>',
              $id,
              $authorized == 1 ? 'checked' : ''
            );
            print('</li>');
          }
          print('</ul>'); //otherwise last album will be not closed;
          $stmt->close();
        } else {
          print('Fuck!!! There is an error in the query!');
        }
        $link->close();
      ?>
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
    <script type="text/javascript" src="/static/js/admin.js"></script>
  </body>
</html>