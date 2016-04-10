"use strict";

//ditto forms library
//variable/function name       | return  | comment
//formID                       | string  | type of form being created
//section                      | string  | form section as it is being generated
//answers                      | array<s>| array of default question answers
//questions                    | array<s>| array of questions
//responses                    | array<i>| array of responses provided
//setup                        | object  | functions for form setup/construction
//setup.fillIn                 | void    | filling in dittoForms variables
//setup.verify                 | boolean | verify form is valid
//function                     | object  | functions for form functionality
//function.management          | void    | managing form responses
//function.call                | void    | calling form's ditto functions
//function.callback            | void    | program-provided callback for  form
//create                       | object  | functions for generating form
//create.question              | void    | forming a question
//create.answer                | void    | forming an answer
//create.form                  | boolean | forming the form, constructor

var dittoForms;
dittoForms = {
  "formID"   : null,
  "meta"     : null,
  "section"  : null,
  "answers"  : null,
  "questions": null,
  "responses": null,

  "console": {
    //Method for logging data to the console
    "log": function () {
      console.log("dittoForms.console.log()");
      console.info(dittoForms);
    }
  },

  "setup": {
    //Method for filling in form attributes
    "fillIn": function (json) {
      console.log("dittoForms.setup.fillIn()");

      dittoForms.meta      = json.form;
      dittoForms.questions = json.questions;
      dittoForms.answers   = json.answers;

      dittoForms.console.log();
    }, //Function to verify that the form provided was correct
    "verify": function () {
      console.log("dittoForms.setup.verify()");

      var toReturn = false;

      $.getJSON(
        "https://ditto.zbee.me/assets/json/forms/"
        + dittoForms.formID
        + ".json", function (json) {
          dittoForms.setup.fillIn(json);
          toReturn = true;
        }
      ).fail(
        function () {
          toReturn = false;
        }
      );

      dittoForms.console.log();
      return toReturn;
    }
  },

  "function": { //Methods for providing functionality to the form
    "management": {}, "call": {}, "callback": {}
  },

  "create": {
    "question": {}, "answer": {}, "form": function (formID, callback) {
      console.log("dittoForms.create.form()");

      dittoForms.formID = formID;
      var verified      = dittoForms.setup.verify();
      if (!verified) {
        return false;
      }

      dittoForms.console.log();
      return true;
    }
  }
}