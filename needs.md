- **Need**
  JavaScript file to create forms based off of JSON descriptions in `assets/json/forms/`.
  
  **Example**
  null
  
  **Reason**
  To more easily create additional forms
  
  **Files**
  - `assets/js/dittoForms.js`

---

- **Need**
  Forms JSONs keys for operations in logic can use the `anyAnswer(rangeBottom, rangeTop)` function keyword.
  
  **Example**
  `{"logic": ["type": "if", "statement": {"conditions": [{"key": "anyAnswer(1,9)"}]}]}`
  
  **Reason**
  PHQ-9 needs to display a concluding question if any of the symptom questions is yes.
  
  **Files**
  - `assets/json/forms/formsSchema.json`
  - `assets/js/dittoForms.js`
