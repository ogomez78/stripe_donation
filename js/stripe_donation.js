/**
 * @file
 */

Drupal.behaviors.stripe_donation = {
  attach: function (context, settings) {


    (function ($) {
      // check 'other' option when 'other amount' is on focus
      $('#edit-field-other-amount-0-value').focus(function() {
        $('#edit-field-donation-amount-0').prop('checked', true);
      });

      // empty 'other amount' when an option is chosen and make 'other amount' required/not required
      // assign the value of the 'donation amount' field to the 'total amount' field if the other amount option is not selected
      $('.form-item-field-donation-amount input').change(function(e) {
        if($(this).val() != '0' ) {
          var amount = $(this).val();
          $('#edit-field-other-amount-0-value').val('');
          $('#edit-field-other-amount-0-value').prop('required', false);
          $('#edit-field-total-amount-0-value').val(amount);
        } else {
          $('#edit-field-other-amount-0-value').prop('required', true);
        }
      });

      // update the total amount field when the 'other amount' field is edited
      $('#edit-field-other-amount-0-value').on('change keyup paste', function() {
        if($(this).val() > 0) {
          var amount = $(this).val();
          $('#edit-field-total-amount-0-value').val(amount);
        }
      });

      // create an empty div to mount the card element on and handle card errors. See lines starting with card.mount('#card-element');
      $('#edit-field-address-wrapper').prepend('<label for="card-element">Credit or debit card</label><div id="card-element"></div><div id="card-errors" role="alert"></div>');

    }(jQuery));

    // Create a Stripe client
    // var stripe = Stripe('pk_test_6pLcZeiW8QethKumTyxQvUBG');
    var stripekey = drupalSettings.stripe_donation.donation.stripeid;
    var stripe = Stripe(stripekey);
    // alert(stripekey);

    // Create an instance of Elements
    var elements = stripe.elements();


    // Custom styling can be passed to options when creating an Element.
    var style = {
      base: {
        '::placeholder': {
          color: '#aab7c4'
        }
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
      }
    };

    // Create an instance of the card Element
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element`
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });

    // Handle form submission
    var form = document.getElementById('node-donation-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      stripe.createToken(card).then(function(result) {
        if (result.error) {
          // Inform the user if there was an error
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
        } else {
          // Send the token to your server
          stripeTokenHandler(result.token);
        }
      });
    });

    function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted as part of the node
      var form = document.getElementById('node-donation-form');
      var stripetoken = document.getElementById('edit-field-stripe-token-0-value')
      stripetoken.value = token.id;
      stripetoken.setAttribute('name', 'stripeToken');
      stripetoken.setAttribute('value', token.id);

      // Submit the form
      form.submit();
    }
  }
};
