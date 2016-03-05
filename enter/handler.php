<?php
require("../assets/php/header.php");
$enter = $_SERVER["REQUEST_URI"];
$enter = str_replace("/enter/", "", $enter);
$enter = str_replace("/", "", $enter);
require($enter . ".php");
require("../assets/php/footer.php");
