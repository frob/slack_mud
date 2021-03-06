<?php

namespace Drupal\kyrandia\Plugin\MudCommand;

use Drupal\node\NodeInterface;
use Drupal\slack_mud\MudCommandPluginInterface;

/**
 * Defines SetGenderFemale command plugin implementation.
 *
 * This is called when the user joins the game from an interactive message.
 *
 * @MudCommandPlugin(
 *   id = "kyrandia_setgenderfemale",
 *   module = "kyrandia"
 * )
 *
 * @package Drupal\kyrnandia\Plugin\MudCommand
 */
class SetGenderFemale extends KyrandiaCommandPluginBase implements MudCommandPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function perform($commandText, NodeInterface $actingPlayer, array &$results) {
    $profile = $this->gameHandler->getKyrandiaProfile($actingPlayer);
    $profile->field_kyrandia_is_female = TRUE;
    $profile->save();

    $introResults = [];
    /** @var \Drupal\slack_mud\MudCommandPluginManager $pluginManager */
    $pluginManager = \Drupal::service('plugin.manager.mud_command');
    /** @var \Drupal\slack_mud\MudCommandPluginInterface $plugin */
    $plugin = $pluginManager->createInstance('kyrandia_joingamefirsttimeintrodisplay');
    $plugin->perform('joingamefirsttimeintrodisplay', $actingPlayer, $introResults);
    $results[$actingPlayer->id()] = $introResults[$actingPlayer->id()];
  }

}
