<?php

namespace Drupal\slack_mud\Service;

use Drupal\node\NodeInterface;

/**
 * Interface MudGameHandlerServiceInterface.
 *
 * @package Drupal\slack_mud\Service
 */
interface MudGameHandlerServiceInterface {

  /**
   * Returns other player nodes who are in the same location.
   *
   * @param \Drupal\node\NodeInterface $location
   *   The location where the user is.
   * @param \Drupal\node\NodeInterface|null $actingPlayer
   *   The player looking in the room (to be excluded from list). If no player
   *   is specified, return all the players in the location.
   *
   * @return array|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   *   An array of players who are also in the same location.
   */
  public function otherPlayersInLocation(NodeInterface $location, NodeInterface $actingPlayer = NULL);

  /**
   * Returns all player nodes who are active in the specified game..
   *
   * @param \Drupal\node\NodeInterface $game
   *   The game..
   *
   * @return array|\Drupal\Core\Entity\EntityInterface[]|\Drupal\node\Entity\Node[]
   *   An array of players who are also in the same location.
   */
  public function allPlayersInGame(NodeInterface $game);

  /**
   * Checks if the player has the specified item.
   *
   * @param \Drupal\node\NodeInterface $player
   *   The player whose inventory we are checking.
   * @param string $targetItemName
   *   The item we're checking for.
   * @param bool $removeItem
   *   If TRUE, remove the item from the player's inventory when found.
   *
   * @return \Drupal\node\NodeInterface|bool
   *   FALSE if the player doesn't the item, otherwise the item in
   *   the player's inventory field.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function playerHasItem(NodeInterface $player, $targetItemName, $removeItem = FALSE);

  /**
   * Removes the named item from the player's inventory.
   *
   * @param \Drupal\node\NodeInterface $player
   *   The player.
   * @param string $targetItemName
   *   The name of the item.
   *
   * @return bool
   *   TRUE if the item was present and removed.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function takeItemFromPlayer(NodeInterface $player, $targetItemName);

  /**
   * Gives the named item to the specified player.
   *
   * @param \Drupal\node\NodeInterface $player
   *   The player.
   * @param string $itemName
   *   The item to give.
   *
   * @return bool
   *   TRUE if the item was given.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function giveItemToPlayer(NodeInterface $player, string $itemName);

  /**
   * Returns a location node from the location name.
   *
   * @param string $locationName
   *   The name of the location to load.
   *
   * @return \Drupal\node\NodeInterface
   *   The location node.
   */
  public function getLocationByName($locationName);

  /**
   * Returns a player node from the player display name.
   *
   * That player must be active in the game.
   *
   * @param string $playerName
   *   The name of the player to load.
   *
   * @return \Drupal\node\NodeInterface
   *   The player node.
   */
  public function getPlayerByName($playerName);

  /**
   * Puts the named item in the specified location.
   *
   * @param \Drupal\node\NodeInterface $location
   *   The location.
   * @param string $itemName
   *   The item to place.
   *
   * @return bool
   *   TRUE if the item was placed.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function placeItemInLocation(NodeInterface $location, string $itemName);

  /**
   * Does the specified target player exist in the specified location?
   *
   * @param string $target
   *   Partial player display name to look for.
   * @param \Drupal\node\NodeInterface $location
   *   Location node.
   * @param bool $excludeActingPlayer
   *   TRUE if we should exclude the acting player.
   * @param \Drupal\node\NodeInterface $actingPlayer
   *   The acting player, so we can exclude them if specified.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\node\Entity\Node|mixed|null
   *   The targeted player.
   */
  public function locationHasPlayer($target, NodeInterface $location, $excludeActingPlayer, NodeInterface $actingPlayer = NULL);

  /**
   * Does the specified target player exist in the specified game?
   *
   * @param string $target
   *   Partial player display name to look for.
   * @param \Drupal\node\NodeInterface $game
   *   Game node.
   *
   * @return \Drupal\Core\Entity\EntityInterface|\Drupal\node\Entity\Node|mixed|null
   *   The targeted player.
   */
  public function gameHasPlayer($target, NodeInterface $game);

  /**
   * Moves a player to the specified location.
   *
   * @param \Drupal\node\NodeInterface $player
   *   The player being moved.
   * @param string $locationName
   *   The name of the new location.
   * @param array $result
   *   The result array.
   * @param string $exitMessage
   *   Text sent to players in the room the player left.
   * @param string $entranceMessage
   *   Text sent to players in the room player entered.
   *
   * @return bool
   *   TRUE if the move was successful (location exists).
   */
  public function movePlayer(NodeInterface $player, $locationName, array &$result, $exitMessage, $entranceMessage);

  /**
   * Sends the specified message to each other player in the actor's location.
   *
   * @param \Drupal\node\NodeInterface $actingPlayer
   *   The player performing the action.
   * @param \Drupal\node\NodeInterface $loc
   *   The player's current location (usually - this could be a remotely
   *   targeted location).
   * @param string $othersMessage
   *   The message to show the players in the target location.
   * @param array $result
   *   The message results.
   * @param array $exceptPlayers
   *   Players in the location not to send the message to.
   */
  public function sendMessageToOthersInLocation(NodeInterface $actingPlayer, NodeInterface $loc, string $othersMessage, array &$result, array $exceptPlayers = []);

  /**
   * Gets human-readable items a player is holding.
   *
   * @param \Drupal\node\NodeInterface $player
   *   The player whose items we're looking at.
   *
   * @return string
   *   The human-readable stringified inventory.
   */
  public function playerInventoryString(NodeInterface $player);

  /**
   * Gets the item targetted in the command in the specified location.
   *
   * @param \Drupal\node\NodeInterface $location
   *   The location.
   * @param string $commandText
   *   The command text.
   * @param bool $removeItem
   *   If TRUE, remove the item from the location when found.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The item if found, otherwise NULL.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function locationHasItem(NodeInterface $location, $commandText, $removeItem = FALSE);

}
