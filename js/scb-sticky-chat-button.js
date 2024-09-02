(function ($) {
  $(document).ready(function () {
    $('#scb-sticky-chat-button').on('click', function (e) {
      e.preventDefault();
      var url = $(this).attr('href');
      window.open(url, '_blank');
    });
  });
})(jQuery);