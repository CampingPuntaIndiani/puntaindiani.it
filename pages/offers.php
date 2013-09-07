<?php 
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
  
  Utils::load_dict();
?>

      <?php foreach($GLOBALS['dict']->offers as $offer): ?>
        <?php if($offer->{$_SESSION['lang']}->available): ?>
          <hr>
          <article class="row" name="article-<?=$offer->{0}->title?>">
          <?php if($offer->img):?>
              <img src="<?=$offer->img?>" class="span2 img-rounded" alt="<?=$offer->img?>">
          <?php else:?>
              <span class="span2">&nbsp;</span>
          <?php endif;?>
          <section class="span10">
            <header><h4><?=$offer->{$_SESSION['lang']}->title?></h4></header>
            <?=$offer->{$_SESSION['lang']}->body?>
          </section>
        </article>
        <hr>
        <?php endif; ?>
      <?php endforeach; ?>
