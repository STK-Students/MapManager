$(document).ready(function () {
    let styleTableBuilder = new TableBuilder($('#styleTable'), '#styleTable');
    $('#styleCreatorButton').click(function () {
        styleTableBuilder.addNewLayer($('#styleName').val(), styleTableEditButtonAction);
    });
    let labelTableBuilder = new TableBuilder($('#labelTable'), '#labelTable');
    $('#labelCreatorButton').click(function () {
        labelTableBuilder.addNewLayer($('#labelName').val(), labelTableEditButtonAction);
    });
});

const styleTableEditButtonAction = function (rowNumber) {
    if (FormSubmitter.formIsValid()) {
        const urlParams = new URLSearchParams(window.location.search)
        if (urlParams.has("serviceUUID")) {
            const serviceUUID = urlParams.get("serviceUUID")
            const layerIndex = urlParams.get("layerIndex")
            const layerClassIndex = urlParams.get("layerClassIndex")
            window.location.href = "/public/forms/style/style.php?serviceUUID=" + serviceUUID +
                "&layerIndex=" + layerIndex + "&layerClassIndex=" + layerClassIndex + "&styleIndex=" + rowNumber;
        }
    }
}
const labelTableEditButtonAction = function (rowNumber) {
    if (FormSubmitter.formIsValid()) {
        const urlParams = new URLSearchParams(window.location.search)
        if (urlParams.has("serviceUUID")) {
            const serviceUUID = urlParams.get("serviceUUID")
            const layerIndex = urlParams.get("rowNumber")
            const layerClassIndex = urlParams.get("layerClassIndex")
            window.location.href = "/public/forms/label/label.php?serviceUUID=" + serviceUUID +
                "&layerIndex=" + layerIndex + "&layerClassIndex=" + layerClassIndex + "&labelIndex=" + rowNumber;
        }
    }
}

addEventListener('beforeunload', (event) => {
    if (!saveData()) {
        event.preventDefault();
        return event.returnValue = "Ihre Eingaben sind nicht valide und werden daher nicht automatisch gespeichert."
    }
});


/**
 * Attempts to save the form data.
 * @returns {boolean} if the form data is in a valid state
 */
function saveData() {
    let searchParams = new URLSearchParams(window.location.search);
    let serviceUUID = searchParams.get('serviceUUID');
    let submitter = new FormSubmitter();
    return submitter.attemptSubmitFormData(serviceUUID, 'layerClass', 'mapForm', "updateHandler.php", formSubmitterWrapper);
}

function formSubmitterWrapper(json) {
    json = provideContext(json);
    return parseStyle(json);
}

function provideContext(json) {
    let searchParams = new URLSearchParams(window.location.search);
    let layerIndex = searchParams.get('layerIndex');
    let classIndex = searchParams.get('layerClassIndex');
    let outher = {}
    outher.layerIndex = layerIndex;
    outher.layerClassIndex = classIndex;
    return {...outher, ...json};
}

/**
 * Parses all style classes from the form and returns them as a json.
 * This is a special case function from the FormSubmitter.
 * @param json the autogenerated json
 * @returns {*} the json with the layers
 */
function parseStyle(json) {
    let table = $('#classTable td');
    let layers = {};
    let layerIndex = 0;
    for (let i = 0; i < table.length; i++) {
        if (i % 2 === 0) {
            layers[layerIndex] = {name: table[i].textContent};
            layerIndex++;
        }
    }
    json['styleClasses'] = layers;
    return json;
}

/**
 * Gets called by map.php with the data of the ogc service as a json.
 * @param ogcServiceData the data of the ogc service as a json
 */
function phpHook(ogcServiceData) {
    $(document).ready(function () {
        new FormFiller().fillForms(ogcServiceData);
    });
}
