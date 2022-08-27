/**
 * Fills a form with data from a JSON object.
 */
class FormFiller {

    /**
     * Fills the forms on an edit page.
     * @param geoServiceData the data of the geoService
     * @param specialCaseFunction optional function to execute
     */
    fillForms(geoServiceData, specialCaseFunction) {
            for (const [setting, value] of Object.entries(geoServiceData)) {
                if (typeof value == "object") {
                    this.#handleNestedSetting(setting, value);
                } else {
                    $("#" + setting).val(value);
                }
            }
            if (specialCaseFunction !== undefined) {
                specialCaseFunction(geoServiceData);
            }
    }

    #handleNestedSetting(setting, value) {
        for (const property in value) {
            $("#" + setting + "-" + property).val(value[property]);
        }
    }
}