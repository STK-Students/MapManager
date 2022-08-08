/**
 * A class that builds a table of layers.
 */
class LayerTableBuilder {

    /**
     * Constructs a new LayerTableBuilder for the given table.
     * @param table the table to modify
     */
    constructor(table) {
        this.table = table;
    }

    /**
     * Adds a new layer to the table.
     * @param layerName the name of the layer
     */
    addNewLayer(layerName) {
        let newTableLength = $('#layerTable tr').length;

        const tableRow = this.#generateTableRow(newTableLength, layerName);

        this.table.append(tableRow);
    }

    /**
     * Adds a table row to the layer table.
     * @param rowNumber the number of the row
     * @param layerName the name of the layer
     * @returns {*|jQuery|HTMLElement} the table row as a jQuery object
     */
    #generateTableRow(rowNumber, layerName) {
        const tr = $("<tr>");
        const th = $("<th>").attr({scope: 'row'}).text(rowNumber);
        const nameTD = $("<td>").text(layerName)

        let buttonEdit = $('<button>').attr({id: 'layerEdit-' + rowNumber})
            .addClass('btn btn-outline-primary').text('Bearbeiten');
        let buttonDelete = $('<button>').attr({id: 'layerDelete-' + rowNumber})
            .addClass('btn btn-outline-danger actionButton').text('Löschen');

        const actionsTD = $("<td>");
        actionsTD.append(buttonEdit, buttonDelete);

        return tr.append(th, nameTD, actionsTD);
    }
}