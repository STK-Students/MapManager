function fillForms(map) {
    $("document").ready(function () {
        for (const [setting, value] of Object.entries(map)) {
            let nestedSetting = map[setting];
            console.log(typeof nestedSetting);
            console.log(typeof nestedSetting == "object");
            if (typeof nestedSetting == "object") {
                if (setting == "layer") {
                    buildLayerTable(value);
                }
                for (const property in nestedSetting) {
                    console.log("Setting " + property + " of " + setting + " to " + nestedSetting[property]);
                    $("#" + setting + "-" + property).val(nestedSetting[property]);
                }
            } else {

                $("#" + setting).val(value);
                console.log("Setting " + setting + " to " + value);
            }
        }
    });
}


function buildLayerTable(layerData) {

}