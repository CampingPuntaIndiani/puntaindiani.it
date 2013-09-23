"use strict";
(function($){
  var current_ajax = undefined;
  var last_loaded_page = undefined;
  $(function(){
    $('#topbar ul.nav > li > a:not([href=#])').on('click', function(e){
      e.preventDefault();
      e.stopImmediatePropagation();
      e.stopPropagation();
      if (last_loaded_page === $(this).data('page')) {
        return;
      }

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
          last_loaded_page = this.data('page');
          _gaq.push(['_trackPageview', last_loaded_page+'_'+window.start_up.curr_lang]);
          $('#topbar ul.nav > li.active').removeClass('active');
          this.parent('li').addClass('active');
          $('section[name=body]').html(page);
        },
        error: function(jqXHR, status, error) {
          last_loaded_page = this.data('page');
          _gaq.push(['_trackPageview', last_loaded_page+'_'+window.start_up.curr_lang+'_error']);
          $('#topbar ul.nav > li.active').removeClass('active');
          this.parent('li').addClass('active');
          $('section[name=body]').html(error);
        }
      // we are not using complete while we need a more responsive 
      // (maps sometimes does not load... =( )
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
