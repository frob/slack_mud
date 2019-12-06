<?php

namespace Drupal\kyrandia\Plugin\MudCommand;

use Drupal\node\NodeInterface;
use Drupal\slack_mud\MudCommandPluginInterface;

/**
 * Defines Believe command plugin implementation.
 *
 * @MudCommandPlugin(
 *   id = "kyrandia_believe",
 *   module = "kyrandia"
 * )
 *
 * @package Drupal\kyrnandia\Plugin\MudCommand
 */
class Believe extends KyrandiaCommandPluginBase implements MudCommandPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function perform($commandText, NodeInterface $actingPlayer) {
    // Players can dig in the brook to randomly find gold.
    $result = NULL;
    $loc = $actingPlayer->field_location->entity;
    if ($loc->getTitle() == 'Location 257') {
      if ($commandText == 'believe in magic') {
        $profile = $this->getKyrandiaProfile($actingPlayer);
        if ($profile->field_kyrandia_level->entity->getName() == '20') {
          if ($this->advanceLevel($profile, 21)) {
            $result = $this->getMessage('LEVL21');
          }
        }
      }
    }
    elseif ($loc->getTitle() == 'Location 293') {
      if ($commandText == 'believe in fantasy') {
        $profile = $this->getKyrandiaProfile($actingPlayer);
        if ($profile->field_kyrandia_level->entity->getName() == '23') {
          if ($this->advanceLevel($profile, 24)) {
            $result = $this->getMessage('LEVL24');
          }
        }
      }
    }
    if (!$result) {
      $result = 'Nothing happens.';
    }
    return $result;
  }

  /**
   * Handles aiming the wand at the tree.
   *
   * @param string $commandText
   *   The command text.
   * @param \Drupal\node\NodeInterface $actingPlayer
   *   The player.
   *
   * @return string
   *   The result.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function crystalTree(string $commandText, NodeInterface $actingPlayer) {
    $result = NULL;
    $words = explode(' ', $commandText);
    // Word 0 has to be 'aim', otherwise we wouldn't be here.
    // We're looking for 'aim wand at tree'.
    $itemPos = array_search('wand', $words);
    $targetPos = array_search('tree', $words);
    if ($itemPos !== FALSE && $targetPos !== FALSE && $itemPos < $targetPos) {
      $profile = $this->getKyrandiaProfile($actingPlayer);
      if ($profile->field_kyrandia_level->entity->getName() == '10' && $this->playerHasItem($actingPlayer, 'wand')) {
        if ($this->advanceLevel($profile, '11')) {
          $result = "As you aim the wand at the crystal tree, there's a flash of silver light!\n***\nYou are now at level 11!";
        }
      }
    }
    if (!$result) {
      $result = "For some reason, nothing happens at all!";
    }
    return $result;
  }

}