<div class="ribbon">
  <div class="container">
    <h1 class="bigbold">Log Sleep</h1>
    <br><br>
    From here you can log your sleep for last night.
    <br>
    Sleep is massively important to your mental health as without it brain
    functionality is altered. Additionally, it affects physical health including
    hormonal balance.
    <br><br>
    Ready to begin monitoring your sleep?
    <br><br>
    <a class="button" href="#length">Get Started</a>
  </div>
</div>

<div class="ribbon" id="length">
  <div class="container">
    <h1 class="bigbold">Length</h1>
    <br><br>
    How many hours did you sleep last night?
    <br><br>
    <a class="button fixed" href="#quality" data-question="length"
       data-answer="0">
      Less than Four
    </a>
    <a class="button fixed" href="#quality" data-question="length"
       data-answer="1">
      Four to Six
    </a><br>
    <a class="button fixed" href="#quality" data-question="length"
       data-answer="2">
      Six to Eight
    </a>
    <a class="button fixed" href="#quality" data-question="length"
       data-answer="3">
      More than Eight
    </a>
  </div>
</div>

<div class="ribbon" id="quality">
  <div class="container">
    <h1 class="bigbold">Quality</h1>
    <br><br>
    How well did you sleep last night?
    <br><br>
    <a class="button fixed" href="#rest" data-question="quality"
       data-answer="0">
      Terribly
    </a>
    <a class="button fixed" href="#rest" data-question="quality"
       data-answer="1">
      Fairly Well
    </a><br>
    <a class="button fixed" href="#rest" data-question="quality"
       data-answer="2">
      Very Well
    </a>
    <a class="button fixed" href="#rest" data-question="quality"
       data-answer="3">
      Incredibly
    </a>
  </div>
</div>

<div class="ribbon" id="rest">
  <div class="container">
    <h1 class="bigbold">Rest</h1>
    <br><br>
    How rested do you feel this morning?
    <br><br>
    <a class="button fixed" href="#meal" data-question="rest"
       data-answer="0">
      Not Rested
    </a>
    <a class="button fixed" href="#meal" data-question="rest"
       data-answer="1">
      Tired
    </a><br>
    <a class="button fixed" href="#meal" data-question="rest"
       data-answer="2">
      Rested
    </a>
    <a class="button fixed" href="#meal" data-question="rest"
       data-answer="3">
      Well Rested
    </a>
  </div>
</div>

<div class="ribbon" id="meal">
  <div class="container">
    <h1 class="bigbold">Digestion</h1>
    <br><br>
    Did you eat shortly before bed?
    <br><br>
    <a class="button fixed" href="#aiddrug" data-question="meal"
       data-answer="0">
      No
    </a>
    <a class="button fixed" href="#aiddrug" data-question="meal"
       data-answer="1">
      Yes
    </a>
  </div>
</div>

<div class="ribbon" id="aiddrug">
  <div class="container">
    <h1 class="bigbold">Aid: Medication</h1>
    <br><br>
    Did you take a sleeping medication?
    <br><br>
    <a class="button fixed" href="#aidalcohol" data-question="aidDrug"
       data-answer="0">
      No
    </a>
    <a class="button fixed" href="#aidalcohol" data-question="aidDrug"
       data-answer="1">
      Yes
    </a>
  </div>
</div>

<div class="ribbon" id="aidalcohol">
  <div class="container">
    <h1 class="bigbold">Aid: Alcohol</h1>
    <br><br>
    Did you take alcohol as a sleep aid?
    <br><br>
    <a class="button fixed" href="#results" data-question="aidAlcohol"
       data-answer="0">
      No
    </a>
    <a class="button fixed" href="#results" data-question="aidAlcohol"
       data-answer="1">
      Yes
    </a>
  </div>
</div>

<div class="ribbon" id="results">
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
    ditto.sleepAnswers = answers;
    var score          = ditto.calculate.sleepQuality();

    $("#tagline").text("Score: " + score);
    $("#content").text(
      "You slept better than last night, your average, and 46% of adults"
    );

    ditto.report.sleep(
      function (res) {
        console.info(res);
      }
    );
  }

  $("html").on(
    "click", ".fixed", function () {
      var $this                       = $(this);
      answers[$this.data("question")] = $this.data("answer");

      if ($this.attr("href").indexOf("results") > 0) {
        result();
      }
    }
  )
</script>