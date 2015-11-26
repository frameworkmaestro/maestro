<?php

class dialogos extends MActionPanel {

    public function __construct() {
        parent::__construct('dialogosPanel', 'Exemplos::Controles::Dialogos', '>exemplos/main/controls');
        $this->addAction('Window', 'exemplosIconForm', '>exemplos/controls/formWindowBase');
        $this->addAction('Prompt', 'exemplosIconForm', '>exemplos/controls/formPrompt');
    }

}

