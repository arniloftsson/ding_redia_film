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
   });
}(jQuery));
