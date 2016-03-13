"use strict";

//ditto library
//name                         | return  | comment
//depressionIndex              | integer | total of PHQ-9 questions
//sleepQuality                 | integer | quality of sleep
//healthyEatingIndex           | integer | total of HEI-2010 questions
//phq9Answers                  | array   | the answers self-reported by human
//hei2010Answers               | array   | the answers self-reported by human
//sleepAnswers                 | array   | the answers self-reported by human
//depressed                    | boolean | if the human is depressed
//diagnosis                    | string  | the complete diagnosis of human
//treatment                    | string  | Pfizer's suggest treatment
//environment                  | object  | information about environment
//calculate                    | void    | object to hold methods
//calculate.depressionIndex    | integer | method to calculate PHQ-9
//calculate.diagnosis          | array   | method to calculate diagnosis
//calculate.sleepQuality       | integer | method to calculate sleep
//calculate.healthyEatingIndex | integer | method to calculate HEI-2010
//analyze                      | void    | object to hold methods
//analyze.battery              | void    | method to run all analyses
//analyze.environment          | object  | method to analyze the environment
//report                       | void    | object to hold methods report.mood
//report.mood                  | void    | method to report mood to server
//report.sleep                 | void    | method to report sleep to server

var ditto;
ditto = {
  "depressionIndex"   : null,
  "sleepQuality"      : null,
  "healthyEatingIndex": null,
  "phq9Answers"       : null,
  "sleepAnswers"      : null,
  "hei2010Answers"    : null,
  "depressed"         : null,
  "diagnosis"         : null,
  "treatment"         : null,
  "environment"       : null,

  "console"  : {
    //Method for logging data to the console
    "log": function () {
      console.log("ditto.console.log()");
      console.info(ditto);
    }
  }, //Methods for calculating results
  "calculate": {
    //Function to calculate the PHQ-9 Index
    "depressionIndex"      : function () {
      console.log("ditto.calculate.depressionIndex()");
      var answers = ditto.phq9Answers;

      //Remove summative question
      if (answers.length === 11) {
        delete answers[10];
      }

      var index = 0;

      //Add answers together
      answers.forEach(
        function (answer) {
          index += answer;
        }
      );

      //Return depressionIndex
      ditto.depressionIndex = index;
      ditto.console.log();
      return index;
    }, //Function to diagnose based off of Pfizer's PHQ-9 stable resource
       // toolkit
    "diagnosis"            : function () {
      console.log("ditto.calculate.diagnosis()");
      var answers = ditto.phq9Answers;

      var exit = function () {
        ditto.console.log();
        return [ditto.depressed, ditto.diagnosis, ditto.treatment];
      };

      //Depression can be effectively ruled out if the depressionIndex is below
      // five
      var index = ditto.depressionIndex;

      if (index < 5) {
        ditto.depressed = false;
        ditto.diagnosis = "Not depressed (" + "PHQ-9 index too low" + ")";
        ditto.treatment = "None";
        return exit();
      }

      //Depression can also be ruled out if depressive feelings are not reported
      if (answers[1] < 2 && answers[2] < 2 && index < 5) {
        ditto.depressed = false;
        ditto.diagnosis =
          "Not depressed (" + "Not self-reporting depression" + ")";
        ditto.treatment = "None";
        return exit();
      }

      //Depression can also be ruled out if symptoms are not reported
      var symptoms = 0;
      symptoms += answers[1] >= 2 ? 1 : 0;
      symptoms += answers[2] >= 2 ? 1 : 0;
      symptoms += answers[3] >= 2 ? 1 : 0;
      symptoms += answers[4] >= 2 ? 1 : 0;
      symptoms += answers[5] >= 2 ? 1 : 0;
      symptoms += answers[6] >= 2 ? 1 : 0;
      symptoms += answers[7] >= 2 ? 1 : 0;
      symptoms += answers[8] >= 2 ? 1 : 0;
      if (symptoms < 5 || answers[9] < 1) {
        ditto.depressed = false;
        ditto.diagnosis =
          "Not depressed (" + "Not self-reporting symptoms of depression" + ")";
        ditto.treatment = "None";
        return exit();
      }

      //Depression can also be ruled out if the symptoms are not difficult
      if (answers[10] < 1) {
        ditto.depressed = false;
        ditto.diagnosis =
          "Not depressed ("
          + "Not self-reporting difficulty with symptoms"
          + ")";
        ditto.treatment = "None";
        return exit();
      }

      //If depression has not been ruled out, patient is depressed
      //Determine severity of depression
      if (index < 10) {
        ditto.depressed = true;
        ditto.diagnosis = "Minimal symptoms";
        ditto.treatment = "Support, call if worse";
      } else if (index < 15) {
        ditto.depressed = true;
        ditto.diagnosis = "Minor depression, Dysthymia, Mild major depression";
        ditto.treatment =
          "Support, watchful waiting, antidepressants or " + "psychotherapy";
      } else if (index < 20) {
        ditto.depressed = true;
        ditto.diagnosis = "Moderate major depression";
        ditto.treatment = "Antidepressants or psychotherapy";
      } else {
        ditto.depressed = true;
        ditto.diagnosis = "Severe major depression";
        ditto.treatment = "Antidepressants or psychotherapy";
      }

      //Return diagnosis
      return exit();
    }, "sleepQuality"      : function () {
      console.log("ditto.calculate.sleepQuality()");
      var quality = 0;

      //Determine quality based off of answers
      quality += ditto.sleepAnswers["length"];
      quality += ditto.sleepAnswers["quality"];
      quality += ditto.sleepAnswers["rest"];
      quality -= ditto.sleepAnswers["meal"];
      quality -= ditto.sleepAnswers["aidAlcohol"];

      //Return the sleepQuality
      ditto.sleepQuality = quality;
      ditto.console.log();
      return quality;
    }, "healthyEatingIndex": function () {
      console.log("ditto.calculate.healthyEatingIndex()");
      var components = ditto.hei2010Answers, hei = 0, extras = {}, highProteins = false;

      //Function for deducing score for component
      function quarterToScore(answer, score) {
        var oScore = score;
        score      = score / 4;
        score      = [
          1 / 8, 3 / 8, 5 / 8, 7 / 8
        ];
        return score[answer] * oScore;
      }

      //Component scores - totals 100
      var componentScores = {
        "totalFruit"   : 5,  //0
        "wholeFruit"   : 5,  //0
        "vegetables"   : 5,  //0    | vegetables + (1 IF proteins == 4)
        "darkGreens"   : 5,  //0    | darkGreens + beansPeas
        "wholeGrains"  : 10, //0
        "dairy"        : 10, //0
        "protein"      : 5,  //0    | vegetables + (beansPeas IF proteins < 4)
        "seafoodPlants": 5,  //0    | vegetables + (beansPeas IF proteins < 4)
        "acids"        : 10, //<1.2 | (poly + mono) / sat
        "refinedGrains": 10, //>2
        "sodium"       : 10, //>2
        "emptyCalories": 20  //>2
      };

      //Component scores - totals 100
      var componentFinals = {
        "totalFruit"   : 0,
        "wholeFruit"   : 0,
        "vegetables"   : 0,
        "darkGreens"   : 0,
        "wholeGrains"  : 0,
        "dairy"        : 0,
        "protein"      : 0,
        "seafoodPlants": 0,
        "acids"        : 0,
        "refinedGrains": 0,
        "sodium"       : 0,
        "emptyCalories": 0
      };

      //Determine base indexes
      Object.keys(components).forEach(
        function (key) {
          var score       = componentScores[key], //Fetch the score for this component
              value       = components[key], //Fetch the provided value
              actualValue = value; //Set up for possible modification later
          if (key in componentFinals) {
            //Save if proteins are high
            if (key === "protein" && value === 3) {
              highProteins = true;
            }
            //Flip values if they're being aimed lower
            if (key
                === "refinedGrains"
                || key
                   === "sodium"
                || key
                   === "emptyCalories") {
              actualValue = value == 0 ? 3 : 0;
              actualValue = value == 1 ? 2 : 0;
              actualValue = value > 2 ? 0 : actualValue;
            }
            //Save tentative score for this component based off of quarters
            componentFinals[key] = quarterToScore(actualValue, score);
          } else {
            //If the key isn't one of the 12 components, use it for calculation
            extras[key] = value;
          }
        }
      );

      //Determine advanced indexes
      //Add beans and peas to appropriate locations as needed
      if (highProteins) {
        componentFinals["vegetables"] += 1;
      } else {
        componentFinals["protein"] += extras["beansPeas"];
        componentFinals["seafoodPlants"] += extras["beansPeas"];
      }
      delete extras["beansPeas"];
      //Determine fatty acids value
      var acids = 0, acidsValue = 0;
      acids     = (
        extras["poly"] + extras["mono"]
      );
      acids /= extras["sat"];
      if (acids >= 2.5) {
        acidsValue = 3;
      } else if (acids <= 1.2) {
        acidsValue = 0;
      } else if (acids >= 1.85) {
        acidsValue = 2;
      } else {
        acidsValue = 1;
      }
      componentFinals["acids"] = quarterToScore(acidsValue, 10);

      ditto.hei2010Answers = Object.keys(componentFinals)
        .map(
          function (key) {
            return componentFinals[key]
          }
        );

      //To get the index in reduced, averaged format
      function sum(obj) {
        var sum = 0;
        for (var el in obj) {
          if (obj.hasOwnProperty(el)) {
            sum += parseFloat(obj[el]);
          }
        }
        return sum;
      }

      hei = sum(componentFinals) //Add all components
      hei = hei > 20 ? hei - 20 : 0; //Set the lower bound
      hei = hei / 36 * 100 | 0; //Set the upper bound and convert to 1-100

      //Return the healthyEatingIndex
      ditto.healthyEatingIndex = hei;
      ditto.console.log();
      return hei;
    }
  }, //Methods for providing additional data on a user
  "analyze"  : {
    "battery"       : function () {
      console.log("ditto.analyze.battery()");

      ditto.analyze.environment();
    }, "environment": function () {
      console.log("ditto.analyze.environment()");

      var environment = {
        "time": null, "day": null, "timezone": null
      };

      //Local time of day
      var pad   = function (num, size) {
        var s = num + "";
        while (s.length < size) {
          s = "0" + s;
        }
        return s;
      };
      var h     = pad(Date.getHours(), 2);
      var m     = pad(Date.getMinutes(), 2);
      data.time = parseInt(h + "" + m);

      //Local day of week
      data.day = (
        new Date
      ).getDay();

      //Local timezone
      var timezone  = new Date();
      data.timezone = timezone.getTimezoneOffset() / 60;

      ditto.console.log();
    }
  }, //Method for reporting ditto results
  "report"   : {
    "ajax"    : function (callback, data) {
      console.log("ditto.report.ajax()");
      $.ajax(
        {
          "url"   : "https://ditto.zbee.me/enter/report.php",
          "method": "POST",
          "data"  : data
        }
      ).done(
        function (res) {
          callback(res);
        }
      );
      ditto.console.log();
    }, "mood" : function (callback) {
      console.log("ditto.report.mood()");
      ditto.report.ajax(
        callback, {
          "depressionIndex": ditto.depressionIndex,
          "depressed"      : ditto.depressed,
          "diagnosis"      : ditto.diagnosis,
          "phq9Answers"    : ditto.phq9Answers,
          "treatment"      : ditto.treatment
        }
      );
      ditto.console.log();
    }, "sleep": function (callback) {
      console.log("ditto.report.sleep()");
      ditto.report.ajax(
        callback, {
          "sleepQuality": ditto.sleepQuality, "sleepAnswers": ditto.sleepAnswers
        }
      );
      ditto.console.log();
    }, "food" : function (callback) {
      console.log("ditto.report.food()");
      ditto.report.ajax(
        callback, {
          "hei": ditto.healthyEatingIndex, "foodAnswers": ditto.hei2010Answers
        }
      );
      ditto.console.log();
    }
  }
};
