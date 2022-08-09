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
            const urlParams = new URLSearchParams(window.location.search)
            if (urlParams.has("uuid")) {
                const result = urlParams.get("uuid")
                window.location.href = "http://localhost/templates/forms/layer/layer.php?mapUUID=" + result + "&layerUUID=" + uuid + "&rowNumber=" + rowNumber;
            }
        });
        let buttonDelete = $('<button>').addClass('btn btn-outline-danger actionButton').text('Löschen')
            .on('click', () => {
                this.#deleteTableRow(uuid);
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
        $('#layerTable tbody tr').each(function (index) {
            $(this).children('th').text(index + 1);
        });
    }
}