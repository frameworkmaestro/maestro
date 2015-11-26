<?php

class output extends MActionPanel {

    public function __construct() {
        parent::__construct('outputPanel','Exemplos::Controles::Output','>exemplos/main/controls');
        $this->addAction('Imagens', 'exemplosIconForm', '>exemplos/controls/formImagens');        
        $this->addAction('CSS', 'exemplosIconForm', '>exemplos/controls/formCSS');
        $this->addAction('TextTable', 'exemplosIconForm', '>exemplos/controls/formTextTable');
        $this->addAction('Controller - RenderPartial', 'exemplosIconForm', '>exemplos/controls/renderPartialView');
    }

}

