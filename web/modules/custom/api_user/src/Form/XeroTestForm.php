<?php

namespace Drupal\api_user\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;

/**
 * Class XeroTestForm.
 */
class XeroTestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'xero_test_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
      'clientId'                => Settings::get('clientId'),
      'clientSecret'            => Settings::get('clientSecret'),
      'redirectUri'             => Settings::get('redirectUri'),
      'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
      'urlAccessToken'          => 'https://identity.xero.com/connect/token',
      'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Users'
    ]);

    // Scope defines the data your app has permission to access.
    // Learn more about scopes at https://developer.xero.com/documentation/oauth2/scopes
    $options = [
      'scope' => ['openid email profile offline_access accounting.settings accounting.transactions accounting.contacts accounting.journals.read accounting.reports.read accounting.attachments']
    ];

    // This returns the authorizeUrl with necessary parameters applied (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl($options);

    // Save the state generated for you and store it to the session.
    // For security, on callback we compare the saved state with the one returned to ensure they match.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit();
  }

}
