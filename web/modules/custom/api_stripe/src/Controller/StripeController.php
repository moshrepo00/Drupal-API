<?php

namespace Drupal\api_stripe\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class StripeController.
 */
class StripeController extends ControllerBase {


  private $publishableKey = 'pk_test_51Hrj6OFxd5abxnyAg233yEzjsPs5MOwbcPusaTL1Q6emMsrWfe993PUYTTAGmk12xee1KmswyamiIT1FmHuGLr0Q00XEuYAnFt';
  private $secretKey;
  private $stripeApi;
  public function __construct()
  {
    $this->publishableKey = $_ENV['publishableKey'];
    $this->secretKey = $_ENV['secretKey'];
    $this->stripeApi = new \Stripe\StripeClient(
      $this->secretKey
    );
  }

  public function createCustomer() {

    $this->stripeApi->customers->create([
      'description' => 'PHP Customer test',
    ]);
  }

  public function createPaymentIntent() {
    $this->stripeApi->paymentIntents->create([
      'amount' => 2000,
      'currency' => 'hkd',
      'customer' => $_ENV['customerId'],
      'payment_method_types' => ['card'],
    ]);
  }

  public function updateCustomer() {
    $this->stripeApi->customers->update(
      $_ENV['customerId'], ['name' => 'MO TEST UPDATED']);
  }

  public function createSubscription() {
    $this->stripeApi->subscriptions->create([
      'customer' => $_ENV['customerId'],
      'items' => [
        ['price' => $_ENV['priceId']],
      ],
    ]);
  }

}
