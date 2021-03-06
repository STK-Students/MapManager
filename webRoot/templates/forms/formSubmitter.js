/**
 * Handles validation, parsing and submission of custom forms.
 */
class FormSubmitter {

    /**
     * Submits the data of a form to the given PHP handler using a POST HTTP request.
     * Check the docs of #autoParseForms to correctly set up your forms.
     * SpecialCase function can be passed to add content to the submission that won't be autogenerated.
     * @param formID id of the form
     * @param handlerLocation name and location of the PHP file, relative to the calling file
     * @param specialCaseFunctions a list of functions to execute
     */
    attemptSubmitFormData(formID, handlerLocation, specialCaseFunctions) {
        if (this.#formIsValid()) {
            const formInputs = $('#' + formID + ' input').toArray();
            this.#sendPOST(handlerLocation, this.#buildPayload(formInputs, specialCaseFunctions));
        }
    }

    /**
     * Checks the validity of all inputs with the 'needs-validation' class.
     * Styling will be applied based on the validity of every single input.
     * @returns {boolean} if all inputs contain valid data
     */
    #formIsValid() {
        const forms = $('.needs-validation')
        let isValid = true;
        Array.from(forms).forEach(form => {
            if (!form.checkValidity()) {
                isValid = false;
            }
            form.classList.add('was-validated')
        });
        return isValid;
    }

    #sendPOST(handlerLocation, payload) {
        fetch('http://localhost/api/formHandler/' + handlerLocation, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });
    }

    /**
     * Builds the payload based on an autogenerated json and 'specialCase' functions.
     * The 'specialCase' functions are executed with the autogenerated as their only argument.
     * They are expected to return a modified version of the json for further processing.
     * @param formInputs a list of input fields
     * @param specialCaseFunctions a list of functions to execute
     * @returns An object representing all user input
     */
    #buildPayload(formInputs, specialCaseFunctions) {
        let json = this.#autoParseForms(formInputs);

        for (const fun in specialCaseFunctions) {
            json = fun(json);
        }
        return json;
    }

    /**
     * Builds an object from all the given input fields.
     * <br>
     * If an input field's ID contains a dash (-), a nested object will be created.
     * This nested object will be named like the part before the dash, the part after
     * the dash will be the key to the value in that nested object.
     * <br>
     * This behaviour makes it easier to parse properties that belong together on the server side.
     * @param formInputs an object that contains all forms
     * @returns An object representing all input fields and their values
     */
    #autoParseForms(formInputs) {
        let payload = {};
        formInputs.forEach(input => {
            let id = input.id;
            let value = input.value;

            let parts = id.split('-')

            if (parts.length === 1) {
                payload[id] = value;
            } else {
                if (payload[parts[0]] == null) {
                    payload[parts[0]] = {};
                }
                payload[parts[0]][parts[1]] = value;
            }
        });
        return payload;
    }
}
