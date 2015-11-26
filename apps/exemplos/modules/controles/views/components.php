<?php

class components extends MActionPanel {

    public function __construct() {
        parent::__construct('componentsPanel','Exemplos::Controles::Components','>exemplos/main/controls');
        $this->addAction('Components XML (Grid)', 'exemplosIconForm', '>exemplos/controls/componentGrid');
        $this->addAction('Components XML (Container)', 'exemplosIconForm', '>exemplos/controls/componentContainer');
        $this->addAction('Components PHP (Container)', 'exemplosIconForm', '>exemplos/controls/componentPHP');
        $this->addLink('Components via URL', 'exemplosIconForm', '>exemplos/ajaxPessoa');
    }

}

