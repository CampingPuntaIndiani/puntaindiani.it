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
  $('input').on('change', function() {
    $(this).data('init', $(this).val());
    $(this).data('changed', 1);
  });
  $('form').on('submit', function(e){
    $(this).find('[data-changed=1]').filter(function(){
      return $(this).data('init') == $(this).val();
    }).data('changed', 0);
    $(this).find('input:not([data-changed=1])').removeAttr('name');
    return $(this).find('input:not([data-changed=1])').length != 0;
  })
})(jQuery);
