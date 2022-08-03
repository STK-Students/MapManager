function fillForms(ogcServiceData) {
    $("document").ready(function () {
        for (const [setting, value] of Object.entries(ogcServiceData)) {
            let nestedSetting = ogcServiceData[setting];
            if (typeof nestedSetting == "object") {
                handleNestedSetting(setting, value, nestedSetting);
            } else {
                $("#" + setting).val(value);
            }
        }
    });
}

function handleNestedSetting(setting, value, nestedSetting) {
    if (setting === "layer") {
        buildLayerTable(value);
    }
    for (const property in nestedSetting) {
        $("#" + setting + "-" + property).val(nestedSetting[property]);
    }
}

function buildLayerTable() {
    //TODO
}