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
    "verify": function () {
      console.log("dittoForms.setup.verify()");

      $.getJSON(
        "https://ditto.zbee.me/assets/json/forms/" + dittoForms.formID + ".json"
      ).then(
        function (json) {
          dittoForms.setup.fillIn(json);
          dittoForms.create.formActual();
          dittoForms.console.log();
        }
      );
    }
  },

  "function": { //Methods for providing functionality to the form
    //For getting responses
    "management" : function (question, answer, answerElement) {
      console.log("dittoForms.function.management()");

      if (dittoForms.responses == null) {
        dittoForms.responses = {};
      }
      dittoForms.responses[question] = answer;

      //Final question
      if (Object.keys(dittoForms.responses).length
          >= dittoForms.questionCount) {
        //If there's a concluding question
        if (dittoForms.questions.length > dittoForms.questionCount && $(
            "#questions .ribbon"
          ).length == dittoForms.questionCount) {
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
          dittoForms.function.call();
        }
      } else { //More questions - continue to next
        $("html, body").animate(
          {
            scrollTop: $(answerElement.attr("href")).offset().top
          }, 700
        );
      }

      return false;
    }, "call"    : function () {
      ditto[dittoForms.formID + "Answers"] = $.map(
        dittoForms.responses, function (value) {
          return [parseInt(value)];
        }
      );

      //Modified from http://stackoverflow.com/a/4351575/1843510
      function executeFunctionByName(functionName, context) {
        var namespaces = functionName.split(".");
        var func       = namespaces.pop();
        namespaces.shift();
        for (var i = 0; i < namespaces.length; i++) {
          context = context[namespaces[i]];
        }
        return context[func].apply(context);
      }

      executeFunctionByName(dittoForms.meta.callback, ditto);

      ditto.calculate.diagnosis();

      var resultsDiv = $(".ribbon[id$='r']");
      resultsDiv.find("#tagline").text(ditto.diagnosis.split("(")[0]);
      resultsDiv.find("#content").text(ditto.treatment);

      $("html, body").animate(
        {
          scrollTop: resultsDiv.offset().top
        }, 700
      );

      ditto.report.mood(
        function (res) {
          console.info(res);
        }
      );
    }, "callback": {}
  },

  "create": {
    //For creating question titles; numbers to words
    "questionTitle": function (number) {
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
    "answers"   : function (question) {
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
            var nextQuestion = question.order + 1;
            if (question.order == "f") {
              nextQuestion = "r";
            }
            answers +=
              "<a class='button fixed answer' href='#"
              + question.qualifier.substring(0, question.qualifier.length - 1)
              + nextQuestion
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
      }

      return answers;
    }, //Constructor
    "form"      : function (formID) {
      console.log("dittoForms.create.form()");

      dittoForms.formID = formID;
      dittoForms.setup.verify();

      dittoForms.console.log();
    }, //Create form once questions are loaded
    "formActual": function () {
      console.log("dittoForms.create.formActual()");

      //From http://stackoverflow.com/a/1026087/1843510
      String.prototype.ucFirst = function () {
        return this.charAt(0).toUpperCase() + this.slice(1);
      }

      //Create form info header
      $("script[data-tag='dF']").after(
        "<div class='ribbon' id='log'><div" + " class='container'></div></div>"
      );
      $("#log .container").append(
        "<h1 class='bigbold'>Log "
        + dittoForms.meta.for.ucFirst()
        + "</h1><br><br>"
      );
      $("#log .container").append(dittoForms.meta.about);
      var firstQuestion = dittoForms.questions[0].id;
      $("#log .container").append(
        "<br><br>"
        + "<a class=\"button\""
        + " onClick=\"$('html,body').animate"
        + "({scrollTop: $('#"
        + firstQuestion
        + "').offset().top}, 700);return"
        + " false;\">Get Started</a>"
      );

      //Allow questions to be added
      $("#log").after("<div id='questions'></div>");

      //Add results container
      $("#questions").after(
        "<div class='ribbon' id='"
        + dittoForms.formID
        + "r'><div"
        + " class='container'></div></div>"
      );
      $("#" + dittoForms.formID + "r .container").append(
        "<h1 class='bigbold'>Results</h1>"
      );
      $("#" + dittoForms.formID + "r .container").append(
        "<h1 id='tagline'></h1>"
        + "<span id='content'></span>"
        + "<br><br>"
        + "<a class='button' href='/dash/'>Return to Dash</a>"
      );

      dittoForms.questions.forEach(
        function (question) {
          if (question.order != "f") {
            dittoForms.create.question(question);
          }
        }
      );

      dittoForms.console.log();

      return true;
    }
  }
}