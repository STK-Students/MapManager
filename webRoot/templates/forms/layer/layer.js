$(document).ready(function () {
    /**
     * Submits the form and other data to the given PHP handler.
     */
    $('#submitAPIButton').click(async function () {
        new FormSubmitter().attemptSubmitFormData('layerForm', "serviceHandler.php", provideContext);
    });
});

function provideContext(json) {
    let searchParams = new URLSearchParams(window.location.search);
    let layerID = searchParams.get('rowNumber');
    let outher = {}
    outher.layers = layerID;
    outher.layers[layerID] = json;
    console.log(outher);
}

/**
 * Gets called by map.php with the data of the ogc service as a json.
 * @param ogcServiceData the data of the ogc service as a json
 */
function phpHook(ogcServiceData) {
    $(document).ready(function () {
        console.log(ogcServiceData["layer"]);
        new FormFiller().fillForms(ogcServiceData);
    });
}

