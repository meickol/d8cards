<?php
/**
 * Created by PhpStorm.
 * User: maicollopez
 * Date: 10/3/17
 * Time: 23:18
 */

namespace Drupal\day_18_events_subscribers;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SimplePageLoadSubscriber implements EventSubscriberInterface {


  public static function getSubscribedEvents() {
    return [
      'simple_page_load' => 'simplePageLoad'
      ];
  }

  public function simplePageLoad(){
    \Drupal::logger('Simple Page')->notice('Simple Page Loaded');
  }


}