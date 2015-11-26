<?php

class formGridXMLCode extends MControl {
    /**
     * Executed before line renderization.
     * Method to be executed before each line renderization.
     * @param $currentRow - line index
     * @param $rowData - array containing the row data - initial index = 0 - references the row query
     * @param $actions - array containing the action's data
     * @param $columns - array containing the column's data - index is the fieldName
     * @param $query - the query parameter of the grid
     * @param $grid - the grid object
     */
    public function rowMethod($currentRow, $rowData, $actions, $columns, $query, $grid) {
        $columns['nome']->control[$currentRow]->setHTMLTitle("HTML title incluido via rowmethod");
        if ($rowData[3] < 0){
            $columns['nome']->control[$currentRow]->addStyle('color','red');
        }
    }

}

?>
