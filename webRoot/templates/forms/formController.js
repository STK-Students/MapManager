$('#submitAPIButton').on('click', async function () {
    if (formIsValid()) {
        submitFormData('mapForm', "mapHandler.php");
    }
});

function formIsValid() {
    const forms = document.querySelectorAll('.needs-validation')
    let isValid = true;
    Array.from(forms).forEach(form => {
        if (!form.checkValidity()) {
            isValid = false;
        }
        form.classList.add('was-validated')
    });
    return isValid;
}

/**
 * Submits the data of a form to the given PHP handler using a POST HTTP request.
 * @param formID id of the form
 * @param handlerLocation name and location of the PHP file, relative to the calling file
 */
function submitFormData(formID, handlerLocation) {
    const formInputs = $('#' + formID + ' input').toArray();
    sendPOST(handlerLocation, buildPayload(formInputs));
}

function sendPOST(handlerLocation, payload) {
    fetch('http://localhost/api/formHandler/' + handlerLocation, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });
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
function buildPayload(formInputs) {
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
