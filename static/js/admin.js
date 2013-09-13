(function ($) {
  $(function(){
    $('section[name=gallery] a').on('click', function(e){
      e.preventDefault();
      e.stopImmediatePropagation();
      e.stopPropagation();

      var album = $(this).parents('ul').find('a');
      var start_at = album.index(this);

      var gallery = blueimp.Gallery(album, {
        container: '#blueimp-gallery',
        carousel: false,
        startSlideshow: true,
        closeOnSlideClick: false,
        hidePageScrollbars: false, //no bouncing
        disableScroll: true,
        continuous: true,
        fullScreen: false, //no double esc on close
        toggleControlsOnReturn: false,
      });
      gallery.slide(start_at, 0);
      gallery.toggleControls();
    });
  });
  $('button[data-action=save]').on('click', function () {
    var update_dict = {};
    $('.make-switch').each(function(){
      var e = $(this), i = e.find('input');
      if (e.bootstrapSwitch('status') !== (i.attr('data-init') == 1)) {
        update_dict[parseInt(i.attr('name'))] = (e.bootstrapSwitch('status') ? 1 : 0);
      }
    });
    
    $.ajax({
      url: '/admin/index.php',
      method: 'POST',
      data: $.extend(update_dict, {'update':1}),
      context: update_dict,
      success: function(){
        console.log('Yes!');
        $.each(this, function(i,e) {
          $('input[name='+i+']').attr('data-init', e);
        });
      },
      error: function(){
        console.log('Error!');
      }
    });
  });

  $('button[data-action=reset]').on('click', function(){
    $('.make-switch').each(function(){
      var e = $(this), i = e.find('input');
      e.bootstrapSwitch('setState', i.attr('data-init') == 1);
    });
  });

  $('button[data-action=show], button[data-action=hide]').on('click', function(){
    var b = $(this);
    console.log(b.parent('*').next('ul'));
    b.parent('*').next('ul').find('div.make-switch').bootstrapSwitch('setState', b.attr('data-action') == 'show');
  });
})(jQuery);
