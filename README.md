Stripe Donation

Provides a turnkey online donation solution based on Stripe.

Features
1. Allow the donor to select from a list of donation amounts or enter a custom value.
2. Track the successful transactions from Stripe in Drupal with a record of the donation. Donations are created as nodes in the 'Donation' content type.
3. Securely collect sensitive card details using Stripe Elements, their pre-built UI components. This significantly simplifies PCI compliance, while allowing you to place the card info inline inside the form. Learn more: https://stripe.com/docs/stripe-js
4. Administrators easily customize:
  - Donation amounts options
      - Change values of existing options or add new options
      - The 'Other' option must remain intact, as well as the 'Other Amount' field
  - The donation page introductory text by editing the 'Explanation or submission guidelines' setting of the 'Donation' content type at admin/structure/types/manage/donation
  - Field labels can be changed and field help text can be added if needed.

5. Use the Stripe API Drupal module to handle tokenization, API calls and webhook integration with Stripe. Benefits:
  - Administrators can easily add or remove Stripe accounts, toggle between test and live transactions and configure webhooks without having to edit the module's code.
  - Other modules can subscribe to the the stripe_api.webhook event or leverage hook_stripe_checkout_charge_succeeded()

6. A set of API keys come with the module to allow the developer to test donations right after enabling the module without additional steps.

Module Dependencies
- Stripe API - stripe_api - https://www.drupal.org/project/stripe_api
- Automatic Entity Label - auto_entitylabel - https://www.drupal.org/project/auto_entitylabel - Allows us to configure the node titles of the nodes created for each donation in this format: "$60 donation by Test Donor on Nov 8 2017, 11:43am"
- Token - token - https://www.drupal.org/project/token - Allows us to use tokens to configure automatic node titles for donation nodes.
- Address - address - https://www.drupal.org/project/address - Allows us to configure donor information fields in the Donation content type.

Installation with composer (I had an issue with this approach so I followed the Manual Instructions below instead)
1. Download the module into the 'modules/custom' directory
2. cd into the module directory: 'cd modules/custom/stripe_donation'
3. Run 'composer install' to install the module and its dependencies.
4. Go to admin/config/services/stripe_api and set up your Stripe API keys and other preferences. Notice that this module comes with a set of keys already installed so that you can easily test the functionality. To use these test keys, select the 'nnp_secret' one as the secret key and the 'nnp' one as the public key. You can remove those keys and add your own as needed.
5. To allow non-admin users to donate, enable the 'Donation: Create new content' permission for anonymous and authenticated users.

Manual Installation 
1. The stripe_api module requires that it be installed using composer. 'composer require drupal/stripe_api'. 
2. Install the other dependencies listed above.
3. Download the stripe_donation module into the 'modules/custom' directory.
4. Enable the stripe_donation module using 'drush en stripe_donation'.
5. Go to admin/config/services/stripe_api and set up your Stripe API keys and other preferences. Notice that this module comes with a set of keys already installed so that you can easily test the functionality. To use these test keys, select the 'nnp_secret' one as the secret key and the 'nnp' one as the public key. You can remove those keys and add your own as needed.
6. To allow non-admin users to donate, enable the 'Donation: Create new content' permission for anonymous and authenticated users.

Make a Donation
1. Go to 'node/add/donation' to make a donation.
2. Use Stripe's test credit card, 4242 4242 4242 4242, use any exp date in the future, any CVC and zip code.
3. Fill out the rest of the fields and submit your donation.
4. If successful, this donation will show up in the Stripe account you created, in test data: https://dashboard.stripe.com/test/payments, 
5. The donation record will also show up as an unpublished node of type 'donation' in your Drupal instance.

Content Type and Fields

The module will create one new content type called 'Donation' along with these custom fields:
  - field_donation_amount
  - field_address
  - field_email
  - field_other_amount
  - field_stripe_token
  - field_total_amount

Security Considerations
- This module allows you to test your donation form and Stripe integration over HTTP. However, live sites must use HTTPS to comply with Stripe's requirements for this type of integration.

Currency support

This module currently only supports transactions in USD.
