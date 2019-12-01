<?php

namespace Drupal\slack_mud\Event;

use Drupal\node\NodeInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Base event for a command not handled by default slack_mud behavior.
 *
 * @package Drupal\slack_mud\Event
 */
class CommandEvent extends Event {

  const COMMAND_EVENT = 'slack_mud.command';

  /**
   * The player performing the action.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $actingPlayer;

  /**
   * The response of the action, in this case, the player's description.
   *
   * @var string
   */
  protected $response;

  /**
   * Command text entered in full, including targets or modifiers.
   *
   * For example, $commandString might be:
   *   "steal ruby from dragon"
   *
   * @var string
   */
  protected $commandString;

  /**
   * CommandEvent constructor.
   *
   * @param \Drupal\node\NodeInterface $acting_player
   *   The player performing the action.
   * @param string $command_string
   *   Command text entered in full, including targets or modifiers.
   */
  public function __construct(NodeInterface $acting_player, $command_string) {
    $this->actingPlayer = $acting_player;
    $this->commandString = $command_string;
  }

  /**
   * Gets the acting player node.
   *
   * @return \Drupal\node\NodeInterface
   *   The acting player node.
   */
  public function getActingPlayer() {
    return $this->actingPlayer;
  }

  /**
   * Command text entered in full, including targets or modifiers.
   *
   * @return string
   *   Command text entered in full, including targets or modifiers.
   */
  public function getCommandString(): string {
    return $this->commandString;
  }

  /**
   * Gets the current response text.
   *
   * @return string
   *   The current response text.
   */
  public function getResponse(): string {
    return $this->response;
  }

  /**
   * Sets new response text.
   *
   * @param string $response
   *   New response text.
   */
  public function setResponse(string $response) {
    $this->response = $response;
  }

}