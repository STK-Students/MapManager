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
    await fetch('http://localhost/api.php?getGroup=' + uuid)
        .then(response => response.json())
        .then(data => {
            $("#main-title").text(data.name);
        });
}

async function setupServiceTable(uuid) {
    await fetch('http://localhost/api.php?getMaps=' + uuid)
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
                const openLink = $('<button>').text("Dienst bearbeiten").addClass('btn btn-outline-primary')
                    .on('click', () => openEditPage(data, item));
                const editLink = $("<a>").text("Beschreibung bearbeiten").addClass('btn btn-outline-primary').
                on("click", () => openDescriptionEdit(data, item));

                openTD.append(openLink);
                editTD.append(editLink);
                row.append(nameTD, descriptionTD, creationDateTD, openTD, editTD);
                table.append(row);
            }
        });
}

function openEditPage(data, item) {
    window.location.href = "/templates/forms/edit.php?uuid=" + data[item].uuid
}

function openDescriptionEdit(data, item) {
    window.location.href = "/templates/home/map/editMap.php?uuid=" + data[item].uuid
}