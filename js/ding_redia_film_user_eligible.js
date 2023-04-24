/**!
 * Ajax script to update user eligibility.
 *
 **/

(function ($) {
  "use strict";
  Drupal.behaviors.ding_redia_user_eligible = {
    attach: function (context) {
      $("header").once("header", function () {
        var url = Drupal.settings.dingRediaFilm.userEligibleUrl;
        $.getJSON(url);
      });
    },
  };
})(jQuery);
