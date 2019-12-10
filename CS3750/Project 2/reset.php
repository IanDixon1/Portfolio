<?php
// reset the session
require_once("core/bootstrap.php");
session_destroy();
?>

<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8" />
  <title>Egyptian Rat Screw</title>
</head>

<body>
  Session destroyed.
  <form action="index.php" method="get">
    <button type="submit" autofocus>go back home</button>
  </form>
</body>
</html>
