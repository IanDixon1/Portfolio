<?php
require("../core/bootstrap.php");
require("../core/auth.php");
?>

<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/game.css" />
  <title>Typing Game</title>
</head>

<body>
  <div class="container">
    <div id="game"></div>
  </div>

  <script type="text/javascript" src="js/Swal.js"></script>
  <script type="module">
    import { Game } from "./js/Game.js";

    new Game("game", "words.php", "HighScores.php");
  </script>
</body>
</html>
