<?php

namespace Drupal\api_chat;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\api_chat\Entity\ChatEntityInterface;

/**
 * Defines the storage handler class for Chat entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Chat entity entities.
 *
 * @ingroup api_chat
 */
interface ChatEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Chat entity revision IDs for a specific Chat entity.
   *
   * @param \Drupal\api_chat\Entity\ChatEntityInterface $entity
   *   The Chat entity entity.
   *
   * @return int[]
   *   Chat entity revision IDs (in ascending order).
   */
  public function revisionIds(ChatEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Chat entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Chat entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

}
