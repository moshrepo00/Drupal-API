<?php

namespace Drupal\api_chat\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Chat entity entities.
 */
class ChatEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
