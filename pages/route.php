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
          <div class="span12" id="map-canvas" style="height:600px">
          </div>

        </div>
        <footer class="row centered">
            <a href="//www.google.com/maps/preview#!data=!1m4!1m3!1d3376!2d11.2326622!3d46.0279884!4m25!2m11!1m10!1s0x0%3A0xd5c1e8a889a3283!3m8!1m3!1d26081603!2d-95.677068!3d37.0625!3m2!1i1024!2i768!4f13.1!5m12!1m11!1sPunta+Indiani!4m8!1m3!1d26081603!2d-95.677068!3d37.0625!3m2!1i1024!2i768!4f13.1!17b1" target="_blank">Zoom</a>
        </footer>
      </section>

      <script type="text/javascript">
        var gMapsLoaded = false;
        window.gMapsCallback = function(){
          gMapsLoaded = true;
          $(window).trigger('gMapsLoaded');
        }
        window.loadGoogleMaps = function(){
          if(gMapsLoaded || window['google']!==undefined) return window.gMapsCallback();
          var script_tag = document.createElement('script');
          script_tag.setAttribute("type","text/javascript");
          script_tag.setAttribute("src","//maps.google.com/maps/api/js?key=AIzaSyBoAQvmSSuH0olUcnb1pWVHUDLLsGLdnoY&sensor=false&callback=gMapsCallback");
          (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
        }
        $(function() {
          var map, marker;
          function initialize() {
            var point = new google.maps.LatLng(46.027988, 11.232662);
            var mapOptions = {
              zoom: 14,
              center: point,
              scrollwheel: false,
              zoomControl: true,
              zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
              },
              mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById('map-canvas'),
                mapOptions);

            marker = new google.maps.Marker({
              map:map,
              draggable:false,
              animation: google.maps.Animation.DROP,
              position: point,
              raiseOnDrag: true,
              title: "Camping Punta Indiani",
            });
            google.maps.event.addListener(marker, 'click', toggleBounce);
          }

          function toggleBounce() {

            if (marker.getAnimation() != null) {
              marker.setAnimation(null);
            } else {
              marker.setAnimation(google.maps.Animation.BOUNCE);
            }
          }
          $(window).bind('gMapsLoaded', initialize);
          window.loadGoogleMaps();
        });
    </script>
