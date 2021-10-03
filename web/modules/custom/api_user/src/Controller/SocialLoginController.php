<?php

namespace Drupal\api_user\Controller;

use \Drupal\Core\Site\Settings;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class SocialLoginController.
 */
class SocialLoginController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function googleLogin() {
    // init configuration
    $clientID = Settings::get('client_id');
    $clientSecret = Settings::get('client_secret');
    $redirectUri = Settings::get('redirect_uri');

// create Client Request to access Google API
    $client = new \Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");

// authenticate code from Google OAuth Flow
    if (isset($_GET['code'])) {
      $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
      $client->setAccessToken($token['access_token']);

      // get profile
      /** @var \Google\Service\Oauth2 $google_oauth */
      $google_oauth = new \Google_Service_Oauth2($client);
      $google_account_info = $google_oauth->userinfo->get();


      $email =  $google_account_info->email;
      $name =  $google_account_info->name;
      $id = $google_account_info->id;

      \Drupal::messenger()->addMessage('Your email is: ' . $email);
      \Drupal::messenger()->addMessage('Your name is: ' . $name);
      \Drupal::messenger()->addMessage('Your id is: ' . $id);
//      \Drupal::logger('api_user')->notice('<pre><code>' . print_r($google_oauth->a, TRUE) . '</code></pre>' );


      // now you can use this profile info to create account in your website and make user logged in.
    } 

    \Drupal::messenger()->addMessage($client->createAuthUrl());

    return [
      '#type' => 'markup',
      '#markup' => "<a href='".$client->createAuthUrl()."'>Google Login</a>",
    ];
  }

}
