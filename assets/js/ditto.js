"use strict";

//ditto library
//var/func | name                | return  | comment
//var      | index               | integer | total of PHQ-9 questions
//var      | depressed           | boolean | if the human is depressed
//var      | diagnosis           | string  | the complete diagonis of human
//var      | phq9Answers         | array   | the answers self-reported by human
//var      | treatment           | string  | Pfizer's suggest treatment
//var      | environment         | object  | information about environment
//var      | calculate           | void    | object to hold methods
//function | calculate.index     | integer | method to calculate index
//function | calculate.diagnosis | array   | method to calculate diagnosis
//var      | analyze             | void    | object to hold methods
//function | analyze.battery     | void    | method to run all analyses
//function | analyze.environment | object  | method to analze the environment
//function | report              | void    | method to report findings to server

//ditto library
var ditto = {
  "index"      : null,
  "depressed"  : null,
  "diagnosis"  : null,
  "phq9Answers": null,
  "treatment"  : null,
  "environment": null,
  //Methods for outputting data to the console
  "console": {
    //Method for logging data to the console
    "log": function () {
      console.log("ditto.console.log()");
      console.info(ditto);
    }
  },
  //Methods for calculating results
  "calculate": {
    //Function to calculate the PHQ-9 Index
    "index": function () {
      console.log("ditto.calculate.index()");
      var answers = ditto.phq9Answers;

      //Remove summative question
      if (answers.length === 11) {
        delete answers[10];
      }

      var index = 0;

      //Add answers together
      answers.forEach(function(answer) {
        index += answer;
      });

      //Return index
      ditto.index = index;
      ditto.console.log();
      return index;
    },
    //Function to diagnose based off of Pfizer's PHQ-9 stable resource toolkit
    "diagnosis": function () {
      console.log("ditto.calculate.diagnosis()");
      var answers = ditto.phq9Answers;

      var exit = function () {
        ditto.console.log();
        return [ditto.depressed, ditto.diagnosis, ditto.treatment];
      };

      //Depression can be effectively ruled out if the index is below five 
      var index = ditto.index;

      if (index < 5) {
        ditto.depressed = false;
        ditto.diagnosis = "Not depressed ("
            + "PHQ-9 index too low"
          + ")";
        ditto.treatment = "None";
        return exit();
      }

      //Depression can also be ruled out if depressive feelings are not reported
      if (answers[1] < 2 && answers[2] < 2 && index < 5) {
        ditto.depressed = false;
        ditto.diagnosis = "Not depressed ("
            + "Not self-reporting depression"
          + ")";
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
        ditto.diagnosis = "Not depressed ("
            + "Not self-reporting symptoms of depression"
          + ")";
        ditto.treatment = "None";
        return exit();
      }

      //Depression can also be ruled out if the symptoms are not difficult
      if (answers[10] < 1) {
        ditto.depressed = false;
        ditto.diagnosis = "Not depressed ("
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
        ditto.treatment = "Support, watchful waiting, antidepressants or "
          + "psychotherapy";
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
    }
  },
  //Methods for providing additional data on a user
  "analyze": {
    "battery": function () {
      console.log("ditto.analyze.battery()");

      ditto.analyze.currentEnvironment();
    },
    "environment": function () {
      console.log("ditto.analyze.environment()");

      var environment = {
        "time"    : null,
        "day"     : null,
        "timezone": null
      };

      //Local time of day
      var pad = function(num, size) {
        var s = num + "";
        while (s.length < size) s = "0" + s;
        return s;
      }
      var h = pad((new Date).getHours(), 2);
      var m = pad((new Date).getMinutes(), 2);
      data.time = parseInt(h + "" + m);

      //Local day of week
      data.day = (new Date).getDay();

      //Local timezone
      var timezone = new Date();
      data.timezone = timezone.getTimezoneOffset() / 60;

      ditto.console.log();
    }
  },
  //Method for reporting ditto results
  "report": function (callback) {
    console.log("ditto.report()");
    $.ajax({
      "url": "https://ditto.zbee.me/enter/report.php",
      "method": "POST",
      "data": {
        "index": ditto.index,
        "depressed": ditto.depressed,
        "diagnosis": ditto.diagnosis,
        "phq9Answers": ditto.phq9Answers,
        "treatment": ditto.treatment
      }
    }).done(function(res) {
      callback(res);
    });
    ditto.console.log();
  }
};
