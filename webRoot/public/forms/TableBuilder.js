/**
 * A class that builds a table of layers.
 */
class TableBuilder {

    /**
     * Constructs a new TableBuilder for the given table.
     * @param table the table to modify
     * @param tableID the id of the table
     */
    constructor(table, tableID) {
        this.table = table;
        this.tableID = tableID;
    }

    /**
     * Adds a new layer to the table.
     * @param layerName the name of the layer
     */
    addNewLayer(layerName) {
        let newTableLength = $(this.tableID + ' tr').length;

        const tableRow = this.#createTableRow(newTableLength, layerName);

        this.table.append(tableRow);
    }

    /**
     * Adds a table row to the layer table.
     * @param rowNumber the number of the row
     * @param layerName the name of the layer
     * @returns {*|jQuery|HTMLElement} the table row as a jQuery object
     */
    #createTableRow(rowNumber, layerName) {
        const uuid = self.crypto.randomUUID();
        const tr = $("<tr>").attr({id: uuid});
        const th = $("<th>").attr({scope: 'row'}).text(rowNumber);
        const nameTD = $("<td>").text(layerName)

        let buttonEdit = $('<button>').addClass('btn btn-outline-primary').text('Bearbeiten').on('click', () => {
            if (saveData()) {
                const urlParams = new URLSearchParams(window.location.search)
                if (urlParams.has("serviceUUID")) {
                    const result = urlParams.get("serviceUUID")
                    window.location.href = "http://localhost/public/forms/layer/layer.php?mapUUID=" + result + "&rowNumber=" + rowNumber;
                }
            }
        });
        let buttonDelete = $('<button>').addClass('btn btn-outline-danger actionButton').text('Löschen')
            .on('click', () => {
                this.#deleteTableRow(uuid);
                saveData();
            });

        const actionsTD = $("<td>");
        actionsTD.append(buttonEdit, buttonDelete);

        return tr.append(th, nameTD, actionsTD);
    }

    #deleteTableRow(uuid) {
        $(`#${uuid}`).closest('tr').remove();
        this.#refreshTableRowNumbers();
    }

    #refreshTableRowNumbers() {
        $(this.tableID + ' tbody tr').each(function (index) {
            $(this).children('th').text(index + 1);
        });
    }
}