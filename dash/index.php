<?php
require("../assets/php/header.php");

$user = ditto::requireLogin();
$user = ditto::getUser($user);

$dataPoints = ditto::getDataPoints($user["id"], null, time() - 3600 * 12);

$moodPoints  = [];
$sleepPoints = [];
$foodPoints  = [];

if (is_array($dataPoints)) {
  foreach ($dataPoints as $dataPoint) {
    if ($dataPoint["type"] == 0) {
      array_push($moodPoints, $dataPoint);
    } elseif ($dataPoint["type"] == 1) {
      array_push($sleepPoints, $dataPoint);
    } elseif ($dataPoint["type"] == 2) {
      array_push($foodPoints, $dataPoint);
    }
  }
}

$dataPointsPerm = [
  "mood"  => $moodPoints,
  "sleep" => $sleepPoints,
  "food"  => $foodPoints
];
?>

  <div class="ribbon" id="welcome">
    <div class="container">
      <h1 class="bigbold">Welcome</h1>
      <h1>To Your Dashboard</h1>
      From here you can view correlations and log mood and the parts of life
      that play into it.
      <br><br>
      <?php if (count($moodPoints) == 0): ?>
        <a class="button" href="/enter/mood/">Log Mood</a>
      <?php else: ?>
        <?php if (count($sleepPoints) == 0): ?>
              <a class="button" href="/enter/csdc/">Log Sleep</a>
        <?php endif; ?>
        <?php if (count($foodPoints) == 0): ?>
          <a class="button" href="/enter/food/">Log Food</a>
        <?php endif; ?>
      <?php endif; ?>
      <?php if ((count($moodPoints) + count($sleepPoints) + count($foodPoints))
                == 3
      ): ?>
        Great job on remembering to log everything today!
        <br>
        Come back tomorrow, and do it again to identify correlations.
      <?php endif; ?>
    </div>
  </div>

<?php
require("../assets/php/footer.php");
?>