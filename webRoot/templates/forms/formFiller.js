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
            if (specialCaseFunction !== undefined) {
                specialCaseFunction(ogcServiceData);
            }

    }

    #handleNestedSetting(setting, value) {
        if (setting === "layers") {
            let layerTableBuilder = new LayerTableBuilder($('#layerTable'));
            for (const layer of Object.values(value)) {
                layerTableBuilder.addNewLayer(layer.name);
            }
        }
        for (const property in value) {
            $("#" + setting + "-" + property).val(value[property]);
        }
    }
}