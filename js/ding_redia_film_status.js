/**!
 * This script uses the easy-pie-chart lightweight plugin to make the pie chart.
 *
 **/

(function ($) {
  "use strict";
  $(document).ready(function () {
    let barColor = '#000000';
    let trackColor = '#F2F2F2';
    let isEligible = Drupal.settings.dingRediaFilm.isEligible;
    console.log(isEligible);
    if (!isEligible) {
      barColor = '#AD313C';
      trackColor = '#AD313C';
    }
    $('.redia-film-status-chart').easyPieChart({
      barColor: barColor,
      scaleLength: 0,
      lineWidth: 10,
      trackColor: trackColor,
      lineCap: "round",
    });
  });

}(jQuery));
