<?php

namespace Drupal\kyrandia\Plugin\MudCommand;

use Drupal\node\NodeInterface;
use Drupal\slack_mud\MudCommandPluginInterface;

/**
 * Defines Walk command plugin implementation.
 *
 * @MudCommandPlugin(
 *   id = "kyrandia_walk",
 *   module = "kyrandia"
 * )
 *
 * @package Drupal\kyrnandia\Plugin\MudCommand
 */
class Walk extends KyrandiaCommandPluginBase implements MudCommandPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function perform($commandText, NodeInterface $actingPlayer) {
    // Players can dig in the brook to randomly find gold.
    $result = NULL;
    $loc = $actingPlayer->field_location->entity;
    $profile = $this->getKyrandiaProfile($actingPlayer);
    if ($loc->getTitle() == 'Location 19') {
      $words = explode(' ', $commandText);
      $synonyms = [
        'thicket',
      ];
      $synonymMatch = array_intersect($synonyms, $words);
      if ($synonymMatch) {
        // If player walks through thicket, they are damaged for 10 hp.
        $result = $this->damagePlayer($actingPlayer, 10);
        if (!$result) {
          $result = t("Ouch!");
        }
      }
    }
    if (!$result) {
      $result = 'Nothing happens.';
    }
    return $result;
  }

}
