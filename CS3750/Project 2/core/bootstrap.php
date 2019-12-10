<?php
session_start();

require_once("core/Config.php");
require_once("database/DB.php");

DB::init();

function redirect($url) {
  ob_start();
  header('Location: ' . $url);
  ob_end_flush();
  die();
}
?>
