<?php

namespace Drupal\api_user\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\node\Entity\Node;

/**
 * Class ExportController.
 */
class ExportController extends ControllerBase
{

  /**
   * Generatefile.
   *
   * @return array
   *   Return Hello string.
   */
  public function generateFile()
  {

    $articleNids = \Drupal::entityQuery('node')
      ->condition('type', 'article')
      ->sort('created', 'DESC')
      ->execute();


    $csvInput = "";
    $list = [];
    $list[0] = 'title,body';

    foreach ($articleNids as $nid) {
      /** @var Node $entity */
      $entity = Node::load($nid);
      $sanitizedTitle = str_replace(",", '&#44', $entity->getTitle());
      $sanitizedBody = str_replace(",", '&#44', $entity->body->value);
      $csvTitle = html_entity_decode($sanitizedTitle, ENT_QUOTES, 'UTF-8');
      $csvBody = html_entity_decode($sanitizedBody, ENT_QUOTES, 'UTF-8');
      $list[] = $csvTitle . ',' . $csvBody;
    }

    $path = PublicStream::basePath();
    $exportDir = $path . '/export';

    if (!is_dir($exportDir)) {
      mkdir($exportDir, 0755);
    }

    /** @var DrupalDateTime $currentDateObj */
    $currentDateObj = new DrupalDateTime('now');
    $currentTimestamp = $currentDateObj->format('Y-m-d\TH:i:s');

    $csvFilename = 'export_' . $currentTimestamp . '.csv';

    $csvHandler = fopen($exportDir . '/' . $csvFilename, 'w');


    foreach ($list as $line) {
      fputcsv($csvHandler, explode(',', $line));
    }
    fclose($csvHandler);


    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: generateFile')
    ];
  }

}
