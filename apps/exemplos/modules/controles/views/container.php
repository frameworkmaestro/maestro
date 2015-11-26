<?php

class container extends MActionPanel {

    public function __construct() {
        parent::__construct('containerPanel','Exemplos::Controles::Containers','>exemplos/main/controls');
        $this->addAction('ContentPane', 'exemplosIconForm', '>exemplos/controls/formContentPane');
        $this->addAction('Box', 'exemplosIconForm', '>exemplos/controls/formBoxPane');
        $this->addAction('Custom Box', 'exemplosIconForm', '>exemplos/controls/formCustomBox');
        $this->addAction('Table', 'exemplosIconForm', '>exemplos/controls/formTable');
        $this->addAction('BaseGroup', 'exemplosIconForm', '>exemplos/controls/formBaseGroup');
        $this->addAction('Containers', 'exemplosIconForm', '>exemplos/controls/formContainer');
        $this->addAction('Stack Container', 'exemplosIconForm', '>exemplos/controls/formStack');
        $this->addAction('Tab Container', 'exemplosIconForm', '>exemplos/controls/formTab');
        $this->addAction('Accordion Container', 'exemplosIconForm', '>exemplos/controls/formAccordion');
    }

}

