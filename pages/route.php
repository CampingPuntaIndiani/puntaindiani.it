<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
  Utils::load_dict();
?>
      <header><p><?=join('<br>',$GLOBALS['dict']->page->{$_SESSION['lang']}->location)?></p></header>
      <h4>GPS 46°1'36 "N,11°13'49"E</h4>
      <section class="row">
          <img src="static/img/route/RoadFromTrento.jpg" class="span3 offset2"/>
          <img src="static/img/route/LocationOnCaldonazzoLake.gif" class="span4 offset1"/>
      </section>
      <section name="google-maps">
        <div class="row">
          <iframe class="span12" style="height: 600px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.it/maps?f=q&amp;source=s_q&amp;hl=it&amp;geocode=&amp;q=Camping+Punta+Indiani,+Frazione+Valcanover,+Pergine+Valsugana+Trento&amp;aq=t&amp;sll=41.442726,12.392578&amp;sspn=25.970301,67.631836&amp;vpsrc=0&amp;ie=UTF8&amp;hq=Camping+Punta+Indiani,&amp;hnear=Frazione+Valcanover,+38057+Pergine+Valsugana+Trento,+Trentino-Alto+Adige&amp;t=m&amp;ll=46.027988,11.232662&amp;spn=0.006295,0.008096&amp;output=embed">
          </iframe>
        </div>
        <footer class="row centered">
            <a href="http://maps.google.it/maps?f=q&amp;source=embed&amp;hl=it&amp;geocode=&amp;q=Camping+Punta+Indiani,+Frazione+Valcanover,+Pergine+Valsugana+Trento&amp;aq=t&amp;sll=41.442726,12.392578&amp;sspn=25.970301,67.631836&amp;vpsrc=0&amp;ie=UTF8&amp;hq=Camping+Punta+Indiani,&amp;hnear=Frazione+Valcanover,+38057+Pergine+Valsugana+Trento,+Trentino-Alto+Adige&amp;t=m&amp;ll=46.027988,11.232662&amp;spn=0.006295,0.008096" style="text-align:left;" target="_blank">Zoom</a>
        </footer>
      </section>