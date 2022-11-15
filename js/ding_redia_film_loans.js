/**!
 * Ajax script to update the number of loans on the menu item.
 *
 **/

(function ($) {
  "use strict";
  Drupal.behaviors.ding_redia_film = {
    attach: function (context) {
      $('ul.main-menu-third-level').once('redia-film-loan-count', function() {
        var url = Drupal.settings.dingRediaFilm.url;
        console.log(url);
        $.getJSON(url, function (data) {
          console.log(2);
          console.log(data);
          if (data !== null) {
            $('ul.main-menu-third-level li a.menu-item').each(function (element) {
              if ($(this).attr('href') == '/user/me/status-loans') {
                $(this).html(data);
              }
            });
          }
        });
      });
    }
  }

}(jQuery));
