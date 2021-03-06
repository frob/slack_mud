<?php

namespace Drupal\kyrandia\Plugin\MudCommand;

use Drupal\node\NodeInterface;
use Drupal\slack_mud\MudCommandPluginInterface;

/**
 * Defines Gold command plugin implementation.
 *
 * @MudCommandPlugin(
 *   id = "kyrandia_gold",
 *   module = "kyrandia"
 * )
 *
 * @package Drupal\kyrnandia\Plugin\MudCommand
 */
class Gold extends KyrandiaCommandPluginBase implements MudCommandPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function perform($commandText, NodeInterface $actingPlayer, array &$results) {
    $profile = $this->gameHandler->getKyrandiaProfile($actingPlayer);
    $loc = $actingPlayer->field_location->entity;
    $gold = $profile->field_kyrandia_gold->value;
    $results[$actingPlayer->id()][] = sprintf($this->gameHandler->getMessage('GLDCNT'), $gold, $gold == 1 ? '' : 's');
    $othersMessage = t(':actor is counting :possessive gold.', [
      ':actor' => $actingPlayer->field_display_name->value,
      ':possessive' => $profile->field_kyrandia_is_female->value ? 'her' : 'his',
    ]);
    $this->gameHandler->sendMessageToOthersInLocation($actingPlayer, $loc, $othersMessage, $results);
  }

}
