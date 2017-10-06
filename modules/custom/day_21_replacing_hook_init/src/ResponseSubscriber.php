<?php

namespace Drupal\day_21_replacing_hook_init;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseSubscriber implements EventSubscriberInterface{

  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => 'onResponse'
    ];
  }


  public function onResponse(FilterResponseEvent $event) {

    if (\Drupal::currentUser()->isAnonymous()) {
      $response = $event->getResponse();
      $response->headers->set('Access-Control-Allow-Origin', '*');
    }

  }

}