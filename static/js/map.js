(function($){
  var points = [],
    org_w = 1278, org_h = 1578,
    id_prefix = "map_elm_",
    id_prefix_len = id_prefix.length,
    url_base = '/static/img/camping_map/view/',
    marks = $('#points div'),
    view = $('#view');

  var build_points = function(){

    var w = parseInt($('#img_map').css('width'));
    var h = parseInt($('#img_map').css('height'));

    return $.map(points,  function(p) {
      return '<div style="left:'+Math.round(w*p.x/org_w - 20)+'px; top:'+Math.round(h*p.y/org_h - 20)+'px;" id="'+id_prefix+p.id+'" />';
    }).join('\r');

  };

  var click = function(ev) {
    var e = $(ev.target);
    var img_url = e.attr('id').substr(id_prefix_len);
    view.removeAttr('src');
    $('#points div[selected]').removeAttr('selected');
    e.attr('selected', 'selected');
    view.attr('src', url_base+img_url+'.jpg');
  };

  $(function(){
    $.getJSON('/static/json/map.js', function(json){
      points = json;

      $(window).on('resize', function(){
        $('#points').html(build_points());
      }).resize();
      $('#points').delegate('div', 'click', click);
      $('#'+id_prefix+points[0].id).click();
    });
  });
})(window.jQuery);
