<?php

namespace Drupal\social_custom_links\EventSubscriber;

use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Class RedirectSubscriber.
 *
 * @package Drupal\social_group\EventSubscriber
 */
class RedirectSubscriber implements EventSubscriberInterface {

  /**
   * Get the request events.
   *
   * @return mixed
   *   Returns request events.
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['checkForRedirection'];
    return $events;
  }

  /**
   * This method is called when the KernelEvents::REQUEST event is dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The event.
   */
  public function checkForRedirection(GetResponseEvent $event) {
    // Check if there is a group object on the current route
    $current_group = _social_custom_links_get_current_group();
    // Check if there is a group object on the frontpage route
    $frontpage_group = _social_custom_links_get_frontpage_group();
    // Get the current route name for the checks being performed below
    $routeMatch = \Drupal::routeMatch()->getRouteName();
    // Get the current user
    $current_user = \Drupal::currentUser();
    // The array of forbidden routes
    $group_routes = [
      'entity.group.canonical',
      'entity.group.join',
      'view.group_events.page_group_events',
      'view.group_topics.page_group_topics',
      'view.group_members.page_group_members',
      'view.domain_groups.page_domain_groups'
    ];
    $group_types = [
      'community',
      'open_group',
      'closed_group'
    ];

    // if not superadmin
    if ($current_user->id() != 1) {
      // If a group is set, and the type is in list
      if ($current_group && in_array($current_group->getGroupType()->id(), $group_types)) {
          // if manager or member of community
          if ($current_user->hasPermission('manage all groups') || $frontpage_group->getMember($current_user)) {
            if ($routeMatch == 'entity.group.canonical') {
              $current_group_domain_name = '';
              $isSubgroup = FALSE;

              // Get current group domain name using Domain Access field (Not Community Group)
              if (!$current_group->field_short_name) {
                foreach ($current_group->domain_access as $domain_access) {
                  if ($domain_access->entity) {
                    if ($domain_access->entity->id() == 'lovin_community') {
                      $current_group_domain_name = 'lovin';
                    }
                    else {
                      list($current_group_domain_name) = explode('_lovin_community',$domain_access->entity->id());
                    }
                    $isSubgroup = TRUE;
                    break;
                  }
                }
              }
              else {
                // Get current group domain name using Short Name (Community Group)
                $current_group_domain_name = $current_group->field_short_name->getString();
              }
              // Get frontpage group domain name
              $frontpage_group_domain_name = $frontpage_group->field_short_name->getString();

              if ($current_group == $frontpage_group || $current_group_domain_name == $frontpage_group_domain_name) {
                return;
              }
              // redirect to group's domain
              else {
                // Get current domain name
                $httphost = $_SERVER['HTTP_HOST'];
                // Strip subdomain from current domain, if not lovin base domain
                $base_domain = $frontpage_group_domain_name == 'lovin' ? $httphost : str_replace($frontpage_group_domain_name . '.','',$httphost);
                // Set this Groups correct domain based on Current Domain Community's corresponding domain name
                $group_domain = $current_group_domain_name == 'lovin' ? $base_domain : $current_group_domain_name . '.' . $base_domain;
                // Add http prefix
                $group_domain_url = 'https://' . $group_domain;
                // Set as Trusted Domain
                $response = new TrustedRedirectResponse($group_domain_url . ($isSubgroup ? '/group/' . $current_group->id() . '/stream' : ''));
                // Set Redirect to correct domain
                $event->setResponse($response);
              }
            }
            else {
              return;
            }
          }
          // If the user is not an member of this group, current route is on list of group routes we are concerned with and user is not anonymous
          elseif (!$current_group->getMember($current_user) && in_array($routeMatch, $group_routes) && !$current_user->isAnonymous()) {
            // redirect to group about page
            $event->setResponse(new RedirectResponse(Url::fromRoute('view.group_information.page_group_about', ['group' => $current_group->id()])
            ->toString()));
          }
      }


      // Check if there is a user object on the current route
      $account = _social_custom_links_get_user_from_current_route();
      // If a user is set on the page
      if ($account) {
        //get list of groups for current and account user
        $group_memberships_current_user = _social_custom_links_get_all_group_members($current_user->id());
        $group_memberships_account_user = _social_custom_links_get_all_group_members($account->id());

        // Get any common groups
        $common_groups = array_intersect ($group_memberships_current_user, $group_memberships_account_user);

        // If current user has admin access to account or common groups with account user
        if ($current_user->hasPermission('view users') || !empty($common_groups)) {
          return;
        }
        // if viewing other account
        elseif ($current_user->id() !== $account->id()) {
          //redirect to frontpage
          $event->setResponse(new RedirectResponse(Url::fromRoute('<front>')
              ->toString()));
        }
      }
    }
  }
}
