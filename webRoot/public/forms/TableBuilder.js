/**
 * A class that builds a table of layers.
 */
class TableBuilder {

    /**
     * Constructs a new TableBuilder for the given table.
     * @param table the table to modify
     * @param tableID the id of the table
     * @param subPageURL the url of the subpage to redirect to when the edit button is clicked
     */
    constructor(table, tableID) {
        this.table = table;
        this.tableID = tableID;
    }

    /**
     * Adds a new layer to the table.
     * The editFunction gets the rowNumber as a parameter.
     * @param layerName the name of the layer
     * @param editFunction the function to call when the edit button is clicked
     */
    addNewLayer(layerName, editFunction) {
        let newTableLength = $(this.tableID + ' tr').length - 1;

        const tableRow = this.#createTableRow(newTableLength, layerName, editFunction);

        this.table.append(tableRow);
    }

    /**
     * Adds a table row to the layer table.
     * The editFunction gets the rowNumber as a parameter.
     * @param rowNumber the number of the row
     * @param rowName the name of the row
     * @param editFunction a function that is executed when the edit button is clicked
     * @returns {*|jQuery|HTMLElement} the table row as a jQuery object
     */
    #createTableRow(rowNumber, rowName, editFunction) {
        const uuid = self.crypto.randomUUID();
        const tr = $("<tr>").attr({id: uuid});
        const th = $("<th>").attr({scope: 'row'}).text(rowNumber + 1);
        const nameTD = $("<td>").text(rowName)

        let buttonEdit = $('<button>').addClass('btn btn-outline-primary').text('Bearbeiten').on('click', () => {
            editFunction(rowNumber);
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