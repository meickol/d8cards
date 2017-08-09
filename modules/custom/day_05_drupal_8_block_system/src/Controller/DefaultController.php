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
   * @return array
   *   Return Hello string.
   */
  public function test() {

    // Get queue.
    $queue = \Drupal::queue('email_cron_queue');

    // Get user id
    $uid = 22;

    // add user id
    $item = (object) ['uid' => $uid];

    // Create item to queue.
    $queue->createItem($item);



    return [
      '#type' => 'markup',
      '#markup' => $this->t('Admin Name is @name;', ['@name' => $uid]),
    ];

  }

}
