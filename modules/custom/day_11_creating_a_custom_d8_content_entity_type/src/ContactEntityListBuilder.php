<?php

namespace Drupal\day_11_creating_a_custom_d8_content_entity_type;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Contact entity entities.
 *
 * @ingroup day_11_creating_a_custom_d8_content_entity_type
 */
class ContactEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Contact entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\day_11_creating_a_custom_d8_content_entity_type\Entity\ContactEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.contact_entity.edit_form',
      ['contact_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
