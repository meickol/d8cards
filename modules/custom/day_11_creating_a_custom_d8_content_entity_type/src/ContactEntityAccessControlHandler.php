<?php

namespace Drupal\day_11_creating_a_custom_d8_content_entity_type;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Contact entity entity.
 *
 * @see \Drupal\day_11_creating_a_custom_d8_content_entity_type\Entity\ContactEntity.
 */
class ContactEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\day_11_creating_a_custom_d8_content_entity_type\Entity\ContactEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished contact entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published contact entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit contact entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete contact entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add contact entity entities');
  }

}
