<?php

namespace Drupal\social_custom_links\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber.
 *
 * @package Drupal\social_custom_links\Routing
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {

    // Override default title for Groups "Topics" page.
    if ($route = $collection->get('view.group_topics.page_group_topics')) {
      $defaults = $route->getDefaults();
      $defaults['_title_callback'] = '\Drupal\social_custom_links\Controller\SocialCustomLinksController::groupTopicsTitle';
      $route->setDefaults($defaults);
    }

    // Override default title for Groups "Events" page.
    if ($route = $collection->get('view.group_events.page_group_events')) {
      $defaults = $route->getDefaults();
      $defaults['_title_callback'] = '\Drupal\social_custom_links\Controller\SocialCustomLinksController::groupEventsTitle';
      $route->setDefaults($defaults);
    }

    // Override default title for Groups "Events" page.
    if ($route = $collection->get('view.group_information.page_group_about')) {
      $defaults = $route->getDefaults();
      $defaults['_title_callback'] = '\Drupal\social_custom_links\Controller\SocialCustomLinksController::groupAboutTitle';
      $route->setDefaults($defaults);
    }
 }

}
