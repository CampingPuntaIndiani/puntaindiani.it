<?php 
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
  
  Utils::load_dict();
?>

      <?php foreach($GLOBALS['dict']->services as $service): ?>
        <?php if($service->{$_SESSION['lang']}->available): ?>
          <hr>
          <article class="row" name="article-<?=$service->{0}->title?>">
          <?php if($service->img):?>
              <img src="<?=$service->img?>" class="span2 img-rounded" alt="<?=$service->img?>">
          <?php else:?>
              <span class="span2">&nbsp;</span>
          <?php endif;?>
          <section class="span10">
            <header><h4><?=$service->{$_SESSION['lang']}->title?></h4></header>
            <p>
            <?=join('</p><p>', $service->{$_SESSION['lang']}->body)?>
            </p>
          </section>
        </article>
        <hr>
        <?php endif; ?>
      <?php endforeach; ?>
