<?php

namespace Drupal\kyrandia\EventSubscriber;

use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\slack_mud\Event\LookAtPlayerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MudEventSubscriber.
 */
class MudEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[LookAtPlayerEvent::LOOK_AT_PLAYER_EVENT] = [
      'onLookAtPlayer',
      600,
    ];
    return $events;
  }

  /**
   * Subscriber for the MudEvent LookAtPlayer event.
   *
   * @param \Drupal\slack_mud\Event\LookAtPlayerEvent $event
   *   The LookAtPlayer event.
   */
  public function onLookAtPlayer(LookAtPlayerEvent $event) {
    $targetPlayer = $event->getTargetPlayer();
    $kyrandiaProfile = $this->getKyrandiaProfile($targetPlayer);
    if ($kyrandiaProfile) {
      $level = $kyrandiaProfile->field_kyrandia_level->entity;
      $desc = $level->field_male_description->value;
      $event->setResponse(strip_tags($desc));
    }
  }

  /**
   * @param \Drupal\node\NodeInterface $targetPlayer
   *
   * @return NodeInterface|null
   *   The player's Kyrandia profile node.
   */
  protected function getKyrandiaProfile(NodeInterface $targetPlayer) {
    $kyrandiaProfile = NULL;
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'kyrandia_profile')
      ->condition('field_player.target_id', $targetPlayer->id());
    $kyrandiaProfileNids = $query->execute();
    if ($kyrandiaProfileNids) {
      $kyrandiaProfileNid = reset($kyrandiaProfileNids);
      $kyrandiaProfile = Node::load($kyrandiaProfileNid);
    }
    return $kyrandiaProfile;
  }

}
