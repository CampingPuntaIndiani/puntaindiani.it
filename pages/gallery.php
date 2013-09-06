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

      <!-- modal-target : modal div -->
      <section data-toggle="modal-gallery" data-target="#modal-gallery">
      <?php
        if($root = opendir($base)):
          while(FALSE !== ($album_link = readdir($root))):
            if($album_link != '.' and $album_link != '..' and is_dir($base.$album_link)):
              // iterate over big to get album structure 
              if(FALSE !== ($album = opendir($base.$album_link.$big))): 
                $secure_album = htmlspecialchars($album_link, $ENT_HTML5, 'UTF-8');
      ?>
          <h3><?=str_replace("_", " ", $secure_album)?></h3>
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
              <a href="<?=str_replace('+', '%20', $base.urlencode($album_link).$big.urlencode($photo))?>" title="<?=$secure_album.' : '.($c+1).'/'.$tot?>" 
                 rel="gallery" data-album="<?=$secure_album?>" target="_blank">
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

      <!-- modal-gallery is the modal dialog used for the image gallery -->
      <div id="modal-gallery" class="modal modal-gallery hide fade" tabindex="-1">
          <div class="modal-header">
              <a class="close" data-dismiss="modal">&times;</a>
              <h3 class="modal-title"></h3>
          </div>
          <div class="modal-body"><div class="modal-image"></div></div>
          <div class="modal-footer">
              <a class="btn btn-info modal-prev pull-left"><i class="icon-arrow-left icon-white"></i>&nbsp;Previous</a>
              <a class="btn btn-success modal-play modal-slideshow" data-slideshow="3000"><i class="icon-play icon-white"></i>&nbsp;Slideshow</a>
              <a class="btn modal-download" target="_blank"><i class="icon-download"></i>&nbsp;Download</a>
              <a class="btn btn-primary modal-next pull-right">Next&nbsp;<i class="icon-arrow-right icon-white"></i></a>
          </div>
      </div>