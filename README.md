Stripe Donation

Provides a turnkey online donation solution based on Stripe.

Features:

1. Allow the donor to select from a list of donation amounts or enter a custom value.
2. Track the successful transactions from Stripe in Drupal with a record of the donation.
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


Security Considerations:
- This module allows you to test your donation form and Stripe integration over HTTP. However, live sites must use HTTPS to comply with Stripe's requirements for this type of integration.

Module Dependencies:
- Stripe API - stripe_api - https://www.drupal.org/project/stripe_api
- Automatic Entity Label - auto_entitylabel - https://www.drupal.org/project/auto_entitylabel - Allows us to configure the node titles of the nodes created for each donation in this format: "$60 donation by Test Donor on Nov 8 2017, 11:43am"
- Token - token - https://www.drupal.org/project/token - Allows us to use tokens to configure automatic node titles for donation nodes.
- Address - address - https://www.drupal.org/project/address - Allows us to configure donor information fields in the Donation content type.

Installation
1. Clone the module's repo: git@github.com:ogomez78/stripe_donation.git and place it in the 'modules/custom' directory
2. Run "composer install" in the module folder
3. Enable the module
4. The module will create one new content type called 'Donation' along with these custom fields:
  - field_donation_amount
  - field_address
  - field_email
  - field_other_amount
  - field_stripe_token
  - field_total_amount

5. Go to admin/config/services/stripe_api and set up your Stripe API keys and other preferences. Notice that this module comes with a set of keys already installed so that you can easily test the functionality. You can remove those and add your own as needed.

Currency support
This module currently only supports transactions in USD.
