<?php
require("core/bootstrap.php");

if (isset($_POST['playerName'])) {
  $_SESSION['playerName'] = $_POST['playerName'];

  redirect('game.php');
}
?>

<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8" />
  <title>Egyptian Rat Screw</title>

  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <div class="container">
    <form method="post">
      <label for="playerName">What is your name?</label>
      <br />
      <input type="text" id="playerName" name="playerName" autofocus required />
      <br />
      <button type="submit">Start</button>
    </form>
  </div>
</body>
</html>
