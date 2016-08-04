<?php

/**
 * @file
 * Contains \Drupal\drupalbase\EventSubscriber\KernelRequestSubscriber.
 */

namespace Drupal\drupalbase\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Subscribe to KernelEvents::REQUEST events and throw a 404 if content should not be accessed directly
 */
class KernelRequestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('disableDirectAccess');
    return $events;
  }

  /**
   * Disable all direct access of content set with the special disable-direct-access route alias
   * Only applies to anonymous users
   */
  public function disableDirectAccess(GetResponseEvent $event) {


  	$anonymous = \Drupal::currentUser()->isAnonymous();

  	if($anonymous===true && $event->isMasterRequest()) {
    	$current_path = \Drupal::service('path.current')->getPath();
    	$alias = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
    	$alias_parts = explode('/', trim($alias,'/'));
      // this content should not be viewed directly
    	if(!empty($alias_parts) && $alias_parts[0] == 'disable-direct-access') {
    		throw new NotFoundHttpException();
    	}
  	}

    // Redirect any pages with aliases to the alias
    $current_uri = \Drupal::request()->getRequestUri();
    $alias_uri = \Drupal::service('path.alias_manager')->getAliasByPath($current_uri);
    if ($current_uri !== $alias_uri) {
        $response = new Response();
        $response->headers->set('Location', $alias_uri);
        $response->setStatusCode(Response::HTTP_PERMANENTLY_REDIRECT);
        $event->setResponse($response);
    }
  }
}
