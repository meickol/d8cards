<?php

namespace Drupal\day_3_building_configuration_forms\Forms;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigForm.
 *
 * @package Drupal\day_3_building_configuration_forms\Forms
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['day_3_building_configuration_forms.my_config_module'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'day_3_building_configuration_forms_my_config_module';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('day_3_building_configuration_forms.my_config_module');

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $config->get('name'),
      '#required' => TRUE,
    ];

    $options = [9, 10, 11, 12];
    $form['shoe_size'] = [
      '#type' => 'select',
      '#title' => $this->t('Shoe Size'),
      '#default_value' => $config->get('shoe_size'),
      '#options' => $options,
      '#required' => TRUE,
    ];

    $gender_options = [
      'F' => $this->t('F'),
      'M' => $this->t('M'),
      'Other' => $this->t('Other'),
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => $gender_options,
      '#default_value' => $config->get('gender'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->config('day_3_building_configuration_forms.my_config_module')
      ->set('name', $form_state->getValue('name'))
      ->set('shoe_size', $form_state->getValue('shoe_size'))
      ->set('gender', $form_state->getValue('gender'));

    $config->save();

    drupal_set_message($this->t('Your configurations have been saved.'));

    parent::submitForm($form, $form_state);
  }

}
