(function ($) {
  "use strict";

  // The form is loaded via ajax so only after an ajax call can we set event on our cancel button.
  $(document).ajaxComplete(function (e, xhr, settings) {
    $('#redia-film-cancel-button').on('click', function (evt) {
      evt.preventDefault();
      $('body').removeClass('popupbar-is-open');
    });
  });
}(jQuery));
