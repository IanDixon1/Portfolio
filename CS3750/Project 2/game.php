<?php
require_once("core/bootstrap.php");
require_once("model/Game.php");
require_once("model/Card.php");

$game = NULL;

// find current game, or join a new one
if (isset($_SESSION['gameID'])) {
  $game = Game::find($_SESSION['gameID']);
}

if ($game == NULL) {
  $game = Game::join();
}
?>

<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8" />
  <title>Egyptian Rat Screw</title>

  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/game.css" />
</head>

<body>
  <audio autoplay loop preload>
    <source src="audio/music.ogg" type="audio/ogg" autoplay />
  </audio>

  <form action="reset.php" method="get">
    <button type="submit">Reset session</button>
  </form>

  <button type="button" id="tickButton">Update game state</button>
  <button type="button" id="slapButton">Slap Test</button>

  <div class="container">
    <?php if ($game != NULL): ?>
    <div id="game"></div>
    <?php else: ?>
    <h1>Unable to load game</h1>
    <?php endif; ?>
  </div>

  <?php if ($game != NULL): ?>
  <script type="text/javascript" src="js/Swal.js"></script>
  <script type="module">
    import { Game } from './js/Game.js';

    let game = new Game("game");
  </script>
  <?php endif; ?>
</body>
</html>
