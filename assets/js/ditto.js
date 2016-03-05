//Ditto Library
var ditto = {
  "index": null,
  "depressed": null,
  "diagnosis": null,
  "phq9Answers": null,
  "treatment": null,
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
      if (answers.length == 11) {
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

      //Depression can be effectively ruled out if the index is below five 
      var index = ditto.index;
      if (index < 5) {
        ditto.depressed = false;
        ditto.diagnosis = "Not depressed ("
            + "PHQ-9 index too low"
          + ")";
        ditto.treatment = "None";
      }

      //Depression can also be ruled out if depressive feelings are not reported
      if (answers[1] < 2 && answers[2] < 2) {
        ditto.depressed = false;
        ditto.diagnosis = "Not depressed ("
            + "Not self-reporting depression"
          + ")";
        ditto.treatment = "None";
      }

      //Depression can also be ruled out if symptoms are not reported
      var symptoms = 0;
      symptoms += answers[1];
      symptoms += answers[2];
      symptoms += answers[3];
      symptoms += answers[4];
      symptoms += answers[5];
      symptoms += answers[6];
      symptoms += answers[7];
      symptoms += answers[8];
      symptoms += answers[9];
      if (symptoms < 5) {
        ditto.depressed = false;
        ditto.diagnosis = "Not depressed ("
            + "Not self-reporting symptoms of depression"
          + ")";
          ditto.treatment = "None";
      }

      //Depression can also be ruled out if the symptoms are not difficult
      if (answers[10] < 1) {
        ditto.depressed = false;
        ditto.diagnosis = "Not depressed ("
            + "Not self-reporting difficulty with symptoms"
          + ")";
        ditto.treatment = "None";
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
        ditto.diagnosis = "Severe major depression"
        ditto.treatment = "Antidepressants or psychotherapy";
      }

      ditto.console.log();
      return [ditto.depressed, ditto.diagnosis, ditto.treatment];
    }
  }
};
