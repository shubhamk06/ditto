<?php
//Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../assets/php/loader.php");

$user = ditto::requireLogin();
$user = ditto::getUser($user);

ditto::enterData(0, $user["id"], [$_POST["index"], $_POST["phq9Answers"]]);
