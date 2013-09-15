<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
  Utils::load_dict();

  $srv_base = '/'; 
  include_once 'libs/db.inc.php';
  include_once 'libs/media.inc.php';


  $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die('Error '.mysqli_error($link));

  
        
?>
      <header>
      <?=join('<br>',$GLOBALS['dict']->page->{$_SESSION['lang']}->send_photo)?>
      </header>
      
      <br>

      <section name="upload">
        <header>
          <p id="upload_errors" class="text-error"></p>
        </header>
        <div class="row">
          <div class="span8">
            <form class="form-inline" method="POST" enctype="multipart/form-data" action="/upload_photo.php" id="upload_form">
              <input type="email" name="email" placeholder="you@provider.ext" required>
              <input type="text" name="album" placeholder="Album name - empty[default]" pattern="[a-zA-Z0-9 -]{2,}">
              <button type="button" class="btn btn-success" id="upload_proxy">Select Photoes&nbsp;<i class="icon-search icon-white"></i></button>
              <button type="submit" class="btn btn-primary" disabled>Upload&nbsp;<i class="icon-upload icon-white"></i></button>
              <input type="file" id="upload" name="photoes[]" multiple accept="image/*" style="display:none;">
            </form>
          </div>
          <div class="span4 progress progress-striped active">
            <div id="progress" class="bar"></div>
          </div>
        </div>
        <blockquote id="upload_list">
        </blockquote>
      </section>
      <script type="text/javascript" src="/static/js/jquery.form.js"></script>
      <script type="text/javascript" src="/static/js/upload-fix.js"></script>
      
      <section name="gallery">
      <?php
        $query = "SELECT name, file_name, uploader_email, album FROM photoes WHERE authorized=1 ORDER BY album ASC";
        if ($stmt = $link->prepare($query)) {
          $stmt->execute();
          $stmt->bind_result($name, $file_name, $uploader_email, $album);

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
            print('<li class="span2 gallery-ico">');
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
            print('</a></li>');
          }
          print('</ul>'); //otherwise last album will be not closed;
          $stmt->close();
        }
        $link->close();
      ?>
      </section>
      <section name="video">
        <h3>Videos</h3>
        <!-- bad practices ... bad pratices every where ;) -->
        <center><iframe width="960" height="540" src="//www.youtube.com/embed/PqUCQyr6FtE" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe><p>Video - J. Brouwers Production - Thanks again </p></center>
        <center><iframe width="960" height="540" src="//player.vimeo.com/video/8970989"  frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe><p><a href="http://vimeo.com/8970989">Camping Punta Indiani</a> from <a href="http://vimeo.com/renzomobiel">Renzo</a> on <a href="http://vimeo.com">Vimeo</a>.</p></center>
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

      <script type="text/javascript" src="/static/js/jquery.blueimp-gallery.min.js"></script>
      <script type="text/javascript">
        (function(){
          (function ($) {
            $(function(){
              window.load_css('/static/css/blueimp-gallery.min.css');
              /* flash fix */
              $('iframe').each(function(){
                $(this).wrap('<div class="gallery-video-placeholder noise"/>');
                $(this).parent('div').css({
                  'width': $(this).width(),
                  'height': $(this).height()                  
                });
              })

              $('section[name=gallery] a').on('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                e.stopPropagation();

                var album = $(this).parents('ul').find('a');
                var start_at = album.index(this);

                

                var gallery = blueimp.Gallery(album, {
                  container: '#blueimp-gallery',
                  carousel: false,
                  startSlideshow: true,
                  closeOnSlideClick: false,
                  hidePageScrollbars: false, //no bouncing
                  disableScroll: true,
                  continuous: true,
                  fullScreen: false, //no double esc on close
                  toggleControlsOnReturn: false,
                  onopen: function () {
                    $('iframe').hide(); // Fackyou you tube! shit of flash.
                  },
                  onclose: function () {
                    $('iframe').show(); // Fackyou you tube! shit of flash.
                  }
                });
                gallery.slide(start_at, 0);
                gallery.toggleControls();
              });
            });
          })(jQuery);
        })();
      </script>      