<?php
//Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!--suppress HtmlUnknownAnchorTarget -->
<div class="ribbon" id="log">
  <div class="container">
    <h1 class="bigbold">Log Mood</h1>
    <br><br>
    From here you can log your mood for today, by filling out a modified
    <a href="http://www.cqaimh.org/pdf/tool_phq9.pdf" target="_blank">PHQ-9
      questionnaire</a>.
    <br>
    Tracking your mood is important to find correlations between it and other
    aspects of your life.
    <br><br>
    Ready to begin monitoring your mood?
    <br><br>
    <a class="button" href="#phq1" onClick="$('html,body').animate
    ({scrollTop: $('#phq1').offset().top}, 700);return false;">Get Started</a>
  </div>
</div>

<div id="questions"></div>

<div class="ribbon" id="phqr">
  <div class="container">
    <h1 class="bigbold">Results</h1>
    <h1 id="tagline"></h1>
    <span id="content"></span>
    <br><br>
    <a class="button" href="/dash/">Retun to Dash</a>
  </div>
</div>