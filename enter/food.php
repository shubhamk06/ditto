<?php
//Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Function create an HTML element of a HEI question
 *
 * @param $number
 *
 * @return mixed|string
 */
function makeHEIQC ($number) {
  //Each of the HEI-2010 components
  $components = [
    [
      "totalFruit", //Component key
      "Total Fruit (including fruit juice) [0.8 cups recommended]" //Component
    ],
    [
      "wholeFruit",
      "Whole Fruits (not fruit juice) [0.4 cups recommended]"
    ],
    [
      "beansPeas",
      "Beans and Peas [1 ounce recommended]"
    ],
    [
      "vegetables",
      "Total Vegetables [1.1 cups recommended]"
      //+ beans and peas IF total proteins are high
    ],
    [
      "darkGreens",
      "Dark-Greens [0.2 cups recommended]" //+ beans and peas
    ],
    [
      "wholeGrains",
      "Whole Grains [1.5 ounces recommended]"
    ],
    [
      "dairy",
      "Dairy (including all milk products [yogurt, cheese, fortified soy "
      . "beverages, etc.]) [1.3 cups recommended]"
    ],
    [
      "protein",
      "Total Proteins [2.5 ounces recommended]"
      //+ beans and peas IF total proteins are low
    ],
    [
      "seafoodPlants",
      "Seafood and Plant Proteins [0.8 ounces recommended]"
      //+ beans and peas IF total proteins are low
    ],
    [
      "poly",
      "(Poly) Fatty Acids (solid oils - soybean, corn, sunflower oils, fish)"
    ],
    [
      "mono",
      "(Mono) Fatty Acids (liquid oils - olive, canola, safflower, sesame "
      . "oils)"
    ],
    [
      "sat",
      "Saturated Fatty Acids (animal foods - beef, lamb, whole milk, cheese,
      butter, etc.)"
    ],
    [
      "refinedGrains",
      "Refined Grains [1.8 ounces or less recommended]"
    ],
    [
      "sodium",
      "Sodium [1.1 grams or less recommended]"
    ],
    [
      "emptyCalories",
      "Empty Calories (from solid fats, alcohol, and added sugar) [20% of all "
      . "calories or less recommended]"
    ]
  ];
  //Set the component based on which number we're on
  $component = $components[$number - 1];
  //Unformatted HTML question
  $formattedComponent = '
    <div class="ribbon" id="hei%ci">
      <div class="container">
        <h1 class="bigbold">Question %cn of Fifteen</h1>
        <br><br>
        How much of this item have you had today?
        <br><br>
        <b>%s</b>
        <br><br>
        <a class="button fixed heic" href="#hei%n" data-answer="0"
          data-component="%c">
          None
        </a>
        <a class="button fixed heic" href="#hei%n" data-answer="1"
          data-component="%c">
          A bit
        </a><br>
        <a class="button fixed heic" href="#hei%n" data-answer="2"
          data-component="%c">
          A fair amount
        </a>
        <a class="button fixed heic" href="#hei%n" data-answer="3"
          data-component="%c" title="100% or more of recommended value">
          A lot, or more
        </a>
      </div>
    </div>';
  //Format the HTML
  //Fill in the word-version number of the component number
  $formatter          = new NumberFormatter("en", NumberFormatter::SPELLOUT);
  $formattedNumber    = ucfirst($formatter->format($number));
  $formattedComponent =
    str_replace("%cn", $formattedNumber, $formattedComponent);
  //Fill in the current question integer
  $formattedComponent = str_replace("%ci", $number, $formattedComponent);
  //Fill in the current question key
  $formattedComponent = str_replace("%c", $component[0], $formattedComponent);
  //Fill in the current question
  $formattedComponent = str_replace("%s", $component[1], $formattedComponent);
  if ($number + 1 < 16) {
    //Fill in the next question integer
    $formattedComponent = str_replace("%n", $number + 1, $formattedComponent);
  } else {
    //Fill in the next question final
    $formattedComponent = str_replace("%n", "f", $formattedComponent);
  }

  //Return the formatted integer
  return $formattedComponent;
}

/**
 * Function to create the HEI-2010 questionnaire in HTML
 * @return string
 */
function makeHEIQ () {
  $questionnaire = "";

  //For each of the twelve components
  for ($q = 1; $q <= 15; $q++) {
    //Echo the formatted HTML component
    $questionnaire .= makeHEIQC($q);
  }

  return $questionnaire;
}

?>

<div class="ribbon">
  <div class="container">
    <h1 class="bigbold">Log Food</h1>
    <br><br>
    From here you can log what you've eaten, by filling out a modified
    <a href="http://epi.grants.cancer.gov/hei/developing.html">Healthy
      Eating Index - 2010</a>.
    <br>
    Food is important to your mental health as it affects such things as your
    blood sugar and results of it tend to a have a big role in how we see
    ourselves.
    <br><br>
    Ready to begin monitoring your eating?
    <br><br>
    <a class="button" href="#hei1">Get Started</a>
  </div>
</div>

<?= makeHEIQ() ?>

<div class="ribbon" id="heif">
  <div class="container">
    <h1 class="bigbold">Results</h1>
    <h1 id="tagline"></h1>
    <span id="content"></span>
    <br><br>
    <a class="button" href="/dash/">Retun to Dash</a>
  </div>
</div>

<script>
  var answers = {};

  function result() {
    console.log("Answers] " + JSON.stringify(answers, null, 2));
    console.log("Running ditto javascript");
    ditto.hei2010Answers = answers;
    var index            = ditto.calculate.healthyEatingIndex();

    $("#tagline").text("Healthy Eating Index: " + index);
    $("#content").text(
      "You slept better than last night, your average, and 46% of adults"
    );

    ditto.report.food(
      function (res) {
        console.info(res);
      }
    );
  }

  $("html").on(
    "click", ".fixed", function () {
      var $this                        = $(this);
      answers[$this.data("component")] = $this.data("answer");

      if ($this.attr("href").indexOf("heif") > 0) {
        result();
      }
    }
  )
</script>