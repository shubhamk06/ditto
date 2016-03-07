<?php
//Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Function create an HTML element of a PHQ question
 *
 * @param $number
 *
 * @return mixed|string
 */
function makePHQQ ($number) {
  //Each of the PHQ-9 questions
  $questions = [
    "Little interest or pleasure in doing things",
    "Feeling down, depressed, or hopeless",
    "Trouble falling asleep, staying asleep, or sleeping too much",
    "Feeling tired or having little energy",
    "Poor appetite or overeating",
    "Feeling bad about yourself - or that you're a failure or have let yourself"
    . " or your family down",
    "Trouble concentrating on things, such as reading or watching TV",
    "Moving or speaking so slowly that other people noticed, or the opposite - "
    . "being so fidgety or restless that you have been moving around a lot "
    . "more than usual",
    "Thoughts that you would be better off dead or of hurting yourself in some "
    . "way"
  ];
  //Set the question based on which number we're on
  $question = $questions[$number - 1];
  //Unformatted HTML question
  $formattedQuestion = '
    <div class="ribbon" id="phq%c">
      <div class="container">
        <h1 class="bigbold">Question %cn of Nine</h1>
        <br><br>
        Over the past two weeks, how often have you been bothered by ...
        <br><br>
        <b>%s</b>
        <br><br>
        <a class="button fixed phqq" href="#phq%n" data-answer="0"
          data-question="%c">
          Not at All
        </a>
        <a class="button fixed phqq" href="#phq%n" data-answer="1"
          data-question="%c">
          Several Days
        </a><br>
        <a class="button fixed phqq" href="#phq%n" data-answer="2"
          data-question="%c">
          A Week or More
        </a>
        <a class="button fixed phqq" href="#phq%n" data-answer="3"
          data-question="%c">
          Nearly Every Day
        </a>
      </div>
    </div>';
  //Format the HTML
  //Fill in the word-version number of the question
  $formatter         = new NumberFormatter("en", NumberFormatter::SPELLOUT);
  $formattedNumber   = ucfirst($formatter->format($number));
  $formattedQuestion = str_replace("%cn", $formattedNumber, $formattedQuestion);
  //Fill in the current question integer
  $formattedQuestion = str_replace("%c", $number, $formattedQuestion);
  //Fill in the current question
  $formattedQuestion = str_replace("%s", $question, $formattedQuestion);
  if ($number + 1 < 10) {
    //Fill in the next question integer
    $formattedQuestion = str_replace("%n", $number + 1, $formattedQuestion);
  } else {
    //Fill in the next question final
    $formattedQuestion = str_replace("%n", "s", $formattedQuestion);
  }

  //Return the formatted integer
  return $formattedQuestion;
}

/**
 * Function to create the PHQ-9 questionnaire in HTML
 * @return string
 */
function makePHQ () {
  $questionnaire = "";

  //For each of the nine questions
  for ($q = 1; $q <= 9; $q++) {
    //Echo the formatted HTML question
    $questionnaire .= makePHQQ($q);
  }

  return $questionnaire;
}

?>

<!--suppress HtmlUnknownAnchorTarget -->
<div class="ribbon" id="log">
  <div class="container">
    <h1 class="bigbold">Log Mood</h1>
    <br><br>
    From here you can log your mood for today, by filling out a modified
    <a href="http://www.cqaimh.org/pdf/tool_phq9.pdf" target="_blank">PHQ-9
      questionnaire</a>.
    <br><br>
    Ready to begin monitoring your mood?
    <br><br>
    <a class="button" href="#phq1">Get Started</a>
  </div>
</div>

<?= makePHQ() ?>

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
    "click", ".button", function () {
      //Do not let the browser move the user to the next question
      //e.preventDefault()

      //Animate the transition to the next question
      //$("html").animate({
      //  "scrollTop": $($(this).attr("href")).offset().top
      //}, 100, "swing");

      //If the button clicked is a PHQ answer
      if ($(this).hasClass("phqq")) {
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
          ditto.calculate.index();
          ditto.calculate.diagnosis();

          $("#tagline").text(ditto.diagnosis.split("(")[0]);
          $("#content").text(ditto.treatment);

          ditto.report(
            function (res) {
              console.info(res);
            }
          );
        }
      }
    }
  );
</script>