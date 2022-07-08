$(document).ready(function () {
    $("#selectGroup").change(async function () {
        let groupUUID = $("#selectGroup").val();
        if (groupUUID.length === 0) {
            $(".sidebar").css("visibility", "hidden");
            $("#table-maps").css("visibility", "hidden");
        } else {
            $("#hiddenGroupUUID").val(groupUUID)
            console.log("Setting to "+ groupUUID);
            $("#hiddenInputGroupUUID").val(groupUUID);

            await setupPageTitle(groupUUID);
            await setupServiceTable(groupUUID);
            await setGroupSession(groupUUID);
        }
    });
});

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

            const table = document.getElementById("table-maps");
            table.style.visibility = "visible";
            for (const item in data) {
                const row = document.createElement("tr");
                row.classList.add("dynamicTableElement")

                const nameTD = document.createElement("td");
                const descriptionTD = document.createElement("td");
                const creationDateTD = document.createElement("td");
                const openTD = document.createElement("td");
                const editTD = document.createElement("td");
                const openLink = document.createElement("a");
                openLink.href = "/templates/forms/edit.php?uuid=" + data[item].uuid;
                const editLink = document.createElement("a");
                editLink.href = "/templates/home/map/editMap.php?uuid=" + data[item].uuid;

                nameTD.innerText = data[item].name;
                descriptionTD.innerText = data[item].description;
                creationDateTD.innerText = data[item].creationDate;
                openLink.innerText = "Dienst bearbeiten";
                editLink.innerText = "Beschreibung bearbeiten";
                openTD.appendChild(openLink);

                editTD.appendChild(editLink);
                row.append(nameTD, descriptionTD, creationDateTD, openTD, editTD);
                table.appendChild(row);
            }
        });
}

async function setGroupSession(uuid){
    await fetch('http://localhost/api.php?setGroupSession=' + uuid)
        .then(response => response.json())
        .then(data => {
            console.log("Session created.");
        });
}
