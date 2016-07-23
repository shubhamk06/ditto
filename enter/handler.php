<?php
require("../assets/php/header.php");
$enter      = $_SERVER["REQUEST_URI"];
$enter      = str_replace("/enter/", "", $enter);
$enter      = str_replace("/", "", $enter);
$enterFound = false;

$forms = scandir("../assets/json/forms/");
foreach ($forms as $form) {
  if ($form == "." || $form == "..") {
    continue;
  }
  $formName = explode(".", $form)[0];
  $form     = "../assets/json/forms/" . $form;
  if ($fh = $form = fopen($form, "r")) {
    $data = fread($form, 2048);
    if (strpos($data, "\"for\": \"") == false) {
      continue;
    }

    $dataEnter = explode("\"for\": \"", $data)[1];
    $dataEnter = explode("\"", $dataEnter)[0];

    if ($dataEnter == $enter) {
      echo "<script data-tag='dF'>dittoForms.create.form('$formName');
      </script>";
      $enterFound = true;
    }

    fclose($fh);
  }
}

if (!$enterFound) {
  require("../assets/php/404.php");
}

require("../assets/php/footer.php");
