<?php
require_once('core/bootstrap.php');
require_once('model/Game.php');

if (isset($_SESSION['gameID'])) {
  $game = Game::find($_SESSION['gameID']);
  $game->turn();
  echo $game->getStateForClient();
}
?>
