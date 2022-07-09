$('#submitAPIButton').click(async function () {
    new FormSubmitter().attemptSubmitFormData('mapForm', "serviceHandler.php");
});

$('#layerCreatorButton').click(function () {
    let layerName = $('#layerName').val();
    let newTableLength = $('#layerTable tr').length;

    let table = $('#layerTable');

    const tr = $("<tr>");
    const th = $("<th>").attr({scope: 'row'}).text(newTableLength);
    const nameTD = $("<td>").text(layerName)

    let buttonEdit = $('<button>').attr({id: 'layerEdit-' + newTableLength})
        .addClass('btn btn-outline-primary').text('Bearbeiten');
    let buttonDelete = $('<button>').attr({id: 'layerDelete-' + newTableLength})
        .addClass('btn btn-outline-danger actionButton').text('Löschen');

    const actionsTD = $("<td>");
    actionsTD.append(buttonEdit, buttonDelete);

    tr.append(th, nameTD, actionsTD);

    table.append(tr);
});
