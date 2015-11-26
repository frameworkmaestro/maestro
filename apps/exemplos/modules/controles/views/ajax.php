<?php

class ajax extends MActionPanel {

    public function __construct() {
        parent::__construct('ajaxPanel','Exemplos::Controles::Ajax','>exemplos/main/controls');
        $this->addAction('Ajax via XML', 'exemplosIconForm', '>exemplos/ajax/formAjaxXML');
        $this->addAction('Ajax via PHP', 'exemplosIconForm', '>exemplos/ajax/formAjaxPHP');
        $this->addAction('Ajax Grid', 'exemplosIconForm', '>exemplos/ajax/formGridAjax');
        $this->addAction('Ajax Controls', 'exemplosIconForm', '>exemplos/ajax/formAjaxControls');
        $this->addAction('Ajax Loading', 'exemplosIconForm', '>exemplos/ajax/formAjaxLoading');
    }

}

