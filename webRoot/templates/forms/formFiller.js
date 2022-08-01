function fillForms(map) {
    $("document").ready(function () {
        for (const [setting, value] of Object.entries(map)) {
            let nestedSetting = map[setting];
            if (typeof nestedSetting == "object") {
                if (setting == "layer") {
                    buildLayerTable(value);
                }
                for (const property in nestedSetting) {
                    $("#" + setting + "-" + property).val( nestedSetting[property]);
                }
            }
            $("#" + setting).val(value);
        }
    });
}

function buildLayerTable() {
    //TODO
}