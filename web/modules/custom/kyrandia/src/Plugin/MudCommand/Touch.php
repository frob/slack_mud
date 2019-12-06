<?php

namespace Drupal\kyrandia\Plugin\MudCommand;

use Drupal\node\NodeInterface;
use Drupal\slack_mud\Event\CommandEvent;
use Drupal\slack_mud\MudCommandPluginInterface;

/**
 * Defines Touch command plugin implementation.
 *
 * @MudCommandPlugin(
 *   id = "kyrandia_touch",
 *   module = "kyrandia"
 * )
 *
 * @package Drupal\kyrnandia\Plugin\MudCommand
 */
class Touch extends KyrandiaCommandPluginBase implements MudCommandPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function perform($commandText, NodeInterface $actingPlayer) {
    $result = NULL;
    $loc = $actingPlayer->field_location->entity;
    $profile = $this->getKyrandiaProfile($actingPlayer);
    if ($loc->getTitle() == 'Location 188') {
      $result = $this->mistyRuins($actingPlayer, $commandText);
    }
    elseif ($loc->getTitle() == 'Location 34') {
      // Druid's circle.
      $words = explode(' ', $commandText);
      // We're looking for 'touch orb with sceptre'.
      $orbPosition = array_search('orb', $words);
      $sceptrePosition = array_search('sceptre', $words);
      if ($orbPosition !== FALSE && $sceptrePosition !== FALSE && $orbPosition < $sceptrePosition) {
        if ($this->takeItemFromPlayer($actingPlayer, 'sceptre')) {
          // Give the player a random spell from this list.
          $spells = [
            'chillou',
            'freezuu',
            'frostie',
            'frythes',
            'hotflas',
          ];
          $randomSpellKey = array_rand($spells);
          $spell = $spells[$randomSpellKey];
          $this->giveSpellToPlayer($actingPlayer, $spell);
          $result = "As you touch the scepter to the orb of light, it vanishes in a flash!\n
***\n
A spell has been added to your spellbook!";
        }
        else {
          $result = "Unfortunately, you don't have a scepter!";
        }
      }
    }
    if (!$result) {
      $result = 'Nothing happens.';
    }
    return $result;
  }

  /**
   * Touching the orb at the misty ruins.
   *
   * @param \Drupal\node\NodeInterface $actingPlayer
   *   The acting player.
   * @param string $commandText
   *   The command text.
   *
   * @return string
   *   The result.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function mistyRuins(NodeInterface $actingPlayer, $commandText) {
    $result = NULL;
    // Touch orb in misty ruins (188) teleports to druid's circle (34).
    // We're looking for 'touch orb'.
    $words = explode(' ', $commandText);
    $synonyms = [
      'orb',
    ];
    $synonymMatch = array_intersect($synonyms, $words);
    if ($synonymMatch) {
      $query = \Drupal::entityQuery('node')
        ->condition('type', 'location')
        ->condition('field_game.entity.title', 'kyrandia')
        ->condition('title', 'Location 34');
      $ids = $query->execute();
      if ($ids) {
        $id = reset($ids);
        $actingPlayer->field_location = $id;
        $actingPlayer->save();
        $result = "As you touch the orb, you are suddenly pulled through a magical portal...\n";

        // The result is LOOKing at the new location.
        $mudEvent = new CommandEvent($actingPlayer, 'look');
        $mudEvent = $this->eventDispatcher->dispatch(CommandEvent::COMMAND_EVENT, $mudEvent);
        $result .= $mudEvent->getResponse();
      }
    }
    return $result;
  }

}