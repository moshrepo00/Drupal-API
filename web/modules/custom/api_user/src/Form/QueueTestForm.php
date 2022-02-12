<?php

namespace Drupal\api_user\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class QueueTestForm.
 */
class QueueTestForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'queue_test_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
      \Drupal::messenger()->addMessage('FORM BUILT!!!!');
    $form['#attached']['library'][] = 'api_user/google-functionality';

//    $form['captcha_button'] = array(
//      '#type' => 'button',
//      '#value' => t('Alternative Action'),
//      '#attributes' => [
//        'class' => ['g-recaptcha'],
//        'data-sitekey' => ['6LccwHMeAAAAALka668fPwunJsV8LtcfxskNmEWu'],
//        'data-callback' => ['onSubmit'],
//        'data-action' => ['submit'],
//      ],
//    );
    $form['submit'] = [
      '#type' => 'submit',
      '#prefix' => '<div class="g-recaptcha" data-sitekey="6LccwHMeAAAAALka668fPwunJsV8LtcfxskNmEWu"></div>',
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
    // Display result.
    // TODO remove comments when testing queue
//    $queue = \Drupal::queue('log_queue');
//    for ($i = 0; $i < 10; $i++) {
//      $queue->createItem($i);
//    }
//    foreach ($form_state->getValues() as $key => $value) {
//      \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format'?$value['value']:$value));
//    }
  }

}
