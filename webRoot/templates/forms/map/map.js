$(document).ready(function () {
    /**
     * Setups the layer table button.
     */
    let layerTableBuilder = new LayerTableBuilder($('#layerTable'));
    $('#layerCreatorButton').click(function () {
        layerTableBuilder.addNewLayer($('#layerName').val());
    });

    /**
     * Submits the form and other data to the given PHP handler.
     */
    $('#submitAPIButton').click(async function () {
        new FormSubmitter().attemptSubmitFormData('mapForm', "serviceHandler.php", parseLayers)
    });
});


/**
 * Gets called by map.php with the data of the geoService as a json.
 * @param geoServiceData the data of the geoService as a json
 */
function phpHook(geoServiceData) {
    $(document).ready(function () {
        new FormFiller().fillForms(geoServiceData, fillLayerTable);
    });
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