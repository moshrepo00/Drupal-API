<?php

namespace Drupal\api_chat;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class ChatEntityStorage extends SqlContentEntityStorage implements ChatEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ChatEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {chat_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {chat_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

}
