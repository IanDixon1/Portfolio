<?php
require_once("core/bootstrap.php");
require_once("model/Game.php");

// send initial information to the client so we don't have to include more data
// than we need in the tick
if (isset($_SESSION['gameID'])) {
  $game = Game::find($_SESSION['gameID']);

  // see if there are two players
  $response = [
    "playerID" => $_SESSION["playerID"],
    "names" => [$game->players[0]->name, $game->players[1]->name]
  ];

  echo json_encode($response);
}
?>
