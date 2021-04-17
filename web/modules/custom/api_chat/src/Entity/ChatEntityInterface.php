<?php

namespace Drupal\api_chat\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Chat entity entities.
 *
 * @ingroup api_chat
 */
interface ChatEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Chat entity name.
   *
   * @return string
   *   Name of the Chat entity.
   */
  public function getName();

  /**
   * Sets the Chat entity name.
   *
   * @param string $name
   *   The Chat entity name.
   *
   * @return \Drupal\api_chat\Entity\ChatEntityInterface
   *   The called Chat entity entity.
   */
  public function setName($name);

  /**
   * Gets the Chat entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Chat entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Chat entity creation timestamp.
   *
   * @param int $timestamp
   *   The Chat entity creation timestamp.
   *
   * @return \Drupal\api_chat\Entity\ChatEntityInterface
   *   The called Chat entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Chat entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Chat entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\api_chat\Entity\ChatEntityInterface
   *   The called Chat entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Chat entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Chat entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\api_chat\Entity\ChatEntityInterface
   *   The called Chat entity entity.
   */
  public function setRevisionUserId($uid);

}
