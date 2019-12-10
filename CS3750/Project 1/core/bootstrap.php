<?php
session_start();

require("config.php");

// set up the database connection
$db = new mysqli($config["dbHost"], $config["dbUser"], $config["dbPassword"], $config["dbSchema"]);

if ($db->connect_error) {
  die("Database connection failed!<br />" . $db->connect_error);
}

function redirect($url) {
  ob_start();
  header('Location: ' . $url);
  ob_end_flush();
  die();
}
?>
