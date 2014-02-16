<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
?>
<div class="row">
    <div class="span6">
        <div style="position: relative; display: block;">
            <img src="static/img/camping_map/CampingPuntaIndianiOfficialMap.jpg" class="img-polaroid" alt="camping map" id="img_map">
            <section id="points">
            </section>
        </div>
    </div>
    <div class="span6">
        <img id="view" class="img-polaroid">
    </div>
</div>
<script type="text/javascript" src="/static/js/map.js"></script>

