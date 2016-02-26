<?php

require_once(__DIR__ . "/_secret_keys.php");

try {
  $db = new PDO(
    "mysql:host=$db[host];dbname=$db[db];charset=utf8",
    $db["user"],
    $db["pass"]
  );
} catch (Exception $e) {
  die("MySQL could not connect<br>$e");
}

try {
  require_once(__DIR__ . "/ditto.php");
  $ditto = new ditto;
} catch (Exception $e) {
  die($e);
}
