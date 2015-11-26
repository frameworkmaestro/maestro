<?php

class main extends MFormAction {

    function __construct() {
        parent::__construct('Passo 1: Dados Básicos', '>examples/main/main');
    }

    public function createFields() {
        $this->setFieldsFromXML('main.xml');
    }
    
}

