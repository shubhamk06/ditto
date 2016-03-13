"use strict";

//ditto library
//name                      | return  | comment
//depressionIndex           | integer | total of PHQ-9 questions
//depressed                 | boolean | if the human is depressed
//diagnosis                 | string  | the complete diagnosis of human
//phq9Answers               | array   | the answers self-reported by human
//sleepAnswers              | array   | the answers self-reported by human
//sleepQuality              | integer | sleepQuality of sleep
//treatment                 | string  | Pfizer's suggest treatment
//environment               | object  | information about environment
//calculate                 | void    | object to hold methods
//calculate.depressionIndex | integer | method to calculate depressionIndex
//calculate.diagnosis       | array   | method to calculate diagnosis
//calculate.sleepQuality    | array   | method to calculate sleep
//sleepQuality analyze      | void    | object to hold methods
//analyze.battery           | void    | method to run all analyses
//analyze.environment       | object  | method to analyze the environment
//report                    | void    | object to hold methods report.mood
//report.mood               | void    | method to report mood to server
//report.sleep              | void    | method to report sleep to server

var ditto;
ditto = {
  "depressionIndex": null,
  "depressed"      : null,
  "diagnosis"      : null,
  "phq9Answers"    : null,
  "sleepAnswers"   : null,
  "sleepQuality"   : null,
  "treatment"      : null,
  "environment"    : null,
  "console"        : {
    //Method for logging data to the console
    "log": function () {
      console.log("ditto.console.log()");
      console.info(ditto);
    }
  }, //Methods for calculating results
  "calculate"      : {
    //Function to calculate the PHQ-9 Index
    "depressionIndex": function () {
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
    "diagnosis"      : function () {
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
    }, "sleepQuality": function () {
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
    }
  }, //Methods for providing additional data on a user
  "analyze"        : {
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
  "report"         : {
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
    }
  }
};
