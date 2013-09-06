<?php
  if (!(isset($GLOBALS['valid_req']) and $GLOBALS['valid_req'] === TRUE)) return;
?>
    </section>
    
    <!-- Footer -->
    <div class="container">
      <hr>
      <footer>
        <section name="info" class="row">
          <address class="span3">
            <strong>Camping Punta Indiani</strong><br>
            38057 Valcanover Pergine<br> 
            (TN) ITALY<br>
            <abbr title="Phone">Phone: 0461 548062</abbr><br>
            <a href="mailto:info@campingpuntaindiani.it">info@campingpuntaindiani.it</a>
          </address>
          <section class="span9">
            <ul class="thumbnails pull-right">
              <li><a><img class="img-rounded img-polaroid" src="static/img/footer/trentino.gif" alt="Trentino"></a></li>
              <li><a><img class="img-rounded img-polaroid" src="static/img/footer/ecc.jpg" alt="ECC"></a></li>
              <li><a><img class="img-rounded img-polaroid" src="static/img/footer/anwb.png" alt="ANWB"></a></li>
              <li><a><img class="img-rounded img-polaroid" src="static/img/footer/adac.jpg" alt="ADAC"></a></li>
              <li><a><img class="img-rounded img-polaroid" src="static/img/footer/dcc.jpg" alt="DCC"></a></li>
              <li><a><img class="img-rounded img-polaroid" src="static/img/footer/valsugana.png" alt="Valsugana"></a></li>
              <li><a><img class="img-rounded img-polaroid" src="static/img/footer/wifi.png" alt="WIFI"></a></li>
              <li><a href="<?=$GLOBALS['dict']->zoover->{$_SESSION['lang']}?>" target="_blank"><img class="img-rounded img-polaroid" src="static/img/footer/zoover.jpg" alt="Zoover winner 2012" ></a></li>
            </ul>
          </section>
        </section>  
        <span class="centered"><p>&copy; Camping Punta Indiani snc - Martin Brugnara - 2014</p></span>
      </footer>
    </div>

    <script type="text/javascript" src="/static/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
    <script>
      // ajax support
      var start_up = {
          page : '<?=$_SESSION['page']?>',
          curr_lang : '<?=$_SESSION['lang']?>'
        };
      history.pushState({}, "snc Camping Punta Indiani", "/");
      $($('.carousel').carousel({
        interval: 3000
      }));
    </script>
    <!-- Google analytics -->
    <!-- uncomment to enable 
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-34314298-1']);
      _gaq.push(['_trackPageview', start_up.page+'_'+start_up.curr_lang]);
      (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = 'https://ssl.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    -->
    <!-- end of Google analytics -->
  </body>
</html>