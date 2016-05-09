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
      From here you can monitor your progress and log mood, sleep, and food.
      <br><br>
      <?php if (count($moodPoints) == 0): ?>
        <a class="button" href="/enter/mood/">Log Mood</a>
      <?php endif; ?>
      <?php if (count($sleepPoints) == 0): ?>
        <a class="button" href="/enter/sleep/">Log Sleep</a>
      <?php endif; ?>
      <?php if (count($foodPoints) == 0): ?>
        <a class="button" href="/enter/food/">Log Food</a>
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

  <script>
    var chartOptions = {
      backgroundColor: "#00b8f1",
      chart          : {title: '', subtitle: ''},
      hAxis          : {
        title         : 'Time',
        minorGridlines: {color: "white"},
        titleTextStyle: {color: "white"},
        textStyle     : {color: "white"},
        baselineColor : "white"
      },
      vAxis          : {
        title         : 'Index',
        titleTextStyle: {color: "white"},
        textStyle     : {color: "white"},
        baselineColor : "white"
      },
      colors         : ['white', 'white', 'white'],
      pointSize      : 5,
      pointShape     : "circle",
      legend         : {textStyle: {color: "white"}},
      height         : 500,
      curveType      : 'function'
    };
  </script>

<?php
require("../assets/php/footer.php");
?>