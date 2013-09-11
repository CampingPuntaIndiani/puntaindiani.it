<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
  Utils::load_dict();

  $base = './media/approved/';
  $ico = '/ico/';
  $big = '/big/';

  $ENT_HTML5 = (16|32); //htmlspecialchars constant

  if (!is_dir($base)) 
    die("<h3>File system error ... Sorry for the inconvenience</h3>");
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
              <input type="text" name="album" placeholder="Album name - empty[default]" pattern="[a-zA-Z0-9]+">
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
        if($root = opendir($base)):
          while(FALSE !== ($album_link = readdir($root))):
            if($album_link != '.' and $album_link != '..' and is_dir($base.$album_link)):
              // iterate over big to get album structure 
              if(FALSE !== ($album = opendir($base.$album_link.$big))): 
                $secure_album = htmlspecialchars($album_link, $ENT_HTML5, 'UTF-8');
                $album_name = str_replace("_", " ", $secure_album)
      ?>
          <h3><?=$album_name?></h3>
          <ul class="thumbnails">
      <?php
                $photoes = array();
                while(FALSE !== ($cp = readdir($album)))
                  if($cp != '.' and $cp != '..' and !is_dir($cp))
                    $photoes[] = $cp;
                $tot = count($photoes);
                foreach($photoes as $c=>$photo):
                  $secure_photo = htmlspecialchars($photo, $ENT_HTML5, 'UTF-8');
      ?>
            <li class="span2 gallery-ico">
              <a href="<?=str_replace('+', '%20', $base.urlencode($album_link).$big.urlencode($photo))?>" title="<?=$album_name.' : '.($c+1).'/'.$tot?>" target="_blank">
                <img src="<?=str_replace('+', '%20', $base.urlencode($album_link).$big.urlencode($photo))?>" alt="<?=$secure_photo?>" class="img-rounded img-polaroid">
              </a>
            </li>
      <?php 
                endforeach; 
              endif;
              closedir($album);

      ?>
          </ul>
      <?php
            endif;
          endwhile;// end photo iterating
        endif;
        closedir($root);
      ?>
      </section>
      <section name="video">
        <h3>Videos</h3>
        <!-- bad practices ... bad pratices every where ;) -->
        <center><iframe width="960" height="540" src="http://www.youtube.com/embed/PqUCQyr6FtE" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe><p>Video - J. Brouwers Production - Thanks again </p></center>
        <center><iframe width="960" height="540" src="http://player.vimeo.com/video/8970989"  frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe><p><a href="http://vimeo.com/8970989">Camping Punta Indiani</a> from <a href="http://vimeo.com/renzomobiel">Renzo</a> on <a href="http://vimeo.com">Vimeo</a>.</p></center>
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
                });
                gallery.slide(start_at, 0);
                gallery.toggleControls();
              });
            });
          })(jQuery);
        })();
      </script>       