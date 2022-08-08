/**
 * Fills a form with data from a JSON object.
 */
class FormFiller {

    /**
     * Fills the forms on an edit page.
     * @param ogcServiceData the data of the ogc service
     * @param specialCaseFunction a function to execute
     */
    fillForms(ogcServiceData, specialCaseFunction) {

            for (const [setting, value] of Object.entries(ogcServiceData)) {
                if (typeof value == "object") {
                    this.#handleNestedSetting(setting, value);
                } else {
                    $("#" + setting).val(value);
                }
            }
            specialCaseFunction(ogcServiceData);

    }

    #handleNestedSetting(setting, value) {
        if (setting === "layer") {
            let layerTableBuilder = new LayerTableBuilder($('#layerTable'));
            for (const layer in value) {
                layerTableBuilder.addNewLayer(layer);
            }
        }
        for (const property in value) {
            $("#" + setting + "-" + property).val(value[property]);
        }
    }
}