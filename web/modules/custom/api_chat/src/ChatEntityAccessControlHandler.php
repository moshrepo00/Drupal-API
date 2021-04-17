<?php

namespace Drupal\api_chat;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Chat entity entity.
 *
 * @see \Drupal\api_chat\Entity\ChatEntity.
 */
class ChatEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\api_chat\Entity\ChatEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished chat entity entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published chat entity entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit chat entity entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete chat entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add chat entity entities');
  }


}
