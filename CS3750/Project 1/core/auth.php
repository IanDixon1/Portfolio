<?php
if (!isset($_SESSION['name'])) {
  redirect("login.php");
}
?>
