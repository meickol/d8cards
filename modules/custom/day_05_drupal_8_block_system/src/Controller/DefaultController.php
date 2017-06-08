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
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: test'),
    ];
  }

}
