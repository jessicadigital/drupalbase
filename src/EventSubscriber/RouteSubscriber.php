<?php
namespace Drupal\drupalbase\EventSubscriber;

use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RoutingEvents;
use Drupal\Core\Routing\RouteBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RouteSubscriber implements EventSubscriberInterface {

    /**
    * {@inheritdoc}
    */
    public static function getSubscribedEvents() {
        $events[RoutingEvents::ALTER] = 'alterRoutes';
        return $events;
    }

    /**
    * Alters existing routes.
    *
    * @param \Drupal\Core\Routing\RouteBuildEvent $event
    *   The route building event.
    */
    public function alterRoutes(RouteBuildEvent $event) {

        // Fetch the collection which can be altered.
        $collection = $event->getRouteCollection();
        // The event is fired multiple times so ensure that the user_page route
        // is available.

        if ($route = $collection->get('user.login')) {
            $route->setPath('/mylogin');
        }
        if ($route = $collection->get('user.logout')) {
            $route->setPath('/mylogout');
        }
        if ($route = $collection->get('view.frontpage.page_1')) {
            $route->setRequirement('_access', 'false');
        }
    }
}
