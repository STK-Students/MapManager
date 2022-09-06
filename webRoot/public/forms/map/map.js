$(document).ready(function () {
    /**
     * Setups the layer table button.
     */
    let layerTableBuilder = new TableBuilder($('#layerTable'), '#layerTable');

    $('#layerCreatorButton').click(function () {
        layerTableBuilder.addNewLayer($('#layerName').val(), layerTableEditButtonAction);
    });

    addEventListener('beforeunload', (event) => {
        if (!saveData()) {
            event.preventDefault();
            return event.returnValue = "Ihre Eingaben sind nicht valide und werden daher nicht automatisch gespeichert."
        }
    })
});

const layerTableEditButtonAction = function (rowNumber) {
    if (FormSubmitter.formIsValid()) {
        const urlParams = new URLSearchParams(window.location.search)
        if (urlParams.has("serviceUUID")) {
            const result = urlParams.get("serviceUUID")
            window.location.href = "/public/forms/layer/layer.php?serviceUUID=" + result + "&rowNumber=" + rowNumber;
        }
    }
}

/**
 * Submits the form and other data to the given PHP handler.
 * @return {boolean} if the form is valid for submission
 * */
function saveData() {
    let searchParams = new URLSearchParams(window.location.search);
    let serviceUUID = searchParams.get('serviceUUID');
    const submitter = new FormSubmitter();
    return submitter.attemptSubmitFormData(serviceUUID, 'map', 'mapForm', "updateHandler.php", formSubmitterSpecialCaseMethodWrapper)
}

/**
 * Wraps all specialCaseHandlers in a function that gets called by the FormSubmitter.
 * @param geoServiceData the auto-parsed data of the geoService as a json
 * @returns {*} the data of the geoService as a json, including the data parsed by the specialCaseHandlers
 */
function formSubmitterSpecialCaseMethodWrapper(geoServiceData) {
    geoServiceData = parseStatusCheckbox(geoServiceData);
    geoServiceData = parseIncludedServices(geoServiceData)
    return parseLayers(geoServiceData);
}

function parseStatusCheckbox(geoServiceData) {
    geoServiceData['status'] = $('#status').is(':checked') ? "1" : "0";
    return geoServiceData;
}

/**
 * Parses the included services.
 * @param geoServiceData
 * @returns {*}
 */
function parseIncludedServices(geoServiceData) {
    geoServiceData.include = $("#includeCheckBoxes :checkbox:checked").map(function () {
        return $(this).val();
    }).get();
    return geoServiceData;
}

/**
 * Parses all layers from the form and returns them as a json.
 * This is a special case function from the FormSubmitter.
 * @param json the autogenerated json
 * @returns {*} the json with the layers
 */
function parseLayers(json) {
    let table = $('#layerTable td');
    let layers = {};
    let layerIndex = 0;
    for (let i = 0; i < table.length; i++) {
        if (i % 2 === 0) {
            layers[layerIndex] = {name: table[i].textContent};
            layerIndex++;
        }
    }
    json['layers'] = layers;
    return json;
}

/** Apply data from server to forms: */

/**
 * Gets called by map.php with the data of the geoService as a json.
 * @param geoServiceData the data of the geoService as a json
 */
function phpHook(geoServiceData) {
    $(document).ready(function () {
        new FormFiller().fillForms(geoServiceData, formFillerSpecialCaseMethodWrapper);
    });
}

function formFillerSpecialCaseMethodWrapper(geoServiceData) {
    setStatusCheckbox(geoServiceData);
    fillLayerTable(geoServiceData);
    setIncludedServices(geoServiceData);
}

function setStatusCheckbox(geoServiceData) {
    if (geoServiceData.status === 1) {
        $('#status').prop("checked", true);
    }
}

function setIncludedServices(geoServiceData) {
    $("#includeModal").on('shown.bs.modal', null, geoServiceData, function (geoServiceData) {
        const includedServices = geoServiceData.data.include;

        for (const index in includedServices) {
            $("#includeCheckBoxes input[value=" + includedServices[index] + "]").prop("checked", true);
        }
    });
}

/**
 * Fills the layer table with the name of the given layers.
 * @param data geoService data as a json
 */
function fillLayerTable(data) {
    const layers = data.layers;
    if (layers !== undefined) {
        let layerTableBuilder = new TableBuilder($('#layerTable'), '#layerTable');
        for (const layer of Object.values(layers)) {
            layerTableBuilder.addNewLayer(layer.name, layerTableEditButtonAction);
        }
    }
}