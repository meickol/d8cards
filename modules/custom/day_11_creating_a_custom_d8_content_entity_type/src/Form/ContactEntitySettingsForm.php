<?php

namespace Drupal\day_11_creating_a_custom_d8_content_entity_type\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ContactEntitySettingsForm.
 *
 * @package Drupal\day_11_creating_a_custom_d8_content_entity_type\Form
 *
 * @ingroup day_11_creating_a_custom_d8_content_entity_type
 */
class ContactEntitySettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'ContactEntity_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * Defines the settings form for Contact entity entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['ContactEntity_settings']['#markup'] = 'Settings form for Contact entity entities. Manage field settings here.';
    return $form;
  }

}
