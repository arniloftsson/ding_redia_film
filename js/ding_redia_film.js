(function ($) {
  "use strict";

  // The form is loaded via ajax so only after an ajax call can we set event on our cancel button.
  $(document).ajaxComplete(function (e, xhr, settings) {
    $('#redia-film-cancel-button').on('click', function (evt) {
      evt.preventDefault();
      $('body').removeClass('popupbar-is-open');
    });
    if ($('#js-redia-film-checked-out').length) {
      var url = $('#js-redia-film-checked-out').attr("data-film-url");
      window.location.pathname = url;
    }
    if ($('#popupbar-ding_redia_film').length) {
      $('.popupbar-close').mousedown(function () {
        var media = $("#redia-film-trailer-video").get(0);
        media.pause();
      });
    }
   });
}(jQuery));
