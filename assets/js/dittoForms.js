"use strict";

//ditto forms library
//variable/function name       | return  | comment
//formID                       | string  | type of form being created
//section                      | string  | form section as it is being generated
//answers                      | array<s>| array of default question answers
//questions                    | array<s>| array of questions
//questionCount                | integer | count of actual questions
//responses                    | array<i>| array of responses provided
//setup                        | object  | functions for form setup/construction
//setup.fillIn                 | void    | filling in dittoForms variables
//setup.verify                 | void    | verify form is valid
//function                     | object  | functions for form functionality
//function.management          | false   | managing form responses
//function.call                | void    | calling form's ditto functions
//function.callback            | void    | program-provided callback for  form
//create                       | object  | functions for generating form
//create.questionTitle         | string  | numbers to word function
//create.question              | void    | forming a question
//create.answer                | string  | forming an answer
//create.form                  | boolean | forming the form, constructor

var dittoForms;
dittoForms = {
  "formID"       : null,
  "meta"         : null,
  "section"      : null,
  "answers"      : null,
  "questions"    : null,
  "questionCount": null,
  "responses"    : null,

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

      if (!Array.isArray(json.questions)) {
        return false;
      }

      dittoForms.meta      = json.form;
      dittoForms.questions = json.questions;
      dittoForms.answers   = json.answers;

      dittoForms.questions.forEach(
        function (question) {
          if (question.order != "f") {
            if (dittoForms.questionCount == null) {
              dittoForms.questionCount = 1;
            } else {
              dittoForms.questionCount += 1;
            }
          }
        }
      );

      dittoForms.console.log();
    }, //Function to verify that the form provided was correct
    "verify": function (callback) {
      console.log("dittoForms.setup.verify()");

      $.getJSON(
        "https://ditto.zbee.me/assets/json/forms/" + dittoForms.formID + ".json"
      ).then(
        function (json) {
          dittoForms.setup.fillIn(json);
          dittoForms.create.formActual(callback);
          dittoForms.console.log();
        }
      );
    }
  },

  "function": { //Methods for providing functionality to the form
    //For getting responses
    "management": function (question, answer, answerElement) {
      console.log("dittoForms.function.management()");

      if (dittoForms.responses == null) {
        dittoForms.responses = {};
      }
      dittoForms.responses[question] = answer;

      //Final question
      if (Object.keys(dittoForms.responses).length
          == dittoForms.questionCount) {
        console.log("last question answered");

        //If there's a concluding question
        if (dittoForms.questions.length > dittoForms.questionCount) {
          var callback = function () {
            $("html, body").animate(
              {
                scrollTop: $(
                  "#" + dittoForms.questions[dittoForms.questionCount].id
                ).offset().top
              }, 700
            );
          };

          dittoForms.create.question(
            dittoForms.questions[dittoForms.questionCount], callback
          );
        } else { //Compute Results

        }
      } else { //More questions - continue to next
        $("html, body").animate(
          {
            scrollTop: $(answerElement.attr("href")).offset().top
          }, 700
        );
      }

      return false;
    }, "call"   : {}, "callback": {}
  },

  "create": {
    //For creating question titles; numbers to words
    "questionTitle": function (number) {
      //From http://stackoverflow.com/a/1026087/1843510
      String.prototype.ucFirst = function () {
        return this.charAt(0).toUpperCase() + this.slice(1);
      }

      //number to string, pluginized from
      //http://stackoverflow.com/questions/5529934/javascript-numbers-to-words

      window.num2str = function (num) {
        return window.num2str.convert(num);
      };

      window.num2str.ones  = [
        '',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'seven',
        'eight',
        'nine'
      ];
      window.num2str.tens  = [
        '',
        '',
        'twenty',
        'thirty',
        'forty',
        'fifty',
        'sixty',
        'seventy',
        'eighty',
        'ninety'
      ];
      window.num2str.teens = [
        'ten',
        'eleven',
        'twelve',
        'thirteen',
        'fourteen',
        'fifteen',
        'sixteen',
        'seventeen',
        'eighteen',
        'nineteen'
      ];

      window.num2str.convert_millions = function (num) {
        if (num >= 1000000) {
          return this.convert_millions(Math.floor(num / 1000000))
                 + " million "
                 + this.convert_thousands(num % 1000000);
        } else {
          return this.convert_thousands(num);
        }
      };

      window.num2str.convert_thousands = function (num) {
        if (num >= 1000) {
          return this.convert_hundreds(Math.floor(num / 1000))
                 + " thousand "
                 + this.convert_hundreds(num % 1000);
        } else {
          return this.convert_hundreds(num);
        }
      };

      window.num2str.convert_hundreds = function (num) {
        if (num > 99) {
          return this.ones[Math.floor(num / 100)]
                 + " hundred "
                 + this.convert_tens(num % 100);
        } else {
          return this.convert_tens(num);
        }
      };

      window.num2str.convert_tens = function (num) {
        if (num < 10) {
          return this.ones[num];
        } else if (num >= 10 && num < 20) {
          return this.teens[num - 10];
        } else {
          return this.tens[Math.floor(num / 10)] + " " + this.ones[num % 10];
        }
      };

      window.num2str.convert = function (num) {
        if (num == 0) {
          return "zero";
        } else {
          return this.convert_millions(num);
        }
      };

      return window.num2str(number).ucFirst();
    }, //Form question HTML
    "question"     : function (question, callback) {
      console.log("dittoForms.create.question()");

      $("#questions").append(
        "<div class='ribbon' id='"
        + question.id
        + "'>"
        + "<div class='container'></div>"
        + "</div>"
      );
      var questionDiv = $("#questions").find(
        "#" + question.id + " .container"
      );
      if (question.order !== "f") {
        var questionTitle = (
          "Question " + dittoForms.create.questionTitle(
            question.order
          ) + " of " + dittoForms.create.questionTitle(dittoForms.questionCount)
        );
      } else {
        var questionTitle = "Additional Concluding Question";
      }
      questionDiv.append(
        "<h1 class='bigbold'>"
        + questionTitle
        + "</h1>"
        + "<br><br>"
        + dittoForms.meta.questionPreface
        + "<br><br>"
        + "<b>"
        + question.question
        + "</b>"
        + "<br><br>"
        + dittoForms.create.answers(question)
      );

      if (callback !== null && typeof callback == "function") {
        callback();
      }
    }, //Form answer button(s) HTML
    "answers"      : function (question) {
      console.log("dittoForms.create.answers()");

      var answers = "";

      if (question.answers == null) {
        dittoForms.answers.forEach(
          function (e) {
            answers +=
              "<a class='button fixed answer' href='#"
              + question.qualifier.substring(0, question.qualifier.length - 1)
              + (
              question.order + 1
              )
              + "' data-question='"
              + question.id
              + "' data-answer='"
              + e.value
              + "' onClick='return dittoForms.function.management(\""
              + question.order
              + "\", \""
              + e.value
              + "\", $(this))'>"
              + e.label
              + "</a> ";
          }
        );
      } else {
        question.answers.forEach(
          function (e) {
            answers +=
              "<a class='button fixed answer' href='#"
              + question.qualifier.substring(0, question.qualifier.length - 1)
              + (
              question.order + 1
              )
              + "' data-question='"
              + question.id
              + "' data-answer='"
              + e.value
              + "'>"
              + e.label
              + "</a> ";
          }
        );
      }

      return answers;
    }, //Constructor
    "form"         : function (formID, callback) {
      console.log("dittoForms.create.form()");

      dittoForms.formID = formID;
      dittoForms.setup.verify(callback);

      dittoForms.console.log();
    }, //Create form once questions are loaded
    "formActual"   : function (callback) {
      console.log("dittoForms.create.formActual()");

      dittoForms.questions.forEach(
        function (question) {
          if (question.order != "f") {
            dittoForms.create.question(question);
          }
        }
      );

      dittoForms.console.log();
      callback();
      return true;
    }
  }
}