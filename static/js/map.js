(function ($) {
    "use strict";
    var points = [],
        org_w = 1278,
        org_h = 1578,
        id_prefix = "map_elm_",
        id_prefix_len = id_prefix.length,
        url_base = '/static/img/camping_map/view/',
        marks = $('#points div'),
        view = $('#view'),
        img_map = $('#img_map'),

        build_points = function () {

            var w = parseInt(img_map.css('width'), 10),
                h = parseInt(img_map.css('height'), 10);

            return $.map(points,  function (p) {
                return ['<div style="left:',
                    Math.round(w * p.x / org_w - 20),
                    'px; top:',
                    Math.round(h * p.y / org_h - 20),
                    'px;" id="',
                    id_prefix,
                    p.id,
                    '" />'].join('');
            }).join('\r');

        },

        click = function (ev) {
            var e = $(ev.target),
                id = e.attr('id'),
                img_url = id.substr(id_prefix_len),
                selected = $('#points div[selected]');
            
            if (!selected || selected.attr('id') === e.attr('id')) {
                return;
            }
            
            view.removeAttr('src');
            selected.removeAttr('selected');
            e.attr('selected', 'selected');
            view.attr('src', url_base + img_url + '.jpg');
        };

    $(function () {
        $.getJSON('/static/json/map.js', function (json) {
            points = json;

            $(window).on('resize', function () {
                $('#points').html(build_points());
            }).resize();
            $('#points').delegate('div', 'click', click);
            $('#' + id_prefix + points[0].id).click();
        });
    });
}(window.jQuery));