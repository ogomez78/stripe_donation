<?php

namespace Drupal\stripe_donation\Controller;

use Stripe\Charge;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\stripe_api\StripeApiService;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class StripeChargeController.
 *
 * @package Drupal\stripe_donation\Controller
 */
class StripeChargeController extends ControllerBase {

  /**
   * Drupal\stripe_api\StripeApiService definition.
   *
   * @var \Drupal\stripe_api\StripeApiService
   */
  protected $stripeApiStripeApi;

  /**
   * {@inheritdoc}
   */
  public function __construct(StripeApiService $stripe_api_stripe_api) {
    $this->stripeApiStripeApi = $stripe_api_stripe_api;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('stripe_api.stripe_api')
    );
  }

  /**
   * Charge.
   *
   * @return string
   *
   */
  public function charge(Request $request) {

    try {
      $token = $request->get('stripeToken');
      $amount = $request->get('field_total_amount')['0']['value'];
      $donationamount = $request->get('field_donation_amount');
      $otheramount = $request->get('field_other_amount')['0']['value'];
      $countrycode = $request->get('field_address')['0']['address']['country_code'];
      $givenname = $request->get('field_address')['0']['address']['given_name'];
      $familyname = $request->get('field_address')['0']['address']['family_name'];
      $addressline = $request->get('field_address')['0']['address']['address_line1'];
      $locality = $request->get('field_address')['0']['address']['locality'];
      $administrativearea = $request->get('field_address')['0']['address']['administrative_area'];
      $postalcode = $request->get('field_address')['0']['address']['postal_code'];
      $email = $request->get('field_email')['0']['value'];

      if (!$token) {
        throw new \Exception("Token is missing!");
      }

      if (!$amount) {
        throw new \Exception("Amount is missing!");
      }

      $charge = Charge::create(array(
        // Convert to cents.
        "amount" => $amount * 100,
        "source" => $token,
        "currency" => "usd",
        "description" => "Donation via the '/node/add/donation' donation form by " . $givenname . " " . $familyname
      ));

      /**
      * When payment succeeds:
      * 1. Display a message
      * 2. Log the result to watchdog and trigger webhook
      * 3. Allow other modules to react to event
      * 4. Create the node
      * 5. Redirect to the donation form (ideally we would redirect to a 'Thank you' page instead with additional calls to action
      *    to further engage the donor).
      */
      if ($charge->paid === TRUE) {
        /* Configure the success message that shows up when the donation is received. */
        drupal_set_message(t("<h2>Thank You!</h2><p></p><p>Dear @fname,</p><p>Your $@amount donation has been received.</p><p>Warm regards,</p><p>My Nonprofit Organization</p>", array('@amount' => $amount, '@fname' => $givenname)));

        /**
        * At this point a Stripe webhook should make a request to the stripe_api.webhook route, which will dispatch an event
        * to which event subscribers can react.
        */
        $this->getLogger('stripe_donation.logger')->info(t("Successfully processed Stripe charge. This should have triggered a Stripe webhook. \nsubmitted data:@data", [
          '@data' => $request->getContent(),
        ]));

        // In addition to the webhook, we fire a traditional Drupal hook to permit other modules to respond to this event instantaneously.
        $this->moduleHandler()->invokeAll('stripe_checkout_charge_succeeded', [
          $charge
        ]);

        // Use the submitted values to create the donation node in Drupal.
        $data = array(
           'type' => 'donation',
           'field_total_amount' => $amount,
           'field_donation_amount' => $donationamount,
           'field_other_amount' => $otheramount,
           'field_stripe_token' => $token,
           'field_address' => array(
              'country_code' => $countrycode,
              'given_name' => $givenname,
              'family_name' => $familyname,
              'address_line1' => $addressline,
              'locality' => $locality,
              'administrative_area' => $administrativearea,
              'postal_code' => $postalcode
           ),
           'field_email' => $email
         );
         $node = Node::create($data);
         $node->save();

        // Redirect to the donation form.
        return new RedirectResponse('donation');
      }
      // Handle failed payments - Needs more work handling error messages.
      else {
        drupal_set_message(t("Payment failed."), 'error');

        $this->getLogger('stripe_donation.logger')->error(t("Could not complete Stripe charge. \nsubmitted data:@data", [
          '@data' => $request->getContent(),
        ]));

        return new Response(NULL, Response::HTTP_FORBIDDEN);
      }

    }
    // Handle exceptions - Needs more work handling error messages.
    catch (\Exception $e) {
      drupal_set_message(t("Payment failed."), 'error');

      $this->getLogger('stripe_donation.logger')->error(t("Could not complete Stripe charge, error:\n@error\nsubmitted data:@data", [
        '@data' => $request->getContent(),
        '@error' => $e->getMessage(),
      ]));

      return new Response(NULL, Response::HTTP_FORBIDDEN);
    }
  }
}
