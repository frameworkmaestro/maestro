<?php

use \Maestro\Services\MJSON as MJSON;

class MDataGrid extends MControl
{

    public $columns = array();

    public function __construct()
    {
        parent::__construct('mdatagrid');
    }

    public function addControl($control)
    {
        if ($control->className == 'mtoolbar') {
            $control->id = $this->id . '_toolbar';
            $this->toolbar = $control;
        } else {
            $this->columns[] = $control;
        }
    }

    public function setNavigator()
    {
        $this->navigator = new StdClass();
        $this->navigator->pageLength = $pageLength;
        $this->navigator->rowCount = $this->rowCount;
        $this->navigator->pageCount = ($this->pageLength > 0) ? (int) (($this->rowCount + $this->pageLength - 1) / $this->pageLength) : 1;
        $this->navigator->action = $action;
        $this->navigator->range = new \Maestro\Types\MRange($this->pageNumber, $this->pageLength, $this->rowCount);
        $this->navigator->idxFirst = $this->navigator->range->offset;
        $this->navigator->idxLast = $this->navigator->range->offset + $this->navigator->range->rows - 1;
        $this->navigator->gridCount = $this->navigator->range->rows;
    }

    public function generateData()
    {
        $this->data = NULL;
        if ($this->query instanceof \Maestro\Database\MQuery) {
            if ($this->query instanceof RetrieveCriteria) {
                $this->query = $this->query->asQuery();
            }
            $this->orderBy = mrequest('orderby');
            $this->rowCount = $this->query->count();
            if ($this->ordered = isset($this->orderBy)) {
                $this->query->msql->setOrderBy($this->orderBy);
                $this->state->set('orderby', $this->orderBy, $this->name);
            }
            if ($this->pageLength) {
                $this->setNavigator();
                $this->firstIndex = $this->navigator->idxFirst;
                $this->query->setRange($this->navigator->range);
                $this->data = $this->query->getResult();
            } else {
                $this->data = $this->query->getResult();
                $this->navigator = null;
            }
        } elseif (is_string($this->query)) {
            $this->url = $this->query;
        }
    }

    public function generateJsData()
    {
        $jsCode = "[";
        if ($this->query && count($this->data)) {
            $id = $this->getId();
            $id = str_replace('::', '', $id);
            $columnNames = array();
            foreach ($this->columns as $i => $column) {
                $column->index = $this->query->getColumnNumber($column->field);
                $index = $column->index ? : $i;
                $columnNames[$index] = $column->field;
            }
            //mdump($columnNames);
            //mdump($this->data);

            $firstRowAdded = false;

            foreach ($this->data as $i => $row) {

                if ($firstRowAdded)
                    $jsCode .= ",";

                $jsCode .= "{";
                $firstColumnAdded = false;
                foreach ($row as $j => $column) {
                    if (is_string($column)) {
                        $column = trim($column);
                        $chars = array("\r\n", "\n", "\r");
                        $column = str_replace($chars, " ", $column);
                        $column = str_replace('"', '\"', $column);
                    }
                    if ($columnNames[$j] != null) {
                        if ($firstColumnAdded)
                            $jsCode .= ",";

                        $jsCode .= "{$columnNames[$j]}:\"{$column}\"";
                        $firstColumnAdded = true;
                    }
                }
                $firstRowAdded = true;
                $jsCode .= "}\n";
            }
        }
        $jsCode .= "]";
        //$this->page->addJsCode($jsCode);
        $this->jsData = $jsCode;
    }

