function fillForms(map) {
    $("document").ready(function () {
        console.log(typeof map)
        for (const [setting, value] of Object.entries(map)) {
            let nestedSetting = map[setting];
            console.log(typeof nestedSetting)
            if (typeof nestedSetting == "object") {
                for (const property in nestedSetting) {
                    console.log("Setting #" + setting + "-" + property + " to " + nestedSetting[property]);
                    $("#" + setting + "-" + property).val( nestedSetting[property]);
                }
            }
            console.log("Setting #" + setting + " to " + value);
            $("#" + setting).val(value);
        }
    });
}
