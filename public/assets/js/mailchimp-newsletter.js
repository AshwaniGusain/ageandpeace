(function($) {
  const $newsletterForm = $('#mc-embedded-subscribe-form');

  $newsletterForm.on('submit', function (e) {
    e.preventDefault();
    handleNewsletterSubscribe($newsletterForm);
  });

  function handleNewsletterSubscribe($form) {
    // TODO set loading state
    $.ajax({
      type: $form.attr('method'),
      url: $form.attr('action'),
      data: $form.serialize(),
      cache: false,
      dataType: 'json',
      contentType: 'application/json; charset=utf-8',
      error: function (err) { alert('Could not connect to the registration server. Please try again later.') },
      success: function (data) {

        if (data.result === 'success') {
          $('#subscribe-result').css('color', '#42A76E');
          $('#subscribe-result').html('<p>Thank you for subscribing. We have sent you a confirmation email.</p>')
          $('#mce-EMAIL').val(''); //reset input val
        } else {
          $('#subscribe-result').css('color', '#ff8282')
          $('#subscribe-result').html('<div><small>' + data.msg.replace('0 - ', '') + '</small></div>')
        }
      }
    })
  }
})(jQuery)
