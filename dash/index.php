<?php
require("../assets/php/header.php");

$user = ditto::requireLogin();
$user = ditto::getUser($user);
?>

<div class="ribbon">
  <div class="container">
    <h1>Hello There!</h1>
  </div>
</div>

<?php
require("../assets/php/footer.php");
?>