/**!
 * Ajax script to update the number of loans on the menu item.
 *
 **/

(function ($) {
  "use strict";
  Drupal.behaviors.ding_redia_film = {
    attach: function (context) {
      $('ul.main-menu-third-level').once('header', function() {
        var url = Drupal.settings.dingRediaFilm.url;
        $.getJSON(url, function (data) {
          if (data !== null) {
            $('ul.main-menu-third-level li a.menu-item').each(function (element) {
              if ($(this).attr('href') == '/user/me/status-digital-loans') {
                $(this).html(data);
              }
            });
          }
        });
      });
    }
  }

}(jQuery));
