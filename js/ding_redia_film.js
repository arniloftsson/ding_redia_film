(function ($) {
  "use strict";

  // The form is loaded via ajax so only after an ajax call can we set event on our cancel button.
  $(document).ajaxComplete(function (e, xhr, settings) {
    $('#redia-film-cancel-button').on('click', function (evt) {
      evt.preventDefault();
      $('body').removeClass('popupbar-is-open');
    });
    let checkedOut = $('#js-redia-film-checked-out');
    if (checkedOut.length) {
      window.location.pathname = checkedOut.attr("data-film-url");
    }
    if ($('#popupbar-ding_redia_film').length) {
      $('.popupbar-close').mousedown(function () {
        var media = $("#redia-film-trailer-video").get(0);
        media.pause();
      });
    }
   });
}(jQuery));
