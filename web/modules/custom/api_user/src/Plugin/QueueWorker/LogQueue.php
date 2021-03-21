<?php

namespace Drupal\api_user\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Plugin implementation of the log_queue queueworker.
 *
 * @QueueWorker (
 *   id = "log_queue",
 *   title = @Translation("Queue description."),
 * )
 */
class LogQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    \Drupal::logger('api_user')->warning('Current iteration: ' . $data);
    // Process item operations.
  }

}
