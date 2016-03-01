<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../assets/php/loader.php");

$remove = $db->prepare("DELETE FROM blobs WHERE hash=?");
$remove->execute([$_COOKIE["ditto-session"]]);
setcookie(
  "ditto-session",
  null,
  strtotime('-30 days'),
  "/",
  "zbee.me"
);
unset($_COOKIE["ditto-session"]);
ditto::redirect("/");
