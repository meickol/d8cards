<?php

namespace Drupal\day_11_creating_a_custom_d8_content_entity_type\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Contact entity entity.
 *
 * @ingroup day_11_creating_a_custom_d8_content_entity_type
 *
 * @ContentEntityType(
 *   id = "contact_entity",
 *   label = @Translation("Contact entity"),
 *   handlers = {
 *     "storage" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\ContactEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\ContactEntityListBuilder",
 *     "views_data" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\Entity\ContactEntityViewsData",
 *     "translation" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\ContactEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\Form\ContactEntityForm",
 *       "add" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\Form\ContactEntityForm",
 *       "edit" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\Form\ContactEntityForm",
 *       "delete" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\Form\ContactEntityDeleteForm",
 *     },
 *     "access" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\ContactEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\day_11_creating_a_custom_d8_content_entity_type\ContactEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "contact_entity",
 *   data_table = "contact_entity_field_data",
 *   revision_table = "contact_entity_revision",
 *   revision_data_table = "contact_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer contact entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/contact_entity/{contact_entity}",
 *     "add-form" = "/admin/structure/contact_entity/add",
 *     "edit-form" = "/admin/structure/contact_entity/{contact_entity}/edit",
 *     "delete-form" = "/admin/structure/contact_entity/{contact_entity}/delete",
 *     "version-history" = "/admin/structure/contact_entity/{contact_entity}/revisions",
 *     "revision" = "/admin/structure/contact_entity/{contact_entity}/revisions/{contact_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/contact_entity/{contact_entity}/revisions/{contact_entity_revision}/revert",
 *     "translation_revert" = "/admin/structure/contact_entity/{contact_entity}/revisions/{contact_entity_revision}/revert/{langcode}",
 *     "revision_delete" = "/admin/structure/contact_entity/{contact_entity}/revisions/{contact_entity_revision}/delete",
 *     "collection" = "/admin/structure/contact_entity",
 *   },
 *   field_ui_base_route = "contact_entity.settings"
 * )
 */
class ContactEntity extends RevisionableContentEntityBase implements ContactEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the contact_entity owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Contact entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Contact entity entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Contact entity is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