    public function generate()
    {
        if ($this->actionUpdate) {
            $this->addTool(_M("Save"), $this->actionUpdate, "icon-save");
        }
        if ($this->actionDelete) {
            $this->addTool(_M("Delete"), $this->actionDelete, "icon-remove");
        }
        if ($this->actionInsert) {
            $this->addTool(_M("Insert"), $this->actionDelete, "icon-add");
        }
        if ($this->actionSelect) {
            $this->checkbox = true;
        }

        foreach ($this->columns as $column) {
            $column->width = $column->width ? : '0';
            $column->field = strtoupper($column->field);
        }
        $this->generateData();
        $this->generateJsData();

        if ($this->checkbox) {
            $column = new MDatagridColumn();
            $column->field = $this->id . '_chk';
            $column->type = "check";
            array_unshift($this->columns, $column);
        }
        $custom = new \StdClass; // opções customizadas via xml
        if ($this->url) {
            $custom->url = $this->url;
        }
        if ($this->singleSelect) {
            $custom->singleSelect = $this->singleSelect;
        }
        if ($this->pageLength) {
            $custom->pagination = true;
            $custom->pageSize = $this->pageLength;
            $custom->pageList = array(5, 10, 15, 20, 30, 50);
        }
        if ($this->idField != '') {
            if (preg_match('/^[0-9]/', $this->idField)) {
                $control->idField = 'F' . $this->idField;
            }
            $custom->idField = $this->idField;
        }
        $toolbar = '';
        if ($this->toolbar) {
            $custom->toolbar = "#{$this->toolbar->id}";
            $toolbar = $this->painter->mdiv($this->toolbar);
        }

        //$control->attributes->custom = rawurlencode(MJSON::encode($custom));

        $fields = array();
        foreach ($this->columns as $column) {
            $field = new StdClass;
            $field->field = $column->field;
            $field->title = $column->title;
            $field->hidden = ($column->visible === false);
            $field->idGrid = $this->id;
            if ($column->action) {
                $field->action = $column->action;
            }
            if ($column->width) {
                $field->width = $column->width;
            }
            if ($column->align) {
                $field->align = $column->align;
            }
            if ($column->align) {
                $field->halign = $column->halign;
            }
            $field->type = $column->type ? : 'label';
            if ($column->options) {
                $field->options = $column->options;
            }
            if ($column->render) {
                $field->render = $column->render;
            }
            if ($column->index) {
                $field->field = 'F' . $column->index;
            }
            if ($column->type == "control") {
                $controls = $column->getControls();
                $firstControl = current($controls);
                $htmlControl = MJSON::encode($firstControl->generate());
                $field->idControl = $firstControl->id;
                $this->page->addJsCode("$('#{$this->id}').data('{$field->idControl}', {$htmlControl});\n");
            }

            $fields[] = $field;
        }

        $this->page->addJsCode("$('#{$this->id}').data('data', {$this->jsData});\n");
        $this->page->addJsCode("$('#{$this->id}').data('custom', '" . MJSON::encode($custom) . "');\n");
        $this->page->addJsCode("$('#{$this->id}').data('fields', '" . MJSON::encode($fields) . "');\n");

        $this->page->addScriptURL(Manager::getThemeURL("scripts/mdatagrid.js"));

        $this->page->onLoad("mdatagrid('{$this->id}');");
        $this->setClass('mgrid');
        $baseId = $this->id;
        $control->property->manager['baseId'] = $baseId;

        $this->idHidden = $baseId . '_data';
        $idField = $this->idField;
        if ($idField != '') {
            $this->property->manager['idField'] = $idField;
        }

        $attributes = $this->painter->getAttributes($this);
        if ($this->head != '') {
            $thead = <<< EOT
<thead>
    <tr>
        {$this->head}
    </tr>
</thead>
EOT;
        }

        $grid = <<<EOT
<input type="hidden" id="{$this->idHidden}" name="{$this->idHidden}" value=""/>
<table {$attributes}>
    {$thead}
</table>
EOT;
        if (!($this->form instanceof MForm)) { // Todos os grids precisam estar dentro de um form
            $formId = $baseId . '_form';
            $grid = <<<EOT
<form id="{$formId}" name="{$formId}" method="POST">
    {$grid}
</form>
EOT;
            $this->form->id = $formId;
        }
        $this->page->onSubmit("jQuery('#{$this->id}').mgrid('onSubmit')", $this->form->id);

        $this->result = $grid . $toolbar;
        return $this->result;
    }

}
