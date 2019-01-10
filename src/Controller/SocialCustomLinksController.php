<?php

namespace Drupal\social_custom_links\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Social Custom Links routes.
 */
class SocialCustomLinksController extends ControllerBase {

  /**
   * The _title_callback for the view.group_topics.page_group_topics route.
   *
   * @param object $group
   *   The group ID.
   *
   * @return string
   *   The page title.
   */
  public function groupTopicsTitle($group) {

    if (is_object($group)) {
      $group_type = $group->getGroupType()->id();
      $group_label = 'group';

      if ($group_type == 'community') {
        $group_label = $group->getGroupType()->label();
      }
    }
    else {
      $storage = \Drupal::entityTypeManager()->getStorage('group');
      $group_entity = $storage->load($group);
      $group_label = empty($group_entity) ? 'group' : $group_entity->getGroupType()->label();
    }

    return $this->t('Topics in our @name', ['@name' => $group_label]);
  }

  public function groupEventsTitle($group) {

    if (is_object($group)) {
      $group_type = $group->getGroupType()->id();
      $group_label = 'group';

      if ($group_type == 'community') {
        $group_label = $group->getGroupType()->label();
      }
    }
    else {
      $storage = \Drupal::entityTypeManager()->getStorage('group');
      $group_entity = $storage->load($group);
      $group_label = empty($group_entity) ? 'group' : $group_entity->getGroupType()->label();
    }

    return $this->t('Events in our @name', ['@name' => $group_label]);
  }

  public function groupAboutTitle($group) {

    if (is_object($group)) {
      $group_type = $group->getGroupType()->id();
      $group_label = 'group';

      if ($group_type == 'community') {
        $group_label = $group->getGroupType()->label();
      }
    }
    else {
      $storage = \Drupal::entityTypeManager()->getStorage('group');
      $group_entity = $storage->load($group);
      $group_label = empty($group_entity) ? 'group' : $group_entity->getGroupType()->label();
    }

    return $this->t('About our @name', ['@name' => $group_label]);
  }

}
