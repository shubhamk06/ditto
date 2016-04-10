"use strict";

//ditto forms library
//variable/function name       | return  | comment
//formID                       | string  | type of form being created
//form                         | string  | form as it is being generated
//answers                      | array<s>| array of default question answers
//questions                    | array<s>| array of questions
//responses                    | array<i>| array of responses provided
//function                     | object  | functions for form functionality
//function.management          | void    | managing form responses
//function.call                | void    | calling form's ditto functions
//function.callback            | void    | program-provided callback for  form
//create                       | object  | functions for generating form
//create.question              | void    | forming a question
//create.answer                | void    | forming an answer
//create.form                  | void    | forming the form, constructor

var dittoForms;
dittoForms = {
  "formID"   : null,
  "form"     : null,
  "answers"  : null,
  "questions": null,
  "responses": null,

  "function": {
    "management": {}, "call": {}, "callback": {}
  },

  "create": {
    "question": {}, "answer": {}, "form": function (formID, callback) {

    }
  }
}