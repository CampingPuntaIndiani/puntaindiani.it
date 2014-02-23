<?php

  $srv_base = '/'; 
  include_once '../libs/pwd.inc.php';
  include_once '../libs/media.inc.php';

  $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Error '.mysqli_error($link));
  $link->autocommit(TRUE);

  $errors = array();

  if(isset($_POST['update']) and $_POST['update'] == '1') {
    unset($_POST['update']);

    $query_array = array(
        'photo_auth_' => function($val, $pk){return sprintf(
            'UPDATE photoes SET authorized=%s WHERE id=%s LIMIT 1',
            intval($val), intval($pk));},
        'photo_priority_' => function($val, $pk){return sprintf(
            'UPDATE photoes SET priority=%s WHERE id=%s LIMIT 1',
            intval($val), intval($pk));},
        'album_name_' => function($val, $pk){return sprintf(
            'UPDATE album SET name="%s" WHERE id=%s LIMIT 1',
            mysqli_real_escape_string(strtolower($val)), intval($pk));},
        'album_show_' => function($val, $pk){return sprintf(
            'UPDATE album SET show=%s WHERE id=%s LIMIT 1',
            intval($val), intval($pk));},
        'album_priority' => function($val, $pk){return sprintf(
            'UPDATE album SET priority=%s WHERE id=%s LIMIT 1',
            intval($val), intval($pk));}
    );

    foreach ($_POST as $name => $val) {
        $query === FALSE;
        foreach ($query_array as $st => $q) {
            if(strpos($name, $st) === 0) {
                $query = $q($val, str_replace($st, '', $name));
                break;
            }
        }

        if ($query and !$link->query($query)) {
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
    
    <style type="text/css">
        input:not(.exclude) {
            padding: 1px 1px 1px 1px !important;
            margin-top: 2px !important;
            width: 30px !important;
            text-align: center !important;
            margin-bottom: 0;
        }

        .make-switch {
            margin-top: 1px !important;
        }

        li {
            text-align: center;
        }

        div.admin-title input:first-child {
            text-align: left !important;
            font-size: 14pt;
            text-transform: capitalize;
            font-weight: bold;
            margin-bottom: 0;
            margin-right: 10px !important;
        }

        div.admin-title {
            width: 100%;
            display: block;
            border-top: 2px solid #333;
            border-left: 1px solid gold;
            padding-top: 5px;
            padding-left: 10px;
            padding-bottom: 5px;
            margin-top: 50px;
            margin-bottom: 5px;
        }
    </style>


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
            $query = join(array( 
                "SELECT photoes.id, photoes.name, file_name, uploader_email, photoes.priority, album.id, album.name, album.show, album.priority, authorized, uploaded_datetime",
                "FROM photoes, album",
                "WHERE photoes.album_id = album.id",
                "ORDER BY album.priority DESC, album.name ASC, photoes.priority DESC, photoes.uploaded_datetime ASC"),
                " ");
          if ($stmt = $link->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($id, $name, $file_name, $uploader_email, $photo_priority, $album_id, $album_name, $album_show, $album_priority, $authorized, $uploaded_datetime);

            $last_album = FALSE;
            while ($stmt->fetch()) {
              if ($album_id !== $last_album){
                if ($last_album !== FALSE) {
                  print('</ul>');
                }
                $last_album = $album_id;
                $album_index = 0;


                /* Album control */
                print('<div class="admin-title">');
                printf('<input type="text" class="exclude" name="album_name_%s" value="%s" data-init="%s">', $album_id, $album_name, $album_name);
                printf('<input type="text" name="album_priority_%s" value="%s" data-init="%s" pattern="(1|0)([0-9]){,2}"/>', $album_id, $album_priority, $album_priority);
                printf('<div class="make-switch switch-small"><input type="checkbox" name="album_show_%s" data-init="%s" %s></div>', 
                    $album_id,
                    $album_show,
                    $album_show == 1 ? 'checked' : ''
                );
                print('</div>');

                print('<ul class="thumbnails">');
              }

              print('<li class="span2">');
              printf('<small class="">%s</small>', $uploaded_datetime);
              printf('<a href="%s" data-uploader="%s" data-name="%s" title="%s" target="_blank">',
                $big_path.$file_name.$big_ext, 
                $uploader_email,
                $name,
                'Camping Punta Indiani-'.ucwords($album_name).'-'.(++$album_index)
              );
              printf('<img src="%s" class="img-rounded img-polaroid" alt="%s">',
                $ico_path.$file_name.$ico_ext,
                $name
              );
              print('</a>');

              /* photo options */
              printf('<input class="input-small pull-left" type="text" name="photo_priority_%s" value="%s" data-init="%s" pattern="(1|0)([0-9]){,2}"/>',
                  $id,
                  $photo_priority,
                  $photo_priority
              );

              print('<div class="make-switch switch-small pull-right">');
              printf('<input type="checkbox" name="photo_auth_%s" data-init="%s" %s>',
                $id,
                $authorized,
                $authorized == 1 ? 'checked' : ''
              );
              print('</div>');
              print('</li>');
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
