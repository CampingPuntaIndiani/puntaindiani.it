/**
 * author: martin.brugnara@gmail.com 
 * since: 2014
 * license: 3-BSD
 */

"use strict";
(function($){
  $(function(){
    // we try to fix people stupidity
    $('[data-autofix=date]').on('change', function () {
      var input = $(this),
        date_str = input.val(),
        date_a = date_str.split(/[^0-9]/),
        ndate_a = [],
        error = false,
        curr_year = new Date().getFullYear();

      if (date_a.length === 2) {
        // we suppose day/month
        ndate_a = [curr_year, date_a[1], date_a[0]];
      } else if (date_a.length === 3) {
        if (date_str.indexOf('-') !== -1) {
          // we suppose year-month-day
          // nothing to do
          ndate_a = date_a;
        } else {
          // we suppose day/month/year
          ndate_a = date_a.reverse();
        }
      } else {
        // invalid data
        error = true;
      }

      // expand year (if required)
      if (!error && ndate_a[0].length <= 2) {
        ndate_a[0] = parseInt(ndate_a[0]);
        if (ndate_a[0] < curr_year % 100) {
          ndate_a[0] += Math.floor(curr_year / 100) * 100;
        } else {
          ndate_a[0] += (Math.floor(curr_year / 100) - 1) * 100;
        }
      }

      date_str = $.map(ndate_a, function(e){
        if ((e+'').length === 1) {
          return '0'+e;
        } else {
          return ''+e;
        }
      }).join('-');

      // validating new Data
      var ndate = new Date(date_str);
      error = isNaN(ndate.getDate()); 

      if (!error) {
        // set the celaned value
        input.val(date_str);

        // validate range
        var min = new Date(input.attr('min')),
            max = new Date(input.attr('max'));
        if (!isNaN(min.getDate())) {
          error = min > ndate;
        }

        if (!isNaN(max.getDate())) {
          error = error || max < ndate;
        }
      } else {
        // invalid data struct -- clean up
        input.val(''); 
      }

      if (error) {
        // not valid
        input.parents('.control-group').addClass('error');
      } else {
        // valid
        input.parents('.control-group').removeClass('error');
      }
    });
  });
})(jQuery);
