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

<script>
  "use strict";

  //Variable holding all of a user's answers
  var answers = [];

  //When a button is clicked
  $("html").on(
    "click", ".fixed", function () {
      //Do not let the browser move the user to the next question
      //e.preventDefault()

      //Animate the transition to the next question
      //$("html").animate({
      //  "scrollTop": $($(this).attr("href")).offset().top
      //}, 100, "swing");

      console.log("PHQ question answered");

      //Determine which question this is
      var question = parseInt($(this).data("question"));

      //Store answer
      answers[question] = parseInt($(this).data("answer"));

      //When all answers have been finished
      if (answers.length >= 10) {
        var anyChecked = false;

        //Determine if any questions are checked
        answers.forEach(
          function (answer) {
            if (answer > 0) {
              anyChecked = true;
            }
          }
        );

        //Add one more question if any others have been checked
        if (anyChecked == true && answers.length != 11) {
          console.log("Trigger sumative");
          $("#phq9").after(
            '<div class="ribbon" id="phqs">'
            + '<div class="container">'
            + '<h1 class="bigbold">'
            + 'Summative Question'
            + '</h1>'
            + '<br><br>'
            + 'You have checked off at least one problem.'
            + '<br><br>'
            + '<b>'
            + 'How difficult have those problems made it for you to do '
            + 'your work, take care of things at home, or get along with '
            + 'other people?'
            + '</b>'
            + '<br><br>'
            + '<a class="button fixed phqq" href="#phqr" data-answer="0" '
            + 'data-question="10">'
            + 'Not Difficult'
            + '</a> '
            + '<a class="button fixed phqq" href="#phqr" data-answer="1" '
            + 'data-question="10">'
            + 'Somewhat Difficult'
            + '</a>'
            + '<br>'
            + '<a class="button fixed phqq" href="#phqr" data-answer="2" '
            + 'data-question="10">'
            + 'Very Difficult'
            + '</a> '
            + '<a class="button fixed phqq" href="#phqr" data-answer="3" '
            + 'data-question="10">'
            + 'Extremely Difficult'
            + '</a>'
            + '</div>'
            + '</div>'
          );
          return true;
        } else {
          $("#phqr").remove();
          $("#phq9").after(
            '<div class="ribbon" id="phqs">'
            + '<div class="container">'
            + '<h1 class="bigbold">'
            + 'Results'
            + '</h1>'
            + '<h1 id="tagline"></h1>'
            + '<span id="content"></span>'
            + '<br><br>'
            + '<a class="button phqq" href="/dash/">'
            + 'Return to Dash'
            + '</a> '
            + '</div>'
            + '</div>'
          );
        }

        //Finale
        console.info(answers);
        console.log("Running ditto javascript");
        ditto.phq9Answers = answers;
        ditto.calculate.depressionIndex();
        ditto.calculate.diagnosis();

        $("#tagline").text(ditto.diagnosis.split("(")[0]);
        $("#content").text(ditto.treatment);

        ditto.report.mood(
          function (res) {
            console.info(res);
          }
        );
      }
    }
  );
</script>