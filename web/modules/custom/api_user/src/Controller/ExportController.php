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
    $currentTimestamp = $currentDateObj->getTimestamp();

    $fileDir = $exportDir . '/article_' . $currentTimestamp;
    if (!is_dir($fileDir)) {
      mkdir($fileDir, 0755);
    }
    $csvFilename = 'export.csv';

    $csvHandler = fopen($fileDir . '/' . $csvFilename, 'w');


    foreach ($list as $line) {
      fputcsv($csvHandler, explode(',', $line));
    }
    fclose($csvHandler);

//    $this->generateHtaccess($fileDir, $csvFilename);

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: generateFile')
    ];
  }

  protected function generateHtaccess($dirPath, $fileName)
  {
    $message = 'Authorization Required';
    $htpasswdPath = getcwd() . '/' . $dirPath . '/.htpasswd';
    if(!file_exists($dirPath . '/.htaccess')) {
      $content = "<Files $fileName>"  . "\n";
      $content .= "AuthName $message"  . "\n";
      $content .= "AuthUserFile $htpasswdPath"  . "\n";
      $content .= "AuthType Basic"  . "\n";
      $content .= "require valid-user"  . "\n";
      $content .= "</Files>";
      file_put_contents($dirPath . '/.htaccess', $content);


    }
  }

  protected function generateHtpasswd()
  {

  }

}
