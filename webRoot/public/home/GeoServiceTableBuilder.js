$(document).ready(function () {
    //Run once at page load
    generatePageContent();

    $("#selectGroup").change(async function () {
        await generatePageContent();
    });
});

async function generatePageContent() {
    let groupUUID = $("#selectGroup").val();
    if (groupUUID.length === 0) {
        $(".sidebar").css("visibility", "hidden");
        $("#table-maps").css("visibility", "hidden");
    } else {
        $("#hiddenGroupUUID").val(groupUUID)
        $("#hiddenInputGroupUUID").val(groupUUID);

        await setupPageTitle(groupUUID);
        await setupServiceTable(groupUUID);
        await setGroupSession(groupUUID);
    }
}

async function setupPageTitle(uuid) {
    $(".sidebar").css("visibility", "visible");
    await fetch('http://localhost/api/api.php?getGroup=' + uuid)
        .then(response => response.json())
        .then(data => {
            $("#main-title").text(data.name);
        });
}

async function setupServiceTable(uuid) {
    await fetch('http://localhost/api/api.php?getMaps=' + uuid)
        .then(response => response.json())
        .then(data => {
            $('.dynamicTableElement').remove();

            const table = $("#table-maps").css('visibility', 'visible');
            for (const item in data) {
                const row = $("<tr>").addClass("dynamicTableElement");
                const nameTD = $("<td>").text(data[item].name);
                const descriptionTD = $("<td>").text(data[item].description);
                const creationDateTD = $("<td>").text(data[item].creationDate);
                const openTD = $("<td>");
                const editTD = $("<td>");
                const serviceUUID = data[item].uuid;
                const openLink = $('<button>').text("Dienst bearbeiten").addClass('btn btn-outline-primary')
                    .on('click', () => openEditPage(serviceUUID));
                const editLink = $("<a>").text("Beschreibung bearbeiten").addClass('btn btn-outline-primary').on("click", () => {
                    $("#inputEditServiceUUID").val(serviceUUID);
                    $("#inputEditServiceName").val(data[item].name);
                    $("#inputEditServiceDescription").val(data[item].description);
                    $("#editServiceModal").modal('show');
                });

                openTD.append(openLink);
                editTD.append(editLink);
                row.append(nameTD, descriptionTD, creationDateTD, openTD, editTD);
                table.append(row);
            }
        });
}

function openEditPage(serviceUUID) {
    window.location.href = "/public/forms/map/map.php?serviceUUID=" + serviceUUID;
}
