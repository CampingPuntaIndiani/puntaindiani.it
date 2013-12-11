<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
  
  Utils::load_dict();
?>

      <header class="row">
        <div class="span10">
          <h5><?=join('<br>', $GLOBALS['dict']->page->{$_SESSION['lang']}->prices_notice)?><h5>
        </div>
        <div class="span2">
          <a href="#prices_map_modal" role="button" class="btn pull-right" data-toggle="modal">CAMPING MAP</a>
        </div>
      </header>
      <br>
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <th><?=$GLOBALS['dict']->prices->year?></th>
          <th><?=$GLOBALS['dict']->prices->low->periods[0]->from?> : <?=$GLOBALS['dict']->prices->low->periods[0]->to?></th>
          <th><?=$GLOBALS['dict']->prices->middle->periods[0]->from?> : <?=$GLOBALS['dict']->prices->middle->periods[0]->to?></th>
          <th><?=$GLOBALS['dict']->prices->height->periods[0]->from?> : <?=$GLOBALS['dict']->prices->height->periods[0]->to?></th>
          <th><?=$GLOBALS['dict']->prices->low->periods[1]->from?> : <?=$GLOBALS['dict']->prices->low->periods[1]->to?></th>
        </thead>
        <tbody>
          <tr>
            <td><span class="label label-important"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->place_a?></span></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->place_a->type)?" ".$GLOBALS['dict']->prices->low->place_a->type:"")?>"><?=$GLOBALS['dict']->prices->low->place_a->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->middle->place_a->type)?" ".$GLOBALS['dict']->prices->middle->place_a->type:"")?>"><?=$GLOBALS['dict']->prices->middle->place_a->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->height->place_a->type)?" ".$GLOBALS['dict']->prices->height->place_a->type:"")?>"><?=$GLOBALS['dict']->prices->height->place_a->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->place_a->type)?" ".$GLOBALS['dict']->prices->low->place_a->type:"")?>"><?=$GLOBALS['dict']->prices->low->place_a->price?></td>
          </tr>
          <tr>
            <td><span class="label label-success"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->place_b?></span></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->place_b->type)?" ".$GLOBALS['dict']->prices->low->place_b->type:"")?>"><?=$GLOBALS['dict']->prices->low->place_b->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->middle->place_b->type)?" ".$GLOBALS['dict']->prices->middle->place_b->type:"")?>"><?=$GLOBALS['dict']->prices->middle->place_b->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->height->place_b->type)?" ".$GLOBALS['dict']->prices->height->place_b->type:"")?>"><?=$GLOBALS['dict']->prices->height->place_b->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->place_b->type)?" ".$GLOBALS['dict']->prices->low->place_b->type:"")?>"><?=$GLOBALS['dict']->prices->low->place_b->price?></td>
          </tr>
          <tr>
            <td><span class="label"><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->place_c?></span></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->place_c->type)?" ".$GLOBALS['dict']->prices->low->place_c->type:"")?>"><?=$GLOBALS['dict']->prices->low->place_c->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->middle->place_c->type)?" ".$GLOBALS['dict']->prices->middle->place_c->type:"")?>"><?=$GLOBALS['dict']->prices->middle->place_c->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->height->place_c->type)?" ".$GLOBALS['dict']->prices->height->place_c->type:"")?>"><?=$GLOBALS['dict']->prices->height->place_c->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->place_c->type)?" ".$GLOBALS['dict']->prices->low->place_c->type:"")?>"><?=$GLOBALS['dict']->prices->low->place_c->price?></td>
          </tr>
          <tr>
            <td><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->person?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->person->type)?" ".$GLOBALS['dict']->prices->low->person->type:"")?>"><?=$GLOBALS['dict']->prices->low->person->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->middle->person->type)?" ".$GLOBALS['dict']->prices->middle->person->type:"")?>"><?=$GLOBALS['dict']->prices->middle->person->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->height->person->type)?" ".$GLOBALS['dict']->prices->height->person->type:"")?>"><?=$GLOBALS['dict']->prices->height->person->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->person->type)?" ".$GLOBALS['dict']->prices->low->person->type:"")?>"><?=$GLOBALS['dict']->prices->low->person->price?></td>
          </tr>
          <tr>
            <td><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->child?> [2-12]</td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->child->type)?" ".$GLOBALS['dict']->prices->low->child->type:"")?>"><?=$GLOBALS['dict']->prices->low->child->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->middle->child->type)?" ".$GLOBALS['dict']->prices->middle->child->type:"")?>"><?=$GLOBALS['dict']->prices->middle->child->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->height->child->type)?" ".$GLOBALS['dict']->prices->height->child->type:"")?>"><?=$GLOBALS['dict']->prices->height->child->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->child->type)?" ".$GLOBALS['dict']->prices->low->child->type:"")?>"><?=$GLOBALS['dict']->prices->low->child->price?></td>
          </tr>
          <tr>
            <td><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->extra?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->extra->type)?" ".$GLOBALS['dict']->prices->low->extra->type:"")?>"><?=$GLOBALS['dict']->prices->low->extra->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->middle->extra->type)?" ".$GLOBALS['dict']->prices->middle->extra->type:"")?>"><?=$GLOBALS['dict']->prices->middle->extra->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->height->extra->type)?" ".$GLOBALS['dict']->prices->height->extra->type:"")?>"><?=$GLOBALS['dict']->prices->height->extra->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->extra->type)?" ".$GLOBALS['dict']->prices->low->extra->type:"")?>"><?=$GLOBALS['dict']->prices->low->extra->price?></td>
          </tr>
          <tr>
            <td><?=$GLOBALS['dict']->page->{$_SESSION['lang']}->visitor?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->visitor->type)?" ".$GLOBALS['dict']->prices->low->visitor->type:"")?>"><?=$GLOBALS['dict']->prices->low->visitor->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->middle->visitor->type)?" ".$GLOBALS['dict']->prices->middle->visitor->type:"")?>"><?=$GLOBALS['dict']->prices->middle->visitor->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->height->visitor->type)?" ".$GLOBALS['dict']->prices->height->visitor->type:"")?>"><?=$GLOBALS['dict']->prices->height->visitor->price?></td>
            <td class="prices<?=(isset($GLOBALS['dict']->prices->low->visitor->type)?" ".$GLOBALS['dict']->prices->low->visitor->type:"")?>"><?=$GLOBALS['dict']->prices->low->visitor->price?></td>
          </tr>
        </tbody>
        <tfoot>
          <td colspan="5">
            <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->extra_explanation?><br>
            <?=$GLOBALS['dict']->page->{$_SESSION['lang']}->visitor_explanation?>
          </td>
        </tfoot>
      </table>

      <div class="modal hide fade" id="prices_map_modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h3>Camping Punta Indiani :: Maps</h3>
        </div>
        <div class="modal-body">
          <img src="static/img/camping_map/CampingPuntaIndianiOfficialMap.jpg" alt="maps" style="width:100%" />
        </div>
      </div>
