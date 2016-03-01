<?php
require("../assets/php/header.php");

$user = ditto::requireLogin();
$user = ditto::getUser($user);
?>

<div class="ribbon" id="welcome">
  <div class="container">
    <h1 class="bigbold">Welcome</h1>
    <h1>To Your Dashboard</h1>
    From here you can monitor your progress and log mood, sleep, and food.
    <br><br>
    <a class="button" href="/enter/mood/">Log Mood</a>
    <a class="button" href="/enter/sleep/">Log Sleep</a>
    <a class="button" href="/enter/food/">Log Food</a>
  </div>
</div>
  </div>
</div>

<?php
require("../assets/php/footer.php");
?>