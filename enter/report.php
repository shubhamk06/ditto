<?php
//Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Include autoloader
require_once(__DIR__ . "/../assets/php/loader.php");

//Establish user
$user = ditto::requireLogin();
$user = ditto::getUser($user);

//Enter Mood
if (array_key_exists("phq9Answers", $_POST)) {
  ditto::enterData(
    0,
    $user["id"],
    [$_POST["depressionIndex"], $_POST["phq9Answers"]]
  );
//Enter Sleep
} elseif (array_key_exists("sleepQuality", $_POST)) {
  ditto::enterData(
    1,
    $user["id"],
    [$_POST["sleepQuality"], $_POST["sleepAnswers"]]
  );
} elseif (array_key_exists("hei", $_POST)) {
  ditto::enterData(
    2,
    $user["id"],
    [$_POST["hei"], $_POST["foodAnswers"]]
  );
}
