Stripe Donation

Provides a turnkey online donation solution based on Stripe.

FEATURES
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

MODULE DEPENDENCIES
- Stripe API - stripe_api - https://www.drupal.org/project/stripe_api
- Automatic Entity Label - auto_entitylabel - https://www.drupal.org/project/auto_entitylabel - Allows us to configure the node titles of the nodes created for each donation in this format: "$60 donation by Test Donor on Nov 8 2017, 11:43am"
- Token - token - https://www.drupal.org/project/token - Allows us to use tokens to configure automatic node titles for donation nodes.
- Address - address - https://www.drupal.org/project/address - Allows us to configure donor information fields in the Donation content type.

INSTALLATION WITH COMPOSER (I had an issue with this approach so I followed the Manual Instructions below instead)
1. Download the module into the 'modules/custom' directory
2. cd into the module directory: 'cd modules/custom/stripe_donation'
3. Run 'composer install' to install the module and its dependencies.
4. Go to admin/config/services/stripe_api and set up your Stripe API keys and other preferences. Notice that this module comes with a set of keys already installed so that you can easily test the functionality. To use these test keys, select the 'nnp_secret' one as the secret key and the 'nnp' one as the public key. You can remove those keys and add your own as needed.
5. To allow non-admin users to donate, enable the 'Donation: Create new content' permission for anonymous and authenticated users.

MANUAL INSTALLATION
1. The stripe_api module requires that it be installed using composer. 'composer require drupal/stripe_api'. 
2. Install the other dependencies listed above.
3. Download the stripe_donation module into the 'modules/custom' directory.
4. Enable the stripe_donation module using 'drush en stripe_donation'.
5. Go to admin/config/services/stripe_api and set up your Stripe API keys and other preferences. Notice that this module comes with a set of keys already installed so that you can easily test the functionality. To use these test keys, select the 'nnp_secret' one as the secret key and the 'nnp' one as the public key. You can remove those keys and add your own as needed.
6. To allow non-admin users to donate, enable the 'Donation: Create new content' permission for anonymous and authenticated users.

MAKE A DONATION
1. Go to 'node/add/donation' to make a donation.
2. Use Stripe's test credit card, 4242 4242 4242 4242, use any exp date in the future, any CVC and zip code.
3. Fill out the rest of the fields and submit your donation.
4. If successful, this donation will show up in the Stripe account you created, in test data: https://dashboard.stripe.com/test/payments, 
5. The donation record will also show up as an unpublished node of type 'donation' in your Drupal instance.

CONTENT TYPE AND FIELDS
- Installing the module automatically creates a new content type called 'Donation' along with these custom fields:
  - field_donation_amount
  - field_address
  - field_email
  - field_other_amount
  - field_stripe_token
  - field_total_amount

SECURITY CONSIDERATIONS
- This module allows you to test your donation form and Stripe integration over HTTP. However, live sites must use HTTPS to comply with Stripe's requirements for this type of integration.

CURRENCY SUPPORT
-This module currently only supports transactions in USD.

NEXT STEPS / IDEAS

This is a very raw first draft approach to what I'd like to do with a donation page for a nonprofit organization. A lot more can be done to improve their conversion rates and allow their team to further engage with their donors. For starters: 
- Enable recurring donations
- Split the form into a javascript or ajax-based two step form where the donor first makes the decision to donate with a simple 'select amount' screen, and then they enter their card and personal info to complete the payment. Similar to https://www.charitywater.org/donate?variant=amount-buttons
- Use Drupal rules to send the donor an email receipt of their donation and notify the fundraising team of the donation. 
- Use Drupal Views to provide the fundraising team with a dashboard of their fundraising results, allowing them to filter by date, state, city, amount range; and allow them to download their results as a csv file for further analysis.

What was hard
I chose to meet both the guidelines and the 'extra credits' provided in the scope of this assignment, even if that meant having a couple of extra steps in the installation process. I took this approach because those items listed in the extra steps would probably feel more like must-haves rather than optional to a nonprofit organization based on my experience creating similar solutions.

What was easy
Crafting a solution that enables the client to increase their conversion rates is what I love doing, so building this was fun!
