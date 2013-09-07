"use strict";
(function($){
  var current_ajax = undefined;
  $(function(){
    $('#topbar ul.nav > li > a:not([href=#])').on('click', function(e){
      e.preventDefault();
      e.stopImmediatePropagation();
      e.stopPropagation();
      if (current_ajax !== undefined) {
        try {
          current_ajax.abort();
        } catch(e) {
          ;
        }
      }
      current_ajax = $.ajax({
        url: '/index.php',
        method: 'POST',
        cache: true,
        dataType: 'html',
        context: $(this),
        data: {
          'ajax' : 1,
          'page' : $(this).data('page')
        },
        success: function(page, status, jqXHR) {
          $('section[name=body]').html(page);
          $('')
        },
        error: function(jqXHR, status, error) {
          $('section[name=body]').html(error);
        },
        complete: function(){
          $('#topbar ul.nav > li.active').removeClass('active');
          this.parent('li').addClass('active');
        }
      });
    });
  });
  // export dynamic css loading
  window.load_css = function(uri) {
    var cssLink = $("<link>");
    $("head").append(cssLink);
      cssLink.attr({
      rel: "stylesheet",
      type: "text/css",
      href: uri
    });
  }
})(jQuery);