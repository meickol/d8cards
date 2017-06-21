<?php

namespace Drupal\day_05_drupal_8_block_system\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 *
 * @package Drupal\day_05_drupal_8_block_system\Controller
 */
class DefaultController extends ControllerBase {

  /**
   * Test.
   *
   * @return string
   *   Return Hello string.
   */
  public function test() {


    // Get blocks list
    $blocks = \Drupal::entityTypeManager()
      ->getStorage('block_content')
      ->loadByProperties(array('type' => 'stock_exchange_rate_card'));



      // Get field_symbol value.
      $block_symbol = $blocks[1]->field_symbol->value;
      //dump($blocks[1]->field_symbol);

    $admin_information = \Drupal::config('day_3_building_configuration_forms.my_config_module');

    dump($admin_information->get('gender'));

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Admin Name is @name; Admin shoe size is @shoe_size; Admin gender is @gender', ['@name' => $admin_information->get('name'),'@shoe_size' => $admin_information->get('shoe_size'),'@gender' => $admin_information->get('gender')]),
    ];
  }

}
